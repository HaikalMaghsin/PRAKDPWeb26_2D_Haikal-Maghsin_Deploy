# Laporan Praktikum Jobsheet 12

## Integrasi Modul Transaksi & Relasi Utuh

### Identitas

| Keterangan | Isi |
|---|---|
| Nama | Haikal Maghsin |
| NIM | 254107020189 |
| Kelas | TI 2D |
| Mata Kuliah | Desain dan Pemrograman Web |

---

## 1. Tujuan

Tujuan Praktikum Jobsheet 12 adalah mengintegrasikan seluruh entitas sistem aplikasi **Geprek Kita** (anggota pelanggan, menu makanan, petugas kasir, dan transaksi pesanan) menjadi satu alur operasional yang utuh, menerapkan transaksi basis data atomik (ACID) dengan isolasi penguncian baris (`SELECT ... FOR UPDATE`), mengelola status sirkulasi pesanan, menyajikan riwayat transaksi multi-tabel JOIN, serta menampilkan indikator statistik real-time pada Beranda.

---

## 2. Pengerjaan

Saya membuat folder `prak12` sebagai pengembangan langsung dari `prak11`. Pada jobsheet ini modul transaksi dan sirkulasi pesanan dibangun secara terintegrasi:

1. **Skema Basis Data Menu & Status Transaksi**:
   - Menyusun skema migrasi `sql/04_transaksi.sql` yang menambahkan tabel `menu` (menyimpan `id`, `kode`, `nama`, `harga`, dan `stok` fisik).
   - Memperluas tabel `pesanan` dengan menambahkan kolom `status` (bernilai `'menunggu'` atau `'selesai'`), `user_id` (foreign key ke petugas kasir), dan timestamp `selesai_pada`.
2. **Pemesanan Atomik (ACID) & Pencegahan Race Condition**:
   - Pada `pesanan/list.php`, proses pemesanan dibungkus dalam blok transaksi `$pdo->beginTransaction()`.
   - Mengambil dan mengunci baris stok menu menggunakan `SELECT ... FOR UPDATE` sebelum verifikasi jumlah.
   - Jika stok fisik menu tidak mencukupi, sistem melakukan `$pdo->rollBack()` dan mengembalikan pesan error informatif.
   - Jika stok cukup, data pesanan disimpan dan stok fisik pada tabel `menu` dikurangi (`stok = stok - ?`) secara atomik, lalu disahkan dengan `$pdo->commit()`.
3. **Penyelesaian & Pembatalan Transaksi**:
   - Menambahkan aksi `selesai` pada daftar pesanan: petugas kasir menandai pesanan yang telah disajikan dan dibayar, status diperbarui menjadi `'selesai'`, dan timestamp `selesai_pada` dicatat.
   - Menambahkan mekanisme pembatalan pesanan: jika pesanan berstatus `'menunggu'` dihapus/dibatalkan, porsi makanan secara atomik dikembalikan (*refund*) ke stok fisik menu.
4. **Modul Riwayat Transaksi (`pesanan/riwayat.php`)**:
   - Membangun halaman riwayat komprehensif yang menggabungkan tiga tabel sekaligus (`pesanan`, `anggota`, dan `users`) menggunakan klausa `JOIN`.
   - Menghitung rekapitulasi indikator keuangan: Total Transaksi, Porsi Terjual, Antrean Menunggu, dan Akumulasi Omset Penjualan Bersih (hanya dari transaksi berstatus `'selesai'`).
   - Menyediakan fitur filter status dan pencarian transaksi.
5. **Pembaruan Kartu Statistik Beranda (`index.php`)**:
   - Menggantikan tampilan statis di Beranda dengan kartu metrik dinamis yang dihitung secara *real-time* dari PostgreSQL via fungsi agregat `COUNT` dan `SUM`.
6. **Navbar Terpadu**:
   - Menambahkan menu navigasi Beranda, Anggota, Pesanan, dan Riwayat Transaksi saat petugas login.

---

## 3. Improvisasi

Saya menerapkan **Tugas Mandiri** penegakan aturan bisnis (*business logic rule*): pelanggan yang masih memiliki 3 pesanan berstatus `'menunggu'` secara otomatis dibatasi dari menambah pesanan baru. Sistem menonaktifkan opsi pelanggan tersebut pada formulir dan memvalidasi ulang di sisi server guna mencegah penumpukan antrean yang belum terbayar. Selain itu, menu dropdown menampilkan sisa stok fisik riil, dan label kondisi stok (Tersedia, Menipis, Habis) ditampilkan secara dinamis di katalog Beranda.

---

## 4. Hasil Pengujian

| No. | Pengujian | Hasil yang Diharapkan | Hasil Aktual |
|---:|---|---|---|
| 1 | Pembuatan tabel `menu` dan kolom status | Tabel dan kolom terbentuk di Supabase | Berhasil terbentuk |
| 2 | Kunci baris `SELECT ... FOR UPDATE` | Stok menu terkunci selama transaksi berlangsung | Berhasil dieksekusi tanpa deadlock |
| 3 | Pesan menu dengan stok cukup | Pesanan tersimpan dan stok menu berkurang otomatis | Berhasil, stok berkurang tepat sesuai porsi |
| 4 | Pesan menu melebihi sisa stok fisik | Ditolak oleh transaksi server dengan pesan stok kurang | Berhasil ditolak, stok tidak minus |
| 5 | Batalkan pesanan 'menunggu' | Pesanan terhapus dan stok porsi pulih kembali | Berhasil, stok bertambah kembali |
| 6 | Klik tombol "Selesaikan" pesanan | Status berubah jadi 'selesai' dan timestamp tercatat | Berhasil, status menjadi hijau 'Selesai' |
| 7 | Buka halaman Riwayat Transaksi | Menampilkan tabel histori lengkap via JOIN 3 tabel | Berhasil, data pelanggan & kasir tampil |
| 8 | Omset penjualan di Riwayat & Beranda | Mengakumulasikan omset hanya dari transaksi selesai | Berhasil dihitung secara real-time |
| 9 | Uji pelanggan dengan 3 pesanan menunggu | Pilihan anggota terkunci dan submit ditolak | Berhasil ditolak oleh aturan bisnis |
| 10 | Pemeriksaan sintaks seluruh berkas (`php -l`) | 0 kesalahan sintaks | Lolos 100% tanpa error |

---

## 5. Kendala

Potensi inkonsistensi data dapat terjadi bila dua kasir secara bersamaan mencatat pesanan untuk menu yang sama saat stok tersisa sedikit (*race condition*). Hal ini berhasil diatasi secara tuntas menggunakan transaksi terisolasi dan penguncian baris `SELECT ... FOR UPDATE` pada tabel menu, sehingga stok makanan dijamin tidak akan pernah bernilai negatif.

---

## 6. Kesimpulan

Pada Jobsheet 12, integrasi seluruh modul front-end dan back-end aplikasi Geprek Kita telah tercapai secara paripurna. Alur pemesanan makanan, validasi dan pemulihan stok fisik secara atomik, penyelesaian transaksi, serta pelaporan riwayat penjualan berjalan harmonis dengan integritas data yang tinggi berkat penerapan transaksi ACID pada PostgreSQL.
