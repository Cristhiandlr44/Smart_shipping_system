<?php
require_once('../CRUD/relog.php'); // Ajuste o caminho conforme necessário

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Consulta para selecionar dados com id_monitoramento NULL
    $sql = "SELECT p.*, n.fornecedor, n.reentrega, n.data_lancamento, n.rota
    FROM produtos p
    INNER JOIN notas n ON p.nf = n.n_nota
    WHERE n.id_monitoramento is NULL AND p.item_alterado IS NULL
    ORDER BY p.nf ASC";

    
    // Preparar a consulta SQL
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        if ($stmt->execute()) {
            // Pega o resultado
            $result = $stmt->get_result();
            $cliente = $result->fetch_all(MYSQLI_ASSOC);

            // Retorna o resultado como JSON
            echo json_encode($cliente);
        } else {
            // Caso haja erro ao executar a consulta
            echo json_encode(['erro' => 'Erro ao executar a consulta.']);
        }
        $stmt->close();
    } else {
        // Caso haja erro ao preparar a consulta
        echo json_encode(['erro' => 'Erro ao preparar a consulta SQL.']);
    }

    // Fecha a conexão
    $conn->close();

} else {
    // Caso o método não seja POST
    echo json_encode(['erro' => 'Método inválido.']);
}
?>
