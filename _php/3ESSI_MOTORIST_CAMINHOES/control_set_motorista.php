<?php
    $relative = "../";
    require_once($relative."CRUD/relog.php");
    if($_SESSION["tipo"] != 1){
        print("<script>alert('Acesso não autorizado!');location.href='../home.php'</script>");
    }
?>
<?php
    require_once("../3SSI_CRUD/conexao.php");
    $placa = $_POST['inscricaoPlaca']; 
    $modelo = $_POST['Modelo'];
    $nome = $_POST['inputNome'];
    $cpf = $_POST['inputCPF'];
    $habilitacao = $_POST['inputNumHabilitacao'];
    $data = $_POST['inputData'];
    $senha = md5($_POST['inputSenha']);

    if($senha != $_SESSION['senha']){
        $pdo = null;
        print("<script>alert('Senha da conta incorreta!');history.back(-1);</script>");
        exit(-1);
    }


    $sqlMotoristas_Caminhoes = "CALL UpMotoristas_Caminhoes('$placa', '$modelo', '$nome', '$cpf', '$habilitacao', '$data')";
    
    $stmt = $pdo->prepare($sqlMotoristas_Caminhoes);
    try{
        $stmt->execute();
        print("<script>alert('Dados inseridos com sucesso no banco de dados!');location.href='view_set_motorista.php';</script>");
    } catch(PDOException $err) {
        print("<script>alert('Erro: Problemas na inserção de Motorista_Usuario. Erro gerado: " . $err->getMessage(). ");history.back(-1);</script>");
    } finally{
        $pdo = null;
    }
?>