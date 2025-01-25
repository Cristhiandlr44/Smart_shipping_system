<?php
    require_once("../CRUD/relog.php");

    if(isset($_POST['clienteId']) && isset($_POST['tipoCliente'])) {
        $clienteId = $_POST['clienteId'];
        $tipoCliente = $_POST['tipoCliente'];

        $sql = "UPDATE clientes SET tipo = '$tipoCliente' WHERE CNPJ = $clienteId";
        if (mysqli_query($conn, $sql)) {
            echo "Tipo de cliente atualizado com sucesso!";
        } else {
            echo "Erro ao atualizar tipo de cliente: " . mysqli_error($conn);
        }
    }
?>
