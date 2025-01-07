<?php
  $relative = "../";
  require_once($relative . "CRUD/relog.php");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devoluções</title>
    <link rel="shortcut icon" href="../../_assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../_style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>
    <script src="../../_scriptjs/script.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <!-- Incluindo CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Incluindo o JS do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Função para mostrar/ocultar as notas fiscais da viagem
        function mostrarAnomalias(fornecedor) {
            $('#anomalias-' + fornecedor).toggle(); // Alternar visibilidade da lista de notas fiscais
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
            /* Adicionando !important para garantir que a cor de fundo não seja sobrescrita */
            background-color: inherit !important; /* Para não ser sobrescrito */
            color: inherit !important;
        }

        /* Botão de Finalizar Viagem */
        .btn-finalizar {
            background-color: #28a745 !important; /* Verde */
            color: white !important;
        }

        .btn-finalizar:hover {
            background-color: #218838 !important; /* Verde escuro */
            transform: scale(1.05); /* Efeito de aumento de tamanho */
        }

        .btn-finalizar:active {
            background-color: #1e7e34 !important; /* Tom de verde ainda mais escuro */
        }

        /* Botão de Alterar Placa */
        .btn-alterar-placa {
            background-color: #ffc107 !important; /* Amarelo */
            color: white !important;
        }

        .btn-alterar-placa:hover {
            background-color: #e0a800 !important; /* Amarelo escuro */
            transform: scale(1.05);
        }

        .btn-alterar-placa:active {
            background-color: #d39e00 !important; /* Tom mais escuro de amarelo */
        }

        /* Botão de Anomalia */
        .btn-anomalia {
            background-color: #dc3545 !important; /* Vermelho */
            color: white !important;
        }

        .btn-anomalia:hover {
            background-color: #c82333 !important; /* Vermelho escuro */
            transform: scale(1.05);
        }

        .btn-anomalia:active {
            background-color: #bd2130 !important; /* Tom mais escuro de vermelho */
        }

        /* Estilo para opções de anomalia */
        .anomalia-options {
            display: none;
        }

        /* Estilo para listas de itens e notas */
        .itens-list, .notas-list {
            display: none;
            margin-top: 10px;
        }

        .sub-list {
            padding-left: 30px;
        }

        /* Botão de toggle para abrir/fechar opções */
        .btn-toggle {
            background-color: transparent;
            border: none;
            cursor: pointer;
            font-size: 20px;
            padding: 0;
            margin: 0;
        }

        /* Estilo para as setas de abrir/fechar */
        .arrow {
            font-size: 18px;
        }

        .arrow-open:before {
            content: "▼"; /* Seta para baixo */
        }

        .arrow-closed:before {
            content: "►"; /* Seta para a direita */
        }

            /* Ajuste para os botões dentro de uma célula de tabela */
        .table td .btn-group {
            display: flex; /* Usando flexbox para alinhamento */
            flex-wrap: wrap; /* Permite que os botões se movam para a próxima linha */
            justify-content: flex-start; /* Alinha os botões à esquerda */
            gap: 10px; /* Adiciona espaço entre os botões */
        }

        .table td .btn-group button {
            font-size: 12px; /* Tamanho da fonte */
            padding: 6px 12px; /* Tamanho do padding */
            margin: 0; /* Remove margens adicionais */
            border-radius: 6px; /* Arredondamento dos cantos */
        }

        /* Ajuste na largura para garantir que a linha fique com dois botões */
        .table td .btn-group button {
            flex: 0 0 auto; /* Garante que os botões não se estiquem */
            max-width: 150px; /* Limita a largura do botão para evitar quebra excessiva */
        }

        /* Se quiser ajustar a altura das células de tabela */
        .table td {
            padding: 8px; /* Controle do preenchimento */
            vertical-align: middle; /* Centraliza o conteúdo verticalmente */
        }

        /* Para a coluna de ações, você pode definir a largura */
        .table td:last-child {
            width: 400px; /* Ajuste para a largura da coluna de ações */
        }
        /* Estilo para o botão de ação */
        .btn-acao {
            background-color: #007bff;
            color: white;
            font-size: 14px;
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            margin-bottom: 10px;
            width: 100%; /* Faz o botão "Ação" ocupar toda a largura disponível */
        }

        /* Estilo para o container das ações */
        .acoes {
            display: none; /* Começa oculto */
            margin-top: 10px;
        }

        /* Estilo para os botões ocultos */
        .acoes .btn {
            font-size: 12px;
            padding: 6px 12px;
            margin: 5px;
            border-radius: 6px;
            width: 100%; /* Faz os botões ocuparem a largura disponível */
        }

        /* Ajuste para os botões quando a ação for clicada */
        .acoes .btn:hover {
            transform: scale(1.05);
        }
        .table th:nth-child(3), /* Alvo da coluna 'Motivo' no cabeçalho */
        .table td:nth-child(3) { /* Alvo da coluna 'Motivo' no corpo da tabela */
            width: 250px; /* Largura maior para a coluna de 'Motivo' */
            white-space: nowrap; /* Evitar quebra de linha no conteúdo */
            overflow: hidden; /* Esconder conteúdo que ultrapassa a largura */
            text-overflow: ellipsis; /* Adiciona "..." quando o texto é muito longo */
        }
        /* Inclusão do CSS do Navbar (caso seja necessário) */
        <?php 
            require("../elements/cssNavbar.php");
        ?>
    </style>

