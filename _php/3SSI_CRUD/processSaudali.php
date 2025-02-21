<?php
require_once("conexao.php");

echo "<p class='mt-3' style='color: green;'>Conexão com o banco de dados bem-sucedida!</p>";

if (!empty($_FILES['xmlSaudaliFilesInput']['tmp_name'])) {
    $listaCodigos = ["440200", "280200", "440203", "280204", "273405", "273402", "285703", "445700", "285704", "285700", "285710", "285711", "445702"];

    foreach ($_FILES['xmlSaudaliFilesInput']['tmp_name'] as $key => $tmp_name) {
        if (!empty($tmp_name)) {
            $xmlContent = file_get_contents($_FILES['xmlSaudaliFilesInput']['tmp_name'][$key]);
            $xml = new SimpleXMLElement($xmlContent);

            // Extrair dados do XML
            $nNF = (string) $xml->NFe->infNFe->ide->nNF;
            $cnpj = (string) $xml->NFe->infNFe->dest->CNPJ;
            $xNome = (string) $xml->NFe->infNFe->dest->xNome;
            $xLgr = (string) $xml->NFe->infNFe->dest->enderDest->xLgr;
            $nro = (string) $xml->NFe->infNFe->dest->enderDest->nro;
            $xBairro = (string) $xml->NFe->infNFe->dest->enderDest->xBairro;
            $xMun = ucwords(strtolower((string) $xml->NFe->infNFe->dest->enderDest->xMun));
            $pBruto = (string) $xml->NFe->infNFe->transp->vol->pesoB;
            $vnota = (string) $xml->NFe->infNFe->total->ICMSTot->vProd;
            $infCpl = (string) $xml->NFe->infNFe->infAdic->infCpl;

            if (preg_match('/SEQ\.\:\s*(\d+)/', $infCpl, $matches)) {
                $nSequencia = substr($matches[1], 3); // Remove os três primeiros caracteres
            } else {
                $nSequencia = 'N/A';
            }
            


            $operacao = 'Saudali';
            $tipo = 'V';

            // Iniciar a transação
            $pdo->beginTransaction();
            try {
                // Verificar se o CNPJ já existe
                $sqlVerificaCNPJ = "SELECT COUNT(*) FROM clientes WHERE CNPJ = :CNPJ";
                $stmtVerificaCNPJ = $pdo->prepare($sqlVerificaCNPJ);
                $stmtVerificaCNPJ->bindParam(':CNPJ', $cnpj);
                $stmtVerificaCNPJ->execute();
                $cnpjExistente = $stmtVerificaCNPJ->fetchColumn();

                // Inserir novo cliente se não existir
                if ($cnpjExistente == 0) {
                    $sqlCliente = "INSERT INTO clientes (CNPJ, nome, rua, bairro, numero, cidade, tipo) 
                                   VALUES (:CNPJ, :nome, :rua, :bairro, :numero, :cidade, :tipo)";
                    $stmtCliente = $pdo->prepare($sqlCliente);
                    $stmtCliente->bindParam(':CNPJ', $cnpj);
                    $stmtCliente->bindParam(':nome', $xNome);
                    $stmtCliente->bindParam(':rua', $xLgr);
                    $stmtCliente->bindParam(':bairro', $xBairro);
                    $stmtCliente->bindParam(':numero', $nro);
                    $stmtCliente->bindParam(':cidade', $xMun);
                    $stmtCliente->bindParam(':tipo', $tipo);
                    $stmtCliente->execute();
                }

                // Inserir nota fiscal
                $sqlNota = "INSERT INTO notas (CNPJ, fornecedor, n_nota, bairro, cidade, peso_bruto, valor_nota, Data_lancamento)
                            VALUES (:CNPJ, :operacao, :nota, :bairro, :cidade, :peso, :valor, :data_lancamento)";
                $stmtNota = $pdo->prepare($sqlNota);
                $stmtNota->bindParam(':CNPJ', $cnpj);
                $stmtNota->bindParam(':operacao', $operacao);
                $stmtNota->bindParam(':nota', $nNF);
                $stmtNota->bindParam(':bairro', $xBairro);
                $stmtNota->bindParam(':cidade', $xMun);
                $stmtNota->bindParam(':peso', $pBruto);
                $stmtNota->bindParam(':valor', $vnota);
                $stmtNota->bindParam(':data_lancamento', $_POST['dataSaudali']);
                $stmtNota->execute();

                // Inserir carga
                $sqlCarga = "INSERT INTO saudali_notas(nf, carga,seq) VALUES (:nf, :carga,:seq)";
                $stmtCarga = $pdo->prepare($sqlCarga);
                $stmtCarga->bindParam(':nf', $nNF);
                $stmtCarga->bindParam(':carga', $_POST['cargaSaudali']);
                $stmtCarga->bindParam(':seq', $nSequencia);
                $stmtCarga->execute();


                // Lista de códigos que devem receber a sequência antes do cProd
                $codigosEspeciais = [
                    "440200-0", "280200-0", "440203-0", "280204-0", "273405-0", "273402-0",
                    "285703-0", "445700-0", "285704-0", "285700-0", "285710-0", "285711-0", "445702-0"
                ];
                // Processar produtos
                foreach ($xml->NFe->infNFe->det as $det) {
                    $cProd = (string) $det->prod->cProd;
                    $xProd = (string) $det->prod->xProd;
                    $uTrib = (string) $det->prod->uTrib;
                    $qTrib = (string) $det->prod->qTrib;
                    $infAdProd = (string) $det->infAdProd;
                    $vProd = (string) $det->prod->vProd;
                    $vUnTrib = (string) $det->prod->vUnTrib;
                    $tipo = 'CX';

                     // Se o código do produto estiver na lista, modificar o código adicionando a sequência
                    if (in_array($cProd, $codigosEspeciais) && $nSequencia !== 'N/A') {
                        $cProd =  $cProd. "-" .$nSequencia ;
                    }

                    // Extrair número após "Qtde_aux="
                    if (preg_match('/Qtde_aux=(\d+)/', $infAdProd, $matches)) {
                        $numero = $matches[1];
                    } else {
                        $numero = 'N/A';
                    }

                    // Inserir produtos
                    $sqlProdutos = "INSERT INTO produtos (cod, nf, descricao, unidade, quantidade, QuantAux,UnidadeAuxiliar,valor_item, valor_uni)
                                    VALUES (:cod, :nf, :descricao, :unidade, :quantidade, :QuantAux, :UnidadeAuxiliar, :valorItem, :valorUni)";
                    $stmtProdutos = $pdo->prepare($sqlProdutos);
                    $stmtProdutos->bindParam(':cod', $cProd);
                    $stmtProdutos->bindParam(':nf', $nNF);
                    $stmtProdutos->bindParam(':descricao', $xProd);
                    $stmtProdutos->bindParam(':unidade', $uTrib);
                    $stmtProdutos->bindParam(':quantidade', $qTrib);
                    $stmtProdutos->bindParam(':QuantAux', $numero);
                    $stmtProdutos->bindParam(':UnidadeAuxiliar', $tipo);
                    $stmtProdutos->bindParam(':valorItem', $vProd);
                    $stmtProdutos->bindParam(':valorUni', $vUnTrib);

                    $stmtProdutos->execute();
                }

                // Commit da transação
                $pdo->commit();
                echo "<p style='color: green;'>Dados inseridos com sucesso no banco de dados!</p>";

            } catch (Exception $e) {
                // Rollback em caso de erro
                $pdo->rollBack();
                echo "<p style='color: red;'>Erro ao inserir dados: " . $e->getMessage() . "</p>";
            }
        }
    }
} else {
    echo "<p style='color: red;'>Nenhum arquivo enviado.</p>";
}

$pdo = null;
?>
