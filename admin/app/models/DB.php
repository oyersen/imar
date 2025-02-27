<?php
date_default_timezone_set('Europe/Istanbul');
class DB
{
    private $connection;
    private static $instance = null;

    private function __construct()
    {
        try {
            $dbPath = 'E:/xampp/htdocs/imarPHS/src/db/imarphs.db'; // Mutlak yolu kullanın
            $this->connection = new PDO('sqlite:' . $dbPath);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Veritabanı bağlantısı başarısız: " . $e->getMessage());
        }
    }
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
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
