# Geprek Kita — Praktikum 13 (Deployment & Dokumentasi Final)

Proyek ini adalah puncak akhir dari pengembangan aplikasi web **Geprek Kita**, memadukan seluruh materi dari Jobsheet 7 hingga Jobsheet 13: pengolahan form PHP, basis data relasional PostgreSQL, operasi CRUD, autentikasi dan manajemen sesi, pengerasan keamanan web (*hardening*), integrasi transaksi atomik (ACID) dengan manajemen stok fisik, hingga konfigurasi siap produksi (*production-ready deployment*).

---

## 1. Arsitektur Basis Data (ERD Final)

Basis data terdiri dari 4 tabel operasional utama dan 1 tabel sesi persisten:

```text
+-----------------------+           +-----------------------------+
|        users          |           |           anggota           |
+-----------------------+           +-----------------------------+
| id (PK, SERIAL)       |           | id (PK, SERIAL)             |
| nama (VARCHAR)        |           | nama (VARCHAR)              |
| username (VARCHAR, UQ)|           | no_anggota (VARCHAR, UNIQUE)|
| password (VARCHAR)    |           | alamat (VARCHAR)            |
| role (VARCHAR)        |           | no_hp (VARCHAR)             |
+-----------------------+           +-----------------------------+
           | 1                                     | 1
           |                                       |
           | melayani                              | memesan
           | N                                     | N
           v                                       v
+-----------------------------------------------------------------+
|                            pesanan                              |
+-----------------------------------------------------------------+
| id (PK, SERIAL)                                                 |
| anggota_id (FK -> anggota.id, ON DELETE RESTRICT)               |
| menu (VARCHAR)                                                  |
| harga (INTEGER)                                                 |
| jumlah (INTEGER)                                                |
| status (VARCHAR: 'menunggu', 'selesai', 'batal')                |
| user_id (FK -> users.id, ON DELETE SET NULL)                    |
| dibuat_pada (TIMESTAMP DEFAULT CURRENT_TIMESTAMP)               |
| selesai_pada (TIMESTAMP)                                        |
+-----------------------------------------------------------------+
                               ^
                               | merujuk varian & stok
                               |
+------------------------------+
|             menu             |
+------------------------------+
| id (PK, SERIAL)              |
| kode (VARCHAR, UNIQUE)       |
| nama (VARCHAR)               |
| harga (INTEGER)              |
| stok (INTEGER CHECK >= 0)    |
+------------------------------+
```

---

## 2. Matriks Hak Akses Pengguna

| Fitur / Halaman | Tamu (Belum Login) | Petugas (Kasir) | Admin |
|---|:---:|:---:|:---:|
| Melihat Beranda & Daftar Menu | Ya | Ya | Ya |
| Registrasi & Login Petugas | Ya | — | — |
| Melihat Daftar Anggota | Tidak (Dialihkan ke Login) | Ya | Ya |
| Menambah & Mengedit Anggota | Tidak | Ya | Ya |
| Menghapus Anggota | Tidak | Ditolak (HTTP 403) | Ya |
| Mencatat Pesanan & Kurangi Stok | Tidak | Ya | Ya |
| Menyelesaikan Pesanan | Tidak | Ya | Ya |
| Membatalkan Pesanan & Pulihkan Stok | Tidak | Ya | Ya |
| Melihat Riwayat Transaksi & Omset | Tidak | Ya | Ya |

---

## 3. Fitur Utama Praktikum 13

1. **Pemisahan Konfigurasi Lingkungan (`includes/config.php`)**:
   - Membaca variabel `DATABASE_URL` atau pasangan `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`, `DB_SSLMODE`.
   - Menghindari *hardcoding* kredensial sensitif di kode sumber.
2. **Hardening Keamanan Web**:
   - Proteksi XSS via fungsi helper terpusat `esc()` dan `e()`.
   - Proteksi CSRF berbasis token per-sesi pada seluruh form POST.
   - Audit SQL Injection 100% menggunakan prepared statement PDO.
   - Pencegahan Session Fixation via `session_regenerate_id(true)`.
3. **Integritas Transaksi ACID**:
   - Penguncian baris `SELECT ... FOR UPDATE` saat memeriksa stok menu.
   - Pemotongan dan pemulihan stok menu secara atomik (`beginTransaction()` / `commit()` / `rollBack()`).
   - Validasi batas maksimal 3 antrean pesanan pending per anggota.
4. **Dokumentasi Lengkap**:
   - Manual pengguna komprehensif: `docs/manual-pengguna.md`.
   - Draf naskah presentasi UAS 5-7 menit: `docs/naskah-presentasi.md`.
   - Checklist keamanan web: `docs/security-checklist.md`.

---

## 4. Petunjuk Instalasi & Menjalankan Lokal

1. Pastikan PHP >= 8.2 terinstal dengan ekstensi `pdo_pgsql` aktif.
2. Jalankan skema database di PostgreSQL:
   ```bash
   psql -d nama_database -f sql/02_geprek.sql
   psql -d nama_database -f sql/03_auth.sql
   psql -d nama_database -f sql/04_transaksi.sql
   ```
3. Salin `.env.example` menjadi `.env`, lalu lengkapi kredensial koneksi:
   ```dotenv
   DB_HOST=localhost
   DB_PORT=5432
   DB_NAME=geprek_kita
   DB_USER=postgres
   DB_PASS="password_kamu"
   DB_SSLMODE=prefer
   ```
4. Jalankan server lokal PHP:
   ```bash
   php -S localhost:8000 router.php
   ```
5. Buka di peramban: `http://localhost:8000/prak13/`.
