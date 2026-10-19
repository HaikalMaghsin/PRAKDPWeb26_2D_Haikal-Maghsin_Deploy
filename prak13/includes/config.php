<?php
// Pemisahan konfigurasi kredensial basis data dari kode aplikasi (Jobsheet 13).
// Mendukung variabel lingkungan produksi (Vercel/Hosting) serta file .env lokal.

require_once __DIR__ . '/env.php';

function getDbConfig(): array
{
    $databaseUrl = getenv('DATABASE_URL');
    if ($databaseUrl) {
        $parsed = parse_url($databaseUrl);
        if ($parsed && !empty($parsed['host']) && !empty($parsed['path'])) {
            parse_str($parsed['query'] ?? '', $query);
            $sslMode = $query['sslmode'] ?? 'require';
            $dsn = sprintf(
                'pgsql:host=%s;port=%s;dbname=%s;sslmode=%s',
                $parsed['host'],
                $parsed['port'] ?? '5432',
                ltrim($parsed['path'], '/'),
                $sslMode
            );
            return [
                'dsn' => $dsn,
                'user' => urldecode($parsed['user'] ?? ''),
                'pass' => urldecode($parsed['pass'] ?? ''),
                'options' => [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],
            ];
        }
    }

    $host = getenv('DB_HOST') ?: 'localhost';
    $port = getenv('DB_PORT') ?: '5432';
    $db   = getenv('DB_NAME') ?: 'postgres';
    $user = getenv('DB_USER') ?: 'postgres';
    $pass = getenv('DB_PASS') ?: '';
    $ssl  = getenv('DB_SSLMODE') ?: 'prefer';

    return [
        'dsn' => "pgsql:host={$host};port={$port};dbname={$db};sslmode={$ssl}",
        'user' => $user,
        'pass' => $pass,
        'options' => [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],
    ];
}
