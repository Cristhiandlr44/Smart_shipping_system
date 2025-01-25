-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 25/01/2025 às 03:49
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

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
CREATE DATABASE IF NOT EXISTS `seminariobd` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `seminariobd`;

DELIMITER $$
--
-- Procedimentos
--
DROP PROCEDURE IF EXISTS `AlterarProdutoQuantidade`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `AlterarProdutoQuantidade` (IN `p_cod` VARCHAR(12), IN `p_nf` INT, IN `p_nova_quantidade` FLOAT)   BEGIN
    UPDATE produtos SET quantidade = p_nova_quantidade
    WHERE cod = p_cod AND nf = p_nf;
END$$

DROP PROCEDURE IF EXISTS `AttrDataEntregaMonitor`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `AttrDataEntregaMonitor` (IN `p_IdMonitoramento` INT, IN `p_DataEntrega` DATE)   BEGIN
    UPDATE monitoramento
    SET data_entrega = p_DataEntrega
    WHERE Id = p_IdMonitoramento;
END$$

DROP PROCEDURE IF EXISTS `Test_LimparNotas`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `Test_LimparNotas` ()   BEGIN
       DELETE FROM CRUZEIRO_NOTAS WHERE 1;
        DELETE FROM PLENA_NOTAS WHERE 1;
        DELETE FROM AURORA_NOTAS WHERE 1;
        DELETE FROM REDES WHERE 1;
        DELETE FROM SUINCO_NOTAS WHERE 1;
        DELETE FROM PRODUTOS WHERE 1;
        DELETE FROM NOTAS WHERE 1;
        DELETE FROM MONITORAMENTO WHERE 1;
        DELETE FROM clientes WHERE 1;
        DELETE FROM dellys_notas WHERE 1;
        

      
   END$$

