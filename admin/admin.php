<?php
require_once('db.php');
$DB = new DB();

$json_data = $DB->get_all_admin();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = $_POST["password"];
    $superAdmin = isset($_POST['superAdmin']) ? 1 : 0; // Checkbox işaretliyse 1 (true), değilse 0 (false)


    if ($username && $password) {
        $result = $DB->insert_admin($username, $password, $superAdmin);

        if ($result['status'] == 'success') {
            header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
            exit;
        } else {
            $message = $result['error'];
        }
    } else {
        $message = "Lütfen kullanıcı adı ve şifre girin.";
    }
}

if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = "Kullanıcı başarıyla oluşturuldu!";
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
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
            <h2 class="text-center my-4">Admin Yönetim Paneli</h2>
            <div class="row">
                <div class="col-lg-10 col-md-11 col-sm-12 mt-4 pt-4 mx-auto">
                    <div class="container-fluid">

                        <div class="card rounded-0 shadow">
                            <div class="card-header">
                                <div class="d-flex justify-content-between">
                                    <div class="card-title col-auto flex-shrink-1 flex-grow-1">Yeni Admin Oluştur</div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="container-fluid">
                                    <?php if (isset($message))
                                        echo "<p class='text-center'>$message</p>"; ?>
                                    <form method="POST">
                                        <div class="mb-3">
                                            <input type="text" name="username" class="form-control"
                                                placeholder="Kullanıcı Adı" required
                                                style="max-width: 400px; margin: 0 auto;">
                                        </div>
                                        <div class="mb-3">
                                            <input type="password" name="password" class="form-control"
                                                placeholder="Şifre" required style="max-width: 400px; margin: 0 auto;">
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check" style="max-width: 400px; margin: 0 auto;">
                                                <input type="checkbox" name="superAdmin" class="form-check-input"
                                                    id="superAdminCheckbox">
                                                <label class="form-check-label" for="superAdminCheckbox">Süper Admin
                                                    Yetkisi Ver</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary form-control"
                                            style="max-width: 400px; margin: 0 auto; display: block;">Oluştur</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                                    <div class="card-title col-auto flex-shrink-1 flex-grow-1">Admin Listesi</div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="container-fluid">
                                    <table class="table table-stripped table-bordered">
                                        <colgroup>
                                            <col width="40%">
                                            <col width="40%">

                                            <col width="20%">
                                        </colgroup>
                                        <thead>
                                            <tr>
                                                <th class="text-center">Kullanıcı Adı</th>
                                                <th class="text-center">Yetkisi</th>
                                                <th class="text-center">İşlemler</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($json_data as $data): ?>
                                                <tr>
                                                    <td><?= $data->username ?></td>
                                                    <td><?php echo $data->superAdmin ? 'Super Admin' : 'Admin'; ?></td>
                                                    <td class="text-center">
                                                        <button class="btn btn-sm btn-outline-danger rounded-0"
                                                            onclick="openDeleteModal(<?= $data->id ?>, '<?= $data->username ?>')">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <div class="modal fade" id="deleteModal" tabindex="-1"
                                        aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel">Kullanıcıyı Sil</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong><span id="deleteUsername"></span></strong> kullanıcısını
                                                        silmek istediğinizden emin misiniz?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">İptal</button>
                                                    <a href="#" id="confirmDelete" class="btn btn-danger">Sil</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal(id, username) {
            document.getElementById('deleteUsername').textContent = username;
            document.getElementById('confirmDelete').href = 'delete_admin.php?id=' + id;
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }
    </script>


</body>

</html>