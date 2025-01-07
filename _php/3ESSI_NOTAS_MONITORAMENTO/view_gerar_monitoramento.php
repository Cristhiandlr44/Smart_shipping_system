<?php
    $relative = "../";
    require_once($relative."CRUD/relog.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motoristas & Caminhões</title>
    <link rel="shortcut icon" href="../../_assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../_style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../../_scriptjs/script_get_products.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <style>
        <?php 
            require("../elements/cssNavbar.php");
        ?>
    </style>
</head>
<body>
    <div id="navbarSt"></div>
    <div class="container d-flex justify-content-center align-items-center">
        <div id="dash" class="inicial dash bg-white p-4 col-12">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="cancelaMonitoramento(id_monitoramento)"></button>
            <div class="row">
                <?php
                    $title = "- Placas";
                    require_once($relative. "elements/tituloProjetoMainSection.php");
                ?>
            </div>
            <div id="motorist_caminho"></div>
            <div class="row">

                    <!-- Cards das Rotas -->
                    <div id="cardsCaminhoes" class="d-flex flex-wrap gap-3">
                        <!-- Cards gerados dinamicamente via JavaScript -->
                    </div>
                </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>

    <script>
        $(document).ready(() => {
            $.ajax({
                url: '../3ESSI_MOTORIST_CAMINHOES/control_view_motoristas_caminhoes.php',
                type: 'POST',
                data: {},
                success: (resposts) => {
                    var confirm = '';
                    // Agora você pode acessar os dados e chamar a função corretamente
                    console.log(resposts); // Aqui você consegue ver os dados retornados pela requisição AJAX

                    for (const row of resposts) {
                        carregarCardsCaminhoes(row['placa_caminhao'], row['nome_motorista'], row['cpf_motorista'], row['modelo']);
                    }
                },
                error: (error) => {
                    console.error('Erro na requisição:', error);
                }
            });
        });
        function getUrlParameter(name) {
            name = name.replace(/[[]/, '\\[').replace(/[\]]/, '\\]');
            var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
            var results = regex.exec(location.search);
            return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
        }

        // Capturar o id_monitoramento da URL usando a função getUrlParameter
        var id_monitoramento = getUrlParameter('id_monitoramento');
        // Inicializar os cards dos caminhoes
        function carregarCardsCaminhoes(placa, motorista, cpf, modelo) {
            const cardsContainer = document.getElementById("cardsCaminhoes");
            const card = document.createElement("div");
            card.className = "card p-3";
            card.style.width = "18rem";
            card.innerHTML = `
                <h5 class="card-title">${placa}</h5>
                <p>Motorista: ${motorista}</p>
                <p>CPF: ${cpf}</p>
                <p>Modelo: ${modelo}</p>
                <button class="btn btn-success" onclick= "selecionarCaminhao('${placa}', '${cpf}','${id_monitoramento}')"=>Selecionar</button>
            `;

            // Adiciona o novo card sem apagar os anteriores
            cardsContainer.appendChild(card);
        }

        function selecionarCaminhao(placa, cpf, id_monitoramento) {
            console.log("entrou");

            // Primeira requisição: consulta disponibilidade
            $.ajax({
                url: 'consulta_disponibilidade.php',
                type: 'GET',
                data: {
                    placa: placa // Envia a placa para a consulta
                },
                success: function(response) {
                    console.log(response);

                    // Resposta é uma lista de placas com viagens em andamento
                    var placasEmViagem = response; // Lista de placas em viagem

                    // Verifica se a placa já está em viagem
                    if (placasEmViagem.includes(placa)) {
                        // Se a placa estiver na lista, exibe o alerta
                        if (confirm("Este veículo já tem uma viagem em aberto. Deseja continuar?")) {
                            // Se o usuário confirmar, executa a alteração
                            alterarPlaca(placa, cpf, id_monitoramento);
                        }
                    } else {
                        // Se a placa não estiver em viagem, executa a alteração imediatamente
                        alterarPlaca(placa, cpf, id_monitoramento);
                    }
                }
            });
        }

        function alterarPlaca(placa, cpf, id_monitoramento) {
            // Segunda requisição: altera a placa e faz o monitoramento
            $.ajax({
                url: 'AlteraPlaca.php',
                type: 'POST',
                data: {
                    placa: placa,
                    cpf: cpf,
                    id_monitoramento: id_monitoramento
                },
                success: function(response) {
                    console.log(response);

                    // Após a resposta bem-sucedida, redireciona para a página de monitoramento
                    alert("Monitoramento criado com sucesso!");
                    window.location.href = 'view_get_notas_2.php';
                }
            });
        }

        function cancelaMonitoramento(id_monitoramento){
            $.ajax({
                url: 'cancela_Monitoramento.php',
                type: 'POST',
                data: {
                    id: id_monitoramento
                },
                success: function(response) {
                    console.log(response);

                    // Após a resposta bem-sucedida, redireciona para a página de monitoramento
                    alert("Monitoramento cancelado com sucesso!");
                    window.location.href = 'view_get_notas_2.php';
                }
            });
        }
    </script>
</body>
</html>
