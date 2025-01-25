<?php

require_once("../3SSI_CRUD/conexao.php");
$costume = isset($_POST['where']) ? $_POST['where'] : null;
    try {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Insira a lógica para gerar o monitoramento e obter o id_monitoramento gerado
            $sql = "INSERT INTO monitoramento (largada) VALUES (NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            // Obtenha o id_monitoramento gerado
            $id_monitoramento = $pdo->lastInsertId();

            // Atualize a tabela notas com o id_monitoramento nas linhas selecionadas
            $linhasSelecionadas = $_POST['linhasSelecionadas'];
            $sqlUpdateNotas = "UPDATE notas SET id_monitoramento = :id_monitoramento, disponivel = 'N' WHERE n_nota IN (" . implode(',', $linhasSelecionadas) . ")";
            $stmtUpdateNotas = $pdo->prepare($sqlUpdateNotas);
            $stmtUpdateNotas->bindParam(':id_monitoramento', $id_monitoramento);
            $stmtUpdateNotas->execute();

            // Adicione outras operações, se necessário...
            echo json_encode(["success" => true, "id_monitoramento" => $id_monitoramento]);
        }
    } catch (PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }

    $pdo = null;
?>

