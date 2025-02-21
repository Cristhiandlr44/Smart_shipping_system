// Função para gerar o relatório com os detalhes da viagem e itens devolvidos agrupados por nota
function gerarRelatorioCompleto(placa, largada, id_monitoramento) {
    console.log("ID Viagem: ", id_monitoramento, "placa: ", placa, "Largada: ", largada);  // Verificando o ID

    $.ajax({
        url: "gerar_relatorio_mapa.php",
        type: "POST",
        data: {
            dataLancamento: largada,
            permission: 1,
            placa: placa,
            id_monitoramento: id_monitoramento
        },
        xhrFields: {
            responseType: 'blob'  // Espera um Blob como resposta
        },
        success: function(response, status, xhr) {
            console.log("Resposta do servidor: ", response);

            // Verificando o tipo de resposta
            const contentType = xhr.getResponseHeader('Content-Type');
            console.log("Content-Type da resposta: ", contentType);

            if (contentType && contentType.includes('application/pdf')) {
                const link = document.createElement('a');
                const url = URL.createObjectURL(response);
                link.href = url;
                link.download = 'Mapa_Carregamento_' + placa + '.pdf';  // Nome do arquivo com a placa
                link.click();  // Simula o clique para download
                URL.revokeObjectURL(url);  // Libera a URL do blob após o download
            } else {
                console.log("Erro: Resposta inesperada, não é um arquivo PDF.");
                alert("Erro: O arquivo retornado não é um PDF. Tipo de resposta: " + contentType);
            }
        },
        error: function(xhr, status, error) {
            console.log("Erro na requisição: ", status, error);
            alert("Erro na requisição: " + error);
        }
    });
}
function gerarMapaXLS(placa, largada, id_monitoramento) {
    fetch('gerar_relatorio_mapa_XLS.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `placa=${placa}&largada=${largada}&id_monitoramento=${id_monitoramento}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.erro) {
            alert("Erro: " + data.erro);
            return;
        }

        // Criar planilha com SheetJS
        let ws_data = [["Operação", "Código", "Descrição", "Peso", "Quantidade", "Tipo", "Data Produção", "Data Validade"]];
        data.forEach(produto => {
            ws_data.push([
                produto.fornecedor,
                produto.cod,
                produto.descricao,
                produto.Peso,
                produto.quantidade,
                produto.UnidadeAuxiliar,
                produto.data_producao,
                produto.data_validade
            ]);
        });

        let wb = XLSX.utils.book_new();
        let ws = XLSX.utils.aoa_to_sheet(ws_data);
        XLSX.utils.book_append_sheet(wb, ws, "Mapa de Carregamento");

        XLSX.writeFile(wb, `Mapa_Carregamento_${placa}.xlsx`);
    })
    .catch(error => {
        console.error("Erro ao gerar planilha:", error);
    });
}


