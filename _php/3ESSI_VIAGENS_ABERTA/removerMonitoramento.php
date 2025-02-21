<?php
// Conectar ao banco de dados
require_once('../CRUD/relog.php'); 

if (isset($_POST['notaFiscal'])) {
    $notaFiscal = $_POST['notaFiscal'];

    // Verificar se todas as colunas de monitoramento estão preenchidas
    $sqlCheck = "SELECT id_monitoramento, id_monitoramento2, id_monitoramento3 FROM notas WHERE n_nota = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("s", $notaFiscal);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();
    $nota = $result->fetch_assoc();

    if ($nota) {
        if (!empty($nota['id_monitoramento']) && !empty($nota['id_monitoramento2']) && !empty($nota['id_monitoramento3'])) {
            // Atualizar a tabela notas para tornar a NF indisponível
            $sqlUpdateNotas = "UPDATE notas SET disponivel = 'N' WHERE n_nota = ?";
            $stmtUpdateNotas = $conn->prepare($sqlUpdateNotas);
            $stmtUpdateNotas->bind_param("s", $notaFiscal);
            $stmtUpdateNotas->execute();

            // Buscar o último ID na tabela anomalias para essa NF
            $sqlGetAnomalia = "SELECT cod FROM anomalias WHERE nf = ? ORDER BY cod DESC LIMIT 1";
            $stmtGetAnomalia = $conn->prepare($sqlGetAnomalia);
            $stmtGetAnomalia->bind_param("s", $notaFiscal);
            $stmtGetAnomalia->execute();
            $resultAnomalia = $stmtGetAnomalia->get_result();
            $anomalia = $resultAnomalia->fetch_assoc();

            if ($anomalia) {
                // Atualizar a coluna tipo para "devolucaoTotal" na tabela anomalias
                $sqlUpdateAnomalia = "UPDATE anomalias SET tipo = 'devolucaoTotal' WHERE cod = ?";
                $stmtUpdateAnomalia = $conn->prepare($sqlUpdateAnomalia);
                $stmtUpdateAnomalia->bind_param("i", $anomalia['cod']);
                $stmtUpdateAnomalia->execute();
            }

            echo json_encode(['success' => false, 'message' => 'A NF já foi reenviada ao cliente 3 vezes e será encaminhada para devolução.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Reentrega registrada']);
        exit;
    }

    // Atualizar a nota para reentrega
    $sqlUpdateReentrega = "UPDATE notas SET reentrega = 'S', disponivel = 'S' WHERE n_nota = ?";
    $stmtUpdateReentrega = $conn->prepare($sqlUpdateReentrega);
    $stmtUpdateReentrega->bind_param("s", $notaFiscal);
    $stmtUpdateReentrega->execute();

    if ($stmtUpdateReentrega->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar a nota fiscal.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Nota fiscal não informada.']);
}
?>
