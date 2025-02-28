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
            $save = $DB->update_admin($_POST);
            $_SESSION['msg_success'] = 'Bilgileriniz Başarı ile Güncellendi';
        } 

        if (isset($save['status']) && $save['status'] == 'success') {
            header('location: ./profil');
            exit;
        } else {
            $_SESSION['msg_error'] = isset($save['error']) ? $save['error'] : 'Kayıt işleminde bir hata oluştu.';
        }
    } catch (Exception $e) {
        $_SESSION['msg_error'] = 'Kayıt işleminde bir hata oluştu: ' . $e->getMessage();
    }
}

// GET isteği kontrolü ve veri alma
$username = $_SESSION['user'];
$data = $DB->get_admin_by_username($username);
echo $_SESSION["superAdmin"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Güncelleme Formu</title>

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
            <h2 class="text-center my-4">Admin Şifre İşlemleri</h2>
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
                                    <div class="card-title col-auto flex-shrink-1 flex-grow-1">
                                        <?php if (isset($data->id)): ?>
                                            <i>Güncelleme İşlemleri</i>
                                        <?php else: ?>
                                            <i>Yeni Şifre Belirle</i>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="container-fluid">

                                    <form id="admin-form" action="" method="POST">
                                        <input type="hidden" name="id" value="<?= isset($data->id) ? $data->id : '' ?>">

                                        <div class="mb-3">
                                            <label for="username" class="form-label">Kullanıcı Adı </label>
                                            <input type="text" class="form-control rounded-0" id="username" name="username" required="required" placeholder="<?= isset($data->username) ? $data->username : '' ?>" value="<?= isset($data->username) ? $data->username : '' ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="password" class="form-label">Şifre</label>
                                            <input type="password" class="form-control rounded-0" id="password" name="password" required="required">
                                        </div>

                                        <div class="mb-3">
                                            <label for="password_verify" class="form-label">Şifreyi Doğrula</label>
                                            <input type="password" class="form-control rounded-0" id="password_verify" name="password_verify" required="required">
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <button class="btn btn-primary rounded-0" form="admin-form"><i class="fa-solid fa-save"></i> Kaydet</button>
                                <a class="btn btn-light border rounded-0" href="./"><i class="fa-solid fa-times"></i> Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>