function gerarRomaneio(placa, largada, id_monitoramento) {
    console.log("ID Viagem: ", id_monitoramento, "placa: ", placa, "Largada: ", largada);  // Verificando o ID

    $.ajax({
        url: "gerar_relatorio_romaneio.php",
        type: "POST",
        data: {
            dataLancamento: largada,
            permission: 1,
            placa: placa,
            id_monitoramento: id_monitoramento
        },
        xhrFields: {
            responseType: 'blob'  // Espera um Blob como resposta
        },
        success: function(response, status, xhr) {
            console.log("Resposta do servidor: ", response);

            // Verificando o tipo de resposta
            const contentType = xhr.getResponseHeader('Content-Type');
            console.log("Content-Type da resposta: ", contentType);

            if (contentType && contentType.includes('application/pdf')) {
                const link = document.createElement('a');
                const url = URL.createObjectURL(response);
                link.href = url;
                link.download = 'Romaneio_notas_'+placa+'.pdf';  // Nome do arquivo
                link.click();  // Simula o clique para download
                URL.revokeObjectURL(url);  // Libera a URL do blob após o download
            } else {
                console.log("Erro: Resposta inesperada, não é um arquivo PDF.");
                alert("Erro: O arquivo retornado não é um PDF. Tipo de resposta: " + contentType);
            }
        },
        error: function(xhr, status, error) {
            console.log("Erro na requisição: ", status, error);
            alert("Erro na requisição: " + error);
        }
    });
}
function gerarRomaneioXLS(placa, largada, id_monitoramento) {
    fetch('gerar_relatorio_romaneio_clientes_XLS.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `placa=${placa}&largada=${largada}&id_monitoramento=${id_monitoramento}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.erro) {
            alert("Erro: " + data.erro);
            return;
        }

        // Criar planilha com SheetJS
        let ws_data = [["Nota Fiscal", "Nome", "Endereço", "Numero", "Bairro", "Cidade", "Peso", "Valor Nf"]];
        data.forEach(clientes => {
            ws_data.push([
                clientes.n_nota,
                clientes.nome,
                clientes.rua,
                clientes.numero,
                clientes.bairro,
                clientes.cidade,
                clientes.peso_bruto,
                clientes.valor_nota
            ]);
        });

        let wb = XLSX.utils.book_new();
        let ws = XLSX.utils.aoa_to_sheet(ws_data);
        XLSX.utils.book_append_sheet(wb, ws, "Romaneio_notas");

        XLSX.writeFile(wb, `Romaneio_notas_${placa}.xlsx`);
    })
    .catch(error => {
        console.error("Erro ao gerar planilha:", error);
    });
}
function gerarRomaneioItensXLS(placa, largada, id_monitoramento) {
    fetch('gerar_relatorio_romaneio_itens_XLS.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `placa=${placa}&largada=${largada}&id_monitoramento=${id_monitoramento}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.erro) {
            alert("Erro: " + data.erro);
            return;
        }

        // Criar planilha com SheetJS
        let ws_data = [["Operação", "Nota Fiscal", "Codigo", "Descrição", "Peso", "Quantidade", "Tipo", "Data de Produção","Data de Validade"]];
        data.forEach(produtos => {
            ws_data.push([
                produtos.fornecedor,
                produtos.nf,
                produtos.cod,
                produtos.descricao,
                produtos.quantidade,
                produtos.QuantAux,
                produtos.UnidadeAuxiliar,
                produtos.data_producao,
                produtos.data_validade
            ]);
        });

        let wb = XLSX.utils.book_new();
        let ws = XLSX.utils.aoa_to_sheet(ws_data);
        XLSX.utils.book_append_sheet(wb, ws, "Romaneio_itens");

        XLSX.writeFile(wb, `Romaneio_itens_${placa}.xlsx`);
    })
    .catch(error => {
        console.error("Erro ao gerar planilha:", error);
    });
}


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
    
                                <!-- Botão dropdown para Mapa de Carregamento -->
                                <div class="btn-group">
                                    <button class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        Gerar Mapa de Carregamento
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="gerarRelatorioCompleto('${monitoramento.placa}', '${dataLancamento}', '${monitoramento.idMonitoramento}')">PDF</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="gerarMapaXLS('${monitoramento.placa}', '${dataLancamento}', '${monitoramento.idMonitoramento}')">XLS</a></li>
                                    </ul>
                                </div>
    
                                <!-- Botão dropdown para Romaneio -->
                                <div class="btn-group">
                                    <button class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        Gerar Romaneio
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="gerarRomaneio('${monitoramento.placa}', '${dataLancamento}', '${monitoramento.idMonitoramento}')">PDF</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="gerarRomaneioXLS('${monitoramento.placa}', '${dataLancamento}', '${monitoramento.idMonitoramento}')">XLS clientes</a></li><li><a class="dropdown-item" href="#" onclick="gerarRomaneioItensXLS('${monitoramento.placa}', '${dataLancamento}', '${monitoramento.idMonitoramento}')">XLS itens</a></li>
                                    </ul>
                                </div>
    
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
