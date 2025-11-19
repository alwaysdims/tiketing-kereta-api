<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pemesanan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .status-lunas { background-color: #d4edda; color: #155724; padding: 2px 5px; border-radius: 3px; }
        .status-belum { background-color: #f8d7da; color: #721c24; padding: 2px 5px; border-radius: 3px; }
        ul { margin: 0; padding-left: 20px; }
    </style>
</head>
<body>
    <h1>Laporan Pemesanan</h1>

    <table>
        <thead>
            <tr>
                <th>ID Pemesanan</th>
                <th>Penumpang</th>
                <th>Jadwal Kereta</th>
                <th>Tanggal Pesan</th>
                <th>Total Bayar</th>
                <th>Jumlah Penumpang</th>
                <th>Status Bayar</th>
                <th>Detail Tiket</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pemesanans as $pemesanan)
            <tr>
                <td>{{ $pemesanan->id }}</td>
                <td>{{ $pemesanan->penumpang->nama ?? 'N/A' }}</td>
                <td>{{ $pemesanan->jadwalKereta->rute ?? 'N/A' }} - {{ $pemesanan->jadwalKereta->waktu_berangkat ?? 'N/A' }}</td>
                <td>{{ $pemesanan->tanggal_pesan }}</td>
                <td>Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</td>
                <td>{{ $pemesanan->jumlah_penumpang }}</td>
                <td>
                    <span class="{{ $pemesanan->status_bayar == 'Lunas' ? 'status-lunas' : 'status-belum' }}">
                        {{ $pemesanan->status_bayar }}
                    </span>
                </td>
                <td>
                    @if($pemesanan->detail->count() > 0)
                        <ul>
                            @foreach($pemesanan->detail as $detail)
                                <li>Gerbong: {{ $detail->gerbong->nama_gerbong ?? 'N/A' }} | Kursi: {{ $detail->no_kursi }} | Status: {{ $detail->status_tiket }}</li>
                            @endforeach
                        </ul>
                    @else
                        <span>Tidak ada detail</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center;">Tidak ada data pemesanan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
