function initNavToggle() {
    const toggleBtn = document.getElementById("nav-toggle-btn");
    const nav = document.getElementById("menu-utama");

    if (!toggleBtn || !nav) return;

    toggleBtn.addEventListener("click", function () {
        const terbuka = nav.classList.toggle("nav-open");
        toggleBtn.setAttribute("aria-expanded", terbuka ? "true" : "false");
    });
}

function initHapusConfirm() {
    const daftarFormHapus = document.querySelectorAll(".form-hapus");

    daftarFormHapus.forEach(function (form) {
        form.addEventListener("submit", function (event) {
            const namaData = form.dataset.nama || "data ini";
            const yakin = confirm('Yakin ingin menghapus "' + namaData + '"?');

            if (!yakin) {
                event.preventDefault();
            }
        });
    });
}

function initTableFilter() {
    const inputCari = document.getElementById("search-input");
    const tabel = document.querySelector(".table-responsive table");

    if (!inputCari || !tabel) return;

    inputCari.addEventListener("input", function () {
        const keyword = inputCari.value.trim().toLowerCase();
        const baris = tabel.querySelectorAll("tbody tr");
        const status = document.getElementById("filter-status");
        let ditemukan = 0;

        baris.forEach(function (row) {
            // Baris pesan tabel kosong bukan data yang perlu disaring.
            if (row.querySelector("td[colspan]")) return;
            const isiBaris = row.textContent.toLowerCase();
            const cocok = isiBaris.includes(keyword);
            row.hidden = !cocok;
            if (cocok) ditemukan++;
        });

        if (status) {
            status.hidden = keyword === "";
            status.textContent = ditemukan > 0
                ? ditemukan + " hasil ditemukan."
                : "Tidak ada data yang cocok. Coba kata kunci lain.";
        }
    });
}

function hapusError(input) {
    const error = input.parentElement.querySelector(".error");
    if (error) {
        error.remove();
    }

    input.removeAttribute("aria-invalid");
    input.removeAttribute("aria-describedby");
}

function tampilkanError(input, pesan) {
    hapusError(input);

    const error = document.createElement("span");
    error.className = "error";
    error.id = input.id + "-error";
    error.textContent = pesan;
    input.insertAdjacentElement("afterend", error);

    // Memberi tanda bahwa kolom ini masih salah.
    input.setAttribute("aria-invalid", "true");
    input.setAttribute("aria-describedby", error.id);
}

function validasiInputWajib(form) {
    let valid = true;
    const inputWajib = form.querySelectorAll("[required]");

    inputWajib.forEach(function (input) {
        if (input.value.trim() === "") {
            tampilkanError(input, "Form ini wajib diisi.");
            valid = false;
        } else {
            hapusError(input);
        }
    });

    return valid;
}

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

function initValidasiForm() {
    const daftarForm = document.querySelectorAll("form[data-validate='true']");

    daftarForm.forEach(function (form) {
        // Pesan error hilang saat pengguna mulai memperbaiki isi kolom.
        form.querySelectorAll("input, select").forEach(function (input) {
            input.addEventListener("input", function () {
                hapusError(input);
            });
        });

        form.addEventListener("submit", function (event) {
            const wajibValid = validasiInputWajib(form);
            const angkaValid = validasiAngka(form);

            if (!wajibValid || !angkaValid) {
                event.preventDefault();

                // Memindahkan kursor ke kolom pertama yang harus diperbaiki.
                const inputPertamaSalah = form.querySelector("[aria-invalid='true']");
                if (inputPertamaSalah) {
                    inputPertamaSalah.focus();
                }
            }
        });
    });
}

document.addEventListener("DOMContentLoaded", function () {
    initNavToggle();
    initHapusConfirm();
    initTableFilter();
    initValidasiForm();
});
