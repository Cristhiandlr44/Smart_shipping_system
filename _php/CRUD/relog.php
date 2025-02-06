<?php
session_start();

// Verificação da sessão
if (isset($_SESSION['usuario']) && isset($_SESSION['senha'])) {
    $usuario = $_SESSION['usuario'];
    $senha = $_SESSION['senha'];
} else {
    // Redireciona para o login se a sessão não existir
    echo "<script>alert('Sessão não encontrada.'); window.location.href = 'login.php';</script>";
    exit();
}

include(__DIR__ . '/conexao.php');

// Verificar se a função mysqli_criar existe antes de chamar
if (!function_exists('mysqli_criar')) {
    die('Função mysqli_criar não foi definida!');
}

$conn = mysqli_criar();

// Consulta ao banco de dados
$sqlBusca = "SELECT * FROM usuarios WHERE usuario = '{$usuario}' AND senha = '{$senha}'";
$res = mysqli_query($conn, $sqlBusca);

if ($res) {
    $row = $res->fetch_object();
    $qtd = $res->num_rows;

    if ($qtd && $usuario[0] !== '#') {
        $_SESSION["usuario"] = $usuario;
        $_SESSION["nome"] = $row->nome;
        $_SESSION["tipo"] = $row->tipo;
        $_SESSION["email"] = $row->email;
        $_SESSION["data"] = $row->data;
        $_SESSION["id"] = $row->id;
        $_SESSION["senha"] = $row->senha;
    } else {
        echo "<script>alert('Dados de sessão atualizados, perfil desativado ou alterado!'); location.href='logout.php';</script>";
        exit();
    }
} else {
    // Erro na consulta SQL
    die('Erro na consulta ao banco de dados: ' . mysqli_error($conn));
}
?>
