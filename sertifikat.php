<?php
// ============================================
// FILE: admin/sertifikat.php
// CRUD Sertifikat
// ============================================

session_start();
require_once 'includes/koneksi.php';
require_once '../includes/fungsi.php';
cek_login();

$pesan = '';

// HAPUS
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $row = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT foto_sertifikat FROM sertifikat WHERE id = $id"));
    if ($row && !empty($row['foto_sertifikat'])) hapus_file('../assets/uploads/sertifikat/' . $row['foto_sertifikat']);
    mysqli_query($koneksi, "DELETE FROM sertifikat WHERE id = $id");
    header('Location: sertifikat.php?msg=hapus');
    exit();
}

// SIMPAN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $nama = mysqli_real_escape_string($koneksi, trim($_POST['nama_sertifikat'] ?? ''));
    $penerbit = mysqli_real_escape_string($koneksi, trim($_POST['penerbit'] ?? ''));
    $tgl_terbit = !empty($_POST['tanggal_terbit']) ? "'" . $_POST['tanggal_terbit'] . "'" : 'NULL';
    $tgl_exp = !empty($_POST['tanggal_kadaluarsa']) ? "'" . $_POST['tanggal_kadaluarsa'] . "'" : 'NULL';
    $nomor = mysqli_real_escape_string($koneksi, trim($_POST['nomor_sertifikat'] ?? ''));
    $link = mysqli_real_escape_string($koneksi, trim($_POST['link_verifikasi'] ?? ''));
    $tampilkan = (int)($_POST['tampilkan'] ?? 1);
    $urutan = (int)($_POST['urutan'] ?? 0);

    $foto_sql = '';
    if (!empty($_FILES['foto_sertifikat']['name'])) {
        $upload = upload_gambar($_FILES['foto_sertifikat'], 'sertifikat');
        if ($upload['sukses']) {
            if ($id > 0) {
                $old = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT foto_sertifikat FROM sertifikat WHERE id = $id"));
                if ($old && !empty($old['foto_sertifikat'])) hapus_file('../assets/uploads/sertifikat/' . $old['foto_sertifikat']);
            }
            $foto_sql = ", foto_sertifikat = '" . $upload['nama_file'] . "'";
        }
    }

    if ($id > 0) {
        $sql = "UPDATE sertifikat SET nama_sertifikat='$nama', penerbit='$penerbit', tanggal_terbit=$tgl_terbit,
                tanggal_kadaluarsa=$tgl_exp, nomor_sertifikat='$nomor', link_verifikasi='$link',
                tampilkan=$tampilkan, urutan=$urutan $foto_sql WHERE id=$id";
    } else {
        $sql = "INSERT INTO sertifikat (nama_sertifikat, penerbit, tanggal_terbit, tanggal_kadaluarsa, nomor_sertifikat, link_verifikasi, tampilkan, urutan)
                VALUES ('$nama','$penerbit',$tgl_terbit,$tgl_exp,'$nomor','$link',$tampilkan,$urutan)";
    }

    if (mysqli_query($koneksi, $sql)) {
        header('Location: sertifikat.php?msg=simpan');
        exit();
    } else {
        $pesan = alert('danger', 'Gagal menyimpan: ' . mysqli_error($koneksi));
    }
}

if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'simpan') $pesan = alert('success', 'Sertifikat berhasil disimpan!');
    if ($_GET['msg'] === 'hapus') $pesan = alert('success', 'Sertifikat berhasil dihapus!');
}

