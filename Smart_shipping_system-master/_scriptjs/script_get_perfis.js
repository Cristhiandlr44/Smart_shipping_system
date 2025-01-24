$(document).ready(() => {
    $.ajax({
        url: 'elements/E_navbar.php',
        type: 'POST',
        data: {
            relative: '' // Passe o ID desejado aqui
        },
        success: (result) => {
            $("#navbarSt").html(result);
        }
    });
    $.ajax({
        url: 'CRUD/listaUser.php',
        type: 'POST',
        data: {
            
        },
        success: (result) => {
            var confirm = "<table id='lista-users' style='width: 100%;' class='table-hover display nowrap'><caption>Lista de usuários</caption><thead><tr><th></th><th scope='col'>ID</th><th scope='col'>Usuário</th><th scope='col'>Nome</th><th scope='col'>Email</th><th scope='col'>Nível de permissão</th><th scope='col'>Data de cadastro</th></tr></thead><tbody id='mytable'>";
            for (var i = 0; i < result.length; i++) {
                var id = result[i]['id'];

                confirm += "<tr id='" + id + "' style='max-width: 90%;'>";
                confirm += ("<td></td>");
                confirm += ("<td>" + id + "</td>");
                confirm += ("<td>" + '<input type="text" style="width: 77px;" class="type_usuario form-control" data-id="' + id + '" id="basic-url1" value="'+result[i]['usuario']+'" aria-describedby="basic-addon3 basic-addon4"></input>' + "</td>");
                confirm += ("<td>" + '<input type="text" style="width: 205px;" class="type_nome form-control" data-id="' + id + '" id="basic-url2" value="'+result[i]['nome']+'" aria-describedby="basic-addon3 basic-addon4"></input>' + "</td>");
                confirm += ("<td>" + '<input type="text" style="width: 250px;" class="type_email form-control" data-id="' + id + '" id="basic-url3" value="'+result[i]['email']+'" aria-describedby="basic-addon3 basic-addon4"></input>' + "</td>");
    
                confirm += ("<td><select data-id='" + id + "' name='tipo' class='type_user form-select'>");
                confirm += result[i]['tipo']==1?"<option selected value='1'>Administrador</option>":"<option value='1'>Administrador</option>";
                confirm += result[i]['tipo']==0?"<option selected value='0'>Usuário</option>":"<option value='0'>Usuário</option>";

                confirm += ("</select></td>");

                confirm += ("<td>" + result[i]['data'] + "</td>");
                confirm += "</tr>";
            }

            confirm += "<tfoot style='font-size: 10px;'><tr><th></th><th rowspan='1' colspan='1' scope='col'>ID</th><th rowspan='1' colspan='1' scope='col'>Usuário</th><th rowspan='1' colspan='1' scope='col'>Nome</th><th rowspan='1' colspan='1' scope='col'>Email</th><th rowspan='1' colspan='1' scope='col'>Nível de permissão</th><th rowspan='1' colspan='1' scope='col'>Data de cadastro</th></tr></tfoot>";
            confirm += "</tbody></table>";

            $("#users").html(confirm);
            $('#1 td input').prop('disabled', true); //DESATIVA A POSSIBILIDADE DE MODIFICAÇÃO DO PERFIL ROOT
            $('#1 td select').prop('disabled', true);
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

    $(document).on('change', '.type_user', function () {
        var id = $(this).data('id');
        var tipo = $(this).val();
        var referencia = $(".type_usuario").val();

        $.ajax({
            url: 'CRUD/update_account.php',
            type: 'POST',
            data: {
                tipo: tipo,
                referencia: referencia,
                id: id
            },
            success: (result) => {
                // console.log(result);
                // debugger;
                if(result.message == "uppSessão"){
                    location.reload();
                    location.href='CRUD/logout.php';
                }
            }
        });
    });

    $(document).on('change', '.type_usuario', function () {
        var id = $(this).data('id');
        var usuario = $(this).val();
        
        $.ajax({
            url: 'CRUD/update_account.php',
            type: 'POST',
            data: {
                usuario: usuario,
                id: id
            },
            dataType: 'json',
            success: (result) => {
                if(result && result.message){
                    if(result.message == "exist"){
                        alert("Erro! Nome de usuário já cadastrado no sistema!");
                        location.reload();
                    } else if(result.message == "same"){
                        alert("Erro! Nome de usuário mudado é o mesmo nome da sessão ativa!");
                        location.reload();
                    }
                }
                if(message && message == "index.html") location.href='CRUD/logout.php';
            }
        });
    });

    $(document).on('change', '.type_nome', function () {
        var id = $(this).data('id');
        var nome = $(this).val();
    
        $.ajax({
            url: 'CRUD/update_account.php',
            type: 'POST',
            data: {
                nome: nome,
                id: id
            },
            dataType: 'json',
            success: (result) => {
                if(result.erroSQL){
                    alert("Erro na conexão com o banco de dados");
                    location.reload();
                }
                if(message == "index.html")
                    location.href='CRUD/logout.php';
            }
        });
    });
    
    $(document).on('change', '.type_email', function () {
        var id = $(this).data('id');
        var email = $(this).val();
    
        $.ajax({
            url: 'CRUD/update_account.php',
            type: 'POST',
            data: {
                email: email,
                id: id
            },
            dataType: 'json',
            success: (result) => {
                if(result.erroSQL){
                    alert("Erro na conexão com o banco de dados");
                    location.reload();
                }
                if(message == "index.html")
                    location.href='CRUD/logout.php';
            }
        });
    });
    
});