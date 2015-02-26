CREATE DATABASE  IF NOT EXISTS `schedule` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `schedule`;
-- MySQL dump 10.13  Distrib 5.5.41, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: schedule
-- ------------------------------------------------------
-- Server version	5.5.41-0+wheezy1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `buildings`
--

DROP TABLE IF EXISTS `buildings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `buildings` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ключ',
  `name` varchar(255) NOT NULL COMMENT 'название',
  `id_campuses` bigint(20) NOT NULL COMMENT 'id кампуса',
  `building_addres` varchar(255) NOT NULL COMMENT 'адрес местополжения',
  `status` tinyint(4) NOT NULL COMMENT 'состояние',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='здания';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buildings`
--

LOCK TABLES `buildings` WRITE;
/*!40000 ALTER TABLE `buildings` DISABLE KEYS */;
INSERT INTO `buildings` VALUES (1,'Главный учебный корпус',1,'ул. Курчатова,7',1),(2,'Лекционный корпус',1,'ул. Курчатова,7',1),(3,'Лаборатория \"Борт-70\"',1,'Ул. Курчатова,7',1),(4,'Главный учебный корпус',2,'Ул. Университетская, 33',1),(5,'Библиотека',2,'Ул. Университетская, 29',1),(6,'Спорткомплекс',2,'Ул. Университетская, ??',1),(7,'Лабораторный корпус \"FESTO\"',2,'Ул. Университетская, 33',1),(8,'Главный учебный корпус',4,'Ул. Гоголя, ??',1),(9,'Корпус корабелов???',4,'Ул, Гоголя, ???',0),(10,'Колледж ???',3,'Ул. Репина???',0),(11,'Главный учебный корпус',5,'???? ПОР',0);
/*!40000 ALTER TABLE `buildings` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-02-26 16:45:47
