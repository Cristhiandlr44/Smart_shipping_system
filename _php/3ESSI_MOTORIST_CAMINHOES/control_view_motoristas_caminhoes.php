<?php
    require_once("../3SSI_CRUD/conexao.php");
    $sql = "SELECT * FROM `visao_motoristascaminhoes` WHERE 1";
    $stmt = $pdo->prepare($sql);
    
    try{
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $stmt->execute();
            $perfis = $stmt->fetchAll(PDO::FETCH_ASSOC);

            header('Content-Type: application/json'); // Defina o cabeçalho para JSON

            if (count($perfis) > 0) {
                echo json_encode($perfis);
            } else {
                echo json_encode(["message" => "none"]);
            }
        }
    } catch (PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }

$pdo = null;
?>
