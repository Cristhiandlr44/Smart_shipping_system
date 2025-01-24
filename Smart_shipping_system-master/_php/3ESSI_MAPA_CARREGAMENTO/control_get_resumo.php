<?php
require_once("../3SSI_CRUD/conexao.php");

// Recebe a data enviada via POST
$dataLancamento = $_POST['dataLancamento'];

header('Content-Type: application/json');

// Consulta para pegar os resumos dos monitoramentos da data específica
$sql = "SELECT 
            m.Id AS idMonitoramento, 
            m.placa_caminhao AS placa, 
            SUM(i.peso_bruto) AS pesoTotal, 
            COUNT(i.Id_monitoramento) AS quantidadeEntrega
        FROM monitoramento m
        LEFT JOIN notas i ON m.Id = i.Id_monitoramento
        WHERE m.largada = '$dataLancamento'
        GROUP BY m.Id, m.placa_caminhao";

$result = $conn->query($sql);

$resumos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $resumos[] = $row;
    }
}

// Retorna o resultado como JSON
echo json_encode($resumos);
?>
