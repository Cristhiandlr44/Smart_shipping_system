<?php
require_once("../3SSI_CRUD/conexao.php");

header('Content-Type: application/json');

// Consulta para pegar as datas de monitoramento
$sql = "SELECT DISTINCT m.largada
        FROM monitoramento m
        ORDER BY m.largada DESC";

$result = $conn->query($sql);

$datas = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $datas[] = $row;
    }
}

// Retorna o resultado como JSON
echo json_encode($datas);
?>
