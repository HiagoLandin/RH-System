<?php
session_start();

if (!isset($_SESSION['empresa_id'])) {
    $_SESSION['mensagem'] = "Você precisa fazer login como empresa.";
    header("Location: login_empresa.php");
    exit;
}

require_once 'Database.php';
require_once 'Usuario.php';
require_once 'Empresa.php';

if (!isset($_GET['vaga_id'])) {
    die("Vaga não especificada.");
}
$empresa = new Empresa();
$dadosEmpresa = $empresa->buscarPorId($_SESSION['empresa_id']); // Busca a empresa pelo ID


$vaga_id = intval($_GET['vaga_id']); // Segurança: cast para int

// Conexão MySQLi
$database = new Database();
$conn = $database->getConnection();

// Buscar dados da vaga para mostrar imagem e descrição
$sqlVaga = "SELECT * FROM vagas WHERE id = ?";
$stmtVaga = mysqli_prepare($conn, $sqlVaga);
mysqli_stmt_bind_param($stmtVaga, "i", $vaga_id);
mysqli_stmt_execute($stmtVaga);
$resultVaga = mysqli_stmt_get_result($stmtVaga);
$vaga = mysqli_fetch_assoc($resultVaga);
mysqli_stmt_close($stmtVaga);

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
        <link rel="stylesheet" href="perfil.css">

   
</head>
<body>
     <header>
        <div class="foto-perfil">
            <a href="perfil_empresa.php">
                    <?php
                    $caminhoImagem = !empty($dadosEmpresa['foto_perfil']) && file_exists($dadosEmpresa['foto_perfil'])
                        ? htmlspecialchars($dadosEmpresa['foto_perfil'])
                        : 'img/foto_padrao.jpg';
                    ?>
                    <img src="<?php echo $caminhoImagem; ?>" alt="Logo da Empresa" class="foto-perfil-img">
                </div>
            </a>
        <div class="logo">
            <nav>
                <a href="perfil_empresa.php" class="btn">Voltar</a>
            </nav>
        </div>
    </header>

   <main>
    <section class="perfil">
        <div class="dados-perfil">
            <div class="informacoes-perfil">
                <h1>Candidatos à Vaga</h1>

                <?php if (!empty($vaga['imagem_vaga']) && file_exists($vaga['imagem_vaga'])): ?>
                    <div style="text-align: center; margin-bottom: 1rem;">
                        <img src="<?php echo htmlspecialchars($vaga['imagem_vaga']); ?>" alt="Imagem da Vaga" class="imagem-vaga">
                    </div>
                <?php endif; ?>

                <p><strong>Descrição:</strong> <?php echo nl2br(htmlspecialchars($vaga['descricao'])); ?></p>

                <h2 style="margin-top: 2rem;">Lista de Candidatos</h2>

                <?php if (!empty($candidatos)): ?>
                    <ul class="lista-candidatos">
                        <?php foreach ($candidatos as $candidato): ?>
                         <li class="card-candidato" style="list-style:none">
                            <div class="nome-candidato">
                                <?php echo htmlspecialchars($candidato['nome']); ?>
                            </div>
                            <a href="perfil_candidato.php?id=<?php echo $candidato['id']; ?>" class="btn-candidatar">Ver Perfil</a>
                        </li>

                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Nenhum candidato para esta vaga ainda.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

</body>
</html>