DROP PROCEDURE IF EXISTS `UpMotoristas_Caminhoes`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpMotoristas_Caminhoes` (IN `p_placa` CHAR(8), IN `p_modelo` CHAR(1), IN `p_nome` VARCHAR(20), IN `p_CPF_motorista` CHAR(11), IN `p_num_habilitacao` INT, IN `p_venci_habilitacao` DATE)   BEGIN
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
END$$

DROP PROCEDURE IF EXISTS `VerificarMonitoramentosAtivos`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `VerificarMonitoramentosAtivos` ()   BEGIN
    SELECT *
    FROM visao_monitoramento
    WHERE largada >= CURDATE();
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `anomalias`
--
-- Criação: 25/01/2025 às 00:14
-- Última atualização: 25/01/2025 às 02:47
--

DROP TABLE IF EXISTS `anomalias`;
CREATE TABLE `anomalias` (
  `cod` smallint(10) UNSIGNED NOT NULL,
  `nf` varchar(14) NOT NULL,
  `tipo` varchar(16) NOT NULL,
  `motivo` varchar(40) NOT NULL,
  `cod_item` varchar(8) DEFAULT NULL,
  `quantidade` float DEFAULT NULL,
  `unidade` varchar(4) DEFAULT NULL,
  `peso` float NOT NULL,
  `Devolvida` varchar(1) NOT NULL DEFAULT 'N',
  `observacao` text DEFAULT NULL,
  `data_devolucao` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `aurora_notas`
--
-- Criação: 25/01/2025 às 00:14
-- Última atualização: 25/01/2025 às 02:47
--

DROP TABLE IF EXISTS `aurora_notas`;
CREATE TABLE `aurora_notas` (
  `fk_notas_n_nota` varchar(14) NOT NULL,
  `n_carga` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `base_rotas`
--
-- Criação: 24/01/2025 às 22:58
-- Última atualização: 24/01/2025 às 23:08
--

DROP TABLE IF EXISTS `base_rotas`;
CREATE TABLE `base_rotas` (
  `id` smallint(6) UNSIGNED NOT NULL,
  `cidade` varchar(30) NOT NULL,
  `rota` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `base_rotas`
--

INSERT DELAYED INTO `base_rotas` (`id`, `cidade`, `rota`) VALUES
(1, 'ALMENARA', 'ALMENARA'),
(2, 'BOCAIUVA', 'BOCAIUVA'),
(3, 'ENGENHEIRO NAVARRO', 'BOCAIUVA'),
(4, 'GUARACIAMA', 'BOCAIUVA'),
(5, 'ARACUAI', 'ARACUAI'),
(6, 'SALINAS', 'ARACUAI'),
(7, 'FRANCISCO SA', 'ARACUAI'),
(8, 'CAPITAO ENEAS', 'JANAUBA'),
(9, 'JANAUBA', 'JANAUBA'),
(10, 'NOVA PORTEIRINHA', 'JANAUBA'),
(11, 'PORTEIRINHA', 'JANAUBA'),
(12, 'BRASILIA DE MINAS', 'JANUARIA'),
(13, 'JANUARIA', 'JANUARIA'),
(14, 'JAPONVAR', 'JANUARIA'),
(15, 'LONTRA', 'JANUARIA'),
(16, 'MIRABELA', 'JANUARIA'),
(17, 'PEDRAS DE MARIA DA CRUZ', 'JANUARIA'),
(18, 'BURITIZEIRO', 'PIRAPORA'),
(19, 'PIRAPORA', 'PIRAPORA'),
(20, 'SAO JOAO DA PONTE', 'SAO JOAO DA PONTE'),
(21, 'TAIOBEIRAS', 'TAIOBEIRAS'),
(22, 'IBIAI', 'VARZEA DA PALMA'),
(23, 'JEQUITAI', 'VARZEA DA PALMA'),
(24, 'LASSANCE', 'VARZEA DA PALMA'),
(25, 'PONTO CHIQUE', 'VARZEA DA PALMA'),
(26, 'VARZEA DA PALMA', 'VARZEA DA PALMA'),
(27, 'Vargem Grande Do Rio Pardo', 'ESPINOSA'),
(28, 'Montezuma', 'MANGA'),
(29, 'Espinosa', 'ESPINOSA');

-- --------------------------------------------------------

--
-- Estrutura para tabela `base_rotas_bairros`
--
-- Criação: 24/01/2025 às 23:10
-- Última atualização: 24/01/2025 às 23:31
--

DROP TABLE IF EXISTS `base_rotas_bairros`;
CREATE TABLE `base_rotas_bairros` (
  `id` smallint(4) UNSIGNED NOT NULL,
  `cidade` varchar(25) DEFAULT NULL,
  `bairro` varchar(25) DEFAULT NULL,
  `rota` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `base_rotas_bairros`
--

INSERT DELAYED INTO `base_rotas_bairros` (`id`, `cidade`, `bairro`, `rota`) VALUES
(1, 'MONTES CLAROS', 'ZONA RURAL', 'BOCAIUVA'),
(2, 'MONTES CLAROS', 'PLANALTO RURAL', 'BOCAIUVA'),
(3, 'MONTES CLAROS', 'BARCELONA PARQUE', 'CENTRO-1'),
(4, 'MONTES CLAROS', 'BELA VISTA', 'CENTRO-1'),
(5, 'MONTES CLAROS', 'CIDADE INDUSTRIAL', 'CENTRO-1'),
(6, 'MONTES CLAROS', 'CONJ.HABITAC.VITORIA II', 'CENTRO-1'),
(7, 'MONTES CLAROS', 'CONJ HABITAC VITORIA II', 'CENTRO-1'),
(8, 'MONTES CLAROS', 'JARDIM PANORAMA', 'CENTRO-1'),
(9, 'MONTES CLAROS', 'CONJ PANORAMA II', 'CENTRO-1'),
(10, 'MONTES CLAROS', 'CONJUNTO PANORAMA II', 'CENTRO-1'),
(11, 'MONTES CLAROS', 'DISTRITO INDUSTRIAL', 'CENTRO-1'),
(12, 'MONTES CLAROS', 'EDGAR PEREIRA', 'CENTRO-1'),
(13, 'MONTES CLAROS', 'ELDORADO', 'CENTRO-1'),
(14, 'MONTES CLAROS', 'JARDIM BRASIL', 'CENTRO-1'),
(15, 'MONTES CLAROS', 'DISTRITO-NOVA ESPERANCA', 'CENTRO-1'),
(16, 'MONTES CLAROS', 'NOVA ESPERANCA', 'CENTRO-1'),
(17, 'MONTES CLAROS', 'NOVA MORADA', 'CENTRO-1'),
(18, 'MONTES CLAROS', 'RESIDENCIAL VITORIA', 'CENTRO-1'),
(19, 'MONTES CLAROS', 'RIO CEDRO', 'CENTRO-1'),
(20, 'MONTES CLAROS', 'SANTA EUGENIA', 'CENTRO-1'),
(21, 'MONTES CLAROS', 'SANTA ROSA DE LIMA', 'CENTRO-1'),
(22, 'MONTES CLAROS', 'SANTOS REIS', 'CENTRO-1'),
(23, 'MONTES CLAROS', 'VILA ANTONIO NARCISO', 'CENTRO-1'),
(24, 'MONTES CLAROS', 'VILA ATLANTIDA', 'CENTRO-1'),
(25, 'MONTES CLAROS', 'VILA AUREA', 'CENTRO-1'),
(26, 'MONTES CLAROS', 'VILA BRASILIA', 'CENTRO-1'),
(27, 'MONTES CLAROS', 'VILA CASTELO BRANCO', 'CENTRO-1'),
(28, 'MONTES CLAROS', 'VILA PRODACON', 'CENTRO-1'),
(29, 'MONTES CLAROS', 'VILA SANTA EUGENIA', 'CENTRO-1'),
(30, 'MONTES CLAROS', 'VILA TONCHEF', 'CENTRO-1'),
(31, 'MONTES CLAROS', 'ALICE MAIA', 'CENTRO-2'),
(32, 'MONTES CLAROS', 'ALTO SAO JOAO', 'CENTRO-2'),
(33, 'MONTES CLAROS', 'CENTRO', 'CENTRO-2'),
(34, 'MONTES CLAROS', 'FUNCIONARIOS', 'CENTRO-2'),
(35, 'MONTES CLAROS', 'JARDIM SAO LUIZ', 'CENTRO-2'),
(36, 'MONTES CLAROS', 'JOAO GORDO', 'CENTRO-2'),
(37, 'MONTES CLAROS', 'LOURDES', 'CENTRO-2'),
(38, 'MONTES CLAROS', 'MELO', 'CENTRO-2'),
(39, 'MONTES CLAROS', 'ROXO VERDE', 'CENTRO-2'),
(40, 'MONTES CLAROS', 'SAO JOSE', 'CENTRO-2'),
(41, 'MONTES CLAROS', 'TODOS OS SANTOS', 'CENTRO-2'),
(42, 'MONTES CLAROS', 'VILA MAURICEIA', 'CENTRO-2'),
(43, 'MONTES CLAROS', 'VILA OLIVEIRA', 'CENTRO-2'),
(44, 'MONTES CLAROS', 'VILA REGINA', 'CENTRO-2'),
(45, 'MONTES CLAROS', 'VILA EXPOSICAO', 'CENTRO-2'),
(46, 'MONTES CLAROS', 'VILA SANTA MAIRA', 'CENTRO-2'),
(47, 'MONTES CLAROS', 'ACACIAS', 'DELFINO-1'),
(48, 'MONTES CLAROS', 'ALCIDES RABELO', 'DELFINO-1'),
(49, 'MONTES CLAROS', 'ALTO FLORESTA', 'DELFINO-1'),
(50, 'MONTES CLAROS', 'BELVEDERE', 'DELFINO-1'),
(51, 'MONTES CLAROS', 'CARMELO', 'DELFINO-1'),
(52, 'MONTES CLAROS', 'CONJUNTO JK', 'DELFINO-1'),
(53, 'MONTES CLAROS', 'Conjunto Residencial Jk', 'DELFINO-1'),
(54, 'MONTES CLAROS', 'ESPLANADA', 'DELFINO-1'),
(55, 'MONTES CLAROS', 'GUARUJA', 'DELFINO-1'),
(56, 'MONTES CLAROS', 'INDEPENDENCIA', 'DELFINO-1'),
(57, 'MONTES CLAROS', 'INTERLAGOS', 'DELFINO-1'),
(58, 'MONTES CLAROS', 'JARAGUA I', 'DELFINO-1'),
(59, 'MONTES CLAROS', 'JARAGUA', 'DELFINO-1'),
(60, 'MONTES CLAROS', 'JARDIM ALEGRE', 'DELFINO-1'),
(61, 'MONTES CLAROS', 'JARDIM PRIMAVERA', 'DELFINO-1'),
(62, 'MONTES CLAROS', 'LOTEAMENTO NOVO JARAGUA', 'DELFINO-1'),
(63, 'MONTES CLAROS', 'LOTEAMENTO RECANTO DAS AG', 'DELFINO-1'),
(64, 'MONTES CLAROS', 'MONTE CARMELO', 'DELFINO-1'),
(65, 'MONTES CLAROS', 'MONTE SIAO', 'DELFINO-1'),
(66, 'MONTES CLAROS', 'MONTE SIAO IV', 'DELFINO-1'),
(67, 'MONTES CLAROS', 'NOVO JARAGUA', 'DELFINO-1'),
(68, 'MONTES CLAROS', 'PLANALTO', 'DELFINO-1'),
(69, 'MONTES CLAROS', 'PORTAL DOS IPES', 'DELFINO-1'),
(70, 'MONTES CLAROS', 'RENASCENCA', 'DELFINO-1'),
(71, 'MONTES CLAROS', 'RESIDENCIAL SUL IPES', 'DELFINO-1'),
(72, 'MONTES CLAROS', 'RESIDENCIAL MONTE SIAO', 'DELFINO-1'),
(73, 'MONTES CLAROS', 'RESIDIDENCIAL MINAS GERAI', 'DELFINO-1'),
(74, 'MONTES CLAROS', 'SANTA LUCIA', 'DELFINO-1'),
(75, 'MONTES CLAROS', 'SANTOS DUMONT', 'DELFINO-1'),
(76, 'MONTES CLAROS', 'SAO BENTO', 'DELFINO-1'),
(77, 'MONTES CLAROS', 'UNIVERSITARIO', 'DELFINO-1'),
(78, 'MONTES CLAROS', 'VENEZA PARQUE', 'DELFINO-1'),
(79, 'MONTES CLAROS', 'VILAGE DO LAGO', 'DELFINO-1'),
(80, 'MONTES CLAROS', 'VILLAGE DO LAGO I', 'DELFINO-1'),
(81, 'MONTES CLAROS', 'ALTO DA BOA VISTA', 'DELFINO-2'),
(82, 'MONTES CLAROS', 'ANTONIO PIMENTA', 'DELFINO-2'),
(83, 'MONTES CLAROS', 'CAMILO PRATES', 'DELFINO-2'),
(84, 'MONTES CLAROS', 'CINTRA', 'DELFINO-2'),
(85, 'MONTES CLAROS', 'CINTRA / DELFINO MAGALHAE', 'DELFINO-2'),
(86, 'MONTES CLAROS', 'DELFINO MAGALHAES', 'DELFINO-2'),
(87, 'MONTES CLAROS', 'NOVO DELFINO', 'DELFINO-2'),
(88, 'MONTES CLAROS', 'JARDIM PALMEIRAS', 'DELFINO-2'),
(90, 'MONTES CLAROS', 'major prates', 'major-1');

-- --------------------------------------------------------

--
-- Estrutura para tabela `caminhoes`
--
-- Criação: 25/01/2025 às 00:14
--

DROP TABLE IF EXISTS `caminhoes`;
CREATE TABLE `caminhoes` (
  `placa` char(8) NOT NULL,
  `modelo` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `caminhoes`
--

INSERT DELAYED INTO `caminhoes` (`placa`, `modelo`) VALUES
('MWZ8I16', '3'),
('PUM3D15', '3');

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--
-- Criação: 25/01/2025 às 00:14
-- Última atualização: 25/01/2025 às 02:47
--

DROP TABLE IF EXISTS `clientes`;
CREATE TABLE `clientes` (
  `CNPJ` varchar(14) NOT NULL,
  `nome` varchar(40) NOT NULL,
  `rua` varchar(30) NOT NULL,
  `numero` int(6) NOT NULL,
  `bairro` varchar(50) NOT NULL,
  `cidade` varchar(50) NOT NULL,
  `tipo` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cruzeiro_notas`
