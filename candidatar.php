<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['mensagem'] = "Você precisa fazer login para se candidatar.";
    header("Location: login.php");
    exit;
}

require_once 'Database.php'; 
require_once 'Empresa.php';
require_once 'Vaga.php';
require_once 'Usuario.php'; // Conexão MySQLi: $conn

$database = new Database();
$conn = $database->getConnection();

if (isset($_GET['vaga_id'])) {
    $vaga_id = intval($_GET['vaga_id']);
    $usuario_id = intval($_SESSION['usuario_id']);

    // Verificar se já está cadastrado
    $verifica = "SELECT * FROM candidaturas WHERE vaga_id = $vaga_id AND usuario_id = $usuario_id";
    $resultado = mysqli_query($conn, $verifica);

    if (mysqli_num_rows($resultado) > 0) {
        $_SESSION['mensagem'] = "Você já se candidatou a esta vaga.";
    } else {
        $sql = "INSERT INTO candidaturas (vaga_id, usuario_id) VALUES ($vaga_id, $usuario_id)";
        
        if (mysqli_query($conn, $sql)) {
            $_SESSION['mensagem'] = "Candidatura realizada com sucesso!";
        } else {
            $_SESSION['mensagem'] = "Erro ao candidatar: " . mysqli_error($conn);
        }
    }
} else {
    $_SESSION['mensagem'] = "Vaga não especificada.";
}

header("Location: feed.php");
exit;
?>
