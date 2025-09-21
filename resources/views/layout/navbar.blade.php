
<nav class="bg-white shadow-md fixed w-full top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center">
                <div class="flex-shrink-0 flex items-center">
                    <i class="fas fa-train text-primary text-2xl mr-2 text-blue-600"></i>
                    <span class="font-bold text-xl text-gray-800">Tiketing Kereta Api</span>
                </div>
            </div>

            <div class="hidden md:flex items-center space-x-4">
                <a href="{{ '/' }}" class="px-3 py-2 rounded-md text-sm font-medium transition text-gray-700 hover:bg-gray-100 hover:text-blue-600" id="nav-beranda">
                    Beranda
                </a>
                <a href="{{ '/pesantiket' }}" class="px-3 py-2 rounded-md text-sm font-medium transition text-gray-700 hover:bg-gray-100 hover:text-blue-600" id="nav-pesan-tiket">
                    Pesan Tiket
                </a>
                <a href="{{ '/bantuan' }}" class="px-3 py-2 rounded-md text-sm font-medium transition text-gray-700 hover:bg-gray-100 hover:text-blue-600">
                    Bantuan
                </a>
            </div>

            <div class="hidden md:flex items-center relative" id="profileDropdownWrapper">
                <button id="profileDropdownButton"
                    class="flex items-center space-x-2 text-sm rounded-full focus:outline-none" aria-haspopup="true"
                    aria-expanded="false" type="button">
                    <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center">
                        <i class="fas fa-user text-white text-sm"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-medium text-gray-700">Penumpang</p>
                        <p class="text-xs text-gray-500">Guest</p>
                    </div>
                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                </button>

                <div id="profileDropdownMenu"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 hidden z-50 dropdown-transition"
                    role="menu" aria-orientation="vertical" aria-labelledby="profileDropdownButton" style="top: 100%;">
                    <a href="#"
                        class="block px-4 py-2 text-sm transition text-gray-700 hover:bg-gray-100"
                        role="menuitem">
                        <i class="fas fa-user-circle mr-2"></i> Profil
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm transition text-gray-700 hover:bg-gray-100"
                        role="menuitem">
                        <i class="fas fa-ticket-alt mr-2"></i> Riwayat Pesanan
                    </a>
                    <a href="{{ 'auth/login' }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        role="menuitem">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login
                    </a>
                </div>
            </div>

            <div class="md:hidden flex items-center">
                <button id="mobileMenuButton"
                    class="p-2 rounded-md text-gray-700 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-600"
                    aria-label="Open main menu" aria-expanded="false" type="button">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>

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
                <a href="#beranda" class="block py-2 px-4 rounded transition text-gray-700 hover:bg-gray-100">
                    Beranda
                </a>
                <a href="#pesan-tiket" class="block py-2 px-4 rounded transition text-gray-700 hover:bg-gray-100">
                    Pesan Tiket
                </a>
                <a href="#bantuan" class="block py-2 px-4 rounded transition text-gray-700 hover:bg-gray-100">
                    Bantuan
                </a>
                <div class="border-t mt-4 pt-4">
                     <div class="px-4 pb-3 border-b mb-3">
                        <p class="text-gray-800 font-semibold">Penumpang</p>
                        <p class="text-gray-500 text-sm">Guest</p>
                    </div>
                    <a href="#" class="block py-2 px-4 rounded transition flex items-center text-gray-700 hover:bg-gray-100">
                         <i class="fas fa-user-circle mr-2"></i> Profil
                    </a>
                    <a href="#" class="block py-2 px-4 rounded transition flex items-center text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-ticket-alt mr-2"></i> Riwayat Pesanan
                    </a>
                    <a href="{{ 'auth/login' }}" class="block py-2 px-4 rounded transition flex items-center text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login
                    </a>
                </div>
            </nav>
        </div>
    </div>
</nav>
