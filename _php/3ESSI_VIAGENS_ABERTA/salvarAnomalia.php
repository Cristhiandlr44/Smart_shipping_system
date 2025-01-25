<?php
require_once('../CRUD/relog.php'); // Incluindo a conexão com o banco de dados

// Verificando a conexão
if ($conn->connect_error) {
    echo json_encode(['erro' => 'Erro de conexão com o banco de dados']);
    exit;
}

// Recebendo os dados enviados via POST
$nf = $_POST['nf'];  // Nota Fiscal
$tipo = $_POST['tipo'];  // Tipo de anomalia
$motivo = $_POST['motivo'];  // Motivo da anomalia
$quantidade = $_POST['quantidade'];  // Quantidade
$unidade = $_POST['unidade'];  // Unidade
$peso = $_POST['peso'];  // Peso
$item = $_POST['itemCodigo'];

// Consulta SQL para inserir os dados na tabela 'anomalias'
$sql = "INSERT INTO anomalias (nf, tipo, motivo, quantidade, unidade, peso, cod_item) 
        VALUES (?, ?, ?, ?, ?, ?,?)";

// Preparando a consulta
$stmt = $conn->prepare($sql);

// Verificando se a preparação foi bem-sucedida
if ($stmt === false) {
    echo json_encode(['erro' => 'Erro na preparação da consulta']);
    exit;
}

// Ligando os parâmetros à consulta
$stmt->bind_param("sssdsds", $nf, $tipo, $motivo, $quantidade, $unidade, $peso,$item);

// Executando a consulta
if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Anomalia salva com sucesso"]);
} else {
    echo json_encode(['erro' => 'Erro ao salvar a anomalia']);
}

// Fechando a conexão
$stmt->close();
$conn->close();
?>
