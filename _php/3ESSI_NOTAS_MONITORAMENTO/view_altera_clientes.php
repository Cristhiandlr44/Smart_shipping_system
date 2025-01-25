<?php
    $relative = "../";
    require_once($relative."CRUD/relog.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de notas</title>
    <link rel="shortcut icon" href="../../_assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../_style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="../../_scriptjs/script.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
    
    <style>
        .dash {
            min-height: 90vh;
            height: 100%;
        }
        .form-control {
            display: inline;
            width: auto;
        }
        .table-hover:hover {
            cursor: pointer;
        }
        .linha-destaque {
            background-color: #f8d7da !important;
            color: #721c24 !important;
        }
        #modalNotasRota .modal-dialog {
            max-width: 90%;
            width: auto;
        }

        #modalNotasRota .modal-body {
            max-height: 60vh;
            overflow-y: auto;
        }
    </style>
    
</head>
<body>    
    
    <div id="navbarSt"></div>
    <div class="container d-flex justify-content-center align-items-center">
        <div class="dash bg-white">
            <div class="row p-3">
                <?php
                    $title = "- Rotas";
                    require_once($relative . "elements/tituloProjetoMainSection.php");

                    if ($_SESSION['tipo'] == 1) {
                        echo "
                        <div class='row'>
                            <p class='col-sm-8 offset-sm-2 col-md-11 offset-md-1' style='font-size: 1.5em;text-align: center;'>Selecione o cliente para alterar</p>
                        </div>";
                    }
                ?>         
            </div>

            <!-- Formulário para filtrar clientes -->
            <div class="row p-3">
                <input type="text" id="clienteSearch" class="form-control" placeholder="Digite o nome do cliente..." />
            </div>

            <!-- Tabela de Clientes -->
            <div class="row p-3">
                <table id="clientesTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>CNPJ</th>
                            <th>Cidade</th>
                            <th>Tipo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // Conectar ao banco de dados e pegar os clientes
                            $sql = "SELECT * FROM clientes";
                            $result = mysqli_query($conn, $sql);

                            if (mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr class='cliente-row' data-id='".$row['CNPJ']."' data-nome='".$row['nome']."' data-cnpj='".$row['CNPJ']."' data-cidade='".$row['cidade']."' data-tipo='".$row['tipo']."'>
                                            <td>".$row['nome']."</td>
                                            <td>".$row['CNPJ']."</td>
                                            <td>".$row['cidade']."</td>
                                            <td>".$row['tipo']."</td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>Nenhum cliente encontrado</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Modal para alterar tipo de cliente -->
    <div class="modal fade" id="modalAlterarTipo" tabindex="-1" aria-labelledby="modalAlterarTipoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAlterarTipoLabel">Alterar Tipo de Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAlterarTipo">
                        <input type="hidden" id="clienteId" name="clienteId" />
                        <div class="mb-3">
                            <label for="tipoCliente" class="form-label">Tipo de Cliente</label>
                            <select class="form-select" id="tipoCliente" name="tipoCliente">
                                <option value="Varejo">Varejo</option>
                                <option value="Rede">Rede</option>
                                <option value="Varejo Noturno">Varejo Noturno</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar Alteração</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <?php 
        if($_SESSION['tipo'] == 1){
            echo '<script src="../../_scriptjs/script_prepara_monitoramentos_2.js"></script>';
        }
    ?>

    <?php    
        if($_SESSION['tipo'] != 1){
            echo '<script src="../../_scriptjs/script_get_notas_user.js"></script>';
        }
    ?>

    <script>
        // Filtrar clientes conforme o usuário digita
        $('#clienteSearch').on('input', function() {
            var value = $(this).val().toLowerCase();
            $('#clientesTable .cliente-row').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        // Ao clicar em uma linha da tabela, abre o modal com os dados do cliente
        $('#clientesTable').on('click', '.cliente-row', function() {
            var id = $(this).data('id');
            var nome = $(this).data('nome');
            var cnpj = $(this).data('cnpj');
            var cidade = $(this).data('cidade');
            var tipo = $(this).data('tipo');

            $('#clienteId').val(id);
            $('#tipoCliente').val(tipo); // Preenche o tipo atual

            // Abre o modal
            $('#modalAlterarTipo').modal('show');
        });

        // Salvar a alteração do tipo de cliente
        $('#formAlterarTipo').on('submit', function(e) {
            e.preventDefault();

            var clienteId = $('#clienteId').val();
            var tipoCliente = $('#tipoCliente').val();

            // Enviar os dados via AJAX para atualizar no banco de dados
            $.ajax({
                url: 'update_tipo_cliente.php',
                type: 'POST',
                data: {
                    clienteId: clienteId,
                    tipoCliente: tipoCliente
                },
                success: function(response) {
                    alert('Tipo de cliente alterado com sucesso!');
                    $('#modalAlterarTipo').modal('hide');
                    location.reload(); // Recarrega a página para refletir as mudanças
                }
            });
        });
    </script>

</body>
</html>
