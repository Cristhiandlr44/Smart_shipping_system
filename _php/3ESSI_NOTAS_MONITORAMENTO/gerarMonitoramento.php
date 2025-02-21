<?php

require_once("../3SSI_CRUD/conexao.php");
$costume = isset($_POST['where']) ? $_POST['where'] : null;

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Criar um novo monitoramento e obter o id_monitoramento gerado
        $sql = "INSERT INTO monitoramento (largada) VALUES (NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $id_monitoramento = $pdo->lastInsertId();

        // Processar cada nota selecionada
        $linhasSelecionadas = $_POST['linhasSelecionadas'];
        foreach ($linhasSelecionadas as $n_nota) {
            // Verificar qual campo de monitoramento está disponível
            $sqlCheck = "SELECT id_monitoramento, id_monitoramento2 FROM notas WHERE n_nota = :n_nota";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->bindParam(':n_nota', $n_nota);
            $stmtCheck->execute();
            $nota = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($nota) {
                if (empty($nota['id_monitoramento'])) {
                    $campo = 'id_monitoramento';
                } elseif (empty($nota['id_monitoramento2'])) {
                    $campo = 'id_monitoramento2';
                } else {
                    $campo = 'id_monitoramento3';
                }

                // Atualizar a tabela notas com o id_monitoramento gerado
                $sqlUpdate = "UPDATE notas SET $campo = :id_monitoramento, disponivel = 'N' WHERE n_nota = :n_nota";
                $stmtUpdate = $pdo->prepare($sqlUpdate);
                $stmtUpdate->bindParam(':id_monitoramento', $id_monitoramento);
                $stmtUpdate->bindParam(':n_nota', $n_nota);
                $stmtUpdate->execute();
            }
        }

        echo json_encode(["success" => true, "id_monitoramento" => $id_monitoramento]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}

$pdo = null;
?>
