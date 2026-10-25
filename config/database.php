<?php
class Database {
    private string $host;
    private string $port;
    private string $db_name;
    private string $username;
    private string $password;
    private ?PDO $conn = null;

    public function __construct() {
        // Lee variables de entorno si existen (en hosting o producción); 
        // de lo contrario, usa tus valores locales por defecto de Docker.
        $this->host     = getenv('DB_HOST')     ?: 'db';
        $this->port     = getenv('DB_PORT')     ?: '3306';
        $this->db_name  = getenv('DB_NAME')     ?: 'vita_sanctorum_db';
        $this->username = getenv('DB_USER')     ?: 'vita_user';
        $this->password = getenv('DB_PASS')     ?: 'vita_local_pass';
    }

    public function getConnection(): ?PDO {
        $this->conn = null;

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        // 1. Intento principal con las credenciales configuradas
        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            return $this->conn;
        } catch (PDOException $e) {
            // 2. Si falló y el host era 'db', hace el fallback a tu máquina local fuera del contenedor
            if ($this->host === 'db') {
                try {
                    $dsnFallback = "mysql:host=127.0.0.1;port=3308;dbname={$this->db_name};charset=utf8mb4";
                    $this->conn = new PDO($dsnFallback, $this->username, $this->password, $options);
                    return $this->conn;
                } catch (PDOException $err) {
                    error_log("Fallo de conexión local fallback: " . $err->getMessage());
                }
            } else {
                error_log("Fallo de conexión a la base de datos: " . $e->getMessage());
            }

            return null;
        }
    }
}