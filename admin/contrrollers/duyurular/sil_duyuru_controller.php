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
    $_SESSION['msg_error'] = "Silme İşlemi Başarısız! ID Bulunamadı.";
} else {
    $delete = $DB_class->delete_data($id);
    if (isset($delete['status'])) {
        if ($delete['status'] == 'success') {
            $_SESSION['msg_success'] = 'İlgili DUyuru Başarıyla Kaldırıldı';
        } elseif ($delete['error']) {
            $_SESSION['msg_error'] = 'Silme İşlemi Bazı Hatalar Nedeniyle Başarısız Oldu. Hata:' . $delete['error'];
        }
    } else {
        $_SESSION['msg_error'] = 'Detaylar bir hata nedeniyle kaydedilemedi.';
    }
}
header('location: ./');
