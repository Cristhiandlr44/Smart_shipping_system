<?php
require_once("../3SSI_CRUD/conexao.php");

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Verifica se os parâmetros foram enviados e os valida
        $rota = isset($_GET['rota']) ? filter_var($_GET['rota'], FILTER_SANITIZE_STRING) : null;
        $cliente = isset($_GET['cliente']) ? filter_var($_GET['cliente'], FILTER_SANITIZE_STRING) : null;

        if (!$rota) {
            echo json_encode(['error' => 'Parâmetro "rota" é obrigatório.']);
            exit;
        }

        // Consulta SQL para selecionar as notas com base na rota e cliente
        $sql = "
            SELECT 
                n.n_nota, 
                c.nome AS Cliente, 
                n.bairro, 
                n.cidade, 
                n.peso_bruto, 
                n.reentrega, 
                n.fornecedor
            FROM 
                notas n
            JOIN 
                clientes c ON n.CNPJ = c.CNPJ
            WHERE 
                n.disponivel = 'S'
                AND c.tipo = 'rede'
                AND c.nome = :cliente
                AND n.cidade = 'Montes Claros'
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['cliente' => $cliente]);

        // Verifica se a consulta retornou algum resultado
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($result) {
            echo json_encode($result);
        } else {
            echo json_encode(['message' => 'Nenhum registro encontrado.']);
        }

    } else {
        echo json_encode(['error' => 'Método HTTP não permitido.']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    $pdo = null; // Fecha a conexão com o banco de dados
}
?>
