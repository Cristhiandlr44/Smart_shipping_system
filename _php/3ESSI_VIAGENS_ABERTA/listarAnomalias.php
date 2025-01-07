<?php
// Conectar ao banco de dados
require_once('../CRUD/relog.php'); // Incluindo a conexão com o banco de dados

// Verifica se o número da nota fiscal foi enviado
if (isset($_GET['notaFiscal'])) {
    $notaFiscal = $_GET['notaFiscal'];

    // Consulta para buscar as anomalias relacionadas à nota fiscal
    $sql = "SELECT * FROM anomalias WHERE nf = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $notaFiscal);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $anomalias = [];

        while ($row = $result->fetch_assoc()) {
            $anomalias[] = $row;
        }

        echo json_encode(['success' => true, 'anomalias' => $anomalias]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao buscar as anomalias.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Nota fiscal não informada.']);
}
?>
