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

$id_vaga = isset($_GET['id']) ? intval($_GET['id']) : 0;
$dadosVaga = $vagas->buscarPorId($id_vaga);

// Protege contra edição de vagas de outras empresas
if (!$dadosVaga || $dadosVaga['empresa_id'] != $_SESSION['empresa_id']) {
    $_SESSION['mensagem'] = "Você não tem permissão para editar esta vaga.";
    header("Location: perfil_empresa.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imagem_vaga = $dadosVaga['imagem_vaga']; // valor original

    // Processa nova imagem, se houver
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
        $diretorioVagas = 'uploads/vagas/';
        if (!is_dir($diretorioVagas)) {
            mkdir($diretorioVagas, 0777, true);
        }

        $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        $extensoes_validas = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($extensao, $extensoes_validas)) {
            $_SESSION['mensagem'] = "Formato de imagem inválido.";
        } elseif ($_FILES['imagem']['size'] > 2 * 1024 * 1024) {
            $_SESSION['mensagem'] = "Imagem muito grande. Máx: 2MB.";
        } else {
            $nomeImagem = uniqid() . '_' . basename($_FILES['imagem']['name']);
            $caminhoImagemVaga = $diretorioVagas . $nomeImagem;

            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoImagemVaga)) {
                $imagem_vaga = $caminhoImagemVaga;
            } else {
                $_SESSION['mensagem'] = "Erro ao fazer upload da nova imagem.";
            }
        }
    }

    $empresa_id = $_SESSION['empresa_id'];
    
    $vagas->editar(
        $id_vaga,
        $empresa_id,
        $imagem_vaga,
        $_POST['requisitos'],
        $_POST['descricao'],
        $_POST['area'],
        isset($_POST['cursos']) ? implode(',', $_POST['cursos']) : '',
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
        <h1>Editar Nova Vaga</h1>

        <form method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="imagem">Imagem da Vaga:</label>
        <input type="file" name="imagem" id="imagem" accept="image/*">
        <?php if (!empty($dadosVaga['imagem_vaga'])): ?>
            <p>Imagem atual: <img src="<?= $dadosVaga['imagem_vaga'] ?>" width="100"></p>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="requisitos">Requisitos:</label>
        <textarea name="requisitos" id="requisitos" required><?= htmlspecialchars($dadosVaga['requisitos']) ?></textarea>
    </div>

    <div class="form-group">
        <label for="descricao">Descrição:</label>
        <textarea name="descricao" id="descricao" required><?= htmlspecialchars($dadosVaga['descricao']) ?></textarea>
    </div>

    <div class="form-group">
    <label for="area">Área:</label>
    <select name="area" id="area" required>
        <option value="">Selecione a área</option>
        <?php
        $areas = [
            "Tecnologia",
            "Engenharias",
            "Ciencias sociais e Humanas",
            "Saúde",
            "Artes",
            "Educação"
        ];

        foreach ($areas as $area) {
            $selected = ($dadosVaga['area'] === $area) ? 'selected' : '';
            echo "<option value=\"$area\" $selected>$area</option>";
        }
        ?>
    </select>
</div>


   <div class="form-group">
    <label for="cursos">Cursos:</label>
    <select name="cursos[]" id="cursos" multiple size="8" required>
        <?php
        $todosCursos = [
            "Administração", "Agronomia", "Análise e Desenvolvimento de Sistemas", "Arquitetura e Urbanismo",
            "Artes", "Biblioteconomia", "Biomedicina", "Ciências Biológicas", "Ciências Contábeis",
            "Ciências Sociais", "Comunicação Social – Jornalismo", "Comunicação Social – Publicidade e Propaganda",
            "Comunicação Social- Relações Públicas", "Direito", "Educação Física – Bacharelado",
            "Educação Física – Licenciatura", "Engenharia Civil", "Engenharia de Produção", "Engenharia Elétrica",
            "Engenharia Mecânica", "Estética e Cosmética", "Farmácia", "Filosofia", "Física", "Fisioterapia",
            "Geografia", "Gestão de Recursos Humanos", "História", "Letras", "Matemática", "Medicina Veterinária",
            "Nutrição", "Pedagogia", "Psicologia", "Química", "Secretariado Executivo", "Sociologia"
        ];

        // Explode os cursos salvos em um array
        $cursosSelecionados = explode(',', $dadosVaga['cursos']);

        foreach ($todosCursos as $curso) {
            $selected = in_array($curso, $cursosSelecionados) ? 'selected' : '';
            echo "<option value=\"$curso\" $selected>$curso</option>";
        }
        ?>
    </select>
    <p><small>Segure Ctrl (Windows) ou Command (Mac) para selecionar múltiplos.</small></p>
</div>


    <div class="form-group">
        

<select name="semestre" id="semestre" required>
    <option value="">Selecione o semestre</option>
    <?php
    $semestres = [
        "1° semestre", "2° semestre", "3° semestre", "4° semestre",
        "5° semestre", "6° semestre", "7° semestre", "8° semestre",
        "9° semestre", "10° semestre", "11° semestre", "12° semestre"
    ];
    foreach ($semestres as $semestre) {
        $selected = ($dadosVaga['semestre'] === $semestre) ? 'selected' : '';
        echo "<option value=\"$semestre\" $selected>$semestre</option>";
    }
    ?>
</select>

    </div>
        <div class="form-group">
        

<select name="tipo_de_vaga" id="tipo_de_vaga" required>
    <option value="">Qual o tipo da vaga:</option>
    <?php
    $tiposdevagas = [
        "CLT", "Estágio", "PJ", "Jovem aprendiz"
        
    ];
    foreach ($tiposdevagas as $tipo_de_vaga) {
        $selected = ($dadosVaga['tipo_de_vaga'] === $tipo_de_vaga) ? 'selected' : '';
        echo "<option value=\"$tipo_de_vaga\" $selected>$tipo_de_vaga</option>";
    }
    ?>
</select>

    </div>

   

    <div class="form-group">
        <label for="localizacao">Localização:</label>
        <input type="text" name="localizacao" id="localizacao" value="<?= htmlspecialchars($dadosVaga['localizacao']) ?>" required>
    </div>

    <button type="submit" class="btn">Salvar Alterações</button>
</form>


    </section>
</main>


    <footer>
        <p>&copy; 2025 LINKIDEAU. Todos os direitos reservados.</p>
    </footer>
</body>

</html>
