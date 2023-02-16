-- MariaDB dump 10.19  Distrib 10.5.17-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: drocerca
-- ------------------------------------------------------
-- Server version	10.5.17-MariaDB-log

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
-- Table structure for table `botpexclu`
--

DROP TABLE IF EXISTS `botpexclu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `botpexclu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `palabra` text DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `palabra` (`palabra`) USING HASH
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `botpexclu`
--

LOCK TABLES `botpexclu` WRITE;
/*!40000 ALTER TABLE `botpexclu` DISABLE KEYS */;
INSERT INTO `botpexclu` VALUES (1,'de'),(2,'hola'),(3,'como'),(4,'un'),(5,'la'),(6,'quiero'),(7,'saber'),(8,'esta'),(9,'cuando'),(10,'y'),(11,'el'),(12,'una'),(13,'precio'),(14,'hay'),(15,'productos'),(16,'producto'),(17,'necesito'),(18,'comprar'),(19,'unidades'),(20,'disponibles'),(21,'me'),(22,'duele'),(23,'cabeza'),(24,'otra');
/*!40000 ALTER TABLE `botpexclu` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-02-15 18:11:18
