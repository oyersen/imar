<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: admin");
    exit();
}

require_once('db.php');
$DB_class = new DB_class();
$id = isset($_GET['id']) ? $_GET['id'] : '';
if (empty($id)) {
    $_SESSION['msg_error'] = "Deletion Faile! No Member ID Given";
} else {
    $delete = $DB_class->delete_data($id);
    if (isset($delete['status'])) {
        if ($delete['status'] == 'success') {
            $_SESSION['msg_success'] = 'Member Details has been deleted from JSON File Successfully';
        } elseif ($delete['error']) {
            $_SESSION['msg_error'] = 'Deletion Failed due to some Error. Error: ' . $delete['error'];
        }
    } else {
        $_SESSION['msg_error'] = 'Details has failed to save due to some error.';
    }
}
header('location: ./');
