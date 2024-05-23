<?php 
namespace sistema;

use PDO;

class DataBase {
    private static $instance;
    private PDO $pdo;

    private function __construct() {
        // Conexão com o banco de dados (exemplo usando PDO)
        $dsn = 'mysql:host=localhost;dbname=estudo-2';
        $username = 'root';
        $password = '';
        $this->pdo = new PDO($dsn, $username, $password, array(PDO::ATTR_PERSISTENT => true));
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() : PDO {
        return $this->pdo;
    }
}
?>