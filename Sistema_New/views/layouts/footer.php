<?php if (!isset($isLoginPage) || !$isLoginPage): ?>
    </div> <!-- End container-fluid -->
    </div> <!-- End content -->
    </div> <!-- End wrapper -->
<?php else: ?>
    </div> <!-- End container-fluid (Login) -->
<?php endif; ?>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Theme Toggle -->
<script src="<?php echo BASE_URL; ?>public/js/theme-toggle.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('sidebarCollapse');
        const closeBtn = document.getElementById('sidebarCloseMobile');
        const body = document.body;

        const createOverlay = () => {
            let overlay = document.getElementById('sidebar-overlay');
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.id = 'sidebar-overlay';
                overlay.style.position = 'fixed';
                overlay.style.top = '0';
                overlay.style.left = '0';
                overlay.style.width = '100vw';
                overlay.style.height = '100vh';
                overlay.style.backgroundColor = 'rgba(0,0,0,0.5)';
                overlay.style.zIndex = '9990';
                overlay.style.display = 'none';
                overlay.style.backdropFilter = 'blur(2px)';
                overlay.onclick = window.closeMobileMenu; // Direct assignment
                document.body.appendChild(overlay);
            }
        };

        // Initialize Overlay immediately
        createOverlay();

        // Handle links
        const sidebarLinks = document.querySelectorAll('#sidebar a:not([data-bs-toggle])');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) window.closeMobileMenu();
            });
        });
    });
</script>


</body>

</html>