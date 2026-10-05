-- Jobsheet 10: tabel users (Petugas) untuk autentikasi
-- Jalankan: psql -d simpus_mini -f sql/02_users.sql

CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'petugas'
);

-- Session disimpan di PostgreSQL agar login tetap terbaca antar-instance Vercel.
CREATE TABLE IF NOT EXISTS geprek_sessions (
    id VARCHAR(128) PRIMARY KEY,
    data TEXT NOT NULL,
    expires_at BIGINT NOT NULL
);
