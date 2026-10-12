<?php
require_once __DIR__ . '/../includes/session.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: list.php');
    exit;
}

$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
$aksi = $_POST['aksi'] ?? 'edit';

if ($aksi === 'hapus') {
    if (!$id || !isset($_SESSION['anggota'][$id])) {
        $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Data anggota tidak ditemukan.'];
        header('Location: list.php');
        exit;
    }

    // Periksa apakah anggota ini punya pesanan aktif di session
    $punyaPesanan = false;
    foreach (($_SESSION['pesanan'] ?? []) as $pesanan) {
        if ($pesanan['anggota_id'] == $id) {
            $punyaPesanan = true;
            break;
        }
    }

    if ($punyaPesanan) {
        $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Anggota gagal dihapus. Hapus pesanan anggota ini terlebih dahulu jika masih ada.'];
    } else {
        unset($_SESSION['anggota'][$id]);
        $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Anggota berhasil dihapus.'];
    }

    header('Location: list.php');
    exit;
}

$nama = trim($_POST['nama'] ?? '');
$noAnggota = trim($_POST['no_anggota'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$noHp = trim($_POST['no_hp'] ?? '');

$errors = [];
if (!$id || !isset($_SESSION['anggota'][$id])) $errors[] = 'ID anggota tidak valid.';
if ($nama === '') $errors[] = 'Nama wajib diisi.';
if ($noAnggota === '') $errors[] = 'No. Anggota wajib diisi.';
if ($alamat === '') $errors[] = 'Alamat wajib diisi.';
if ($noHp === '') $errors[] = 'No. Hp wajib diisi.';

// Cek nomor anggota unik selain dirinya sendiri
foreach (($_SESSION['anggota'] ?? []) as $checkId => $item) {
    if ($checkId != $id && strcasecmp($item['no_anggota'], $noAnggota) === 0) {
        $errors[] = 'Nomor anggota sudah digunakan.';
        break;
    }
}

if ($errors) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: ' . ($id ? 'edit.php?id=' . $id : 'list.php'));
    exit;
}

$_SESSION['anggota'][$id] = [
    'id' => $id,
    'nama' => $nama,
    'no_anggota' => $noAnggota,
    'alamat' => $alamat,
    'no_hp' => $noHp,
];

$_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Data anggota berhasil diperbarui.'];
header('Location: list.php');
exit;
