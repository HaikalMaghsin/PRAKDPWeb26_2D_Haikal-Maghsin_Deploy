<?php
// Router for PHP's built-in development server.
$path = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/');

if ($path === '/' || $path === '/index.html') {
    readfile(__DIR__ . '/index.html');
    return;
}
if (preg_match('#^/prak(?:[1-9]|10)$#', $path)) {
    header('Location: ' . $path . '/');
    return;
}
// Seperti hosting biasa, file HTML, CSS, JS, dan gambar disajikan langsung.
if ($path === '/docs/HANDBOOK-GEPREK-KITA.html') {
    return false;
}
if (in_array($path, ['/docs/HANDBOOK-GEPREK-KITA.pdf', '/docs/images/menu.png', '/docs/images/anggota.png', '/docs/images/pesanan.png', '/docs/images/validasi.png'], true)) {
    return false;
}
if (preg_match('#^/(assets/|prak[1-7]/)#', $path) && !str_contains($path, '..') && preg_match('#\.(html|css|js|json|png|jpg|jpeg|svg|webp|ico)$#i', $path) && is_file(__DIR__ . $path)) {
    return false;
}
if (preg_match('#^/prak([1-6])/$#', $path, $match)) {
    readfile(__DIR__ . '/prak' . $match[1] . '/index.html');
    return;
}
if (strpos($path, '/prak7/') === 0) {
    require __DIR__ . '/api/prak7.php';
    return;
}
if (strpos($path, '/prak9/') === 0) {
    require __DIR__ . '/api/prak9.php';
    return;
}
if (strpos($path, '/prak10/') === 0) {
    require __DIR__ . '/api/prak10.php';
    return;
}
if (strpos($path, '/prak8/') === 0 || $path === '/index.php' || strpos($path, '/anggota/') === 0 || strpos($path, '/pesanan/') === 0) {
    require __DIR__ . '/api/prak8.php';
    return;
}
http_response_code(404);
echo 'Halaman tidak ditemukan.';
