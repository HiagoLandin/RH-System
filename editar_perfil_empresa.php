<?php
session_start();
require_once 'database.php'; // conexão MySQLi
require_once 'Empresa.php';

// Verifica se está logado
if (!isset($_SESSION['empresa_id'])) {
    header("Location: login_empresa.php");
    exit;
}

$database = new Database();
$conn = $database->getConnection();

$empresa_id = $_SESSION['empresa_id'];

// Busca dados da empresa
$query = "SELECT * FROM empresas WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $empresa_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Empresa não encontrada.";
    exit;
}

$empresa = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empresa</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="perfil.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <header>
        <div class="logo"></div>
            <nav>
                <a href="perfil_empresa.php" class="btn">Voltar</a>
            </nav>
        </div>
    </header>

    <main>
        <section class="login perfil">
            <div class="dados-perfil">
            <h1>Editar Dados da Empresa</h1>

            <form action="processar_edicao_empresa.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="foto_perfil">Logo da Empresa:</label>
                    <input type="file" name="foto_perfil" accept="image/*">
                </div>

                <div class="form-group">
                    <label for="nome">Nome da Empresa:</label>
                    <input type="text" name="nome" value="<?php echo htmlspecialchars($empresa['nome']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($empresa['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="telefone">Telefone:</label>
                    <input type="text" name="telefone" value="<?php echo htmlspecialchars($empresa['telefone']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="endereco">Endereço:</label>
                    <input type="text" name="endereco" value="<?php echo htmlspecialchars($empresa['endereco']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="descricao">Descrição:</label>
                    <textarea name="descricao" rows="5" cols="30" required><?php echo htmlspecialchars($empresa['descricao']); ?></textarea>
                </div>


                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" name="senha">
                    <small>Deixe em branco se não quiser mudar a senha.</small>
                </div>

            <div class= "btn-container">
                <button type="submit" class="btn">Salvar Alterações</button>
            </div>

            </div>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 CARREIRA IDEAU. Todos os direitos reservados.</p>
    </footer>

</body>
</html>
