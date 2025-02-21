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

    $sql = "SELECT 
                n.id_monitoramento,
                n.data_lancamento,
                m.placa_caminhao,
                p.cod,
                p.descricao,
                MAX(p.nf) AS nf,
                ROUND(SUM(CASE WHEN p.item_alterado IS NULL THEN p.quantidade ELSE 0 END), 2) AS Peso,
                SUM(CASE WHEN p.item_alterado IS NULL THEN p.QuantAux ELSE 0 END) AS quantidade,
                p.data_producao,
                p.data_validade,
                MAX(p.UnidadeAuxiliar) AS UnidadeAuxiliar,
                MAX(p.item_alterado) AS item_alterado,
                MAX(n.fornecedor) AS fornecedor,
                CASE 
                    WHEN n.reentrega = 'S' THEN 'S'
                    ELSE 'N'
                END AS reentrega
            FROM produtos p
            LEFT JOIN notas n ON p.nf = n.n_nota AND n.id_monitoramento = ?
            LEFT JOIN monitoramento m ON n.id_monitoramento = m.id
            WHERE m.placa_caminhao = ? 
            AND m.largada = ? 
            AND n.id_monitoramento = ?
            GROUP BY p.cod, p.data_producao, p.data_validade, n.id_monitoramento, n.reentrega, item_alterado, p.descricao, n.data_lancamento
            ORDER BY fornecedor, p.cod ASC, nf ASC, n.reentrega DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $id_monitoramento, $placa, $dataLancamento, $id_monitoramento);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $produtos = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($produtos);
    } else {
        echo json_encode(['erro' => 'Erro ao buscar os dados no banco.']);
    }
} else {
    echo json_encode(['erro' => 'Método inválido.']);
}
