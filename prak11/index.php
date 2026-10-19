<?php
$page_title = 'Menu';
include __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/menu.php';
?>
<section class="panel">
    <h1>Ayam Geprek Kita</h1>
    <p>Ayam geprek dengan nasi hangat. Pilih menu, lalu catat pesanan anggota.</p>
    <p>Buka setiap hari, pukul 10.00–21.00.</p>
    <h2>Daftar Menu</h2>
    <div class="table-responsive">
        <table>
            <thead><tr><th>Menu</th><th>Harga</th></tr></thead>
            <tbody>
            <?php foreach ($daftarMenu as $menu): ?>
                <tr><td><?php echo esc($menu['nama']); ?></td><td>Rp <?php echo number_format($menu['harga'], 0, ',', '.'); ?></td></tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <p>Semua menu sudah termasuk nasi. Harga untuk satu porsi.</p>
    <a class="btn-tambah" href="pesanan/list.php">Pesan Menu</a>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
