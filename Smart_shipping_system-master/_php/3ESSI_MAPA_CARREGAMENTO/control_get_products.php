<?php
require_once("../3SSI_CRUD/conexao.php");

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $dataLancamento = isset($_POST['dataLancamento']) ? $_POST['dataLancamento'] : '';
        $sql = "SELECT
        n.id_monitoramento,
        m.placa_caminhao,
        p.cod,
        nc.sequencia,
        MAX(p.descricao) as descricao,
        MAX(p.nf) as nf,
        ROUND(SUM(p.quantidade),2) as Peso,
        SUM(P.QuantAux) as quantidade,
        MAX(p.data_producao) as data_producao,
        MAX(p.data_validade) as data_validade,
        MAX(n.fornecedor) as fornecedor
        FROM produtos p
        LEFT JOIN notas n ON p.nf = n.n_nota
        LEFT JOIN monitoramento m  ON n.id_monitoramento = m.id
        LEFT JOIN cruzeiro_notas nc ON n.n_nota = nc.fk_notas_n_nota
        WHERE n.id_monitoramento IS NOT NULL
        AND m.largada = :dataLancamento
        GROUP BY p.cod, n.id_monitoramento  
        ORDER BY `nf` ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':dataLancamento', $dataLancamento);
        $stmt->execute();
        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json'); // Defina o cabeçalho para JSON

        if (count($produtos) > 0) {
            // Agrupe os resultados por id_monitoramento
            $produtosAgrupados = [];
            foreach ($produtos as $produto) {
                $id_monitoramento = $produto['id_monitoramento'];
                unset($produto['id_monitoramento']); // Remova o id_monitoramento do item individual
                $produtosAgrupados[$id_monitoramento][] = $produto;
            }

            echo json_encode($produtosAgrupados);
        } else {
            echo json_encode(["message" => "none"]); // Se não houver dados, retorne um JSON indicando isso
        }
        
        // exit; // Adicione esta linha para evitar qualquer código adicional
    }
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]); // Se houver um erro, retorne um JSON com a mensagem de erro
}

$pdo = null;
?>