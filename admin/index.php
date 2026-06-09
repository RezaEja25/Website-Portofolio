<?php
// ============================================
// FILE: admin/index.php
// Dashboard admin
// ============================================

session_start();
require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/fungsi.php';
cek_login();

$profil = get_profil($koneksi);

// Statistik
$stats = [
    'proyek'     => mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as n FROM proyek"))['n'],
    'sertifikat' => mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as n FROM sertifikat"))['n'],
    'keahlian'   => mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as n FROM keahlian"))['n'],
    'pengalaman' => mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as n FROM pengalaman_kerja"))['n'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Portfolio CV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include 'partials/sidebar.php'; ?>

<div class="admin-content">
    <?php include 'partials/topbar.php'; ?>

    <div class="content-area">
        <div class="page-header">
            <h2>Dashboard</h2>
            <p>Selamat datang kembali, <strong><?= htmlspecialchars($_SESSION['admin_username']) ?></strong>!</p>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:rgba(99,102,241,.15);color:#818cf8"><i class="fas fa-folder-open"></i></div>
                    <div class="stat-num"><?= $stats['proyek'] ?></div>
                    <div class="stat-label">Total Proyek</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:rgba(34,197,94,.15);color:#22c55e"><i class="fas fa-certificate"></i></div>
                    <div class="stat-num"><?= $stats['sertifikat'] ?></div>
                    <div class="stat-label">Sertifikat</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:rgba(234,179,8,.15);color:#eab308"><i class="fas fa-cogs"></i></div>
                    <div class="stat-num"><?= $stats['keahlian'] ?></div>
                    <div class="stat-label">Keahlian</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:rgba(239,68,68,.15);color:#ef4444"><i class="fas fa-briefcase"></i></div>
                    <div class="stat-num"><?= $stats['pengalaman'] ?></div>
                    <div class="stat-label">Pengalaman</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="section-card">
            <h5 class="section-card-title"><i class="fas fa-bolt me-2"></i>Aksi Cepat</h5>
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <a href="profil.php" class="quick-action-btn">
                        <i class="fas fa-user-edit"></i>
                        <span>Edit Profil</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="proyek.php" class="quick-action-btn">
                        <i class="fas fa-plus-circle"></i>
                        <span>Tambah Proyek</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="sertifikat.php" class="quick-action-btn">
                        <i class="fas fa-award"></i>
                        <span>Tambah Sertifikat</span>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="../index.php" target="_blank" class="quick-action-btn">
                        <i class="fas fa-eye"></i>
                        <span>Lihat Portfolio</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Profil Preview -->
        <div class="section-card mt-3">
            <h5 class="section-card-title"><i class="fas fa-id-card me-2"></i>Profil Aktif</h5>
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-preview">
                    <?php if (!empty($profil['foto_profil']) && file_exists('../assets/uploads/foto/' . $profil['foto_profil'])): ?>
                        <img src="../assets/uploads/foto/<?= htmlspecialchars($profil['foto_profil']) ?>" alt="">
                    <?php else: ?>
                        <i class="fas fa-user"></i>
                    <?php endif; ?>
                </div>
                <div>
                    <div class="fw-bold" style="font-size:16px"><?= htmlspecialchars($profil['nama_lengkap'] ?? 'Belum diisi') ?></div>
                    <div style="color:#94a3b8;font-size:13px"><?= htmlspecialchars($profil['jabatan'] ?? '') ?></div>
                    <div style="color:#94a3b8;font-size:13px"><?= htmlspecialchars($profil['email'] ?? '') ?></div>
                </div>
                <div class="ms-auto">
                    <a href="profil.php" class="btn btn-sm btn-outline-accent">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>