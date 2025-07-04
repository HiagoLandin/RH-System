<?php
// Inicia a sessão para armazenar mensagens
session_start();

// Verifica se há uma mensagem na sessão
$mensagem = isset($_SESSION['mensagem']) ? $_SESSION['mensagem'] : '';
unset($_SESSION['mensagem']); // Remove a mensagem após exibi-la
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login de Candidato - CARREIRA IDEAU</title>

    <!-- Link para o Google Fonts da logo lindinha (Bebas Neue) -->
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">

    <!-- Link para o CSS -->
    <link rel="stylesheet" href="styles.css">
    
</head>

<body>
    <header>
        <div class="logo">
            <span>CARREIRA IDEAU</span>
            <!-- Imagem da logo da ideau com link externo -->
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
        <section class="login">
            <h1>Login de Candidato</h1>

            <!-- Exibe a notificação se houver -->
            <?php if (!empty($mensagem)): ?>
                <div class="notificacao <?php echo (strpos($mensagem, 'sucesso') !== false) ? 'success' : 'error'; ?>">
                    <?php echo htmlspecialchars($mensagem); ?>
                </div>
            <?php endif; ?>

            <form action="processar_login.php" method="POST">
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
            
                <button type="submit" class="btn">Entrar</button>
            </form>

            <!-- Link para a página de cadastro -->
            <p>Não tem uma conta? <a href="cadastro_usuario.php">Cadastre-se aqui</a></p>

            <!-- Botão para voltar à página inicial -->
            <a href="index.php" class="btn-voltar">Voltar à Página Inicial</a>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 CARREIRA IDEAU. Todos os direitos reservados.</p>
    </footer>
</body>

</html>
