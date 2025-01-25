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

    $("#btnBuscar").click(() => {
        $.ajax({
            url: "control_get_products.php",
            type: 'POST',
            data: {
                dataLancamento: $("#dataLancamento").val(),
                permission: 1
            },
            success: (dados) => {
                try {
                    var idMonitoramentos = []; // Array para armazenar os id_monitoramento
                    var pesoTotal = 0; // Variável para rastrear o peso total
                    var volumeTotal = 0; // Variável para rastrear o volume total

                    var confirm;
                    if (dados['message'] === "none") {
                        confirm = "<p style='text-align: center;'>O Banco não possui nenhuma nota sem monitoramento ativo</p>";
                    } else {
                        for (const id_monitoramento in dados) {
                            const placa = dados[id_monitoramento][0]['placa_caminhao'];
                            idMonitoramentos.push(id_monitoramento);

                            // Calcula e atualiza o peso total e o volume total
                            const pesoTabela = dados[id_monitoramento].reduce((total, item) => total + parseFloat(item['Peso']), 0);
                            const volumeTabela = dados[id_monitoramento].reduce((total, item) => total + parseFloat(item['quantidade']), 0);
                            pesoTotal += pesoTabela;
                            volumeTotal += volumeTabela; 

                            confirm += `<div class='row m-2 mt-3'><h3 class="col-3">Placa: ${placa}</h3>`;
                            confirm += `<button style='display: inline-block;' class='btn btn-primary col-2' onclick='exibirEspecificas(${id_monitoramento}, "${dados[id_monitoramento][0]['placa_caminhao']}")'>Específicas</button></div>`;
                            confirm += `<div class='table-responsive col-md-12 col-11 offset-1 offset-sm-0 p-2'><span>Peso Total: ${pesoTabela.toFixed(2)}</span><span> | Volume Total: ${volumeTabela.toFixed(2)}</span><table id='tabelaMapa${id_monitoramento}' class='tablemapa table table-bordered table-hover mt-2'><thead><tr><th scope='col'>#</th><th scope='col'>Operação</th><th scope='col'>Codigo</th><th scope='col'>Descricao</th><th scope='col'>Peso</th><th scope='col'>Quantidade</th><th scope='col'>Data Produção</th><th scope='col'>Data Validade</th></tr></thead><tbody>`;

                            for (let i = 0; i < dados[id_monitoramento].length; i++) {
                                confirm += "<tr>";
                                confirm += "<th scope='row'>" + (i + 1) + "</th>";
                                confirm += ("<td>" + dados[id_monitoramento][i]['fornecedor'] + "</td>");
                                confirm += ("<td>" + dados[id_monitoramento][i]['cod'] + "</td>");
                                confirm += ("<td>" + dados[id_monitoramento][i]['descricao'] + "</td>");
                                confirm += ("<td>" + dados[id_monitoramento][i]['Peso'] + "</td>");
                                confirm += ("<td>" + dados[id_monitoramento][i]['quantidade'] + "</td>");
                                confirm += ("<td>" + dados[id_monitoramento][i]['data_producao'] + "</td>");
                                confirm += ("<td>" + dados[id_monitoramento][i]['data_validade'] + "</td>");

                                confirm += "</tr>";
                            }
                            confirm += "</tbody></table></div>";
                        }
                    }
                    $("#dash").toggleClass("inicial");

                    // Atualiza o peso total e o volume total gerais
                    $('#peso').val(pesoTotal.toFixed(2));
                    $('#volume').val(volumeTotal.toFixed(2));

                    $("#resultado").html(confirm.replace("undefined", ""));
                    idMonitoramentos.forEach(id => {
                        $('#tabelaMapa' + id).DataTable({
                            "language": {
                                "url": "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
                            },
                            "pageLength": 5,
                            // Outras opções se necessário
                        });
                    });
                } catch (e) {
                    console.error("Erro ao analisar JSON:", e);
                }
            },
            error: function (xhr, status, error) {
                console.error("Erro na requisição Ajax:", status, error);
            }
        });
    });
});

// Função para ser chamada ao clicar no botão "Específicas"
function exibirEspecificas(id_monitoramento, placa) {
    var endereco = "view_get_espec_monitoramento.php?id=" + id_monitoramento+"&placa=" + placa;
    console.log(endereco);
    window.location.href = endereco;
}
