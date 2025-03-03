document.addEventListener("DOMContentLoaded", function () { 
    fetch("files.php")
        .then(response => response.json())
        .then(data => populateFileTable(data))
        .catch(error => {
            console.error("Hata oluştu:", error);
            document.getElementById("fileTableBody").innerHTML = `<tr>
                <td colspan="2" class="text-center text-danger">Dosyalar yüklenirken hata oluştu.</td>
            </tr>`;
        });

    function populateFileTable(categories) {
        const tableBody = document.getElementById("fileTableBody");
        tableBody.innerHTML = "";

        if (Object.keys(categories).length === 0) {
            tableBody.innerHTML = `<tr>
                <td colspan="2" class="text-center text-warning">Henüz dosya eklenmedi.</td>
            </tr>`;
            return;
        }

        Object.keys(categories).forEach(category => {
            // Kategori başlığı için yeni bir satır ekle
            let categoryRow = document.createElement("tr");
            categoryRow.classList.add("table-secondary", "category-row");
            categoryRow.innerHTML = `<td colspan="2" class="fw-bold text-primary">${category}</td>`;
            tableBody.appendChild(categoryRow);

            // Kategorinin altındaki dosyaları listele
            categories[category].forEach(file => {
                let fileRow = document.createElement("tr");
                fileRow.classList.add("file-row");

                let fileNameCell = document.createElement("td");
                fileNameCell.textContent = file.name;
                fileNameCell.classList.add("file-name");

                let fileDownloadCell = document.createElement("td");
                fileDownloadCell.classList.add("text-center");

                let downloadButton = document.createElement("a");
                downloadButton.href = file.path;
                downloadButton.classList.add("btn", "btn-sm", "btn-success");
                downloadButton.innerHTML = `<i class="fa fa-download"></i> İndir`;
                downloadButton.setAttribute("download", file.name);

                fileDownloadCell.appendChild(downloadButton);
                fileRow.appendChild(fileNameCell);
                fileRow.appendChild(fileDownloadCell);
                tableBody.appendChild(fileRow);
            });
        });
    }
});

// 🔍 Dosya Arama Fonksiyonu
function filterFiles() {
    let searchInput = document.getElementById("searchInput").value.toLowerCase();
    let fileRows = document.querySelectorAll(".file-row");

    fileRows.forEach(row => {
        let fileName = row.querySelector(".file-name").textContent.toLowerCase();
        if (fileName.includes(searchInput)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}
