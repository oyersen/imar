<?php

class DB
{
    private static $instance = null;
    private $db;
    private static $config = [
        'db_path' => __DIR__ . '/../db/imarphs.db', // Tam yol



    ];

    private function __construct()
    {
        try {
            $this->db = new PDO('sqlite:' . self::$config['db_path']);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

            throw new DBException("Veritabanı bağlantısı başarısız: " . $e->getMessage());
        } catch (Exception $e) {

            throw new DBException("Veritabanı hatası: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->db;
    }

}

class DBException extends Exception
{
}

?>