<?php
// Hanya halaman dalam daftar ini yang boleh dijalankan.
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$page = substr($path, strlen('/prak7/'));
if ($page === '') $page = 'index.php';
$allowed = [
    'index.php',
    'anggota/list.php',
    'anggota/tambah.php',
    'anggota/proses_tambah.php',
    'anggota/edit.php',
    'anggota/proses_edit.php',
    'pesanan/list.php',
];
if (!in_array($page, $allowed, true)) {
    http_response_code(404);
    exit('Halaman tidak ditemukan.');
}
$_SERVER['SCRIPT_FILENAME'] = dirname(__DIR__) . '/prak7/' . $page;
require $_SERVER['SCRIPT_FILENAME'];
