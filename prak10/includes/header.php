<?php
require_once __DIR__ . '/session.php';
$base = '/prak10/';
function esc($value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Geprek Kita | <?php echo esc($page_title ?? 'Beranda'); ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<header>
    <strong>Geprek Kita</strong>
    <nav aria-label="Menu utama">
        <a href="<?php echo $base; ?>index.php" <?php if (($page_title ?? '') === 'Menu') echo 'aria-current="page"'; ?>>Menu</a>
        <?php if (isset($_SESSION['user_id'])): ?>
        <a href="<?php echo $base; ?>anggota/list.php">Anggota</a>
        <a href="<?php echo $base; ?>pesanan/list.php">Pesanan</a>
        <span class="auth-status"><?php echo esc($_SESSION['nama']); ?></span>
        <a href="<?php echo $base; ?>auth/logout.php">Logout</a>
        <?php else: ?>
        <a href="<?php echo $base; ?>auth/login.php">Login Petugas</a>
        <?php endif; ?>
    </nav>
</header>
<main id="konten">
