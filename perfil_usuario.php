<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['mensagem'] = "Você precisa fazer login para acessar esta página.";
    header("Location: login.php");
    exit;
}

require_once 'database.php';
require_once 'Usuario.php';
require_once 'Empresa.php';
require_once 'Vaga.php';

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
    <title>Perfil do Candidato - Carreira IDEAU</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="perfil.css">
</head>
<body>
    <header>
           <div class="logo">
            <nav>
                <a href="feed.php" class="btn-sair">Voltar</a>
            </nav>
                </div>
           
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
                    <p><strong></strong> <?php echo htmlspecialchars($dadosUsuario['data_nascimento']); ?></p>
                   
                </div>
                <div> 
                    <nav>
                        <a href="editar_perfil.php" class="btn-sair">✏️</a>
                     </nav>
                </div>
            </div>
        </section>

        <section class="perfil">
            <div class="dados-perfil">
             <div class="informacoes-perfil">
                <h1>Visão Geral</h1>
                    <p><strong>Sobre:<br></strong> <?php echo nl2br(htmlspecialchars($dadosUsuario['descricao'])); ?></p>
                     <h1>Informações</h1>
                    <h2>contatos</h1>
                    <p><strong></strong> <?php echo htmlspecialchars($dadosUsuario['email']); ?></p>
                    <p><strong>Telefone:</strong> <?php echo htmlspecialchars($dadosUsuario['telefone']); ?></p>
                    
                     <h1>Anexos</h1>
                     <?php
                        if (!empty($dadosUsuario['curriculo'])) {
                            // Extrai só o nome do arquivo (tudo depois da última /)
                            $nomeArquivo = basename($dadosUsuario['curriculo']);
                            // Monta o caminho para link, pasta + arquivo codificado
                            $caminho = "curriculos/" . rawurlencode($nomeArquivo);

                            echo "<p>Currículo: " . htmlspecialchars($nomeArquivo) . "</p>";
                            echo "<a href='$caminho' target='_blank'>Abrir currículo</a>";
                        } else {
                            echo "<p>Nenhum currículo disponível.</p>";
                        }
                        ?>
                        <p><strong>Semestre:</strong> <?php echo htmlspecialchars($dadosUsuario['semestre']); ?></p>
                         <p><strong>Cursos:</strong> <?php echo htmlspecialchars($dadosUsuario['cursos']); ?></p>

                     </div>
            </div>
        </section>
        <?php 
        // Conexão para buscar candidatos
$database = new Database();
$conn = $database->getConnection();

        $usuario_id = $_SESSION['usuario_id'];
                $sql = "
            SELECT v.* , e.nome AS nome_empresa
            FROM vagas v
            INNER JOIN candidaturas i ON v.id = i.vaga_id
           INNER JOIN empresas e ON v.empresa_id = e.id
            WHERE i.usuario_id = ?
        ";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $usuario_id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $vagasInscritas = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $vagasInscritas[] = $row;
        }

        mysqli_stmt_close($stmt);
        ?>
<section class="lista-cadastrados">
    <h2>Vagas que você se cadastrou</h2>
    <div class="lista">
        <?php if (!empty($vagasInscritas)): ?>
            <?php foreach ($vagasInscritas as $vaga): ?>
                <div class="item">
                    <?php
                    $imagemVaga = !empty($vaga['imagem_vaga']) && file_exists($vaga['imagem_vaga'])
                        ? htmlspecialchars($vaga['imagem_vaga'])
                        : 'img/imagem_padrao.jpg';
                    ?>
                    <img src="<?php echo $imagemVaga; ?>" alt="Imagem da vaga">
                    <div class="detalhes">
                       
                            <h4><strong>Empresa:</strong> <?php echo htmlspecialchars($vaga['nome_empresa']); ?></h4>                               
                                <p><strong>Area:</strong> <?php echo htmlspecialchars($vaga['area']); ?></p>
                                <p><strong>Curso:</strong> <?php echo htmlspecialchars($vaga['cursos']); ?></p>
                                <p><strong>Semestre:</strong> <?php echo htmlspecialchars($vaga['semestre']); ?></p>
                                <p><strong>Tipo de Vaga:</strong> <?php echo htmlspecialchars($vaga['tipo_de_vaga']); ?></p>
                                <a href="ver_vagas.php?id=<?= $vaga['id'] ?>" class="detalhes">Detalhes</a></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Você ainda não se cadastrou em nenhuma vaga.</p>
        <?php endif; ?>
    </div>
</section>

    </main>

    <footer>
        <p>&copy; 2025 LINKIDEAU. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
