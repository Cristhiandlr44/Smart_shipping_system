<?php
header("Content-type: text/html; charset=utf-8"); 
$HOST = "localhost";
$USER = "root";
$PASS = "";
$BASE = 'seminariobd';

function mysqli_criar(){
    return mysqli_connect($GLOBALS['HOST'], $GLOBALS['USER'], $GLOBALS['PASS'], $GLOBALS['BASE']);
}

function PDO_Criar(){
    return new PDO("mysql:host=".$GLOBALS['HOST'].";dbname=".$GLOBALS['BASE'], $GLOBALS['USER'], $GLOBALS['PASS']);
}
?>