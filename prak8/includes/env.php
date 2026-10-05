<?php

// Membaca file .env untuk penggunaan di komputer lokal.
$envFile = dirname(__DIR__, 2) . '/.env';

if (is_file($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        // Environment variable dari server tetap lebih diprioritaskan.
        if ($key !== '' && getenv($key) === false) {
            $value = trim($value, "\"'");
            putenv($key . '=' . $value);
            $_ENV[$key] = $value;
        }
    }
}

