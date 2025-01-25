<?php
require_once("../3SSI_CRUD/conexao.php");

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $notaId = $data['notaId'] ?? null;
        $novaRota = $data['rota'] ?? null;

        if (!$notaId || !$novaRota) {
            echo json_encode(['error' => 'Nota e nova rota são obrigatórios']);
            exit;
        }

        // Atualiza a rota da nota
        $sql = "UPDATE notas SET rota = :novaRota WHERE n_nota = :notaId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['novaRota' => $novaRota, 'notaId' => $notaId]);

        echo json_encode(['success' => true]);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
