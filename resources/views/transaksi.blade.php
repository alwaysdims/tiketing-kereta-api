@extends('layout.main', ['title' => 'Konfirmasi Pemesanan'])

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    @if (!$jadwal || !$jadwal->stasiunAsal || !$jadwal->stasiunTujuan || !$jadwal->kereta)
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            Data jadwal tidak lengkap. Silakan coba lagi atau hubungi dukungan.
        </div>
    @else
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Konfirmasi Pemesanan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p><strong>Rute:</strong> {{ optional($jadwal->stasiunAsal)->nama_stasiun ?? 'Tidak tersedia' }} → {{ optional($jadwal->stasiunTujuan)->nama_stasiun ?? 'Tidak tersedia' }}</p>
                    <p><strong>Kereta:</strong> {{ optional($jadwal->kereta)->nama_kereta ?? 'Tidak tersedia' }}</p>
                    <p><strong>Keberangkatan:</strong> {{ $jadwal->jam_keberangkatan ? \Carbon\Carbon::parse($jadwal->jam_keberangkatan)->format('d M Y, H:i') : 'Tidak tersedia' }}</p>
                    <p><strong>Kedatangan:</strong> {{ $jadwal->jam_kedatangan ? \Carbon\Carbon::parse($jadwal->jam_kedatangan)->format('d M Y, H:i') : 'Tidak tersedia' }}</p>
                    <p><strong>Jumlah Penumpang:</strong> {{ $passengers }}</p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-blue-600">Total: Rp {{ number_format($total_bayar ?? 0, 0, ',', '.') }}</p>
                    <p class="text-sm text-gray-500">Harga per tiket: Rp {{ number_format($jadwal->harga ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('pesantiket.store') }}" method="POST" class="bg-white rounded-xl shadow-md p-6">
            @csrf
            <input type="hidden" name="id_jadwal" value="{{ $jadwal->id }}">
            <input type="hidden" name="passengers" value="{{ $passengers }}">
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-md hover:bg-blue-700 transition duration-300">
                Konfirmasi dan Lanjutkan ke Pembayaran
            </button>
        </form>
    @endif
</div>
@endsection
