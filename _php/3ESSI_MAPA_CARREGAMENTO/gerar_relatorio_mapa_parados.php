<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start(); // Inicia o buffer de saída

require_once('../CRUD/relog.php');
require('../../fpdf/fpdf.php');  // Caminho correto para o FPDF
require_once('../CRUD/conexao.php'); // Certifique-se de incluir o arquivo correto de conexão ao banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "SELECT 
                n.id_monitoramento,
                n.data_lancamento,
                m.placa_caminhao,
                p.cod,
                p.descricao,
                MAX(p.nf) AS nf,
                MAX(n.rota) AS rota,
                ROUND(SUM(CASE WHEN p.item_alterado IS NULL THEN p.quantidade ELSE 0 END), 2) AS Peso,
                SUM(CASE WHEN p.item_alterado IS NULL THEN p.QuantAux ELSE 0 END) AS quantidade,
                p.data_producao,
                p.data_validade,
                MAX(p.UnidadeAuxiliar) AS UnidadeAuxiliar,
                p.item_alterado,
                MAX(n.fornecedor) AS fornecedor,
                CASE 
                    WHEN n.reentrega = 'S' THEN 'S'
                    ELSE 'N'
                END AS reentrega
            FROM produtos p
            LEFT JOIN notas n ON p.nf = n.n_nota
            LEFT JOIN monitoramento m ON n.id_monitoramento = m.id
            WHERE n.id_monitoramento IS NULL  
            GROUP BY n.rota, p.cod, p.data_producao, p.data_validade, 
                     n.id_monitoramento, n.reentrega, p.item_alterado, 
                     p.descricao, n.data_lancamento, m.placa_caminhao
            ORDER BY n.rota, fornecedor, p.cod ASC, p.nf ASC, n.reentrega DESC";

    $stmt = $conn->prepare($sql);
    
    if ($stmt && $stmt->execute()) {
        $result = $stmt->get_result();
        $produtos = $result->fetch_all(MYSQLI_ASSOC);
        
        if (!empty($produtos)) {
            // Agrupar produtos por rota
            $produtosPorRota = [];
            foreach ($produtos as $produto) {
                $rota = $produto['rota'] ?? 'Sem Rota';
                if (!isset($produtosPorRota[$rota])) {
                    $produtosPorRota[$rota] = [];
                }
                $produtosPorRota[$rota][] = $produto;
            }

            $pdf = new FPDF();
            $pdf->SetAutoPageBreak(true, 10);
            $pdf->AddPage();

            // Cabeçalho principal
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, 'Mapa de Carregamento', 0, 1, 'C');
            $pdf->Ln(5);
            
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(50, 10, 'Placa:', 0, 0);
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(0, 10, $produtos[0]['placa_caminhao'] ?? 'N/A', 0, 1);
            
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(50, 10, 'Id Monitoramento:', 0, 0);
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(0, 10, $produtos[0]['id_monitoramento'] ?? 'N/A', 0, 1);
            
            $pdf->Ln(10);
            
            // Gerar uma tabela para cada rota
            foreach ($produtosPorRota as $rota => $lista) {
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(0, 10, "Rota: $rota", 0, 1, 'C');
                $pdf->Ln(5);

                // Criando o cabeçalho da tabela
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(17, 6, 'Operacao', 1);
                $pdf->Cell(25, 6, 'Codigo', 1);
                $pdf->Cell(52, 6, 'Descricao', 1);
                $pdf->Cell(18, 6, 'Peso', 1);
                $pdf->Cell(10, 6, 'Qnt', 1);
                $pdf->Cell(20, 6, 'Tipo', 1);
                $pdf->Cell(25, 6, 'Data Prod.', 1);
                $pdf->Cell(25, 6, 'Data Val.', 1);
                $pdf->Ln();

                // Adicionando os produtos
                $pdf->SetFont('Arial', '', 10);
                foreach ($lista as $produto) {
                    $fornecedor = (strlen($produto['fornecedor']) > 18) ? substr($produto['fornecedor'], 0, 18) : $produto['fornecedor'];
                    $pdf->Cell(17, 6, $fornecedor, 1);
                    $pdf->Cell(25, 6, $produto['cod'], 1);
                    $descricao = (strlen($produto['descricao']) > 22) ? substr($produto['descricao'], 0, 22) : $produto['descricao'];
                    $pdf->Cell(52, 6, $descricao, 1);
                    $pdf->Cell(18, 6, number_format($produto['Peso'], 2), 1);
                    $pdf->Cell(10, 6, $produto['quantidade'], 1);
                    $pdf->Cell(20, 6, $produto['UnidadeAuxiliar'], 1);
                    $pdf->Cell(25, 6, $produto['data_producao'], 1);
                    $pdf->Cell(25, 6, $produto['data_validade'], 1);
                    $pdf->Ln();
                }

                // Separação entre tabelas
                $pdf->Ln(10);
            }

            ob_end_clean();
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="Mapa_Carregamento.pdf"');
            $pdf->Output('I', 'Mapa_Carregamento.pdf');
            exit;
        } else {
            echo json_encode(['erro' => 'Nenhum produto encontrado para os critérios informados.']);
            exit;
        }
    } else {
        echo json_encode(['erro' => 'Erro ao executar a consulta no banco de dados.']);
        exit;
    }
} else {
    echo json_encode(['erro' => 'Erro: Método de requisição inválido.']);
    exit;
}
