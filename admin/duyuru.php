<?php
session_start();

// Oturum kontrolü
if (!isset($_SESSION["user"])) {
    header("Location: index");
    exit();
}

require_once('db.php');
$DB = new DB();

// POST isteği kontrolü
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) && is_numeric($_POST['id']) && $_POST['id'] > 0 ? (int)$_POST['id'] : 0;

    try {
        if ($id > 0) {
            $save = $DB->update_data($_POST);
            $_SESSION['msg_success'] = 'Duyuru Başarıyla Güncellendi';
        } else {
            $save = $DB->insert_data($_POST);
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
$data = $DB->get_data($id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duyuru Formu</title>

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
            <h2 class="text-center my-4">Duyuru Formu</h2>
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
                                    <div class="card-title col-auto flex-shrink-1 flex-grow-1">
                                        <?php if (isset($data->id)): ?>
                                        <i>Güncelle "<?= isset($data->title) ? $data->title : '' ?>"</i>
                                        <?php else: ?>
                                        <i>Yeni Kayıt Oluştur</i>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (isset($data->updated_at)): ?>
                                    <div class="text-end">
                                        Son Güncellenme Tarihi: <?= $data->updated_at ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="container-fluid">
                                    <form id="member-form" action="" method="POST">
                                        <?php if (isset($data->id)): ?>
                                        <input type="hidden" name="updated_by" value="<?= $_SESSION["user"] ?>">
                                        <?php else: ?>
                                        <input type="hidden" name="created_by"
                                            value="<?= isset($data->created_by) ? $data->created_by : $_SESSION["user"] ?>">
                                        <?php endif; ?>

                                        <input type="hidden" name="id" value="<?= isset($data->id) ? $data->id : '' ?>">

                                        <div class="mb-3">
                                            <label for="title" class="form-label">Başlık</label>
                                            <input type="text" class="form-control rounded-0" id="title" name="title"
                                                required="required"
                                                value="<?= isset($data->title) ? $data->title : '' ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="content" class="form-label">İçerik</label>
                                            <textarea rows="3" class="form-control rounded-0" id="content"
                                                name="content"
                                                required="required"><?= isset($data->content) ? $data->content : '' ?></textarea>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="created_by_display" class="form-label">İlk Oluşturan</label>
                                                <input type="text" class="form-control rounded-0"
                                                    id="created_by_display"
                                                    value="<?= isset($data->created_by) ? $data->created_by : $_SESSION["user"] ?>"
                                                    disabled>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="updated_by_display" class="form-label">Son
                                                    Güncelleyen</label>
                                                <input type="text" class="form-control rounded-0"
                                                    id="updated_by_display"
                                                    value="<?= isset($data->updated_by) ? $data->updated_by : $_SESSION["user"] ?>"
                                                    disabled>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <label for="is_active" class="form-label">Aktif</label>
                                                <div class="rounded p-2 text-center clickable <?= isset($data->is_active) && $data->is_active == 1 ? 'bg-success text-white' : 'bg-secondary text-white' ?>"
                                                    onclick="toggleIsActive()">
                                                    <?= isset($data->is_active) && $data->is_active == 1 ? 'Aktif' : 'Pasif' ?>
                                                </div>
                                                <input type="hidden" name="is_active" id="is_active_hidden"
                                                    value="<?= isset($data->is_active) ? $data->is_active : 0  ?>">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <button class="btn btn-primary rounded-0" form="member-form"><i
                                        class="fa-solid fa-save"></i> Kaydet</button>
                                <a class="btn btn-light border rounded-0" href="./"><i class="fa-solid fa-times"></i>
                                    Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
        function toggleIsActive() {
            var hiddenInput = document.getElementById('is_active_hidden');
            var div = document.querySelector('.clickable');
            var isActive = hiddenInput.value == 1;
            var newIsActive = isActive ? 0 : 1;
            var newBackgroundColor = newIsActive == 1 ? 'bg-success' : 'bg-secondary';
            var newText = newIsActive == 1 ? 'Aktif' : 'Pasif';

            // Div'in stilini ve metnini güncelle
            div.classList.remove('bg-success', 'bg-secondary');
            div.classList.add(newBackgroundColor);
            div.textContent = newText;

            // Gizli input'un değerini güncelle
            hiddenInput.value = newIsActive;
            console.log(hiddenInput.value);
            console.log(newIsActive);
            console.log(document.getElementById('is_active_hidden').value);
        }
        </script>
</body>

</html>