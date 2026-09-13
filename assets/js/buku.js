async function muatDaftarBuku() {
    const tbody = document.querySelector(".table-responsive table tbody");
    const loading = document.getElementById("loading-indicator");

    if (!tbody) return;

    if (loading) {
        loading.style.display = "block";
    }

    tbody.innerHTML = "";

    try {
        await new Promise(function (resolve) {
            setTimeout(resolve, 600);
        });

        const response = await fetch("../data/buku.json");
        if (!response.ok) {
            throw new Error("status " + response.status);
        }

        const daftarBuku = await response.json();
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
        tbody.innerHTML = "<tr><td colspan=\"5\">Gagal memuat data buku: " + error.message + "</td></tr>";
    } finally {
        if (loading) {
            loading.style.display = "none";
        }
    }
}

document.addEventListener("DOMContentLoaded", muatDaftarBuku);
