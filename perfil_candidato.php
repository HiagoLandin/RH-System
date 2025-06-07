<?php
session_start();

if (!isset($_SESSION['empresa_id'])) {
    $_SESSION['mensagem'] = "Você precisa fazer login como empresa.";
    header("Location: login_empresa.php");
    exit;
}

require_once 'database.php';
require_once 'Usuario.php';

if (!isset($_GET['id'])) {
    die("Usuário não especificado.");
}

$usuario_id = $_GET['id'];

$usuario = new Usuario();
$dadosUsuario = $usuario->buscarPorId($usuario_id);

if (!$dadosUsuario) {
    die("Usuário não encontrado.");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Perfil do Candidato</title>
    <link rel="stylesheet" href="perfil.css">
    <link rel="stylesheet" href="styles.css">

</head>
<body>
    <header>
           <div class="logo"></div>
            <nav>
                <a href="minhas_vagas.php" class="btn-sair">Voltar</a>
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

        
    </main>

</body>
</html>
