<?php
session_start(); // Inicia a sessão

// Verifica se a empresa está logada
if (!isset($_SESSION['empresa_id'])) {
    $_SESSION['mensagem'] = "Você precisa fazer login para acessar esta página.";
    header("Location: login_empresa.php");
    exit;
}

require_once 'Empresa.php';
require_once 'Usuario.php'; // Usamos a classe Usuario para listar os candidatos
require_once 'Vaga.php';

$empresa = new Empresa();
$dadosEmpresa = $empresa->buscarPorId($_SESSION['empresa_id']); // Busca a empresa pelo ID

$vagaObj = new Vaga();
$vagas = $vagaObj->listarPorEmpresa($_SESSION['empresa_id']);


if (!$dadosEmpresa) {
    $_SESSION['mensagem'] = "Empresa não encontrada.";
    header("Location: login_empresa.php");
    exit;
}

$usuario = new Usuario();
$candidatos = $usuario->listarTodos(); // Método para listar todos os usuários (candidatos)
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil da Empresa - Carreira IDEAU</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="perfil.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="pagina-empresa">
    <header>
        <div class="logo">
            <nav>
                <a href="logout.php" class="btn-sair">Sair</a>
            </nav>
                </div>
        <nav>
            <a href="add_vaga.php" class="btn-sair">Adicionar Vaga</a>
             <a href="minhas_vagas.php" class="btn-sair">Candidaturas</a>
        
        </nav>
       



    </header>

    <main>
        <section class="perfil">
            <h1>Perfil da Empresa</h1>
            <div class="dados-perfil">
                <div class="foto-perfil">
                    <?php
                    $caminhoImagem = !empty($dadosEmpresa['foto_perfil']) && file_exists($dadosEmpresa['foto_perfil'])
                        ? htmlspecialchars($dadosEmpresa['foto_perfil'])
                        : 'img/foto_padrao.jpg';
                    ?>
                    <img src="<?php echo $caminhoImagem; ?>" alt="Logo da Empresa" class="foto-perfil-img">
                </div>
                <div class="informacoes-perfil">
                    <h1><strong></strong> <?php echo htmlspecialchars($dadosEmpresa['nome']); ?></h1>
                </div>

                 <div> 
                    <nav>
                        <a href="editar_perfil_empresa.php" class="btn-sair">✏️</a>
                     </nav>
                </div>
            </div>
            </div>
        </section>

        <section class="perfil">
            <div class="dados-perfil">
                <div class="informacoes-perfil">
                    <h1>Visão Geral</h1>
                    <h2>Sobre:</h2>
                    <p><strong></strong> <?php echo nl2br(htmlspecialchars($dadosEmpresa['descricao'])); ?></p>
                    <H2>Contatos</H2>
                    <p><strong>E-mail:</strong> <?php echo htmlspecialchars($dadosEmpresa['email']); ?></p>
                    <p><strong>Telefone:</strong> <?php echo htmlspecialchars($dadosEmpresa['telefone']); ?></p>
                    
                    <p><strong>Endereço:</strong> <?php echo htmlspecialchars($dadosEmpresa['endereco']); ?></p>
                </div>
            </div>
        </section>

        <section class="lista-cadastrados">
           <h1 style="font-size: 80px;"><strong>Vagas</strong> </h1>
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
                                <a href="editar_vagas.php?id=<?= $vaga['id'] ?>" class="Editar">Editar</a>
                                 
                            </div>
                           
                        </div>
                    </div>
                <?php endforeach; ?>
                  
            <?php else: ?>
                <p>Nenhuma vaga disponível.</p>
            <?php endif; ?>
        </div>
    </div>
        </section>
        
    </main>

    <footer>
        <p>&copy; 2025 LINKIDEAU. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
