-- MySQL dump 10.18  Distrib 10.3.27-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: checkingboard
-- ------------------------------------------------------
-- Server version	10.3.27-MariaDB-0+deb10u1

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
-- Table structure for table `change_email_verifications`
--

DROP TABLE IF EXISTS `change_email_verifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `change_email_verifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `token` text NOT NULL,
  `expired` datetime NOT NULL,
  `email` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `change_email_verifications_FK` (`user_id`),
  CONSTRAINT `change_email_verifications_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `change_email_verifications`
--

LOCK TABLES `change_email_verifications` WRITE;
/*!40000 ALTER TABLE `change_email_verifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `change_email_verifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `completed_tasks`
--

DROP TABLE IF EXISTS `completed_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `completed_tasks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `task_id` bigint(20) unsigned NOT NULL,
  `score` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `completed_tasks_FK` (`user_id`),
  KEY `completed_tasks_FK_1` (`task_id`),
  CONSTRAINT `completed_tasks_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `completed_tasks_FK_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `completed_tasks`
--

LOCK TABLES `completed_tasks` WRITE;
/*!40000 ALTER TABLE `completed_tasks` DISABLE KEYS */;
INSERT INTO `completed_tasks` VALUES (14,21,6,0),(18,23,7,0),(20,23,16,1),(21,21,16,1),(23,23,13,2),(24,21,13,0);
/*!40000 ALTER TABLE `completed_tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `courses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `password` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES (15,'dgs','sdg'),(19,'dvsvsdv','$2y$10$OUFrSmtTMittSHdsMEpZSuTAcZRFK5aCWr8oX/2FI8PLiNDpzAyNe'),(20,'wqdq','$2y$10$MnBoZHNsNHhvRVBZY3BEcOZlv3D/X/Asc7.Fqap99.pUOxCYYi7Yq'),(21,'Курс 1','$2y$10$c2tBTmtlb21wcXkyTkRwb.IShkJV26UI2HivdKTlCAAIi/wzRjDmi');
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courses_users`
--

DROP TABLE IF EXISTS `courses_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `courses_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nickname` text NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `course_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `courses_users_FK` (`role_id`),
  KEY `courses_users_FK_1` (`user_id`),
  KEY `courses_users_FK_2` (`course_id`),
  CONSTRAINT `courses_users_FK` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `courses_users_FK_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `courses_users_FK_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses_users`
--

LOCK TABLES `courses_users` WRITE;
/*!40000 ALTER TABLE `courses_users` DISABLE KEYS */;
INSERT INTO `courses_users` VALUES (63,'Препод',3,20,19),(69,'Вадим Городов',1,21,19),(71,'Юлия Петрова',1,23,19),(72,'qwdq',3,20,20),(74,'Препод 2',2,24,19),(75,'Вячеслав Давыдов',3,20,21);
/*!40000 ALTER TABLE `courses_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_verifications`
--

DROP TABLE IF EXISTS `email_verifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_verifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `token` text NOT NULL,
  `expired` datetime NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=246 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_verifications`
--

LOCK TABLES `email_verifications` WRITE;
/*!40000 ALTER TABLE `email_verifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `email_verifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `materials`
--

DROP TABLE IF EXISTS `materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `materials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `date_time` datetime NOT NULL,
  `course_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `materials_FK` (`course_id`),
  CONSTRAINT `materials_FK` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materials`
--

LOCK TABLES `materials` WRITE;
/*!40000 ALTER TABLE `materials` DISABLE KEYS */;
INSERT INTO `materials` VALUES (83,'erg','<p>reg</p>','2021-05-11 13:19:53',19),(84,'asdas','<p>asdadsa</p>','2021-05-11 14:34:48',19),(85,'sdfs','<p>sdfsf</p>','2021-05-11 14:35:24',19),(86,'sada','<p>sdfsf</p>','2021-05-11 14:35:52',19),(87,'hello!dfbf','<p><strong>hello!fdbfdb</strong></p>','2021-05-11 14:36:27',19);
/*!40000 ALTER TABLE `materials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `materials_files`
--

