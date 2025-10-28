-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 28/10/2025 às 01:31
-- Versão do servidor: 8.0.31
-- Versão do PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sistema_escala`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `escalas_mensais`
--

DROP TABLE IF EXISTS `escalas_mensais`;
CREATE TABLE IF NOT EXISTS `escalas_mensais` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_militar` int NOT NULL,
  `mes_ano` date NOT NULL,
  `dias_json` text,
  `tenant_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `escala_unica` (`id_militar`,`mes_ano`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `escalas_mensais`
--

INSERT INTO `escalas_mensais` (`id`, `id_militar`, `mes_ano`, `dias_json`, `tenant_id`) VALUES
(1, 1, '2025-06-01', '{\"1\":\"H\",\"2\":\"F\",\"3\":\"F\",\"4\":\"5\",\"5\":\"5\",\"6\":\"F\",\"7\":\"F\",\"8\":\"5\",\"9\":\"5\",\"10\":\"F\",\"11\":\"F\",\"12\":\"5\",\"13\":\"1\",\"14\":\"F\",\"15\":\"F\",\"16\":\"5\",\"17\":\"5\",\"18\":\"F\",\"19\":\"F\",\"20\":\"5\",\"21\":\"1\",\"22\":\"F\",\"23\":\"F\",\"24\":\"5\",\"25\":\"5\",\"26\":\"5\",\"27\":\"F\",\"28\":\"1\",\"29\":\"1\",\"30\":\"F\"}', 0),
(2, 1, '2025-10-01', '{\"1\":\"2\",\"2\":\"5\",\"3\":\"F\",\"4\":\"H\",\"5\":\"F\",\"6\":\"2\",\"7\":\"F\",\"8\":\"F\",\"9\":\"5\",\"10\":\"F\",\"11\":\"F\",\"12\":\"F\",\"13\":\"F\",\"14\":\"F\",\"15\":\"F\",\"16\":\"F\",\"17\":\"F\",\"18\":\"F\",\"19\":\"F\",\"20\":\"F\",\"21\":\"F\",\"22\":\"F\",\"23\":\"F\",\"24\":\"F\",\"25\":\"F\",\"26\":\"F\",\"27\":\"F\",\"28\":\"F\",\"29\":\"F\",\"30\":\"F\",\"31\":\"F\"}', 0),
(3, 3, '2025-10-01', '{\"1\":\"F\",\"2\":\"F\",\"3\":\"F\",\"4\":\"F\",\"5\":\"F\",\"6\":\"F\",\"7\":\"F\",\"8\":\"F\",\"9\":\"F\",\"10\":\"F\",\"11\":\"F\",\"12\":\"F\",\"13\":\"F\",\"14\":\"F\",\"15\":\"F\",\"16\":\"F\",\"17\":\"F\",\"18\":\"F\",\"19\":\"F\",\"20\":\"F\",\"21\":\"F\",\"22\":\"F\",\"23\":\"F\",\"24\":\"F\",\"25\":\"F\",\"26\":\"F\",\"27\":\"F\",\"28\":\"F\",\"29\":\"F\",\"30\":\"F\",\"31\":\"F\"}', 0),
(4, 2, '2025-10-01', '{\"1\":\"F\",\"2\":\"F\",\"3\":\"F\",\"4\":\"F\",\"5\":\"F\",\"6\":\"F\",\"7\":\"F\",\"8\":\"F\",\"9\":\"F\",\"10\":\"F\",\"11\":\"F\",\"12\":\"F\",\"13\":\"F\",\"14\":\"F\",\"15\":\"F\",\"16\":\"F\",\"17\":\"F\",\"18\":\"F\",\"19\":\"F\",\"20\":\"F\",\"21\":\"F\",\"22\":\"F\",\"23\":\"F\",\"24\":\"F\",\"25\":\"F\",\"26\":\"F\",\"27\":\"F\",\"28\":\"F\",\"29\":\"F\",\"30\":\"F\",\"31\":\"F\"}', 0),
(5, 4, '2025-10-01', '{\"1\":\"F\",\"2\":\"F\",\"3\":\"F\",\"4\":\"F\",\"5\":\"F\",\"6\":\"F\",\"7\":\"F\",\"8\":\"F\",\"9\":\"F\",\"10\":\"F\",\"11\":\"F\",\"12\":\"F\",\"13\":\"F\",\"14\":\"F\",\"15\":\"F\",\"16\":\"F\",\"17\":\"F\",\"18\":\"F\",\"19\":\"F\",\"20\":\"F\",\"21\":\"F\",\"22\":\"F\",\"23\":\"F\",\"24\":\"F\",\"25\":\"F\",\"26\":\"F\",\"27\":\"F\",\"28\":\"F\",\"29\":\"F\",\"30\":\"F\",\"31\":\"F\"}', 0),
(6, 1, '2025-09-01', '{\"1\":\"F\",\"2\":\"F\",\"3\":\"F\",\"4\":\"F\",\"5\":\"2\",\"6\":\"F\",\"7\":\"F\",\"8\":\"F\",\"9\":\"F\",\"10\":\"F\",\"11\":\"F\",\"12\":\"F\",\"13\":\"F\",\"14\":\"F\",\"15\":\"F\",\"16\":\"F\",\"17\":\"F\",\"18\":\"F\",\"19\":\"F\",\"20\":\"F\",\"21\":\"F\",\"22\":\"F\",\"23\":\"F\",\"24\":\"F\",\"25\":\"F\",\"26\":\"F\",\"27\":\"F\",\"28\":\"F\",\"29\":\"F\",\"30\":\"F\"}', 0),
(7, 3, '2025-09-01', '{\"1\":\"F\",\"2\":\"F\",\"3\":\"F\",\"4\":\"F\",\"5\":\"F\",\"6\":\"F\",\"7\":\"F\",\"8\":\"F\",\"9\":\"F\",\"10\":\"F\",\"11\":\"F\",\"12\":\"F\",\"13\":\"F\",\"14\":\"F\",\"15\":\"F\",\"16\":\"F\",\"17\":\"F\",\"18\":\"F\",\"19\":\"F\",\"20\":\"F\",\"21\":\"F\",\"22\":\"F\",\"23\":\"F\",\"24\":\"F\",\"25\":\"F\",\"26\":\"F\",\"27\":\"F\",\"28\":\"F\",\"29\":\"F\",\"30\":\"F\"}', 0),
(8, 2, '2025-09-01', '{\"1\":\"F\",\"2\":\"F\",\"3\":\"F\",\"4\":\"F\",\"5\":\"F\",\"6\":\"F\",\"7\":\"F\",\"8\":\"F\",\"9\":\"F\",\"10\":\"F\",\"11\":\"F\",\"12\":\"F\",\"13\":\"F\",\"14\":\"F\",\"15\":\"F\",\"16\":\"F\",\"17\":\"F\",\"18\":\"F\",\"19\":\"F\",\"20\":\"F\",\"21\":\"F\",\"22\":\"F\",\"23\":\"F\",\"24\":\"F\",\"25\":\"F\",\"26\":\"F\",\"27\":\"F\",\"28\":\"F\",\"29\":\"F\",\"30\":\"F\"}', 0),
(9, 4, '2025-09-01', '{\"1\":\"F\",\"2\":\"F\",\"3\":\"F\",\"4\":\"F\",\"5\":\"F\",\"6\":\"F\",\"7\":\"F\",\"8\":\"F\",\"9\":\"F\",\"10\":\"F\",\"11\":\"F\",\"12\":\"F\",\"13\":\"F\",\"14\":\"F\",\"15\":\"F\",\"16\":\"F\",\"17\":\"F\",\"18\":\"F\",\"19\":\"F\",\"20\":\"F\",\"21\":\"F\",\"22\":\"F\",\"23\":\"F\",\"24\":\"F\",\"25\":\"F\",\"26\":\"F\",\"27\":\"F\",\"28\":\"F\",\"29\":\"F\",\"30\":\"F\"}', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `lancamentos_ajustes`
--

DROP TABLE IF EXISTS `lancamentos_ajustes`;
CREATE TABLE IF NOT EXISTS `lancamentos_ajustes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_militar` int NOT NULL,
  `data_lancamento` date NOT NULL,
  `horas_ajuste` decimal(5,2) NOT NULL COMMENT 'Ex: 2.5 (extra) ou -1.0 (saída)',
  `justificativa` varchar(255) DEFAULT NULL,
  `tenant_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_militar` (`id_militar`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `lancamentos_ajustes`
--

INSERT INTO `lancamentos_ajustes` (`id`, `id_militar`, `data_lancamento`, `horas_ajuste`, `justificativa`, `tenant_id`) VALUES
(1, 1, '2025-10-27', 1.50, 'ljkhkjh', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `militares`
--

DROP TABLE IF EXISTS `militares`;
CREATE TABLE IF NOT EXISTS `militares` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero` varchar(20) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `posto` varchar(50) DEFAULT NULL,
  `carga_horaria_padrao` int NOT NULL DEFAULT '160',
  `status` enum('ativo','inativo','ferias','licenca') NOT NULL DEFAULT 'ativo',
  `tenant_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero` (`numero`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `militares`
--

INSERT INTO `militares` (`id`, `numero`, `nome`, `posto`, `carga_horaria_padrao`, `status`, `tenant_id`) VALUES
(1, '127947-0', '2º Sgt Filho', '2º Sgt', 160, 'ativo', 0),
(2, '129549-2', '3º Sgt Marcelo', '3º Sgt', 160, 'ativo', 0),
(3, '144149-2', '3º Sgt Carlos', '3º Sgt', 160, 'ativo', 0),
(5, '', '2º Sgt Filho', NULL, 160, 'ativo', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tenants`
--

DROP TABLE IF EXISTS `tenants`;
CREATE TABLE IF NOT EXISTS `tenants` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome_grupo` varchar(255) NOT NULL,
  `codigo_grupo` varchar(50) NOT NULL,
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo_grupo` (`codigo_grupo`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `tenants`
--

INSERT INTO `tenants` (`id`, `nome_grupo`, `codigo_grupo`, `criado_em`) VALUES
(1, 'Organização Teste', 'TESTE-01', '2025-10-27 19:14:14');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipos_turno`
--

DROP TABLE IF EXISTS `tipos_turno`;
CREATE TABLE IF NOT EXISTS `tipos_turno` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `duracao_horas` decimal(5,2) NOT NULL DEFAULT '0.00',
  `tipo` enum('Trabalho','Folga','Neutro') NOT NULL DEFAULT 'Trabalho',
  `tenant_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `tipos_turno`
--

INSERT INTO `tipos_turno` (`id`, `codigo`, `descricao`, `duracao_horas`, `tipo`, `tenant_id`) VALUES
(1, '5', 'Turno 10h', 10.00, 'Trabalho', 0),
(2, 'H', 'Horário Admin', 5.55, 'Trabalho', 0),
(4, 'F', 'Folga', 0.00, 'Folga', 0),
(5, '2', 'Abono/Atestado', 25.00, '', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `nome`, `email`, `password_hash`, `criado_em`) VALUES
(2, 'Administrador Teste', 'teste@sistema.com', '$2y$10$bOkqDs6wWwEy5dG5ExrgpeCUSwO/Lx6jY1f3GZkMqgQWY2DOFJowi', '2025-10-27 19:14:14');

-- --------------------------------------------------------

--
-- Estrutura para tabela `user_tenants`
--

DROP TABLE IF EXISTS `user_tenants`;
CREATE TABLE IF NOT EXISTS `user_tenants` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `tenant_id` int NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'leitor',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`tenant_id`),
  KEY `tenant_id` (`tenant_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `user_tenants`
--

INSERT INTO `user_tenants` (`id`, `user_id`, `tenant_id`, `role`) VALUES
(1, 2, 1, 'admin');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
