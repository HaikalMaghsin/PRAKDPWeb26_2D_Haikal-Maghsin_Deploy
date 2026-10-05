<?php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$route = str_starts_with($path, '/prak8') ? substr($path, strlen('/prak8')) : $path;
$routes = [
    '' => 'index.php', '/' => 'index.php', '/index.php' => 'index.php',
    '/anggota/list.php' => 'anggota/list.php',
    '/anggota/tambah.php' => 'anggota/tambah.php',
    '/anggota/proses_tambah.php' => 'anggota/proses_tambah.php',
    '/anggota/edit.php' => 'anggota/edit.php',
    '/anggota/proses_edit.php' => 'anggota/proses_edit.php',
    '/pesanan/list.php' => 'pesanan/list.php',
];
if (!isset($routes[$route])) {
    http_response_code(404);
    exit('Halaman tidak ditemukan.');
}
require dirname(__DIR__) . '/prak8/' . $routes[$route];
