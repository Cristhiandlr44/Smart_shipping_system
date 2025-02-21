<?php
  $relative = "../";
  require_once($relative . "CRUD/relog.php");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viagens Abertas</title>
    <link rel="shortcut icon" href="../../_assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../_style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> <!-- DataTables JS -->
    <script src="../../_scriptjs/script.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <script>
        // Função para mostrar/ocultar as notas fiscais da viagem
        function mostrarNotasFiscais(id) {
            $('#notas-' + id).toggle(); // Alternar visibilidade da lista de notas fiscais
        }

        // Função para exibir os itens da nota fiscal
        function mostrarItensNota(nota) {
            $('#itens-' + nota).toggle(); // Alternar visibilidade dos itens da nota fiscal
        }
    </script>

    <style>
        /* Estilo geral para o container da dashboard */
        .dash {
            min-height: 90vh;
            height: 100%;
        }

        /* Estilo para os campos de formulário */
        .form-control {
            display: inline;
            width: auto;
        }

        /* Estilo para a tabela com efeito hover */
        .table-hover:hover {
            cursor: pointer;
        }

        /* Estilo para a área de observações */
        .observacoes-textarea {
            border-radius: 6px;
            padding: 6px 10px;
            width: 100%;
            border: 1px solid #ccc;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
            resize: none;
            font-size: 14px;
            height: 40px;
        }

        /* Estilo quando o textarea de observações estiver em foco */
        .observacoes-textarea:focus {
            border-color: #007bff;
            background-color: #ffffff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.25);
        }

        /* Estilo para o botão Salvar */
        .btn-salvar {
            border-radius: 6px;
            padding: 6px 12px;
            background-color: #007bff;
            color: white;
            border: none;
            font-size: 12px;
            margin-top: 5px;
            transition: background-color 0.3s ease;
        }

        .btn-salvar:hover {
            background-color: #0056b3;
        }

        /* Estilos para os botões específicos (Anomalia, Alterar Placa e Finalizar) */
        .btn {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s, transform 0.2s;
            background-color: inherit !important; /* Para não ser sobrescrito */
            color: inherit !important;
        }

        /* Ajuste para os botões dentro de uma célula de tabela */
        .table td .btn-group {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            gap: 10px;
        }

        .table td .btn-group button {
            font-size: 12px;
            padding: 6px 12px;
            margin: 0;
            border-radius: 6px;
        }

        /* Ajuste na largura para garantir que a linha fique com dois botões */
        .table td .btn-group button {
            flex: 0 0 auto;
            max-width: 150px;
        }

        /* Se quiser ajustar a altura das células de tabela */
        .table td {
            padding: 8px;
            vertical-align: middle;
        }

        /* Para a coluna de ações, você pode definir a largura */
        .table td:last-child {
            width: 400px;
        }
    </style>
</head>
<body>    
    <div id="navbarSt"></div>
    <div class="container d-flex justify-content-center align-items-center">
        <div class="dash bg-white">
            <div class="row p-3">
                <?php
                    $title = "- Controle Cargas - Cargas";
                    require_once($relative . "elements/tituloProjetoMainSection.php");

                    if ($_SESSION['tipo'] == 1) {
                        echo "
                        <div class='row'>
                            <p class='col-sm-8 offset-sm-2 col-md-11 offset-md-1' style='font-size: 1.5em;text-align: center;'>Cargas Multi Service</p>
                        </div>";
                        
                    }
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <input type="text" id="tabelaFiltro" class="form-control" placeholder="Pesquisar...">
                    </div>
                </div>
                <button class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" onclick="gerarXlsCarga()">Gerar XLS</button>

            </div>
            <div class="row" >
                <div id="table" class="table table-striped table-bordered text-nowrap" style="max-width: 100%;">
                    <table class="table table-striped table-bordered text-nowrap" id="viagensAbertasTable">
                        <thead class="thead-dark">
                            <tr>
                                <th>Numero Carga</th>
                                <th>Fornecedor</th>
                                <th>Peso Total</th>
                                <th>Quantidade de Notas</th>
                                <th>Valor Total</th>
                                <th> </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $notasSql = "SELECT 
                                                sn.carga AS numero_carga,
                                                n.fornecedor,
                                                SUM(n.peso_bruto) AS total_peso_bruto,
                                                COUNT(n.n_nota) AS quantidade_notas,
                                                SUM(n.valor_nota) AS total_valor_nota
                                            FROM saudali_notas sn
                                            JOIN notas n ON n.n_nota = sn.nf
                                            GROUP BY sn.carga, n.fornecedor;
                                            ";
                                $notasResult = $conn->query($notasSql);
                                
                                while ($nota = $notasResult->fetch_assoc()) {
                                    echo "<tr>
                                            <td>{$nota['numero_carga']}</td>
                                            <td>{$nota['fornecedor']}</td>
                                            <td>{$nota['total_peso_bruto']}</td>
                                            <td>{$nota['quantidade_notas']}</td>
                                            <td>{$nota['total_valor_nota']}</td>
                                            <td>
                                                <button class='btn-toggle arrow arrow-closed' onclick='mostrarNotas({$nota['numero_carga']})'></button>
                                            </td>
                                        </tr>";
                                    
                                    $itensSql = "
                                        SELECT 
                                        n.n_nota,
                                        n.cnpj,
                                        c.nome AS nome_cliente,
                                        c.bairro,
                                        c.cidade,
                                        n.peso_bruto,
                                        n.valor_nota,
                                        n.id_monitoramento
                                    FROM notas n
                                    JOIN clientes c ON n.cnpj = c.cnpj
                                    WHERE n.n_nota IN (
                                        SELECT nf FROM saudali_notas WHERE carga = '{$nota['numero_carga']}');
                                        ";
                                    $itensResult = $conn->query($itensSql);

                                    echo "<tr id='itens-{$nota['numero_carga']}' class='itens-list'>
                                            <td colspan='6'>
                                                <table class='table table-bordered'>
                                                    <thead>
                                                        <tr>
                                                            <th>Nota Fiscal</th>
                                                            <th>CNPJ</th>
                                                            <th>Cliente</th>
                                                            <th>Bairro</th>
                                                            <th>Cidade</th>
                                                            <th>Peso</th>
                                                            <th>Valor NF</th>
                                                            <th>Id Monitoramento</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>";
                                    while ($item = $itensResult->fetch_assoc()) {
                                        echo "<tr>
                                                <td>{$item['n_nota']}</td>
                                                <td>{$item['cnpj']}</td>
                                                <td>{$item['nome_cliente']}</td>
                                                <td>{$item['bairro']}</td>
                                                <td>{$item['cidade']}</td>
                                                <td>{$item['peso_bruto']}</td>
                                                <td>{$item['valor_nota']}</td>
                                                <td>{$item['id_monitoramento']}</td>



                                              </tr>";
                                    }
                                    echo "</tbody></table></td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../../_scriptjs/script_controle_cargas.js"></script>


</body>
</html>
