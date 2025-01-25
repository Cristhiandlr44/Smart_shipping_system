<?php
require_once('../CRUD/relog.php'); // Conexão com o banco de dados

$response = ['success' => false];

try {
    $sql = "SELECT DISTINCT fk_placa FROM motorista_caminhoes";
    $result = $conn->query($sql);

    $placas = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $placas[] = $row['fk_placa'];
        }
    }

    $response['success'] = true;
    $response['placas'] = $placas;
} catch (Exception $e) {
    $response['message'] = "Erro ao buscar placas: " . $e->getMessage();
}

echo json_encode($response);
?>
