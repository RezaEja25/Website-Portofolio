<?php
// ============================================
// FILE: index.php
// Halaman utama portfolio / CV publik
// ============================================

require_once 'includes/koneksi.php';
require_once 'includes/fungsi.php';

// Ambil semua data
$profil = get_profil($koneksi);

$pendidikan = mysqli_query($koneksi, "SELECT * FROM pendidikan ORDER BY urutan ASC, tahun_masuk DESC");

$pengalaman = mysqli_query($koneksi, "SELECT * FROM pengalaman_kerja ORDER BY urutan ASC, tanggal_mulai DESC");

$keahlian_raw = mysqli_query($koneksi, "SELECT * FROM keahlian ORDER BY kategori, urutan ASC");
$keahlian_grouped = [];
while ($row = mysqli_fetch_assoc($keahlian_raw)) {
    $keahlian_grouped[$row['kategori']][] = $row;
}

$proyek = mysqli_query($koneksi, "SELECT * FROM proyek WHERE tampilkan = 1 ORDER BY urutan ASC, id DESC");
$total_proyek = mysqli_num_rows($proyek);
mysqli_data_seek($proyek, 0);

$sertifikat = mysqli_query($koneksi, "SELECT * FROM sertifikat WHERE tampilkan = 1 ORDER BY urutan ASC, tanggal_terbit DESC");

// Hitung statistik
$total_skill = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM keahlian"))['total'];
$total_sertif = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM sertifikat"))['total'];
$total_exp_raw = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT MIN(tanggal_mulai) as first FROM pengalaman_kerja"))['first'];
$tahun_exp = $total_exp_raw ? (date('Y') - date('Y', strtotime($total_exp_raw))) : 0;

require_once 'includes/header.php';
?>

<!-- ============ HERO ============ -->
<section class="hero-section" id="beranda">
    <div class="hero-bg-orb orb-1"></div>
    <div class="hero-bg-orb orb-2"></div>

    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7 order-2 order-lg-1">
                <div class="hero-badge">
                    <span class="dot"></span>
                    Tersedia untuk Peluang Kerja
                </div>
                <h1 class="hero-name">
                    Halo, Saya<br>
                    <span class="accent-text"><?= htmlspecialchars($profil['nama_lengkap'] ?? 'Nama Lengkap') ?></span>
                </h1>
                <p class="hero-jabatan">
                    <i class="fas fa-briefcase me-2" style="color:var(--accent-light)"></i>
                    <?= htmlspecialchars($profil['jabatan'] ?? 'Profesi Anda') ?>
                </p>
                <p class="hero-desc">
                    <?= htmlspecialchars($profil['tagline'] ?? 'Deskripsi singkat tentang diri Anda.') ?>
                </p>
                <div class="hero-actions">
                    <a href="#kontak" class="btn-primary-custom">
                        <i class="fas fa-paper-plane"></i> Hubungi Saya
                    </a>
                    <a href="assets/pdf/cv-reza.pdf" target="_blank" class="btn-outline-custom">
                        <i class="fas fa-file-pdf"></i> Lihat CV
                    </a>
                    <a href="#proyek" class="btn-outline-custom">
                        <i class="fas fa-folder-open"></i> Proyek Saya
                    </a>
                </div>

                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-num" data-count="<?= $tahun_exp ?>" data-suffix="+"><?= $tahun_exp ?>+</span>
                        <span class="stat-label">Tahun Pengalaman</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-num" data-count="<?= $total_proyek ?>" data-suffix="+"><?= $total_proyek ?>+</span>
                        <span class="stat-label">Proyek Selesai</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-num" data-count="<?= $total_sertif ?>" data-suffix="+"><?= $total_sertif ?>+</span>
                        <span class="stat-label">Sertifikat</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-num" data-count="<?= $total_skill ?>" data-suffix="+"><?= $total_skill ?>+</span>
                        <span class="stat-label">Keahlian</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 order-1 order-lg-2">
                <div class="hero-photo-wrap">
                    <div class="hero-photo-ring">
                        <div class="hero-photo-inner">
                            <?php if (!empty($profil['foto_profil']) && file_exists('assets/uploads/foto/' . $profil['foto_profil'])): ?>
                                <img src="assets/uploads/foto/<?= htmlspecialchars($profil['foto_profil']) ?>" alt="<?= htmlspecialchars($profil['nama_lengkap']) ?>">
                            <?php else: ?>
                                <div class="hero-photo-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============ TENTANG SAYA ============ -->
