<?php
require_once('../CRUD/relog.php');

if (isset($_POST['cidade']) && isset($_POST['rota'])) {
    $cidade = $conn->real_escape_string($_POST['cidade']);
    $rota = $conn->real_escape_string($_POST['rota']);

    $sql = "INSERT INTO base_rotas (cidade, rota) VALUES ('$cidade', '$rota')";

    if ($conn->query($sql) === TRUE) {
        echo "Cidade e rota adicionadas com sucesso!";
    } else {
        echo "Erro ao adicionar: " . $conn->error;
    }
} else {
    echo "Dados inválidos.";
}
?>
