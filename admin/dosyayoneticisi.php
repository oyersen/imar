<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}
require_once('db.php');
$DB = new DB();

$currentDir = __DIR__; // Mevcut dizini al
$currentDir = dirname($currentDir);
$currentDir .= '/src/files';

// Dizin ve Dosyaları Listeleme (Yükleme formunda kullanılacak)
$files = scandir($currentDir);
$files = array_diff($files, array('.', '..'));

// Klasör Oluşturma
if (isset($_GET['action']) && $_GET['action'] == 'createFolder' && isset($_POST['folderName'])) {
    $folderName = $_POST['folderName'];
    if (!file_exists($currentDir . '/' . $folderName)) {
        mkdir($currentDir . '/' . $folderName);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Dosya Yükleme
if (isset($_GET['action']) && $_GET['action'] == 'uploadFile' && isset($_FILES['file']) && isset($_POST['targetFolder'])) {
    $file = $_FILES['file'];
    $targetFolder = $_POST['targetFolder'];
    move_uploaded_file($file['tmp_name'], $targetFolder . '/' . $file['name']);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Dosya Silme
if (isset($_GET['delete'])) {
    $fileToDelete = $_GET['delete'];
    unlink($currentDir . '/' . $fileToDelete);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Tüm klasör yapısını al
function getDirectoryStructure($dir)
{
    $files = scandir($dir);
    $result = [];

    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;

        $filePath = $dir . '/' . $file;
        $fileInfo = [
            'name' => $file,
            'path' => str_replace(dirname(__DIR__), '', $filePath)
        ];

        if (is_dir($filePath)) {
            $result[$file] = getDirectoryStructure($filePath);
        } else {
            $result[] = (object) $fileInfo;
        }
    }

    return $result;
}

$directoryStructure = getDirectoryStructure($currentDir); // $currentDir'i kullan
$jsonDirectoryStructure = json_encode($directoryStructure);
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <?php include 'include/sidebar.php'; ?>
    <div class="content">
        <div class="container px-5 my-3">
            <h2 class="text-center my-4">Dosya Yönetim Paneli</h2>
            <div class="row">
                <div class="col-lg-10 col-md-11 col-sm-12 mt-4 pt-4 mx-auto">
                    <div class="container-fluid">
                        <div class="container my-3">
                            <input type="text" id="searchInput" class="form-control mb-3" placeholder="Dosya ara..." onkeyup="filterFiles()">
                        </div>
                        <div class="container">
                            <table class="table table-striped table-bordered mb-5">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Dosya Adı</th>
                                        <th class="text-center">İndir</th>
                                    </tr>
                                </thead>
                                <tbody id="fileTableBody">
                                    <tr>
                                        <td colspan="2" class="text-center">Dosyalar yükleniyor...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <h2>Yeni Klasör Oluştur</h2>
                        <p><?php echo $jsonDirectoryStructure; ?>;</p>
                            <form method="post" action="?action=createFolder">
                                <input type="text" name="folderName" placeholder="Klasör Adı">
                                <button type="submit">Oluştur</button>
                            </form>
                            <h2>Dosya Yükle</h2>
                            <form method="post" action="?action=uploadFile" enctype="multipart/form-data">
                                <select name="targetFolder">
                                    <option value="<?php echo $currentDir; ?>">Ana Dizin</option>
                                    <?php foreach ($files as $file): ?>
                                        <?php if (is_dir($currentDir . '/' . $file)): ?>
                                            <option value="<?php echo $currentDir . '/' . $file; ?>"><?php echo $file; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                                <input type="file" name="file">
                                <button type="submit">Yükle</button>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {


            const directoryStructure = <?php echo $jsonDirectoryStructure; ?>;

            populateFileTable(directoryStructure);

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
        });
    </script>
</body>

</html>