// Tanya dulu sebelum data dihapus.
document.querySelectorAll(".form-hapus").forEach(function (form) {
    form.addEventListener("submit", function (event) {
        if (!confirm('Hapus "' + form.dataset.nama + '"?')) event.preventDefault();
    });
});

// Cek form dan pindahkan kursor ke kolom pertama yang masih salah.
document.querySelectorAll("[data-validate]").forEach(function (form) {
    const inputs = form.querySelectorAll("input:not([type='hidden']), select");
    inputs.forEach(function (input) {
        input.addEventListener("input", function () {
            input.removeAttribute("aria-invalid");
            input.removeAttribute("aria-describedby");
            const pesan = input.parentElement.querySelector(".error");
            if (pesan) pesan.remove();
        });
    });
    form.addEventListener("submit", function (event) {
        let pertama = null;
        form.querySelectorAll(".error").forEach(function (pesan) { pesan.remove(); });
        inputs.forEach(function (input) {
            input.removeAttribute("aria-invalid");
            input.removeAttribute("aria-describedby");
            let pesan = "";
            if (input.required && input.value.trim() === "") pesan = "Form ini wajib diisi.";
            else if (!input.checkValidity()) pesan = "Isian belum sesuai. Periksa batas angka.";
            if (pesan) {
                const teks = document.createElement("span");
                teks.className = "error";
                teks.id = input.id + "-error";
                teks.textContent = pesan;
                input.after(teks);
                input.setAttribute("aria-invalid", "true");
                input.setAttribute("aria-describedby", teks.id);
                if (!pertama) pertama = input;
            }
        });
        if (pertama) {
            event.preventDefault();
            pertama.focus();
        }
    });
});

// Tampilkan perkiraan total saat menu atau jumlah diganti.
const menu = document.getElementById("menu");
const jumlah = document.getElementById("jumlah");
function hitungTotal() {
    const harga = Number(menu.selectedOptions[0].dataset.harga || 0);
    const total = harga * Number(jumlah.value);
    document.getElementById("total").textContent = "Rp " + total.toLocaleString("id-ID");
}
if (menu && jumlah) {
    menu.addEventListener("change", hitungTotal);
    jumlah.addEventListener("input", hitungTotal);
    hitungTotal();
}

// Cari anggota dari isi tabel.
const cari = document.getElementById("search-input");
if (cari) {
    cari.addEventListener("input", function () {
        let hasil = 0;
        document.querySelectorAll("#tabel-data tbody tr").forEach(function (baris) {
            if (baris.querySelector("[colspan]")) return;
            baris.hidden = !baris.textContent.toLowerCase().includes(cari.value.toLowerCase().trim());
            if (!baris.hidden) hasil++;
        });
        const status = document.getElementById("filter-status");
        status.hidden = cari.value === "";
        status.textContent = hasil + " anggota ditemukan.";
    });
}
