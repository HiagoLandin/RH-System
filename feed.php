<?php 
session_start();

if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['mensagem'] = "Você precisa fazer login para acessar esta página.";
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id']; //

require_once 'database.php';
require_once 'Empresa.php';
require_once 'Vaga.php';
require_once 'Usuario.php';

$empresa = new Empresa();
$empresas = $empresa->listarEmpresas();

$vagas = new Vaga();
$vagas = $vagas->listar();

$vagaObj = new Vaga();

if (isset($_GET['pesquisa']) && !empty($_GET['pesquisa'])) {
    $termo = $_GET['pesquisa'];
    $vagas = $vagaObj->buscarPorEmpresa($termo); // você vai criar esse método
} else {
    $vagas = $vagaObj->listar();
}


$usuario = new Usuario();
$dadosUsuario = $usuario->buscarPorId($usuario_id); // <<< ESSA LINHA ESTÁ FALTANDO

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
            <a href="perfil_usuario.php">
                  <?php
                    $caminhoImagem = !empty($dadosUsuario['foto_perfil']) && file_exists($dadosUsuario['foto_perfil'])
                        ? htmlspecialchars($dadosUsuario['foto_perfil'])
                        : 'img/foto_padrao.jpg';
                    ?>
                    <img src="<?php echo $caminhoImagem; ?>" alt="Foto de Perfil" class="foto-perfil-img">
            </a>
                </div>
           
        </div>
        <nav>
             <form action="feed.php" method="GET" class="form-pesquisa">
        <input type="text" name="pesquisa" placeholder="Buscar empresa..." class="campo-pesquisa">
        <button type="submit" class="botao-pesquisa">
            🔍
        </button>
    </form>
        </nav>
        <nav>
            <a href="logout.php" class="btn-sair">Sair</a>
        </nav>
    </header>

    <main>
       
        <section class="lista-cadastrados">
    <h2>Vagas Disponíveis</h2>
    <div class="tabela-container">
        <div class="anime-bloco">
            <?php if (!empty($vagas)): ?>
                <?php foreach ($vagas as $vaga): ?>
                    <div class="anime-card">
                          <?php
                            $imagemVaga = !empty($vaga['imagem_vaga']) && file_exists($vaga['imagem_vaga'])
                                ? htmlspecialchars($vaga['imagem_vaga'])
                                : 'img/imagem_padrao.jpg';
                            ?>
                         <img src="<?php echo $imagemVaga; ?>" alt="Imagem da vaga">
                          
                         <div class="anime-info">
                          
                        
                            <div class="detalhes">
                                <h4><strong>Empresa:</strong> <?php echo htmlspecialchars($vaga['nome_empresa']); ?></h4>                               
                                <p><strong>Descrição:</strong> <?php echo htmlspecialchars($vaga['descricao']); ?></p>
                                <p><strong>Requisitos:</strong> <?php echo htmlspecialchars($vaga['requisitos']); ?></p>
                                <p><strong>Localização:</strong> <?php echo htmlspecialchars($vaga['localizacao']); ?></p>
                                <p><strong>Tipo de Vaga:</strong> <?php echo htmlspecialchars($vaga['tipo_de_vaga']); ?></p>
                                <a href="ver_vagas.php?id=<?= $vaga['id'] ?>" class="detalhes">Detalhes</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhuma vaga disponível.</p>
            <?php endif; ?>
        </div>
    </divc>
</section>

       

        
    </main>

    <footer>
        <p>&copy; 2025 LINKIDEAU. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
