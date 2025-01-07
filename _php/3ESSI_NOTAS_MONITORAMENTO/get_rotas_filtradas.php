<?php
require_once("../3SSI_CRUD/conexao.php");
header('Content-Type: application/json; charset=utf-8'); // Retorno como JSON

// Valida se o parâmetro 'fornecedores' foi passado na URL
$fornecedores = isset($_GET['fornecedores']) ? $_GET['fornecedores'] : null;

// Verifica se o método de requisição é GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['error' => 'Método não permitido']);
    exit;
}

// Verifica se o parâmetro 'fornecedores' foi passado e é válido
if (empty($fornecedores)) {
    echo json_encode(['error' => 'Fornecedor não informado']);
    exit;
}

// Converte a string de fornecedores separada por vírgulas em um array
$fornecedoresArray = explode(',', $fornecedores);

// Verifica se ao menos um fornecedor foi passado
if (empty($fornecedoresArray)) {
    echo json_encode(['error' => 'Nenhum fornecedor válido']);
    exit;
}

try {
    // Configura o modo de erro do PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta SQL para buscar rotas e dados associados com múltiplos fornecedores
    $sql = "
        SELECT 
            rota,
            COUNT(*) AS quantidadeNotas,
            COUNT(DISTINCT Cliente) AS quantidadeEntregas,
            SUM(peso_bruto) AS pesoTotal
        FROM notas 
        WHERE 
            disponivel = 'S' AND fornecedor IN (" . implode(',', array_fill(0, count($fornecedoresArray), '?')) . ")
        GROUP BY rota
    ";

    // Prepara e executa a consulta com os parâmetros dos fornecedores
    $stmt = $pdo->prepare($sql);
    $stmt->execute($fornecedoresArray);

    // Recupera os dados da consulta
    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retorna os dados ou um array vazio caso não haja dados
    echo json_encode($dados);

} catch (Exception $e) {
    // Caso ocorra um erro, retorna código 500 e a mensagem do erro
    http_response_code(500); // Erro de servidor
    echo json_encode(['error' => $e->getMessage()]);
}
?>
