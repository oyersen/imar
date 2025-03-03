<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: admin");
    exit();
}

require_once('db.php');
$DB = new DB();
$id = isset($_GET['id']) ? $_GET['id'] : '';
if (empty($id)) {
    $_SESSION['msg_error'] = "Silme işlemi başarısız! Duyuru ID'si verilmedi";
} else {
    $delete = $DB->delete_data($id);
    if (isset($delete['status'])) {
        if ($delete['status'] == 'success') {
            $_SESSION['msg_success'] = 'Duyuru bilgileri veritabanından başarıyla silindi';
        } elseif ($delete['error']) {
            $_SESSION['msg_error'] = 'Silme işlemi bazı hatalar nedeniyle başarısız oldu. Hata: ' . $delete['error'];
        }
    } else {
        $_SESSION['msg_error'] = 'Bilgiler bazı hatalar nedeniyle kaydedilemedi.';
    }
}
header('location: ./duyurular');
exit();
?>
