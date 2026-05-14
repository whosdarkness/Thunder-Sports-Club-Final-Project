-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: clube_desp
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
-- Table structure for table `inscricao_mod_socio`
--

DROP TABLE IF EXISTS `inscricao_mod_socio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inscricao_mod_socio` (
  `num_insc` int(11) NOT NULL AUTO_INCREMENT,
  `id_mod` int(2) NOT NULL,
  `num_socio` int(3) NOT NULL,
  `data_registo` date DEFAULT curdate(),
  `pago` int(1) DEFAULT 0,
  `data_validacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`num_insc`),
  KEY `fk_num_socio` (`num_socio`),
  KEY `fk_id_mod` (`id_mod`),
  CONSTRAINT `fk_id_mod` FOREIGN KEY (`id_mod`) REFERENCES `modalidade` (`id_mod`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_num_socio` FOREIGN KEY (`num_socio`) REFERENCES `socios` (`num_socio`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6690 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inscricao_mod_socio`
--

LOCK TABLES `inscricao_mod_socio` WRITE;
/*!40000 ALTER TABLE `inscricao_mod_socio` DISABLE KEYS */;
INSERT INTO `inscricao_mod_socio` VALUES (6671,22,667,'2026-03-05',1,'2026-03-06 12:18:23'),(6674,55,670,'2026-03-06',1,'2026-03-06 12:52:05'),(6675,33,671,'2026-03-06',1,'2026-03-06 17:20:15'),(6676,11,672,'2026-03-13',1,'2026-03-13 16:54:21'),(6677,44,673,'2026-04-23',1,'2026-04-23 09:31:06'),(6678,22,674,'2026-04-23',1,'2026-04-23 09:31:37'),(6679,11,671,'2026-04-23',1,'2026-04-23 09:32:38'),(6680,44,676,'2026-04-24',1,'2026-04-24 08:33:26'),(6681,11,676,'2026-04-24',1,'2026-04-24 08:33:56'),(6682,22,678,'2026-04-24',1,'2026-04-24 08:34:42'),(6683,11,678,'2026-04-24',1,'2026-04-24 08:35:02'),(6684,22,678,'2026-04-24',1,'2026-05-13 09:22:11'),(6685,55,678,'2026-04-24',1,'2026-04-24 18:12:31'),(6686,22,678,'2026-05-13',1,'2026-05-13 09:22:11'),(6687,22,678,'2026-05-13',1,'2026-05-13 09:26:50'),(6688,22,684,'2026-05-13',1,'2026-05-13 09:27:31'),(6689,66,678,'2026-05-13',1,'2026-05-13 09:31:14');
/*!40000 ALTER TABLE `inscricao_mod_socio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modalidade`
--

DROP TABLE IF EXISTS `modalidade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modalidade` (
  `id_mod` int(2) NOT NULL,
  `nome_mod` varchar(100) NOT NULL,
  `preco` decimal(10,2) NOT NULL DEFAULT 0.00,
  `preco_junior` decimal(10,2) NOT NULL DEFAULT 0.00,
  `preco_adulto` decimal(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id_mod`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modalidade`
--

LOCK TABLES `modalidade` WRITE;
/*!40000 ALTER TABLE `modalidade` DISABLE KEYS */;
INSERT INTO `modalidade` VALUES (11,'Futebol',19.99,9.99,19.99),(22,'Basquetebol',19.99,9.99,19.99),(33,'Natacao',14.99,9.99,14.99),(44,'Tenis',39.99,19.99,39.99),(55,'Badminton',39.99,19.99,39.99),(66,'Rugby',49.99,29.99,49.99);
/*!40000 ALTER TABLE `modalidade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pagamentos`
--

DROP TABLE IF EXISTS `pagamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pagamentos` (
  `id_pagamento` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_mod` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `metodo` varchar(50) NOT NULL,
  `data_pagamento` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'Conclu├¡do',
  PRIMARY KEY (`id_pagamento`),
  KEY `id_user` (`id_user`),
  KEY `id_mod` (`id_mod`),
  CONSTRAINT `pagamentos_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  CONSTRAINT `pagamentos_ibfk_2` FOREIGN KEY (`id_mod`) REFERENCES `modalidade` (`id_mod`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pagamentos`
--

LOCK TABLES `pagamentos` WRITE;
/*!40000 ALTER TABLE `pagamentos` DISABLE KEYS */;
INSERT INTO `pagamentos` VALUES (1,2,22,29.99,'MBWAY','2026-03-05 11:51:34','Conclu├¡do'),(2,2,22,29.99,'MBWAY','2026-03-05 11:56:24','Conclu├¡do'),(3,2,22,29.99,'MBWAY','2026-03-05 11:57:03','Conclu├¡do'),(4,2,22,29.99,'MBWAY','2026-03-05 11:57:33','Conclu├¡do');
/*!40000 ALTER TABLE `pagamentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedidos_treino`
--

DROP TABLE IF EXISTS `pedidos_treino`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pedidos_treino` (
  `id_pedido` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `id_mod` int(11) DEFAULT NULL,
  `estado` enum('pendente','aprovado','rejeitado') DEFAULT 'pendente',
  `data_pedido` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_pedido`),
  KEY `id_user` (`id_user`),
  KEY `id_mod` (`id_mod`),
  CONSTRAINT `pedidos_treino_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  CONSTRAINT `pedidos_treino_ibfk_2` FOREIGN KEY (`id_mod`) REFERENCES `modalidade` (`id_mod`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidos_treino`
--

LOCK TABLES `pedidos_treino` WRITE;
/*!40000 ALTER TABLE `pedidos_treino` DISABLE KEYS */;
/*!40000 ALTER TABLE `pedidos_treino` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `propostas_treino`
--

DROP TABLE IF EXISTS `propostas_treino`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `propostas_treino` (
  `id_proposta` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_mod` int(11) NOT NULL,
  `aprovado` tinyint(1) DEFAULT 0,
  `data_pedido` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_proposta`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `propostas_treino`
--

LOCK TABLES `propostas_treino` WRITE;
/*!40000 ALTER TABLE `propostas_treino` DISABLE KEYS */;
INSERT INTO `propostas_treino` VALUES (1,3,22,1,'2026-03-06 13:09:59'),(2,3,11,1,'2026-04-24 08:35:28'),(5,3,33,1,'2026-04-24 18:13:14'),(6,3,22,1,'2026-05-13 09:26:24');
/*!40000 ALTER TABLE `propostas_treino` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `socios`
--

DROP TABLE IF EXISTS `socios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `socios` (
  `num_socio` int(11) NOT NULL AUTO_INCREMENT,
  `nome_socio` varchar(100) NOT NULL,
  `email_socio` varchar(100) NOT NULL,
  `data_nasc_socio` date NOT NULL,
  `telefone_socio` int(9) NOT NULL,
  `cont_socio` int(9) DEFAULT NULL,
  PRIMARY KEY (`num_socio`)
) ENGINE=InnoDB AUTO_INCREMENT=686 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `socios`
--

LOCK TABLES `socios` WRITE;
/*!40000 ALTER TABLE `socios` DISABLE KEYS */;
INSERT INTO `socios` VALUES (2,'Duarte Rafael','infor.pedrorafael@gmail.com','2008-09-16',925514787,986543412),(111,'Duarte Rafael','infor.pedrorafael@gmail.com','2008-09-16',925514787,225298210),(222,'Maria Ines','mariainesivone@gmail.com','2012-05-03',961432654,210243213),(333,'Felipe Marques','pai_de_todos@gmail.com','2000-07-12',924163847,214564542),(444,'Beatriz Fernandes','BeatrizFndes@gmail.com','2004-10-14',910573413,241256754),(555,'Miguel Alves','Malves.comp@gmail.com','2008-10-17',963762898,224286751),(666,'Marta Figueiredo','MartaFig2005@gmail.com','2006-03-25',942643854,274975126),(667,'Duarte Rafael','infor.pedrorafael@gmail.com','2008-09-16',925514787,124141241),(668,'Duarte Rafael','infor.pedrorafael@gmail.com','2008-09-16',925514787,552341421),(669,'Duarte Rafael','infor.pedrorafael@gmail.com','2008-09-16',925514787,241411241),(670,'Rafael Raposo','28187@aeamatolusitano.edu.pt','2008-03-27',111111222,676767676),(671,'Fernando Estevez','melhordelmundo@gmail.com','2000-06-06',967679897,123456789),(672,'Duarte Rafael','infor.pedrorafael@gmail.com','2008-09-16',925514787,452523523),(673,'Duarte Raposo','infor.pedrorafael@gmail.com','2008-09-16',925514787,344242342),(674,'Duarte Rafael','infor.pedrorafael@gmail.com','2008-09-16',925514787,435353555),(675,'Duarte Rafael','infor.pedrorafael@gmail.com','2008-09-16',925514787,123456789),(676,'Rafael Raposo','28187@aeamatolusitano.edu.pt','1999-04-12',95422532,234567890),(677,'Rafael Raposo','28187@aeamatolusitano.edu.pt','1999-04-12',95422532,234567890),(678,'Duarte Rafael','infor.pedrorafael@gmail.com','2008-09-16',925514787,12345678),(679,'Duarte Rafael','infor.pedrorafael@gmail.com','2008-09-16',925514787,12345678),(680,'Duarte Rafael','infor.pedrorafael@gmail.com','2008-09-16',925514787,12345678),(681,'Duarte Rafael','infor.pedrorafael@gmail.com','2008-09-16',925514787,12345678),(682,'Duarte Rafael','infor.pedrorafael@gmail.com','2008-09-16',925514787,12345678),(683,'Duarte Rafael','infor.pedrorafael@gmail.com','2008-09-16',925514787,12345678),(684,'Rafael Rafael','28187@aeamatolusitano.edu.pt','2008-05-15',954225327,123123213),(685,'Duarte Rafael','infor.pedrorafael@gmail.com','2008-09-16',925514787,12345678);
/*!40000 ALTER TABLE `socios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id_user` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `cargo` int(11) DEFAULT 1,
  `num_socio` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  KEY `fk_user_socio` (`num_socio`),
  CONSTRAINT `fk_user_socio` FOREIGN KEY (`num_socio`) REFERENCES `socios` (`num_socio`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'ze','asdadad@gmail.com','e10adc3949ba59abbe56e057f20f883e',1,NULL),(2,'Duarte Rafael','infor.pedrorafael@gmail.com','$2y$10$ciwCpCOzkhGoCIsNZkQpUOliZd1zyzVQT7H3jIsvAAU9IAmLdOBoq',3,2),(3,'Maria In├¬s','masterzimtop@gmail.com','$2y$10$GHUTRvrzPdriiMOeWbbfouwhUXR6/xqzjTLvrI51fFSl2FF71mKyu',2,NULL),(4,'Rafael Raposo','28187@aeamatolusitano.edu.pt','$2y$10$pt2E8T/M9tUdEcfeMZ9F5OJ4RijRPTySmTupzHo2ERp97jRmn6BNq',1,NULL),(5,'Fernando Estevez','melhorstordelmundo@gmail.com','$2y$10$MLm1/yfRtUy8tv3KO71UC.7Ycm/IWPESi6uV49LihY6vSv/nRYXx.',1,NULL),(6,'manel guterres','123123123123123@gmail.com','$2y$10$07dzXFYbWVzCVrQ6ZMIsm.isal70sOzWHibL/OUaj02F9HoU5CVmW',2,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-13 10:36:49
