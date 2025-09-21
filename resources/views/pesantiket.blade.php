@extends('layout.main', ['title' => 'Pesan Tiket'])
@section('content')
<section id="pesan-tiket" class="bg-white rounded-lg shadow-lg p-6 md:p-8">
    <h2 class="text-xl md:text-2xl font-bold text-center text-gray-800 mb-6">Cari Tiket Kereta Api</h2>
    <form id="search-form" class="space-y-4">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="origin" class="block text-gray-700 font-semibold mb-2">Asal</label>
                <input type="text" id="origin" name="origin" placeholder="Stasiun Keberangkatan" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div>
                <label for="destination" class="block text-gray-700 font-semibold mb-2">Tujuan</label>
                <input type="text" id="destination" name="destination" placeholder="Stasiun Tujuan" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="date" class="block text-gray-700 font-semibold mb-2">Tanggal Keberangkatan</label>
                <input type="date" id="date" name="date" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div>
                <label for="passengers" class="block text-gray-700 font-semibold mb-2">Jumlah Penumpang</label>
                <input type="number" id="passengers" name="passengers" min="1" value="1" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-md hover:bg-blue-700 transition duration-300">
                Cari Tiket
            </button>
        </div>
    </form>
</section>

<section id="results-section" class="mt-8 hidden">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Hasil Pencarian</h2>
    <div id="results-container" class="space-y-4">
        </div>
</section>

@endsection
