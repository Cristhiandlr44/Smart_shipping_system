<?php
require_once('../CRUD/relog.php'); // Conexão com o banco de dados

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nf_anomalia = $_POST['nf_anomalia'];
    $devolvida = $_POST['devolvida'];


    try {
        // Atualizar a coluna 'devolvida' e 'data_devolucao'
        $sql = "UPDATE anomalias SET devolvida = ?, data_devolucao = NULL WHERE nf = ?";
        $stmt = $conn->prepare($sql);

        // Aqui, a data é tratada como string (s), e o código da anomalia (nf) como inteiro (i)
        $stmt->bind_param("ss", $devolvida, $nf_anomalia);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Devolvida alterada com sucesso!";
        } else {
            $response['message'] = "Erro ao atualizar a devolvida: " . $stmt->error;
        }

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
