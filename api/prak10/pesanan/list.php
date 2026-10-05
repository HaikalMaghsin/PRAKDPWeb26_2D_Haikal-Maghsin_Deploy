<?php
require_once __DIR__ . '/../includes/auth.php';
$page_title = 'Pesanan';
require_once __DIR__ . '/../includes/session.php';
require __DIR__ . '/../includes/koneksi.php';
require __DIR__ . '/../includes/menu.php';
require __DIR__ . '/../includes/pagination.php';

$error = $databaseError;
$pesanan = [];
$anggota = [];
$edit = null;

// Satu halaman untuk menampilkan, menambah, mengedit, dan menghapus pesanan.
if ($pdo && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? 'simpan';
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    try {
        if ($aksi === 'hapus') {
            if (!$id || $id < 1) throw new RuntimeException('Pesanan tidak valid.');
            $stmt = $pdo->prepare('DELETE FROM pesanan WHERE id = ?');
            $stmt->execute([$id]);
            if (!$stmt->rowCount()) throw new RuntimeException('Pesanan tidak ditemukan.');
            $_SESSION['pesan'] = 'Pesanan berhasil dihapus.';
        } else {
            $anggotaId = filter_input(INPUT_POST, 'anggota_id', FILTER_VALIDATE_INT);
            $kode = $_POST['menu'] ?? '';
            $jumlah = filter_input(INPUT_POST, 'jumlah', FILTER_VALIDATE_INT);
            if (!$anggotaId || !isset($daftarMenu[$kode]) || !$jumlah || $jumlah < 1 || $jumlah > 100) {
                throw new RuntimeException('Pilih anggota, menu, dan jumlah antara 1 sampai 100.');
            }
            // Harga diambil dari PHP supaya total tidak bisa diganti melalui browser.
            $menu = $daftarMenu[$kode];
            if ($id) {
                $stmt = $pdo->prepare('UPDATE pesanan SET anggota_id = ?, menu = ?, harga = ?, jumlah = ? WHERE id = ?');
                $stmt->execute([$anggotaId, $kode, $menu['harga'], $jumlah, $id]);
                if (!$stmt->rowCount()) throw new RuntimeException('Pesanan tidak ditemukan.');
            } else {
                $stmt = $pdo->prepare('INSERT INTO pesanan (anggota_id, menu, harga, jumlah) VALUES (?, ?, ?, ?)');
                $stmt->execute([$anggotaId, $kode, $menu['harga'], $jumlah]);
            }
            $_SESSION['pesan'] = 'Pesanan berhasil disimpan.';
        }
        header('Location: list.php');
        exit;
    } catch (PDOException $e) {
        $error = 'Pesanan gagal diproses. Pastikan tabel pesanan dan anggota sudah tersedia.';
    } catch (RuntimeException $e) {
        $error = $e->getMessage();
    }
}

