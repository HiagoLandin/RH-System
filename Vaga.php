<?php
require_once 'Database.php';

class Vaga {
    private $conn;
    private $tabela = "vagas";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function cadastrar($empresa_id, $imagem, $requisitos, $descricao, $area, $cursos, $semestre, $tipo_de_vaga, $localizacao) {
        $query = "INSERT INTO $this->tabela 
            (empresa_id, imagem_vaga, requisitos, descricao, area, cursos, semestre, tipo_de_vaga, localizacao) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issssssss", $empresa_id, $imagem, $requisitos, $descricao, $area, $cursos, $semestre, $tipo_de_vaga, $localizacao);
        
        return $stmt->execute();
    }

  public function listar() {
    $query = "SELECT v.*, e.nome AS nome_empresa , v.empresa_id 
              FROM $this->tabela v
              JOIN empresas e ON v.empresa_id = e.id";
              
    $result = $this->conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}
 public function buscarPorId($id) {
    $query = "SELECT v.*, e.nome AS nome_empresa 
              FROM $this->tabela v
              JOIN empresas e ON v.empresa_id = e.id
              WHERE v.id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

public function buscarPorEmpresa($nomeEmpresa) {
    $conn = $this->conn;

    $sql = "SELECT v.*, e.nome AS nome_empresa
            FROM vagas v
            JOIN empresas e ON v.empresa_id = e.id
            WHERE e.nome LIKE ?";

    $stmt = $conn->prepare($sql);
    $param = '%' . $nomeEmpresa . '%';
    $stmt->bind_param("s", $param);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $vagas = [];

    while ($row = $resultado->fetch_assoc()) {
        $vagas[] = $row;
    }

    return $vagas;
}

public function listarPorEmpresa($empresaId) {
    $query = "SELECT v.*, e.nome AS nome_empresa , v.empresa_id 
              FROM $this->tabela v
              JOIN empresas e ON v.empresa_id = e.id
              WHERE v.empresa_id = ?";

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $empresaId);
    $stmt->execute();
    $result = $stmt->get_result();

    $vagas = [];
    while ($row = $result->fetch_assoc()) {
        $vagas[] = $row;
    }

    return $vagas;
}

public function editar($id, $empresa_id, $imagem, $requisitos, $descricao, $area, $cursos, $semestre, $tipo_de_vaga, $localizacao) {
    $query = "UPDATE $this->tabela SET 
        imagem_vaga = ?, requisitos = ?, descricao = ?, area = ?, cursos = ?, semestre = ?, tipo_de_vaga = ?, localizacao = ?
        WHERE id = ? AND empresa_id = ?";

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ssssssssii", $imagem, $requisitos, $descricao, $area, $cursos, $semestre, $tipo_de_vaga, $localizacao, $id, $empresa_id);
    
    return $stmt->execute();
}

public function filtrarVagas($filtros) {
    $sql = "SELECT * FROM vagas WHERE 1=1";
    $params = [];
    $types = ""; // Tipos de dados para bind_param (ex: "sssi")

    if (!empty($filtros['tipo_de_vaga'])) {
        $sql .= " AND tipo_de_vaga = ?";
        $params[] = $filtros['tipo_de_vaga'];
        $types .= "s";
    }

    if (!empty($filtros['semestre'])) {
        $sql .= " AND semestre = ?";
        $params[] = $filtros['semestre'];
        $types .= "s";
    }

    if (!empty($filtros['area'])) {
        $sql .= " AND area = ?";
        $params[] = $filtros['area'];
        $types .= "s";
    }

    if (!empty($filtros['curso'])) {
        $sql .= " AND cursos LIKE ?";
        $params[] = '%' . $filtros['curso'] . '%';
        $types .= "s";
    }

    $stmt = $this->conn->prepare($sql);

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $vagas = [];
    while ($row = $result->fetch_assoc()) {
        $vagas[] = $row;
    }

    return $vagas;
}

public function excluir($id, $empresa_id) {
    $query = "DELETE FROM $this->tabela WHERE id = ? AND empresa_id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ii", $id, $empresa_id);
    return $stmt->execute();
}



}


?>
