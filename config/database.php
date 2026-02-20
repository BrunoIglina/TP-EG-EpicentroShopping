<?php




class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        $servername = "127.0.0.1";
        $username = "root";
        $password = "";
        $dbname = "shopping_db";
        $port = 3309;

        $this->conn = new mysqli($servername, $username, $password, $dbname, $port);

        if ($this->conn->connect_error) {
            error_log("Error de conexión a BD: " . $this->conn->connect_error);
            die("Error al conectar con la base de datos. Por favor, contacte al administrador.");
        }

        $this->conn->set_charset("utf8mb4");
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }

    private function __clone() {}
    
    public function __wakeup() {
        throw new Exception("No se puede deserializar singleton");
    }
}

function getDB() {
    return Database::getInstance()->getConnection();
}