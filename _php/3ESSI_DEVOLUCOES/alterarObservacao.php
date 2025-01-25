<?php
require_once('../CRUD/relog.php'); // Conexão com o banco de dados

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nf_anomalia = $_POST['nf_anomalia'];
    $observacao = $_POST['observacao'];
    try {
        // Atualizar a coluna 'devolvida' para 'S'
     // Atualizar a observação no banco de dados
            $sql = "UPDATE anomalias SET observacao = ? WHERE nf = ?";
            $stmt = $conn->prepare($sql);
            mysqli_stmt_bind_param($stmt, 'si', $observacao, $nf_anomalia);
            mysqli_stmt_execute($stmt);

            // Verificar se a atualização foi bem-sucedida
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                echo "Observação atualizada com sucesso!";
            } else {
                echo "Erro ao atualizar a observação.";
            }

            // Fechar a conexão
            $stmt->close();
            $conn->close();
    } catch (Exception $e) {
        $response['message'] = "Erro ao atualizar a placa: " . $e->getMessage();
     }
    } else {
        $response['message'] = "Método de requisição inválido.";
    }

echo json_encode($response);
?>
