<?php
require_once("../3SSI_CRUD/conexao.php");

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $id = isset($_POST['id']) ? $_POST['id'] : '';

        $sql_cruzeiro = "SELECT cn.fk_notas_n_nota, cn.n_caixas, cn.peso_liquido, cn.sequencia, n.fornecedor
        FROM cruzeiro_notas cn
        JOIN notas n ON cn.fk_notas_n_nota = n.n_nota
        WHERE n.id_monitoramento = :id";

        $sql_plena = "SELECT pn.fk_notas_n_nota, pn.n_caixas, n.peso_bruto, pn.sequencia, n.fornecedor
        FROM plena_notas pn
        JOIN notas n ON pn.fk_notas_n_nota = n.n_nota
        WHERE n.id_monitoramento = :id";

        $stmt = $pdo->prepare($sql_cruzeiro);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $dados_cruzeiro = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $st = $pdo->prepare($sql_plena);
        $st->bindParam(':id', $id);
        $st->execute();
        $dados_plena = $st->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json'); // Defina o cabeçalho para JSON

        print(json_encode(['cruzeiro'=>$dados_cruzeiro,'plena'=>$dados_plena]));
    }
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]); // Se houver um erro, retorne um JSON com a mensagem de erro
}

$pdo = null;


?>