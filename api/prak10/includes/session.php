<?php
if (session_status() === PHP_SESSION_ACTIVE) return;
require_once __DIR__ . '/koneksi.php';

final class GeprekSessionHandler implements SessionHandlerInterface {
    public function __construct(private PDO $db) {}
    public function open(string $path, string $name): bool { return true; }
    public function close(): bool { return true; }
    public function read(string $id): string|false {
        $stmt = $this->db->prepare('SELECT data FROM geprek_sessions WHERE id = ? AND expires_at > ?');
        $stmt->execute([$id, time()]);
        return $stmt->fetchColumn() ?: '';
    }
    public function write(string $id, string $data): bool {
        $stmt = $this->db->prepare('INSERT INTO geprek_sessions (id, data, expires_at) VALUES (?, ?, ?)
            ON CONFLICT (id) DO UPDATE SET data = EXCLUDED.data, expires_at = EXCLUDED.expires_at');
        return $stmt->execute([$id, $data, time() + 3600]);
    }
    public function destroy(string $id): bool {
        return $this->db->prepare('DELETE FROM geprek_sessions WHERE id = ?')->execute([$id]);
    }
    public function gc(int $lifetime): int|false {
        $stmt = $this->db->prepare('DELETE FROM geprek_sessions WHERE expires_at < ?');
        $stmt->execute([time()]);
        return $stmt->rowCount();
    }
}

session_name('GEPREK10');
$authReady = false;
if ($pdo) {
    try {
        $authReady = (bool) $pdo->query("SELECT to_regclass('geprek_sessions')")->fetchColumn();
    } catch (PDOException $error) {
        $authReady = false;
    }
}
session_set_cookie_params([
    'lifetime' => 0, 'path' => '/prak10/',
    'secure' => isset($_SERVER['HTTPS']) || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https',
    'httponly' => true, 'samesite' => 'Lax',
]);
if ($pdo && $authReady) {
    session_set_save_handler(new GeprekSessionHandler($pdo), true);
} else {
    // Agar halaman login tetap bisa dibuka saat database belum dikonfigurasi.
    session_save_path(sys_get_temp_dir());
}
session_start();
