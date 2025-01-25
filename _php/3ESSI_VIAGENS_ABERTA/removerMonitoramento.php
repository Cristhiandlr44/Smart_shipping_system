<?php
// Conectar ao banco de dados (adapte conforme necessário)
require_once('../CRUD/relog.php'); // Incluindo a conexão com o banco de dados

if (isset($_POST['notaFiscal'])) {
    $notaFiscal = $_POST['notaFiscal'];

    // Remover o Id_monitoramento da tabela notas
    $sql = "UPDATE notas SET reentrega = 'S', disponivel = 'S' WHERE n_nota = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $notaFiscal);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Nota fiscal não informada.']);
}
?>
