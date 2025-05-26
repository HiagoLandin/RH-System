<?php
session_start();
require_once 'Usuario.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome_completo'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $telefone = $_POST['telefone'];
    $descricao = $_POST['descricao'];
    $cpf = $_POST['cpf'];
    $data_nascimento = $_POST['data_nascimento'];

    $data = DateTime::createFromFormat('d/m/Y', $data_nascimento);
    if ($data) {
        $data_nascimento_formatada = $data->format('Y-m-d');
    } else {
        $_SESSION['mensagem'] = "Data de nascimento inválida.";
        header("Location: cadastro_usuario.php");
        exit();
    }
    // Upload da foto de perfil
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
        $foto = $_FILES['foto_perfil'];
        $diretorio_foto = 'fotos/';
        if (!is_dir($diretorio_foto)) {
            mkdir($diretorio_foto, 0755, true);
        }
        $nome_foto = uniqid() . '_' . basename($foto['name']);
        $caminho_foto = $diretorio_foto . $nome_foto;

        if (!move_uploaded_file($foto['tmp_name'], $caminho_foto)) {
            $_SESSION['mensagem'] = "Erro ao fazer upload da foto.";
            header("Location: cadastro_usuario.php");
            exit;
        }
    } else {
        $_SESSION['mensagem'] = "Erro no upload da foto: " . $_FILES['foto_perfil']['error'];
        header("Location: cadastro_usuario.php");
        exit;
    }

    // Upload do currículo (PDF)
    if (isset($_FILES['curriculo']) && $_FILES['curriculo']['error'] == 0) {
        $curriculo = $_FILES['curriculo'];
        $fileType = mime_content_type($curriculo['tmp_name']);
        if ($fileType != 'application/pdf') {
            $_SESSION['mensagem'] = "Erro: O arquivo do currículo deve ser um PDF.";
            header("Location: cadastro_usuario.php");
            exit;
        }

        $diretorio_curriculo = 'curriculos/';
        if (!is_dir($diretorio_curriculo)) {
            mkdir($diretorio_curriculo, 0755, true);
        }

        $nome_curriculo = uniqid() . '_' . basename($curriculo['name']);
        $caminho_curriculo = $diretorio_curriculo . $nome_curriculo;

        if (!move_uploaded_file($curriculo['tmp_name'], $caminho_curriculo)) {
            $_SESSION['mensagem'] = "Erro ao fazer upload do currículo.";
            header("Location: cadastro_usuario.php");
            exit;
        }
    } else {
        $_SESSION['mensagem'] = "Erro no upload do currículo: " . $_FILES['curriculo']['error'];
        header("Location: cadastro_usuario.php");
        exit;
    }

    $usuario = new Usuario();

    if ($usuario->cadastrar($nome, $email, $senha, $telefone, $descricao, $caminho_foto, $caminho_curriculo, $cpf, $data_nascimento_formatada)) {
        $_SESSION['mensagem'] = "Cadastro realizado com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao cadastrar o usuário.";
    }

    header("Location: perfil_usuario.php");
    exit;
} else {
    $_SESSION['mensagem'] = "Requisição inválida.";
    header("Location: cadastro_usuario.php");
    exit;
}
?>
