@extends('admin_kai.layout.main',['title'=>'Laporan Admin'])
@section('content')

<div class="mb-6">
    <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Cetak Laporan
    </button>
</div>

<h1 class="text-2xl font-bold text-gray-800 mb-6">Laporan Pemesanan</h1>

<div class="overflow-x-auto">
    <table class="min-w-full bg-white border border-gray-300">
        <thead class="bg-gray-50">
            <tr>
                <th class="py-2 px-4 border-b text-left">ID Pemesanan</th>
                <th class="py-2 px-4 border-b text-left">Penumpang</th>
                <th class="py-2 px-4 border-b text-left">Jadwal Kereta</th>
                <th class="py-2 px-4 border-b text-left">Tanggal Pesan</th>
                <th class="py-2 px-4 border-b text-left">Total Bayar</th>
                <th class="py-2 px-4 border-b text-left">Jumlah Penumpang</th>
                <th class="py-2 px-4 border-b text-left">Status Bayar</th>
                <th class="py-2 px-4 border-b text-left">Detail Tiket</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pemesanans as $pemesanan)
            <tr>
                <td class="py-2 px-4 border-b">{{ $pemesanan->id }}</td>
                <td class="py-2 px-4 border-b">{{ $pemesanan->penumpang->nama ?? 'N/A' }}</td>
                <td class="py-2 px-4 border-b">{{ $pemesanan->jadwalKereta->rute ?? 'N/A' }} - {{ $pemesanan->jadwalKereta->waktu_berangkat ?? 'N/A' }}</td>
                <td class="py-2 px-4 border-b">{{ $pemesanan->tanggal_pesan }}</td>
                <td class="py-2 px-4 border-b">Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</td>
                <td class="py-2 px-4 border-b">{{ $pemesanan->jumlah_penumpang }}</td>
                <td class="py-2 px-4 border-b">
                    <span class="px-2 py-1 text-xs rounded {{ $pemesanan->status_bayar == 'Lunas' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                        {{ $pemesanan->status_bayar }}
                    </span>
                </td>
                <td class="py-2 px-4 border-b">
                    @if($pemesanan->detail->count() > 0)
                        <ul class="list-disc list-inside text-sm">
                            @foreach($pemesanan->detail as $detail)
                                <li>Gerbong: {{ $detail->gerbong->nama_gerbong ?? 'N/A' }} | Kursi: {{ $detail->no_kursi }} | Status: {{ $detail->status_tiket }}</li>
                            @endforeach
                        </ul>
                    @else
                        <span class="text-gray-500">Tidak ada detail</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="py-4 px-4 text-center text-gray-500">Tidak ada data pemesanan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<style>
@media print {
    .no-print { display: none; }
}
</style>

@endsection
