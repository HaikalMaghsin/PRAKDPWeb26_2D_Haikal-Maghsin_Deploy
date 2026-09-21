<?php
$page_title = "Tambah Buku";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
        <section class="panel">
            <a class="back-link" href="list.php">&larr; Kembali ke daftar buku</a>
            <h1>Tambah Buku</h1>
            <p class="page-description">Lengkapi informasi buku untuk menambahkannya ke koleksi perpustakaan.</p>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo esc($flash['type']); ?>"><?php echo esc($flash['pesan']); ?></p>
            <?php endif; ?>

            <form id="form-tambah" method="post" action="proses_tambah.php" data-validate="true" novalidate>
                <p>
                    <label for="judul">Judul</label>
                    <input type="text" id="judul" name="judul" required autofocus>
                </p>
                <p>
                    <label for="pengarang">Pengarang</label>
                    <input type="text" id="pengarang" name="pengarang" required>
                </p>
                <p>
                    <label for="tahun">Tahun Terbit</label>
                    <input type="number" id="tahun" name="tahun" min="1900" max="2026" required>
                </p>
                <p>
                    <label for="isbn">ISBN (opsional)</label>
                    <input type="text" id="isbn" name="isbn">
                </p>
                <p>
                    <label for="stok">Stok</label>
                    <input type="number" id="stok" name="stok" min="0" required>
                </p>
                <p>
                    <label for="kategori">Kategori</label>
                    <select id="kategori" name="kategori">
                        <option value="fiksi">Fiksi</option>
                        <option value="non-fiksi">Non-Fiksi</option>
                        <option value="referensi">Referensi</option>
                    </select>
                </p>
                <p class="form-actions">
                    <button type="submit">Simpan buku</button>
                    <a class="text-link" href="list.php">Batal</a>
                </p>
            </form>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
