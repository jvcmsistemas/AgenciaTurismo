// Theme Toggle Functionality
// Handles switching between dark and light themes for Superadmin panel

(function () {
    'use strict';

    // Get theme toggle element
    const themeToggle = document.getElementById('themeToggle');

    if (!themeToggle) {
        return; // Theme toggle not present on this page
    }

    // Get stored theme preference or default to 'dark'
    const currentTheme = localStorage.getItem('superadmin-theme') || 'dark';

    // Apply the stored theme on page load
    applyTheme(currentTheme);

    // Set toggle state based on current theme
    themeToggle.checked = (currentTheme === 'dark');

    // Listen for toggle changes
    themeToggle.addEventListener('change', function () {
        const newTheme = this.checked ? 'dark' : 'light';
        applyTheme(newTheme);
        localStorage.setItem('superadmin-theme', newTheme);
    });

    /**
     * Apply theme to body element
     * @param {string} theme - 'dark' or 'light'
     */
    function applyTheme(theme) {
        const body = document.body;

        if (theme === 'dark') {
            body.classList.remove('superadmin-light-theme');
            body.classList.add('superadmin-theme');
        } else {
            body.classList.remove('superadmin-theme');
            body.classList.add('superadmin-light-theme');
        }
    }

    // Sidebar collapse functionality (existing code preserved)
    const sidebarCollapse = document.getElementById('sidebarCollapse');
    const sidebar = document.getElementById('sidebar');

    if (sidebarCollapse && sidebar) {
        sidebarCollapse.addEventListener('click', function () {
            sidebar.classList.toggle('active');
        });
    }
})();
