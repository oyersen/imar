<?php
require_once('db.php');
$DB_class = new DB_class();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = $_POST["password"];
    $superAdmin = isset($_POST['superAdmin']) ? 1 : 0; // Checkbox işaretliyse 1 (true), değilse 0 (false)


    if ($username && $password) {
        $result = $DB_class->insert_admin($username,$password, $superAdmin);

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
    <title>Kullanıcı Oluştur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/layout.css">
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
                                    <?php if (isset($message)) echo "<p class='text-center'>$message</p>"; ?>
                                    <form method="POST">
                                        <div class="mb-3">
                                            <input type="text" name="username" class="form-control" placeholder="Kullanıcı Adı" required style="max-width: 400px; margin: 0 auto;">
                                        </div>
                                        <div class="mb-3">
                                            <input type="password" name="password" class="form-control" placeholder="Şifre" required style="max-width: 400px; margin: 0 auto;">
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check" style="max-width: 400px; margin: 0 auto;">
                                                <input type="checkbox" name="superAdmin" class="form-check-input" id="superAdminCheckbox">
                                                <label class="form-check-label" for="superAdminCheckbox">Süper Admin Yetkisi Ver</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary form-control" style="max-width: 400px; margin: 0 auto; display: block;">Oluştur</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>