<section class="section section-alt" id="tentang">
    <div class="container">
        <div class="section-header text-center fade-in-up">
            <span class="section-eyebrow">Kenalan Yuk</span>
            <h2 class="section-title">Tentang Saya</h2>
            <div class="section-line mx-auto"></div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6 fade-in-up">
                <div class="info-card h-100">
                    <h5 class="fw-bold mb-4" style="color:var(--accent-light)">
                        <i class="fas fa-user me-2"></i> Data Pribadi
                    </h5>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-signature me-2"></i>Nama Lengkap</span>
                        <span class="info-value"><?= htmlspecialchars($profil['nama_lengkap'] ?? '-') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-map-marker-alt me-2"></i>Tempat, Tgl Lahir</span>
                        <span class="info-value">
                            <?= htmlspecialchars($profil['tempat_lahir'] ?? '-') ?>,
                            <?= format_tanggal($profil['tanggal_lahir'] ?? '') ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-birthday-cake me-2"></i>Umur</span>
                        <span class="info-value"><?= hitung_umur($profil['tanggal_lahir'] ?? '') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-venus-mars me-2"></i>Jenis Kelamin</span>
                        <span class="info-value"><?= htmlspecialchars($profil['jenis_kelamin'] ?? '-') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-pray me-2"></i>Agama</span>
                        <span class="info-value"><?= htmlspecialchars($profil['agama'] ?? '-') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-ring me-2"></i>Status</span>
                        <span class="info-value"><?= htmlspecialchars($profil['status_pernikahan'] ?? '-') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-flag me-2"></i>Kewarganegaraan</span>
                        <span class="info-value"><?= htmlspecialchars($profil['kewarganegaraan'] ?? 'Indonesia') ?></span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 fade-in-up">
                <div class="info-card h-100">
                    <h5 class="fw-bold mb-4" style="color:var(--accent-light)">
                        <i class="fas fa-address-card me-2"></i> Informasi Kontak & Alamat
                    </h5>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-home me-2"></i>Alamat</span>
                        <span class="info-value"><?= htmlspecialchars($profil['alamat'] ?? '-') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-city me-2"></i>Kota</span>
                        <span class="info-value">
                            <?= htmlspecialchars($profil['kota'] ?? '-') ?>
                            <?= !empty($profil['kode_pos']) ? '(' . htmlspecialchars($profil['kode_pos']) . ')' : '' ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-map me-2"></i>Provinsi</span>
                        <span class="info-value"><?= htmlspecialchars($profil['provinsi'] ?? '-') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-envelope me-2"></i>Email</span>
                        <span class="info-value">
                            <a href="mailto:<?= htmlspecialchars($profil['email'] ?? '') ?>" style="color:var(--accent-light)">
                                <?= htmlspecialchars($profil['email'] ?? '-') ?>
                            </a>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-phone me-2"></i>Telepon</span>
                        <span class="info-value"><?= htmlspecialchars($profil['telepon'] ?? '-') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fab fa-whatsapp me-2"></i>WhatsApp</span>
                        <span class="info-value">
                            <?php if (!empty($profil['whatsapp'])): ?>
                                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $profil['whatsapp']) ?>" target="_blank" style="color:var(--accent-light)">
                                    <?= htmlspecialchars($profil['whatsapp']) ?>
                                </a>
                            <?php else: ?> - <?php endif; ?>
                        </span>
                    </div>
                    <?php if (!empty($profil['linkedin'])): ?>
                        <div class="info-row">
                            <span class="info-label"><i class="fab fa-linkedin me-2"></i>LinkedIn</span>
                            <span class="info-value">
                                <a href="<?= htmlspecialchars($profil['linkedin']) ?>" target="_blank" style="color:var(--accent-light)">Lihat Profil</a>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($profil['ringkasan'])): ?>
                <div class="col-12 fade-in-up">
                    <div class="info-card">
                        <h5 class="fw-bold mb-3" style="color:var(--accent-light)">
                            <i class="fas fa-quote-left me-2"></i> Ringkasan Profesional
                        </h5>
                        <p style="color:var(--text-muted);line-height:1.9;font-size:15px;margin:0">
                            <?= nl2br(htmlspecialchars($profil['ringkasan'])) ?>
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pendidikan -->
        <div class="mt-5 fade-in-up">
            <h4 class="fw-bold mb-4" style="color:var(--text)">
                <i class="fas fa-graduation-cap me-2" style="color:var(--accent-light)"></i> Riwayat Pendidikan
            </h4>
            <?php while ($edu = mysqli_fetch_assoc($pendidikan)): ?>
                <div class="edu-card fade-in-up">
                    <div class="edu-icon"><i class="fas fa-university"></i></div>
                    <div class="flex-grow-1">
                        <div class="edu-jenjang"><?= htmlspecialchars($edu['jenjang']) ?></div>
                        <div class="edu-institusi"><?= htmlspecialchars($edu['institusi']) ?></div>
                        <?php if (!empty($edu['jurusan'])): ?>
                            <div class="edu-jurusan"><?= htmlspecialchars($edu['jurusan']) ?></div>
                        <?php endif; ?>
                        <div class="edu-meta">
                            <span><i class="far fa-calendar me-1"></i>
                                <?= $edu['tahun_masuk'] ?> - <?= $edu['masih_berjalan'] ? 'Sekarang' : ($edu['tahun_lulus'] ?? '?') ?>
                            </span>
                            <?php if (!empty($edu['ipk'])): ?>
                                <span class="edu-ipk"><i class="fas fa-star me-1"></i>IPK: <?= htmlspecialchars($edu['ipk']) ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($edu['deskripsi'])): ?>
                            <p class="mt-2 mb-0" style="font-size:13px;color:var(--text-muted)"><?= htmlspecialchars($edu['deskripsi']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- ============ KEAHLIAN ============ -->
<section class="section" id="keahlian">
    <div class="container">
        <div class="section-header text-center fade-in-up">
            <span class="section-eyebrow">Yang Saya Kuasai</span>
            <h2 class="section-title">Keahlian</h2>
            <div class="section-line mx-auto"></div>
        </div>
        <div class="row g-4">
            <?php foreach ($keahlian_grouped as $kategori => $skills): ?>
                <div class="col-md-6 col-lg-4 fade-in-up">
                    <div class="info-card h-100">
                        <div class="skill-category-title">
                            <i class="fas fa-layer-group me-1"></i> <?= htmlspecialchars($kategori) ?>
                        </div>
                        <?php foreach ($skills as $skill): ?>
                            <div class="skill-item">
                                <div class="skill-header">
                                    <span class="skill-name"><?= htmlspecialchars($skill['nama_keahlian']) ?></span>
                                    <span class="skill-pct"><?= $skill['level'] ?>%</span>
                                </div>
                                <div class="skill-bar-track">
                                    <div class="skill-bar-fill" data-level="<?= $skill['level'] ?>"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============ PENGALAMAN KERJA ============ -->
<section class="section section-alt" id="pengalaman">
    <div class="container">
        <div class="section-header text-center fade-in-up">
            <span class="section-eyebrow">Perjalanan Karir</span>
            <h2 class="section-title">Pengalaman Kerja</h2>
            <div class="section-line mx-auto"></div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="timeline">
                    <?php while ($exp = mysqli_fetch_assoc($pengalaman)): ?>
                        <div class="timeline-item fade-in-up">
                            <div class="timeline-dot"></div>
                            <div class="timeline-card">
                                <div class="timeline-header">
                                    <div>
                                        <div class="timeline-posisi"><?= htmlspecialchars($exp['posisi']) ?></div>
                                        <div class="timeline-perusahaan">
                                            <i class="fas fa-building me-1"></i><?= htmlspecialchars($exp['perusahaan']) ?>
                                        </div>
                                    </div>
                                    <span class="badge-tipe"><?= htmlspecialchars($exp['tipe_kerja']) ?></span>
                                </div>
                                <div class="timeline-meta">
                                    <span>
                                        <i class="far fa-calendar"></i>
                                        <?= format_tanggal($exp['tanggal_mulai']) ?> -
                                        <?= $exp['masih_bekerja'] ? '<span style="color:var(--green);font-weight:600">Sekarang</span>' : format_tanggal($exp['tanggal_selesai']) ?>
                                    </span>
                                    <span>
                                        <i class="fas fa-clock"></i>
                                        <?= hitung_durasi($exp['tanggal_mulai'], $exp['tanggal_selesai'], $exp['masih_bekerja']) ?>
                                    </span>
                                    <?php if (!empty($exp['lokasi'])): ?>
                                        <span>
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?= htmlspecialchars($exp['lokasi']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($exp['deskripsi'])): ?>
                                    <p class="timeline-desc"><?= nl2br(htmlspecialchars($exp['deskripsi'])) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============ PROYEK ============ -->
<section class="section" id="proyek">
    <div class="container">
        <div class="section-header text-center fade-in-up">
            <span class="section-eyebrow">Portofolio Karya</span>
            <h2 class="section-title">Proyek Saya</h2>
            <div class="section-line mx-auto"></div>
        </div>
        <div class="row g-4">
            <?php while ($pjk = mysqli_fetch_assoc($proyek)): ?>
                <div class="col-md-6 col-lg-4 fade-in-up">
                    <div class="project-card">
                        <div class="project-img">
                            <?php if (!empty($pjk['foto_proyek']) && file_exists('assets/uploads/project/' . $pjk['foto_proyek'])): ?>
                                <img src="assets/uploads/project/<?= htmlspecialchars($pjk['foto_proyek']) ?>" alt="<?= htmlspecialchars($pjk['nama_proyek']) ?>">
                            <?php else: ?>
                                <div class="project-img-placeholder"><i class="fas fa-code"></i></div>
                            <?php endif; ?>
                        </div>
                        <div class="project-body">
                            <?php if (!empty($pjk['kategori'])): ?>
                                <div class="project-kategori"><?= htmlspecialchars($pjk['kategori']) ?></div>
                            <?php endif; ?>
                            <div class="project-nama"><?= htmlspecialchars($pjk['nama_proyek']) ?></div>
                            <?php if (!empty($pjk['deskripsi'])): ?>
                                <p class="project-desc"><?= htmlspecialchars(substr($pjk['deskripsi'], 0, 120)) . (strlen($pjk['deskripsi']) > 120 ? '...' : '') ?></p>
                            <?php endif; ?>
                            <?php if (!empty($pjk['teknologi'])): ?>
                                <div class="tech-tags">
                                    <?php foreach (explode(',', $pjk['teknologi']) as $tech): ?>
                                        <span class="tech-tag"><?= htmlspecialchars(trim($tech)) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($pjk['tanggal_mulai'])): ?>
                                <p style="font-size:12px;color:var(--text-muted);margin-bottom:12px">
                                    <i class="far fa-calendar me-1"></i>
                                    <?= format_tanggal($pjk['tanggal_mulai']) ?>
                                    <?= !empty($pjk['tanggal_selesai']) ? ' - ' . format_tanggal($pjk['tanggal_selesai']) : '' ?>
                                </p>
                            <?php endif; ?>
                            <div class="project-links">
                                <?php if (!empty($pjk['link_demo'])): ?>
                                    <a href="<?= htmlspecialchars($pjk['link_demo']) ?>" target="_blank" class="project-link">
                                        <i class="fas fa-external-link-alt"></i> Demo
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($pjk['link_github'])): ?>
                                    <a href="<?= htmlspecialchars($pjk['link_github']) ?>" target="_blank" class="project-link">
                                        <i class="fab fa-github"></i> Source
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- ============ SERTIFIKAT ============ -->
<section class="section section-alt" id="sertifikat">
    <div class="container">
        <div class="section-header text-center fade-in-up">
            <span class="section-eyebrow">Bukti Kompetensi</span>
            <h2 class="section-title">Sertifikat</h2>
            <div class="section-line mx-auto"></div>
        </div>
        <div class="row g-4">
            <?php while ($cert = mysqli_fetch_assoc($sertifikat)): ?>
                <div class="col-md-6 col-lg-3 fade-in-up">
                    <div class="cert-card" data-bs-toggle="modal" data-bs-target="#modalSertifikat"
                        data-nama="<?= htmlspecialchars($cert['nama_sertifikat']) ?>"
                        data-penerbit="<?= htmlspecialchars($cert['penerbit']) ?>"
                        data-tanggal="<?= format_tanggal($cert['tanggal_terbit'] ?? '') ?>"
                        data-nomor="<?= htmlspecialchars($cert['nomor_sertifikat'] ?? '') ?>"
                        data-link="<?= htmlspecialchars($cert['link_verifikasi'] ?? '') ?>"
                        data-foto="<?= !empty($cert['foto_sertifikat']) ? 'assets/uploads/sertifikat/' . htmlspecialchars($cert['foto_sertifikat']) : '' ?>">
                        <div class="cert-img">
                            <?php if (!empty($cert['foto_sertifikat']) && file_exists('assets/uploads/sertifikat/' . $cert['foto_sertifikat'])): ?>
                                <img src="assets/uploads/sertifikat/<?= htmlspecialchars($cert['foto_sertifikat']) ?>" alt="<?= htmlspecialchars($cert['nama_sertifikat']) ?>">
                            <?php else: ?>
                                <div class="cert-img-placeholder"><i class="fas fa-certificate"></i></div>
                            <?php endif; ?>
                            <div class="cert-overlay"><i class="fas fa-expand"></i></div>
                        </div>
                        <div class="cert-body">
                            <div class="cert-nama"><?= htmlspecialchars($cert['nama_sertifikat']) ?></div>
                            <div class="cert-penerbit">
                                <i class="fas fa-award"></i> <?= htmlspecialchars($cert['penerbit']) ?>
                            </div>
                            <?php if (!empty($cert['tanggal_terbit'])): ?>
                                <div class="cert-tanggal">
                                    <i class="far fa-calendar-check me-1"></i><?= format_tanggal($cert['tanggal_terbit']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Modal Sertifikat -->
<div class="modal fade" id="modalSertifikat" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalCertNama">Sertifikat</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-0">
                <div id="modalCertImg" class="bg-dark rounded"></div>
                <div class="p-4 text-start">
                    <p><strong style="color:var(--accent-light)"><i class="fas fa-award me-2"></i>Penerbit:</strong> <span id="modalCertPenerbit"></span></p>
                    <p><strong style="color:var(--accent-light)"><i class="far fa-calendar me-2"></i>Tanggal:</strong> <span id="modalCertTanggal"></span></p>
                    <p id="modalCertNomorWrap"><strong style="color:var(--accent-light)"><i class="fas fa-hashtag me-2"></i>No. Sertifikat:</strong> <span id="modalCertNomor"></span></p>
                </div>
            </div>
            <div class="modal-footer">
                <a id="modalCertLink" href="#" target="_blank" class="btn btn-sm btn-accent" style="display:none">
                    <i class="fas fa-external-link-alt me-1"></i> Verifikasi Sertifikat
                </a>
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- ============ KONTAK ============ -->
<section class="section" id="kontak">
    <div class="container">
        <div class="section-header text-center fade-in-up">
            <span class="section-eyebrow">Ayo Terhubung</span>
            <h2 class="section-title">Hubungi Saya</h2>
            <div class="section-line mx-auto"></div>
        </div>
        <div class="row justify-content-center g-4">
            <div class="col-lg-7 fade-in-up">
                <div class="contact-card">
                    <?php if (!empty($profil['email'])): ?>
                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=<?= urlencode($profil['email']) ?>&su=Halo%20Saya%20Tertarik%20Dengan%20Profil%20Anda&body=Halo,%20saya%20tertarik%20dengan%20profil%20Anda%20dan%20ingin%20berdiskusi%20lebih%20lanjut."
                            target="_blank"
                            class="contact-item">
                            <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                            <div>
                                <div class="contact-label">Email</div>
                                <div class="contact-value"><?= htmlspecialchars($profil['email']) ?></div>
                            </div>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($profil['telepon'])): ?>
                        <a href="tel:<?= htmlspecialchars($profil['telepon']) ?>" class="contact-item">
                            <div class="contact-icon"><i class="fas fa-phone"></i></div>
                            <div>
                                <div class="contact-label">Telepon</div>
                                <div class="contact-value"><?= htmlspecialchars($profil['telepon']) ?></div>
                            </div>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($profil['whatsapp'])): ?>
                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $profil['whatsapp']) ?>" target="_blank" class="contact-item">
                            <div class="contact-icon"><i class="fab fa-whatsapp"></i></div>
                            <div>
                                <div class="contact-label">WhatsApp</div>
                                <div class="contact-value"><?= htmlspecialchars($profil['whatsapp']) ?></div>
                            </div>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($profil['alamat'])): ?>
                        <div class="contact-item" style="cursor:default">
                            <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div>
                                <div class="contact-label">Alamat</div>
                                <div class="contact-value">
                                    <?= htmlspecialchars($profil['alamat']) ?>,
                                    <?= htmlspecialchars($profil['kota'] ?? '') ?>,
                                    <?= htmlspecialchars($profil['provinsi'] ?? '') ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($profil['linkedin'])): ?>
                        <a href="<?= htmlspecialchars($profil['linkedin']) ?>" target="_blank" class="contact-item">
                            <div class="contact-icon"><i class="fab fa-linkedin"></i></div>
                            <div>
                                <div class="contact-label">LinkedIn</div>
                                <div class="contact-value">Lihat Profil LinkedIn</div>
                            </div>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($profil['github'])): ?>
                        <a href="https://github.com/<?= urlencode($profil['github']) ?>" target="_blank" class="contact-item">
                            <div class="contact-icon"><i class="fab fa-github"></i></div>
                            <div>
                                <div class="contact-label">GitHub</div>
                                <div class="contact-value">Kunjungi Profile</div>
                            </div>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>

