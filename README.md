# Geprek Kita & Portofolio Praktikum Web

Panduan belajar & ujian:
- **Jobsheet 1–6 (SIMPUS-Mini)**: [Handbook SIMPUS-Mini](docs/HANDBOOK-SIMPUS-JOBSHEET-1-6.md) · Versi cetak: [Handbook SIMPUS HTML](docs/HANDBOOK-SIMPUS-JOBSHEET-1-6.html)
- **Jobsheet 7–10 (Geprek Kita)**: [Handbook Geprek Kita](docs/HANDBOOK-GEPREK-KITA.md) · Versi cetak: [Handbook Geprek Kita HTML](docs/HANDBOOK-GEPREK-KITA.html)

Buka file HTML di browser lalu pilih Cetak / Simpan PDF.

Handbook Jobsheet 1–6 mencakup materi HTML5 Semantik, CSS Layout & Typography, RWD & CSS Grid, UI/UX Wireframe, JavaScript DOM & Validasi Form, serta Fetch API & JSON.
Handbook Geprek Kita berisi 9 bab pembuatan, screenshot aplikasi, pengujian, dan lampiran UTS.
Sumbernya adalah Markdown. Setelah mengeditnya, jalankan:
- `python scripts/build_simpus_handbook.py` untuk memperbarui Handbook SIMPUS HTML.
- `python scripts/build_handbook.py` untuk memperbarui Handbook Geprek Kita HTML.

## Rangkaian repo dan masterpage

Repo praktikum (folder lokal ../P.D.Web) adalah sumber latihan.
Repo deploy ini menyimpan masterpage dan salinan hasil yang siap ditampilkan:

- / atau index.html: masterpage.
- /prak1/ sampai /prak6/: HTML, CSS, JavaScript, dan data JSON latihan (SIMPUS-Mini).
- /prak7/: latihan PHP dan form, data sementara dalam session (Ayam Geprek Kita).
- /prak8/: Geprek Kita dengan database, kode di prak8/.
- /prak9/: Geprek Kita dengan CRUD, pencarian server, dan pagination lima data; kode di prak9/.
- /prak10/: Geprek Kita dengan registrasi, login, logout, session database, dan hak akses admin; kode di prak10/.
- /prak11/: Geprek Kita dengan hardening keamanan web (XSS protection, CSRF token, session fixation, audit query); kode di prak11/.
- /prak12/: Geprek Kita dengan transaksi atomik (ACID), manajemen stok real-time, status sirkulasi, dan riwayat pesanan; kode di prak12/.
- /prak13/: Geprek Kita versi final siap deploy, pemisahan konfigurasi environment, manual pengguna, dan draf naskah demo UAS; kode di prak13/.

Prak 1 disalin dari kode-praktikum/jobsheet-01, Prak 2 dari folder utama,
dan Prak 3–6 dari folder Jobsheet3–Jobsheet6 di repo praktikum.
Tema SIMPUS pada arsip 1–6 dipertahankan. Prak 7–13 adalah proyek Geprek Kita.
Salinan ini tidak otomatis tersinkron; perubahan sumber perlu disalin lagi.
Repo praktikum belum diubah oleh penataan ini.

Prak 7 memakai api/prak7.php sebagai pintu masuk agar tidak menambah
satu fungsi deployment untuk setiap halaman. Session hanya untuk latihan,
bukan penyimpanan permanen dan dapat hilang/berbeda antar-instance hosting.
Rute lokal di router.php mengikuti rute hosting di vercel.json.

Web sederhana untuk mencatat anggota pelanggan dan pesanan ayam geprek.
Menggunakan HTML, CSS, JavaScript, PHP, dan PostgreSQL (Supabase).

## Menjalankan lokal
1. Aktifkan ekstensi pdo_pgsql di PHP.
2. Salin .env.example menjadi .env, lalu isi koneksi database.
3. Jalankan isi sql/02_geprek.sql di SQL Editor Supabase.
4. Jalankan: php -S localhost:8000 router.php
5. Buka http://localhost:8000

Tidak memerlukan npm. File .env jangan diunggah ke Git.
Untuk Vercel, isi variabel yang sama di Environment Variables lalu deploy ulang.
Konfigurasi memakai empat fungsi PHP. Folder api/ hanya berisi pintu masuk Praktikum 7–10. Rute PHP diproses sebelum file statis agar Launch membuka halaman, bukan mengunduh kode PHP.

