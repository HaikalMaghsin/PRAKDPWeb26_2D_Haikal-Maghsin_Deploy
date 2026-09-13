# SIMPUS-Mini Deploy

Repo ini khusus untuk deploy versi campuran/kumulatif SIMPUS-Mini milik Haikal Maghsin.

Isi root repo adalah versi terbaru yang menggabungkan hasil Jobsheet 1-6, sehingga fitur bisa langsung dicoba dari domain deploy tanpa masuk ke folder Jobsheet tertentu.

## Fitur aktif

- Beranda dengan ringkasan data.
- Daftar buku dan daftar anggota.
- Form tambah dan edit buku.
- Form tambah dan edit anggota.
- Tampilan responsif.
- Hamburger menu menggunakan JavaScript.
- Pencarian tabel real-time.
- Validasi form client-side.
- Konfirmasi hapus baris tabel.
- Data buku dan anggota dimuat dari JSON menggunakan Fetch API.

## Struktur deploy

- `index.html` - halaman utama.
- `buku/` - halaman daftar, tambah, dan edit buku.
- `anggota/` - halaman daftar, tambah, dan edit anggota.
- `assets/` - CSS dan JavaScript.
- `data/` - data JSON buku dan anggota.

Data masih bersifat front-end. Tombol hapus menghapus baris dari tampilan, tetapi belum menyimpan perubahan ke database.