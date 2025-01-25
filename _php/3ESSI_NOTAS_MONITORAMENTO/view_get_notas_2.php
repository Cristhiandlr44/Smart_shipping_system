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
    <!-- Adicione a biblioteca DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../../_scriptjs/script.js"></script>

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
    
    <style>
        .dash{
            min-height: 90vh;
            height: 100%;
        }
        .form-control{
            display: inline;
            width: auto;
        }
        .table-hover:hover{
            cursor: pointer;
        }
        .linha-destaque {
            background-color: #f8d7da !important;
            color: #721c24 !important;
        }
        <?php 
            require("../elements/cssNavbar.php");
        ?>
        #modalNotasRota .modal-dialog {
    max-width: 90%; /* Ajusta o modal para ter largura maior em telas pequenas */
    width: auto;
}

#modalNotasRota .modal-body {
    max-height: 60vh; /* Limita a altura do modal */
    overflow-y: auto; /* Permite rolagem se necessário */
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
                                    <p class='col-sm-8 offset-sm-2 col-md-11 offset-md-1' style='font-size: 1.5em;text-align: center;'>Selecione uma rota para Carregamento</p>
                                </div>";
                            }
                ?>         
              
              <div class="container mt-5">
                <h3>Filtrar por Fornecedor</h3>
                
                <!-- Select para múltiplos fornecedores -->
                <div >
                    <label for="filter-fornecedor" >Selecione os Fornecedores:</label>
                    <select id="filter-fornecedor" class="form-select" multiple>
                        <!-- As opções serão preenchidas dinamicamente pelo JavaScript -->
                    </select>
                </div>

                <!-- Botão para gerar a URL com os fornecedores selecionados -->
                <button id="generateUrlBtn" class="btn btn-primary">Filtrar</button>

                <!-- Exibir a URL gerada -->
                <div class="mt-3" id="generatedUrl"></div>
            </div>

         
                <div class="container my-4">
                <div class="row">

                    <!-- Cards das Rotas -->
                    <div id="cardsRotas" class="d-flex flex-wrap gap-3">
                        <!-- Cards gerados dinamicamente via JavaScript -->
                    </div>
                </div>
            </div>
            



                    <!-- Modal de Notas por Rota -->
            <div class="modal fade" id="modalNotasRota" tabindex="-1" aria-labelledby="modalNotasRotaLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalNotasRotaLabel">
                                Notas da Rota - <span id="modalDetalhesRota"></span>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                                <table class="table table-striped table-bordered text-nowrap">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th >Operação</th>
                                            <th >Nota Fiscal</th> <!-- Coluna 3 maior -->
                                            <th >Cliente</th>
                                            <th >Peso</th>
                                            <th >Bairro</th>
                                            <th >Cidade</th>
                                            <th >Reentrega</th>
                                            <th >Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody id="notasRotaTable">
                                        <!-- Notas inseridas dinamicamente -->
                                    </tbody>
                                </table>
                            <button id="btnTrocarRotas" class="btn btn-primary" disabled onclick="abrirModalTrocarRota()">Trocar Rota para Várias Notas</button>
                            <button id="btnTrocarRotas" class="btn btn-success" onclick="enviarIdsSelecionados(linhasSelecionadas)">Gerar Viagem</button>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Modal Trocar Rota Varias Notas -->
                <div class="modal fade" id="modalTrocarRota" tabindex="-1" aria-labelledby="modalTrocarRotaLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTrocarRotaLabel">Trocar Rota</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="notaId">
                                <label for="selectRota">Selecione a nova rota:</label>
                                <select id="selectRota" class="form-select">
                                    <!-- As opções serão preenchidas dinamicamente -->
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="button" class="btn btn-primary" onclick="trocarRota()">Trocar Rota</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                            $title = "- Rotas";
                            require_once($relative . "elements/tituloProjetoMainSection.php");

                            if ($_SESSION['tipo'] == 1) {
                                echo "
                                <div class='row'>
                                    <p class='col-sm-8 offset-sm-2 col-md-11 offset-md-1' style='font-size: 1.5em;text-align: center;'>Redes</p>
                                </div>";
                            }
                ?>    
                    </div>

         
                    <div class="container my-4">
                    <div class="row">

                        <!-- Cards das Rotas -->
                        <div id="cardsRotasRedes" class="d-flex flex-wrap gap-3">
                            <!-- Cards gerados dinamicamente via JavaScript -->
                        </div>
                    </div>
                    </div>
            </div>


                        </div>
                        <div class="row" style="padding: 0px 1em 1em 1em;">
                            <div id="table" class="col-md-12 col-11 offset-1  offset-sm-0 p-2" style="max-width: 100%;">
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
</body>
</html>