<?php
// Hanya halaman dalam daftar ini yang boleh dijalankan.
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$page = substr($path, strlen('/prak7/'));
if ($page === '') $page = 'index.php';
$allowed = [
    'index.php', 'buku/list.php', 'buku/tambah.php', 'buku/proses_tambah.php',
    'anggota/list.php', 'anggota/tambah.php', 'anggota/proses_tambah.php',
];
if (!in_array($page, $allowed, true)) {
    http_response_code(404);
    exit('Halaman tidak ditemukan.');
}
$_SERVER['SCRIPT_FILENAME'] = dirname(__DIR__) . '/prak7/' . $page;
require $_SERVER['SCRIPT_FILENAME'];
