<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Certifique-se de que a solicitação não está vazia
    if (empty($_POST)) {
        echo "Erro: Nenhum dado recebido.";
        exit;
    }

    // Recupere a data selecionada a partir do campo de entrada "dataSelecionada" ou use a data atual
    $dataSelecionada = isset($_POST['dataSelecionada']) ? $_POST['dataSelecionada'] : date('Y-m-d');
    $cargaPlena = isset($_POST['cargaPlena']) ? $_POST['cargaPlena'] : null;


   // Recupere os dados JSON e decodifique-os
$jsonData = json_decode($_POST['jsonData'], true);

// Adicione logs para verificar o conteúdo das colunas específicas
foreach ($jsonData as $row) {
   
}
    // Se os dados JSON são válidos, proceda com o processamento
    if (is_array($jsonData)) {
        // Conectar ao banco de dados
        require_once('conexao.php');
        // $host = 'localhost';
        // $user = 'root';
        // $password = ''; 
        // $database = 'seminariobd';
        
        // try {
        //     $pdo = new PDO("mysql:host=$host;dbname=$database", $user, $password);
        //     // Defina o PDO para lançar exceções em caso de erro
        //     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // } catch (PDOException $e) {
        //     echo "Erro na conexão com o banco de dados: " . $e->getMessage();
        //     exit;
        // }

        // Prepare as instruções SQL para inserção
        $sqlCliente = "INSERT INTO notas (fornecedor, n_nota, Cliente, Endereco, bairro, municipio, peso_bruto, valor_nota, Data_lancamento) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $sqlRedes = "INSERT INTO redes (fk_notas_n_nota, fornecedor) VALUES (?, ?)";
        $sqlPlena = "INSERT INTO plena_notas (fk_notas_n_nota, n_caixas, sequencia, Carga) VALUES (?, ?, ?,?)";
        $operacao = 'Plena';

        // Array para armazenar as notas a serem inseridas
        $notasArray = [];

        // Percorra os dados do Excel e adicione as notas ao array
        foreach ($jsonData as $row) {
           
            $numeroNota = isset($row['N° NF']) ? substr(trim($row['N° NF']), -6) : null;
            $descricaoCliente = isset($row['Razão Social']) ? trim($row['Razão Social']) : null;

            if (!empty($numeroNota)) {
                $notasArray[] = [
                    'numeroNota' => $numeroNota,
                    'descricaoCliente' => $descricaoCliente,
                    'endereco' => isset($row['Endereço']) ? $row['Endereço'] : '',
                    'bairro' => isset($row['Bairro']) ? $row['Bairro'] : '',
                    'municipio' => isset($row['Cidade']) ? $row['Cidade'] : '',
                    'peso_bruto' => isset($row['Peso Bruto']) ? $row['Peso Bruto'] : null,
                    'valor_nota' => isset($row['$ Faturado']) ? $row['$ Faturado'] : null,
                    'quant_caixas'=> isset($row['Qtd. Caixas']) ? $row['Qtd. Caixas'] : null,
                    'sequencia'=> isset($row['Ent.']) ? $row['Ent.'] : null,
                    'data_lancamento' => $dataSelecionada,
                    'carga' => $cargaPlena, // Adiciona o número da carga

                ];
            }

            // Verifique a condição para inserção em plena_notas
            if (
                $descricaoCliente === 'CENCOSUD BRASIL COMERCIAL S A' ||
                $descricaoCliente === 'COMERCIAL GALA LTDA' ||
                $descricaoCliente === 'COMERCIAL GALA' ||
                $descricaoCliente === 'MART MINAS DISTRIBUICAO LTDA' ||
                $descricaoCliente === 'SUPERMERCADOS BH COMERCIO DE ALIMENTOS S' ||
                $descricaoCliente === 'SUPERMERCADOS BH COMERCIO DE ALIMENTOS' ||
                $descricaoCliente === 'SUPERMERCADOS BH COMERCIO DE ALIMENTOS S A' ||
                $descricaoCliente === 'CEMA CENTRAL MINEIRA ATACADISTA LTDA'
            ) {
                $inserirPlena = true;
            }
        }

    // Inserir as notas no banco de dados
    foreach ($notasArray as $nota) {
        
        try {
            // Inserir em notas
            $stmt = $pdo->prepare($sqlCliente);
            $params = [
                $operacao,
                $nota['numeroNota'],
                $nota['descricaoCliente'],
                $nota['endereco'],
                $nota['bairro'],
                $nota['municipio'],
                $nota['peso_bruto'],
                $nota['valor_nota'],
                $nota['data_lancamento'],
            ];
            $stmt->execute($params);

            // Verifique a condição para inserção em redes
            if (
                $nota['descricaoCliente'] === 'CENCOSUD BRASIL COMERCIAL S A' ||
                $nota['descricaoCliente'] === 'COMERCIAL GALA LTDA' ||
                $nota['descricaoCliente'] === 'COMERCIAL GALA' ||
                $nota['descricaoCliente'] === 'MART MINAS DISTRIBUICAO LTDA' ||
                $nota['descricaoCliente'] === 'SUPERMERCADOS BH COMERCIO DE ALIMENTOS S' ||
                $nota['descricaoCliente'] === 'SUPERMERCADOS BH COMERCIO DE ALIMENTOS' ||
                $nota['descricaoCliente'] === 'SUPERMERCADOS BH COMERCIO DE ALIMENTOS S A' ||
                $nota['descricaoCliente'] === 'CEMA CENTRAL MINEIRA ATACADISTA LTDA'
            ) {
                // Inserir em redes
                $stmtRedes = $pdo->prepare($sqlRedes);
                $paramsRedes = [
                    $nota['numeroNota'],
                    $operacao
                ];
                $stmtRedes->execute($paramsRedes);
            }

            // Inserir em plena_notas
            $stmtPlena = $pdo->prepare($sqlPlena);
            $paramsPlena = [
                $nota['numeroNota'],
                $nota['quant_caixas'],
                $nota['sequencia'],
                $nota['carga'], // Adiciona o número da carga


            ];
            $stmtPlena->execute($paramsPlena);
        } catch (PDOException $e) {
            echo "Erro ao inserir dados no banco de dados: " . $e->getMessage() . "\n";
            exit;
        }
    }
        $pdo = null;

        // Responda com uma mensagem de confirmação
        echo "Dados do Excel foram importados com sucesso!";
    } else {
        echo "Erro: Dados JSON inválidos.";
    }
}
?>
