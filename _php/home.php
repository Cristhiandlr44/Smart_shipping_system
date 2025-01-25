<?php
    $relative = "";
    require_once("CRUD/relog.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Login</title>
    <link rel="shortcut icon" href="../_assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../_style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="../_scriptjs/script.js"></script>
    <style>
        <?php 
            require("elements/cssNavbar.php");
        ?>
    </style>
</head>
<body>    
    <div id="navbarSt"></div>
    
    <div class="container d-flex justify-content-center align-items-center">
        <div class="dash bg-white">
            <div class="row p-3">
                <?php
                    $title = "- Página Inicial";
                    require_once("elements/tituloProjetoMainSection.php");
                ?>
                <p>
                    Bem vindo a página inicial do sistema! <br><Br>Aqui pode ser feito o controle de dados fiscais a cerca dos serviços prestados pela empresa de entregas. Além disso, contamos com um sistema de controle de Motoristas, de Caminhões sendo utilizados.
                </p>
                <hr>
                <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Illo amet vel praesentium corrupti, fugit doloremque, delectus natus ratione assumenda aut porro quis unde error deserunt animi laudantium? Accusantium, dolore odio.
                </p>
                <br style="margin-bottom: 20px;">
                <br><br><br><br><br><br><br>
                <hr>
                <h4 style="text-align: center;">Novidades</h4>
                <div id="carouselExampleAutoplaying" class="row carousel slide col-md-10 offset-md-1 col-lg-12 offset-lg-0 offset-0 col-12 mt-2" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                        <img src="https://lh3.googleusercontent.com/p/AF1QipP8yx8Mfe4x7iZpWbQXz8fko0v70gZYAKBXn5KP=s680-w680-h510" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                        <img src="https://lh3.googleusercontent.com/p/AF1QipM8dMvSC7zALp0tUtQpvuoSzJLq_tYTduZrDwQ2=s680-w680-h510" class="d-block w-100" alt="...">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../_scriptjs/script.js"></script>
    <script>
        $(document).ready(() => {
            $.ajax({
                url: 'elements/E_navbar.php',
                type: 'POST',
                data: {
                    relative: ''
                },
                success: (result) => {
                    $("#navbarSt").html(result);
                }
            });
        });
    </script>
</body>
</html>