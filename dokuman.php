<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dosya Listesi</title>
    <link rel="shortcut icon" href="./src/img/imar_logo.png" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./src/css/personel.css">
</head>

<body>
    <?php
    include 'include/header.php';
    ?>
    <!-- Jumbotron -->
    <div class="jumbotron jumbotron-fluid bg-light  text-center py-5">
        <div class="container">
            <h1 class="display-4">Personel Hizmetleri Sistemi</h1>
            <h2 class="display-6">Dosya Listesi</h2>
            <p class="lead">Aşağıdaki dosyaları indirip kullanabilirsiniz.</p>
        </div>
    </div>
    <!-- Search Box -->
    <div class="container my-3">
        <input type="text" id="searchInput" class="form-control mb-3" placeholder="Dosya ara..." onkeyup="filterFiles()">
    </div>

    <!-- File Table -->
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

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-1">
        <div class="container">
            <p>&copy; 2025 İmar A.Ş. Personel Hizmetleri Sistemi. Tüm hakları saklıdır.</p>
        </div>
    </footer>

    <script src="./src/js/dokuman.js"></script>

</body>

</html>