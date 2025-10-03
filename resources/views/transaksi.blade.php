@extends('layout.main', ['title' => 'Konfirmasi Pemesanan'])

@section('content')
<div class="container mx-auto max-w-4xl px-4 py-12">
    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 rounded-lg mb-8" role="alert">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 rounded-lg mb-8" role="alert">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (!$jadwal || !$jadwal->stasiunAsal || !$jadwal->stasiunTujuan || !$jadwal->kereta)
        <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 rounded-lg mb-8" role="alert">
            <p>Data jadwal tidak lengkap. Silakan coba lagi atau hubungi dukungan.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-2xl shadow-xl p-8 h-fit">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Detail Pemesanan</h2>
                <div class="space-y-4 text-gray-700">
                    <p><strong class="font-semibold">Rute:</strong> {{ optional($jadwal->stasiunAsal)->nama_stasiun ?? 'Tidak tersedia' }} <span class="text-gray-400">→</span> {{ optional($jadwal->stasiunTujuan)->nama_stasiun ?? 'Tidak tersedia' }}</p>
                    <p><strong class="font-semibold">Kereta:</strong> {{ optional($jadwal->kereta)->nama_kereta ?? 'Tidak tersedia' }}</p>
                    <p><strong class="font-semibold">Keberangkatan:</strong> {{ $jadwal->jam_keberangkatan ? \Carbon\Carbon::parse($jadwal->jam_keberangkatan)->isoFormat('dddd, D MMMM Y, HH:mm') : 'Tidak tersedia' }}</p>
                    <p><strong class="font-semibold">Kedatangan:</strong> {{ $jadwal->jam_kedatangan ? \Carbon\Carbon::parse($jadwal->jam_kedatangan)->isoFormat('dddd, D MMMM Y, HH:mm') : 'Tidak tersedia' }}</p>
                    <p><strong class="font-semibold">Jumlah Penumpang:</strong> {{ $passengers }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-8 h-fit">
                @error('bukti_pembayaran')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Pembayaran</h2>
                <form action="{{ route('pesantiket.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <input type="hidden" name="id_jadwal" value="{{ $jadwal->id }}">
                    <input type="hidden" name="passengers" value="{{ $passengers }}">

                    <div class="text-center bg-gray-50 p-6 rounded-lg">
                        <p class="text-lg text-gray-600 font-medium">Total Pembayaran</p>
                        <p class="text-4xl font-extrabold text-blue-600 mt-2">Rp {{ number_format($total_bayar ?? 0, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-500 mt-1">Harga per tiket: Rp {{ number_format($jadwal->harga ?? 0, 0, ',', '.') }}</p>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg text-center">
                        <p class="text-lg font-medium text-blue-700  mb-4">Scan Qris Untuk Pembayar</p>
                        @if (base64_decode($barcode, true) !== false)
                            <img src="data:image/png;base64,{{ $barcode }}" alt="Barcode" class="h-24 w-auto mx-auto border border-gray-200 rounded-md p-2">
                        @else
                            <p class="text-center text-gray-600 py-4">{{ $barcode }}</p>
                        @endif
                    </div>

                    <div>
                        <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Pembayaran</label>
                        <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100" accept="image/*" required>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-4 rounded-xl hover:bg-blue-700 transition duration-300 transform hover:scale-105 shadow-lg">
                        Konfirmasi Pembayaran
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection
