<?php
require_once("../3SSI_CRUD/conexao.php");
header('Content-Type: application/json; charset=utf-8'); // Retorno como JSON

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['error' => 'Método não permitido']);
    exit;
}

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para as rotas normais (não são da rede)
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
            AND (n.cidade != 'Montes Claros' OR c.tipo != 'rede')  -- Exclui clientes de Montes Claros com tipo 'rede'
        GROUP BY 
            n.rota
    ";
    
    // Consulta para as rotas das redes
    $sqlRotasRedes = "
            SELECT 
               
                c.nome AS Cliente,
                COUNT(*) AS quantidadeNotas,
                COUNT(DISTINCT n.n_nota) AS quantidadeEntregas,
                SUM(n.peso_bruto) AS pesoTotal
            FROM 
                notas n
            JOIN 
                clientes c ON n.CNPJ = c.CNPJ
            WHERE 
                n.disponivel = 'S'
                AND n.cidade = 'Montes Claros'
                AND c.tipo = 'rede'  -- Considera apenas clientes de tipo 'rede'
            GROUP BY 
                c.nome  -- Agrupa pelas informações dos clientes (nome)
        ";


    // Preparando e executando a consulta para as rotas normais
    $stmtRotas = $pdo->prepare($sqlRotas);
    $stmtRotas->execute();
    $rotas = $stmtRotas->fetchAll(PDO::FETCH_ASSOC);

    // Preparando e executando a consulta para as rotas das redes
    $stmtRotasRedes = $pdo->prepare($sqlRotasRedes);
    $stmtRotasRedes->execute();
    $rotasRedes = $stmtRotasRedes->fetchAll(PDO::FETCH_ASSOC);

  

    // Preparando os dados de resposta
    $response = [
        'rotas' => $rotas,  // Rotas normais
        'rotasRedes' => $rotasRedes  // Rotas das redes
    ];
    array_walk_recursive($response, function (&$item) {
        if (!mb_check_encoding($item, 'UTF-8')) {
            $item = utf8_encode($item);  // Converte para UTF-8
        }
    });
    
  
    $jsonResponse = json_encode($response);
    if ($jsonResponse === false) {
        echo json_encode(['error' => 'Erro ao codificar os dados em JSON']);
    } else {
        echo $jsonResponse;
    }

} catch (Exception $e) {
    http_response_code(500); // Erro de servidor
    echo json_encode(['error' => $e->getMessage()]);
}
?>
