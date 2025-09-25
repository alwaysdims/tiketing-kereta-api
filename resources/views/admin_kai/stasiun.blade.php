@extends('admin_kai.layout.main', ['title' => 'Data Stasiun'])

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between mb-6 items-center">
        <h1 class="text-2xl font-bold text-gray-800">Data Stasiun</h1>
        <button onclick="showModal('addModal')" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
            + Tambah Stasiun
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
                <input type="text" id="searchInput" placeholder="🔍 Cari stasiun..."
                       class="w-full pl-10 pr-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <select id="namaStasiunFilter" class="w-full sm:w-48 border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Nama Stasiun</option>
                @foreach($stasiuns->pluck('nama_stasiun')->unique() as $nama_stasiun)
                    <option value="{{ $nama_stasiun }}">{{ $nama_stasiun }}</option>
                @endforeach
            </select>
            <select id="kotaFilter" class="w-full sm:w-48 border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Kota</option>
                @foreach($stasiuns->pluck('kota')->unique() as $kota)
                    <option value="{{ $kota }}">{{ $kota }}</option>
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
        <table class="w-full text-sm text-gray-700" id="tableStasiun">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">No</th>
                    <th class="px-4 py-3 text-left font-semibold">Kode Stasiun</th>
                    <th class="px-4 py-3 text-left font-semibold">Nama Stasiun</th>
                    <th class="px-4 py-3 text-left font-semibold">Kota</th>
                    <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($stasiuns as $i => $stasiun)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $stasiuns->firstItem() + $i }}</td>
                        <td class="px-4 py-3">{{ $stasiun->kode_stasiun }}</td>
                        <td class="px-4 py-3">{{ $stasiun->nama_stasiun }}</td>
                        <td class="px-4 py-3">{{ $stasiun->kota }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-2">
                                <!-- Detail -->
                                <button type="button" onclick='showDetail(@json($stasiun))' class="text-blue-500 hover:text-blue-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                <!-- Edit -->
                                <button type="button" onclick='showEdit(@json($stasiun))' class="text-green-500 hover:text-green-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </button>
                                <!-- Delete -->
                                <form action="{{ route('admin.stasiun.destroy', $stasiun->id) }}" method="POST" onsubmit="return confirm('Yakin hapus stasiun ini?')">
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
                        <td colspan="5" class="text-center py-4">Tidak ada data stasiun</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-between items-center mt-4">
        <div id="paginationInfo" class="text-sm text-gray-600"></div>
        <div class="flex space-x-2">
            <button id="prevPage" class="px-3 py-1 border rounded-md bg-gray-100 hover:bg-gray-200 disabled:opacity-50" disabled>‹ Sebelumnya</button>
            <div id="pageNumbers" class="flex space-x-1"></div>
            <button id="nextPage" class="px-3 py-1 border rounded-md bg-gray-100 hover:bg-gray-200 disabled:opacity-50">Berikutnya ›</button>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Tambah Stasiun Baru</h2>
            <form method="POST" action="{{ route('admin.stasiun.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Kode Stasiun *</label>
                    <input type="text" name="kode_stasiun" value="{{ old('kode_stasiun') }}"
                           class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('kode_stasiun') border-red-500 @enderror">
                    @error('kode_stasiun')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Nama Stasiun *</label>
                    <input type="text" name="nama_stasiun" value="{{ old('nama_stasiun') }}"
                           class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_stasiun') border-red-500 @enderror">
                    @error('nama_stasiun')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Kota *</label>
                    <input type="text" name="kota" value="{{ old('kota') }}"
                           class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('kota') border-red-500 @enderror">
                    @error('kota')
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
            <h2 class="text-xl font-bold mb-4">Edit Stasiun</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editId">
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Kode Stasiun *</label>
                    <input type="text" name="kode_stasiun" id="editKodeStasiun"
                           class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('kode_stasiun') border-red-500 @enderror">
                    @error('kode_stasiun')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Nama Stasiun *</label>
                    <input type="text" name="nama_stasiun" id="editNamaStasiun"
                           class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_stasiun') border-red-500 @enderror">
                    @error('nama_stasiun')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Kota *</label>
                    <input type="text" name="kota" id="editKota"
                           class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('kota') border-red-500 @enderror">
                    @error('kota')
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
            <h2 class="text-2xl font-bold text-gray-800 mb-5">Detail Stasiun</h2>
            <div class="space-y-3 text-gray-700">
                <p><strong>Kode Stasiun:</strong> <span id="detailKodeStasiun" class="font-medium"></span></p>
                <p><strong>Nama Stasiun:</strong> <span id="detailNamaStasiun" class="font-medium"></span></p>
                <p><strong>Kota:</strong> <span id="detailKota" class="font-medium"></span></p>
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
    const stasiuns = @json($stasiuns->items());
    let currentPage = 1;
    let pageSize = 5;
    let filteredData = [...stasiuns];

    // DOM Elements
    const tableBody = document.getElementById('tableBody');
    const searchInput = document.getElementById('searchInput');
    const namaStasiunFilter = document.getElementById('namaStasiunFilter');
    const kotaFilter = document.getElementById('kotaFilter');
    const pageLengthSelect = document.getElementById('pageLength');
    const prevPageBtn = document.getElementById('prevPage');
    const nextPageBtn = document.getElementById('nextPage');
    const pageNumbers = document.getElementById('paginationInfo');
    const pageNumbersContainer = document.getElementById('pageNumbers');

    // Apply Filters
    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedNamaStasiun = namaStasiunFilter.value;
        const selectedKota = kotaFilter.value;

        filteredData = stasiuns.filter(stasiun => {
            const matchesSearch = (
                stasiun.kode_stasiun.toLowerCase().includes(searchTerm) ||
                stasiun.nama_stasiun.toLowerCase().includes(searchTerm) ||
                stasiun.kota.toLowerCase().includes(searchTerm)
            );
            const matchesNamaStasiun = selectedNamaStasiun ? stasiun.nama_stasiun === selectedNamaStasiun : true;
            const matchesKota = selectedKota ? stasiun.kota === selectedKota : true;
            return matchesSearch && matchesNamaStasiun && matchesKota;
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
            tableBody.innerHTML = `<tr><td colspan="5" class="text-center py-4">Tidak ada data stasiun</td></tr>`;
            return;
        }

        paginatedData.forEach((stasiun, index) => {
            const row = document.createElement('tr');
            row.className = 'border-b hover:bg-gray-50';
            row.innerHTML = `
                <td class="px-4 py-3">${start + index + 1}</td>
                <td class="px-4 py-3">${stasiun.kode_stasiun}</td>
                <td class="px-4 py-3">${stasiun.nama_stasiun}</td>
                <td class="px-4 py-3">${stasiun.kota}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center space-x-2">
                        <button type="button" onclick='showDetail(${JSON.stringify(stasiun)})' class="text-blue-500 hover:text-blue-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                        <button type="button" onclick='showEdit(${JSON.stringify(stasiun)})' class="text-green-500 hover:text-green-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </button>
                        <form action="/admin/stasiun/${stasiun.id}" method="POST" onsubmit="return confirm('Yakin hapus stasiun ini?')">
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
    namaStasiunFilter.addEventListener('change', applyFilters);
    kotaFilter.addEventListener('change', applyFilters);

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

    function showEdit(stasiun) {
        document.getElementById('editId').value = stasiun.id;
        document.getElementById('editKodeStasiun').value = stasiun.kode_stasiun;
        document.getElementById('editNamaStasiun').value = stasiun.nama_stasiun;
        document.getElementById('editKota').value = stasiun.kota;
        document.getElementById('editForm').action = `/admin/stasiun/${stasiun.id}`;
        showModal('editModal');
    }

    function showDetail(stasiun) {
        document.getElementById('detailKodeStasiun').innerText = stasiun.kode_stasiun;
        document.getElementById('detailNamaStasiun').innerText = stasiun.nama_stasiun;
        document.getElementById('detailKota').innerText = stasiun.kota;
        showModal('detailModal');
    }

    // Initialize Table
    renderTable(filteredData, currentPage, pageSize);

    // Show modal if there are validation errors
    @if ($errors->has('kode_stasiun') || $errors->has('nama_stasiun') || $errors->has('kota'))
        @if (session('showAddModal'))
            showModal('addModal');
        @elseif (session('showEditModal'))
            showModal('editModal');
            @if (session('editStasiun'))
                showEdit(@json(session('editStasiun')));
            @endif
        @endif
    @endif
</script>
@endsection
