<?php
require_once("../3SSI_CRUD/conexao.php");

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Verifica se o parâmetro de rota foi enviado
        $rota = $_GET['rota'] ?? null;
        if (!$rota) {
            echo json_encode(['error' => 'Parâmetro "rota" é obrigatório.']);
            exit;
        }

        // Consulta SQL para selecionar as notas com base na rota
        $sql = "
            SELECT 
                n_nota, Cliente, bairro, municipio, peso_bruto, reentrega,fornecedor
            FROM 
                notas 
            WHERE 
                disponivel = 'S'
                AND rota = :rota
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['rota' => $rota]);

        // Retorna os dados em formato JSON
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result ?: ['message' => 'Nenhum registro encontrado.']);
    } else {
        echo json_encode(['error' => 'Método HTTP não permitido.']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    $pdo = null; // Fecha a conexão com o banco de dados
}
?>