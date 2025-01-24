<?php
    $usuario = $_POST["usuario"];

    include("conexao.php");
    $conn = mysqli_criar();
    $consulta = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
    $resposta = $conn->query($consulta);
    if ($resposta->num_rows > 0) {
        print("<script>alert('Erro, usuário já cadastrado no sistema!');history.back(-1);</script>");
        exit();
    }

    $nomeCompleto = $_POST["nome"]." ". $_POST["sobrenome"];
    $email = $_POST["email"].$_POST["dominio"];
    $senha = md5($_POST["senha"]);
    $confirmaSenha = $_POST["confirmaSenha"];

    $sql = "INSERT INTO `usuarios` (`nome`, `email`, `usuario`, `senha`, `tipo`, `data`) VALUES 
    ('$nomeCompleto', '$email', '$usuario', '$senha', '0', CURRENT_DATE())";
    
    if(mysqli_query($conn, $sql)){
        print("<script>alert('Usuário cadastrado com sucesso!');location.href='index.html';</script>");
    } else{
        print("<script>alert('Erro no cadastro de usuário!');history.back(-1);</script>");
    }
    header("Location: ../../index.html");
?>