<?php
require_once 'Database.php';

class Vaga {
    private $conn;
    private $tabela = "vagas";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function cadastrar($empresa_id, $titulo, $descricao, $localizacao) {
        $query = "INSERT INTO $this->tabela (empresa_id, titulo, descricao, localizacao) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isss", $empresa_id, $titulo, $descricao, $localizacao);
        return $stmt->execute();
    }

    public function listar() {
        $query = "SELECT * FROM $this->tabela";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>