$daftar = mysqli_query($koneksi, "SELECT * FROM sertifikat ORDER BY urutan ASC, tanggal_terbit DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat | Admin</title>
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
        <div class="page-header d-flex justify-content-between align-items-start">
            <div><h2>Sertifikat</h2><p>Kelola sertifikat dan penghargaan Anda</p></div>
            <button class="btn btn-accent" data-bs-toggle="modal" data-bs-target="#modalSertif" onclick="resetForm()">
                <i class="fas fa-plus me-1"></i> Tambah Sertifikat
            </button>
        </div>

        <?= $pesan ?>

        <div class="section-card">
            <div class="admin-table">
                <table>
                    <thead><tr>
                        <th width="70">Foto</th>
                        <th>Nama Sertifikat</th>
                        <th>Penerbit</th>
                        <th>Tanggal Terbit</th>
                        <th width="80">Status</th>
                        <th width="100">Aksi</th>
                    </tr></thead>
                    <tbody>
                    <?php while ($s = mysqli_fetch_assoc($daftar)): ?>
                    <tr>
                        <td>
                            <?php if (!empty($s['foto_sertifikat']) && file_exists('../assets/uploads/sertifikat/' . $s['foto_sertifikat'])): ?>
                                <div class="img-preview-wrap" style="width:55px;height:38px">
                                    <img src="../assets/uploads/sertifikat/<?= htmlspecialchars($s['foto_sertifikat']) ?>">
                                </div>
                            <?php else: ?>
                                <div class="img-preview-wrap" style="width:55px;height:38px"><i class="fas fa-certificate" style="font-size:16px"></i></div>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= htmlspecialchars($s['nama_sertifikat']) ?></strong></td>
                        <td><span style="font-size:13px;color:var(--text-muted)"><?= htmlspecialchars($s['penerbit']) ?></span></td>
                        <td><span style="font-size:13px"><?= format_tanggal($s['tanggal_terbit'] ?? '') ?></span></td>
                        <td>
                            <?= $s['tampilkan'] ?
                                '<span class="badge" style="background:rgba(34,197,94,.15);color:#22c55e;font-size:11px">Tampil</span>' :
                                '<span class="badge" style="background:rgba(239,68,68,.15);color:#ef4444;font-size:11px">Sembunyikan</span>'
                            ?>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-accent me-1" data-bs-toggle="modal" data-bs-target="#modalSertif"
                               data-id="<?= $s['id'] ?>" data-nama="<?= htmlspecialchars($s['nama_sertifikat']) ?>"
                               data-penerbit="<?= htmlspecialchars($s['penerbit']) ?>" data-terbit="<?= $s['tanggal_terbit'] ?>"
                               data-exp="<?= $s['tanggal_kadaluarsa'] ?>" data-nomor="<?= htmlspecialchars($s['nomor_sertifikat'] ?? '') ?>"
                               data-link="<?= htmlspecialchars($s['link_verifikasi'] ?? '') ?>"
                               data-tampil="<?= $s['tampilkan'] ?>" data-urutan="<?= $s['urutan'] ?>"
                               onclick="isiForm(this)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <a href="?hapus=<?= $s['id'] ?>" class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Yakin hapus sertifikat ini?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalSertif" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSertifTitle">Tambah Sertifikat</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="f_id" value="0">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Nama Sertifikat *</label>
                            <input type="text" name="nama_sertifikat" id="f_nama" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Penerbit *</label>
                            <input type="text" name="penerbit" id="f_penerbit" class="form-control" required placeholder="Google, Dicoding...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Terbit</label>
                            <input type="date" name="tanggal_terbit" id="f_terbit" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Kadaluarsa</label>
                            <input type="date" name="tanggal_kadaluarsa" id="f_exp" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor Sertifikat</label>
                            <input type="text" name="nomor_sertifikat" id="f_nomor" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Link Verifikasi</label>
                            <input type="url" name="link_verifikasi" id="f_link" class="form-control" placeholder="https://...">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Upload Foto Sertifikat</label>
                            <input type="file" name="foto_sertifikat" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Urutan</label>
                            <input type="number" name="urutan" id="f_urutan" class="form-control" value="0">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tampilkan</label>
                            <select name="tampilkan" id="f_tampil" class="form-select">
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-accent btn-sm"><i class="fas fa-save me-1"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function resetForm() {
    document.getElementById('modalSertifTitle').textContent = 'Tambah Sertifikat';
    ['id','nama','penerbit','terbit','exp','nomor','link','urutan'].forEach(k => {
        const el = document.getElementById('f_'+k);
        if (el) el.value = k === 'id' ? '0' : (k === 'urutan' ? '0' : '');
    });
    document.getElementById('f_tampil').value = '1';
}
function isiForm(btn) {
    document.getElementById('modalSertifTitle').textContent = 'Edit Sertifikat';
    document.getElementById('f_id').value = btn.dataset.id;
    document.getElementById('f_nama').value = btn.dataset.nama;
    document.getElementById('f_penerbit').value = btn.dataset.penerbit;
    document.getElementById('f_terbit').value = btn.dataset.terbit || '';
    document.getElementById('f_exp').value = btn.dataset.exp || '';
    document.getElementById('f_nomor').value = btn.dataset.nomor;
    document.getElementById('f_link').value = btn.dataset.link;
    document.getElementById('f_tampil').value = btn.dataset.tampil;
    document.getElementById('f_urutan').value = btn.dataset.urutan;
}
</script>
</body>
</html>