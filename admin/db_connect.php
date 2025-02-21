<?php
try {
    $db = new PDO('sqlite:../src/db/imarphs.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantısı başarısız: " . $e->getMessage());
}
// Veritabanı bağlantısını kapat
$db = null;
