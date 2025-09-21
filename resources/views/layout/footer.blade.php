<footer id="bantuan" class="bg-gray-800 text-white py-6 mt-8">
    <div class="container mx-auto text-center">
        <p>&copy; 2024 Tiketing Kereta Api. Semua Hak Dilindungi.</p>
    </div>
</footer>

<script src="script.js"></script>
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
