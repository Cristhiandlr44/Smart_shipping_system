<?php
$relative = "../";
require_once($relative . "CRUD/relog.php");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Viagem</title>
    <link rel="shortcut icon" href="../../_assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../_style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../../_scriptjs/script.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
</head>
<body>    
    <div id="navbarSt"></div>
    <div class="container">
        <div class="dash bg-white">
            <div class="row p-3">
                <?php
                    $title = "- Relatório de Viagem - Monitoramento";
                    require_once($relative . "elements/tituloProjetoMainSection.php");

                    if ($_SESSION['tipo'] == 1) {
                        echo "
                        <div class='row'>
                            <p class='col-sm-8 offset-sm-2 col-md-11 offset-md-1' style='font-size: 1.5em;text-align: center;'>Selecione uma viagem para monitoramento</p>
                        </div>";
                    }
                ?>
            </div>
            <div class="row">
                <div id="table" class="table table-striped table-bordered text-nowrap" style="max-width: 100%;">
                    <table class="table table-striped table-bordered text-nowrap" id="viagensAbertasTable">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Motorista</th>
                                <th>Caminhão</th>
                                <th>Data de Partida</th>
                                <th>Data de Finalização</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "
                                SELECT 
                                    m.id, 
                                    mo.nome AS motorista, 
                                    mc.fk_placa AS placa_caminhao, 
                                    m.data_finalizacao, 
                                    m.largada, 
                                    m.finalizada
                                FROM 
                                    monitoramento AS m
                                JOIN 
                                    motorista_caminhoes AS mc ON m.placa_caminhao = mc.fk_placa
                                JOIN 
                                    motorista AS mo ON mc.fk_cpf_motorista = mo.CPF_motorista
                                WHERE 
                                    EXISTS (
                                        SELECT 1
                                        FROM anomalias a
                                        JOIN notas n ON n.n_nota = a.nf
                                        WHERE n.id_monitoramento = m.id
                                    )
                            ";

                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    // Formatando a data
                                    $dataLargada = date('d/m/Y', strtotime($row['largada']));
                                    $dataFinalizacao = date('d/m/Y', strtotime($row['data_finalizacao']));

                                    echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>{$row['motorista']}</td>
                                        <td>{$row['placa_caminhao']}</td>
                                        <td>{$dataLargada}</td>
                                        <td>{$dataFinalizacao}</td>
                                        <td>
                                            <button class='btn btn-acao' onclick='gerarRelatorio({$row['id']})'>Gerar Relatório</button>
                                        </td>
                                    </tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
         $(document).ready(function() {
            $('#viagensAbertasTable').DataTable();
        });
        $(document).ready(() => {
            $.ajax({
                url: '../elements/E_navbar.php',
                type: 'POST',
                data: {
                    relative: '../'
                },
                success: (result) => {
                    $("#navbarSt").html(result);
                }
            });
        });
        // Função para gerar o relatório com os detalhes da viagem e itens devolvidos agrupados por nota
        function gerarRelatorio(idViagem) {
            console.log("ID Viagem: ", idViagem);  // Verificando o ID

            $.ajax({
                url: 'gerar_relatorio.php',
                type: 'POST',
                data: { id: idViagem },
                xhrFields: {
                    responseType: 'blob'  // Definindo a resposta como blob
                },
                success: function(response) {
                    console.log("Resposta do servidor: ", response);

                    var link = document.createElement('a');
                    var url = URL.createObjectURL(response);
                    link.href = url;
                    link.download = 'relatorio_viagem.pdf';  // Nome do arquivo
                    link.click();  // Simula o clique para download
                    URL.revokeObjectURL(url);  // Libera a URL do blob após o download
                },
                error: function(xhr, status, error) {
                    console.log("Erro na requisição: ", status, error);
                }
            });
        }



    </script>
</body>
</html>