<script>
    // Modal sertifikat handler
    document.getElementById('modalSertifikat').addEventListener('show.bs.modal', function(e) {
        const card = e.relatedTarget;
        const nama = card.getAttribute('data-nama');
        const penerbit = card.getAttribute('data-penerbit');
        const tanggal = card.getAttribute('data-tanggal');
        const nomor = card.getAttribute('data-nomor');
        const link = card.getAttribute('data-link');
        const foto = card.getAttribute('data-foto');

        document.getElementById('modalCertNama').textContent = nama;
        document.getElementById('modalCertPenerbit').textContent = penerbit;
        document.getElementById('modalCertTanggal').textContent = tanggal || '-';
        document.getElementById('modalCertNomor').textContent = nomor || '-';

        const imgDiv = document.getElementById('modalCertImg');
        if (foto && foto !== '') {
            imgDiv.innerHTML = `<img src="${foto}" alt="${nama}" class="img-fluid" style="max-height:450px;border-radius:12px 12px 0 0">`;
        } else {
            imgDiv.innerHTML = `<div style="height:200px;display:flex;align-items:center;justify-content:center;font-size:64px;color:#475569"><i class="fas fa-certificate"></i></div>`;
        }

        const linkEl = document.getElementById('modalCertLink');
        if (link && link !== '') {
            linkEl.href = link;
            linkEl.style.display = 'inline-flex';
        } else {
            linkEl.style.display = 'none';
        }

        document.getElementById('modalCertNomorWrap').style.display = nomor ? 'block' : 'none';
    });
</script>