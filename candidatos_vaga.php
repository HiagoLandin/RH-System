<?php
session_start();

if (!isset($_SESSION['empresa_id'])) {
    $_SESSION['mensagem'] = "Você precisa fazer login como empresa.";
    header("Location: login_empresa.php");
    exit;
}

require_once 'Database.php';
require_once 'Usuario.php';

if (!isset($_GET['vaga_id'])) {
    die("Vaga não especificada.");
}

$vaga_id = intval($_GET['vaga_id']); // Segurança: cast para int

// Conexão MySQLi
$database = new Database();
$conn = $database->getConnection();

// Buscar candidatos da vaga
$sql = "
    SELECT u.* FROM candidaturas c
    JOIN usuarios u ON c.usuario_id = u.id
    WHERE c.vaga_id = ?
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $vaga_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$candidatos = [];
while ($row = mysqli_fetch_assoc($result)) {
    $candidatos[] = $row;
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Candidatos à Vaga</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Candidatos à Vaga</h1>

    <?php if (!empty($candidatos)): ?>
        <ul>
            <?php foreach ($candidatos as $candidato): ?>
                <li>
                    <?php echo htmlspecialchars($candidato['nome']); ?>
                    <a href="perfil_candidato.php?id=<?php echo $candidato['id']; ?>">Ver Perfil</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Nenhum candidato para esta vaga ainda.</p>
    <?php endif; ?>
</body>
</html>
