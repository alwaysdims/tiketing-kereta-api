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
use Illuminate\Support\Facades\Log;
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
            \Log::error('Invalid relationships for JadwalKereta ID: ' . $id, [
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

        session(['booking_data' => [
            'id_jadwal' => $jadwal->id,
            'passengers' => $passengers,
            'total_bayar' => $total_bayar,
        ]]);

        return view('transaksi', compact('jadwal', 'passengers', 'total_bayar', 'gerbongs'));
    }

    public function store(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'penumpang') {
            return redirect()->route('pesantiket.index')->with('error', 'Anda harus login sebagai penumpang untuk memesan tiket.');
        }

        $request->validate([
            'id_jadwal' => 'required|exists:jadwal_kereta,id',
            'passengers' => 'required|integer|min:1|max:100',
        ]);

        $booking_data = session('booking_data');
        if (!$booking_data || $booking_data['id_jadwal'] != $request->input('id_jadwal')) {
            return redirect()->route('pesantiket.index')->with('error', 'Sesi pemesanan tidak valid.');
        }

        $jadwal = JadwalKereta::findOrFail($request->input('id_jadwal'));
        if ($jadwal->is_expired) {
            return redirect()->route('pesantiket.index')->with('error', 'Jadwal ini telah expired.');
        }

        $penumpang = Penumpang::where('id_user', Auth::user()->id)->first();
        if (!$penumpang) {
            \Log::error('No Penumpang record found for user ID: ' . Auth::user()->id);
            return redirect()->route('pesantiket.index')->with('error', 'Akun Anda tidak terdaftar sebagai penumpang. Silakan lengkapi profil Anda atau hubungi dukungan.');
        }

        $pemesanan = new Pemesanan();
        $pemesanan->id_penumpang = $penumpang->id;
        $pemesanan->id_jadwal = $request->input('id_jadwal');
        $pemesanan->jumlah_penumpang = $request->input('passengers');
        $pemesanan->tanggal_pesan = now();
        $pemesanan->total_bayar = $jadwal->harga * $request->input('passengers');
        $pemesanan->status_bayar = 'pending';
        $pemesanan->save();

        $hashedId = Hash::make($pemesanan->id . '_' . Str::random(10));
        session(['booking_hash' => $hashedId, 'booking_id' => $pemesanan->id]);

        return redirect()->route('pesantiket.payment', ['id' => $hashedId])
                         ->with('success', 'Pemesanan berhasil! Silakan lanjutkan ke pembayaran.');
    }

    public function showPayment($hashedId)
    {
        if (!session('booking_id') || !Hash::check(session('booking_id') . '_' . Str::random(10), session('booking_hash'))) {
            return redirect()->route('pesantiket.index')->with('error', 'Data pemesanan tidak valid.');
        }

        $pemesanan = Pemesanan::with(['penumpang', 'jadwalKereta.stasiunAsal', 'jadwalKereta.stasiunTujuan', 'jadwalKereta.kereta'])
                               ->findOrFail(session('booking_id'));

        if (!$pemesanan->penumpang || !$pemesanan->jadwalKereta || !$pemesanan->jadwalKereta->stasiunAsal || !$pemesanan->jadwalKereta->stasiunTujuan || !$pemesanan->jadwalKereta->kereta) {
            \Log::error('Invalid relationships for Pemesanan ID: ' . session('booking_id'), [
                'penumpang' => $pemesanan->penumpang,
                'jadwalKereta' => $pemesanan->jadwalKereta,
            ]);
            return redirect()->route('pesantiket.index')->with('error', 'Data pemesanan tidak lengkap. Silakan hubungi dukungan.');
        }

        $gerbongs = Gerbong::whereHas('kereta', function ($q) use ($pemesanan) {
            $q->where('id', $pemesanan->jadwalKereta->kereta->id);
        })->get();

        foreach ($gerbongs as $gerbong) {
            $gerbong->available_seats = 50 - PemesananDetail::where('id_gerbong', $gerbong->id)
                                                             ->whereHas('pemesanan', function ($q) use ($pemesanan) {
                                                                 $q->where('id_jadwal', $pemesanan->id_jadwal);
                                                             })->count();
            $gerbong->next_seats = collect(range(1, 50))->reject(function ($seat) use ($gerbong, $pemesanan) {
                return PemesananDetail::where('id_gerbong', $gerbong->id)
                                       ->whereHas('pemesanan', function ($q) use ($pemesanan) {
                                           $q->where('id_jadwal', $pemesanan->id_jadwal);
                                       })->pluck('no_kursi')->contains($seat);
            })->take($pemesanan->jumlah_penumpang)->values()->toArray();
        }

        return view('payment', compact('pemesanan', 'gerbongs'));
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