</head>

<body>    
    <div id="navbarSt"></div>
        <div class="container d-flex justify-content-center align-items-center">
            <div class="dash bg-white">
                <div class="row p-3">
                    <?php
                        $title = "- Devoluções Estoque";
                        require_once($relative . "elements/tituloProjetoMainSection.php");

                        if ($_SESSION['tipo'] == 1) {
                            echo "
                            <div class='row'>
                                <p class='col-sm-8 offset-sm-2 col-md-11 offset-md-1' style='font-size: 1.5em;text-align: center;'>Selecione um Cliente</p>
                            </div>";
                        }
                    ?>
                </div>
                <div class="row" style="padding: 0px 1em 1em 1em;">
                    <div id="table" class="col-md-12 col-11 offset-1 offset-sm-0 p-2" style="max-width: 100%;">
                        <table class="table table-bordered table-striped mt-3" id="Devolucoes">
                            <thead class="thead-dark">
                                <tr>
                                    <th> </th> <!-- Coluna extra para a seta -->
                                    <th>Fornecedor</th>
                                    <th>Total de Anomalias</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    // Consulta para agrupar pelos fornecedores com anomalias
                                    $sql = "SELECT 
                                        n.fornecedor,
                                        COUNT(a.cod) AS total_anomalias
                                    FROM 
                                        anomalias a
                                    LEFT JOIN 
                                        notas n ON a.nf = n.n_nota
                                    WHERE 
                                        a.devolvida = 'N'
                                        AND a.tipo != 'reentrega'
                                    GROUP BY 
                                        n.fornecedor";

                        

                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        
                                        while ($row = $result->fetch_assoc()) {
                                            $fornecedor = $row['fornecedor'];
                                            $total_anomalias = $row['total_anomalias'];
                                            
                                            echo "<tr>
                                                    <td>
                                                        <button class='btn-toggle arrow arrow-closed' onclick='mostrarAnomalias(\"$fornecedor\")'>Listar</button>
                                                    </td>
                                                    <td>{$fornecedor}</td>
                                                    <td>{$total_anomalias}</td>
                                                    <td>
                                                        <button class='btn btn-export' onclick='exportarExcel(\"$fornecedor\")'>Exportar para Excel</button>
                                                    </td>
                                                </tr>";

                                            // Consulta para exibir as anomalias relacionadas ao fornecedor
                                            echo "<tr id='anomalias-{$fornecedor}' class='anomalias-list' style='display: none;'>
                                                    <td colspan='4'>
                                                        <table class='table table-bordered'>
                                                            <thead>
                                                                <tr>
                                                                    <th>Nota Fiscal</th>
                                                                    <th>Cod Item</th>
                                                                    <th>Motivo</th>
                                                                    <th>Quantidade</th>
                                                                    <th>Unidade</th>
                                                                    <th>Peso</th>
                                                                    <th>Tipo</th>
                                                                    <th>Caminhão</th>
                                                                    <th>Status</th>
                                                                    <th>Devolvida</th> <!-- Coluna Devolvida -->
                                                                    <th>Observação</th>     
                                                                    <th>Ações</th> <!-- Coluna de Ações para o botão -->
                                                                </tr>
                                                            </thead>

                                                            <tbody>";

                                            $anomaliasSql = "SELECT 
                                                                a.nf AS nf_anomalia,
                                                                a.cod_item,
                                                                a.motivo,
                                                                a.quantidade,
                                                                a.unidade,
                                                                a.peso,
                                                                a.tipo,
                                                                a.devolvida,
                                                                a.observacao,
                                                                m.placa_caminhao,
                                                                m.finalizada
                                                            FROM 
                                                                anomalias a
                                                            LEFT JOIN 
                                                                notas n ON a.nf = n.n_nota
                                                            LEFT JOIN 
                                                                monitoramento m ON n.id_monitoramento = m.id
                                                            WHERE 
                                                                n.fornecedor = '$fornecedor'";

                                            $anomaliasResult = $conn->query($anomaliasSql);
                                            while ($anomalia = $anomaliasResult->fetch_assoc()) {
                                                if ($anomalia['tipo'] != 'reentrega' && $anomalia['devolvida'] !='S') { // Verificação para não exibir "Reentrega"
                                                        echo "<tr id='anomalia-{$anomalia['nf_anomalia']}'>
                                                        <td>{$anomalia['nf_anomalia']}</td>
                                                        <td>{$anomalia['cod_item']}</td>
                                                        <td>{$anomalia['motivo']}</td>
                                                        <td>{$anomalia['quantidade']}</td>
                                                        <td>{$anomalia['unidade']}</td>
                                                        <td>{$anomalia['peso']}</td>
                                                        <td>{$anomalia['tipo']}</td>
                                                        <td>{$anomalia['placa_caminhao']}</td>
                                                        <td>" . ($anomalia['finalizada'] ? 'Finalizada' : 'Aberta') . "</td>
                                                        <td id='devolvida-{$anomalia['nf_anomalia']}'>".($anomalia['devolvida'] )."</td>
                                                        <td id='observacao-". $anomalia['nf_anomalia'] . "' contenteditable='true'>" . $anomalia['observacao'] . "</td>
                                                        <td>
                                                            <button class='btn btn-warning' onclick='abrirModal(\"{$anomalia['nf_anomalia']}\")'>Devolvida</button>
                                                        </td>
                                                    </tr>";
                                                }
                                            }
                                            echo "        </tbody>
                                                        </table>
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
   <!-- Modal de Seleção de Data -->
   <div class="modal fade" id="modal-devolvida" tabindex="-1" aria-labelledby="modal-devolvidaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-devolvidaLabel">Escolher Data de Devolução</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="date" id="data-devolucao" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="confirmarData()">Confirmar</button>
            </div>
        </div>
    </div>
</div>




    <script>

        function mostrarAnomalias(fornecedor) {
                console.log("Fornecedor:", fornecedor); // Depuração
                const row = document.getElementById(`anomalias-${fornecedor}`);
                if (row.style.display === "none") {
                    row.style.display = "table-row";
                } else {
                    row.style.display = "none";
                }
        }
         $(document).ready(function() {
            $('#Devolucoes').DataTable();
            console.log("dsadas");
            
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

        function exportarExcel(fornecedor) {
            // Obter a tabela
            var tabela = document.getElementById("Devolucoes");

            // Criar uma nova planilha de Excel
            var wb = XLSX.utils.table_to_book(tabela, {sheet: "Devoluções"});

            // Gerar o nome do arquivo com o nome do fornecedor
            var nomeArquivo = "Devolucoes_" + fornecedor + ".xlsx";

            // Gerar o arquivo Excel e fazer o download
            XLSX.writeFile(wb, nomeArquivo);
        }
// Inicializar o modal
const modal = new bootstrap.Modal(document.getElementById('modal-devolvida'));

// Função para abrir o modal
function abrirModal() {
    modal.show();
}

// Função para fechar o modal
function fecharModal() {
    modal.hide();
}

// Função para confirmar a data e enviar a requisição
function confirmarData() {
    const nf_anomalia = sessionStorage.getItem('nf_anomalia');
    const selectedDate = document.getElementById('data-devolucao').value;

    if (!selectedDate) {
        alert("Por favor, selecione uma data.");
        return;
    }

    // Alterar o texto na tabela para 'S' (antes de enviar a requisição, para melhorar a experiência do usuário)
    document.getElementById('devolvida-' + nf_anomalia).innerText = 'S';

    // Enviar a requisição AJAX para atualizar o banco de dados
    $.ajax({
        url: 'alterarDevolvida.php', // Arquivo PHP que irá atualizar o banco
        type: 'POST',
        data: {
            nf_anomalia: nf_anomalia, // Passa a nota fiscal da anomalia
            devolvida: 'S', // Valor a ser alterado
            data_devolucao: selectedDate // Passa a data escolhida
        },
        success: function(response) {
            alert("Devolvida alterada com sucesso!");
            console.log(response);
            fecharModal();  // Fechar o modal após o sucesso
        },
        error: function() {
            document.getElementById('devolvida-' + nf_anomalia).innerText = 'N';  // Reverter para 'N' em caso de erro
            alert("Erro ao alterar a devolvida.");
            fecharModal();  // Fechar o modal após o erro
        }
    });
}





        // Função para atualizar a observação no banco de dados
        function editarObservacao(nf_anomalia) {
            // Obter o valor da célula de Observação
            var observacao = document.getElementById('observacao-' + nf_anomalia).innerText;

            // Enviar uma requisição AJAX para atualizar a observação no banco de dados
            $.ajax({
                url: 'alterarObservacao.php', // Arquivo PHP que irá atualizar a observação no banco
                type: 'POST',
                data: {
                    nf_anomalia: nf_anomalia, // Passa a nota fiscal da anomalia
                    observacao: observacao // Novo valor da observação
                },
                success: function(response) {
                    console.log(response)
                    alert("Observação atualizada com sucesso!");
                },
                error: function() {
                    console.log(response)
                    alert("Erro ao atualizar a observação.");
                }
            });
            console.log('NF:', nf_anomalia + 'Obs:', observacao)

        }

        // Função para lidar com o evento "Enter" ao editar a observação
        function handleEnterKey(event, nf_anomalia) {
            if (event.key === 'Enter') {
                event.preventDefault();  // Evitar a quebra de linha
                editarObservacao(nf_anomalia);  // Chama a função para salvar a observação
            }
        }

        // Adiciona um listener para a tecla "Enter" em cada célula de observação
        document.querySelectorAll('[id^="observacao-"]').forEach(cell => {
            cell.addEventListener('keydown', function(event) {
                const nf_anomalia = this.id.split('-')[1];
                handleEnterKey(event, nf_anomalia);
            });
        });


        
    </script>
   
   <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
    <!-- Importando jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Importando Bootstrap (se estiver usando o modal do Bootstrap) -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
 
</body>
</html>
