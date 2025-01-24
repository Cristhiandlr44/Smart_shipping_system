<?php
header("Content-type: text/html; charset=utf-8"); 

$host = 'localhost';
$user = 'root'; 
$password = ''; 
$database = 'seminariobd';

$permission = isset($_POST['permission'])? $_POST['permission']: 0;

$relative = "../";
require_once($relative."CRUD/relog.php");
if(!($_SESSION["tipo"] == 1 || $permission)){
    print("<script>alert('Acesso não autorizado!');location.href='../home.php'</script>");
    exit(-1);
}

try {
    
//Conexão sem a porta
    $pdo = new PDO("mysql:host=$host;dbname=$database", $user, $password);
    //echo "Conexão com banco de dados realizado com sucesso.";
} catch (PDOException $err) {
    echo "Erro: Conexão com banco de dados não realizado com sucesso. Erro gerado " . $err->getMessage();
    exit(-1);
}
?>