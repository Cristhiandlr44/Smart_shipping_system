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

    $sql = "SELECT p.*, n.fornecedor
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
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, 'Galpao (Armazem)', 0, 1, 'C');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(15, 6, 'Operacao', 1);
            $pdf->Cell(15, 6, 'NF', 1);
            $pdf->Cell(20, 6, 'Codigo', 1);
            $pdf->Cell(55, 6, 'Descricao', 1);
            $pdf->Cell(15, 6, 'Peso', 1);
            $pdf->Cell(15, 6, 'Qnt', 1);
            $pdf->Cell(10, 6, 'tipo', 1);
            $pdf->Cell(25, 6, 'Data Producao', 1);
            $pdf->Cell(25, 6, 'Data Validade', 1);
            $pdf->Ln();

            // Preenche a tabela Galpão com os dados
            foreach ($produtosAgrupados['armazem'] as $produto) {
                $operacao = $produto['fornecedor'];
                $pdf->Cell(15, 6, $operacao, 1);
                $pdf->Cell(15, 6, $produto['nf'], 1);
                $pdf->Cell(20, 6, $produto['cod'], 1);
                $descricao = $produto['descricao'];
                if (strlen($descricao) > 28) {
                    $descricao = substr($descricao, 0, 28);
                }
                // Exibe a descrição truncada
                $pdf->Cell(55, 6, $descricao, 1);

                $pdf->Cell(15, 6, number_format($produto['Peso'], 2), 1);
                $pdf->Cell(15, 6, $produto['quantidade'], 1);
                $pdf->Cell(10, 6, $produto['UnidadeAuxiliar'], 1);
                $pdf->Cell(25, 6, $produto['data_producao'], 1);
                $pdf->Cell(25, 6, $produto['data_validade'], 1);
                $pdf->Ln();
            }

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
                if (strlen($nome) > 22) {
                    $nome= substr($nome, 0, 22);
                }
                $pdf->Cell(40, 6, $nome, 1);
                $rua = $cliente['rua'];
                if (strlen($rua) > 22) {
                    $rua= substr($rua, 0, 22);
                }
                $pdf->Cell(40, 6, $rua, 1);
                $pdf->Cell(12, 6, $cliente['numero'], 1);
                $pdf->Cell(30, 6, $cliente['bairro'], 1);
                $pdf->Cell(25, 6, $cliente['cidade'], 1);
                $pdf->Cell(15, 6, $cliente['peso_bruto'], 1);
                $pdf->Cell(15, 6, $cliente['valor_nota'], 1);

                $pdf->Ln();
            }

            // Limpa o buffer de saída antes de enviar os cabeçalhos
            ob_end_clean();

            // Definir cabeçalhos para o PDF
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="Mapa_Carregamento.pdf"');
            header('Cache-Control: no-cache, no-store, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');

            // Envia o arquivo PDF para o navegador
            $pdf->Output('I', 'Mapa_Carregamento.pdf');  // 'I' para visualização no navegador, 'D' para download
            exit;
        } else {
            // Caso não haja produtos
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['erro' => 'Nenhum produto encontrado para os critérios informados.']);
            exit;
        }
    } else {
        // Se a consulta falhar
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['erro' => 'Erro ao executar a consulta no banco de dados.']);
        exit;
    }
} else {
    // Se a requisição não for POST
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['erro' => 'Erro: Método de requisição inválido.']);
    exit;
}
?>
