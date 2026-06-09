<?php
// ============================================
// FILE: includes/footer.php
// Template footer halaman publik
// ============================================
?>

<!-- FOOTER -->
<footer class="footer-section py-4 mt-5">
    <div class="container text-center">
        <p class="mb-1 small" style="color:#fff; letter-spacing:0.5px;">
            &copy; <?= date('Y') ?> <?= htmlspecialchars($profil['nama_lengkap'] ?? 'Portfolio CV') ?>. Dibuat dengan <i class="fas fa-heart text-danger"></i> menggunakan PHP & Bootstrap.
        </p>
        <div class="social-links-footer mt-2">
            <?php if (!empty($profil['linkedin'])): ?>
                <a href="<?= htmlspecialchars($profil['linkedin']) ?>" target="_blank" class="me-3 text-white fs-5">
                    <i class="fab fa-linkedin"></i>
                </a>
            <?php endif; ?>

            <?php if (!empty($profil['github'])): ?>
                <?php
                $githubUrl = filter_var($profil['github'], FILTER_VALIDATE_URL)
                    ? $profil['github']
                    : 'https://github.com/' . $profil['github'];
                ?>
                <a href="<?= htmlspecialchars($githubUrl) ?>"
                    target="_blank"
                    class="me-3 text-white fs-5">
                    <i class="fab fa-github"></i>
                </a>
            <?php endif; ?>

            <?php if (!empty($profil['email'])): ?>
                <a href="https://mail.google.com/mail/?view=cm&fs=1&to=<?= urlencode($profil['email']) ?>&su=Halo%20Saya%20Tertarik%20Dengan%20Profil%20Anda&body=Halo,%20saya%20tertarik%20dengan%20profil%20Anda."
                    target="_blank"
                    class="text-white fs-5">
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