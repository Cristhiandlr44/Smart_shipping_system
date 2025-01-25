<?php
$relative = "../";
require_once($relative . "CRUD/relog.php");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Base Rota</title>
    <link rel="shortcut icon" href="../../_assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../_style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="../../_scriptjs/script.js"></script>
</head>
<body>
    <div id="navbarSt"></div>
    <div class="container">
        <div class="dash bg-white">
            <div class="row p-3">
                <?php
                    $title = "- Base Rota";
                    require_once($relative . "elements/tituloProjetoMainSection.php");

                    if ($_SESSION['tipo'] == 1) {
                        echo "
                        <div class='row'>
                            <p class='col-sm-8 offset-sm-2 col-md-11 offset-md-1' style='font-size: 1.5em;text-align: center;'>Atualize o Base Rotas</p>
                        </div>";
                    }
                ?>
            </div>
            <!-- Formulário para adicionar uma nova cidade e rota -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <form id="formAdicionarRota">
                        <div class="row">
                            <div class="col-md-5">
                                <input type="text" id="novaCidade" class="form-control" placeholder="Digite a cidade" required>
                            </div>
                            <div class="col-md-5">
                                <input type="text" id="novaRota" class="form-control" placeholder="Digite a rota" required>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Adicionar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div id="table" class="table table-striped table-bordered" style="width: 100%;">
                    <table id="viagensAbertasTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cidade</th>
                                <th>Rota</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM base_rotas";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>{$row['cidade']}</td>
                                        <td>{$row['rota']}</td>
                                        <td>
                                            <button class='btn btn-acao' onclick='alterarRota({$row['id']})'>Alterar Rota</button>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center'>Nenhuma rota encontrada.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#viagensAbertasTable').DataTable({
                "paging": true,
                "searching": true,
                "info": true
            });

            // Carrega o navbar
            $.ajax({
                url: '../elements/E_navbar.php',
                type: 'POST',
                data: { relative: '../' },
                success: (result) => $("#navbarSt").html(result)
            });

            // Submissão do formulário para adicionar cidade e rota
            $('#formAdicionarRota').on('submit', function(e) {
                e.preventDefault(); // Impede o envio padrão do formulário
                const cidade = $('#novaCidade').val();
                const rota = $('#novaRota').val();

                $.ajax({
                    url: 'adicionar_rota.php', // Arquivo PHP para inserir os dados
                    type: 'POST',
                    data: { cidade, rota },
                    success: function(response) {
                        alert(response); // Mostra a mensagem de retorno
                        location.reload(); // Recarrega a página
                    },
                    error: function(xhr, status, error) {
                        alert("Erro ao adicionar rota: " + error);
                    }
                });
            });
        });

        function alterarRota(id) {
            const novaRota = prompt("Digite a nova rota para o ID " + id + ":");

            if (novaRota && novaRota.trim() !== "") {
                $.ajax({
                    url: 'atualizar_rota.php',
                    type: 'POST',
                    data: { id, rota: novaRota },
                    success: function(response) {
                        alert(response);
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        alert("Erro ao atualizar a rota: " + error);
                    }
                });
            } else {
                alert("A rota não foi alterada, pois o valor inserido é inválido.");
            }
        }
    </script>
</body>
</html>
