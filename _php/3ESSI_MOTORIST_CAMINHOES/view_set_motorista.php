<?php
    $relative = "../";
    require_once($relative."CRUD/relog.php");
    if($_SESSION["tipo"] != 1){
        print("<script>alert('Acesso não autorizado!');location.href='../home.php'</script>");
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motoristas & Caminhões</title>
    <link rel="shortcut icon" href="../../_assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../_style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="../../_scriptjs/script.js"></script>
    <script src="../../_scriptjs/script_format_forms.js"></script>
    <style>
        .btns{
            display: block;
            margin: auto;
            margin-top: 15px;
            margin-bottom: 10px;
        }
        
        .dash{
            max-width: 920px;
            max-height: 5200px;
        }
        
        @media (max-width: 780px) {
            form h3{
                margin-top: 40px;
            }
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
                    $title = "- Cadastro de Motoristas e Caminhões";
                    require_once($relative. "elements/tituloProjetoMainSection.php");
                ?><div class="row">
            </div>
                <div class="px-2 py-2" style="margin-bottom: 35px;">
                    <div class="row g-4 py-2 px-2 col-md-12">
                        <form action="control_set_motorista.php" method="POST" class="d-flex">
                            <div class="row col-6 col-md-6 col-lg-6">
                                <h3 style="margin-bottom: 0;">Cadastrar Motorista</h3>
                                <div class="col-md-11 col-lg-10">
                                    <label for="inputText1" class="form-label">Nome</label>
                                    <input type="text" class="form-control" id="inputNome" name="inputNome" placeholder="Nome do Motorista">
                                </div>
                                <div class="col-md-10 col-lg-6 mt-3">
                                    <label for="inputText2" class="form-label">CPF</label>
                                    <input type="text" name="inputCPF" id="inputCPF" class="form-control" maxlength="14" placeholder="CPF do Motorista" oninput="formatarCPF(this)" required pattern="\d{11}\" title="000.000.000-00">
                                </div>
                                <div class="col-md-9 col-lg-7 mt-4">
                                    <label for="inputText3" class="form-label">N° de Habilitação</label>
                                    <input type="text" class="form-control" id="inputNumHabilitacao" name="inputNumHabilitacao" placeholder="Número de Habilitação" maxlength="8">
                                </div>
                                <div class="col-md-9 mt-3">
                                    <label for="inputAddress2" class="form-label">Data de Vencimento da Habilitação</label>
                                    <input type="date" class="form-control" id="inputData" name="inputData" placeholder="01/01/2023">
                                </div>
                            </div>
                            
                            <div class="row offset-1 offset-md-0 offset-lg-2 col-6 col-md-6 col-lg-5">
                                <h3 style="margin-bottom: 0;">Cadastrar Caminhão</h3>
                                <div class="col-md-12">
                                    <label for="inputPlaca" class="form-label">Inscrição da Placa</label>
                                    <input type="text" class="form-control" name="inscricaoPlaca" id="inputPlaca" placeholder="Valor da Placa" required autocomplete="off">
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label for="inputModelo" class="form-label">Modelo de Veículo</label>
                                    <select id="inputModelo" name="Modelo" class="form-select" required>
                                        <option selected>Selecione o Modelo</option>
                                        <option value="T">Toco</option>
                                        <option value="B">Truco</option>
                                        <option value="L">Leve</option>
                                        <option value="3">3x4</option>
                                    </select>
                                </div>
                                <div class="col-md-5 mt-3">
                                    <label for="inputSenha" class="form-label">Senha da conta</label>
                                    <input type="password" class="form-control" id="inputSenha" name="inputSenha" required autocomplete="current-password">
                                </div>
                                <div class="col-12 mt-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="gridCheck" required>
                                        <p class="form-check-label" for="gridCheck">
                                            Confirmar veracidade dos dados acima
                                        </p>
                                    </div>
                                    <button type="submit" class="btn btn-primary" onclick="removerMascaraCPF(document.getElementById('inputCPF')); formatData(document.getElementById('inputData'));">
                                        Inserir Motorista-Caminhão
                                    </button>
                                </div>
                            </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="../../_scriptjs/script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(() => {
            $.ajax({
                url: '../elements/E_navbar.php',
                type: 'POST',
                data: {
                    relative: '../'
                },
                success: (result) => {
                    $("#navbarSt").html(result);
                }
            });
        });
    </script>
</body>
</html>