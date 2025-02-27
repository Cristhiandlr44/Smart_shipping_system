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
let filtrada = false;
let fornecedor = '';  // Variável para armazenar o fornecedor selecionado
let url = '';  // URL para requisição
let url2='';
let fornecedores=[];
document.addEventListener('DOMContentLoaded', function () {
    const selectFornecedor = document.getElementById('filter-fornecedor');
    const generateUrlBtn = document.getElementById('generateUrlBtn');
    const generatedUrlDiv = document.getElementById('generatedUrl');
    
    // Fazer requisição AJAX para obter os fornecedores
    fetch('get_fornecedores.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Erro ao buscar fornecedores:", data.error);
                selectFornecedor.innerHTML = '<option disabled>Erro ao carregar fornecedores</option>';
                return;
            }

            // Limpa opções iniciais
            selectFornecedor.innerHTML = '';

            // Adiciona os fornecedores ao select
            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.fornecedor;
                option.textContent = item.fornecedor;
                selectFornecedor.appendChild(option);
            });
        })
        .catch(error => {
            console.error("Erro na requisição:", error);
            selectFornecedor.innerHTML = '<option disabled>Erro ao carregar fornecedores</option>';
        });

    // Função para gerar a URL com os fornecedores selecionados
    generateUrlBtn.addEventListener('click', function() {
        const selectedOptions = Array.from(selectFornecedor.selectedOptions);
        fornecedores = selectedOptions.map(option => option.value);
    
        // Caso não tenha fornecedores selecionados
        if (fornecedores.length === 0) {
            alert('Selecione ao menos um fornecedor.');
            return;
        }
    
        // Construir a URL com os parâmetros de fornecedores
        url = new URL('get_rotas_filtradas.php', window.location.origin);
        // Passar os fornecedores como uma lista separada por vírgulas
        url.searchParams.append('fornecedores', fornecedores.join(',')); // Aqui estamos passando os fornecedores como uma string separada por vírgulas
    
        
        filtrada = true;
        carregarRotas();
    });
    

});

// Função para carregar as rotas via AJAX
function carregarRotas() {
    console.log("passou 1")
    if (filtrada == false) {
        fetch('get_rotas.php')
        .then(response => response.json())
        .then(data => {
            console.log("passou 2")
            if (data.error) {
                console.error('Erro ao carregar rotas:', data.error);
                return;
            }

            // Supondo que os dados retornado sejam estruturados assim:
            // data.rotas -> Rotas normais
            // data.rotasRedes -> Rotas das redes
            const rotas = data.rotas || [];  // Dados das rotas
            const rotasRedes = data.rotasRedes || [];  // Dados das rotas das redes

            // Exibe as rotas normais
            exibirCardsRotas(rotas);

            // Exibe as rotas das redes
            exibirCardsRotasRedes(rotasRedes);
        })
        .catch(error => console.error('Erro na requisição AJAX:', error));
    } else {
        url2 = url.pathname.replace(/^\/+/, '') + url.search;
        console.log(url2)
        fetch(url2)  // Faz a requisição AJAX com a URL gerada
            .then(response => {
                console.log("data:", response);

                if (!response.ok) {
                    throw new Error('Erro na requisição: ' + response.status);
                }
                return response.json();  // Tenta transformar a resposta em JSON
            })
            .then(data => {
                if (data.error) {
                    console.error('Erro ao carregar rotas:', data.error);
                    return;
                }
                const rotas = data.rotas || [];  // Dados das rotas
                const rotasRedes = data.rotasRedes || [];  // Dados das rotas das redes
                console.log("rotas normais:", data.rotas)
                console.log("rotas redes:", data.rotasRedes)
                // Exibe as rotas normais
                exibirCardsRotas(rotas);

                // Exibe as rotas das redes
                exibirCardsRotasRedes(rotasRedes);
            })
            .catch(error => {
                console.error('Erro na requisição AJAX:', error);
                response.text().then(text => {
                    console.error("Conteúdo retornado:", text);
                    console.log("data:", data);
                });
            });
    }
}

