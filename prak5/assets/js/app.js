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
    const tombolHapus = document.querySelectorAll(".btn-hapus");

    tombolHapus.forEach(function (tombol) {
        tombol.addEventListener("click", function () {
            const baris = tombol.closest("tr");
            const namaData = baris ? baris.querySelector("td")?.textContent.trim() : "data ini";
            const yakin = confirm('Yakin ingin menghapus "' + namaData + '"?');

            if (yakin && baris) {
                baris.remove();
            }
        });
    });
}

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

function hapusError(input) {
    const error = input.parentElement.querySelector(".error");
    if (error) {
        error.remove();
    }
}

function tampilkanError(input, pesan) {
    hapusError(input);

    const error = document.createElement("span");
    error.className = "error";
    error.textContent = pesan;
    input.insertAdjacentElement("afterend", error);
}

function validasiInputWajib(form) {
    let valid = true;
    const inputWajib = form.querySelectorAll("[required]");

    inputWajib.forEach(function (input) {
        if (input.value.trim() === "") {
            tampilkanError(input, "Field ini wajib diisi.");
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
        form.addEventListener("submit", function (event) {
            const wajibValid = validasiInputWajib(form);
            const angkaValid = validasiAngka(form);

            if (!wajibValid || !angkaValid) {
                event.preventDefault();
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
