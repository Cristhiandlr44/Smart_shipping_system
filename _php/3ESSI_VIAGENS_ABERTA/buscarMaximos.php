<?php
require_once('../CRUD/relog.php'); // Incluindo a conexão com o banco de dados

header('Content-Type: application/json');

// Validação dos parâmetros enviados
if (!isset($_GET['notaFiscal']) || !isset($_GET['item'])) {
    echo json_encode(['success' => false, 'message' => 'Parâmetros inválidos ou ausentes.']);
    exit;
}

// Dados enviados pelo AJAX
$notaFiscal = $_GET['notaFiscal'];
$item = $_GET['item'];

// Verificando se os dados não estão vazios
if (empty($notaFiscal) || empty($item)) {
    echo json_encode(['success' => false, 'message' => 'Nota fiscal ou item não pode estar vazio.']);
    exit;
}

// Verificando a conexão com o banco de dados
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Erro de conexão com o banco de dados: ' . $conn->connect_error]);
    exit;
}

// Consulta ao banco de dados
$sql = "SELECT QuantAux AS maxQuantidade, quantidade AS maxPeso 
        FROM produtos 
        WHERE nf = ? AND cod = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Erro ao preparar a query: ' . $conn->error]);
    exit;
}

$stmt->bind_param("ss", $notaFiscal, $item);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode([
        'success' => true,
        'maxQuantidade' => $row['maxQuantidade'],
        'maxPeso' => $row['maxPeso']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Item não encontrado.']);
}

$stmt->close();
$conn->close();
?>
