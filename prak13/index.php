<?php
$page_title = 'Beranda';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/helpers.php';
require __DIR__ . '/includes/koneksi.php';
require __DIR__ . '/includes/menu.php';
include __DIR__ . '/includes/header.php';

$totalMenu = count($daftarMenu);
$totalStok = array_sum(array_column($daftarMenu, 'stok'));
$totalAnggota = 0;
$pesananAktif = 0;
$pesananSelesai = 0;
$totalOmset = 0;

if (isset($pdo) && $pdo) {
    try {
        $totalAnggota = (int) $pdo->query('SELECT COUNT(*) FROM anggota')->fetchColumn();
        $pesananAktif = (int) $pdo->query("SELECT COUNT(*) FROM pesanan WHERE status = 'menunggu'")->fetchColumn();
        $stmtSelesai = $pdo->query("SELECT COUNT(*), COALESCE(SUM(harga * jumlah), 0) FROM pesanan WHERE status = 'selesai'");
        $rowS = $stmtSelesai->fetch(PDO::FETCH_NUM);
        $pesananSelesai = (int) ($rowS[0] ?? 0);
        $totalOmset = (int) ($rowS[1] ?? 0);
    } catch (Throwable $e) {}
}
?>
<section class="panel">
    <h1>Ayam Geprek Kita — Dashboard Sistem</h1>
    <p>Aplikasi operasional pencatatan pelanggan, pengelolaan stok makanan, dan transaksi pesanan ayam geprek.</p>

    <!-- Kartu Statistik Real-Time dari Basis Data (Jobsheet 12) -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:16px;margin:24px 0;">
        <div style="background:#f4f7f9;border-left:4px solid #304b5e;padding:16px;border-radius:4px;">
            <span style="font-size:12px;color:#666;text-transform:uppercase;">Varian Menu</span>
            <strong style="display:block;font-size:24px;color:#304b5e;margin-top:4px;"><?php echo $totalMenu; ?> Menu</strong>
            <small><?php echo $totalStok; ?> porsi stok fisik</small>
        </div>
        <div style="background:#f4f7f9;border-left:4px solid #1976d2;padding:16px;border-radius:4px;">
            <span style="font-size:12px;color:#666;text-transform:uppercase;">Pelanggan Terdaftar</span>
            <strong style="display:block;font-size:24px;color:#1976d2;margin-top:4px;"><?php echo $totalAnggota; ?> Orang</strong>
            <small>Anggota aktif</small>
        </div>
        <div style="background:#f4f7f9;border-left:4px solid #ed6c02;padding:16px;border-radius:4px;">
            <span style="font-size:12px;color:#666;text-transform:uppercase;">Pesanan Menunggu</span>
            <strong style="display:block;font-size:24px;color:#ed6c02;margin-top:4px;"><?php echo $pesananAktif; ?> Antrean</strong>
            <small>Perlu disajikan</small>
        </div>
        <div style="background:#f4f7f9;border-left:4px solid #2e7d32;padding:16px;border-radius:4px;">
            <span style="font-size:12px;color:#666;text-transform:uppercase;">Omset Penjualan</span>
            <strong style="display:block;font-size:22px;color:#2e7d32;margin-top:4px;">Rp <?php echo number_format($totalOmset, 0, ',', '.'); ?></strong>
            <small><?php echo $pesananSelesai; ?> transaksi selesai</small>
        </div>
    </div>

    <h2>Katalog Menu & Ketersediaan Stok</h2>
    <div class="table-responsive">
        <table>
            <thead><tr><th>Menu</th><th>Harga</th><th>Sisa Stok</th><th>Kondisi</th></tr></thead>
            <tbody>
            <?php foreach ($daftarMenu as $menu): ?>
                <tr>
                    <td><strong><?php echo esc($menu['nama']); ?></strong></td>
                    <td>Rp <?php echo number_format($menu['harga'], 0, ',', '.'); ?></td>
                    <td><?php echo (int) $menu['stok']; ?> porsi</td>
                    <td>
                        <?php if ($menu['stok'] > 10): ?>
                            <span style="color:#2e7d32;font-weight:bold;">Tersedia</span>
                        <?php elseif ($menu['stok'] > 0): ?>
                            <span style="color:#ed6c02;font-weight:bold;">Menipis</span>
                        <?php else: ?>
                            <span style="color:#d32f2f;font-weight:bold;">Habis</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <p>Semua menu sudah termasuk nasi hangat. Harga untuk satu porsi.</p>
    <div style="margin-top:16px;">
        <a class="btn-tambah" href="pesanan/list.php">Kelola Pesanan</a>
        <a class="btn-tambah" style="background:#496374;margin-left:8px;" href="pesanan/riwayat.php">Lihat Riwayat Transaksi</a>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
