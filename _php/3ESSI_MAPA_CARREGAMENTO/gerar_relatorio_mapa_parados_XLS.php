<?php
require_once('../CRUD/relog.php'); // Ajuste o caminho conforme necessário

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Verifica se a conexão com o banco foi estabelecida corretamente
    if (!isset($conn) || $conn->connect_error) {
        echo json_encode(['erro' => 'Falha na conexão com o banco de dados.']);
        exit;
    }

    $sql = "SELECT 
                n.id_monitoramento,
                n.data_lancamento,
                m.placa_caminhao,
                p.cod,
                p.descricao,
                MAX(p.nf) AS nf,
                MAX(n.rota) AS rota,
                ROUND(SUM(CASE WHEN p.item_alterado IS NULL THEN p.quantidade ELSE 0 END), 2) AS Peso,
                SUM(CASE WHEN p.item_alterado IS NULL THEN p.QuantAux ELSE 0 END) AS quantidade,
                p.data_producao,
                p.data_validade,
                MAX(p.UnidadeAuxiliar) AS UnidadeAuxiliar,
                p.item_alterado,
                MAX(n.fornecedor) AS fornecedor,
                CASE 
                    WHEN n.reentrega = 'S' THEN 'S'
                    ELSE 'N'
                END AS reentrega,
                n.rota  -- Agrupamento por rota
            FROM produtos p
            LEFT JOIN notas n ON p.nf = n.n_nota
            LEFT JOIN monitoramento m ON n.id_monitoramento = m.id
            WHERE n.id_monitoramento IS NULL  -- Filtra apenas onde id_monitoramento é NULL
            GROUP BY n.rota, p.cod, p.data_producao, p.data_validade, 
                     n.id_monitoramento, n.reentrega, p.item_alterado, 
                     p.descricao, n.data_lancamento, m.placa_caminhao
            ORDER BY n.rota, n.fornecedor, p.cod ASC, p.nf ASC, n.reentrega DESC";

    // Prepara a consulta
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $produtos = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($produtos);
        } else {
            echo json_encode(['erro' => 'Erro ao executar a consulta.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['erro' => 'Erro ao preparar a consulta SQL.']);
    }

    // Fecha a conexão
    $conn->close();

} else {
    echo json_encode(['erro' => 'Método inválido.']);
}
