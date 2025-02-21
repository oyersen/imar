<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

require_once('master.php');
$master = new Master();
$json_data = $master->get_all_data();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js" integrity="sha512-naukR7I+Nk6gp7p5TMA4ycgfxaZBJ7MO5iC3Fp6ySQyKFHOGfpkSZkYVWV5R7u7cfAicxanwYQ5D1e17EfJcMA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

    <style>
        html,
        body {
            min-height: 100%;
            width: 100%;
            background-color: #f8f9fa;
            font-family: 'Roboto', sans-serif;
        }

        .sidebar {
            background-color: #343a40;
            color: white;
            height: 100vh;
            padding: 20px;
            position: fixed;
        }

        .sidebar-title {
            text-align: center;
            margin-bottom: 30px;
            color: #fff;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
        }

        .nav-link {
            display: block;
            padding: 10px 15px;
            color: #ccc;
            text-decoration: none;
            transition: background-color 0.3s ease;
            border-radius: 5px;
        }

        .nav-link i {
            margin-right: 10px;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: #495057;
            color: white;
        }

        .nav-link.active {
            font-weight: 500;
        }

        .logout-link {
            background-color: #dc3545;
            color: white;
            margin-top: 20px;
        }

        .logout-link:hover {
            background-color: #c82333;
        }

        .content {
            margin-left: 220px;
            padding: 20px;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .btn-outline-info,
        .btn-outline-danger {
            border-radius: 50px;
        }
    </style>
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
                                    <div class="card-title col-auto flex-shrink-1 flex-grow-1">Liste</div>
                                    <div class="col-auto">
                                        <a class="btn btn-primary btn-sm btn-flat" href="member_form.php"><i class="fa fa-plus-square"></i> Yeni Kayıt</a>
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
                                            <?php foreach ($json_data as $data): ?>
                                                <tr>
                                                    <td><?= $data->title ?></td>
                                                    <td><?= $data->content ?></td>
                                                    <td><?= $data->updated_by ?></td>
                                                    <td class="text-center">
                                                        <a href="member_form.php?id=<?= $data->id ?>" class="btn btn-sm btn-outline-info rounded-0">
                                                            <i class="fa-solid fa-edit"></i>
                                                        </a>
                                                        <a href="delete_data.php?id=<?= $data->id ?>" class="btn btn-sm btn-outline-danger rounded-0" onclick="if(confirm(`Are you sure to delete <?= $data->name ?> details?`) === false) event.preventDefault();">
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