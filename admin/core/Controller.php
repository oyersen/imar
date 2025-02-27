<?php
// Temel Controller sınıfı
class Controller
{
    // View dosyasını yükler ve veri gönderir
    public function view($view, $data = [])
    {
        // View dosyasının yolunu belirle
        $viewPath = 'app/views/' . $view . '.php';

        // View dosyası mevcutsa, verileri extract ederek dahil et
        if (file_exists($viewPath)) {
            extract($data);
            require_once $viewPath;
        } else {
            // View dosyası bulunamazsa hata mesajı
            die("View dosyası bulunamadı: " . $viewPath);
        }
    }

    // Model dosyasını yükler
    public function model($model)
    {
        // Model dosyasının yolunu belirle
        $modelPath = 'app/models/' . $model . '.php';

        // Model dosyası mevcutsa dahil et
        if (file_exists($modelPath)) {
            require_once $modelPath;
            // Model sınıfını instantiate et ve döndür
            return new $model();
        } else {
            // Model dosyası bulunamazsa hata mesajı
            die("Model dosyası bulunamadı: " . $modelPath);
        }
    }

    // Redirect işlemi
    public function redirect($url)
    {
        header('Location: ' . $url);
        exit();
    }
}
