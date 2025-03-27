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
    <title>Duyuru Listesi</title>
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
            <h2 class="display-6"></h2>
            <!-- MANŞET -->
            <div class="list-group col-md-8 mx-auto">
                <?php foreach ($duyurular as $data): ?>
                    <?php if ($data['is_active'] == 1 && $data['is_banner'] == 1): ?>
                        <div class="border shadow-sm p-3 mb-2">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                                <div class="flex-grow-1">

                                    <h3 class="text-danger  fw-semibold"><?= htmlspecialchars($data['title']) ?></h3>
                                    <div class="text-danger mb-5">
                                        <?= nl2br(htmlspecialchars($data['content'])) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="bg-white">
        <div class="fluid py-1 mb-5">
            <div class="container">
                <div class="list-group">
                    <?php $aktif_duyuru_var = false; ?>
                    <?php foreach ($duyurular as $data): ?>
                        <?php if ($data['is_active'] == 1 && $data['is_banner'] == 0): ?>
                            <?php $aktif_duyuru_var = true; ?>
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
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <?php if (!$aktif_duyuru_var): ?>
                        <div class="list-group-item border rounded shadow-sm p-3 mb-2"
                            style="background: linear-gradient(to right, #f9f9f9, #ececec);">
                            <div
                                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-0 text-center">
                                        Duyurularımızı Buradan Takip Edebilirsiniz. Henüz Aktif Bir Duyuru Bulunmamaktadır.
                                    </p>
                                    <p class="text-muted mb-0 text-center">

                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
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