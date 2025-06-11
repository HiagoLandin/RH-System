<?php
session_start();
$mensagem = isset($_SESSION['mensagem']) ? $_SESSION['mensagem'] : '';
unset($_SESSION['mensagem']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Empresa - CARREIRA IDEAU</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo">
            <span>CARREIRA IDEAU</span>
            <a href="https://www.passofundo.ideau.com.br/" target="_blank">
                <img src="https://www.getulio.ideau.com.br/wp-content/uploads/2019/05/logo_ideau.png" alt="Logo LINKIDEAU">
            </a>
        </div>
        <nav>
            <a href="login_empresa.php" class="btn">Empresa</a>
            <a href="login.php" class="btn">Candidato</a>
            <a href="sobre.php" class="btn">Sobre Nós</a>
        </nav>
    </header>

    <main>
        <section class="cadastro">
            <h1>Cadastro de Empresa</h1>
            <?php if (!empty($mensagem)): ?>
                <div class="notificacao"><?php echo htmlspecialchars($mensagem); ?></div>
            <?php endif; ?>
            <form action="processar_cadastro_empresa.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="foto_perfil">Logo da Empresa:</label>
                    <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*" required>
                </div>
                <div class="form-group">
                    <label for="nome">Nome da Empresa:</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone:</label>
                    <input type="tel" id="telefone" name="telefone" required>
                </div>
                <div class="form-group">
                    <label for="endereco">Endereço:</label>
                    <input type="text" id="endereco" name="endereco" required>
                </div>
                <button type="submit" class="btn">Cadastrar</button>
            </form>
            <a href="index.php" class="btn-voltar">Voltar à Página Inicial</a>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 CARREIRA IDEAU. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
