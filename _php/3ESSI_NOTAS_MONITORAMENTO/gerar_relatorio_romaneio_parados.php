<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start(); // Inicia o buffer de saída

require_once('../CRUD/relog.php');
require('../../fpdf/fpdf.php');  // Caminho correto para o FPDF

// Verifica se é um método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $sql = "SELECT p.*, n.fornecedor, n.reentrega, n.data_lancamento, n.rota
            FROM produtos p
            INNER JOIN notas n ON p.nf = n.n_nota
            WHERE n.id_monitoramento is NULL AND p.item_alterado IS NULL
            ORDER BY p.nf ASC";

    // Prepara e executa a consulta dos produtos
    $stmt = $conn->prepare($sql);

    // Verifica se a consulta foi executada com sucesso
    if ($stmt->execute()) {
        // Obtém o resultado dos produtos
        $result = $stmt->get_result();
        $produtos = $result->fetch_all(MYSQLI_ASSOC);

        // Verifica se há produtos
        if (count($produtos) > 0) {
            // Agrupar os resultados por tipo de reentrega e data de lançamento
            $produtosAgrupadosPorRota = [];

            foreach ($produtos as $produto) {
                // Verifica se a rota já existe, se não cria
                if (!isset($produtosAgrupadosPorRota[$produto['rota']])) {
                    $produtosAgrupadosPorRota[$produto['rota']] = ['armazem' => [], 'carga_parada' => [], 'normais' => []];
                }

                $dataLancamentoProduto = new DateTime($produto['data_lancamento']);
                $dataAtual = new DateTime();
                $intervalo = $dataLancamentoProduto->diff($dataAtual)->days;

                if ($intervalo >= 2) {
                    // Agrupando produtos como carga parada
                    $produtosAgrupadosPorRota[$produto['rota']]['carga_parada'][] = $produto;
                } elseif ($produto['reentrega'] === 'S') {
                    // Agrupando produtos como armazém (reentrega)
                    $produtosAgrupadosPorRota[$produto['rota']]['armazem'][] = $produto;
                } else {
                    // Agrupando produtos como normais
                    $produtosAgrupadosPorRota[$produto['rota']]['normais'][] = $produto;
                }
            }

            // Consulta os dados do cliente
            $sqlCliente = "SELECT nome, rua, numero, c.bairro, c.cidade, n.n_nota, n.peso_bruto, n.valor_nota, n.rota
                           FROM clientes c
                           INNER JOIN notas n ON c.cnpj = n.cnpj
                           WHERE n.id_monitoramento is NULL";
            $stmtCliente = $conn->prepare($sqlCliente);
            $stmtCliente->execute();
            $resultCliente = $stmtCliente->get_result();
            $clientes = $resultCliente->fetch_all(MYSQLI_ASSOC);

            // Cria o objeto PDF
            $pdf = new FPDF();
            $pdf->SetAutoPageBreak(true, 10);  // Habilitar quebra automática de página

            // Para cada rota, gerar a página com dados do cliente e produtos
            foreach ($produtosAgrupadosPorRota as $rota => $produtosAgrupados) {
                // Nova página para cada rota
                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 12);
                 // Cabeçalho principal
                 $pdf->SetFont('Arial', 'B', 14);
                 $pdf->Cell(0, 10, 'Mapa de Carregamento', 0, 1, 'C');
                 $pdf->Ln(5);
 
                 $pdf->SetFont('Arial', 'B', 12);
                 $pdf->Cell(50, 10, 'Placa:', 0, 0);
                 $pdf->SetFont('Arial', '', 12);
                 $pdf->Cell(0, 10, '', 0, 1);
                 
                 $pdf->SetFont('Arial', 'B', 12);
                 $pdf->Cell(50, 10, 'Id Monitoramento:', 0, 0);
                 $pdf->SetFont('Arial', '', 12);
                 $pdf->Cell(0, 10, '', 0, 1);
                 
                 $pdf->SetFont('Arial', 'B', 12);
                 $pdf->Cell(50, 10, "Rota: $rota", 0, 1);

                // Cálculo do peso e volume total por rota
                $totalPeso = 0;
                $totalVolume = 0;
                foreach ($produtosAgrupados as $categoria => $produtos) {
                    foreach ($produtos as $produto) {
                        $totalPeso += $produto['quantidade'];  // Somando as quantidades de peso
                        $totalVolume += $produto['QuantAux'];  // Somando as quantidades de volume
                    }
                }
                
                $pdf->Cell(0, 10, 'Peso Total: ' . number_format($totalPeso, 2), 0, 1);
                $pdf->Cell(0, 10, 'Volume Total: ' . number_format($totalVolume, 2), 0, 1);
                
                 $pdf->Ln(10); // Espaço para o início d

                // Exibe os dados dos clientes da rota atual
                $clientesRota = array_filter($clientes, function($cliente) use ($rota) {
                    return $cliente['rota'] == $rota;
                });

                if ($clientesRota) {
                    $pdf->Cell(0, 10, 'Dados dos Clientes', 0, 1, 'C');
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Cell(15, 6, 'NF', 1);   
                    $pdf->Cell(40, 6, 'Nome', 1);
                    $pdf->Cell(40, 6, 'Endereco', 1);
                    $pdf->Cell(12, 6, 'Numero', 1);
                    $pdf->Cell(30, 6, 'Bairro', 1);
                    $pdf->Cell(25, 6, 'Cidade', 1);
                    $pdf->Cell(15, 6, 'Peso', 1);
                    $pdf->Cell(15, 6, 'Valor Nf', 1);
                    $pdf->Ln();

                    // Preenche os dados dos clientes da rota
                    foreach ($clientesRota as $cliente) {
                        $pdf->Cell(15, 6, $cliente['n_nota'], 1);
                        $nome = $cliente['nome'];
                        if (strlen($nome) > 20) {
                            $nome = substr($nome, 0, 20);
                        }
                        $pdf->Cell(40, 6, $nome, 1);

                        $rua = $cliente['rua'];
                        if (strlen($rua) > 20) {
                            $rua = substr($rua, 0, 20);
                        }
                        $pdf->Cell(40, 6, $rua, 1);

                        $pdf->Cell(12, 6, $cliente['numero'], 1);

                        $bairro = strlen($cliente['bairro']) > 15 ? substr($cliente['bairro'], 0, 15) : $cliente['bairro'];
                        $pdf->Cell(30, 6, $bairro, 1);

                        $cidade = strlen($cliente['cidade']) > 13 ? substr($cliente['cidade'], 0, 17) : $cliente['cidade'];
                        $pdf->Cell(25, 6, $cidade, 1);

                        $pdf->Cell(15, 6, $cliente['peso_bruto'], 1);
                        $pdf->Cell(15, 6, $cliente['valor_nota'], 1);

                        $pdf->Ln();
                    }

                    $pdf->Ln(5); // Adiciona um espaçamento entre os dados dos clientes e os produtos
                }

                // Tabela de Produtos por Rota
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(0, 10, 'Produtos por Rota: ' . $rota, 0, 1, 'C');
                $pdf->Ln(5);

                // Tabela de Produtos Normais
                if (!empty($produtosAgrupados['normais'])) {
                    $pdf->SetFont('Arial', 'B', 12);
                    $pdf->Cell(0, 10, 'Entrega Regular', 0, 1, 'C');
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Cell(15, 6, 'Operacao', 1);
                    $pdf->Cell(15, 6, 'NF', 1);
                    $pdf->Cell(20, 6, 'Codigo', 1);
                    $pdf->Cell(55, 6, 'Descricao', 1);
                    $pdf->Cell(15, 6, 'Peso', 1);
                    $pdf->Cell(15, 6, 'Qnt', 1);
                    $pdf->Cell(10, 6, 'Tipo', 1);
                    $pdf->Cell(25, 6, 'Data Producao', 1);
                    $pdf->Cell(25, 6, 'Data Validade', 1);
                    $pdf->Ln();

                    // Preenche a tabela de Produtos Normais
                    foreach ($produtosAgrupados['normais'] as $produto) {
                        $operacao = $produto['fornecedor'];
                        $pdf->Cell(15, 6, $operacao, 1);
                        $pdf->Cell(15, 6, $produto['nf'], 1);
                        $pdf->Cell(20, 6, $produto['cod'], 1);
                        $descricao = $produto['descricao'];
                        if (strlen($descricao) > 28) {
                            $descricao = substr($descricao, 0, 28);
                        }
                        $pdf->Cell(55, 6, $descricao, 1);
                        $pdf->Cell(15, 6, number_format($produto['quantidade'], 2), 1);
                        $pdf->Cell(15, 6, $produto['QuantAux'], 1);
                        $pdf->Cell(10, 6, $produto['UnidadeAuxiliar'], 1);
                        $pdf->Cell(25, 6, $produto['data_producao'], 1);
                        $pdf->Cell(25, 6, $produto['data_validade'], 1);
                        $pdf->Ln();
                    }
                }

                // Tabela Galpão (Armazém)
                if (!empty($produtosAgrupados['armazem'])) {
                    $pdf->SetFont('Arial', 'B', 12);
                    $pdf->Cell(0, 10, 'Galpao (Armazem)', 0, 1, 'C');
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Cell(15, 6, 'Operacao', 1);
                    $pdf->Cell(15, 6, 'NF', 1);
                    $pdf->Cell(20, 6, 'Codigo', 1);
                    $pdf->Cell(55, 6, 'Descricao', 1);
                    $pdf->Cell(15, 6, 'Peso', 1);
                    $pdf->Cell(15, 6, 'Qnt', 1);
                    $pdf->Cell(10, 6, 'Tipo', 1);
                    $pdf->Cell(25, 6, 'Data Producao', 1);
                    $pdf->Cell(25, 6, 'Data Validade', 1);
                    $pdf->Ln();

                    // Preenche a tabela de Produtos no Galpão
                    foreach ($produtosAgrupados['armazem'] as $produto) {
                        $operacao = $produto['fornecedor'];
                        $pdf->Cell(15, 6, $operacao, 1);
                        $pdf->Cell(15, 6, $produto['nf'], 1);
                        $pdf->Cell(20, 6, $produto['cod'], 1);
                        $descricao = $produto['descricao'];
                        if (strlen($descricao) > 28) {
                            $descricao = substr($descricao, 0, 28);
                        }
                        $pdf->Cell(55, 6, $descricao, 1);
                        $pdf->Cell(15, 6, number_format($produto['quantidade'], 2), 1);
                        $pdf->Cell(15, 6, $produto['QuantAux'], 1);
                        $pdf->Cell(10, 6, $produto['UnidadeAuxiliar'], 1);
                        $pdf->Cell(25, 6, $produto['data_producao'], 1);
                        $pdf->Cell(25, 6, $produto['data_validade'], 1);
                        $pdf->Ln();
                    }
                }

                // Tabela de Carga Parada
                if (!empty($produtosAgrupados['carga_parada'])) {
                    $pdf->SetFont('Arial', 'B', 12);
                    $pdf->Cell(0, 10, 'Carga Parada', 0, 1, 'C');
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Cell(15, 6, 'Operacao', 1);
                    $pdf->Cell(15, 6, 'NF', 1);
                    $pdf->Cell(20, 6, 'Codigo', 1);
                    $pdf->Cell(55, 6, 'Descricao', 1);
                    $pdf->Cell(15, 6, 'Peso', 1);
                    $pdf->Cell(15, 6, 'Qnt', 1);
                    $pdf->Cell(10, 6, 'Tipo', 1);
                    $pdf->Cell(25, 6, 'Data Producao', 1);
                    $pdf->Cell(25, 6, 'Data Validade', 1);
                    $pdf->Ln();

                    // Preenche a tabela de Carga Parada
                    foreach ($produtosAgrupados['carga_parada'] as $produto) {
                        $operacao = $produto['fornecedor'];
                        $pdf->Cell(15, 6, $operacao, 1);
                        $pdf->Cell(15, 6, $produto['nf'], 1);
                        $pdf->Cell(20, 6, $produto['cod'], 1);
                        $descricao = $produto['descricao'];
                        if (strlen($descricao) > 28) {
                            $descricao = substr($descricao, 0, 28);
                        }
                        $pdf->Cell(55, 6, $descricao, 1);
                        $pdf->Cell(15, 6, number_format($produto['quantidade'], 2), 1);
                        $pdf->Cell(15, 6, $produto['QuantAux'], 1);
                        $pdf->Cell(10, 6, $produto['UnidadeAuxiliar'], 1);
                        $pdf->Cell(25, 6, $produto['data_producao'], 1);
                        $pdf->Cell(25, 6, $produto['data_validade'], 1);
                        $pdf->Ln();
                    }
                }
            }

            // Envia o PDF para o navegador
            $pdf->Output('I', 'relatorio_produtos_por_rota.pdf');
        } else {
            echo "Nenhum produto encontrado.";
        }
    } else {
        echo "Erro ao executar a consulta.";
    }
}
?>
