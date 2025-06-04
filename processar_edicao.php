<?php
session_start();
require_once 'Usuario.php';
require_once 'Database.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$db = new Database();
$conn = $db->getConnection();

// Recebe dados
$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$data_nascimento = $_POST['data_nascimento'];
$descricao = $_POST['descricao'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];
$senha = $_POST['senha']; // pode ser em branco
$senha_hash = null;
$semestre = $_POST['semestre'];

if (isset($_POST['cursos'])) {
    $cursos = $_POST['cursos']; // Aqui é array!
    $cursosString = implode(',', $cursos); // Agora vira "Administração,Engenharia Civil,Direito"

}
if (!empty($senha)) {
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
}

// Atualização do currículo
$curriculo = null;
if (isset($_FILES['curriculo']) && $_FILES['curriculo']['error'] == UPLOAD_ERR_OK) {
    $curriculo = 'curriculos/' . basename($_FILES['curriculo']['name']);
    move_uploaded_file($_FILES['curriculo']['tmp_name'], $curriculo);
}

// Atualização da foto
$foto_perfil = null;
if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == UPLOAD_ERR_OK) {
    $foto_perfil = 'fotos/' . basename($_FILES['foto_perfil']['name']);
    move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $foto_perfil);
}

// Monta a query
$query = "UPDATE usuarios SET nome=?, cpf=?, data_nascimento=?, descricao=?, telefone=?, email=?, semestre=?, cursos=?";

if ($senha_hash) $query .= ", senha=?";
if ($foto_perfil) $query .= ", foto_perfil=?";
if ($curriculo) $query .= ", curriculo=?";

$query .= " WHERE id=?";

$stmt = $conn->prepare($query);

$types = "ssssssss";
$params = [$nome, $cpf, $data_nascimento, $descricao, $telefone, $email, $semestre, $cursosString];

if ($senha_hash) {
    $types .= "s";
    $params[] = $senha_hash;
}
if ($foto_perfil) {
    $types .= "s";
    $params[] = $foto_perfil;
}
if ($curriculo) {
    $types .= "s";
    $params[] = $curriculo;
}

$types .= "i";
$params[] = $usuario_id;

$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    $_SESSION['mensagem'] = "Perfil atualizado com sucesso!";
} else {
    $_SESSION['mensagem'] = "Erro ao atualizar perfil: " . $stmt->error;
}

header("Location: perfil_usuario.php");
exit;
?>
