<?php
session_start();

// Oturum zaten aktifse, dashboard'a yönlendir
if (isset($_SESSION['user'])) {
    header("Location: views/duyurular.php");
    exit();
}

// Veritabanı bağlantısı
try {
    $db = new PDO('sqlite:../src/db/imarphs.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantısı başarısız: " . $e->getMessage());
}

// Form gönderildiğinde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    try {
        // Kullanıcıyı veritabanından sorgula
        $stmt = $db->prepare("SELECT * FROM admin WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Kullanıcı varsa ve şifre doğruysa
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION["user"] = $username;
            $_SESSION["superAdmin"] = $user['superAdmin'];
            header("Location: views/duyurular.php");
            exit();
        } else {
            $error = "Geçersiz kullanıcı adı veya şifre!";
        }
    } catch (PDOException $e) {
        $error = "Veritabanı hatası: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Sayfası</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body,
        html {
            height: 100%;
            /* Sayfanın tamamını kaplaması için */
            margin: 0;
            /* Varsayılan boşlukları kaldır */
            padding: 0;
        }

        img {
            max-width: 400px;
            height: auto;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container-fluid p-0">

        <div class="row g-0">
            <div class="col-md-6 bg-dark d-none d-md-flex justify-content-center align-items-center vh-100">
                <a href="index.php"><img src="assets/img/imar_logo.png" alt="Firma Logosu"></a>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-center align-items-center vh-100">

                    <div class="card shadow p-4" style="width: 300px;">

                        <?php if (isset($error)) echo "<p class='text-danger text-center'>$error</p>"; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <input type="text" name="username" class="form-control" placeholder="Kullanıcı Adı" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" name="password" class="form-control" placeholder="Şifre" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>