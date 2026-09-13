async function muatDaftarAnggota() {
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

        const response = await fetch("../data/anggota.json");
        if (!response.ok) {
            throw new Error("status " + response.status);
        }

        const daftarAnggota = await response.json();
        daftarAnggota.forEach(function (anggota) {
            const tr = document.createElement("tr");
            tr.innerHTML =
                "<td>" + anggota.no_anggota + "</td>" +
                "<td>" + anggota.nama + "</td>" +
                "<td>" + anggota.alamat + "</td>" +
                "<td>" + anggota.no_hp + "</td>" +
                "<td>" +
                    "<a href=\"edit.html\" class=\"btn-aksi btn-edit\">Edit</a> " +
                    "<button type=\"button\" class=\"btn-aksi btn-hapus\">Hapus</button>" +
                "</td>";
            tbody.appendChild(tr);
        });
    } catch (error) {
        tbody.innerHTML = "<tr><td colspan=\"5\">Gagal memuat data anggota: " + error.message + "</td></tr>";
    } finally {
        if (loading) {
            loading.style.display = "none";
        }
    }
}

document.addEventListener("DOMContentLoaded", muatDaftarAnggota);
