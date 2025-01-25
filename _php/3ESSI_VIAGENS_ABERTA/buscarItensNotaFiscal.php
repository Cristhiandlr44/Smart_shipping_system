<?php
require_once('../CRUD/relog.php'); // Incluindo a conexão com o banco de dados

header('Content-Type: application/json');

// Validação da nota fiscal
if (!isset($_GET['notaFiscal'])) {
    echo json_encode(['success' => false, 'message' => 'Parâmetro notaFiscal ausente.']);
    exit;
}

$notaFiscal = $_GET['notaFiscal'];

// Consulta aos itens da nota fiscal
$sql = "SELECT cod, QuantAux, quantidade, unidade 
        FROM produtos 
        WHERE nf = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $notaFiscal);
$stmt->execute();
$result = $stmt->get_result();

// Verificando se há itens para a nota fiscal
if ($result->num_rows > 0) {
    $itens = [];
    while ($row = $result->fetch_assoc()) {
        $itens[] = [
            'cod' => $row['cod'],
            'quantidade' => $row['QuantAux'],
            'peso' => $row['quantidade'],
            'unidade' => $row['unidade']
        ];
    }
    echo json_encode(['success' => true, 'itens' => $itens]);
} else {
    echo json_encode(['success' => false, 'message' => 'Nenhum item encontrado para esta nota fiscal.']);
}

$stmt->close();
$conn->close();
?>
