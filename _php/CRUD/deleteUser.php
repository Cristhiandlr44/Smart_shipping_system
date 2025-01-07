<?php
    session_start();
    if(empty($_SESSION)){
        print("<script>location.href='../../index.html'</script>");
    }
    
    $usuario = $_SESSION["usuario"];
    $nome = $_SESSION["nome"];
    $tipo = $_SESSION["tipo"];
    $email = $_SESSION["email"];
    $data = $_SESSION["data"];
    
    include("conexao.php");
    $conn = mysqli_criar();
?>
<?php
    function executarExclusao($conexao, $sentenca){
        if (mysqli_query($conexao, $sentenca)) {
            print("<script>alert('A conta do usuário foi deletado do sistema!');location.href='logout.php';
            </script>");
            exit();
        } else{
            print("<script>alert('Erro ao deletar a conta de usuário!');history.back(-1);</script>");
        }
    }

    $sql = "UPDATE `usuarios` SET `usuario` = '#$usuario' WHERE `usuarios`.`usuario` = '$usuario'";
    
    //header("Location: ../../index.html");
    executarExclusao($conn, $sql);
?>