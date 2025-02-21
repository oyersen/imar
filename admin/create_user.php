<?php
require_once('master.php');
$master = new Master();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = $_POST["password"];

    if ($username && $password) {
        $result = $master->insert_admin($username, $password);

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
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow p-4" style="max-width: 400px; margin: auto;">
            <h2 class="text-center mb-4">Yeni Kullanıcı Oluştur</h2>
            <?php if (isset($message)) echo "<p class='text-center'>$message</p>"; ?>
            <form method="POST">
                <div class="mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Kullanıcı Adı" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Şifre" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Oluştur</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>