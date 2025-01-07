<?php

require_once("../3SSI_CRUD/conexao.php");

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Verificar se 'id' está presente no POST
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $id_monitoramento = $_POST['id'];

            // Preparar a consulta SQL para atualizar os dados
            $sqlUpdateNotas = "UPDATE notas SET id_monitoramento = NULL, disponivel = 'S' WHERE id_monitoramento = :id_monitoramento";
            $stmtUpdateNotas = $pdo->prepare($sqlUpdateNotas);
            $stmtUpdateNotas->bindParam(':id_monitoramento', $id_monitoramento, PDO::PARAM_INT);

            // Executar a consulta
            $stmtUpdateNotas->execute();

            // Retornar sucesso
            echo json_encode(["success" => true, "id_monitoramento" => $id_monitoramento]);
        } else {
            echo json_encode(["error" => "ID de monitoramento não fornecido."]);
        }
    }
} catch (PDOException $e) {
    // Retornar erro caso ocorra algum problema com a consulta
    echo json_encode(["error" => $e->getMessage()]);
}

// Fechar a conexão
$pdo = null;
?>