--
-- Criação: 25/01/2025 às 00:15
--

DROP TABLE IF EXISTS `cruzeiro_notas`;
CREATE TABLE `cruzeiro_notas` (
  `fk_notas_n_nota` varchar(14) NOT NULL,
  `peso_liquido` decimal(9,2) NOT NULL,
  `sequencia` int(4) NOT NULL,
  `Carga` int(8) DEFAULT NULL,
  `n_caixas` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `dellys_notas`
--
-- Criação: 25/01/2025 às 00:15
-- Última atualização: 25/01/2025 às 01:57
--

DROP TABLE IF EXISTS `dellys_notas`;
CREATE TABLE `dellys_notas` (
  `nf` int(10) NOT NULL,
  `carregamento` int(10) NOT NULL,
  `rca` int(5) NOT NULL,
  `codCliente` int(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `monitoramento`
--
-- Criação: 25/01/2025 às 00:12
-- Última atualização: 25/01/2025 às 02:47
--

DROP TABLE IF EXISTS `monitoramento`;
CREATE TABLE `monitoramento` (
  `Id` smallint(6) UNSIGNED NOT NULL,
  `controle` smallint(6) DEFAULT NULL,
  `largada` date DEFAULT NULL,
  `lead_time` tinyint(1) DEFAULT NULL,
  `status` bit(1) DEFAULT NULL,
  `data_finalizacao` date DEFAULT NULL,
  `placa_caminhao` char(8) DEFAULT NULL,
  `cpf_motorista` char(11) DEFAULT NULL,
  `Observacoes` text DEFAULT NULL,
  `finalizada` varchar(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `motorista`
--
-- Criação: 24/01/2025 às 23:52
-- Última atualização: 25/01/2025 às 00:10
--

DROP TABLE IF EXISTS `motorista`;
CREATE TABLE `motorista` (
  `nome` varchar(20) NOT NULL,
  `CPF_motorista` char(11) NOT NULL,
  `num_habilitacao` int(11) NOT NULL,
  `venci_habilitacao` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `motorista`
--

INSERT DELAYED INTO `motorista` (`nome`, `CPF_motorista`, `num_habilitacao`, `venci_habilitacao`) VALUES
('Cristhian Daniel', '11381756689', 58756264, '2028-07-05'),
('jaime jair rocha', '56434383649', 25487469, '2027-11-24');

-- --------------------------------------------------------

--
-- Estrutura para tabela `motorista_caminhoes`
--
-- Criação: 24/01/2025 às 23:55
-- Última atualização: 25/01/2025 às 00:10
--

DROP TABLE IF EXISTS `motorista_caminhoes`;
CREATE TABLE `motorista_caminhoes` (
  `fk_placa` char(8) NOT NULL,
  `fk_cpf_motorista` char(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `motorista_caminhoes`
--

INSERT DELAYED INTO `motorista_caminhoes` (`fk_placa`, `fk_cpf_motorista`) VALUES
('MWZ8I16', '56434383649'),
('PUM3D15', '11381756689');

-- --------------------------------------------------------

--
-- Estrutura para tabela `notas`
--
-- Criação: 25/01/2025 às 00:13
-- Última atualização: 25/01/2025 às 02:47
--

DROP TABLE IF EXISTS `notas`;
CREATE TABLE `notas` (
  `CNPJ` varchar(14) DEFAULT NULL,
  `n_nota` varchar(14) NOT NULL,
  `bairro` varchar(50) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `peso_bruto` decimal(9,2) NOT NULL,
  `Data_lancamento` date DEFAULT NULL,
  `Id_monitoramento` smallint(6) DEFAULT NULL,
  `fornecedor` varchar(20) NOT NULL,
  `rota` varchar(20) DEFAULT NULL,
  `valor_nota` double(9,2) NOT NULL,
  `reentrega` varchar(1) DEFAULT NULL,
  `disponivel` varchar(1) DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Acionadores `notas`
--
DROP TRIGGER IF EXISTS `before_insert_aurora`;
DELIMITER $$
CREATE TRIGGER `before_insert_aurora` BEFORE INSERT ON `notas` FOR EACH ROW BEGIN
    DECLARE rota_found VARCHAR(255);

    -- Quando for Montes Claros, buscar na tabela base_rotas_bairros pelo bairro
    IF NEW.cidade = 'MONTES CLAROS' THEN
        SELECT rota INTO rota_found
        FROM base_rotas_bairros
        WHERE bairro = NEW.bairro
        LIMIT 1;

        -- Definir a rota ou "ROTA NÃO ENCONTRADA"
        IF rota_found IS NOT NULL THEN
            SET NEW.rota = rota_found;
        ELSE
            SET NEW.rota = 'ROTA NÃO ENCONTRADA';
        END IF;

    -- Para outras cidades, buscar na tabela base_rotas pelo nome da cidade
    ELSE
        SELECT rota INTO rota_found
        FROM base_rotas
        WHERE cidade = NEW.cidade
        LIMIT 1;

        -- Definir a rota ou "ROTA NÃO ENCONTRADA"
        IF rota_found IS NOT NULL THEN
            SET NEW.rota = rota_found;
        ELSE
            SET NEW.rota = 'ROTA NÃO ENCONTRADA';
        END IF;
    END IF;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `before_insert_cruzeiro`;
DELIMITER $$
CREATE TRIGGER `before_insert_cruzeiro` BEFORE INSERT ON `notas` FOR EACH ROW BEGIN
    -- Verificar se a operação é "Plena" e o município está na lista correspondente
    IF NEW.fornecedor = 'Cruzeiro' AND NEW.cidade IN ('ALMENARA', 'ARAÇUAI', 'BERILO', 'CORONEL MURTA', 'CURRAL DE DENTRO', 'FRANCISCO BADARÓ', 'JENIPAPO DE MINAS', 'AGUA BOA', 'ANGELANDIA', 'CAPELINHA', 'CARBONITA', 'ITAMARANDIBA', 'JOSÉ GONÇALVES DE MINAS', 'JOSÉ RAYDAN', 'MINAS NOVAS', 'OLHOS DÁGUA', 'SANTA MARIA DO SUAÇUÍ', 'SÃO SEBASTIÃO DO MARANHÃO', 'TURMALINA', 'CORAÇÃO DE JESUS', 'LUISLÂNDIA', 'SÃO JOÃO DA LAGOA', 'AUGUSTO DE LIMA', 'BOCAIÚVA', 'BUENÓPOLIS', 'CORINTO', 'CURVELO', 'ENGENHEIRO NAVARRO', 'FRANCISCO DUMONT', 'GUARACIAMA', 'INIMUTABA', 'JOAQUIM FELÍCIO', 'MORRO DA GARÇA', 'COUTO DE MAGALHÃES DE MINAS', 'DIAMANTINA', 'GOUVEIA', 'JAÍBA', 'JUVENÍLIA', 'MANGA', 'MATIAS CARDOSO', 'MIRAVÂNIA', 'MONTALVÂNIA', 'CATUTI', 'ESPINOSA', 'GAMELEIRAS', 'JANAÚBA', 'MAMONAS', 'MATO VERDE', 'MONTE AZUL', 'MONTEZUMA', 'PAI PEDRO', 'PORTEIRINHA', 'SANTO ANTÔNIO DO RETIRO', 'SERRANÓPOLIS DE MINAS', 'BONITO DE MINAS', 'BRASÍLIA DE MINAS', 'CÔNEGO MARINHO', 'ITACARAMBI', 'JANUÁRIA', 'JAPONVAR', 'LONTRA', 'MIRABELA', 'PEDRAS DE MARIA DA CRUZ', 'SAO FRANCISCO', 'SÃO JOÃO DAS MISSÕES', 'CRISTÁLIA', 'GLAUCILÂNDIA', 'JURAMENTO', 'MONTES CLAROS', 'BURITIZEIRO', 'CLARO DOS POÇÕES', 'PIRAPORA', 'BERIZAL', 'FRANCISCO SÁ', 'NINHEIRA', 'PADRE CARVALHO', 'RIO PARDO DE MINAS', 'SALINAS', 'SÃO JOÃO DO PARAÍSO', 'TAIOBEIRAS', 'VIRGEM DA LAPA', 'IBIAÍ', 'JEQUITAÍ', 'LASSANCE', 'VÁRZEA DA PALMA', 'SÃO JOÃO DA PONTE', 'VARZELÂNDIA') THEN
        -- Atualizar o campo "rota" na tabela "notas" com a rota correspondente
        SET NEW.rota =
            CASE
                WHEN NEW.cidade IN ('ALMENARA') THEN 'ALMENARA'
                WHEN NEW.cidade IN ('ARAÇUAI', 'BERILO', 'CORONEL MURTA', 'CURRAL DE DENTRO', 'FRANCISCO BADARÓ', 'JENIPAPO DE MINAS') THEN 'ARAÇUAI'
                WHEN NEW.cidade IN ('AGUA BOA', 'ANGELANDIA', 'CAPELINHA', 'CARBONITA', 'ITAMARANDIBA', 'JOSÉ GONÇALVES DE MINAS', 'JOSÉ RAYDAN', 'MINAS NOVAS', 'OLHOS DÁGUA', 'SANTA MARIA DO SUAÇUÍ', 'SÃO SEBASTIÃO DO MARANHÃO', 'TURMALINA') THEN 'CAPELINHA'
                WHEN NEW.cidade IN ('CORAÇÃO DE JESUS', 'LUISLÂNDIA', 'SÃO JOÃO DA LAGOA') THEN 'CORACAO'
                WHEN NEW.cidade IN ('AUGUSTO DE LIMA', 'BOCAIÚVA', 'BUENÓPOLIS', 'CORINTO', 'CURVELO', 'ENGENHEIRO NAVARRO', 'FRANCISCO DUMONT', 'GUARACIAMA', 'INIMUTABA', 'JOAQUIM FELÍCIO', 'MORRO DA GARÇA') THEN 'CURVELO'
                WHEN NEW.cidade IN ('COUTO DE MAGALHÃES DE MINAS', 'DIAMANTINA', 'GOUVEIA') THEN 'DIAMANTINA'
                WHEN NEW.cidade IN ('JAÍBA', 'JUVENÍLIA', 'MANGA', 'MATIAS CARDOSO', 'MIRAVÂNIA', 'MONTALVÂNIA') THEN 'JAIBA'
                WHEN NEW.cidade IN ('CATUTI', 'ESPINOSA', 'GAMELEIRAS', 'JANAÚBA', 'MAMONAS', 'MATO VERDE', 'MONTE AZUL', 'MONTEZUMA', 'PAI PEDRO', 'PORTEIRINHA', 'SANTO ANTÔNIO DO RETIRO', 'SERRANÓPOLIS DE MINAS') THEN 'JANAUBA'
                WHEN NEW.cidade IN ('BONITO DE MINAS', 'BRASÍLIA DE MINAS', 'CÔNEGO MARINHO', 'ITACARAMBI', 'JANUÁRIA', 'JAPONVAR', 'LONTRA', 'MIRABELA', 'PEDRAS DE MARIA DA CRUZ', 'SAO FRANCISCO', 'SÃO JOÃO DAS MISSÕES') THEN 'JANUÁRIA'
		WHEN NEW.cidade IN ('CRISTÁLIA', 'GLAUCILÂNDIA', 'JURAMENTO') THEN 'MONTES CLAROS'
                WHEN NEW.cidade IN ('MONTES CLAROS') THEN 
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
                WHEN NEW.cidade IN ('BURITIZEIRO', 'CLARO DOS POÇÕES', 'PIRAPORA') THEN 'PIRAPORA'
                WHEN NEW.cidade IN ('BERIZAL', 'FRANCISCO SÁ', 'NINHEIRA', 'PADRE CARVALHO', 'RIO PARDO DE MINAS', 'SALINAS', 'SÃO JOÃO DO PARAÍSO', 'TAIOBEIRAS', 'VIRGEM DA LAPA') THEN 'TAIOBEIRAS'
                WHEN NEW.cidade IN ('IBIAÍ', 'JEQUITAÍ', 'LASSANCE', 'VÁRZEA DA PALMA') THEN  'VÁRZEA DA PALMA'
                WHEN NEW.cidade IN ('SÃO JOÃO DA PONTE', 'VARZELÂNDIA') THEN  'VARZELÂNDIA'
            END;
    END IF;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `before_insert_plena`;
DELIMITER $$
CREATE TRIGGER `before_insert_plena` BEFORE INSERT ON `notas` FOR EACH ROW BEGIN
    -- Verificar se a operação é "Plena" e o município está na lista correspondente
    IF NEW.fornecedor = 'Plena' AND NEW.cidade IN ('ARACUAI', 'FRANCISCO SA', 'BOCAIUVA', 'CAPELINHA', 'CARBONITA', 'ITAMARANDIBA', 'OLHOS-D''AGUA', 'TURMALINA', 'AUGUSTO DE LIMA', 'BUENOPOLIS', 'CORDISBURGO', 'CORINTO', 'CURVELO', 'PARAOPEBA', 'POMPEU', 'SANTO HIPOLITO', 'BRASILIA DE MINAS', 'CORACAO DE JESUS', 'LUISLANDIA', 'SAO FRANCISCO', 'COUTO DE MAGALHAES DE MINAS', 'DIAMANTINA', 'GOUVEIA', 'JAIBA', 'MONTALVANIA', 'ESPINOSA', 'JANAUBA', 'MATO VERDE', 'MONTE AZUL', 'PORTEIRINHA', 'BONITO DE MINAS', 'ITACARAMBI', 'JANUARIA', 'LONTRA', 'MIRABELA', 'BELO HORIZONTE', 'MONTES CLAROS', 'BURITIZEIRO', 'IBIAI', 'JEQUITAI', 'PIRAPORA', 'RIO PARDO DE MINAS', 'SALINAS', 'SAO JOAO DO PARAISO', 'TAIOBEIRAS', 'VARZEA DA PALMA') THEN
        -- Atualizar o campo "rota" na tabela "notas" com a rota correspondente
        SET NEW.rota =
            CASE
                WHEN NEW.cidade IN ('ARAÇUAI', 'FRANCISCO SA') THEN 'ARAÇUAI'
                WHEN NEW.cidade = 'BOCAIUVA' THEN 'BOCAIUVA'
                WHEN NEW.cidade IN ('CAPELINHA', 'CARBONITA', 'ITAMARANDIBA', 'OLHOS-D''AGUA', 'TURMALINA') THEN 'CAPELINHA'
                WHEN NEW.cidade IN ('AUGUSTO DE LIMA', 'BUENOPOLIS', 'CORDISBURGO', 'CORINTO', 'CURVELO', 'PARAOPEBA', 'POMPEU', 'SANTO HIPOLITO') THEN 'CARRO PLENA'
                WHEN NEW.cidade IN ('BRASILIA DE MINAS', 'CORACAO DE JESUS', 'LUISLANDIA', 'SAO FRANCISCO') THEN 'CORAÇÃO JESUS'
                WHEN NEW.cidade = 'COUTO DE MAGALHAES DE MINAS' THEN 'DIAMANTINA'
                WHEN NEW.cidade = 'GOUVEIA' THEN 'DIAMANTINA'
		WHEN NEW.cidade = 'DIAMANTINA' THEN 'DIAMANTINA'
                WHEN NEW.cidade IN ('JAIBA', 'MONTALVANIA') THEN 'JAIBA'
                WHEN NEW.cidade IN ('ESPINOSA', 'JANAUBA', 'MATO VERDE', 'MONTE AZUL', 'PORTEIRINHA') THEN 'JANAUBA'
                WHEN NEW.cidade IN ('BONITO DE MINAS', 'ITACARAMBI', 'JANUARIA', 'LONTRA', 'MIRABELA') THEN 'JANUARIA'
                WHEN NEW.cidade IN ('BELO HORIZONTE', 'MONTES CLAROS') THEN 
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
                WHEN NEW.cidade IN ('BURITIZEIRO', 'IBIAI', 'JEQUITAI', 'PIRAPORA') THEN 'PIRAPORA'
                WHEN NEW.cidade = 'VARZEA DA PALMA' THEN 'VARZEA DA PALMA'
                WHEN NEW.cidade IN ('RIO PARDO DE MINAS', 'SALINAS', 'SAO JOAO DO PARAISO', 'TAIOBEIRAS') THEN 'TAIOBEIRAS'
            END;
    END IF;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `before_insert_suinco`;
DELIMITER $$
CREATE TRIGGER `before_insert_suinco` BEFORE INSERT ON `notas` FOR EACH ROW BEGIN
    -- Verificar se a operação é "Plena" e o município está na lista correspondente
    IF NEW.fornecedor = 'Suinco' AND NEW.cidade IN ('Angelândia', 'Aricanduva', 'Capelinha', 'Chapada do Norte', 'Itamarandiba', 'Minas Novas', 'Olhos-d''Água', 'Turmalina', 'Veredinha', 'Augusto de Lima', 'Bocaiúva', 'Buenópolis', 'Corinto', 'Curvelo', 'Datas', 'Engenheiro Navarro', 'Francisco Dumont', 'Guaraciama', 'Inimutaba', 'Joaquim Felício', 'Morro da Garça', 'Couto de Magalhães de Min', 'Diamantina', 'Gouveia', 'São Gonçalo do Rio Preto', 'Jaíba', 'Juvenília', 'Manga', 'Matias Cardoso', 'Miravânia', 'Montalvânia', 'Verdelândia', 'Capitão Enéas', 'Catuti', 'Espinosa', 'Gameleiras', 'Janaúba', 'Mamonas', 'Mato Verde', 'Monte Azul', 'Montezuma', 'Pai Pedro', 'Porteirinha', 'Riacho dos Machados', 'Serranópolis de Minas', 'Bonito de Minas', 'Brasília de Minas', 'Campo Azul', 'Cônego Marinho', 'Coração de Jesus', 'Icaraí de Minas', 'Itacarambi', 'Januária', 'Japonvar', 'Lontra', 'Luislândia', 'Mirabela', 'Pedras de Maria da Cruz', 'Ponto Chique', 'São Francisco', 'São João da Lagoa', 'São João do Pacuí', 'São Romão', 'Ubaí', 'Glaucilândia', 'Itacambira', 'Juramento', 'Montes Claros', 'Buritizeiro', 'Claro dos Poções', 'Ibiaí', 'Jequitaí', 'Pirapora', 'Várzea da Palma', 'Ibiracatu', 'São João da Ponte', 'Varzelândia', 'Araçuaí', 'Berilo', 'Botumirim', 'Cristália', 'Francisco Sá', 'Grão Mogol', 'Indaiabira', 'Ninheira', 'Rio Pardo de Minas', 'Salinas', 'São João do Paraíso', 'Taiobeiras') THEN
        -- Atualizar o campo "rota" na tabela "notas" com a rota correspondente
        SET NEW.rota =
            CASE
                WHEN NEW.cidade IN ('Angelândia', 'Aricanduva', 'Capelinha', 'Chapada do Norte', 'Itamarandiba', 'Minas Novas', 'Olhos-d''Água', 'Turmalina', 'Veredinha') THEN 'Capelinha'
                WHEN NEW.cidade IN ('Augusto de Lima', 'Bocaiúva', 'Buenópolis', 'Corinto', 'Curvelo', 'Datas', 'Engenheiro Navarro', 'Francisco Dumont', 'Guaraciama', 'Inimutaba', 'Joaquim Felício', 'Morro da Garça') THEN 'Curvelo'
                WHEN NEW.cidade IN ('Couto de Magalhães de Min', 'Diamantina', 'Gouveia', 'São Gonçalo do Rio Preto') THEN 'Diamantina'
                WHEN NEW.cidade IN ('Jaíba', 'Juvenília', 'Manga', 'Matias Cardoso', 'Miravânia', 'Montalvânia', 'Verdelândia') THEN 'Jaíba'
                WHEN NEW.cidade IN ('Capitão Enéas', 'Catuti', 'Espinosa', 'Gameleiras', 'Janaúba', 'Mamonas', 'Mato Verde', 'Monte Azul', 'Montezuma', 'Pai Pedro', 'Porteirinha', 'Riacho dos Machados', 'Serranópolis de Minas') THEN 'Janaúba'
                WHEN NEW.cidade IN ('Bonito de Minas', 'Brasília de Minas', 'Campo Azul', 'Cônego Marinho', 'Coração de Jesus', 'Icaraí de Minas', 'Itacarambi', 'Januária', 'Japonvar', 'Lontra', 'Luislândia', 'Mirabela', 'Pedras de Maria da Cruz', 'Ponto Chique', 'São Francisco', 'São João da Lagoa', 'São João do Pacuí', 'São Romão', 'Ubaí') THEN 'Januária'
                WHEN NEW.cidade IN ('Glaucilândia', 'Itacambira', 'Juramento') THEN 'Montes Claros'
		WHEN NEW.cidade IN ( 'Montes Claros') THEN
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
                WHEN NEW.cidade IN ('Buritizeiro', 'Claro dos Poções', 'Ibiaí', 'Jequitaí', 'Pirapora', 'Várzea da Palma') THEN 'Pirapora'
                WHEN NEW.cidade IN ('Ibiracatu', 'São João da Ponte', 'Varzelândia') THEN 'São João da Ponte'
                WHEN NEW.cidade IN ('Araçuaí', 'Berilo', 'Botumirim', 'Cristália', 'Francisco Sá', 'Grão Mogol', 'Indaiabira', 'Ninheira', 'Rio Pardo de Minas', 'Salinas', 'São João do Paraíso', 'Taiobeiras') THEN 'Taiobeiras'
            END;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `plena_notas`
--
-- Criação: 25/01/2025 às 00:15
--

DROP TABLE IF EXISTS `plena_notas`;
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
-- Criação: 25/01/2025 às 00:15
-- Última atualização: 25/01/2025 às 02:47
--

DROP TABLE IF EXISTS `produtos`;
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
-- Criação: 25/01/2025 às 00:15
--

DROP TABLE IF EXISTS `redes`;
CREATE TABLE `redes` (
  `fk_notas_n_nota` varchar(14) NOT NULL,
  `fornecedor` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `suinco_notas`
--
-- Criação: 25/01/2025 às 00:15
--

DROP TABLE IF EXISTS `suinco_notas`;
CREATE TABLE `suinco_notas` (
  `fk_notas_n_nota` varchar(14) NOT NULL,
  `Carga` int(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipos_clientes`
--
-- Criação: 25/01/2025 às 00:15
--

DROP TABLE IF EXISTS `tipos_clientes`;
CREATE TABLE `tipos_clientes` (
  `CNPJ` varchar(14) NOT NULL,
  `tipo` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--
-- Criação: 25/01/2025 às 00:19
--

DROP TABLE IF EXISTS `usuarios`;
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
-- Despejando dados para a tabela `usuarios`
--

INSERT DELAYED INTO `usuarios` (`id`, `nome`, `email`, `usuario`, `senha`, `tipo`, `data`) VALUES
(1, 'admin', 'rootprofile@email.com', 'root', '202cb962ac59075b964b07152d234b70', '1', '2023-10-20'),
(4, 'Cristhian', 'cristhian.limarocha@gmail.com', 'Cristhian', 'Crisdlr44', '1', '2024-11-11');

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `visao_motoristascaminhoes`
-- (Veja abaixo para a visão atual)
--
DROP VIEW IF EXISTS `visao_motoristascaminhoes`;
CREATE TABLE `visao_motoristascaminhoes` (
`placa_caminhao` char(8)
,`modelo_caminhao` char(1)
,`nome_motorista` varchar(20)
,`cpf_motorista` char(11)
,`num_habilitacao` int(11)
,`venci_habilitacao` date
);

-- --------------------------------------------------------

--
-- Estrutura para view `visao_motoristascaminhoes`
--
DROP TABLE IF EXISTS `visao_motoristascaminhoes`;

DROP VIEW IF EXISTS `visao_motoristascaminhoes`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `visao_motoristascaminhoes`  AS SELECT `c`.`placa` AS `placa_caminhao`, `c`.`modelo` AS `modelo_caminhao`, `mt`.`nome` AS `nome_motorista`, `mt`.`CPF_motorista` AS `cpf_motorista`, `mt`.`num_habilitacao` AS `num_habilitacao`, `mt`.`venci_habilitacao` AS `venci_habilitacao` FROM ((`motorista_caminhoes` `m` join `caminhoes` `c`) join `motorista` `mt`) WHERE `m`.`fk_placa` = `c`.`placa` AND `m`.`fk_cpf_motorista` = `mt`.`CPF_motorista` ;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `anomalias`
--
ALTER TABLE `anomalias`
  ADD PRIMARY KEY (`cod`);

--
-- Índices de tabela `aurora_notas`
--
ALTER TABLE `aurora_notas`
  ADD PRIMARY KEY (`fk_notas_n_nota`);

--
-- Índices de tabela `base_rotas`
--
ALTER TABLE `base_rotas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `base_rotas_bairros`
--
ALTER TABLE `base_rotas_bairros`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `caminhoes`
--
ALTER TABLE `caminhoes`
  ADD PRIMARY KEY (`placa`);

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`CNPJ`);

--
-- Índices de tabela `cruzeiro_notas`
--
ALTER TABLE `cruzeiro_notas`
  ADD PRIMARY KEY (`fk_notas_n_nota`);

--
-- Índices de tabela `dellys_notas`
--
ALTER TABLE `dellys_notas`
  ADD PRIMARY KEY (`nf`);

--
-- Índices de tabela `monitoramento`
--
ALTER TABLE `monitoramento`
  ADD PRIMARY KEY (`Id`);

--
-- Índices de tabela `motorista`
--
ALTER TABLE `motorista`
  ADD PRIMARY KEY (`CPF_motorista`);

--
-- Índices de tabela `motorista_caminhoes`
--
ALTER TABLE `motorista_caminhoes`
  ADD PRIMARY KEY (`fk_placa`,`fk_cpf_motorista`);

--
-- Índices de tabela `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`n_nota`);

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
  ADD PRIMARY KEY (`fk_notas_n_nota`,`fornecedor`);

--
-- Índices de tabela `suinco_notas`
--
ALTER TABLE `suinco_notas`
  ADD PRIMARY KEY (`fk_notas_n_nota`);

--
-- Índices de tabela `tipos_clientes`
--
ALTER TABLE `tipos_clientes`
  ADD PRIMARY KEY (`CNPJ`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `anomalias`
--
ALTER TABLE `anomalias`
  MODIFY `cod` smallint(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `base_rotas`
--
ALTER TABLE `base_rotas`
  MODIFY `id` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de tabela `base_rotas_bairros`
--
ALTER TABLE `base_rotas_bairros`
  MODIFY `id` smallint(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT de tabela `monitoramento`
--
ALTER TABLE `monitoramento`
  MODIFY `Id` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
