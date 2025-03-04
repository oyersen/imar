<?php
date_default_timezone_set('Europe/Istanbul');
class DB
{
    private $db;

    public function __construct()
    {
       
        try {
            $this->db = new PDO('sqlite:../db/imarphs.db');
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Veritabanı bağlantısı başarısız: " . $e->getMessage());
        }
    }

    public function __destruct()
    {
        $this->db = null; // Bağlantıyı kapat
    }

    public function get_all_data()
    {
        $stmt = $this->db->query("SELECT * FROM duyurular");
        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[$row['id']] = (object) $row;
        }
        return $data;
    }

    public function get_data($id = '')
    {
        if (!empty($id)) {
            $stmt = $this->db->prepare("SELECT * FROM duyurular WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return (object) $row;
            }
        }
        return (object) [];
    }

    public function insert_data($data)
    {
        // Gerekli verilerin varlığını kontrol et
        if (empty($data['title']) || empty($data['content'])) {
            return $this->error_response('Başlık ve içerik alanları zorunludur.');
        }

        $now = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare("INSERT INTO duyurular (id,title, content, created_at, updated_at, is_active, created_by, updated_by) VALUES (:id ,:title, :content, :created_at, :updated_at, :is_active, :created_by, :updated_by)");
        try {
            $result = $stmt->execute([
                ':id' => date("YmdHis"),
                ':title' => htmlspecialchars($data['title']),
                ':content' => htmlspecialchars($data['content']),
                ':created_at' => $now,
                ':updated_at' => $now,
                ':is_active' => 1,
                ':created_by' => $data['created_by'],
                ':updated_by' => $data['created_by']
            ]);
        } catch (PDOException $e) {
            return $this->error_response('Veritabanına ekleme başarısız: ' . $e->getMessage());
        }

        return $this->handle_result($result, 'Veritabanına ekleme başarısız.');
    }

    public function update_data($data)
    {
        $updated_at = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare("UPDATE duyurular SET title = :title, content = :content, updated_at = :updated_at, is_active = :is_active, updated_by = :updated_by WHERE id = :id");
        try {
            $result = $stmt->execute([
                ':title' => htmlspecialchars($data['title']),
                ':content' => htmlspecialchars($data['content']),
                ':updated_at' => $updated_at,
                ':is_active' => 1,
                ':updated_by' => $data['updated_by'],
                ':id' => $data['id']
            ]);
        } catch (PDOException $e) {
            return $this->error_response('Veritabanı güncelleme başarısız: ' . $e->getMessage());
        }
        return $this->handle_result($result, 'Veritabanı güncelleme başarısız.');
    }

    public function delete_data($id = '')
    {
        if (empty($id)) {
            return $this->error_response('Veri ID boş.');
        } else {
            $stmt = $this->db->prepare("DELETE FROM duyurular WHERE id = :id");
            try {
                $result = $stmt->execute([':id' => $id]);
            } catch (PDOException $e) {
                return $this->error_response('Veritabanından silme başarısız: ' . $e->getMessage());
            }
            return $this->handle_result($result, 'Veritabanından silme başarısız.');
        }
    }

  

    //get admin
    public function get_admin($id = '')
    {
        if (!empty($id)) {
            $stmt = $this->db->prepare("SELECT * FROM admin WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return (object) $row;
            }
        }
        return (object) [];
    }

    //get admin by_username
    public function get_admin_by_username($username = '')
    {
        if (!empty($username)) {
            try {
                $stmt = $this->db->prepare("SELECT * FROM admin WHERE username = :username");
                $stmt->execute([':username' => $username]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row) {
                    return (object) $row;
                } else {
                    // Kayıt bulunamadıysa mesaj yazdır
                    echo 'Kullanıcı bulunamadı.';
                }
            } catch (PDOException $e) {
                // Hata mesajını görüntüleyin veya loglayın
                echo 'Veritabanı hatası: ' . $e->getMessage();
            }
        } else {
            // Kullanıcı adı boşsa mesaj yazdır
            echo 'Kullanıcı adı boş.';
        }
        return (object) [];
    }



    //get all admin
    public function get_all_admin()
    {
        $stmt = $this->db->query("SELECT * FROM admin");
        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[$row['id']] = (object) $row;
        }
        return $data;
    }
  //create admin
    public function insert_admin($username, $password, $superAdmin)
    {
        try {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("INSERT INTO admin (username, password, superAdmin) VALUES (?, ?, ?)");

            // Parametreleri doğru sırada ve tiplerde bağlayalım
            $stmt->execute([$username, $hashed_password, $superAdmin]);

            return ['status' => 'success'];
        } catch (PDOException $e) {
            if (
                $e->getCode() == 23000 && strpos($e->getMessage(), 'UNIQUE constraint failed: kullanicilar.username') !== false
            ) {
                return ['status' => 'failed', 'error' => 'Bu kullanıcı adı zaten kullanılıyor.'];
            } else {
                return ['status' => 'failed', 'error' => 'Veritabanı hatası: ' . $e->getMessage()];
            }
        }
    }
    // Admin bilgilerini güncelleyen fonksiyon
    public function update_admin($data)
    {
        // SQL sorgusunu hazırlayın
        $stmt = $this->db->prepare("UPDATE admin SET username = :username, password = :password, superAdmin = :superAdmin WHERE id = :id");

        // Şifreyi hashleyin
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);

        try {
            // Sorguyu çalıştırın
            $result = $stmt->execute([
                ':username' => $data['username'],
                ':password' => $hashed_password,
                ':superAdmin' => isset($data['superAdmin']) ? $data['superAdmin'] : 0, // Varsayılan değer 0
                ':id' => $data['id']
            ]);

            // İşlemin başarılı olup olmadığını kontrol edin
            if ($result) {
                return ['status' => 'success'];
            } else {
                return ['status' => 'error', 'error' => 'Güncelleme başarısız.'];
            }
        } catch (PDOException $e) {
            // Hata durumunda geri bildirim sağlayın
            return ['status' => 'error', 'error' => 'Veritabanı güncelleme hatası: ' . $e->getMessage()];
        }
    }


    //delete admin
    public function delete_admin($id = '')
    {
        if (empty($id)) {
            return $this->error_response('Veri ID boş.');
        } else {
            $stmt = $this->db->prepare("DELETE FROM admin WHERE id = :id");
            try {
                $result = $stmt->execute([':id' => $id]);
            } catch (PDOException $e) {
                return $this->error_response('Veritabanından silme başarısız: ' . $e->getMessage());
            }
            return $this->handle_result($result, 'Veritabanından silme başarısız.');
        }
    }



    public function check_username_exists($username)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM admin WHERE username = :username");
        $stmt->execute([':username' => $username]);
        return $stmt->fetchColumn() > 0;
    }


    private function handle_result($result, $error_message)
    {
        if ($result) {
            return ['status' => 'success'];
        } else {
            return $this->error_response($error_message);
        }
    }

    private function error_response($error_message)
    {
        return ['status' => 'failed', 'error' => $error_message];
    }
}
