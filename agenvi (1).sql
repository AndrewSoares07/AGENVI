-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 30/11/2024 às 21:05
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
-- Banco de dados: `agenvi`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `admin_agenvi`
--

CREATE TABLE `admin_agenvi` (
  `idadmin` int(11) NOT NULL,
  `nome` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `senha` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `admin_agenvi`
--

INSERT INTO `admin_agenvi` (`idadmin`, `nome`, `email`, `senha`) VALUES
(1, 'ADM', 'adminagenvi@gmail.com', '123456');

-- --------------------------------------------------------

--
-- Estrutura para tabela `agendamento`
--

CREATE TABLE `agendamento` (
  `idagendamento` int(11) NOT NULL,
  `idcliente` int(11) NOT NULL,
  `idempresa` int(11) NOT NULL,
  `idservico` int(11) NOT NULL,
  `idfuncionario` int(11) NOT NULL,
  `status` enum('em andamento','finalizado','cancelado','') NOT NULL,
  `dt_agendamento` date NOT NULL,
  `horario_ini` time NOT NULL,
  `horario_fim` time NOT NULL,
  `dtsemana` varchar(25) NOT NULL,
  `preco_ad` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `agendamento`
--

INSERT INTO `agendamento` (`idagendamento`, `idcliente`, `idempresa`, `idservico`, `idfuncionario`, `status`, `dt_agendamento`, `horario_ini`, `horario_fim`, `dtsemana`, `preco_ad`) VALUES
(316, 19, 263, 98, 131, 'em andamento', '2024-12-03', '08:45:00', '09:20:00', 'ter', 25.00),
(317, 19, 263, 99, 133, 'em andamento', '2024-12-04', '17:00:00', '17:40:00', 'qua', 25.00),
(318, 19, 263, 104, 132, 'em andamento', '2024-12-07', '09:00:00', '11:00:00', 'sab', 35.00),
(319, 20, 264, 109, 135, 'em andamento', '2024-12-05', '11:00:00', '12:00:00', 'qui', 120.00),
(320, 20, 264, 113, 141, 'em andamento', '2024-12-06', '15:00:00', '15:40:00', 'sex', 40.00),
(321, 20, 264, 113, 141, 'cancelado', '2024-12-06', '15:00:00', '15:40:00', 'sex', 40.00),
(322, 20, 262, 95, 127, 'finalizado', '2024-12-06', '16:20:00', '17:00:00', 'sex', 50.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `agendamento_formpg`
--

CREATE TABLE `agendamento_formpg` (
  `idform_pg` int(11) NOT NULL,
  `idpagamento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avaliacao`
--

CREATE TABLE `avaliacao` (
  `idavaliacao` int(11) NOT NULL,
  `idcliente` int(11) NOT NULL,
  `idempresa` int(11) NOT NULL,
  `estrelas` enum('1','2','3','4','5') DEFAULT NULL,
  `chat` varchar(200) DEFAULT NULL,
  `data_coment` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `avaliacao`
--

INSERT INTO `avaliacao` (`idavaliacao`, `idcliente`, `idempresa`, `estrelas`, `chat`, `data_coment`) VALUES
(62, 19, 263, '5', 'Meu reflexo aí ficou mídia pprt', '2024-11-30'),
(63, 19, 263, '5', 'Tropa aqui  deixa na risca', '2024-11-30'),
(64, 20, 262, '4', 'Fiz minha sobrancelha aqui, adorei', '2024-11-30'),
(65, 20, 262, '5', 'Muito bom o serviço meninas parabéns ❤️', '2024-11-30'),
(66, 20, 264, '4', 'Minha progressiva esta linda muito obrigado meninas 😘😘', '2024-11-30');

-- --------------------------------------------------------

--
-- Estrutura para tabela `banido`
--

CREATE TABLE `banido` (
  `idcliente` int(11) NOT NULL,
  `idempresa` int(11) NOT NULL,
  `data_bani` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE `cliente` (
  `idcliente` int(11) NOT NULL,
  `nome_cliente` varchar(40) DEFAULT NULL,
  `genero` enum('F','M','NI') DEFAULT NULL,
  `data_nasc` date DEFAULT NULL,
  `cep` varchar(9) NOT NULL,
  `telefone` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `senha` text NOT NULL,
  `foto_perfil` varchar(50) NOT NULL,
  `foto_back` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cliente`
--

INSERT INTO `cliente` (`idcliente`, `nome_cliente`, `genero`, `data_nasc`, `cep`, `telefone`, `email`, `senha`, `foto_perfil`, `foto_back`) VALUES
(19, 'Joao Victor', 'M', '2006-08-30', '21620-210', '(21) 97362-8323', 'joao@gmail.com', '$2y$10$kQkp4WoGzqTMH5CruqAZ0uHP/jdS.qYANzL.cir3PbD35McUiL2ha', 'perfil-19-1732978161.png', ''),
(20, 'Beatriz', 'F', '2000-01-03', '21620-210', '(21) 97362-8323', 'bia@gmail.com', '$2y$10$XKAsXlzPpQTUyLPaNJXKE.uNI2/hoZsQe2oKzIF/m1ZKQIgN.eiOG', 'perfil-20-1732984560.png', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `curtidas`
--

CREATE TABLE `curtidas` (
  `idcurti` int(11) NOT NULL,
  `idcliente` int(11) NOT NULL,
  `idavaliacao` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresa`
--

CREATE TABLE `empresa` (
  `idempresa` int(11) NOT NULL,
  `nivel` enum('1','2','3') NOT NULL,
  `nome` varchar(20) DEFAULT NULL,
  `nome_fantasia` varchar(20) DEFAULT NULL,
  `tipo` enum('barbearia','advocacia','clinica','salaobeleza') NOT NULL,
  `CNPJ_CPF` varchar(20) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `cep` varchar(12) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `senha` varchar(100) DEFAULT NULL,
  `foto_empresa` varchar(50) DEFAULT NULL,
  `foto_amb1` varchar(50) NOT NULL,
  `foto_amb2` varchar(50) NOT NULL,
  `foto_amb3` varchar(50) NOT NULL,
  `foto_amb4` varchar(50) NOT NULL,
  `foto_amb5` varchar(50) NOT NULL,
  `foto_amb6` varchar(50) NOT NULL,
  `complemento` text NOT NULL,
  `data_inicio` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `empresa`
--

INSERT INTO `empresa` (`idempresa`, `nivel`, `nome`, `nome_fantasia`, `tipo`, `CNPJ_CPF`, `telefone`, `cep`, `numero`, `email`, `senha`, `foto_empresa`, `foto_amb1`, `foto_amb2`, `foto_amb3`, `foto_amb4`, `foto_amb5`, `foto_amb6`, `complemento`, `data_inicio`) VALUES
(262, '1', 'Lyandra Roberta', 'Lyandra Sobrancelhas', 'salaobeleza', '123.123.123-23', '(21) 98762-4356', '21620-210', '315', 'lyly@gmail.com', '$2y$10$Li5FUf6S6GaRsZMSic0ZJOftWY5/uCfL7MOA7BtT5/LfFlqd9oW6y', 'empresa-262.png', 'foto_amb1-262.png', 'foto_amb2-262.png', 'foto_amb3-262.png', 'foto_amb4-262.png', 'foto_amb5-262.png', 'foto_amb6-262.png', 'Design de sobrancelha, agende o seu horário!!', '2024-11-30'),
(263, '1', 'João Victor', 'Barbearia Jotta', 'barbearia', '186.474.287-90', '(21) 97362-8323', '21620-210', '315', 'jotta@gmail.com', '$2y$10$eQ4aaJPpRO.5nB2XGSD2DuTa7N2rVvaxtfP65QRAEnRD7fuS0bJxi', 'empresa-263.png', 'foto_amb1-263.jpg', 'foto_amb2-263.jpg', 'foto_amb3-263.jpg', 'foto_amb4-263.jpg', 'foto_amb5-263.jpg', 'foto_amb6-263.jpg', 'Espaço dos crias', '2024-11-30'),
(264, '1', 'Rafaela de Freitas', 'Rafa cachos', 'salaobeleza', '18.647.422/222222-22', '(21) 89000-0033', '21610-330', '300', 'rafa@gmail.com', '$2y$10$nKVo2CjzbQOaMmpjtERWmOr1hGraehRAa0ywpuokeojEcXlTfdZ0a', 'empresa-264.jpg', 'foto_amb1-264.jpg', 'foto_amb2-264.jpg', 'foto_amb3-264.jpg', 'foto_amb4-264.jpg', 'foto_amb5-264.jpg', 'foto_amb6-264.jpg', 'Espaço para mulheres de todas as idades', '2024-11-30');

-- --------------------------------------------------------

--
-- Estrutura para tabela `favoritos`
--

CREATE TABLE `favoritos` (
  `idcliente` int(11) NOT NULL,
  `idempresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `favoritos`
--

INSERT INTO `favoritos` (`idcliente`, `idempresa`) VALUES
(19, 262),
(19, 263),
(20, 262),
(20, 264);

-- --------------------------------------------------------

--
-- Estrutura para tabela `forma_pagamento`
--

CREATE TABLE `forma_pagamento` (
  `idform_pg` int(11) NOT NULL,
  `forma_pag` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionario`
--

CREATE TABLE `funcionario` (
  `idfuncionario` int(11) NOT NULL,
  `nome_func` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `dt_nasc` date NOT NULL,
  `cel` varchar(20) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `status` enum('ativo','inativo') NOT NULL,
  `foto_func` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `funcionario`
--

INSERT INTO `funcionario` (`idfuncionario`, `nome_func`, `email`, `dt_nasc`, `cel`, `cpf`, `status`, `foto_func`) VALUES
(126, 'Karolina', 'karol@gmail.com', '2000-11-23', '(21) 99999-9999', '123.456.678-90', 'ativo', 'foto-func-126-262.png'),
(127, 'Lyandra', 'lyandra@gmail.com', '2000-11-23', '(21) 90876-5432', '152.863.765-65', 'ativo', 'foto-func-127-262.png'),
(128, 'Luiza', 'luiza@gmail.com', '2000-11-02', '(21) 34343-4343', '186.473.333-33', 'ativo', 'foto-func-128-262.png'),
(129, 'Jhulia', 'Jhulia@gmail.com', '2005-04-25', '(21) 45454-5454', '454.545.454-54', 'ativo', 'foto-func-129-262.png'),
(130, 'João Victor', 'jhonvt06@gmail.com', '2006-08-30', '(21) 65656-6565', '786.765.656-75', 'ativo', 'foto-func-130-263.png'),
(131, 'Welithon', 'welitin@gmail.com', '2004-02-24', '(21) 23232-3232', '232.323.232-32', 'ativo', 'foto-func-131-263.png'),
(132, 'Pedro', 'ph10@gmail.com', '0200-10-08', '(21) 23343-4343', '232.323.232-32', 'ativo', 'foto-func-132-263.png'),
(133, 'Yan', 'yan@gmail.com', '0200-07-09', '(21) 12121-2121', '123.321.123-45', 'ativo', 'foto-func-133-263.png'),
(134, 'Rafaela', 'rafabangu@gmail.com', '2000-02-10', '(21) 34343-4334', '123.456.889-89', 'ativo', 'foto-func-134-264.png'),
(135, 'Madu', 'maduu@gmail.com', '0200-11-22', '(21) 89898-9899', '123.456.789-90', 'ativo', 'foto-func-135-264.png'),
(136, 'Karine', 'karine@gmail.com', '2000-03-03', '(21) 89899-8989', '123.321.234-56', 'ativo', 'foto-func-136-264.png'),
(137, 'Fernanda', 'fernanda@gmail.com', '2000-07-27', '(21) 90909-0909', '234.545.454-54', 'ativo', 'foto-func-137-264.png'),
(138, 'Anna', 'japa@gmail.com', '2000-04-02', '(21) 21212-1212', '127.878.787-87', 'ativo', 'foto-func-138-264.png'),
(139, 'Rafaella', 'rafaredonda@gmail.com', '2000-05-04', '(21) 99492-9186', '898.988.899-89', 'ativo', 'foto-func-139-264.png'),
(140, 'Vitória', 'vivi@gmail.com', '2000-07-01', '(21) 54545-4545', '545.454.545-45', 'ativo', 'foto-func-140-264.png'),
(141, 'Carol', 'carola@gmail.com', '2024-11-22', '(21) 87878-7878', '767.676.767-67', 'ativo', 'foto-func-141-264.png');

-- --------------------------------------------------------

--
-- Estrutura para tabela `guard_img`
--

CREATE TABLE `guard_img` (
  `perfil_img` varchar(100) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `horarios_empresa`
--

CREATE TABLE `horarios_empresa` (
  `idempresa` int(11) NOT NULL,
  `horario_ini` time DEFAULT NULL,
  `dias_semana` varchar(25) NOT NULL,
  `horario_fim` time NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `horarios_empresa`
--

INSERT INTO `horarios_empresa` (`idempresa`, `horario_ini`, `dias_semana`, `horario_fim`, `id`) VALUES
(262, '09:00:00', 'ter', '18:00:00', 854),
(262, '09:00:00', 'qua', '18:00:00', 855),
(262, '09:00:00', 'qui', '18:00:00', 856),
(262, '09:00:00', 'sex', '18:00:00', 857),
(262, '09:00:00', 'sab', '18:00:00', 858),
(263, '07:00:00', 'ter', '18:00:00', 864),
(263, '07:00:00', 'qua', '18:00:00', 865),
(263, '07:00:00', 'qui', '18:00:00', 866),
(263, '07:00:00', 'sex', '18:00:00', 867),
(263, '07:00:00', 'sab', '12:00:00', 868),
(264, '07:00:00', 'seg', '18:00:00', 874),
(264, '07:00:00', 'ter', '18:00:00', 875),
(264, '07:00:00', 'qua', '18:00:00', 876),
(264, '07:00:00', 'qui', '18:00:00', 877),
(264, '07:00:00', 'sex', '18:00:00', 878),
(264, '07:00:00', 'sab', '14:00:00', 879);

-- --------------------------------------------------------

--
-- Estrutura para tabela `horario_func`
--

CREATE TABLE `horario_func` (
  `idfuncionario` int(11) NOT NULL,
  `dia_semana` varchar(100) DEFAULT NULL,
  `horario_ini` time DEFAULT NULL,
  `horario_fim` time DEFAULT NULL,
  `id_hr` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `horario_func`
--

INSERT INTO `horario_func` (`idfuncionario`, `dia_semana`, `horario_ini`, `horario_fim`, `id_hr`) VALUES
(129, 'ter', '09:00:00', '18:00:00', 1562),
(129, 'qua', '09:00:00', '18:00:00', 1563),
(129, 'qui', '09:00:00', '18:00:00', 1564),
(129, 'sex', '09:00:00', '18:00:00', 1565),
(129, 'sab', '09:00:00', '18:00:00', 1566),
(126, 'ter', '09:00:00', '18:00:00', 1567),
(126, 'qua', '09:00:00', '18:00:00', 1568),
(126, 'qui', '09:00:00', '18:00:00', 1569),
(126, 'sex', '09:00:00', '18:00:00', 1570),
(126, 'sab', '09:00:00', '18:00:00', 1571),
(127, 'ter', '09:00:00', '18:00:00', 1572),
(127, 'qua', '09:00:00', '18:00:00', 1573),
(127, 'qui', '09:00:00', '18:00:00', 1574),
(127, 'sex', '09:00:00', '18:00:00', 1575),
(127, 'sab', '09:00:00', '18:00:00', 1576),
(128, 'ter', '09:00:00', '18:00:00', 1577),
(128, 'qua', '09:00:00', '18:00:00', 1578),
(128, 'qui', '09:00:00', '18:00:00', 1579),
(128, 'sex', '09:00:00', '18:00:00', 1580),
(128, 'sab', '09:00:00', '18:00:00', 1581),
(130, 'ter', '07:00:00', '18:00:00', 1582),
(130, 'qua', '07:00:00', '18:00:00', 1583),
(130, 'qui', '07:00:00', '18:00:00', 1584),
(130, 'sex', '07:00:00', '18:00:00', 1585),
(130, 'sab', '07:00:00', '12:00:00', 1586),
(131, 'ter', '07:00:00', '18:00:00', 1587),
(131, 'qua', '07:00:00', '18:00:00', 1588),
(131, 'qui', '07:00:00', '18:00:00', 1589),
(131, 'sex', '07:00:00', '18:00:00', 1590),
(131, 'sab', '07:00:00', '12:00:00', 1591),
(132, 'ter', '07:00:00', '18:00:00', 1592),
(132, 'qua', '07:00:00', '18:00:00', 1593),
(132, 'qui', '07:00:00', '18:00:00', 1594),
(132, 'sex', '07:00:00', '18:00:00', 1595),
(132, 'sab', '07:00:00', '12:00:00', 1596),
(133, 'ter', '07:00:00', '18:00:00', 1597),
(133, 'qua', '07:00:00', '18:00:00', 1598),
(133, 'qui', '07:00:00', '18:00:00', 1599),
(133, 'sex', '07:00:00', '18:00:00', 1600),
(133, 'sab', '07:00:00', '12:00:00', 1601),
(134, 'seg', '07:00:00', '18:00:00', 1602),
(134, 'ter', '07:00:00', '18:00:00', 1603),
(134, 'qua', '07:00:00', '18:00:00', 1604),
(134, 'qui', '07:00:00', '18:00:00', 1605),
(134, 'sex', '07:00:00', '18:00:00', 1606),
(134, 'sab', '07:00:00', '14:00:00', 1607),
(135, 'seg', '07:00:00', '18:00:00', 1608),
(135, 'ter', '07:00:00', '18:00:00', 1609),
(135, 'qua', '07:00:00', '18:00:00', 1610),
(135, 'qui', '07:00:00', '18:00:00', 1611),
(135, 'sex', '07:00:00', '18:00:00', 1612),
(135, 'sab', '07:00:00', '14:00:00', 1613),
(136, 'seg', '07:00:00', '18:00:00', 1614),
(136, 'ter', '07:00:00', '18:00:00', 1615),
(136, 'qua', '07:00:00', '18:00:00', 1616),
(136, 'qui', '07:00:00', '18:00:00', 1617),
(136, 'sex', '07:00:00', '18:00:00', 1618),
(136, 'sab', '07:00:00', '14:00:00', 1619),
(137, 'seg', '07:00:00', '18:00:00', 1620),
(137, 'ter', '07:00:00', '18:00:00', 1621),
(137, 'qua', '07:00:00', '18:00:00', 1622),
(137, 'qui', '07:00:00', '18:00:00', 1623),
(137, 'sex', '07:00:00', '18:00:00', 1624),
(137, 'sab', '07:00:00', '14:00:00', 1625),
(138, 'seg', '07:00:00', '18:00:00', 1626),
(138, 'ter', '07:00:00', '18:00:00', 1627),
(138, 'qua', '07:00:00', '18:00:00', 1628),
(138, 'qui', '07:00:00', '18:00:00', 1629),
(138, 'sex', '07:00:00', '18:00:00', 1630),
(138, 'sab', '07:00:00', '14:00:00', 1631),
(139, 'seg', '07:00:00', '18:00:00', 1632),
(139, 'ter', '07:00:00', '18:00:00', 1633),
(139, 'qua', '07:00:00', '18:00:00', 1634),
(139, 'qui', '07:00:00', '18:00:00', 1635),
(139, 'sex', '07:00:00', '18:00:00', 1636),
(139, 'sab', '07:00:00', '14:00:00', 1637),
(140, 'seg', '07:00:00', '18:00:00', 1638),
(140, 'ter', '07:00:00', '18:00:00', 1639),
(140, 'qua', '07:00:00', '18:00:00', 1640),
(140, 'qui', '07:00:00', '18:00:00', 1641),
(140, 'sex', '07:00:00', '18:00:00', 1642),
(140, 'sab', '07:00:00', '14:00:00', 1643),
(141, 'seg', '07:00:00', '18:00:00', 1644),
(141, 'ter', '07:00:00', '18:00:00', 1645),
(141, 'qua', '07:00:00', '18:00:00', 1646),
(141, 'qui', '07:00:00', '18:00:00', 1647),
(141, 'sex', '07:00:00', '18:00:00', 1648),
(141, 'sab', '07:00:00', '14:00:00', 1649);

-- --------------------------------------------------------

--
-- Estrutura para tabela `img_ambientes`
--

CREATE TABLE `img_ambientes` (
  `salvar_amb1` varchar(100) NOT NULL,
  `salvar_amb2` varchar(100) NOT NULL,
  `salvar_amb3` varchar(100) NOT NULL,
  `salvar_amb4` varchar(100) NOT NULL,
  `salvar_amb5` varchar(100) NOT NULL,
  `salvar_amb6` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `lista_funcionario_empresa`
--

CREATE TABLE `lista_funcionario_empresa` (
  `idempresa` int(11) NOT NULL,
  `idfuncionario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `lista_funcionario_empresa`
--

INSERT INTO `lista_funcionario_empresa` (`idempresa`, `idfuncionario`) VALUES
(262, 126),
(262, 127),
(262, 128),
(262, 129),
(263, 130),
(263, 131),
(263, 132),
(263, 133),
(264, 134),
(264, 135),
(264, 136),
(264, 137),
(264, 138),
(264, 139),
(264, 140),
(264, 141);

-- --------------------------------------------------------

--
-- Estrutura para tabela `lista_servicos_empresa`
--

CREATE TABLE `lista_servicos_empresa` (
  `idempresa` int(11) NOT NULL,
  `idservico` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `lista_servicos_empresa`
--

INSERT INTO `lista_servicos_empresa` (`idempresa`, `idservico`) VALUES
(262, 89),
(262, 90),
(262, 91),
(262, 92),
(262, 93),
(262, 94),
(262, 95),
(262, 96),
(262, 97),
(263, 98),
(263, 99),
(263, 100),
(263, 101),
(263, 102),
(263, 103),
(263, 104),
(263, 105),
(263, 106),
(263, 107),
(263, 108),
(264, 109),
(264, 110),
(264, 111),
(264, 112),
(264, 113),
(264, 114),
(264, 115),
(264, 116),
(264, 117),
(264, 118);

-- --------------------------------------------------------

--
-- Estrutura para tabela `localidade`
--

CREATE TABLE `localidade` (
  `cep` varchar(12) NOT NULL,
  `logradouro` varchar(20) DEFAULT NULL,
  `bairro` varchar(30) DEFAULT NULL,
  `UF` varchar(2) DEFAULT NULL,
  `cidade` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `localidade`
--

INSERT INTO `localidade` (`cep`, `logradouro`, `bairro`, `UF`, `cidade`) VALUES
('21610-330', 'Rua Xavier Curado', 'Marechal Hermes', 'RJ', 'Rio de Janeiro'),
('21620-210', 'Rua Cracituba', 'Ricardo de Albuquerque', 'RJ', 'Rio de Janeiro');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagamento`
--

CREATE TABLE `pagamento` (
  `idpagamento` int(11) NOT NULL,
  `idform_pg` int(11) NOT NULL,
  `idempresa` int(11) NOT NULL,
  `nivel` enum('1','2','3') NOT NULL,
  `data_pagamento` date DEFAULT NULL,
  `horario_pagamento` time DEFAULT NULL,
  `forma_pag` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `perfil_cliente`
--

CREATE TABLE `perfil_cliente` (
  `idcliente` int(11) NOT NULL,
  `foto_perfil` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `perfil_empresa`
--

CREATE TABLE `perfil_empresa` (
  `idempresa` int(11) NOT NULL,
  `foto_amb1` varchar(50) DEFAULT NULL,
  `foto_amb2` varchar(50) NOT NULL,
  `foto_amb3` varchar(50) NOT NULL,
  `foto_amb4` varchar(50) NOT NULL,
  `foto_amb5` varchar(50) NOT NULL,
  `foto_amb6` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `planos`
--

CREATE TABLE `planos` (
  `nivel` enum('1','2','3') NOT NULL,
  `preco` decimal(5,2) DEFAULT NULL,
  `Nome` varchar(25) NOT NULL,
  `profissionais` int(11) NOT NULL,
  `clientes-dia` int(11) NOT NULL,
  `desc` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `planos`
--

INSERT INTO `planos` (`nivel`, `preco`, `Nome`, `profissionais`, `clientes-dia`, `desc`) VALUES
('1', 19.90, 'Básico+', 5, 40, 'Plano ideal para você que possui um pequeno estabelecimento!'),
('2', 59.90, 'Premium+', 20, 120, 'Plano ideal para você que possui um estabelecimento médio!'),
('3', 39.90, 'Plus+', 10, 80, 'Plano ideal para você que possui um grande estabelecimento!');

-- --------------------------------------------------------

--
-- Estrutura para tabela `portifolio_empresa`
--

CREATE TABLE `portifolio_empresa` (
  `idempresa` int(11) NOT NULL,
  `port_img1` varchar(50) DEFAULT NULL,
  `port_img2` varchar(50) DEFAULT NULL,
  `port_img3` varchar(50) DEFAULT NULL,
  `port_img4` varchar(50) DEFAULT NULL,
  `port_img5` varchar(50) DEFAULT NULL,
  `port_img6` varchar(50) DEFAULT NULL,
  `port_img7` varchar(50) DEFAULT NULL,
  `port_img8` varchar(50) DEFAULT NULL,
  `port_img9` varchar(50) DEFAULT NULL,
  `port_img10` varchar(50) DEFAULT NULL,
  `port_img11` varchar(50) DEFAULT NULL,
  `port_img12` varchar(50) DEFAULT NULL,
  `id_port` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `portifolio_empresa`
--

INSERT INTO `portifolio_empresa` (`idempresa`, `port_img1`, `port_img2`, `port_img3`, `port_img4`, `port_img5`, `port_img6`, `port_img7`, `port_img8`, `port_img9`, `port_img10`, `port_img11`, `port_img12`, `id_port`) VALUES
(262, 'back.png', 'back.png', 'back.png', 'back.png', 'back.png', 'back.png', 'back.png', 'back.png', 'back.png', 'back.png', 'back.png', 'back.png', 56),
(263, 'back.png', 'back.png', 'back.png', 'back.png', 'back.png', 'back.png', 'back.png', 'back.png', 'back.png', 'back.png', 'back.png', 'back.png', 57),
(264, 'back.png', 'back.png', 'back.png', 'back.png', 'back.png', 'back.png', 'back.png', 'back.png', 'back.png', 'back.png', 'back.png', 'back.png', 58);

-- --------------------------------------------------------

--
-- Estrutura para tabela `servicos`
--

CREATE TABLE `servicos` (
  `idservico` int(11) NOT NULL,
  `nome_serv` varchar(50) DEFAULT NULL,
  `preco_serv` decimal(5,2) DEFAULT NULL,
  `descricao_serv` varchar(30) DEFAULT NULL,
  `duracao_serv` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `servicos`
--

INSERT INTO `servicos` (`idservico`, `nome_serv`, `preco_serv`, `descricao_serv`, `duracao_serv`) VALUES
(89, 'Design simples', 20.00, 'Design fixo', '00:30:00'),
(90, 'Limpeza', 15.00, 'Limpeza de sobrancelha', '00:35:00'),
(91, 'Pigmentação', 50.00, 'Design fixo simples', '01:00:00'),
(92, 'Renna', 25.00, 'Renna simples', '00:40:00'),
(93, 'Preenchimento', 15.00, 'Preenchimento onde falta', '00:30:00'),
(94, 'Cílios', 25.00, 'Preenchimento de cílios', '00:35:00'),
(95, 'Extensão de cílios', 50.00, 'Aumentar os cílios', '00:40:00'),
(96, 'Cílios tufo', 25.00, 'Preenchimento de cílios em par', '00:30:00'),
(97, 'Cílios 3D', 35.00, 'Cílios postiço natural', '00:40:00'),
(98, 'Corte simples', 25.00, 'Corte com maquina', '00:35:00'),
(99, 'Corte disfarçado', 25.00, 'Máquina mais de um pente', '00:40:00'),
(100, 'corte navalhado', 20.00, 'corte total', '00:30:00'),
(101, 'Corte na tesoura', 30.00, 'Corte com tesoura e pente', '01:00:00'),
(102, 'Pigmentação', 15.00, 'Tinta preta no cabelo', '00:30:00'),
(103, 'Nevou', 35.00, 'Descoloração completa', '02:00:00'),
(104, 'Reflexo', 35.00, 'Descolorir fios com touca', '02:00:00'),
(105, 'luzes', 40.00, 'Descolorir mechas', '02:00:00'),
(106, 'Barba', 15.00, 'Design de barba', '00:30:00'),
(107, 'Limpeza facial', 20.00, 'Limpeza do rosto', '00:50:00'),
(108, 'Relaxamento', 40.00, 'Química no cabelo', '02:30:00'),
(109, 'Progressiva', 120.00, 'Alisar o cabelo', '02:00:00'),
(110, 'Escova', 130.00, 'Pranchar o cabelo', '01:30:00'),
(111, 'luzes', 300.00, 'Descolorir mechas', '03:00:00'),
(112, 'Corte simples', 80.00, 'Corte com tesoura', '00:40:00'),
(113, 'Trança simples', 40.00, 'trança simples', '00:40:00'),
(114, 'trança box braid', 200.00, 'Tranças longa', '04:00:00'),
(115, 'Finalização', 80.00, 'finalização', '01:30:00'),
(116, 'Hidratação ', 90.00, 'Hidratar com produtos', '00:40:00'),
(117, 'Acidificação', 120.00, 'Tirar frizz e poros', '01:00:00'),
(118, 'Traça nagô', 70.00, 'trança simples topo da cabeça', '01:30:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `servicos_funcionario`
--

CREATE TABLE `servicos_funcionario` (
  `idservico` int(11) NOT NULL,
  `idfuncionario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `servicos_funcionario`
--

INSERT INTO `servicos_funcionario` (`idservico`, `idfuncionario`) VALUES
(89, 129),
(90, 129),
(91, 129),
(92, 129),
(93, 129),
(94, 129),
(95, 129),
(96, 129),
(97, 129),
(94, 126),
(95, 126),
(96, 126),
(97, 126),
(89, 127),
(90, 127),
(91, 127),
(92, 127),
(93, 127),
(94, 127),
(95, 127),
(96, 127),
(97, 127),
(89, 128),
(91, 128),
(94, 128),
(95, 128),
(96, 128),
(97, 128),
(98, 130),
(99, 130),
(100, 130),
(101, 130),
(102, 130),
(106, 130),
(108, 130),
(98, 131),
(99, 131),
(100, 131),
(101, 131),
(102, 131),
(103, 131),
(104, 131),
(105, 131),
(106, 131),
(107, 131),
(108, 131),
(98, 132),
(100, 132),
(101, 132),
(102, 132),
(104, 132),
(105, 132),
(106, 132),
(107, 132),
(108, 132),
(98, 133),
(99, 133),
(100, 133),
(101, 133),
(102, 133),
(103, 133),
(104, 133),
(105, 133),
(106, 133),
(107, 133),
(108, 133),
(113, 134),
(114, 134),
(115, 134),
(116, 134),
(117, 134),
(118, 134),
(109, 135),
(110, 135),
(111, 135),
(112, 135),
(113, 135),
(114, 135),
(115, 135),
(116, 135),
(117, 135),
(118, 135),
(109, 136),
(110, 136),
(111, 136),
(112, 136),
(113, 136),
(114, 136),
(115, 136),
(116, 136),
(117, 136),
(118, 136),
(109, 137),
(110, 137),
(111, 137),
(112, 137),
(113, 137),
(114, 137),
(115, 137),
(116, 137),
(117, 137),
(118, 137),
(109, 138),
(110, 138),
(111, 138),
(112, 138),
(113, 138),
(114, 138),
(115, 138),
(116, 138),
(117, 138),
(118, 138),
(109, 139),
(110, 139),
(111, 139),
(112, 139),
(113, 139),
(114, 139),
(115, 139),
(116, 139),
(117, 139),
(118, 139),
(109, 140),
(110, 140),
(111, 140),
(113, 140),
(114, 140),
(115, 140),
(116, 140),
(117, 140),
(118, 140),
(113, 141),
(114, 141),
(115, 141),
(116, 141),
(117, 141),
(118, 141);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `admin_agenvi`
--
ALTER TABLE `admin_agenvi`
  ADD PRIMARY KEY (`idadmin`);

--
-- Índices de tabela `agendamento`
--
ALTER TABLE `agendamento`
  ADD PRIMARY KEY (`idagendamento`),
  ADD KEY `idcliente` (`idcliente`),
  ADD KEY `idempresa` (`idempresa`),
  ADD KEY `idservico` (`idservico`),
  ADD KEY `agendamento_ibfk_4` (`idfuncionario`);

--
-- Índices de tabela `agendamento_formpg`
--
ALTER TABLE `agendamento_formpg`
  ADD KEY `idform_pg` (`idform_pg`),
  ADD KEY `idpagamento` (`idpagamento`);

--
-- Índices de tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  ADD PRIMARY KEY (`idavaliacao`),
  ADD KEY `idcliente` (`idcliente`),
  ADD KEY `idempresa` (`idempresa`);

--
-- Índices de tabela `banido`
--
ALTER TABLE `banido`
  ADD KEY `idcliente` (`idcliente`),
  ADD KEY `idempresa` (`idempresa`);

--
-- Índices de tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`idcliente`),
  ADD KEY `cep` (`cep`);

--
-- Índices de tabela `curtidas`
--
ALTER TABLE `curtidas`
  ADD PRIMARY KEY (`idcurti`),
  ADD KEY `curtidas_ibfk_1` (`idavaliacao`),
  ADD KEY `curtidas_ibfk_2` (`idcliente`);

--
-- Índices de tabela `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`idempresa`),
  ADD KEY `cep` (`cep`),
  ADD KEY `nivel` (`nivel`);

--
-- Índices de tabela `favoritos`
--
ALTER TABLE `favoritos`
  ADD KEY `favoritos_ibfk_1` (`idcliente`),
  ADD KEY `favoritos_ibfk_2` (`idempresa`);

--
-- Índices de tabela `forma_pagamento`
--
ALTER TABLE `forma_pagamento`
  ADD PRIMARY KEY (`idform_pg`);

--
-- Índices de tabela `funcionario`
--
ALTER TABLE `funcionario`
  ADD PRIMARY KEY (`idfuncionario`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- Índices de tabela `guard_img`
--
ALTER TABLE `guard_img`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `horarios_empresa`
--
ALTER TABLE `horarios_empresa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `horarios_empresa_ibfk_1` (`idempresa`);

--
-- Índices de tabela `horario_func`
--
ALTER TABLE `horario_func`
  ADD PRIMARY KEY (`id_hr`),
  ADD KEY `horario_func_ibfk_1` (`idfuncionario`);

--
-- Índices de tabela `lista_funcionario_empresa`
--
ALTER TABLE `lista_funcionario_empresa`
  ADD KEY `idfuncionario` (`idfuncionario`),
  ADD KEY `idempresa` (`idempresa`);

--
-- Índices de tabela `lista_servicos_empresa`
--
ALTER TABLE `lista_servicos_empresa`
  ADD KEY `lista_servicos_empresa_ibfk_1` (`idempresa`),
  ADD KEY `lista_servicos_empresa_ibfk_2` (`idservico`);

--
-- Índices de tabela `localidade`
--
ALTER TABLE `localidade`
  ADD PRIMARY KEY (`cep`);

--
-- Índices de tabela `pagamento`
--
ALTER TABLE `pagamento`
  ADD PRIMARY KEY (`idpagamento`),
  ADD KEY `idform_pg` (`idform_pg`),
  ADD KEY `idempresa` (`idempresa`),
  ADD KEY `nivel` (`nivel`);

--
-- Índices de tabela `perfil_cliente`
--
ALTER TABLE `perfil_cliente`
  ADD KEY `idcliente` (`idcliente`);

--
-- Índices de tabela `perfil_empresa`
--
ALTER TABLE `perfil_empresa`
  ADD KEY `idempresa` (`idempresa`);

--
-- Índices de tabela `planos`
--
ALTER TABLE `planos`
  ADD PRIMARY KEY (`nivel`);

--
-- Índices de tabela `portifolio_empresa`
--
ALTER TABLE `portifolio_empresa`
  ADD PRIMARY KEY (`id_port`),
  ADD KEY `portifolio_empresa_ibfk_1` (`idempresa`);

--
-- Índices de tabela `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`idservico`);

--
-- Índices de tabela `servicos_funcionario`
--
ALTER TABLE `servicos_funcionario`
  ADD KEY `idservico` (`idservico`),
  ADD KEY `idfuncionario` (`idfuncionario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `admin_agenvi`
--
ALTER TABLE `admin_agenvi`
  MODIFY `idadmin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `agendamento`
--
ALTER TABLE `agendamento`
  MODIFY `idagendamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=323;

--
-- AUTO_INCREMENT de tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  MODIFY `idavaliacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `idcliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `curtidas`
--
ALTER TABLE `curtidas`
  MODIFY `idcurti` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT de tabela `empresa`
--
ALTER TABLE `empresa`
  MODIFY `idempresa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=265;

--
-- AUTO_INCREMENT de tabela `forma_pagamento`
--
ALTER TABLE `forma_pagamento`
  MODIFY `idform_pg` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `funcionario`
--
ALTER TABLE `funcionario`
  MODIFY `idfuncionario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT de tabela `guard_img`
--
ALTER TABLE `guard_img`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT de tabela `horarios_empresa`
--
ALTER TABLE `horarios_empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=880;

--
-- AUTO_INCREMENT de tabela `horario_func`
--
ALTER TABLE `horario_func`
  MODIFY `id_hr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1650;

--
-- AUTO_INCREMENT de tabela `pagamento`
--
ALTER TABLE `pagamento`
  MODIFY `idpagamento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `portifolio_empresa`
--
ALTER TABLE `portifolio_empresa`
  MODIFY `id_port` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `idservico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `agendamento`
--
ALTER TABLE `agendamento`
  ADD CONSTRAINT `agendamento_ibfk_1` FOREIGN KEY (`idcliente`) REFERENCES `cliente` (`idcliente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `agendamento_ibfk_2` FOREIGN KEY (`idempresa`) REFERENCES `empresa` (`idempresa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `agendamento_ibfk_3` FOREIGN KEY (`idservico`) REFERENCES `servicos` (`idservico`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `agendamento_ibfk_4` FOREIGN KEY (`idfuncionario`) REFERENCES `funcionario` (`idfuncionario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `agendamento_formpg`
--
ALTER TABLE `agendamento_formpg`
  ADD CONSTRAINT `agendamento_formpg_ibfk_1` FOREIGN KEY (`idform_pg`) REFERENCES `forma_pagamento` (`idform_pg`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `agendamento_formpg_ibfk_2` FOREIGN KEY (`idpagamento`) REFERENCES `pagamento` (`idpagamento`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `avaliacao`
--
ALTER TABLE `avaliacao`
  ADD CONSTRAINT `avaliacao_ibfk_1` FOREIGN KEY (`idcliente`) REFERENCES `cliente` (`idcliente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `avaliacao_ibfk_2` FOREIGN KEY (`idempresa`) REFERENCES `empresa` (`idempresa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `banido`
--
ALTER TABLE `banido`
  ADD CONSTRAINT `banido_ibfk_1` FOREIGN KEY (`idcliente`) REFERENCES `cliente` (`idcliente`),
  ADD CONSTRAINT `banido_ibfk_2` FOREIGN KEY (`idempresa`) REFERENCES `empresa` (`idempresa`);

--
-- Restrições para tabelas `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`cep`) REFERENCES `localidade` (`cep`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `curtidas`
--
ALTER TABLE `curtidas`
  ADD CONSTRAINT `curtidas_ibfk_1` FOREIGN KEY (`idavaliacao`) REFERENCES `avaliacao` (`idavaliacao`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `curtidas_ibfk_2` FOREIGN KEY (`idcliente`) REFERENCES `cliente` (`idcliente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `empresa`
--
ALTER TABLE `empresa`
  ADD CONSTRAINT `empresa_ibfk_1` FOREIGN KEY (`cep`) REFERENCES `localidade` (`cep`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `empresa_ibfk_2` FOREIGN KEY (`nivel`) REFERENCES `planos` (`nivel`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`idcliente`) REFERENCES `cliente` (`idcliente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`idempresa`) REFERENCES `empresa` (`idempresa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `horarios_empresa`
--
ALTER TABLE `horarios_empresa`
  ADD CONSTRAINT `horarios_empresa_ibfk_1` FOREIGN KEY (`idempresa`) REFERENCES `empresa` (`idempresa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `horario_func`
--
ALTER TABLE `horario_func`
  ADD CONSTRAINT `horario_func_ibfk_1` FOREIGN KEY (`idfuncionario`) REFERENCES `funcionario` (`idfuncionario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `lista_funcionario_empresa`
--
ALTER TABLE `lista_funcionario_empresa`
  ADD CONSTRAINT `lista_funcionario_empresa_ibfk_1` FOREIGN KEY (`idfuncionario`) REFERENCES `funcionario` (`idfuncionario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lista_funcionario_empresa_ibfk_2` FOREIGN KEY (`idempresa`) REFERENCES `empresa` (`idempresa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `lista_servicos_empresa`
--
ALTER TABLE `lista_servicos_empresa`
  ADD CONSTRAINT `lista_servicos_empresa_ibfk_1` FOREIGN KEY (`idempresa`) REFERENCES `empresa` (`idempresa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lista_servicos_empresa_ibfk_2` FOREIGN KEY (`idservico`) REFERENCES `servicos` (`idservico`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `pagamento`
--
ALTER TABLE `pagamento`
  ADD CONSTRAINT `pagamento_ibfk_1` FOREIGN KEY (`idform_pg`) REFERENCES `forma_pagamento` (`idform_pg`),
  ADD CONSTRAINT `pagamento_ibfk_2` FOREIGN KEY (`idempresa`) REFERENCES `empresa` (`idempresa`),
  ADD CONSTRAINT `pagamento_ibfk_3` FOREIGN KEY (`nivel`) REFERENCES `planos` (`nivel`);

--
-- Restrições para tabelas `perfil_cliente`
--
ALTER TABLE `perfil_cliente`
  ADD CONSTRAINT `perfil_cliente_ibfk_1` FOREIGN KEY (`idcliente`) REFERENCES `cliente` (`idcliente`);

--
-- Restrições para tabelas `perfil_empresa`
--
ALTER TABLE `perfil_empresa`
  ADD CONSTRAINT `perfil_empresa_ibfk_1` FOREIGN KEY (`idempresa`) REFERENCES `empresa` (`idempresa`);

--
-- Restrições para tabelas `portifolio_empresa`
--
ALTER TABLE `portifolio_empresa`
  ADD CONSTRAINT `portifolio_empresa_ibfk_1` FOREIGN KEY (`idempresa`) REFERENCES `empresa` (`idempresa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `servicos_funcionario`
--
ALTER TABLE `servicos_funcionario`
  ADD CONSTRAINT `servicos_funcionario_ibfk_1` FOREIGN KEY (`idservico`) REFERENCES `servicos` (`idservico`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `servicos_funcionario_ibfk_2` FOREIGN KEY (`idfuncionario`) REFERENCES `funcionario` (`idfuncionario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
