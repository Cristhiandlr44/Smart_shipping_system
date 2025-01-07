<?php
// Inicia a sessão para validar o usuário
session_start();

// Conecta ao banco de dados
require_once("../CRUD/relog.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebe os dados do formulário
    $id = $_POST['id'];
    $observacoes = $_POST['observacoes'];

    // Valida se os dados não estão vazios
    if (!empty($id) && !empty($observacoes)) {
        // Prepara a consulta para atualizar a observação
        $sql = "UPDATE monitoramento SET observacoes = ? WHERE id = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            // Liga os parâmetros à consulta preparada
            $stmt->bind_param("si", $observacoes, $id);
            
            // Executa a consulta
            if ($stmt->execute()) {
                // Se a atualização for bem-sucedida, redireciona para a página anterior
                header("Location: view_viagens_abertas.php?status=success");
            } else {
                // Se falhar, redireciona com erro
                header("Location: view_viagens_abertas.php?status=error");
            }
            
            // Fecha a declaração
            $stmt->close();
        } else {
            // Se a preparação falhar, redireciona com erro
            header("Location: viagens_abertas.php?status=error");
        }
    } else {
        // Se os dados não forem válidos
        header("Location: viagens_abertas.php?status=invalid");
    }
    
    // Fecha a conexão com o banco
    $conn->close();
} else {
    // Caso não seja uma requisição POST
    header("Location: viagens_abertas.php");
    exit();
}
?>
