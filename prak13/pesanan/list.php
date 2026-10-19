<?php
require_once __DIR__ . '/../includes/auth.php';
$page_title = 'Pesanan';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/csrf.php';
require __DIR__ . '/../includes/koneksi.php';
require __DIR__ . '/../includes/menu.php';
require __DIR__ . '/../includes/pagination.php';

$error = $databaseError;
$pesanan = [];
$anggota = [];
$edit = null;

// Satu halaman terintegrasi untuk mengelola siklus pesanan, transaksi atomik, dan stok.
if ($pdo && $_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $aksi = $_POST['aksi'] ?? 'simpan';
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    try {
        if ($aksi === 'selesai') {
            // Tandai pesanan telah selesai disajikan dan dibayar
            if (!$id || $id < 1) throw new RuntimeException('Pesanan tidak valid.');
            $stmt = $pdo->prepare("UPDATE pesanan SET status = 'selesai', selesai_pada = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$id]);
            if (!$stmt->rowCount()) throw new RuntimeException('Pesanan tidak ditemukan.');
            $_SESSION['pesan'] = 'Pesanan #' . $id . ' berhasil diselesaikan.';
        } elseif ($aksi === 'hapus') {
            // Hapus pesanan dan kembalikan stok secara atomik jika pesanan belum selesai
            if (!$id || $id < 1) throw new RuntimeException('Pesanan tidak valid.');
            $pdo->beginTransaction();
            $stmtCheck = $pdo->prepare('SELECT menu, jumlah, status FROM pesanan WHERE id = ? FOR UPDATE');
            $stmtCheck->execute([$id]);
            $rowOrder = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            if (!$rowOrder) {
                $pdo->rollBack();
                throw new RuntimeException('Pesanan tidak ditemukan.');
            }
            if ($rowOrder['status'] === 'menunggu') {
                $stmtRestore = $pdo->prepare('UPDATE menu SET stok = stok + ? WHERE kode = ?');
                $stmtRestore->execute([$rowOrder['jumlah'], $rowOrder['menu']]);
            }
            $stmtDel = $pdo->prepare('DELETE FROM pesanan WHERE id = ?');
            $stmtDel->execute([$id]);
            $pdo->commit();
            $_SESSION['pesan'] = 'Pesanan #' . $id . ' berhasil dibatalkan dan stok telah dipulihkan.';
        } else {
            // Simpan pesanan baru atau edit pesanan
            $anggotaId = filter_input(INPUT_POST, 'anggota_id', FILTER_VALIDATE_INT);
            $kode = trim($_POST['menu'] ?? '');
            $jumlah = filter_input(INPUT_POST, 'jumlah', FILTER_VALIDATE_INT);

            if (!$anggotaId || !isset($daftarMenu[$kode]) || !$jumlah || $jumlah < 1 || $jumlah > 100) {
                throw new RuntimeException('Pilih anggota, menu yang valid, dan jumlah antara 1 sampai 100.');
            }

            // Aturan Bisnis (Tugas Mandiri): Anggota dengan >= 3 pesanan berstatus 'menunggu' tidak boleh menambah pesanan baru
            if (!$id) {
                $stmtPending = $pdo->prepare("SELECT COUNT(*) FROM pesanan WHERE anggota_id = ? AND status = 'menunggu'");
                $stmtPending->execute([$anggotaId]);
                $pendingCount = (int) $stmtPending->fetchColumn();
                if ($pendingCount >= 3) {
                    throw new RuntimeException('Anggota ini masih memiliki ' . $pendingCount . ' pesanan yang belum diselesaikan. Selesaikan pesanan terdahulu terlebih dahulu.');
                }
            }

            $menuItem = $daftarMenu[$kode];
            $userId = $_SESSION['user_id'] ?? null;

            $pdo->beginTransaction();
            if ($id) {
                // Update pesanan: hitung selisih stok jika menu/jumlah berubah
                $stmtOld = $pdo->prepare('SELECT menu, jumlah, status FROM pesanan WHERE id = ? FOR UPDATE');
                $stmtOld->execute([$id]);
                $oldOrder = $stmtOld->fetch(PDO::FETCH_ASSOC);
                if (!$oldOrder) {
                    $pdo->rollBack();
                    throw new RuntimeException('Pesanan tidak ditemukan.');
                }

                // Kembalikan stok lama jika pesanan masih 'menunggu'
                if ($oldOrder['status'] === 'menunggu') {
                    $pdo->prepare('UPDATE menu SET stok = stok + ? WHERE kode = ?')->execute([$oldOrder['jumlah'], $oldOrder['menu']]);
                }

                // Kunci dan periksa ketersediaan stok menu baru
                $stmtMenuLock = $pdo->prepare('SELECT stok, harga FROM menu WHERE kode = ? FOR UPDATE');
                $stmtMenuLock->execute([$kode]);
                $stokBaru = $stmtMenuLock->fetch(PDO::FETCH_ASSOC);
                if ($stokBaru && $stokBaru['stok'] < $jumlah) {
                    $pdo->rollBack();
                    throw new RuntimeException("Sisa stok {$menuItem['nama']} tidak mencukupi (tersedia: {$stokBaru['stok']} porsi).");
                }

                // Potong stok baru
                $pdo->prepare('UPDATE menu SET stok = stok - ? WHERE kode = ?')->execute([$jumlah, $kode]);

                // Update pesanan
                $stmt = $pdo->prepare('UPDATE pesanan SET anggota_id = ?, menu = ?, harga = ?, jumlah = ? WHERE id = ?');
                $stmt->execute([$anggotaId, $kode, $menuItem['harga'], $jumlah, $id]);
            } else {
                // Transaksi atomik: Kunci baris menu dengan FOR UPDATE untuk mencegah race condition
                $stmtMenuLock = $pdo->prepare('SELECT stok, harga FROM menu WHERE kode = ? FOR UPDATE');
                $stmtMenuLock->execute([$kode]);
                $menuDb = $stmtMenuLock->fetch(PDO::FETCH_ASSOC);

                if ($menuDb && $menuDb['stok'] < $jumlah) {
                    $pdo->rollBack();
                    throw new RuntimeException("Stok {$menuItem['nama']} tidak mencukupi (sisa: {$menuDb['stok']} porsi).");
                }

                // Kurangi stok menu
                $stmtStok = $pdo->prepare('UPDATE menu SET stok = stok - ? WHERE kode = ?');
                $stmtStok->execute([$jumlah, $kode]);

                // Simpan pesanan baru dengan status 'menunggu'
                $stmt = $pdo->prepare('INSERT INTO pesanan (anggota_id, menu, harga, jumlah, status, user_id) VALUES (?, ?, ?, ?, ?, ?)');
                $stmt->execute([$anggotaId, $kode, $menuItem['harga'], $jumlah, 'menunggu', $userId]);
            }
            $pdo->commit();
            $_SESSION['pesan'] = 'Pesanan berhasil disimpan dan stok menu telah disesuaikan secara real-time.';
        }
        header('Location: list.php');
        exit;
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        $error = 'Transaksi database gagal: ' . $e->getMessage();
    } catch (RuntimeException $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        $error = $e->getMessage();
    }
}

if ($pdo) {
    try {
        // Ambil daftar anggota beserta jumlah pesanan pending
        $anggota = $pdo->query('
            SELECT a.id, a.nama, a.no_anggota,
                   COUNT(p.id) FILTER (WHERE p.status = \'menunggu\') AS pending_count
            FROM anggota a
            LEFT JOIN pesanan p ON p.anggota_id = a.id
            GROUP BY a.id, a.nama, a.no_anggota
            ORDER BY a.nama
        ')->fetchAll(PDO::FETCH_ASSOC);

        $join = ' FROM pesanan p JOIN anggota a ON a.id = p.anggota_id LEFT JOIN users u ON u.id = p.user_id';
        $where = $keyword === '' ? '' : ' WHERE a.nama ILIKE :nama OR p.menu ILIKE :menu OR p.status ILIKE :status';
        $params = $keyword === '' ? [] : [
            'nama' => '%' . $keyword . '%',
            'menu' => '%' . $keyword . '%',
            'status' => '%' . $keyword . '%',
        ];
        $pesanan = paginatedRows(
            $pdo,
            'SELECT p.*, a.nama, a.no_anggota, u.nama AS nama_petugas' . $join . $where . ' ORDER BY p.id DESC',
            'SELECT COUNT(*)' . $join . $where,
            $params
        );

        $editId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($editId) {
            $stmt = $pdo->prepare('SELECT * FROM pesanan WHERE id = ?');
            $stmt->execute([$editId]);
            $edit = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$edit) $error = 'Pesanan tidak ditemukan.';
        }
    } catch (PDOException $e) {
        $error = 'Data pesanan belum tersedia. Pastikan skema tabel sudah dijalankan.';
    }
}

// Pertahankan isian form jika submit gagal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !in_array($_POST['aksi'] ?? '', ['hapus', 'selesai'], true)) {
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
    <h1><?php echo !empty($edit['id']) ? 'Edit Pesanan' : 'Pesan Menu Baru'; ?></h1>
    <p class="page-description">Pencatatan transaksi dengan integrasi stok otomatis dan transaksi atomik database (ACID).</p>
    <?php if ($error): ?><p class="flash flash-error" role="alert"><?php echo esc($error); ?></p><?php endif; ?>
    <?php if (isset($_SESSION['pesan'])): ?>
        <p class="flash flash-success" role="status"><?php echo esc($_SESSION['pesan']); unset($_SESSION['pesan']); ?></p>
    <?php endif; ?>
    <?php if (!$anggota): ?><p>Tambahkan <a href="../anggota/tambah.php">anggota</a> dulu sebelum memesan.</p><?php endif; ?>
    <form method="post" data-validate="true" novalidate>
        <?php echo csrf_field(); ?>
        <input type="hidden" name="id" value="<?php echo esc($edit['id'] ?? ''); ?>">
        <p>
            <label for="anggota_id">Anggota Pelanggan</label>
            <select id="anggota_id" name="anggota_id" required autofocus>
                <option value="">Pilih anggota</option>
                <?php foreach ($anggota as $orang): ?>
                    <?php $terkunci = ($orang['pending_count'] >= 3 && empty($edit['id'])); ?>
                    <option value="<?php echo esc($orang['id']); ?>" <?php if (($edit['anggota_id'] ?? '') == $orang['id']) echo 'selected'; ?> <?php if ($terkunci) echo 'disabled'; ?>>
                        <?php echo esc($orang['nama'] . ' (' . $orang['no_anggota'] . ')'); ?>
                        <?php if ($orang['pending_count'] > 0): ?>
                            — [<?php echo (int) $orang['pending_count']; ?> pesanan menunggu]<?php if ($terkunci) echo ' (Maksimal tercapai)'; ?>
                        <?php endif; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="menu">Menu (Sisa Stok Real-Time)</label>
            <select id="menu" name="menu" required>
                <option value="">Pilih menu</option>
                <?php foreach ($daftarMenu as $kode => $menu): ?>
                    <option value="<?php echo esc($kode); ?>" data-harga="<?php echo (int) $menu['harga']; ?>" data-stok="<?php echo (int) $menu['stok']; ?>" <?php if (($edit['menu'] ?? '') === $kode) echo 'selected'; ?>>
                        <?php echo esc($menu['nama'] . ' — Rp ' . number_format($menu['harga'], 0, ',', '.') . ' (Stok: ' . $menu['stok'] . ' porsi)'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="jumlah">Jumlah Porsi</label>
            <input type="number" id="jumlah" name="jumlah" min="1" max="100" step="1" required value="<?php echo esc($edit['jumlah'] ?? 1); ?>">
        </p>
        <p>Perkiraan Total: <strong id="total" aria-live="polite">Pilih menu dan jumlah</strong></p>
        <p class="form-actions">
            <button type="submit" <?php if (!$anggota || !$pdo) echo 'disabled'; ?>>Simpan Transaksi</button>
            <a class="text-link" href="list.php">Batal</a>
        </p>
    </form>
</section>

<section class="panel">
    <h2>Daftar Pesanan & Status Sirkulasi</h2>
    <form method="get" class="search-box">
        <label for="cari-pesanan">Cari anggota, menu, atau status</label>
        <input id="cari-pesanan" type="search" name="q" value="<?php echo esc($keyword); ?>" placeholder="Ketik kata kunci...">
        <button type="submit">Cari</button>
    </form>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID</th>
                    <th>Anggota</th>
                    <th>Menu</th>
                    <th>Porsi</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Waktu Pesan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!$pesanan): ?><tr><td colspan="9">Belum ada data pesanan.</td></tr><?php endif; ?>
            <?php foreach ($pesanan as $index => $baris): ?>
                <tr>
                    <td><?php echo ($page - 1) * $perPage + $index + 1; ?></td>
                    <td>#<?php echo esc($baris['id']); ?></td>
                    <td>
                        <strong><?php echo esc($baris['nama']); ?></strong><br>
                        <small><?php echo esc($baris['no_anggota'] ?? ''); ?></small>
                    </td>
                    <td><?php echo esc($daftarMenu[$baris['menu']]['nama'] ?? $baris['menu']); ?></td>
                    <td><?php echo esc($baris['jumlah']); ?> porsi</td>
                    <td>Rp <?php echo number_format($baris['harga'] * $baris['jumlah'], 0, ',', '.'); ?></td>
                    <td>
                        <?php if (($baris['status'] ?? 'menunggu') === 'selesai'): ?>
                            <span class="badge" style="background:#2e7d32;color:#fff;padding:3px 8px;border-radius:4px;font-size:12px;">Selesai</span>
                        <?php else: ?>
                            <span class="badge" style="background:#ed6c02;color:#fff;padding:3px 8px;border-radius:4px;font-size:12px;">Menunggu</span>
                        <?php endif; ?>
                    </td>
                    <td><small><?php echo esc(date('d/m/Y H:i', strtotime($baris['dibuat_pada']))); ?></small></td>
                    <td class="aksi">
                        <?php if (($baris['status'] ?? 'menunggu') === 'menunggu'): ?>
                            <form method="post" style="display:inline;" onsubmit="return confirm('Tandai pesanan ini sudah selesai dan dibayar?');">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="id" value="<?php echo esc($baris['id']); ?>">
                                <input type="hidden" name="aksi" value="selesai">
                                <button class="btn-aksi" style="background:#2e7d32;color:white;" type="submit">Selesaikan</button>
                            </form>
                            <a class="btn-aksi btn-edit" href="?id=<?php echo esc($baris['id']); ?>">Edit</a>
                        <?php endif; ?>
                        <form method="post" class="form-hapus" data-nama="pesanan #<?php echo esc($baris['id']); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="id" value="<?php echo esc($baris['id']); ?>">
                            <input type="hidden" name="aksi" value="hapus">
                            <button class="btn-aksi btn-hapus" type="submit">Batalkan</button>
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
