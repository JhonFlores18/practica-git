-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: sistema_hibrido
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ambientes`
--

DROP TABLE IF EXISTS `ambientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ambientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `ubicacion` varchar(255) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ambientes`
--

LOCK TABLES `ambientes` WRITE;
/*!40000 ALTER TABLE `ambientes` DISABLE KEYS */;
INSERT INTO `ambientes` VALUES (1,'Laboratorio de Computación A','Planta Baja','2026-11-14 23:05:01'),(2,'Telecentro B','Primer Piso','2026-11-14 23:05:01');
/*!40000 ALTER TABLE `ambientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipos`
--

DROP TABLE IF EXISTS `equipos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ambiente_id` int(11) NOT NULL,
  `codigo_qr` varchar(100) NOT NULL,
  `generacion` varchar(50) NOT NULL,
  `procesador` varchar(100) DEFAULT NULL,
  `memoria_ram` varchar(50) DEFAULT NULL,
  `almacenamiento` varchar(50) DEFAULT NULL,
  `estado_operativo` enum('Operativo','Con Fallas','Inoperativo','De Baja') DEFAULT 'Operativo',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo_qr` (`codigo_qr`),
  KEY `ambiente_id` (`ambiente_id`),
  CONSTRAINT `equipos_ibfk_1` FOREIGN KEY (`ambiente_id`) REFERENCES `ambientes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipos`
--

LOCK TABLES `equipos` WRITE;
/*!40000 ALTER TABLE `equipos` DISABLE KEYS */;
INSERT INTO `equipos` VALUES (1,1,'TEL-DE4B0F','5ta Generación','Intel Core i3','4GB','256GB SSD','Operativo','2026-11-14 23:07:55'),(2,2,'TEL-FAEFA9','11va Generación','Intel Core i5','16GB','500GB SSD','Con Fallas','2026-11-14 23:45:49'),(3,1,'TEL-357B1F','11va Generación','Intel Core i7','16GB','1TB SSD','Operativo','2026-11-14 23:52:12'),(4,2,'TEL-2F1987','4ta Generación','Ryzen 3500 G','8GB','500GB HDD','Operativo','2026-11-14 23:52:59'),(5,1,'TEL-74727E','5ta Generación','Intel Core i7','12GB','256GB SSD','Operativo','2026-11-15 00:31:49');
/*!40000 ALTER TABLE `equipos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `incidencias`
--

DROP TABLE IF EXISTS `incidencias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `incidencias` (
  `id` varchar(36) NOT NULL,
  `equipo_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `estado_incidencia` enum('Reportada','En Revisión','Solucionada') DEFAULT 'Reportada',
  `fecha_reporte` datetime NOT NULL,
  `sincronizado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `equipo_id` (`equipo_id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `incidencias_ibfk_1` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`),
  CONSTRAINT `incidencias_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `incidencias`
--

LOCK TABLES `incidencias` WRITE;
/*!40000 ALTER TABLE `incidencias` DISABLE KEYS */;
INSERT INTO `incidencias` VALUES ('00a0a56e-c7c1-4f52-9a10-01fe0f43e7e3',3,2,'FALTA DE  MATENIMIENTE PREVENTIVO ','Solucionada','2026-11-15 01:34:58','2026-11-15 00:34:58'),('31338321-00e1-400f-a2e3-9426796c8261',4,2,'sads','Solucionada','2026-11-20 17:09:37','2026-11-20 16:09:37'),('7bcda813-d804-406c-ab55-c599f8c71251',5,2,'safsfs','Solucionada','2026-11-20 16:46:47','2026-11-20 15:46:47'),('8829cdf9-ec7b-46cd-bde5-c9dbe8da7e48',2,2,'Falla en la actualizaciones de seguridad de Windows 11.','Solucionada','2026-11-15 00:46:18','2026-11-14 23:46:18'),('9c196bbf-ca22-498b-afb2-27486f3f10bc',1,2,'El monitor parpadea constantemente y no da imagen clara. (Reportado desde App Móvil Offline)','Solucionada','2026-11-15 00:16:45','2026-11-14 23:16:45'),('c8770d6a-b746-46ea-a995-06f3172fc978',1,2,'El monitor parpadea constantemente y no da imagen clara. (Reportado desde App Móvil Offline)','Reportada','2026-11-15 00:54:14','2026-11-14 23:54:14'),('ccfae8fa-c58d-45ae-80d9-a6dfbc0c586f',1,2,'Falla en el SSD capacidad sobrepasada e inicio del equipo lento.','Reportada','2026-11-15 00:14:01','2026-11-14 23:14:01'),('ed15b3ec-d938-4650-8d69-73a116bd8da7',1,2,'El monitor parpadea constantemente y no da imagen clara. (Reportado desde App Móvil Offline)','Reportada','2026-11-15 00:16:35','2026-11-14 23:16:35'),('edbc36e4-f16b-436c-9c8a-2798b897ef4d',4,2,'El equipo presenta ruido a encender. Posible falta de limpieza.','Solucionada','2026-11-15 00:53:58','2026-11-14 23:53:58');
/*!40000 ALTER TABLE `incidencias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mantenimientos`
--

DROP TABLE IF EXISTS `mantenimientos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mantenimientos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `equipo_id` int(11) NOT NULL,
  `tecnico_id` int(11) NOT NULL,
  `tipo_mantenimiento` enum('Preventivo','Correctivo') NOT NULL,
  `detalles` text DEFAULT NULL,
  `fecha_programada` date DEFAULT NULL,
  `fecha_realizada` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `equipo_id` (`equipo_id`),
  KEY `tecnico_id` (`tecnico_id`),
  CONSTRAINT `mantenimientos_ibfk_1` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`),
  CONSTRAINT `mantenimientos_ibfk_2` FOREIGN KEY (`tecnico_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mantenimientos`
--

LOCK TABLES `mantenimientos` WRITE;
/*!40000 ALTER TABLE `mantenimientos` DISABLE KEYS */;
/*!40000 ALTER TABLE `mantenimientos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Administrador'),(3,'Encargado de Aula'),(2,'Técnico');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rol_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `estado` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `correo` (`correo`),
  KEY `rol_id` (`rol_id`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,2,'Jhon Flores','tecnico@telecentro.edu','TecPassword2026',1),(2,1,'Super Admin','admin@telecentro.edu','Admin123',1);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-20 12:22:31
