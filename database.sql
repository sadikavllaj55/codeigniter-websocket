-- MySQL dump 10.13  Distrib 8.0.28, for Linux (x86_64)
--
-- Host: localhost    Database: igniter
-- ------------------------------------------------------
-- Server version	8.0.28-0ubuntu0.21.10.3

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
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `parent` int DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `departments_id_uindex` (`id`),
  KEY `departments_parent_index` (`parent`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'Main Office Tirane',NULL,'2022-04-17 14:42:52'),(2,'Human Resources',1,'2022-04-17 14:42:52'),(3,'Help Desk',1,'2022-04-17 14:42:52'),(4,'IT',1,'2022-04-17 14:42:52'),(5,'Frontend',4,'2022-04-17 14:42:52'),(6,'Backend IT',4,'2022-04-17 14:42:52'),(7,'Customer Support',3,'2022-04-17 14:44:10'),(8,'PHP Department',6,'2022-04-17 14:44:25'),(10,'Core Team',6,'2022-04-17 14:45:26'),(11,'Library Team',6,'2022-04-17 14:45:26'),(12,'Team 1',NULL,'2022-04-17 14:45:26'),(13,'Maintenance',NULL,'2022-04-17 14:45:55'),(14,'Frontend 2',NULL,'2022-04-20 19:42:10'),(15,'Frontend 3',14,'2022-04-20 19:42:44');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender` int NOT NULL,
  `recipient` int NOT NULL,
  `message` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (2,44,14,'Hello\n','2022-04-20 20:16:45'),(3,44,11,'Hi Sadik\n','2022-04-20 20:18:15');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(128) NOT NULL,
  `token` varchar(32) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
INSERT INTO `password_resets` VALUES (2,'sadik@igniter.web','naedfXpBQ86ZqSrPT9H1I2lkFAO0DVbK','2022-04-14 18:58:46');
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_id_uindex` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Employee'),(2,'Administrator');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role_id` int NOT NULL DEFAULT '1',
  `department_id` int DEFAULT NULL,
  `profile_image` varchar(256) DEFAULT 'uploads/profile.png',
  `verification_key` varchar(32) DEFAULT NULL,
  `email_verified` tinyint(1) NOT NULL DEFAULT '0',
  `telephone` varchar(16) DEFAULT NULL,
  `website` varchar(32) DEFAULT NULL,
  `address` varchar(128) DEFAULT NULL,
  `city` varchar(32) DEFAULT NULL,
  `country` varchar(64) DEFAULT NULL,
  `zipcode` varchar(16) DEFAULT NULL,
  `bio` text,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_uindex` (`email`),
  UNIQUE KEY `users_id_uindex` (`id`),
  KEY `users_roles_id_fk` (`role_id`),
  KEY `users_department_id_index` (`department_id`),
  CONSTRAINT `users_roles_id_fk` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (11,'Sadik Avllaj','sadik@igniter.web','$2y$10$tkNRm6xOdNMdbRfphLrKTu/3JCdYimLvH9o96yld57UoZs.vnOZAu',2,3,'uploads/20220419/1650394794_dfa475301fafda639e87.png','',1,'+355674395111','http://sadikavllaj.com','','','','','Hello,\r\nI&#039;m Sadik. I love PHP.','2022-04-14 18:23:57'),(12,'Sadik','sadikavllaj@greatx.web','$2y$10$1BTMd6Z8QH9v4xvuNFoNeuBSLJxTSv5d2t094cppiDQ4iVok6EaWm',1,NULL,'uploads/profile.png','',1,'','','','','','',NULL,'2022-04-14 18:23:57'),(14,'New User','user1@igniter.web','$2y$10$IrTNwK/rzRZbRLhDI7e5se0fRljzL6RTVeFR2Ign.uAyBcIoCyWIm',1,3,'uploads/profile.png','CUX9zd6RkwKemPiND801r53uBlqahJLv',0,'','','Rruga Varosh','Kruje','Shqiperi','1001','','2022-04-17 14:57:22'),(15,'User 2','user2@igniter.web','$2y$10$WaVvtXqWxzOziLyLjAFz.uPbtt5TFm0fug2B6PUMuWzP7LGm2wDre',1,13,'uploads/profile.png','hdtJgKyBX6Q8MrWjn3b9iAoRZ7GY5mPE',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2022-04-17 14:57:22'),(16,'User 3','user3@igniter.web','$2y$10$oXBWWOZM5JUE3cJOr2YbL.JnmsW3xNQBT.bIljV7p5pSgzjHi4HgG',1,10,'uploads/profile.png','sb3dIjEPx4C8tlLKkpy7HV1FBeaNRWnc',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2022-04-17 14:57:22'),(17,'User 4','user4@igniter.web','$2y$10$u2FmE7ZFBgZuhwX5nNwQbuoPdppkUq6bgpRqogLeF48sBwgPMH6cm',1,3,'uploads/profile.png','PvpKGTd3ieku7MS0z6FEYfLVOIlA8gC2',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2022-04-17 14:57:22'),(18,'User 5','user5@igniter.web','$2y$10$J5vtx3JTDpJCJPhnNU20AeRJWDzZ6J.d5mXKWuvEbNIplI9QTOPhW',1,11,'uploads/profile.png','hr6LU4obsDTIE3imYZXezqP9RFHufCJ5',0,'','','','','','','','2022-04-17 14:57:23'),(19,'User 6','user6@igniter.web','$2y$10$W03bASMKGOfrbwnKh637ZuLqweHt7ICqOd24rYp61ugsyrLPt1WAu',1,8,'uploads/profile.png','rTXk3JHiA9pyM5tqamPzVf2vduRUElsZ',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2022-04-17 14:57:23'),(20,'User 7','user7@igniter.web','$2y$10$KZGjnSWplk7QdV1eRUJIueT.aLr9q/jsF8BSY4lSCDuCkOh211sY6',1,4,'uploads/profile.png','Goz0X6ricxlHTOqMN1pDBfSvAnhZ3wCR',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2022-04-17 14:57:23'),(22,'User 9','user9@igniter.web','$2y$10$7P6uSHMaO8r2ONNtWKgWRutvWPkvcvPq.RukBWcS1KLE4Zv5i7Ni6',1,12,'uploads/profile.png','Gbc0yjMHJvFkDNVwT7ziW3O8mxIgSnQ2',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2022-04-17 14:57:23'),(23,'Reception','user10@igniter.web','$2y$10$quht1a2CWF1T9ouowJn60evHub8Zysoz9m/zC8cTBJo0kyeTWWBYe',1,13,'uploads/profile.png','Okw2eNgVTY9GI8Jjlfas4v0AbzPr6ZiU',0,'+675454533','','','','','',NULL,'2022-04-17 14:57:23'),(24,'User 11','user11@igniter.web','$2y$10$C/M435PdaD6KDE.hL2vytu4QdwN.jQjGEWrOogJ9IJcJDY8ifdoWe',1,6,'uploads/profile.png','aJ2iljnoftRbH18zmCI5h7TdQxWG0Dce',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2022-04-17 14:57:23'),(25,'User 12','user12@igniter.web','$2y$10$ep5YpdPQs/oWUYE7gU.zdOrqvpXn6gkL9CO0qBF7jVdSVl/RTbnZ6',1,10,'uploads/profile.png','hHXaflAwNeCnQZj1qT0GE9ioROJc236W',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2022-04-17 14:57:23'),(26,'User 13','user13@igniter.web','$2y$10$fnH/9SFcHVr9SVEl.l8mHuCNF5YzBZIpKOXm27nZwROf6lk0BR2qm',1,12,'uploads/profile.png','E3z1iZ6AhslfpTPIeyRjKO04XUYgotxQ',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2022-04-17 14:57:23'),(27,'User 14','user14@igniter.web','$2y$10$uUHx11mqSaqAjy138x7dteGhserTyIJUYsD6R1BfGoOUeo4jJl/5K',1,10,'uploads/profile.png','seiZLdynA3CBkMHurVv1xzlGjo8WUYQN',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2022-04-17 14:57:23'),(28,'User 15','user15@igniter.web','$2y$10$ZZ1UiKph6SB9hXCFNlaxR.tadJGYMeEC1OGCZsRreInKeIy7GKV9i',1,7,'uploads/profile.png','CXKBFTctD5IY9p0yN2QOnwEuxbL6MZGa',0,'','','','','','','','2022-04-17 14:57:23'),(29,'User 16','user16@igniter.web','$2y$10$ElL6gLMynVKNorrmAjl/ieMnp2pLpqvN2jmZWSoc7dLeM/1zzg0/K',1,5,'uploads/profile.png','K63E5QlPV1vHt0BjJc9UXyo4SAGb2LeM',0,'','','','','','','','2022-04-17 14:57:23'),(30,'User 17','user17@igniter.web','$2y$10$tozG0CnrQOojwW3.uAtPY.dbs1W/17cH8GXjDqW9w26CSQPOqlAJi',1,7,'uploads/profile.png','ji6bgEcoPxGTVzmBvnaqFWN0YlhHs5IK',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2022-04-17 14:57:23'),(31,'User 18','user18@igniter.web','$2y$10$hIEh8.uvonqgqUA3I9LtfeRvWPcLa1u9o/skMURONbUZ7r3I4e9kq',1,3,'uploads/profile.png','o6ap9ZVOdl7keHiBMzsQR8yUCJ1AhX0W',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2022-04-17 14:57:23'),(32,'User 19','user19@igniter.web','$2y$10$3FXNYZQ9wsUQKYYkyuZU9.dVLOv4VHtZhGXZOmPuTczMwoy9h4MEK',1,5,'uploads/profile.png','FvKifzxHOoqXcwJkTV4spLCAEd50hNSW',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2022-04-17 14:57:24'),(33,'User 20','user20@igniter.web','$2y$10$PSj1MB2bKC0Zvt.M8sZpkeOWEyAW..hsR943C4lwhr2jvuM8nCgsa',1,5,'uploads/profile.png','tsdmuEaY8rcQ3bg6xlPe7zMLFBWqAGRX',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2022-04-17 14:57:24'),(34,'User 21','user21@igniter.web','$2y$10$JHaXry0f5u42mUuUzZpKLe2J9DSfdJHltGIUGCg0fEqOaoUKHJ8JC',1,NULL,'uploads/profile.png','jne3K2rE18Y69tpDT7lJhkGIzRvCaBSq',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2022-04-17 14:57:24'),(35,'User 22','user22@igniter.web','$2y$10$t9ClXWC2sbNJXP0cgLaVZuOd767KquZFUy8b2QrCa8mF83h9A4KSq',1,NULL,'uploads/profile.png','VhFZ0QTJEd3mULk7MAa49GcOWYji5SnP',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2022-04-17 14:57:24'),(36,'User 23','user23@igniter.web','$2y$10$NwMlK5GOyFwdBgl.UqZlieqXt26zYJogTP1UIObo3ysBQiN/xMCCe',1,12,'uploads/profile.png','8a3qpwiA7oGLXHkOeQjzP1mvBncE5Cux',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2022-04-17 14:57:24'),(37,'User 24','user24@igniter.web','$2y$10$Y7c4Sohhclv0.xhiJ7IDpeRGSLZjrQ5pj98Ng.Qu4.gbCTzl406ta',1,11,'uploads/profile.png','pWFzsMyjwufaRDCebY3KVUQcJOPqkHrL',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2022-04-17 14:57:24'),(38,'User 25','user25@igniter.web','$2y$10$BMKugOQLhbDFQ6SkHSeaIevg7MYGD8a5RtMZlkV9x3Xvi8ioKeYYW',1,2,'uploads/profile.png','8ZzErRMqTCnxWQweLydY9NhmgvbIGtUS',0,'','','','','','','New Bio','2022-04-17 14:57:24'),(39,'User 26','user26@igniter.web','$2y$10$y3QoFc9WsJzIcd3K7HoDzuayGCArU/CQvF7tUwThMhhcv85d9Gtou',1,6,'uploads/profile.png','eHgXaM8nBPT135h72tuYcLf0y4zowGDx',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2022-04-17 14:57:24'),(40,'User 27','user27@igniter.web','$2y$10$0wJddy3XwiiM6xjhCVumEO9t7GD776DQSptORrZZG1GEQT7TZ9i5q',1,7,'uploads/profile.png','Uq3TWlkwSe2RYxuGvhQ08A9o6ZiLB5cP',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2022-04-17 14:57:24'),(41,'User 28','user28@igniter.web','$2y$10$HRokic1ifMkDGqnExh0jvu3Oy2Wm3vr/rxTodSmZiYTc0RxWpdwRW',1,8,'uploads/profile.png','vkmNyS95MTXjJWFOItoEeGh6gCYwKUxn',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2022-04-17 14:57:24'),(42,'User 29','user29@igniter.web','$2y$10$29N7hGiZw3.i4jtRx890bO3EQs4JhnkdZCD2bDWI/a0Lnvu.yvrny',1,4,'uploads/profile.png','BrjYhVF15Nk4dDGzx0eSMfu9KRbqZcOA',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2022-04-17 14:57:24'),(43,'User 30','user30@igniter.web','$2y$10$QMakPvpxFT/i9HN22ggLf.R5BSVqD6CF1ADlmfILJBBNjuiVktZ4S',1,12,'uploads/profile.png','CP58l7b0EztJDsLVkjZmHI9WYSh4dp1N',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2022-04-17 14:57:24'),(44,'New User','user_new@email.com','$2y$10$DOKOc03JAfWlBW5LzP1Q8e4LSD2MgQCHghEN5brGHQQib/GGZWX5W',1,1,'uploads/profile.png','',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2022-04-20 19:49:01');
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

-- Dump completed on 2022-04-20 22:32:38
