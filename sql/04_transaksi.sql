-- Jobsheet 12: Pengelolaan Stok Menu dan Status Transaksi Pesanan
-- Menambahkan tabel menu dengan manajemen stok fisik, serta memperluas tabel pesanan
-- dengan kolom status, petugas pencatat (user_id), dan waktu penyelesaian transaksi.

CREATE TABLE IF NOT EXISTS menu (
    id SERIAL PRIMARY KEY,
    kode VARCHAR(50) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    harga INTEGER NOT NULL CHECK (harga > 0),
    stok INTEGER NOT NULL DEFAULT 50 CHECK (stok >= 0)
);

-- Masukkan menu awal Geprek Kita jika belum ada
INSERT INTO menu (kode, nama, harga, stok) VALUES
('original', 'Geprek Original', 15000, 50),
('keju', 'Geprek Keju', 18000, 35),
('sambal_ijo', 'Geprek Sambal Ijo', 16000, 40)
ON CONFLICT (kode) DO NOTHING;

-- Perluas tabel pesanan jika kolom belum tersedia
ALTER TABLE pesanan ADD COLUMN IF NOT EXISTS status VARCHAR(20) NOT NULL DEFAULT 'menunggu';
ALTER TABLE pesanan ADD COLUMN IF NOT EXISTS user_id INTEGER REFERENCES users(id) ON DELETE SET NULL;
ALTER TABLE pesanan ADD COLUMN IF NOT EXISTS selesai_pada TIMESTAMP;
