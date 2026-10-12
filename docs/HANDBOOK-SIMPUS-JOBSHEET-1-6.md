# Handbook SIMPUS-Mini: Panduan Penguasaan Jobsheet 1–6

**Haikal Maghsin · NIM 254107020189 · Kelas TI 2D**  
Mata Kuliah: Desain dan Pemrograman Web (DPWeb)  
Topik: Pemrograman Web Sisi Klien (HTML, CSS, RWD, UI/UX, JavaScript DOM, Fetch API & JSON)  
Edisi: Persiapan Uji Praktikum & Ujian Tengah Semester (UTS)

---

## Daftar Isi
1. [Peta Jalan & Evolusi Aplikasi (Jobsheet 1 sampai 6)](#1-peta-jalan--evolusi-aplikasi-jobsheet-1-sampai-6)
2. [Bab 1 — HTML5 Dasar & Struktur Semantik (Jobsheet 1)](#bab-1--html5-dasar--struktur-semantik-jobsheet-1)
3. [Bab 2 — CSS Dasar, Box Model, Typography & Warna (Jobsheet 2)](#bab-2--css-dasar-box-model-typography--warna-jobsheet-2)
4. [Bab 3 — Responsive Web Design, Media Query & CSS Grid (Jobsheet 3)](#bab-3--responsive-web-design-media-query--css-grid-jobsheet-3)
5. [Bab 4 — Perancangan UI/UX, Wireframe & User Flow (Jobsheet 4)](#bab-4--perancangan-uiux-wireframe--user-flow-jobsheet-4)
6. [Bab 5 — JavaScript DOM, Validasi Form, Live Search & Dashboard (Jobsheet 5)](#bab-5--javascript-dom-validasi-form-live-search--dashboard-jobsheet-5)
7. [Bab 6 — Asynchronous JavaScript, JSON & Fetch API (Jobsheet 6)](#bab-6--asynchronous-javascript-json--fetch-api-jobsheet-6)
8. [Bab 7 — Kamus Istilah & Tanya-Jawab Penguji (FAQ Dosen & Aslab)](#bab-7--kamus-istilah--tanya-jawab-penguji-faq-dosen--aslab)

---

## 1. Peta Jalan & Evolusi Aplikasi (Jobsheet 1 sampai 6)

Aplikasi **SIMPUS-Mini** (Sistem Informasi Perpustakaan Mini) dibangun secara bertahap dari Jobsheet 1 hingga Jobsheet 6. Memahami garis waktu ini sangat penting agar kamu bisa menjelaskan proses evolusi kodenya kepada dosen/aslab:

```
[Jobsheet 1] HTML Murni (Semantik, Tabel Statis, Form Polos)
     │
     ▼
[Jobsheet 2] CSS Styling (Reset, Box Model, Palette Warna, Zebra Table, Button)
     │
     ▼
[Jobsheet 3] Responsive Web Design (Mobile-First, Hamburger CSS-Only, CSS Grid)
     │
     ▼
[Jobsheet 4] Desain UI/UX (Wireframing, User Flow Peminjaman & Pengembalian, Role)
     │
     ▼
[Jobsheet 5] Interaktivitas JS DOM (Validasi Form, Confirm Delete, Live Search Table, Split Dashboard)
     │
     ▼
[Jobsheet 6] Asinkron & Data (JSON Data, Fetch API, Promise, Async/Await, Dynamic DOM Injection)
```

| Praktikum | Fokus Utama | File Kunci | Ciri Khas Tampilan / Fitur |
| :--- | :--- | :--- | :--- |
| **Prak 1** | Struktur Tulang Web | `index.html`, `buku/list.html`, `anggota/list.html` | Polos hitam putih tanpa CSS, tabel default browser |
| **Prak 2** | Estetika & Visual | `assets/css/style.css` | Mulai ada warna `#222831`, tombol warna-warni, tabel bergaris |
| **Prak 3** | Responsivitas Layar | `assets/css/style.css` | Tampilan bisa mengecil di HP, menu hamburger, kartu 3 kolom |
| **Prak 4** | Perancangan Sistem | `docs/wireframe.md` | Dokumen alur user flow tamu vs petugas dan sketsa ASCII |
| **Prak 5** | Interaksi & Validasi | `assets/js/app.js`, `style.css` | Pencarian langsung saat ngetik, validasi tahun/stok, Dashboard 2 kolom |
| **Prak 6** | Konsumsi Data JSON | `assets/js/buku.js`, `data/buku.json` | Tabel kosong saat HTML dimuat, diisi otomatis via `fetch()` |

---

## Bab 1 — HTML5 Dasar & Struktur Semantik (Jobsheet 1)

**Tujuan:** Membangun kerangka halaman web perpustakaan yang memiliki arti struktural (*semantic*) bagi browser dan mesin pencari, tanpa bantuan CSS maupun JavaScript.

### 1.1 Tag Semantik HTML5
Tag semantik adalah tag yang namanya secara eksplisit menjelaskan fungsinya di dalam dokumen:
* `<header>`: Kepala halaman, membungkus judul utama aplikasi (`<h1>`) dan navigasi.
* `<nav>`: Area navigasi yang membungkus daftar link (`<ul><li><a href="...">`).
* `<main>`: Konten utama yang unik untuk halaman tersebut. Tidak boleh ada lebih dari satu `<main>` per halaman.
* `<section>`: Pengelompokan bagian konten yang memiliki topik/judul (`<h2>`).
* `<article>`: Bagian konten mandiri yang berdiri sendiri (digunakan untuk tiap kartu ringkasan).
* `<footer>`: Kaki halaman, berisi informasi hak cipta (`&copy;`) dan identitas jobsheet.

### 1.2 Struktur Tabel Data
Tabel digunakan untuk menampilkan data katalog buku dan daftar anggota:
```html
<table border="1">
    <thead>
        <tr>
            <th>Judul</th>
            <th>Pengarang</th>
            <th>Tahun</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Laskar Pelangi</td>
            <td>Andrea Hirata</td>
            <td>2005</td>
            <td>7</td>
            <td>
                <a href="edit.html">Edit</a>
                <a href="#">Hapus</a>
            </td>
        </tr>
    </tbody>
</table>
```
* `<thead>` dan `<tbody>` memisahkan kepala tabel (kolom) dengan baris isi data.
* `<th>` (*Table Header*) otomatis dicetak tebal dan rata tengah oleh browser.
* `<td>` (*Table Data*) menampung nilai tiap sel data.

### 1.3 Form Input dan Atribut HTML5
Digunakan pada form penambahan buku dan anggota:
```html
<form action="#" method="get">
    <p>
        <label for="judul">Judul Buku</label>
        <input type="text" id="judul" name="judul" required>
    </p>
    <p>
        <label for="tahun">Tahun Terbit</label>
        <input type="number" id="tahun" name="tahun" min="1900" max="2026">
    </p>
    <button type="submit">Simpan</button>
</form>
```
* **Hubungan `<label for="id">` dan `<input id="id">`**: Memastikan saat pengguna mengklik teks label, kursor langsung aktif (*focus*) di dalam kotak input terkait. Ini syarat penting aksesibilitas web (A11y).
* **Atribut `name`**: Kunci parameter yang dikirimkan ke server saat form disubmit.
* **Atribut `required`**: Validasi bawaan browser agar field tidak boleh kosong.

---

## Bab 2 — CSS Dasar, Box Model, Typography & Warna (Jobsheet 2)

**Tujuan:** Memberikan gaya tampilan, tata letak, warna, dan hirarki visual sehingga aplikasi nyaman dipandang.

### 2.1 CSS Reset & `box-sizing: border-box`
Di baris paling atas CSS:
```css
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}
```
* **Kenapa wajib `box-sizing: border-box`?**  
  Secara default (`content-box`), jika sebuah kotak diberi `width: 200px; padding: 20px; border: 5px;`, maka lebar total kotak menjadi `200 + 40 + 10 = 250px` (melar keluar!).  
  Dengan `border-box`, lebar total tetap **200px**. Padding dan border dimasukkan ke dalam ruang yang ada. Layout tidak akan bocor/hancur.

### 2.2 Color Palette dengan CSS Variables (`:root`)
Variabel CSS didefinisikan secara global di `:root`:
```css
:root {
    --warna-gelap: #222831;   /* Warna header & tombol submit */
    --warna-abu:   #393E46;   /* Warna latar kartu statistik */
    --warna-aksen: #c9c9c9;   /* Warna highlight teks/link hover */
    --warna-terang: #EEEEEE;  /* Background body */
    --warna-putih:  #FFFFFF;  /* Background section & teks putih */
    --warna-hapus:  #D9534F;  /* Warna merah tombol hapus */
}
```
* **Keuntungan:** Jika ingin mengganti skema warna seluruh website, cukup ubah satu nilai hex di `:root`, semua halaman otomatis berubah serempak.

### 2.3 Styling Tabel Modern
* `border-collapse: collapse;`: Menyatukan border antar sel agar tidak bergaris ganda.
* `tbody tr:nth-child(even)`: Menerapkan **Zebra Striping** (baris genap abu-abu tipis, baris ganjil putih) agar mata pengguna tidak selip saat membaca data tabel yang banyak.
* `tbody tr:hover`: Memberikan highlight saat kursor melintas di atas baris.

### 2.4 Tombol Aksi (`.btn-aksi`)
```css
.btn-aksi {
    display: inline-block;
    padding: 0.35rem 0.7rem;
    border-radius: 4px;
    font-size: 0.85rem;
    text-decoration: none;
    cursor: pointer;
}
.btn-edit  { background-color: #f0ad4e; color: #fff; } /* Kuning oranye */
.btn-hapus { background-color: #d9534f; color: #fff; } /* Merah */
.btn-tambah{ background-color: #198754; color: #fff; } /* Hijau */
```

---

## Bab 3 — Responsive Web Design, Media Query & CSS Grid (Jobsheet 3)

**Tujuan:** Memastikan halaman web dapat menyesuaikan tata letaknya secara mulus baik di layar HP (360px), tablet (768px), maupun laptop/desktop (1024px+).

### 3.1 Konsep Mobile-First
* CSS ditulis dari aturan layar terkecil terlebih dahulu (tanpa media query).
* Ketika layar melebar, kita gunakan `@media (min-width: ...)` untuk menambah kolom atau mengubah posisi elemen.
* Keunggulan: Performa di perangkat mobile lebih ringan karena tidak perlu membatalkan aturan desktop yang berat.

### 3.2 Trik Hamburger Menu Tanpa JavaScript (CSS-Only Checkbox Hack)
Di Jobsheet 3, menu hamburger dioperasikan murni oleh CSS tanpa setetes pun JavaScript:
```html
<input type="checkbox" id="nav-toggle" class="nav-toggle sr-only">
<label for="nav-toggle" class="nav-toggle-label">&#9776;</label>
<nav>
    <ul>...</ul>
</nav>
```
Cara kerjanya di CSS:
1. Checkbox disembunyikan menggunakan kelas `.sr-only`.
2. Ikon hamburger dibuat dari `<label for="nav-toggle">&#9776;</label>`. Saat ikon diklik, checkbox otomatis tercentang (`checked`).
3. Selector CSS menangkap status centang tersebut:
   ```css
   header nav { display: none; } /* Default: navigasi sembunyi */
   .nav-toggle:checked ~ nav { display: block; } /* Jika checkbox tercentang, buka nav! */
   ```
4. Di desktop (`@media (min-width: 768px)`), checkbox dan ikon hamburger di-`display: none;`, sedangkan `header nav` dibuat `display: block;` permanen.

### 3.3 Kartu Statistik dengan CSS Grid
```css
/* Tampilan Desktop Asli */
main section:nth-of-type(2) {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* 3 kolom sama lebar */
    gap: 1rem;
}
```
* `repeat(3, 1fr)` membagi lebar kontainer menjadi 3 pecahan sama rata.

### 3.4 Solusi Tabel Meluap: `.table-responsive`
Layar HP terlalu sempit untuk memuat 5 kolom tabel sekaligus. Solusinya:
```css
.table-responsive {
    max-width: 100%;
    overflow-x: auto; /* Munculkan scrollbar horizontal jika tabel lebih lebar dari layar */
}
.table-responsive table {
    min-width: 640px; /* Jaga agar teks tabel tidak berhimpitan */
}
```

---

## Bab 4 — Perancangan UI/UX, Wireframe & User Flow (Jobsheet 4)

**Tujuan:** Merancang pengalaman pengguna (UX) dan antarmuka (UI) sebelum melangkah ke implementasi logika program yang kompleks.

### 4.1 Pembagian Aktor
* **Tamu (Guest)**: Pengguna publik tanpa autentikasi. Hanya diizinkan melihat katalog buku dan anggota (Read-Only).
* **Petugas (Staff / Admin)**: Pengguna yang login menggunakan kredensial (Username & Password). Memiliki hak akses penuh (CRUD) dan transaksi peminjaman/pengembalian.

### 4.2 User Flow Peminjaman & Pengembalian
Alur transaksi peminjaman buku:
```
[Petugas Login] ──> [Buka Dashboard] ──> [Klik "Peminjaman Baru"]
                          │
                          ▼
                 [Pilih Anggota Peminjam]
                          │
                          ▼
                 [Pilih Judul Buku (Stok > 0)]
                          │
                          ▼
                 [Klik Simpan Transaksi]
                          │
                          ▼
                 [Stok Buku Berkurang -1] ──> [Kembali ke Dashboard]
```

Alur transaksi pengembalian buku:
```
[Buka Daftar Peminjaman Aktif] ──> [Cari Nama Anggota / ID Transaksi]
                                              │
                                              ▼
                                     [Klik "Tandai Kembali"]
                                              │
                                              ▼
                                     [Stok Buku Bertambah +1]
                                              │
                                              ▼
                                     [Status Berubah: "Selesai"]
```

---

## Bab 5 — JavaScript DOM, Validasi Form, Live Search & Dashboard (Jobsheet 5)

**Tujuan:** Memberikan interaktivitas dinamis pada halaman menggunakan JavaScript berbasis *Document Object Model* (DOM).

Di Jobsheet 5, file JavaScript utama berada di [prak5/assets/js/app.js](file:///E:/Tugas%20Kuliah/Second%20Collage/PRAKDPWeb26_2D_Haikal-Maghsin_Deploy/prak5/assets/js/app.js).

### 5.1 Event `DOMContentLoaded`
```javascript
document.addEventListener("DOMContentLoaded", function () {
    initNavToggle();
    initHapusConfirm();
    initTableFilter();
    initValidasiForm();
});
```
* **Mengapa harus menunggu `DOMContentLoaded`?**  
  Agar kode JavaScript baru dijalankan setelah seluruh elemen HTML selesai dibaca oleh browser. Jika dijalankan sebelum HTML siap, pemanggilan elemen seperti `document.getElementById(...)` akan menghasilkan `null` (error!).

---

### 5.2 Fitur 1: Konfirmasi Hapus Baris Dinamis
```javascript
function initHapusConfirm() {
    const tombolHapus = document.querySelectorAll(".btn-hapus");

    tombolHapus.forEach(function (tombol) {
        tombol.addEventListener("click", function () {
            const baris = tombol.closest("tr");
            const namaData = baris ? baris.querySelector("td")?.textContent.trim() : "data ini";
            const yakin = confirm('Yakin ingin menghapus "' + namaData + '"?');

            if (yakin && baris) {
                baris.remove(); /* Menghapus elemen <tr> langsung dari DOM */
            }
        });
    });
}
```
* `tombol.closest("tr")`: Berjalan ke atas hirarki DOM mencari elemen induk baris `<tr>` terdekat dari tombol hapus yang diklik.
* `confirm(...)`: Memunculkan dialog konfirmasi standar browser. Mengembalikan `true` jika pengguna menekan tombol "OK", dan `false` jika menekan "Cancel".
* `baris.remove()`: Menghapus baris secara instan dari tampilan layar tanpa me-refresh halaman.

---

### 5.3 Fitur 2: Live Search Filtering (Pencarian Real-Time)
```javascript
function initTableFilter() {
    const inputCari = document.getElementById("search-input");
    const tabel = document.querySelector(".table-responsive table");

    if (!inputCari || !tabel) return;

    inputCari.addEventListener("keyup", function () {
        const keyword = inputCari.value.toLowerCase();
        const baris = tabel.querySelectorAll("tbody tr");

        baris.forEach(function (row) {
            const isiBaris = row.textContent.toLowerCase();
            row.style.display = isiBaris.includes(keyword) ? "" : "none";
        });
    });
}
```
* **Event `keyup`**: Memicu pencarian setiap kali pengguna melepaskan tombol keyboard saat mengetik di input pencarian.
* `.toLowerCase()`: Mengubah teks input dan teks tabel menjadi huruf kecil agar pencarian bersifat *case-insensitive* (mengetik "laskar" tetap menemukan "Laskar").
* `.includes(keyword)`: Mengecek apakah teks dalam baris mengandung kata kunci. Jika cocok, baris ditampilkan (`""`); jika tidak cocok, disembunyikan (`"none"`).

---

### 5.4 Fitur 3: Validasi Form Multilevel Sisi Klien
```javascript
function validasiAngka(form) {
    let valid = true;
    const tahun = form.querySelector("[name='tahun']");
    const stok = form.querySelector("[name='stok']");

    if (tahun && tahun.value.trim() !== "") {
        const nilaiTahun = Number(tahun.value);
        if (nilaiTahun < 1900 || nilaiTahun > 2026) {
            tampilkanError(tahun, "Tahun harus berada di antara 1900 sampai 2026.");
            valid = false;
        }
    }

    if (stok && stok.value.trim() !== "") {
        const nilaiStok = Number(stok.value);
        if (nilaiStok < 0) {
            tampilkanError(stok, "Stok tidak boleh bernilai negatif.");
            valid = false;
        }
    }

    return valid;
}
```
* **`event.preventDefault()`**: Jika salah satu validasi gagal (`!wajibValid || !angkaValid`), pengiriman form dibatalkan sehingga halaman tidak reload dan data pengguna tidak hilang.
* **`tampilkanError(...)`**: Membuat elemen `<span class="error">` baru secara dinamis dan menyisipkannya tepat di bawah input yang bermasalah menggunakan `insertAdjacentElement("afterend", error)`.

---

### 5.5 Inovasi Khusus: Split Hero Dashboard 2 Kolom (Prak 5 & 6)
Fitur unggulan pembeda yang kamu buat:
```css
/* Mengunci tata letak 2 kolom khusus di Beranda */
@media (min-width: 768px) {
    main.dashboard-grid {
        display: grid;
        grid-template-columns: 1fr 1fr; /* 50% kiri : 50% kanan */
        gap: 1.5rem;
        align-items: stretch;          /* Tinggi kotak kiri & kanan sama persis */
    }

    /* Kotak Kiri: Hero Banner dengan Tombol Aksi */
    main.dashboard-grid .sec-hero {
        padding: 2rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between; /* Judul di atas, tombol di paling bawah */
    }

    /* Kotak Kanan: Kartu Statistik */
    main.dashboard-grid .sec-stats {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 0.75rem;
    }

    main.dashboard-grid .sec-stats article h3 {
        white-space: nowrap; /* Teks "Sedang Dipinjam" tidak akan pernah patah baris */
    }
}
```

---

## Bab 6 — Asynchronous JavaScript, JSON & Fetch API (Jobsheet 6)

**Tujuan:** Mengambil data eksternal berformat JSON dari server/file secara asinkron menggunakan Fetch API dan merendernya ke tabel HTML secara dinamis.

Di Jobsheet 6, file kuncinya adalah:
* Sumber data: [prak6/data/buku.json](file:///E:/Tugas%20Kuliah/Second%20Collage/PRAKDPWeb26_2D_Haikal-Maghsin_Deploy/prak6/data/buku.json) dan `anggota.json`.
* Script pemroses: [prak6/assets/js/buku.js](file:///E:/Tugas%20Kuliah/Second%20Collage/PRAKDPWeb26_2D_Haikal-Maghsin_Deploy/prak6/assets/js/buku.js).

### 6.1 Format Data JSON
Contoh isi file `data/buku.json`:
```json
[
  {
    "id": 1,
    "judul": "Laskar Pelangi",
    "pengarang": "Andrea Hirata",
    "tahun": 2005,
    "stok": 7
  },
  {
    "id": 2,
    "judul": "Bumi Manusia",
    "pengarang": "Pramoedya Ananta Toer",
    "tahun": 1980,
    "stok": 4
  }
]
```
* **Ciri khas JSON**: Kunci (*key*) wajib diapit tanda petik ganda (`"judul"`), format teks ringan, dan didukung secara native oleh JavaScript melalui fungsi `JSON.parse()` dan `JSON.stringify()`.

---

### 6.2 Bedah Kode `muatDaftarBuku()` (Async / Await)
```javascript
async function muatDaftarBuku() {
    const tbody = document.querySelector(".table-responsive table tbody");
    const loading = document.getElementById("loading-indicator");

    if (!tbody) return;

    // 1. Munculkan indikator loading
    if (loading) loading.style.display = "block";
    tbody.innerHTML = ""; // Bersihkan tabel terlebih dahulu

    try {
        // 2. Simulasi jeda network 600ms agar efek loading terlihat
        await new Promise(resolve => setTimeout(resolve, 600));

        // 3. Permintaan data via HTTP Fetch
        const response = await fetch("../data/buku.json");
        if (!response.ok) {
            throw new Error("status " + response.status);
        }

        // 4. Parsing response HTTP menjadi Array Objek JavaScript
        const daftarBuku = await response.json();

        // 5. Looping data dan injeksikan ke dalam DOM
        daftarBuku.forEach(function (buku) {
            const tr = document.createElement("tr");
            tr.innerHTML =
                "<td>" + buku.judul + "</td>" +
                "<td>" + buku.pengarang + "</td>" +
                "<td>" + buku.tahun + "</td>" +
                "<td>" + buku.stok + "</td>" +
                "<td>" +
                    "<a href=\"edit.html\" class=\"btn-aksi btn-edit\">Edit</a> " +
                    "<button type=\"button\" class=\"btn-aksi btn-hapus\">Hapus</button>" +
                "</td>";
            tbody.appendChild(tr);
        });
    } catch (error) {
        // 6. Tangani kegagalan koneksi / 404 Not Found
        tbody.innerHTML = "<tr><td colspan=\"5\">Gagal memuat data buku: " + error.message + "</td></tr>";
    } finally {
        // 7. Sembunyikan indikator loading baik saat sukses maupun gagal
        if (loading) loading.style.display = "none";
    }
}
```

### 6.3 Mengapa Menggunakan `async` dan `await`?
* Tanpa `async/await`, pembacaan data network akan memblokir (*freeze*) antarmuka browser.
* `await` memberitahu JavaScript: *"Tunggu data ini selesai diunduh di latar belakang, tanpa membuat browser macet. Setelah datanya tiba, lanjutkan ke baris berikutnya."*
* Blok `try...catch...finally` memastikan jika terjadi error (misalnya file JSON terhapus atau server mati), aplikasi tidak crash dan pengguna melihat pesan peringatan yang ramah.

---

## Bab 7 — Kamus Istilah & Tanya-Jawab Penguji (FAQ Dosen & Aslab)

Hafalkan pertanyaan-pertanyaan ini karena sering ditanyakan saat demonstrasi kode:

### Q1: Apa perbedaan utama antara Jobsheet 1, 3, 5, dan 6?
> **Jawaban:**  
> - **Jobsheet 1** hanya kerangka struktur HTML murni dengan data yang ditulis mati (*hardcoded*).  
> - **Jobsheet 3** membuat tampilan responsif di layar mobile dan tablet menggunakan CSS Grid dan Media Query.  
> - **Jobsheet 5** menambahkan interaktivitas di sisi klien (validasi form tanpa reload, hapus baris dinamis, dan pencarian langsung).  
> - **Jobsheet 6** memisahkan data dari dokumen HTML; data disimpan dalam file JSON dan dimuat secara asinkron menggunakan Fetch API.

### Q2: Mengapa kita memakai `const` dan `let` di JavaScript, bukan `var`?
> **Jawaban:**  
> `const` dan `let` memiliki sifat *block-scoped* (hanya hidup di dalam kurung kurawal `{}` tempat mereka dibuat), sehingga mencegah bentrok variabel (*variable shadowing/hoisting*). Gunakan `const` untuk nilai yang tidak akan diganti, dan `let` untuk variabel yang nilainya akan berubah.

### Q3: Mengapa kamu memakai `event.preventDefault()` pada validasi form?
> **Jawaban:**  
> Secara bawaan, aksi klik tombol submit pada form akan langsung mengirim data dan me-refresh halaman. `event.preventDefault()` digunakan untuk membatalkan perilaku bawaan tersebut jika validasi input gagal, sehingga halaman tidak me-refresh dan inputan pengguna tidak terhapus.

### Q4: Di Jobsheet 3 ada menu hamburger tanpa JS, lalu di Jobsheet 5 ada JS toggle. Apa bedanya?
> **Jawaban:**  
> Di Jobsheet 3 kita menggunakan teknik **CSS Checkbox Hack** (`.nav-toggle:checked ~ nav`) untuk membuktikan bahwa CSS modern mampu menangani interaksi sederhana tanpa skrip. Sedangkan di Jobsheet 5, kita beralih ke manipulasi class JavaScript (`classList.toggle("nav-open")`) yang lebih standar, fleksibel, dan mendukung penambahan atribut aksesibilitas `aria-expanded` untuk pembaca layar (*screen reader*).

### Q5: Mengapa kamu menggunakan `1fr 1fr` pada dashboard 2 kolom di Jobsheet 5 & 6?
> **Jawaban:**  
> Unit `fr` (*fractional unit*) pada CSS Grid membagi sisa ruang secara fleksibel setelah dikurangi ukuran `gap`. Menggunakan `1fr 1fr` menjamin kedua kolom memiliki lebar tepat 50% tanpa resiko meluap (*overflow*) ke samping, dan dipadukan dengan `align-items: stretch` agar tinggi kotak kiri dan kanan selalu sama persis.

### Q6: Bagaimana alur kerja `fetch()` di Jobsheet 6?
> **Jawaban:**  
> Fungsi `fetch("../data/buku.json")` mengirimkan HTTP GET Request asinkron ke server untuk mengambil file JSON. Hasilnya berupa objek `Response`. Kita mengecek `response.ok`, lalu mengubah stream data menjadi array objek menggunakan `await response.json()`, kemudian melakukan perulangan `forEach` untuk membuat elemen `<tr>` dan menempelkannya ke `<tbody>` tabel.

---

### Ringkasan Perintah Lokal untuk Pengujian

Jalankan server lokal dari folder root:
```bash
php -S localhost:8000
```
Buka halaman di browser:
* Jobsheet 1 (HTML polos): `http://localhost:8000/prak1/index.html`
* Jobsheet 2 (CSS styling): `http://localhost:8000/prak2/index.html`
* Jobsheet 3 (CSS responsif): `http://localhost:8000/prak3/index.html`
* Jobsheet 4 (Wireframe dokumen): `http://localhost:8000/prak4/docs/wireframe.md`
* Jobsheet 5 (JS DOM & Dashboard 2 Kolom): `http://localhost:8000/prak5/index.html`
* Jobsheet 6 (Fetch JSON Dinamis): `http://localhost:8000/prak6/index.html`
* Geprek Kita (Full PHP & Session / DB): `http://localhost:8000/prak7/` dan `http://localhost:8000/prak8/`
