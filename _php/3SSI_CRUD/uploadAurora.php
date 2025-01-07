<?php
    require_once('conexao.php');
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        // Conectar ao banco de dados

        // Prepare a instrução SQL para inserção dos dados da Aurora
        $sqlAurora = "UPDATE PRODUTOS SET data_producao = ?, data_validade = ? WHERE nf = ? AND cod = ?";

        // Prepare a instrução SQL para inserção dos novos dados na tabela produtos
        $sqlInsert = "INSERT INTO produtos (cod, descricao, nf, quantidade, unidade, QuantAux, data_producao, data_validade) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        // Rastreador único para a sequência
        $numeroSequencia = 1;

        // Percorrer os dados do Excel da Aurora e inserir no banco de dados
        foreach ($data as $row) {
            $dataProducao = $row['Data produção'];
            $dataVencimento = $row['Data vencimento'];
            $nf = $row['Nota fiscal'];
            $cod = $row['Item'];
            $descricaoItem = $row['Descrição item'];
            $pesoLiquido = $row['Peso liquido kg'];
            $quantidade = 'kg'; // Valor fixo para 'kg'
            $unidade = '1'; // Valor fixo para '1'

            if (!empty($dataProducao) && !empty($dataVencimento) && !empty($nf) && !empty($cod)) {
                // Adicione a sequência ao número do item se for igual a 2895
                if ($cod == 2895) {
                    $cod .= sprintf('-%02d', $numeroSequencia);

                    // Inserir novos dados na tabela produtos
                    $paramsInsert = [
                        $cod,
                        $descricaoItem,
                        $nf,
                        $pesoLiquido,
                        $quantidade,
                        $unidade,
                        $dataProducao,
                        $dataVencimento
                    ];

                    $stmtInsert = $pdo->prepare($sqlInsert);
                    $stmtInsert->execute($paramsInsert);

                    // Incrementar a sequência
                    $numeroSequencia++;
                }

                // Preparar os parâmetros para a atualização
                $params = [
                    $dataProducao,
                    $dataVencimento,
                    $nf,
                    $cod
                ];
                
                $stmt = $pdo->prepare($sqlAurora);
                $stmt->execute($params);
            }
        }
        // Responda com uma mensagem de confirmação
        print("<p style='color: green;'>Dados da Aurora foram inseridos com sucesso no banco de dados!</p>");
    } else {
        print("<p style='color: red;'>Nenhum arquivo enviado.</p>");
    }
    $pdo = null;
?>
