@extends('layout.main', ['title' => 'Pembayaran Pemesanan'])

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
                <p><strong>Rute:</strong> {{ optional($pemesanan->jadwalKereta->stasiunAsal)->nama_stasiun ?? 'Tidak tersedia' }} → {{ optional($pemesanan->jadwalKereta->stasiunTujuan)->nama_stasiun ?? 'Tidak tersedia' }}</p>
                <p><strong>Kereta:</strong> {{ optional($pemesanan->jadwalKereta->kereta)->nama_kereta ?? 'Tidak tersedia' }}</p>
                <p><strong>Keberangkatan:</strong> {{ $pemesanan->jadwalKereta->jam_keberangkatan ? \Carbon\Carbon::parse($pemesanan->jadwalKereta->jam_keberangkatan)->format('d M Y, H:i') : 'Tidak tersedia' }}</p>
                <p><strong>Jumlah Penumpang:</strong> {{ $pemesanan->jumlah_penumpang }}</p>
            </div>
            <div class="text-right">
                <p class="text-3xl font-bold text-blue-600">Total: Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</p>
                <p class="text-sm text-gray-500">Harga per tiket: Rp {{ number_format($pemesanan->jadwalKereta->harga ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <form action="{{ route('pesantiket.payment.process') }}" method="POST" class="bg-white rounded-xl shadow-md p-6">
        @csrf
        <input type="hidden" name="id_pemesanan" value="{{ $pemesanan->id }}">

        <h3 class="text-xl font-bold mb-4">Metode Pembayaran (Online)</h3>
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Metode:</label>
            <div class="grid grid-cols-3 gap-4">
                <label class="flex items-center p-3 border rounded-md cursor-pointer @error('metode_pembayaran') border-red-500 @enderror">
                    <input type="radio" name="metode_pembayaran" value="transfer" class="mr-2" required>
                    <span>Transfer Bank</span>
                </label>
                <label class="flex items-center p-3 border rounded-md cursor-pointer @error('metode_pembayaran') border-red-500 @enderror">
                    <input type="radio" name="metode_pembayaran" value="credit_card" class="mr-2" required>
                    <span>Kartu Kredit</span>
                </label>
                <label class="flex items-center p-3 border rounded-md cursor-pointer @error('metode_pembayaran') border-red-500 @enderror">
                    <input type="radio" name="metode_pembayaran" value="qris" class="mr-2" required>
                    <span>QRIS</span>
                </label>
            </div>
            @error('metode_pembayaran')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div id="transfer-details" class="mb-6 hidden">
            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Rekening Tujuan:</label>
            <input type="text" name="nomor_rekening" placeholder="Masukkan nomor rekening" class="w-full p-3 border rounded-md">
            @error('nomor_rekening')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div id="credit-card-details" class="mb-6 hidden">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Kartu:</label>
                    <input type="text" name="nomor_kartu" placeholder="1234 5678 9012 3456" class="w-full p-3 border rounded-md">
                    @error('nomor_kartu')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pemilik Kartu:</label>
                    <input type="text" name="nama_pemilik_kartu" placeholder="John Doe" class="w-full p-3 border rounded-md">
                    @error('nama_pemilik_kartu')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <h3 class="text-xl font-bold mb-4">Detail Penumpang</h3>
        <div id="passengers-container" class="mb-6">
            @for ($i = 0; $i < $pemesanan->jumlah_penumpang; $i++)
                <div class="border p-4 rounded-md mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Penumpang {{ $i + 1 }}:</label>
                    <input type="text" name="passengers_data[{{ $i }}][nama_penumpang]" placeholder="Nama lengkap penumpang {{ $i + 1 }}" class="w-full p-3 border rounded-md" required>
                    @error("passengers_data.{$i}.nama_penumpang")
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endfor
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-md hover:bg-blue-700 transition duration-300">
            Proses Pembayaran & Terbitkan Tiket
        </button>
    </form>

    <script>
        document.querySelectorAll('input[name="metode_pembayaran"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('transfer-details').classList.add('hidden');
                document.getElementById('credit-card-details').classList.add('hidden');
                if (this.value === 'transfer') {
                    document.getElementById('transfer-details').classList.remove('hidden');
                } else if (this.value === 'credit_card') {
                    document.getElementById('credit-card-details').classList.remove('hidden');
                }
            });
        });
    </script>
</div>
@endsection
