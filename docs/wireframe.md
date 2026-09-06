# Wireframe & User Flow — SIMPUS-Mini

Sub-CPMK: Merancang UI/UX aplikasi (proyek).

Diadaptasi dari [wireframe Pak Dimas](https://github.com/dimas1984/PemogramanWeb2026/blob/2db73f56ff348f4ed0502ea2f8aedd710fb38420/kode-praktikum/jobsheet-04/docs/wireframe.md) untuk proyek **Haikal Maghsin — 254107020189 — TI 2D**.

Halaman yang sudah ada (Beranda, Daftar/Tambah/Edit Buku, Daftar/Tambah/Edit Anggota — Jobsheet 1-3) belum mencakup fitur Login, Dashboard Petugas, dan Peminjaman/Pengembalian. Dokumen ini merancang wireframe untuk halaman-halaman tersebut sebelum diimplementasikan mulai Jobsheet 5 dan seterusnya. Pembatasan hak akses di bawah adalah rancangan; halaman statis saat ini masih dapat dibuka tanpa login.

## Aktor
- **Tamu**: hanya bisa melihat katalog buku (Beranda, Daftar Buku) tanpa login.
- **Petugas**: login untuk mengakses seluruh fitur CRUD dan transaksi peminjaman.

## User Flow — Peminjaman Buku

```
[Petugas Login] -> [Dashboard] -> [Pilih menu "Peminjaman Baru"]
        -> [Pilih Anggota] -> [Pilih Buku (stok > 0)]
        -> [Simpan] -> [Stok buku berkurang 1] -> [Kembali ke Dashboard]
```

## User Flow — Pengembalian Buku

```
[Dashboard] -> [Menu "Pengembalian"] -> [Cari transaksi aktif (anggota/buku)]
        -> [Tandai "Dikembalikan"] -> [Stok buku bertambah 1]
        -> [Kembali ke Dashboard]
```

## Wireframe: Halaman Login

```
+--------------------------------------+
|              SIMPUS-Mini             |
|--------------------------------------|
|                                      |
|        [ Login Petugas ]            |
|                                      |
|   Username : [______________]       |
|   Password : [______________]       |
|                                      |
|          [   Masuk   ]              |
|                                      |
|   Belum punya akun? Daftar di sini  |
+--------------------------------------+
```

## Wireframe: Dashboard Petugas

```
+-----------------------------------------------------+
| SIMPUS-Mini      Beranda | Buku | Anggota | Peminjaman | (Nama Petugas) Logout |
|-------------------------------------------------------|
|  [Total Buku]   [Total Anggota]   [Sedang Dipinjam]    |
|                                                         |
|  Aksi Cepat:                                           |
|  [ + Peminjaman Baru ]   [ + Pengembalian ]            |
|                                                         |
|  Transaksi Terbaru                                     |
|  --------------------------------------------------    |
|  Anggota | Buku | Tgl Pinjam | Status                  |
+-----------------------------------------------------+
```

## Wireframe: Form Peminjaman

```
+--------------------------------------+
|  Form Peminjaman Buku                |
|--------------------------------------|
|  Anggota : [ dropdown pilih anggota ]|
|  Buku    : [ dropdown, hanya stok>0 ]|
|  Tanggal Pinjam : [ auto: hari ini ] |
|                                      |
|          [  Simpan Peminjaman  ]    |
+--------------------------------------+
```

## Wireframe: Form Pengembalian

```
+--------------------------------------+
|  Pengembalian Buku                   |
|--------------------------------------|
|  Cari transaksi aktif:               |
|  [ nama anggota / judul buku ______ ]|
|                                      |
|  Anggota | Buku | Tgl Pinjam | [Kembalikan] |
+--------------------------------------+
```

## Wireframe: Riwayat Peminjaman per Anggota

```
+--------------------------------------+
|  Riwayat Peminjaman — Siti Aminah    |
|--------------------------------------|
|  Buku            | Pinjam   | Kembali | Status      |
|  Laskar Pelangi   | 01/07    | 10/07   | Selesai     |
|  Bumi Manusia      | 15/07    | -       | Dipinjam    |
+--------------------------------------+
```

## Konsistensi dengan Desain yang Sudah Berjalan
- Warna aksen, tipografi navbar, dan gaya tabel/kartu mengikuti `assets/css/style.css` yang sudah dibangun sejak Jobsheet 2-3.
- Navbar akan ditambah menu **Peminjaman** dan indikator status login (nama petugas / tombol Logout) mulai implementasi di Jobsheet 10.
- Edge case yang perlu ditangani saat implementasi: buku stok habis tidak boleh dipilih di form peminjaman; anggota dengan tunggakan terlambat divalidasi di Jobsheet 12 (tugas mandiri).

## Panduan Visual Proyek Haikal

Gunakan [CSS lokal](../assets/css/style.css) sebagai dasar implementasi mockup berikutnya.

| Komponen | Gaya |
|---|---|
| Header dan judul | `--warna-gelap: #222831` |
| Kartu statistik | `--warna-abu: #393E46`, angka putih |
| Aksen navigasi | `--warna-aksen: #00ADB5` |
| Latar halaman / section | `#EEEEEE` / `#FFFFFF` |
| Tombol tambah | Hijau `#198754` |
| Tombol edit / hapus | Oranye `#F0AD4E` / merah `#D9534F` |
| Tombol simpan / masuk | Biru `#1D5B8A` |
| Tipografi | Segoe UI, Arial, sans-serif; line-height 1.5 |
| Kartu dan form | Radius section 8px, input 4px, label di atas input |
| Responsif | Satu kartu hingga 480px, dua mulai 481px, tiga mulai 1024px; hamburger di bawah 768px |

Ringkasan contoh mengikuti beranda sebelumnya: **55** total buku (jumlah stok contoh), **10** anggota, dan **10** sedang dipinjam. Angka peminjaman masih dummy dan belum berasal dari transaksi.

## Pemetaan Navigasi

| Halaman yang ada | Hubungan dengan rancangan |
|---|---|
| [Beranda](../index.html) | Dasar tampilan ringkasan Dashboard Petugas |
| [Daftar Buku](../buku/list.html) | Katalog Tamu; aksi tambah/edit/hapus nantinya khusus Petugas |
| [Tambah Buku](../buku/tambah.html) dan [Edit Buku](../buku/edit.html) | Acuan gaya form untuk transaksi |
| [Daftar Anggota](../anggota/list.html) | Nantinya memiliki tautan Riwayat per anggota |
| [Tambah Anggota](../anggota/tambah.html) dan [Edit Anggota](../anggota/edit.html) | Melengkapi pengelolaan data anggota oleh Petugas |

Tamu nantinya melihat Beranda, Daftar Buku, dan Login. Petugas melihat Dashboard, Buku, Anggota, Peminjaman, Pengembalian, dan Logout. Pada mobile, menu menggunakan pola hamburger yang sama. Semua form transaksi menyediakan aksi Batal untuk kembali ke halaman sebelumnya.

## Alur Kondisi Khusus

```text
Peminjaman Baru
  -> Pilih anggota
     -> Tidak ditemukan: tampilkan pesan dan arahkan ke Tambah Anggota
     -> Ada tunggakan: tampilkan rincian, selesaikan pengembalian dahulu
     -> Memenuhi syarat: pilih buku
        -> Stok habis: nonaktifkan pilihan dan beri keterangan "Stok habis"
        -> Stok tersedia: isi tanggal pinjam dan jatuh tempo
           -> Data tidak valid: tampilkan pesan di dekat input
           -> Valid: simpan transaksi dan kurangi stok satu kali
              -> Berhasil: tampilkan konfirmasi dan tautan Riwayat
              -> Gagal: pertahankan input, stok tidak berubah

Pengembalian
  -> Cari transaksi aktif berdasarkan anggota / judul buku
     -> Tidak ditemukan: tampilkan pesan "Tidak ada pinjaman aktif"
     -> Ditemukan: tampilkan anggota, buku, tanggal pinjam, jatuh tempo
        -> Terlambat: tampilkan status terlambat dan aturan tunggakan
        -> Konfirmasi pengembalian
           -> Batal: kembali ke daftar transaksi aktif
           -> Simpan: tandai selesai dan tambah stok satu kali
              -> Sudah dikembalikan: jangan proses ulang / menambah stok lagi
```

Riwayat harus menyediakan status Dipinjam, Terlambat, dan Selesai beserta tanggal pinjam, jatuh tempo, dan tanggal kembali. Jika kosong, tampilkan "Anggota ini belum memiliki riwayat peminjaman". Aturan nominal denda ditentukan pada tahap implementasi, bukan diasumsikan dari data dummy.

## Persiapan Presentasi

Tunjukkan lima wireframe di atas, jelaskan dua alur transaksi, lalu bandingkan tema dan navigasi dengan halaman yang sudah ada. Catat umpan balik dosen setelah presentasi; dokumen ini belum menyatakan adanya persetujuan atau hasil presentasi.
