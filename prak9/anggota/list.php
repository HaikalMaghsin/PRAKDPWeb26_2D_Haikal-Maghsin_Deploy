<?php
$page_title = "Daftar Anggota";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';
require __DIR__ . '/../includes/pagination.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$daftarAnggota = [];
if ($pdo) {
    try {
        $where = $keyword === '' ? '' : ' WHERE nama ILIKE :nama OR no_anggota ILIKE :nomor';
        $params = $keyword === '' ? [] : ['nama' => '%' . $keyword . '%', 'nomor' => '%' . $keyword . '%'];
        $daftarAnggota = paginatedRows($pdo, 'SELECT * FROM anggota' . $where . ' ORDER BY id DESC',
            'SELECT COUNT(*) FROM anggota' . $where, $params);
    } catch (PDOException $exception) {
        $databaseError = 'Tabel anggota belum tersedia. Jalankan file sql/02_geprek.sql terlebih dahulu.';
    }
}
?>
        <section class="panel">
            <h1>Daftar Anggota</h1>
            <p class="page-description">Daftar anggota pelanggan Geprek Kita.</p>

            <?php if ($databaseError): ?>
                <p class="flash flash-error"><?php echo esc($databaseError); ?></p>
            <?php endif; ?>

            <a class="btn-tambah" href="tambah.php">+ Tambah Anggota</a>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo esc($flash['type']); ?>"><?php echo esc($flash['pesan']); ?></p>
            <?php endif; ?>

            <div class="search-box">
                <label for="search-input">Cari Nama Anggota</label>
                <form method="get">
                    <input type="search" name="q" value="<?php echo esc($keyword); ?>" placeholder="Ketik nama atau nomor anggota...">
                    <button type="submit">Cari</button>
                </form>
            </div>
            <p class="filter-status" id="filter-status" role="status" hidden></p>

            <div class="table-responsive">
            <table id="tabel-data">
                <thead>
                    <tr>
                        <th>No. Anggota</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No. HP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($daftarAnggota)): ?>
                    <tr>
                        <td colspan="5">Belum ada data anggota. Silakan tambah lewat menu "Tambah Anggota".</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($daftarAnggota as $anggota): ?>
                        <tr>
                            <td><?php echo esc($anggota['no_anggota']); ?></td>
                            <td><?php echo esc($anggota['nama']); ?></td>
                            <td><?php echo esc($anggota['alamat']); ?></td>
                            <td><?php echo esc($anggota['no_hp']); ?></td>
                            <td>
                                <a class="btn-aksi btn-edit" href="edit.php?id=<?php echo esc($anggota['id']); ?>">Edit</a>
                                <form class="form-hapus" method="post" action="proses_edit.php" data-nama="<?php echo esc($anggota['nama']); ?>">
                                    <input type="hidden" name="aksi" value="hapus">
                                    <input type="hidden" name="id" value="<?php echo esc($anggota['id']); ?>">
                                    <button type="submit" class="btn-aksi btn-hapus">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
            <?php showPagination(); ?>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
