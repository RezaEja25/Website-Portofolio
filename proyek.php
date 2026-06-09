<?php
// ============================================
// FILE: admin/proyek.php
// CRUD Proyek
// ============================================

session_start();
require_once 'includes/koneksi.php';
require_once '../includes/fungsi.php';
cek_login();

$pesan = '';

// HAPUS
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $row = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT foto_proyek FROM proyek WHERE id = $id"));
    if ($row && !empty($row['foto_proyek'])) hapus_file('../assets/uploads/project/' . $row['foto_proyek']);
    mysqli_query($koneksi, "DELETE FROM proyek WHERE id = $id");
    header('Location: proyek.php?msg=hapus');
    exit();
}

// SIMPAN (tambah/edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $nama = mysqli_real_escape_string($koneksi, trim($_POST['nama_proyek'] ?? ''));
    $kategori = mysqli_real_escape_string($koneksi, trim($_POST['kategori'] ?? ''));
    $deskripsi = mysqli_real_escape_string($koneksi, trim($_POST['deskripsi'] ?? ''));
    $teknologi = mysqli_real_escape_string($koneksi, trim($_POST['teknologi'] ?? ''));
    $tgl_mulai = !empty($_POST['tanggal_mulai']) ? "'" . $_POST['tanggal_mulai'] . "'" : 'NULL';
    $tgl_selesai = !empty($_POST['tanggal_selesai']) ? "'" . $_POST['tanggal_selesai'] . "'" : 'NULL';
    $link_demo = mysqli_real_escape_string($koneksi, trim($_POST['link_demo'] ?? ''));
    $link_github = mysqli_real_escape_string($koneksi, trim($_POST['link_github'] ?? ''));
    $tampilkan = (int)($_POST['tampilkan'] ?? 1);
    $urutan = (int)($_POST['urutan'] ?? 0);

    $foto_sql = '';
    if (!empty($_FILES['foto_proyek']['name'])) {
        $upload = upload_gambar($_FILES['foto_proyek'], 'project');
        if ($upload['sukses']) {
            if ($id > 0) {
                $old = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT foto_proyek FROM proyek WHERE id = $id"));
                if ($old && !empty($old['foto_proyek'])) hapus_file('../assets/uploads/project/' . $old['foto_proyek']);
            }
            $foto_sql = ", foto_proyek = '" . $upload['nama_file'] . "'";
        }
    }

    if ($id > 0) {
        $sql = "UPDATE proyek SET nama_proyek='$nama', kategori='$kategori', deskripsi='$deskripsi', teknologi='$teknologi',
                tanggal_mulai=$tgl_mulai, tanggal_selesai=$tgl_selesai, link_demo='$link_demo', link_github='$link_github',
                tampilkan=$tampilkan, urutan=$urutan $foto_sql WHERE id=$id";
    } else {
        $sql = "INSERT INTO proyek (nama_proyek, kategori, deskripsi, teknologi, tanggal_mulai, tanggal_selesai, link_demo, link_github, tampilkan, urutan)
                VALUES ('$nama','$kategori','$deskripsi','$teknologi',$tgl_mulai,$tgl_selesai,'$link_demo','$link_github',$tampilkan,$urutan)";
    }

    if (mysqli_query($koneksi, $sql)) {
        header('Location: proyek.php?msg=simpan');
        exit();
    } else {
        $pesan = alert('danger', 'Gagal menyimpan: ' . mysqli_error($koneksi));
    }
}

// Edit: load data
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $edit_data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM proyek WHERE id = $id"));
}

// Pesan dari redirect
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'simpan') $pesan = alert('success', 'Proyek berhasil disimpan!');
    if ($_GET['msg'] === 'hapus') $pesan = alert('success', 'Proyek berhasil dihapus!');
}

