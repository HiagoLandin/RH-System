<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['mensagem'] = "Você precisa fazer login para acessar esta página.";
    header("Location: login.php");
    exit;
}

require_once 'Empresa.php';

if (!isset($_GET['empresa_id'])) {
    die("ID da empresa não fornecido.");
}

$empresa_id = $_GET['empresa_id'];
$empresa = new Empresa();

// Busca as vagas da empresa
$vagas = $empresa->buscarVagasPorEmpresa($empresa_id);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vagas da Empresa - LINKIDEAU</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo">
            <span>LINKIDEAU</span>
            <a href="https://www.passofundo.ideau.com.br/" target="_blank">
                <img src="https://www.getulio.ideau.com.br/wp-content/uploads/2019/05/logo_ideau.png" alt="Logo LINKIDEAU">
            </a>
        </div>
        <nav>
            <a href="login_empresa.php" class="btn">Empresa</a>
            <a href="login.php" class="btn">Candidato</a> <!-- Botão de login -->
            <a href="sobre.php" class="btn">Sobre Nós</a>
        </nav>
    </header>

    <main>
        <section class="vagas-empresa">
            <h1>Vagas da Empresa</h1>

            <div class="lista-vagas">
                <?php if (!empty($vagas)): ?>
                    <?php foreach ($vagas as $vaga): ?>
                        <div class="vaga">
                            <h3><?php echo htmlspecialchars($vaga['titulo']); ?></h3>
                            <p><strong>Descrição:</strong> <?php echo htmlspecialchars($vaga['descricao']); ?></p>
                            <p><strong>Localização:</strong> <?php echo htmlspecialchars($vaga['localizacao']); ?></p>
                            <a href="candidatar.php?vaga_id=<?php echo $vaga['id']; ?>" class="btn">Candidatar-se</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhuma vaga disponível.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 LINKIDEAU. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
