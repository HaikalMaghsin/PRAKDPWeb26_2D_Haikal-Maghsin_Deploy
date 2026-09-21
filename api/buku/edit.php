<?php
$page_title = "Edit Buku";
require_once __DIR__ . '/../includes/session.php';
require __DIR__ . '/../includes/koneksi.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$buku = null;
$pageError = null;

if (!$pdo) {
    $pageError = $databaseError;
} elseif (!$id) {
    $pageError = 'Data buku tidak valid.';
} else {
    $stmt = $pdo->prepare('SELECT * FROM buku WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $buku = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$buku) {
        $pageError = 'Data buku tidak ditemukan.';
    }
}

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

include __DIR__ . '/../includes/header.php';
?>
        <section class="panel">
            <a class="back-link" href="list.php">&larr; Kembali ke daftar buku</a>
            <h1>Edit Buku</h1>
            <p class="page-description">Ubah informasi buku, lalu tekan tombol Simpan Perubahan.</p>

            <?php if ($pageError): ?>
                <p class="flash flash-error"><?php echo esc($pageError); ?></p>
            <?php elseif ($buku): ?>
                <?php if ($flash): ?>
                    <p class="flash flash-<?php echo esc($flash['type']); ?>"><?php echo esc($flash['pesan']); ?></p>
                <?php endif; ?>

                <form method="post" action="proses_edit.php" data-validate="true" novalidate>
                    <input type="hidden" name="id" value="<?php echo esc($buku['id']); ?>">
                    <p>
                        <label for="judul">Judul</label>
                        <input type="text" id="judul" name="judul" value="<?php echo esc($buku['judul']); ?>" required autofocus>
                    </p>
                    <p>
                        <label for="pengarang">Pengarang</label>
                        <input type="text" id="pengarang" name="pengarang" value="<?php echo esc($buku['pengarang']); ?>" required>
                    </p>
                    <p>
                        <label for="tahun">Tahun Terbit</label>
                        <input type="number" id="tahun" name="tahun" min="1900" max="2026" value="<?php echo esc($buku['tahun']); ?>" required>
                    </p>
                    <p>
                        <label for="isbn">ISBN (opsional)</label>
                        <input type="text" id="isbn" name="isbn" value="<?php echo esc($buku['isbn']); ?>">
                    </p>
                    <p>
                        <label for="stok">Stok</label>
                        <input type="number" id="stok" name="stok" min="0" value="<?php echo esc($buku['stok']); ?>" required>
                    </p>
                    <p>
                        <label for="kategori">Kategori</label>
                        <select id="kategori" name="kategori">
                            <option value="fiksi" <?php if ($buku['kategori'] === 'fiksi') echo 'selected'; ?>>Fiksi</option>
                            <option value="non-fiksi" <?php if ($buku['kategori'] === 'non-fiksi') echo 'selected'; ?>>Non-Fiksi</option>
                            <option value="referensi" <?php if ($buku['kategori'] === 'referensi') echo 'selected'; ?>>Referensi</option>
                        </select>
                    </p>
                    <p class="form-actions">
                        <button type="submit">Simpan Perubahan</button>
                        <a class="text-link" href="list.php">Batal</a>
                    </p>
                </form>
            <?php endif; ?>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
