<?php
require_once __DIR__ . '/../includes/auth.php';
$page_title = 'Riwayat Transaksi';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/csrf.php';
require __DIR__ . '/../includes/koneksi.php';
require __DIR__ . '/../includes/menu.php';
require __DIR__ . '/../includes/pagination.php';

$error = $databaseError;
$riwayat = [];
$stats = [
    'total_transaksi' => 0,
    'total_porsi' => 0,
    'total_omset' => 0,
    'total_menunggu' => 0,
    'total_selesai' => 0,
];

$statusFilter = trim($_GET['status'] ?? '');

if ($pdo) {
    try {
        // Ambil data agregat ringkasan transaksi
        $stmtStats = $pdo->query("
            SELECT
                COUNT(*) AS total_transaksi,
                COALESCE(SUM(jumlah), 0) AS total_porsi,
                COALESCE(SUM(CASE WHEN status = 'selesai' THEN harga * jumlah ELSE 0 END), 0) AS total_omset,
                COALESCE(COUNT(*) FILTER (WHERE status = 'menunggu'), 0) AS total_menunggu,
                COALESCE(COUNT(*) FILTER (WHERE status = 'selesai'), 0) AS total_selesai
            FROM pesanan
        ");
        $stats = $stmtStats->fetch(PDO::FETCH_ASSOC) ?: $stats;

        // Query riwayat transaksi via JOIN multi-tabel (pesanan, anggota, users)
        $join = ' FROM pesanan p
                  JOIN anggota a ON a.id = p.anggota_id
                  LEFT JOIN users u ON u.id = p.user_id';

        $whereClauses = [];
        $params = [];

        if ($keyword !== '') {
            $whereClauses[] = '(a.nama ILIKE :kw OR a.no_anggota ILIKE :kw OR p.menu ILIKE :kw)';
            $params['kw'] = '%' . $keyword . '%';
        }

        if ($statusFilter !== '' && in_array($statusFilter, ['menunggu', 'selesai'], true)) {
            $whereClauses[] = 'p.status = :status';
            $params['status'] = $statusFilter;
        }

        $whereSql = $whereClauses ? ' WHERE ' . implode(' AND ', $whereClauses) : '';

        $selectQuery = 'SELECT p.*, a.nama AS nama_anggota, a.no_anggota, a.no_hp,
                               COALESCE(u.nama, \'Petugas Kasir\') AS nama_petugas'
                     . $join . $whereSql . ' ORDER BY p.id DESC';

        $countQuery = 'SELECT COUNT(*)' . $join . $whereSql;

        $riwayat = paginatedRows($pdo, $selectQuery, $countQuery, $params);
    } catch (PDOException $e) {
        $error = 'Gagal memuat riwayat transaksi: ' . $e->getMessage();
    }
}

include __DIR__ . '/../includes/header.php';
?>
<section class="panel">
    <h1>Riwayat Transaksi & Laporan Sirkulasi</h1>
    <p class="page-description">Laporan terpadu menghubungkan data pelanggan, pesanan, menu, dan petugas yang melayani.</p>

    <?php if ($error): ?><p class="flash flash-error" role="alert"><?php echo esc($error); ?></p><?php endif; ?>

    <!-- Kartu Ringkasan Agregat -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:16px;margin:20px 0;">
        <div style="background:#f4f7f9;border-left:4px solid #304b5e;padding:16px;border-radius:4px;">
            <span style="font-size:12px;color:#666;text-transform:uppercase;">Total Transaksi</span>
            <strong style="display:block;font-size:24px;color:#304b5e;margin-top:4px;"><?php echo (int) $stats['total_transaksi']; ?></strong>
            <small><?php echo (int) $stats['total_porsi']; ?> porsi tercatat</small>
        </div>
        <div style="background:#f4f7f9;border-left:4px solid #ed6c02;padding:16px;border-radius:4px;">
            <span style="font-size:12px;color:#666;text-transform:uppercase;">Pesanan Menunggu</span>
            <strong style="display:block;font-size:24px;color:#ed6c02;margin-top:4px;"><?php echo (int) $stats['total_menunggu']; ?></strong>
            <small>Perlu diproses / disajikan</small>
        </div>
        <div style="background:#f4f7f9;border-left:4px solid #2e7d32;padding:16px;border-radius:4px;">
            <span style="font-size:12px;color:#666;text-transform:uppercase;">Pesanan Selesai</span>
            <strong style="display:block;font-size:24px;color:#2e7d32;margin-top:4px;"><?php echo (int) $stats['total_selesai']; ?></strong>
            <small>Telah lunas dibayar</small>
        </div>
        <div style="background:#f4f7f9;border-left:4px solid #1976d2;padding:16px;border-radius:4px;">
            <span style="font-size:12px;color:#666;text-transform:uppercase;">Total Omset Selesai</span>
            <strong style="display:block;font-size:22px;color:#1976d2;margin-top:4px;">Rp <?php echo number_format($stats['total_omset'], 0, ',', '.'); ?></strong>
            <small>Pendapatan aktual</small>
        </div>
    </div>

    <!-- Filter & Pencarian -->
    <form method="get" class="search-box" style="margin-bottom:16px;">
        <label for="cari-riwayat">Cari riwayat transaksi</label>
        <input id="cari-riwayat" type="search" name="q" value="<?php echo esc($keyword); ?>" placeholder="Nama anggota, no. anggota, menu...">
        <select name="status" style="padding:7px;margin-left:8px;font:inherit;">
            <option value="">Semua Status</option>
            <option value="menunggu" <?php if ($statusFilter === 'menunggu') echo 'selected'; ?>>Menunggu</option>
            <option value="selesai" <?php if ($statusFilter === 'selesai') echo 'selected'; ?>>Selesai</option>
        </select>
        <button type="submit">Filter</button>
        <?php if ($keyword !== '' || $statusFilter !== ''): ?>
            <a class="text-link" href="riwayat.php" style="margin-left:8px;">Reset</a>
        <?php endif; ?>
    </form>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID Transaksi</th>
                    <th>Pelanggan (Anggota)</th>
                    <th>Menu Dipesan</th>
                    <th>Porsi</th>
                    <th>Subtotal</th>
                    <th>Status</th>
                    <th>Petugas Kasir</th>
                    <th>Waktu Pesan</th>
                    <th>Waktu Selesai</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!$riwayat): ?><tr><td colspan="10">Tidak ada riwayat transaksi yang cocok.</td></tr><?php endif; ?>
            <?php foreach ($riwayat as $index => $row): ?>
                <tr>
                    <td><?php echo ($page - 1) * $perPage + $index + 1; ?></td>
                    <td><strong>#<?php echo esc($row['id']); ?></strong></td>
                    <td>
                        <?php echo esc($row['nama_anggota']); ?><br>
                        <small style="color:#666;"><?php echo esc($row['no_anggota']); ?></small>
                    </td>
                    <td><?php echo esc($daftarMenu[$row['menu']]['nama'] ?? $row['menu']); ?></td>
                    <td><?php echo esc($row['jumlah']); ?> porsi</td>
                    <td><strong>Rp <?php echo number_format($row['harga'] * $row['jumlah'], 0, ',', '.'); ?></strong></td>
                    <td>
                        <?php if (($row['status'] ?? 'menunggu') === 'selesai'): ?>
                            <span class="badge" style="background:#2e7d32;color:#fff;padding:3px 8px;border-radius:4px;font-size:12px;">Selesai</span>
                        <?php else: ?>
                            <span class="badge" style="background:#ed6c02;color:#fff;padding:3px 8px;border-radius:4px;font-size:12px;">Menunggu</span>
                        <?php endif; ?>
                    </td>
                    <td><small><?php echo esc($row['nama_petugas']); ?></small></td>
                    <td><small><?php echo esc(date('d/m/Y H:i', strtotime($row['dibuat_pada']))); ?></small></td>
                    <td>
                        <small>
                            <?php echo !empty($row['selesai_pada']) ? esc(date('d/m/Y H:i', strtotime($row['selesai_pada']))) : '—'; ?>
                        </small>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php showPagination(); ?>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
