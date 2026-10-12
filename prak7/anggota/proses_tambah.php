<?php
require_once __DIR__ . '/../includes/session.php';

$nama = trim($_POST['nama'] ?? '');
$noAnggota = trim($_POST['no_anggota'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$noHp = trim($_POST['no_hp'] ?? '');

$errors = [];
if ($nama === '') {
    $errors[] = "Nama wajib diisi.";
}
if ($noAnggota === '') {
    $errors[] = "No. Anggota wajib diisi.";
}
if ($alamat === '') {
    $errors[] = "Alamat wajib diisi.";
}
if ($noHp === '') {
    $errors[] = "No. Hp wajib diisi.";
}

if (!isset($_SESSION['anggota'])) {
    $_SESSION['anggota'] = [];
}

// Cek nomor anggota unik
foreach ($_SESSION['anggota'] as $item) {
    if (strcasecmp($item['no_anggota'], $noAnggota) === 0) {
        $errors[] = "Nomor anggota sudah digunakan.";
        break;
    }
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: tambah.php');
    exit;
}

$nextId = empty($_SESSION['anggota']) ? 1 : (max(array_keys($_SESSION['anggota'])) + 1);
$_SESSION['anggota'][$nextId] = [
    'id' => $nextId,
    'nama' => $nama,
    'no_anggota' => $noAnggota,
    'alamat' => $alamat,
    'no_hp' => $noHp,
];

$_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Anggota berhasil ditambahkan.'];
header('Location: list.php');
exit;
