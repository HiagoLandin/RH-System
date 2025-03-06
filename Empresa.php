<?php
require_once 'Database.php';

class Empresa {
    private $conn;
    private $tabela = "empresas";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Método para cadastrar uma empresa
    public function cadastrar($nome, $email, $senha, $telefone, $endereco, $foto_perfil) {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $query = "INSERT INTO $this->tabela (nome, email, senha, telefone, endereco, foto_perfil) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            echo "Erro ao preparar a query: " . $this->conn->error;
            return false;
        }

        $stmt->bind_param("ssssss", $nome, $email, $senhaHash, $telefone, $endereco, $foto_perfil);

        if ($stmt->execute()) {
            return true;
        } else {
            echo "Erro ao executar a query: " . $stmt->error;
            return false;
        }
    }

    // Método para buscar uma empresa por e-mail
    public function buscarPorEmail($email) {
        $query = "SELECT * FROM $this->tabela WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Método para buscar uma empresa por ID
    public function buscarPorId($id) {
        $query = "SELECT * FROM $this->tabela WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Método para listar todas as empresas
    public function listarEmpresas() {
        $query = "SELECT id, nome, foto_perfil, email, telefone FROM empresas";
        $stmt = $this->conn->prepare($query);
    
        if (!$stmt) {
            echo "Erro ao preparar a query: " . $this->conn->error;
            return false;
        }
    
        $stmt->execute();
        $resultado = $stmt->get_result();
    
        // Retorna um array associativo com todas as empresas
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function buscarVagasPorEmpresa($empresa_id) {
        $query = "SELECT id, titulo, descricao, localizacao FROM vagas WHERE empresa_id = ?";
        $stmt = $this->conn->prepare($query);
    
        if (!$stmt) {
            echo "Erro ao preparar a query: " . $this->conn->error;
            return false;
        }
    
        $stmt->bind_param("i", $empresa_id);
        $stmt->execute();
        $resultado = $stmt->get_result();
    
        // Retorna um array associativo com as vagas da empresa
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
}
?>
