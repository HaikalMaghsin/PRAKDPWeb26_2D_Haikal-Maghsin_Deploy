# SIMPUS-Mini Deploy

Repo ini adalah versi deploy kumulatif SIMPUS-Mini milik Haikal Maghsin. Isi root merepresentasikan hasil Jobsheet 1 sampai 8 dalam satu aplikasi PHP.

## Materi yang diterapkan

- Jobsheet 1-4: struktur HTML, CSS, responsif, dan desain halaman.
- Jobsheet 5: hamburger menu, pencarian, validasi form, serta konfirmasi hapus dengan JavaScript.
- Jobsheet 6: pemisahan data dari halaman statis sebagai tahap sebelum server-side.
- Jobsheet 7: PHP, `include`, form `POST`, validasi server, dan flash message.
- Jobsheet 8: PostgreSQL melalui PDO, prepared statement, `SELECT`, dan `COUNT(*)`.

## Struktur

- `api/` berisi halaman PHP dan kode server.
- `assets/` berisi stylesheet dan JavaScript.
- `sql/01_buku_anggota.sql` berisi skema tabel PostgreSQL.
- `vercel.json` mengarahkan rute publik ke PHP runtime.

## Tampilan dan bahan handbook

Website menggunakan PHP, CSS, dan JavaScript biasa tanpa framework atau proses
build. Font menggunakan font sistem agar halaman tidak perlu mengunduh font.

- `api/index.php`: foto pembuka, pengantar singkat, jumlah buku dan anggota,
  tautan ke halaman daftar, serta informasi jam buka dan aturan berkunjung.
- `api/includes/header.php` dan `footer.php`: layout bersama melalui `include`.
- `assets/css/style.css`: warna, jarak, Flexbox, Grid, foto transparan,
  tabel, formulir, dan media query untuk layar ponsel.
- `assets/js/app.js`: menu ponsel, pencarian tabel, pesan hasil pencarian,
  validasi formulir, dan simulasi konfirmasi hapus.
- `api/buku/` dan `api/anggota/`: tabel, formulir POST, session, validasi
  server, serta proses tambah, edit, dan hapus data lewat PDO.

Beranda sengaja dibuat singkat untuk latihan dasar HTML, CSS, dan PHP.
Jam buka merupakan contoh untuk praktikum dan bisa diganti di `api/index.php`.
Website belum mencatat transaksi peminjaman. Data buku dan anggota sudah dapat
ditambah, diedit, dan dihapus dari database PostgreSQL.

Foto lokal di `assets/images/perpustakaan.jpg` bersumber dari
[Keisha Kim / Unsplash](https://unsplash.com/photos/a-man-and-a-woman-holding-a-book-in-a-library-sVY04fp6T5Q).
Efek samar diterapkan melalui CSS, sehingga file foto aslinya tetap utuh.

## Menjalankan di lokal

Dari folder proyek, jalankan PHP development server di PowerShell:

```powershell
& "C:\xampp\php\php.exe" -S localhost:8000 router.php
```

Jika PHP sudah tersedia di PATH, gunakan `php -S localhost:8000 router.php`.
Buka http://localhost:8000. Tekan `Ctrl+C` untuk menghentikan server.
Proyek ini memakai PHP, sehingga tidak membutuhkan `npm run dev`.

Halaman tetap bisa dibuka tanpa database. Untuk membaca dan menyimpan data,
aktifkan ekstensi PHP `pdo_pgsql`, siapkan database PostgreSQL, lalu jalankan
`sql/01_buku_anggota.sql`. Setelah itu, buka file `.env` dan sesuaikan koneksinya:

```dotenv
DB_HOST=localhost
DB_PORT=5432
DB_NAME=simpus
DB_USER=postgres
DB_PASS=password_postgresql
DB_SSLMODE=disable
```

File `.env` diabaikan oleh Git agar password tidak ikut diunggah. File
`.env.example` menjadi contoh konfigurasi tanpa menyimpan password asli.

## Konfigurasi Vercel

PHP di Vercel membutuhkan community runtime `vercel-php`. Tambahkan salah satu konfigurasi database berikut di **Vercel → Project → Settings → Environment Variables**:

- `DATABASE_URL`: connection string PostgreSQL publik, atau
- `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`, dan opsional `DB_SSLMODE`.

Database lokal di komputer tidak bisa dipakai langsung oleh Vercel. Gunakan PostgreSQL yang dapat diakses dari internet, lalu jalankan `sql/01_buku_anggota.sql` pada database tersebut.
