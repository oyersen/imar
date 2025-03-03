<?php

if (file_exists('./src/model/DB.php')) {
    require_once './src/model/DB.php';
} else {
    die('DB.php dosyası bulunamadı!');
}

if (file_exists('./src/model/DuyuruModel.php')) {
    require_once './src/model/DuyuruModel.php';
} else {
    die('DuyuruModel.php dosyası bulunamadı!');
}

try {
    $db = DB::getInstance()->getConnection();
    $duyuruModel = new DuyuruModel($db); // DuyuruModel sınıfını kullan
    $duyurular = $duyuruModel->tumDuyurulariGetir(); // Duyuruları çek

} catch (PDOException $e) { // PDOException yakala
    die("Veritabanı hatası: " . $e->getMessage());
} catch (Exception $e) { // Diğer hataları yakala
    die("Bir hata oluştu: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personel Hizmetleri Sistemi</title>
    <link rel="canonical" href="https://www.imar.istanbul/tr" />
    <link rel="shortcut icon" href="https://www.imar.istanbul/assets/images/favicon.ico" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./src/css/personel.css">
</head>

<body>
    <!-- Header -->
    <header class="bg-white text-white p-3">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="logo" style="display: flex; align-items: center;">
                <!-- Logo olarak img dosyası kullanılıyor -->
                <a href="./" class="text-decoration-none">
                    <img src="./src/img/imar_logo.png" alt="Logo" width="50" style="margin-right: 10px;">
                </a>
                <h3 style="margin: 0;">İMAR İSTANBUL</h3> <!-- margin: 0 ile h3'ün varsayılan margin'ini sıfırladık -->
            </div>
            <div class="social-icons">
                <a href="https://www.facebook.com/istanbulimar1947/" class="me-2"><i class="fab fa-facebook-f"></i></a>
                <a href="https://twitter.com/istanbulimaras" class="me-2"><i class="fab fa-twitter"></i></a>
                <a href="https://www.instagram.com/istanbul_imar" class="me-2"><i class="fab fa-instagram"></i></a>
                <a href="https://www.linkedin.com/company/istanbul-imar-construction-co" class=""><i
                        class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
    </header>

    <!-- Jumbotron -->
    <div class="jumbotron jumbotron-fluid bg-light  text-center py-5">
        <div class="container">
            <h1 class="display-4">Personel Hizmetleri Sistemi</h1>
            <p class="lead">Tüm hizmetlerimiz yakında erişime açılacaktır.</p>
        </div>
    </div>

    <!-- Cards -->
    <div class="container my-5">
        <div class="row">
            <!-- Kart 1 -->
            <div class="col-md-3 mb-4">
                <a href="https://imar.istanbul" class="text-decoration-none">
                    <div class="card h-100 card-hover">
                        <img src="./src/img/imar_logo.png" class="card-img-top  d-flex mx-auto" alt="Hizmet 1">
                        <div class="card-body">
                            <h5 class="card-title">İMAR İSTANBUL</h5>
                            <p class="card-text"></p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Kart 2 -->
            <div class="col-md-3 mb-4">
                <a href="./rehber" class="text-decoration-none">
                    <div class="card h-100 card-hover">
                        <img src="./src/img/telephone-directory.png" class="card-img-top  d-flex mx-auto"
                            alt="Hizmet 2">
                        <div class="card-body">
                            <h5 class="card-title">Rehber</h5>
                            <p class="card-text">Bu hizmetimiz erişime açılmıştır.</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Kart 3 -->
            <div class="col-md-3 mb-4">
                <a href="https://forms.gle/F49LR8me9rz9zvy58" class="text-decoration-none" target="_blank"
                    rel="noopener noreferrer">
                    <div class="card h-100 card-hover">
                        <img src="./src/img/bilgisistemi.png" class="card-img-top  d-flex mx-auto" alt="Hizmet 3">
                        <div class="card-body">
                            <h5 class="card-title">Bilgi İşlem Talep Sistemi</h5>
                            <p class="card-text">Bu hizmetimiz erişime açılmıştır.</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Kart 4 -->
            <div class="col-md-3 mb-4">
                <a href="./dokuman" class="text-decoration-none">
                    <div class="card h-100 card-hover">
                        <img src="./src/img/file.png" class="card-img-top d-flex mx-auto" alt="Hizmet 4">
                        <div class="card-body">
                            <h5 class="card-title">Dökümanlar</h5>
                            <p class="card-text">Bu hizmet yakın zamanda erişime açılacaktır.</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="fluid bg-light text-center py-1 mb-5">
        <div class="container">
            <h5 class="text-danger fw-bold">📢 Duyuru!</h5>
            <h5 class="fw-semibold">Personel Hizmetleri Sistemi</h5>
            <p class="fw-medium text-muted">Tüm hizmetlerimiz yakında erişime açılacaktır.</p>

            <div class="list-group">
                <?php foreach ($duyurular as $data): ?>
                    <div class="list-group-item border rounded shadow-sm p-3 mb-2"
                        style="background: linear-gradient(to right, #f9f9f9, #ececec);">
                        <div
                            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                            <div class="flex-grow-1">
                                <h6 class="text-secondary fw-semibold"><?= htmlspecialchars($data['title']) ?></h6>
                                <p class="text-muted mb-0">
                                    <?= htmlspecialchars($data['content']) ?>
                                </p>
                            </div>
                            <small
                                class="text-muted mt-2 mt-md-0"><?= date('d.m.Y H:i', strtotime($data['updated_at'])) ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-1">

        <div class="container">
            <p>&copy; 2025 İmar A.Ş. Personel Hizmetleri Sistemi. Tüm hakları saklıdır.</p>
        </div>
    </footer>

</body>

</html>