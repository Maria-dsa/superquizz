-- SQLBook: Code
-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: superquizz
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `answer`
--
SET GLOBAL time_zone = "+00:00";
DROP TABLE IF EXISTS `answer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `answer` (
  `id` int NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `is_correct` tinyint(1) NOT NULL,
  `question_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_answer_question` (`question_id`),
  CONSTRAINT `fk_answer_question` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `answer`
--

LOCK TABLES `answer` WRITE;
/*!40000 ALTER TABLE `answer` DISABLE KEYS */;
INSERT INTO `answer` VALUES (1,'bleu',0,1),(2,'rouge',1,1),(3,'vert',0,1),(4,'jaune',0,1),(5,'PHP',1,2),(6,'JavaScript',0,2),(7,'Java',0,2),(8,'C#',0,2),(9,'Agglomérer',1,3),(10,'Disséminer',0,3),(11,'Répandre',0,3),(12,'Eparpiller',0,3),(13,'démonte-pneu',1,4),(14,'Cul-de-poule',0,4),(15,'ouvre-boîte',0,4),(16,'lèchefrite',0,4),(17,'Leurs testicules',1,5),(18,'Un album d’Astérix',0,5),(19,'Les lauriers de César',0,5),(20,'Une Bible, comme tout le monde',0,5),(21,'Il y a deux couilles sous la bite',1,6),(22,'Le Pont-Neuf fait soixante-pieds',0,6),(23,'Les nouilles cuisent au jus de cane',0,6),(24,'Les laborieuses populations du Cap',0,6),(25,'Le requin-citron',1,7),(26,'Le requin-fraise',0,7),(27,'Le requin-banane',0,7),(28,'Le requin-chocolat-pistache',0,7),(29,'La Mazda « Laputa »',1,8),(30,'La Fiat « 500 l’amour et 200 la pipe »',0,8),(31,'La Nissan « Gigolo »',0,8),(32,'La Skoda « Tapina »',0,8),(33,'Charcutier',1,9),(34,'Philosophie orientale',0,9),(35,'Menuisier',0,9),(36,'Reconnaissance en paternité',0,9),(37,'Brelan',1,10),(38,'La quinte',0,10),(39,'La quinte flush',0,10),(40,'Le full',0,10),(41,'Russie',1,11),(42,'Arménie',0,11),(43,'Bulgarie',0,11),(44,'Pays-Bas',0,11),(45,'Herbert George Wells',1,12),(46,'Jules Verne',0,12),(47,'Jack London',0,12),(48,'Aldous Huxley',0,12),(49,'le printemps',1,13),(50,'l\'amour',0,13),(51,'le bonheur',0,13),(52,'la vaisselle',0,13),(53,'Christopher Lloyd',1,14),(54,'Bob Odenkirk',0,14),(55,'Robert Zemeckis',0,14),(56,'Bryan Cranston',0,14),(57,'1927',1,15),(58,'1924',0,15),(59,'1934',0,15),(60,'1939',0,15),(61,'les muscles oculaires',1,16),(62,'les muscles de la langue',0,16),(63,'les muscles du dos',0,16),(64,'les muscles des bras',0,16),(65,'8min et 20sec',1,17),(66,'35sec',0,17),(67,'3min et 15sec',0,17),(68,'17min et 40sec',0,17),(69,'82',1,18),(70,'27',0,18),(71,'45',0,18),(72,'108',0,18),(73,'F',1,19),(74,'E',0,19),(75,'G',0,19),(76,'H',0,19),(77,'7',1,20),(78,'5',0,20),(79,'6',0,20),(80,'8',0,20),(81,'Henri Becquerel',1,21),(82,'Frédéric Joliot-Curie',0,21),(83,'Wilhelm Röntgen',0,21),(84,'John Dalton',0,21),(85,'de la relativité',1,22),(86,'de l\'évolution',0,22),(87,'de l\'espace temps',0,22),(88,'du complot',0,22),(89,'C',1,23),(90,'A',0,23),(91,'B',0,23),(92,'D',0,23),(93,'L\'ananas',1,24),(94,'Le corossol',0,24),(95,'Le ramboutan',0,24),(96,'Le cajou',0,24),(97,'M',1,25),(98,'R',0,25),(99,'P',0,25),(100,'E',0,25),(101,'L\'herpétologie',1,26),(102,'La reptilogie',0,26),(103,'La serpentologie',0,26),(104,'L\'hichyologie',0,26),(105,'Jakarta',1,27),(106,'Tokyo',0,27),(107,'Manille',0,27),(108,'Kuala Lumpur',0,27),(109,'Paramaribo',1,28),(110,'Georgetown',0,28),(111,'Asmara',0,28),(112,'Roseau',0,28),(113,'Irak',1,29),(114,'Iran',0,29),(115,'Italie',0,29),(116,'Syrie',0,29),(117,'Nuuk',1,30),(118,'Muuk',0,30),(119,'Buuk',0,30),(120,'Puuk',0,30),(121,'Alaska',1,31),(122,'Wyoming',0,31),(123,'Nevada',0,31),(124,'Oregon',0,31),(125,'Les îles Caïmans',1,32),(126,'Belize',0,32),(127,'Antigua et Barbuda',0,32),(128,'Trinité et Tobago',0,32);
/*!40000 ALTER TABLE `answer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game`
--

DROP TABLE IF EXISTS `game`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `game` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` int DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `endedAt` datetime DEFAULT NULL,
  `userId` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `game_user` (`userId`),
  CONSTRAINT `game_user` FOREIGN KEY (`userId`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game`
--

LOCK TABLES `game` WRITE;
/*!40000 ALTER TABLE `game` DISABLE KEYS */;
INSERT INTO `game` VALUES (1,1,'2022-11-10 10:39:43','2022-11-10 10:40:19',6),(2,1,'2022-11-10 10:40:37','2022-11-10 10:41:15',7),(3,1,'2022-11-10 10:41:38','2022-11-10 10:42:07',8),(4,1,'2022-11-10 10:42:29','2022-11-10 10:43:05',9),(5,1,'2022-11-10 10:43:32',NULL,10),(6,1,'2022-11-10 10:44:46','2022-11-10 10:45:16',11),(7,1,'2022-11-10 10:45:47',NULL,12),(8,1,'2022-11-10 10:46:59',NULL,13);
/*!40000 ALTER TABLE `game` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_has_question`
--

DROP TABLE IF EXISTS `game_has_question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `game_has_question` (
  `id` int NOT NULL AUTO_INCREMENT,
  `game_id` int NOT NULL,
  `question_id` int NOT NULL,
  `answer_id` int NOT NULL,
  `is_true` tinyint(1) NOT NULL,
  `time` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_game_has_question_game` (`game_id`),
  KEY `fk_game_has_question_question` (`question_id`),
  KEY `fk_game_has_question_answer` (`answer_id`),
  CONSTRAINT `fk_game_has_question_answer` FOREIGN KEY (`answer_id`) REFERENCES `answer` (`id`),
  CONSTRAINT `fk_game_has_question_game` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`),
  CONSTRAINT `fk_game_has_question_question` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_has_question`
--

LOCK TABLES `game_has_question` WRITE;
/*!40000 ALTER TABLE `game_has_question` DISABLE KEYS */;
INSERT INTO `game_has_question` VALUES (1,1,13,49,1,NULL),(2,1,30,118,0,NULL),(3,1,22,86,0,NULL),(4,1,26,104,0,NULL),(5,1,23,92,0,NULL),(6,1,6,24,0,NULL),(7,1,2,5,1,NULL),(8,1,12,46,0,NULL),(9,1,28,110,0,NULL),(10,1,15,59,0,NULL),(11,1,5,19,0,NULL),(12,1,31,122,0,NULL),(13,1,29,113,1,NULL),(14,1,19,76,0,NULL),(15,1,32,125,1,NULL),(16,2,22,86,0,NULL),(17,2,14,53,1,NULL),(18,2,27,105,1,NULL),(19,2,10,37,1,NULL),(20,2,7,26,0,NULL),(21,2,1,3,0,NULL),(22,2,9,33,1,NULL),(23,2,5,18,0,NULL),(24,2,18,69,1,NULL),(25,2,29,115,0,NULL),(26,2,26,101,1,NULL),(27,2,17,65,1,NULL),(28,2,8,30,0,NULL),(29,2,12,47,0,NULL),(30,2,20,80,0,NULL),(31,3,23,92,0,NULL),(32,3,28,110,0,NULL),(33,3,18,70,0,NULL),(34,3,6,24,0,NULL),(35,3,19,73,1,NULL),(36,3,16,61,1,NULL),(37,3,29,114,0,NULL),(38,3,8,31,0,NULL),(39,3,30,119,0,NULL),(40,3,2,7,0,NULL),(41,3,9,36,0,NULL),(42,3,20,79,0,NULL),(43,3,11,42,0,NULL),(44,3,5,20,0,NULL),(45,3,32,127,0,NULL),(46,4,1,3,0,NULL),(47,4,3,11,0,NULL),(48,4,17,66,0,NULL),(49,4,21,81,1,NULL),(50,4,25,98,0,NULL),(51,4,26,104,0,NULL),(52,4,11,41,1,NULL),(53,4,24,93,1,NULL),(54,4,4,13,1,NULL),(55,4,23,91,0,NULL),(56,4,8,32,0,NULL),(57,4,19,74,0,NULL),(58,4,27,108,0,NULL),(59,4,18,70,0,NULL),(60,4,12,45,1,NULL),(61,5,11,42,0,NULL),(62,5,10,37,1,NULL),(63,5,5,19,0,NULL),(64,5,26,102,0,NULL),(65,5,1,2,1,NULL),(66,5,32,127,0,NULL),(67,5,25,98,0,NULL),(68,5,28,112,0,NULL),(69,5,4,16,0,NULL),(70,5,18,71,0,NULL),(71,5,30,119,0,NULL),(72,5,19,76,0,NULL),(73,5,23,92,0,NULL),(74,6,31,123,0,NULL),(75,6,18,69,1,NULL),(76,6,22,87,0,NULL),(77,6,24,93,1,NULL),(78,6,7,27,0,NULL),(79,6,13,49,1,NULL),(80,6,27,105,1,NULL),(81,6,10,39,0,NULL),(82,6,20,79,0,NULL),(83,6,14,56,0,NULL),(84,6,12,47,0,NULL),(85,6,32,128,0,NULL),(86,6,4,15,0,NULL),(87,6,30,117,1,NULL),(88,6,17,67,0,NULL),(89,7,18,70,0,NULL),(90,7,1,1,0,NULL),(91,7,29,116,0,NULL),(92,7,20,79,0,NULL),(93,7,10,40,0,NULL),(94,7,7,27,0,NULL),(95,7,17,67,0,NULL),(96,7,21,84,0,NULL),(97,8,28,111,0,NULL),(98,8,18,72,0,NULL),(99,8,21,81,1,NULL),(100,8,8,30,0,NULL),(101,8,13,49,1,NULL),(102,8,5,17,1,NULL);
/*!40000 ALTER TABLE `game_has_question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `question`
--

DROP TABLE IF EXISTS `question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `question` (
  `id` int NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `theme` varchar(255) DEFAULT NULL,
  `difficulty_level` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `question`
--

LOCK TABLES `question` WRITE;
/*!40000 ALTER TABLE `question` DISABLE KEYS */;
INSERT INTO `question` VALUES (1,'Quelle est la couleur d\'une tomate mure ?','test','facile',NULL),(2,'Quelle est le meilleur langage de programmation ?','autre','difficile',NULL),(3,'Trouver l\'intrus parmi ces mots :','Logique','Facile',NULL),(4,'Un seul de ses ustensiles de cuisine à consonance sexuelle n\'existe pas, lequel ?','Humour','Facile',NULL),(5,'Dans l\'Antiquité, sur quoi les romains prêtaient-t-il serment ?','Humour','Difficile',NULL),(6,'Où n\'y a-t-il pas de contrepèterie ?','Humour','Difficile',NULL),(7,'Lequel de ces requins existe vraiment ?','Humour','Moyen',NULL),(8,'Parmi ces 4 voitures, laquelle a vraiment existé ?','Humour','Moyen',NULL),(9,'Alain Delon a un CAP de ?','Humour','Moyen',NULL),(10,'Au poker, comment appelle-t-on la réunion de trois cartes de même valeur ?','Culture Générale','Moyen',NULL),(11,'A quel pays est associé ce drapeau ?','Culture Générale','Facile',NULL),(12,'Qui est l\'auteur du roman \"La Guerre des mondes\", adapté au cinéma','Culture Générale','Difficile',NULL),(13,'D\'après le proverbe que ne fait pas une hirondelle','Culture Générale','Facile',NULL),(14,'Quel est le nom de cet acteur américain ?','Culture Générale','Moyen',NULL),(15,'En quelle année est arrivée le cinéma parlant ?','Culture Générale','Difficile',NULL),(16,'Quels sont les muscles les plus actifs dans la journée ?','Sciences','Moyen',NULL),(17,'Combien de temps met la lumière du soleil pour nous atteindre ?','Sciences','Difficile',NULL),(18,'Chassez l\'intrus...','Logique','Moyen',NULL),(19,'A ; b ; e ; c ; i ; d ; o ;','Logique','Difficile',NULL),(20,'(1 * 10) ; (2 * 9) ; (3 * 8) ; (4 * ? )','Logique','Facile',NULL),(21,'Quel grand physicien découvrit la radioactivité ?','Sciences','Difficile',NULL),(22,'Par quelle théorie Einstein révolutionna-t-il la physique ?','Sciences','Facile',NULL),(23,'Complétez cette suite logique. Quelle forme doit venir ensuite ?','Logique','Difficile',NULL),(24,'Lequel de ces fruits ne pousse pas dans un arbre','Sciences','Moyen',NULL),(25,'Comment compléter cette suite logique : S, O, N, D, J, F, ...','Culture Générale','Moyen',NULL),(26,'Comment s\'appelle la science qui étudie les serpents ?','Sciences','Difficile',NULL),(27,'Quelle est la capitale du plus grand archipel du monde, composé de plus de 17000 îles ?','Géographie','Moyen',NULL),(28,'Quelle est la capitale du Surinam ?','Géographie','Difficile',NULL),(29,'Quel est le pays dont la capitale arrosée par le Tigre et dont les habitants sont les Bagdadis ?','Géographie','Facile',NULL),(30,'Quelle est la capitale de la province autonome du Danemark, située dans l\'océan Atlantique ?','Géographie','Difficile',NULL),(31,'Dans quel état se situe le plus haut sommet des Etats-Unis ?','Géographie','Moyen',NULL),(32,'A quel pays appartient ce drapeau ?','Géographie','Difficile',NULL);
/*!40000 ALTER TABLE `question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `role` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'florent',NULL,NULL,'admin'),(2,'nicolas',NULL,NULL,'admin'),(3,'maria',NULL,NULL,'admin'),(4,'magali',NULL,NULL,'admin'),(5,'JF',NULL,NULL,'admin'),(6,'maria',NULL,NULL,NULL),(7,'newplayer',NULL,NULL,NULL),(8,'Florent',NULL,NULL,NULL),(9,'Magali',NULL,NULL,NULL),(10,'Nicolas',NULL,NULL,NULL),(11,'Victor',NULL,NULL,NULL),(12,'Hugo',NULL,NULL,NULL),(13,'Max',NULL,NULL,NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-10 11:07:13
LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'florent',NULL,NULL,'admin'),(2,'nicolas',NULL,NULL,'admin'),(3,'maria',NULL,NULL,'admin'),(4,'magali',NULL,NULL,'admin'),(5,'JF',NULL,NULL,'admin'),(6,'maria',NULL,NULL,NULL),(7,'newplayer',NULL,NULL,NULL),(8,'Florent',NULL,NULL,NULL),(9,'Magali',NULL,NULL,NULL),(10,'Nicolas',NULL,NULL,NULL),(11,'Victor',NULL,NULL,NULL),(12,'Hugo',NULL,NULL,NULL),(13,'Max',NULL,NULL,NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;



CREATE TABLE `theme` (
  `id` INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `content` VARCHAR(255) NOT NULL
);

INSERT INTO `theme` VALUES (1, 'Humour'), (2, 'Sciences'), (3, 'Logique'), (4, 'Géographie'), (5, 'Culture Générale');




/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-10 11:07:13

