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
        const sidebarCollapse = document.getElementById('sidebarCollapse');
        const sidebar = document.getElementById('sidebar');

        if (sidebarCollapse) {
            sidebarCollapse.addEventListener('click', function () {
                sidebar.classList.toggle('active');
            });
        }
    });
</script>
</body>

</html>