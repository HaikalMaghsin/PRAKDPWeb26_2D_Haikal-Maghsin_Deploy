# Draf Naskah Presentasi Demo UAS — Geprek Kita

**Presenter**: Haikal Maghsin (NIM: 254107020189)  
**Kelas**: TI 2D — D4 Teknik Informatika, Politeknik Negeri Malang  
**Mata Kuliah**: Desain dan Pemrograman Web  
**Estimasi Waktu**: 5 – 7 Menit

---

## 1. Pembukaan & Latar Belakang (Menit 0:00 – 1:00)
> *"Selamat pagi/siang Bapak Dosen Penguji dan rekan-rekan. Perkenalkan saya Haikal Maghsin, NIM 254107020189 dari kelas TI 2D. Pada kesempatan ujian akhir praktikum Desain dan Pemrograman Web kali ini, saya mempresentasikan proyek akhir aplikasi web berjudul **Geprek Kita**.*
>
> *Geprek Kita adalah sistem informasi operasional warung kuliner berbasis web native yang mencakup siklus pengelolaan pelanggan, varian menu dengan pemotongan stok fisik otomatis, transaksi sirkulasi pesanan, otorisasi peran petugas kasir, dan laporan riwayat keuangan. Aplikasi dibangun dari fondasi HTML5 semantik, CSS responsif modern, interaktivitas DOM JavaScript, hingga backend PHP native yang terintegrasi dengan database relasional PostgreSQL (Supabase)."*

---

## 2. Arsitektur Teknis & Keputusan Desain (Menit 1:00 – 2:30)
> *"Saya ingin menyoroti beberapa keputusan desain teknis penting yang diimplementasikan pada aplikasi ini:*
>
> 1. **Skema Relasional & Integritas Data (PostgreSQL)**:
>    *Basis data dirancang dengan 4 entitas utama: `users` untuk akun petugas/admin, `anggota` untuk data pelanggan, `menu` untuk master makanan beserta stok fisik, dan `pesanan` sebagai entitas transaksi yang mereferensikan anggota dan user.*
>    *Relasi foreign key diatur dengan aturan `ON DELETE RESTRICT` pada pelanggan, memastikan data anggota yang memiliki transaksi aktif tidak bisa terhapus secara tidak sengaja.*
>
> 2. **Transaksi Atomik (ACID) & Pencegahan Race Condition**:
>    *Pada modul pemesanan (Jobsheet 12), ketika pesanan dibuat, sistem membuka transaksi database (`$pdo->beginTransaction()`) dan mengunci baris stok menu menggunakan `SELECT ... FOR UPDATE`. Mekanisme ini mencegah terjadinya kondisi balapan (*race condition*) apabila ada dua kasir mencatat menu yang sama secara simultan saat stok tersisa sedikit. Transaksi menjamin stok menu berkurang tepat sejumlah porsi dan pesanan tersimpan secara atomik.*
>
> 3. **Pemisahan Konfigurasi Lingkungan (Jobsheet 13)**:
>    *Kredensial database dipisahkan secara terpusat pada `includes/config.php` yang membaca variabel lingkungan (`getenv`) dengan penanganan fallback, sehingga repositori publik bebas dari kebocoran password produksi.*"

---

## 3. Pertahanan Keamanan Web (Menit 2:30 – 3:30)
> *"Sesuai capaian Jobsheet 11 tentang pengerasan keamanan (hardening), aplikasi ini menerapkan prinsip defense-in-depth:*
>
> - **Proteksi XSS**: Seluruh output dinamis yang berasal dari input pengguna, database, atau parameter URL dibungkus menggunakan fungsi helper `esc()` berbasis `htmlspecialchars()` dengan flag `ENT_QUOTES` dan encoding `UTF-8`.
> - **Proteksi CSRF**: Setiap form POST dilengkapi token acak 32-byte (`bin2hex(random_bytes(32))`) berbasis sesi. Setiap proses submit diverifikasi menggunakan `hash_equals()`; permintaan tanpa token sah langsung ditolak dengan status HTTP 403 Forbidden.
> - **Pencegahan SQL Injection**: Audit 100% kueri membuktikan tidak ada penggabungan string query langsung; seluruhnya memanfaatkan prepared statements PDO.
> - **Pencegahan Session Fixation**: Segera setelah verifikasi `password_verify()` berhasil, fungsi `session_regenerate_id(true)` dipanggil untuk memperbarui ID sesi pengguna.*"

---

## 4. Demonstrasi Langsung / Live Demo (Menit 3:30 – 5:30)
> *(Langkah peragaan langsung di hadapan penguji):*
>
> 1. **Membuka Beranda**:
>    *"Pertama, kita buka halaman Beranda. Di sini kita melihat katalog menu beserta indikator stok fisik dan kartu ringkasan real-time yang langsung dihitung dari database melalui kueri agregat `COUNT` dan `SUM`."*
>
> 2. **Login Petugas & Guard Sesi**:
>    *"Selanjutnya saya login menggunakan akun petugas. Perhatikan URL dan navigasi; sebelum login, menu Anggota dan Pesanan terkunci. Setelah login berhasil, nama petugas muncul di navbar."*
>
> 3. **Pencatatan Pesanan & Pemotongan Stok**:
>    *"Sekarang kita masuk ke menu Pesanan. Saya memilih pelanggan dan menu 'Geprek Original' sebanyak 2 porsi. Sebelum disimpan, stok awal adalah 50 porsi. Kita klik Simpan Transaksi. Transaksi berhasil, dan jika kita periksa kembali, stok 'Geprek Original' otomatis berkurang menjadi 48 porsi."*
>
> 4. **Penyelesaian Transaksi & Pembaruan Omset**:
>    *"Pesanan yang baru dibuat berstatus 'Menunggu'. Ketika pesanan selesai disajikan dan dibayar, petugas mengklik tombol 'Selesaikan'. Status berubah menjadi hijau ('Selesai')."*
>
> 5. **Membuka Riwayat Transaksi**:
>    *"Kita buka menu Riwayat. Di sini data disajikan melalui kueri multi-tabel JOIN antara pesanan, anggota, dan petugas. Total omset penjualan terakumulasi secara otomatis hanya dari transaksi yang telah selesai."*

---

## 5. Penutup & Tanya Jawab (Menit 5:30 – 6:30)
> *"Demikian presentasi dan demonstrasi proyek aplikasi web Geprek Kita. DariJobsheet 1 hingga 13, aplikasi ini telah berevolusi dari halaman statis sederhana menjadi sistem pencatatan terintegrasi yang siap pakai, aman dari kerentanan umum, dan siap dideploy ke server produksi.*
>
> *Terima kasih atas perhatian Bapak Dosen Penguji, saya persilakan apabila ada pertanyaan atau masukan. Terima kasih."*
