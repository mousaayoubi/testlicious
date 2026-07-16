-- MySQL dump 10.13  Distrib 8.0.45, for Linux (x86_64)
--
-- Host: localhost    Database: magento
-- ------------------------------------------------------
-- Server version	8.0.45

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
-- Table structure for table `testlicious_aiseo_audit`
--

DROP TABLE IF EXISTS `testlicious_aiseo_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `testlicious_aiseo_audit` (
  `audit_id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Audit ID',
  `entity_type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Entity Type',
  `entity_id` int unsigned NOT NULL COMMENT 'Entity ID',
  `store_id` smallint unsigned NOT NULL DEFAULT '0' COMMENT 'Store ID',
  `entity_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Entity Name',
  `current_meta_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Current Meta Title',
  `current_meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Current Meta Description',
  `current_url_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Current URL Key',
  `seo_score` smallint unsigned NOT NULL DEFAULT '0' COMMENT 'SEO Score',
  `issues_json` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Issues JSON',
  `status` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending' COMMENT 'Status',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Created At',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Updated At',
  PRIMARY KEY (`audit_id`),
  KEY `TESTLICIOUS_AISEO_AUDIT_ENTITY_TYPE_ENTITY_ID_STORE_ID` (`entity_type`,`entity_id`,`store_id`),
  KEY `TESTLICIOUS_AISEO_AUDIT_SEO_SCORE` (`seo_score`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Testlicious AI SEO Audit';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testlicious_aiseo_audit`
--

