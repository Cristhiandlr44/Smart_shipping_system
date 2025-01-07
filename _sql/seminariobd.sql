-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 12/11/2023 às 02:49
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `seminariobd`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `aurora_notas`
--

CREATE TABLE `aurora_notas` (
  `fk_notas_n_nota` varchar(14) NOT NULL,
  `n_carga` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `caminhoes`
--

CREATE TABLE `caminhoes` (
  `placa` char(8) NOT NULL,
  `modelo` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cruzeiro_notas`
--

CREATE TABLE `cruzeiro_notas` (
  `fk_notas_n_nota` varchar(14) NOT NULL,
  `peso_liquido` decimal(9,2) NOT NULL,
  `sequencia` int(4) NOT NULL,
  `Carga` int(8) DEFAULT NULL,
  `n_caixas` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `monitoramento`
--

CREATE TABLE `monitoramento` (
  `Id` smallint(6) NOT NULL,
  `controle` smallint(6) DEFAULT NULL,
  `largada` date DEFAULT NULL,
  `lead_time` tinyint(1) DEFAULT NULL,
  `status` bit(1) DEFAULT NULL,
  `data_entrega` date DEFAULT NULL,
  `placa_caminhao` char(8) DEFAULT NULL,
  `cpf_motorista` char(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `motorista`
--

CREATE TABLE `motorista` (
  `nome` varchar(20) NOT NULL,
  `CPF_motorista` char(11) NOT NULL,
  `num_habilitacao` int(11) NOT NULL,
  `venci_habilitacao` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `motorista_caminhoes`
--

CREATE TABLE `motorista_caminhoes` (
  `fk_placa` char(8) NOT NULL,
  `fk_cpf_motorista` char(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `notas`
--

CREATE TABLE `notas` (
  `Cliente` varchar(85) NOT NULL,
  `Endereco` varchar(50) NOT NULL,
  `numero` int(6) DEFAULT NULL,
  `bairro` varchar(30) NOT NULL,
  `municipio` varchar(45) NOT NULL,
  `n_nota` varchar(14) NOT NULL,
  `peso_bruto` decimal(9,2) NOT NULL,
  `Data_lancamento` date DEFAULT NULL,
  `Id_monitoramento` smallint(6) DEFAULT NULL,
  `fornecedor` varchar(20) NOT NULL,
  `rota` varchar(20) DEFAULT NULL,
  `valor_nota` double(9,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `plena_notas`
--

CREATE TABLE `plena_notas` (
  `fk_notas_n_nota` varchar(14) NOT NULL,
  `n_caixas` smallint(6) DEFAULT NULL,
  `sequencia` int(4) DEFAULT NULL,
  `Carga` int(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `cod` varchar(12) NOT NULL,
  `descricao` varchar(30) NOT NULL,
  `nf` int(12) NOT NULL,
  `quantidade` float NOT NULL,
  `unidade` varchar(4) NOT NULL,
  `QuantAux` int(4) DEFAULT NULL,
  `data_producao` date DEFAULT NULL,
  `data_validade` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `redes`
--

CREATE TABLE `redes` (
  `fk_notas_n_nota` varchar(14) NOT NULL,
  `fornecedor` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `suinco_notas`
--

CREATE TABLE `suinco_notas` (
  `fk_notas_n_nota` varchar(14) NOT NULL,
  `Carga` int(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `aurora_notas`
--
ALTER TABLE `aurora_notas`
  ADD PRIMARY KEY (`fk_notas_n_nota`);

--
-- Índices de tabela `caminhoes`
--
ALTER TABLE `caminhoes`
  ADD PRIMARY KEY (`placa`);

--
-- Índices de tabela `cruzeiro_notas`
--
ALTER TABLE `cruzeiro_notas`
  ADD PRIMARY KEY (`fk_notas_n_nota`);

--
-- Índices de tabela `monitoramento`
--
ALTER TABLE `monitoramento`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `placa_caminhao` (`placa_caminhao`),
  ADD KEY `cpf_motorista` (`cpf_motorista`);

--
-- Índices de tabela `motorista`
--
ALTER TABLE `motorista`
  ADD PRIMARY KEY (`CPF_motorista`);

--
-- Índices de tabela `motorista_caminhoes`
--
ALTER TABLE `motorista_caminhoes`
  ADD PRIMARY KEY (`fk_placa`,`fk_cpf_motorista`),
  ADD KEY `fk_cpf_motorista` (`fk_cpf_motorista`);

--
-- Índices de tabela `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`n_nota`),
  ADD KEY `Id_monitoramento` (`Id_monitoramento`);

--
-- Índices de tabela `plena_notas`
--
ALTER TABLE `plena_notas`
  ADD PRIMARY KEY (`fk_notas_n_nota`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`cod`,`nf`);

--
-- Índices de tabela `redes`
--

ALTER TABLE `redes`
  ADD PRIMARY KEY (`fk_notas_n_nota`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `monitoramento`
--
ALTER TABLE `monitoramento`
  MODIFY `Id` smallint(6) NOT NULL AUTO_INCREMENT;

--

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `aurora_notas`
--
ALTER TABLE `aurora_notas`
  ADD CONSTRAINT `aurora_notas_ibfk_1` FOREIGN KEY (`fk_notas_n_nota`) REFERENCES `notas` (`n_nota`);

--
-- Restrições para tabelas `cruzeiro_notas`
--
ALTER TABLE `cruzeiro_notas`
  ADD CONSTRAINT `cruzeiro_notas_ibfk_1` FOREIGN KEY (`fk_notas_n_nota`) REFERENCES `notas` (`n_nota`);

--
-- Restrições para tabelas `monitoramento`
--
ALTER TABLE `monitoramento`
  ADD CONSTRAINT `monitoramento_ibfk_1` FOREIGN KEY (`placa_caminhao`) REFERENCES `motorista_caminhoes` (`fk_placa`),
  ADD CONSTRAINT `monitoramento_ibfk_2` FOREIGN KEY (`cpf_motorista`) REFERENCES `motorista_caminhoes` (`fk_cpf_motorista`);

--
-- Restrições para tabelas `motorista_caminhoes`
--
ALTER TABLE `motorista_caminhoes`
  ADD CONSTRAINT `motorista_caminhoes_ibfk_1` FOREIGN KEY (`fk_placa`) REFERENCES `caminhoes` (`placa`),
  ADD CONSTRAINT `motorista_caminhoes_ibfk_2` FOREIGN KEY (`fk_cpf_motorista`) REFERENCES `motorista` (`CPF_motorista`);

--
-- Restrições para tabelas `notas`
--
ALTER TABLE `notas`
  ADD CONSTRAINT `notas_ibfk_1` FOREIGN KEY (`Id_monitoramento`) REFERENCES `monitoramento` (`Id`);

--
-- Restrições para tabelas `plena_notas`
--
ALTER TABLE `plena_notas`
  ADD CONSTRAINT `plena_notas_ibfk_1` FOREIGN KEY (`fk_notas_n_nota`) REFERENCES `notas` (`n_nota`);

--
-- Restrições para tabelas `redes`
--
DROP VIEW IF EXISTS Visao_Monitoramento;
CREATE VIEW Visao_Monitoramento AS
SELECT m.Id, m.controle, m.largada, m.lead_time, m.status, m.data_entrega, m.placa_caminhao, m.cpf_motorista,
    c.modelo AS modelo_caminhao, mt.nome AS nome_motorista
FROM monitoramento m, caminhoes c, motorista mt 
WHERE m.placa_caminhao = c.placa AND
m.cpf_motorista = mt.CPF_motorista;

DROP VIEW IF EXISTS Visao_SimplificadaNotas;
CREATE VIEW Visao_SimplificadaNotas AS
SELECT 
    n.n_nota,
    n.Cliente,
    n.Endereco,
    n.bairro,
    n.municipio,
    COUNT(p.cod) AS total_produtos
FROM 
    notas n
JOIN 
    produtos p ON n.n_nota = p.nf
GROUP BY 
    n.n_nota, n.Cliente, n.Endereco, n.bairro, n.municipio;

DROP VIEW IF EXISTS Visao_PesoPorMunicipFornecSimples;
CREATE VIEW Visao_PesoPorMunicipFornecSimples AS
SELECT n.municipio, SUM(n.peso_bruto) as 'Soma de Pesos'
FROM notas n
  LEFT JOIN redes r ON n.n_nota = r.fk_notas_n_nota
  LEFT JOIN aurora_notas a ON n.n_nota = a.fk_notas_n_nota
  LEFT JOIN cruzeiro_notas c ON n.n_nota = c.fk_notas_n_nota
  LEFT JOIN plena_notas p ON n.n_nota = p.fk_notas_n_nota
  LEFT JOIN suinco_notas s ON n.n_nota = s.fk_notas_n_nota
GROUP BY n.municipio
ORDER BY n.municipio;

DROP VIEW IF EXISTS Visao_MotoristasCaminhoes;
CREATE VIEW Visao_MotoristasCaminhoes AS
SELECT c.placa AS placa_caminhao, c.modelo AS modelo_caminhao, mt.nome AS nome_motorista, mt.cpf_motorista AS cpf_motorista, mt.num_habilitacao AS num_habilitacao, mt.venci_habilitacao AS venci_habilitacao
FROM motorista_caminhoes m, caminhoes c, motorista mt 
WHERE m.fk_placa = c.placa AND
m.fk_cpf_motorista = mt.CPF_motorista;

DROP VIEW IF EXISTS Visao_PesoPorMunicipFornecCompleto;
CREATE VIEW Visao_PesoPorMunicipFornecCompleto AS
SELECT 
    n.fornecedor, 
    n.municipio, 
    SUM(n.peso_bruto) as 'Soma de Pesos', 
    n.cliente,
    CASE WHEN r.fk_notas_n_nota IS NOT NULL THEN 'Valor para redes' ELSE NULL END AS ColunaRedes
FROM notas n
  LEFT JOIN redes r ON n.n_nota = r.fk_notas_n_nota
  LEFT JOIN aurora_notas a ON n.n_nota = a.fk_notas_n_nota
  LEFT JOIN cruzeiro_notas c ON n.n_nota = c.fk_notas_n_nota
  LEFT JOIN plena_notas p ON n.n_nota = p.fk_notas_n_nota
  LEFT JOIN suinco_notas s ON n.n_nota = s.fk_notas_n_nota
GROUP BY n.fornecedor, n.cliente, n.municipio  
ORDER BY `ColunaRedes` DESC;

DROP VIEW IF EXISTS Visao_DetalhamentoNotas;
CREATE VIEW Visao_GroupByMunicipFornecedores AS
SELECT n.fornecedor, n.cliente, n.n_nota as 'N° da Nota', n.endereco, n.numero, n.bairro, n.municipio, n.data_lancamento, n.id_monitoramento, n.valor_nota
FROM notas n
  LEFT JOIN redes r ON n.n_nota = r.fk_notas_n_nota
  LEFT JOIN aurora_notas a ON n.n_nota = a.fk_notas_n_nota
  LEFT JOIN cruzeiro_notas c ON n.n_nota = c.fk_notas_n_nota
  LEFT JOIN plena_notas p ON n.n_nota = p.fk_notas_n_nota
  LEFT JOIN suinco_notas s ON n.n_nota = s.fk_notas_n_nota
ORDER BY n.fornecedor;

CREATE INDEX IndexMonitoramentoPlaca ON monitoramento (placa_caminhao);
CREATE INDEX IndexProdutosNota ON produtos (nf);
   
DELIMITER //
DROP PROCEDURE IF EXISTS AlterarProdutoQuantidade//
CREATE PROCEDURE AlterarProdutoQuantidade(
    IN p_cod VARCHAR(12),
    IN p_nf INT,
    IN p_nova_quantidade FLOAT
)
BEGIN
    UPDATE produtos SET quantidade = p_nova_quantidade
    WHERE cod = p_cod AND nf = p_nf;
END;
//
DELIMITER ;

DELIMITER //
DROP PROCEDURE IF EXISTS UpMotoristas_Caminhoes//
CREATE PROCEDURE UpMotoristas_Caminhoes(
    IN p_placa CHAR(8),
    IN p_modelo CHAR(1),
    IN p_nome VARCHAR(20),
    IN p_CPF_motorista CHAR(11),
    IN p_num_habilitacao INT,
    IN p_venci_habilitacao DATE
)
BEGIN
  -- Verifica se a placa já existe na tabela
  IF NOT EXISTS (SELECT 1 FROM caminhoes WHERE placa = p_placa) THEN
      INSERT INTO caminhoes (placa, modelo) VALUES (p_placa, p_modelo);
      SELECT 'Caminhão inserido com sucesso.' AS Mensagem;
  ELSE
      SELECT 'Placa já cadastrada. Não foi possível inserir o caminhão.' AS Mensagem_Caminhao;
  END IF;

  -- Verifica se o motorista já existe na tabela
  IF NOT EXISTS (SELECT 1 FROM motorista WHERE cpf_motorista = p_CPF_motorista) THEN
      INSERT INTO motorista (nome, CPF_motorista, num_habilitacao, venci_habilitacao) VALUES (p_nome, p_CPF_motorista, p_num_habilitacao, p_venci_habilitacao);
      SELECT 'Motorista inserido com sucesso.' AS Mensagem;
  ELSE
      SELECT 'Motorista já cadastrado. Não foi possível inseri-lo.' AS Mensagem_Motorista;
  END IF;

  -- Atualiza a tabela motorista_caminhoes
  IF NOT EXISTS (SELECT 1 FROM motorista_caminhoes WHERE fk_cpf_motorista = p_CPF_motorista AND fk_placa = p_placa) THEN
      INSERT INTO motorista_caminhoes (fk_placa, fk_cpf_motorista) VALUES (p_placa, p_CPF_motorista);
      SELECT 'Tabela motorista_caminhoes atualizada com sucesso!' AS Info;
  ELSE
      SELECT 'Motorista já associado ao caminhão selecionado.' AS Info;
  END IF;
END //
DELIMITER ;

DELIMITER //
DROP PROCEDURE IF EXISTS VerificarMonitoramentosAtivos//
CREATE PROCEDURE VerificarMonitoramentosAtivos()
BEGIN
    SELECT *
    FROM visao_monitoramento
    WHERE largada >= CURDATE();
END //
DELIMITER ;

DELIMITER //
DROP PROCEDURE IF EXISTS AttrDataEntregaMonitor//
CREATE PROCEDURE AttrDataEntregaMonitor(
    IN p_IdMonitoramento INT,
    IN p_DataEntrega DATE
)
BEGIN
    UPDATE monitoramento
    SET data_entrega = p_DataEntrega
    WHERE Id = p_IdMonitoramento;
END //
DELIMITER ;

DELIMITER //
DROP TRIGGER IF EXISTS INSERE_REDES//
CREATE TRIGGER INSERE_REDES
AFTER INSERT ON notas FOR EACH ROW
BEGIN
    IF(NEW.cliente = 'CENCOSUD BRASIL COMERCIAL S A' OR NEW.cliente = 'COMERCIAL GALA LTDA' OR NEW.cliente = 'COMERCIAL GALA' OR NEW.cliente = 'MART MINAS DISTRIBUICAO LTDA' OR NEW.cliente ='SUPERMERCADOS BH COMERCIO DE ALIMENTOS S' OR NEW.cliente = 'SUPERMERCADOS BH COMERCIO DE ALIMENTOS' OR NEW.cliente = 'SUPERMERCADOS BH COMERCIO DE ALIMENTOS S A' OR NEW.cliente = 'CEMA CENTRAL MINEIRA ATACADISTA LTDA') THEN 
        INSERT INTO redes (fk_notas_n_nota, fornecedor) VALUES (NEW.n_nota, NEW.fornecedor);
    END IF;
END;
//
DELIMITER ;

CREATE TABLE `usuarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` char(1) NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `usuario`, `senha`, `tipo`, `data`) VALUES
(1, 'admin', 'rootprofile@email.com', 'root', '202cb962ac59075b964b07152d234b70', '1', '2023-10-20');

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

DELIMITER //
DROP TRIGGER IF EXISTS before_delete_root//
CREATE TRIGGER before_delete_root
BEFORE DELETE ON `usuarios`
FOR EACH ROW
BEGIN
    -- Impede a exclusão da tupla com o nome de usuário 'root'
    IF OLD.usuario = 'root' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Não é permitido excluir o usuário root.';
    END IF;
END//

DROP TRIGGER IF EXISTS before_update_root//
CREATE TRIGGER before_update_root
BEFORE UPDATE ON `usuarios`
FOR EACH ROW
BEGIN
    -- Impede a modificação da tupla com o nome de usuário 'root'
    IF old.usuario = 'root' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Não é permitido modificar o usuário root.';
    END IF;
END//
DELIMITER ;

DELIMITER //

CREATE TRIGGER before_insert_plena
BEFORE INSERT ON notas
FOR EACH ROW
BEGIN
    -- Verificar se a operação é "Plena" e o município está na lista correspondente
    IF NEW.fornecedor = 'Plena' AND NEW.municipio IN ('ARACUAI', 'FRANCISCO SA', 'BOCAIUVA', 'CAPELINHA', 'CARBONITA', 'ITAMARANDIBA', 'OLHOS-D''AGUA', 'TURMALINA', 'AUGUSTO DE LIMA', 'BUENOPOLIS', 'CORDISBURGO', 'CORINTO', 'CURVELO', 'PARAOPEBA', 'POMPEU', 'SANTO HIPOLITO', 'BRASILIA DE MINAS', 'CORACAO DE JESUS', 'LUISLANDIA', 'SAO FRANCISCO', 'COUTO DE MAGALHAES DE MINAS', 'DIAMANTINA', 'GOUVEIA', 'JAIBA', 'MONTALVANIA', 'ESPINOSA', 'JANAUBA', 'MATO VERDE', 'MONTE AZUL', 'PORTEIRINHA', 'BONITO DE MINAS', 'ITACARAMBI', 'JANUARIA', 'LONTRA', 'MIRABELA', 'BELO HORIZONTE', 'MONTES CLAROS', 'BURITIZEIRO', 'IBIAI', 'JEQUITAI', 'PIRAPORA', 'RIO PARDO DE MINAS', 'SALINAS', 'SAO JOAO DO PARAISO', 'TAIOBEIRAS', 'VARZEA DA PALMA') THEN
        -- Atualizar o campo "rota" na tabela "notas" com a rota correspondente
        SET NEW.rota =
            CASE
                WHEN NEW.municipio IN ('ARAÇUAI', 'FRANCISCO SA') THEN 'ARAÇUAI'
                WHEN NEW.municipio = 'BOCAIUVA' THEN 'BOCAIUVA'
                WHEN NEW.municipio IN ('CAPELINHA', 'CARBONITA', 'ITAMARANDIBA', 'OLHOS-D''AGUA', 'TURMALINA') THEN 'CAPELINHA'
                WHEN NEW.municipio IN ('AUGUSTO DE LIMA', 'BUENOPOLIS', 'CORDISBURGO', 'CORINTO', 'CURVELO', 'PARAOPEBA', 'POMPEU', 'SANTO HIPOLITO') THEN 'CARRO PLENA'
                WHEN NEW.municipio IN ('BRASILIA DE MINAS', 'CORACAO DE JESUS', 'LUISLANDIA', 'SAO FRANCISCO') THEN 'CORAÇÃO JESUS'
                WHEN NEW.municipio = 'COUTO DE MAGALHAES DE MINAS' THEN 'DIAMANTINA'
                WHEN NEW.municipio = 'GOUVEIA' THEN 'DIAMANTINA'
		WHEN NEW.municipio = 'DIAMANTINA' THEN 'DIAMANTINA'
                WHEN NEW.municipio IN ('JAIBA', 'MONTALVANIA') THEN 'JAIBA'
                WHEN NEW.municipio IN ('ESPINOSA', 'JANAUBA', 'MATO VERDE', 'MONTE AZUL', 'PORTEIRINHA') THEN 'JANAUBA'
                WHEN NEW.municipio IN ('BONITO DE MINAS', 'ITACARAMBI', 'JANUARIA', 'LONTRA', 'MIRABELA') THEN 'JANUARIA'
                WHEN NEW.municipio IN ('BELO HORIZONTE', 'MONTES CLAROS') THEN 
 CASE NEW.bairro
        WHEN 'ZONA RURAL' THEN 'BOCAIUVA'
        WHEN 'PLANALTO RURAL' THEN 'BOCAIUVA'
        WHEN 'BARCELONA PARQUE' THEN 'CENTRO-1'
        WHEN 'BELA VISTA' THEN 'CENTRO-1'
        WHEN 'CIDADE INDUSTRIAL' THEN 'CENTRO-1'
        WHEN 'CONJ.HABITAC.VITORIA II' THEN 'CENTRO-1'
        WHEN 'CONJ HABITAC VITORIA II' THEN 'CENTRO-1'
        WHEN 'JARDIM PANORAMA' THEN 'CENTRO-1'
        WHEN 'CONJ PANORAMA II' THEN 'CENTRO-1'
        WHEN 'CONJUNTO PANORAMA II' THEN 'CENTRO-1'
        WHEN 'DISTRITO INDUSTRIAL' THEN 'CENTRO-1'
        WHEN 'EDGAR PEREIRA' THEN 'CENTRO-1'
        WHEN 'ELDORADO' THEN 'CENTRO-1'
        WHEN 'JARDIM BRASIL' THEN 'CENTRO-1'
        WHEN 'DISTRITO-NOVA ESPERANCA' THEN 'CENTRO-1'
        WHEN 'NOVA ESPERANCA' THEN 'CENTRO-1'
        WHEN 'NOVA MORADA' THEN 'CENTRO-1'
        WHEN 'RESIDENCIAL VITORIA' THEN 'CENTRO-1'
        WHEN 'RIO CEDRO' THEN 'CENTRO-1'
        WHEN 'SANTA EUGENIA' THEN 'CENTRO-1'
        WHEN 'SANTA ROSA DE LIMA' THEN 'CENTRO-1'
        WHEN 'SANTOS REIS' THEN 'CENTRO-1'
        WHEN 'VILA ANTONIO NARCISO' THEN 'CENTRO-1'
        WHEN 'VILA ATLANTIDA' THEN 'CENTRO-1'
        WHEN 'VILA AUREA' THEN 'CENTRO-1'
        WHEN 'VILA BRASILIA' THEN 'CENTRO-1'
        WHEN 'VILA CASTELO BRANCO' THEN 'CENTRO-1'
        WHEN 'VILA PRODACON' THEN 'CENTRO-1'
        WHEN 'VILA SANTA EUGENIA' THEN 'CENTRO-1'
        WHEN 'VILA TONCHEF' THEN 'CENTRO-1'
        WHEN 'ALICE MAIA' THEN 'CENTRO-2'
        WHEN 'ALTO SAO JOAO' THEN 'CENTRO-2'
        WHEN 'CENTRO' THEN 'CENTRO-2'
        WHEN 'FUNCIONARIOS' THEN 'CENTRO-2'
        WHEN 'JARDIM SAO LUIZ' THEN 'CENTRO-2'
        WHEN 'JOAO GORDO' THEN 'CENTRO-2'
        WHEN 'LOURDES' THEN 'CENTRO-2'
        WHEN 'MELO' THEN 'CENTRO-2'
        WHEN 'ROXO VERDE' THEN 'CENTRO-2'
        WHEN 'SAO JOSE' THEN 'CENTRO-2'
        WHEN 'TODOS OS SANTOS' THEN 'CENTRO-2'
        WHEN 'VILA MAURICEIA' THEN  'CENTRO-2'
        WHEN 'VILA OLIVEIRA' THEN  'CENTRO-2'
        WHEN 'VILA REGINA' THEN  'CENTRO-2'
        WHEN 'VILA EXPOSICAO' THEN  'CENTRO-2'
        WHEN 'VILA SANTA MAIRA' THEN  'CENTRO-2'
        WHEN 'ACACIAS' THEN  'DELFINO-1'
        WHEN 'ALCIDES RABELO' THEN  'DELFINO-1'
        WHEN 'ALTO FLORESTA' THEN  'DELFINO-1'
        WHEN 'BELVEDERE' THEN  'DELFINO-1'
        WHEN 'CARMELO' THEN  'DELFINO-1'
        WHEN 'CONJUNTO JK' THEN 'DELFINO-1'
        WHEN 'Conjunto Residencial Jk' THEN 'DELFINO-1'
        WHEN 'ESPLANADA' THEN 'DELFINO-1'
        WHEN 'GUARUJA' THEN 'DELFINO-1'
        WHEN 'INDEPENDENCIA' THEN 'DELFINO-1'
        WHEN 'INTERLAGOS' THEN 'DELFINO-1'
        WHEN 'JARAGUA I' THEN 'DELFINO-1'
        WHEN 'JARAGUA' THEN 'DELFINO-1'
        WHEN 'JARDIM ALEGRE' THEN 'DELFINO-1'
        WHEN 'JARDIM PRIMAVERA' THEN 'DELFINO-1'
        WHEN 'LOTEAMENTO NOVO JARAGUA' THEN 'DELFINO-1'
        WHEN 'LOTEAMENTO RECANTO DAS AGUAS' THEN 'DELFINO-1'
        WHEN 'MONTE CARMELO' THEN 'DELFINO-1'
        WHEN 'MONTE SIAO' THEN 'DELFINO-1'
        WHEN 'MONTE SIAO IV' THEN 'DELFINO-1'
        WHEN 'NOVO JARAGUA' THEN 'DELFINO-1'
        WHEN 'PLANALTO' THEN 'DELFINO-1'
        WHEN 'PORTAL DOS IPES' THEN 'DELFINO-1'
        WHEN 'RENASCENCA' THEN 'DELFINO-1'
        WHEN 'RESIDENCIAL SUL IPES' THEN 'DELFINO-1'
        WHEN 'RESIDENCIAL MONTE SIAO' THEN 'DELFINO-1'
        WHEN 'RESIDIDENCIAL MINAS GERAIS' THEN 'DELFINO-1'
        WHEN 'SANTA LUCIA' THEN 'DELFINO-1'
        WHEN 'SANTOS DUMONT' THEN 'DELFINO-1'
        WHEN 'SAO BENTO' THEN 'DELFINO-1'
        WHEN 'UNIVERSITARIO' THEN 'DELFINO-1'
        WHEN 'VENEZA PARQUE' THEN 'DELFINO-1'
        WHEN 'VILAGE DO LAGO' THEN 'DELFINO-1'
        WHEN 'VILLAGE DO LAGO I' THEN 'DELFINO-1'
        WHEN 'ALTO DA BOA VISTA' THEN 'DELFINO-2'
        WHEN 'ANTONIO PIMENTA' THEN 'DELFINO-2'
        WHEN 'CAMILO PRATES' THEN 'DELFINO-2'
        WHEN 'CINTRA' THEN 'DELFINO-2'
        WHEN 'CINTRA / DELFINO MAGALHAE' THEN 'DELFINO-2'
        WHEN 'DELFINO MAGALHAES' THEN 'DELFINO-2'
        WHEN 'NOVO DELFINO' THEN 'DELFINO-2'
        WHEN 'FRANCISCO PERES' THEN 'DELFINO-2'
        WHEN 'FRANCISCO PERES I' THEN 'DELFINO-2'
        WHEN 'JARDIM ALVORADA' THEN 'DELFINO-2'
        WHEN 'JARDIM OLIMPICO' THEN 'DELFINO-2'
        WHEN 'JARDIM PALMEIRAS' THEN 'DELFINO-2'
        WHEN 'JOSE CARLOS VALE DE LIMA' THEN 'DELFINO-2'
        WHEN 'MARIA CANDIDA' THEN 'DELFINO-2'
        WHEN 'MONTE ALEGRE' THEN 'DELFINO-2'
        WHEN 'MORRINHOS' THEN 'DELFINO-2'
        WHEN 'NOSSA SENHORA DE  FATIMA' THEN 'DELFINO-2'
        WHEN 'SANTA RAFAELA' THEN 'DELFINO-2'
        WHEN 'SANTA RITA' THEN 'DELFINO-2'
        WHEN 'SANTA RITA I' THEN 'DELFINO-2'
        WHEN 'SANTA RITA II' THEN 'DELFINO-2'
        WHEN 'SANTO ANTONIO' THEN 'DELFINO-2'
        WHEN 'SANTO INACIO' THEN 'DELFINO-2'
        WHEN 'SANTO IN' THEN 'DELFINO-2'
        WHEN 'SION' THEN 'DELFINO-2'
        WHEN 'VERA CRUZ' THEN 'DELFINO-2'
	WHEN 'VILA ANALIA' THEN 'DELFINO-2'
                    WHEN 'VILA IPIRANGA' THEN 'DELFINO-2'
                    WHEN 'VILA MARIA CANDIDA' THEN 'DELFINO-2'
                    WHEN 'VILA NAZARE' THEN'DELFINO-2'
                    WHEN 'VILA NAZARETH' THEN 'DELFINO-2'
                    WHEN 'VILA SION' THEN 'DELFINO-2'
                    WHEN 'VILA SUMARE' THEN'DELFINO-2'
                    WHEN 'VILA TELMA' THEN 'DELFINO-2'
                    WHEN 'AUGUSTA MOTA' THEN 'MAJOR-1'
                    WHEN 'Augusto Mota' THEN 'MAJOR-1'
                    WHEN 'CANDIDA CAMARA' THEN 'MAJOR-1'
                    WHEN 'CANELA' THEN 'MAJOR-1'
                    WHEN 'CANELAS' THEN 'MAJOR-1'
                    WHEN 'CIDADE NOVA' THEN 'MAJOR-1'
                    WHEN 'DOS CANELAS' THEN 'MAJOR-1'
                    WHEN 'FUNCIONARIOS' THEN 'MAJOR-1'
                    WHEN 'IBITURUNA' THEN 'MAJOR-1'
                    WHEN 'JARDIM LIBERDADE' THEN 'MAJOR-1'
                    WHEN 'JARDIM SAO GERALDO' THEN 'MAJOR-1'
                    WHEN 'JARDIM SAO GERALDO II' THEN 'MAJOR-1'
                    WHEN 'KM 14' THEN 'MAJOR-1'
                    WHEN 'MAJOR PRATES' THEN 'MAJOR-1'
                    WHEN 'MORADA DA SERRA' THEN 'MAJOR-1'
                    WHEN 'MORADA DO PARQUE' THEN 'MAJOR-1'
                    WHEN 'MORADA DO SOL' THEN 'MAJOR-1'
                    WHEN 'SAGRADA FAMILIA' THEN 'MAJOR-1'
                    WHEN 'SANTO EXPEDITO' THEN 'MAJOR-1'
                    WHEN 'SAO GERALDO' THEN 'MAJOR-1'
                    WHEN 'SAO GERALDO' THEN 'MAJOR-1'
                    WHEN 'SAO GERALDO II' THEN 'MAJOR-1'
                    WHEN 'VARGEM GRANDE' THEN 'MAJOR-1'
                    WHEN 'VILA GUILHERMINA' THEN 'MAJOR-1'
                    WHEN 'VILA LUISA' THEN 'MAJOR-1'
                    WHEN 'VILA LUIZA' THEN 'MAJOR-1'
                    WHEN 'ALTEROSAS' THEN 'MAJOR-2'
                    WHEN 'CONJUNTO CHIQUINHO GUIMARAES' THEN 'MAJOR-2'
                    WHEN 'Conjunto Ciro dos Anjos' THEN 'MAJOR-2'
                    WHEN 'CONJUNTO CRISTO REI' THEN 'MAJOR-2'
                    WHEN 'CONJUNTO JOAQUIM COSTA' THEN 'MAJOR-2'
                    WHEN 'DOS MANGUES' THEN 'MAJOR-2'
                    WHEN 'DR. JOAO ALVES' THEN 'MAJOR-2'
                    WHEN 'JOAO ALVES' THEN 'MAJOR-2'
                    WHEN 'JOSE CORREA  MACHADO' THEN 'MAJOR-2'
                    WHEN 'MARACANA' THEN 'MAJOR-2'
                    WHEN 'RESIDENCIAL SUL IPES' THEN 'MAJOR-2'
                    WHEN 'SAO JUDAS' THEN 'MAJOR-2'
                    WHEN 'SAO JUDAS TADEU' THEN 'MAJOR-2'
                    WHEN 'Sao Judas Tadeu I' THEN 'MAJOR-2'
                    WHEN 'VILA CAMPOS' THEN 'MAJOR-2'
                    WHEN 'VILA GREICE' THEN 'MAJOR-2'
                    END
                WHEN NEW.municipio IN ('BURITIZEIRO', 'IBIAI', 'JEQUITAI', 'PIRAPORA') THEN 'PIRAPORA'
                WHEN NEW.municipio = 'VARZEA DA PALMA' THEN 'VARZEA DA PALMA'
                WHEN NEW.municipio IN ('RIO PARDO DE MINAS', 'SALINAS', 'SAO JOAO DO PARAISO', 'TAIOBEIRAS') THEN 'TAIOBEIRAS'
            END;
    END IF;
END //

DELIMITER ;


DELIMITER //

CREATE TRIGGER before_insert_aurora
BEFORE INSERT ON notas
FOR EACH ROW
BEGIN
    -- Verificar se a operação é "Plena" e o município está na lista correspondente
    IF NEW.fornecedor = 'Aurora' AND NEW.municipio IN ('ALMENARA', 'ARACUAI', 'SALINAS', 'FRANCISCO SA', 'BOCAIUVA', 'ENGENHEIRO NAVARRO', 'GUARACIAMA', 'CAPITAO ENEAS', 'CAPITAO ENEAS', 'JANAUBA', 'NOVA PORTEIRINHA', 'PORTEIRINHA', 'BRASILIA DE MINAS', 'JANUARIA', 'JAPONVAR', 'LONTRA', 'MIRABELA', 'PEDRAS DE MARIA DA CRUZ', 'MONTES CLAROS', 'BURITIZEIRO', 'PIRAPORA', 'SAO JOAO DA PONTE', 'TAIOBEIRAS', 'IBIAI', 'JEQUITAI', 'LASSANCE', 'PONTO CHIQUE', 'VARZEA DA PALMA') THEN
        -- Atualizar o campo "rota" na tabela "notas" com a rota correspondente
        SET NEW.rota =
            CASE
                WHEN NEW.municipio IN ('ALMENARA') THEN 'ALMENARA'
                WHEN NEW.municipio IN ('BOCAIUVA', 'ENGENHEIRO NAVARRO', 'GUARACIAMA') THEN 'BOCAIUVA'
                WHEN NEW.municipio IN ('ARACUAI', 'SALINAS', 'FRANCISCO SA') THEN 'ARACUAI'
                WHEN NEW.municipio IN ('CAPITAO ENEAS','JANAUBA', 'NOVA PORTEIRINHA', 'PORTEIRINHA') THEN 'JANAUBA'
                WHEN NEW.municipio IN ('BRASILIA DE MINAS', 'JANUARIA', 'JAPONVAR', 'LONTRA', 'MIRABELA', 'PEDRAS DE MARIA DA CRUZ') THEN 'JANUARIA'
           	WHEN NEW.municipio IN ('BURITIZEIRO', 'PIRAPORA') THEN 'PIRAPORA'
                WHEN NEW.municipio = 'SAO JOAO DA PONTE' THEN 'SAO JOAO DA PONTE'
                WHEN NEW.municipio = 'TAIOBEIRAS' THEN 'TAIOBEIRAS'
                WHEN NEW.municipio IN ('IBIAI', 'JEQUITAI', 'LASSANCE', 'PONTO CHIQUE', 'VARZEA DA PALMA') THEN 'VARZEA DA PALMA'
                WHEN NEW.municipio = 'MONTES CLAROS' THEN 
		 CASE NEW.bairro
        WHEN 'ZONA RURAL' THEN 'BOCAIUVA'
        WHEN 'PLANALTO RURAL' THEN 'BOCAIUVA'
        WHEN 'BARCELONA PARQUE' THEN 'CENTRO-1'
        WHEN 'BELA VISTA' THEN 'CENTRO-1'
        WHEN 'CIDADE INDUSTRIAL' THEN 'CENTRO-1'
        WHEN 'CONJ.HABITAC.VITORIA II' THEN 'CENTRO-1'
        WHEN 'CONJ HABITAC VITORIA II' THEN 'CENTRO-1'
        WHEN 'JARDIM PANORAMA' THEN 'CENTRO-1'
        WHEN 'CONJ PANORAMA II' THEN 'CENTRO-1'
        WHEN 'CONJUNTO PANORAMA II' THEN 'CENTRO-1'
        WHEN 'DISTRITO INDUSTRIAL' THEN 'CENTRO-1'
        WHEN 'EDGAR PEREIRA' THEN 'CENTRO-1'
        WHEN 'ELDORADO' THEN 'CENTRO-1'
        WHEN 'JARDIM BRASIL' THEN 'CENTRO-1'
        WHEN 'DISTRITO-NOVA ESPERANCA' THEN 'CENTRO-1'
        WHEN 'NOVA ESPERANCA' THEN 'CENTRO-1'
        WHEN 'NOVA MORADA' THEN 'CENTRO-1'
        WHEN 'RESIDENCIAL VITORIA' THEN 'CENTRO-1'
        WHEN 'RIO CEDRO' THEN 'CENTRO-1'
        WHEN 'SANTA EUGENIA' THEN 'CENTRO-1'
        WHEN 'SANTA ROSA DE LIMA' THEN 'CENTRO-1'
        WHEN 'SANTOS REIS' THEN 'CENTRO-1'
        WHEN 'VILA ANTONIO NARCISO' THEN 'CENTRO-1'
        WHEN 'VILA ATLANTIDA' THEN 'CENTRO-1'
        WHEN 'VILA AUREA' THEN 'CENTRO-1'
        WHEN 'VILA BRASILIA' THEN 'CENTRO-1'
        WHEN 'VILA CASTELO BRANCO' THEN 'CENTRO-1'
        WHEN 'VILA PRODACON' THEN 'CENTRO-1'
        WHEN 'VILA SANTA EUGENIA' THEN 'CENTRO-1'
        WHEN 'VILA TONCHEF' THEN 'CENTRO-1'
        WHEN 'ALICE MAIA' THEN 'CENTRO-2'
        WHEN 'ALTO SAO JOAO' THEN 'CENTRO-2'
        WHEN 'CENTRO' THEN 'CENTRO-2'
        WHEN 'FUNCIONARIOS' THEN 'CENTRO-2'
        WHEN 'JARDIM SAO LUIZ' THEN 'CENTRO-2'
        WHEN 'JOAO GORDO' THEN 'CENTRO-2'
        WHEN 'LOURDES' THEN 'CENTRO-2'
        WHEN 'MELO' THEN 'CENTRO-2'
        WHEN 'ROXO VERDE' THEN 'CENTRO-2'
        WHEN 'SAO JOSE' THEN 'CENTRO-2'
        WHEN 'TODOS OS SANTOS' THEN 'CENTRO-2'
        WHEN 'VILA MAURICEIA' THEN  'CENTRO-2'
        WHEN 'VILA OLIVEIRA' THEN  'CENTRO-2'
        WHEN 'VILA REGINA' THEN  'CENTRO-2'
        WHEN 'VILA EXPOSICAO' THEN  'CENTRO-2'
        WHEN 'VILA SANTA MAIRA' THEN  'CENTRO-2'
        WHEN 'ACACIAS' THEN  'DELFINO-1'
        WHEN 'ALCIDES RABELO' THEN  'DELFINO-1'
        WHEN 'ALTO FLORESTA' THEN  'DELFINO-1'
        WHEN 'BELVEDERE' THEN  'DELFINO-1'
        WHEN 'CARMELO' THEN  'DELFINO-1'
        WHEN 'CONJUNTO JK' THEN 'DELFINO-1'
        WHEN 'Conjunto Residencial Jk' THEN 'DELFINO-1'
        WHEN 'ESPLANADA' THEN 'DELFINO-1'
        WHEN 'GUARUJA' THEN 'DELFINO-1'
        WHEN 'INDEPENDENCIA' THEN 'DELFINO-1'
        WHEN 'INTERLAGOS' THEN 'DELFINO-1'
        WHEN 'JARAGUA I' THEN 'DELFINO-1'
        WHEN 'JARAGUA' THEN 'DELFINO-1'
        WHEN 'JARDIM ALEGRE' THEN 'DELFINO-1'
        WHEN 'JARDIM PRIMAVERA' THEN 'DELFINO-1'
        WHEN 'LOTEAMENTO NOVO JARAGUA' THEN 'DELFINO-1'
        WHEN 'LOTEAMENTO RECANTO DAS AGUAS' THEN 'DELFINO-1'
        WHEN 'MONTE CARMELO' THEN 'DELFINO-1'
        WHEN 'MONTE SIAO' THEN 'DELFINO-1'
        WHEN 'MONTE SIAO IV' THEN 'DELFINO-1'
        WHEN 'NOVO JARAGUA' THEN 'DELFINO-1'
        WHEN 'PLANALTO' THEN 'DELFINO-1'
        WHEN 'PORTAL DOS IPES' THEN 'DELFINO-1'
        WHEN 'RENASCENCA' THEN 'DELFINO-1'
        WHEN 'RESIDENCIAL SUL IPES' THEN 'DELFINO-1'
        WHEN 'RESIDENCIAL MONTE SIAO' THEN 'DELFINO-1'
        WHEN 'RESIDIDENCIAL MINAS GERAIS' THEN 'DELFINO-1'
        WHEN 'SANTA LUCIA' THEN 'DELFINO-1'
        WHEN 'SANTOS DUMONT' THEN 'DELFINO-1'
        WHEN 'SAO BENTO' THEN 'DELFINO-1'
        WHEN 'UNIVERSITARIO' THEN 'DELFINO-1'
        WHEN 'VENEZA PARQUE' THEN 'DELFINO-1'
        WHEN 'VILAGE DO LAGO' THEN 'DELFINO-1'
        WHEN 'VILLAGE DO LAGO I' THEN 'DELFINO-1'
        WHEN 'ALTO DA BOA VISTA' THEN 'DELFINO-2'
        WHEN 'ANTONIO PIMENTA' THEN 'DELFINO-2'
        WHEN 'CAMILO PRATES' THEN 'DELFINO-2'
        WHEN 'CINTRA' THEN 'DELFINO-2'
        WHEN 'CINTRA / DELFINO MAGALHAE' THEN 'DELFINO-2'
        WHEN 'DELFINO MAGALHAES' THEN 'DELFINO-2'
        WHEN 'NOVO DELFINO' THEN 'DELFINO-2'
        WHEN 'FRANCISCO PERES' THEN 'DELFINO-2'
        WHEN 'FRANCISCO PERES I' THEN 'DELFINO-2'
        WHEN 'JARDIM ALVORADA' THEN 'DELFINO-2'
        WHEN 'JARDIM OLIMPICO' THEN 'DELFINO-2'
        WHEN 'JARDIM PALMEIRAS' THEN 'DELFINO-2'
        WHEN 'JOSE CARLOS VALE DE LIMA' THEN 'DELFINO-2'
        WHEN 'MARIA CANDIDA' THEN 'DELFINO-2'
        WHEN 'MONTE ALEGRE' THEN 'DELFINO-2'
        WHEN 'MORRINHOS' THEN 'DELFINO-2'
        WHEN 'NOSSA SENHORA DE  FATIMA' THEN 'DELFINO-2'
        WHEN 'SANTA RAFAELA' THEN 'DELFINO-2'
        WHEN 'SANTA RITA' THEN 'DELFINO-2'
        WHEN 'SANTA RITA I' THEN 'DELFINO-2'
        WHEN 'SANTA RITA II' THEN 'DELFINO-2'
        WHEN 'SANTO ANTONIO' THEN 'DELFINO-2'
        WHEN 'SANTO INACIO' THEN 'DELFINO-2'
        WHEN 'SANTO IN' THEN 'DELFINO-2'
        WHEN 'SION' THEN 'DELFINO-2'
        WHEN 'VERA CRUZ' THEN 'DELFINO-2'
	WHEN 'VILA ANALIA' THEN 'DELFINO-2'
                    WHEN 'VILA IPIRANGA' THEN 'DELFINO-2'
                    WHEN 'VILA MARIA CANDIDA' THEN 'DELFINO-2'
                    WHEN 'VILA NAZARE' THEN'DELFINO-2'
                    WHEN 'VILA NAZARETH' THEN 'DELFINO-2'
                    WHEN 'VILA SION' THEN 'DELFINO-2'
                    WHEN 'VILA SUMARE' THEN'DELFINO-2'
                    WHEN 'VILA TELMA' THEN 'DELFINO-2'
                    WHEN 'AUGUSTA MOTA' THEN 'MAJOR-1'
                    WHEN 'Augusto Mota' THEN 'MAJOR-1'
                    WHEN 'CANDIDA CAMARA' THEN 'MAJOR-1'
                    WHEN 'CANELA' THEN 'MAJOR-1'
                    WHEN 'CANELAS' THEN 'MAJOR-1'
                    WHEN 'CIDADE NOVA' THEN 'MAJOR-1'
                    WHEN 'DOS CANELAS' THEN 'MAJOR-1'
                    WHEN 'FUNCIONARIOS' THEN 'MAJOR-1'
                    WHEN 'IBITURUNA' THEN 'MAJOR-1'
                    WHEN 'JARDIM LIBERDADE' THEN 'MAJOR-1'
                    WHEN 'JARDIM SAO GERALDO' THEN 'MAJOR-1'
                    WHEN 'JARDIM SAO GERALDO II' THEN 'MAJOR-1'
                    WHEN 'KM 14' THEN 'MAJOR-1'
                    WHEN 'MAJOR PRATES' THEN 'MAJOR-1'
                    WHEN 'MORADA DA SERRA' THEN 'MAJOR-1'
                    WHEN 'MORADA DO PARQUE' THEN 'MAJOR-1'
                    WHEN 'MORADA DO SOL' THEN 'MAJOR-1'
                    WHEN 'SAGRADA FAMILIA' THEN 'MAJOR-1'
                    WHEN 'SANTO EXPEDITO' THEN 'MAJOR-1'
                    WHEN 'SAO GERALDO' THEN 'MAJOR-1'
                    WHEN 'SAO GERALDO' THEN 'MAJOR-1'
                    WHEN 'SAO GERALDO II' THEN 'MAJOR-1'
                    WHEN 'VARGEM GRANDE' THEN 'MAJOR-1'
                    WHEN 'VILA GUILHERMINA' THEN 'MAJOR-1'
                    WHEN 'VILA LUISA' THEN 'MAJOR-1'
                    WHEN 'VILA LUIZA' THEN 'MAJOR-1'
                    WHEN 'ALTEROSAS' THEN 'MAJOR-2'
                    WHEN 'CONJUNTO CHIQUINHO GUIMARAES' THEN 'MAJOR-2'
                    WHEN 'Conjunto Ciro dos Anjos' THEN 'MAJOR-2'
                    WHEN 'CONJUNTO CRISTO REI' THEN 'MAJOR-2'
                    WHEN 'CONJUNTO JOAQUIM COSTA' THEN 'MAJOR-2'
                    WHEN 'DOS MANGUES' THEN 'MAJOR-2'
                    WHEN 'DR. JOAO ALVES' THEN 'MAJOR-2'
                    WHEN 'JOAO ALVES' THEN 'MAJOR-2'
                    WHEN 'JOSE CORREA  MACHADO' THEN 'MAJOR-2'
                    WHEN 'MARACANA' THEN 'MAJOR-2'
                    WHEN 'RESIDENCIAL SUL IPES' THEN 'MAJOR-2'
                    WHEN 'SAO JUDAS' THEN 'MAJOR-2'
                    WHEN 'SAO JUDAS TADEU' THEN 'MAJOR-2'
                    WHEN 'Sao Judas Tadeu I' THEN 'MAJOR-2'
                    WHEN 'VILA CAMPOS' THEN 'MAJOR-2'
                    WHEN 'VILA GREICE' THEN 'MAJOR-2'
                    END
            END;
    END IF;
END //

DELIMITER //

CREATE TRIGGER before_insert_cruzeiro
BEFORE INSERT ON notas
FOR EACH ROW
BEGIN
    -- Verificar se a operação é "Plena" e o município está na lista correspondente
    IF NEW.fornecedor = 'Cruzeiro' AND NEW.municipio IN ('ALMENARA', 'ARAÇUAI', 'BERILO', 'CORONEL MURTA', 'CURRAL DE DENTRO', 'FRANCISCO BADARÓ', 'JENIPAPO DE MINAS', 'AGUA BOA', 'ANGELANDIA', 'CAPELINHA', 'CARBONITA', 'ITAMARANDIBA', 'JOSÉ GONÇALVES DE MINAS', 'JOSÉ RAYDAN', 'MINAS NOVAS', 'OLHOS DÁGUA', 'SANTA MARIA DO SUAÇUÍ', 'SÃO SEBASTIÃO DO MARANHÃO', 'TURMALINA', 'CORAÇÃO DE JESUS', 'LUISLÂNDIA', 'SÃO JOÃO DA LAGOA', 'AUGUSTO DE LIMA', 'BOCAIÚVA', 'BUENÓPOLIS', 'CORINTO', 'CURVELO', 'ENGENHEIRO NAVARRO', 'FRANCISCO DUMONT', 'GUARACIAMA', 'INIMUTABA', 'JOAQUIM FELÍCIO', 'MORRO DA GARÇA', 'COUTO DE MAGALHÃES DE MINAS', 'DIAMANTINA', 'GOUVEIA', 'JAÍBA', 'JUVENÍLIA', 'MANGA', 'MATIAS CARDOSO', 'MIRAVÂNIA', 'MONTALVÂNIA', 'CATUTI', 'ESPINOSA', 'GAMELEIRAS', 'JANAÚBA', 'MAMONAS', 'MATO VERDE', 'MONTE AZUL', 'MONTEZUMA', 'PAI PEDRO', 'PORTEIRINHA', 'SANTO ANTÔNIO DO RETIRO', 'SERRANÓPOLIS DE MINAS', 'BONITO DE MINAS', 'BRASÍLIA DE MINAS', 'CÔNEGO MARINHO', 'ITACARAMBI', 'JANUÁRIA', 'JAPONVAR', 'LONTRA', 'MIRABELA', 'PEDRAS DE MARIA DA CRUZ', 'SAO FRANCISCO', 'SÃO JOÃO DAS MISSÕES', 'CRISTÁLIA', 'GLAUCILÂNDIA', 'JURAMENTO', 'MONTES CLAROS', 'BURITIZEIRO', 'CLARO DOS POÇÕES', 'PIRAPORA', 'BERIZAL', 'FRANCISCO SÁ', 'NINHEIRA', 'PADRE CARVALHO', 'RIO PARDO DE MINAS', 'SALINAS', 'SÃO JOÃO DO PARAÍSO', 'TAIOBEIRAS', 'VIRGEM DA LAPA', 'IBIAÍ', 'JEQUITAÍ', 'LASSANCE', 'VÁRZEA DA PALMA', 'SÃO JOÃO DA PONTE', 'VARZELÂNDIA') THEN
        -- Atualizar o campo "rota" na tabela "notas" com a rota correspondente
        SET NEW.rota =
            CASE
                WHEN NEW.municipio IN ('ALMENARA') THEN 'ALMENARA'
                WHEN NEW.municipio IN ('ARAÇUAI', 'BERILO', 'CORONEL MURTA', 'CURRAL DE DENTRO', 'FRANCISCO BADARÓ', 'JENIPAPO DE MINAS') THEN 'ARAÇUAI'
                WHEN NEW.municipio IN ('AGUA BOA', 'ANGELANDIA', 'CAPELINHA', 'CARBONITA', 'ITAMARANDIBA', 'JOSÉ GONÇALVES DE MINAS', 'JOSÉ RAYDAN', 'MINAS NOVAS', 'OLHOS DÁGUA', 'SANTA MARIA DO SUAÇUÍ', 'SÃO SEBASTIÃO DO MARANHÃO', 'TURMALINA') THEN 'CAPELINHA'
                WHEN NEW.municipio IN ('CORAÇÃO DE JESUS', 'LUISLÂNDIA', 'SÃO JOÃO DA LAGOA') THEN 'CORACAO'
                WHEN NEW.municipio IN ('AUGUSTO DE LIMA', 'BOCAIÚVA', 'BUENÓPOLIS', 'CORINTO', 'CURVELO', 'ENGENHEIRO NAVARRO', 'FRANCISCO DUMONT', 'GUARACIAMA', 'INIMUTABA', 'JOAQUIM FELÍCIO', 'MORRO DA GARÇA') THEN 'CURVELO'
                WHEN NEW.municipio IN ('COUTO DE MAGALHÃES DE MINAS', 'DIAMANTINA', 'GOUVEIA') THEN 'DIAMANTINA'
                WHEN NEW.municipio IN ('JAÍBA', 'JUVENÍLIA', 'MANGA', 'MATIAS CARDOSO', 'MIRAVÂNIA', 'MONTALVÂNIA') THEN 'JAIBA'
                WHEN NEW.municipio IN ('CATUTI', 'ESPINOSA', 'GAMELEIRAS', 'JANAÚBA', 'MAMONAS', 'MATO VERDE', 'MONTE AZUL', 'MONTEZUMA', 'PAI PEDRO', 'PORTEIRINHA', 'SANTO ANTÔNIO DO RETIRO', 'SERRANÓPOLIS DE MINAS') THEN 'JANAUBA'
                WHEN NEW.municipio IN ('BONITO DE MINAS', 'BRASÍLIA DE MINAS', 'CÔNEGO MARINHO', 'ITACARAMBI', 'JANUÁRIA', 'JAPONVAR', 'LONTRA', 'MIRABELA', 'PEDRAS DE MARIA DA CRUZ', 'SAO FRANCISCO', 'SÃO JOÃO DAS MISSÕES') THEN 'JANUÁRIA'
		WHEN NEW.municipio IN ('CRISTÁLIA', 'GLAUCILÂNDIA', 'JURAMENTO') THEN 'MONTES CLAROS'
                WHEN NEW.municipio IN ('MONTES CLAROS') THEN 
		 CASE NEW.bairro
        WHEN 'ZONA RURAL' THEN 'BOCAIUVA'
        WHEN 'PLANALTO RURAL' THEN 'BOCAIUVA'
        WHEN 'BARCELONA PARQUE' THEN 'CENTRO-1'
        WHEN 'BELA VISTA' THEN 'CENTRO-1'
        WHEN 'CIDADE INDUSTRIAL' THEN 'CENTRO-1'
        WHEN 'CONJ.HABITAC.VITORIA II' THEN 'CENTRO-1'
        WHEN 'CONJ HABITAC VITORIA II' THEN 'CENTRO-1'
        WHEN 'JARDIM PANORAMA' THEN 'CENTRO-1'
        WHEN 'CONJ PANORAMA II' THEN 'CENTRO-1'
        WHEN 'CONJUNTO PANORAMA II' THEN 'CENTRO-1'
        WHEN 'DISTRITO INDUSTRIAL' THEN 'CENTRO-1'
        WHEN 'EDGAR PEREIRA' THEN 'CENTRO-1'
        WHEN 'ELDORADO' THEN 'CENTRO-1'
        WHEN 'JARDIM BRASIL' THEN 'CENTRO-1'
        WHEN 'DISTRITO-NOVA ESPERANCA' THEN 'CENTRO-1'
        WHEN 'NOVA ESPERANCA' THEN 'CENTRO-1'
        WHEN 'NOVA MORADA' THEN 'CENTRO-1'
        WHEN 'RESIDENCIAL VITORIA' THEN 'CENTRO-1'
        WHEN 'RIO CEDRO' THEN 'CENTRO-1'
        WHEN 'SANTA EUGENIA' THEN 'CENTRO-1'
        WHEN 'SANTA ROSA DE LIMA' THEN 'CENTRO-1'
        WHEN 'SANTOS REIS' THEN 'CENTRO-1'
        WHEN 'VILA ANTONIO NARCISO' THEN 'CENTRO-1'
        WHEN 'VILA ATLANTIDA' THEN 'CENTRO-1'
        WHEN 'VILA AUREA' THEN 'CENTRO-1'
        WHEN 'VILA BRASILIA' THEN 'CENTRO-1'
        WHEN 'VILA CASTELO BRANCO' THEN 'CENTRO-1'
        WHEN 'VILA PRODACON' THEN 'CENTRO-1'
        WHEN 'VILA SANTA EUGENIA' THEN 'CENTRO-1'
        WHEN 'VILA TONCHEF' THEN 'CENTRO-1'
        WHEN 'ALICE MAIA' THEN 'CENTRO-2'
        WHEN 'ALTO SAO JOAO' THEN 'CENTRO-2'
        WHEN 'CENTRO' THEN 'CENTRO-2'
        WHEN 'FUNCIONARIOS' THEN 'CENTRO-2'
        WHEN 'JARDIM SAO LUIZ' THEN 'CENTRO-2'
        WHEN 'JOAO GORDO' THEN 'CENTRO-2'
        WHEN 'LOURDES' THEN 'CENTRO-2'
        WHEN 'MELO' THEN 'CENTRO-2'
        WHEN 'ROXO VERDE' THEN 'CENTRO-2'
        WHEN 'SAO JOSE' THEN 'CENTRO-2'
        WHEN 'TODOS OS SANTOS' THEN 'CENTRO-2'
        WHEN 'VILA MAURICEIA' THEN  'CENTRO-2'
        WHEN 'VILA OLIVEIRA' THEN  'CENTRO-2'
        WHEN 'VILA REGINA' THEN  'CENTRO-2'
        WHEN 'VILA EXPOSICAO' THEN  'CENTRO-2'
        WHEN 'VILA SANTA MAIRA' THEN  'CENTRO-2'
        WHEN 'ACACIAS' THEN  'DELFINO-1'
        WHEN 'ALCIDES RABELO' THEN  'DELFINO-1'
        WHEN 'ALTO FLORESTA' THEN  'DELFINO-1'
        WHEN 'BELVEDERE' THEN  'DELFINO-1'
        WHEN 'CARMELO' THEN  'DELFINO-1'
        WHEN 'CONJUNTO JK' THEN 'DELFINO-1'
        WHEN 'Conjunto Residencial Jk' THEN 'DELFINO-1'
        WHEN 'ESPLANADA' THEN 'DELFINO-1'
        WHEN 'GUARUJA' THEN 'DELFINO-1'
        WHEN 'INDEPENDENCIA' THEN 'DELFINO-1'
        WHEN 'INTERLAGOS' THEN 'DELFINO-1'
        WHEN 'JARAGUA I' THEN 'DELFINO-1'
        WHEN 'JARAGUA' THEN 'DELFINO-1'
        WHEN 'JARDIM ALEGRE' THEN 'DELFINO-1'
        WHEN 'JARDIM PRIMAVERA' THEN 'DELFINO-1'
        WHEN 'LOTEAMENTO NOVO JARAGUA' THEN 'DELFINO-1'
        WHEN 'LOTEAMENTO RECANTO DAS AGUAS' THEN 'DELFINO-1'
        WHEN 'MONTE CARMELO' THEN 'DELFINO-1'
        WHEN 'MONTE SIAO' THEN 'DELFINO-1'
        WHEN 'MONTE SIAO IV' THEN 'DELFINO-1'
        WHEN 'NOVO JARAGUA' THEN 'DELFINO-1'
        WHEN 'PLANALTO' THEN 'DELFINO-1'
        WHEN 'PORTAL DOS IPES' THEN 'DELFINO-1'
        WHEN 'RENASCENCA' THEN 'DELFINO-1'
        WHEN 'RESIDENCIAL SUL IPES' THEN 'DELFINO-1'
        WHEN 'RESIDENCIAL MONTE SIAO' THEN 'DELFINO-1'
        WHEN 'RESIDIDENCIAL MINAS GERAIS' THEN 'DELFINO-1'
        WHEN 'SANTA LUCIA' THEN 'DELFINO-1'
        WHEN 'SANTOS DUMONT' THEN 'DELFINO-1'
        WHEN 'SAO BENTO' THEN 'DELFINO-1'
        WHEN 'UNIVERSITARIO' THEN 'DELFINO-1'
        WHEN 'VENEZA PARQUE' THEN 'DELFINO-1'
        WHEN 'VILAGE DO LAGO' THEN 'DELFINO-1'
        WHEN 'VILLAGE DO LAGO I' THEN 'DELFINO-1'
        WHEN 'ALTO DA BOA VISTA' THEN 'DELFINO-2'
        WHEN 'ANTONIO PIMENTA' THEN 'DELFINO-2'
        WHEN 'CAMILO PRATES' THEN 'DELFINO-2'
        WHEN 'CINTRA' THEN 'DELFINO-2'
        WHEN 'CINTRA / DELFINO MAGALHAE' THEN 'DELFINO-2'
        WHEN 'DELFINO MAGALHAES' THEN 'DELFINO-2'
        WHEN 'NOVO DELFINO' THEN 'DELFINO-2'
        WHEN 'FRANCISCO PERES' THEN 'DELFINO-2'
        WHEN 'FRANCISCO PERES I' THEN 'DELFINO-2'
        WHEN 'JARDIM ALVORADA' THEN 'DELFINO-2'
        WHEN 'JARDIM OLIMPICO' THEN 'DELFINO-2'
        WHEN 'JARDIM PALMEIRAS' THEN 'DELFINO-2'
        WHEN 'JOSE CARLOS VALE DE LIMA' THEN 'DELFINO-2'
        WHEN 'MARIA CANDIDA' THEN 'DELFINO-2'
        WHEN 'MONTE ALEGRE' THEN 'DELFINO-2'
        WHEN 'MORRINHOS' THEN 'DELFINO-2'
        WHEN 'NOSSA SENHORA DE  FATIMA' THEN 'DELFINO-2'
        WHEN 'SANTA RAFAELA' THEN 'DELFINO-2'
        WHEN 'SANTA RITA' THEN 'DELFINO-2'
        WHEN 'SANTA RITA I' THEN 'DELFINO-2'
        WHEN 'SANTA RITA II' THEN 'DELFINO-2'
        WHEN 'SANTO ANTONIO' THEN 'DELFINO-2'
        WHEN 'SANTO INACIO' THEN 'DELFINO-2'
        WHEN 'SANTO IN' THEN 'DELFINO-2'
        WHEN 'SION' THEN 'DELFINO-2'
        WHEN 'VERA CRUZ' THEN 'DELFINO-2'
	WHEN 'VILA ANALIA' THEN 'DELFINO-2'
                    WHEN 'VILA IPIRANGA' THEN 'DELFINO-2'
                    WHEN 'VILA MARIA CANDIDA' THEN 'DELFINO-2'
                    WHEN 'VILA NAZARE' THEN'DELFINO-2'
                    WHEN 'VILA NAZARETH' THEN 'DELFINO-2'
                    WHEN 'VILA SION' THEN 'DELFINO-2'
                    WHEN 'VILA SUMARE' THEN'DELFINO-2'
                    WHEN 'VILA TELMA' THEN 'DELFINO-2'
                    WHEN 'AUGUSTA MOTA' THEN 'MAJOR-1'
                    WHEN 'Augusto Mota' THEN 'MAJOR-1'
                    WHEN 'CANDIDA CAMARA' THEN 'MAJOR-1'
                    WHEN 'CANELA' THEN 'MAJOR-1'
                    WHEN 'CANELAS' THEN 'MAJOR-1'
                    WHEN 'CIDADE NOVA' THEN 'MAJOR-1'
                    WHEN 'DOS CANELAS' THEN 'MAJOR-1'
                    WHEN 'FUNCIONARIOS' THEN 'MAJOR-1'
                    WHEN 'IBITURUNA' THEN 'MAJOR-1'
                    WHEN 'JARDIM LIBERDADE' THEN 'MAJOR-1'
                    WHEN 'JARDIM SAO GERALDO' THEN 'MAJOR-1'
                    WHEN 'JARDIM SAO GERALDO II' THEN 'MAJOR-1'
                    WHEN 'KM 14' THEN 'MAJOR-1'
                    WHEN 'MAJOR PRATES' THEN 'MAJOR-1'
                    WHEN 'MORADA DA SERRA' THEN 'MAJOR-1'
                    WHEN 'MORADA DO PARQUE' THEN 'MAJOR-1'
                    WHEN 'MORADA DO SOL' THEN 'MAJOR-1'
                    WHEN 'SAGRADA FAMILIA' THEN 'MAJOR-1'
                    WHEN 'SANTO EXPEDITO' THEN 'MAJOR-1'
                    WHEN 'SAO GERALDO' THEN 'MAJOR-1'
                    WHEN 'SAO GERALDO' THEN 'MAJOR-1'
                    WHEN 'SAO GERALDO II' THEN 'MAJOR-1'
                    WHEN 'VARGEM GRANDE' THEN 'MAJOR-1'
                    WHEN 'VILA GUILHERMINA' THEN 'MAJOR-1'
                    WHEN 'VILA LUISA' THEN 'MAJOR-1'
                    WHEN 'VILA LUIZA' THEN 'MAJOR-1'
                    WHEN 'ALTEROSAS' THEN 'MAJOR-2'
                    WHEN 'CONJUNTO CHIQUINHO GUIMARAES' THEN 'MAJOR-2'
                    WHEN 'Conjunto Ciro dos Anjos' THEN 'MAJOR-2'
                    WHEN 'CONJUNTO CRISTO REI' THEN 'MAJOR-2'
                    WHEN 'CONJUNTO JOAQUIM COSTA' THEN 'MAJOR-2'
                    WHEN 'DOS MANGUES' THEN 'MAJOR-2'
                    WHEN 'DR. JOAO ALVES' THEN 'MAJOR-2'
                    WHEN 'JOAO ALVES' THEN 'MAJOR-2'
                    WHEN 'JOSE CORREA  MACHADO' THEN 'MAJOR-2'
                    WHEN 'MARACANA' THEN 'MAJOR-2'
                    WHEN 'RESIDENCIAL SUL IPES' THEN 'MAJOR-2'
                    WHEN 'SAO JUDAS' THEN 'MAJOR-2'
                    WHEN 'SAO JUDAS TADEU' THEN 'MAJOR-2'
                    WHEN 'Sao Judas Tadeu I' THEN 'MAJOR-2'
                    WHEN 'VILA CAMPOS' THEN 'MAJOR-2'
                    WHEN 'VILA GREICE' THEN 'MAJOR-2'
                    END
                WHEN NEW.municipio IN ('BURITIZEIRO', 'CLARO DOS POÇÕES', 'PIRAPORA') THEN 'PIRAPORA'
                WHEN NEW.municipio IN ('BERIZAL', 'FRANCISCO SÁ', 'NINHEIRA', 'PADRE CARVALHO', 'RIO PARDO DE MINAS', 'SALINAS', 'SÃO JOÃO DO PARAÍSO', 'TAIOBEIRAS', 'VIRGEM DA LAPA') THEN 'TAIOBEIRAS'
                WHEN NEW.municipio IN ('IBIAÍ', 'JEQUITAÍ', 'LASSANCE', 'VÁRZEA DA PALMA') THEN  'VÁRZEA DA PALMA'
                WHEN NEW.municipio IN ('SÃO JOÃO DA PONTE', 'VARZELÂNDIA') THEN  'VARZELÂNDIA'
            END;
    END IF;
END //

DELIMITER ;



DELIMITER //

CREATE TRIGGER before_insert_suinco
BEFORE INSERT ON notas
FOR EACH ROW
BEGIN
    -- Verificar se a operação é "Plena" e o município está na lista correspondente
    IF NEW.fornecedor = 'Suinco' AND NEW.municipio IN ('Angelândia', 'Aricanduva', 'Capelinha', 'Chapada do Norte', 'Itamarandiba', 'Minas Novas', 'Olhos-d''Água', 'Turmalina', 'Veredinha', 'Augusto de Lima', 'Bocaiúva', 'Buenópolis', 'Corinto', 'Curvelo', 'Datas', 'Engenheiro Navarro', 'Francisco Dumont', 'Guaraciama', 'Inimutaba', 'Joaquim Felício', 'Morro da Garça', 'Couto de Magalhães de Min', 'Diamantina', 'Gouveia', 'São Gonçalo do Rio Preto', 'Jaíba', 'Juvenília', 'Manga', 'Matias Cardoso', 'Miravânia', 'Montalvânia', 'Verdelândia', 'Capitão Enéas', 'Catuti', 'Espinosa', 'Gameleiras', 'Janaúba', 'Mamonas', 'Mato Verde', 'Monte Azul', 'Montezuma', 'Pai Pedro', 'Porteirinha', 'Riacho dos Machados', 'Serranópolis de Minas', 'Bonito de Minas', 'Brasília de Minas', 'Campo Azul', 'Cônego Marinho', 'Coração de Jesus', 'Icaraí de Minas', 'Itacarambi', 'Januária', 'Japonvar', 'Lontra', 'Luislândia', 'Mirabela', 'Pedras de Maria da Cruz', 'Ponto Chique', 'São Francisco', 'São João da Lagoa', 'São João do Pacuí', 'São Romão', 'Ubaí', 'Glaucilândia', 'Itacambira', 'Juramento', 'Montes Claros', 'Buritizeiro', 'Claro dos Poções', 'Ibiaí', 'Jequitaí', 'Pirapora', 'Várzea da Palma', 'Ibiracatu', 'São João da Ponte', 'Varzelândia', 'Araçuaí', 'Berilo', 'Botumirim', 'Cristália', 'Francisco Sá', 'Grão Mogol', 'Indaiabira', 'Ninheira', 'Rio Pardo de Minas', 'Salinas', 'São João do Paraíso', 'Taiobeiras') THEN
        -- Atualizar o campo "rota" na tabela "notas" com a rota correspondente
        SET NEW.rota =
            CASE
                WHEN NEW.municipio IN ('Angelândia', 'Aricanduva', 'Capelinha', 'Chapada do Norte', 'Itamarandiba', 'Minas Novas', 'Olhos-d''Água', 'Turmalina', 'Veredinha') THEN 'Capelinha'
                WHEN NEW.municipio IN ('Augusto de Lima', 'Bocaiúva', 'Buenópolis', 'Corinto', 'Curvelo', 'Datas', 'Engenheiro Navarro', 'Francisco Dumont', 'Guaraciama', 'Inimutaba', 'Joaquim Felício', 'Morro da Garça') THEN 'Curvelo'
                WHEN NEW.municipio IN ('Couto de Magalhães de Min', 'Diamantina', 'Gouveia', 'São Gonçalo do Rio Preto') THEN 'Diamantina'
                WHEN NEW.municipio IN ('Jaíba', 'Juvenília', 'Manga', 'Matias Cardoso', 'Miravânia', 'Montalvânia', 'Verdelândia') THEN 'Jaíba'
                WHEN NEW.municipio IN ('Capitão Enéas', 'Catuti', 'Espinosa', 'Gameleiras', 'Janaúba', 'Mamonas', 'Mato Verde', 'Monte Azul', 'Montezuma', 'Pai Pedro', 'Porteirinha', 'Riacho dos Machados', 'Serranópolis de Minas') THEN 'Janaúba'
                WHEN NEW.municipio IN ('Bonito de Minas', 'Brasília de Minas', 'Campo Azul', 'Cônego Marinho', 'Coração de Jesus', 'Icaraí de Minas', 'Itacarambi', 'Januária', 'Japonvar', 'Lontra', 'Luislândia', 'Mirabela', 'Pedras de Maria da Cruz', 'Ponto Chique', 'São Francisco', 'São João da Lagoa', 'São João do Pacuí', 'São Romão', 'Ubaí') THEN 'Januária'
                WHEN NEW.municipio IN ('Glaucilândia', 'Itacambira', 'Juramento') THEN 'Montes Claros'
		WHEN NEW.municipio IN ( 'Montes Claros') THEN
		 CASE NEW.bairro
        WHEN 'ZONA RURAL' THEN 'BOCAIUVA'
        WHEN 'PLANALTO RURAL' THEN 'BOCAIUVA'
        WHEN 'BARCELONA PARQUE' THEN 'CENTRO-1'
        WHEN 'BELA VISTA' THEN 'CENTRO-1'
        WHEN 'CIDADE INDUSTRIAL' THEN 'CENTRO-1'
        WHEN 'CONJ.HABITAC.VITORIA II' THEN 'CENTRO-1'
        WHEN 'CONJ HABITAC VITORIA II' THEN 'CENTRO-1'
        WHEN 'JARDIM PANORAMA' THEN 'CENTRO-1'
        WHEN 'CONJ PANORAMA II' THEN 'CENTRO-1'
        WHEN 'CONJUNTO PANORAMA II' THEN 'CENTRO-1'
        WHEN 'DISTRITO INDUSTRIAL' THEN 'CENTRO-1'
        WHEN 'EDGAR PEREIRA' THEN 'CENTRO-1'
        WHEN 'ELDORADO' THEN 'CENTRO-1'
        WHEN 'JARDIM BRASIL' THEN 'CENTRO-1'
        WHEN 'DISTRITO-NOVA ESPERANCA' THEN 'CENTRO-1'
        WHEN 'NOVA ESPERANCA' THEN 'CENTRO-1'
        WHEN 'NOVA MORADA' THEN 'CENTRO-1'
        WHEN 'RESIDENCIAL VITORIA' THEN 'CENTRO-1'
        WHEN 'RIO CEDRO' THEN 'CENTRO-1'
        WHEN 'SANTA EUGENIA' THEN 'CENTRO-1'
        WHEN 'SANTA ROSA DE LIMA' THEN 'CENTRO-1'
        WHEN 'SANTOS REIS' THEN 'CENTRO-1'
        WHEN 'VILA ANTONIO NARCISO' THEN 'CENTRO-1'
        WHEN 'VILA ATLANTIDA' THEN 'CENTRO-1'
        WHEN 'VILA AUREA' THEN 'CENTRO-1'
        WHEN 'VILA BRASILIA' THEN 'CENTRO-1'
        WHEN 'VILA CASTELO BRANCO' THEN 'CENTRO-1'
        WHEN 'VILA PRODACON' THEN 'CENTRO-1'
        WHEN 'VILA SANTA EUGENIA' THEN 'CENTRO-1'
        WHEN 'VILA TONCHEF' THEN 'CENTRO-1'
        WHEN 'ALICE MAIA' THEN 'CENTRO-2'
        WHEN 'ALTO SAO JOAO' THEN 'CENTRO-2'
        WHEN 'CENTRO' THEN 'CENTRO-2'
        WHEN 'FUNCIONARIOS' THEN 'CENTRO-2'
        WHEN 'JARDIM SAO LUIZ' THEN 'CENTRO-2'
        WHEN 'JOAO GORDO' THEN 'CENTRO-2'
        WHEN 'LOURDES' THEN 'CENTRO-2'
        WHEN 'MELO' THEN 'CENTRO-2'
        WHEN 'ROXO VERDE' THEN 'CENTRO-2'
        WHEN 'SAO JOSE' THEN 'CENTRO-2'
        WHEN 'TODOS OS SANTOS' THEN 'CENTRO-2'
        WHEN 'VILA MAURICEIA' THEN  'CENTRO-2'
        WHEN 'VILA OLIVEIRA' THEN  'CENTRO-2'
        WHEN 'VILA REGINA' THEN  'CENTRO-2'
        WHEN 'VILA EXPOSICAO' THEN  'CENTRO-2'
        WHEN 'VILA SANTA MAIRA' THEN  'CENTRO-2'
        WHEN 'ACACIAS' THEN  'DELFINO-1'
        WHEN 'ALCIDES RABELO' THEN  'DELFINO-1'
        WHEN 'ALTO FLORESTA' THEN  'DELFINO-1'
        WHEN 'BELVEDERE' THEN  'DELFINO-1'
        WHEN 'CARMELO' THEN  'DELFINO-1'
        WHEN 'CONJUNTO JK' THEN 'DELFINO-1'
        WHEN 'Conjunto Residencial Jk' THEN 'DELFINO-1'
        WHEN 'ESPLANADA' THEN 'DELFINO-1'
        WHEN 'GUARUJA' THEN 'DELFINO-1'
        WHEN 'INDEPENDENCIA' THEN 'DELFINO-1'
        WHEN 'INTERLAGOS' THEN 'DELFINO-1'
        WHEN 'JARAGUA I' THEN 'DELFINO-1'
        WHEN 'JARAGUA' THEN 'DELFINO-1'
        WHEN 'JARDIM ALEGRE' THEN 'DELFINO-1'
        WHEN 'JARDIM PRIMAVERA' THEN 'DELFINO-1'
        WHEN 'LOTEAMENTO NOVO JARAGUA' THEN 'DELFINO-1'
        WHEN 'LOTEAMENTO RECANTO DAS AGUAS' THEN 'DELFINO-1'
        WHEN 'MONTE CARMELO' THEN 'DELFINO-1'
        WHEN 'MONTE SIAO' THEN 'DELFINO-1'
        WHEN 'MONTE SIAO IV' THEN 'DELFINO-1'
        WHEN 'NOVO JARAGUA' THEN 'DELFINO-1'
        WHEN 'PLANALTO' THEN 'DELFINO-1'
        WHEN 'PORTAL DOS IPES' THEN 'DELFINO-1'
        WHEN 'RENASCENCA' THEN 'DELFINO-1'
        WHEN 'RESIDENCIAL SUL IPES' THEN 'DELFINO-1'
        WHEN 'RESIDENCIAL MONTE SIAO' THEN 'DELFINO-1'
        WHEN 'RESIDIDENCIAL MINAS GERAIS' THEN 'DELFINO-1'
        WHEN 'SANTA LUCIA' THEN 'DELFINO-1'
        WHEN 'SANTOS DUMONT' THEN 'DELFINO-1'
        WHEN 'SAO BENTO' THEN 'DELFINO-1'
        WHEN 'UNIVERSITARIO' THEN 'DELFINO-1'
        WHEN 'VENEZA PARQUE' THEN 'DELFINO-1'
        WHEN 'VILAGE DO LAGO' THEN 'DELFINO-1'
        WHEN 'VILLAGE DO LAGO I' THEN 'DELFINO-1'
        WHEN 'ALTO DA BOA VISTA' THEN 'DELFINO-2'
        WHEN 'ANTONIO PIMENTA' THEN 'DELFINO-2'
        WHEN 'CAMILO PRATES' THEN 'DELFINO-2'
        WHEN 'CINTRA' THEN 'DELFINO-2'
        WHEN 'CINTRA / DELFINO MAGALHAE' THEN 'DELFINO-2'
        WHEN 'DELFINO MAGALHAES' THEN 'DELFINO-2'
        WHEN 'NOVO DELFINO' THEN 'DELFINO-2'
        WHEN 'FRANCISCO PERES' THEN 'DELFINO-2'
        WHEN 'FRANCISCO PERES I' THEN 'DELFINO-2'
        WHEN 'JARDIM ALVORADA' THEN 'DELFINO-2'
        WHEN 'JARDIM OLIMPICO' THEN 'DELFINO-2'
        WHEN 'JARDIM PALMEIRAS' THEN 'DELFINO-2'
        WHEN 'JOSE CARLOS VALE DE LIMA' THEN 'DELFINO-2'
        WHEN 'MARIA CANDIDA' THEN 'DELFINO-2'
        WHEN 'MONTE ALEGRE' THEN 'DELFINO-2'
        WHEN 'MORRINHOS' THEN 'DELFINO-2'
        WHEN 'NOSSA SENHORA DE  FATIMA' THEN 'DELFINO-2'
        WHEN 'SANTA RAFAELA' THEN 'DELFINO-2'
        WHEN 'SANTA RITA' THEN 'DELFINO-2'
        WHEN 'SANTA RITA I' THEN 'DELFINO-2'
        WHEN 'SANTA RITA II' THEN 'DELFINO-2'
        WHEN 'SANTO ANTONIO' THEN 'DELFINO-2'
        WHEN 'SANTO INACIO' THEN 'DELFINO-2'
        WHEN 'SANTO IN' THEN 'DELFINO-2'
        WHEN 'SION' THEN 'DELFINO-2'
        WHEN 'VERA CRUZ' THEN 'DELFINO-2'
	WHEN 'VILA ANALIA' THEN 'DELFINO-2'
                    WHEN 'VILA IPIRANGA' THEN 'DELFINO-2'
                    WHEN 'VILA MARIA CANDIDA' THEN 'DELFINO-2'
                    WHEN 'VILA NAZARE' THEN'DELFINO-2'
                    WHEN 'VILA NAZARETH' THEN 'DELFINO-2'
                    WHEN 'VILA SION' THEN 'DELFINO-2'
                    WHEN 'VILA SUMARE' THEN'DELFINO-2'
                    WHEN 'VILA TELMA' THEN 'DELFINO-2'
                    WHEN 'AUGUSTA MOTA' THEN 'MAJOR-1'
                    WHEN 'Augusto Mota' THEN 'MAJOR-1'
                    WHEN 'CANDIDA CAMARA' THEN 'MAJOR-1'
                    WHEN 'CANELA' THEN 'MAJOR-1'
                    WHEN 'CANELAS' THEN 'MAJOR-1'
                    WHEN 'CIDADE NOVA' THEN 'MAJOR-1'
                    WHEN 'DOS CANELAS' THEN 'MAJOR-1'
                    WHEN 'FUNCIONARIOS' THEN 'MAJOR-1'
                    WHEN 'IBITURUNA' THEN 'MAJOR-1'
                    WHEN 'JARDIM LIBERDADE' THEN 'MAJOR-1'
                    WHEN 'JARDIM SAO GERALDO' THEN 'MAJOR-1'
                    WHEN 'JARDIM SAO GERALDO II' THEN 'MAJOR-1'
                    WHEN 'KM 14' THEN 'MAJOR-1'
                    WHEN 'MAJOR PRATES' THEN 'MAJOR-1'
                    WHEN 'MORADA DA SERRA' THEN 'MAJOR-1'
                    WHEN 'MORADA DO PARQUE' THEN 'MAJOR-1'
                    WHEN 'MORADA DO SOL' THEN 'MAJOR-1'
                    WHEN 'SAGRADA FAMILIA' THEN 'MAJOR-1'
                    WHEN 'SANTO EXPEDITO' THEN 'MAJOR-1'
                    WHEN 'SAO GERALDO' THEN 'MAJOR-1'
                    WHEN 'SAO GERALDO' THEN 'MAJOR-1'
                    WHEN 'SAO GERALDO II' THEN 'MAJOR-1'
                    WHEN 'VARGEM GRANDE' THEN 'MAJOR-1'
                    WHEN 'VILA GUILHERMINA' THEN 'MAJOR-1'
                    WHEN 'VILA LUISA' THEN 'MAJOR-1'
                    WHEN 'VILA LUIZA' THEN 'MAJOR-1'
                    WHEN 'ALTEROSAS' THEN 'MAJOR-2'
                    WHEN 'CONJUNTO CHIQUINHO GUIMARAES' THEN 'MAJOR-2'
                    WHEN 'Conjunto Ciro dos Anjos' THEN 'MAJOR-2'
                    WHEN 'CONJUNTO CRISTO REI' THEN 'MAJOR-2'
                    WHEN 'CONJUNTO JOAQUIM COSTA' THEN 'MAJOR-2'
                    WHEN 'DOS MANGUES' THEN 'MAJOR-2'
                    WHEN 'DR. JOAO ALVES' THEN 'MAJOR-2'
                    WHEN 'JOAO ALVES' THEN 'MAJOR-2'
                    WHEN 'JOSE CORREA  MACHADO' THEN 'MAJOR-2'
                    WHEN 'MARACANA' THEN 'MAJOR-2'
                    WHEN 'RESIDENCIAL SUL IPES' THEN 'MAJOR-2'
                    WHEN 'SAO JUDAS' THEN 'MAJOR-2'
                    WHEN 'SAO JUDAS TADEU' THEN 'MAJOR-2'
                    WHEN 'Sao Judas Tadeu I' THEN 'MAJOR-2'
                    WHEN 'VILA CAMPOS' THEN 'MAJOR-2'
                    WHEN 'VILA GREICE' THEN 'MAJOR-2'
                    END
                WHEN NEW.municipio IN ('Buritizeiro', 'Claro dos Poções', 'Ibiaí', 'Jequitaí', 'Pirapora', 'Várzea da Palma') THEN 'Pirapora'
                WHEN NEW.municipio IN ('Ibiracatu', 'São João da Ponte', 'Varzelândia') THEN 'São João da Ponte'
                WHEN NEW.municipio IN ('Araçuaí', 'Berilo', 'Botumirim', 'Cristália', 'Francisco Sá', 'Grão Mogol', 'Indaiabira', 'Ninheira', 'Rio Pardo de Minas', 'Salinas', 'São João do Paraíso', 'Taiobeiras') THEN 'Taiobeiras'
            END;
    END IF;
END //

DELIMITER ;

