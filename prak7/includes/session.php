<?php
$sessionPath = sys_get_temp_dir();

if (session_status() === PHP_SESSION_NONE) {
    session_name('praktikum7');
    session_save_path($sessionPath);
    session_start();
}

// Inisialisasi data awal session Geprek Kita (Praktikum 7) jika belum diset
if (!isset($_SESSION['prak7_initialized'])) {
    $_SESSION['prak7_initialized'] = true;

    if (!isset($_SESSION['anggota']) || empty($_SESSION['anggota'])) {
        $_SESSION['anggota'] = [
            1 => [
                'id' => 1,
                'no_anggota' => 'MBR-001',
                'nama' => 'Haikal Maghsin',
                'alamat' => 'Malang',
                'no_hp' => '081234567890',
            ],
            2 => [
                'id' => 2,
                'no_anggota' => 'MBR-002',
                'nama' => 'Siti Aminah',
                'alamat' => 'Surabaya',
                'no_hp' => '082345678901',
            ],
        ];
    }

    if (!isset($_SESSION['pesanan']) || empty($_SESSION['pesanan'])) {
        $_SESSION['pesanan'] = [
            1 => [
                'id' => 1,
                'anggota_id' => 1,
                'menu' => 'original',
                'harga' => 15000,
                'jumlah' => 2,
            ],
            2 => [
                'id' => 2,
                'anggota_id' => 2,
                'menu' => 'keju',
                'harga' => 18000,
                'jumlah' => 1,
            ],
        ];
    }
}

// Normalisasi otomatis data anggota lama jika ada item yang belum memiliki ID
if (isset($_SESSION['anggota']) && is_array($_SESSION['anggota'])) {
    $normalizedAnggota = [];
    $idCounter = 1;
    foreach ($_SESSION['anggota'] as $key => $item) {
        if (!is_array($item)) continue;
        $id = (isset($item['id']) && is_numeric($item['id']) && $item['id'] > 0)
            ? (int)$item['id']
            : (is_numeric($key) && $key > 0 ? (int)$key : $idCounter);
        $item['id'] = $id;
        $normalizedAnggota[$id] = $item;
        $idCounter = max($idCounter, $id + 1);
    }
    $_SESSION['anggota'] = $normalizedAnggota;
}

// Normalisasi otomatis data pesanan lama jika ada item yang belum memiliki ID
if (isset($_SESSION['pesanan']) && is_array($_SESSION['pesanan'])) {
    $normalizedPesanan = [];
    $idCounter = 1;
    foreach ($_SESSION['pesanan'] as $key => $item) {
        if (!is_array($item)) continue;
        $id = (isset($item['id']) && is_numeric($item['id']) && $item['id'] > 0)
            ? (int)$item['id']
            : (is_numeric($key) && $key > 0 ? (int)$key : $idCounter);
        $item['id'] = $id;
        $normalizedPesanan[$id] = $item;
        $idCounter = max($idCounter, $id + 1);
    }
    $_SESSION['pesanan'] = $normalizedPesanan;
}
