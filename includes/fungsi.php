<?php
// ============================================
// FILE: includes/fungsi.php
// Fungsi-fungsi pembantu global
// ============================================

// Bersihkan input dari XSS
function bersihkan($data) {
    global $koneksi;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Format tanggal ke Bahasa Indonesia
function format_tanggal($tanggal, $format = 'd F Y') {
    if (empty($tanggal) || $tanggal == '0000-00-00') return '-';
    $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
              'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $d = date('d', strtotime($tanggal));
    $m = (int)date('m', strtotime($tanggal));
    $y = date('Y', strtotime($tanggal));
    return "$d {$bulan[$m]} $y";
}

// Hitung umur dari tanggal lahir
function hitung_umur($tanggal_lahir) {
    if (empty($tanggal_lahir)) return '-';
    $lahir = new DateTime($tanggal_lahir);
    $sekarang = new DateTime();
    $selisih = $lahir->diff($sekarang);
    return $selisih->y . ' tahun';
}

// Hitung lama kerja / lama periode
function hitung_durasi($mulai, $selesai = null, $masih = false) {
    if ($masih || empty($selesai) || $selesai == '0000-00-00') {
        $selesai = date('Y-m-d');
    }
    $d1 = new DateTime($mulai);
    $d2 = new DateTime($selesai);
    $diff = $d1->diff($d2);
    $hasil = '';
    if ($diff->y > 0) $hasil .= $diff->y . ' thn ';
    if ($diff->m > 0) $hasil .= $diff->m . ' bln';
    return trim($hasil) ?: '< 1 bulan';
}

// Upload file gambar
function upload_gambar($file, $folder, $nama_baru = null) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        return ['sukses' => false, 'pesan' => 'Format file tidak didukung. Gunakan JPG, PNG, GIF, atau WEBP.'];
    }

    if ($file['size'] > 5 * 1024 * 1024) {
        return ['sukses' => false, 'pesan' => 'Ukuran file terlalu besar. Maksimal 5MB.'];
    }

    $nama_file = $nama_baru ? $nama_baru . '.' . $ext : uniqid('img_', true) . '.' . $ext;
    $path = 'assets/uploads/' . $folder . '/' . $nama_file;

    if (!is_dir('assets/uploads/' . $folder)) {
        mkdir('assets/uploads/' . $folder, 0777, true);
    }

    if (move_uploaded_file($file['tmp_name'], $path)) {
        return ['sukses' => true, 'nama_file' => $nama_file, 'path' => $path];
    }

    return ['sukses' => false, 'pesan' => 'Gagal mengupload file.'];
}

// Hapus file
function hapus_file($path) {
    if (file_exists($path) && $path !== '') {
        @unlink($path);
    }
}

// Cek apakah user sudah login admin
function cek_login() {
    if (!isset($_SESSION['admin_login']) || $_SESSION['admin_login'] !== true) {
        header('Location: ../admin/login.php');
        exit();
    }
}

// Ambil data profil
function get_profil($koneksi) {
    $result = mysqli_query($koneksi, "SELECT * FROM profil LIMIT 1");
    return $result ? mysqli_fetch_assoc($result) : [];
}

// Tampilkan alert Bootstrap
function alert($tipe, $pesan) {
    $icons = ['success' => '✅', 'danger' => '❌', 'warning' => '⚠️', 'info' => 'ℹ️'];
    $icon = $icons[$tipe] ?? 'ℹ️';
    return "<div class='alert alert-{$tipe} alert-dismissible fade show' role='alert'>
                {$icon} {$pesan}
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>";
}