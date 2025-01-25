<?php
// $servername = "localhost";
// $username = "root"; // Seu usuário
// $password = ""; // Sua senha
// $dbname = "seminariobd";

require_once("conexao.php");

// try {
    // $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Adicione uma mensagem no console se a conexão for bem-sucedida
    echo "Conexão com o banco de dados bem-sucedida!";

    if (!empty($_FILES['xmlSuincoFilesInput']['tmp_name'])) {
        foreach ($_FILES['xmlSuincoFilesInput']['tmp_name'] as $key => $tmp_name) {
            if (!empty($tmp_name)) {
                $xmlContent = file_get_contents($_FILES['xmlSuincoFilesInput']['tmp_name'][$key]);
                $xml = new SimpleXMLElement($xmlContent);
    
                // Extrair dados do XML
                $nNF = $xml->NFe->infNFe->ide->nNF;
                $xNome = $xml->NFe->infNFe->dest->xNome;
                $xLgr = (string) $xml->NFe->infNFe->dest->enderDest->xLgr;
                $nro = (string) $xml->NFe->infNFe->dest->enderDest->nro;
                $xBairro = (string) $xml->NFe->infNFe->dest->enderDest->xBairro;
                $xMun = (string) $xml->NFe->infNFe->dest->enderDest->xMun;
                $pBruto = (string) $xml->NFe->infNFe->transp->vol->pesoB;
                $pLiq = (string) $xml->NFe->infNFe->transp->vol->pesoL;
                $vnota = (string) $xml->NFe->infNFe->total->ICMSTot->vProd;
                $infCpl = (string) $xml->NFe->infNFe->infAdic->infCpl;

                // Use uma expressão regular para encontrar o número da carga
                if (preg_match('/SEQUENCIA ENTREGA:\s(\d+)/', $infCpl, $matches)) {
                    $nSequencia = $matches[1];
                } else {
                    $nSequencia = 'N/A'; // Defina um valor padrão se não encontrar o número da carga
                }

                $operacao = 'Suinco';
                
                // Inserir dados do cliente no banco de dados (assumindo que já foi criada uma tabela "notas")
                $sqlCliente = "INSERT INTO notas (fornecedor, n_nota, Cliente, Endereco, bairro, numero, municipio, peso_bruto, valor_nota, Data_lancamento)
                        VALUES (:operacao,:nota, :cliente, :endereco, :bairro, :numero, :cidade, :peso, :valor,:data_lancamento)";
                
                $stmtCliente = $conn->prepare($sqlCliente);
                $stmtCliente->bindParam(':operacao', $operacao);
                $stmtCliente->bindParam(':nota', $nNF);
                $stmtCliente->bindParam(':cliente', $xNome);
                $stmtCliente->bindParam(':endereco', $xLgr);
                $stmtCliente->bindParam(':bairro', $xBairro);
                $stmtCliente->bindParam(':numero', $nro);
                $stmtCliente->bindParam(':cidade', $xMun);
                $stmtCliente->bindParam(':peso', $pBruto);
                $stmtCliente->bindParam(':valor', $vnota);
                $stmtCliente->bindParam(':data_lancamento', $_POST['dataSuinco']);

                $stmtCliente->execute();

                // Inserir o número da carga no banco de dados
                $sqlcarga = "INSERT INTO suinco_notas(fk_notas_n_nota, Carga) VALUES (:nf, :carga )";
                $stmtcarga = $conn->prepare($sqlcarga);
                $stmtcarga->bindParam(':nf', $nNF);  
                $stmtcarga->bindParam(':carga', $_POST['cargaSuinco']);

                $stmtcarga->execute();

                if (
                    $xNome === 'CENCOSUD BRASIL COMERCIAL S A' ||
                    $xNome === 'COMERCIAL GALA LTDA' ||
                    $xNome === 'COMERCIAL GALA' ||
                    $xNome === 'MART MINAS DISTRIBUICAO LTDA' ||
                    $xNome === 'SUPERMERCADOS BH COMERCIO DE ALIMENTOS S' ||
                    $xNome === 'SUPERMERCADOS BH COMERCIO DE ALIMENTOS' ||
                    $xNome === 'SUPERMERCADOS BH COMERCIO DE ALIMENTOS S A' ||
                    $xNome === 'CEMA CENTRAL MINEIRA ATACADISTA LTDA' 
                ) {
                     $sqlRede ="INSERT INTO redes(fk_notas_n_nota, fornecedor) VALUES (:nota, :operacao)";
                     $stmtRede = $conn->prepare($sqlRede);
                     $stmtRede->bindParam(':nota', $nNF);
                     $stmtRede->bindParam(':operacao', $operacao);
                     $stmtRede->execute();
                }
                
                // Extrair informações dos produtos e inserir na tabela "produtos"
                foreach ($xml->NFe->infNFe->det as $det) {
                    $cProd = (string) $det->prod->cProd;
                    $xProd = (string) $det->prod->xProd;
                    $uTrib = (string) $det->prod->uTrib;
                    $qTrib = (string) $det->prod->qTrib;
                   
                    // Extrair o conteúdo de <infAdProd>
                    $infAdProd = (string) $det->infAdProd;

                    // Use uma expressão regular para encontrar o número após 'Qtde:'
                    if (preg_match('/Qtde_aux=(\d+)/', $infAdProd, $matches)) {
                        $numero = $matches[1];
                    } else {
                        // Defina um valor padrão se não encontrar o número
                        $numero = 'N/A';
                    }
                    
                    $sqlProdutos = "INSERT INTO produtos (cod, nf, descricao, unidade, quantidade, QuantAux)
                            VALUES (:cod, :nf, :descricao, :unidade, :quantidade, :QuantAux)";
                    $stmtProdutos = $conn->prepare($sqlProdutos);
                    $stmtProdutos->bindParam(':cod', $cProd);
                    $stmtProdutos->bindParam(':nf', $nNF);
                    $stmtProdutos->bindParam(':descricao', $xProd);
                    $stmtProdutos->bindParam(':unidade', $uTrib);
                    $stmtProdutos->bindParam(':quantidade', $qTrib);
                    $stmtProdutos->bindParam(':QuantAux', $numero);
                    $stmtProdutos->execute();
                }
            }
        }
        echo "Dados inseridos com sucesso no banco de dados!";
    } else {
        echo "Nenhum arquivo enviado.";
    }
    
// } catch(PDOException $e) {
//     echo "Erro: " . $e->getMessage();
// }

$conn = null;
?>
