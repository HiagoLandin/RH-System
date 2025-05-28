<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['mensagem'] = "Você precisa fazer login para acessar esta página.";
    header("Location: login.php");
    exit;
}

require_once 'Usuario.php';
require_once 'Empresa.php';

$usuario = new Usuario();
$dadosUsuario = $usuario->buscarPorId($_SESSION['usuario_id']);

if (!$dadosUsuario) {
    $_SESSION['mensagem'] = "Usuário não encontrado.";
    header("Location: login.php");
    exit;
}

$empresa = new Empresa();
$empresas = $empresa->listarEmpresas();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Candidato - LINKIDEAU</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="perfil.css">
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
            <button class="btn-sair" onclick="history.back()">← Voltar</button>
            <a href="logout.php" class="btn-sair">Sair</a>
        </nav>
    </header>

    <main>
        <section class="perfil">
            <h1>Perfil do Candidato</h1>
            <div class="dados-perfil">
                <div class="foto-perfil">
                    <?php
                    $caminhoImagem = !empty($dadosUsuario['foto_perfil']) && file_exists($dadosUsuario['foto_perfil'])
                        ? htmlspecialchars($dadosUsuario['foto_perfil'])
                        : 'img/foto_padrao.jpg';
                    ?>
                    <img src="<?php echo $caminhoImagem; ?>" alt="Foto de Perfil" class="foto-perfil-img">
                </div>
                <div class="informacoes-perfil">
                    <p><strong>Nome:</strong> <?php echo htmlspecialchars($dadosUsuario['nome']); ?></p>
                    <p><strong>E-mail:</strong> <?php echo htmlspecialchars($dadosUsuario['email']); ?></p>
                    <p><strong>Telefone:</strong> <?php echo htmlspecialchars($dadosUsuario['telefone']); ?></p>
                </div>
            </div>
        </section>

        <section class="lista-cadastrados">
            <h2>Empresas Cadastradas</h2>
            <div class="lista">
                <?php if (!empty($empresas)): ?>
                    <?php foreach ($empresas as $empresa): ?>
                        <div class="item">
                            <?php
                            $caminhoEmpresa = !empty($empresa['foto_perfil']) && file_exists($empresa['foto_perfil'])
                                ? htmlspecialchars($empresa['foto_perfil'])
                                : 'img/imagem_padrao.jpg';
                            ?>
                            <img src="<?php echo $caminhoEmpresa; ?>" alt="<?php echo htmlspecialchars($empresa['nome']); ?>">
                            <div class="detalhes">
                                <h3><?php echo htmlspecialchars($empresa['nome']); ?></h3>
                                <p><strong>E-mail:</strong> <?php echo htmlspecialchars($empresa['email']); ?></p>
                                <p><strong>Telefone:</strong> <?php echo htmlspecialchars($empresa['telefone']); ?></p>
                            </div>
                            <div class="acoes">
                                <a href="ver_vagas.php?empresa_id=<?php echo $empresa['id']; ?>" class="btn">Ver Vagas</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhuma empresa cadastrada.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 LINKIDEAU. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
