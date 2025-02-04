<?php
require_once("../3SSI_CRUD/conexao.php");

header('Content-Type: application/json');  // Define o cabeçalho para retorno JSON

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Recebendo os dados da requisição POST
        $dataLancamento = isset($_POST['dataLancamento']) ? $_POST['dataLancamento'] : '';
        $placa = isset($_POST['placa']) ? $_POST['placa'] : '';
        $id_monitoramento = isset($_POST['id_monitoramento']) ? $_POST['id_monitoramento'] : '';
        
        // Verificação dos dados recebidos (para depuração, caso necessário)
        // Remova ou comente a linha abaixo em produção
        // var_dump($placa, $id_monitoramento, $dataLancamento); 

        // Validação simples dos dados
        if (empty($placa) || empty($dataLancamento) || empty($id_monitoramento)) {
            echo json_encode(["error" => "Dados incompletos."]);
            exit;
        }

        // SQL para buscar os dados, incluindo o campo reentrega
        $sql = "SELECT
            n.id_monitoramento,
            m.placa_caminhao,
            p.cod,
            nc.sequencia,
            p.descricao,
            p.nf,
            ROUND(SUM(p.quantidade), 2) AS Peso,
            SUM(p.QuantAux) AS quantidade,
            p.data_producao,
            p.data_validade,
            MAX(n.fornecedor) AS fornecedor,
            CASE
                WHEN n.reentrega = 'S' THEN 'S'
                ELSE 'N'  -- Para o caso de ser NULL ou outro valor
            END AS reentrega
        FROM produtos p
        LEFT JOIN notas n ON p.nf = n.n_nota AND n.id_monitoramento = :id_monitoramento
        LEFT JOIN monitoramento m ON n.id_monitoramento = m.id
        LEFT JOIN cruzeiro_notas nc ON n.n_nota = nc.fk_notas_n_nota
        WHERE m.placa_caminhao = :placa
        AND m.largada = :dataLancamento
        AND n.id_monitoramento = :id_monitoramento
        GROUP BY p.cod, p.data_producao, p.data_validade, n.id_monitoramento, n.reentrega
        ORDER BY n.reentrega DESC, p.cod ASC, p.nf ASC;";


        // Preparando a consulta e vinculando os parâmetros
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':placa', $placa, PDO::PARAM_STR);
        $stmt->bindValue(':id_monitoramento', $id_monitoramento, PDO::PARAM_INT);
        $stmt->bindParam(':dataLancamento', $dataLancamento, PDO::PARAM_STR);
        
        // Executando a consulta
        if ($stmt->execute()) {
            $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Verificar se há produtos retornados
            if (count($produtos) > 0) {
                // Agrupar os resultados por tipo de reentrega
                $produtosAgrupados = ['armazem' => [], 'normais' => []];

                foreach ($produtos as $produto) {
                    // Separar os itens de reentrega (S) e itens normais (N)
                    if ($produto['reentrega'] === 'S') {
                        $produtosAgrupados['armazem'][] = $produto;
                    } else {
                        $produtosAgrupados['normais'][] = $produto;
                    }
                }

                // Retorna os dados agrupados como JSON
                echo json_encode($produtosAgrupados);
            } else {
                // Caso não tenha nenhum produto, retorne uma mensagem de "none"
                echo json_encode(["message" => "none"]);
            }
        } else {
            // Se não conseguir executar a consulta, retorne uma mensagem de erro
            echo json_encode(["error" => "Erro ao executar a consulta."]);
            // Log de erro para ajudar na depuração
            error_log("Erro na execução: " . $stmt->errorInfo());
        }
    } else {
        echo json_encode(["error" => "Método de requisição inválido."]);
    }
} catch (PDOException $e) {
    // Se ocorrer um erro na execução da consulta ou na conexão, retorne o erro com o código
    echo json_encode(["error" => "Erro ao processar a requisição: " . $e->getMessage()]);
    error_log("Erro na conexão ou execução da consulta: " . $e->getMessage());
}

// Fechar a conexão
$pdo = null;
?>
