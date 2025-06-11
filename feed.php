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
    } elseif (!empty($_GET['tipo_de_vaga']) || !empty($_GET['semestre']) || !empty($_GET['curso']) || !empty($_GET['area'])) {
    $filtros = [
        'tipo_de_vaga' => $_GET['tipo_de_vaga'] ?? '',
        'semestre' => $_GET['semestre'] ?? '',
        'curso' => $_GET['curso'] ?? '',
        'area' => $_GET['area'] ?? ''
    ];

    $vagas = $vagaObj->filtrarVagas($filtros);

$termo = $_GET['pesquisa'] ?? '';

if (!empty($termo)) {
    $vagas = $vagaObj->buscarPorEmpresa($termo);
}
} else {
    $vagas = $vagaObj->listar();
}





$usuario = new Usuario();
$dadosUsuario = $usuario->buscarPorId($usuario_id); 

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Candidato - CARREIRA IDEAU</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
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

  <div>
      <form action="feed.php" method="GET" class="form-pesquisa">
        <input type="text" name="pesquisa" placeholder="Buscar empresa..." class="campo-pesquisa">
        <button type="submit" class="botao-pesquisa">🔍</button>
      </form>
    <div class="filtros-container">
      <button class="toggle-filtros">🔍 Filtros</button>
      <form action="feed.php" method="GET" class="filtro1">
        <select name="tipo_de_vaga" class="filtro2">
          <option value="">Tipo de Vaga</option>
          <option value="CLT">CLT</option>
          <option value="Estágio">Estágio</option>
          <option value="PJ">PJ</option>
          <option value="Jovem aprendiz">Jovem aprendiz</option>
        </select>

        <select name="semestre" class="filtro2">
          <option value="">Semestre</option>
          <?php for ($i = 1; $i <= 12; $i++): ?>
            <option value="<?= $i ?>° semestre"><?= $i ?>° semestre</option>
          <?php endfor; ?>
        </select>

        <select name="area" class="filtro2">
          <option value="">Área</option>
          <option value="Tecnologia">Tecnologia</option>
          <option value="Engenharias">Engenharias</option>
          <option value="Ciencias sociais e Humanas">Ciências Sociais e Humanas</option>
          <option value="Saúde">Saúde</option>
          <option value="Artes">Artes</option>
          <option value="Educação">Educação</option>
        </select>

        <input type="text" name="curso" placeholder="Filtrar por curso" class="filtro2">
        <button type="submit" class="botao-pesquisa">Aplicar</button>
      </form>
    </div>
        <nav>
            <a href="logout.php" class="btn">Sair</a>
        </nav>
  </div>
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
                                <p><strong>Area:</strong> <?php echo htmlspecialchars($vaga['area']); ?></p>
                                <p><strong>Curso:</strong> <?php echo htmlspecialchars($vaga['cursos']); ?></p>
                                <p><strong>Semestre:</strong> <?php echo htmlspecialchars($vaga['semestre']); ?></p>
                                <p><strong>Tipo de Vaga:</strong> <?php echo htmlspecialchars($vaga['tipo_de_vaga']); ?></p>
                                <a href="ver_vagas.php?id=<?= $vaga['id'] ?>" class="Editar">Detalhes</a>
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
        <p>&copy; 2025 CARREIRA IDEAU. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
