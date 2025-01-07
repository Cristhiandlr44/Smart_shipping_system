<?php
    $relative = "../";
    require_once($relative."CRUD/relog.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Carregamento</title>
    <link rel="shortcut icon" href="../../_assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../_style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
   
</head>
<body>
    <div id="navbarSt"></div>
    <div class="container d-flex justify-content-center align-items-center">
        <div class="dash bg-white">
            <div class="row p-3">
                <h3>- Mapa de Carregamento</h3>
            </div>
            <!-- Lista de datas com monitoramento -->
            <div class="row">
                <div class="col-12">
                    <h4>Selecione uma Data:</h4>
                    <div id="dataList" class="d-flex flex-wrap">
                        <!-- As datas serão carregadas aqui via Ajax -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para mostrar os resumos dos mapas -->
    <div class="modal fade" id="modalResumo" tabindex="-1" aria-labelledby="modalResumoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalResumoLabel">Resumo do Mapa de Carregamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalResumoContent">
                    <!-- Os cards serão carregados aqui via Ajax -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para mostrar o mapa detalhado -->
    <div class="modal fade" id="modalDetalhado" tabindex="-1" aria-labelledby="modalDetalhadoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetalhadoLabel">Mapa Detalhado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table id="tabelaDetalhado" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Operação</th>
                                <th>Codigo</th>
                                <th>Descricao</th>
                                <th>Peso</th>
                                <th>Quantidade</th>
                                <th>Data Produção</th>
                                <th>Data Validade</th>
                            </tr>
                        </thead>
                        <tbody id="tabelaDetalhadoBody">
                            <!-- Dados detalhados serão carregados aqui -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="../../_scriptjs/script_get_products_2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
