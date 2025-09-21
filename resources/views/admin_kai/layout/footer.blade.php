 <!-- JavaScript -->
 <script>
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    const closeMobileMenu = document.getElementById('closeMobileMenu');

    mobileMenuButton.addEventListener('click', () => {
        mobileMenu.classList.remove('hidden');
        mobileMenuButton.setAttribute('aria-expanded', 'true');
    });

    closeMobileMenu.addEventListener('click', () => {
        mobileMenu.classList.add('hidden');
        mobileMenuButton.setAttribute('aria-expanded', 'false');
    });

    // Close mobile menu when clicking outside menu panel
    mobileMenu.addEventListener('click', (e) => {
        if (e.target === mobileMenu) {
            mobileMenu.classList.add('hidden');
            mobileMenuButton.setAttribute('aria-expanded', 'false');
        }
    });

    // Profile dropdown toggle (desktop only)
    const profileButton = document.getElementById('profileDropdownButton');
    const profileMenu = document.getElementById('profileDropdownMenu');

    if (profileButton && profileMenu) {
        profileButton.addEventListener('click', (e) => {
            e.stopPropagation();
            profileMenu.classList.toggle('hidden');
            const expanded = profileButton.getAttribute('aria-expanded') === 'true';
            profileButton.setAttribute('aria-expanded', !expanded);
        });

        // Close profile dropdown if clicking outside
        document.addEventListener('click', (e) => {
            if (!profileMenu.classList.contains('hidden')) {
                if (!profileMenu.contains(e.target) && !profileButton.contains(e.target)) {
                    profileMenu.classList.add('hidden');
                    profileButton.setAttribute('aria-expanded', 'false');
                }
            }
        });
    }

</script>
