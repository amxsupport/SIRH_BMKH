-- MariaDB dump 10.19  Distrib 10.4.28-MariaDB, for osx10.10 (x86_64)
--
-- Host: localhost    Database: sirh_bmkh
-- ------------------------------------------------------
-- Server version	10.4.28-MariaDB

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
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL AUTO_INCREMENT,
  `ppr` varchar(20) NOT NULL,
  `cin` varchar(20) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `sex` enum('M','F') NOT NULL,
  `date_naissance` date NOT NULL,
  `corps` enum('medical','paramedical','administratif') NOT NULL,
  `specialite` varchar(100) DEFAULT NULL,
  `grade` varchar(100) NOT NULL,
  `date_recrutement` date NOT NULL,
  `date_prise_service` date NOT NULL,
  `province_etablissement` varchar(100) NOT NULL,
  `milieu_etablissement` enum('Urbain','Rural') NOT NULL,
  `categorie_etablissement` varchar(100) NOT NULL,
  `nom_etablissement` varchar(200) NOT NULL,
  `service_etablissement` varchar(100) DEFAULT NULL,
  `situation_familiale` enum('Célibataire','Marié(e)','Divorcé(e)','Veuf(ve)') NOT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `notification_read` tinyint(1) DEFAULT 0,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('user','admin','approver') DEFAULT 'user',
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`employee_id`),
  UNIQUE KEY `ppr` (`ppr`),
  UNIQUE KEY `cin` (`cin`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_ppr` (`ppr`),
  KEY `idx_cin` (`cin`),
  KEY `idx_nom_prenom` (`nom`,`prenom`),
  KEY `idx_corps` (`corps`),
  KEY `idx_province` (`province_etablissement`),
  KEY `idx_etablissement` (`nom_etablissement`),
  KEY `idx_auth` (`remember_token`,`reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (36,'M123456','QB123456','Alami','Mohammed','M','1963-05-15','medical','Cardiologie','Médecin spécialiste','2010-09-01','2010-09-15','Béni Mellal','Urbain','Hôpital','Hôpital Régional','Cardiologie','Marié(e)','Béni Mellal','0661234567','2025-05-24 19:01:30','2025-05-24 20:20:47',0,NULL,NULL,'user',NULL,NULL,NULL,NULL,NULL),(37,'M123457','QB123457','Bennani','Fatima','F','1982-03-20','medical','Pédiatrie','Médecin spécialiste','2011-01-15','2011-02-01','Khénifra','Urbain','Hôpital','Hôpital Provincial','Pédiatrie','Marié(e)','Khénifra','0662345678','2025-05-24 19:01:30','2025-05-24 19:01:30',0,NULL,NULL,'user',NULL,NULL,NULL,NULL,NULL),(38,'M123458','QB123458','Tazi','Ahmed','M','1975-11-10','medical','Médecine Générale','Médecin généraliste','2005-06-20','2005-07-01','Khouribga','Urbain','Centre de Santé','CS Khouribga','Consultation','Marié(e)','Khouribga','0663456789','2025-05-24 19:01:30','2025-05-24 19:01:30',0,NULL,NULL,'user',NULL,NULL,NULL,NULL,NULL),(39,'M123459','QB123459','El Amrani','Leila','F','1983-08-12','medical','Gynécologie','Médecin spécialiste','2012-03-15','2012-04-01','Béni Mellal','Urbain','Hôpital','Hôpital Régional','Gynécologie','Marié(e)','Béni Mellal','0661234568','2025-05-24 19:01:30','2025-05-24 19:01:30',0,NULL,NULL,'user',NULL,NULL,NULL,NULL,NULL),(40,'M123460','QB123460','Fassi','Karim','M','1979-06-25','medical','Médecine Générale','Médecin généraliste','2009-11-01','2009-11-15','Azilal','Rural','Centre de Santé','CS Azilal','Consultation','Marié(e)','Azilal','0662345679','2025-05-24 19:01:30','2025-05-24 19:01:30',0,NULL,NULL,'user',NULL,NULL,NULL,NULL,NULL),(41,'P123456','QB234567','Rachidi','Samira','F','1985-07-25','paramedical','Soins Infirmiers','Infirmier','2012-03-10','2012-03-25','Fquih Ben Salah','Rural','Dispensaire','Dispensaire Rural','Soins','Marié(e)','Fquih Ben Salah','0664567890','2025-05-24 19:01:30','2025-05-24 19:01:30',0,NULL,NULL,'user',NULL,NULL,NULL,NULL,NULL),(42,'P123457','QB234568','Ouazzani','Karim','M','1988-09-12','paramedical','Soins Infirmiers','Infirmier','2013-11-05','2013-11-20','Azilal','Rural','Centre de Santé','CS Azilal','Urgences','Célibataire','Azilal','0665678901','2025-05-24 19:01:30','2025-05-24 19:01:30',0,NULL,NULL,'user',NULL,NULL,NULL,NULL,NULL),(43,'P123458','QB234569','Idrissi','Amina','F','1983-12-30','paramedical','Obstétrique','Sage-femme','2009-08-15','2009-09-01','Béni Mellal','Urbain','Maternité','Maternité Provinciale','Maternité','Marié(e)','Béni Mellal','0666789012','2025-05-24 19:01:30','2025-05-24 19:01:30',0,NULL,NULL,'user',NULL,NULL,NULL,NULL,NULL),(44,'P123459','QB234570','Belhaj','Hassan','M','1961-04-15','paramedical','Soins Infirmiers','Infirmier','2014-02-01','2014-02-15','Khouribga','Urbain','Hôpital','Hôpital Provincial','Urgences','Marié(e)','Khouribga','0667890123','2025-05-24 19:01:30','2025-06-14 13:24:13',1,NULL,NULL,'user',NULL,NULL,NULL,NULL,NULL),(45,'P123460','QB234571','Ziani','Fatima','F','1984-11-20','paramedical','Radiologie','Technicien','2011-06-10','2011-06-25','Béni Mellal','Urbain','Hôpital','Hôpital Régional','Radiologie','Marié(e)','Béni Mellal','0668901234','2025-05-24 19:01:30','2025-05-24 19:01:30',0,NULL,NULL,'user',NULL,NULL,NULL,NULL,NULL),(46,'A123456','QB345678','El Fassi','Youssef','M','1979-04-18','administratif','Gestion','Administrateur','2008-02-01','2008-02-15','Khénifra','Urbain','Direction','Direction Provinciale','Administration','Marié(e)','Khénifra','0667890123','2025-05-24 19:01:30','2025-05-24 19:01:30',0,NULL,NULL,'user',NULL,NULL,NULL,NULL,NULL),(47,'A123457','QB345679','Benjelloun','Nadia','F','1963-08-22','administratif','Secrétariat','Secrétaire','2014-05-20','2014-06-01','Khouribga','Urbain','Hôpital','Hôpital Provincial','Administration','Marié(e)','Khouribga','0668901234','2025-05-24 19:01:30','2025-07-09 18:27:20',0,NULL,NULL,'user',NULL,NULL,NULL,NULL,NULL),(48,'A123458','QB345680','Chraibi','Hassan','M','1977-01-05','administratif','Maintenance','Technicien','2007-12-10','2008-01-01','Béni Mellal','Urbain','Direction','Direction Régionale','Services Techniques','Marié(e)','Béni Mellal','0669012345','2025-05-24 19:01:30','2025-05-24 19:01:30',0,NULL,NULL,'user',NULL,NULL,NULL,NULL,NULL),(49,'A123459','QB345681','Saadi','Amal','F','1988-03-30','administratif','Administration','Agent administratif','2015-09-01','2015-09-15','Fquih Ben Salah','Urbain','Centre de Santé','CS Fquih Ben Salah','Administration','Célibataire','Fquih Ben Salah','0670123456','2025-05-24 19:01:30','2025-05-24 19:01:30',0,NULL,NULL,'user',NULL,NULL,NULL,NULL,NULL),(50,'A123460','QB345682','Alaoui','Mehdi','M','1963-07-14','administratif','Ressources Humaines','Responsable RH','2010-04-01','2010-04-15','Azilal','Urbain','Direction','Direction Provinciale','Administration','Marié(e)','Azilal','0671234567','2025-05-24 19:01:30','2025-06-22 18:14:27',0,NULL,NULL,'user',NULL,NULL,NULL,NULL,NULL),(51,'M123461','QB123461','Moussaoui','Sara','F','1984-09-22','medical','Dermatologie','Médecin spécialiste','2013-01-10','2013-02-01','Béni Mellal','Urbain','Hôpital','Hôpital Régional','Dermatologie','Marié(e)','Béni Mellal','0672345678','2025-05-24 19:01:30','2025-05-24 19:01:30',0,NULL,NULL,'user',NULL,NULL,NULL,NULL,NULL),(52,'M123462','QB123462','El Khoury','Reda','M','1964-12-03','medical','Orthopédie','Médecin spécialiste','2008-07-15','2008-08-01','Khénifra','Urbain','Hôpital','Hôpital Provincial','Orthopédie','Marié(e)','Khénifra','0673456789','2025-05-24 19:01:30','2025-06-22 18:15:19',0,NULL,NULL,'user',NULL,NULL,NULL,NULL,NULL),(53,'P123461','QB234572','Tahiri','Younes','M','1989-05-18','paramedical','Soins Infirmiers','Infirmier','2015-03-20','2015-04-01','Khouribga','Rural','Centre de Santé','CS Rural Khouribga','Soins','Célibataire','Khouribga','0674567890','2025-05-24 19:01:30','2025-05-24 19:01:30',0,NULL,NULL,'user',NULL,NULL,NULL,NULL,NULL),(54,'P123462','QB234573','Lahlou','Meryem','F','1986-02-28','paramedical','Obstétrique','Sage-femme','2012-09-01','2012-09-15','Fquih Ben Salah','Urbain','Maternité','Maternité Provinciale','Maternité','Marié(e)','Fquih Ben Salah','0675678901','2025-05-24 19:01:30','2025-05-24 19:01:30',0,NULL,NULL,'user',NULL,NULL,NULL,NULL,NULL),(55,'A123461','QB345683','El Hamidi','Zineb','F','1985-11-07','administratif','Secrétariat','Secrétaire','2013-06-15','2013-07-01','Azilal','Urbain','Hôpital','Hôpital Provincial','Administration','Marié(e)','Azilal','0676789012','2025-05-24 19:01:30','2025-05-24 19:01:30',0,NULL,NULL,'user',NULL,NULL,NULL,NULL,NULL),(56,'A123462','QB345684','Rami','Omar','M','1980-04-25','administratif','Finances','Comptable','2009-12-01','2009-12-15','Béni Mellal','Urbain','Direction','Direction Régionale','Comptabilité','Marié(e)','Béni Mellal','0677890123','2025-05-24 19:01:30','2025-05-24 19:01:30',0,NULL,NULL,'user',NULL,NULL,NULL,NULL,NULL),(57,'123456','A123456','Mansouri','Hassan','M','1980-01-15','medical',NULL,'Chef de Service','2010-01-01','2010-01-15','Béni Mellal','Urbain','Hopital','Hôpital Régional','Service de Cardiologie','Marié(e)',NULL,NULL,'2025-06-28 13:04:18','2025-06-28 13:04:18',0,NULL,NULL,'user',NULL,NULL,NULL,NULL,NULL),(58,'234567','B234567','Ziani','Amina','F','1985-03-20','medical',NULL,'Chef de Service','2012-06-01','2012-06-15','Béni Mellal','Urbain','Centre de Santé','Centre de Santé','Service de Pédiatrie','Marié(e)',NULL,NULL,'2025-06-28 13:04:18','2025-06-28 13:04:18',0,NULL,NULL,'user',NULL,NULL,NULL,NULL,NULL),(63,'EMP002','B654321','Benani','Sara','M','1980-03-20','medical',NULL,'Chef de Service','2008-06-15','2008-06-15','Berkane-BMKH','Urbain','Administration','BMKH',NULL,'Célibataire',NULL,NULL,'2025-06-29 13:47:46','2025-06-29 13:47:46',0,'s.benani@example.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','approver',NULL,NULL,NULL,NULL,NULL),(64,'345678','C345678','El Amrani','Karim','M','1982-07-10','medical',NULL,'Médecin','2015-03-01','2015-03-15','Khénifra','Urbain','Hopital','Hôpital Provincial','Service de Médecine Interne','Marié(e)',NULL,NULL,'2025-07-03 21:38:40','2025-07-03 21:38:40',0,NULL,NULL,'user',NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `holidays`
--

DROP TABLE IF EXISTS `holidays`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `holidays` (
  `holiday_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `type` enum('civil','religious') NOT NULL,
  `recurring` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`holiday_id`),
  UNIQUE KEY `unique_holiday_date` (`date`,`recurring`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `holidays`
--

LOCK TABLES `holidays` WRITE;
/*!40000 ALTER TABLE `holidays` DISABLE KEYS */;
INSERT INTO `holidays` VALUES (1,'Nouvel An','2025-01-01','civil',1,'2025-07-03 21:37:36'),(2,'Manifeste de l\'Indépendance','2025-01-11','civil',1,'2025-07-03 21:37:36'),(3,'Fête du Travail','2025-05-01','civil',1,'2025-07-03 21:37:36'),(4,'Fête du Trône','2025-07-30','civil',1,'2025-07-03 21:37:36'),(5,'Récupération de Oued Ed-Dahab','2025-08-14','civil',1,'2025-07-03 21:37:36'),(6,'La Marche Verte','2025-11-06','civil',1,'2025-07-03 21:37:36'),(7,'Fête de l\'Indépendance','2025-11-18','civil',1,'2025-07-03 21:37:36'),(8,'Aid Al Fitr','2025-04-01','religious',0,'2025-07-03 21:37:36'),(9,'Aid Al Adha','2025-06-29','religious',0,'2025-07-03 21:37:36'),(10,'1er Moharram','2025-07-30','religious',0,'2025-07-03 21:37:36'),(11,'Aid Al Mawlid','2025-09-29','religious',0,'2025-07-03 21:37:36');
/*!40000 ALTER TABLE `holidays` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `interns`
--

DROP TABLE IF EXISTS `interns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `interns` (
  `intern_id` int(11) NOT NULL AUTO_INCREMENT,
  `cin` varchar(20) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `sex` enum('M','F') NOT NULL,
  `date_naissance` date NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `etablissement_education` varchar(200) NOT NULL,
  `niveau_education` varchar(100) NOT NULL,
  `specialite` varchar(100) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `province_etablissement` varchar(100) NOT NULL,
  `nom_etablissement` varchar(200) NOT NULL,
  `service_etablissement` varchar(100) NOT NULL,
  `superviseur_id` int(11) DEFAULT NULL,
  `status` enum('en_cours','termine','abandonne') DEFAULT 'en_cours',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`intern_id`),
  UNIQUE KEY `cin` (`cin`),
  KEY `idx_nom_prenom` (`nom`,`prenom`),
  KEY `idx_etablissement` (`nom_etablissement`),
  KEY `idx_dates` (`date_debut`,`date_fin`),
  KEY `superviseur_id` (`superviseur_id`),
  CONSTRAINT `interns_ibfk_1` FOREIGN KEY (`superviseur_id`) REFERENCES `employees` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `interns`
--

LOCK TABLES `interns` WRITE;
/*!40000 ALTER TABLE `interns` DISABLE KEYS */;
INSERT INTO `interns` VALUES (1,'BE123456','Alami','Karim','M','2000-05-15','0612345678',NULL,'karim.alami@email.com','Faculté de Médecine de Casablanca','Master 2','Médecine Générale','2025-01-01','2025-06-30','Béni Mellal','Hôpital Régional','Service de Cardiologie',57,'en_cours','2025-06-28 13:03:38','2025-06-28 13:04:18'),(2,'BE234567','Benani','Sara','F','2001-03-20','0623456789',NULL,'sara.benani@email.com','ISPITS Béni Mellal','Licence 3','Soins Infirmiers','2025-02-01','2025-07-31','Béni Mellal','Centre de Santé','Service de Pédiatrie',58,'en_cours','2025-06-28 13:03:38','2025-06-28 13:04:18'),(3,'BE345678','Chakir','Youssef','M','1999-11-10','0634567890',NULL,'youssef.chakir@email.com','FST Béni Mellal','Master 1','Informatique Médicale','2024-09-01','2025-02-28','Khénifra','Hôpital Provincial','Service Informatique',NULL,'termine','2025-06-28 13:03:38','2025-06-28 13:03:38'),(4,'BE456789','Doukkali','Fatima','F','2002-07-25','0645678901','<br />\r\n<b>Deprecated</b>:  htmlspecialchars(): Passing null to parameter #1 ($string) of type string is deprecated in <b>/Applications/XAMPP/xamppfiles/htdocs/SIRH_BMKH/views/interns/edit.php</b> on line <b>74</b><br />','fatima.doukkali@email.com','ENCG Béni Mellal','Licence 3','Gestion Hospitalière','2024-12-01','2025-05-31','Fquih Ben Salah','Centre Hospitalier','Service Administratif',52,'en_cours','2025-06-28 13:03:38','2025-06-28 13:09:35'),(5,'BE567890','El Fassi','Mohammed','M','2001-09-30','0656789012',NULL,'mohammed.elfassi@email.com','Faculté de Pharmacie de Rabat','Master 2','Pharmacie','2024-07-01','2024-12-31','Azilal','Hôpital Local','Service Pharmacie',NULL,'termine','2025-06-28 13:03:38','2025-06-28 13:03:38');
/*!40000 ALTER TABLE `interns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leave_balances`
--

DROP TABLE IF EXISTS `leave_balances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leave_balances` (
  `balance_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `initial_balance` decimal(5,1) NOT NULL,
  `used_days` decimal(5,1) DEFAULT 0.0,
  `remaining_days` decimal(5,1) DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`balance_id`),
  UNIQUE KEY `unique_balance` (`employee_id`,`type_id`,`year`),
  KEY `type_id` (`type_id`),
  CONSTRAINT `leave_balances_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE CASCADE,
  CONSTRAINT `leave_balances_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `leave_types` (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=159 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leave_balances`
--

LOCK TABLES `leave_balances` WRITE;
/*!40000 ALTER TABLE `leave_balances` DISABLE KEYS */;
INSERT INTO `leave_balances` VALUES (32,36,1,2025,30.0,11.0,19.0,'2025-07-03 21:40:19'),(33,37,1,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(34,38,1,2025,30.0,10.0,20.0,'2025-07-03 21:40:19'),(35,39,1,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(36,36,4,2025,98.0,0.0,98.0,'2025-07-03 21:40:19'),(37,37,4,2025,98.0,0.0,98.0,'2025-07-03 21:40:19'),(38,38,4,2025,98.0,0.0,98.0,'2025-07-03 21:40:19'),(39,39,4,2025,98.0,0.0,98.0,'2025-07-03 21:40:19'),(40,36,5,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(41,37,5,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(42,38,5,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(43,39,5,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(44,36,6,2025,5.0,0.0,5.0,'2025-07-03 21:40:19'),(45,37,6,2025,5.0,5.0,0.0,'2025-07-03 21:40:19'),(46,38,6,2025,5.0,0.0,5.0,'2025-07-03 21:40:19'),(47,39,6,2025,5.0,0.0,5.0,'2025-07-03 21:40:19'),(48,36,7,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(49,37,7,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(50,38,7,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(51,39,7,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(52,36,8,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(53,37,8,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(54,38,8,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(55,39,8,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(56,36,11,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(57,37,11,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(58,38,11,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(59,39,11,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(60,36,12,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(61,37,12,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(62,38,12,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(63,39,12,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(64,36,15,2025,98.0,0.0,98.0,'2025-07-03 21:40:19'),(65,37,15,2025,98.0,0.0,98.0,'2025-07-03 21:40:19'),(66,38,15,2025,98.0,0.0,98.0,'2025-07-03 21:40:19'),(67,39,15,2025,98.0,0.0,98.0,'2025-07-03 21:40:19'),(68,36,16,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(69,37,16,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(70,38,16,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(71,39,16,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(72,36,17,2025,5.0,0.0,5.0,'2025-07-03 21:40:19'),(73,37,17,2025,5.0,0.0,5.0,'2025-07-03 21:40:19'),(74,38,17,2025,5.0,0.0,5.0,'2025-07-03 21:40:19'),(75,39,17,2025,5.0,0.0,5.0,'2025-07-03 21:40:19'),(76,36,18,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(77,37,18,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(78,38,18,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(79,39,18,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(80,36,19,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(81,37,19,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(82,38,19,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(83,39,19,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(84,36,22,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(85,37,22,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(86,38,22,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(87,39,22,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(88,36,23,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(89,37,23,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(90,38,23,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(91,39,23,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(92,36,26,2025,98.0,0.0,98.0,'2025-07-03 21:40:19'),(93,37,26,2025,98.0,0.0,98.0,'2025-07-03 21:40:19'),(94,38,26,2025,98.0,0.0,98.0,'2025-07-03 21:40:19'),(95,39,26,2025,98.0,0.0,98.0,'2025-07-03 21:40:19'),(96,36,27,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(97,37,27,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(98,38,27,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(99,39,27,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(100,36,28,2025,5.0,0.0,5.0,'2025-07-03 21:40:19'),(101,37,28,2025,5.0,0.0,5.0,'2025-07-03 21:40:19'),(102,38,28,2025,5.0,0.0,5.0,'2025-07-03 21:40:19'),(103,39,28,2025,5.0,0.0,5.0,'2025-07-03 21:40:19'),(104,36,29,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(105,37,29,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(106,38,29,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(107,39,29,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(108,36,30,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(109,37,30,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(110,38,30,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(111,39,30,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(112,36,33,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(113,37,33,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(114,38,33,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(115,39,33,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(116,36,34,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(117,37,34,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(118,38,34,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(119,39,34,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(120,36,37,2025,98.0,0.0,98.0,'2025-07-03 21:40:19'),(121,37,37,2025,98.0,0.0,98.0,'2025-07-03 21:40:19'),(122,38,37,2025,98.0,0.0,98.0,'2025-07-03 21:40:19'),(123,39,37,2025,98.0,0.0,98.0,'2025-07-03 21:40:19'),(124,36,38,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(125,37,38,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(126,38,38,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(127,39,38,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(128,36,39,2025,5.0,0.0,5.0,'2025-07-03 21:40:19'),(129,37,39,2025,5.0,0.0,5.0,'2025-07-03 21:40:19'),(130,38,39,2025,5.0,0.0,5.0,'2025-07-03 21:40:19'),(131,39,39,2025,5.0,0.0,5.0,'2025-07-03 21:40:19'),(132,36,40,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(133,37,40,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(134,38,40,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(135,39,40,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(136,36,41,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(137,37,41,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(138,38,41,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(139,39,41,2025,3.0,0.0,3.0,'2025-07-03 21:40:19'),(140,36,44,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(141,37,44,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(142,38,44,2025,30.0,0.0,30.0,'2025-07-03 21:40:19'),(143,39,44,2025,30.0,0.0,30.0,'2025-07-03 21:40:19');
/*!40000 ALTER TABLE `leave_balances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leave_requests`
--

DROP TABLE IF EXISTS `leave_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leave_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `leave_type` enum('annual','sick','maternity','other') NOT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `leave_requests_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leave_requests`
--

LOCK TABLES `leave_requests` WRITE;
/*!40000 ALTER TABLE `leave_requests` DISABLE KEYS */;
INSERT INTO `leave_requests` VALUES (1,39,'2025-07-10','2025-07-30','annual','','approved','2025-07-08 20:42:48','2025-07-08 20:50:52'),(2,37,'2025-07-10','2025-10-14','maternity','','pending','2025-07-08 20:57:39','2025-07-08 20:57:39'),(3,40,'2025-07-14','2025-07-31','annual','','pending','2025-07-08 20:58:15','2025-07-08 20:58:15'),(4,45,'2025-07-09','2025-07-12','sick','','pending','2025-07-08 20:59:17','2025-07-08 20:59:17');
/*!40000 ALTER TABLE `leave_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leave_types`
--

DROP TABLE IF EXISTS `leave_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leave_types` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `default_days` int(11) DEFAULT NULL,
  `requires_approval` tinyint(1) DEFAULT 1,
  `requires_document` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leave_types`
--

LOCK TABLES `leave_types` WRITE;
/*!40000 ALTER TABLE `leave_types` DISABLE KEYS */;
INSERT INTO `leave_types` VALUES (1,'Congé Annuel','Congé annuel régulier',30,1,0,'2025-07-03 21:37:36'),(2,'Congé Maladie Ordinaire','Congé pour maladie de courte durée',NULL,1,1,'2025-07-03 21:37:36'),(3,'Congé Maladie Longue Durée','Congé pour maladie nécessitant un traitement prolongé',NULL,1,1,'2025-07-03 21:37:36'),(4,'Congé Maternité','Congé pour les employées en maternité',98,1,1,'2025-07-03 21:37:36'),(5,'Congé Paternité','Congé pour les nouveaux pères',3,1,1,'2025-07-03 21:37:36'),(6,'Congé Exceptionnel - Mariage','Congé pour mariage de l\'employé',5,1,1,'2025-07-03 21:37:36'),(7,'Congé Exceptionnel - Décès','Congé pour décès d\'un proche',3,1,1,'2025-07-03 21:37:36'),(8,'Congé Exceptionnel - Naissance','Congé pour naissance d\'un enfant',3,1,1,'2025-07-03 21:37:36'),(9,'Congé Sans Solde','Congé pour convenances personnelles',NULL,1,1,'2025-07-03 21:37:36'),(10,'Congé Administratif','Congé pour formation ou mission',NULL,1,1,'2025-07-03 21:37:36'),(11,'Congé Pèlerinage','Congé pour Hajj ou Omra (une fois dans la carrière)',30,1,1,'2025-07-03 21:37:36'),(12,'Congé Annuel','Congé annuel régulier',30,1,0,'2025-07-03 21:38:40'),(13,'Congé Maladie Ordinaire','Congé pour maladie de courte durée',NULL,1,1,'2025-07-03 21:38:40'),(14,'Congé Maladie Longue Durée','Congé pour maladie nécessitant un traitement prolongé',NULL,1,1,'2025-07-03 21:38:40'),(15,'Congé Maternité','Congé pour les employées en maternité',98,1,1,'2025-07-03 21:38:40'),(16,'Congé Paternité','Congé pour les nouveaux pères',3,1,1,'2025-07-03 21:38:40'),(17,'Congé Exceptionnel - Mariage','Congé pour mariage de l\'employé',5,1,1,'2025-07-03 21:38:40'),(18,'Congé Exceptionnel - Décès','Congé pour décès d\'un proche',3,1,1,'2025-07-03 21:38:40'),(19,'Congé Exceptionnel - Naissance','Congé pour naissance d\'un enfant',3,1,1,'2025-07-03 21:38:40'),(20,'Congé Sans Solde','Congé pour convenances personnelles',NULL,1,1,'2025-07-03 21:38:40'),(21,'Congé Administratif','Congé pour formation ou mission',NULL,1,1,'2025-07-03 21:38:40'),(22,'Congé Pèlerinage','Congé pour Hajj ou Omra (une fois dans la carrière)',30,1,1,'2025-07-03 21:38:40'),(23,'Congé Annuel','Congé annuel régulier',30,1,0,'2025-07-03 21:39:31'),(24,'Congé Maladie Ordinaire','Congé pour maladie de courte durée',NULL,1,1,'2025-07-03 21:39:31'),(25,'Congé Maladie Longue Durée','Congé pour maladie nécessitant un traitement prolongé',NULL,1,1,'2025-07-03 21:39:31'),(26,'Congé Maternité','Congé pour les employées en maternité',98,1,1,'2025-07-03 21:39:31'),(27,'Congé Paternité','Congé pour les nouveaux pères',3,1,1,'2025-07-03 21:39:31'),(28,'Congé Exceptionnel - Mariage','Congé pour mariage de l\'employé',5,1,1,'2025-07-03 21:39:31'),(29,'Congé Exceptionnel - Décès','Congé pour décès d\'un proche',3,1,1,'2025-07-03 21:39:31'),(30,'Congé Exceptionnel - Naissance','Congé pour naissance d\'un enfant',3,1,1,'2025-07-03 21:39:31'),(31,'Congé Sans Solde','Congé pour convenances personnelles',NULL,1,1,'2025-07-03 21:39:31'),(32,'Congé Administratif','Congé pour formation ou mission',NULL,1,1,'2025-07-03 21:39:31'),(33,'Congé Pèlerinage','Congé pour Hajj ou Omra (une fois dans la carrière)',30,1,1,'2025-07-03 21:39:31'),(34,'Congé Annuel','Congé annuel régulier',30,1,0,'2025-07-03 21:40:19'),(35,'Congé Maladie Ordinaire','Congé pour maladie de courte durée',NULL,1,1,'2025-07-03 21:40:19'),(36,'Congé Maladie Longue Durée','Congé pour maladie nécessitant un traitement prolongé',NULL,1,1,'2025-07-03 21:40:19'),(37,'Congé Maternité','Congé pour les employées en maternité',98,1,1,'2025-07-03 21:40:19'),(38,'Congé Paternité','Congé pour les nouveaux pères',3,1,1,'2025-07-03 21:40:19'),(39,'Congé Exceptionnel - Mariage','Congé pour mariage de l\'employé',5,1,1,'2025-07-03 21:40:19'),(40,'Congé Exceptionnel - Décès','Congé pour décès d\'un proche',3,1,1,'2025-07-03 21:40:19'),(41,'Congé Exceptionnel - Naissance','Congé pour naissance d\'un enfant',3,1,1,'2025-07-03 21:40:19'),(42,'Congé Sans Solde','Congé pour convenances personnelles',NULL,1,1,'2025-07-03 21:40:19'),(43,'Congé Administratif','Congé pour formation ou mission',NULL,1,1,'2025-07-03 21:40:19'),(44,'Congé Pèlerinage','Congé pour Hajj ou Omra (une fois dans la carrière)',30,1,1,'2025-07-03 21:40:19');
/*!40000 ALTER TABLE `leave_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leaves`
--

DROP TABLE IF EXISTS `leaves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leaves` (
  `leave_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `duration` int(11) NOT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('en_attente','approuve_n1','approuve_final','refuse','annule') DEFAULT 'en_attente',
  `document_path` varchar(255) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`leave_id`),
  KEY `employee_id` (`employee_id`),
  KEY `type_id` (`type_id`),
  KEY `approved_by` (`approved_by`),
  CONSTRAINT `leaves_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE CASCADE,
  CONSTRAINT `leaves_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `leave_types` (`type_id`),
  CONSTRAINT `leaves_ibfk_3` FOREIGN KEY (`approved_by`) REFERENCES `employees` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leaves`
--

LOCK TABLES `leaves` WRITE;
/*!40000 ALTER TABLE `leaves` DISABLE KEYS */;
INSERT INTO `leaves` VALUES (11,36,1,'2025-07-15','2025-07-30',11,'Congé d\'été','approuve_final',NULL,37,'2025-06-28 10:30:00','2025-07-03 21:40:19'),(12,36,2,'2025-08-10','2025-08-15',4,'Consultation médicale','en_attente',NULL,NULL,NULL,'2025-07-03 21:40:19'),(13,37,6,'2025-09-01','2025-09-05',5,'Mariage','approuve_final',NULL,38,'2025-08-15 14:20:00','2025-07-03 21:40:19'),(14,37,10,'2025-10-01','2025-10-03',3,'Formation professionnelle','refuse',NULL,38,'2025-09-25 11:45:00','2025-07-03 21:40:19'),(15,38,1,'2025-12-20','2026-01-05',10,'Congé de fin d\'année','approuve_n1',NULL,39,'2025-11-30 09:15:00','2025-07-03 21:40:19'),(16,46,7,'2025-07-09','2025-07-12',4,'deces','en_attente',NULL,NULL,NULL,'2025-07-08 23:29:50'),(17,36,1,'2025-07-11','2025-07-20',10,'congé','approuve_final',NULL,NULL,'2025-07-09 01:19:01','2025-07-09 00:18:47');
/*!40000 ALTER TABLE `leaves` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `retirement_notifications`
--

DROP TABLE IF EXISTS `retirement_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `retirement_notifications` (
  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `notification_date` date NOT NULL,
  `retirement_date` date NOT NULL,
  `status` enum('pending','read') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`notification_id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `retirement_notifications_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `retirement_notifications`
--

LOCK TABLES `retirement_notifications` WRITE;
/*!40000 ALTER TABLE `retirement_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `retirement_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_permissions`
--

DROP TABLE IF EXISTS `user_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_permissions` (
  `permission_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `resource` varchar(50) NOT NULL,
  `action` enum('create','read','update','delete') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`permission_id`),
  UNIQUE KEY `unique_user_permission` (`user_id`,`resource`,`action`),
  CONSTRAINT `user_permissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_permissions`
--

LOCK TABLES `user_permissions` WRITE;
/*!40000 ALTER TABLE `user_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `password_reset_token` varchar(100) DEFAULT NULL,
  `password_reset_expires` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin@example.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin','System','Administrator',1,'2025-07-12 23:14:00','2025-07-13 14:45:42',NULL,NULL,NULL),(3,'admin2','admin2@example.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin','System','Administrator',1,'2025-07-13 14:47:35','2025-07-13 14:47:35',NULL,NULL,NULL);
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

-- Dump completed on 2025-07-13 15:49:45