LOCK TABLES `testlicious_aiseo_audit` WRITE;
/*!40000 ALTER TABLE `testlicious_aiseo_audit` DISABLE KEYS */;
INSERT INTO `testlicious_aiseo_audit` VALUES (1,'product',1,0,'Joust Duffle Bag','Joust Duffle Bag - Gym & Travel','Sporty Joust Duffle Bag with dual top handles, adjustable strap, and full-length zipper. Spacious 29 x 13 x 11 in for gym gear or travel.','joust-duffle-bag',90,'[\"Missing short description\"]','pending','2026-06-11 14:18:19','2026-06-11 14:18:19'),(2,'product',2,0,'Strive Shoulder Pack',NULL,NULL,'strive-shoulder-pack',30,'[\"Missing meta title\",\"Missing meta description\",\"Missing short description\"]','pending','2026-06-11 14:18:19','2026-06-11 14:18:19'),(3,'product',3,0,'Crown Summit Backpack',NULL,NULL,'crown-summit-backpack',30,'[\"Missing meta title\",\"Missing meta description\",\"Missing short description\"]','pending','2026-06-11 14:18:19','2026-06-11 14:18:19'),(4,'product',4,0,'Wayfarer Messenger Bag',NULL,NULL,'wayfarer-messenger-bag',30,'[\"Missing meta title\",\"Missing meta description\",\"Missing short description\"]','pending','2026-06-11 14:18:19','2026-06-11 14:18:19'),(5,'product',5,0,'Rival Field Messenger','Rival Field Messenger Bag - Leather','Rival Field Messenger in soft textured leather with two exterior pockets and a roomy interior. Adjustable strap; 18 x 10 x 4 in.','rival-field-messenger',90,'[\"Missing short description\"]','pending','2026-06-11 14:18:19','2026-06-11 14:18:19'),(6,'product',6,0,'Fusion Backpack','Fusion Backpack | Nylon 2-Compartment Daypack','Durable nylon backpack with two main zippered compartments, a front pocket, and mesh side pouches. Padded straps for comfortable daily use.','fusion-backpack',90,'[\"Missing short description\"]','pending','2026-06-11 14:18:19','2026-06-11 14:18:19'),(7,'product',7,0,'Impulse Duffle',NULL,NULL,'impulse-duffle',30,'[\"Missing meta title\",\"Missing meta description\",\"Missing short description\"]','pending','2026-06-11 14:18:19','2026-06-11 14:18:19'),(8,'product',8,0,'Voyage Yoga Bag',NULL,NULL,'voyage-yoga-bag',30,'[\"Missing meta title\",\"Missing meta description\",\"Missing short description\"]','pending','2026-06-11 14:18:19','2026-06-11 14:18:19'),(9,'product',9,0,'Compete Track Tote',NULL,NULL,'compete-track-tote',30,'[\"Missing meta title\",\"Missing meta description\",\"Missing short description\"]','pending','2026-06-11 14:18:19','2026-06-11 14:18:19'),(10,'product',10,0,'Savvy Shoulder Tote','Savvy Shoulder Tote | Water-Resistant Gym Tote','Savvy Shoulder Tote with a water-resistant shell, water bottle pocket, top-loading main compartment, and front/side zip pockets for cash, cards, and phone.','savvy-shoulder-tote',90,'[\"Missing short description\"]','pending','2026-06-11 14:18:19','2026-06-11 14:18:19'),(11,'product',11,0,'Endeavor Daytrip Backpack',NULL,NULL,'endeavor-daytrip-backpack',30,'[\"Missing meta title\",\"Missing meta description\",\"Missing short description\"]','pending','2026-07-13 10:33:11','2026-07-13 10:33:11'),(12,'product',12,0,'Driven Backpack',NULL,NULL,'driven-backpack',30,'[\"Missing meta title\",\"Missing meta description\",\"Missing short description\"]','pending','2026-07-13 10:33:11','2026-07-13 10:33:11'),(13,'product',13,0,'Overnight Duffle',NULL,NULL,'overnight-duffle',30,'[\"Missing meta title\",\"Missing meta description\",\"Missing short description\"]','pending','2026-07-13 10:33:11','2026-07-13 10:33:11'),(14,'product',14,0,'Push It Messenger Bag',NULL,NULL,'push-it-messenger-bag',30,'[\"Missing meta title\",\"Missing meta description\",\"Missing short description\"]','pending','2026-07-13 10:33:11','2026-07-13 10:33:11'),(15,'product',15,0,'Affirm Water Bottle ',NULL,NULL,'affirm-water-bottle',30,'[\"Missing meta title\",\"Missing meta description\",\"Missing short description\"]','pending','2026-07-13 10:33:11','2026-07-13 10:33:11'),(16,'product',16,0,'Dual Handle Cardio Ball',NULL,NULL,'dual-handle-cardio-ball',10,'[\"Missing meta title\",\"Missing meta description\",\"Description is too short\",\"Missing short description\"]','pending','2026-07-13 10:33:11','2026-07-13 10:33:11'),(17,'product',17,0,'Zing Jump Rope',NULL,NULL,'zing-jump-rope',30,'[\"Missing meta title\",\"Missing meta description\",\"Missing short description\"]','pending','2026-07-13 10:33:11','2026-07-13 10:33:11'),(18,'product',18,0,'Pursuit Lumaflex&trade; Tone Band',NULL,NULL,'pursuit-lumaflex-trade-tone-band',10,'[\"Missing meta title\",\"Missing meta description\",\"Description is too short\",\"Missing short description\"]','pending','2026-07-13 10:33:11','2026-07-13 10:33:11'),(19,'product',19,0,'Go-Get\'r Pushup Grips',NULL,NULL,'go-get-r-pushup-grips',10,'[\"Missing meta title\",\"Missing meta description\",\"Description is too short\",\"Missing short description\"]','pending','2026-07-13 10:33:11','2026-07-13 10:33:11'),(20,'product',20,0,'Quest Lumaflex&trade; Band',NULL,NULL,'quest-lumaflex-trade-band',30,'[\"Missing meta title\",\"Missing meta description\",\"Missing short description\"]','pending','2026-07-13 10:33:11','2026-07-13 10:33:11');
/*!40000 ALTER TABLE `testlicious_aiseo_audit` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-15 11:34:30
