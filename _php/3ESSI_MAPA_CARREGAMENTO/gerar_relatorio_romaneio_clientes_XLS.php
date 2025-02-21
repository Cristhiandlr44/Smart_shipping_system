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

    $sqlCliente = "SELECT nome, rua, numero, c.bairro, c.cidade, n.n_nota, n.peso_bruto, n.valor_nota
    FROM clientes c
    INNER JOIN notas n ON c.cnpj = n.cnpj
    WHERE n.id_monitoramento = ?";
    $stmtCliente = $conn->prepare($sqlCliente);
    $stmtCliente->bind_param('s', $id_monitoramento);
    $stmtCliente->execute();
    

    if ($stmtCliente->execute()) {
        $resultCliente = $stmtCliente->get_result();
        $clientes = $resultCliente->fetch_all(MYSQLI_ASSOC);
        echo json_encode($clientes);
    } else {
        echo json_encode(['erro' => 'Erro ao buscar os dados no banco.']);
    }
} else {
    echo json_encode(['erro' => 'Método inválido.']);
}
