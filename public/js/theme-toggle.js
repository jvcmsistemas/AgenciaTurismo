// Theme Toggle Functionality
// Handles switching between dark and light themes for both Superadmin and Agency portals

(function () {
    'use strict';

    const themeToggle = document.getElementById('themeToggle');
    if (!themeToggle) return;

    // Determine current role based on body class
    const isSuperAdmin = document.body.classList.contains('superadmin-theme') || 
                       document.body.classList.contains('superadmin-light-theme');
    
    const rolePrefix = isSuperAdmin ? 'superadmin' : 'agency';
    const storageKey = `${rolePrefix}-theme`;
    
    // Get stored theme preference or default to 'dark'
    const storedTheme = localStorage.getItem(storageKey) || 'dark';

    // Apply the stored theme on page load
    applyTheme(rolePrefix, storedTheme);

    // Set toggle state based on current theme (Checked = Dark)
    themeToggle.checked = (storedTheme === 'dark');

    // Listen for toggle changes
    themeToggle.addEventListener('change', function () {
        const newTheme = this.checked ? 'dark' : 'light';
        applyTheme(rolePrefix, newTheme);
        localStorage.setItem(storageKey, newTheme);
    });

    /**
     * Apply theme classes to body element
     */
    function applyTheme(prefix, theme) {
        const body = document.body;
        const mainClass = `${prefix}-theme`;
        const lightClass = `${prefix}-light-theme`;

        if (theme === 'dark') {
            body.classList.remove(lightClass);
            body.classList.add(mainClass);
        } else {
            body.classList.remove(mainClass);
            body.classList.add(lightClass);
        }
    }
})();
