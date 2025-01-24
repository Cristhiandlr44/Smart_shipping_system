<?php
require_once('../CRUD/relog.php'); // Conexão com o banco de dados

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $finalizaqr = $_POST['dataEntrega'];

    $dataEntrega = $_POST['dataEntrega'];

    try {
        // Atualizar a viagem como finalizada e registrar a data de entrega
        $sql = "UPDATE monitoramento SET finalizada = 'S', data_finalizacao = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $dataEntrega, $id);

        if ($stmt->execute()) {
            $response['success'] = true;
        } else {
            $response['message'] = "Erro ao finalizar a viagem.";
        }
        $stmt->close();
    } catch (Exception $e) {
        $response['message'] = "Erro ao processar a finalização: " . $e->getMessage();
    }
} else {
    $response['message'] = "Método de requisição inválido.";
}

echo json_encode($response);
?>
