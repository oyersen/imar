<?php
session_start();

// Oturum kontrolü
if (!isset($_SESSION["user"])) {
    header("Location: index");
    exit();
}

require_once('master.php');
$master = new Master();

// POST isteği kontrolü
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) && is_numeric($_POST['id']) && $_POST['id'] > 0 ? (int)$_POST['id'] : 0;

    try {
        if ($id > 0) {
            $save = $master->update_data($_POST);
            $_SESSION['msg_success'] = 'Duyuru Başarıyla Güncellendi';
        } else {
            $save = $master->insert_data($_POST);
            $_SESSION['msg_success'] = 'Yeni Duyuru Başarıyla Eklendi';
        }

        if (isset($save['status']) && $save['status'] == 'success') {
            header('location: ./');
            exit;
        } else {
            $_SESSION['msg_error'] = isset($save['error']) ? $save['error'] : 'Kayıt işleminde bir hata oluştu.';
        }
    } catch (Exception $e) {
        $_SESSION['msg_error'] = 'Kayıt işleminde bir hata oluştu: ' . $e->getMessage();
    }
}

// GET isteği kontrolü ve veri alma
$id = isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0 ? (int)$_GET['id'] : '';
$data = $master->get_data($id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duyuru Formu</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">

    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        html,
        body {
            min-height: 100%;
            width: 100%;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-gradient">
        <div class="container">
            <a class="navbar-brand" href="./"></a>
            <div>
                <a href="logout.php" class="text-light fw-bolder h6 text-decoration-none" target="_blank">Çıkış</a>
            </div>
        </div>
    </nav>
    <div class="container px-5 my-3">
        <h2 class="text-center">Duyuru Formu</h2>
        <div class="row">
            <div class="col-lg-10 col-md-11 col-sm-12 mt-4 pt-4 mx-auto">
                <div class="container-fluid">
                    <?php if (isset($_SESSION['msg_success']) || isset($_SESSION['msg_error'])): ?>
                        <?php if (isset($_SESSION['msg_success'])): ?>
                            <div class="alert alert-success rounded-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="col-auto flex-shrink-1 flex-grow-1"><?= $_SESSION['msg_success'] ?></div>
                                    <div class="col-auto">
                                        <a href="#" onclick="$(this).closest('.alert').remove()" class="text-decoration-none text-reset fw-bolder mx-3">
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
                                        <a href="#" onclick="$(this).closest('.alert').remove()" class="text-decoration-none text-reset fw-bolder mx-3">
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
                                <div class="card-title col-auto flex-shrink-1 flex-grow-1">Duyuru</div>
                                <div class="col-atuo">
                                    <button class="btn btn-primary btn-sm btn-flat" id="add"><i class="fa fa-plus-square"></i> Yeni Kayıt</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="container-fluid">
                                <?php if (isset($data->id)): ?>
                                    <p class="text-muted"><i>Güncelle <b><?= isset($data->title) ? $data->title : '' ?></b></i></p>
                                <?php else: ?>
                                    <p class="text-muted"><i>Yeni Kayıt Oluştur</b></i></p>
                                <?php endif; ?>
                                <form id="member-form" action="" method="POST">
                                    <?php if (isset($data->id)): ?>
                                        <input type="hidden" name="updated_by" value="<?= $_SESSION["user"] ?>">
                                    <?php else: ?>
                                        <input type="hidden" name="created_by" value="<?= isset($data->created_by) ? $data->created_by : $_SESSION["user"] ?>">
                                    <?php endif; ?>

                                    <input type="hidden" name="id" value="<?= isset($data->id) ? $data->id : '' ?>">

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Başlık</label>
                                        <input type="text" class="form-control rounded-0" id="title" name="title" required="required" value="<?= isset($data->title) ? $data->title : '' ?>">

                                    </div>

                                    <div class="mb-3">
                                        <label for="content" class="form-label">İçerik</label>
                                        <textarea rows="3" class="form-control rounded-0" id="content" name="content" required="required"><?= isset($data->content) ? $data->content : '' ?></textarea>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <button class="btn btn-primary rounded-0" form="member-form"><i class="fa-solid fa-save"></i> Save Member</button>
                            <a class="btn btn-light border rounded-0" href="./"><i class="fa-solid fa-times"></i> Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>