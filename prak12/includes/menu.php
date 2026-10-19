<?php
// Daftar menu dan stok Geprek Kita.
// Secara otomatis tersinkron dengan tabel database `menu` bila database aktif,
// dengan nilai fallback jika database belum terhubung.

$daftarMenu = [
    'original' => ['nama' => 'Geprek Original', 'harga' => 15000, 'stok' => 50],
    'keju' => ['nama' => 'Geprek Keju', 'harga' => 18000, 'stok' => 35],
    'sambal_ijo' => ['nama' => 'Geprek Sambal Ijo', 'harga' => 16000, 'stok' => 40],
];

if (isset($pdo) && $pdo) {
    try {
        $stmtMenu = $pdo->query('SELECT kode, nama, harga, stok FROM menu ORDER BY id');
        $rowsMenu = $stmtMenu->fetchAll(PDO::FETCH_ASSOC);
        if ($rowsMenu) {
            $daftarMenu = [];
            foreach ($rowsMenu as $row) {
                $daftarMenu[$row['kode']] = [
                    'nama' => $row['nama'],
                    'harga' => (int) $row['harga'],
                    'stok' => (int) $row['stok'],
                ];
            }
        }
    } catch (Throwable $e) {
        // Fallback ke array tetap bila tabel menu belum siap.
    }
}
