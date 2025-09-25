@extends('admin_kai.layout.main', ['title' => 'Data Gerbong'])

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between mb-6 items-center">
        <h1 class="text-2xl font-bold text-gray-800">Data Gerbong</h1>
        <button onclick="showModal('addModal')" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
            + Tambah Gerbong
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
                <input type="text" id="searchInput" placeholder="🔍 Cari gerbong..."
                       class="w-full pl-10 pr-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <select id="keretaFilter" class="w-full sm:w-48 border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Kereta</option>
                @foreach($keretas ?? [] as $kereta)
                    <option value="{{ $kereta->id }}">{{ $kereta->nama_kereta }}</option>
                @endforeach
            </select>
            <select id="kelasFilter" class="w-full sm:w-48 border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Kelas</option>
                <option value="ekonomi">Ekonomi</option>
                <option value="bisnis">Bisnis</option>
                <option value="eksekutif">Eksekutif</option>
                <option value="luxury">Luxury</option>
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
        <table class="w-full text-sm text-gray-700" id="tableGerbong">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">No</th>
                    <th class="px-4 py-3 text-left font-semibold">Kode Gerbong</th>
                    <th class="px-4 py-3 text-left font-semibold">No Gerbong</th>
                    <th class="px-4 py-3 text-left font-semibold">Nama Kereta</th>
                    <th class="px-4 py-3 text-left font-semibold">Jumlah Kursi</th>
                    <th class="px-4 py-3 text-left font-semibold">Kelas Gerbong</th>
                    <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($gerbongs as $i => $gerbong)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $gerbongs->firstItem() + $i + 1 }}</td>
                        <td class="px-4 py-3">{{ $gerbong->kode_gerbong }}</td>
                        <td class="px-4 py-3">{{ $gerbong->no_gerbong }}</td>
                        <td class="px-4 py-3">{{ $gerbong->kereta->nama_kereta ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $gerbong->jumlah_kursi }}</td>
                        <td class="px-4 py-3">{{ $gerbong->kelas_gerbong }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-2">
                                <!-- Detail -->
                                <button type="button" onclick='showDetail(@json($gerbong))' class="text-blue-500 hover:text-blue-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                <!-- Edit -->
                                <button type="button" onclick='showEdit(@json($gerbong))' class="text-green-500 hover:text-green-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </button>
                                <!-- Delete -->
                                <form action="{{ route('admin.gerbong.destroy', $gerbong->id) }}" method="POST" onsubmit="return confirm('Yakin hapus gerbong ini?')">
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
                        <td colspan="7" class="text-center py-4">Tidak ada data gerbong</td>
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
                <!-- Icon panah kiri -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <!-- Nomor halaman -->
            <div id="pageNumbers" class="flex space-x-1"></div>

            <!-- Tombol Next -->
            <button id="nextPage"
                class="px-3 py-1 border rounded-md bg-gray-100 hover:bg-gray-200 disabled:opacity-50 flex items-center justify-center">
                <!-- Icon panah kanan -->
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
            <h2 class="text-xl font-bold mb-4">Tambah Gerbong Baru</h2>
            <form method="POST" action="{{ route('admin.gerbong.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Nama Kereta *</label>
                    <select name="id_kereta" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('id_kereta') border-red-500 @enderror">
                        <option value="">Pilih Kereta</option>
                        @foreach($keretas ?? [] as $kereta)
                            <option value="{{ $kereta->id }}" {{ old('id_kereta') == $kereta->id ? 'selected' : '' }}>
                                {{ $kereta->nama_kereta }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_kereta')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Kode Gerbong *</label>
                    <input type="text" name="kode_gerbong" value="{{ old('kode_gerbong') }}"
                           class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('kode_gerbong') border-red-500 @enderror">
                    @error('kode_gerbong')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">No Gerbong *</label>
                    <input type="number" name="no_gerbong" value="{{ old('no_gerbong') }}"
                           class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('no_gerbong') border-red-500 @enderror">
                    @error('no_gerbong')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Jumlah Kursi *</label>
                    <input type="number" name="jumlah_kursi" value="{{ old('jumlah_kursi') }}"
                           class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jumlah_kursi') border-red-500 @enderror">
                    @error('jumlah_kursi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Kelas Gerbong *</label>
                    <select name="kelas_gerbong" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('kelas_gerbong') border-red-500 @enderror">
                        <option value="">Pilih Kelas</option>
                        <option value="ekonomi" {{ old('kelas_gerbong') == 'ekonomi' ? 'selected' : '' }}>Ekonomi</option>
                        <option value="bisnis" {{ old('kelas_gerbong') == 'bisnis' ? 'selected' : '' }}>Bisnis</option>
                        <option value="eksekutif" {{ old('kelas_gerbong') == 'eksekutif' ? 'selected' : '' }}>Eksekutif</option>
                        <option value="luxury" {{ old('kelas_gerbong') == 'luxury' ? 'selected' : '' }}>Luxury</option>
                    </select>
                    @error('kelas_gerbong')
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
            <h2 class="text-xl font-bold mb-4">Edit Gerbong</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editId">
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Nama Kereta *</label>
                    <select name="id_kereta" id="editIdKereta" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('id_kereta') border-red-500 @enderror">
                        <option value="">Pilih Kereta</option>
                        @foreach($keretas ?? [] as $kereta)
                            <option value="{{ $kereta->id }}">{{ $kereta->nama_kereta }}</option>
                        @endforeach
                    </select>
                    @error('id_kereta')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Kode Gerbong *</label>
                    <input type="text" name="kode_gerbong" id="editKodeGerbong"
                           class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('kode_gerbong') border-red-500 @enderror">
                    @error('kode_gerbong')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">No Gerbong *</label>
                    <input type="number" name="no_gerbong" id="editNoGerbong"
                           class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('no_gerbong') border-red-500 @enderror">
                    @error('no_gerbong')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Jumlah Kursi *</label>
                    <input type="number" name="jumlah_kursi" id="editJumlahKursi"
                           class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jumlah_kursi') border-red-500 @enderror">
                    @error('jumlah_kursi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Kelas Gerbong *</label>
                    <select name="kelas_gerbong" id="editKelasGerbong" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('kelas_gerbong') border-red-500 @enderror">
                        <option value="">Pilih Kelas</option>
                        <option value="ekonomi">Ekonomi</option>
                        <option value="bisnis">Bisnis</option>
                        <option value="eksekutif">Eksekutif</option>
                        <option value="luxury">Luxury</option>
                    </select>
                    @error('kelas_gerbong')
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
            <h2 class="text-2xl font-bold text-gray-800 mb-5">Detail Gerbong</h2>
            <div class="space-y-3 text-gray-700">
                <p><strong>Kode Gerbong:</strong> <span id="detailKodeGerbong" class="font-medium"></span></p>
                <p><strong>No Gerbong:</strong> <span id="detailNoGerbong" class="font-medium"></span></p>
                <p><strong>Nama Kereta:</strong> <span id="detailNamaKereta" class="font-medium"></span></p>
                <p><strong>Jumlah Kursi:</strong> <span id="detailJumlahKursi" class="font-medium"></span></p>
                <p><strong>Kelas Gerbong:</strong> <span id="detailKelasGerbong" class="font-medium"></span></p>
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
    const gerbongs = @json($gerbongs->items());
    const keretas = @json($keretas ?? []);
    let currentPage = 1;
    let pageSize = 5;
    let filteredData = [...gerbongs];

    // DOM Elements
    const tableBody = document.getElementById('tableBody');
    const searchInput = document.getElementById('searchInput');
    const keretaFilter = document.getElementById('keretaFilter');
    const kelasFilter = document.getElementById('kelasFilter');
    const pageLengthSelect = document.getElementById('pageLength');
    const prevPageBtn = document.getElementById('prevPage');
    const nextPageBtn = document.getElementById('nextPage');
    const pageNumbers = document.getElementById('paginationInfo');
    const pageNumbersContainer = document.getElementById('pageNumbers');

    // Apply Filters
    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedKereta = keretaFilter.value;
        const selectedKelas = kelasFilter.value;

        filteredData = gerbongs.filter(gerbong => {
            const kereta = keretas.find(k => k.id === gerbong.id_kereta);
            const matchesSearch = (
                gerbong.kode_gerbong.toLowerCase().includes(searchTerm) ||
                gerbong.no_gerbong.toString().includes(searchTerm) ||
                (kereta?.nama_kereta?.toLowerCase() || '').includes(searchTerm) ||
                gerbong.jumlah_kursi.toString().includes(searchTerm) ||
                gerbong.kelas_gerbong.toLowerCase().includes(searchTerm)
            );
            const matchesKereta = selectedKereta ? gerbong.id_kereta === parseInt(selectedKereta) : true;
            const matchesKelas = selectedKelas ? gerbong.kelas_gerbong.toLowerCase() === selectedKelas.toLowerCase() : true;
            return matchesSearch && matchesKereta && matchesKelas;
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
            tableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4">Tidak ada data gerbong</td></tr>`;
            return;
        }

        paginatedData.forEach((gerbong, index) => {
            const row = document.createElement('tr');
            row.className = 'border-b hover:bg-gray-50';
            const kereta = keretas.find(k => k.id === gerbong.id_kereta);
            row.innerHTML = `
                <td class="px-4 py-3">${start + index + 1}</td>
                <td class="px-4 py-3">${gerbong.kode_gerbong}</td>
                <td class="px-4 py-3">${gerbong.no_gerbong}</td>
                <td class="px-4 py-3">${kereta?.nama_kereta ?? '-'}</td>
                <td class="px-4 py-3">${gerbong.jumlah_kursi}</td>
                <td class="px-4 py-3">${gerbong.kelas_gerbong}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center space-x-2">
                        <button type="button" onclick='showDetail(${JSON.stringify(gerbong)})' class="text-blue-500 hover:text-blue-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                        <button type="button" onclick='showEdit(${JSON.stringify(gerbong)})' class="text-green-500 hover:text-green-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </button>
                        <form action="/admin/gerbong/${gerbong.id}" method="POST" onsubmit="return confirm('Yakin hapus gerbong ini?')">
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
        pageNumbers.innerText = `Menampilkan ${(page - 1) * size + 1} - ${Math.min(page * size, totalItems)} dari ${totalItems} data`;

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
    kelasFilter.addEventListener('change', applyFilters);

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

    function showEdit(gerbong) {
        document.getElementById('editId').value = gerbong.id;
        document.getElementById('editIdKereta').value = gerbong.id_kereta;
        document.getElementById('editKodeGerbong').value = gerbong.kode_gerbong;
        document.getElementById('editNoGerbong').value = gerbong.no_gerbong;
        document.getElementById('editJumlahKursi').value = gerbong.jumlah_kursi;
        document.getElementById('editKelasGerbong').value = gerbong.kelas_gerbong;
        document.getElementById('editForm').action = `/admin/gerbong/${gerbong.id}`;
        showModal('editModal');
    }

    function showDetail(gerbong) {
        document.getElementById('detailKodeGerbong').innerText = gerbong.kode_gerbong;
        document.getElementById('detailNoGerbong').innerText = gerbong.no_gerbong;
        document.getElementById('detailNamaKereta').innerText = gerbong.kereta?.nama_kereta ?? '-';
        document.getElementById('detailJumlahKursi').innerText = gerbong.jumlah_kursi;
        document.getElementById('detailKelasGerbong').innerText = gerbong.kelas_gerbong;
        showModal('detailModal');
    }

    // Initialize Table
    renderTable(filteredData, currentPage, pageSize);

    // Show modal if there are validation errors
    @if ($errors->has('id_kereta') || $errors->has('kode_gerbong') || $errors->has('no_gerbong') || $errors->has('jumlah_kursi') || $errors->has('kelas_gerbong'))
        @if (session('showAddModal'))
            showModal('addModal');
        @elseif (session('showEditModal'))
            showModal('editModal');
            @if (session('editGerbong'))
                showEdit(@json(session('editGerbong')));
            @endif
        @endif
    @endif
</script>
@endsection
