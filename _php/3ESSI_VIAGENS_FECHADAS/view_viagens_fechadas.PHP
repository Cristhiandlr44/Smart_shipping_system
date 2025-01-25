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
                        $title = "- Viagens Abertas - Monitoramento";
                        require_once($relative . "elements/tituloProjetoMainSection.php");

                        if ($_SESSION['tipo'] == 1) {
                            echo "
                            <div class='row'>
                                <p class='col-sm-8 offset-sm-2 col-md-11 offset-md-1' style='font-size: 1.5em;text-align: center;'>Selecione uma viagem para monitoramento</p>
                            </div>";
                        }
                    ?>
                </div>
                <div class="row" >
                    <div id="table" class="table table-striped table-bordered text-nowrap" style="max-width: 100%;">
                        <table class="table table-striped table-bordered text-nowrap" id="viagensAbertasTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th> </th> <!-- Coluna extra para a seta -->
                                    <th>ID</th>
                                    <th>Motorista</th>
                                    <th>Caminhão</th>
                                    <th>Data de Partida</th>
                                    <th>Data de Finalização</th>
                                    <th>Observações</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Consulta principal com filtro de `finalizada IS NULL`
                                $sql = "SELECT m.id, mo.nome AS motorista, mc.fk_placa AS placa_caminhao, m.data_finalizacao, m.largada, m.observacoes, m.finalizada
                                        FROM monitoramento AS m
                                        JOIN motorista_caminhoes AS mc ON m.placa_caminhao = mc.fk_placa
                                        JOIN motorista AS mo ON mc.fk_cpf_motorista = mo.CPF_motorista
                                        WHERE m.finalizada = 'S'";

                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        // Formatando a data
                                        $dataLargada = date('d/m/Y', strtotime($row['largada']));
                                        $dataFinalizacao = date('d/m/Y', strtotime($row['data_finalizacao']));

                                      
                                        echo "<tr>
                                                    <td>
                                                        <button class='btn-toggle arrow arrow-closed' onclick='mostrarNotasFiscais({$row['id']})'></button>
                                                    </td>
                                                    <td>{$row['id']}</td>
                                                    <td>{$row['motorista']}</td>
                                                    <td>{$row['placa_caminhao']}</td>
                                                    <td>{$dataLargada}</td>
                                                    <td>{$dataFinalizacao}</td>
                                                    <td>{$row['observacoes']}</td>
                                                    <td>
                                                        <!-- Botão de Ação -->
                                                        <button class='btn btn-acao' onclick='toggleAcoes({$row['id']})'>Ação</button>
                                            
                                                        <!-- Div contendo os botões de ação -->
                                                        <div class='acoes-{$row['id']}' style='display: none;'>
                                                            <button class='btn btn-anomalia' onclick='reabrirViagem({$row['id']})'>Reabrir Viagem</button>
                                                            <button class='btn btn-anomalia' onclick='listarAnomalias({$row['id']})'>Listar Anomalia</button>
                                                           
                                                        </div>
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
    <div class="modal fade" id="anomaliaModal" tabindex="-1" aria-labelledby="anomaliaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="anomaliaModalLabel">Apontar Anomalia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick = "fecharTela()"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="anomaliaTipo">Tipo de Anomalia</label>
                        <select class="form-control" id="anomaliaTipo" onchange="carregarNotasFiscais(viagemIdGlobal); mostrarOpcaoAnomalia();">
                            <option value="devolucaoParcial">Devolução Parcial</option>
                            <option value="devolucaoTotal">Devolução Total</option>
                            <option value="reentrega">Reentrega</option>
                        </select>
                    </div>
                    <div id="anomaliaOptions"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick = "fecharTela()">Fechar </button>
                    <button type="button" class="btn btn-primary" id="saveAnomalia" onclick="salvarAnomalia()">Salvar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para listar as anomalias -->
    <div class="modal fade" id="listarAnomaliasModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Anomalias</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick = "fecharTela()"></button>
                </div>
                <div class="modal-body">
                    <ul id="anomaliasUl" class="list-group">
                        <!-- Anomalias serão inseridas aqui -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Alterar Placa -->
    <div class="modal fade" id="alterarPlacaModal" tabindex="-1" aria-labelledby="alterarPlacaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alterarPlacaModalLabel">Alterar Placa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="alterarPlacaForm">
                <div class="mb-3">
                    <label for="placaSelect" class="form-label">Selecione a Nova Placa</label>
                    <select class="form-select" id="placaSelect" name="placa" required>
                    <!-- Opções serão carregadas dinamicamente -->
                    </select>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="salvarPlacaBtn">Salvar</button>
            </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="itensModal" tabindex="-1" role="dialog" aria-labelledby="itensModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                            <h5 class="modal-title" id="itensModalLabel">Itens da Viagem</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
            </div>
            <div class="modal-body" id="itensList">
                        <!-- Itens da viagem serão listados aqui -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary">Salvar</button>
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
        function toggleAcoes(id) {
            const acoes = document.querySelector('.acoes-' + id);
            if (acoes.style.display === 'none' || acoes.style.display === '') {
                acoes.style.display = 'block';
            } else {
                acoes.style.display = 'none';
            }
        }

        let viagemIdGlobal = null;  // Variável global para armazenar o ID da viagem

        

        function listarAnomalias(idMonitoramento) {
            if (!idMonitoramento) {
                alert("ID do monitoramento não encontrado.");
                return;
            }

            // Faz uma requisição AJAX para buscar os números das notas fiscais a partir do ID do monitoramento
            $.ajax({
                url: '../3ESSI_VIAGENS_ABERTA/buscarNotaFiscal.php', // Arquivo PHP que retorna os números das notas fiscais
                type: 'GET',
                data: { idMonitoramento: idMonitoramento },
                success: function(response) {
                    try {
                        const data = JSON.parse(response);

                        if (data.success) {
                            const notasFiscais = data.notasFiscais; // Lista de notas fiscais
                            console.log("Notas fiscais encontradas:", notasFiscais);

                            // Chama a função para listar as anomalias para cada nota fiscal
                            notasFiscais.forEach(function(nf) {
                                console.log("salve");
                                listarAnomaliasPorNotaFiscal(nf); // Lista as anomalias para cada nota fiscal
                            });

                        } else {
                            alert("Não foi possível recuperar as notas fiscais.");
                        }
                    } catch (e) {
                        console.error("Erro ao processar a resposta:", e);
                        alert("Erro ao processar a resposta.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Erro na requisição AJAX:", error);
                    alert("Erro ao buscar as notas fiscais.");
                }
            });
        }

        function listarAnomaliasPorNotaFiscal(nf) {
            // Limpar a lista ao iniciar a função
            const anomaliasUl = document.getElementById('anomaliasUl');
            if (anomaliasUl) {
                anomaliasUl.innerHTML = ''; // Garante que a lista começa limpa
            }

            // Solicitação AJAX
            $.ajax({
                url: '../3ESSI_VIAGENS_ABERTA/listarAnomalias.php', // Arquivo PHP que retorna as anomalias
                type: 'GET',
                data: { notaFiscal: nf },
                success: function(response) {
                    try {
                        const data = JSON.parse(response);
                        if (data.success) {
                            const anomalias = data.anomalias;
                            console.log(anomalias)

                            // Agrupando "DevoluçãoTotal" e "Reentrega" por nota e motivo
                            const agrupadas = {};   
                            anomalias.forEach(anomalia => {
                                if (anomalia.tipo === "devolucaoTotal" || anomalia.tipo === "reentrega") {
                                    const chave = `${anomalia.tipo}_${anomalia.motivo}`;
                                    if (!agrupadas[chave]) {
                                        agrupadas[chave] = {
                                            tipo: anomalia.tipo,
                                            motivo: anomalia.motivo,
                                            cod: anomalia.cod
                                        };
                                    }
                                } else if (anomalia.tipo === "devolucaoParcial") {

                                    // Listar devolução parcial individualmente
                                    const li = document.createElement('li');
                                    li.className = 'list-group-item d-flex flex-column align-items-start mb-3';
                                    li.innerHTML = `
                                        <div class="card p-3">
                                            <h5 class="card-title">Nota Fiscal: ${nf}</h5>
                                            <p class="card-text">
                                                <strong>Código do Item:</strong> ${anomalia.cod_item}<br>
                                                <strong>Tipo:</strong> ${anomalia.tipo}<br>
                                                <strong>Motivo:</strong> ${anomalia.motivo}<br>
                                                <strong>Quantidade:</strong> ${anomalia.quantidade} ${anomalia.unidade}
                                            </p>
                                            <div class="text-end">
                                                <button class="btn btn-danger btn-sm" onclick="excluirAnomalia(${anomalia.cod})">Excluir</button>
                                            </div>
                                        </div>`;
                                    anomaliasUl.appendChild(li);
                                }
                            });

                            // Adicionar "DevoluçãoTotal" e "Reentrega" agrupadas
                            Object.values(agrupadas).forEach(anomalia => {
                                const li = document.createElement('li');
                                li.className = 'list-group-item d-flex flex-column align-items-start mb-3';
                                li.innerHTML = `
                                    <div class="card p-3">
                                        <h5 class="card-title">Nota Fiscal: ${nf}</h5>
                                        <p class="card-text">
                                            <strong>Tipo:</strong> ${anomalia.tipo}<br>
                                            <strong>Motivo:</strong> ${anomalia.motivo}
                                        </p>
                                        <div class="text-end">
                                                <button class="btn btn-danger btn-sm" onclick="excluirAnomaliaTotal(${anomalia.cod}, '${nf}')">Excluir</button>
                                            </div>
                                    </div>`;
                                anomaliasUl.appendChild(li);
                                console.log("COD: ",anomalia.cod);
                            });

                            // Exibe o modal
                            $('#listarAnomaliasModal').modal('show');
                        } else {
                            alert("Nenhuma anomalia encontrada.");
                        }
                    } catch (e) {
                        console.error("Erro ao processar a resposta:", e);
                        alert("Erro ao listar as anomalias.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Erro na requisição AJAX:", error);
                    alert("Erro ao listar as anomalias.");
                }
            });
        }

        function reabrirViagem(id) {
            if (!confirm("Tem certeza de que deseja reabrir esta viagem?")) {
                return; // Cancelar a finalização se o usuário não confirmar
            }
            console.log("ID:", id)
            // Enviar requisição AJAX para finalizar a viagem e registrar a data de entrega
            $.ajax({
                url: 'reabrirViagem.php', // Arquivo PHP que processa a finalização
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    console.log(Response)
                    try {
                        const data = JSON.parse(response);
                        if (data.success) {
                            alert("Viagem Reaberta com sucesso.");
                            location.reload(); // Atualizar a página
                        } else {
                            alert("Erro ao Reabrir viagem: " + data.message);
                        }
                    } catch (e) {
                        console.error("Erro ao processar a resposta:", e);
                        alert("Erro ao Reabrir a viagem.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Erro na requisição AJAX:", error);
                    alert("Erro ao Reabrir viagem.");
                }
            });
        }

        let idViagemSelecionada = null; // Armazena o ID da viagem para alteração

  
            function fecharTela(){
                $('#anomaliaModal').modal('hide');
                $('#listarAnomaliasModal').modal('hide');
            }
            console.log("viagemIdGlobal: ", viagemIdGlobal);
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
    <!-- Importando jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Importando Bootstrap (se estiver usando o modal do Bootstrap) -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
