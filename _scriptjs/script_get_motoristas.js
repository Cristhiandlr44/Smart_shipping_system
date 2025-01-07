// var quantdCPFs = 1;

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
        url: 'control_view_motoristas_caminhoes.php',
        type: 'POST',
        data: {
            permission: permission
        },
        success: (result) => {
            console.log(permission);
            var confirm = "<table id='lista-users' style='width: 100%;' class='table-hover display nowrap'><caption>Lista de motoristas associados aos seus caminhões</caption><thead><tr><th></th><th scope='col'>#</th><th scope='col'>CPF</th><th scope='col'>Nome</th><th scope='col'>N° Habilitação</th><th scope='col'>Placa do Caminhão</th><th scope='col'>Modelo de veículo</th><th scope='col'>Vencimento da Habilitação</th></tr></thead><tbody id='mytable'>";
            for (var i = 0; i < result.length; i++) {
                var id = result[i]['cpf_motorista'] + "_" + result[i]['placa'];
                confirm += "<tr id='" + id + "' style='max-width: 90%;'>";
                confirm += ("<td></td>");
                confirm += ("<td>" + (parseInt(i) + 1) + "</td>");
                confirm += ("<td>" + '<input type="text" style="width: 135px;" class="cpf_motorista form-control" data-id="' + id + '" id="cpf_motorist'+(parseInt(i) + 1)+ ' " value="'+result[i]['cpf_motorista']+'" aria-describedby="basic-addon3 basic-addon4" maxlength="14" oninput="formatarCPF(this)" ' + (permission === 3 ? 'disabled' : '') + '></input>' + "</td>");
                confirm += ("<td>" + '<input type="text" style="width: 160px;" class="nome_motorista form-control" data-id="' + id + '" id="nome_motorista'+ (parseInt(i) + 1) +'" value="'+result[i]['nome_motorista']+'" aria-describedby="basic-addon3 basic-addon4" ' + (permission === 3 ? 'disabled' : '') + '></input>' + "</td>");
                confirm += ("<td>" + '<input type="text" style="width: 100px;" class="num_habilitacao form-control" data-id="' + id + '" id="num_habilitacao'+ (parseInt(i) + 1) +'" value="'+result[i]['num_habilitacao']+'" aria-describedby="basic-addon3 basic-addon4" ' + (permission === 3 ? 'disabled' : '') + '></input>' + "</td>");
                confirm += ("<td>" + '<input type="text" style="width: 105px;" class="placa_caminhao form-control" data-id="' + id + '" id="placa_caminhao'+ (parseInt(i) + 1) +'" value="'+result[i]['placa_caminhao']+'" aria-describedby="basic-addon3 basic-addon4" maxlength="8" ' + (permission === 3 ? 'disabled' : '') + '></input>' + "</td>");
                confirm += ("<td><select data-id='" + id + "' name='tipo' style='width: 90px;' class='modelo_caminhao form-select' " + (permission === 3 ? 'disabled' : '') + ">");
                confirm += result[i]['modelo_caminhao']=='T'?"<option selected value='T'>Toco</option>":"<option value='T'>Toco</option>";
                confirm += result[i]['modelo_caminhao']=='B'?"<option selected value='B'>Truco</option>":"<option value='B'>Truco</option>";
                confirm += result[i]['modelo_caminhao']=='L'?"<option selected value='L'>Leve</option>":"<option value='L'>Leve</option>";
                confirm += result[i]['modelo_caminhao']=='3'?"<option selected value='3'>3x4</option>":"<option value='3'>3x4</option>";
                confirm += ("</select></td>");
                confirm += ("<td>" + '<input type="text" style="width: 110px;" class="venci_habilitacao form-control" data-id="' + id + '" id="venci_habilitacao'+ (parseInt(i) + 1) +'" value="'+result[i]['venci_habilitacao']+'" aria-describedby="basic-addon3 basic-addon4" ' + (permission === 3 ? 'disabled' : '') + '></input>' + "</td>");
                confirm += "</tr>";
            }

            confirm += "<tfoot style='font-size: 10px;'><tr><th></th><th rowspan='1' colspan='1' scope='col'>#</th><th rowspan='1' colspan='1' scope='col'>CPF</th><th rowspan='1' colspan='1' scope='col'>Nome</th><th rowspan='1' colspan='1' scope='col'>N° Habilitação</th><th rowspan='1' colspan='1' scope='col'>Placa do Caminhão</th><th rowspan='1' colspan='1' scope='col'>Modelo de veículo</th><th rowspan='1' colspan='1' scope='col'>Vencimento da Habilitação</th></tr></tfoot>";
            confirm += "</tbody></table>";
            $("#users").html(confirm);
            $('#lista-users').DataTable({
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

    // $(document).on('change', '.type_usuario', function () {
    //     var id = $(this).data('id');
    //     var usuario = $(this).val();
        
    //     $.ajax({
    //         url: 'CRUD/update_account.php',
    //         type: 'POST',
    //         data: {
    //             usuario: usuario,
    //             id: id
    //         },
    //         dataType: 'json',
    //         success: (result) => {
    //             if(result.message == "exist"){
    //                 alert("Erro! Nome de usuário já cadastrado no sistema!");
    //                 location.reload();
    //             } else if(result.message == "same"){
    //                 alert("Erro! Nome de usuário mudado é o mesmo nome da sessão ativa!");
    //                 location.reload();
    //             }
                
    //             if(message == "index.html")
    //                 location.href='CRUD/logout.php';
    //         }
    //     });
    // });
});