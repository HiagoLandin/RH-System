<?php

require_once 'Vaga.php';

if (isset($_GET['id']) && isset($_GET['empresa_id'])) {
    $id = intval($_GET['id']);
    $empresa_id = intval($_GET['empresa_id']);

    $vaga = new Vaga();
    if ($vaga->excluir($id, $empresa_id)) {
        header("Location: perfil_empresa.php?mensagem=Vaga excluída com sucesso");
        exit;
    } else {
        echo "Erro ao excluir a vaga.";
    }
} else {
    echo "Parâmetros inválidos.";
}
?>
