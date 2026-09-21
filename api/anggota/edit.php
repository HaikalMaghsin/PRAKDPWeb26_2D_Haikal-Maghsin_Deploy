<?php
$page_title = "Edit Anggota";
require_once __DIR__ . '/../includes/session.php';
require __DIR__ . '/../includes/koneksi.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$anggota = null;
$pageError = null;

if (!$pdo) {
    $pageError = $databaseError;
} elseif (!$id) {
    $pageError = 'Data anggota tidak valid.';
} else {
    $stmt = $pdo->prepare('SELECT * FROM anggota WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $anggota = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$anggota) {
        $pageError = 'Data anggota tidak ditemukan.';
    }
}

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

include __DIR__ . '/../includes/header.php';
?>
        <section class="panel">
            <a class="back-link" href="list.php">&larr; Kembali ke daftar anggota</a>
            <h1>Edit Anggota</h1>
            <p class="page-description">Ubah data anggota, lalu tekan tombol Simpan Perubahan.</p>

            <?php if ($pageError): ?>
                <p class="flash flash-error"><?php echo esc($pageError); ?></p>
            <?php elseif ($anggota): ?>
                <?php if ($flash): ?>
                    <p class="flash flash-<?php echo esc($flash['type']); ?>"><?php echo esc($flash['pesan']); ?></p>
                <?php endif; ?>

                <form method="post" action="proses_edit.php" data-validate="true" novalidate>
                    <input type="hidden" name="id" value="<?php echo esc($anggota['id']); ?>">
                    <p>
                        <label for="nama">Nama</label>
                        <input type="text" id="nama" name="nama" value="<?php echo esc($anggota['nama']); ?>" required autofocus>
                    </p>
                    <p>
                        <label for="no_anggota">No. Anggota</label>
                        <input type="text" id="no_anggota" name="no_anggota" value="<?php echo esc($anggota['no_anggota']); ?>" required>
                    </p>
                    <p>
                        <label for="alamat">Alamat</label>
                        <input type="text" id="alamat" name="alamat" value="<?php echo esc($anggota['alamat']); ?>">
                    </p>
                    <p>
                        <label for="no_hp">No. HP</label>
                        <input type="tel" id="no_hp" name="no_hp" value="<?php echo esc($anggota['no_hp']); ?>">
                    </p>
                    <p class="form-actions">
                        <button type="submit">Simpan Perubahan</button>
                        <a class="text-link" href="list.php">Batal</a>
                    </p>
                </form>
            <?php endif; ?>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
