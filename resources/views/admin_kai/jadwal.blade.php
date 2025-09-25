@extends('admin_kai.layout.main', ['title' => 'Data Jadwal Kereta'])

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between mb-6 items-center">
        <h1 class="text-2xl font-bold text-gray-800">Data Jadwal Kereta</h1>
        <button onclick="showModal('addModal')" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
            + Tambah Jadwal
        </button>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search and Filter Controls -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
        <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <input type="text" id="searchInput" placeholder="🔍 Cari jadwal..."
                       class="w-full pl-10 pr-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <select id="keretaFilter" class="w-full sm:w-48 border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Kereta</option>
                @foreach($keretas ?? [] as $kereta)
                    <option value="{{ $kereta->id }}">{{ $kereta->kode_kereta }} ({{ $kereta->nama_kereta }})</option>
                @endforeach
            </select>
            <select id="stasiunAsalFilter" class="w-full sm:w-48 border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Stasiun Asal</option>
                @foreach($stasiuns ?? [] as $stasiun)
                    <option value="{{ $stasiun->id }}">{{ $stasiun->nama_stasiun }}</option>
                @endforeach
            </select>
            <select id="stasiunTujuanFilter" class="w-full sm:w-48 border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Stasiun Tujuan</option>
                @foreach($stasiuns ?? [] as $stasiun)
                    <option value="{{ $stasiun->id }}">{{ $stasiun->nama_stasiun }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-center gap-4">
            <select id="pageLength" class="border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="5">5 per halaman</option>
                <option value="10">10 per halaman</option>
                <option value="25">25 per halaman</option>
                <option value="50">50 per halaman</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full text-sm text-gray-700" id="tableJadwal">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">No</th>
                    <th class="px-4 py-3 text-left font-semibold">Kereta</th>
                    <th class="px-4 py-3 text-left font-semibold">Stasiun Asal</th>
                    <th class="px-4 py-3 text-left font-semibold">Stasiun Tujuan</th>
                    <th class="px-4 py-3 text-left font-semibold">Jam Keberangkatan</th>
                    <th class="px-4 py-3 text-left font-semibold">Jam Kedatangan</th>
                    <th class="px-4 py-3 text-left font-semibold">Harga</th>
                    <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($jadwals as $i => $jadwal)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $i + 1 }}</td>
                        <td class="px-4 py-3">{{ $jadwal->kereta->nama_kereta ?? '-' }} ({{ $jadwal->kereta->kode_kereta ?? '-' }})</td>
                        <td class="px-4 py-3">{{ $jadwal->stasiunAsal->nama_stasiun ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $jadwal->stasiunTujuan->nama_stasiun ?? '-' }}</td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($jadwal->jam_keberangkatan)->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($jadwal->jam_kedatangan)->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($jadwal->harga, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-2">
                                <!-- Detail -->
                                <button type="button" onclick='showDetail(@json($jadwal))' class="text-blue-500 hover:text-blue-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                <!-- Edit -->
                                <button type="button" onclick='showEdit(@json($jadwal))' class="text-green-500 hover:text-green-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </button>
                                <!-- Delete -->
                                <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Yakin hapus jadwal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">Tidak ada data jadwal kereta</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-between items-center mt-4">
        <div id="paginationInfo" class="text-sm text-gray-600"></div>
        <div class="flex space-x-2">
            <!-- Tombol Prev -->
            <button id="prevPage"
                class="px-3 py-1 border rounded-md bg-gray-100 hover:bg-gray-200 disabled:opacity-50 flex items-center justify-center"
                disabled>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <!-- Nomor halaman -->
            <div id="pageNumbers" class="flex space-x-1"></div>

            <!-- Tombol Next -->
            <button id="nextPage"
                class="px-3 py-1 border rounded-md bg-gray-100 hover:bg-gray-200 disabled:opacity-50 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Tambah Jadwal Baru</h2>
            <form method="POST" action="{{ route('admin.jadwal.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Kereta *</label>
                    <select name="id_kereta" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('id_kereta') border-red-500 @enderror">
                        <option value="">Pilih Kereta</option>
                        @foreach($keretas ?? [] as $kereta)
                            <option value="{{ $kereta->id }}" {{ old('id_kereta') == $kereta->id ? 'selected' : '' }}>
                                {{ $kereta->kode_kereta }} ({{ $kereta->nama_kereta }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_kereta')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Stasiun Asal *</label>
                    <select name="id_stasiun_asal" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('id_stasiun_asal') border-red-500 @enderror">
                        <option value="">Pilih Stasiun Asal</option>
                        @foreach($stasiuns ?? [] as $stasiun)
                            <option value="{{ $stasiun->id }}" {{ old('id_stasiun_asal') == $stasiun->id ? 'selected' : '' }}>
                                {{ $stasiun->nama_stasiun }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_stasiun_asal')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Stasiun Tujuan *</label>
                    <select name="id_stasiun_tujuan" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('id_stasiun_tujuan') border-red-500 @enderror">
                        <option value="">Pilih Stasiun Tujuan</option>
                        @foreach($stasiuns ?? [] as $stasiun)
                            <option value="{{ $stasiun->id }}" {{ old('id_stasiun_tujuan') == $stasiun->id ? 'selected' : '' }}>
                                {{ $stasiun->nama_stasiun }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_stasiun_tujuan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Jam Keberangkatan *</label>
                    <input type="datetime-local" name="jam_keberangkatan" value="{{ old('jam_keberangkatan') }}"
                           class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jam_keberangkatan') border-red-500 @enderror">
                    @error('jam_keberangkatan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Jam Kedatangan *</label>
                    <input type="datetime-local" name="jam_kedatangan" value="{{ old('jam_kedatangan') }}"
                           class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jam_kedatangan') border-red-500 @enderror">
                    @error('jam_kedatangan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Harga *</label>
                    <input type="number" name="harga" value="{{ old('harga') }}" min="0"
                           class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('harga') border-red-500 @enderror">
                    @error('harga')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" onclick="hideModal('addModal')" class="px-3 py-2 border rounded-md text-gray-600 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Edit Jadwal</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editId">
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Kereta *</label>
                    <select name="id_kereta" id="editIdKereta" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('id_kereta') border-red-500 @enderror">
                        <option value="">Pilih Kereta</option>
                        @foreach($keretas ?? [] as $kereta)
                            <option value="{{ $kereta->id }}">{{ $kereta->kode_kereta }} ({{ $kereta->nama_kereta }})</option>
                        @endforeach
                    </select>
                    @error('id_kereta')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Stasiun Asal *</label>
                    <select name="id_stasiun_asal" id="editIdStasiunAsal" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('id_stasiun_asal') border-red-500 @enderror">
                        <option value="">Pilih Stasiun Asal</option>
                        @foreach($stasiuns ?? [] as $stasiun)
                            <option value="{{ $stasiun->id }}">{{ $stasiun->nama_stasiun }}</option>
                        @endforeach
                    </select>
                    @error('id_stasiun_asal')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Stasiun Tujuan *</label>
                    <select name="id_stasiun_tujuan" id="editIdStasiunTujuan" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('id_stasiun_tujuan') border-red-500 @enderror">
                        <option value="">Pilih Stasiun Tujuan</option>
                        @foreach($stasiuns ?? [] as $stasiun)
                            <option value="{{ $stasiun->id }}">{{ $stasiun->nama_stasiun }}</option>
                        @endforeach
                    </select>
                    @error('id_stasiun_tujuan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Jam Keberangkatan *</label>
                    <input type="datetime-local" name="jam_keberangkatan" id="editJamKeberangkatan"
                           class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jam_keberangkatan') border-red-500 @enderror">
                    @error('jam_keberangkatan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Jam Kedatangan *</label>
                    <input type="datetime-local" name="jam_kedatangan" id="editJamKedatangan"
                           class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jam_kedatangan') border-red-500 @enderror">
                    @error('jam_kedatangan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Harga *</label>
                    <input type="number" name="harga" id="editHarga" min="0"
                           class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('harga') border-red-500 @enderror">
                    @error('harga')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" onclick="hideModal('editModal')" class="px-3 py-2 border rounded-md text-gray-600 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm md:max-w-md my-10 transform transition-all duration-300 scale-95 hover:scale-100 ease-out">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-5">Detail Jadwal Kereta</h2>
            <div class="space-y-3 text-gray-700">
                <p><strong>Kereta:</strong> <span id="detailKereta" class="font-medium"></span></p>
                <p><strong>Stasiun Asal:</strong> <span id="detailStasiunAsal" class="font-medium"></span></p>
                <p><strong>Stasiun Tujuan:</strong> <span id="detailStasiunTujuan" class="font-medium"></span></p>
                <p><strong>Jam Keberangkatan:</strong> <span id="detailJamKeberangkatan" class="font-medium"></span></p>
                <p><strong>Jam Kedatangan:</strong> <span id="detailJamKedatangan" class="font-medium"></span></p>
                <p><strong>Harga:</strong> <span id="detailHarga" class="font-medium"></span></p>
            </div>
            <div class="flex justify-end mt-6">
                <button type="button" onclick="hideModal('detailModal')"
                        class="px-5 py-2 text-sm font-semibold text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50 transition-colors duration-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Data for the table (extracted from Blade)
    const jadwals = @json($jadwals);
    const keretas = @json($keretas ?? []);
    const stasiuns = @json($stasiuns ?? []);
    let currentPage = 1;
    let pageSize = 5;
    let filteredData = [...jadwals];

    // DOM Elements
    const tableBody = document.getElementById('tableBody');
    const searchInput = document.getElementById('searchInput');
    const keretaFilter = document.getElementById('keretaFilter');
    const stasiunAsalFilter = document.getElementById('stasiunAsalFilter');
    const stasiunTujuanFilter = document.getElementById('stasiunTujuanFilter');
    const pageLengthSelect = document.getElementById('pageLength');
    const prevPageBtn = document.getElementById('prevPage');
    const nextPageBtn = document.getElementById('nextPage');
    const paginationInfo = document.getElementById('paginationInfo');
    const pageNumbersContainer = document.getElementById('pageNumbers');

    // Apply Filters
    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedKereta = keretaFilter.value;
        const selectedAsal = stasiunAsalFilter.value;
        const selectedTujuan = stasiunTujuanFilter.value;

        filteredData = jadwals.filter(jadwal => {
            const kereta = keretas.find(k => k.id === jadwal.id_kereta);
            const asal = stasiuns.find(s => s.id === jadwal.id_stasiun_asal);
            const tujuan = stasiuns.find(s => s.id === jadwal.id_stasiun_tujuan);
            const matchesSearch = (
                (kereta?.kode_kereta?.toLowerCase() || '').includes(searchTerm) ||
                (kereta?.nama_kereta?.toLowerCase() || '').includes(searchTerm) ||
                (asal?.nama_stasiun?.toLowerCase() || '').includes(searchTerm) ||
                (tujuan?.nama_stasiun?.toLowerCase() || '').includes(searchTerm) ||
                jadwal.jam_keberangkatan.toLowerCase().includes(searchTerm) ||
                jadwal.jam_kedatangan.toLowerCase().includes(searchTerm) ||
                jadwal.harga.toString().includes(searchTerm)
            );
            const matchesKereta = selectedKereta ? jadwal.id_kereta === parseInt(selectedKereta) : true;
            const matchesAsal = selectedAsal ? jadwal.id_stasiun_asal === parseInt(selectedAsal) : true;
            const matchesTujuan = selectedTujuan ? jadwal.id_stasiun_tujuan === parseInt(selectedTujuan) : true;
            return matchesSearch && matchesKereta && matchesAsal && matchesTujuan;
        });

        currentPage = 1;
        renderTable(filteredData, currentPage, pageSize);
    }

    // Render Table
    function renderTable(data, page, size) {
        const start = (page - 1) * size;
        const end = start + size;
        const paginatedData = data.slice(start, end);

        tableBody.innerHTML = '';
        if (paginatedData.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="8" class="text-center py-4">Tidak ada data jadwal kereta</td></tr>`;
            return;
        }

        paginatedData.forEach((jadwal, index) => {
            const row = document.createElement('tr');
            row.className = 'border-b hover:bg-gray-50';
            const kereta = keretas.find(k => k.id === jadwal.id_kereta);
            const asal = stasiuns.find(s => s.id === jadwal.id_stasiun_asal);
            const tujuan = stasiuns.find(s => s.id === jadwal.id_stasiun_tujuan);
            row.innerHTML = `
                <td class="px-4 py-3">${start + index + 1}</td>
                <td class="px-4 py-3">${kereta?.nama_kereta ?? '-'} (${kereta?.kode_kereta ?? '-'})</td>
                <td class="px-4 py-3">${asal?.nama_stasiun ?? '-'}</td>
                <td class="px-4 py-3">${tujuan?.nama_stasiun ?? '-'}</td>
                <td class="px-4 py-3">${new Date(jadwal.jam_keberangkatan).toLocaleString('id-ID', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' })}</td>
                <td class="px-4 py-3">${new Date(jadwal.jam_kedatangan).toLocaleString('id-ID', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' })}</td>
                <td class="px-4 py-3">Rp ${Number(jadwal.harga).toLocaleString('id-ID')}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center space-x-2">
                        <button type="button" onclick='showDetail(${JSON.stringify(jadwal)})' class="text-blue-500 hover:text-blue-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                        <button type="button" onclick='showEdit(${JSON.stringify(jadwal)})' class="text-green-500 hover:text-green-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </button>
                        <form action="/jadwal-kereta/${jadwal.id}" method="POST" onsubmit="return confirm('Yakin hapus jadwal ini?')">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.content}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="text-red-500 hover:text-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            `;
            tableBody.appendChild(row);
        });

        updatePagination(data.length, page, size);
    }

    // Update Pagination
    function updatePagination(totalItems, page, size) {
        const totalPages = Math.ceil(totalItems / size);
        pageNumbersContainer.innerHTML = '';
        paginationInfo.innerText = `Menampilkan ${(page - 1) * size + 1} - ${Math.min(page * size, totalItems)} dari ${totalItems} data`;

        // Generate page numbers
        const maxPagesToShow = 5;
        let startPage = Math.max(1, page - Math.floor(maxPagesToShow / 2));
        let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);
        if (endPage - startPage + 1 < maxPagesToShow) {
            startPage = Math.max(1, endPage - maxPagesToShow + 1);
        }

        for (let i = startPage; i <= endPage; i++) {
            const btn = document.createElement('button');
            btn.innerText = i;
            btn.className = `px-3 py-1 border rounded-md ${i === page ? 'bg-blue-600 text-white' : 'bg-gray-100 hover:bg-gray-200'}`;
            btn.onclick = () => {
                currentPage = i;
                renderTable(filteredData, currentPage, pageSize);
            };
            pageNumbersContainer.appendChild(btn);
        }

        prevPageBtn.disabled = page === 1;
        nextPageBtn.disabled = page === totalPages;
    }

    // Event Listeners for Filters and Search
    searchInput.addEventListener('input', applyFilters);
    keretaFilter.addEventListener('change', applyFilters);
    stasiunAsalFilter.addEventListener('change', applyFilters);
    stasiunTujuanFilter.addEventListener('change', applyFilters);

    // Page Size Change
    pageLengthSelect.addEventListener('change', () => {
        pageSize = parseInt(pageLengthSelect.value);
        currentPage = 1;
        renderTable(filteredData, currentPage, pageSize);
    });

    // Pagination Navigation
    prevPageBtn.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            renderTable(filteredData, currentPage, pageSize);
        }
    });

    nextPageBtn.addEventListener('click', () => {
        if (currentPage < Math.ceil(filteredData.length / pageSize)) {
            currentPage++;
            renderTable(filteredData, currentPage, pageSize);
        }
    });

    // Modal Functions
    function showModal(id) {
        const modal = document.getElementById(id);
        if (!modal) return;
        modal.classList.remove('hidden');
    }

    function hideModal(id) {
        const modal = document.getElementById(id);
        if (!modal) return;
        modal.classList.add('hidden');
    }

    function showEdit(jadwal) {
        document.getElementById('editId').value = jadwal.id;
        document.getElementById('editIdKereta').value = jadwal.id_kereta;
        document.getElementById('editIdStasiunAsal').value = jadwal.id_stasiun_asal;
        document.getElementById('editIdStasiunTujuan').value = jadwal.id_stasiun_tujuan;
        document.getElementById('editJamKeberangkatan').value = jadwal.jam_keberangkatan.replace(' ', 'T');
        document.getElementById('editJamKedatangan').value = jadwal.jam_kedatangan.replace(' ', 'T');
        document.getElementById('editHarga').value = jadwal.harga;document.getElementById('editForm').action = '{{ route("admin.jadwal.update", ":id") }}'.replace(':id', jadwal.id);
        showModal('editModal');
    }

    function showDetail(jadwal) {
        const kereta = keretas.find(k => k.id === jadwal.id_kereta);
        const asal = stasiuns.find(s => s.id === jadwal.id_stasiun_asal);
        const tujuan = stasiuns.find(s => s.id === jadwal.id_stasiun_tujuan);
        document.getElementById('detailKereta').innerText = `${kereta?.nama_kereta ?? '-'} (${kereta?.kode_kereta ?? '-'})`;
        document.getElementById('detailStasiunAsal').innerText = asal?.nama_stasiun ?? '-';
        document.getElementById('detailStasiunTujuan').innerText = tujuan?.nama_stasiun ?? '-';
        document.getElementById('detailJamKeberangkatan').innerText = new Date(jadwal.jam_keberangkatan).toLocaleString('id-ID', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' });
        document.getElementById('detailJamKedatangan').innerText = new Date(jadwal.jam_kedatangan).toLocaleString('id-ID', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' });
        document.getElementById('detailHarga').innerText = `Rp ${Number(jadwal.harga).toLocaleString('id-ID')}`;
        showModal('detailModal');
    }

    // Initialize Table
    renderTable(filteredData, currentPage, pageSize);

    // Show modal if there are validation errors
    @if ($errors->has('id_kereta') || $errors->has('id_stasiun_asal') || $errors->has('id_stasiun_tujuan') || $errors->has('jam_keberangkatan') || $errors->has('jam_kedatangan') || $errors->has('harga'))
        @if (session('showAddModal'))
            showModal('addModal');
        @elseif (session('showEditModal'))
            showModal('editModal');
            @if (session('editJadwal'))
                showEdit(@json(session('editJadwal')));
            @endif
        @endif
    @endif
</script>
@endsection
