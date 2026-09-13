# Jobsheet 6 - Fetch API & JSON

Pengembangan SIMPUS-Mini milik **Haikal Maghsin**, NIM **254107020189**, kelas **TI 2D**, berdasarkan [Jobsheet 6 Pak Dimas](https://github.com/dimas1984/PemogramanWeb2026/tree/275cf8e753cc0b113b9de4d62806b1fd17ef0fd7/kode-praktikum/jobsheet-06).

## Laporan praktikum

Laporan lengkap untuk Jobsheet 6 dapat dibuka di [Dokumentasi/Laporan/Jobsheet6.md](../Dokumentasi/Laporan/Jobsheet6.md).

## Hasil penggabungan

- Tujuh halaman SIMPUS-Mini tetap tersedia seperti Jobsheet 5.
- Data buku dipindahkan ke [data/buku.json](data/buku.json).
- Data anggota dipindahkan ke [data/anggota.json](data/anggota.json).
- Halaman daftar buku dirender oleh [assets/js/buku.js](assets/js/buku.js).
- Halaman daftar anggota dirender oleh [assets/js/anggota.js](assets/js/anggota.js).
- Loading indicator tampil saat data sedang dimuat.
- Error handling disiapkan jika file JSON gagal dibaca.
- Tombol Hapus memakai event delegation karena baris tabel dibuat setelah `fetch()` selesai.

## Cara menjalankan

Jobsheet 6 perlu dijalankan melalui server lokal karena browser biasanya memblokir `fetch()` ke file JSON jika halaman dibuka langsung memakai `file://`.

Gunakan Live Server di VS Code, atau jalankan:

```bash
php -S localhost:8000
```

Lalu buka `http://localhost:8000/Jobsheet6/index.html` jika server dijalankan dari root repo.

## Materi sumber

[Dokumentasi Pak Dimas](Dokumentasi/README.md) disertakan sebagai bahan belajar. Versi ini menyesuaikan data JSON dengan sepuluh buku dan sepuluh anggota milik Haikal.
