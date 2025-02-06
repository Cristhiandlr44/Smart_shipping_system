<?php
    require_once("../3SSI_CRUD/conexao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas e Monitoramento</title>
    <link rel="shortcut icon" href="../../_assets/favicon.ico" type="image/x-icon">
    <link href="../../_style/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="../../_scriptjs/script.js"></script>
    <script src="../../_scriptjs/script_format_forms.js"></script>
    <script src="../../_scriptjs/script_set_notas.js"></script>
    <style>
        input[type=data], input[type=date]{
            margin: 5px 0;
        }
        .btns{
            margin-top: 35px;
            margin-bottom: 10px;
        }

        .btns,
        input{
            width: 100%;
        }

        h2{
            margin-bottom: 30px;
            white-space: nowrap;
        }

        .dash{
            width: 69vw;
            /* min-height: 87vh; */
            margin: auto;
            max-width: 920px;
            min-width: 330px;
        }
        
        @media (max-width: 995px) {
            .dash {
                /* min-height: 720px; */
                margin-bottom: 35px;
            }
        }
        <?php 
            require("../elements/cssNavbar.php");
        ?>
    </style>
</head>
<body>
    <div id="navbarSt"></div>
    
    <div class="container d-flex justify-content-center align-items-center" style="margin-top: 10px;">
        <div class="dash col-lg-4 offset-lg-4 bg-white">
            <div class="row p-3">
                <?php 
		    $relative = "../";
                    $title = "- Escaner de Notas Fiscais";
                    require_once($relative. "elements/tituloProjetoMainSection.php");
                    require_once($relative. "elements/selecionadorDeFornecedor.php");
                ?>
                <hr id='hr' class='hide'>
                <div class="Notas">
                    <div id="Dellys" class="hide container px-2 py-2 text-center">
                        <div class="row g-4 p-2 row-cols-1 row-cols-lg-2">
                            <div class="col-10 col-lg-6 mx-auto">
                                <h2>Escanear XML Dellys</h2>
                                <form id="xmlFormDellys">
                                    <div id="inserir" class="scan">
                                        <div class="mb-3">
                                            <input class="form-control" type="file" id="formFile" name="xmlFilesInput[]" accept=".xml" multiple required>
                                            <input id="dataDellys" class="form-control" type="date" style="margin-top: 5px;" required value="<?php echo date("Y-m-d"); ?>">
                                            <button type="button" id="scanButtonDellys" class='btns btn btn-outline-secondary'>Escanear &nbspXMLs</button>
                                        </div>
                                    </div>
                                </form>
                                <div class="col-md-6 offset-md-3 mt-2">
                                    <div id="loadingDellys" class="hide">Carregando...</div>
                                    <progress id="progressBarDellys" class="hide" value="0" max="100"></progress>
                                </div>
                                <div id="outputDellys" class="mt-1">

                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div id="Aurora" class="hide container px-2 py-2 text-center">
                        <div class="row g-4 p-2 row-cols-1 row-cols-lg-2">
                            <div class="col-10 col-lg-6 mx-auto">
                                <h2>Escanear XML Aurora</h2>
                                <form id="xmlFormAurora">
                                    <div id="inserir" class="scan">
                                        <div class="mb-3">
                                            <input class="form-control" type="file" id="xmlFilesInput" name="xmlFilesInput[]" accept=".xml" multiple required>
                                            <input id="dataAurora" class="form-control" type="date" style="margin-top: 5px;" required value="<?php echo date("Y-m-d"); ?>">
                                            <button type="button" id="scanButtonAurora" class='btns btn btn-outline-secondary'>Escanear &nbspXMLs</button>
                                        </div>
                                    </div>
                                </form>
                                <div class="col-md-6 offset-md-3 mt-2">
                                    <div id="loadingAurora" class="hide">Carregando...</div>
                                    <progress id="progressBarAurora" class="hide" value="0" max="100"></progress>
                                </div>
                                <div id="outputAurora" class="mt-1"></div>
                            </div>
                            <div class="col-10 col-lg-6 offset-lg-0 mx-auto">
                                <h2>Escanear Excel Aurora</h2>
                                <p style='font-size: 17px; color: gray;margin-bottom: 19.3px;'>Datas de produção e validade</p>
                                <form id="xlsxFormAurora">
                                    <div class="mb-3">
                                        <input class="form-control" type="file" id="fileInput" name="excel_file" accept=".xlsx" required>
                                        <input type="submit" id="enviarAurora" class="btns btn btn-outline-secondary d-inline-block" value="Escanear XLSXs">
                                    </div>
                                </form>
                                <div id="outputAuroraExcel" class="mt-1"></div>
                            </div>
                        </div>
                    </div>
                    <div id="Saudali" class="hide container-fluid px-2 py-2 text-center">
                        <div class="hide row g-4 py-2 row-cols-1 row-cols-lg-1">
                            <div class="col">
                                <h2>Escanear XML Saudali</h2>
                                <form id="xmlFormSaudali">
                                    <div id="inserir" class="scan">
                                        <div class="col-md-6 offset-md-3 mb-3">
                                            <input class="form-control" type="file" id="InputSaudali" name="xmlSaudaliFilesInput[]" accept=".xml" multiple required>
                                            <input id="dataSaudali" type="date" class="form-control" required value="<?php echo date("Y-m-d"); ?>">
                                            <input id="cargaSaudali" type="number" class="form-control" placeholder="Digite a Carga:" required>
                                            <button type="button" id="scanButtonSaudali" class="btns btn btn-outline-secondary">Escanear XMLs</button>
                                        </div>
                                    </div>
                                </form>
                                <div id="loadingSaudali" class="hide">Carregando...</div>
                                <progress id="progressBarSaudali" class="hide" value="0" max="100"></progress>
                                <div id="outputSaudali"></div>
                            </div>
                        </div>
                    </div>
                    <!--
                    <div id="Cruzeiro" class="hide container-fluid px-2 py-2 text-center">
                        <div class="hide row g-4 py-2 row-cols-1 row-cols-lg-1">
                            <div class="col">
                                    <h2>Escanear XML Cruzeiro</h2>
                                    <form id="xmlFormCruzeiro">
                                        <div id="inserir" class="scan">
                                            <div class="col-md-6 offset-md-3 mb-3">
                                                <input class="form-control" type="file" id="fileInput" name="xmlCruzeiroFilesInput[]" accept=".xml" multiple required>
                                                <input id="dataCruzeiro" type="date" class="form-control" required value="<?php echo date("Y-m-d"); ?>">
                                                <input id="cargaCruzeiro" type="number" class="form-control" placeholder="Digite a Carga:" required>
                                                <button type="button" id="scanButtonCruzeiro" class="btns btn btn-outline-secondary">Escanear XMLs</button>
                                            </div>
                                        </div>
                                    </form>
                                    <div id="loadingCruzeiro" class="hide">Carregando...</div>
                                    <progress id="progressBarCruzeiro" class="hide" value="0" max="100"></progress>
                            </div>
                            <div id="outputCruzeiro"></div>
                        </div>
                    </div>                    
                    <div id="Plena" class="hide container-fluid px-2 py-2 text-center">
                        <div class="hide row g-4 py-2 row-cols-1 row-cols-lg-1">
                            <div class="col">
                                <h2>Escanear Excel Plena</h2>
                                <form enctype="multipart/form-data" action="processPlena.php" method="post" id="plenaForm">
                                    <div id="inserir" class="scan">
                                        <div class="col-md-6 offset-md-3 mb-3">
                                            <input class="form-control" type="file" id="inputPlena" name="excel_file" accept=".xlsx" required>
                                            <input id="data" type="date" class="form-control" required/>
                                            <input id="cargaPlena" class="form-control" type="number" placeholder="Digite a Carga:" required>
                                            <input type="submit" value="Escanear XLSXs" class="btns btn btn-outline-secondary">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div id="outputPlena"></div>
                        </div>
                    </div>
                    <div id="Suinco" class="hide container-fluid px-2 py-2 text-center">
                        <div class="hide row g-4 py-2 row-cols-1 row-cols-lg-1">
                            <div class="col">
                                <h2>Escanear XML Suinco</h2>
                                <form id="xmlFormSuinco">
                                    <div id="inserir" class="scan">
                                        <div class="col-md-6 offset-md-3 mb-3">
                                            <input class="form-control" type="file" id="InputSuinco" name="xmlSuincoFilesInput[]" accept=".xml" multiple required>
                                            <input id="dataSuinco" type="date" class="form-control" required value="<?php echo date("Y-m-d"); ?>">
                                            <input id="cargaSuinco" type="number" class="form-control" placeholder="Digite a Carga:" required>
                                            <button type="button" id="scanButtonSuinco" class="btns btn btn-outline-secondary">Escanear XMLs</button>
                                        </div>
                                    </div>
                                </form>
                                <div id="loadingSuinco" class="hide">Carregando...</div>
                                <progress id="progressBarSuinco" class="hide" value="0" max="100"></progress>
                                <div id="outputSuinco"></div>
                            </div>
                        </div>
                    </div>
                    -->
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(() => {
            $.ajax({
                url: '../elements/E_navbar.php',
                type: 'POST',
                data: {
                    relative: '../' // Passe o ID desejado aqui
                },
                success: (result) => {
                    $("#navbarSt").html(result);
                }
            });
        });
    </script>
</body>
</html>
