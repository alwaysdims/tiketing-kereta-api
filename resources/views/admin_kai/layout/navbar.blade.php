<!-- Navbar -->
<nav class="bg-white shadow-md fixed w-full top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Judul Tiketing Kereta Api -->
            <div class="flex items-center">
                <div class="flex-shrink-0 flex items-center">
                    <i class="fas fa-train text-primary text-2xl mr-2 text-blue-600"></i>
                    <span class="font-bold text-xl text-gray-800">Tiketing Kereta Api</span>
                </div>
            </div>

            <!-- Navigation Items (Tengah) desktop -->
            <div class="hidden md:flex items-center space-x-4">
                <a href="{{ route('admin.dashboard') }}"
                   class="px-3 py-2 rounded-md text-sm font-medium transition
                   {{ request()->routeIs('admin.dashboard')
                        ? 'bg-blue-600 text-white'
                        : 'text-gray-700 hover:bg-gray-100 hover:text-blue-600' }}">
                   Dashboard
                </a>

                <a href="{{ route('admin.kereta') }}"
                   class="px-3 py-2 rounded-md text-sm font-medium transition
                   {{ request()->routeIs('admin.kereta')
                        ? 'bg-blue-600 text-white'
                        : 'text-gray-700 hover:bg-gray-100 hover:text-blue-600' }}">
                   Kereta
                </a>

                <!-- Dropdown Users -->
                <div class="relative group">
                    <button
                        class="px-3 py-2 rounded-md text-sm font-medium transition flex items-center
                        {{ request()->routeIs('admin.users.*')
                            ? 'bg-blue-600 text-white'
                            : 'text-gray-700 hover:bg-gray-100 hover:text-blue-600' }}"
                        aria-haspopup="true" aria-expanded="false" id="usersDropdownButton">
                        Data Users
                        <i class="fas fa-chevron-down ml-1 text-xs"></i>
                    </button>
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 hidden group-hover:block"
                        role="menu" aria-orientation="vertical" aria-labelledby="usersDropdownButton">
                        <a href="{{ route('admin.users.admin.index') }}"
                           class="block px-4 py-2 text-sm transition
                           {{ request()->routeIs('admin.users.admin.index')
                                ? 'bg-blue-600 text-white'
                                : 'text-gray-700 hover:bg-gray-100' }}"
                           role="menuitem">Admin</a>

                        <a href="{{ route('admin.users.petugas') }}"
                           class="block px-4 py-2 text-sm transition
                           {{ request()->routeIs('admin.users.petugas')
                                ? 'bg-blue-600 text-white'
                                : 'text-gray-700 hover:bg-gray-100' }}"
                           role="menuitem">Petugas</a>

                        <a href="{{ route('admin.users.penumpang.index') }}"
                           class="block px-4 py-2 text-sm transition
                           {{ request()->routeIs('admin.users.penumpang.index')
                                ? 'bg-blue-600 text-white'
                                : 'text-gray-700 hover:bg-gray-100' }}"
                           role="menuitem">Penumpang</a>
                    </div>
                </div>

                <a href="{{ route('admin.jadwal') }}"
                   class="px-3 py-2 rounded-md text-sm font-medium transition
                   {{ request()->routeIs('admin.jadwal')
                        ? 'bg-blue-600 text-white'
                        : 'text-gray-700 hover:bg-gray-100 hover:text-blue-600' }}">
                   Jadwal Kereta
                </a>

                <a href="{{ route('admin.gerbong') }}"
                   class="px-3 py-2 rounded-md text-sm font-medium transition
                   {{ request()->routeIs('admin.gerbong')
                        ? 'bg-blue-600 text-white'
                        : 'text-gray-700 hover:bg-gray-100 hover:text-blue-600' }}">
                   Gerbong
                </a>

                <a href="{{ route('admin.stasiun') }}"
                   class="px-3 py-2 rounded-md text-sm font-medium transition
                   {{ request()->routeIs('admin.stasiun')
                        ? 'bg-blue-600 text-white'
                        : 'text-gray-700 hover:bg-gray-100 hover:text-blue-600' }}">
                   Stasiun
                </a>
            </div>

            <!-- Profile Section (Kanan) desktop only -->
            <div class="hidden md:flex items-center relative" id="profileDropdownWrapper">
                <button id="profileDropdownButton"
                    class="flex items-center space-x-2 text-sm rounded-full focus:outline-none" aria-haspopup="true"
                    aria-expanded="false" type="button">
                    <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center">
                        <i class="fas fa-user text-white text-sm"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-medium text-gray-700">Admin Name</p>
                        <p class="text-xs text-gray-500">Administrator</p>
                    </div>
                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                </button>

                <!-- Dropdown Menu -->
                <div id="profileDropdownMenu"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 hidden z-50 dropdown-transition"
                    role="menu" aria-orientation="vertical" aria-labelledby="profileDropdownButton" style="top: 100%;">
                    <a href="{{ route('admin.settings') }}"
                        class="block px-4 py-2 text-sm transition
                        {{ request()->routeIs('admin.settings')
                            ? 'bg-blue-600 text-white'
                            : 'text-gray-700 hover:bg-gray-100' }}"
                        role="menuitem">
                        <i class="fas fa-cog mr-2"></i> Pengaturan
                    </a>
                    <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        role="menuitem"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button id="mobileMenuButton"
                    class="p-2 rounded-md text-gray-700 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-600"
                    aria-label="Open main menu" aria-expanded="false" type="button">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="fixed inset-0 bg-gray-800 bg-opacity-75 z-40 hidden">
        <div class="fixed right-0 top-0 h-full w-64 bg-white shadow-lg overflow-y-auto">
            <div class="p-4 border-b flex items-center justify-between">
                <span class="font-bold text-lg">Menu</span>
                <button id="closeMobileMenu" class="p-2 text-gray-700 hover:text-blue-600 focus:outline-none"
                    aria-label="Close main menu" type="button">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <nav class="p-4 space-y-2" aria-label="Mobile main menu">
                <a href="{{ route('admin.dashboard') }}"
                   class="block py-2 px-4 rounded transition
                   {{ request()->routeIs('admin.dashboard')
                        ? 'bg-blue-600 text-white'
                        : 'text-gray-700 hover:bg-gray-100' }}">
                   Dashboard
                </a>

                <a href="{{ route('admin.kereta') }}"
                   class="block py-2 px-4 rounded transition
                   {{ request()->routeIs('admin.kereta')
                        ? 'bg-blue-600 text-white'
                        : 'text-gray-700 hover:bg-gray-100' }}">
                   Kereta
                </a>

                <!-- Users dropdown in mobile menu -->
                <details class="group">
                    <summary
                        class="flex justify-between items-center py-2 px-4 rounded cursor-pointer transition
                        {{ request()->routeIs('admin.users.*')
                            ? 'bg-blue-600 text-white'
                            : 'text-gray-700 hover:bg-gray-100' }}">
                        Data Users
                        <i class="fas fa-chevron-down transition-transform group-open:rotate-180"></i>
                    </summary>
                    <div class="pl-4 mt-1 space-y-1">
                        <a href="{{ route('admin.users.admin.index') }}"
                           class="block py-1 px-4 rounded transition
                           {{ request()->routeIs('admin.users.admin.index')
                                ? 'bg-blue-600 text-white'
                                : 'text-gray-600 hover:bg-gray-100' }}">
                           Admin
                        </a>
                        <a href="{{ route('admin.users.petugas') }}"
                           class="block py-1 px-4 rounded transition
                           {{ request()->routeIs('admin.users.petugas')
                                ? 'bg-blue-600 text-white'
                                : 'text-gray-600 hover:bg-gray-100' }}">
                           Petugas
                        </a>
                        <a href="{{ route('admin.users.penumpang.index') }}"
                           class="block py-1 px-4 rounded transition
                           {{ request()->routeIs('admin.users.penumpang.index')
                                ? 'bg-blue-600 text-white'
                                : 'text-gray-600 hover:bg-gray-100' }}">
                           Penumpang
                        </a>
                    </div>
                </details>

                <a href="{{ route('admin.jadwal') }}"
                   class="block py-2 px-4 rounded transition
                   {{ request()->routeIs('admin.jadwal')
                        ? 'bg-blue-600 text-white'
                        : 'text-gray-700 hover:bg-gray-100' }}">
                   Jadwal Kereta
                </a>

                <a href="{{ route('admin.gerbong') }}"
                   class="block py-2 px-4 rounded transition
                   {{ request()->routeIs('admin.gerbong')
                        ? 'bg-blue-600 text-white'
                        : 'text-gray-700 hover:bg-gray-100' }}">
                   Gerbong
                </a>

                <a href="{{ route('admin.stasiun') }}"
                   class="block py-2 px-4 rounded transition
                   {{ request()->routeIs('admin.stasiun')
                        ? 'bg-blue-600 text-white'
                        : 'text-gray-700 hover:bg-gray-100' }}">
                   Stasiun
                </a>

                <div class="border-t mt-4 pt-4">
                    <!-- Admin info -->
                    <div class="px-4 pb-3 border-b mb-3">
                        <p class="text-gray-800 font-semibold">Admin Name</p>
                        <p class="text-gray-500 text-sm">Administrator</p>
                    </div>
                    <!-- Profile menu items -->
                    <a href="{{ route('admin.settings') }}"
                       class="block py-2 px-4 rounded transition flex items-center
                       {{ request()->routeIs('admin.settings')
                            ? 'bg-blue-600 text-white'
                            : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-cog mr-2"></i> Pengaturan
                    </a>

                    <!-- Logout pakai form POST -->
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full text-left block py-2 px-4 rounded transition flex items-center text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </nav>
        </div>
    </div>
</nav>
