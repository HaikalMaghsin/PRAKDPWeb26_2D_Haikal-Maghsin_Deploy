<?php
// Router for PHP's built-in development server.
$path = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/');

if (strpos($path, '/assets/') === 0) {
    return false;
}

$routes = [
    '/' => 'index.php',
    '/index.php' => 'index.php',
    '/buku/list.php' => 'buku/list.php',
    '/buku/tambah.php' => 'buku/tambah.php',
    '/buku/proses_tambah.php' => 'buku/proses_tambah.php',
    '/buku/edit.php' => 'buku/edit.php',
    '/buku/proses_edit.php' => 'buku/proses_edit.php',
    '/buku/proses_hapus.php' => 'buku/proses_hapus.php',
    '/anggota/list.php' => 'anggota/list.php',
    '/anggota/tambah.php' => 'anggota/tambah.php',
    '/anggota/proses_tambah.php' => 'anggota/proses_tambah.php',
    '/anggota/edit.php' => 'anggota/edit.php',
    '/anggota/proses_edit.php' => 'anggota/proses_edit.php',
    '/anggota/proses_hapus.php' => 'anggota/proses_hapus.php',
];

if (!isset($routes[$path])) {
    http_response_code(404);
    echo 'Halaman tidak ditemukan.';
    return;
}

$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/api/' . $routes[$path];
$_SERVER['SCRIPT_NAME'] = $path === '/' ? '/index.php' : $path;
require $_SERVER['SCRIPT_FILENAME'];
