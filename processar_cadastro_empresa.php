<?php
require_once 'Empresa.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['senha']) || empty($_POST['telefone']) || empty($_POST['endereco'])) {
        $_SESSION['mensagem'] = "Todos os campos são obrigatórios.";
    } else {
        $nome = htmlspecialchars(trim($_POST['nome']));
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $senha = trim($_POST['senha']);
        $telefone = htmlspecialchars(trim($_POST['telefone']));
        $endereco = htmlspecialchars(trim($_POST['endereco']));

        $foto_perfil = '';
        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
            $diretorioUploads = 'uploads/empresas/';
            if (!is_dir($diretorioUploads)) {
                mkdir($diretorioUploads, 0777, true);
            }

            $extensao = strtolower(pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION));
            $extensoes_validas = ['jpg', 'jpeg', 'png', 'gif'];

            if (!in_array($extensao, $extensoes_validas)) {
                $_SESSION['mensagem'] = "Formato de imagem inválido. Use apenas JPG, JPEG, PNG ou GIF.";
            } elseif ($_FILES['foto_perfil']['size'] > 2 * 1024 * 1024) {
                $_SESSION['mensagem'] = "O arquivo é muito grande. Máximo: 2MB.";
            } else {
                $nomeArquivo = uniqid() . '_' . basename($_FILES['foto_perfil']['name']);
                $caminhoCompleto = $diretorioUploads . $nomeArquivo;

                if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $caminhoCompleto)) {
                    $foto_perfil = $caminhoCompleto;
                } else {
                    $_SESSION['mensagem'] = "Erro ao fazer upload da foto de perfil.";
                }
            }
        }

        if (!isset($_SESSION['mensagem'])) {
            $empresa = new Empresa();
            if ($empresa->cadastrar($nome, $email, $senha, $telefone, $endereco, $foto_perfil)) {
                $_SESSION['mensagem'] = "Cadastro realizado com sucesso!";
            } else {
                $_SESSION['mensagem'] = "Erro ao cadastrar a empresa.";
            }
        }
    }

    header("Location: cadastro_empresa.php");
    exit;
}
