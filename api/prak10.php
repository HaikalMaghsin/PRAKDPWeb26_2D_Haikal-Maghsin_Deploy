<?php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$prefix = '/prak10';
$route = substr($path, strlen($prefix));
$routes = [
    '' => 'index.php',
    '/' => 'index.php', '/index.php' => 'index.php',
    '/anggota/list.php' => 'anggota/list.php',
    '/anggota/tambah.php' => 'anggota/tambah.php',
    '/anggota/proses_tambah.php' => 'anggota/proses_tambah.php',
    '/anggota/edit.php' => 'anggota/edit.php',
    '/anggota/proses_edit.php' => 'anggota/proses_edit.php',
    '/pesanan/list.php' => 'pesanan/list.php',
    '/auth/login.php' => 'auth/login.php',
    '/auth/register.php' => 'auth/register.php',
    '/auth/proses_login.php' => 'auth/proses_login.php',
    '/auth/proses_register.php' => 'auth/proses_register.php',
    '/auth/logout.php' => 'auth/logout.php',
];
if (!isset($routes[$route])) {
    http_response_code(404);
    exit('Halaman tidak ditemukan.');
}
require __DIR__ . '/prak10/' . $routes[$route];
