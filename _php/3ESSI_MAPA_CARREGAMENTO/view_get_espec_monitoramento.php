<?php
    $relative = "../";
    require_once($relative."CRUD/relog.php");


    $idMonitoramento = $_GET['id'];
    $placa = $_GET['placa'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoramento <?php echo $idMonitoramento; ?></title>
    <link rel="shortcut icon" href="../../_assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../_style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <script src="../../_scriptjs/script_get_especifc_monitoramentos.js"></script>
    <style>
        .btns{
            display: block;
            margin: auto;
            margin-top: 15px;
            margin-bottom: 10px;
        }
        
        .dash{
            height: 100%;
            min-height: 600px;
            min-width: 516px;
            margin: 10px auto;
            max-width: 980px;
            border-radius: 3px;
            box-shadow: rgba(14, 30, 37, 0.12) 0px 2px 4px 0px, rgba(14, 30, 37, 0.32) 0px 2px 16px 0px;
        }
        input,
        button{
            max-width: 180px;
        }
        <?php 
            require("../elements/cssNavbar.php");
        ?>
    </style>
</head>
<body>
    <div id="navbarSt"></div>
    <div class="container d-flex justify-content-center align-items-center">
        <div id="<?php echo "$idMonitoramento"; ?>" class="inicial dash bg-white p-4 col-12">
            <div class="row">
                <?php
                    $title = "- Placa: {$placa}";
                    require_once("../elements/tituloProjetoMainSection.php");
                ?>
            </div>
            <div class="row">
                <!--<div id="col" class="offset-0 col-5 col-md-6 offset-md-1">
                    <input class="col-md-6 col-auto btn-outline-primary" type="date" id="dataLancamento">
                    <button class="col-md-5 col-auto btn btn-outline-primary" type="submit" id="btnBuscar">Buscar</button>
                </div> -->
            </div>
            <div id="resultado" class='row'></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
</body>
</html>

