<?php
$page_title = 'Beranda';
include __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/koneksi.php';

$totalBuku = $pdo ? $pdo->query('SELECT COUNT(*) FROM buku')->fetchColumn() : null;
$totalAnggota = $pdo ? $pdo->query('SELECT COUNT(*) FROM anggota')->fetchColumn() : null;
?>
        <section class="hero" aria-labelledby="hero-title">
            <img class="hero-image" src="assets/images/perpustakaan.jpg" alt="Dua orang membaca buku di antara rak perpustakaan" fetchpriority="high">
            <div class="hero-copy">
                <p class="eyebrow">SELAMAT DATANG DI SIMPUS-MINI</p>
                <h1 id="hero-title">Buka buku,<br>buka wawasan.</h1>
                <p>Tempat singgah untuk membaca, mencari referensi, dan menemukan cerita baru. Mulai dari satu buku hari ini.</p>
                <div class="hero-links">
                    <a class="button" href="buku/list.php">Jelajahi koleksi <span aria-hidden="true">&rarr;</span></a>
                    <a class="text-link" href="anggota/list.php">Daftar anggota</a>
                </div>
            </div>
            <span class="hero-caption">Ruang untuk membaca dan belajar bersama.</span>
        </section>

        <section class="summary-grid" aria-label="Perpustakaan dalam angka">
            <div><strong><?php echo $totalBuku === null ? '&mdash;' : esc($totalBuku); ?></strong><span>Judul buku dalam koleksi</span></div>
            <div><strong><?php echo $totalAnggota === null ? '&mdash;' : esc($totalAnggota); ?></strong><span>Anggota terdaftar</span></div>
        </section>
        <?php if ($databaseError): ?>
            <p class="data-note">Jumlah buku dan anggota belum tersedia.</p>
        <?php endif; ?>

        <section class="visit-info" aria-labelledby="visit-title">
            <h2 id="visit-title">Informasi Perpustakaan</h2>
            <div class="visit-columns">
                <div>
                    <h3>Jam buka</h3>
                    <dl class="visit-hours">
                        <div><dt>Senin &ndash; Jumat</dt><dd>08.00 &ndash; 16.00</dd></div>
                        <div><dt>Sabtu</dt><dd>08.00 &ndash; 12.00</dd></div>
                        <div><dt>Minggu &amp; hari libur</dt><dd>Tutup</dd></div>
                    </dl>
                </div>
                <div>
                    <h3>Saat berkunjung</h3>
                    <ul class="visit-rules">
                        <li>Jaga ketenangan di ruang baca.</li>
                        <li>Rawat buku dan hindari mencoret halamannya.</li>
                        <li>Bawa kartu anggota untuk meminjam buku.</li>
                    </ul>
                </div>
            </div>
        </section>
<?php include __DIR__ . '/includes/footer.php'; ?>
