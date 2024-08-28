-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.4.32-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.5.0.6677
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para recriare
CREATE DATABASE IF NOT EXISTS `recriare` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `recriare`;

-- Copiando estrutura para tabela recriare.consultas
CREATE TABLE IF NOT EXISTS `consultas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paciente_id` int(11) NOT NULL,
  `terapeuta_id` int(11) NOT NULL,
  `terapia_id` int(11) NOT NULL,
  `data_consulta` date NOT NULL,
  `horario_consulta` time NOT NULL,
  `status` varchar(50) DEFAULT 'Aberto',
  `observacoes` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `paciente_id` (`paciente_id`),
  KEY `terapeuta_id` (`terapeuta_id`),
  CONSTRAINT `consultas_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`),
  CONSTRAINT `consultas_ibfk_2` FOREIGN KEY (`terapeuta_id`) REFERENCES `terapeutas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela recriare.consultas: ~10 rows (aproximadamente)
INSERT INTO `consultas` (`id`, `paciente_id`, `terapeuta_id`, `terapia_id`, `data_consulta`, `horario_consulta`, `status`, `observacoes`) VALUES
	(1, 1, 1, 0, '2024-08-30', '15:45:00', 'Aberto', NULL),
	(2, 1, 1, 1, '2024-08-31', '13:45:00', 'Agendado', 'swswsws'),
	(3, 2, 1, 2, '2024-08-28', '12:45:00', 'Finalizado', 'foi'),
	(4, 2, 1, 2, '2024-08-28', '12:45:00', 'Aberto', 'wsd'),
	(5, 2, 1, 3, '2024-08-25', '04:48:00', 'Consulta do Dia', NULL),
	(6, 3, 4, 3, '2024-08-31', '00:10:00', 'Agendado', NULL),
	(7, 3, 4, 5, '2024-09-05', '05:16:00', 'Agendado', NULL),
	(8, 3, 2, 4, '2024-08-05', '05:11:00', 'Aberto', NULL),
	(9, 1, 3, 3, '2024-08-08', '00:19:00', 'Agendado', 'swswsws'),
	(10, 2, 7, 3, '2024-08-30', '15:16:00', 'Aberto', NULL),
	(11, 4, 6, 3, '2024-08-30', '16:37:00', 'Aberto', NULL);

-- Copiando estrutura para tabela recriare.pacientes
CREATE TABLE IF NOT EXISTS `pacientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `especialidade` varchar(100) NOT NULL,
  `numero_terapias` int(11) NOT NULL,
  `dias_da_semana` varchar(100) NOT NULL,
  `turno` varchar(50) NOT NULL,
  `terapeuta_preferencial` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela recriare.pacientes: ~3 rows (aproximadamente)
INSERT INTO `pacientes` (`id`, `nome`, `especialidade`, `numero_terapias`, `dias_da_semana`, `turno`, `terapeuta_preferencial`) VALUES
	(1, 'moa', 'Psicopedagogia', 5, 'Segunda-feira', 'Manhã', 'tal cara'),
	(2, 'teste1', 'Psicologia', 6, 'Quinta-feira', 'Tarde', 'tal cara'),
	(3, 'Utente1', 'Psicopedagogia', 10, 'Segunda-feira, Terça-feira, Quarta-feira, Quinta-feira, Sexta-feira', 'Manhã', 'moa123'),
	(4, 'Edu', 'Fisioterapia', 12, 'Terça-feira, Quarta-feira', 'Manhã', 'Moacir');

-- Copiando estrutura para tabela recriare.terapeutas
CREATE TABLE IF NOT EXISTS `terapeutas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `especialidade` varchar(100) NOT NULL,
  `dias_disponiveis` varchar(100) NOT NULL,
  `turnos_disponiveis` varchar(100) NOT NULL,
  `numero_crm` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela recriare.terapeutas: ~10 rows (aproximadamente)
INSERT INTO `terapeutas` (`id`, `nome`, `especialidade`, `dias_disponiveis`, `turnos_disponiveis`, `numero_crm`) VALUES
	(1, 'moa123', 'Psicologia', 'Terça-feira, Quarta-feira', 'Manhã', '1231232321'),
	(2, 'teste1', 'Terapia Ocupacional', 'Segunda-feira, Terça-feira, Quarta-feira, Quinta-feira, Sexta-feira', 'Tarde', '123123232123r234'),
	(3, 'Moamoa', 'Fonoaudiologia', 'Segunda-feira, Terça-feira, Quarta-feira', 'Manhã', '123'),
	(4, 'admin', 'Psicologia', 'Terça-feira', 'Manhã', '78'),
	(5, 'Lucky', 'Psicologia', 'Terça-feira', 'Manhã', '789456'),
	(6, 'Lucky', 'Psicologia', 'Terça-feira', 'Manhã', '789456'),
	(7, 'Arlindo', 'Terapia Ocupacional', 'Quinta-feira', 'Tarde', '159753'),
	(8, 'Strike', 'Psicologia', 'Segunda-feira, Terça-feira, Quarta-feira, Quinta-feira, Sexta-feira', 'Manhã', '456456'),
	(9, 'Moacir', 'Psicologia', 'Segunda-feira, Quarta-feira, Quinta-feira', 'Manhã', '489265'),
	(10, 'João ', 'Psicologia', 'Quarta-feira', 'Manhã', '789798');

-- Copiando estrutura para tabela recriare.terapias
CREATE TABLE IF NOT EXISTS `terapias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL DEFAULT '0',
  `paciente_id` int(11) DEFAULT NULL,
  `terapeuta_id` int(11) DEFAULT NULL,
  `data_entrada` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `paciente_id` (`paciente_id`),
  KEY `terapeuta_id` (`terapeuta_id`),
  CONSTRAINT `terapias_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`),
  CONSTRAINT `terapias_ibfk_2` FOREIGN KEY (`terapeuta_id`) REFERENCES `terapeutas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela recriare.terapias: ~5 rows (aproximadamente)
INSERT INTO `terapias` (`id`, `nome`, `paciente_id`, `terapeuta_id`, `data_entrada`) VALUES
	(1, 'Moa', NULL, NULL, NULL),
	(2, 'Musicoterapia', NULL, 1, NULL),
	(3, 'Terapia Ocupacional', NULL, 2, '2024-08-27'),
	(4, 'Quiropraxia', NULL, 1, '2024-08-27'),
	(5, 'Arteterapia', NULL, 3, '2024-08-28');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
