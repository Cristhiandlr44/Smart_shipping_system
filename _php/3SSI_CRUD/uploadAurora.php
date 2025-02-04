<?php
require_once('conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $sqlAurora = "UPDATE PRODUTOS SET data_producao = ?, data_validade = ? WHERE nf = ? AND cod = ?" ;
    $sqlAlteracao = "UPDATE PRODUTOS SET item_alterado = 'S' WHERE nf = ? AND cod = ?";

    $sqlInsert = "INSERT INTO produtos (cod, descricao, nf, quantidade, unidade, QuantAux, data_producao, data_validade) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $sqlInsertRg = "INSERT INTO produtos (cod, descricao, nf, quantidade, unidade, QuantAux, data_producao, data_validade,cod_especifico) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?,?)";
    $sqlCheck = "SELECT COUNT(*) FROM produtos WHERE cod = ? AND nf = ?";

    $codigosUsados = []; // Array para rastrear os códigos usados por NF e código base
    $produtosArray = []; // Array para armazenar os dados da planilha


    function converterData($data) {
        $meses = ["JAN" => "01", "FEB" => "02", "MAR" => "03", "APR" => "04", "MAY" => "05", "JUN" => "06", 
                  "JUL" => "07", "AUG" => "08", "SEP" => "09", "OCT" => "10", "NOV" => "11", "DEC" => "12"];
    
        // Caso 1: Data no formato 12-MAY-24
        if (preg_match('/^(\d{2})-([A-Z]{3})-(\d{2})$/', $data, $matches)) {
            return "20" . $matches[3] . "-" . $meses[$matches[2]] . "-" . $matches[1];
        }
        
        // Caso 2: Data no formato inteiro (série do Excel)
        if (is_numeric($data)) {
            $baseDate = new DateTime('1899-12-30');
            $baseDate->modify("+$data days");
            return $baseDate->format('Y-m-d');
        }
        
        // Caso 3: Se não for reconhecido, retorna NULL
        return NULL;
    }

    // Passo 1: Carregar os dados da planilha no array
    foreach ($data as $row) {
        $dataProducao = converterData($row['DT_FABR'] ?? '');
        $dataVencimento = converterData($row['DT_VENC'] ?? '');
        $nf = $row['NRO_NFSA'] ?? '';
        $codBase = $row['CD_ITEM'] ?? '';
        $descricaoItem = $row['DESCR_ITEM'] ?? '';
        $pesoVariavel = $row['NRO_RG_CX1'] ?? '';
        $quantidade = $row['QTDE'] ?? 0;
        $quantAux = $row['QTDE_EMB'] ?? 0;

   

        if (!empty($dataProducao) && !empty($dataVencimento) && !empty($nf) && !empty($codBase)) {
            // Inicializa o array para a NF, se necessário
            if (!isset($codigosUsados[$nf])) {
                $codigosUsados[$nf] = [];
            }

            // Inicializa o contador para o código base, se necessário
            if (!isset($codigosUsados[$nf][$codBase])) {
                $codigosUsados[$nf][$codBase] = 0;
            }

            // Adiciona os itens ao array para posterior processamento
            $produtosArray[$nf][$codBase][] = [
                'descricaoItem' => $descricaoItem,
                'dataProducao' => $dataProducao,
                'dataVencimento' => $dataVencimento,
                'quantidade' => $quantidade,
                'quantAux' => $quantAux,
                'pesoVariavel' => $pesoVariavel
            ];

            // Se o item tem peso variável, processa-o
            if (!empty($pesoVariavel)) {
                preg_match_all('/(\d+)\s*\(([\d,]+)\)/', $pesoVariavel, $matches);
                
                foreach ($matches[0] as $par) {
                    if (preg_match('/(\d+)\s*\(([\d,]+)\)/', trim($par), $match)) {
                        $rg = $match[1]; // RG
                        $peso = str_replace(',', '.', $match[2]); // Peso
                        $cod = $codBase . "-" . $rg; // Código com RG

                        // Verifica se o item com o mesmo código e NF já existe no banco
                        $stmtCheck = $pdo->prepare($sqlCheck);
                        $stmtCheck->execute([$cod, $nf]);
                        $existe = $stmtCheck->fetchColumn();

                        // Se não existe, insere a nova linha
                        if ($existe == 0) {
                            $paramsInsertRg = [
                                $cod,
                                $descricaoItem,
                                $nf,
                                $peso,
                                'kg',
                                '1',
                                $dataProducao,
                                $dataVencimento,
                                $rg
                            ];

                            $stmtInsertRg = $pdo->prepare($sqlInsertRg);
                            $stmtInsertRg->execute($paramsInsertRg);

                            $paramsUpdateAlteracao = [
                                $nf,
                                $codBase,
                            ];

                            $stmtUpdateAlteracao = $pdo->prepare($sqlAlteracao);
                            $stmtUpdateAlteracao->execute($paramsUpdateAlteracao);
                            
                        } else {
                            var_dump("Linha com RG já existe: Código = $cod, NF = $nf");
                        }
                    } else {
                        var_dump("Formato inválido para RG e Peso: $par");
                    }
                }
            }
        }
    }
    // Passo 2: Verificar repetições e inserir dados
    foreach ($produtosArray as $nf => $itens) {
        foreach ($itens as $codBase => $detalhes) {
            // Remover a var_dump() desnecessária
    
            if (count($detalhes) > 1) { // Verifica se há repetição de código base na mesma NF
                $sequencial = 1; // Contador para gerar códigos únicos para repetidos
    
                foreach ($detalhes as $row) {
                    $cod = $codBase . "-" . substr($nf, -3) . "-" . $sequencial; // Gera o código único para cada repetição
                    
                    // Use as datas de cada item dentro do loop
                    $dataProducao = $row['dataProducao'];
                    $dataVencimento = $row['dataVencimento'];
    
                    // Insere o item no banco
                    $paramsInsert = [
                        $cod,
                        $row['descricaoItem'], 
                        $nf,
                        $row['quantidade'], 
                        'kg',
                        $row['quantAux'],
                        $dataProducao,
                        $dataVencimento
                    ];
    
                    $stmtInsert = $pdo->prepare($sqlInsert);
                    $stmtInsert->execute($paramsInsert);
                    $sequencial++; // Incrementa o sequencial
    
                    // Atualiza a alteração
                    $paramsUpdateAlteracao = [
                        $nf,
                        $codBase,
                    ];
    
                    $stmtUpdateAlteracao = $pdo->prepare($sqlAlteracao);
                    $stmtUpdateAlteracao->execute($paramsUpdateAlteracao);
                }
            } else {
                // Para itens não repetidos, deve-se pegar os valores da data do item correto
                $row = reset($detalhes);  // Pega o primeiro item do array (não repetido)
                $dataProducao = $row['dataProducao'];
                $dataVencimento = $row['dataVencimento'];
    
                $stmtUpdate = $pdo->prepare($sqlAurora);
                $stmtUpdate->execute([$dataProducao, $dataVencimento, $nf, $codBase]);
            }
        }
    }
    

    print("<p style='color: green;'>Dados da Aurora foram processados e inseridos com sucesso no banco de dados!</p>");
} else {
    print("<p style='color: red;'>Nenhum arquivo enviado.</p>");
}

$pdo = null;
?>
