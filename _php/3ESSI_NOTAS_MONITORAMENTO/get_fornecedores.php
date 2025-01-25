<?php
require_once("../3SSI_CRUD/conexao.php");

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Consulta as rotas existentes no banco
        $sql = "SELECT DISTINCT fornecedor FROM notas";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        // Retorna as rotas em formato JSON
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
