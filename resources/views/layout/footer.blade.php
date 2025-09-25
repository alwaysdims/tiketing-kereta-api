<footer class="bg-blue-50 text-gray-700 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="flex flex-col items-start">
            <h4 class="text-2xl font-bold text-blue-800 mb-2">Tiketing Kereta</h4>
            <p class="text-sm">Pesan tiket kereta api dengan mudah, cepat, dan aman.</p>
            <div class="flex mt-4 space-x-4">
                <a href="#" class="text-gray-500 hover:text-blue-600 transition duration-300">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">...</svg>
                </a>
                <a href="#" class="text-gray-500 hover:text-blue-600 transition duration-300">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">...</svg>
                </a>
            </div>
        </div>

        <div>
            <h5 class="text-lg font-semibold text-blue-800 mb-4">Tautan Cepat</h5>
            <ul class="space-y-2">
                <li><a href="#" class="hover:text-blue-600 transition duration-300">Beranda</a></li>
                <li><a href="#" class="hover:text-blue-600 transition duration-300">Pesan Tiket</a></li>
                <li><a href="#" class="hover:text-blue-600 transition duration-300">Jadwal Kereta</a></li>
                <li><a href="#" class="hover:text-blue-600 transition duration-300">Bantuan</a></li>
            </ul>
        </div>

        <div>
            <h5 class="text-lg font-semibold text-blue-800 mb-4">Hubungi Kami</h5>
            <p class="text-sm">Email: info@tiketingkereta.com</p>
            <p class="text-sm mt-1">Telepon: +62 812 3456 7890</p>
            <p class="text-sm mt-6 text-gray-500">&copy; 2024 Tiketing Kereta Api. All Rights Reserved.</p>
        </div>
    </div>
</footer>

{{-- <script src="script.js"></script> --}}
<script>
    // JavaScript untuk Navbar
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    const closeMobileMenu = document.getElementById('closeMobileMenu');

    mobileMenuButton.addEventListener('click', () => {
        mobileMenu.classList.remove('hidden');
    });

    closeMobileMenu.addEventListener('click', () => {
        mobileMenu.classList.add('hidden');
    });

    // Dropdown Profil Desktop
    const profileDropdownButton = document.getElementById('profileDropdownButton');
    const profileDropdownMenu = document.getElementById('profileDropdownMenu');

    if (profileDropdownButton) {
        profileDropdownButton.addEventListener('click', () => {
            profileDropdownMenu.classList.toggle('hidden');
        });

        // Sembunyikan dropdown jika klik di luar
        document.addEventListener('click', (e) => {
            if (!profileDropdownButton.contains(e.target) && !profileDropdownMenu.contains(e.target)) {
                profileDropdownMenu.classList.add('hidden');
            }
        });
    }
</script>
