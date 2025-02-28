<?php

class DuyuruModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function tumDuyurulariGetir()
    {
        try {
            $stmt = $this->db->query("SELECT * FROM duyurular");
            $duyurular = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Veri doğrulama (örnek)
            if (!is_array($duyurular)) {
                throw new Exception("Duyuru verileri geçersiz.");
            }

            return $duyurular;
        } catch (PDOException $e) {
            error_log("Duyuru verileri alınamadı: " . $e->getMessage(), 0);
            return []; // Hata durumunda boş dizi döndür
        } catch (Exception $e) {
            error_log("Duyuru verileri alınamadı: " . $e->getMessage(), 0);
            return [];
        }
    }

    public function duyuruGetir($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM duyurular WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $duyuru = $stmt->fetch(PDO::FETCH_ASSOC);

            // Veri doğrulama (örnek)
            if (!is_array($duyuru)) {
                throw new Exception("Duyuru bulunamadı.");
            }

            return $duyuru;
        } catch (PDOException $e) {
            error_log("Duyuru alınamadı: " . $e->getMessage(), 0);
            return null; // Hata durumunda null döndür
        } catch (Exception $e) {
            error_log("Duyuru alınamadı: " . $e->getMessage(), 0);
            return null;
        }
    }

    // İhtiyacınıza göre diğer fonksiyonları ekleyebilirsiniz
}

?>