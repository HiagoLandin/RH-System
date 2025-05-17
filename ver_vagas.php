<?php
session_start();
require_once 'Vaga.php';

if (!isset($_GET['id'])) {
    die("ID da vaga não foi especificado.");
}

$vaga_id = $_GET['id'];
$vagaObj = new Vaga();
$vaga = $vagaObj->buscarPorId($vaga_id);

if (!$vaga) {
    die("Vaga não encontrada.");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Detalhes da Vaga - LINKIDEAU</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <div class="logo">
        <span>LINKIDEAU</span>
        <a href="https://www.passofundo.ideau.com.br/" target="_blank">
            <img src="https://www.getulio.ideau.com.br/wp-content/uploads/2019/05/logo_ideau.png" alt="Logo Ideau">
        </a>
    </div>
    <nav>
        <a href="index.php" class="btn">Início</a>
        <a href="sobre.php" class="btn">Sobre Nós</a>
        <a href="login.php" class="btn">Login</a>
    </nav>
</header>

<main>
    <section class="vaga-detalhes">
        <h1>Detalhes da Vaga</h1>

        <?php if (!empty($vaga['imagem_vaga']) && file_exists($vaga['imagem_vaga'])): ?>
            <img src="<?php echo htmlspecialchars($vaga['imagem_vaga']); ?>" alt="Imagem da Vaga" class="imagem-vaga">
        <?php endif; ?>

        <div class="vaga-info">
            <p><strong>Empresa:</strong> <?php echo htmlspecialchars($vaga['nome_empresa']); ?></p>
            <p><strong>Descrição:</strong> <?php echo nl2br(htmlspecialchars($vaga['descricao'])); ?></p>
            <p><strong>Requisitos:</strong> <?php echo nl2br(htmlspecialchars($vaga['requisitos'])); ?></p>
            <p><strong>Área:</strong> <?php echo htmlspecialchars($vaga['area']); ?></p>
            <p><strong>Cursos:</strong> <?php echo htmlspecialchars($vaga['cursos']); ?></p>
            <p><strong>Semestre:</strong> <?php echo htmlspecialchars($vaga['semestre']); ?></p>
            <p><strong>Tipo de Vaga:</strong> <?php echo htmlspecialchars($vaga['tipo_de_vaga']); ?></p>
            <p><strong>Localização:</strong> <?php echo htmlspecialchars($vaga['localizacao']); ?></p>
        </div>

        <a href="candidatar.php?vaga_id=<?php echo $vaga['id']; ?>" class="btn">Candidatar-se</a>
    </section>
</main>

<footer>
    <p>&copy; 2025 LINKIDEAU. Todos os direitos reservados.</p>
</footer>

</body>
</html>
