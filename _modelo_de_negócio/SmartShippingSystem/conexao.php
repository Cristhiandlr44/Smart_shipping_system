<?php

//Inicio da conexão com o banco de dados utilizando PDO
$host = 'localhost';
$user = 'root'; 
$password = ''; 
$database = 'seminariobd';

try {
    //Conexão com a porta
    //$conn = new PDO("mysql:host=$host;port=$port;dbname=" . $dbname, $user, $pass);

    //Conexão sem a porta
    $pdo = new PDO("mysql:host=$host;dbname=$database", $user, $password);
    //echo "Conexão com banco de dados realizado com sucesso.";
} catch (PDOException $err) {
    echo "Erro: Conexão com banco de dados não realizado com sucesso. Erro gerado " . $err->getMessage();
    print("<script>alert('Erro: Conexão com banco de dados não realizado com sucesso. Erro gerado'". $err.");</script>"); 
    exit(-1);
}
    //Fim da conexão com o banco de dados utilizando PDO
