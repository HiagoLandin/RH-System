<?php
session_start(); // Inicia a sessão
session_destroy(); // Destrói a sessão
header("Location: index.php"); // Redireciona para a página de login
exit;
?>
