<?php
// ============================================
// FILE: admin/partials/sidebar.php
// ============================================
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <div class="logo-icon">CV</div>
            <div class="logo-text">Admin Panel</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-group">
            <span class="nav-group-label">Utama</span>
            <a href="index.php" class="nav-item <?= $current_page === 'index.php' ? 'active' : '' ?>">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
        </div>
        <div class="nav-group">
            <span class="nav-group-label">Data Saya</span>
            <a href="profil.php" class="nav-item <?= $current_page === 'profil.php' ? 'active' : '' ?>">
                <i class="fas fa-user"></i> Profil & Data Diri
            </a>
            <a href="pendidikan.php" class="nav-item <?= $current_page === 'pendidikan.php' ? 'active' : '' ?>">
                <i class="fas fa-graduation-cap"></i> Pendidikan
            </a>
            <a href="pengalaman.php" class="nav-item <?= $current_page === 'pengalaman.php' ? 'active' : '' ?>">
                <i class="fas fa-briefcase"></i> Pengalaman Kerja
            </a>
            <a href="keahlian.php" class="nav-item <?= $current_page === 'keahlian.php' ? 'active' : '' ?>">
                <i class="fas fa-cogs"></i> Keahlian
            </a>
        </div>
        <div class="nav-group">
            <span class="nav-group-label">Portfolio</span>
            <a href="proyek.php" class="nav-item <?= $current_page === 'proyek.php' ? 'active' : '' ?>">
                <i class="fas fa-folder-open"></i> Proyek
            </a>
            <a href="sertifikat.php" class="nav-item <?= $current_page === 'sertifikat.php' ? 'active' : '' ?>">
                <i class="fas fa-certificate"></i> Sertifikat
            </a>
        </div>
        <div class="nav-group">
            <span class="nav-group-label">Lainnya</span>
            <a href="../index.php" target="_blank" class="nav-item">
                <i class="fas fa-external-link-alt"></i> Lihat Portfolio
            </a>
            <a href="logout.php" class="nav-item nav-item-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </nav>
</aside>