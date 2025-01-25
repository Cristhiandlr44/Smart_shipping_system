<?php
require_once("../3SSI_CRUD/conexao.php");

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['notaId']) || empty($data['rota'])) {
        echo json_encode(['error' => 'Dados inválidos.']);
        exit;
    }

    $notaId = $data['notaId'];
    $novaRota = $data['rota'];

    // Atualiza a rota de uma única nota
    $sql = "UPDATE notas SET rota = ? WHERE n_nota = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$novaRota, $notaId]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
