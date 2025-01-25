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
    <script src="../../_scriptjs/script_get_products.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
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
        <div id="dash" class="inicial dash bg-white p-4 col-12">
            <div class="row">
                <?php
                    $title = "- Mapa de carregamento";
                    require_once("../elements/tituloProjetoMainSection.php");
                ?>
            </div>
            <div class="row">
                <p class="col-6 col-md-4 p-0" style="margin-left: 15px; font-size: 1.3em;">Data de Lançamento</p>
                <div id="col" class="offset-0 col-5 col-md-6 offset-md-1">
                    <input class="col-md-6 col-auto btn-outline-primary" type="date" id="dataLancamento">
                    <button class="col-md-5 col-auto btn btn-outline-primary" type="submit" id="btnBuscar">Buscar</button>
                </div>
            </div>
            <div id="resultado"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
</body>
</html>

