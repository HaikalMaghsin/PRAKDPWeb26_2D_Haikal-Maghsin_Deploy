<?php
$page_title = 'Beranda';
include __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/koneksi.php';

$totalBuku = $pdo ? $pdo->query('SELECT COUNT(*) FROM buku')->fetchColumn() : 0;
$totalAnggota = $pdo ? $pdo->query('SELECT COUNT(*) FROM anggota')->fetchColumn() : 0;
?>
        <section class="hero">
            <span class="eyebrow">SISTEM INFORMASI PERPUSTAKAAN</span>
            <h2>Temukan cerita, kelola koleksi.</h2>
            <p>SIMPUS-Mini menggabungkan materi Jobsheet 1 sampai 8: HTML, CSS, JavaScript, PHP, dan PostgreSQL.</p>
            <div class="hero-links">
                <a href="buku/list.php">Lihat koleksi</a>
                <a href="anggota/list.php">Data anggota</a>
            </div>
            <?php if ($databaseError): ?>
                <p class="flash flash-error"><?php echo esc($databaseError); ?></p>
            <?php endif; ?>
        </section>

        <p class="summary-title">RINGKASAN KOLEKSI</p>
        <section class="summary-grid" aria-label="Ringkasan data perpustakaan">
            <article>
                <h3>Total Buku</h3>
                <p><?php echo esc($totalBuku); ?></p>
            </article>
            <article>
                <h3>Total Anggota</h3>
                <p><?php echo esc($totalAnggota); ?></p>
            </article>
            <article>
                <h3>Sedang Dipinjam</h3>
                <p>0</p>
            </article>
        </section>
<?php include __DIR__ . '/includes/footer.php'; ?>
