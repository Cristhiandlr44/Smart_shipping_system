<?php
require_once('../CRUD/relog.php'); // Ajuste o caminho conforme necessário

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Consulta para selecionar dados com id_monitoramento NULL
    $sqlCliente = "SELECT 
            sc.carga,
            n.n_nota,
            n.CNPJ,
            c.nome,
            c.rua,
            c.numero,
            c.bairro,
            c.cidade,
            n.peso_bruto
        FROM saudali_notas sc
        JOIN notas n ON sc.nf = n.n_nota
        JOIN clientes c ON n.CNPJ = c.CNPJ;";
            
    // Preparar a consulta SQL
    $stmt = $conn->prepare($sqlCliente);

    if ($stmt) {
        if ($stmt->execute()) {
            // Pega o resultado
            $result = $stmt->get_result();
            $produto = $result->fetch_all(MYSQLI_ASSOC);

            // Retorna o resultado como JSON
            echo json_encode($produto);
        } else {
            // Caso haja erro ao executar a consulta
            echo json_encode(['erro' => 'Erro ao executar a consulta.']);
        }
        $stmt->close();
    } else {
        // Caso haja erro ao preparar a consulta
        echo json_encode(['erro' => 'Erro ao preparar a consulta SQL.']);
    }

    // Fecha a conexão
    $conn->close();

} else {
    // Caso o método não seja POST
    echo json_encode(['erro' => 'Método inválido.']);
}
?>
