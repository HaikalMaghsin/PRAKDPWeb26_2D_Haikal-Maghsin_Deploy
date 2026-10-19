# Checklist & Audit Keamanan Web — Geprek Kita (Praktikum 11)

Dokumen ini memuat hasil audit keamanan menyeluruh terhadap aplikasi **Geprek Kita**, membandingkan kondisi sebelum audit (Praktikum 7–10) dan sesudah penguatan keamanan (*hardening*) pada Praktikum 11.

---

## 1. Ringkasan Status Keamanan

| No | Aspek Keamanan | Status Sebelum Hardening | Status Sesudah Hardening | Solusi Teknis |
|---|---|---|---|---|
| 1 | **SQL Injection** | Aman sebagian (PDO prepared statements pada query utama) | **Aman 100%** | Seluruh query SELECT, INSERT, UPDATE, DELETE menggunakan PDO prepared statement dengan parameter binding |
| 2 | **Cross-Site Scripting (XSS)** | Sebagian output belum dibungkus escape | **Aman 100%** | Fungsi helper terpusat `esc()` / `e()` (`htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`) disematkan pada seluruh output dinamis |
| 3 | **Cross-Site Request Forgery (CSRF)** | Belum ada token CSRF pada form POST | **Aman 100%** | Implementasi token acak 32-byte per sesi (`csrf_token()`), field input (`csrf_field()`), dan verifikasi wajib (`csrf_verify()`) pada setiap request POST |
| 4 | **Session Fixation & Hijacking** | Pengaturan session default | **Aman 100%** | Pemanggilan `session_regenerate_id(true)` saat login sukses, cookie session diberi flag `httponly=true`, `samesite=Lax`, dan `secure` adaptif HTTPS |
| 5 | **Validasi & Sanitasi Input** | Validasi string dasar | **Aman 100%** | Penegakan type casting `(int)` eksplisit untuk ID dan jumlah, sanitasi `trim()`, whitelist validasi menu dari array `$daftarMenu`, batas kuantitas 1–100 |

---

## 2. Rincian Temuan & Perbaikan per Berkas

### A. Proteksi Cross-Site Scripting (XSS)
- **File Helper**: `prak11/includes/helpers.php`
- **Fungsi**:
  ```php
  function esc($value): string {
      return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
  }
  ```
- **Area yang Diberi Perlindungan**:
  - `prak11/anggota/list.php`: Nama, No. Anggota, Alamat, No. HP, dan parameter pencarian `q`.
  - `prak11/anggota/edit.php`: Seluruh atribut `value` pada input teks form.
  - `prak11/pesanan/list.php`: Nama anggota, nama menu, jumlah, total harga, pesan flash.
  - `prak11/includes/header.php`: Nama pengguna sesi pada status login navbar (`$_SESSION['nama']`).
  - `prak11/auth/login.php` & `register.php`: Tampilan pesan flash error/sukses.
- **Bukti Uji**:
  - Input nama: `<script>alert('xss')</script>`
  - Output di browser: `&lt;script&gt;alert(&#039;xss&#039;)&lt;/script&gt;` (dirender sebagai teks biasa, script tidak dieksekusi).

### B. Proteksi Cross-Site Request Forgery (CSRF)
- **File Helper**: `prak11/includes/csrf.php`
- **Mekanisme**:
  - `csrf_token()`: Mengenerate token acak 32-byte (`bin2hex(random_bytes(32))`) dan menyimpannya di `$_SESSION['csrf_token']`.
  - `csrf_field()`: Menghasilkan `<input type="hidden" name="csrf_token" value="...">`.
  - `csrf_verify()`: Memeriksa kesesuaian token POST dengan token sesi via `hash_equals()`. Bila tidak cocok, eksekusi dihentikan dengan HTTP 403 Forbidden.
- **Form yang Dilindungi**:
  - Form Login Petugas (`auth/login.php`)
  - Form Registrasi Petugas (`auth/register.php`)
  - Form Tambah Anggota (`anggota/tambah.php`)
  - Form Edit Anggota (`anggota/edit.php`)
  - Form Hapus Anggota (`anggota/list.php`)
  - Form Simpan Pesanan (`pesanan/list.php`)
  - Form Hapus Pesanan (`pesanan/list.php`)
- **Urutan Guard Clause**:
  Pada berkas aksi POST (misalnya `anggota/proses_edit.php`):
  1. `auth.php` (memastikan pengguna login; tamu dialihkan ke halaman Login)
  2. `csrf_verify()` (memastikan request berasal dari form resmi aplikasi)
  3. Validasi input & eksekusi query database.

### C. Audit SQL Injection
- Seluruh kueri pada berkas proses (`proses_login.php`, `proses_register.php`, `proses_tambah.php`, `proses_edit.php`, `pesanan/list.php`) menggunakan prepared statement PDO:
  ```php
  $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
  $stmt->execute(['username' => $username]);
  ```
- Tidak ada konkatenasi string SQL dengan data kiriman pengguna (`$_GET` atau `$_POST`).
- **Bukti Uji**: Percobaan bypass login dengan input `' OR '1'='1` menghasilkan status "Username atau password salah" dan tidak meloloskan autentikasi.

### D. Session Hardening & Session Fixation
- Pada `auth/proses_login.php`:
  ```php
  if ($user && password_verify($password, $user['password'])) {
      session_regenerate_id(true);
      $_SESSION['user_id'] = $user['id'];
      ...
  }
  ```
  Fungsi `session_regenerate_id(true)` menghapus ID sesi lama dan menerbitkan ID baru segera setelah password terverifikasi, mencegah serangan pembajakan sesi lama.
- Konfigurasi cookie sesi pada `includes/session.php`:
  - `lifetime = 0` (sesi berakhir saat browser ditutup)
  - `path = /prak11/` (lingkup terisolasi per praktikum)
  - `httponly = true` (mencegah cookie dibaca oleh JavaScript/XSS)
  - `samesite = 'Lax'` (mencegah pengiriman cookie lintas situs pada permintaan pihak ketiga)

---

## 3. Kesimpulan Audit
Praktikum 11 memastikan sistem pencatatan Geprek Kita memiliki fondasi pertahanan berlapis (defense-in-depth) yang memenuhi standar OWASP dasar: data terlindungi dari manipulasi query SQL, bebas dari eksekusi skrip berbahaya di sisi klien, dan form terproteksi penuh dari pemalsuan permintaan antar-situs.
