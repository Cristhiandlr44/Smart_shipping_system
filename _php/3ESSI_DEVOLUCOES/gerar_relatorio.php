<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start(); // Inicia o buffer de saída

require_once('../CRUD/relog.php');
require('../../fpdf/fpdf.php');  // Caminho correto para o FPDF

// Recebe o ID da viagem
$viagemId = $_POST['id'];  // Usando a variável correta para o ID

// Verificação de erro no ID recebido
if (empty($viagemId)) {
    echo "ID da viagem não fornecido!";
    exit();
}

// Consulta os detalhes da viagem
$sql = "
    SELECT 
        m.id, 
        mo.nome AS motorista, 
        mc.fk_placa AS placa_caminhao, 
        m.data_finalizacao, 
        m.largada
    FROM 
        monitoramento AS m
    JOIN 
        motorista_caminhoes AS mc ON m.placa_caminhao = mc.fk_placa
    JOIN 
        motorista AS mo ON mc.fk_cpf_motorista = mo.CPF_motorista
    WHERE 
        m.id = $viagemId
";
$viagem = $conn->query($sql)->fetch_assoc();

// Verificar se os dados da viagem foram encontrados
var_dump($viagem); // Adicionando var_dump para verificar os dados da viagem
// Verificação de erro na viagem
// Verificação de erro na viagem
if (!$viagem) {
    echo "Viagem não encontrada!";
    exit();
}

// Formatação das datas
$dataLargada = date('d/m/Y', strtotime($viagem['largada']));
$dataFinalizacao = date('d/m/Y', strtotime($viagem['data_finalizacao']));

// Consulta os itens devolvidos agrupados por nota
$sqlItens = "
    SELECT 
        a.tipo,
        a.nf, 
        a.cod_item, 
        a.quantidade,
        a.unidade,
        a.peso
    FROM 
        anomalias AS a
    JOIN 
        notas AS n ON a.nf = n.n_nota
    JOIN 
        monitoramento AS m ON n.id_monitoramento = m.id
    WHERE 
        m.id = ?  
    GROUP BY 
        a.nf, a.cod_item, a.quantidade, a.tipo,a.unidade, a.peso
";
$stmt = $conn->prepare($sqlItens);
$stmt->bind_param('i', $viagemId);  // 'i' indica que o parâmetro é um inteiro
$stmt->execute();
$itens = $stmt->get_result();

// Verificar se itens forams encontrados
if ($itens->num_rows == 0) {
    echo "Nenhum item encontrado para esta viagem!";
    exit();
}

// Criar uma nova instância do FPDF
$pdf = new FPDF();
$pdf->SetAutoPageBreak(true, 10);  // Habilitar quebra automática de página
$pdf->AddPage();

// Definir o título do documento
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(190, 10, "Relatorio de Viagem", 0, 1, 'C');

// Espaço entre as seções
$pdf->Ln(5);

// Informações da viagem
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(45, 10, "Placa do caminhao: ", 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(95, 10, $viagem['placa_caminhao'], 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(45, 10, "Nome do motorista: ", 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(95, 10, $viagem['motorista'], 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(45, 10, "Data de Largada: ", 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(95, 10, $dataLargada, 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(45, 10, "Data de Finalizacao: ", 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(95, 10, $dataFinalizacao, 0, 1);

// Agrupar os itens por NF
$itensPorNF = [];
while ($item = $itens->fetch_assoc()) {
    $itensPorNF[$item['nf']][] = $item;
}

// Gerar PDF com itens agrupados por NF
foreach ($itensPorNF as $nf => $itensNF) {
    // Adicionar um título para a NF
    $pdf->Ln(5);  // Espaço entre a NF e os itens
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 10, "Nota Fiscal: $nf", 0, 1, 'C');

    // Criar cabeçalho para a tabela de itens
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(30, 10, 'Tipo', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Cod Item', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Quantidade', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Unidade', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Peso', 1, 0, 'C');
    $pdf->Cell(80, 10, 'Obs.', 1, 1, 'C');  // Coluna Observações mais larga

    // Adicionar os itens dentro da tabela
    $pdf->SetFont('Arial', '', 10);
    foreach ($itensNF as $item) {
        $pdf->Cell(30, 10, $item['tipo'], 1, 0, 'C');
        $pdf->Cell(20, 10, $item['cod_item'], 1, 0, 'C');
        $pdf->Cell(20, 10, $item['quantidade'], 1, 0, 'C');
        $pdf->Cell(20, 10, $item['unidade'], 1, 0, 'C');
        $pdf->Cell(20, 10, $item['peso'], 1, 0, 'C');
        $pdf->Cell(80, 10, '', 1, 1, 'C');  // Coluna de observações vazia (para cada item)
    }

    // Adicionar um grande espaço abaixo da tabela
    $pdf->Ln(20);  // Grande espaço em branco após a tabela
}

// Limpar o buffer de saída antes de enviar os cabeçalhos e gerar o PDF
ob_end_clean();

// Definir cabeçalhos para o PDF
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="relatorio_viagem.pdf"');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Gerar o PDF e forçar o download
$pdf->Output('I', 'relatorio_viagem.pdf');  // 'I' para abrir diretamente no navegador
exit();
