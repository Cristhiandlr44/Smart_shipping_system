<?php

require_once('../CRUD/relog.php'); // Incluindo a conexão com o banco de dados

if (isset($_GET['notaFiscal'])) {
    $notaFiscal = $_GET['notaFiscal'];


    $sql = "SELECT p.cod, p.descricao FROM produtos AS p WHERE p.nf = '$notaFiscal'";
    $result = $conn->query($sql);

    $itens = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $itens[] = $row; // Adiciona os itens encontrados no array
        }
    }

    // Retorna os itens como JSON
    echo json_encode($itens);
}
?>
