<?php
require_once 'Database.php';

class Usuario {
    private $conn;
    private $tabela = "usuarios";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Método para cadastrar um usuário
    public function cadastrar($nome, $email, $senha, $telefone, $descricao, $foto_perfil, $curriculo, $cpf, $data_nascimento) {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $query = "INSERT INTO $this->tabela (nome, email, senha, telefone, descricao, foto_perfil, curriculo, cpf, data_nascimento) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            echo "Erro ao preparar a query: " . $this->conn->error;
            return false;
        }

        $stmt->bind_param("sssssssss", $nome, $email, $senhaHash, $telefone, $descricao, $foto_perfil, $curriculo, $cpf, $data_nascimento);

        if ($stmt->execute()) {
            return true;
        } else {
            echo "Erro ao executar a query: " . $stmt->error;
            return false;
        }
    }

    // Buscar usuário por e-mail
    public function buscarPorEmail($email) {
        $query = "SELECT * FROM $this->tabela WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Buscar usuário por ID
    public function buscarPorId($id) {
        $query = "SELECT * FROM $this->tabela WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Listar todos os usuários
    public function listarTodos() {
        $query = "SELECT id, nome, email, telefone, foto_perfil, data_nascimento, descricao, curriculo, cpf FROM $this->tabela";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
