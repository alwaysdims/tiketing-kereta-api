<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tiket Pemesanan #{{ $pemesanan->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; color: #333; background: white; }
        .ticket { border: 2px solid #007bff; border-radius: 10px; margin-bottom: 20px; padding: 20px; page-break-inside: avoid; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        .header h3 { color: #007bff; margin: 0; font-size: 24px; }
        .header p { margin: 5px 0; font-size: 12px; color: #666; }
        .content { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .info { flex: 1; }
        .barcode-section { flex: 1; text-align: center; }
        .info p { margin: 10px 0; font-size: 14px; }
        .info strong { color: #007bff; }
        .barcode { width: 150px; height: 150px; margin: 0 auto; border: 1px solid #ccc; border-radius: 5px; padding: 5px; }
        .fallback-barcode { background: #f8f9fa; border: 1px dashed #ccc; border-radius: 5px; padding: 10px; font-family: monospace; font-size: 10px; word-break: break-all; }
        .footer { text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 10px; margin-top: 20px; }
        @page { margin: 1cm; }
    </style>
</head>
<body>
    @foreach($tiket_details as $index => $tiket)
        <div class="ticket">
            <div class="header">
                <h3>Tiket Kereta Api</h3>
                <p>Pemesanan #{{ $pemesanan->id }} - Penumpang {{ $index + 1 }}</p>
            </div>

            <div class="content">
                <div class="info">
                    <p><strong>Rute:</strong> {{ $tiket['rute'] }}</p>
                    <p><strong>Gerbong & Kursi:</strong> {{ $tiket['gerbong_info'] }}</p>
                    <p><strong>Status Tiket:</strong>
                        <span style="color: {{ $tiket['status_tiket'] === 'aktif' ? '#28a745' : '#dc3545' }}; font-weight: bold;">
                            {{ ucfirst($tiket['status_tiket']) }}
                        </span>
                    </p>
                    <p><strong>Harga per Tiket:</strong> Rp {{ number_format($tiket['harga_per_tiket'], 0, ',', '.') }}</p>
                    <p><strong>Keberangkatan:</strong> {{ \Carbon\Carbon::parse($pemesanan->jadwalKereta->jam_keberangkatan)->format('d M Y, H:i') }}</p>
                </div>

                <div class="barcode-section">
                    <p><strong>Barcode Tiket</strong></p>
                    @if (str_starts_with($tiket['barcode'], 'data:image'))
                        <img src="{{ $tiket['barcode'] }}" alt="Barcode" class="barcode">
                    @else
                        <div class="fallback-barcode">{{ $tiket['barcode'] }}</div>
                    @endif
                </div>
            </div>

            <div class="footer">
                <p>Cetak atau simpan tiket ini untuk check-in. Berlaku hingga kedatangan kereta.</p>
                <p>PT Kereta Api Indonesia - {{ now()->format('d M Y') }}</p>
            </div>
        </div>
        @if ($loop->last == false)
            <div style="page-break-before: always;"></div>  <!-- Page break antar tiket -->
        @endif
    @endforeach
</body>
</html>
