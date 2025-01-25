<?php
require_once('../CRUD/relog.php'); // Conexão com o banco de dados

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null; // ID da viagem

    if (!$id) {
        $response['message'] = "ID não fornecido.";
        echo json_encode($response);
        exit;
    }

    try {
        // Atualizar a viagem para deixar os campos 'finalizada' e 'data_finalizacao' como NULL
        $sql = "UPDATE monitoramento SET finalizada = NULL, data_finalizacao = NULL WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Erro ao preparar a consulta SQL.");
        }

        // Vincula o parâmetro à consulta
        $stmt->bind_param("i", $id);

        // Executa a consulta
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Viagem atualizada com sucesso.";
        } else {
            $response['message'] = "Erro ao atualizar a viagem.";
        }

        $stmt->close();
    } catch (Exception $e) {
        $response['message'] = "Erro ao processar a atualização: " . $e->getMessage();
    }
} else {
    $response['message'] = "Método de requisição inválido.";
}

echo json_encode($response);
?>
