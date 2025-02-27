<?php
require_once('../models/db.php');
require_once('../models/KullaniciModel.php');
class AdminController extends Controller
{
    private $DB;

    public function __construct()
    {
        $this->DB = DB::getInstance();
    }

    public function admin_ekle($username, $password, $superAdmin)
    {
        if (empty($username) || empty($password)) {
            return "<div class='alert alert-warning'>Tüm alanları doldurun.</div>";
        }

        $superAdmin = $superAdmin ? 1 : 0;
        $isAdded = $this->DB->admin_ekle($username, $password, $superAdmin);

        if ($isAdded) {
            return "<div class='alert alert-success'>Admin başarıyla eklendi.</div>";
        } else {
            return "<div class='alert alert-danger'>Hata oluştu. Admin eklenemedi.</div>";
        }
    }
}
