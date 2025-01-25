<?php
// Configurações de conexão com o banco de dados
require_once('../CRUD/relog.php'); // Incluindo a conexão com o banco de dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nf = $_POST['nf'];  // Nota Fiscal

    if ($nf > 0) {
        $sql = "DELETE FROM anomalias WHERE nf = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nf);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao excluir no banco de dados.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'ID inválido.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método de requisição inválido.']);
}
?>
