<?php
// ============================================
// FILE: includes/footer.php
// Template footer halaman publik
// ============================================
?>

<!-- FOOTER -->
<footer class="footer-section py-4 mt-5">
    <div class="container text-center">
        <p class="mb-1 text-muted small">
            &copy; <?= date('Y') ?> <?= htmlspecialchars($profil['nama_lengkap'] ?? 'Portfolio CV') ?>.
            Dibuat dengan <i class="fas fa-heart text-danger"></i> menggunakan PHP & Bootstrap.
        </p>
        <div class="social-links-footer mt-2">
            <?php if (!empty($profil['linkedin'])): ?>
                <a href="<?= htmlspecialchars($profil['linkedin']) ?>" target="_blank" class="me-3 text-muted">
                    <i class="fab fa-linkedin"></i>
                </a>
            <?php endif; ?>
            <?php if (!empty($profil['github'])): ?>
                <a href="<?= htmlspecialchars($profil['github']) ?>" target="_blank" class="me-3 text-muted">
                    <i class="fab fa-github"></i>
                </a>
            <?php endif; ?>
            <?php if (!empty($profil['email'])): ?>
                <a href="mailto:<?= htmlspecialchars($profil['email']) ?>" class="text-muted">
                    <i class="fas fa-envelope"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="assets/js/script.js"></script>
</body>
</html>