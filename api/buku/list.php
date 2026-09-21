<?php
$page_title = "Daftar Buku";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$daftarBuku = $pdo ? $pdo->query("SELECT * FROM buku ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC) : [];
?>
        <section class="panel">
            <h1>Daftar Buku</h1>
            <p class="page-description">Jelajahi koleksi perpustakaan dan cek stok buku sebelum berkunjung.</p>

            <?php if ($databaseError): ?>
                <p class="flash flash-error"><?php echo esc($databaseError); ?></p>
            <?php endif; ?>

            <a class="btn-tambah" href="tambah.php">+ Tambah Buku</a>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo esc($flash['type']); ?>"><?php echo esc($flash['pesan']); ?></p>
            <?php endif; ?>

            <div class="search-box">
                <label for="search-input">Cari buku</label>
                <input type="search" id="search-input" placeholder="Ketik judul atau nama pengarang..." aria-controls="tabel-data">
            </div>
            <p class="filter-status" id="filter-status" role="status" hidden></p>

            <div class="table-responsive">
            <table id="tabel-data">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Pengarang</th>
                        <th>Tahun</th>
                        <th>Stok</th>
                        <th>Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($daftarBuku)): ?>
                    <tr>
                        <td colspan="5">Belum ada data buku. Silakan tambah lewat menu "Tambah Buku".</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($daftarBuku as $buku): ?>
                        <tr>
                            <td><?php echo esc($buku['judul']); ?></td>
                            <td><?php echo esc($buku['pengarang']); ?></td>
                            <td><?php echo esc($buku['tahun']); ?></td>
                            <td><?php echo esc($buku['stok']); ?></td>
                            <td><?php echo esc($buku['kategori']); ?></td>
                            <td>
                                <a class="btn-aksi btn-edit" href="edit.php?id=<?php echo esc($buku['id']); ?>">Edit</a>
                                <form class="form-hapus" method="post" action="proses_hapus.php" data-nama="<?php echo esc($buku['judul']); ?>">
                                    <input type="hidden" name="id" value="<?php echo esc($buku['id']); ?>">
                                    <button type="submit" class="btn-aksi btn-hapus">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
