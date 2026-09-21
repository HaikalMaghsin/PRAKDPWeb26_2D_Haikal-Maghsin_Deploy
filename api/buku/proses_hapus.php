<?php
require_once __DIR__ . '/../includes/session.php';
require __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$pdo || !$id) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => $pdo ? 'ID buku tidak valid.' : $databaseError];
    header('Location: list.php');
    exit;
}

try {
    $stmt = $pdo->prepare('DELETE FROM buku WHERE id = :id');
    $stmt->execute(['id' => $id]);

    $_SESSION['flash'] = $stmt->rowCount() > 0
        ? ['type' => 'success', 'pesan' => 'Buku berhasil dihapus.']
        : ['type' => 'error', 'pesan' => 'Data buku tidak ditemukan.'];
} catch (Throwable $exception) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Buku gagal dihapus.'];
}

header('Location: list.php');
exit;

