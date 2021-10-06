-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.14-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             11.1.0.6116
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para encuesta
CREATE DATABASE IF NOT EXISTS `encuesta` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci */;
USE `encuesta`;

-- Volcando estructura para tabla encuesta.encuestas
CREATE TABLE IF NOT EXISTS `encuestas` (
  `idencuesta` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `fk_idgenero` int(11) unsigned NOT NULL DEFAULT 0,
  `fk_idhobby` int(11) unsigned NOT NULL DEFAULT 0,
  `dedicacion_hobby` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`idencuesta`),
  KEY `fk_idgenero` (`fk_idgenero`),
  KEY `fk_idhobby` (`fk_idhobby`),
  CONSTRAINT `fk_idgenero` FOREIGN KEY (`fk_idgenero`) REFERENCES `generos` (`idgenero`),
  CONSTRAINT `fk_idhobby` FOREIGN KEY (`fk_idhobby`) REFERENCES `hobbies` (`idhobby`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla encuesta.encuestas: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `encuestas` DISABLE KEYS */;
/*!40000 ALTER TABLE `encuestas` ENABLE KEYS */;

-- Volcando estructura para tabla encuesta.generos
CREATE TABLE IF NOT EXISTS `generos` (
  `idgenero` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idgenero`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla encuesta.generos: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `generos` DISABLE KEYS */;
INSERT INTO `generos` (`idgenero`, `nombre`) VALUES
	(1, 'Mujer'),
	(2, 'Hombre');
/*!40000 ALTER TABLE `generos` ENABLE KEYS */;

-- Volcando estructura para tabla encuesta.hobbies
CREATE TABLE IF NOT EXISTS `hobbies` (
  `idhobby` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idhobby`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Volcando datos para la tabla encuesta.hobbies: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `hobbies` DISABLE KEYS */;
INSERT INTO `hobbies` (`idhobby`, `nombre`) VALUES
	(1, 'Ninguno'),
	(2, 'Deporte'),
	(3, 'Musical'),
	(4, 'Cocina'),
	(5, 'Literario'),
	(6, 'Manualidades'),
	(7, 'Juegos'),
	(8, 'Modelismo'),
	(9, 'Baile'),
	(10, 'Cine'),
	(11, 'Otro');
/*!40000 ALTER TABLE `hobbies` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
