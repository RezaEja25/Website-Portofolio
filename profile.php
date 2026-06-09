<?php
// ============================================
// FILE: admin/profil.php
// Halaman edit profil & data pribadi
// ============================================

session_start();
require_once 'includes/koneksi.php';
require_once '../includes/fungsi.php';
cek_login();

$profil = get_profil($koneksi);
$pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = [
        'nama_lengkap', 'jabatan', 'tagline', 'tempat_lahir', 'jenis_kelamin',
        'agama', 'status_pernikahan', 'kewarganegaraan', 'alamat', 'kota',
        'provinsi', 'kode_pos', 'email', 'telepon', 'whatsapp',
        'linkedin', 'github', 'website', 'ringkasan'
    ];
    $date_fields = ['tanggal_lahir'];

    $data = [];
    foreach ($fields as $f) {
        $data[$f] = mysqli_real_escape_string($koneksi, trim($_POST[$f] ?? ''));
    }
    foreach ($date_fields as $f) {
        $data[$f] = !empty($_POST[$f]) ? mysqli_real_escape_string($koneksi, $_POST[$f]) : null;
    }

    // Upload foto profil
    if (!empty($_FILES['foto_profil']['name'])) {
        $upload = upload_gambar($_FILES['foto_profil'], 'foto', 'profil_' . time());
        if ($upload['sukses']) {
            // Hapus foto lama
            if (!empty($profil['foto_profil'])) {
                hapus_file('../assets/uploads/foto/' . $profil['foto_profil']);
            }
            $data['foto_profil'] = $upload['nama_file'];
        } else {
            $pesan = alert('danger', $upload['pesan']);
        }
    }

    if (empty($pesan)) {
        // Cek apakah profil sudah ada
        $cek = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id FROM profil LIMIT 1"));

        $set_parts = [];
        foreach ($data as $col => $val) {
            if ($val === null) {
                $set_parts[] = "`$col` = NULL";
            } else {
                $set_parts[] = "`$col` = '$val'";
            }
        }
        $set_sql = implode(', ', $set_parts);

        if ($cek) {
            $sql = "UPDATE profil SET $set_sql WHERE id = {$cek['id']}";
        } else {
            $cols = implode(', ', array_map(fn($c) => "`$c`", array_keys($data)));
            $vals_arr = array_map(fn($v) => $v === null ? 'NULL' : "'$v'", array_values($data));
            $vals = implode(', ', $vals_arr);
            $sql = "INSERT INTO profil ($cols) VALUES ($vals)";
        }

        if (mysqli_query($koneksi, $sql)) {
            $pesan = alert('success', 'Profil berhasil disimpan!');
            $profil = get_profil($koneksi);
        } else {
            $pesan = alert('danger', 'Gagal menyimpan: ' . mysqli_error($koneksi));
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil | Admin</title>
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
            <h2>Edit Profil</h2>
            <p>Kelola data pribadi dan informasi kontak Anda</p>
        </div>

        <?= $pesan ?>

        <form method="POST" enctype="multipart/form-data">
            <!-- Foto Profil -->
            <div class="section-card mb-3">
                <h5 class="section-card-title"><i class="fas fa-camera me-2"></i>Foto Profil</h5>
                <div class="d-flex align-items-center gap-4">
                    <div class="img-preview-wrap foto-profil-preview" id="fotoPreviewWrap">
                        <?php if (!empty($profil['foto_profil']) && file_exists('../assets/uploads/foto/' . $profil['foto_profil'])): ?>
                            <img src="../assets/uploads/foto/<?= htmlspecialchars($profil['foto_profil']) ?>" id="fotoPreview">
                        <?php else: ?>
                            <i class="fas fa-user" id="fotoIcon"></i>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="form-label">Upload Foto Baru</label>
                        <input type="file" name="foto_profil" class="form-control" accept="image/*" onchange="previewFoto(this)">
                        <small class="text-muted">JPG, PNG, WEBP. Maks 5MB. Rasio 1:1 (persegi) disarankan.</small>
                    </div>
                </div>
            </div>

            <!-- Data Pribadi -->
            <div class="section-card mb-3">
                <h5 class="section-card-title"><i class="fas fa-user me-2"></i>Data Pribadi</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" class="form-control" required value="<?= htmlspecialchars($profil['nama_lengkap'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jabatan / Profesi</label>
                        <input type="text" name="jabatan" class="form-control" placeholder="Contoh: Web Developer" value="<?= htmlspecialchars($profil['jabatan'] ?? '') ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Tagline / Slogan</label>
                        <input type="text" name="tagline" class="form-control" placeholder="Kalimat singkat yang menggambarkan Anda" value="<?= htmlspecialchars($profil['tagline'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control" value="<?= htmlspecialchars($profil['tempat_lahir'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" value="<?= $profil['tanggal_lahir'] ?? '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select">
                            <option value="">-- Pilih --</option>
                            <option value="Laki-laki" <?= ($profil['jenis_kelamin'] ?? '') == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="Perempuan" <?= ($profil['jenis_kelamin'] ?? '') == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Agama</label>
                        <input type="text" name="agama" class="form-control" value="<?= htmlspecialchars($profil['agama'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status Pernikahan</label>
                        <select name="status_pernikahan" class="form-select">
                            <option value="Belum Menikah" <?= ($profil['status_pernikahan'] ?? '') == 'Belum Menikah' ? 'selected' : '' ?>>Belum Menikah</option>
                            <option value="Menikah" <?= ($profil['status_pernikahan'] ?? '') == 'Menikah' ? 'selected' : '' ?>>Menikah</option>
                            <option value="Cerai" <?= ($profil['status_pernikahan'] ?? '') == 'Cerai' ? 'selected' : '' ?>>Cerai</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kewarganegaraan</label>
                        <input type="text" name="kewarganegaraan" class="form-control" value="<?= htmlspecialchars($profil['kewarganegaraan'] ?? 'Indonesia') ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Ringkasan Profesional</label>
                        <textarea name="ringkasan" class="form-control" rows="4" placeholder="Ceritakan tentang diri Anda secara profesional..."><?= htmlspecialchars($profil['ringkasan'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Alamat -->
            <div class="section-card mb-3">
                <h5 class="section-card-title"><i class="fas fa-map-marker-alt me-2"></i>Alamat</h5>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="alamat" class="form-control" rows="2" placeholder="Jl. Nama Jalan No., RT/RW, Kel/Desa, Kec."><?= htmlspecialchars($profil['alamat'] ?? '') ?></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kota</label>
                        <input type="text" name="kota" class="form-control" value="<?= htmlspecialchars($profil['kota'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Provinsi</label>
                        <input type="text" name="provinsi" class="form-control" value="<?= htmlspecialchars($profil['provinsi'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kode Pos</label>
                        <input type="text" name="kode_pos" class="form-control" value="<?= htmlspecialchars($profil['kode_pos'] ?? '') ?>">
                    </div>
                </div>
            </div>

            <!-- Kontak & Sosial -->
            <div class="section-card mb-3">
                <h5 class="section-card-title"><i class="fas fa-address-book me-2"></i>Kontak & Sosial Media</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-envelope me-1"></i>Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($profil['email'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-phone me-1"></i>Nomor Telepon</label>
                        <input type="text" name="telepon" class="form-control" placeholder="0812xxxxxxxx" value="<?= htmlspecialchars($profil['telepon'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="fab fa-whatsapp me-1"></i>WhatsApp</label>
                        <input type="text" name="whatsapp" class="form-control" placeholder="08xxxxxxxxxx" value="<?= htmlspecialchars($profil['whatsapp'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="fab fa-linkedin me-1"></i>LinkedIn URL</label>
                        <input type="url" name="linkedin" class="form-control" placeholder="https://linkedin.com/in/..." value="<?= htmlspecialchars($profil['linkedin'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="fab fa-github me-1"></i>GitHub URL</label>
                        <input type="url" name="github" class="form-control" placeholder="https://github.com/..." value="<?= htmlspecialchars($profil['github'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-globe me-1"></i>Website Personal</label>
                        <input type="url" name="website" class="form-control" placeholder="https://..." value="<?= htmlspecialchars($profil['website'] ?? '') ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-accent px-4 py-2">
                <i class="fas fa-save me-2"></i>Simpan Profil
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const wrap = document.getElementById('fotoPreviewWrap');
            wrap.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
</body>
</html>