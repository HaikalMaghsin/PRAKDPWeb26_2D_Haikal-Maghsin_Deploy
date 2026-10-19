# Laporan Praktikum Jobsheet 13

## Deployment & Dokumentasi Final

### Identitas

| Keterangan | Isi |
|---|---|
| Nama | Haikal Maghsin |
| NIM | 254107020189 |
| Kelas | TI 2D |
| Mata Kuliah | Desain dan Pemrograman Web |

---

## 1. Tujuan

Tujuan Praktikum Jobsheet 13 adalah menyiapkan aplikasi **Geprek Kita** agar siap dideploy ke server produksi (hosting cloud / Vercel dan Supabase), memisahkan kredensial sensitif database dari kode sumber melalui konfigurasi variabel lingkungan terpusat, menyusun manual pengguna operasional yang komprehensif, serta merancang draf naskah presentasi teknis untuk pelaksanaan Ujian Akhir Semester (UAS).

---

## 2. Pengerjaan

Saya membuat folder `prak13` sebagai hasil final (*production snapshot*) dari seluruh siklus pengembangan aplikasi Geprek Kita:

1. **Pemisahan Kredensial Database**:
   - Menambahkan berkas `includes/config.php` yang memusatkan pembacaan variabel lingkungan (`DATABASE_URL`, `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`, `DB_SSLMODE`) dengan penanganan fallback default.
   - Memperbarui `includes/koneksi.php` agar memanfaatkan fungsi konfigurasi `getDbConfig()` dari `config.php`, memisahkan antara definisi konfigurasi dengan inisialisasi objek PDO.
2. **Pencegahan Kebocoran Kredensial (Security Guard)**:
   - Memastikan file `.env` yang memuat password database Supabase dikecualikan dari Git melalui `.gitignore`.
   - Menyiapkan template `.env.example` sebagai panduan konfigurasi bagi pengembang lain.
3. **Portabilitas Deployment**:
   - Menyiapkan berkas pintu masuk serverless `api/prak13.php` dan memperbarui `vercel.json` dengan runtime PHP Vercel.
   - Memperbarui `router.php` untuk server pengembangan lokal `php -S localhost:8000 router.php`.
   - Mengatur parameter cookie sesi pada `includes/session.php` agar terisolasi per folder (`path = /prak13/`) dan menggunakan nama sesi unik `GEPREK13`.
4. **Penyusunan Manual Pengguna**:
   - Menyusun dokumen panduan lengkap [prak13/docs/manual-pengguna.md](../../prak13/docs/manual-pengguna.md) yang menguraikan alur kerja operasional langkah-demi-langkah: registrasi akun kasir, pengelolaan data pelanggan, pencatatan pesanan dengan pengurangan stok otomatis, penyelesaian transaksi, hingga pemantauan omset penjualan.
5. **Dokumentasi Teknis Proyek & Masterpage**:
   - Menyusun `prak13/README.md` yang memuat deskripsi arsitektur sistem, ERD final 4 entitas (`users`, `anggota`, `menu`, `pesanan`), matriks hak akses per role, dan petunjuk instalasi.
   - Memperbarui masterpage portofolio `index.html` dengan kartu hasil belajar Praktikum 11, 12, dan 13.

---

## 3. Improvisasi

Saya menyelesaikan **Tugas Mandiri** penyiapan draf naskah presentasi teknis demo UAS berdurasi 5–7 menit pada berkas [prak13/docs/naskah-presentasi.md](../../prak13/docs/naskah-presentasi.md). Naskah ini menguraikan argumentasi keputusan desain teknis (alasan pemilihan arsitektur, normalisasi 4 tabel, strategi penanganan *race condition* stok dengan `SELECT ... FOR UPDATE` dalam transaksi ACID, pertahanan keamanan berlapis XSS/CSRF/SQLi, serta skenario langkah demi langkah peragaan langsung di hadapan dosen penguji).

---

## 4. Hasil Pengujian

| No. | Pengujian | Hasil yang Diharapkan | Hasil Aktual |
|---:|---|---|---|
| 1 | Koneksi via `includes/config.php` | Berhasil membaca variabel lingkungan dan terhubung ke PostgreSQL Supabase | Berhasil terhubung |
| 2 | Perlindungan kredensial Git | File `.env` tidak terlacak oleh Git (`git status` bersih) | Berhasil terlindungi |
| 3 | Akses Beranda & Katalog (`/prak13/`) | Menampilkan kartu metrik real-time dan tabel sisa stok menu | Berhasil tampil normal |
| 4 | Pengujian alur End-to-End | Registrasi → Login → Tambah Pelanggan → Pesan Geprek (Stok -2) → Selesaikan → Riwayat → Logout | Seluruh siklus berjalan lancar |
| 5 | Masterpage `index.html` | Kartu Praktikum 11, 12, dan 13 aktif dan dapat dibuka | Berhasil dibuka di tab baru |
| 6 | Kelengkapan Manual Pengguna | Dokumen panduan langkah-demi-langkah tersedia lengkap | Tersedia di `docs/manual-pengguna.md` |
| 7 | Kesiapan Draf Naskah UAS | Naskah presentasi 5–7 menit siap digunakan untuk demo | Tersedia di `docs/naskah-presentasi.md` |
| 8 | Pemeriksaan sintaks seluruh berkas (`php -l`) | 0 kesalahan sintaks | Lolos 100% tanpa error |

---

## 5. Kendala

Perbedaan konfigurasi antara server lokal dan lingkungan cloud hosting serverless dapat menyebabkan inkonsistensi sesi pengguna jika hanya mengandalkan filesystem lokal. Masalah ini diselesaikan secara elegan dengan memanfaatkan tabel PostgreSQL `geprek_sessions` sebagai session save handler terpusat, didukung oleh pemusatan konfigurasi database pada `includes/config.php`.

---

## 6. Kesimpulan

Pada Jobsheet 13, aplikasi web Geprek Kita telah selesai secara menyeluruh (*feature-complete*). Seluruh fungsionalitas sistem dari antarmuka semantik responsif, pemrosesan transaksi berintegritas tinggi dengan proteksi keamanan web dasar, hingga pemisahan konfigurasi dan dokumentasi komprehensif telah siap digunakan dan dipresentasikan untuk penilaian Ujian Akhir Semester (UAS).
