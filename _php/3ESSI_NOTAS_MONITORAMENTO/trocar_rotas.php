<?php
require_once("../3SSI_CRUD/conexao.php");

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['notas']) || empty($data['rota'])) {
        echo json_encode(['error' => 'Dados inválidos.']);
        exit;
    }

    $notas = $data['notas'];
    $novaRota = $data['rota'];

    $placeholders = implode(',', array_fill(0, count($notas), '?'));
    $sql = "UPDATE notas SET rota = ? WHERE n_nota IN ($placeholders)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_merge([$novaRota], $notas));

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
