<?php

class Database
{
    private static $instance = null;
    private $conn;

    private function __construct()
    {
        $env_path = __DIR__ . '/../../.env';
        if (!file_exists($env_path)) {
            die("Error crítico: No se encontró el archivo de configuración.");
        }
        $env = parse_ini_file($env_path);

        $is_local = ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1');

        if ($is_local) {
            $servername = $env['LOCAL_DB_HOST'];
            $username   = $env['LOCAL_DB_USER'];
            $password   = $env['LOCAL_DB_PASS'];
            $dbname     = $env['LOCAL_DB_NAME'];
        } else {
            $servername = $env['PROD_DB_HOST'];
            $username   = $env['PROD_DB_USER'];
            $password   = $env['PROD_DB_PASS'];
            $dbname     = $env['PROD_DB_NAME'];
        }

        $port = 3306;

        // Intentamos la conexión
        try {
            $this->conn = new mysqli($servername, $username, $password, $dbname, $port);

            if ($this->conn->connect_error) {
                throw new Exception("Error de conexión: " . $this->conn->connect_error);
            }

            $this->conn->set_charset("utf8mb4");
        } catch (Exception $e) {
            error_log($e->getMessage());
            die("Lo sentimos, hay un problema técnico con la base de datos. Por favor, intente más tarde.");
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }

    private function __clone() {}

    public function __wakeup()
    {
        throw new Exception("No se puede deserializar singleton");
    }
}

function getDB()
{
    return Database::getInstance()->getConnection();
}
