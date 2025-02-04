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

    $sql = "SELECT
            n.id_monitoramento,
            m.placa_caminhao,
            p.cod,
            nc.sequencia,
            p.descricao,
            p.nf,
            ROUND(SUM(CASE WHEN p.item_alterado IS NULL THEN p.quantidade ELSE 0 END), 2) AS Peso,
            SUM(CASE WHEN p.item_alterado IS NULL THEN p.QuantAux ELSE 0 END) AS quantidade,
            p.data_producao,
            p.data_validade,
            p.UnidadeAuxiliar,
            p.item_alterado,
            MAX(n.fornecedor) AS fornecedor,
            CASE
                WHEN n.reentrega = 'S' THEN 'S'
                ELSE 'N'
            END AS reentrega
        FROM produtos p
        LEFT JOIN notas n ON p.nf = n.n_nota AND n.id_monitoramento = ?
        LEFT JOIN monitoramento m ON n.id_monitoramento = m.id
        LEFT JOIN cruzeiro_notas nc ON n.n_nota = nc.fk_notas_n_nota
        WHERE m.placa_caminhao = ?
        AND m.largada = ?
        AND n.id_monitoramento = ?
        GROUP BY p.cod, p.data_producao, p.data_validade, n.id_monitoramento, n.reentrega, p.item_alterado
        ORDER BY p.nf ASC, p.cod ASC,  n.reentrega DESC";

    // Prepara e executa a consulta
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $id_monitoramento, $placa, $dataLancamento, $id_monitoramento);  // 'ssss' indica 4 parâmetros string

    // Verifica se a consulta foi executada com sucesso
    if ($stmt->execute()) {
        // Obtém o resultado
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
            $totalPeso = array_sum(array_column($produtos, 'Peso'));
            $totalVolume = array_sum(array_column($produtos, 'quantidade'));
            $pdf->Cell(0, 10, 'Peso Total: ' . number_format($totalPeso, 2), 0, 1);
            $pdf->Cell(0, 10, 'Volume Total: ' . number_format($totalVolume, 2), 0, 1);

            // Espaço para separar o cabeçalho da tabela
            $pdf->Ln(10);

            // Tabela Entrega Regular
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 10, 'Entrega Regular', 0, 1, 'C');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(15, 10, 'Operacao', 1);
            $pdf->Cell(25, 10, 'Codigo', 1);
            $pdf->Cell(55, 10, 'Descricao', 1);
            $pdf->Cell(25, 10, 'Peso', 1);
            $pdf->Cell(15, 10, 'Qnt', 1);
            $pdf->Cell(10, 10, 'Tipo', 1);
            $pdf->Cell(25, 10, 'Data Producao', 1);
            $pdf->Cell(25, 10, 'Data Validade', 1);
            $pdf->Ln();

            // Preenche a tabela Entrega Regular com os dados
            foreach ($produtosAgrupados['normais'] as $produto) {
                // Exibe a operação corretamente
                $operacao = $produto['fornecedor'];
                $pdf->Cell(15, 10, $operacao, 1);
                $pdf->Cell(25, 10, $produto['cod'], 1);

                // Ajusta a descrição para não ultrapassar o limite de caracteres
                $descricao = $produto['descricao'];
                $limiteCaracteres = 22; // Ajuste conforme necessário
                if (strlen($descricao) > $limiteCaracteres) {
                    $descricao = substr($descricao, 0, $limiteCaracteres); // Corta a descrição sem adicionar "..."
                }

                // Exibe a descrição truncada
                $pdf->Cell(55, 10, $descricao, 1);

                $pdf->Cell(25, 10, number_format($produto['Peso'], 2), 1);
                $pdf->Cell(15, 10, $produto['quantidade'], 1);
                $pdf->Cell(10, 10, $produto['UnidadeAuxiliar'], 1);
                $pdf->Cell(25, 10, $produto['data_producao'], 1);
                $pdf->Cell(25, 10, $produto['data_validade'], 1);
                $pdf->Ln();
            }

            // Espaço entre as tabelas
            $pdf->Ln(10);

            // Tabela Galpão (Armazém)
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 10, 'Galpao (Armazem)', 0, 1, 'C');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(15, 10, 'Operacao', 1);
            $pdf->Cell(25, 10, 'Codigo', 1);
            $pdf->Cell(55, 10, 'Descricao', 1);
            $pdf->Cell(25, 10, 'Peso', 1);
            $pdf->Cell(15, 10, 'Qnt', 1);
            $pdf->Cell(10, 10, 'tipo', 1);
            $pdf->Cell(25, 10, 'Data Producao', 1);
            $pdf->Cell(25, 10, 'Data Validade', 1);
            $pdf->Ln();

            // Preenche a tabela Galpão com os dados
            foreach ($produtosAgrupados['armazem'] as $produto) {
                $operacao = $produto['fornecedor'];
                $pdf->Cell(15, 10, $operacao, 1);
                $pdf->Cell(25, 10, $produto['cod'], 1);

                // Ajusta a descrição para não ultrapassar o limite de caracteres
                $descricao = $produto['descricao'];
                if (strlen($descricao) > $limiteCaracteres) {
                    $descricao = substr($descricao, 0, $limiteCaracteres); // Corta a descrição sem adicionar "..."
                }

                // Exibe a descrição truncada
                $pdf->Cell(55, 10, $descricao, 1);

                $pdf->Cell(25, 10, number_format($produto['Peso'], 2), 1);
                $pdf->Cell(15, 10, $produto['quantidade'], 1);
                $pdf->Cell(10, 10, $produto['UnidadeAuxiliar'], 1);
                $pdf->Cell(25, 10, $produto['data_producao'], 1);
                $pdf->Cell(25, 10, $produto['data_validade'], 1);
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
            // Caso não haja dados, exibe uma mensagem de erro
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
