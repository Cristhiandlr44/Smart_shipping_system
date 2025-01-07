<?php
require_once("../3SSI_CRUD/conexao.php");
header('Content-Type: application/json; charset=utf-8'); // Retorno como JSON

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['error' => 'Método não permitido']);
    exit;
}

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "
        SELECT 
            rota,
            COUNT(*) AS quantidadeNotas,
            COUNT(DISTINCT Cliente) AS quantidadeEntregas,
            SUM(peso_bruto) AS pesoTotal
        FROM notas 
        WHERE 
            disponivel = 'S'
        GROUP BY rota
    ";


    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$dados) {
        echo json_encode([]); // Retorna vazio se não houver dados
    } else {
        echo json_encode($dados);
    }
} catch (Exception $e) {
    http_response_code(500); // Erro de servidor
    echo json_encode(['error' => $e->getMessage()]);
}
