-- Jalankan di SQL Editor Supabase sekali sebelum memakai fitur pesanan.
-- Tabel lama tidak dihapus. Anggota yang sudah ada tetap bisa digunakan.
CREATE TABLE IF NOT EXISTS anggota (
    id SERIAL PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    no_anggota VARCHAR(50) NOT NULL UNIQUE,
    alamat VARCHAR(255),
    no_hp VARCHAR(30)
);

CREATE TABLE IF NOT EXISTS pesanan (
    id SERIAL PRIMARY KEY,
    anggota_id INTEGER NOT NULL REFERENCES anggota(id) ON DELETE RESTRICT,
    menu VARCHAR(50) NOT NULL,
    harga INTEGER NOT NULL CHECK (harga > 0),
    jumlah INTEGER NOT NULL CHECK (jumlah BETWEEN 1 AND 100),
    dibuat_pada TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
