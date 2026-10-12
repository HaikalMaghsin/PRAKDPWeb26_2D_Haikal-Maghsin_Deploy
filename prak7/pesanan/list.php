<?php
$page_title = 'Pesanan';
require_once __DIR__ . '/../includes/session.php';
require __DIR__ . '/../includes/menu.php';

$error = null;
$edit = null;

if (!isset($_SESSION['pesanan'])) {
    $_SESSION['pesanan'] = [];
}
if (!isset($_SESSION['anggota'])) {
    $_SESSION['anggota'] = [];
}

// Satu halaman untuk menampilkan, menambah, mengedit, dan menghapus pesanan (berbasis SESSION).
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $aksi = $_POST['aksi'] ?? 'simpan';
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    try {
        if ($aksi === 'hapus') {
            if (!$id || !isset($_SESSION['pesanan'][$id])) {
                throw new RuntimeException('Pesanan tidak ditemukan.');
            }
            unset($_SESSION['pesanan'][$id]);
            $_SESSION['pesan'] = 'Pesanan berhasil dihapus.';
        } else {
            $anggotaId = filter_var($_POST['anggota_id'] ?? null, FILTER_VALIDATE_INT);
            $kode = $_POST['menu'] ?? '';
            $jumlah = filter_var($_POST['jumlah'] ?? null, FILTER_VALIDATE_INT);
            if (!$anggotaId || !isset($_SESSION['anggota'][$anggotaId]) || !isset($daftarMenu[$kode]) || !$jumlah || $jumlah < 1 || $jumlah > 100) {
                throw new RuntimeException('Pilih anggota, menu, dan jumlah antara 1 sampai 100.');
            }
            $menu = $daftarMenu[$kode];
            if ($id) {
                if (!isset($_SESSION['pesanan'][$id])) {
                    throw new RuntimeException('Pesanan tidak ditemukan.');
                }
                $_SESSION['pesanan'][$id] = [
                    'id' => $id,
                    'anggota_id' => $anggotaId,
                    'menu' => $kode,
                    'harga' => $menu['harga'],
                    'jumlah' => $jumlah,
                ];
            } else {
                $nextId = empty($_SESSION['pesanan']) ? 1 : (max(array_keys($_SESSION['pesanan'])) + 1);
                $_SESSION['pesanan'][$nextId] = [
                    'id' => $nextId,
                    'anggota_id' => $anggotaId,
                    'menu' => $kode,
                    'harga' => $menu['harga'],
                    'jumlah' => $jumlah,
                ];
            }
            $_SESSION['pesan'] = 'Pesanan berhasil disimpan.';
        }
        header('Location: list.php');
        exit;
    } catch (RuntimeException $e) {
        $error = $e->getMessage();
    }
}

$anggota = array_values($_SESSION['anggota']);
usort($anggota, fn($a, $b) => strcmp($a['nama'], $b['nama']));

$pesanan = [];
foreach ($_SESSION['pesanan'] as $p) {
    $item = $p;
    $item['nama'] = $_SESSION['anggota'][$p['anggota_id']]['nama'] ?? 'Anggota (' . $p['anggota_id'] . ')';
    $pesanan[] = $item;
}
usort($pesanan, fn($a, $b) => $b['id'] <=> $a['id']);

$editId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if ($editId) {
    if (isset($_SESSION['pesanan'][$editId])) {
        $edit = $_SESSION['pesanan'][$editId];
    } else {
        $error = 'Pesanan tidak ditemukan.';
    }
}

// Pertahankan isian jika penyimpanan gagal.
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['aksi'] ?? '') !== 'hapus') {
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
        <p class="form-actions"><button type="submit" <?php if (!$anggota) echo 'disabled'; ?>>Simpan Pesanan</button> <a href="list.php">Batal</a></p>
    </form>
</section>
<section class="panel">
    <h2>Daftar Pesanan</h2>
    <div class="table-responsive">
        <table>
            <thead><tr><th>No.</th><th>Anggota</th><th>Menu</th><th>Jumlah</th><th>Total</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php if (!$pesanan): ?><tr><td colspan="6">Belum ada pesanan.</td></tr><?php endif; ?>
            <?php foreach ($pesanan as $index => $baris): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo esc($baris['nama'] ?? ''); ?></td>
                    <td><?php echo esc($daftarMenu[$baris['menu'] ?? '']['nama'] ?? ($baris['menu'] ?? '')); ?></td>
                    <td><?php echo esc($baris['jumlah'] ?? 0); ?></td>
                    <td>Rp <?php echo number_format(($baris['harga'] ?? 0) * ($baris['jumlah'] ?? 0), 0, ',', '.'); ?></td>
                    <td class="aksi">
                        <a class="btn-aksi btn-edit" href="?id=<?php echo esc($baris['id'] ?? ''); ?>">Edit</a>
                        <form method="post" class="form-hapus" data-nama="pesanan ini">
                            <input type="hidden" name="id" value="<?php echo esc($baris['id'] ?? ''); ?>">
                            <input type="hidden" name="aksi" value="hapus">
                            <button class="btn-aksi btn-hapus" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
