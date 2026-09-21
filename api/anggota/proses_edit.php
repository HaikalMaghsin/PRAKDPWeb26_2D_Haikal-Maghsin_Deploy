<?php
require_once __DIR__ . '/../includes/session.php';
require __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nama = trim($_POST['nama'] ?? '');
$noAnggota = trim($_POST['no_anggota'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$noHp = trim($_POST['no_hp'] ?? '');

$errors = [];
if (!$id) $errors[] = 'ID anggota tidak valid.';
if ($nama === '') $errors[] = 'Nama wajib diisi.';
if ($noAnggota === '') $errors[] = 'No. Anggota wajib diisi.';
if ($alamat === ''){
    $errors[] = "Alamat wajib diisi.";
}
if ($noHp === ''){
    $errors[] = "No. Hp wajib diisi.";
}
if (!$pdo) {
    $errors[] = $databaseError;
}

if ($errors) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: ' . ($id ? 'edit.php?id=' . $id : 'list.php'));
    exit;
}

try {
    $stmt = $pdo->prepare(
        'UPDATE anggota SET nama = :nama, no_anggota = :no_anggota,
         alamat = :alamat, no_hp = :no_hp WHERE id = :id'
    );
    $stmt->execute([
        'id' => $id,
        'nama' => $nama,
        'no_anggota' => $noAnggota,
        'alamat' => $alamat,
        'no_hp' => $noHp,
    ]);

    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Data anggota berhasil diperbarui.'];
    header('Location: list.php');
} catch (PDOException $exception) {
    $pesan = $exception->getCode() === '23505'
        ? 'Nomor anggota sudah digunakan.'
        : 'Data anggota gagal diperbarui.';
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => $pesan];
    header('Location: edit.php?id=' . $id);
}
exit;

