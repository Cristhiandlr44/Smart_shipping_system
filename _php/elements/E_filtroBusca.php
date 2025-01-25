<?php 
    echo "<div class='container'>
        <div class='row'>
            <div id='linh1'>
                <span>Qtd de notas selecionadas:</span>
                <input id='qtdLinhas' class='form-control' type='text' name='' value='0' disabled style='width: 60px; display: inline-block;'>
            </div>
            <div id='linh2'>
                <button id='btnSelecionarTodas' onclick='selecionarTodasLinhasVisiveis();'>Selecionar todas as linhas abaixo</button>
            </div>
        </div>
        <div id='linha3' class='row my-2'>
            <span id='pesoTotalSpan' style='text-align: start; width: 100px;'>Peso Total:</span>
            <input id='peso' class='form-control' type='text' name='' value='0' disabled style='width: 85px;  text-align: center;'>
        </div>
    </div>
    <br>";
?>