<?php
require_once("../3SSI_CRUD/conexao.php");
header('Content-Type: application/json; charset=utf-8'); // Retorno como JSON

// Verifica se o método de requisição é GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['error' => 'Método não permitido']);
    exit;
}

// Valida se o parâmetro 'fornecedores' foi passado
$fornecedores = isset($_GET['fornecedores']) ? $_GET['fornecedores'] : null;

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
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para as rotas dos fornecedores
    $sqlRotas = "
        SELECT 
            n.rota,
            COUNT(*) AS quantidadeNotas,
            COUNT(DISTINCT c.nome) AS quantidadeEntregas,
            SUM(n.peso_bruto) AS pesoTotal
        FROM 
            notas n
        JOIN 
            clientes c ON n.CNPJ = c.CNPJ
        WHERE 
            n.disponivel = 'S' 
            AND n.fornecedor IN (" . implode(',', array_fill(0, count($fornecedoresArray), '?')) . ")
        GROUP BY 
            n.rota
    ";

    // Consulta para as rotas das redes
    $sqlRotasRedes = "
        SELECT 
          
            c.nome AS Cliente,
            COUNT(*) AS quantidadeNotas,
            COUNT(DISTINCT c.nome) AS quantidadeEntregas,
            SUM(n.peso_bruto) AS pesoTotal
        FROM 
            notas n
        JOIN 
            clientes c ON n.CNPJ = c.CNPJ
        WHERE 
            n.disponivel = 'S'
            AND n.cidade = 'Montes Claros'
            AND c.tipo = 'rede'  -- Considera apenas clientes de tipo 'rede'
            AND n.fornecedor IN (" . implode(',', array_fill(0, count($fornecedoresArray), '?')) . ")

        GROUP BY 
            c.nome  -- Agrupa pelas informações dos clientes (nome)
    ";

    // Preparando e executando a consulta para as rotas dos fornecedores
    $stmtRotas = $pdo->prepare($sqlRotas);
    $stmtRotas->execute($fornecedoresArray);
    $rotas = $stmtRotas->fetchAll(PDO::FETCH_ASSOC);

    // Preparando e executando a consulta para as rotas das redes
    $stmtRotasRedes = $pdo->prepare($sqlRotasRedes);
    $stmtRotasRedes->execute($fornecedoresArray);
    $rotasRedes = $stmtRotasRedes->fetchAll(PDO::FETCH_ASSOC);

    // Preparando os dados de resposta
    $response = [
        'rotas' => $rotas,  // Rotas dos fornecedores
        'rotasRedes' => $rotasRedes  // Rotas das redes
    ];
    array_walk_recursive($response, function (&$item) {
        if (!mb_check_encoding($item, 'UTF-8')) {
            $item = utf8_encode($item);  // Converte para UTF-8
        }
    });
    // Retorna os dados em formato JSON
    echo json_encode($response);

} catch (Exception $e) {
    // Caso ocorra um erro, retorna código 500 e a mensagem do erro
    http_response_code(500); // Erro de servidor
    echo json_encode(['error' => $e->getMessage()]);
}
?>
