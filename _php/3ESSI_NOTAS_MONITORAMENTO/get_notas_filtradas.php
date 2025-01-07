<?php
require_once("../3SSI_CRUD/conexao.php");

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Verifica se os parâmetros de 'rota' e 'fornecedores' foram enviados
        $rota = $_GET['rota'] ?? null;
        $fornecedores = isset($_GET['fornecedores']) ? $_GET['fornecedores'] : null;
        
        if (!$rota) {
            echo json_encode(['error' => 'Parâmetro "rota" é obrigatório.']);
            exit;
        }

        // Converte os fornecedores para um array
        $fornecedoresArray = explode(',', $fornecedores);

        // Verifica se ao menos um fornecedor foi passado
        if (empty($fornecedoresArray)) {
            echo json_encode(['error' => 'Nenhum fornecedor válido']);
            exit;
        }

        // Consulta SQL para selecionar as notas com base na rota e fornecedores
        // Utilizando parâmetros nomeados para fornecedores
        $placeholders = [];
        foreach ($fornecedoresArray as $index => $fornecedor) {
            $placeholders[":fornecedor_$index"] = $fornecedor;
        }
        
        // Monta a SQL com parâmetros nomeados para fornecedores
        $sql = "
            SELECT 
                n_nota, Cliente, bairro, municipio, peso_bruto, reentrega, fornecedor
            FROM 
                notas 
            WHERE 
                disponivel = 'S'
                AND rota = :rota
                AND fornecedor IN (" . implode(',', array_keys($placeholders)) . ")
        ";

        // Prepara e executa a consulta com os parâmetros
        $stmt = $pdo->prepare($sql);
        
        // Passa a rota e os fornecedores como parâmetros nomeados
        $params = ['rota' => $rota] + $placeholders;

        // Execute a consulta passando os parâmetros
        $stmt->execute($params);

        // Retorna os dados em formato JSON com os resultados da consulta
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Garantir que o retorno seja um array
        if (is_array($result) && count($result) > 0) {
            echo json_encode(['nota' => $result]);
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
