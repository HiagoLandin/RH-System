<?php
session_start();
require_once("Vaga.php");
$vagas = new Vaga();

// Verifica se a empresa está logada
if (!isset($_SESSION['empresa_id'])) {
    $_SESSION['mensagem'] = "Você precisa fazer login para acessar esta página.";
    header("Location: login_empresa.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $imagem_vaga = '';
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
    $diretorioVagas = 'uploads/vagas/';
    if (!is_dir($diretorioVagas)) {
        mkdir($diretorioVagas, 0777, true);
    }

    $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
    $extensoes_validas = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($extensao, $extensoes_validas)) {
        $_SESSION['mensagem'] = "Formato de imagem da vaga inválido. Use JPG, JPEG, PNG ou GIF.";
    } elseif ($_FILES['imagem']['size'] > 2 * 1024 * 1024) {
        $_SESSION['mensagem'] = "A imagem da vaga é muito grande. Máximo permitido: 2MB.";
    } else {
        $nomeImagem = uniqid() . '_' . basename($_FILES['imagem']['name']);
        $caminhoImagemVaga = $diretorioVagas . $nomeImagem;

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoImagemVaga)) {
            $imagem_vaga = $caminhoImagemVaga;
        } else {
            $_SESSION['mensagem'] = "Erro ao fazer upload da imagem da vaga.";
        }
    }
}
 $empresa_id = $_SESSION['empresa_id'];

    $vagas->cadastrar(
        $empresa_id,
        $imagem_vaga,
        $_POST['requisitos'],
        $_POST['descricao'],
        $_POST['area'],
        $_POST['cursos'],
        $_POST['semestre'],
        $_POST['tipo_de_vaga'],
        $_POST['localizacao']
    );

    header("Location: perfil_empresa.php");
    exit();
}
?>




<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar vaga</title>

    <!-- Link para o Google Fonts da logo lindinha (Bebas Neue) -->
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">

    <!-- Link para o CSS -->
    <link rel="stylesheet" href="styles.css">
</head>

<body>
   <main>
    <section class="login">
        <h1>Adicionar Nova Vaga</h1>

        <form method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label for="imagem">Imagem da Vaga:</label>
                <input type="file" name="imagem" id="imagem" accept="image/*">
            </div>

            <div class="form-group">
                <label for="requisitos">Requisitos:</label>
                <textarea name="requisitos" id="requisitos" required></textarea>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <textarea name="descricao" id="descricao" required></textarea>
            </div>

            <div class="form-group">
                <label for="area">Área:</label>
                <input type="text" name="area" id="area" required>
            </div>

            <div class="form-group">
                <label for="cursos">Cursos:</label>
                <input type="text" name="cursos" id="cursos">
            </div>

            <div class="form-group">
                <label for="semestre">Semestre:</label>
                <input type="text" name="semestre" id="semestre">
            </div>

            <div class="form-group">
                <label for="tipo_de_vaga">Tipo de Vaga:</label>
                <input type="text" name="tipo_de_vaga" id="tipo_de_vaga">
            </div>

            <div class="form-group">
                <label for="localizacao">Localização:</label>
                <input type="text" name="localizacao" id="localizacao" required>
            </div>

            <button type="submit" class="btn">Cadastrar Vaga</button>
        </form>

    </section>
</main>


    <footer>
        <p>&copy; 2025 LINKIDEAU. Todos os direitos reservados.</p>
    </footer>
</body>

</html>
