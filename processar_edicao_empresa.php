<?php
session_start();
require_once 'Empresa.php';
require_once 'database.php';

// Verifica se o usuário está logado e tem ID da empresa
if (!isset($_SESSION['empresa_id'])) {
    header("Location: login_empresa.php");
    exit;
}

$database = new Database();
$conn = $database->getConnection();

// Obtém o ID da empresa a partir da sessão
$empresa_id = $_SESSION['empresa_id'];

// Validação básica
if (empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['telefone']) || empty($_POST['endereco'])|| empty($_POST['descricao'])) {
    $_SESSION['mensagem'] = "Todos os campos são obrigatórios.";
    header("Location:  editar_perfil_empresa.php");
    exit;
}

$nome = htmlspecialchars(trim($_POST['nome']));
$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$telefone = htmlspecialchars(trim($_POST['telefone']));
$endereco = htmlspecialchars(trim($_POST['endereco']));
$descricao = htmlspecialchars(trim($_POST['descricao']));

// Se a senha foi enviada, criptografa
$senha = null;
if (!empty($_POST['senha'])) {
    $senha = password_hash(trim($_POST['senha']), PASSWORD_DEFAULT);
}

// Processamento da foto de perfil
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
        header("Location:  editar_perfil_empresa.php");
        exit;
    } elseif ($_FILES['foto_perfil']['size'] > 2 * 1024 * 1024) {
        $_SESSION['mensagem'] = "O arquivo é muito grande. Máximo: 2MB.";
        header("Location:  editar_perfil_empresa.php");
        exit;
    } else {
        $nomeArquivo = uniqid() . '_' . basename($_FILES['foto_perfil']['name']);
        $caminhoCompleto = $diretorioUploads . $nomeArquivo;

        if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $caminhoCompleto)) {
            $foto_perfil = $caminhoCompleto;
        } else {
            $_SESSION['mensagem'] = "Erro ao fazer upload da foto de perfil.";
            header("Location: editar_perfil_empresa.php");
            exit;
        }
    }
}

// Monta a query de atualização
$query = "UPDATE empresas SET nome = ?, email = ?, telefone = ?, endereco = ?, descricao = ?";

$params = [$nome, $email, $telefone, $endereco, $descricao];
$types = "sssss";

if (!empty($foto_perfil)) {
    $query .= ", foto_perfil = ?";
    $params[] = $foto_perfil;
    $types .= "s";
}

if (!empty($senha)) {
    $query .= ", senha = ?";
    $params[] = $senha;
    $types .= "s";
}

$query .= " WHERE id = ?";
$params[] = $empresa_id;
$types .= "i";

$stmt = $conn->prepare($query);

if (!$stmt) {
    $_SESSION['mensagem'] = "Erro na preparação da query: " . $conn->error;
    header("Location: editar_perfil_empresa.php");
    exit;
}

$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    $_SESSION['mensagem'] = "Dados da empresa atualizados com sucesso!";
} else {
    $_SESSION['mensagem'] = "Erro ao atualizar os dados: " . $stmt->error;
}

$stmt->close();
$conn->close();

header("Location: perfil_empresa.php");
exit;
?>