## Cara memakai
1. Tambahkan anggota pelanggan.
2. Buka Pesanan, pilih anggota, menu, dan jumlah.
3. Simpan pesanan. Data bisa diedit atau dihapus dari tabel.
4. Anggota yang punya pesanan tidak dapat dihapus sebelum pesanannya dihapus.

Menu dan harga diatur di prak8/includes/menu.php.
Salinan Praktikum 9 dan 10 memakai prak9/includes/menu.php dan prak10/includes/menu.php.
Satu pesanan berisi satu jenis menu. Untuk menu lain, buat pesanan berikutnya.
Total dihitung kembali di PHP dari harga menu, bukan dari nilai browser.
Praktikum 10 menambahkan login petugas. Pembayaran belum diterapkan.

## Tambahan Praktikum 9 dan 10

Tema Geprek Kita dipakai pada anggota pelanggan dan pesanan, mengikuti alur CRUD dari Jobsheet 9 serta autentikasi dari Jobsheet 10.

Jalankan `sql/03_auth.sql` di SQL Editor database Supabase sebelum memakai registrasi dan login. Tabel `users` menyimpan akun dengan password hash; tabel `geprek_sessions` menyimpan sesi antar-instance Vercel.

Akun registrasi mendapat role `petugas`. Hanya admin boleh menghapus anggota. Untuk akun sendiri yang ditunjuk admin, ubah kolom `role` menjadi `admin` di database, lalu login kembali. Anggota yang masih punya pesanan tetap tidak bisa dihapus.

## Tambahan Praktikum 11, 12, dan 13

1. **Praktikum 11 (Keamanan Web Dasar)**:
   - Proteksi XSS terpusat menggunakan fungsi helper `esc()` / `e()` (`htmlspecialchars` flag `ENT_QUOTES`).
   - Proteksi CSRF berbasis token per-sesi acak 32-byte pada seluruh form POST (Login, Register, Anggota, Pesanan).
   - Penegakan urutan guard clause (`auth.php` sebelum `csrf_verify()`).
   - Audit 100% kueri prepared statement PDO terhadap SQL Injection.
   - Dokumen audit keamanan: `prak11/docs/security-checklist.md`.

2. **Praktikum 12 (Integrasi Modul Transaksi & Stok)**:
   - Skema basis data diperluas dengan tabel `menu` (stok fisik) dan kolom status pesanan (`sql/04_transaksi.sql`).
   - Transaksi atomik database (ACID) via `$pdo->beginTransaction()` dan `$pdo->commit()`.
   - Penguncian baris `SELECT ... FOR UPDATE` pada stok menu untuk mencegah race condition.
   - Pemotongan stok saat pesan baru dan pemulihan stok otomatis saat pesanan dibatalkan.
   - Aksi penyelesaian pesanan (`status = 'selesai'`) dan penegakan batas maksimal antrean pesanan pending.
   - Modul Riwayat Transaksi (`pesanan/riwayat.php`) via JOIN multi-tabel serta ringkasan omset penjualan.
   - Dashboard Beranda real-time dengan kartu agregat dari database.

3. **Praktikum 13 (Deployment & Dokumentasi Final)**:
   - Pemisahan konfigurasi lingkungan secara terpusat pada `includes/config.php` via variabel lingkungan.
   - Manual pengguna operasional: `prak13/docs/manual-pengguna.md`.
   - Draf naskah presentasi teknis UAS (5–7 menit): `prak13/docs/naskah-presentasi.md`.
   - Laporan praktikum: `docs/laporan/Jobsheet11.md`, `Jobsheet12.md`, `Jobsheet13.md`.

Lokal: jalankan `php -S localhost:8000 router.php`, buka `/prak11/`, `/prak12/`, atau `/prak13/`.

## Bahan Penjelasan UAS
- Arsitektur: HTML5 semantik, CSS responsif tanpa framework, JavaScript validasi & interaktif, PHP native MVC-like, PostgreSQL relasional.
- Keamanan: Sanitasi XSS `esc()`, CSRF token hash-equals, PDO prepared statements, session fixation mitigation.
- Transaksi ACID: `beginTransaction()`, `commit()`, `rollBack()`, row-locking `SELECT ... FOR UPDATE` menjamin integritas stok.
- Relasi Multi-Tabel: `JOIN` antara `pesanan`, `anggota`, `users`, dan `menu`.
- Siap Produksi: Pemisahan konfigurasi `.env`, isolasi sesi per-path, dan integrasi Vercel serverless.
