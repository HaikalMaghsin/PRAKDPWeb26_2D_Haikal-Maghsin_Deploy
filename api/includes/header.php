<?php
require_once __DIR__ . '/session.php';

// Prefix relatif ke root proyek ini (bukan root domain) — supaya
// /assets, /index.php, dst tetap benar walau proyek diakses lewat
// subfolder (mis. dp2026.test/kode-praktikum/jobsheet-08/), bukan cuma
// lewat vhost yang document root-nya langsung folder ini.
$__jobsheetRoot = dirname(__DIR__);
$__scriptDir = dirname($_SERVER['SCRIPT_FILENAME']);
$__rel = ltrim(str_replace('\\', '/', substr($__scriptDir, strlen($__jobsheetRoot))), '/');
$base = $__rel === '' ? '' : str_repeat('../', substr_count($__rel, '/') + 1);
$activePage = $page_title ?? '';

function esc($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMPUS-Mini<?php echo isset($page_title) ? ' | ' . htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') : ''; ?></title>
    <link rel="stylesheet" href="<?php echo $base; ?>assets/css/style.css">
</head>
<body>
    <a class="skip-link" href="#konten">Langsung ke konten</a>
    <header class="site-header">
        <a class="brand" href="<?php echo $base; ?>index.php" aria-label="SIMPUS-Mini, beranda">
            <svg class="brand-icon" viewBox="0 0 32 32" fill="none" aria-hidden="true"><path d="M16 8C12 5 7 5 3 6v20c4-1 9-1 13 2 4-3 9-3 13-2V6c-4-1-9-1-13 2Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="M16 8v20M7 11c2-.2 4 .2 6 1M7 16c2-.2 4 .2 6 1M20 12c2-.8 3.5-1 5-1M20 17c2-.8 3.5-1 5-1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            <span><strong>SIMPUS-Mini</strong><small>Perpustakaan Kampus</small></span>
        </a>
        <button type="button" id="nav-toggle-btn" class="nav-toggle-label" aria-label="Buka atau tutup menu navigasi" aria-controls="menu-utama" aria-expanded="false">&#9776;</button>
        <nav id="menu-utama" aria-label="Navigasi utama">
            <ul>
                <li><a href="<?php echo $base; ?>index.php" <?php if ($activePage === 'Beranda') echo 'aria-current="page"'; ?>>Beranda</a></li>
                <li><a href="<?php echo $base; ?>buku/list.php" <?php if (strpos($activePage, 'Buku') !== false) echo 'aria-current="page"'; ?>>Daftar Buku</a></li>
                <li><a href="<?php echo $base; ?>anggota/list.php" <?php if (strpos($activePage, 'Anggota') !== false) echo 'aria-current="page"'; ?>>Anggota</a></li>
            </ul>
        </nav>
    </header>

    <main id="konten">
