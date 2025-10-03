@extends('layout.main', ['title' => 'Pesan Tiket'])

@section('content')
<!-- Include Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<section id="pesan-tiket" class="bg-white rounded-lg shadow-lg p-6 md:p-8 max-w-4xl mx-auto">
    <h2 class="text-xl md:text-2xl font-bold text-center text-gray-800 mb-6">Cari Tiket Kereta Api</h2>

    <!-- Search Form -->
    <form id="search-form" action="{{ route('pesantiket.search') }}" method="POST" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="origin" class="block text-gray-700 font-semibold mb-2">Stasiun Asal</label>
                <select id="origin" name="origin" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="" {{ old('origin', $origin ?? '') === '' ? 'selected' : '' }}>Pilih Stasiun (Opsional)</option>
                    @foreach (\App\Models\Stasiun::all() as $stasiun)
                        <option value="{{ $stasiun->nama_stasiun }}" {{ old('origin', $origin ?? '') === $stasiun->nama_stasiun ? 'selected' : '' }}>
                            {{ $stasiun->nama_stasiun }} ({{ $stasiun->kota }})
                        </option>
                    @endforeach
                </select>
                @error('origin')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="destination" class="block text-gray-700 font-semibold mb-2">Stasiun Tujuan</label>
                <select id="destination" name="destination" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="" {{ old('destination', $destination ?? '') === '' ? 'selected' : '' }}>Pilih Stasiun (Opsional)</option>
                    @foreach (\App\Models\Stasiun::all() as $stasiun)
                        <option value="{{ $stasiun->nama_stasiun }}" {{ old('destination', $destination ?? '') === $stasiun->nama_stasiun ? 'selected' : '' }}>
                            {{ $stasiun->nama_stasiun }} ({{ $stasiun->kota }})
                        </option>
                    @endforeach
                </select>
                @error('destination')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="date" class="block text-gray-700 font-semibold mb-2">Tanggal Keberangkatan</label>
                <input type="date" id="date" name="date" value="{{ old('date', $date ?? now()->format('Y-m-d')) }}"
                       class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="passengers" class="block text-gray-700 font-semibold mb-2">Jumlah Penumpang</label>
                <input type="number" id="passengers" name="passengers" min="1" max="100" value="{{ old('passengers', $passengers ?? 1) }}"
                       class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('passengers')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-md hover:bg-blue-700 transition duration-300">
                Cari Tiket
            </button>
        </div>
    </form>
</section>

<!-- Results Section -->
@if(isset($jadwals))
    <section id="results-section" class="mt-8 max-w-4xl mx-auto">
        @if($jadwals->count() > 0)
            <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-6 text-center">Jadwal Kereta Tersedia</h2>
            <div id="results-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($jadwals as $jadwal)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 {{ $jadwal->is_expired ? 'opacity-50' : '' }}">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $jadwal->kereta->nama_kereta ?? 'Kereta' }}</h3>
                            <div class="flex items-center text-gray-600 mb-4">
                                <span class="font-medium">{{ $jadwal->stasiunAsal->nama_stasiun }}</span>
                                <span class="mx-2">&rarr;</span>
                                <span class="font-medium">{{ $jadwal->stasiunTujuan->nama_stasiun }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p class="text-sm text-gray-500">Keberangkatan</p>
                                    <p class="font-semibold {{ $jadwal->is_expired ? 'text-red-600' : '' }}">
                                        {{ \Carbon\Carbon::parse($jadwal->jam_keberangkatan)->format('H:i, d M Y') }}
                                        @if($jadwal->is_expired)
                                            <span class="text-sm text-red-600">(Expired)</span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Kedatangan</p>
                                    <p class="font-semibold">{{ \Carbon\Carbon::parse($jadwal->jam_kedatangan)->format('H:i, d M Y') }}</p>
                                </div>
                            </div>
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <p class="text-sm text-gray-500">Durasi</p>
                                    <p class="font-semibold">
                                        @php
                                            $duration = \Carbon\Carbon::parse($jadwal->jam_kedatangan)->diff(\Carbon\Carbon::parse($jadwal->jam_keberangkatan));
                                            echo $duration->format('%h jam %i menit');
                                        @endphp
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Harga per Tiket</p>
                                    <p class="font-bold text-blue-600">Rp {{ number_format($jadwal->harga, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            @if($jadwal->is_expired)
                                <div class="text-center bg-red-100 text-red-600 font-medium p-4 rounded-md mt-4">
                                    Tiket ini telah expired dan tidak dapat dipesan.
                                </div>
                            @elseif(Auth::check() && Auth::user()->role === 'penumpang')
                                <form action="{{ route('pesantiket.transaction', ['id' => $jadwal->id]) }}" method="GET">
                                    @csrf
                                    <input type="hidden" name="id_jadwal" value="{{ $jadwal->id }}">
                                    <input type="hidden" name="passengers" value="{{ $passengers }}">
                                    <button type="submit" class="w-full bg-green-600 text-white font-bold py-3 rounded-md hover:bg-green-700 transition duration-300 flex items-center justify-center">
                                        <span>Pesan Tiket</span>
                                        <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3 eastward:0/svg">
                                            
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                    </button>
                                </form>
                            @else
                                <div class="text-center bg-yellow-100 text-yellow-600 font-medium p-4 rounded-md mt-4">
                                    Login sebagai penumpang untuk memesan tiket.
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center">
                <p class="text-red-600 font-medium text-lg">Tidak ada jadwal ditemukan.</p>
                <p class="text-gray-600 mt-2">Coba ubah kriteria pencarian Anda.</p>
            </div>
        @endif
    </section>
@endif

<!-- Include Select2 JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2 for station dropdowns
        $('#origin, #destination').select2({
            placeholder: "Pilih stasiun (opsional)",
            allowClear: true,
            width: '100%'
        });

        // Restrict date input to today or later
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('date').setAttribute('min', today);

        // Optional: Ensure passengers input doesn't go below 1
        $('#passengers').on('change', function() {
            if (this.value < 1) this.value = 1;
            if (this.value > 100) this.value = 100; // Match controller max
        });
    });
</script>
@endsection
