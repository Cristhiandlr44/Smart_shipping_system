<?php
// Conectar ao banco de dados
require_once('../CRUD/relog.php'); // Incluindo a conexão com o banco de dados

// Verifica se o ID do monitoramento foi enviado
if (isset($_GET['idMonitoramento'])) {
    $idMonitoramento = $_GET['idMonitoramento'];

    // Consulta para buscar todas as notas fiscais associadas ao ID do monitoramento
    $sql = "SELECT n_nota FROM notas WHERE id_monitoramento = ? OR id_monitoramento2 = ? OR id_monitoramento3 = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $idMonitoramento, $idMonitoramento, $idMonitoramento);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $notasFiscais = [];

        // Adiciona todas as notas fiscais encontradas em um array
        while ($row = $result->fetch_assoc()) {
            $notasFiscais[] = $row['n_nota'];
        }

        if (count($notasFiscais) > 0) {
            echo json_encode(['success' => true, 'notasFiscais' => $notasFiscais]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Nenhuma nota fiscal encontrada.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao buscar as notas fiscais.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID do monitoramento não informado.']);
}
?>
