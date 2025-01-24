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
});

$(document).ready(() => {
    let ids = $('.dash').attr('id');
    $.ajax({
        url: "control_get_espec_monitoramento.php",
        type: 'POST',
        data: {
            id: ids,
            permission: 1
        },
        dataType: 'json',
        success: (dados) => {
            // console.log(dados);
            var confirm = "<table id='tabelaMapa" + ids +"' style='width: 100%;' class='table-hover display nowrap'><caption>Lista de específicos</caption><thead><tr><th scope='col'>#</th><th scope='col'>N° da nota</th><th scope='col'>Fornecedor</th><th scope='col'>N° de caixas</th><th scope='col'>Peso Líquido</th><th scope='col'>N° de sequência</th></tr></thead><tbody id='mytable'>";

            for (let i = 0; i < dados.cruzeiro.length; i++) {
                confirm += "<tr id='cruzeiro" + i + "'>";
                confirm += ("<td></td>");
                // confirm += "<th scope='row'>" + (i + 1) + "</th>";
                confirm += ("<td>" + dados.cruzeiro[i].fk_notas_n_nota + "</td>");
                confirm += ("<td>" + dados.cruzeiro[i].fornecedor + "</td>");
                confirm += ("<td>" + dados.cruzeiro[i].n_caixas + "</td>");
                confirm += ("<td>" + dados.cruzeiro[i].peso_liquido + "</td>");
                confirm += ("<td>" + dados.cruzeiro[i].sequencia + "</td>");

                confirm += "</tr>";
            }

            for (let i = 0; i < dados.plena.length; i++) {
                confirm += "<tr id='plena" + i + "'>";
                confirm += ("<td></td>");
                // confirm += "<th scope='row'>" + (i + 1) + "</th>";
                confirm += ("<td>" + dados.plena[i].fk_notas_n_nota + "</td>");
                confirm += ("<td>" + dados.plena[i].fornecedor + "</td>");
                confirm += ("<td>" + dados.plena[i].n_caixas + "</td>");
                confirm += ("<td>" + dados.plena[i].peso_bruto + "</td>");
                confirm += ("<td>" + dados.plena[i].sequencia + "</td>");

                confirm += "</tr>";
            }
            confirm += "</tbody></table></div><button class='btn btn-outline-danger col-2 profile-button' type='button' onclick='history.back();'>Voltar</button>";

            $("#resultado").html(confirm);

            $('#tabelaMapa'+ids).DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
                },
                pageLength: 10,
                columnDefs: [
                    {
                        className: 'dtr-control',
                        orderable: false,
                        targets: 0
                    }
                ],
                order: [1, 'asc'],
                responsive: {
                    details: {
                        type: 'column'
                    }
                }
            });
        }
    });
});