<?php
session_start(); // Inicia a sessão
require_once 'Empresa.php'; // Inclui a classe Empresa

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // limpa os dados do formulário
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $senha = trim($_POST['senha']);

    // Valida os campos
    if (empty($email) || empty($senha)) {
        $_SESSION['mensagem'] = "Todos os campos são obrigatórios.";
    } else {
        // Busca a empresa no banco de dados
        $empresa = new Empresa();
        $dadosEmpresa = $empresa->buscarPorEmail($email);

        if ($dadosEmpresa && password_verify($senha, $dadosEmpresa['senha'])) {
            // Login bem-sucedido
            $_SESSION['empresa_id'] = $dadosEmpresa['id']; // Armazena o ID da empresa na sessão
            $_SESSION['mensagem'] = "Login realizado com sucesso!";
            header("Location: perfil_empresa.php"); // Redireciona para o perfil da empresa
            exit;
        } else {
            // Credenciais inválidas
            $_SESSION['mensagem'] = "E-mail ou senha incorretos.";
        }
    }

    // Redireciona de volta para a página de login da empresa
    header("Location: login_empresa.php");
    exit;
}
?>
