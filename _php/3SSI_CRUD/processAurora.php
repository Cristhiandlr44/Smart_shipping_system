<?php
require_once('conexao.php');

echo "<p class='mt-3' style='color: green;'>Conexão com o banco de dados bem-sucedida!</p>";

if (!empty($_FILES['xmlFilesInput']['tmp_name'])) {
    foreach ($_FILES['xmlFilesInput']['tmp_name'] as $key => $tmp_name) {
        if (!empty($tmp_name)) {
            $xmlContent = file_get_contents($_FILES['xmlFilesInput']['tmp_name'][$key]);
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

            // Use uma expressão regular para encontrar o número da carga
            if (preg_match('/CARGA:\s(\d+)/', $infCpl, $matches)) {
                $nCarga = $matches[1];
            } else {
                $nCarga = 'N/A'; // Defina um valor padrão se não encontrar o número da carga
            }

            $operacao = 'Aurora';
            $tipo = 'V';

            // Iniciar a transação para garantir a consistência dos dados
            $pdo->beginTransaction();
            try {
                // Verifica se o CNPJ já existe na tabela "clientes"
                $sqlVerificaCNPJ = "SELECT COUNT(*) FROM clientes WHERE CNPJ = :CNPJ";
                $stmtVerificaCNPJ = $pdo->prepare($sqlVerificaCNPJ);
                $stmtVerificaCNPJ->bindParam(':CNPJ', $cnpj);
                $stmtVerificaCNPJ->execute();
                $cnpjExistente = $stmtVerificaCNPJ->fetchColumn();

                // Se o CNPJ não existir, insere os dados na tabela clientes
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
                $stmtCliente->bindParam(':data_lancamento', $_POST['dataAurora']);
                $stmtCliente->execute();

                // Inserir o número da carga no banco de dados
                $sqlcarga = "INSERT INTO aurora_notas(fk_notas_n_nota, n_carga) VALUES (:nota, :carga)";
                $stmtcarga = $pdo->prepare($sqlcarga);
                $stmtcarga->bindParam(':nota', $nNF);
                $stmtcarga->bindParam(':carga', $nCarga);
                $stmtcarga->execute();

                // Extrair informações dos produtos e inserir na tabela "produtos"
                foreach ($xml->NFe->infNFe->det as $det) {
                    $cProd = (string) $det->prod->cProd;
                    $xProd = (string) $det->prod->xProd;
                    $uTrib = (string) $det->prod->uTrib;
                    $qTrib = (string) $det->prod->qTrib;

                    // Extrair o conteúdo de <infAdProd>
                    $infAdProd = (string) $det->infAdProd;

                    // Use uma expressão regular para encontrar o número após 'Qtde:'
                    if (preg_match('/Qtde:\s*([\d.]+)\s*/', $infAdProd, $matches)) {
                        $numero = $matches[1];
                    } else {
                        // Defina um valor padrão se não encontrar o número
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

                // Commit da transação
                $pdo->commit();
            } catch (Exception $e) {
                // Em caso de erro, faz o rollback
                $pdo->rollBack();
                echo "<p style='color: red;'>Erro ao inserir dados: " . $e->getMessage() . "</p>";
            }
        }
    }
    print("<p style='color: green;'>Dados inseridos com sucesso no banco de dados!</p>");
} else {
    print("<p style='color: red;'>Nenhum arquivo enviado.</p>");
}

$pdo = null;
?>
