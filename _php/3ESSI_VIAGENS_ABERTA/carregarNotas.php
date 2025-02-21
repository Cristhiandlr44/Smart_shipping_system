<?php
require_once('../CRUD/relog.php'); // Incluindo a conexão com o banco de dados

if (isset($_GET['viagemId'])) {
    $viagemId = $_GET['viagemId'];

    // Buscar as notas fiscais da viagem
    $sql = "
    SELECT n.n_nota 
    FROM notas AS n
    INNER JOIN monitoramento AS m ON n.id_monitoramento = m.id OR n.id_monitoramento2 = m.id OR n.id_monitoramento3 = m.id
    WHERE (n.id_monitoramento = ? OR n.id_monitoramento2 = ? OR n.id_monitoramento3 = ?) AND m.finalizada IS NULL";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iii', $viagemId, $viagemId, $viagemId);
    $stmt->execute();
    $result = $stmt->get_result();

    $notas = [];
    while ($row = $result->fetch_assoc()) {
        $notas[] = $row;
    }

    // Retornar as notas fiscais em formato JSON
    echo json_encode($notas);
}
?>
