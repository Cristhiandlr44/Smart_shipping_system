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
                                                                <th>NF</th>
                                                                <th>Cliente</th>
                                                                <th>Bairro</th>
                                                                <th>Cidade</th>
                                                                <th>Peso Bruto</th>
                                                                <th> </th>
                                                            </tr>
                            </thead>
                            <tbody>
                                <?php
                            
                                        // Buscando as notas fiscais relacionadas à viagem
                                        $notasSql = "SELECT n.n_nota, c.nome AS cliente, n.bairro, n.cidade, n.peso_bruto 
                                                    FROM notas AS n
                                                    JOIN 
                                                    clientes c ON n.CNPJ = c.CNPJ
                                                    WHERE n.id_monitoramento IS NULL";
                                        $notasResult = $conn->query($notasSql);
                                        
                                        

                                        // Sublista de Notas Fiscais em formato de tabela
                                        
                                        while ($nota = $notasResult->fetch_assoc()) {
                                            echo "<tr>
                                                    <td>{$nota['n_nota']}</td>
                                                    <td>{$nota['cliente']}</td>
                                                    <td>{$nota['bairro']}</td>
                                                    <td>{$nota['cidade']}</td>
                                                    <td>{$nota['peso_bruto']}</td>
                                                    <td>
                                                        <button class='btn-toggle arrow arrow-closed' onclick='mostrarItensNota({$nota['n_nota']})'></button>
                                                    </td>
                                                </tr>";

                                            // Sublista de itens da nota fiscal
                                            $itensSql = "SELECT p.cod, p.descricao, p.quantidade, p.QuantAux, p.data_validade 
                                                        FROM produtos AS p 
                                                        WHERE p.nf = '{$nota['n_nota']}'";
                                            $itensResult = $conn->query($itensSql);

                                            echo "<tr id='itens-{$nota['n_nota']}' class='itens-list'>
                                                    <td colspan='6'>
                                                        <table class='table table-bordered'>
                                                            <thead>
                                                                <tr>
                                                                    <th>Código</th>
                                                                    <th>Descrição</th>
                                                                    <th>Quantidade</th>
                                                                    <th>Volume</th>
                                                                    <th>Data de Validade</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>";
                                            while ($item = $itensResult->fetch_assoc()) {
                                                // Formatando a data de validade
                                                $dataValidade = date('d/m/Y', strtotime($item['data_validade']));
                                                echo "<tr>
                                                        <td>{$item['cod']}</td>
                                                        <td>{$item['descricao']}</td>
                                                        <td>{$item['quantidade']}</td>
                                                        <td>{$item['QuantAux']}</td>
                                                        <td>{$dataValidade}</td>
                                                    </tr>";
                                            }
                                            echo "</tbody></table></td></tr>";
                                        }
                                        echo "</tbody></table></td></tr>";
                                    
                                
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

        function apontarAnomalia(viagemId) {
            viagemIdGlobal = viagemId;
            // Mostrar o modal de anomalia
            $('#anomaliaModal').modal('show');
            $('#anomaliaTipo').val('devolucaoTotal'); // Definir o tipo de anomalia
            mostrarOpcaoAnomalia(); // Mostrar as opções de anomalia

            // Carregar as notas fiscais associadas à viagem
            carregarNotasFiscais(viagemId);
        }


        function carregarNotasFiscais(viagemId) {
            $.ajax({
                url: 'carregarNotas.php', // Arquivo PHP que buscará as notas fiscais
                method: 'GET',
                data: { viagemId: viagemId }, // Passando o ID da viagem
                success: function(response) {
                    // Popular o campo de seleção de notas fiscais
                    const notas = JSON.parse(response);
                    let options = '<option value="">Selecione a Nota Fiscal</option>';
                    
                    // Adicionar as opções de notas fiscais no select
                    notas.forEach(nota => {
                        options += `<option value="${nota.n_nota}">${nota.n_nota}</option>`;
                    });

                    $('#notaFiscal').html(options);
                },
                error: function() {
                    alert("Erro ao carregar as notas fiscais.");
                }
            });
        }

        // Função para carregar os itens quando uma nota fiscal é selecionada
        function carregarItensNota() {
            const notaFiscal = $('#notaFiscal').val(); // Obtemos o valor da nota fiscal selecionada
            // Verificamos se uma nota foi selecionada
            if (notaFiscal) {
                $.ajax({
                    url: 'carregarItens.php', // Arquivo PHP que buscará os itens da nota
                    method: 'GET',
                    data: { notaFiscal: notaFiscal }, // Passando o número da nota fiscal
                    success: function(response) {
                        console.log("Resposta do servidor:", response);
                        const itens = JSON.parse(response);
                        let options = '<option value="">Selecione o Item</option>';

                        // Adicionamos os itens no campo de seleção
                        itens.forEach(item => {
                            options += `<option value="${item.cod}">${item.descricao}</option>`;
                        });

                        // Atualizamos o campo de seleção com os itens
                        $('#item').html(options);
                        console.log(options);
                    },
                    error: function() {
                        alert("Erro ao carregar os itens.");
                        console.log("erro")
                    }
                });
            } else {
                // Se nenhuma nota fiscal for selecionada, limpamos o campo de itens
                $('#item').html('<option value="">Selecione a Nota Fiscal primeiro</option>');
            }
            console.log("Itens chamada");
            
        }

        // Evento de mudança no campo de nota fiscal
        $('#notaFiscal').change(function() {
            carregarItensNota(); // Chama a função para carregar os itens da nota selecionada
        });


        function mostrarOpcaoAnomalia() {
            const tipoAnomalia = $('#anomaliaTipo').val();
            let content = '';
            console.log("TipoAnomalia: ",tipoAnomalia)
            if (tipoAnomalia === 'devolucaoParcial') {
                content = `
                    <div class="form-group">
                        <label for="notaFiscal" >Selecione a Nota Fiscal</label>
                        <select class="form-control" id="notaFiscal" required onchange="carregarItensNota(viagemIdGlobal)">
                            <!-- As opções de notas fiscais serão carregadas dinamicamente -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="item" >Selecione o Item</label>
                        <select class="form-control" id="item" required>
                            <option value="">Selecione a Nota Fiscal primeiro</option>
                            <!-- Os itens serão carregados após a seleção da nota fiscal -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="quantidade"  >Quantidade</label>
                        <input type="number" id="quantidadeAnomalia" name="quantidade" step="any" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="unidade">Selecione a Unidade</label>
                        <select class="form-control" id="unidadeAnomalia">
                            <option value="UN">Selecione</option>
                            <option value="UN">UNIDADE</option>
                            <option value="KG">QUILO</option>
                            <option value="CX">CAIXA</option>
                            <option value="PCT">PACOTE</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="peso">Peso</label>
                        <input type="number" id="pesoAnomalia" name="peso" step="any" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="motivo" >Selecione o Motivo</label>
                        <select class="form-control" id="motivoAnomalia" required>
                            <option value="01-Atraso na Entrega">Atraso na Entrega</option>
                            <option value="02-Avaria na mercadoria">Avaria na mercadoria</option>
                            <option value="03-Boleto vencido ou próximo do vencimento">Boleto vencido ou próximo do vencimento</option>
                            <option value="04-Cliente fechado">Cliente fechado</option>
                            <option value="05-Cliente não esta mais no local">Cliente não esta mais no local</option>
                            <option value="06-Cliente não fez o pedido">Cliente não fez o pedido</option>
                            <option value="07-Cliente não tem espaço para receber">Cliente não tem espaço para receber</option>
                            <option value="08-Cliente se recusou a receber">Cliente se recusou a receber</option>
                            <option value="09-Falta de vácuo">Falta de vácuo</option>
                            <option value="10-Falta da mercadoria">Falta da mercadoria</option>
                            <option value="11-Impossibilitado de chegar ao cliente">Impossibilitado de chegar ao cliente</option>
                            <option value="12-Mercadoria Invertida dentro da caixa">Mercadoria Invertida dentro da caixa</option>
                            <option value="13-Mercadoria descongelada">Mercadoria descongelada</option>
                        </select>
                    </div>
                `;
            } else if (tipoAnomalia === 'devolucaoTotal') {
                content = `
                    <div class="form-group">
                        <label for="notaFiscal" required>Selecione a Nota Fiscal</label>
                        <select class="form-control" id="notaFiscal"  onchange="carregarItensNota()">
                            <!-- As opções de notas fiscais serão carregadas dinamicamente -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="motivo" required>Selecione o Motivo</label>
                        <select class="form-control" id="motivoAnomalia">
                            <option value="01-Atraso na Entrega">Atraso na Entrega</option>
                            <option value="02-Avaria na mercadoria">Avaria na mercadoria</option>
                            <option value="03-Boleto vencido ou próximo do vencimento">Boleto vencido ou próximo do vencimento</option>
                            <option value="04-Cliente fechado">Cliente fechado</option>
                            <option value="05-Cliente não esta mais no local">Cliente não esta mais no local</option>
                            <option value="06-Cliente não fez o pedido">Cliente não fez o pedido</option>
                            <option value="07-Cliente não tem espaço para receber">Cliente não tem espaço para receber</option>
                            <option value="08-Cliente se recusou a receber">Cliente se recusou a receber</option>
                            <option value="09-Falta de vácuo">Falta de vácuo</option>
                            <option value="10-Falta da mercadoria">Falta da mercadoria</option>
                            <option value="11-Impossibilitado de chegar ao cliente">Impossibilitado de chegar ao cliente</option>
                            <option value="12-Mercadoria Invertida dentro da caixa">Mercadoria Invertida dentro da caixa</option>
                            <option value="13-Mercadoria descongelada">Mercadoria descongelada</option>
                        </select>
                    </div>
                `;
            } else if (tipoAnomalia === 'reentrega') {
                content = `
                    <div class="form-group">
                        <label for="notaFiscal" required>Selecione a Nota Fiscal</label>
                        <select class="form-control" id="notaFiscal"  onchange="carregarItensNota()">
                            <!-- As opções de notas fiscais serão carregadas dinamicamente -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="motivo" required>Selecione o Motivo</label>
                        <select class="form-control" id="motivoAnomalia">
                            <option value="01-Atraso na Entrega">Atraso na Entrega</option>
                            <option value="02-Avaria na mercadoria">Avaria na mercadoria</option>
                            <option value="03-Boleto vencido ou próximo do vencimento">Boleto vencido ou próximo do vencimento</option>
                            <option value="04-Cliente fechado">Cliente fechado</option>
                            <option value="05-Cliente não esta mais no local">Cliente não esta mais no local</option>
                            <option value="06-Cliente não fez o pedido">Cliente não fez o pedido</option>
                            <option value="07-Cliente não tem espaço para receber">Cliente não tem espaço para receber</option>
                            <option value="08-Cliente se recusou a receber">Cliente se recusou a receber</option>
                            <option value="09-Falta de vácuo">Falta de vácuo</option>
                            <option value="10-Falta da mercadoria">Falta da mercadoria</option>
                            <option value="11-Impossibilitado de chegar ao cliente">Impossibilitado de chegar ao cliente</option>
                            <option value="12-Mercadoria Invertida dentro da caixa">Mercadoria Invertida dentro da caixa</option>
                            <option value="13-Mercadoria descongelada">Mercadoria descongelada</option>
                        </select>
                    </div>
                `;
            }

            $('#anomaliaOptions').html(content);
        }
        function salvarAnomalia() {
            var nf = document.getElementById('notaFiscal') ? document.getElementById('notaFiscal').value : null; // Verifica se o elemento existe
            var tipo = document.getElementById('anomaliaTipo').value;
            var motivo = document.getElementById('motivoAnomalia').value;
            var quantidade = parseFloat(document.getElementById('quantidadeAnomalia') ? document.getElementById('quantidadeAnomalia').value : 0);
            var unidade = document.getElementById('unidadeAnomalia') ? document.getElementById('unidadeAnomalia').value : null;
            var peso = parseFloat(document.getElementById('pesoAnomalia') ? document.getElementById('pesoAnomalia').value : 0);
            var item = document.getElementById('item') ? document.getElementById('item').value : null;

            if(tipo === 'devolucaoParcial') {
                // Validações básicas (campos preenchidos)
                if (!quantidade || quantidade <= 0 || !peso || peso <= 0) {
                    alert("Os campos de quantidade e peso devem ser preenchidos corretamente.");
                    return;
                }

                // Fazendo uma chamada AJAX para buscar os valores máximos no backend
                $.ajax({
                    url: 'buscarMaximos.php', // Endpoint PHP que consulta o banco
                    type: 'GET',
                    data: { notaFiscal: nf, item: item },
                    success: function(response) {
                        try {
                            const data = response;

                            if (data.success) {
                                const maxQuantidade = parseFloat(data.maxQuantidade);
                                const maxPeso = parseFloat(data.maxPeso);
                                const itemCodigo= item; 
                                // Validando com os valores máximos
                                if (quantidade > maxQuantidade) {
                                    alert(`A quantidade informada (${quantidade}) não pode ser maior do que a disponível (${maxQuantidade}).`);
                                    return;
                                }
                                if (peso > maxPeso) {
                                    alert(`O peso informado (${peso}) não pode ser maior do que o disponível (${maxPeso}).`);
                                    return;
                                }

                                // Se a validação passou, continua o salvamento
                                enviarAnomalia({ nf, tipo, motivo, quantidade, unidade, peso, itemCodigo });
                            } else {
                                alert("Não foi possível buscar os valores máximos. Tente novamente.");
                            }
                        } catch (e) {
                            console.error("Erro ao processar a resposta do servidor:", e);
                            alert("Erro ao processar a resposta do servidor.");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Erro na requisição AJAX:", error);
                        alert("Erro ao buscar os valores máximos.");
                    }
                });
            } else if(tipo === 'devolucaoTotal') {
                // Validação básica (campo preenchido)
                if (!nf) {
                    alert("O campo da nota fiscal não pode estar vazio.");
                    return;
                }

                // Fazendo uma chamada AJAX para buscar todos os itens da nota fiscal
                $.ajax({
                    url: 'buscarItensNotaFiscal.php',  // Arquivo PHP que vai consultar os itens da nota fiscal
                    type: 'GET',
                    data: { notaFiscal: nf },
                    success: function(response) {
                        console.log(response);
                        try {
                            const data = response;
                            console.log(data);
                            if (data.success) {
                                // Criando anomalias para cada item da nota fiscal
                                data.itens.forEach(item => {
                                    const quantidade = item.quantidade;
                                    const peso = item.peso;
                                    const unidade = item.unidade;
                                    const itemCodigo = item.cod;
                                    
                                    // Criando anomalias
                                    enviarAnomalia({ nf, tipo, motivo, itemCodigo, quantidade, unidade, peso });
                                });
                                alert("Anomalias criadas com sucesso!");
                            } else {
                                alert("Erro ao buscar itens da nota fiscal.");
                            }
                        } catch (e) {
                            console.error("Erro ao processar a resposta do servidor:", e);
                            alert("Erro ao processar a resposta do servidor.");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Erro na requisição AJAX:", error);
                        alert("Erro ao buscar itens da nota fiscal.");
                    }
                });
            }else if(tipo === 'reentrega'){
                if (!nf) {
                    alert("O campo da nota fiscal não pode estar vazio.");
                    return;
                }
                
                // Confirmar se o usuário deseja realmente remover o Id_monitoramento
                 if (confirm("Você deseja realmente remover o monitoramento dessa nota fiscal?")) {
                 // Fazer uma chamada AJAX para apagar o Id_monitoramento
                 $.ajax({
                    url: 'removerMonitoramento.php', // Arquivo PHP que remove o monitoramento
                    type: 'POST',
                    data: { notaFiscal: nf },
                    success: function(response) {
                        try {
                            // Se a resposta for um JSON válido
                            const data = JSON.parse(response); // Caso a resposta seja uma string JSON
                            // Verificando se a resposta contém sucesso
                            if (data.success === true) {
                                alert("Reentrega processada com sucesso!");
                            } else {
                                
                                alert("Erro ao remover o monitoramento.");
                            }
                        } catch (e) {
                            console.error("Erro ao processar a resposta do servidor:", e);
                            alert("Erro ao processar a resposta do servidor.");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Erro na requisição AJAX:", error);
                        alert("Erro ao remover o monitoramento.");
                    }
                });


                // Fazendo uma chamada AJAX para buscar todos os itens da nota fiscal
                $.ajax({
                    url: 'buscarItensNotaFiscal.php',  // Arquivo PHP que vai consultar os itens da nota fiscal
                    type: 'GET',
                    data: { notaFiscal: nf },
                    success: function(response) {
                        console.log(response);
                        try {
                            const data = response;
                            if (data.success) {
                                // Criando anomalias para cada item da nota fiscal
                                data.itens.forEach(item => {
                                    const quantidade = item.quantidade;
                                    const peso = item.peso;
                                    const unidade = item.unidade;
                                    const itemCodigo = item.cod;
                                    
                                    // Criando anomalias
                                    enviarAnomalia({ nf, tipo, motivo, itemCodigo, quantidade, unidade, peso });
                                });
                                alert("Anomalias criadas com sucesso!");
                            } else {
                                alert("Erro ao buscar itens da nota fiscal.");
                            }
                        } catch (e) {
                            console.error("Erro ao processar a resposta do servidor:", e);
                            alert("Erro ao processar a resposta do servidor.");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Erro na requisição AJAX:", error);
                        alert("Erro ao buscar itens da nota fiscal.");
                    }
                });

            }
        }

    }
    
        // Função separada para enviar a anomalia ao backend
        function enviarAnomalia(data) {
            $.ajax({
                url: 'salvarAnomalia.php',
                type: 'POST',
                data: data,
                success: function(response) {
                    console.log(response);
                    try {
                        var res = JSON.parse(response);
                        if (res.success) {
                            $('#anomaliaModal').modal('hide');
                            alert("Anomalia salva com sucesso!");
                        } else {
                            alert("Erro ao salvar anomalia.");
                        }
                    } catch (e) {
                        console.error("Erro ao processar a resposta do servidor:", e);
                        alert("Erro ao salvar anomalia*.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Erro ao salvar anomalia:", error);
                }
            });
        }

        function listarAnomalias(idMonitoramento) {
            if (!idMonitoramento) {
                alert("ID do monitoramento não encontrado.");
                return;
            }

            // Faz uma requisição AJAX para buscar os números das notas fiscais a partir do ID do monitoramento
            $.ajax({
                url: 'buscarNotaFiscal.php', // Arquivo PHP que retorna os números das notas fiscais
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
                url: 'listarAnomalias.php', // Arquivo PHP que retorna as anomalias
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


        function excluirAnomalia(id) {
            if (!id) {
                alert("ID inválido para exclusão.");
                return;
            }

            if (!confirm("Tem certeza de que deseja excluir esta anomalia?")) {
                return; // Cancelar a exclusão se o usuário não confirmar
            }

            // Enviar requisição AJAX para excluir a anomalia
            $.ajax({
                url: 'excluirAnomalia.php', // Arquivo PHP que processa a exclusão
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    try {
                        const data = JSON.parse(response);
                        if (data.success) {
                            alert("Anomalia excluída com sucesso.");
                            
                            // Remover a anomalia da lista na interface
                            const liToRemove = document.querySelector(`button[onclick="excluirAnomalia(${id})"]`).closest('li');
                            if (liToRemove) {
                                liToRemove.remove();
                            }
                        } else {
                            alert("Erro ao excluir a anomalia: " + data.message);
                        }
                    } catch (e) {
                        console.error("Erro ao processar a resposta:", e);
                        alert("Erro ao excluir a anomalia.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Erro na requisição AJAX:", error);
                    alert("Erro ao excluir a anomalia.");
                }
            });
        }
        function excluirAnomaliaTotal(id, nf) {
            console.log(id, nf);  // Verificar se os parâmetros estão corretos

            if (!id) {
                alert("ID inválido para exclusão.");
                return;
            }

            if (!confirm("Tem certeza de que deseja excluir esta anomalia?")) {
                return; // Cancelar a exclusão se o usuário não confirmar
            }

            // Enviar requisição AJAX para excluir a anomalia
            $.ajax({
                url: 'excluirAnomaliaTotal.php', // Arquivo PHP que processa a exclusão
                type: 'POST',
                data: { id: id, nf: nf },
                success: function(response) {
                    console.log(response)
                    try {
                        const data = JSON.parse(response);
                        if (data.success) {
                            alert("Anomalia excluída com sucesso.");
                            
                            // Remover a anomalia da lista na interface
                            const liToRemove = document.querySelector(`button[onclick="excluirAnomaliaTotal(${id}, '${nf}')"]`).closest('li');
                            if (liToRemove) {
                                liToRemove.remove();
                            }
                        } else {
                            alert("Erro ao excluir a anomalia: " + data.message);
                        }
                    } catch (e) {
                        console.error("Erro ao processar a resposta:", e);
                        alert("Erro ao excluir a anomalia.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Erro na requisição AJAX:", error);
                    alert("Erro ao excluir a anomalia.");
                }
            });
        }

        function finalizarViagem(id) {
    if (!confirm("Tem certeza de que deseja finalizar esta viagem?")) {
        return; // Cancela se o usuário não confirmar
    }

    // Cria o modal para o input de data
    const modal = document.createElement("div");
    modal.style.position = "fixed";
    modal.style.top = "50%";
    modal.style.left = "50%";
    modal.style.transform = "translate(-50%, -50%)";
    modal.style.zIndex = "9999";
    modal.style.backgroundColor = "#fff";
    modal.style.boxShadow = "0 4px 15px rgba(0, 0, 0, 0.2)";
    modal.style.padding = "20px";
    modal.style.textAlign = "center";
    modal.style.borderRadius = "12px";
    modal.style.width = "320px";
    modal.style.fontFamily = "'Arial', sans-serif";
    modal.style.color = "#333";

    // Adiciona o conteúdo do modal
    modal.innerHTML = `
        <h3 style="margin-bottom: 15px; font-size: 18px; color: #444;">Finalizar Viagem</h3>
        <p style="margin-bottom: 10px; font-size: 14px; color: #666;">Selecione a data de entrega:</p>
        <input type="date" id="dataEntrega" style="margin-bottom: 20px; padding: 10px; width: 90%; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
        <div>
            <button id="confirmarData" style="background-color: #28a745; color: #fff; border: none; padding: 10px 15px; border-radius: 8px; cursor: pointer; margin-right: 10px; font-size: 14px;">
                Confirmar
            </button>
            <button id="cancelar" style="background-color: #dc3545; color: #fff; border: none; padding: 10px 15px; border-radius: 8px; cursor: pointer; font-size: 14px;">
                Cancelar
            </button>
        </div>
    `;

    // Adiciona o modal ao corpo
    document.body.appendChild(modal);

    // Botão para confirmar a data
    document.getElementById("confirmarData").addEventListener("click", function () {
        const dataEntrega = document.getElementById("dataEntrega").value;

        if (!dataEntrega) {
            alert("Por favor, selecione uma data.");
            return;
        }

        // Faz o AJAX com a data selecionada
        $.ajax({
            url: 'finalizarViagem.php',
            type: 'POST',
            data: { id: id, dataEntrega: dataEntrega },
            success: function (response) {
                try {
                    const data = JSON.parse(response);
                    if (data.success) {
                        alert("Viagem finalizada com sucesso.");
                        location.reload(); // Atualiza a página
                    } else {
                        alert("Erro ao finalizar viagem: " + data.message);
                    }
                } catch (e) {
                    console.error("Erro ao processar a resposta:", e);
                    alert("Erro ao finalizar a viagem.");
                }
            },
            error: function (xhr, status, error) {
                console.error("Erro na requisição AJAX:", error);
                alert("Erro ao finalizar viagem.");
            }
        });

        // Remove o modal
        document.body.removeChild(modal);
    });

    // Botão para cancelar
    document.getElementById("cancelar").addEventListener("click", function () {
        document.body.removeChild(modal);
    });
}




        let idViagemSelecionada = null; // Armazena o ID da viagem para alteração

    // Abrir modal e carregar placas disponíveis
        function alterarPlaca(id) {
            console.log("clicou")
            idViagemSelecionada = id; // Define o ID da viagem selecionada
            $('#placaSelect').empty(); // Limpa as opções do select

            // Requisição AJAX para buscar as placas disponíveis
            $.ajax({
                url: 'buscarPlacas.php', // Arquivo que retorna as placas disponíveis
                type: 'GET',
                success: function(response) {
                    try {
                        const data = JSON.parse(response);
                        if (data.success) {
                            // Adicionar opções ao select
                            data.placas.forEach(placa => {
                                $('#placaSelect').append(`<option value="${placa}">${placa}</option>`);
                            });
                            // Exibir o modal
                            $('#alterarPlacaModal').modal('show');
                        } else {
                            alert("Erro ao carregar placas: " + data.message);
                        }
                    } catch (e) {
                        console.error("Erro ao processar a resposta:", e);
                        alert("Erro ao carregar placas.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Erro na requisição AJAX:", error);
                    alert("Erro ao buscar placas disponíveis.");
                }
            });
        }

        // Salvar nova placa
        $('#salvarPlacaBtn').click(function() {
            const novaPlaca = $('#placaSelect').val();

            if (!novaPlaca) {
                alert("Selecione uma placa para continuar.");
                return;
            }

            // Enviar requisição para atualizar a placa
            $.ajax({
                url: 'alterarPlaca.php', // Arquivo PHP para realizar a alteração
                type: 'POST',
                data: {
                    id: idViagemSelecionada,
                    placa: novaPlaca
                },
                success: function(response) {
                    try {
                        const data = JSON.parse(response);
                        if (data.success) {
                            alert("Placa alterada com sucesso!");
                            location.reload(); // Atualizar a página
                        } else {
                            alert("Erro ao alterar placa: " + data.message);
                        }
                    } catch (e) {
                        console.error("Erro ao processar a resposta:", e);
                        alert("Erro ao alterar a placa.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Erro na requisição AJAX:", error);
                    alert("Erro ao salvar a nova placa.");
                }
            });
        });

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
