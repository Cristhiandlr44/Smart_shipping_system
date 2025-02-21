<?php
require_once('../CRUD/relog.php'); // Ajuste o caminho conforme necessário

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $placa = $_POST['placa'] ?? '';
    $dataLancamento = $_POST['largada'] ?? '';
    $id_monitoramento = $_POST['id_monitoramento'] ?? '';

    if (empty($placa) || empty($dataLancamento) || empty($id_monitoramento)) {
        echo json_encode(['erro' => 'Dados incompletos.']);
        exit;
    }

    $sql = "SELECT p.*, n.fornecedor, n.reentrega, n.data_lancamento
            FROM produtos p
            INNER JOIN notas n ON p.nf = n.n_nota
            WHERE n.id_monitoramento = ? AND p.item_alterado IS NULL
            ORDER BY p.nf ASC";

    // Prepara e executa a consulta dos produtos
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $id_monitoramento);  

    // Verifica se a consulta foi executada com sucesso
    if ($stmt->execute()) {
        // Obtém o resultado dos produtos
        $result = $stmt->get_result();
        $produtos = $result->fetch_all(MYSQLI_ASSOC);
    
        echo json_encode($produtos  );
    } else {
        echo json_encode(['erro' => 'Erro ao buscar os dados no banco.']);
    }
} else {
    echo json_encode(['erro' => 'Método inválido.']);
}