$daftar = mysqli_query($koneksi, "SELECT * FROM proyek ORDER BY urutan ASC, id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyek | Admin</title>
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
            <div><h2>Proyek</h2><p>Kelola proyek dan portofolio Anda</p></div>
            <button class="btn btn-accent" data-bs-toggle="modal" data-bs-target="#modalProyek" onclick="resetForm()">
                <i class="fas fa-plus me-1"></i> Tambah Proyek
            </button>
        </div>

        <?= $pesan ?>

        <div class="section-card">
            <div class="admin-table">
                <table>
                    <thead><tr>
                        <th width="60">Foto</th>
                        <th>Nama Proyek</th>
                        <th>Kategori</th>
                        <th>Teknologi</th>
                        <th width="80">Status</th>
                        <th width="100">Aksi</th>
                    </tr></thead>
                    <tbody>
                    <?php while ($p = mysqli_fetch_assoc($daftar)): ?>
                    <tr>
                        <td>
                            <?php if (!empty($p['foto_proyek']) && file_exists('../assets/uploads/project/' . $p['foto_proyek'])): ?>
                                <div class="img-preview-wrap" style="width:50px;height:35px">
                                    <img src="../assets/uploads/project/<?= htmlspecialchars($p['foto_proyek']) ?>">
                                </div>
                            <?php else: ?>
                                <div class="img-preview-wrap" style="width:50px;height:35px"><i class="fas fa-code" style="font-size:16px"></i></div>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= htmlspecialchars($p['nama_proyek']) ?></strong></td>
                        <td><span style="font-size:12px;color:var(--text-muted)"><?= htmlspecialchars($p['kategori']) ?></span></td>
                        <td><span style="font-size:12px;color:var(--text-muted)"><?= htmlspecialchars(substr($p['teknologi'], 0, 40)) ?></span></td>
                        <td>
                            <?php if ($p['tampilkan']): ?>
                                <span class="badge" style="background:rgba(34,197,94,.15);color:#22c55e;font-size:11px">Tampil</span>
                            <?php else: ?>
                                <span class="badge" style="background:rgba(239,68,68,.15);color:#ef4444;font-size:11px">Sembunyikan</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="?edit=<?= $p['id'] ?>" class="btn btn-sm btn-outline-accent me-1" data-bs-toggle="modal" data-bs-target="#modalProyek"
                               data-id="<?= $p['id'] ?>" data-nama="<?= htmlspecialchars($p['nama_proyek']) ?>"
                               data-kat="<?= htmlspecialchars($p['kategori']) ?>" data-tek="<?= htmlspecialchars($p['teknologi']) ?>"
                               data-desc="<?= htmlspecialchars($p['deskripsi']) ?>" data-mulai="<?= $p['tanggal_mulai'] ?>"
                               data-selesai="<?= $p['tanggal_selesai'] ?>" data-demo="<?= htmlspecialchars($p['link_demo']) ?>"
                               data-github="<?= htmlspecialchars($p['link_github']) ?>" data-tampil="<?= $p['tampilkan'] ?>"
                               data-urutan="<?= $p['urutan'] ?>" onclick="isiForm(this)">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="?hapus=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Yakin hapus proyek ini?')">
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

<!-- Modal Proyek -->
<div class="modal fade" id="modalProyek" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalProyekTitle">Tambah Proyek</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="f_id" value="0">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Nama Proyek *</label>
                            <input type="text" name="nama_proyek" id="f_nama" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kategori</label>
                            <input type="text" name="kategori" id="f_kat" class="form-control" placeholder="Web App, Mobile...">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="f_desc" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Teknologi (pisahkan dengan koma)</label>
                            <input type="text" name="teknologi" id="f_tek" class="form-control" placeholder="PHP, Laravel, MySQL, Bootstrap">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="f_mulai" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" id="f_selesai" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Link Demo</label>
                            <input type="url" name="link_demo" id="f_demo" class="form-control" placeholder="https://...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Link GitHub</label>
                            <input type="url" name="link_github" id="f_github" class="form-control" placeholder="https://github.com/...">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Foto Proyek</label>
                            <input type="file" name="foto_proyek" class="form-control" accept="image/*">
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
    document.getElementById('modalProyekTitle').textContent = 'Tambah Proyek';
    ['id','nama','kat','tek','desc','mulai','selesai','demo','github','urutan'].forEach(k => {
        const el = document.getElementById('f_'+k);
        if (el) el.value = k === 'id' ? '0' : (k === 'urutan' ? '0' : '');
    });
    document.getElementById('f_tampil').value = '1';
}
function isiForm(btn) {
    document.getElementById('modalProyekTitle').textContent = 'Edit Proyek';
    document.getElementById('f_id').value = btn.dataset.id;
    document.getElementById('f_nama').value = btn.dataset.nama;
    document.getElementById('f_kat').value = btn.dataset.kat;
    document.getElementById('f_tek').value = btn.dataset.tek;
    document.getElementById('f_desc').value = btn.dataset.desc;
    document.getElementById('f_mulai').value = btn.dataset.mulai || '';
    document.getElementById('f_selesai').value = btn.dataset.selesai || '';
    document.getElementById('f_demo').value = btn.dataset.demo;
    document.getElementById('f_github').value = btn.dataset.github;
    document.getElementById('f_tampil').value = btn.dataset.tampil;
    document.getElementById('f_urutan').value = btn.dataset.urutan;
}
</script>
</body>
</html>