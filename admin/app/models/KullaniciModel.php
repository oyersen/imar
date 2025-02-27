<?php
class KullaniciModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }


    // Tüm kullanıcıları getir
    public function tumKullanicilariGetir()
    {
        try {
            $stmt = $this->db->query("SELECT * FROM admin");
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            return ['status' => 'failed', 'error' => 'Veritabanı hatası: ' . $e->getMessage()];
        }
    }

    // Yeni kullanıcı ekle
    public function kullaniciEkle($username, $password, $superAdmin)
    {
        try {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("INSERT INTO admin (username, password, superAdmin) VALUES (:username, :password, :superAdmin)");
            $stmt->execute([
                ':username' => $username,
                ':password' => $hashed_password,
                ':superAdmin' => $superAdmin
            ]);
            return ['status' => 'success'];
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'failed', 'error' => 'Bu kullanıcı adı zaten kullanılıyor.'];
            } else {
                return ['status' => 'failed', 'error' => 'Veritabanı hatası: ' . $e->getMessage()];
            }
        }
    }

    // Kullanıcı bilgilerini güncelle
    public function kullaniciGuncelle($id, $username, $password = null, $superAdmin)
    {
        try {
            $sql = "UPDATE admin SET username = :username, superAdmin = :superAdmin";
            $params = [
                ':username' => $username,
                ':superAdmin' => $superAdmin,
                ':id' => $id
            ];

            if ($password) {
                $sql .= ", password = :password";
                $params[':password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            $sql .= " WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            return ['status' => 'success'];
        } catch (PDOException $e) {
            return ['status' => 'failed', 'error' => 'Veritabanı hatası: ' . $e->getMessage()];
        }
    }

    // Kullanıcı sil
    public function kullaniciSil($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM admin WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return ['status' => 'success'];
        } catch (PDOException $e) {
            return ['status' => 'failed', 'error' => 'Veritabanı hatası: ' . $e->getMessage()];
        }
    }

    // Belirli bir kullanıcıyı ID ile getir
    public function kullaniciGetir($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM admin WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            return ['status' => 'failed', 'error' => 'Veritabanı hatası: ' . $e->getMessage()];
        }
    }

    // Kullanıcı adını kontrol et
    public function kullaniciGetirByUsername($username)
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM admin WHERE username = :username");
            $stmt->execute([':username' => $username]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Giriş işlemi
    public function girisYap($username, $password)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM admin WHERE username = :username");
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_OBJ);

            if ($user && password_verify($password, $user->password)) {
                return $user; // Kullanıcı bilgilerini döndür
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }
}
