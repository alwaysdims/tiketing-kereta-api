 <!-- JavaScript -->
 <script>
 document.addEventListener('DOMContentLoaded', function () {
    // Mobile Menu
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    const closeMobileMenu = document.getElementById('closeMobileMenu');

    // Desktop Dropdowns
    const profileButton = document.getElementById('profileDropdownButton');
    const profileMenu = document.getElementById('profileDropdownMenu');
    const usersButton = document.getElementById('usersDropdownButton');
    const usersMenu = document.getElementById('usersDropdownMenu');

    // Mobile Dropdowns
    const mobileUsersButton = document.getElementById('mobileUsersDropdownButton');
    const mobileUsersMenu = document.getElementById('mobileUsersDropdownMenu');
    const mobileProfileButton = document.getElementById('mobileProfileDropdownButton');
    const mobileProfileMenu = document.getElementById('mobileProfileDropdownMenu');

    // Mobile Menu Toggle
    if (mobileMenuButton && mobileMenu && closeMobileMenu) {
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            mobileMenuButton.setAttribute('aria-expanded', !mobileMenu.classList.contains('hidden'));
        });

        closeMobileMenu.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
            mobileMenuButton.setAttribute('aria-expanded', 'false');
            // Close all dropdowns when closing mobile menu
            if (mobileUsersMenu) {
                mobileUsersMenu.classList.add('hidden');
                mobileUsersButton.setAttribute('aria-expanded', 'false');
            }
            if (mobileProfileMenu) {
                mobileProfileMenu.classList.add('hidden');
                mobileProfileButton.setAttribute('aria-expanded', 'false');
            }
        });

        // Close mobile menu when clicking outside
        mobileMenu.addEventListener('click', (e) => {
            if (e.target === mobileMenu) {
                mobileMenu.classList.add('hidden');
                mobileMenuButton.setAttribute('aria-expanded', 'false');
                // Close all dropdowns
                if (mobileUsersMenu) {
                    mobileUsersMenu.classList.add('hidden');
                    mobileUsersButton.setAttribute('aria-expanded', 'false');
                }
                if (mobileProfileMenu) {
                    mobileProfileMenu.classList.add('hidden');
                    mobileProfileButton.setAttribute('aria-expanded', 'false');
                }
            }
        });
    }

    // Desktop Profile Dropdown
    if (profileButton && profileMenu) {
        profileButton.addEventListener('click', (e) => {
            e.stopPropagation();
            profileMenu.classList.toggle('hidden');
            profileButton.setAttribute('aria-expanded', !profileMenu.classList.contains('hidden'));
        });

        document.addEventListener('click', (e) => {
            if (!profileMenu.classList.contains('hidden') && !profileMenu.contains(e.target) && !profileButton.contains(e.target)) {
                profileMenu.classList.add('hidden');
                profileButton.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // Desktop Users Dropdown
    if (usersButton && usersMenu) {
        usersButton.addEventListener('click', (e) => {
            e.stopPropagation();
            usersMenu.classList.toggle('hidden');
            usersButton.setAttribute('aria-expanded', !usersMenu.classList.contains('hidden'));
        });

        document.addEventListener('click', (e) => {
            if (!usersMenu.classList.contains('hidden') && !usersMenu.contains(e.target) && !usersButton.contains(e.target)) {
                usersMenu.classList.add('hidden');
                usersButton.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // Mobile Users Dropdown
    if (mobileUsersButton && mobileUsersMenu) {
        mobileUsersButton.addEventListener('click', (e) => {
            e.stopPropagation();
            mobileUsersMenu.classList.toggle('hidden');
            mobileUsersButton.setAttribute('aria-expanded', !mobileUsersMenu.classList.contains('hidden'));
        });

        document.addEventListener('click', (e) => {
            if (!mobileUsersMenu.classList.contains('hidden') && !mobileUsersMenu.contains(e.target) && !mobileUsersButton.contains(e.target)) {
                mobileUsersMenu.classList.add('hidden');
                mobileUsersButton.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // Mobile Profile Dropdown
    if (mobileProfileButton && mobileProfileMenu) {
        mobileProfileButton.addEventListener('click', (e) => {
            e.stopPropagation();
            mobileProfileMenu.classList.toggle('hidden');
            mobileProfileButton.setAttribute('aria-expanded', !mobileProfileMenu.classList.contains('hidden'));
        });

        document.addEventListener('click', (e) => {
            if (!mobileProfileMenu.classList.contains('hidden') && !mobileProfileMenu.contains(e.target) && !mobileProfileButton.contains(e.target)) {
                mobileProfileMenu.classList.add('hidden');
                mobileProfileButton.setAttribute('aria-expanded', 'false');
            }
        });
    }
});
</script>
