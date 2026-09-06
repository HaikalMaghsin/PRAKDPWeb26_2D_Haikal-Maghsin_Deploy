# SIMPUS-Mini — Deploy

Versi website terbaru dari proyek praktikum **Haikal Maghsin**, NIM **254107020189**, kelas **TI 2D**.

- [Website](https://haikalmaghsin.github.io/PRAKDPWeb26_2D_Haikal-Maghsin_Deploy/)
- [Repository tugas](https://github.com/HaikalMaghsin/PRAKDPWeb26_2D_Haikal-Maghsin)
- [Wireframe dan user flow Jobsheet 4](docs/wireframe.md)

## Versi saat ini

Kode website berasal dari folder `Jobsheet4` di repo tugas. Isinya mencakup tujuh halaman beranda, daftar/tambah/edit buku, serta daftar/tambah/edit anggota, dengan data dan tema Haikal. Jobsheet 3 menambahkan meta viewport, hamburger CSS, tabel yang dapat digeser, dan kartu ringkasan responsif. Jobsheet 4 melengkapi rancangan UI/UX.

Form belum menyimpan data. Login dan transaksi peminjaman/pengembalian masih berupa rancangan untuk jobsheet berikutnya.

## Menjalankan

Buka `index.html` langsung di browser atau gunakan Live Server. Semua aset menggunakan path relatif sehingga dapat berjalan pada subfolder GitHub Pages.

GitHub Pages menggunakan branch `main`, folder `/ (root)`. File `.nojekyll` memastikan file statis disajikan tanpa pemrosesan Jekyll.

## Memperbarui deploy

Salin `index.html`, `buku/`, `anggota/`, `assets/`, dan `docs/` dari jobsheet terbaru ke root repo deploy, lalu commit dan push ke `main`. Pembaruan repo tugas tidak otomatis disalin ke repo ini. Jangan menyalin folder `.git` dari repo tugas.

## Sumber materi

Dikembangkan dari [materi Pak Dimas](https://github.com/dimas1984/PemogramanWeb2026/tree/2db73f56ff348f4ed0502ea2f8aedd710fb38420/kode-praktikum) dengan penyesuaian tema, data, halaman edit, dan responsivitas dari proyek Haikal.

## Validasi

Tujuh halaman disalin tanpa perubahan dari versi Jobsheet 4 yang telah lolos pemeriksaan layout pada lebar 375, 480, 600, 768, dan 1024 piksel. Tautan serta lokasi CSS diperiksa kembali setelah penempatan di root repo deploy.