function gerarRelatorioCompletoParados() {
    $.ajax({
        url: "gerar_relatorio_mapa_parados.php",
        type: "POST",
        data: {
            permission: 1
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
                link.download = 'Mapa_Carregamento_Parados.pdf';  // Nome do arquivo com a placa
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
function gerarMapaXLSParados() {
    fetch('gerar_relatorio_mapa_parados_XLS.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        }
        
    })
    .then(response => response.json())
    .then(data => {
        if (data.erro) {
            alert("Erro: " + data.erro);
            return;
        }

        // Criar planilha com SheetJS
        let ws_data = [["Rota","Operação", "Código", "Descrição", "Peso", "Quantidade", "Tipo", "Data Produção", "Data Validade"]];
        data.forEach(produto => {
            ws_data.push([
                produto.rota,
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

        XLSX.writeFile(wb, `Mapa_Carregamento_Parado.xlsx`);
    })
    .catch(error => {
        console.error("Erro ao gerar planilha:", error);
    });
}
function gerarRomaneioParados() {
    
    $.ajax({
        url: "gerar_relatorio_romaneio_parados.php",
        type: "POST",
        data: {
            
            permission: 1
            
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
                link.download = 'Romaneio_notas_Parado.pdf';  // Nome do arquivo
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
function gerarRomaneioXLSParados() {
    fetch('gerar_relatorio_romaneio_clientes_parados_XLS.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        }
       
    })
    .then(response => response.json())
    .then(data => {
        if (data.erro) {
            alert("Erro: " + data.erro);
            return;
        }

        // Criar planilha com SheetJS
        let ws_data = [["Rota","Nota Fiscal", "Nome", "Endereço", "Numero", "Bairro", "Cidade", "Peso", "Valor Nf"]];
        data.forEach(clientes => {
            ws_data.push([
		clientes.rota,
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

        XLSX.writeFile(wb, `Romaneio_notas_Parado.xlsx`);
    })
    .catch(error => {
        console.error("Erro ao gerar planilha:", error);
    });
}
function gerarRomaneioItensXLS() {
    fetch('gerar_relatorio_romaneio_itens_parados_XLS.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        }
       
    })
    .then(response => response.json())
    .then(data => {
        if (data.erro) {
            alert("Erro: " + data.erro);
            return;
        }

        // Criar planilha com SheetJS
        let ws_data = [["Rota","Operação", "Nota Fiscal", "Codigo", "Descrição", "Peso", "Quantidade", "Tipo", "Data de Produção","Data de Validade"]];
        data.forEach(produtos => {
            ws_data.push([
                produtos.rota,
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

        XLSX.writeFile(wb, `Romaneio_itens_Parados}.xlsx`);
    })
    .catch(error => {
        console.error("Erro ao gerar planilha:", error);
    });
}



// Função para exibir os cards das rotas 
function exibirCardsRotas(rotas) {
    const cardsContainer = document.getElementById("cardsRotas");
    cardsContainer.innerHTML = ""; // Limpa os cards existentes

    rotas.forEach(rota => {
        const card = document.createElement("div");
        card.className = "card p-3";
        card.style.width = "18rem";
        card.innerHTML = `
            <h5 class="card-title">Rota: ${rota.rota}</h5>
            <p>Peso Total: ${rota.pesoTotal} kg</p>
            <p>Notas: ${rota.quantidadeNotas}</p>
            <p>Entregas: ${rota.quantidadeEntregas}</p>
            <div class="d-flex justify-content-between"> 
                <button class="btn btn-primary" onclick="abrirModalNotas('${rota.rota}')">Ver Detalhes</button>
                <button class="btn btn-success" onclick="buscarNotas('${rota.rota}')">Gerar Viagem</button>
            </div>
        `;
        
        cardsContainer.appendChild(card);
        
    });
}

function exibirCardsRotasRedes(rotas) {
    const cardsContainer = document.getElementById("cardsRotasRedes");
    cardsContainer.innerHTML = ""; // Limpa os cards existentes
    console.log("Dados redes: ", rotas)
    rotas.forEach(rota => {
        const card = document.createElement("div");
        card.className = "card p-3";
        card.style.width = "18rem";
        card.innerHTML = `
            <h5 class="card-title">Rota: ${rota.Cliente}</h5>
            <p>Peso Total: ${rota.pesoTotal} kg</p>
            <p>Notas: ${rota.quantidadeNotas}</p>
            <p>Entregas: ${rota.quantidadeEntregas}</p>
            <div class="d-flex justify-content-between"> 
            <button class="btn btn-primary" onclick="abrirModalNotasRedes('${rota.rota}', '${rota.Cliente}')">Ver Detalhes</button>
                <button class="btn btn-success" onclick="buscarNotas('${rota.rota}')">Gerar Viagem</button>
            </div>
        `;
        
        cardsContainer.appendChild(card);
        
    });
}

function exibirDadosDetalhes(rotas) {
    const cardsContainer = document.getElementById("modalNotasRotaLabel").textContent = 
    `Detalhes das Notas - Peso Total: ${rota.pesoTtotal}kg - Quantidade: ${rota.quantidadeEntregas}`;

    cardsContainer.innerHTML = ""; // Limpa os cards existentes
    rotas.forEach(rota => {
        const card = document.createElement("div");
        card.className = "card p-3";
        card.style.width = "18rem";
        card.innerHTML = `
            <h5>Rota: ${rota.rota}</h5>
            <p>Peso Total: ${rota.pesoTotal} kg</p>
            <p>Notas: ${rota.quantidadeNotas}</p>
            <p>Entregas: ${rota.quantidadeEntregas}</p>
        `;
        cardsContainer.appendChild(card);
    });
}

// Chamada inicial para carregar as rotas
carregarRotas();

// Inicializar os cards das rotas
function carregarCardsRotas() {
    const cardsContainer = document.getElementById("cardsRotas");
    cardsContainer.innerHTML = "";

    rotas.forEach((rota) => {
        const card = document.createElement("div");
        card.className = "card p-3";
        card.style.width = "18rem";
        card.innerHTML = `
            <h5 class="card-title">${rota.nome}</h5>
            <p>Peso Total: ${rota.pesoTotal} kg</p>
            <p>Notas: ${rota.quantidadeNotas}</p>
            <p>Entregas: ${rota.quantidadeEntregas}</p>
            <button class="btn btn-primary" onclick="abrirModalNotas(${rota.id})">Ver Detalhes</button>
        `;
        cardsContainer.appendChild(card);
    });
}


function buscarNotas(rotaId) {
    if (filtrada == false) {
        fetch(`get_notas.php?rota=${rotaId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Erro ao carregar notas:', data.error);
                return;
            }

            // Cria uma lista com o número das notas
            const linhasSelecionadas = data.map(nota => nota.n_nota);

            // Exibe no console para verificar
            console.log('Linhas Selecionadas:', linhasSelecionadas);
            enviarIdsSelecionados(linhasSelecionadas);

            // Aqui você pode usar a variável linhasSelecionadas conforme necessário
        })
        .catch(error => console.error('Erro ao carregar notas:', error));
    }else{
        const url3 = `get_notas_filtradas.php?rota=${rotaId}`+url.search
        console.log(`get_notas_filtradas.php?rota=${rotaId}&fornecedores=${fornecedores.join(',')}`) 
        fetch(`get_notas_filtradas.php?rota=${rotaId}&fornecedores=${fornecedores.join(',')}`)
            .then(response => response.json())
            .then(data => {
                console.log('Resposta bruta:', data); // Verifique a estrutura dos dados retornados
                
                // Verifique se a chave "nota" existe no objeto de resposta
                if (!data.nota || !Array.isArray(data.nota)) {
                    console.error('A chave "nota" não é um array ou não existe:', data);
                    return;
                }

                // Acesse o array de notas
                const linhasSelecionadas = data.nota.map(nota => nota.n_nota);

                // Exibe no console para verificar
                console.log('Linhas Selecionadas:', linhasSelecionadas);
                enviarIdsSelecionados(linhasSelecionadas);

                // Aqui você pode usar a variável linhasSelecionadas conforme necessário
            })
            .catch(error => console.error('Erro ao carregar notas filtradas:', error));


    }
    
}

let linhasSelecionadas = [];

function abrirModalNotas(rotaId) {
   
    if (filtrada == false) {
        console.log("chama");
        console.log("rota: ",rotaId);
        fetch(`get_notas.php?rota=${rotaId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Erro ao carregar notas:', data.error);
                return;
            }

            const notasTable = document.getElementById("notasRotaTable");
            const modalDetalhesRota = document.getElementById("modalDetalhesRota");
            notasTable.innerHTML = "";

            linhasSelecionadas = data.map(nota => nota.n_nota);

            let pesoTotal = 0;
            let quantidadeNotas = 0;

            // Processar as notas
            data.forEach(nota => {
                const reentregaTexto = nota.reentrega === 'S' ? 'SIM' : 'NÃO';
                // Atualizar os valores para o resumo
                pesoTotal += parseFloat(nota.peso_bruto) || 0;
                quantidadeNotas++;
                if (nota.reentrega === 'S');

                // Criar linha da tabela
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td><input type="checkbox" class="nota-checkbox" data-id="${nota.n_nota}" /></td>
                    <td>${nota.fornecedor}</td>
                    <td>${nota.n_nota}</td>
                    <td>${nota.Cliente}</td>
                    <td>${nota.peso_bruto} kg</td>
                    <td>${nota.bairro}</td>
                    <td>${nota.cidade}</td>
                    <td>${reentregaTexto}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="abrirModalTrocarRota(${nota.n_nota})">Trocar Rota</button>
                    </td>
                `;

                // Destacar linha se for reentrega
                if (nota.reentrega === 'S') {
                    row.classList.add('table-warning');
                    row.style.backgroundColor = '#f8d7da';
                    row.style.color = '#721c24';
                }
                
                notasTable.appendChild(row);
            });

            // Atualizar o título do modal com os detalhes
            modalDetalhesRota.textContent = `Peso Total: ${pesoTotal.toFixed(2)} kg -  Notas: ${quantidadeNotas}`;

            // Adicionar evento às checkboxes
            document.querySelectorAll('.nota-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', habilitarBotaoTrocar);
            });

            // Exibir o modal
            const modalNotas = new bootstrap.Modal(document.getElementById("modalNotasRota"));
            modalNotas.show();



        })
        .catch(error => console.error('Erro ao carregar notas:', error));

    }else{
        console.log(`get_notas_filtradas.php?rota=${rotaId}&fornecedores=${fornecedores.join(',')}`);

        fetch(`get_notas_filtradas.php?rota=${rotaId}&fornecedores=${fornecedores.join(',')}`)
            .then(response => response.json())
            .then(data => {

                if (data.error) {
                    console.error('Erro ao carregar notas:', data.error);
                    return;
                }
                
                const notasTable = document.getElementById("notasRotaTable");
                const modalDetalhesRota = document.getElementById("modalDetalhesRota");
                notasTable.innerHTML = "";

                // Acesse o array de notas com data.nota
                linhasSelecionadas = data.nota.map(nota => nota.n_nota);

                let pesoTotal = 0;
                let quantidadeNotas = 0;

                // Processar as notas
                data.nota.forEach(nota => {
                    const reentregaTexto = nota.reentrega === 'S' ? 'SIM' : 'NÃO';

                    // Atualizar os valores para o resumo
                    pesoTotal += parseFloat(nota.peso_bruto) || 0;
                    quantidadeNotas++;

                    // Criar linha da tabela
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td><input type="checkbox" class="nota-checkbox" data-id="${nota.n_nota}" /></td>
                        <td>${nota.fornecedor}</td>
                        <td>${nota.n_nota}</td>
                        <td>${nota.Cliente}</td>
                        <td>${nota.peso_bruto} kg</td>
                        <td>${nota.bairro}</td>
                        <td>${nota.cidade}</td>
                        <td>${reentregaTexto}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" onclick="abrirModalTrocarRota(${nota.n_nota})">Trocar Rota</button>
                        </td>
                    `;

                    // Destacar linha se for reentrega
                    if (nota.reentrega === 'S') {
                        row.classList.add('linha-destaque');
                        row.style.backgroundColor = '#f8d7da';
                        row.style.color = '#721c24';
                    }
                    
                    notasTable.appendChild(row);
                });

                // Atualizar o título do modal com os detalhes
                modalDetalhesRota.textContent = `Peso Total: ${pesoTotal.toFixed(2)} kg -  Notas: ${quantidadeNotas}`;

                // Adicionar evento às checkboxes
                document.querySelectorAll('.nota-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', habilitarBotaoTrocar);
                });

                // Exibir o modal
                const modalNotas = new bootstrap.Modal(document.getElementById("modalNotasRota"));
                modalNotas.show();

            })
            .catch(error => console.error('Erro ao carregar notas:', error));
        console.log("Apos a função 2 ", linhasSelecionadas);

            }

            
        }


function abrirModalNotasRedes(rotaId, Cliente) {
    console.log("rota: ", rotaId  || "cliente: ", Cliente);
    if (filtrada == false) {
        // Corrigindo a URL para enviar o parâmetro 'cliente' corretamente
        fetch(`get_notas_Redes.php?rota=${rotaId}&cliente=${Cliente}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Erro ao carregar notas:', data.error);
                return;
            }

            const notasTable = document.getElementById("notasRotaTable");
            const modalDetalhesRota = document.getElementById("modalDetalhesRota");
            notasTable.innerHTML = "";
            console.log("Data: ", data)

            linhasSelecionadas = data.map(nota => nota.n_nota);  // Assumindo que 'data' é um array de notas

            let pesoTotal = 0;
            let quantidadeNotas = 0;

            // Processar as notas
            data.forEach(nota => {
                const reentregaTexto = nota.reentrega === 'S' ? 'SIM' : 'NÃO';
                // Atualizar os valores para o resumo
                pesoTotal += parseFloat(nota.peso_bruto) || 0;
                quantidadeNotas++;

                // Criar linha da tabela
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td><input type="checkbox" class="nota-checkbox" data-id="${nota.n_nota}" /></td>
                    <td>${nota.fornecedor}</td>
                    <td>${nota.n_nota}</td>
                    <td>${nota.Cliente}</td>
                    <td>${nota.peso_bruto} kg</td>
                    <td>${nota.bairro}</td>
                    <td>${nota.cidade}</td>
                    <td>${reentregaTexto}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="abrirModalTrocarRota(${nota.n_nota})">Trocar Rota</button>
                    </td>
                `;

                // Destacar linha se for reentrega
                if (nota.reentrega === 'S') {
                    row.classList.add('table-warning');
                    row.style.backgroundColor = '#f8d7da';
                    row.style.color = '#721c24';
                }

                notasTable.appendChild(row);
            });

            // Atualizar o título do modal com os detalhes
            modalDetalhesRota.textContent = `Peso Total: ${pesoTotal.toFixed(2)} kg - Notas: ${quantidadeNotas}`;

            // Adicionar evento às checkboxes
            document.querySelectorAll('.nota-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', habilitarBotaoTrocar);
            });

            // Exibir o modal
            const modalNotas = new bootstrap.Modal(document.getElementById("modalNotasRota"));
            modalNotas.show();

        })
        .catch(error => console.error('Erro ao carregar notas:', error));

    } else {

        fetch(`get_notas_filtradas.php?rota=${rotaId}&fornecedores=${fornecedores.join(',')}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Erro ao carregar notas:', data.error);
                return;
            }

            const notasTable = document.getElementById("notasRotaTable");
            const modalDetalhesRota = document.getElementById("modalDetalhesRota");
            notasTable.innerHTML = "";

            // Acesse o array de notas com data.nota
            linhasSelecionadas = data.nota.map(nota => nota.n_nota);

            let pesoTotal = 0;
            let quantidadeNotas = 0;

            // Processar as notas
            data.nota.forEach(nota => {
                const reentregaTexto = nota.reentrega === 'S' ? 'SIM' : 'NÃO';

                // Atualizar os valores para o resumo
                pesoTotal += parseFloat(nota.peso_bruto) || 0;
                quantidadeNotas++;

                // Criar linha da tabela
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td><input type="checkbox" class="nota-checkbox" data-id="${nota.n_nota}" /></td>
                    <td>${nota.fornecedor}</td>
                    <td>${nota.n_nota}</td>
                    <td>${nota.Cliente}</td>
                    <td>${nota.peso_bruto} kg</td>
                    <td>${nota.bairro}</td>
                    <td>${nota.municipio}</td>
                    <td>${reentregaTexto}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="abrirModalTrocarRota(${nota.n_nota})">Trocar Rota</button>
                    </td>
                `;

                // Destacar linha se for reentrega
                if (nota.reentrega === 'S') {
                    row.classList.add('linha-destaque');
                    row.style.backgroundColor = '#f8d7da';
                    row.style.color = '#721c24';
                }

                notasTable.appendChild(row);
            });

            // Atualizar o título do modal com os detalhes
            modalDetalhesRota.textContent = `Peso Total: ${pesoTotal.toFixed(2)} kg - Notas: ${quantidadeNotas}`;

            // Adicionar evento às checkboxes
            document.querySelectorAll('.nota-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', habilitarBotaoTrocar);
            });

            // Exibir o modal
            const modalNotas = new bootstrap.Modal(document.getElementById("modalNotasRota"));
            modalNotas.show();

        })
        .catch(error => console.error('Erro ao carregar notas:', error));
        
        console.log("Após a função 2 ", linhasSelecionadas);
    }
}

function habilitarBotaoTrocar() {
    const checkedNotas = document.querySelectorAll('.nota-checkbox:checked');
    const botaoTrocar = document.getElementById('btnTrocarRotas');
    
    // Habilita o botão se pelo menos uma nota estiver selecionada
    botaoTrocar.disabled = checkedNotas.length === 0;
}
function trocarRotas() {
    // Coleta os IDs das notas selecionadas
    const notasSelecionadas = [];
    document.querySelectorAll('.nota-checkbox:checked').forEach(checkbox => {
        notasSelecionadas.push(checkbox.getAttribute('data-id'));
    });

    // Abre o modal para trocar a rota
    abrirModalSelecaoRota(notasSelecionadas);
}

function abrirModalSelecaoRota(notasSelecionadas) {
    const modal = new bootstrap.Modal(document.getElementById("modalTrocarRota"));
    modal.show();

    // Armazene os IDs das notas selecionadas para poder usá-los depois
    window.notasSelecionadasParaTroca = notasSelecionadas;
}
   
function abrirModalTrocarRota(notaId) {
    // Marca automaticamente o checkbox correspondente à nota
    const checkbox = document.querySelector(`input[type="checkbox"][data-id="${notaId}"]`);
    if (checkbox) {
        checkbox.checked = true; // Marca o checkbox
    }
    // Faz a requisição para buscar as rotas existentes
    fetch("get_somente_rotas.php")
        .then(response => response.json())
        .then(rotas => {
            console.log('Rotas:', rotas);
            // Verifica se houve erro ao carregar as rotas
            if (rotas.error) {
                console.error('Erro ao carregar rotas:', rotas.error);
                alert('Erro ao carregar rotas');
                return;
            }

            // Preenche o select com as rotas existentes
            const selectRota = document.getElementById("selectRota");
            selectRota.innerHTML = ""; // Limpa as opções existentes

            // Adiciona as rotas no dropdown
            rotas.forEach(rota => {
                const option = document.createElement("option");
                option.value = rota.rota;
                option.textContent = rota.rota;
                selectRota.appendChild(option);
            });

            // Adiciona a opção de criar nova rota
            const novaRotaOption = document.createElement("option");
            novaRotaOption.value = "nova";
            novaRotaOption.textContent = "Criar Nova Rota";
            selectRota.appendChild(novaRotaOption);

            // Mostra o modal
            const modalTrocarRota = new bootstrap.Modal(document.getElementById("modalTrocarRota"));
            modalTrocarRota.show();

            // Armazena o ID da nota para usar ao trocar a rota
            document.getElementById("notaId").value = notaId;
        })
        .catch(error => {
            console.error('Erro ao carregar rotas:', error);
            alert('Erro ao carregar rotas');
        });
}

function trocarRota() {
    const selectRota = document.getElementById("selectRota");
    let novaRota = selectRota.value;

    // Caso o usuário tenha escolhido "Criar Nova Rota", pede para digitar a nova rota
    if (novaRota === "nova") {
        novaRota = prompt("Digite o nome da nova rota:");
        if (!novaRota) {
            alert("A nova rota não pode ser vazia.");
            return;
        }
    }

    // Obter as notas selecionadas
    const notasSelecionadas = Array.from(document.querySelectorAll('.nota-checkbox:checked')).map(checkbox => checkbox.getAttribute('data-id'));
    console.log("Notas identificadas:",notasSelecionadas)
    // Se não houver nenhuma nota selecionada
    if (notasSelecionadas.length === 0) {
        alert("Selecione pelo menos uma nota.");
        return;
    }

    // Se for uma única nota
    if (notasSelecionadas.length === 1) {
        const notaId = notasSelecionadas[0] ; // Pega o valor da nota selecionada
        const dados = {
            notaId: notaId,
            rota: novaRota
        };

        // Log para verificar os dados
        console.log("Enviando os seguintes dados para o PHP (uma nota):", dados);

        // AJAX para uma única nota
        fetch("trocar_rota_unica.php", {
            method: "POST",
            body: JSON.stringify(dados),
            headers: {
                "Content-Type": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Resposta do servidor (uma nota):', data);
            if (data.success) {
                window.location.reload();
                alert("Rota trocada com sucesso!");
            } else {
                alert("Erro ao trocar rota: " + data.error);
            }
        })
        .catch(error => {
            console.error("Erro ao trocar rota (uma nota):", error);
            alert("Erro desconhecido ao trocar rota. Verifique o console.");
        });

    } else {
       
        const dados = {
            notas: notasSelecionadas,
            rota: novaRota
        };

        // Log para verificar os dados
        console.log("Enviando os seguintes dados para o PHP (múltiplas notas):", dados);

        // AJAX para múltiplas notas
        fetch("trocar_rotas.php", {
            method: "POST",
            body: JSON.stringify(dados),
            headers: {
                "Content-Type": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Resposta do servidor (múltiplas notas):', data);
            if (data.success) {
                window.location.reload();
                alert("Rota trocada com sucesso!");
            } else {
                alert("Erro ao trocar rota: " + data.error);
            }
        })
        .catch(error => {
            console.error("Erro ao trocar rota (múltiplas notas):", error);
            alert("Erro desconhecido ao trocar rota. Verifique o console.");
        });
    }
}
function enviarIdsSelecionados(linhasSelecionadas) {
    console.log(linhasSelecionadas);

    if (linhasSelecionadas.length > 0) {
        $.ajax({
            url: 'gerarMonitoramento.php',
            type: 'POST',
            data: {
                linhasSelecionadas: linhasSelecionadas,
            },
            success: function(response) {
                var jsonResponse = JSON.parse(response);
                if (jsonResponse.success) {
                    // Após a resposta bem-sucedida, redirecionar para a view_gerar_monitoramento.php com o id_monitoramento
                    window.location.href = 'view_gerar_monitoramento.php?id_monitoramento=' + jsonResponse.id_monitoramento;
                } else {
                    console.log("Erro ao gerar monitoramento");
                }
            }
        });
        console.log("IDs das linhas selecionadas: " + linhasSelecionadas.join(','));
    } else {
        console.log("Nenhuma linha selecionada.");
    }
}
