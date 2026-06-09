<?php
// FILE: admin/partials/topbar.php
?>
<header class="admin-topbar">
    <button class="sidebar-toggle" id="sidebarToggle" onclick="document.getElementById('adminSidebar').classList.toggle('open')">
        <i class="fas fa-bars"></i>
    </button>
    <div class="topbar-title">
        <?php
        $titles = [
            'index.php' => 'Dashboard',
            'profil.php' => 'Edit Profil',
            'pendidikan.php' => 'Riwayat Pendidikan',
            'pengalaman.php' => 'Pengalaman Kerja',
            'keahlian.php' => 'Keahlian',
            'proyek.php' => 'Proyek',
            'sertifikat.php' => 'Sertifikat',
        ];
        echo $titles[basename($_SERVER['PHP_SELF'])] ?? 'Admin';
        ?>
    </div>
    <div class="topbar-user">
        <i class="fas fa-user-circle"></i>
        <?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?>
    </div>
</header>