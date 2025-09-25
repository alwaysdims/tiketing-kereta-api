@extends('admin_kai.layout.main', ['title' => 'Dashboard Admin'])

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Admin</h1>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-700">Total Users</h2>
            <p class="text-3xl font-bold text-blue-600">{{ $totalUsers }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-700">Completed Bookings</h2>
            <p class="text-3xl font-bold text-green-600">{{ $completedBookings }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-700">Total Trains</h2>
            <p class="text-3xl font-bold text-purple-600">{{ $totalTrains }}</p>
        </div>
    </div>

    <!-- Today's Schedules -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Today's Train Schedules</h2>

        <!-- Search and Filter Section -->
        <div class="mb-6 space-y-4 md:space-y-0 md:flex md:items-center md:justify-between">
            <!-- Search Input -->
            <div class="relative w-full md:w-64">
                <input type="text" id="searchInput" placeholder="Search train..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="flex flex-wrap gap-2">
                <!-- Origin Filter -->
                <select id="originFilter"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Origins</option>
                    @foreach($todaySchedules->unique('stasiun_asal_id') as $schedule)
                    <option value="{{ $schedule->stasiunAsal->nama_stasiun }}">
                        {{ $schedule->stasiunAsal->nama_stasiun }}</option>
                    @endforeach
                </select>

                <!-- Destination Filter -->
                <select id="destinationFilter"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Destinations</option>
                    @foreach($todaySchedules->unique('stasiun_tujuan_id') as $schedule)
                    <option value="{{ $schedule->stasiunTujuan->nama_stasiun }}">
                        {{ $schedule->stasiunTujuan->nama_stasiun }}</option>
                    @endforeach
                </select>

                <!-- Reset Filter Button -->
                <button id="resetFilter"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200">
                    Reset
                </button>
            </div>
        </div>

        @if($todaySchedules->isEmpty())
        <p class="text-gray-500">No schedules available for today.</p>
        @else
        <!-- Table Container -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-2 px-4 border-b text-left cursor-pointer sortable" data-sort="train">
                            Train
                            <span class="sort-icon">↕️</span>
                        </th>
                        <th class="py-2 px-4 border-b text-left cursor-pointer sortable" data-sort="origin">
                            Origin
                            <span class="sort-icon">↕️</span>
                        </th>
                        <th class="py-2 px-4 border-b text-left cursor-pointer sortable" data-sort="destination">
                            Destination
                            <span class="sort-icon">↕️</span>
                        </th>
                        <th class="py-2 px-4 border-b text-left cursor-pointer sortable" data-sort="departure">
                            Departure
                            <span class="sort-icon">↕️</span>
                        </th>
                        <th class="py-2 px-4 border-b text-left cursor-pointer sortable" data-sort="arrival">
                            Arrival
                            <span class="sort-icon">↕️</span>
                        </th>
                        <th class="py-2 px-4 border-b text-left cursor-pointer sortable" data-sort="price">
                            Price
                            <span class="sort-icon">↕️</span>
                        </th>
                    </tr>
                </thead>
                <tbody id="scheduleTableBody">
                    @foreach($todaySchedules as $schedule)
                    <tr class="schedule-row" data-train="{{ $schedule->kereta->nama_kereta }}"
                        data-origin="{{ $schedule->stasiunAsal->nama_stasiun }}"
                        data-destination="{{ $schedule->stasiunTujuan->nama_stasiun }}"
                        data-departure="{{ \Carbon\Carbon::parse($schedule->jam_keberangkatan)->format('H:i') }}"
                        data-arrival="{{ \Carbon\Carbon::parse($schedule->jam_kedatangan)->format('H:i') }}"
                        data-price="{{ $schedule->harga }}">
                        <td class="py-2 px-4 border-b">{{ $schedule->kereta->nama_kereta }}</td>
                        <td class="py-2 px-4 border-b">{{ $schedule->stasiunAsal->nama_stasiun }}</td>
                        <td class="py-2 px-4 border-b">{{ $schedule->stasiunTujuan->nama_stasiun }}</td>
                        <td class="py-2 px-4 border-b">
                            {{ \Carbon\Carbon::parse($schedule->jam_keberangkatan)->format('H:i') }}</td>
                        <td class="py-2 px-4 border-b">
                            {{ \Carbon\Carbon::parse($schedule->jam_kedatangan)->format('H:i') }}</td>
                        <td class="py-2 px-4 border-b">Rp {{ number_format($schedule->harga, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Showing <span id="showingStart">1</span> to <span id="showingEnd">10</span> of <span
                    id="totalItems">{{ $todaySchedules->count() }}</span> results
            </div>
            <div class="flex space-x-2">
                <button id="prevPage"
                    class="px-3 py-1 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    Previous
                </button>
                <div id="paginationNumbers" class="flex space-x-1">
                    <!-- Pagination numbers will be generated here -->
                </div>
                <button id="nextPage"
                    class="px-3 py-1 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    Next
                </button>
            </div>
        </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Configuration
            const itemsPerPage = 10;
            let currentPage = 1;
            let currentSort = {
                column: null,
                direction: 'asc'
            };
            let allSchedules = Array.from(document.querySelectorAll('.schedule-row'));
            let filteredSchedules = [...allSchedules];

            // DOM Elements
            const searchInput = document.getElementById('searchInput');
            const originFilter = document.getElementById('originFilter');
            const destinationFilter = document.getElementById('destinationFilter');
            const resetFilter = document.getElementById('resetFilter');
            const scheduleTableBody = document.getElementById('scheduleTableBody');
            const prevPage = document.getElementById('prevPage');
            const nextPage = document.getElementById('nextPage');
            const paginationNumbers = document.getElementById('paginationNumbers');
            const showingStart = document.getElementById('showingStart');
            const showingEnd = document.getElementById('showingEnd');
            const totalItems = document.getElementById('totalItems');
            const sortableHeaders = document.querySelectorAll('.sortable');

            // Initialize
            updatePagination();
            renderTable();

            // Event Listeners
            searchInput.addEventListener('input', filterSchedules);
            originFilter.addEventListener('change', filterSchedules);
            destinationFilter.addEventListener('change', filterSchedules);
            resetFilter.addEventListener('click', resetFilters);
            prevPage.addEventListener('click', goToPrevPage);
            nextPage.addEventListener('click', goToNextPage);

            // Sort functionality
            sortableHeaders.forEach(header => {
                header.addEventListener('click', () => {
                    const column = header.dataset.sort;
                    if (currentSort.column === column) {
                        currentSort.direction = currentSort.direction === 'asc' ? 'desc' :
                        'asc';
                    } else {
                        currentSort.column = column;
                        currentSort.direction = 'asc';
                    }
                    sortSchedules();
                    renderTable();
                    updateSortIcons();
                });
            });

            function filterSchedules() {
                const searchTerm = searchInput.value.toLowerCase();
                const originValue = originFilter.value;
                const destinationValue = destinationFilter.value;

                filteredSchedules = allSchedules.filter(row => {
                    const train = row.dataset.train.toLowerCase();
                    const origin = row.dataset.origin;
                    const destination = row.dataset.destination;
                    const departure = row.dataset.departure;
                    const arrival = row.dataset.arrival;
                    const price = row.dataset.price;

                    // Search filter
                    const matchesSearch = train.includes(searchTerm) ||
                        origin.toLowerCase().includes(searchTerm) ||
                        destination.toLowerCase().includes(searchTerm);

                    // Origin filter
                    const matchesOrigin = !originValue || origin === originValue;

                    // Destination filter
                    const matchesDestination = !destinationValue || destination === destinationValue;

                    return matchesSearch && matchesOrigin && matchesDestination;
                });

                currentPage = 1;
                updatePagination();
                renderTable();
            }

            function resetFilters() {
                searchInput.value = '';
                originFilter.value = '';
                destinationFilter.value = '';
                filterSchedules();
            }

            function sortSchedules() {
                if (!currentSort.column) return;

                filteredSchedules.sort((a, b) => {
                    let aValue, bValue;

                    switch (currentSort.column) {
                        case 'train':
                            aValue = a.dataset.train;
                            bValue = b.dataset.train;
                            break;
                        case 'origin':
                            aValue = a.dataset.origin;
                            bValue = b.dataset.origin;
                            break;
                        case 'destination':
                            aValue = a.dataset.destination;
                            bValue = b.dataset.destination;
                            break;
                        case 'departure':
                            aValue = a.dataset.departure;
                            bValue = b.dataset.departure;
                            break;
                        case 'arrival':
                            aValue = a.dataset.arrival;
                            bValue = b.dataset.arrival;
                            break;
                        case 'price':
                            aValue = parseFloat(a.dataset.price);
                            bValue = parseFloat(b.dataset.price);
                            break;
                        default:
                            return 0;
                    }

                    if (currentSort.direction === 'asc') {
                        return aValue > bValue ? 1 : -1;
                    } else {
                        return aValue < bValue ? 1 : -1;
                    }
                });
            }

            function updateSortIcons() {
                sortableHeaders.forEach(header => {
                    const icon = header.querySelector('.sort-icon');
                    if (header.dataset.sort === currentSort.column) {
                        icon.textContent = currentSort.direction === 'asc' ? '↑' : '↓';
                    } else {
                        icon.textContent = '↕️';
                    }
                });
            }

            function renderTable() {
                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;
                const currentItems = filteredSchedules.slice(startIndex, endIndex);

                scheduleTableBody.innerHTML = '';
                currentItems.forEach(row => {
                    scheduleTableBody.appendChild(row.cloneNode(true));
                });

                updateShowingInfo();
            }

            function updatePagination() {
                const totalPages = Math.ceil(filteredSchedules.length / itemsPerPage);

                // Update button states
                prevPage.disabled = currentPage === 1;
                nextPage.disabled = currentPage === totalPages || totalPages === 0;

                // Generate pagination numbers
                paginationNumbers.innerHTML = '';
                const maxVisiblePages = 5;
                let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
                let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

                if (endPage - startPage + 1 < maxVisiblePages) {
                    startPage = Math.max(1, endPage - maxVisiblePages + 1);
                }

                for (let i = startPage; i <= endPage; i++) {
                    const pageButton = document.createElement('button');
                    pageButton.className = `px-3 py-1 rounded-lg ${
                i === currentPage
                    ? 'bg-blue-500 text-white'
                    : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
            }`;
                    pageButton.textContent = i;
                    pageButton.addEventListener('click', () => {
                        currentPage = i;
                        updatePagination();
                        renderTable();
                    });
                    paginationNumbers.appendChild(pageButton);
                }

                totalItems.textContent = filteredSchedules.length;
            }

            function updateShowingInfo() {
                const start = ((currentPage - 1) * itemsPerPage) + 1;
                const end = Math.min(currentPage * itemsPerPage, filteredSchedules.length);

                showingStart.textContent = filteredSchedules.length === 0 ? 0 : start;
                showingEnd.textContent = end;
            }

            function goToPrevPage() {
                if (currentPage > 1) {
                    currentPage--;
                    updatePagination();
                    renderTable();
                }
            }

            function goToNextPage() {
                const totalPages = Math.ceil(filteredSchedules.length / itemsPerPage);
                if (currentPage < totalPages) {
                    currentPage++;
                    updatePagination();
                    renderTable();
                }
            }
        });

    </script>

    <style>
        .sortable:hover {
            background-color: #f3f4f6;
        }

        .sort-icon {
            margin-left: 4px;
            font-size: 12px;
        }

    </style>
</div>
@endsection
