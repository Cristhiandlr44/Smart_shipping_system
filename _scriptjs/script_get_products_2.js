$(document).ready(() => {
    $.ajax({
        url: '../elements/E_navbar.php',
        type: 'POST',
        data: {
            relative: '../' // Passe o ID desejado aqui
        },
        success: (result) => {
            $("#navbarSt").html(result);
        }
    });

    // Carregar as datas com monitoramento
    $.ajax({
        url: 'control_get_monitoramentos.php', // Endpoint para pegar as datas com monitoramento
        type: 'GET',
        success: (dados) => {
            if (dados && dados.length > 0) {
                dados.forEach((data) => {
                    $('#dataList').append(`
                        <button class="btn btn-outline-primary m-2" onclick="abrirModalResumo('${data.largada}')">
                            ${data.largada}
                        </button>
                    `);
                });
            }
            console.log(dados);
        },
        error: function (xhr, status, error) {
            console.error("Erro ao carregar as datas:", status, error);
        }
    });

    // Função para abrir o modal de resumo
    window.abrirModalResumo = (dataLancamento) => {
        $.ajax({
            url: 'control_get_resumo.php',
            type: 'POST',
            data: { dataLancamento: dataLancamento },
            success: (dados) => {
                console.log(dados);
                let resumos = '';
                dados.forEach((monitoramento) => {
                    resumos += `
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Placa: ${monitoramento.placa}</h5>
                                <p class="card-text">Peso Total: ${monitoramento.pesoTotal} kg</p>
                                <p class="card-text">Quantidade: ${monitoramento.quantidadeEntrega}</p>
                                <button class="btn btn-primary" 
                                        onclick="abrirModalDetalhado('${monitoramento.placa}', '${dataLancamento}','${monitoramento.idMonitoramento}')">
                                    Ver Detalhado
                                </button>
                            </div>
                        </div>
                    `;
                });
                $('#modalResumoContent').html(resumos);
                $('#modalResumo').modal('show');
            },
            error: function (xhr, status, error) {
                console.error("Erro ao carregar o resumo:", status, error);
            }
        });
    };

  // Função para abrir o modal detalhado
    window.abrirModalDetalhado = (placa, largada, id_monitoramento) => {
        console.log("PLACA: ", placa, "DATA: ", largada, "ID: ", id_monitoramento);
        $.ajax({
            url: "control_get_detalhado.php",
            type: "POST",
            data: {
                dataLancamento: largada,
                permission: 1,
                placa: placa,
                id_monitoramento: id_monitoramento
            },
            success: (dados) => {
                try {
                    // Verifique se a resposta é uma string e converta para JSON, se necessário
                    if (typeof dados === 'string') {
                        dados = JSON.parse(dados); // Converte string para objeto JSON, se for o caso
                    }
            
                    // Verifique se 'message' existe e é "none"
                    if (dados['message'] === "none") {
                        $("#tabelaDetalhadoBodyNormais").html(
                            "<tr><td colspan='8' class='text-center'>Nenhum dado encontrado</td></tr>"
                        );
                        $("#tabelaDetalhadoBodyArmazem").html(
                            "<tr><td colspan='8' class='text-center'>Nenhum dado encontrado</td></tr>"
                        );
                    } else {
                        console.log(dados); // Verifique o conteúdo de dados
                        let tabelaBodyNormais = "";
                        let tabelaBodyArmazem = "";
            
                        // Processar dados['normais']
                        if (Array.isArray(dados['normais']) && dados['normais'].length > 0) {
                            dados['normais'].forEach((item, index) => {
                                tabelaBodyNormais += `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${item['fornecedor'] || '-'}</td>
                                        <td>${item['cod'] || '-'}</td>
                                        <td>${item['descricao'] || '-'}</td>
                                        <td>${item['Peso'] ? parseFloat(item['Peso']).toFixed(2) : '0.00'}</td>
                                        <td>${item['quantidade'] || '0'}</td>
                                        <td>${item['data_producao'] || '-'}</td>
                                        <td>${item['data_validade'] || '-'}</td>
                                    </tr>`;
                            });
                        } else {
                            console.log("dados['normais'] não encontrado ou vazio.");
                        }
            
                        // Processar dados['armazem']
                        if (Array.isArray(dados['armazem']) && dados['armazem'].length > 0) {
                            dados['armazem'].forEach((item, index) => {
                                tabelaBodyArmazem += `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${item['fornecedor'] || '-'}</td>
                                        <td>${item['cod'] || '-'}</td>
                                        <td>${item['descricao'] || '-'}</td>
                                        <td>${item['Peso'] ? parseFloat(item['Peso']).toFixed(2) : '0.00'}</td>
                                        <td>${item['quantidade'] || '0'}</td>
                                        <td>${item['data_producao'] || '-'}</td>
                                        <td>${item['data_validade'] || '-'}</td>
                                    </tr>`;
                            });
                        } else {
                            console.log("dados['armazem'] não encontrado ou vazio.");
                        }
            
                        // Insere os dados nas tabelas
                        $("#tabelaDetalhadoBodyNormais").html(tabelaBodyNormais);
                        $("#tabelaDetalhadoBodyArmazem").html(tabelaBodyArmazem);
                    }
            
                    // Exibe o modal
                    $("#modalDetalhado").modal("show");
            
                } catch (e) {
                    console.error("Erro ao processar os dados:", e);
                }
            },
            
            error: function (xhr, status, error) {
                console.error("Erro na requisição Ajax:", status, error);
            }
        });
    };





});
