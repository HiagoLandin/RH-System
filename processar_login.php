<?php
session_start(); // Inicia a sessão
require_once 'Usuario.php'; // Inclui a classe Usuario

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitiza os dados do formulário
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $senha = trim($_POST['senha']);

    // Valida os campos
    if (empty($email) || empty($senha)) {
        $_SESSION['mensagem'] = "Todos os campos são obrigatórios.";
    } else {
        // Busca o usuário no banco de dados
        $usuario = new Usuario();
        $dadosUsuario = $usuario->buscarPorEmail($email);

        if ($dadosUsuario && password_verify($senha, $dadosUsuario['senha'])) {
            // Login bem-sucedido
            $_SESSION['usuario_id'] = $dadosUsuario['id']; // Armazena o ID do usuário na sessão
            $_SESSION['mensagem'] = "Login realizado com sucesso!";
            header("Location: perfil_usuario.php"); // Redireciona para o perfil do usuário
            exit;
        } else {
            // Credenciais inválidas
            $_SESSION['mensagem'] = "E-mail ou senha incorretos.";
        }
    }

    // Redireciona de volta para a página de login
    header("Location: login.php");
    exit;
}
?>
