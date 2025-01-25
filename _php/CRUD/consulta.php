<?php
    include("conexao.php");
    $conn = mysqli_criar();
    if (isset($_POST['usuario'])) {
        $usuario = $_POST['usuario'];
        $consulta = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
        if ($resposta = $conn->query($consulta)) {
            if ($resposta->num_rows > 0) {
                echo "false";
            } else {
                echo "true";
            }
        }
    }
?>