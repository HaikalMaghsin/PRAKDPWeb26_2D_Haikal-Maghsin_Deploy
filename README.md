# Geprek Kita

Panduan belajar UTS: [Handbook Geprek Kita](docs/HANDBOOK-GEPREK-KITA.md).
Versi siap cetak tersedia di [handbook HTML](docs/HANDBOOK-GEPREK-KITA.html).
Buka file HTML di browser lalu pilih Cetak / Simpan PDF.

Handbook berisi 9 bab pembuatan, screenshot aplikasi, pengujian, dan lampiran UTS.
Sumbernya adalah Markdown. Setelah mengeditnya, jalankan
`python scripts/build_handbook.py` untuk memperbarui versi HTML, lalu cetak
ulang ke PDF melalui browser. File PDF harus diperbarui terpisah agar isinya sama.

## Rangkaian repo dan masterpage

Repo praktikum (folder lokal ../P.D.Web) adalah sumber latihan.
Repo deploy ini menyimpan masterpage dan salinan hasil yang siap ditampilkan:

- / atau index.html: masterpage.
- /prak1/ sampai /prak6/: HTML, CSS, JavaScript, dan data JSON latihan.
- /prak7/: latihan PHP dan form, data sementara dalam session.
- /prak8/: Geprek Kita dengan database, kode di prak8/.
- /prak9/: Geprek Kita dengan CRUD, pencarian server, dan pagination lima data; kode di prak9/.
- /prak10/: Geprek Kita dengan registrasi, login, logout, session database, dan hak akses admin; kode di prak10/.

Prak 1 disalin dari kode-praktikum/jobsheet-01, Prak 2 dari folder utama,
dan Prak 3–7 dari folder Jobsheet3–Jobsheet7 di repo praktikum.
Tema SIMPUS pada arsip 1–7 dipertahankan. Prak 8 adalah proyek Geprek Kita.
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

Lokal: jalankan `php -S localhost:8000 router.php`, buka `/prak9/` atau `/prak10/`. Kredensial tetap memakai file `.env` atau environment variable Vercel.

## Bahan penjelasan UTS
- HTML: header, nav, form, input, select, dan table.
- CSS: warna, font, margin, padding, border, flex, dan media query.
- JavaScript: validasi, fokus input, konfirmasi hapus, pencarian, dan total harga.
- PHP: membaca POST, percabangan, perulangan, session, dan prepared statement.
- Database: anggota terhubung ke pesanan melalui anggota_id.

assets/css/style.css diberi komentar singkat. Ubah padding tombol untuk
ukurannya dan font-size untuk besar tulisan. Tidak memakai framework CSS.
Data buku lama tidak dihapus dari database; aplikasi baru tidak memakainya.
