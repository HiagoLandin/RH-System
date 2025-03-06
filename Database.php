<?php
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

class Database {
    private $host = "localhost";
    private $db_name = "recrutamento_rh";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
        } catch (Exception $e) {
            die("Erro de conexão: " . $e->getMessage());
        }
        return $this->conn;
    }
}
?>
