<?php
require_once 'Usuario.php';
session_start(); // Inicia a sessão para armazenar mensagens

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica se todos os campos obrigatórios foram preenchidos
    if (empty($_POST['nome_completo']) || empty($_POST['cpf']) || empty($_POST['data_nascimento']) || empty($_POST['telefone']) || empty($_POST['email']) || empty($_POST['senha'])) {
        $_SESSION['mensagem'] = "Todos os campos são obrigatórios.";
    } else {
        // Sanitiza os dados do formulário
        $nome_completo = htmlspecialchars(trim($_POST['nome_completo']));
        $cpf = htmlspecialchars(trim($_POST['cpf']));
        $data_nascimento = trim($_POST['data_nascimento']);
        $telefone = htmlspecialchars(trim($_POST['telefone']));
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $senha = trim($_POST['senha']);

        // Validar a data de nascimento
        if (!DateTime::createFromFormat('d/m/Y', $data_nascimento)) {
            $_SESSION['mensagem'] = "Data de nascimento inválida! Use o formato DD/MM/AAAA.";
        } else {
            // Converter a data para o formato do banco de dados
            $data_nascimento_db = DateTime::createFromFormat('d/m/Y', $data_nascimento)->format('Y-m-d');

            // Upload da foto de perfil
            $foto_perfil = '';
            if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
                // Verifica se a pasta "uploads" existe, se não, cria
                if (!is_dir('uploads/usuarios')) {
                    mkdir('uploads/usuarios', 0777, true);
                }

                // Verifica a extensão do arquivo
                $extensao = strtolower(pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION));
                $extensoes_validas = ['jpg', 'jpeg', 'png', 'gif'];

                if (!in_array($extensao, $extensoes_validas)) {
                    $_SESSION['mensagem'] = "Formato de imagem inválido. Use apenas JPG, JPEG, PNG ou GIF.";
                } elseif ($_FILES['foto_perfil']['size'] > 2 * 1024 * 1024) { // 2MB
                    $_SESSION['mensagem'] = "O arquivo é muito grande. Máximo: 2MB.";
                } else {
                    // Gera um nome único para o arquivo
                    $nomeArquivo = uniqid() . '_' . basename($_FILES['foto_perfil']['name']);
                    $foto_perfil = 'uploads/usuarios/' . $nomeArquivo;

                    // Move o arquivo para o diretório de uploads
                    if (!move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $foto_perfil)) {
                        $_SESSION['mensagem'] = "Erro ao fazer upload da foto de perfil.";
                    }
                }
            }

            // Se não houver erros, cadastra o usuário
            if (!isset($_SESSION['mensagem'])) {
                $usuario = new Usuario();
                if ($usuario->cadastrar($nome_completo, $email, $senha, $telefone, $data_nascimento_db, $foto_perfil, $cpf)) {
                    $_SESSION['mensagem'] = "Cadastro realizado com sucesso!";
                } else {
                    $_SESSION['mensagem'] = "Erro ao cadastrar o usuário. Tente novamente.";
                }
            }
        }
    }

    // Redireciona de volta para a página de cadastro
    header("Location: cadastro_usuario.php");
    exit;
}
?>
