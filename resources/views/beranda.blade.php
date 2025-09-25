@extends('layout.main', ['title' => 'Beranda'])
@section('content')

<section id="beranda" class="bg-white rounded-lg shadow-lg p-6 md:p-12 mb-8 text-center">
    <div class="flex flex-col items-center">
        <h1 class="text-3xl md:text-5xl font-extrabold text-gray-800 leading-tight mb-4">
            Jelajahi Indonesia dengan Kereta Api
        </h1>
        <p class="text-lg text-gray-600 mb-6 max-w-2xl">
            Nikmati perjalanan yang nyaman, aman, dan tepat waktu. Pesan tiket Anda sekarang dan mulai petualangan baru!
        </p>
        <a href="{{ '/pesantiket' }}" class="bg-blue-600 text-white font-bold py-3 px-8 rounded-full hover:bg-blue-700 transition duration-300 transform hover:scale-105">
            Pesan Tiket Sekarang
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
        <div class="flex flex-col items-center text-center">
            <i class="fas fa-clock text-4xl text-blue-600 mb-4"></i>
            <h3 class="font-bold text-gray-800 text-lg mb-2">Pemesanan Cepat</h3>
            <p class="text-gray-600 text-sm">Pesan tiket hanya dalam hitungan menit dari mana saja.</p>
        </div>
        <div class="flex flex-col items-center text-center">
            <i class="fas fa-route text-4xl text-blue-600 mb-4"></i>
            <h3 class="font-bold text-gray-800 text-lg mb-2">Berbagai Pilihan Rute</h3>
            <p class="text-gray-600 text-sm">Tersedia banyak rute ke berbagai kota di seluruh Indonesia.</p>
        </div>
        <div class="flex flex-col items-center text-center">
            <i class="fas fa-shield-alt text-4xl text-blue-600 mb-4"></i>
            <h3 class="font-bold text-gray-800 text-lg mb-2">Pembayaran Aman</h3>
            <p class="text-gray-600 text-sm">Transaksi Anda terjamin aman dengan berbagai metode pembayaran.</p>
        </div>
    </div>
</section>

<section id="jadwal-hari-ini" class="bg-white rounded-lg shadow-lg p-6 md:p-12 mb-8">
    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 text-center">Jadwal Kereta Hari Ini</h2>
    @if($jadwal->isEmpty())
        <p class="text-gray-600 text-center">Tidak ada jadwal kereta untuk hari ini.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-blue-100">
                        <th class="p-4 font-semibold text-gray-800">Nama Kereta</th>
                        <th class="p-4 font-semibold text-gray-800">Stasiun Asal</th>
                        <th class="p-4 font-semibold text-gray-800">Stasiun Tujuan</th>
                        <th class="p-4 font-semibold text-gray-800">Jam Keberangkatan</th>
                        <th class="p-4 font-semibold text-gray-800">Jam Kedatangan</th>
                        <th class="p-4 font-semibold text-gray-800">Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jadwal as $item)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4">{{ $item->kereta->nama_kereta ?? 'N/A' }}</td>
                            <td class="p-4">{{ $item->stasiunAsal->nama_stasiun ?? 'N/A' }}</td>
                            <td class="p-4">{{ $item->stasiunTujuan->nama_stasiun ?? 'N/A' }}</td>
                            <td class="p-4">{{ \Carbon\Carbon::parse($item->jam_keberangkatan)->format('H:i') }}</td>
                            <td class="p-4">{{ \Carbon\Carbon::parse($item->jam_kedatangan)->format('H:i') }}</td>
                            <td class="p-4">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</section>

@endsection
