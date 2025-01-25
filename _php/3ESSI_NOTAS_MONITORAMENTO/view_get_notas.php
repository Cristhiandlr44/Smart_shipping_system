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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../../_scriptjs/script.js"></script>
    <!-- Adicione a biblioteca DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
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
        <?php 
            require("../elements/cssNavbar.php");
        ?>
    </style>
</head>
<body>    
    <div id="navbarSt"></div>
    <div class="container d-flex justify-content-center align-items-center">
        <div class="dash bg-white">
            <div class="row p-3">
                <?php
                    $title = "- Tabelas de Notas sem Monitoramento";
                    require_once($relative. "elements/tituloProjetoMainSection.php");

                    if($_SESSION['tipo'] == 1){
                        echo "
                        <div class='row'>
                            <p class='col-sm-8 offset-sm-2 col-md-11 offset-md-1' style='font-size: 1.5em;text-align: center;'>Selecione as notas desejadas para um novo monitoramento</p>
                        </div>
                        <div class='container'>
                            <div class='row justify-content-center'>
                            <button id= 'btnGerarMonitoramento' class='col-auto btn btn-outline-primary mt-3 mb-3'>Gerar monitoramento</button>tabelaNotas_filter
                            </div>
                        </div>
                        <div id='filtro' class='row'>
                        </div>";
                    }
                ?>
            </div>
            <div class="row" style="padding: 0px 1em 1em 1em;">
                <div id="table" class="col-md-12 col-11 offset-1 offset-sm-0 p-2" style="max-width: 100%;">
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../../_scriptjs/script.js"></script>

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
    <?php 
        if($_SESSION['tipo'] == 1){
            echo '<script src="../../_scriptjs/script_prepara_monitoramentos.js"></script>';
        }
    ?>

    <?php    
        if($_SESSION['tipo'] != 1){
            echo '<script src="../../_scriptjs/script_get_notas_user.js"></script>';
        }
    ?>
</body>
</html>