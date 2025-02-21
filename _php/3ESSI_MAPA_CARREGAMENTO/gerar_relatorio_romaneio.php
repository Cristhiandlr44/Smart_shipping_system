<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start(); // Inicia o buffer de saída

require_once('../CRUD/relog.php');
require('../../fpdf/fpdf.php');  // Caminho correto para o FPDF

// Verifica se é um método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebendo os dados da requisição POST
    $dataLancamento = isset($_POST['dataLancamento']) ? $_POST['dataLancamento'] : '';
    
    $placa = isset($_POST['placa']) ? $_POST['placa'] : '';
    $id_monitoramento = isset($_POST['id_monitoramento']) ? $_POST['id_monitoramento'] : '';

    // Verifica se os dados essenciais foram enviados
    if (empty($placa) || empty($dataLancamento) || empty($id_monitoramento)) {
        // Exibe mensagem de erro diretamente
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['erro' => 'Dados incompletos.']);
        exit;
    }

    $sql = "SELECT p.*, n.fornecedor, n.reentrega, n.data_lancamento
            FROM produtos p
            INNER JOIN notas n ON p.nf = n.n_nota
            WHERE n.id_monitoramento = ? AND p.item_alterado IS NULL
            ORDER BY p.nf ASC";

    // Prepara e executa a consulta dos produtos
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $id_monitoramento);  

    // Verifica se a consulta foi executada com sucesso
    if ($stmt->execute()) {
        // Obtém o resultado dos produtos
        $result = $stmt->get_result();
        $produtos = $result->fetch_all(MYSQLI_ASSOC);

        // Verifica se há produtos
        if (count($produtos) > 0) {
            // Agrupar os resultados por tipo de reentrega e data de lançamento
            $produtosAgrupados = ['armazem' => [], 'carga_parada' => [], 'normais' => []];
            $dataAtual = new DateTime();
            $dataMenorCargaParada = null;  // Variável para armazenar a menor data de carga parada

            foreach ($produtos as $produto) {
                $dataLancamentoProduto = new DateTime($produto['data_lancamento']);
                $intervalo = $dataLancamentoProduto->diff($dataAtual)->days;

                if ($intervalo >= 2) {
                    // Agrupando produtos como carga parada
                    $produtosAgrupados['carga_parada'][] = $produto;
                    // Verificando a menor data de lançamento para carga parada
                    if ($dataMenorCargaParada === null || $dataLancamentoProduto < $dataMenorCargaParada) {
                        $dataMenorCargaParada = $dataLancamentoProduto;
                    }
                } elseif ($produto['reentrega'] === 'S') {
                    // Agrupando produtos como armazém (reentrega)
                    $produtosAgrupados['armazem'][] = $produto;
                } else {
                    // Agrupando produtos como normais
                    $produtosAgrupados['normais'][] = $produto;
                }
            }

            // Consulta os dados do cliente baseado no id_monitoramento
            $sqlCliente = "SELECT nome, rua, numero, c.bairro, c.cidade, n.n_nota, n.peso_bruto, n.valor_nota
                           FROM clientes c
                           INNER JOIN notas n ON c.cnpj = n.cnpj
                           WHERE n.id_monitoramento = ?";
            $stmtCliente = $conn->prepare($sqlCliente);
            $stmtCliente->bind_param('s', $id_monitoramento);
            $stmtCliente->execute();
            $resultCliente = $stmtCliente->get_result();
            $clientes = $resultCliente->fetch_all(MYSQLI_ASSOC);

            // Cria o objeto PDF
            $pdf = new FPDF();
            $pdf->SetAutoPageBreak(true, 10);  // Habilitar quebra automática de página
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 12);

            // Cabeçalho do PDF
            $pdf->Cell(0, 10, 'Placa: ' . $placa, 0, 1);
            $pdf->Cell(0, 10, 'Id Monitoramento: ' . $id_monitoramento, 0, 1);
            $pdf->Cell(0, 10, 'Data Largada: ' . $dataLancamento, 0, 1);

            // Cálculos do peso e volume
            $totalPeso = array_sum(array_column($produtos, 'quantidade'));
            $totalVolume = array_sum(array_column($produtos, 'QuantAux'));
            $pdf->Cell(0, 10, 'Peso Total: ' . number_format($totalPeso, 2), 0, 1);
            $pdf->Cell(0, 10, 'Volume Total: ' . number_format($totalVolume, 2), 0, 1);
            $pdf->Ln(10);

                // Tabela de Dados dos Clientes
                $pdf->SetFont('Arial', 'B', 12);
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
                    
                // Preenche a tabela com os dados dos clientes
                foreach ($clientes as $cliente) {
                    $pdf->Cell(15, 6, $cliente['n_nota'], 1);

                    $nome = $cliente['nome'];
                    if (strlen($nome) > 20) {
                        $nome= substr($nome, 0, 20);
                    }
                    $pdf->Cell(40, 6, $nome, 1);

                    $rua = $cliente['rua'];
                    if (strlen($rua) > 20) {
                        $rua= substr($rua, 0, 20);
                    }
                    $pdf->Cell(40, 6, $rua, 1);

                    $pdf->Cell(12, 6, $cliente['numero'], 1);

                    $bairro = strlen($cliente['bairro']) > 15 ? substr($cliente['bairro'], 0, 15) : $cliente['bairro'];
                    $pdf->Cell(30, 6, $bairro, 1);

                    $cidade = strlen($cliente['cidade']) > 15 ? substr($cliente['cidade'], 0, 19) : $cliente['cidade'];
                    $pdf->Cell(25, 6, $cidade, 1);

                    $pdf->Cell(15, 6, $cliente['peso_bruto'], 1);
                    $pdf->Cell(15, 6, $cliente['valor_nota'], 1);

                    $pdf->Ln();
                }
                $pdf->Ln(10);    
            // Espaço para separar o cabeçalho da tabela de produtos
            $pdf->Ln(10);

            // Tabela Entrega Regular (Produtos Normais)
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

            // Preenche a tabela Entrega Regular com os dados dos produtos
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

            // Espaço entre as tabelas de produtos e dados dos clientes
            $pdf->Ln(10);
            
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
                
                // Preenche a tabela Galpão com os dados dos produtos
                foreach ($produtosAgrupados['armazem'] as $produto) {
                    $pdf->Cell(15, 6, $produto['fornecedor'], 1);
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

            // Espaço entre as tabelas de produtos e dados dos clientes
            $pdf->Ln(10);
            
            // Tabela Carga Parada
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
                
                // Preenche a tabela Carga Parada com os dados dos produtos
                foreach ($produtosAgrupados['carga_parada'] as $produto) {
                    $pdf->Cell(15, 6, $produto['fornecedor'], 1);
                    $pdf->Cell(15, 6, $produto['nf'], 1);
                    $pdf->Cell(20, 6, $produto['cod'], 1);
                    $descricao = $produto['descricao'];
                    if (strlen($descricao) > 28) {
                        $descricao = substr($descricao, 0, 28);
                    } $descricao = $produto['descricao'];
                   
                    $pdf->Cell(55, 6, $descricao, 1);
                    $pdf->Cell(15, 6, number_format($produto['quantidade'], 2), 1);
                    $pdf->Cell(15, 6, $produto['QuantAux'], 1);
                    $pdf->Cell(10, 6, $produto['UnidadeAuxiliar'], 1);
                    $pdf->Cell(25, 6, $produto['data_producao'], 1);
                    $pdf->Cell(25, 6, $produto['data_validade'], 1);
                    $pdf->Ln();
                }
            }    
                


            // Gera o PDF
            $pdf->Output('I', 'Romaneio_Notas' . $placa . '.pdf');
        } else {
            // Exibe mensagem se não houver produtos
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['erro' => 'Nenhum produto encontrado.']);
            exit;
        }
    } else {
        // Exibe erro em caso de falha na consulta
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['erro' => 'Erro ao consultar os produtos.']);
        exit;
    }
}
