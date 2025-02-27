<?php

require_once '../models/DB.php';
require_once '../models/DuyuruModel.php';

class DuyuruController extends Controller
{
    private $duyuruModel;

    public function __construct()
    {
        $db = DB::getInstance()->getConnection();
        $this->duyuruModel = new DuyuruModel($db);
    }

    public function index()
    {
        session_start();

        if (!isset($_SESSION['user'])) {
            $this->redirect('index'); // Redirect fonksiyonunu kullan
            return; // Redirect'ten sonra kodun devam etmesini engelle
        }

        try {
            $duyurular = $this->duyuruModel->tumDuyurulariGetir();
            $this->view('duyurular', ['duyurular' => $duyurular]); // Verileri view'e gönder
        } catch (PDOException $e) {
            // Hata yönetimi
            error_log("Duyuru listeleme hatası: " . $e->getMessage(), 0); // Hata mesajını log dosyasına kaydet
            $this->view('duyurular', ['duyurular' => []]); // Boş dizi gönder veya hata mesajı göster
        }
    }

    // Diğer fonksiyonlar buraya eklenebilir.
}
