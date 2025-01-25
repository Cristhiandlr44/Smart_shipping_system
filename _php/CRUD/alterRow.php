<?php
    session_start();
    if(empty($_SESSION)){
        print("<script>location.href='../../index.html'</script>");
    }
?>
<?php
    $usuario = $_POST["usuario"];
    $oldUsuario = $_SESSION['usuario'];

    include("conexao.php");
    $conn = mysqli_criar();
    $consulta = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
    $resposta = $conn->query($consulta);
    if ($resposta->num_rows > 0 && $usuario != $_SESSION['usuario']) {
        print("<script>alert('Erro, usuário já cadastrado no sistema!');history.back(-1);</script>");
        exit();
    }

    $nome = $_POST["nome"]. " ". $_POST["sobrenome"];
    $email = $_POST["email"];

    $sql = "UPDATE `usuarios` SET `usuario` = '$usuario', `nome` = '$nome', `email` = '$email' WHERE `usuarios`.`usuario` = '$oldUsuario'";
    $res = $conn->query($sql);
    
    if ($res) {
        $_SESSION['usuario'] = $usuario;
        print("<script>alert('Dados da conta foram alterados!');history.back(-1);
        </script>");
    } else{
        print("<script>alert('Erro ao alterar dados da conta!');history.back(-1);</script>");
    }

?>