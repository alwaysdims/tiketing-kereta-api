<?php

namespace App\Http\Controllers\Penumpang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalKereta;
use App\Models\Pemesanan;
use App\Models\PemesananDetail;
use App\Models\Gerbong;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Penumpang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Str;

class PesanTiketController extends Controller
{
    public function index()
    {
        return view('pesantiket');
    }

    public function search(Request $request)
    {
        $request->validate([
            'origin' => 'nullable|string',
            'destination' => 'nullable|string',
            'passengers' => 'required|integer|min:1|max:100',
            'date' => 'required|date|after_or_equal:' . now()->format('Y-m-d'),
        ]);

        $origin = $request->input('origin');
        $destination = $request->input('destination');
        $passengers = $request->input('passengers');
        $date = $request->input('date');

        $query = JadwalKereta::query()->with(['stasiunAsal', 'stasiunTujuan', 'kereta']);

        if ($origin) {
            $query->whereHas('stasiunAsal', function ($q) use ($origin) {
                $q->where('nama_stasiun', 'LIKE', "%{$origin}%");
            });
        }

        if ($destination) {
            $query->whereHas('stasiunTujuan', function ($q) use ($destination) {
                $q->where('nama_stasiun', 'LIKE', "%{$destination}%");
            });
        }

        $query->whereDate('jam_keberangkatan', $date);
        $jadwals = $query->orderByRaw('jam_keberangkatan >= NOW() DESC, jam_keberangkatan ASC')->get();

        return view('pesantiket', compact('jadwals', 'passengers', 'origin', 'destination', 'date'));
    }

