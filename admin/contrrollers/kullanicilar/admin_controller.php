<?php
require_once('../models/db.php');

class AdminController
{
    private $DB_class;

    public function __construct()
    {
        $this->DB_class = DB_class::getInstance();
    }

    public function admin_ekle($username, $password, $superAdmin)
    {
        if (empty($username) || empty($password)) {
            return "<div class='alert alert-warning'>Tüm alanları doldurun.</div>";
        }

        $superAdmin = $superAdmin ? 1 : 0;
        $isAdded = $this->DB_class->admin_ekle($username, $password, $superAdmin);

        if ($isAdded) {
            return "<div class='alert alert-success'>Admin başarıyla eklendi.</div>";
        } else {
            return "<div class='alert alert-danger'>Hata oluştu. Admin eklenemedi.</div>";
        }
    }
}
