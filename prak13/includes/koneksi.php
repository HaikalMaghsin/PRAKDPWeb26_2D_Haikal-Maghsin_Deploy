<?php
// Koneksi basis data PostgreSQL PDO terpusat menggunakan konfigurasi config.php (Jobsheet 13).

require_once __DIR__ . '/config.php';

$pdo = null;
$databaseError = null;

try {
    $cfg = getDbConfig();
    $pdo = new PDO($cfg['dsn'], $cfg['user'], $cfg['pass'], $cfg['options']);
} catch (Throwable $exception) {
    $databaseError = 'Data warung belum bisa dimuat. Silakan periksa koneksi database.';
}
