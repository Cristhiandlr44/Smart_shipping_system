<?php
    require_once("../3SSI_CRUD/conexao.php");

$sql = "SELECT placa_caminhao, finalizada FROM monitoramento WHERE finalizada IS NULL";
$result = mysqli_query($conn, $sql);

$placasEmViagem = [];
while ($row = mysqli_fetch_assoc($result)) {
    $placasEmViagem[] = $row['placa_caminhao'];
}

echo json_encode($placasEmViagem);
?>
