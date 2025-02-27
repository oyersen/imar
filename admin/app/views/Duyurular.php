<?php
// app/views/Duyurular.php

if (isset($duyurular) && is_array($duyurular)) {
    // foreach döngüsü burada
} else {
    echo 'Duyuru verileri alınamadı.';
    var_dump($duyurular); // Hata ayıklama
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="../../public/assets/css/layout.css">
</head>

<body>

    <?php include 'partials/sidebar.php'; ?>

    <div class="content">
        <div class="container px-5 my-3">
            <h2 class="text-center my-4">Duyuru Yönetim Paneli</h2>
            <div class="row">
                <div class="col-lg-10 col-md-11 col-sm-12 mt-4 pt-4 mx-auto">
                    <div class="container-fluid">
                        <?php if (isset($msg_success)): ?>
                            <div class="alert alert-success rounded-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="col-auto flex-shrink-1 flex-grow-1"><?= htmlspecialchars($msg_success) ?></div>
                                    <div class="col-auto">
                                        <a href="#" onclick="$(this).closest('.alert').remove()" class="text-decoration-none text-reset fw-bolder mx-3">
                                            <i class="fa-solid fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($msg_error)): ?>
                            <div class="alert alert-danger rounded-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="col-auto flex-shrink-1 flex-grow-1"><?= htmlspecialchars($msg_error) ?></div>
                                    <div class="col-auto">
                                        <a href="#" onclick="$(this).closest('.alert').remove()" class="text-decoration-none text-reset fw-bolder mx-3">
                                            <i class="fa-solid fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="card rounded-0 shadow">
                            <div class="card-header">
                                <div class="d-flex justify-content-between">
                                    <div class="card-title col-auto flex-shrink-1 flex-grow-1">Duyuru Listesi</div>
                                    <div class="col-auto">
                                        <a class="btn btn-primary btn-sm btn-flat" href="duyuru/ekle"><i class="fa fa-plus-square"></i> Yeni Kayıt</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="container-fluid">
                                    <table class="table table-stripped table-bordered">
                                        <colgroup>
                                            <col width="20%">
                                            <col width="40%">
                                            <col width="15%">
                                            <col width="20%">
                                        </colgroup>
                                        <thead>
                                            <tr>
                                                <th class="text-center">Başlık</th>
                                                <th class="text-center">İçerik</th>
                                                <th class="text-center">Yazan</th>
                                                <th class="text-center">İşlemler</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($duyurular as $data): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($data['title']) ?></td>
                                                    <td><?= nl2br(htmlspecialchars($data['content'])) ?></td>
                                                    <td><?= htmlspecialchars(strtoupper($data['updated_by'])) ?></td>
                                                    <td class="text-center">
                                                        <a href="duyuru/duzenle?id=<?= htmlspecialchars($data['id']) ?>" class="btn btn-sm btn-outline-info rounded-0">
                                                            <i class="fa-solid fa-edit"></i>
                                                        </a>
                                                        <a href="duyuru/sil?id=<?= htmlspecialchars($data['id']) ?>" class="btn btn-sm btn-outline-danger rounded-0" onclick="if(confirm(`Are you sure to delete <?= htmlspecialchars($data['title']) ?> details?`) === false) event.preventDefault();">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>