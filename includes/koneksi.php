<?php
// ============================================
// FILE: includes/koneksi.php
// Konfigurasi koneksi database
// ============================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // Ganti sesuai username MySQL kamu
define('DB_PASS', '');            // Ganti sesuai password MySQL kamu
define('DB_NAME', 'portfolio_cv');

// Buat koneksi
$koneksi = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek koneksi
if (!$koneksi) {
    die('<div style="font-family:sans-serif;padding:30px;background:#fee2e2;border:1px solid #ef4444;border-radius:8px;margin:20px;">
        <h3 style="color:#dc2626;margin:0 0 10px">❌ Koneksi Database Gagal</h3>
        <p style="margin:0">Error: ' . mysqli_connect_error() . '</p>
        <p style="margin:10px 0 0;font-size:13px;color:#666;">Cek konfigurasi DB_HOST, DB_USER, DB_PASS, DB_NAME di file <code>includes/koneksi.php</code></p>
    </div>');
}

// Set charset UTF-8
mysqli_set_charset($koneksi, 'utf8mb4');