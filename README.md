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

## Konfigurasi Vercel

PHP di Vercel membutuhkan community runtime `vercel-php`. Tambahkan salah satu konfigurasi database berikut di **Vercel → Project → Settings → Environment Variables**:

- `DATABASE_URL`: connection string PostgreSQL publik, atau
- `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`, dan opsional `DB_SSLMODE`.

Database lokal di komputer tidak bisa dipakai langsung oleh Vercel. Gunakan PostgreSQL yang dapat diakses dari internet, lalu jalankan `sql/01_buku_anggota.sql` pada database tersebut.