if ($pdo) {
    try {
        $anggota = $pdo->query('SELECT id, nama, no_anggota FROM anggota ORDER BY nama')->fetchAll(PDO::FETCH_ASSOC);
        $join = ' FROM pesanan p JOIN anggota a ON a.id = p.anggota_id';
        $where = $keyword === '' ? '' : ' WHERE a.nama ILIKE :nama OR p.menu ILIKE :menu';
        $params = $keyword === '' ? [] : ['nama' => '%' . $keyword . '%', 'menu' => '%' . $keyword . '%'];
        $pesanan = paginatedRows($pdo, 'SELECT p.*, a.nama' . $join . $where . ' ORDER BY p.id DESC',
            'SELECT COUNT(*)' . $join . $where, $params);
        $editId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($editId) {
            $stmt = $pdo->prepare('SELECT * FROM pesanan WHERE id = ?');
            $stmt->execute([$editId]);
            $edit = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$edit) $error = 'Pesanan tidak ditemukan.';
        }
    } catch (PDOException $e) {
        $error = 'Data pesanan belum tersedia. Pengelola perlu menyiapkan tabel database.';
    }
}
// Pertahankan isian jika penyimpanan gagal.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['aksi'] ?? '') !== 'hapus') {
    $edit = [
        'id' => $_POST['id'] ?? '',
        'anggota_id' => $_POST['anggota_id'] ?? '',
        'menu' => $_POST['menu'] ?? '',
        'jumlah' => $_POST['jumlah'] ?? 1,
    ];
}
include __DIR__ . '/../includes/header.php';
?>
<section class="panel">
    <h1><?php echo !empty($edit['id']) ? 'Edit Pesanan' : 'Pesan Menu'; ?></h1>
    <?php if ($error): ?><p class="flash flash-error" role="alert"><?php echo esc($error); ?></p><?php endif; ?>
    <?php if (isset($_SESSION['pesan'])): ?>
        <p class="flash" role="status"><?php echo esc($_SESSION['pesan']); unset($_SESSION['pesan']); ?></p>
    <?php endif; ?>
    <?php if (!$anggota): ?><p>Tambahkan <a href="../anggota/tambah.php">anggota</a> dulu sebelum memesan.</p><?php endif; ?>
    <form method="post" data-validate="true" novalidate>
        <input type="hidden" name="id" value="<?php echo esc($edit['id'] ?? ''); ?>">
        <p>
            <label for="anggota_id">Anggota</label>
            <select id="anggota_id" name="anggota_id" required autofocus>
                <option value="">Pilih anggota</option>
                <?php foreach ($anggota as $orang): ?>
                    <option value="<?php echo esc($orang['id']); ?>" <?php if (($edit['anggota_id'] ?? '') == $orang['id']) echo 'selected'; ?>><?php echo esc($orang['nama'] . ' (' . $orang['no_anggota'] . ')'); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="menu">Menu</label>
            <select id="menu" name="menu" required>
                <option value="">Pilih menu</option>
                <?php foreach ($daftarMenu as $kode => $menu): ?>
                    <option value="<?php echo esc($kode); ?>" data-harga="<?php echo $menu['harga']; ?>" <?php if (($edit['menu'] ?? '') === $kode) echo 'selected'; ?>><?php echo esc($menu['nama']); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p><label for="jumlah">Jumlah porsi</label><input type="number" id="jumlah" name="jumlah" min="1" max="100" step="1" required value="<?php echo esc($edit['jumlah'] ?? 1); ?>"></p>
        <p>Total: <strong id="total" aria-live="polite">Pilih menu dan jumlah</strong></p>
        <p class="form-actions"><button type="submit" <?php if (!$anggota || !$pdo) echo 'disabled'; ?>>Simpan Pesanan</button> <a href="list.php">Batal</a></p>
    </form>
</section>
<section class="panel">
    <h2>Daftar Pesanan</h2>
    <form method="get" class="search-box">
        <label for="cari-pesanan">Cari anggota atau menu</label>
        <input id="cari-pesanan" type="search" name="q" value="<?php echo esc($keyword); ?>">
        <button type="submit">Cari</button>
    </form>
    <div class="table-responsive">
        <table>
            <thead><tr><th>No.</th><th>Anggota</th><th>Menu</th><th>Jumlah</th><th>Total</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php if (!$pesanan): ?><tr><td colspan="6">Belum ada pesanan.</td></tr><?php endif; ?>
            <?php foreach ($pesanan as $index => $baris): ?>
                <tr>
                    <td><?php echo ($page - 1) * $perPage + $index + 1; ?></td>
                    <td><?php echo esc($baris['nama']); ?></td>
                    <td><?php echo esc($daftarMenu[$baris['menu']]['nama'] ?? $baris['menu']); ?></td>
                    <td><?php echo esc($baris['jumlah']); ?></td>
                    <td>Rp <?php echo number_format($baris['harga'] * $baris['jumlah'], 0, ',', '.'); ?></td>
                    <td class="aksi">
                        <a class="btn-aksi btn-edit" href="?id=<?php echo esc($baris['id']); ?>">Edit</a>
                        <form method="post" class="form-hapus" data-nama="pesanan ini">
                            <input type="hidden" name="id" value="<?php echo esc($baris['id']); ?>">
                            <input type="hidden" name="aksi" value="hapus">
                            <button class="btn-aksi btn-hapus" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php showPagination(); ?>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
