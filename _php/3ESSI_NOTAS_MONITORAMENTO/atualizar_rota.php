<?php
require_once('../CRUD/relog.php');

// Verifica se os dados foram enviados via POST
if (isset($_POST['id']) && isset($_POST['rota'])) {
    $id = intval($_POST['id']); // Garante que o ID seja um número inteiro
    $novaRota = $conn->real_escape_string($_POST['rota']); // Protege contra SQL Injection

    // Consulta SQL para atualizar a rota
    $sql = "UPDATE base_rotas SET rota = '$novaRota' WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Rota atualizada com sucesso!";
    } else {
        echo "Erro ao atualizar a rota: " . $conn->error;
    }
} else {
    echo "Dados inválidos.";
}
?>
