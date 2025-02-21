    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    ob_start(); // Inicia o buffer de saída

    require_once('../CRUD/relog.php');
    require('../../fpdf/fpdf.php');  // Caminho correto para o FPDF

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $dataLancamento = isset($_POST['dataLancamento']) ? $_POST['dataLancamento'] : '';

            // Verifica se a data foi recebida e não está vazia
            if (!empty($dataLancamento)) {
                // Cria um objeto DateTime a partir da string da data recebida
                $data = new DateTime($dataLancamento);

                // Formata a data para o formato desejado (d/m/Y)
                $dataLancamentoFormatada = $data->format('d/m/Y');

                // Exibe ou usa a data formatada conforme necessário
                echo $dataLancamentoFormatada; // Exemplo de saída: 07/02/2025
            } else {
                echo "Data não fornecida.";
            }

        $placa = isset($_POST['placa']) ? $_POST['placa'] : '';
        $id_monitoramento = isset($_POST['id_monitoramento']) ? $_POST['id_monitoramento'] : '';

        if (empty($placa) || empty($dataLancamento) || empty($id_monitoramento)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['erro' => 'Dados incompletos.']);
            exit;
        }

        $sql = "SELECT
                n.id_monitoramento,
                n.data_lancamento,
                m.placa_caminhao,
                p.cod,
                p.descricao,
                MAX(p.nf) AS nf,
                MAX(ti.tipo) AS tipoItem,
                ROUND(SUM(CASE WHEN p.item_alterado IS NULL THEN p.quantidade ELSE 0 END), 2) AS Peso,
                SUM(CASE WHEN p.item_alterado IS NULL THEN p.QuantAux ELSE 0 END) AS quantidade,
                p.data_producao,
                p.data_validade,
                MAX(p.UnidadeAuxiliar) AS UnidadeAuxiliar,
                MAX(p.item_alterado) AS item_alterado ,
                MAX(n.fornecedor) AS fornecedor,
                CASE
                    WHEN n.reentrega = 'S' THEN 'S'
                    ELSE 'N'
                END AS reentrega
            FROM produtos p
            LEFT JOIN notas n ON p.nf = n.n_nota AND n.id_monitoramento = ?
            LEFT JOIN monitoramento m ON n.id_monitoramento = m.id
            LEFT JOIN cruzeiro_notas nc ON n.n_nota = nc.fk_notas_n_nota
            LEFT JOIN tipos_itens ti ON  p.cod = ti.cod
            WHERE m.placa_caminhao = ?
            AND m.largada = ?
            AND n.id_monitoramento = ?
            GROUP BY p.cod, p.data_producao, p.data_validade, n.id_monitoramento, n.reentrega, item_alterado, p.descricao, n.data_lancamento
            ORDER BY fornecedor,tipoItem ,p.cod ASC,nf ASC,  n.reentrega DESC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $id_monitoramento, $placa, $dataLancamento, $id_monitoramento);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $produtos = $result->fetch_all(MYSQLI_ASSOC);

            if (count($produtos) > 0) {
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
                        $produtosAgrupados['armazem'][] = $produto;
                    } else {
                        $produtosAgrupados['normais'][] = $produto;
                    }
                }

                // Se encontrar uma data de carga parada, formate-a para ser usada no nome da tabela
                if ($dataMenorCargaParada !== null) {
                    $nomeTabela = 'carga_parada_' . $dataMenorCargaParada->format('Y-m-d');
                } else {
                    $nomeTabela = 'carga_parada_sem_data';  // Caso não haja carga parada
                }

                // Agora você pode usar $nomeTabela como nome da tabela ou em qualquer lugar necessário
            

                $pdf = new FPDF();
                $pdf->SetAutoPageBreak(true, 10);
                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(0, 10, 'Placa: ' . $placa, 0, 1);
                $pdf->Cell(0, 10, 'Id Monitoramento: ' . $id_monitoramento, 0, 1);
                $pdf->Cell(0, 10, 'Data Largada: ' . $dataLancamentoFormatada, 0, 1);
                // Cálculos do peso e volume
                $totalPeso = array_sum(array_column($produtos, 'Peso'));
                $totalVolume = array_sum(array_column($produtos, 'quantidade'));
                $pdf->Cell(0, 10, 'Peso Total: ' . number_format($totalPeso, 2), 0, 1);
                $pdf->Cell(0, 10, 'Volume Total: ' . number_format($totalVolume, 2), 0, 1);
                $pdf->Ln();
                
                foreach (['normais' => 'Entrega Regular', 'armazem' => 'Galpao (Armazem)', 'carga_parada' => 'Armazem (Carga Parada)'] as $categoria => $titulo) {
                    if (!empty($produtosAgrupados[$categoria])) {
                        $pdf->SetFont('Arial', 'B', 10);
                        $pdf->Cell(0, 15, $titulo, 0, 1, 'C');
                        $pdf->SetFont('Arial', '', 8);
                        $pdf->Cell(15, 6, 'Operacao', 1);
                        $pdf->Cell(13, 6, 'Tipo', 1);
                        $pdf->Cell(20, 6, 'Codigo', 1);
                        $pdf->Cell(90, 6, 'Descricao', 1);
                        $pdf->Cell(14, 6, 'Peso', 1);
                        $pdf->Cell(8, 6, 'Qnt', 1);
                        $pdf->Cell(10, 6, 'Tipo', 1);
                        $pdf->Cell(20, 6, 'Data Validade', 1);
                        $pdf->Ln();
                        
                        foreach ($produtosAgrupados[$categoria] as $produto) {
                            $dataValidadeProduto = new DateTime($produto['data_validade']);
                            $dataValidadeProdutoFormatada = $dataValidadeProduto->format('d/m/Y');

                            $pdf->Cell(15, 6, $produto['fornecedor'], 1);
                            $pdf->Cell(13, 6, $produto['tipoItem'], 1);
                            $pdf->Cell(20, 6, $produto['cod'], 1);
                            $descricao = strlen($produto['descricao']) > 50 ? substr($produto['descricao'], 0, 50) : $produto['descricao'];
                            $pdf->Cell(90, 6, $descricao, 1);
                            $pdf->Cell(14, 6, number_format($produto['Peso'], 2), 1);
                            $pdf->Cell(8, 6, $produto['quantidade'], 1);
                            $pdf->Cell(10, 6, $produto['UnidadeAuxiliar'], 1);
                            $pdf->Cell(20, 6, $dataValidadeProdutoFormatada, 1);
                            $pdf->Ln();
                        }
                        $pdf->Ln(10);
                    }
                }
                
                ob_end_clean(); // Limpa o buffer de saída antes de enviar o PDF
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename="Mapa_Carregamento_' . $placa . '.pdf"'); // Personaliza o nome do arquivo com a placa
                $pdf->Output('I', 'Mapa_Carregamento_' . $placa . '.pdf'); // Usa a placa no nome do arquivo gerado
                exit;

            } else {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['erro' => 'Nenhum produto encontrado para os critérios informados.']);
                exit;
            }
        } else {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['erro' => 'Erro ao executar a consulta no banco de dados.']);
            exit;
        }
    } else {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['erro' => 'Erro: Método de requisição inválido.']);
        exit;
    }
