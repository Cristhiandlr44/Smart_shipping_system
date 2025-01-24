<?php
    //O QUE É SESSION
    session_start();
    unset($_SESSION["usuario"]);
    unset($_SESSION["nome"]);
    unset($_SESSION["tipo"]);
    unset($_SESSION["email"]);
    unset($_SESSION["data"]);
    unset($_SESSION["id"]);
    session_destroy();
    header("Location: ../../index.html");
    exit();
?>