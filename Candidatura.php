<?php
require_once 'Database.php';

//tudo isso é pro sistema de candidatura tipo o do site do filme, vai listar tudo
class Candidatura {
    private $conn;
    private $tabela = "candidaturas";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function candidatar($usuario_id, $vaga_id) {
        $query = "INSERT INTO $this->tabela (usuario_id, vaga_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $usuario_id, $vaga_id);
        return $stmt->execute();
    }

    public function listarCandidatos($vaga_id) {
        $query = "SELECT u.nome, u.email FROM usuarios u INNER JOIN candidaturas c ON u.id = c.usuario_id WHERE c.vaga_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $vaga_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
