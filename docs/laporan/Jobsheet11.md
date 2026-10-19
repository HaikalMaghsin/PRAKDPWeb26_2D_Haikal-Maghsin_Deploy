# Laporan Praktikum Jobsheet 11

## Keamanan Web Dasar

### Identitas

| Keterangan | Isi |
|---|---|
| Nama | Haikal Maghsin |
| NIM | 254107020189 |
| Kelas | TI 2D |
| Mata Kuliah | Desain dan Pemrograman Web |

---

## 1. Tujuan

Tujuan Praktikum Jobsheet 11 adalah mengidentifikasi, mengaudit, dan menutup kerentanan keamanan web dasar pada aplikasi **Geprek Kita**, mencakup mitigasi Cross-Site Scripting (XSS), implementasi Cross-Site Request Forgery (CSRF) token, audit pencegahan SQL Injection, penegakan urutan guard autentikasi, serta perlindungan terhadap Session Fixation.

---

## 2. Pengerjaan

Saya membuat folder `prak11` sebagai kelanjutan langsung dari `prak10`. Pada tahap ini fokus utama adalah melakukan pengerasan sistem (*security hardening*) menyeluruh terhadap seluruh modul aplikasi Geprek Kita:

1. **Proteksi Cross-Site Scripting (XSS)**:
   - Membuat file `includes/helpers.php` yang mendefinisikan fungsi helper `esc($value)` dan `e($value)` menggunakan `htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8')`.
   - Menggantikan seluruh pencetakan langsung data pengguna, database, dan URL dengan fungsi ini pada seluruh halaman (`anggota/list.php`, `anggota/edit.php`, `pesanan/list.php`, pesan flash, dan nama pengguna di navbar).
2. **Proteksi Cross-Site Request Forgery (CSRF)**:
   - Membuat file `includes/csrf.php` dengan tiga fungsi inti: `csrf_token()`, `csrf_field()`, dan `csrf_verify()`.
   - Token acak 32-byte (`bin2hex(random_bytes(32))`) disimpan di dalam session dan disematkan sebagai hidden field `<input type="hidden" name="csrf_token" value="...">` pada setiap formulir POST (Login, Registrasi, Tambah Anggota, Edit Anggota, Hapus Anggota, Simpan Pesanan, dan Hapus Pesanan).
   - Fungsi `csrf_verify()` memeriksa token kiriman form menggunakan perbandingan aman `hash_equals()`. Jika token hilang atau tidak cocok, server mengembalikan status HTTP 403 Forbidden.
3. **Audit SQL Injection**:
   - Memeriksa ulang seluruh kueri database pada modul `auth/`, `anggota/`, dan `pesanan/`. Seluruh kueri dipastikan 100% menggunakan prepared statement PDO dengan parameter terikat (`:parameter` atau `?`), sehingga input pengguna diperlakukan murni sebagai data dan kebal dari injeksi SQL.
4. **Session Fixation & Cookie Hardening**:
   - Memastikan `session_regenerate_id(true)` dipanggil tepat saat verifikasi `password_verify()` berhasil pada `auth/proses_login.php`.
   - Memperbarui cookie session pada `includes/session.php` dengan flag `httponly = true` (mencegah pencurian cookie via skrip browser) dan `samesite = 'Lax'`.
5. **Urutan Guard Clause**:
   - Memastikan `includes/auth.php` dijalankan sebelum `csrf_verify()` pada berkas proses, sehingga pengguna yang belum login langsung dialihkan (*redirect*) ke halaman Login alih-alih menghadapi pesan error CSRF.
6. **Entry Point & Router**:
   - Menambahkan berkas `api/prak11.php` dan memperbarui `router.php` serta `vercel.json` agar praktikum 11 dapat diakses langsung.

---

## 3. Improvisasi

Saya menyusun dokumen audit keamanan lengkap pada [prak11/docs/security-checklist.md](../../prak11/docs/security-checklist.md) yang mendokumentasikan temuan sebelum dan sesudah hardening untuk 5 pilar keamanan web dasar. Saya juga menambahkan validasi whitelist menu pada proses simpan pesanan (`array_key_exists($kode, $daftarMenu)`) dan type casting integer eksplisit `(int)` pada ID data.

---

## 4. Hasil Pengujian

| No. | Pengujian | Hasil yang Diharapkan | Hasil Aktual |
|---:|---|---|---|
| 1 | Uji SQL Injection `' OR '1'='1` pada login | Login ditolak, pesan error muncul | Berhasil ditolak, aman |
| 2 | Uji XSS input nama `<script>alert(1)</script>` | Teks dirender sebagai karakter literal HTML | Berhasil dinetralkan, tidak ada pop-up |
| 3 | Submit form POST tanpa token CSRF | Ditolak server dengan HTTP 403 Forbidden | Berhasil ditolak |
| 4 | Submit form POST dengan token CSRF valid | Permintaan berhasil diproses dan disimpan | Berhasil diproses |
| 5 | Akses proses POST tanpa login | Dialihkan ke halaman Login | Berhasil dialihkan |
| 6 | Uji Session Fixation setelah login | ID session berganti ke ID baru | Berhasil berganti ID sesi |
| 7 | Pemeriksaan sintaks seluruh berkas (`php -l`) | 0 kesalahan sintaks | Lolos 100% tanpa error |

---

## 5. Kendala

Ketika form diuji melalui skrip atau request langsung pihak ketiga tanpa menyertakan session cookie dan token CSRF, permintaan langsung digagalkan dengan HTTP 403. Hal ini merupakan perilaku yang memang diharapkan untuk membuktikan bahwa data hanya dapat dimanipulasi dari formulir resmi aplikasi Geprek Kita.

---

## 6. Kesimpulan

Pada Jobsheet 11, aplikasi Geprek Kita telah berhasil diperkuat (*hardened*) dari berbagai kerentanan dasar web. Melalui enkapsulasi fungsi helper XSS, implementasi token CSRF per-sesi, audit kueri PDO prepared statements, regenerasi sesi login, dan isolasi cookie, sistem memiliki tingkat keamanan yang kokoh tanpa mengorbankan fungsionalitas dan kenyamanan pengguna.
