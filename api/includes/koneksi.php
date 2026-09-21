<?php

require_once __DIR__ . '/env.php';

$pdo = null;
$databaseError = null;
$databaseUrl = getenv('DATABASE_URL');

try {
    if ($databaseUrl) {
        $config = parse_url($databaseUrl);
        if (!$config || empty($config['host']) || empty($config['path'])) {
            throw new RuntimeException('DATABASE_URL tidak valid.');
        }

        parse_str($config['query'] ?? '', $query);
        $sslMode = $query['sslmode'] ?? 'require';
        $dsn = sprintf(
            'pgsql:host=%s;port=%s;dbname=%s;sslmode=%s',
            $config['host'],
            $config['port'] ?? '5432',
            ltrim($config['path'], '/'),
            $sslMode
        );
        $user = urldecode($config['user'] ?? '');
        $pass = urldecode($config['pass'] ?? '');
    } else {
        $host = getenv('DB_HOST');
        $port = getenv('DB_PORT') ?: '5432';
        $db = getenv('DB_NAME');
        $user = getenv('DB_USER');
        $pass = getenv('DB_PASS');
        $sslMode = getenv('DB_SSLMODE') ?: 'require';

        if (!$host || !$db || !$user || $pass === false) {
            throw new RuntimeException('Konfigurasi database belum tersedia.');
        }

        $dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=$sslMode";
    }

    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (Throwable $exception) {
    $databaseError = 'Data perpustakaan belum bisa dimuat. Silakan hubungi pengelola.';
}
