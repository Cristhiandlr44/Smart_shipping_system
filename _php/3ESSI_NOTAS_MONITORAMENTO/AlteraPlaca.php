<?php
require_once("../3SSI_CRUD/conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Certifique-se de ajustar a consulta SQL conforme necessário
    $placa = $_POST['placa'];
    $cpf = $_POST['cpf'];
    $id = $_POST['id_monitoramento'];
    

    try {

        // Atualizar a tabela notas com o id_monitoramento nas linhas selecionadas
        $sql = "UPDATE monitoramento SET placa_caminhao = :placa, cpf_motorista = :cpf where Id = :id ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':placa', $placa);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':cpf', $cpf);

        $stmt->execute();

        // Aqui você pode realizar outras operações, se necessário

        echo json_encode(["success" => true]);
    } catch (PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Método não permitido"]);
}

$pdo = null;
?>