DROP TABLE IF EXISTS `materials_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `materials_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `location` text NOT NULL,
  `material_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `materials_files_FK` (`material_id`),
  CONSTRAINT `materials_files_FK` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materials_files`
--

LOCK TABLES `materials_files` WRITE;
/*!40000 ALTER TABLE `materials_files` DISABLE KEYS */;
INSERT INTO `materials_files` VALUES (27,'/var/www/html/app/storage/materials/549fb7aef9488e9b0fcb3902bf3109414c655e21b01bc8676d9c8e390c4b54bc__-__Untitled Document',87);
/*!40000 ALTER TABLE `materials_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` bigint(20) unsigned NOT NULL,
  `text` text NOT NULL,
  `date_time` datetime NOT NULL,
  `task_id` bigint(20) unsigned NOT NULL,
  `receptionist_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `messages_FK_1` (`task_id`),
  KEY `messages_FK_2` (`sender_id`),
  KEY `messages_FK` (`receptionist_id`),
  CONSTRAINT `messages_FK` FOREIGN KEY (`receptionist_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `messages_FK_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `messages_FK_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=208 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (61,23,'Юлия Петрова отправил(а) файлы на проверку','2021-05-17 16:56:02',6,20),(62,23,'tyjtjt','2021-05-17 16:57:06',6,20),(63,20,'хорошо\n','2021-05-17 16:57:13',6,23),(65,20,'hgjhjmbn hjb ','2021-05-18 06:43:20',6,23),(66,23,'hjgjhgjhhgjgjghj','2021-05-18 06:43:25',6,20),(67,23,'Юлия Петрова отменил(а) отправку файлов на проверку','2021-05-18 08:51:19',6,20),(68,23,'Юлия Петрова отправил(а) файлы на проверку','2021-05-18 13:19:51',7,20),(69,23,'Юлия Петрова отменил(а) отправку файлов на проверку','2021-05-18 13:22:33',7,20),(70,23,'Юлия Петрова отправил(а) файлы на проверку','2021-05-18 13:45:29',7,20),(71,20,'супер!!!','2021-05-18 13:57:16',7,23),(72,23,'Юлия Петрова отменил(а) отправку файлов на проверку','2021-05-18 13:57:29',7,20),(74,24,'hello','2021-05-18 14:28:52',6,23),(75,24,'how are you','2021-05-18 14:28:58',6,23),(76,23,'Юлия Петрова отправил(а) файлы на проверку','2021-05-19 09:28:44',6,20),(77,20,'hrt','2021-05-19 09:37:18',6,23),(78,20,'rthr','2021-05-19 09:39:07',6,23),(83,20,'Препод принял(а) задание','2021-05-19 10:10:48',6,23),(92,20,'Препод принял(а) задание','2021-05-19 12:20:03',13,23),(93,20,'Препод отменил(а) приём задания','2021-05-19 12:26:36',13,23),(94,20,'Препод принял(а) задание','2021-05-19 12:26:48',13,23),(95,20,'Препод принял(а) задание','2021-05-20 08:30:59',13,21),(96,20,'Препод отменил(а) приём задания','2021-05-20 08:42:05',13,23),(97,20,'Препод принял(а) задание','2021-05-20 08:42:10',13,23),(98,20,'Препод отменил(а) приём задания','2021-05-20 12:19:28',13,23),(99,20,'Препод принял(а) задание','2021-05-20 12:19:30',13,23),(100,20,'Препод отменил(а) приём задания','2021-05-20 12:19:35',13,23),(101,20,'Препод принял(а) задание','2021-05-20 12:30:36',13,23),(102,20,'Препод отменил(а) приём задания','2021-05-20 13:59:19',13,23),(103,20,'Препод принял(а) задание','2021-05-20 13:59:45',13,23),(104,20,'rtbtr','2021-05-20 13:59:48',13,23),(105,20,'dscsd','2021-05-20 14:18:53',13,21),(106,20,'sdcs','2021-05-20 14:19:02',13,23),(107,20,'dscsdc','2021-05-20 14:19:10',13,23),(108,20,'Препод принял(а) задание','2021-05-20 14:28:02',6,21),(109,20,'Препод отменил(а) приём задания','2021-05-20 14:28:05',6,23),(110,20,'Препод принял(а) задание','2021-05-20 14:28:13',6,23),(111,20,'цуацу','2021-05-20 14:28:18',6,23),(112,20,'Препод отменил(а) приём задания','2021-05-20 14:30:17',6,23),(113,20,'Препод принял(а) задание','2021-05-20 14:30:27',6,23),(114,20,'Препод отменил(а) приём задания','2021-05-20 14:40:45',6,23),(115,20,'Препод отменил(а) приём задания','2021-05-20 14:41:03',13,23),(116,20,'Препод принял(а) задание','2021-05-20 14:41:07',13,23),(117,20,'Препод отменил(а) приём задания','2021-05-20 15:13:26',13,21),(118,20,'fwef','2021-05-20 19:37:12',13,23),(119,20,'Препод принял(а) задание','2021-05-20 19:37:41',7,23),(120,20,'Препод принял(а) задание','2021-05-20 19:38:18',16,23),(121,20,'Препод отменил(а) приём задания','2021-05-20 19:38:20',16,23),(122,20,'Препод принял(а) задание','2021-05-20 19:38:24',16,23),(123,23,' bdfb','2021-05-20 19:40:31',13,20),(124,20,'Препод принял(а) задание','2021-05-20 21:08:06',16,21),(125,20,'Препод отменил(а) приём задания','2021-05-20 21:34:30',13,23),(126,23,'hgm','2021-05-20 21:34:38',13,20),(127,20,'ghm','2021-05-20 21:34:48',13,23),(128,23,'asc','2021-05-20 21:36:27',13,20),(129,20,'jhgj','2021-05-20 21:37:21',13,23),(130,20,'Препод принял(а) задание','2021-05-20 21:37:44',13,23),(131,23,'jmh','2021-05-20 21:37:52',13,20),(132,23,'fnfgn','2021-05-20 21:42:47',6,20),(133,23,'xzcxzczxczxc','2021-05-21 07:33:48',7,20),(134,23,'Юлия Петрова отправил(а) файлы на проверку','2021-05-21 07:39:19',16,20),(135,23,'fgn','2021-05-21 07:50:09',13,20),(136,24,'fgfnm','2021-05-21 07:50:15',13,23),(137,23,'ghmghn','2021-05-21 07:50:26',13,20),(138,23,'ghmg','2021-05-21 07:50:29',13,20),(139,24,'ghmhgm','2021-05-21 07:50:35',13,23),(140,24,'ghmgh hghmg','2021-05-21 07:50:39',13,23),(141,23,' n.lnk','2021-05-21 07:51:09',13,20),(142,24,'Препод 2 отменил(а) приём задания','2021-05-21 07:51:30',13,23),(143,23,'хэй','2021-05-21 07:51:39',13,20),(144,24,'Препод 2 принял(а) задание','2021-05-21 07:51:44',13,23),(145,23,'икпик','2021-05-21 07:55:00',13,20),(146,23,'керрек','2021-05-21 07:55:09',13,20),(147,20,'uyjy','2021-05-21 07:57:41',13,23),(148,20,'yujyu','2021-05-21 07:57:43',13,23),(149,20,'yujyuj','2021-05-21 07:57:50',13,23),(150,20,'yujyuj','2021-05-21 07:57:52',13,23),(151,23,'hghm','2021-05-21 07:58:17',13,20),(152,20,'hgmgh','2021-05-21 07:58:19',13,23),(153,20,'ghmgm','2021-05-21 07:58:22',13,23),(154,20,'tyjytjtyj','2021-05-21 08:00:56',13,23),(155,20,'jh,hjh,h','2021-05-21 08:00:59',13,23),(156,20,'jhmhm','2021-05-21 08:01:07',13,23),(157,23,'jkhjkghkhjk','2021-05-21 08:01:15',13,20),(158,23,'hjkhjk','2021-05-21 08:01:21',13,20),(159,23,'m,nm,nm,nm,','2021-05-21 08:01:29',13,20),(160,23,'mn,n,','2021-05-21 08:01:50',13,20),(161,23,';;,;','2021-05-21 08:02:20',13,20),(162,23,'p[pl\n','2021-05-21 08:02:31',13,20),(163,23,'l,','2021-05-21 08:03:03',13,20),(164,23,'jh,','2021-05-21 08:41:53',13,20),(165,23,'tyjt','2021-05-21 08:42:27',13,20),(166,20,'ytjt','2021-05-21 08:42:49',13,23),(167,20,'ujyjyk','2021-05-21 08:42:54',13,23),(168,23,'укпукп','2021-05-21 08:43:09',13,20),(169,20,'ввт','2021-05-21 08:43:18',13,23),(170,23,'автивп','2021-05-21 08:43:20',13,20),(171,23,'рптпр','2021-05-21 08:43:25',13,20),(172,23,'прьп','2021-05-21 08:43:38',16,20),(173,20,'прьпр','2021-05-21 08:43:46',16,23),(174,24,'fnfgn','2021-05-21 08:44:31',13,23),(175,23,'fgnfnfg','2021-05-21 08:44:38',16,20),(176,23,'fgnf','2021-05-21 08:44:51',16,20),(177,23,'fgnf','2021-05-21 08:45:01',13,20),(178,24,'fgnf','2021-05-21 08:45:07',13,23),(179,23,'fgnfgn','2021-05-21 08:45:19',13,20),(180,23,'fgnfgn','2021-05-21 08:45:28',13,20),(181,21,'tgt','2021-05-21 09:45:15',13,20),(182,20,'trgrh','2021-05-21 09:45:32',13,23),(183,20,'hnmgm','2021-05-21 09:45:58',13,23),(184,23,'rtn','2021-05-21 09:46:20',13,20),(185,20,'Препод принял(а) задание','2021-05-21 09:53:29',13,21),(186,23,'hngm','2021-05-21 09:53:38',13,20),(187,20,'ghmhgm','2021-05-21 09:53:45',13,21),(188,20,'hgm','2021-05-21 09:53:49',13,23),(189,23,'dfbdf','2021-05-21 10:13:00',13,20),(190,23,'dbndhg','2021-05-21 10:14:17',13,20),(191,23,'fdbdb','2021-05-21 10:14:33',13,20),(192,23,'bfdbfdb','2021-05-21 10:14:46',13,20),(193,23,'rthrt','2021-05-21 10:15:59',13,20),(194,23,'ergerg','2021-05-21 10:17:55',13,20),(195,23,'wefwe','2021-05-21 11:59:03',13,20),(196,23,'rthrt','2021-05-21 11:59:34',13,20),(197,21,'sx','2021-05-21 12:00:08',13,20),(198,21,'saxsa','2021-05-21 12:00:22',13,20),(199,21,'uikuiku','2021-05-21 12:00:51',13,20),(200,21,'uil','2021-05-21 12:01:07',13,20),(201,20,'uiliul','2021-05-21 12:01:14',13,21),(202,21,'iuluil','2021-05-21 12:01:19',13,20),(203,21,'io;oi;','2021-05-21 12:01:32',13,20),(204,21,'jkjk;','2021-05-21 12:01:56',13,20),(205,21,'iului','2021-05-21 12:02:16',13,20),(206,21,'tyjyt','2021-05-21 12:03:40',13,20),(207,23,'fdgfdg','2021-05-21 12:04:13',13,20);
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `new_materials_notifications`
--

DROP TABLE IF EXISTS `new_materials_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `new_materials_notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `new_materials_notifications_FK` (`course_id`),
  KEY `new_materials_notifications_FK_1` (`user_id`),
  CONSTRAINT `new_materials_notifications_FK` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `new_materials_notifications_FK_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `new_materials_notifications`
--

LOCK TABLES `new_materials_notifications` WRITE;
/*!40000 ALTER TABLE `new_materials_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `new_materials_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `new_members_notifications`
--

DROP TABLE IF EXISTS `new_members_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `new_members_notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `new_members_notifications_FK` (`course_id`),
  KEY `new_members_notifications_FK_1` (`user_id`),
  CONSTRAINT `new_members_notifications_FK` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `new_members_notifications_FK_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `new_members_notifications`
--

LOCK TABLES `new_members_notifications` WRITE;
/*!40000 ALTER TABLE `new_members_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `new_members_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `new_messages_notifications`
--

DROP TABLE IF EXISTS `new_messages_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `new_messages_notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` bigint(20) unsigned NOT NULL,
  `sender_id` bigint(20) unsigned NOT NULL,
  `receptionist_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `new_messages_notifications_FK` (`task_id`),
  KEY `new_messages_notifications_FK_1` (`sender_id`),
  KEY `new_messages_notifications_FK_2` (`receptionist_id`),
  CONSTRAINT `new_messages_notifications_FK` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `new_messages_notifications_FK_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `new_messages_notifications_FK_2` FOREIGN KEY (`receptionist_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `new_messages_notifications`
--

LOCK TABLES `new_messages_notifications` WRITE;
/*!40000 ALTER TABLE `new_messages_notifications` DISABLE KEYS */;
INSERT INTO `new_messages_notifications` VALUES (12,16,20,21),(72,13,21,20);
/*!40000 ALTER TABLE `new_messages_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `new_tasks_notifications`
--

DROP TABLE IF EXISTS `new_tasks_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `new_tasks_notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `new_materials_notifications_FK` (`course_id`) USING BTREE,
  KEY `new_materials_notifications_FK_1` (`user_id`) USING BTREE,
  CONSTRAINT `new_tasks_notifications_FK` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `new_tasks_notifications_FK_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `new_tasks_notifications`
--

LOCK TABLES `new_tasks_notifications` WRITE;
/*!40000 ALTER TABLE `new_tasks_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `new_tasks_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reset_passwords`
--

DROP TABLE IF EXISTS `reset_passwords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reset_passwords` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `token` text NOT NULL,
  `expired` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `reset_passwords_FK` (`user_id`),
  CONSTRAINT `reset_passwords_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reset_passwords`
--

LOCK TABLES `reset_passwords` WRITE;
/*!40000 ALTER TABLE `reset_passwords` DISABLE KEYS */;
/*!40000 ALTER TABLE `reset_passwords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'user'),(2,'admin'),(3,'owner');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tasks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `date_time` datetime NOT NULL,
  `course_id` bigint(20) unsigned NOT NULL,
  `score` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `materials_FK` (`course_id`) USING BTREE,
  CONSTRAINT `tasks_FK` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
INSERT INTO `tasks` VALUES (6,'kl.','<p>kl.</p>','2021-05-12 17:14:45',19,0),(7,'gbsdf','<p>gfdg</p>','2021-05-12 17:18:23',19,0),(13,'Лабораторная работа №1','<p>g</p>','2021-05-19 12:19:37',19,5),(15,'fgbfg','<p>fgn</p>','2021-05-20 10:24:37',21,1),(16,'dfvdfv','<p>fdvdf</p>','2021-05-20 19:37:30',19,1);
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasks_files`
--

DROP TABLE IF EXISTS `tasks_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tasks_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `location` text NOT NULL,
  `task_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `materials_files_FK` (`task_id`) USING BTREE,
  CONSTRAINT `tasks_files_FK` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasks_files`
--

LOCK TABLES `tasks_files` WRITE;
/*!40000 ALTER TABLE `tasks_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `tasks_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` text NOT NULL,
  `password` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'firig46864@quossum.com','$2y$10$Ym8xZ0lTZmp6UnI0RTBBQOt4h3GgjVtrEWTO.ztI4ivzLAllMfSl2'),(3,'figiva5517@yehudabx.com','$2y$10$RER6Mk1VK0ZuZjZxQU9sSenOemPrOTMm8RLIIbSC5mx5kyp8O5GV.'),(8,'nilok66826@sejkt.com','$2y$10$UkxSNnQra3dUT3ZxRU1WT.rdMTNo377RNe9ep604pGJEVqbulgTs.'),(9,'gosel74676@sumwan.com','$2y$10$Zm9vVmpuL3BXa0htWUNVOOfhuIQ6pPki6vbGk8pmmpjjjp8GeP9Oy'),(10,'rabedew149@yehudabx.com','$2y$10$VkExRTZrb2J1V2tRcWRCRezlfWVoAxVaLfs2J43.Qkc5ACQqvLd1u'),(12,'temanaf566@yehudabx.com','$2y$10$djJaYzhmdCtKbkJoUFJUSeaHS55jsfTYv575gU/D7TB62jZIJ/Tb2'),(13,'kabed46680@hype68.com','$2y$10$bHJOMldONXc2dlFyM1hrd.mF3iKGJLpnrgyFQt6OOzXzEm5rPcNyK'),(16,'hacobij232@87708b.com','$2y$10$d1F6M2F1RmpGOXFwVGlrS./kN2/iE76dMhoKnpNDRbgU0TrRFThKq'),(17,'tihap72005@87708b.com','$2y$10$VnNhNEE5bVFzWk5YMzVtMeYcmaK38MvlyzdJxJBxeke0kQ.rQiMx2'),(20,'dvo@tuta.io','$2y$10$QzgzN3BaU1NHNmhjdUF5Qu8EbPgWFzaNg7Nuurws3F/dZ.ixmfX.G'),(21,'secari7859@labebx.com','$2y$10$aFB4bU50ckpRazI1aVJ3T.XSk3EFx8k11H9LBebXuwB1HoFMyCqru'),(23,'rokiveb233@firmjam.com','$2y$10$TkdVTHZpV25YOGEwR0hCReFXl.2/RCi3/oHudaNOlp6xzfYyzJutS'),(24,'wokoso1370@dvdoto.com','$2y$10$SjdEMmthZ2tGMHVvOHBVYeyzyLOVARiRMd.EFAnbvXbi5Wbon7nkq');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_files`
--

DROP TABLE IF EXISTS `users_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `location` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `users_files_FK` (`user_id`),
  KEY `users_files_FK_1` (`task_id`),
  CONSTRAINT `users_files_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_files_FK_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_files`
--

LOCK TABLES `users_files` WRITE;
/*!40000 ALTER TABLE `users_files` DISABLE KEYS */;
INSERT INTO `users_files` VALUES (14,6,23,'/var/www/html/app/storage/users_files/8d72e1fb1a7027eef33c0770ac781c589ff4f409f99a8827e86b746e4c631117__-__Armin van Buuren feat. Sharon den Adel - In And Out Of Love (www.hotplayer.ru).mp3'),(15,16,23,'/var/www/html/app/storage/users_files/1e764973e89082b749e469574ff99aab712f9433bbe764db19e91486a5acff4d__-__8b7ecb76339fa251.docx');
/*!40000 ALTER TABLE `users_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'checkingboard'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-05-21 12:05:35
