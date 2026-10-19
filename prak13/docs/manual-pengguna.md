# Manual Pengguna — Sistem Informasi Geprek Kita

**Pengembang**: Haikal Maghsin (NIM: 254107020189) · Kelas TI 2D  
**Mata Kuliah**: Desain dan Pemrograman Web  
**Aplikasi**: Geprek Kita (Pencatatan Pelanggan, Menu, dan Transaksi Ayam Geprek)

---

## 1. Pengantar Aplikasi
Aplikasi **Geprek Kita** adalah sistem pencatatan operasional warung ayam geprek yang menghubungkan pelanggan (anggota), varian menu dengan pemotongan stok otomatis, transaksi pesanan berstatus sirkulasi, dan akun petugas kasir/admin.

---

## 2. Alur Penggunaan Aplikasi

### Langkah 1: Membuka Beranda & Katalog Menu
1. Akses halaman utama di `/prak13/index.php`.
2. Halaman menampilkan:
   - **Kartu Statistik Real-Time**: Varian Menu, Pelanggan Terdaftar, Pesanan Menunggu, dan Total Omset Penjualan.
   - **Tabel Menu & Ketersediaan Stok**: Daftar menu lengkap beserta harga porsi dan status sisa stok fisik (Tersedia, Menipis, atau Habis).
3. Pengunjung dapat melihat menu tanpa harus login. Untuk melakukan pencatatan transaksi dan pengelolaan data, pengguna wajib masuk sebagai Petugas.

---

### Langkah 2: Registrasi & Login Petugas
1. Klik tautan **Login Petugas** di pojok kanan atas navbar.
2. Jika belum memiliki akun:
   - Klik **Daftar di sini** menuju form registrasi.
   - Masukkan Nama Lengkap, Username unik, dan Password (minimal 6 karakter).
   - Klik tombol **Daftar**. Akun baru otomatis terdaftar dengan peran (*role*) `petugas`.
3. Pada halaman Login:
   - Masukkan Username dan Password yang telah didaftarkan.
   - Klik tombol **Masuk**.
   - Sistem memverifikasi hash kata sandi, meregenerasi ID sesi baru, dan menampilkan nama Anda pada navigasi atas.

---

### Langkah 3: Mengelola Data Anggota (Pelanggan)
1. Pilih menu **Anggota** pada navigasi utama.
2. **Melihat Anggota**: Tabel menyajikan daftar nomor anggota, nama, alamat, nomor telepon, serta tombol aksi.
3. **Mencari Anggota**: Masukkan nama atau nomor anggota pada kotak pencarian lalu tekan tombol **Cari**.
4. **Menambah Anggota Baru**:
   - Klik tombol **+ Tambah Anggota**.
   - Isi Nama, Nomor Anggota unik (mis. `PLG-001`), Alamat, dan Nomor HP.
   - Klik **Simpan anggota**.
5. **Mengubah Data Anggota**:
   - Klik tombol **Edit** pada baris anggota yang ingin diperbarui.
   - Ubah informasi pada form, lalu klik **Simpan Perubahan**.
6. **Menghapus Anggota (Khusus Admin)**:
   - Tombol **Hapus** hanya dapat dieksekusi oleh akun dengan role `admin`.
   - Anggota yang masih memiliki riwayat transaksi/pesanan tidak dapat dihapus (*Foreign Key RESTRICT*). Hapus atau selesaikan pesanannya terlebih dahulu.

---

### Langkah 4: Mencatat Pesanan Baru & Manajemen Stok
1. Buka menu **Pesanan**.
2. Form **Pesan Menu Baru** akan menampilkan:
   - **Pilihan Anggota**: Menampilkan nama dan nomor anggota. Jika anggota telah memiliki 3 pesanan berstatus *Menunggu*, sistem akan mengunci opsi anggota tersebut hingga pesanan sebelumnya diselesaikan.
   - **Pilihan Menu**: Menampilkan nama menu, harga porsi, dan sisa stok fisik secara *real-time*.
   - **Jumlah Porsi**: Masukkan jumlah porsi yang dipesan (antara 1 sampai 100).
   - **Perkiraan Total**: JavaScript akan menghitung subtotal otomatis tanpa perlu memuat ulang halaman.
3. Klik tombol **Simpan Transaksi**.
4. Sistem menjalankan transaksi atomik database (ACID) yang mengunci baris stok menu (`SELECT ... FOR UPDATE`), memverifikasi kecukupan stok, mencatat pesanan baru dengan status `Menunggu`, dan memotong sisa stok fisik menu secara otomatis.

---

### Langkah 5: Memproses & Menyelesaikan Pesanan
1. Pada tabel **Daftar Pesanan & Status Sirkulasi**:
   - Pesanan baru berstatus badge oranye: `Menunggu`.
2. Setelah makanan selesai disajikan dan dibayar oleh pelanggan:
   - Klik tombol hijau **Selesaikan** pada kolom aksi.
   - Konfirmasi dialog "Tandai pesanan ini sudah selesai dan dibayar?".
   - Status pesanan berganti menjadi badge hijau: `Selesai`, dan waktu penyelesaian dicatat ke basis data.
3. Jika pesanan dibatalkan:
   - Klik tombol merah **Batalkan**.
   - Sistem secara atomik mengembalikan jumlah porsi pesanan kembali ke stok fisik menu di database.

---

### Langkah 6: Memeriksa Riwayat Transaksi & Omset
1. Buka menu **Riwayat** pada navigasi utama (`pesanan/riwayat.php`).
2. Halaman ini menyediakan:
   - **Indikator Keuangan**: Total Transaksi, Jumlah Antrean Menunggu, Jumlah Pesanan Selesai, dan Akumulasi Omset Penjualan Aktual (hanya dari transaksi lunas).
   - **Filter Transaksi**: Saring riwayat berdasarkan nama pelanggan atau status pembayaran.
   - **Audit Jejak Petugas**: Menampilkan petugas kasir yang bertanggung jawab mencatat setiap transaksi.

---

### Langkah 7: Logout
1. Klik tautan **Logout** di pojok kanan atas navbar.
2. Sesi pengguna akan dihapus dari server dan cookie sesi dinonaktifkan.
3. Seluruh akses menu data anggota, pencatatan pesanan, dan riwayat akan terkunci kembali dari pihak umum.
