<?php
require_once 'Database.php';

class Usuario {
    private $conn;
    private $tabela = "usuarios";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Método para cadastrar um usuário legal
    public function cadastrar($nome, $email, $senha, $telefone, $descricao, $foto_perfil, $curriculo) {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $query = "INSERT INTO $this->tabela (nome, email, senha, telefone, descricao, foto_perfil, curriculo) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            echo "Erro ao preparar a query: " . $this->conn->error;
            return false;
        }

        $stmt->bind_param("sssssss", $nome, $email, $senhaHash, $telefone, $descricao, $foto_perfil, $curriculo);

        if ($stmt->execute()) {
            return true;
        } else {
            echo "Erro ao executar a query: " . $stmt->error;
            return false;
        }
    }

    // Método para buscar um usuário por e-mail
    public function buscarPorEmail($email) {
        $query = "SELECT * FROM $this->tabela WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Método para buscar um usuário por ID
    public function buscarPorId($id) {
        $query = "SELECT * FROM $this->tabela WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id); // "i" indica que o parâmetro é um inteiro
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Método para listar todos os usuários (candidatos)
    public function listarTodos() {
        $query = "SELECT id, nome, email, telefone, foto_perfil FROM $this->tabela";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
