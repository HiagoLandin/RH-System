<?php
session_start();
require_once 'database.php'; // sua conexão MySQLi
require_once 'Usuario.php';

// Verifica se está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
$database = new Database();
$conn = $database->getConnection();

$usuario_id = $_SESSION['usuario_id'];

// Busca dados do usuário
$query = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Usuário não encontrado.";
    exit;
}

$usuario = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
     <!-- Link para o Google Fonts pra deixar a logo bonitinha (Bebas Neue) -->
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <!-- Link para o CSS -->
    <link rel="stylesheet" href="perfil.css">
    <link rel="stylesheet" href="styles.css">

</head>
<body>

    <header>
        <div class="logo"></div> 
        <nav>
            <a href="perfil_usuario.php" class="btn">Voltar</a>
        </nav>

    </header>

<main>
    <section class="login perfil">
        <div class="dados-perfil">
        <h1>Editar Perfil</h1>
        <form action="processar_edicao.php" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label for="foto_perfil">Foto de Perfil:</label>
                <input type="file" name="foto_perfil" accept="image/*">
            </div>

            <div class="form-group">
                <label for="nome">Nome Completo:</label>
                <input type="text" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
            </div>

            <div class="form-group">
                <label for="cpf">CPF:</label>
                <input type="text" name="cpf" value="<?php echo htmlspecialchars($usuario['cpf']); ?>" required>
            </div>

            <div class="form-group">
                <label for="data_nascimento">Data de Nascimento:</label>
                <input type="text" name="data_nascimento" value="<?php echo htmlspecialchars($usuario['data_nascimento']); ?>" required>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <textarea name="descricao" rows="5" cols="30" required><?php echo htmlspecialchars($usuario['descricao']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="text" name="telefone" value="<?php echo htmlspecialchars($usuario['telefone']); ?>" required>
            </div>

            <div class="form-group">
                <label for="curriculo">Currículo (PDF):</label>
                <input type="file" name="curriculo" accept="application/pdf">
            </div>

            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" name="senha">
                <small>Deixe em branco se não quiser mudar a senha.</small>
            </div>

            <div class="form-group">
                <label for="semestre">Semestre:</label>
                <select name="semestre" id="semestre" required>
                    <option value="">Selecione o semestre</option>
                    <?php
                    $semestres = [
                        "1° semestre", "2° semestre", "3° semestre", "4° semestre",
                        "5° semestre", "6° semestre", "7° semestre", "8° semestre",
                        "9° semestre", "10° semestre", "11° semestre", "12° semestre"
                    ];
                    foreach ($semestres as $semestre) {
                        $selected = ($dadosUsuario['semestre'] === $semestre) ? 'selected' : '';
                        echo "<option value=\"$semestre\" $selected>$semestre</option>";
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

                    $cursosSelecionados = explode(',', $dadosUsuario['cursos']);

                    foreach ($todosCursos as $curso) {
                        $selected = in_array($curso, $cursosSelecionados) ? 'selected' : '';
                        echo "<option value=\"$curso\" $selected>$curso</option>";
                    }
                    ?>
                </select>
                <p><small>Segure Ctrl (Windows) ou Command (Mac) para selecionar múltiplos.</small></p>
            </div>
                <button type="submit" class="btn-voltar">Salvar Alterações</button>
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
