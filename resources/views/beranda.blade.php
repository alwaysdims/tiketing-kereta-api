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

@endsection
