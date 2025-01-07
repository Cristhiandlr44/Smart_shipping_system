<?php
require_once('conexao.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Conectar ao banco de dados

    // Prepare a instrução SQL para inserção dos dados da Aurora
    $sqlAurora = "UPDATE  PRODUTOS SET data_producao = ?,  data_validade= ? WHERE nf = ? AND cod = ?";

    // Percorrer os dados do Excel da Aurora e inserir no banco de dados
    foreach ($data as $row) {
        $dataProducao = $row['Data produção'];
        $dataVencimento = $row['Data vencimento'];
        $nf = $row['Nota fiscal'];
        $cod = $row['Item'];

        if (!empty($dataProducao) && !empty($dataVencimento) && !empty($nf) && !empty($cod)) {
            // Preparar os parâmetros para a inserção
            $params = [
                $dataProducao,
                $dataVencimento,
                $nf,
                $cod
            ];

            $stmt = $pdo->prepare($sqlAurora);
            $stmt->execute($params);
        }
    }

    $pdo = null;

    // Responda com uma mensagem de confirmação
    echo "Dados da Aurora foram inseridos com sucesso no banco de dados! /n";
}
?>
