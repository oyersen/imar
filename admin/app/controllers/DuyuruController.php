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
            $this->redirect('index');
            return;
        }

        try {
            $duyurular = $this->duyuruModel->tumDuyurulariGetir();
            echo "<pre>"; // Önceden biçimlendirilmiş çıktı
            var_dump($duyurular); // Hata ayıklama
            echo "</pre>";
            $this->view('Duyurular', ['duyurular' => $duyurular]);
        } catch (PDOException $e) {
            error_log("Duyuru listeleme hatası: " . $e->getMessage(), 0);
            echo "<pre>"; // Önceden biçimlendirilmiş çıktı
            var_dump([]); // Hata ayıklama
            echo "</pre>";
            $this->view('Duyurular', ['duyurular' => []]);
        }
    }


    // Diğer fonksiyonlar buraya eklenebilir.
}
