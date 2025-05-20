<?php
// Inicia a sessão para armazenar mensagens
session_start();

// Verifica se há uma mensagem na sessão
$mensagem = isset($_SESSION['mensagem']) ? $_SESSION['mensagem'] : '';
unset($_SESSION['mensagem']); // Remove a mensagem após exibir
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Candidato 23 - LINKIDEAU</title>
    <!-- Link para o Google Fonts pra deixar a logo bonitinha (Bebas Neue) -->
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <!-- Link para o CSS -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo">
            <span>LINKIDEAU</span>
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
        <section class="cadastro">
            <h1>Cadastro de Candidato</h1>
            <!-- Exibe a notificação se houver -->
            <?php if (!empty($mensagem)): ?>
                <div class="notificacao"><?php echo htmlspecialchars($mensagem); ?></div>
            <?php endif; ?>
            <form action="processar_cadastro.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="foto_perfil">Foto de Perfil:</label>
                    <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*" required>
                </div>
                <div class="form-group">
                    <label for="nome_completo">Nome Completo:</label>
                    <input type="text" id="nome_completo" name="nome_completo" required>
                </div>
                <div class="form-group">
                    <label for="cpf">CPF:</label>
                    <input type="text" id="cpf" name="cpf" required>
                </div>
                <div class="form-group">
                    <label for="data_nascimento">Data de Nascimento (DD/MM/AAAA):</label>
                    <input type="text" id="data_nascimento" name="data_nascimento" placeholder="DD/MM/AAAA" required>
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone:</label>
                    <input type="tel" id="telefone" name="telefone" required>
                </div>
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                <button type="submit" class="btn">Cadastrar</button>
            </form>
            <!-- Botão para voltar à página inicial -->
            <a href="index.php" class="btn-voltar">Voltar à Página Inicial</a>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 LINKIDEAU. Todos os direitos reservados.</p>
    </footer>

    <!-- Biblioteca Inputmask (tudo isso foi pra deixar o formato de aniver em d/m/a) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
    <script>
        $(document).ready(function() {
            // Aplica a máscara de data no campo
            $('#data_nascimento').inputmask('99/99/9999', { placeholder: 'DD/MM/AAAA' });
        });
    </script>
</body>
</html>