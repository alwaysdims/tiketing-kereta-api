@extends('layout.main', ['title' => 'Tiket Pemesanan'])

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

    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Detail Pemesanan</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p><strong>Rute:</strong> {{ $pemesanan->jadwalKereta->stasiunAsal->nama_stasiun ?? 'Tidak tersedia' }} → {{ $pemesanan->jadwalKereta->stasiunTujuan->nama_stasiun ?? 'Tidak tersedia' }}</p>
                <p><strong>Kereta:</strong> {{ $pemesanan->jadwalKereta->kereta->nama_kereta ?? 'Tidak tersedia' }}</p>
                <p><strong>Keberangkatan:</strong> {{ \Carbon\Carbon::parse($pemesanan->jadwalKereta->jam_keberangkatan)->format('d M Y, H:i') }}</p>
                <p><strong>Jumlah Penumpang:</strong> {{ $pemesanan->jumlah_penumpang }}</p>
            </div>
            <div class="text-right">
                <p class="text-3xl font-bold text-blue-600">Total: Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</p>
                <p class="text-sm text-gray-500">Harga per tiket: Rp {{ number_format($harga_per_tiket, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        @foreach($tiket_details as $index => $tiket)
            <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6 print:border-t-4 print:border-blue-500">
                <div class="text-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900">Tiket Kereta Api</h3>
                    <p class="text-sm text-gray-600">Pemesanan #{{ $pemesanan->id }} - Penumpang {{ $index + 1 }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <p class="font-semibold text-gray-800 mb-1">Rute:</p>
                        <p class="text-gray-700">{{ $tiket['rute'] }}</p>

                        <p class="font-semibold text-gray-800 mb-1 mt-3">Gerbong & Kursi:</p>
                        <p class="text-gray-700">{{ $tiket['gerbong_info'] }}</p>

                        <p class="font-semibold text-gray-800 mb-1 mt-3">Status Tiket:</p>
                        <p class="text-{{ $tiket['status_tiket'] === 'aktif' ? 'green' : 'red' }}-700 font-medium">
                            {{ ucfirst($tiket['status_tiket']) }}
                        </p>

                        <p class="font-semibold text-gray-800 mb-1 mt-3">Harga per Tiket:</p>
                        <p class="text-blue-600 font-bold">Rp {{ number_format($tiket['harga_per_tiket'], 0, ',', '.') }}</p>
                    </div>

                    <div class="text-center">
                        <p class="font-semibold text-gray-800 mb-3">Barcode Tiket</p>
                        @if (str_starts_with($tiket['barcode'], 'data:image'))
                            <img src="{{ $tiket['barcode'] }}" alt="Barcode Tiket" class="mx-auto w-48 h-48 border border-gray-300 rounded-md p-2">
                        @else
                            <div class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-md p-4 mx-auto w-48 h-48 flex items-center justify-center text-sm font-mono text-gray-600">
                                {{ $tiket['barcode'] }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="text-center print:text-xs">
                    <p class="text-xs text-gray-500">Cetak atau simpan tiket ini untuk check-in. Berlaku hingga kedatangan kereta.</p>
                    <a href="{{ route('pesantiket.cetak', $pemesanan->id) }}"
                       class="mt-2 bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 inline-block no-print">
                        Download & Cetak PDF
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    {{-- <div class="text-center mt-8">
        <a href="{{ route('pesantiket.index') }}" class="bg-gray-500 text-white px-6 py-3 rounded-md hover:bg-gray-600">
            Kembali ke Daftar Jadwal
        </a>
    </div> --}}
</div>

<style>
    @media print {
        body { margin: 0; }
        .no-print { display: none; }
        .print-break { page-break-before: always; }
    }
</style>
@endsection
