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
    $id = isset($_POST['id']) && is_numeric($_POST['id']) && $_POST['id'] > 0 ? (int) $_POST['id'] : 0;
    $password = $_POST['password'];
    $password_verify = $_POST['password_verify'];

    // Şifrelerin eşleşip eşleşmediğini kontrol et
    if ($password !== $password_verify) {
        $_SESSION['msg_error'] = "Şifreler eşleşmiyor.";
    } else {
        // Güncelleme için gerekli verileri hazırla
        $data = [
            'id' => $id,
            'username' => $_POST['username'],
            'password' => $password,
            'superAdmin' => $_SESSION["superAdmin"],
        ];

        // Güncelleme işlemini yap
        $result = $DB->update_admin($data);

        if ($result['status'] == 'success') {
            $_SESSION['msg_success'] = 'Bilgileriniz başarıyla güncellendi.';
        } else {
            $_SESSION['msg_error'] = isset($result['error']) ? $result['error'] : 'Güncelleme sırasında bir hata oluştu.';
        }
    }

    // Yönlendirme
    header('location: ./profil');
    exit;
}

// GET isteği kontrolü ve veri alma
$username = $_SESSION['user'];
$data = $DB->get_admin_by_username($username);
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
                                            <label for="superAdmin" class="form-label">Yetki Durumu</label>
                                            <input type="text" class="form-control rounded-0" id="yetki" name="yetki"
                                                value="<?= isset($data->superAdmin) && $data->superAdmin ? 'Super Admin' : 'Admin' ?>"
                                                disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Kullanıcı Adı </label>
                                            <input type="text" class="form-control rounded-0" id="username"
                                                name="username" required="required"
                                                placeholder="<?= isset($data->username) ? $data->username : '' ?>"
                                                value="<?= isset($data->username) ? $data->username : '' ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="password" class="form-label">Şifre</label>
                                            <input type="password" class="form-control rounded-0" id="password"
                                                name="password" required="required">
                                        </div>

                                        <div class="mb-3">
                                            <label for="password_verify" class="form-label">Şifreyi Doğrula</label>
                                            <input type="password" class="form-control rounded-0" id="password_verify"
                                                name="password_verify" required="required">
                                            <!-- <ul style="list-style-type: disc; margin-left: 20px;">
                                                <li style="color: red; font-size: small;">en az 8 karakter</li>
                                                <li style="color: red; font-size: small;">en az bir büyük harf</li>
                                                <li style="color: red; font-size: small;">en az bir küçük harf</li>
                                                <li style="color: red; font-size: small;">en az bir rakam</li>
                                                <li style="color: red; font-size: small;">en az bir özel karakter</li>
                                            </ul> -->
                                        </div>



                                    </form>
                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <button class="btn btn-primary rounded-0" form="admin-form"><i
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
            const passwordInput = document.getElementById('password');
            const passwordVerifyInput = document.getElementById('password_verify');

            function validatePassword(password) {
                const minLen = 8;
                const hasUpper = /[A-Z]/.test(password);
                const hasLower = /[a-z]/.test(password);
                const hasNumber = /\d/.test(password);
                const hasSpecial = /[^a-zA-Z\d]/.test(password);

                if (password.length < minLen) {
                    return "Şifre en az 8 karakter olmalıdır.";
                } else if (!hasUpper) {
                    return "Şifre en az bir büyük harf içermelidir.";
                } else if (!hasLower) {
                    return "Şifre en az bir küçük harf içermelidir.";
                } else if (!hasNumber) {
                    return "Şifre en az bir rakam içermelidir.";
                } else if (!hasSpecial) {
                    return "Şifre en az bir özel karakter içermelidir.";
                }

                return true; // Şifre geçerli
            }

            passwordInput.addEventListener('input', function () {
                const password = passwordInput.value;
                const passwordValidation = validatePassword(password);

                if (passwordValidation !== true) {
                    passwordInput.setCustomValidity(passwordValidation);
                } else {
                    passwordInput.setCustomValidity('');
                }
            });

            passwordVerifyInput.addEventListener('input', function () {
                if (passwordInput.value !== passwordVerifyInput.value) {
                    passwordVerifyInput.setCustomValidity("Şifreler eşleşmiyor.");
                } else {
                    passwordVerifyInput.setCustomValidity('');
                }
            });
        </script>
</body>

</html>