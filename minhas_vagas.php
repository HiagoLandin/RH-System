<?php
session_start();
if (!isset($_SESSION['empresa_id'])) {
    $_SESSION['mensagem'] = "Você precisa fazer login como empresa.";
    header("Location: login_empresa.php");
    exit;
}

require_once 'database.php';
require_once 'Vaga.php';
require_once 'Empresa.php';

// Buscar todas as vagas da empresa
$vaga = new Vaga();
$vagas = $vaga->listarPorEmpresa($_SESSION['empresa_id']);

$empresa = new Empresa();
$dadosEmpresa = $empresa->buscarPorId($_SESSION['empresa_id']); // Busca a empresa pelo ID


// Conexão para buscar candidatos
$database = new Database();
$conn = $database->getConnection();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil da Empresa - Carreira IDEAU</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
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
                <a href="perfil_empresa.php" class="btn-sair">Voltar</a>
            </nav>
        </div>
    </header>

    <main class="pagina-vagas">
        <section class="lista-cadastrados">
            <h1 style="font-size: 80px;"><strong>Minhas Vagas e Candidatos</strong></h1>
            <div class="tabela-container">
               <div class="anime-bloco">
                <?php if (!empty($vagas)): ?>
                    <?php foreach ($vagas as $vaga): ?>
                        <div class="vaga-container">
                            <div class="anime-card">
                                <?php
                                $imagemVaga = !empty($vaga['imagem_vaga']) && file_exists($vaga['imagem_vaga'])
                                    ? htmlspecialchars($vaga['imagem_vaga'])
                                    : 'img/imagem_padrao.jpg';
                                ?>
                                <img src="<?php echo $imagemVaga; ?>" alt="Imagem da vaga">

                                <div class="anime-info">
                                    <div class="detalhes">
                                        <p><strong>Descrição:</strong> <?php echo htmlspecialchars($vaga['descricao']); ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="candidatos">
                                <?php
                                // Buscar candidatos dessa vaga
                                $sql = "
                                    SELECT u.* FROM candidaturas c
                                    JOIN usuarios u ON c.usuario_id = u.id
                                    WHERE c.vaga_id = ?
                                ";
                                $stmt = mysqli_prepare($conn, $sql);
                                mysqli_stmt_bind_param($stmt, "i", $vaga['id']);
                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);

                                $candidatos = [];
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $candidatos[] = $row;
                                }

                                mysqli_stmt_close($stmt);
                                ?>

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
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhuma vaga disponível.</p>
                <?php endif; ?>
            </div>

        </section>
    </main>

    
</body>
</html>

<?php
mysqli_close($conn);
?>
