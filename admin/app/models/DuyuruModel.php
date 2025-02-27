<?php
class DuyuruModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Tüm duyuruları getir
    public function tumDuyurulariGetir()
    {
        try {
            $stmt = $this->db->query("SELECT * FROM duyurular");
            $duyurular = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $duyurular;
        } catch (PDOException $e) {
            error_log("Duyuru model hatası: " . $e->getMessage(), 0);
            return []; // Hata durumunda boş dizi döndür
        }
    }

    // Belirli bir duyuruyu ID ile getir
    public function duyuruGetir($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM duyurular WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Yeni duyuru ekle
    public function duyuruEkle($data)
    {
        if (empty($data['title']) || empty($data['content'])) {
            return ['status' => 'failed', 'error' => 'Başlık ve içerik alanları zorunludur.'];
        }

        $now = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare("INSERT INTO duyurular (id, title, content, created_at, updated_at, is_active, created_by, updated_by) VALUES (:id, :title, :content, :created_at, :updated_at, :is_active, :created_by, :updated_by)");

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
            return ['status' => 'success'];
        } catch (PDOException $e) {
            return ['status' => 'failed', 'error' => 'Veritabanına ekleme başarısız: ' . $e->getMessage()];
        }
    }

    // Duyuruyu güncelle
    public function duyuruGuncelle($data)
    {
        if (empty($data['id']) || empty($data['title']) || empty($data['content'])) {
            return ['status' => 'failed', 'error' => 'ID, başlık ve içerik alanları zorunludur.'];
        }

        $updated_at = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare("UPDATE duyurular SET title = :title, content = :content, updated_at = :updated_at, is_active = :is_active, updated_by = :updated_by WHERE id = :id");

        try {
            $result = $stmt->execute([
                ':title' => htmlspecialchars($data['title']),
                ':content' => htmlspecialchars($data['content']),
                ':updated_at' => $updated_at,
                ':is_active' => isset($data['is_active']) ? $data['is_active'] : 1,
                ':updated_by' => $data['updated_by'],
                ':id' => $data['id']
            ]);
            return ['status' => 'success'];
        } catch (PDOException $e) {
            return ['status' => 'failed', 'error' => 'Veritabanı güncelleme başarısız: ' . $e->getMessage()];
        }
    }

    // Duyuruyu sil
    public function duyuruSil($id)
    {
        if (empty($id)) {
            return ['status' => 'failed', 'error' => 'Veri ID boş.'];
        }

        $stmt = $this->db->prepare("DELETE FROM duyurular WHERE id = :id");

        try {
            $result = $stmt->execute([':id' => $id]);
            return ['status' => 'success'];
        } catch (PDOException $e) {
            return ['status' => 'failed', 'error' => 'Veritabanından silme başarısız: ' . $e->getMessage()];
        }
    }
}
