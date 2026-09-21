<?php
$page_title = "Tambah Anggota";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
        <section class="panel">
            <a class="back-link" href="list.php">&larr; Kembali ke daftar anggota</a>
            <h1>Tambah Anggota</h1>
            <p class="page-description">Isi data anggota baru. Nama dan nomor anggota wajib diisi.</p>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo esc($flash['type']); ?>"><?php echo esc($flash['pesan']); ?></p>
            <?php endif; ?>

            <form id="form-tambah" method="post" action="proses_tambah.php" data-validate="true" novalidate>
                <p>
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" required autofocus>
                </p>
                <p>
                    <label for="no_anggota">No. Anggota</label>
                    <input type="text" id="no_anggota" name="no_anggota" required>
                </p>
                <p>
                    <label for="alamat">Alamat</label>
                    <input type="text" id="alamat" name="alamat">
                </p>
                <p>
                    <label for="no_hp">No. HP</label>
                    <input type="tel" id="no_hp" name="no_hp">
                </p>
                <p class="form-actions">
                    <button type="submit">Simpan anggota</button>
                    <a class="text-link" href="list.php">Batal</a>
                </p>
            </form>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
