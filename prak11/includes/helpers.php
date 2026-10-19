<?php
// Membungkus output data yang berasal dari input pengguna, database, atau URL
// sebelum dicetak ke dokumen HTML untuk mencegah Cross-Site Scripting (XSS).

if (!function_exists('esc')) {
    function esc($value): string
    {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('e')) {
    function e($value): string
    {
        return esc($value);
    }
}
