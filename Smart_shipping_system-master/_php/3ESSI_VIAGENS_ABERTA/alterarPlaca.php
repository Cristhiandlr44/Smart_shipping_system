<?php
require_once('../CRUD/relog.php'); // Conexão com o banco de dados

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $placa = $_POST['placa'];

    try {
        $sql = "UPDATE monitoramento SET placa_caminhao = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $placa, $id);

        if ($stmt->execute()) {
            $response['success'] = true;
        } else {
            $response['message'] = "Erro ao atualizar a placa.";
        }
        $stmt->close();
    } catch (Exception $e) {
        $response['message'] = "Erro ao atualizar a placa: " . $e->getMessage();
    }
} else {
    $response['message'] = "Método de requisição inválido.";
}

echo json_encode($response);
?>
