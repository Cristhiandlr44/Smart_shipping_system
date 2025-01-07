<?php
    require_once("conexao.php");

    $sql = "SELECT `id`, `nome`, `email`, `usuario`, `tipo`, `data` FROM `usuarios` ORDER BY `id`";
    $conn = PDO_Criar();
    $stmt = $conn->prepare($sql);
    
    try{
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $stmt->execute();
            $perfis = $stmt->fetchAll(PDO::FETCH_ASSOC);

            header('Content-Type: application/json');

            if (count($perfis) > 0) {
                $perfisAgrupados = [];
                foreach ($perfis as $perfil) {
                    $id = $perfil['id'];
                    unset($perfil['id']);
                    $perfisAgrupados[$id][] = $perfil;
                }
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