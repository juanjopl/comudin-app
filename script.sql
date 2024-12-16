-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: localhost    Database: dbtfg
-- ------------------------------------------------------
-- Server version	8.0.40

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `instacomments`
--
CREATE DATABASE comudindb;
USE comudindb;

DROP TABLE IF EXISTS `instacomments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `instacomments` (
  `idinstacomment` int NOT NULL AUTO_INCREMENT,
  `comment` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `commentator` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `post` int NOT NULL,
  PRIMARY KEY (`idinstacomment`),
  UNIQUE KEY `idinstacomment_UNIQUE` (`idinstacomment`),
  KEY `fk_instacomments_post` (`post`),
  KEY `fk_instacomments_commentator_idx` (`commentator`),
  CONSTRAINT `fk_instacomments_commentator` FOREIGN KEY (`commentator`) REFERENCES `users` (`username`),
  CONSTRAINT `fk_instacomments_post` FOREIGN KEY (`post`) REFERENCES `instaposts` (`idinstapost`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instacomments`
--

LOCK TABLES `instacomments` WRITE;
/*!40000 ALTER TABLE `instacomments` DISABLE KEYS */;
/*!40000 ALTER TABLE `instacomments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instalikes`
--

DROP TABLE IF EXISTS `instalikes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `instalikes` (
  `idinstalikes` int NOT NULL AUTO_INCREMENT,
  `post` int NOT NULL,
  `idUser` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  PRIMARY KEY (`idinstalikes`),
  UNIQUE KEY `idinstalikes_UNIQUE` (`idinstalikes`),
  KEY `fk_instalikes_post` (`post`),
  KEY `fk_instalikes_idUser` (`idUser`),
  CONSTRAINT `fk_instalikes_idUser` FOREIGN KEY (`idUser`) REFERENCES `users` (`username`),
  CONSTRAINT `fk_instalikes_post` FOREIGN KEY (`post`) REFERENCES `instaposts` (`idinstapost`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instalikes`
--

LOCK TABLES `instalikes` WRITE;
/*!40000 ALTER TABLE `instalikes` DISABLE KEYS */;
/*!40000 ALTER TABLE `instalikes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instaposts`
--

DROP TABLE IF EXISTS `instaposts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `instaposts` (
  `idinstapost` int NOT NULL AUTO_INCREMENT,
  `photo` longblob NOT NULL,
  `description` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `ubication` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `idUser` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `likes` int DEFAULT NULL,
  PRIMARY KEY (`idinstapost`),
  UNIQUE KEY `idinstapost_UNIQUE` (`idinstapost`),
  KEY `fk_user_username` (`idUser`),
  CONSTRAINT `fk_user_username` FOREIGN KEY (`idUser`) REFERENCES `users` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instaposts`
--

LOCK TABLES `instaposts` WRITE;
/*!40000 ALTER TABLE `instaposts` DISABLE KEYS */;
/*!40000 ALTER TABLE `instaposts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `msg_id` int NOT NULL AUTO_INCREMENT,
  `incoming_msg_id` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `outgoing_msg_id` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `msg` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `state` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  PRIMARY KEY (`msg_id`),
  UNIQUE KEY `msg_id_UNIQUE` (`msg_id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shortcomments`
--

DROP TABLE IF EXISTS `shortcomments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shortcomments` (
  `idshortcomment` int NOT NULL AUTO_INCREMENT,
  `comment` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `commentator` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `post` int NOT NULL,
  PRIMARY KEY (`idshortcomment`),
  UNIQUE KEY `idshortcomment_UNIQUE` (`idshortcomment`),
  KEY `fk_shortcomments_post` (`post`),
  KEY `fk_shortcomments_commentator_idx` (`commentator`),
  CONSTRAINT `fk_shortcomments_commentator` FOREIGN KEY (`commentator`) REFERENCES `users` (`username`),
  CONSTRAINT `fk_shortcomments_post` FOREIGN KEY (`post`) REFERENCES `shortposts` (`idshortpost`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shortcomments`
--

LOCK TABLES `shortcomments` WRITE;
/*!40000 ALTER TABLE `shortcomments` DISABLE KEYS */;
/*!40000 ALTER TABLE `shortcomments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shortlikes`
--

DROP TABLE IF EXISTS `shortlikes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shortlikes` (
  `idshortlikes` int NOT NULL AUTO_INCREMENT,
  `post` int NOT NULL,
  `idUser` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  PRIMARY KEY (`idshortlikes`),
  UNIQUE KEY `idshortlikes_UNIQUE` (`idshortlikes`),
  KEY `fk_shortlikes_post` (`post`),
  KEY `fk_shortlikes_idUser` (`idUser`),
  CONSTRAINT `fk_shortlikes_idUser` FOREIGN KEY (`idUser`) REFERENCES `users` (`username`),
  CONSTRAINT `fk_shortlikes_post` FOREIGN KEY (`post`) REFERENCES `shortposts` (`idshortpost`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shortlikes`
--

LOCK TABLES `shortlikes` WRITE;
/*!40000 ALTER TABLE `shortlikes` DISABLE KEYS */;
/*!40000 ALTER TABLE `shortlikes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shortposts`
--

DROP TABLE IF EXISTS `shortposts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shortposts` (
  `idshortpost` int NOT NULL AUTO_INCREMENT,
  `message` varchar(1000) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `idUser` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `likes` int NOT NULL,
  `comments` int NOT NULL,
  PRIMARY KEY (`idshortpost`),
  UNIQUE KEY `idshortpost_UNIQUE` (`idshortpost`),
  KEY `fk_shortposts_users` (`idUser`),
  CONSTRAINT `fk_shortposts_users` FOREIGN KEY (`idUser`) REFERENCES `users` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shortposts`
--

LOCK TABLES `shortposts` WRITE;
/*!40000 ALTER TABLE `shortposts` DISABLE KEYS */;
/*!40000 ALTER TABLE `shortposts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tracking`
--

DROP TABLE IF EXISTS `tracking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tracking` (
  `idTrack` int NOT NULL AUTO_INCREMENT,
  `follower` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `following` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  PRIMARY KEY (`idTrack`),
  UNIQUE KEY `idTrack_UNIQUE` (`idTrack`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tracking`
--

LOCK TABLES `tracking` WRITE;
/*!40000 ALTER TABLE `tracking` DISABLE KEYS */;
INSERT INTO `tracking` VALUES (59,'juanjo1','juanjo');
/*!40000 ALTER TABLE `tracking` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `idUser` int NOT NULL AUTO_INCREMENT,
  `username` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `password` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `name` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `region` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `foto` longblob,
  `followers` int NOT NULL,
  `following` int NOT NULL,
  `posts` int NOT NULL,
  PRIMARY KEY (`idUser`),
  UNIQUE KEY `idUser_UNIQUE` (`idUser`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
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

-- Dump completed on 2024-12-10 10:55:38
