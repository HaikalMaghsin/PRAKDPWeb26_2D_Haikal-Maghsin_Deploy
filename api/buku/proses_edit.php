<?php
require_once __DIR__ . '/../includes/session.php';
require __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$aksi = $_POST['aksi'] ?? 'edit';

// Proses hapus digabung di file ini agar jumlah Serverless Function lebih sedikit.
if ($aksi === 'hapus') {
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
}

$judul = trim($_POST['judul'] ?? '');
$pengarang = trim($_POST['pengarang'] ?? '');
$tahun = $_POST['tahun'] ?? '';
$isbn = trim($_POST['isbn'] ?? '');
$stok = $_POST['stok'] ?? '';
$kategori = trim($_POST['kategori'] ?? '');
$kategoriValid = ['fiksi', 'non-fiksi', 'referensi'];

$errors = [];
if (!$id) $errors[] = 'ID buku tidak valid.';
if ($judul === '') $errors[] = 'Judul wajib diisi.';
if ($pengarang === '') $errors[] = 'Pengarang wajib diisi.';
if (!is_numeric($tahun) || $tahun < 1900 || $tahun > 2026) $errors[] = 'Tahun harus di antara 1900-2026.';
if (!is_numeric($stok) || $stok < 0) $errors[] = 'Stok tidak boleh negatif.';
if (!in_array($kategori, $kategoriValid, true)) $errors[] = 'Kategori tidak valid.';

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
        'UPDATE buku SET judul = :judul, pengarang = :pengarang, tahun = :tahun,
         isbn = :isbn, stok = :stok, kategori = :kategori WHERE id = :id'
    );
    $stmt->execute([
        'id' => $id,
        'judul' => $judul,
        'pengarang' => $pengarang,
        'tahun' => (int) $tahun,
        'isbn' => $isbn,
        'stok' => (int) $stok,
        'kategori' => $kategori,
    ]);

    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Data buku berhasil diperbarui.'];
    header('Location: list.php');
} catch (Throwable $exception) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Data buku gagal diperbarui.'];
    header('Location: edit.php?id=' . $id);
}
exit;
