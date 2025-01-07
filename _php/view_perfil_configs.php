<?php
    $relative = "";
    require_once("CRUD/relog.php"); 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="shortcut icon" href="../_assets/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <link rel="stylesheet" href="../_style/style.css">
    <style>
        input{
            margin-bottom: 10px;
        }

        .dash{
            max-width: 990px;
        }

        .buttons{
            margin-top: -200px;
        }
        @media (min-width: 992px) {
            #linha1{
                justify-content: center;
            }
            #linha1 img{
                width: 400px;
            }
        }

        <?php 
            require("elements/cssNavbar.php");
        ?>
    </style>
</head>
<body>
    <div id="navbarSt"></div>

    <div class="container d-flex justify-content-center align-items-center">
        <div class="dash bg-white">
            <div class="p-3">
                <?php 
                    $title = "- Alterar informações de Perfil";
                    require_once("elements/tituloProjetoMainSection.php");
                ?>
                <div class="container bg-white mb-1">
                    <form action="CRUD/alterRow.php" method="POST">
                        <div class="row">
                            <div id="linha1" class="col-md-4 col-lg-5 border-right d-flex flex-column align-items-center">
                                <div class="text-center">
                                    <img src="https://cdn-icons-png.flaticon.com/256/6596/6596121.png" alt="" width="225px" class="rounded-circle mt-4 d-block">
                                    <span class="font-weight-bold d-block">
                                        <?php
                                            echo $_SESSION['nome'];
                                        ?>
                                    </span>
                                    <span class="text-black-50 d-block">
                                        <?php
                                            echo $_SESSION["email"]. "<br>";
                                            $nome = ""; $sobrenome = "";
                                            $i = 0;
                                            for(;$i < strlen($_SESSION['nome']); $i++){
                                                if($_SESSION['nome'][$i] == " ") break;
                                                $nome .= $_SESSION['nome'][$i];
                                            }
                                            $i++;
                                            for(;$i < strlen($_SESSION['nome']); $i++){
                                                $sobrenome .= $_SESSION['nome'][$i];
                                            }
                                        ?>
                                    </span>
                                    <span>&nbsp;</span>
                                </div>
                            </div>
                            <div class="col-md-8 offset-lg-1 col-lg-6 border-right">
                                <div class="p-2 py-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="text-right">Configurações de Perfil</h4>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6"><label class="labels">Nome</label><input type="text" name="nome" required pattern="[A-Za-z ]+" class="form-control" placeholder="Primeiro nome" value="<?php echo $nome;?>"></div>
                                        <div class="col-md-6"><label class="labels">Sobrenome</label><input type="text" name="sobrenome" required pattern="[A-Za-z ]+" class="form-control" value="<?php echo $sobrenome;?>" placeholder="Sobrenome"></div>
                                        <div class="col-md-12"><label class="labels">Email</label><input type="text" name="email" required pattern=".*@.*\.com.*" class="form-control" placeholder="seuemail@dominio.com" value="<?php echo $_SESSION['email'];?>"></div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <!-- hide -->
                                            <small id="userValidation" class="hide" style="margin-top: 0; color: rgba(255, 0, 0, 0.678);">
                                                Usuário já cadastrado!
                                            </small>
                                            <br>
                                            <label class="labels">Nome de usuário</label>
                                            <input type="text" id="newUser" name="usuario" class="form-control" placeholder="Username" value="<?php echo $_SESSION['usuario'];?>" required pattern="[A-Za-z0-9._`´À-ÖØ-öø-ÿ ]+" onblur="buscarUser(this.value);" oninput="this.value = this.value.replace(/[^A-Za-z0-9._`´À-ÖØ-öø-ÿ ]+/, ''); var element = document.querySelector('#userValidation'); element.classList.add('hide');">
                                        </div>
                                        <div class="col-md-6"><label class="labels">Cep</label><input type="text" class="form-control" placeholder="Digite seu cep" value="" disabled></div>
                                        <div class="col-md-6"><label class="labels">Estado</label><input type="text" class="form-control" placeholder="Nome do estado" value="" disabled></div>
                                        <div class="col-md-12"><label class="labels">Bairro</label><input type="text" class="form-control" placeholder="Nome do bairro" value="" disabled></div>
                                        <div class="col-md-12"><label class="labels">Cidade</label><input type="text" class="form-control" placeholder="Nome da cidade" value="" disabled></div>
                                        <div class="col-md-12"><label class="labels">Referência</label><input type="text" class="form-control" placeholder="Referência" value="" disabled></div>
                                        <div class="col-md-12"><label class="labels">Escolaridade</label><input type="text" class="form-control" placeholder="Nível de Escolaridade" value="" disabled></div>
                                    </div>
                                    <div class="row mt-6">
                                        <div class="col-md-6"><label class="labels">País</label><input type="text" class="form-control" placeholder="Nome do país" value="" disabled></div>
                                        <div class="col-md-6"><label class="labels">Região</label><input type="text" class="form-control" value="" placeholder="Nome da região" disabled></div>
                                    </div>
                                    <div class="row mt-6">
                                    <div class="col-md-6"><label class="labels">Data de Cadastro</label><input type="text" class="form-control" value="<?php echo $_SESSION['data'];?>" placeholder="Nome da região" disabled></div>
                                    </div>
                                    <div class="buttons mt-5 text-center">
                                        <button class="btn btn-outline-primary profile-button" type="submit">Salvar alterações</button>
                                        <button class="btn btn-outline-danger profile-button" type="button" onclick="location.href='CRUD/deleteUser.php';">
                                            <?php
                                                if($_SESSION['tipo'] == 1){
                                                    echo "Apagar conta";
                                                } else{
                                                    echo "Desativar conta";
                                                }
                                            ?>
                                        </button>
                                        <button class="btn btn-outline-secondary profile-button" type="button" onclick="location.href='CRUD/logout.php';">Sair</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../_scriptjs/script.js"></script>
    <script>
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
        });

        function buscarUser(usuario){
            var validador = document.querySelector("#userValidation");
            $.ajax({
                type: "POST",
                url: "CRUD/consulta.php",
                data: { usuario: usuario },
                success: function(response) {
                    var resposta = response;
                    if(resposta === "false" && usuario != "<?php echo $_SESSION['usuario'];?>"){
                        validador.classList.remove("hide");
                        verificado(document.querySelector("#newUser"), "", 1);

                    } else{
                        validador.classList.add("hide");
                        verificado(document.querySelector("#newUser"), usuario, 1);
                    }
                }
            });
        }
    </script>
</body>
</html>