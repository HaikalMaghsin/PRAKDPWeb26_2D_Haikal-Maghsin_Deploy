<?php
require_once __DIR__ . '/session.php';
$base = '/prak9/';
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
        <a href="<?php echo $base; ?>anggota/list.php" <?php if (str_contains($page_title ?? '', 'Anggota')) echo 'aria-current="page"'; ?>>Anggota</a>
        <a href="<?php echo $base; ?>pesanan/list.php" <?php if (($page_title ?? '') === 'Pesanan') echo 'aria-current="page"'; ?>>Pesanan</a>
    </nav>
</header>
<main id="konten">
