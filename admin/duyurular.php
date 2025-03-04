<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

require_once('db.php');
$DB = new DB();
$json_data = $DB->get_all_data();
// Aktiflik durumuna göre sıralama fonksiyonu
function sortByActive($a, $b)
{
    if ($a->is_active == $b->is_active) {
        return 0;
    }
    return ($a->is_active > $b->is_active) ? -1 : 1; // Aktif olanları üste getir
}

// Diziyi sırala
usort($json_data, 'sortByActive');
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

    <?php
    include 'include/sidebar.php';
    ?>

    <div class="content">
        <div class="container px-5 my-3">
            <h2 class="text-center my-4">Duyuru Yönetim Paneli</h2>
            <div class="row">
                <div class="col-lg-10 col-md-11 col-sm-12 mt-4 pt-4 mx-auto">
                    <div class="container-fluid">
                        <?php if (isset($_SESSION['msg_success']) || isset($_SESSION['msg_error'])): ?>
                            <?php if (isset($_SESSION['msg_success'])): ?>
                                <div class="alert alert-success rounded-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="col-auto flex-shrink-1 flex-grow-1"><?= $_SESSION['msg_success'] ?></div>
                                        <div class="col-auto">
                                            <a href="#" onclick="$(this).closest('.alert').remove()"
                                                class="text-decoration-none text-reset fw-bolder mx-3">
                                                <i class="fa-solid fa-times"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php unset($_SESSION['msg_success']); ?>
                            <?php endif; ?>
                            <?php if (isset($_SESSION['msg_error'])): ?>
                                <div class="alert alert-danger rounded-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="col-auto flex-shrink-1 flex-grow-1"><?= $_SESSION['msg_error'] ?></div>
                                        <div class="col-auto">
                                            <a href="#" onclick="$(this).closest('.alert').remove()"
                                                class="text-decoration-none text-reset fw-bolder mx-3">
                                                <i class="fa-solid fa-times"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php unset($_SESSION['msg_error']); ?>
                            <?php endif; ?>
                        <?php endif; ?>
                        <div class="card rounded-0 shadow">
                            <div class="card-header">
                                <div class="d-flex justify-content-between">
                                    <div class="card-title col-auto flex-shrink-1 flex-grow-1">Duyuru Listesi</div>
                                    <div class="col-auto">
                                        <a class="btn btn-primary btn-sm btn-flat" href="duyuru.php"><i
                                                class="fa fa-plus-square"></i> Yeni Kayıt</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="container-fluid">
                                    <table class="table table-stripped table-bordered">
                                        <colgroup>
                                            <col width="5%">
                                            <col width="25%">
                                            <col width="40%">
                                            <col width="15%">
                                            <col width="15%">
                                        </colgroup>
                                        <thead>
                                            <tr>
                                                <th>Aktif</th>
                                                <th>Başlık</th>
                                                <th>İçerik</th>
                                                <th class="text-center">Güncelleyen</th>
                                                <th class="text-center">İşlemler</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($json_data as $data): ?>
                                                <tr
                                                    style="<?= $data->is_active == 1 ? 'background-color: #e6ffe6;' : 'background-color:rgb(244, 243, 243);' ?>">
                                                    <td>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="isActive<?= $data->id ?>"
                                                                <?= $data->is_active == 1 ? 'checked' : '' ?>
                                                                onchange="toggleActive(<?= $data->id ?>, this.checked ? 1 : 0)">
                                                            <label class="form-check-label"
                                                                for="isActive<?= $data->id ?>"></label>
                                                        </div>
                                                    </td>
                                                    <td><?= $data->title ?></td>
                                                    <td><?= $data->content ?></td>
                                                    <td class="text-center">
                                                        <strong><?= strtoupper($data->updated_by) ?></strong><br><?= $data->updated_at ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="duyuru.php?id=<?= $data->id ?>"
                                                            class="btn btn-sm btn-outline-info rounded-0">
                                                            <i class="fa-solid fa-edit"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-danger rounded-0"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal<?= $data->id ?>">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>

                                                <div class="modal fade" id="deleteModal<?= $data->id ?>" tabindex="-1"
                                                    aria-labelledby="deleteModalLabel<?= $data->id ?>" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="deleteModalLabel<?= $data->id ?>">Silme Onayı</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Silmek istediğinize emin misiniz?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Vazgeç</button>
                                                                <a href="delete_data.php?id=<?= $data->id ?>"
                                                                    class="btn btn-danger">Sil</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </tbody>

                                        <script>
                                            function toggleActive(id, isActive) {
                                                // AJAX isteği ile db.php'yi güncelle
                                                var xhr = new XMLHttpRequest();
                                                xhr.open('POST', 'db.php', true);
                                                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                                xhr.onload = function() {
                                                    if (this.status >= 200 && this.status < 400) {
                                                        // Başarılı güncelleme, sayfayı yenile
                                                        location.reload();
                                                    } else {
                                                        // Hata durumu
                                                        alert('Güncelleme başarısız.');
                                                    }
                                                };
                                                xhr.onerror = function() {
                                                    alert('Güncelleme başarısız.');
                                                };
                                                xhr.send('action=updateActive&id=' + id + '&is_active=' + isActive);
                                            }
                                        </script>
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