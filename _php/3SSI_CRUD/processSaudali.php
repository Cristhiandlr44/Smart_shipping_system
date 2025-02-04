<?php
require_once("conexao.php");

echo "<p class='mt-3' style='color: green;'>Conex�o com o banco de dados bem-sucedida!";

if (!empty($_FILES['xmlSaudaliFilesInput']['tmp_name'])) {
    foreach ($_FILES['xmlSaudaliFilesInput']['tmp_name'] as $key => $tmp_name) {
        if (!empty($tmp_name)) {
            $xmlContent = file_get_contents($_FILES['xmlSaudaliFilesInput']['tmp_name'][$key]);
            $xml = new SimpleXMLElement($xmlContent);

            // Extrair dados do XML
            $nNF = $xml->NFe->infNFe->ide->nNF;
            $cnpj = $xml->NFe->infNFe->dest->CNPJ;
            $xNome = $xml->NFe->infNFe->dest->xNome;
            $xLgr = (string) $xml->NFe->infNFe->dest->enderDest->xLgr;
            $nro = (string) $xml->NFe->infNFe->dest->enderDest->nro;
            $xBairro = (string) $xml->NFe->infNFe->dest->enderDest->xBairro;
            $xMun = ucwords(strtolower((string) $xml->NFe->infNFe->dest->enderDest->xMun));
            $pBruto = (string) $xml->NFe->infNFe->transp->vol->pesoB;
            $pLiq = (string) $xml->NFe->infNFe->transp->vol->pesoL;
            $vnota = (string) $xml->NFe->infNFe->total->ICMSTot->vProd;
            $infCpl = (string) $xml->NFe->infNFe->infAdic->infCpl;

            // Use uma express�o regular para encontrar o n�mero da carga
            if (preg_match('/SEQUENCIA ENTREGA:\s(\d+)/', $infCpl, $matches)) {
                $nSequencia = $matches[1];
            } else {
                $nSequencia = 'N/A'; // Defina um valor padr�o se n�o encontrar o n�mero da carga
            }

            $operacao = 'Saudali';
            
            $tipo = 'V';

            // Iniciar a transa��o para garantir a consist�ncia dos dados
            $pdo->beginTransaction();
            try {
                // Verifica se o CNPJ j� existe na tabela "clientes"
                $sqlVerificaCNPJ = "SELECT COUNT(*) FROM clientes WHERE CNPJ = :CNPJ";
                $stmtVerificaCNPJ = $pdo->prepare($sqlVerificaCNPJ);
                $stmtVerificaCNPJ->bindParam(':CNPJ', $cnpj);
                $stmtVerificaCNPJ->execute();
                $cnpjExistente = $stmtVerificaCNPJ->fetchColumn();

                // Se o CNPJ n�o existir, insere os dados na tabela clientes
                if ($cnpjExistente == 0) {
                    $sqlclientedados = "INSERT INTO clientes(CNPJ, nome, rua, bairro, numero, cidade, tipo) 
                                        VALUES (:CNPJ, :nome, :rua, :bairro, :numero, :cidade, :tipo)";
                    $stmtClientesDados = $pdo->prepare($sqlclientedados);
                    $stmtClientesDados->bindParam(':CNPJ', $cnpj);
                    $stmtClientesDados->bindParam(':nome', $xNome);
                    $stmtClientesDados->bindParam(':rua', $xLgr);
                    $stmtClientesDados->bindParam(':bairro', $xBairro);
                    $stmtClientesDados->bindParam(':numero', $nro);
                    $stmtClientesDados->bindParam(':cidade', $xMun);
                    $stmtClientesDados->bindParam(':tipo', $tipo);
                    $stmtClientesDados->execute();
                } 

                // Inserir dados do cliente na tabela "notas"
                $sqlCliente = "INSERT INTO notas (CNPJ, fornecedor, n_nota, bairro, cidade, peso_bruto, valor_nota, Data_lancamento)
                        VALUES (:CNPJ, :operacao, :nota,:bairro, :cidade, :peso, :valor, :data_lancamento)";
                
                $stmtCliente = $pdo->prepare($sqlCliente);
                $stmtCliente->bindParam(':CNPJ', $cnpj);
                $stmtCliente->bindParam(':operacao', $operacao);
                $stmtCliente->bindParam(':nota', $nNF);
                $stmtCliente->bindParam(':bairro', $xBairro);
                $stmtCliente->bindParam(':cidade', $xMun);
                $stmtCliente->bindParam(':peso', $pBruto);
                $stmtCliente->bindParam(':valor', $vnota);
                $stmtCliente->bindParam(':data_lancamento', $_POST['dataSaudali']);
                $stmtCliente->execute();

                // Inserir o n�mero da carga no banco de dados
                $sqlcarga = "INSERT INTO saudali_notas(nf, carga) VALUES (:nf, :carga )";
                $stmtcarga = $pdo->prepare($sqlcarga);
                $stmtcarga->bindParam(':nf', $nNF);  
                $stmtcarga->bindParam(':carga', $_POST['cargaSaudali']);

                $stmtcarga->execute();




                // Extrair informa��es dos produtos e inserir na tabela "produtos"
                    foreach ($xml->NFe->infNFe->det as $det) {
                        $cProd = (string) $det->prod->cProd;
                        $xProd = (string) $det->prod->xProd;
                        $uTrib = (string) $det->prod->uTrib;
                        $qTrib = (string) $det->prod->qTrib;
                        
                        // Extrair o conte�do de <infAdProd>
                        $infAdProd = (string) $det->infAdProd;

                        // Use uma express�o regular para encontrar o n�mero ap�s 'Qtde:'
                        if (preg_match('/Qtde_aux=(\d+)/', $infAdProd, $matches)) {
                            $numero = $matches[1];
                        } else {
                            // Defina um valor padr�o se n�o encontrar o n�mero
                            $numero = 'N/A';
                        }
                        
                        $sqlProdutos = "INSERT INTO produtos (cod, nf, descricao, unidade, quantidade, QuantAux)
                                VALUES (:cod, :nf, :descricao, :unidade, :quantidade, :QuantAux)";
                        $stmtProdutos = $pdo->prepare($sqlProdutos);
                        $stmtProdutos->bindParam(':cod', $cProd);
                        $stmtProdutos->bindParam(':nf', $nNF);
                        $stmtProdutos->bindParam(':descricao', $xProd);
                        $stmtProdutos->bindParam(':unidade', $uTrib);
                        $stmtProdutos->bindParam(':quantidade', $qTrib);
                        $stmtProdutos->bindParam(':QuantAux', $numero);
                        $stmtProdutos->execute();
                    }

                // Commit da transa��o
                $pdo->commit();
            } catch (Exception $e) {
                // Em caso de erro, faz o rollback
                $pdo->rollBack();
                echo "<p style='color: red;'>Erro ao inserir dados: " . $e->getMessage() . "</p>";
            }
        
        }
    }
    echo "<p style='color: green;'>Dados inseridos com sucesso no banco de dados!</p>";
} else {
    echo "<p style='color: red;'>Nenhum arquivo enviado.</p>";
}

$pdo = null;
?>
