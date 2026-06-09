partials<?php
        // ============================================
        // FILE: includes/header.php
        // Template header halaman publik
        // ============================================

        require_once __DIR__ . '/koneksi.php';
        require_once __DIR__ . '/fungsi.php';

        $profil = get_profil($koneksi);
        $nama = $profil['nama_lengkap'] ?? 'Portfolio CV';
        $jabatan = $profil['jabatan'] ?? '';
        ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portfolio & CV <?= htmlspecialchars($nama) ?> - <?= htmlspecialchars($jabatan) ?>">
    <title><?= htmlspecialchars($nama) ?> | Portfolio CV</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="navbar">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <span class="brand-initial"><?= strtoupper(substr($nama, 0, 2)) ?></span>
                <span class="brand-name ms-2"><?= htmlspecialchars($nama) ?></span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto gap-1">
                    <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="#keahlian">Keahlian</a></li>
                    <li class="nav-item"><a class="nav-link" href="#pengalaman">Pengalaman</a></li>
                    <li class="nav-item"><a class="nav-link" href="#proyek">Proyek</a></li>
                    <li class="nav-item"><a class="nav-link" href="#sertifikat">Sertifikat</a></li>
                    <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-accent btn-sm px-3"
                            href="/portopolio/assets/pdf/cv-reza.pdf"
                            download>
                            <i class="fas fa-download me-1"></i> Unduh CV
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>