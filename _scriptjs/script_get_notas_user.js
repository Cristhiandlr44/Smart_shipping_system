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

    $.ajax({
        url: 'consultaNotas.php',
        type: 'POST',
        data: {
            permission: 1
        },
        success: (result) => {
            if (result === "none") {
                $("#table").html("<p style='text-align: center;'>O Banco não possui nenhuma nota sem monitoramento ativo</p>");
            } else {
                dados = JSON.parse(result);
                var confirm = "<table id='tabelaNotas' class='table table-bordered table-hover table-sm'><caption>Lista de notas sem monitoramento ativo</caption><thead><tr><th scope='col'>#</th><th scope='col'>N° Nota</th><th scope='col'>Cliente</th><th scope='col'>Município</th><th scope='col'>Fornecedor</th><th scope='col'>Peso_Bruto</th><th scope='col'>Rota</th></tr></thead><tbody id='mytable'>";
                for (var i = 0; i < dados.length; i++) {
                    var idNota = dados[i]['n_nota'];

                    confirm += "<tr id='" + idNota + "'>";
                    confirm += "<th scope='row'>" + (parseInt(i) + 1) + "</th>";
                    confirm += ("<td>" + idNota + "</td>");
                    confirm += ("<td>" + dados[i]['Cliente'] + "</td>");
                    confirm += ("<td>" + dados[i]['municipio'] + "</td>");
                    confirm += ("<td>" + dados[i]['fornecedor'] + "</td>");
                    confirm += ("<td>" + dados[i]['peso_bruto'] + "</td>");
                    confirm += ("<td>" + dados[i]['rota'] + "</td>");

                    confirm += "</tr>";
                }

                confirm += "</tbody></table>";
                $("#table").html(confirm);
                
                // Css para elementos do html a partir de ID com Ajax
                $("#tabelaNotas").css({
                    "box-shadow": "rgb(14 30 37 / 6%) 0px 2px 4px 0px, rgb(5 11 14 / 11%) 0px 2px 16px 0px"
                });

                // Inicialize a tabela como uma DataTable
                
                $('#tabelaNotas').DataTable({
                    "language": {
                        "url": "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
                    },
                    "pageLength":50, // Defina o número de linhas por página
                    // "searching": false 
                    // Desabilita a barra de pesquisa
                });

                $('#tabelaNotas_filter label input').attr('id', 'meuId');
                // $('#tabelaNotas_filter input').attr('id', 'pesquisar');
                // $('#tabelaNotas_filter input').addClass("form-control");
            }
        }
    });
});