    public function store(Request $request)
    {
        Log::info('=== STORE METHOD START ===', $request->all());

        if (!Auth::check() || Auth::user()->role !== 'penumpang') {
            Log::warning('Auth failed');
            return redirect()->route('pesantiket.index')->with('error', 'Anda harus login sebagai penumpang untuk memesan tiket.');
        }

        // Ambil penumpang dari user
        $penumpang = Auth::user()->penumpang;
        if (!$penumpang) {
            Log::error('No penumpang record for user: ' . Auth::id());
            return redirect()->back()->with('error', 'Anda belum memiliki profile penumpang. Silakan lengkapi data penumpang terlebih dahulu.');
        }
        $id_penumpang = $penumpang->id;  // Ini yang benar: penumpang.id (bukan users.id)
        Log::info('Penumpang loaded', ['id' => $id_penumpang, 'user_id' => Auth::id()]);

        $request->validate([
            'id_jadwal' => 'required|exists:jadwal_kereta,id',
            'passengers' => 'required|integer|min:1|max:100',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        Log::info('Validation passed');

        $jadwal = JadwalKereta::with(['kereta'])->findOrFail($request->id_jadwal);
        $passengers = $request->passengers;
        $total_bayar = $jadwal->harga * $passengers;
        Log::info('Jadwal loaded', ['id' => $jadwal->id, 'kereta_id' => $jadwal->kereta->id ?? 'NULL']);

        // Check available seats (sama seperti sebelumnya)
        $gerbongs = Gerbong::whereHas('kereta', function ($q) use ($jadwal) {
            $q->where('id', $jadwal->kereta->id);
        })->get();
        Log::info('Gerbongs found', ['count' => $gerbongs->count()]);

        $total_available_seats = 0;
        foreach ($gerbongs as $gerbong) {
            $booked_seats = PemesananDetail::where('id_gerbong', $gerbong->id)
                ->whereHas('pemesanan', function ($q) use ($jadwal) {
                    $q->where('id_jadwal', $jadwal->id);
                })->count();
            $gerbong->available_seats = $gerbong->jumlah_kursi - $booked_seats;
            $total_available_seats += $gerbong->available_seats;
            Log::info('Gerbong seats', ['id' => $gerbong->id, 'available' => $gerbong->available_seats]);
        }

        if ($total_available_seats < $passengers) {
            Log::warning('Seats insufficient');
            return redirect()->route('pesantiket.index')->with('error', 'Maaf, kursi yang tersedia tidak cukup untuk jumlah penumpang.');
        }

        DB::beginTransaction();
        try {
            Log::info('Transaction started');

            // Upload file
            $bukti_pembayaran = $request->file('bukti_pembayaran');
            $fileName = time() . '_' . $bukti_pembayaran->getClientOriginalName();
            $path = $bukti_pembayaran->storeAs('bukti_pembayaran', $fileName, 'public');
            Log::info('File uploaded', ['path' => $path]);

            // Create Pemesanan - GUNAKAN $id_penumpang (penumpang.id)
            Log::info('Attempting to create Pemesanan', [
                'id_penumpang' => $id_penumpang,  // Ini kunci: penumpang.id
                'id_jadwal' => $jadwal->id,
                'total_bayar' => $total_bayar,
            ]);
            $pemesanan = Pemesanan::create([
                'id_penumpang' => $id_penumpang,  // Fixed: Gunakan penumpang.id
                'id_jadwal' => $jadwal->id,
                'tanggal_pesan' => now(),
                'total_bayar' => $total_bayar,
                'jumlah_penumpang' => $passengers,
                'status_bayar' => 'sudah bayar',
                'bukti_pembayaran' => $path,
            ]);
            Log::info('Pemesanan CREATED SUCCESS', ['id' => $pemesanan->id]);

            // Assign seats (sama seperti sebelumnya, skip QR sementara)
            $assigned_seats = 0;
            foreach ($gerbongs as $gerbong) {
                if ($assigned_seats >= $passengers) break;

                $available_seats = $gerbong->available_seats;
                $seats_to_assign = min($available_seats, $passengers - $assigned_seats);

                $booked_count = PemesananDetail::where('id_gerbong', $gerbong->id)
                    ->whereHas('pemesanan', function ($q) use ($jadwal) {
                        $q->where('id_jadwal', $jadwal->id);
                    })->count();
                Log::info('Assigning to gerbong', ['id' => $gerbong->id, 'booked' => $booked_count, 'to_assign' => $seats_to_assign]);

                for ($i = 1; $i <= $seats_to_assign; $i++) {
                    $no_kursi = $booked_count + $i;
                    if ($no_kursi > $gerbong->jumlah_kursi) {
                        throw new \Exception('No kursi melebihi: ' . $no_kursi . ' > ' . $gerbong->jumlah_kursi);
                    }

                    $barcode = $pemesanan->id . '-' . $gerbong->id . '-' . $no_kursi . '-' . time();  // Fallback tanpa QR
                    $username = Auth::user()->username;
                    Log::info('Attempting detail create', ['no_kursi' => $no_kursi, 'id_gerbong' => $gerbong->id]);
                    $detail = PemesananDetail::create([
                        'id_pesan' => $pemesanan->id,
                        'id_gerbong' => $gerbong->id,
                        'no_kursi' => $no_kursi,
                        'nama_penumpang' => $username,
                        'kode_barcode' => $barcode,
                        'status_tiket' => 'aktif',
                    ]);
                    Log::info('Detail CREATED SUCCESS', ['id' => $detail->id, 'no_kursi' => $no_kursi]);

                    $assigned_seats++;
                }
            }

            DB::commit();
            Log::info('=== FULL SUCCESS - COMMITTED ===');
            return redirect()->route('pesantiket.payment', $pemesanan->id)->with('success', 'Pemesanan berhasil!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('=== ROLLEDBACK - ERROR: ' . $e->getMessage() . ' | Line: ' . $e->getLine());
            return redirect()->route('pesantiket.transaction', $jadwal->id)->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function showTransaction($id, Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'penumpang') {
            return redirect()->route('pesantiket.index')->with('error', 'Anda harus login sebagai penumpang untuk memesan tiket.');
        }

        $request->validate([
            'id_jadwal' => 'required|exists:jadwal_kereta,id',
            'passengers' => 'required|integer|min:1|max:100',
        ]);

        if ($request->input('id_jadwal') != $id) {
            return redirect()->route('pesantiket.index')->with('error', 'ID jadwal tidak valid.');
        }

        $jadwal = JadwalKereta::with(['stasiunAsal', 'stasiunTujuan', 'kereta'])->findOrFail($id);

        if (!$jadwal->stasiunAsal || !$jadwal->stasiunTujuan || !$jadwal->kereta) {
            Log::error('Invalid relationships for JadwalKereta ID: ' . $id, [
                'stasiunAsal' => $jadwal->stasiunAsal,
                'stasiunTujuan' => $jadwal->stasiunTujuan,
                'kereta' => $jadwal->kereta,
            ]);
            return redirect()->route('pesantiket.index')->with('error', 'Data jadwal tidak lengkap. Silakan hubungi dukungan.');
        }

        if ($jadwal->is_expired) {
            return redirect()->route('pesantiket.index')->with('error', 'Jadwal ini telah expired.');
        }

        $passengers = $request->input('passengers');
        $total_bayar = $jadwal->harga * $passengers;

        $gerbongs = Gerbong::whereHas('kereta', function ($q) use ($jadwal) {
            $q->where('id', $jadwal->kereta->id);
        })->get();

        $total_available_seats = 0;
        foreach ($gerbongs as $gerbong) {
            $gerbong->available_seats = 50 - PemesananDetail::where('id_gerbong', $gerbong->id)
                ->whereHas('pemesanan', function ($q) use ($jadwal) {
                    $q->where('id_jadwal', $jadwal->id);
                })->count();
            $total_available_seats += $gerbong->available_seats;
        }

        if ($total_available_seats < $passengers) {
            return redirect()->route('pesantiket.index')->with('error', 'Maaf, kursi yang tersedia tidak cukup untuk jumlah penumpang.');
        }

        // Generate a sample barcode for display
        $barcode_data = 'sample-' . $jadwal->id . '-' . time();
        try {
            $qrCode = QrCode::create($barcode_data)
                ->setSize(200);
            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            $barcode = base64_encode($result->getString());
        } catch (Exception $e) {
            Log::warning('Sample QR code generation failed: ' . $e->getMessage());
            $barcode = $barcode_data; // Fallback to plain text code
        }

        session(['booking_data' => [
            'id_jadwal' => $jadwal->id,
            'passengers' => $passengers,
            'total_bayar' => $total_bayar,
        ]]);

        return view('transaksi', compact('jadwal', 'passengers', 'total_bayar', 'gerbongs', 'barcode'));
    }

    public function showPayment($id)
    {
        $pemesanan = Pemesanan::with([
            'penumpang.user',  // Load user untuk validasi
            'jadwalKereta.stasiunAsal',
            'jadwalKereta.stasiunTujuan',
            'jadwalKereta.kereta',
            'detail.gerbong'  // Load detail & gerbong untuk barcode & no_kursi
        ])->findOrFail($id);

        // Validasi: User harus owner (penumpang.user.id == Auth::id())
        if (!Auth::check() || $pemesanan->penumpang->user->id !== Auth::id()) {
            Log::warning('Unauthorized access to pemesanan ID: ' . $id . ' by user: ' . Auth::id());
            return redirect()->route('pesantiket.index')->with('error', 'Anda tidak memiliki akses ke pemesanan ini.');
        }

        if (!$pemesanan->penumpang || !$pemesanan->jadwalKereta || !$pemesanan->jadwalKereta->stasiunAsal || !$pemesanan->jadwalKereta->stasiunTujuan || !$pemesanan->jadwalKereta->kereta) {
            Log::error('Invalid relationships for Pemesanan ID: ' . $id);
            return redirect()->route('pesantiket.index')->with('error', 'Data pemesanan tidak lengkap.');
        }

         // Harga per tiket
         $harga_per_tiket = $pemesanan->jadwalKereta->harga ?? 0;
        // Siapkan data tiket per detail (sudah loaded)
        $tiket_details = $pemesanan->detail->map(function ($detail) use ($pemesanan) {
            // Harga per tiket
            $harga_per_tiket = $pemesanan->jadwalKereta->harga ?? 0;
            // Jika kode_barcode adalah base64 image, gunakan langsung; jika text, regenerate QR
            $timestamp = Carbon::parse($pemesanan->tanggal_pesan)->timestamp;
            $barcode = $detail->kode_barcode;
            if (!str_starts_with($barcode, 'data:image')) {  // Jika bukan base64 image, regenerate
                $barcode_data = $pemesanan->id . '-' . $detail->gerbong->id . '-' . $detail->no_kursi . '-' . $timestamp;
                try {
                    $qrCode = QrCode::create($barcode_data)->setSize(200);
                    $writer = new PngWriter();
                    $result = $writer->write($qrCode);
                    $barcode = 'data:image/png;base64,' . base64_encode($result->getString());
                } catch (\Exception $e) {
                    Log::warning('QR regenerate failed for detail ID: ' . $detail->id . ' - ' . $e->getMessage());
                    $barcode = $barcode_data;  // Fallback text
                }
            }

            return [
                'detail' => $detail,
                'barcode' => $barcode,
                'rute' => optional($pemesanan->jadwalKereta->stasiunAsal)->nama_stasiun . ' → ' . optional($pemesanan->jadwalKereta->stasiunTujuan)->nama_stasiun,
                'status_tiket' => $detail->status_tiket,
                'harga_per_tiket' => $harga_per_tiket,
                'gerbong_info' => 'Gerbong ' . $detail->gerbong->kode_gerbong . ', Kursi ' . $detail->no_kursi,
            ];
        });

        return view('tiket', compact('pemesanan', 'tiket_details', 'harga_per_tiket'));
    }
    public function cetakTiket($id)
    {
        // Load pemesanan dengan relasi lengkap
        $pemesanan = Pemesanan::with([
            'penumpang.user',  // Untuk validasi owner
            'jadwalKereta.stasiunAsal',
            'jadwalKereta.stasiunTujuan',
            'jadwalKereta.kereta',
            'detail.gerbong'  // Untuk barcode & no_kursi
        ])->findOrFail($id);

        // Validasi: User harus owner
        if (!Auth::check() || $pemesanan->penumpang->user->id !== Auth::id()) {
            Log::warning('Unauthorized access to cetak tiket ID: ' . $id . ' by user: ' . Auth::id());
            return redirect()->route('pesantiket.index')->with('error', 'Anda tidak memiliki akses ke pemesanan ini.');
        }

        if (!$pemesanan->penumpang || !$pemesanan->jadwalKereta || !$pemesanan->jadwalKereta->stasiunAsal || !$pemesanan->jadwalKereta->stasiunTujuan || !$pemesanan->jadwalKereta->kereta) {
            Log::error('Invalid relationships for Pemesanan ID: ' . $id);
            return redirect()->route('pesantiket.index')->with('error', 'Data pemesanan tidak lengkap.');
        }

        // Harga per tiket
        $harga_per_tiket = $pemesanan->jadwalKereta->harga ?? 0;

        // Siapkan data tiket per detail (regenerate QR jika perlu)
        $tiket_details = $pemesanan->detail->map(function ($detail) use ($pemesanan) {
            $harga_per_tiket = $pemesanan->jadwalKereta->harga ?? 0;
            $timestamp = Carbon::parse($pemesanan->tanggal_pesan)->timestamp;
            $barcode = $detail->kode_barcode;
            if (!str_starts_with($barcode, 'data:image')) {  // Jika bukan base64, regenerate
                $barcode_data = $pemesanan->id . '-' . $detail->gerbong->id . '-' . $detail->no_kursi . '-' . $timestamp;
                try {
                    $qrCode = QrCode::create($barcode_data)->setSize(200);
                    $writer = new PngWriter();
                    $result = $writer->write($qrCode);
                    $barcode = 'data:image/png;base64,' . base64_encode($result->getString());
                } catch (\Exception $e) {
                    Log::warning('QR regenerate failed for detail ID: ' . $detail->id . ' - ' . $e->getMessage());
                    $barcode = $barcode_data;  // Fallback text
                }
            }

            return [
                'detail' => $detail,
                'barcode' => $barcode,
                'rute' => optional($pemesanan->jadwalKereta->stasiunAsal)->nama_stasiun . ' → ' . optional($pemesanan->jadwalKereta->stasiunTujuan)->nama_stasiun,
                'status_tiket' => $detail->status_tiket,
                'harga_per_tiket' => $harga_per_tiket,
                'gerbong_info' => 'Gerbong ' . $detail->gerbong->kode_gerbong . ', Kursi ' . $detail->no_kursi,
            ];
        });

        // Generate PDF
        $pdf = Pdf::loadView('cetak', compact('pemesanan', 'tiket_details', 'harga_per_tiket'))
                  ->setPaper('a4', 'portrait')  // Ukuran A4, potret
                  ->setOptions([
                      'defaultFont' => 'sans-serif',
                      'isHtml5ParserEnabled' => true,
                      'isRemoteEnabled' => true,  // Untuk load QR image
                  ]);

        // Return sebagai download
        return $pdf->download('tiket-pemesanan-' . $pemesanan->id . '.pdf');
    }
    public function processPayment(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'penumpang') {
            return redirect()->route('pesantiket.index')->with('error', 'Unauthorized.');
        }

        $request->validate([
            'metode_pembayaran' => 'required|in:transfer,credit_card,qris',
            'nomor_rekening' => 'required_if:metode_pembayaran,transfer|nullable|string',
            'nomor_kartu' => 'required_if:metode_pembayaran,credit_card|nullable|string',
            'nama_pemilik_kartu' => 'required_if:metode_pembayaran,credit_card|nullable|string',
            'passengers_data' => 'required|array|min:1',
            'passengers_data.*.nama_penumpang' => 'required|string|max:255',
        ]);

        if (!session('booking_id')) {
            return redirect()->route('pesantiket.index')->with('error', 'Sesi pemesanan expired.');
        }

        $pemesanan = Pemesanan::findOrFail(session('booking_id'));
        $jadwal = $pemesanan->jadwalKereta;
        if ($jadwal->is_expired) {
            return redirect()->route('pesantiket.index')->with('error', 'Jadwal telah expired.');
        }

        $passengersCount = count($request->input('passengers_data'));
        if ($passengersCount !== $pemesanan->jumlah_penumpang) {
            return back()->with('error', 'Jumlah penumpang tidak sesuai.');
        }

        $pemesanan->status_bayar = 'paid';
        $pemesanan->save();

        $gerbongs = Gerbong::whereHas('kereta', function ($q) use ($jadwal) {
            $q->where('id', $jadwal->kereta->id);
        })->orderBy('id')->get();

        $assignedSeats = [];
        foreach ($request->input('passengers_data') as $index => $passengerData) {
            $gerbong = $gerbongs->first(function ($g) use ($passengersCount, $index, $assignedSeats) {
                $takenInGerbong = collect($assignedSeats)->where('gerbong_id', $g->id)->count();
                return ($takenInGerbong + 1) <= 50;
            });

            if (!$gerbong) {
                return back()->with('error', 'Tidak ada kursi tersedia.');
            }

            $takenSeatsInGerbong = PemesananDetail::where('id_gerbong', $gerbong->id)
                                                  ->whereHas('pemesanan', function ($q) use ($jadwal) {
                                                      $q->where('id_jadwal', $jadwal->id);
                                                  })
                                                  ->pluck('no_kursi')
                                                  ->merge(collect($assignedSeats)->where('gerbong_id', $gerbong->id)->pluck('seat_no'))
                                                  ->toArray();

            $nextSeat = collect(range(1, 50))->first(function ($seat) use ($takenSeatsInGerbong) {
                return !in_array($seat, $takenSeatsInGerbong);
            });

            if (!$nextSeat) {
                return back()->with('error', 'Tidak ada kursi tersedia di gerbong ini.');
            }

            $detail = new PemesananDetail();
            $detail->id_pesan = $pemesanan->id;
            $detail->id_gerbong = $gerbong->id;
            $detail->no_kursi = $nextSeat;
            $detail->nama_penumpang = $passengerData['nama_penumpang'];
            $detail->kode_barcode = Str::random(10);
            $detail->save();

            $assignedSeats[] = ['gerbong_id' => $gerbong->id, 'seat_no' => $nextSeat];
        }

        session()->forget(['booking_hash', 'booking_id', 'booking_data']);

        return redirect()->route('penumpang.dashboard')->with('success', 'Pemesanan dan pembayaran berhasil! Tiket telah terbit dengan nomor kursi otomatis.');
    }
}
