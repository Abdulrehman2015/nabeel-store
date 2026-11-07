-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: nabeel_store
-- ------------------------------------------------------
-- Server version	8.4.3

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
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_id` bigint unsigned DEFAULT NULL,
  `details` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES (1,1,'order.created','App\\Models\\Order',1,'{\"grand_total\": 340.9, \"order_number\": \"BS-2026-000101\", \"advance_amount\": 34.09}','2407:aa80:126:b0f0:9cd5:3294:fb71:39bb','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-10-31 23:20:06','2026-10-31 23:20:06'),(2,1,'settings.notifications_updated',NULL,NULL,NULL,'2407:aa80:126:b0f0:9cd5:3294:fb71:39bb','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-10-31 23:22:00','2026-10-31 23:22:00'),(3,1,'settings.appearance_updated',NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-01 17:35:22','2026-11-01 17:35:22'),(4,1,'settings.appearance_updated',NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-01 17:35:28','2026-11-01 17:35:28'),(5,1,'settings.appearance_updated',NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-01 17:35:37','2026-11-01 17:35:37'),(6,1,'settings.appearance_updated',NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-01 17:35:52','2026-11-01 17:35:52'),(7,1,'settings.appearance_updated',NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-01 17:52:52','2026-11-01 17:52:52'),(8,1,'settings.delivery_updated',NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-01 17:59:09','2026-11-01 17:59:09'),(9,1,'settings.notifications_updated',NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-01 19:42:24','2026-11-01 19:42:24'),(10,1,'settings.delivery_updated',NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-01 19:56:59','2026-11-01 19:56:59'),(11,1,'settings.delivery_updated',NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-01 19:57:37','2026-11-01 19:57:37'),(12,1,'settings.delivery_updated',NULL,NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-01 20:04:19','2026-11-01 20:04:19'),(13,1,'customer.deleted',NULL,NULL,'{\"name\": \"Abdul Rehman\", \"email\": \"ss4871836@gmail.com\", \"deleted_by\": 1, \"deleted_user_id\": 5}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-02 17:06:24','2026-11-02 17:06:24'),(14,NULL,'customer.account_blocked','App\\Models\\User',8,'{\"reason\": \"Failed OTP verification 5 attempts twice\", \"identifier\": \"otp_test_buyer@example.com\"}','127.0.0.1','Symfony','2026-11-02 17:25:59','2026-11-02 17:25:59'),(15,NULL,'customer.status_toggled','App\\Models\\User',8,'{\"is_active\": true}','127.0.0.1','Symfony','2026-11-02 17:25:59','2026-11-02 17:25:59'),(16,1,'customer.account_unlocked','App\\Models\\User',9,'{\"email\": \"suspended_buyer@example.com\", \"admin_id\": 1}','127.0.0.1','Symfony','2026-11-02 17:36:36','2026-11-02 17:36:36'),(17,1,'customer.deleted',NULL,NULL,'{\"name\": \"Abdul Rehman\", \"email\": \"ss4871836@gmail.com\", \"deleted_by\": 1, \"deleted_user_id\": 7}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-02 18:03:30','2026-11-02 18:03:30'),(18,1,'category.created','App\\Models\\Category',11,'{\"name\": \"Abdul Rehman\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-02 19:18:37','2026-11-02 19:18:37'),(19,1,'category.updated','App\\Models\\Category',11,'{\"name\": \"Abdul Rehman\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-02 19:19:42','2026-11-02 19:19:42'),(20,1,'order.invoice_printed','App\\Models\\Order',1,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-04 11:36:15','2026-11-04 11:36:15'),(21,1,'pos.sale_completed','App\\Models\\Order',2,'{\"change\": 0, \"cashier\": \"Store Administrator\", \"tendered\": 41.5, \"grand_total\": 41.5, \"payment_method\": \"cash\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-04 16:49:07','2026-11-04 16:49:07'),(22,1,'pos.sale_completed','App\\Models\\Order',3,'{\"change\": 0, \"cashier\": \"Store Administrator\", \"tendered\": 182, \"grand_total\": 182, \"payment_method\": \"cash\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-04 16:49:20','2026-11-04 16:49:20'),(23,1,'pos.sale_completed','App\\Models\\Order',4,'{\"change\": 0, \"cashier\": \"Store Administrator\", \"tendered\": 104.5, \"grand_total\": 104.5, \"payment_method\": \"cash\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-04 16:49:52','2026-11-04 16:49:52'),(24,1,'pos.sale_completed','App\\Models\\Order',5,'{\"change\": 0, \"cashier\": \"Store Administrator\", \"tendered\": 41.5, \"grand_total\": 41.5, \"payment_method\": \"cash\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-04 16:50:07','2026-11-04 16:50:07'),(25,1,'pos.sale_completed','App\\Models\\Order',6,'{\"change\": 0, \"cashier\": \"Store Administrator\", \"tendered\": 182, \"grand_total\": 182, \"payment_method\": \"cash\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-04 16:50:12','2026-11-04 16:50:12'),(26,1,'pos.sale_completed','App\\Models\\Order',7,'{\"change\": 0, \"cashier\": \"Store Administrator\", \"tendered\": 910, \"grand_total\": 910, \"payment_method\": \"cash\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-04 16:51:19','2026-11-04 16:51:19'),(27,1,'pos.sale_completed','App\\Models\\Order',8,'{\"change\": 0, \"cashier\": \"Store Administrator\", \"tendered\": 182, \"grand_total\": 182, \"payment_method\": \"cash\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-04 16:51:25','2026-11-04 16:51:25'),(28,1,'payment.verified','App\\Models\\Order',1,'{\"order_number\": \"BS-2026-000101\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-04 16:52:02','2026-11-04 16:52:02'),(29,1,'order.created','App\\Models\\Order',9,'{\"grand_total\": 179.8, \"order_number\": \"BS-2026-000102\", \"advance_amount\": 17.98}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-04 17:46:04','2026-11-04 17:46:04'),(30,1,'order.created','App\\Models\\Order',10,'{\"grand_total\": 118.5, \"order_number\": \"BS-2026-000103\", \"advance_amount\": 11.85}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-04 17:47:04','2026-11-04 17:47:04'),(31,1,'admin_password_changed','App\\Models\\User',1,'{\"message\": \"Admin password was updated successfully\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-04 19:08:51','2026-11-04 19:08:51'),(32,1,'admin_profile_updated','App\\Models\\User',1,'{\"name\": \"Store Administrator\", \"email\": \"nabeel@store.com\", \"old_email\": \"admin@99bulksales.my\", \"email_changed\": true}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-04 19:09:09','2026-11-04 19:09:09'),(33,1,'admin_profile_updated','App\\Models\\User',1,'{\"name\": \"N&I Store\", \"email\": \"nabeel@store.com\", \"old_email\": null, \"email_changed\": false}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-04 19:09:18','2026-11-04 19:09:18'),(34,1,'pos.sale_completed','App\\Models\\Order',11,'{\"change\": 0, \"cashier\": \"N&I Store\", \"tendered\": 408.5, \"grand_total\": 408.5, \"payment_method\": \"cash\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','2026-11-05 12:05:03','2026-11-05 12:05:03');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `banners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `badge` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Shop Now',
  `desktop_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--

LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
INSERT INTO `banners` VALUES (1,'Fresh Groceries & Everyday Essentials','Shop beverages, pantry staples, snacks, dairy, and household cleaning essentials delivered right to your door.','⚡ FRESH MART SAVINGS','/category/beverages-soft-drinks','Shop Groceries','banners/banner-1.jpg',NULL,1,1,'2026-10-31 21:33:27','2026-11-04 11:49:51'),(2,'Pantry Staples, Cooking Oil & Rice','Quality cooking oils, premium fragrant rice, sauces, and family food essentials with prompt local delivery.','🔥 DAILY HOME ESSENTIALS','/category/cooking-oil-essentials','Explore Pantry','banners/banner-2.jpg',NULL,2,1,'2026-10-31 21:33:27','2026-11-04 11:49:51'),(3,'Free Local Delivery Within 2 km','Fast doorstep delivery up to Floor 1 at zero extra charge for your neighbourhood.','🚚 FAST LOCAL DELIVERY','/categories','Shop Now','banners/banner-3.jpg',NULL,3,1,'2026-10-31 21:33:27','2026-11-04 11:49:51');
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `benefits`
--

DROP TABLE IF EXISTS `benefits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `benefits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `benefits`
--

LOCK TABLES `benefits` WRITE;
/*!40000 ALTER TABLE `benefits` DISABLE KEYS */;
INSERT INTO `benefits` VALUES (1,'Everyday Low Mart Pricing','Affordable everyday groceries and household savings direct from trusted brands without excessive markups.','tag',1,1,'2026-10-31 21:33:27','2026-11-04 11:48:26'),(2,'Free Local Delivery Radius','Enjoy free delivery within our 5km radius and nominal rates for adjoining zones calculated automatically.','truck',2,1,'2026-10-31 21:33:27','2026-10-31 21:33:27'),(3,'Transparent Floor Charges','Clear, upfront floor handling rates. Free delivery up to 1st floor with minimal fee for upper levels.','building',3,1,'2026-10-31 21:33:27','2026-10-31 21:33:27'),(4,'Secure DuitNow QR & Online Pay','Pay with instant DuitNow QR or bank transfer to confirm your order. Simple, fast and secure.','shield-check',4,1,'2026-10-31 21:33:27','2026-11-04 11:48:26');
/*!40000 ALTER TABLE `benefits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `brands` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brands_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` VALUES (1,'Coca-Cola','coca-cola',NULL,1,'2026-10-31 21:33:26','2026-10-31 21:33:26'),(2,'Fanta','fanta',NULL,1,'2026-10-31 21:33:26','2026-10-31 21:33:26'),(3,'100 Plus','100-plus',NULL,1,'2026-10-31 21:33:26','2026-10-31 21:33:26'),(4,'Milo','milo',NULL,1,'2026-10-31 21:33:26','2026-10-31 21:33:26'),(5,'Dutch Lady','dutch-lady',NULL,1,'2026-10-31 21:33:26','2026-10-31 21:33:26'),(6,'Buruh','buruh',NULL,1,'2026-10-31 21:33:26','2026-10-31 21:33:26'),(7,'Seri Murni','seri-murni',NULL,1,'2026-10-31 21:33:26','2026-10-31 21:33:26'),(8,'Maggi','maggi',NULL,1,'2026-10-31 21:33:26','2026-10-31 21:33:26'),(9,'Ayam Brand','ayam-brand',NULL,1,'2026-10-31 21:33:26','2026-10-31 21:33:26'),(10,'Julie\'s','julies',NULL,1,'2026-10-31 21:33:26','2026-10-31 21:33:26'),(11,'Nescafe','nescafe',NULL,1,'2026-10-31 21:33:26','2026-10-31 21:33:26'),(12,'Breeze','breeze',NULL,1,'2026-10-31 21:33:26','2026-10-31 21:33:26'),(13,'Dynamo','dynamo',NULL,1,'2026-10-31 21:33:26','2026-10-31 21:33:26'),(14,'Premier','premier',NULL,1,'2026-10-31 21:33:26','2026-10-31 21:33:26'),(15,'Dettol','dettol',NULL,1,'2026-10-31 21:33:26','2026-10-31 21:33:26'),(16,'Sunlight','sunlight',NULL,1,'2026-11-04 12:09:51','2026-11-04 12:09:51');
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('bulk-mart-cache-356a192b7913b04c54574d18c28d46e6395428ab','i:1;',1793832484),('bulk-mart-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer','i:1793832484;',1793832484),('bulk-mart-cache-5c785c036466adea360111aa28563bfd556b5fba','i:1;',1794059851),('bulk-mart-cache-5c785c036466adea360111aa28563bfd556b5fba:timer','i:1794059851;',1794059851),('bulk-mart-cache-admin@99bulksales.my|127.0.0.1','i:1;',1793838771),('bulk-mart-cache-admin@99bulksales.my|127.0.0.1:timer','i:1793838771;',1793838771);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci,
  `canonical_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `robots` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'index, follow',
  `og_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_description` text COLLATE utf8mb4_unicode_ci,
  `og_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_card` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'summary_large_image',
  `focus_keyword` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `schema_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'CollectionPage',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,NULL,'Beverages & Soft Drinks','beverages-soft-drinks','Cartons of carbonated soft drinks, isotonic beverages, mineral water, and fruit juices.','categories/cat-1.png','categories/cat-1.png',1,1,'Beverages & Soft Drinks mart Cartons | 99 Bulk Sales','Cartons of carbonated soft drinks, isotonic beverages, mineral water, and fruit juices. Best mart prices in cartons with prompt delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'CollectionPage','2026-10-31 21:33:26','2026-11-01 19:50:11'),(2,NULL,'Milk & Dairy Drinks','milk-dairy-drinks','UHT milk cartons, condensed milk, evaporated creamer, and malt chocolate drinks in bulk.','categories/cat-2.png','categories/cat-2.png',2,1,'Milk & Dairy Drinks mart Cartons | 99 Bulk Sales','UHT milk cartons, condensed milk, evaporated creamer, and malt chocolate drinks in bulk. Best mart prices in cartons with prompt delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'CollectionPage','2026-10-31 21:33:26','2026-11-01 19:50:11'),(3,NULL,'Cooking Oil & Essentials','cooking-oil-essentials','Commercial 5kg/17kg cooking oil tins and cartons, pure ghee, and blended oils.','categories/cat-3.png','categories/cat-3.png',3,1,'Cooking Oil & Essentials mart Cartons | 99 Bulk Sales','Commercial 5kg/17kg cooking oil tins and cartons, pure ghee, and blended oils. Best mart prices in cartons with prompt delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'CollectionPage','2026-10-31 21:33:26','2026-11-01 19:50:11'),(4,NULL,'Rice, Noodles & Grains','rice-noodles-grains','Bulk cartons of instant noodles, vermicelli, pasta, fragrant rice, and basmati.','categories/cat-4.png','categories/cat-4.png',4,1,'Rice, Noodles & Grains mart Cartons | 99 Bulk Sales','Bulk cartons of instant noodles, vermicelli, pasta, fragrant rice, and basmati. Best mart prices in cartons with prompt delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'CollectionPage','2026-10-31 21:33:26','2026-11-01 19:50:11'),(5,NULL,'Canned Food & Condiments','canned-food-condiments','Cases of canned sardines, tuna, baked beans, tomato paste, soya sauce, and chilli sauces.','categories/cat-5.png','categories/cat-5.png',5,1,'Canned Food & Condiments mart Cartons | 99 Bulk Sales','Cases of canned sardines, tuna, baked beans, tomato paste, soya sauce, and chilli sauces. Best mart prices in cartons with prompt delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'CollectionPage','2026-10-31 21:33:26','2026-11-01 19:50:11'),(6,NULL,'Biscuits, Snacks & Crackers','biscuits-snacks-crackers','mart cartons of sandwich biscuits, potato chips, wafers, and snack packets.','categories/cat-6.png','categories/cat-6.png',6,1,'Biscuits, Snacks & Crackers mart Cartons | 99 Bulk Sales','mart cartons of sandwich biscuits, potato chips, wafers, and snack packets. Best mart prices in cartons with prompt delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'CollectionPage','2026-10-31 21:33:26','2026-11-01 19:50:11'),(7,NULL,'Coffee, Tea & Cocoa','coffee-tea-cocoa','Bulk 3-in-1 coffee cartons, premium tea bags, and hot chocolate mixes.','categories/cat-7.png','categories/cat-7.png',7,1,'Coffee, Tea & Cocoa mart Cartons | 99 Bulk Sales','Bulk 3-in-1 coffee cartons, premium tea bags, and hot chocolate mixes. Best mart prices in cartons with prompt delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'CollectionPage','2026-10-31 21:33:26','2026-11-01 19:50:11'),(8,NULL,'Laundry & Fabric Care','laundry-fabric-care','Liquid and powder detergent cartons, fabric softeners, and stain removers.','categories/cat-8.png','categories/cat-8.png',8,1,'Laundry & Fabric Care mart Cartons | 99 Bulk Sales','Liquid and powder detergent cartons, fabric softeners, and stain removers. Best mart prices in cartons with prompt delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'CollectionPage','2026-10-31 21:33:26','2026-11-01 19:50:11'),(9,NULL,'Household Paper & Tissues','household-paper-tissues','Cartons of facial tissues, kitchen towel rolls, and bulk toilet tissue rolls.','categories/cat-9.png','categories/cat-9.png',9,1,'Household Paper & Tissues mart Cartons | 99 Bulk Sales','Cartons of facial tissues, kitchen towel rolls, and bulk toilet tissue rolls. Best mart prices in cartons with prompt delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'CollectionPage','2026-10-31 21:33:26','2026-11-01 19:50:11'),(10,NULL,'Sanitation & Disinfectants','sanitation-disinfectants','Commercial floor cleaners, bleach cases, dishwashing paste, and hand wash gallons.','categories/cat-10.png','categories/cat-10.png',10,1,'Sanitation & Disinfectants mart Cartons | 99 Bulk Sales','Commercial floor cleaners, bleach cases, dishwashing paste, and hand wash gallons. Best mart prices in cartons with prompt delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'CollectionPage','2026-10-31 21:33:26','2026-11-01 19:50:11'),(11,NULL,'Abdul Rehman','abdul-rehman','test','categories/xzk88GK2ygiH9IyTpI0bY2FXOCN70KGnGOMSFqco.png','categories/icons/D067zvv9PNCSKjw96FaYk9HyD9Vk4HcRNGe7KdNY.png',0,1,'Abdul Rehman',NULL,NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'CollectionPage','2026-11-02 19:18:36','2026-11-02 19:18:36');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_addresses`
--

DROP TABLE IF EXISTS `customer_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_addresses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Home',
  `recipient_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_line` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `area_city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `floor` int NOT NULL DEFAULT '0' COMMENT '0 = Ground floor',
  `landmark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_addresses_user_id_foreign` (`user_id`),
  CONSTRAINT `customer_addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_addresses`
--

LOCK TABLES `customer_addresses` WRITE;
/*!40000 ALTER TABLE `customer_addresses` DISABLE KEYS */;
INSERT INTO `customer_addresses` VALUES (1,3,'Restaurant HQ','Nabeel Ahmad','+60112233445','No. 18, Jalan BK 5A/2, Bandar Kinrara','Puchong, Selangor',1,'Near Kinrara Golf Club',3.0380000,101.6420000,1,'2026-10-31 21:33:28','2026-10-31 21:33:28');
/*!40000 ALTER TABLE `customer_addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `data_requests`
--

DROP TABLE IF EXISTS `data_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `data_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `data_requests_user_id_foreign` (`user_id`),
  CONSTRAINT `data_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_requests`
--

LOCK TABLES `data_requests` WRITE;
/*!40000 ALTER TABLE `data_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `data_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faqs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'General',
  `question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faqs`
--

LOCK TABLES `faqs` WRITE;
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
INSERT INTO `faqs` VALUES (1,'Delivery','How does the free delivery radius work?','We offer completely Free Delivery for addresses located within 5 km straight-line distance of our warehouse. For addresses between 5 km and 7 km, a nominal delivery charge is applied. Beyond 7 km, checkout is restricted.',1,1,'2026-10-31 21:33:27','2026-10-31 21:33:27'),(2,'Delivery','How are floor charges calculated?','Delivery to Ground floor (Floor 0) and 1st floor (Floor 1) is completely FREE. For higher floors without a service lift or with stair delivery, a standard extra charge of RM 10.00 is charged per additional floor above Floor 1 (e.g. Floor 3 = 2 extra floors x RM 10 = RM 20).',2,1,'2026-10-31 21:33:27','2026-10-31 21:33:27'),(3,'Payment','Why is an advance payment required?','To confirm your order and schedule prompt delivery, an advance payment or deposit is requested via DuitNow QR or online transfer. This guarantees your fresh items and secures your delivery slot.',3,1,'2026-10-31 21:33:27','2026-11-04 11:52:12'),(4,'Payment','How do I submit payment proof?','During checkout, you will see our DuitNow QR code and the exact advance deposit amount. Scan and complete the transfer in your banking app, take a screenshot, and input the Transaction/Reference ID and upload the image before placing the order.',4,1,'2026-10-31 21:33:27','2026-10-31 21:33:27'),(5,'Ordering','Can I order as a guest without creating an account?','Yes! You can check out as a guest. All you need is your name, phone number, and delivery address. You can track your order at any time using your Order Number and phone number.',5,1,'2026-10-31 21:33:27','2026-10-31 21:33:27'),(6,'Ordering','What is the minimum order value?','There is no strict minimum order limit — shop for what you need and enjoy convenient local delivery!',6,1,'2026-10-31 21:33:27','2026-11-04 11:52:12');
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `home_sections`
--

DROP TABLE IF EXISTS `home_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `home_sections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `settings` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `home_sections_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `home_sections`
--

LOCK TABLES `home_sections` WRITE;
/*!40000 ALTER TABLE `home_sections` DISABLE KEYS */;
INSERT INTO `home_sections` VALUES (1,'categories','Shop by Category','Browse our fresh groceries, beverages, snacks, and daily household essentials.',1,1,NULL,'2026-10-31 21:33:27','2026-11-04 11:50:37'),(2,'promo','Featured Store Specials & Hot Deals','Limited-time discounts on fresh groceries and everyday family favorites.',2,1,NULL,'2026-10-31 21:33:27','2026-11-04 11:50:37'),(3,'new_arrivals','New Mart Arrivals','Latest fresh stock and products added to our shelves.',3,1,NULL,'2026-10-31 21:33:27','2026-11-04 11:50:37'),(4,'featured','Featured Mart Favorites','Everyday essentials trusted by our neighbourhood families and shoppers.',4,1,NULL,'2026-10-31 21:33:27','2026-11-04 11:50:37'),(5,'how_it_works','How Online Ordering Works','Fast, easy ordering delivered right to your doorstep in 4 simple steps.',5,1,NULL,'2026-10-31 21:33:27','2026-11-04 11:50:37'),(6,'benefits','Why Shop at N&J Mini Mart?','Genuine quality, reliable neighbourhood delivery, and everyday honest pricing.',6,1,NULL,'2026-10-31 21:33:27','2026-11-04 11:50:37'),(7,'faqs','Frequently Asked Questions','Everything you need to know about delivery, floor rates, and easy payment.',7,1,NULL,'2026-10-31 21:33:27','2026-11-04 11:50:37');
/*!40000 ALTER TABLE `home_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `how_it_works_steps`
--

DROP TABLE IF EXISTS `how_it_works_steps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `how_it_works_steps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `step_number` int NOT NULL DEFAULT '1',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `how_it_works_steps`
--

LOCK TABLES `how_it_works_steps` WRITE;
/*!40000 ALTER TABLE `how_it_works_steps` DISABLE KEYS */;
INSERT INTO `how_it_works_steps` VALUES (1,1,'Browse Products & Add to Cart','Explore our wide selection of fresh groceries, drinks, snacks, and essentials. Add items to your cart with ease.','cart',1,1,'2026-10-31 21:33:27','2026-11-04 11:48:26'),(2,2,'Pin Delivery Location & Floor','Select your exact delivery address on the interactive map. Enter your floor number to see live free or paid delivery rates.','map-pin',2,1,'2026-10-31 21:33:27','2026-10-31 21:33:27'),(3,3,'Scan QR & Upload Advance Proof','Scan the DuitNow QR to pay the small advance deposit (default 10%), then upload your payment screenshot and reference ID.','qrcode',3,1,'2026-10-31 21:33:27','2026-10-31 21:33:27'),(4,4,'Quick Verification & Fast Delivery','Our store team verifies your payment immediately, carefully packs your grocery items, and delivers right to your doorstep.','truck',4,1,'2026-10-31 21:33:27','2026-11-04 11:48:26');
/*!40000 ALTER TABLE `how_it_works_steps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (3,'default','{\"uuid\":\"79abedf3-b2be-41ba-b1ca-3be36c8ad564\",\"displayName\":\"App\\\\Mail\\\\OrderStatusUpdatedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:31:\\\"App\\\\Mail\\\\OrderStatusUpdatedMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"admin@99bulksales.my\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1793829123,\"delay\":null}',0,NULL,1793829123,1793829123),(4,'default','{\"uuid\":\"88afcfe1-d181-48be-8211-60cf3d2be937\",\"displayName\":\"App\\\\Mail\\\\OrderPlacedCustomerMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:32:\\\"App\\\\Mail\\\\OrderPlacedCustomerMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:9;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"admin@99bulksales.my\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1793832364,\"delay\":null}',0,NULL,1793832364,1793832364),(5,'default','{\"uuid\":\"94725925-b062-4bf7-b9cf-dc0b284ad081\",\"displayName\":\"App\\\\Mail\\\\OrderAlertAdminMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:28:\\\"App\\\\Mail\\\\OrderAlertAdminMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:9;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:19:\\\"ss4871836@gmail.com\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1793832364,\"delay\":null}',0,NULL,1793832364,1793832364),(6,'default','{\"uuid\":\"4f8a5dd6-fe3f-4173-b8a8-86431dee3c60\",\"displayName\":\"App\\\\Mail\\\\OrderPlacedCustomerMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:32:\\\"App\\\\Mail\\\\OrderPlacedCustomerMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:10;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:20:\\\"admin@99bulksales.my\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1793832424,\"delay\":null}',0,NULL,1793832424,1793832424),(7,'default','{\"uuid\":\"d2bd4764-1e7d-4a29-9f78-7d418556b879\",\"displayName\":\"App\\\\Mail\\\\OrderAlertAdminMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:28:\\\"App\\\\Mail\\\\OrderAlertAdminMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:10;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:19:\\\"ss4871836@gmail.com\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1793832424,\"delay\":null}',0,NULL,1793832424,1793832424);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_01_01_000010_create_settings_table',1),(5,'2026_01_01_000020_create_categories_table',1),(6,'2026_01_01_000030_create_brands_table',1),(7,'2026_01_01_000040_create_products_table',1),(8,'2026_01_01_000050_create_product_images_table',1),(9,'2026_01_01_000060_create_banners_and_cms_tables',1),(10,'2026_01_01_000070_create_customer_addresses_table',1),(11,'2026_01_01_000080_create_orders_and_order_items_tables',1),(12,'2026_01_01_000090_create_audit_and_notification_logs_tables',1),(13,'2026_11_01_041225_create_otp_codes_table',2),(14,'2026_11_04_151110_make_pack_size_nullable_in_products_table',3),(15,'2026_11_05_160000_add_purchase_price_to_products_table',4),(16,'2026_11_07_140407_add_low_stock_threshold_to_products_table',5);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notification_logs`
--

DROP TABLE IF EXISTS `notification_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notification_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned DEFAULT NULL,
  `channel` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_preview` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `error_message` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notification_logs_order_id_foreign` (`order_id`),
  CONSTRAINT `notification_logs_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notification_logs`
--

LOCK TABLES `notification_logs` WRITE;
/*!40000 ALTER TABLE `notification_logs` DISABLE KEYS */;
INSERT INTO `notification_logs` VALUES (1,1,'email','admin@99bulksales.my','Order confirmation email sent to customer.','success',NULL,'2026-10-31 23:20:06','2026-10-31 23:20:06'),(2,1,'email','admin@99bulksales.my','Order alert email sent to admin.','success',NULL,'2026-10-31 23:20:06','2026-10-31 23:20:06'),(3,1,'email','admin@99bulksales.my','Status update to pending_payment_verification','success',NULL,'2026-11-04 16:52:03','2026-11-04 16:52:03'),(4,9,'callmebot','+923176121524','CallMeBot unconfigured','skipped','Missing credentials','2026-11-04 17:46:04','2026-11-04 17:46:04'),(5,9,'email','admin@99bulksales.my','Order confirmation email sent to customer.','success',NULL,'2026-11-04 17:46:04','2026-11-04 17:46:04'),(6,9,'email','ss4871836@gmail.com','Order alert email sent to admin.','success',NULL,'2026-11-04 17:46:04','2026-11-04 17:46:04'),(7,10,'callmebot','+923176121524','CallMeBot unconfigured','skipped','Missing credentials','2026-11-04 17:47:04','2026-11-04 17:47:04'),(8,10,'email','admin@99bulksales.my','Order confirmation email sent to customer.','success',NULL,'2026-11-04 17:47:04','2026-11-04 17:47:04'),(9,10,'email','ss4871836@gmail.com','Order alert email sent to admin.','success',NULL,'2026-11-04 17:47:04','2026-11-04 17:47:04');
/*!40000 ALTER TABLE `notification_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_histories`
--

DROP TABLE IF EXISTS `order_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `status_from` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_to` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `changed_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_histories_order_id_foreign` (`order_id`),
  KEY `order_histories_changed_by_foreign` (`changed_by`),
  CONSTRAINT `order_histories_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `order_histories_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_histories`
--

LOCK TABLES `order_histories` WRITE;
/*!40000 ALTER TABLE `order_histories` DISABLE KEYS */;
INSERT INTO `order_histories` VALUES (1,1,NULL,'pending_payment_verification','Order placed with payment proof (Ref: tesetes). Awaiting admin verification.',1,'2026-10-31 23:20:06','2026-10-31 23:20:06'),(2,2,NULL,'delivered','POS in-store counter sale completed. Items handed to customer.',1,'2026-11-04 16:49:07','2026-11-04 16:49:07'),(3,3,NULL,'delivered','POS in-store counter sale completed. Items handed to customer.',1,'2026-11-04 16:49:20','2026-11-04 16:49:20'),(4,4,NULL,'delivered','POS in-store counter sale completed. Items handed to customer.',1,'2026-11-04 16:49:52','2026-11-04 16:49:52'),(5,5,NULL,'delivered','POS in-store counter sale completed. Items handed to customer.',1,'2026-11-04 16:50:07','2026-11-04 16:50:07'),(6,6,NULL,'delivered','POS in-store counter sale completed. Items handed to customer.',1,'2026-11-04 16:50:12','2026-11-04 16:50:12'),(7,7,NULL,'delivered','POS in-store counter sale completed. Items handed to customer.',1,'2026-11-04 16:51:19','2026-11-04 16:51:19'),(8,8,NULL,'delivered','POS in-store counter sale completed. Items handed to customer.',1,'2026-11-04 16:51:25','2026-11-04 16:51:25'),(9,1,'pending_payment_verification','confirmed','Payment verified by admin. ',1,'2026-11-04 16:52:02','2026-11-04 16:52:02'),(10,9,NULL,'pending_payment_verification','Order placed with payment proof (Ref: tette). Awaiting admin verification.',1,'2026-11-04 17:46:04','2026-11-04 17:46:04'),(11,10,NULL,'pending_payment_verification','Order placed with payment proof (Ref: testa). Awaiting admin verification.',1,'2026-11-04 17:47:04','2026-11-04 17:47:04'),(12,11,NULL,'delivered','POS in-store counter sale completed. Items handed to customer.',1,'2026-11-05 12:05:02','2026-11-05 12:05:02');
/*!40000 ALTER TABLE `order_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pack_size` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  `line_total` decimal(10,2) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,11,'KNIFE BRAND BLENDED COOKING OIL 4*5KG','OIL-KNF-03','4 x 5KG Bottle Carton',138.00,2,276.00,'products/product-11.png','2026-10-31 23:20:06','2026-10-31 23:20:06'),(2,1,7,'DUTCH LADY CHOCOLATE UHT 12*1L','DRY-DUT-03','12 x 1L Tetra Brik Carton',64.90,1,64.90,'products/product-7.png','2026-10-31 23:20:06','2026-10-31 23:20:06'),(3,2,3,'100 PLUS ISOTONIC 24*325ML','BEV-100-03','24 x 325ML Can Pack',41.50,1,41.50,'products/product-3.png','2026-11-04 16:49:07','2026-11-04 16:49:07'),(4,3,17,'AYAM BRAND SARDINES IN TOMATO SAUCE 24*425G','CAN-AYM-01','24 x 425G Big Tin Pack',182.00,1,182.00,'products/product-17.png','2026-11-04 16:49:20','2026-11-04 16:49:20'),(5,4,19,'AYAM BRAND BAKED BEANS 24*425G','CAN-AYM-03','24 x 425G Tin Pack',104.50,1,104.50,'products/product-19.png','2026-11-04 16:49:52','2026-11-04 16:49:52'),(6,5,3,'100 PLUS ISOTONIC 24*325ML','BEV-100-03','24 x 325ML Can Pack',41.50,1,41.50,'products/product-3.png','2026-11-04 16:50:07','2026-11-04 16:50:07'),(7,6,17,'AYAM BRAND SARDINES IN TOMATO SAUCE 24*425G','CAN-AYM-01','24 x 425G Big Tin Pack',182.00,1,182.00,'products/product-17.png','2026-11-04 16:50:12','2026-11-04 16:50:12'),(8,7,17,'AYAM BRAND SARDINES IN TOMATO SAUCE 24*425G','CAN-AYM-01','24 x 425G Big Tin Pack',182.00,5,910.00,'products/product-17.png','2026-11-04 16:51:19','2026-11-04 16:51:19'),(9,8,17,'AYAM BRAND SARDINES IN TOMATO SAUCE 24*425G','CAN-AYM-01','24 x 425G Big Tin Pack',182.00,1,182.00,'products/product-17.png','2026-11-04 16:51:25','2026-11-04 16:51:25'),(10,9,39,'SUNLIGHT DISHWASHING LIQUID LIME 6*4L','SAN-SUN-03','6 x 4L Value Jug Pack',89.90,2,179.80,'products/product-39.png','2026-11-04 17:46:04','2026-11-04 17:46:04'),(11,10,2,'COCA-COLA ORIGINAL 24*320ML','BEV-COC-02','24 x 320ML Can Pack',39.50,3,118.50,'products/product-2.png','2026-11-04 17:47:04','2026-11-04 17:47:04'),(12,11,17,'AYAM BRAND SARDINES IN TOMATO SAUCE 24*425G','CAN-AYM-01','24 x 425G Big Tin Pack',182.00,1,182.00,'products/product-17.png','2026-11-05 12:05:02','2026-11-05 12:05:02'),(13,11,18,'AYAM BRAND TUNA FLAKES IN OIL 24*150G','CAN-AYM-02','24 x 150G Easy-Open Tin Pack',142.00,1,142.00,'products/product-18.png','2026-11-05 12:05:02','2026-11-05 12:05:02'),(14,11,31,'BREEZE COLOUR CARE DETERGENT POWDER 4*4.4KG','LND-BRZ-03','4 x 4.4KG Bag Pack',84.50,1,84.50,'products/product-31.png','2026-11-05 12:05:02','2026-11-05 12:05:02');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `delivery_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `delivery_city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `floor` int NOT NULL DEFAULT '0' COMMENT '0 = Ground floor',
  `landmark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_notes` text COLLATE utf8mb4_unicode_ci,
  `latitude` decimal(10,7) NOT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `distance_km` decimal(8,2) NOT NULL,
  `delivery_zone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'free or paid',
  `subtotal` decimal(10,2) NOT NULL,
  `floor_charge` decimal(10,2) NOT NULL DEFAULT '0.00',
  `delivery_charge` decimal(10,2) NOT NULL DEFAULT '0.00',
  `grand_total` decimal(10,2) NOT NULL,
  `advance_percentage` decimal(5,2) NOT NULL DEFAULT '10.00',
  `advance_amount` decimal(10,2) NOT NULL,
  `balance_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'qr_advance',
  `payment_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Transaction / Ref ID',
  `payment_proof_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Private storage path',
  `payment_status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending_verification',
  `payment_rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `payment_verified_at` timestamp NULL DEFAULT NULL,
  `payment_verified_by` bigint unsigned DEFAULT NULL,
  `order_status` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending_payment_verification',
  `internal_notes` text COLLATE utf8mb4_unicode_ci,
  `accepted_policy_versions` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `idempotency_token` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  UNIQUE KEY `orders_payment_reference_unique` (`payment_reference`),
  UNIQUE KEY `orders_idempotency_token_unique` (`idempotency_token`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_payment_verified_by_foreign` (`payment_verified_by`),
  KEY `orders_customer_phone_index` (`customer_phone`),
  KEY `orders_customer_email_index` (`customer_email`),
  KEY `orders_payment_status_index` (`payment_status`),
  KEY `orders_order_status_index` (`order_status`),
  CONSTRAINT `orders_payment_verified_by_foreign` FOREIGN KEY (`payment_verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'BS-2026-000101',1,'Store Administrator','+60128899900','admin@99bulksales.my','tts','Puchong, Selangor',0,NULL,NULL,3.0245000,101.6166000,0.00,'free',340.90,0.00,0.00,340.90,10.00,34.09,306.81,'qr_advance','tesetes','payment_proofs/proof_20261101_042006_tsAHlr3pI7ZH9ABl.jpeg','verified',NULL,'2026-11-04 16:52:02',1,'confirmed',NULL,'{\"ip\": \"2407:aa80:126:b0f0:9cd5:3294:fb71:39bb\", \"timestamp\": \"2026-11-01T04:20:06+00:00\", \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36\", \"terms_accepted\": true}','2407:aa80:126:b0f0:9cd5:3294:fb71:39bb','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','052b3e23-0358-4dc3-8b23-685cf9a2c53c','2026-10-31 23:20:06','2026-11-04 16:52:02'),(2,'POS-2026-01001',NULL,'Walk-in Customer','-','pos@nandjminimart.com','N&J Mini Mart Counter (Walk-in POS)','Puchong',0,'In-Store Register #1',NULL,1.5205574,103.6616431,0.00,'pos_counter',41.50,0.00,0.00,41.50,100.00,41.50,0.00,'cash','POS-CASH-EIB6FA',NULL,'verified',NULL,'2026-11-04 16:49:07',1,'delivered','In-store POS counter checkout by Store Administrator. Tendered: RM 41.50, Change: RM 0.00',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','ae3982d6-2644-41dc-b4cd-799486f927e0','2026-11-04 16:49:07','2026-11-04 16:49:07'),(3,'POS-2026-01002',NULL,'Walk-in Customer','-','pos@nandjminimart.com','N&J Mini Mart Counter (Walk-in POS)','Puchong',0,'In-Store Register #1',NULL,1.5205574,103.6616431,0.00,'pos_counter',182.00,0.00,0.00,182.00,100.00,182.00,0.00,'cash','POS-CASH-FLWVPU',NULL,'verified',NULL,'2026-11-04 16:49:20',1,'delivered','In-store POS counter checkout by Store Administrator. Tendered: RM 182.00, Change: RM 0.00',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','8a0f7476-2859-4764-9469-a14324055a4c','2026-11-04 16:49:20','2026-11-04 16:49:20'),(4,'POS-2026-01003',NULL,'Walk-in Customer','-','pos@nandjminimart.com','N&J Mini Mart Counter (Walk-in POS)','Puchong',0,'In-Store Register #1',NULL,1.5205574,103.6616431,0.00,'pos_counter',104.50,0.00,0.00,104.50,100.00,104.50,0.00,'cash','POS-CASH-IW9CUA',NULL,'verified',NULL,'2026-11-04 16:49:52',1,'delivered','In-store POS counter checkout by Store Administrator. Tendered: RM 104.50, Change: RM 0.00',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','f4cb2dcb-6fef-4e57-9d77-b71fb7e4c8c5','2026-11-04 16:49:52','2026-11-04 16:49:52'),(5,'POS-2026-01004',NULL,'Walk-in Customer','-','pos@nandjminimart.com','N&J Mini Mart Counter (Walk-in POS)','Puchong',0,'In-Store Register #1',NULL,1.5205574,103.6616431,0.00,'pos_counter',41.50,0.00,0.00,41.50,100.00,41.50,0.00,'cash','POS-CASH-1JIQU0',NULL,'verified',NULL,'2026-11-04 16:50:07',1,'delivered','In-store POS counter checkout by Store Administrator. Tendered: RM 41.50, Change: RM 0.00',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','b84e504e-49aa-4bf7-b5c4-636aab01e8a0','2026-11-04 16:50:07','2026-11-04 16:50:07'),(6,'POS-2026-01005',NULL,'Walk-in Customer','-','pos@nandjminimart.com','N&J Mini Mart Counter (Walk-in POS)','Puchong',0,'In-Store Register #1',NULL,1.5205574,103.6616431,0.00,'pos_counter',182.00,0.00,0.00,182.00,100.00,182.00,0.00,'cash','POS-CASH-NBBOTG',NULL,'verified',NULL,'2026-11-04 16:50:12',1,'delivered','In-store POS counter checkout by Store Administrator. Tendered: RM 182.00, Change: RM 0.00',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','f00956c3-48af-43f9-b166-b28da478886b','2026-11-04 16:50:12','2026-11-04 16:50:12'),(7,'POS-2026-01006',NULL,'Walk-in Customer','-','pos@nandjminimart.com','N&J Mini Mart Counter (Walk-in POS)','Puchong',0,'In-Store Register #1',NULL,1.5205574,103.6616431,0.00,'pos_counter',910.00,0.00,0.00,910.00,100.00,910.00,0.00,'cash','POS-CASH-NHAJFX',NULL,'verified',NULL,'2026-11-04 16:51:19',1,'delivered','In-store POS counter checkout by Store Administrator. Tendered: RM 910.00, Change: RM 0.00',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','7bdf8c5a-3865-414d-96ea-f22ee1009aea','2026-11-04 16:51:19','2026-11-04 16:51:19'),(8,'POS-2026-01007',NULL,'Walk-in Customer','-','pos@nandjminimart.com','N&J Mini Mart Counter (Walk-in POS)','Puchong',0,'In-Store Register #1',NULL,1.5205574,103.6616431,0.00,'pos_counter',182.00,0.00,0.00,182.00,100.00,182.00,0.00,'cash','POS-CASH-OSDNF5',NULL,'verified',NULL,'2026-11-04 16:51:25',1,'delivered','In-store POS counter checkout by Store Administrator. Tendered: RM 182.00, Change: RM 0.00',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','501d9a81-5ae4-485a-99c5-4d0041016c04','2026-11-04 16:51:25','2026-11-04 16:51:25'),(9,'BS-2026-000102',1,'Store Administrator','+60128899900','admin@99bulksales.my','test','Puchong, Selangor',0,NULL,NULL,1.5205574,103.6616431,0.00,'free',179.80,0.00,0.00,179.80,10.00,17.98,161.82,'qr_advance','tette','payment_proofs/proof_20261104_224604_FiQWRF7Hy4KMnfY7.webp','pending_verification',NULL,NULL,NULL,'pending_payment_verification',NULL,'{\"ip\": \"127.0.0.1\", \"timestamp\": \"2026-11-04T22:46:04+00:00\", \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36\", \"terms_accepted\": true}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','c7e72a6a-98a8-4038-9b55-a2bc5faf3237','2026-11-04 17:46:04','2026-11-04 17:46:04'),(10,'BS-2026-000103',1,'Store Administrator','+60128899900','admin@99bulksales.my','teste','Puchong, Selangor',0,NULL,NULL,1.5205574,103.6616431,0.00,'free',118.50,0.00,0.00,118.50,10.00,11.85,106.65,'qr_advance','testa','payment_proofs/proof_20261104_224704_1QTF4JLnvtleY3lc.webp','pending_verification',NULL,NULL,NULL,'pending_payment_verification',NULL,'{\"ip\": \"127.0.0.1\", \"timestamp\": \"2026-11-04T22:47:04+00:00\", \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36\", \"terms_accepted\": true}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','1988298f-2dd8-471e-82f7-044590dbd4d3','2026-11-04 17:47:04','2026-11-04 17:47:04'),(11,'POS-2026-01008',NULL,'Walk-in Customer','-','pos@nandjminimart.com','N&J Mini Mart Counter (Walk-in POS)','Puchong',0,'In-Store Register #1',NULL,1.5205574,103.6616431,0.00,'pos_counter',408.50,0.00,0.00,408.50,100.00,408.50,0.00,'cash','POS-CASH-Y7XV82',NULL,'verified',NULL,'2026-11-05 12:05:01',1,'delivered','In-store POS counter checkout by N&I Store. Tendered: RM 408.50, Change: RM 0.00',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','f9aae16b-c06e-420c-91e3-ee4503124c43','2026-11-05 12:05:02','2026-11-05 12:05:02');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `otp_codes`
--

DROP TABLE IF EXISTS `otp_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `otp_codes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `identifier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'email_verification',
  `attempts` tinyint unsigned NOT NULL DEFAULT '0',
  `max_attempts` tinyint unsigned NOT NULL DEFAULT '5',
  `expires_at` timestamp NOT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `otp_codes_user_id_foreign` (`user_id`),
  KEY `otp_codes_identifier_index` (`identifier`),
  KEY `otp_codes_type_index` (`type`),
  KEY `otp_codes_expires_at_index` (`expires_at`),
  CONSTRAINT `otp_codes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `otp_codes`
--

LOCK TABLES `otp_codes` WRITE;
/*!40000 ALTER TABLE `otp_codes` DISABLE KEYS */;
INSERT INTO `otp_codes` VALUES (1,4,'customer@example.com','$2y$12$7vOouOT8ii7TFS1jXfI5F.vqpUJwqLXF3w.dbPPumOmuQWzDl.Jfy','email_verification',0,5,'2026-11-01 17:45:55',NULL,'2026-11-01 17:35:56','2026-11-01 17:35:56'),(4,NULL,'ss4871836@gmail.com','$2y$12$DTTjtFxLkjvZPk0ZsFe8helFro44k1pUaeIW4ouIkUjbEdSCK/zke','email_verification',1,5,'2026-11-02 17:18:04','2026-11-02 17:08:11','2026-11-02 17:08:05','2026-11-02 17:08:11');
/*!40000 ALTER TABLE `otp_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_versions`
--

DROP TABLE IF EXISTS `page_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `page_versions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `page_id` bigint unsigned NOT NULL,
  `version_number` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `change_summary` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `page_versions_page_id_foreign` (`page_id`),
  KEY `page_versions_created_by_foreign` (`created_by`),
  CONSTRAINT `page_versions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `page_versions_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_versions`
--

LOCK TABLES `page_versions` WRITE;
/*!40000 ALTER TABLE `page_versions` DISABLE KEYS */;
INSERT INTO `page_versions` VALUES (1,1,1,'Privacy Policy','<h2>Privacy Policy for {store_name}</h2>\n<p><strong>Last Updated: {last_updated}</strong></p>\n<p>{store_name} (Company Registration No: {company_registration_no}) is committed to safeguarding the personal data of our customers and visitors in compliance with applicable Personal Data Protection laws.</p>\n\n<h3>1. Data We Collect</h3>\n<p>To process mart bulk orders and arrange doorstep carton logistics, we collect:</p>\n<ul>\n    <li><strong>Identity & Contact Information:</strong> Full name, phone number, and email address.</li>\n    <li><strong>Delivery Information:</strong> Physical delivery address, city, floor level, landmarks, and geographic coordinates selected on our interactive map.</li>\n    <li><strong>Payment Verification Data:</strong> Bank transaction reference IDs and payment confirmation screenshot receipts uploaded during checkout.</li>\n    <li><strong>Device & Session Data:</strong> IP address, browser type, and cookie preferences.</li>\n</ul>\n\n<h3>2. Purpose of Collection</h3>\n<p>We process your data strictly to verify advance payments, fulfill and dispatch carton deliveries to your floor, communicate order status updates, prevent fraudulent submissions, and meet statutory accounting regulations.</p>\n\n<h3>3. Data Storage & Protection</h3>\n<p>Payment receipt screenshots are maintained on private, non-public storage directories accessible solely by authorised administrators via cryptographically signed temporary tokens. Personal data is never sold, traded, or shared with third-party advertising brokers.</p>\n\n<h3>4. Your Rights & Data Requests</h3>\n<p>You possess the right to access your personal data, request corrections, export your purchase history, or request account deletion via our <a href=\"/data-request\">Data Request Page</a>.</p>\n\n<h3>5. Contact Us</h3>\n<p>For inquiries regarding this policy, contact our Data Privacy Officer at <a href=\"mailto:{store_email}\">{store_email}</a> or phone {store_phone}.</p>',NULL,'Initial seeded template','2026-10-31 21:33:27','2026-10-31 21:33:27'),(2,2,1,'Terms & Conditions','<h2>Terms and Conditions</h2>\n<p><strong>Effective Date: {last_updated}</strong></p>\n<p>Welcome to {store_name}. By accessing our bulk ordering platform or placing an order, you agree to comply with and be bound by the following Terms and Conditions.</p>\n\n<h3>1. mart Carton Supply</h3>\n<p>Products on {store_name} are sold in manufacturer carton or case packaging as indicated in each product description (e.g., \"12*1.25L\", \"24*320ML\"). Individual unit splitting is not supported unless explicitly noted.</p>\n\n<h3>2. Minimum Order Value</h3>\n<p>The minimum order requirement across our store is {min_order_value}. Orders below this value cannot be processed through checkout.</p>\n\n<h3>3. Order Placement & Advance Deposit</h3>\n<p>Orders are officially placed only upon submission of valid advance payment proof (representing {advance_percentage} of the total order value). Submitting a falsified or duplicated payment receipt constitutes breach of terms and may lead to cancellation and blacklisting.</p>\n\n<h3>4. Price & Availability</h3>\n<p>While we make every endeavor to maintain accurate live inventory and mart pricing, we reserve the right to correct manifest clerical errors and notify you prior to dispatch.</p>\n\n<h3>5. Governing Law</h3>\n<p>These terms shall be governed by and construed in accordance with the laws of Malaysia, and parties submit to the exclusive jurisdiction of the competent courts.</p>',NULL,'Initial seeded template','2026-10-31 21:33:27','2026-10-31 21:33:27'),(3,3,1,'Refund & Return Policy','<h2>Refund and Return Policy</h2>\n<p><strong>Last Updated: {last_updated}</strong></p>\n<p>At {store_name}, customer satisfaction with our mart goods is our highest priority.</p>\n\n<h3>1. Inspection Upon Delivery</h3>\n<p>Because bulk goods are delivered via dedicated ground delivery, customers or their authorized representatives must inspect outer carton seals upon receipt before settling the final cash or transfer balance.</p>\n\n<h3>2. Return Window</h3>\n<p>Claims for missing items, damaged packaging during transit, or expired stock must be reported within <strong>{return_window_days}</strong> of delivery with clear photographic evidence.</p>\n\n<h3>3. Non-Returnable Goods</h3>\n<p>Perishable food items, opened cartons where individual units have been consumed or removed, and items damaged due to inappropriate storage after delivery cannot be returned.</p>\n\n<h3>4. Refund Method</h3>\n<p>Approved refunds for verified defective or non-delivered carton goods are processed via direct bank transfer or instant DuitNow back to the originating account within 3 to 5 business days.</p>',NULL,'Initial seeded template','2026-10-31 21:33:27','2026-10-31 21:33:27'),(4,4,1,'Shipping & Delivery Policy','<h2>Shipping and Delivery Policy</h2>\n<p><strong>Last Updated: {last_updated}</strong></p>\n<p>{store_name} operates specialized logistics vehicles suited for transporting heavy mart food and grocery cartons.</p>\n\n<h3>1. Delivery Zones & Distance Calculations</h3>\n<p>Delivery distance is calculated dynamically using straight-line geographical coordinates from our central fulfillment hub at {store_address}:</p>\n<ul>\n    <li><strong>Free Delivery Zone:</strong> Deliveries within <strong>{free_delivery_radius}</strong> receive 100% Free Shipping.</li>\n    <li><strong>Paid Delivery Zone:</strong> Deliveries from <strong>{free_delivery_radius} up to {paid_delivery_radius}</strong> additional radius are serviced at nominal delivery rates calculated at checkout.</li>\n    <li><strong>Beyond Serviceable Area:</strong> Addresses beyond our maximum serviceable radius cannot be accommodated via standard delivery.</li>\n</ul>\n\n<h3>2. Floor Level Policy & Charges</h3>\n<p>Due to the heavy physical weight of mart cartons, floor level rules apply:</p>\n<ul>\n    <li><strong>Up to {free_floor_limit}:</strong> Completely <strong>FREE delivery</strong>.</li>\n    <li><strong>Above {free_floor_limit}:</strong> Surcharge of <strong>{extra_floor_charge}</strong> per extra floor level.</li>\n</ul>\n\n<h3>3. Order Cut-Off Time</h3>\n<p>Orders confirmed and payment-verified before <strong>{order_cutoff_time}</strong> are scheduled for next-day dispatch. Orders received after this cut-off time are batched for the subsequent dispatch cycle.</p>',NULL,'Initial seeded template','2026-10-31 21:33:27','2026-10-31 21:33:27'),(5,5,1,'Payment Policy','<h2>Payment Policy</h2>\n<p><strong>Last Updated: {last_updated}</strong></p>\n<p>To provide high mart value and eliminate third-party gateway fee markups, {store_name} utilizes a streamlined QR advance payment model.</p>\n\n<h3>1. Advance Deposit Requirement</h3>\n<p>Customers are required to transfer an advance deposit of <strong>{advance_percentage}</strong> of the grand total at the time of checkout. This deposit confirms your booking and reserves heavy warehouse inventory.</p>\n\n<h3>2. How to Pay Advance</h3>\n<ol>\n    <li>Scan the displayed DuitNow QR code or initiate an instant bank transfer using your mobile banking application.</li>\n    <li>Input the exact advance figure stated on your order summary.</li>\n    <li>Save or screenshot the completed transaction confirmation.</li>\n    <li>Submit the screenshot and unique Reference ID into the checkout portal.</li>\n</ol>\n\n<h3>3. Balance Payment on Delivery</h3>\n<p>The remaining balance is payable upon delivery directly to our delivery team via Cash, DuitNow QR on delivery, or instant transfer upon receipt of cartons.</p>\n\n<h3>4. Payment Rejection</h3>\n<p>If a submitted payment screenshot is unreadable, invalid, or shows a mismatched transaction amount, our administrators will flag the order as <em>Payment Rejected</em> with a written reason. You may re-submit proof or contact customer care at {store_phone}.</p>',NULL,'Initial seeded template','2026-10-31 21:33:27','2026-10-31 21:33:27'),(6,6,1,'Cancellation Policy','<h2>Cancellation Policy</h2>\n<p><strong>Last Updated: {last_updated}</strong></p>\n<p>We understand plans change. Here is our policy regarding order cancellations:</p>\n\n<h3>1. Cancellation Before Verification</h3>\n<p>If your order is in <em>Pending Payment Verification</em> status and you wish to cancel, you may contact our customer support team immediately to cancel with full advance refund.</p>\n\n<h3>2. Cancellation After Dispatch</h3>\n<p>Once an order has been marked as <em>Out for Delivery</em>, the delivery run cannot be halted in transit. Cancellations at the doorstep may be subject to a logistics handling fee to cover vehicle dispatch and driver costs.</p>',NULL,'Initial seeded template','2026-10-31 21:33:28','2026-10-31 21:33:28'),(7,7,1,'Cookie Policy','<h2>Cookie Policy</h2>\n<p><strong>Last Updated: {last_updated}</strong></p>\n<p>{store_name} uses cookies and related browser storage technologies to deliver a secure, responsive browsing experience.</p>\n\n<h3>1. Essential Cookies</h3>\n<p>These cookies are technically indispensable for operating the website, preserving items in your shopping cart across pages, and protecting against CSRF attacks. They do not store personally identifiable promotional profiles.</p>\n\n<h3>2. Analytics & Performance Cookies</h3>\n<p>Non-essential analytics cookies (such as Google Analytics or visitor statistics) are loaded <strong>only after you grant explicit consent</strong> via our Cookie Consent banner.</p>\n\n<h3>3. Managing Cookies</h3>\n<p>You can customize your preferences or withdraw consent at any time via your browser settings or our cookie preference dialog.</p>',NULL,'Initial seeded template','2026-10-31 21:33:28','2026-10-31 21:33:28'),(8,8,1,'Security Policy','<h2>Security Policy</h2>\n<p><strong>Last Updated: {last_updated}</strong></p>\n<p>{store_name} implements defense-in-depth security principles to protect our customer transactions and infrastructure:</p>\n<ul>\n    <li><strong>Strict Access Controls:</strong> Payment screenshots and customer delivery addresses are stored in private storage partitions accessible solely by authenticated staff members through cryptographically signed temporary routes.</li>\n    <li><strong>Encrypted Transport:</strong> All platform traffic is encrypted end-to-end via TLS/HTTPS with modern cipher suites and HSTS enforcement.</li>\n    <li><strong>Server-Side Pricing Integrity:</strong> All product prices, delivery zone calculations, floor fees, and deposit requirements are strictly computed on the server during order creation to prevent client-side parameter tampering.</li>\n    <li><strong>Rate Limiting & Abuse Prevention:</strong> Authentication, search queries, and payment proof submissions are throttled to avert brute-force and credential stuffing attacks.</li>\n</ul>',NULL,'Initial seeded template','2026-10-31 21:33:28','2026-10-31 21:33:28'),(9,9,1,'Disclaimer','<h2>Website & Product Disclaimer</h2>\n<p><strong>Last Updated: {last_updated}</strong></p>\n<p>The information and product imagery presented on {store_name} are provided in good faith. Product carton packaging graphics and minor manufacturer reformulations may vary slightly from displayed stock photos without altering the core specification or carton unit count.</p>',NULL,'Initial seeded template','2026-10-31 21:33:28','2026-10-31 21:33:28'),(10,10,1,'About Us','<h2>About {store_name}</h2>\n<p>{store_name} is a premier bulk grocery and household supplies distributor headquartered in Puchong, Selangor. Established to bridge the gap between heavy FMCG manufacturers and end-users, we empower small eateries, sundry grocers, offices, and large households to acquire everyday carton essentials at direct mart volume pricing.</p>\n\n<h3>Our Mission</h3>\n<p>To make mart bulk grocery shopping effortless, transparent, and economical through streamlined digital ordering and reliable local fleet delivery.</p>\n\n<h3>Visit or Contact Our Distribution Center</h3>\n<p>\n    <strong>Address:</strong> {store_address}<br>\n    <strong>Telephone:</strong> {store_phone}<br>\n    <strong>Email:</strong> {store_email}<br>\n    <strong>Business Hours:</strong> Monday through Saturday, 8:00 AM – 7:00 PM\n</p>',NULL,'Initial seeded template','2026-10-31 21:33:28','2026-10-31 21:33:28');
/*!40000 ALTER TABLE `page_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '1',
  `show_in_footer` tinyint(1) NOT NULL DEFAULT '1',
  `show_at_checkout` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  `current_version` int NOT NULL DEFAULT '1',
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci,
  `canonical_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `robots` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'index, follow',
  `og_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_description` text COLLATE utf8mb4_unicode_ci,
  `og_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_card` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'summary_large_image',
  `focus_keyword` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `schema_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'WebPage',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'Privacy Policy','privacy-policy','<h2>Privacy Policy for {store_name}</h2>\n<p><strong>Last Updated: {last_updated}</strong></p>\n<p>{store_name} (Company Registration No: {company_registration_no}) is committed to safeguarding the personal data of our customers and visitors in compliance with applicable Personal Data Protection laws.</p>\n\n<h3>1. Data We Collect</h3>\n<p>To process mart bulk orders and arrange doorstep carton logistics, we collect:</p>\n<ul>\n    <li><strong>Identity & Contact Information:</strong> Full name, phone number, and email address.</li>\n    <li><strong>Delivery Information:</strong> Physical delivery address, city, floor level, landmarks, and geographic coordinates selected on our interactive map.</li>\n    <li><strong>Payment Verification Data:</strong> Bank transaction reference IDs and payment confirmation screenshot receipts uploaded during checkout.</li>\n    <li><strong>Device & Session Data:</strong> IP address, browser type, and cookie preferences.</li>\n</ul>\n\n<h3>2. Purpose of Collection</h3>\n<p>We process your data strictly to verify advance payments, fulfill and dispatch carton deliveries to your floor, communicate order status updates, prevent fraudulent submissions, and meet statutory accounting regulations.</p>\n\n<h3>3. Data Storage & Protection</h3>\n<p>Payment receipt screenshots are maintained on private, non-public storage directories accessible solely by authorised administrators via cryptographically signed temporary tokens. Personal data is never sold, traded, or shared with third-party advertising brokers.</p>\n\n<h3>4. Your Rights & Data Requests</h3>\n<p>You possess the right to access your personal data, request corrections, export your purchase history, or request account deletion via our <a href=\"/data-request\">Data Request Page</a>.</p>\n\n<h3>5. Contact Us</h3>\n<p>For inquiries regarding this policy, contact our Data Privacy Officer at <a href=\"mailto:{store_email}\">{store_email}</a> or phone {store_phone}.</p>',1,1,1,1,1,'Privacy Policy | {store_name}','Privacy Policy for {store_name}\nLast Updated: {last_updated}\n{store_name} (Company Registration No: {company_registration_no',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'WebPage','2026-10-31 21:33:27','2026-10-31 21:33:27'),(2,'Terms & Conditions','terms-and-conditions','<h2>Terms and Conditions</h2>\n<p><strong>Effective Date: {last_updated}</strong></p>\n<p>Welcome to {store_name}. By accessing our bulk ordering platform or placing an order, you agree to comply with and be bound by the following Terms and Conditions.</p>\n\n<h3>1. mart Carton Supply</h3>\n<p>Products on {store_name} are sold in manufacturer carton or case packaging as indicated in each product description (e.g., \"12*1.25L\", \"24*320ML\"). Individual unit splitting is not supported unless explicitly noted.</p>\n\n<h3>2. Minimum Order Value</h3>\n<p>The minimum order requirement across our store is {min_order_value}. Orders below this value cannot be processed through checkout.</p>\n\n<h3>3. Order Placement & Advance Deposit</h3>\n<p>Orders are officially placed only upon submission of valid advance payment proof (representing {advance_percentage} of the total order value). Submitting a falsified or duplicated payment receipt constitutes breach of terms and may lead to cancellation and blacklisting.</p>\n\n<h3>4. Price & Availability</h3>\n<p>While we make every endeavor to maintain accurate live inventory and mart pricing, we reserve the right to correct manifest clerical errors and notify you prior to dispatch.</p>\n\n<h3>5. Governing Law</h3>\n<p>These terms shall be governed by and construed in accordance with the laws of Malaysia, and parties submit to the exclusive jurisdiction of the competent courts.</p>',1,1,1,2,1,'Terms & Conditions | {store_name}','Terms and Conditions\nEffective Date: {last_updated}\nWelcome to {store_name}. By accessing our bulk ordering platform or plac',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'WebPage','2026-10-31 21:33:27','2026-10-31 21:33:27'),(3,'Refund & Return Policy','refund-and-return-policy','<h2>Refund and Return Policy</h2>\n<p><strong>Last Updated: {last_updated}</strong></p>\n<p>At {store_name}, customer satisfaction with our mart goods is our highest priority.</p>\n\n<h3>1. Inspection Upon Delivery</h3>\n<p>Because bulk goods are delivered via dedicated ground delivery, customers or their authorized representatives must inspect outer carton seals upon receipt before settling the final cash or transfer balance.</p>\n\n<h3>2. Return Window</h3>\n<p>Claims for missing items, damaged packaging during transit, or expired stock must be reported within <strong>{return_window_days}</strong> of delivery with clear photographic evidence.</p>\n\n<h3>3. Non-Returnable Goods</h3>\n<p>Perishable food items, opened cartons where individual units have been consumed or removed, and items damaged due to inappropriate storage after delivery cannot be returned.</p>\n\n<h3>4. Refund Method</h3>\n<p>Approved refunds for verified defective or non-delivered carton goods are processed via direct bank transfer or instant DuitNow back to the originating account within 3 to 5 business days.</p>',1,1,1,3,1,'Refund & Return Policy | {store_name}','Refund and Return Policy\nLast Updated: {last_updated}\nAt {store_name}, customer satisfaction with our mart goods is our',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'WebPage','2026-10-31 21:33:27','2026-10-31 21:33:27'),(4,'Shipping & Delivery Policy','shipping-and-delivery-policy','<h2>Shipping and Delivery Policy</h2>\n<p><strong>Last Updated: {last_updated}</strong></p>\n<p>{store_name} operates specialized logistics vehicles suited for transporting heavy mart food and grocery cartons.</p>\n\n<h3>1. Delivery Zones & Distance Calculations</h3>\n<p>Delivery distance is calculated dynamically using straight-line geographical coordinates from our central fulfillment hub at {store_address}:</p>\n<ul>\n    <li><strong>Free Delivery Zone:</strong> Deliveries within <strong>{free_delivery_radius}</strong> receive 100% Free Shipping.</li>\n    <li><strong>Paid Delivery Zone:</strong> Deliveries from <strong>{free_delivery_radius} up to {paid_delivery_radius}</strong> additional radius are serviced at nominal delivery rates calculated at checkout.</li>\n    <li><strong>Beyond Serviceable Area:</strong> Addresses beyond our maximum serviceable radius cannot be accommodated via standard delivery.</li>\n</ul>\n\n<h3>2. Floor Level Policy & Charges</h3>\n<p>Due to the heavy physical weight of mart cartons, floor level rules apply:</p>\n<ul>\n    <li><strong>Up to {free_floor_limit}:</strong> Completely <strong>FREE delivery</strong>.</li>\n    <li><strong>Above {free_floor_limit}:</strong> Surcharge of <strong>{extra_floor_charge}</strong> per extra floor level.</li>\n</ul>\n\n<h3>3. Order Cut-Off Time</h3>\n<p>Orders confirmed and payment-verified before <strong>{order_cutoff_time}</strong> are scheduled for next-day dispatch. Orders received after this cut-off time are batched for the subsequent dispatch cycle.</p>',1,1,0,4,1,'Shipping & Delivery Policy | {store_name}','Shipping and Delivery Policy\nLast Updated: {last_updated}\n{store_name} operates specialized logistics vehicles suited for tr',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'WebPage','2026-10-31 21:33:27','2026-10-31 21:33:27'),(5,'Payment Policy','payment-policy','<h2>Payment Policy</h2>\n<p><strong>Last Updated: {last_updated}</strong></p>\n<p>To provide high mart value and eliminate third-party gateway fee markups, {store_name} utilizes a streamlined QR advance payment model.</p>\n\n<h3>1. Advance Deposit Requirement</h3>\n<p>Customers are required to transfer an advance deposit of <strong>{advance_percentage}</strong> of the grand total at the time of checkout. This deposit confirms your booking and reserves heavy warehouse inventory.</p>\n\n<h3>2. How to Pay Advance</h3>\n<ol>\n    <li>Scan the displayed DuitNow QR code or initiate an instant bank transfer using your mobile banking application.</li>\n    <li>Input the exact advance figure stated on your order summary.</li>\n    <li>Save or screenshot the completed transaction confirmation.</li>\n    <li>Submit the screenshot and unique Reference ID into the checkout portal.</li>\n</ol>\n\n<h3>3. Balance Payment on Delivery</h3>\n<p>The remaining balance is payable upon delivery directly to our delivery team via Cash, DuitNow QR on delivery, or instant transfer upon receipt of cartons.</p>\n\n<h3>4. Payment Rejection</h3>\n<p>If a submitted payment screenshot is unreadable, invalid, or shows a mismatched transaction amount, our administrators will flag the order as <em>Payment Rejected</em> with a written reason. You may re-submit proof or contact customer care at {store_phone}.</p>',1,1,0,5,1,'Payment Policy | {store_name}','Payment Policy\nLast Updated: {last_updated}\nTo provide high mart value and eliminate third-party gateway fee markups, {',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'WebPage','2026-10-31 21:33:27','2026-10-31 21:33:27'),(6,'Cancellation Policy','cancellation-policy','<h2>Cancellation Policy</h2>\n<p><strong>Last Updated: {last_updated}</strong></p>\n<p>We understand plans change. Here is our policy regarding order cancellations:</p>\n\n<h3>1. Cancellation Before Verification</h3>\n<p>If your order is in <em>Pending Payment Verification</em> status and you wish to cancel, you may contact our customer support team immediately to cancel with full advance refund.</p>\n\n<h3>2. Cancellation After Dispatch</h3>\n<p>Once an order has been marked as <em>Out for Delivery</em>, the delivery run cannot be halted in transit. Cancellations at the doorstep may be subject to a logistics handling fee to cover vehicle dispatch and driver costs.</p>',1,1,0,6,1,'Cancellation Policy | {store_name}','Cancellation Policy\nLast Updated: {last_updated}\nWe understand plans change. Here is our policy regarding order cancellation',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'WebPage','2026-10-31 21:33:28','2026-10-31 21:33:28'),(7,'Cookie Policy','cookie-policy','<h2>Cookie Policy</h2>\n<p><strong>Last Updated: {last_updated}</strong></p>\n<p>{store_name} uses cookies and related browser storage technologies to deliver a secure, responsive browsing experience.</p>\n\n<h3>1. Essential Cookies</h3>\n<p>These cookies are technically indispensable for operating the website, preserving items in your shopping cart across pages, and protecting against CSRF attacks. They do not store personally identifiable promotional profiles.</p>\n\n<h3>2. Analytics & Performance Cookies</h3>\n<p>Non-essential analytics cookies (such as Google Analytics or visitor statistics) are loaded <strong>only after you grant explicit consent</strong> via our Cookie Consent banner.</p>\n\n<h3>3. Managing Cookies</h3>\n<p>You can customize your preferences or withdraw consent at any time via your browser settings or our cookie preference dialog.</p>',1,1,0,7,1,'Cookie Policy | {store_name}','Cookie Policy\nLast Updated: {last_updated}\n{store_name} uses cookies and related browser storage technologies to deliver a s',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'WebPage','2026-10-31 21:33:28','2026-10-31 21:33:28'),(8,'Security Policy','security-policy','<h2>Security Policy</h2>\n<p><strong>Last Updated: {last_updated}</strong></p>\n<p>{store_name} implements defense-in-depth security principles to protect our customer transactions and infrastructure:</p>\n<ul>\n    <li><strong>Strict Access Controls:</strong> Payment screenshots and customer delivery addresses are stored in private storage partitions accessible solely by authenticated staff members through cryptographically signed temporary routes.</li>\n    <li><strong>Encrypted Transport:</strong> All platform traffic is encrypted end-to-end via TLS/HTTPS with modern cipher suites and HSTS enforcement.</li>\n    <li><strong>Server-Side Pricing Integrity:</strong> All product prices, delivery zone calculations, floor fees, and deposit requirements are strictly computed on the server during order creation to prevent client-side parameter tampering.</li>\n    <li><strong>Rate Limiting & Abuse Prevention:</strong> Authentication, search queries, and payment proof submissions are throttled to avert brute-force and credential stuffing attacks.</li>\n</ul>',1,1,0,8,1,'Security Policy | {store_name}','Security Policy\nLast Updated: {last_updated}\n{store_name} implements defense-in-depth security principles to protect our cus',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'WebPage','2026-10-31 21:33:28','2026-10-31 21:33:28'),(9,'Disclaimer','disclaimer','<h2>Website & Product Disclaimer</h2>\n<p><strong>Last Updated: {last_updated}</strong></p>\n<p>The information and product imagery presented on {store_name} are provided in good faith. Product carton packaging graphics and minor manufacturer reformulations may vary slightly from displayed stock photos without altering the core specification or carton unit count.</p>',1,1,0,9,1,'Disclaimer | {store_name}','Website & Product Disclaimer\nLast Updated: {last_updated}\nThe information and product imagery presented on {store_name} are ',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'WebPage','2026-10-31 21:33:28','2026-10-31 21:33:28'),(10,'About Us','about','<h2>About {store_name}</h2>\n<p>{store_name} is a premier bulk grocery and household supplies distributor headquartered in Puchong, Selangor. Established to bridge the gap between heavy FMCG manufacturers and end-users, we empower small eateries, sundry grocers, offices, and large households to acquire everyday carton essentials at direct mart volume pricing.</p>\n\n<h3>Our Mission</h3>\n<p>To make mart bulk grocery shopping effortless, transparent, and economical through streamlined digital ordering and reliable local fleet delivery.</p>\n\n<h3>Visit or Contact Our Distribution Center</h3>\n<p>\n    <strong>Address:</strong> {store_address}<br>\n    <strong>Telephone:</strong> {store_phone}<br>\n    <strong>Email:</strong> {store_email}<br>\n    <strong>Business Hours:</strong> Monday through Saturday, 8:00 AM – 7:00 PM\n</p>',1,1,0,10,1,'About Us | {store_name}','About {store_name}\n{store_name} is a premier bulk grocery and household supplies distributor headquartered in Puchong, Selangor. Established to brid',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'WebPage','2026-10-31 21:33:28','2026-10-31 21:33:28');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_foreign` (`product_id`),
  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `brand_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pack_size` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight_volume` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `purchase_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Cost / wholesale purchase price of the product',
  `sale_price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int NOT NULL DEFAULT '0',
  `low_stock_threshold` int NOT NULL DEFAULT '10' COMMENT 'Alert threshold quantity for low stock warning',
  `short_description` text COLLATE utf8mb4_unicode_ci,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `featured_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_new` tinyint(1) NOT NULL DEFAULT '0',
  `is_promo` tinyint(1) NOT NULL DEFAULT '0',
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci,
  `canonical_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `robots` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'index, follow',
  `og_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_description` text COLLATE utf8mb4_unicode_ci,
  `og_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_card` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'summary_large_image',
  `focus_keyword` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `schema_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Product',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_brand_id_foreign` (`brand_id`),
  CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,2,'FANTA ORANGE 12*1.25L','fanta-orange-12125l','BEV-FAN-01','12 x 1.25L Bottle Pack','12 bottles per pack (15 Litres total)','15.5 kg',38.50,26.95,34.90,120,10,'FANTA ORANGE 12*1.25L. 12 bottles per pack (15 Litres total).','<p><strong>Unit Details:</strong> 12 x 1.25L Bottle pack</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-1.png',1,1,0,1,'FANTA ORANGE 12*1.25L Online Mart Price','Buy FANTA ORANGE 12*1.25L with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:26','2026-11-04 12:12:40'),(2,1,1,'COCA-COLA ORIGINAL 24*320ML','coca-cola-original-24320ml','BEV-COC-02','24 x 320ML Can Pack','24 aluminium cans per pack','8.2 kg',42.00,29.40,39.50,200,10,'COCA-COLA ORIGINAL 24*320ML. 24 aluminium cans per pack.','<p><strong>Unit Details:</strong> 24 x 320ML Can pack</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-2.png',1,1,0,1,'COCA-COLA ORIGINAL 24*320ML Online Mart Price','Buy COCA-COLA ORIGINAL 24*320ML with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(3,1,3,'100 PLUS ISOTONIC 24*325ML','100-plus-isotonic-24325ml','BEV-100-03','24 x 325ML Can Pack','24 aluminium cans per pack','8.5 kg',41.50,29.05,NULL,178,10,'100 PLUS ISOTONIC 24*325ML. 24 aluminium cans per pack.','<p><strong>Unit Details:</strong> 24 x 325ML Can pack</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-3.png',1,1,0,0,'100 PLUS ISOTONIC 24*325ML Online Mart Price','Buy 100 PLUS ISOTONIC 24*325ML with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 16:50:07'),(4,1,1,'SPRITE LEMON-LIME 12*1.25L','sprite-lemon-lime-12125l','BEV-SPR-04','12 x 1.25L Bottle Pack','12 PET bottles per pack','15.5 kg',38.50,26.95,35.00,90,10,'SPRITE LEMON-LIME 12*1.25L. 12 PET bottles per pack.','<p><strong>Unit Details:</strong> 12 x 1.25L Bottle pack</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-4.png',1,0,1,1,'SPRITE LEMON-LIME 12*1.25L Online Mart Price','Buy SPRITE LEMON-LIME 12*1.25L with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(5,2,4,'MILO UHT CHOCOLATE MALT 24*200ML','milo-uht-chocolate-malt-24200ml','DRY-MIL-01','24 x 200ML Tetra Pak Pack','24 ready-to-drink tetra packs','5.2 kg',39.90,27.93,36.80,250,10,'MILO UHT CHOCOLATE MALT 24*200ML. 24 ready-to-drink tetra packs.','<p><strong>Unit Details:</strong> 24 x 200ML Tetra Pak pack</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-5.png',1,1,0,1,'MILO UHT CHOCOLATE MALT 24*200ML Online Mart Price','Buy MILO UHT CHOCOLATE MALT 24*200ML with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(6,2,5,'DUTCH LADY FULL CREAM UHT 24*200ML','dutch-lady-full-cream-uht-24200ml','DRY-DUT-02','24 x 200ML Tetra Pak Pack','24 packets per pack','5.1 kg',37.50,26.25,NULL,140,10,'DUTCH LADY FULL CREAM UHT 24*200ML. 24 packets per pack.','<p><strong>Unit Details:</strong> 24 x 200ML Tetra Pak pack</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-6.png',1,0,1,0,'DUTCH LADY FULL CREAM UHT 24*200ML Online Mart Price','Buy DUTCH LADY FULL CREAM UHT 24*200ML with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(7,2,5,'DUTCH LADY CHOCOLATE UHT 12*1L','dutch-lady-chocolate-uht-121l','DRY-DUT-03','12 x 1L Tetra Brik Pack','12 1-Litre packs','12.5 kg',69.90,48.93,64.90,109,10,'DUTCH LADY CHOCOLATE UHT 12*1L. 12 1-Litre packs.','<p><strong>Unit Details:</strong> 12 x 1L Tetra Brik pack</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-7.png',1,1,0,1,'DUTCH LADY CHOCOLATE UHT 12*1L Online Mart Price','Buy DUTCH LADY CHOCOLATE UHT 12*1L with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 16:52:02'),(8,2,5,'F&N SWEETENED CONDENSED MILK 48*500G','fn-sweetened-condensed-milk-48500g','DRY-FN-04','48 x 500G Tin Pack','48 tins bulk catering pack','25.0 kg',148.00,103.60,NULL,65,10,'F&N SWEETENED CONDENSED MILK 48*500G. 48 tins bulk catering pack.','<p><strong>Unit Details:</strong> 48 x 500G Tin pack</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-8.png',1,0,0,0,'F&N SWEETENED CONDENSED MILK 48*500G Online Mart Price','Buy F&N SWEETENED CONDENSED MILK 48*500G with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(9,3,6,'BURUH PURE COOKING OIL 4*5KG','buruh-pure-cooking-oil-45kg','OIL-BUR-01','4 x 5KG Bottle Pack','4 large 5kg bottles per pack','20.5 kg',118.00,82.60,109.90,150,10,'BURUH PURE COOKING OIL 4*5KG. 4 large 5kg bottles per pack.','<p><strong>Unit Details:</strong> 4 x 5KG Bottle pack</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-9.png',1,1,0,1,'BURUH PURE COOKING OIL 4*5KG Online Mart Price','Buy BURUH PURE COOKING OIL 4*5KG with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(10,3,7,'SERI MURNI COOKING OIL 4*5KG','seri-murni-cooking-oil-45kg','OIL-SER-02','4 x 5KG Bottle Pack','4 bottles per pack','20.5 kg',122.00,85.40,NULL,130,10,'SERI MURNI COOKING OIL 4*5KG. 4 bottles per pack.','<p><strong>Unit Details:</strong> 4 x 5KG Bottle pack</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-10.png',1,0,0,0,'SERI MURNI COOKING OIL 4*5KG Online Mart Price','Buy SERI MURNI COOKING OIL 4*5KG with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(11,3,6,'KNIFE BRAND BLENDED COOKING OIL 4*5KG','knife-brand-blended-cooking-oil-45kg','OIL-KNF-03','4 x 5KG Bottle Pack','4 bottles per pack (rich in Vitamin E)','20.8 kg',145.00,101.50,138.00,83,10,'KNIFE BRAND BLENDED COOKING OIL 4*5KG. 4 bottles per pack (rich in Vitamin E).','<p><strong>Unit Details:</strong> 4 x 5KG Bottle pack</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-11.png',1,1,1,1,'KNIFE BRAND BLENDED COOKING OIL 4*5KG Online Mart Price','Buy KNIFE BRAND BLENDED COOKING OIL 4*5KG with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 16:52:02'),(12,3,6,'BURUH COOKING OIL 17KG TIN','buruh-cooking-oil-17kg-tin','OIL-BUR-04','1 x 17KG Commercial Catering Tin','Commercial metal jerry tin for restaurants and caterers','17.8 kg',89.50,62.65,NULL,70,10,'BURUH COOKING OIL 17KG TIN. Commercial metal jerry tin for restaurants and caterers.','<p><strong>Unit Details:</strong> 1 x 17KG Commercial Catering Tin</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-12.png',1,0,0,0,'BURUH COOKING OIL 17KG TIN Online Mart Price','Buy BURUH COOKING OIL 17KG TIN with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(13,4,8,'MAGGI 2-MINUTE NOODLE CURRY 12*5*79G','maggi-2-minute-noodle-curry-12579g','NDL-MAG-01','12 Packs x 5 Bundles (60 Packets Pack)','60 individual noodle packets per pack','5.2 kg',54.00,37.80,49.90,190,10,'MAGGI 2-MINUTE NOODLE CURRY 12*5*79G. 60 individual noodle packets per pack.','<p><strong>Unit Details:</strong> 12 Packs x 5 Bundles (60 Packets pack)</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-13.png',1,1,0,1,'MAGGI 2-MINUTE NOODLE CURRY 12*5*79G Online Mart Price','Buy MAGGI 2-MINUTE NOODLE CURRY 12*5*79G with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(14,4,8,'MAGGI 2-MINUTE NOODLE CHICKEN 12*5*77G','maggi-2-minute-noodle-chicken-12577g','NDL-MAG-02','12 Packs x 5 Bundles (60 Packets Pack)','60 individual packets per pack','5.0 kg',54.00,37.80,NULL,140,10,'MAGGI 2-MINUTE NOODLE CHICKEN 12*5*77G. 60 individual packets per pack.','<p><strong>Unit Details:</strong> 12 Packs x 5 Bundles (60 Packets pack)</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-14.png',1,0,0,0,'MAGGI 2-MINUTE NOODLE CHICKEN 12*5*77G Online Mart Price','Buy MAGGI 2-MINUTE NOODLE CHICKEN 12*5*77G with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(15,4,8,'MAMEE CHEF LAKSA KARI 12*4*80G','mamee-chef-laksa-kari-12480g','NDL-MAM-03','12 Bundles x 4 Packets (48 Packets)','La Mian authentic curry laksa paste','4.2 kg',66.00,46.20,59.90,95,10,'MAMEE CHEF LAKSA KARI 12*4*80G. La Mian authentic curry laksa paste.','<p><strong>Unit Details:</strong> 12 Bundles x 4 Packets (48 Packets)</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-15.png',1,0,1,1,'MAMEE CHEF LAKSA KARI 12*4*80G Online Mart Price','Buy MAMEE CHEF LAKSA KARI 12*4*80G with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(16,4,8,'JASMINE SUPER SPECIAL TEMPATAN 10KG (CARTON OF 3)','jasmine-super-special-tempatan-10kg-carton-of-3','RIC-JAS-04','3 Bags x 10KG (30KG mart)','3 bags of 10kg local fragrant white rice','30.2 kg',115.00,80.50,NULL,80,10,'JASMINE SUPER SPECIAL TEMPATAN 10KG (pack OF 3). 3 bags of 10kg local fragrant white rice.','<p><strong>Unit Details:</strong> 3 Bags x 10KG (30KG mart)</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-16.png',1,1,0,0,'JASMINE SUPER SPECIAL TEMPATAN 10KG (CARTON OF 3) Online Mart Price','Buy JASMINE SUPER SPECIAL TEMPATAN 10KG (CARTON OF 3) with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(17,5,9,'AYAM BRAND SARDINES IN TOMATO SAUCE 24*425G','ayam-brand-sardines-in-tomato-sauce-24425g','CAN-AYM-01','24 x 425G Big Tin Pack','24 large round tins per mart pack','11.5 kg',195.00,136.50,182.00,71,10,'AYAM BRAND SARDINES IN TOMATO SAUCE 24*425G. 24 large round tins per mart pack.','<p><strong>Unit Details:</strong> 24 x 425G Big Tin pack</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-17.png',1,1,0,1,'AYAM BRAND SARDINES IN TOMATO SAUCE 24*425G Online Mart Price','Buy AYAM BRAND SARDINES IN TOMATO SAUCE 24*425G with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-05 12:05:01'),(18,5,9,'AYAM BRAND TUNA FLAKES IN OIL 24*150G','ayam-brand-tuna-flakes-in-oil-24150g','CAN-AYM-02','24 x 150G Easy-Open Tin Pack','24 cans wild caught skipjack tuna','4.2 kg',142.00,99.40,NULL,109,10,'AYAM BRAND TUNA FLAKES IN OIL 24*150G. 24 cans wild caught skipjack tuna.','<p><strong>Unit Details:</strong> 24 x 150G Easy-Open Tin pack</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-18.png',1,0,1,0,'AYAM BRAND TUNA FLAKES IN OIL 24*150G Online Mart Price','Buy AYAM BRAND TUNA FLAKES IN OIL 24*150G with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-05 12:05:01'),(19,5,9,'AYAM BRAND BAKED BEANS 24*425G','ayam-brand-baked-beans-24425g','CAN-AYM-03','24 x 425G Tin Pack','24 cans baked beans in savory sauce','11.6 kg',112.00,78.40,104.50,119,10,'AYAM BRAND BAKED BEANS 24*425G. 24 cans baked beans in savory sauce.','<p><strong>Unit Details:</strong> 24 x 425G Tin pack</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-19.png',1,0,0,1,'AYAM BRAND BAKED BEANS 24*425G Online Mart Price','Buy AYAM BRAND BAKED BEANS 24*425G with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 16:49:52'),(20,5,8,'MAGGI TOMATO KETCHUP 12*475G','maggi-tomato-ketchup-12475g','CND-MAG-04','12 x 475G Glass Bottle Pack','12 bottles per pack','8.0 kg',45.00,31.50,NULL,100,10,'MAGGI TOMATO KETCHUP 12*475G. 12 bottles per pack.','<p><strong>Unit Details:</strong> 12 x 475G Glass Bottle pack</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-20.png',1,0,0,0,'MAGGI TOMATO KETCHUP 12*475G Online Mart Price','Buy MAGGI TOMATO KETCHUP 12*475G with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(21,6,10,'JULIE\'S PEANUT BUTTER SANDWICH 24*360G','julies-peanut-butter-sandwich-24360g','SNK-JUL-01','24 Packets x 360G Pack','24 family packs with individual sachets','9.4 kg',135.00,94.50,124.90,115,10,'JULIE\'S PEANUT BUTTER SANDWICH 24*360G. 24 family packs with individual sachets.','<p><strong>Unit Details:</strong> 24 Packets x 360G pack</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-21.png',1,1,0,1,'JULIE\'S PEANUT BUTTER SANDWICH 24*360G Online Mart Price','Buy JULIE\'S PEANUT BUTTER SANDWICH 24*360G with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(22,6,10,'JULIE\'S CHEESE SANDWICH 24*336G','julies-cheese-sandwich-24336g','SNK-JUL-02','24 Packets x 336G Pack','24 packs of golden crispy cheese biscuits','8.9 kg',135.00,94.50,NULL,90,10,'JULIE\'S CHEESE SANDWICH 24*336G. 24 packs of golden crispy cheese biscuits.','<p><strong>Unit Details:</strong> 24 Packets x 336G pack</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-22.png',1,0,0,0,'JULIE\'S CHEESE SANDWICH 24*336G Online Mart Price','Buy JULIE\'S CHEESE SANDWICH 24*336G with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(23,6,10,'OREO VANILLA COOKIES 24*119.6G','oreo-vanilla-cookies-241196g','SNK-ORE-03','24 Rolls Pack','24 roll packs classic cream sandwich','3.5 kg',68.00,47.60,61.90,170,10,'OREO VANILLA COOKIES 24*119.6G. 24 roll packs classic cream sandwich.','<p><strong>Unit Details:</strong> 24 Rolls pack</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-23.png',1,1,1,1,'OREO VANILLA COOKIES 24*119.6G Online Mart Price','Buy OREO VANILLA COOKIES 24*119.6G with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(24,6,10,'MUNCHY\'S CRACKERS PLUS ORIGINAL 12*300G','munchys-crackers-plus-original-12300g','SNK-MUN-04','12 Packs x 300G Pack','12 packs containing crisp whole wheat crackers','4.1 kg',48.00,33.60,NULL,105,10,'MUNCHY\'S CRACKERS PLUS ORIGINAL 12*300G. 12 packs containing crisp whole wheat crackers.','<p><strong>Unit Details:</strong> 12 Packs x 300G pack</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-24.png',1,0,0,0,'MUNCHY\'S CRACKERS PLUS ORIGINAL 12*300G Online Mart Price','Buy MUNCHY\'S CRACKERS PLUS ORIGINAL 12*300G with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(25,7,11,'NESCAFE CLASSIC 3IN1 ORIGINAL 10*25*18G','nescafe-classic-3in1-original-102518g','COF-NES-01','10 Bags x 25 Sticks (250 Sticks Pack)','250 instant coffee sachets in 10 polybags','5.2 kg',119.00,83.30,110.00,160,10,'NESCAFE CLASSIC 3IN1 ORIGINAL 10*25*18G. 250 instant coffee sachets in 10 polybags.','<p><strong>Unit Details:</strong> 10 Bags x 25 Sticks (250 Sticks pack)</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-25.png',1,1,0,1,'NESCAFE CLASSIC 3IN1 ORIGINAL 10*25*18G Online Mart Price','Buy NESCAFE CLASSIC 3IN1 ORIGINAL 10*25*18G with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(26,7,11,'NESCAFE GOLD BLEND SOLUBLE 6*200G','nescafe-gold-blend-soluble-6200g','COF-NES-02','6 Glass Jars x 200G Pack','6 jars premium freeze-dried coffee','3.8 kg',165.00,115.50,NULL,75,10,'NESCAFE GOLD BLEND SOLUBLE 6*200G. 6 jars premium freeze-dried coffee.','<p><strong>Unit Details:</strong> 6 Glass Jars x 200G pack</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-26.png',1,0,1,0,'NESCAFE GOLD BLEND SOLUBLE 6*200G Online Mart Price','Buy NESCAFE GOLD BLEND SOLUBLE 6*200G with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(27,7,11,'BOH CAMERON HIGHLANDS TEA BAGS 12*100\'S','boh-cameron-highlands-tea-bags-12100s','TEA-BOH-03','12 Boxes x 100 Potbags (1,200 Bags Pack)','Bulk catering pack for mamaks and offices','3.2 kg',125.00,87.50,115.00,90,10,'BOH CAMERON HIGHLANDS TEA BAGS 12*100\'S. Bulk catering pack for mamaks and offices.','<p><strong>Unit Details:</strong> 12 Boxes x 100 Potbags (1,200 Bags pack)</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-27.png',1,1,0,1,'BOH CAMERON HIGHLANDS TEA BAGS 12*100\'S Online Mart Price','Buy BOH CAMERON HIGHLANDS TEA BAGS 12*100\'S with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(28,7,4,'MILO ACTIV-GO REFILL PACK 12*1KG','milo-activ-go-refill-pack-121kg','COF-MIL-04','12 Bags x 1KG (12KG Pack)','12 x 1kg chocolate malt powder pouches','12.5 kg',228.00,159.60,NULL,85,10,'MILO ACTIV-GO REFILL PACK 12*1KG. 12 x 1kg chocolate malt powder pouches.','<p><strong>Unit Details:</strong> 12 Bags x 1KG (12KG pack)</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-28.png',1,0,0,0,'MILO ACTIV-GO REFILL PACK 12*1KG Online Mart Price','Buy MILO ACTIV-GO REFILL PACK 12*1KG with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(29,8,12,'BREEZE LIQUID POWER CLEAN DETERGENT 4*3.6KG','breeze-liquid-power-clean-detergent-436kg','LND-BRZ-01','4 x 3.6KG Bottle Pack','4 heavy duty liquid detergent bottles','15.5 kg',95.00,66.50,86.90,130,10,'BREEZE LIQUID POWER CLEAN DETERGENT 4*3.6KG. 4 heavy duty liquid detergent bottles.','<p><strong>Unit Details:</strong> 4 x 3.6KG Bottle pack</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-29.png',1,1,0,1,'BREEZE LIQUID POWER CLEAN DETERGENT 4*3.6KG Online Mart Price','Buy BREEZE LIQUID POWER CLEAN DETERGENT 4*3.6KG with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(30,8,13,'DYNAMO POWER GEL REGULAR 4*2.6KG','dynamo-power-gel-regular-426kg','LND-DYN-02','4 x 2.6KG Bottle Pack','4 concentrated gel formula bottles','11.2 kg',88.00,61.60,NULL,120,10,'DYNAMO POWER GEL REGULAR 4*2.6KG. 4 concentrated gel formula bottles.','<p><strong>Unit Details:</strong> 4 x 2.6KG Bottle pack</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-30.png',1,0,0,0,'DYNAMO POWER GEL REGULAR 4*2.6KG Online Mart Price','Buy DYNAMO POWER GEL REGULAR 4*2.6KG with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(31,8,12,'BREEZE COLOUR CARE DETERGENT POWDER 4*4.4KG','breeze-colour-care-detergent-powder-444kg','LND-BRZ-03','4 x 4.4KG Bag Pack','4 large bags per pack (17.6kg total)','18.0 kg',92.00,64.40,84.50,109,10,'BREEZE COLOUR CARE DETERGENT POWDER 4*4.4KG. 4 large bags per pack (17.6kg total).','<p><strong>Unit Details:</strong> 4 x 4.4KG Bag pack</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-31.png',1,0,1,1,'BREEZE COLOUR CARE DETERGENT POWDER 4*4.4KG Online Mart Price','Buy BREEZE COLOUR CARE DETERGENT POWDER 4*4.4KG with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-05 12:05:01'),(32,8,12,'DOWNY FABRIC SOFTENER SUNRISE FRESH 4*3.8L','downy-fabric-softener-sunrise-fresh-438l','LND-DWN-04','4 x 3.8L Gallon Bottle Pack','Commercial bottle case with pour spout','16.0 kg',110.00,77.00,NULL,70,10,'DOWNY FABRIC SOFTENER SUNRISE FRESH 4*3.8L. Commercial bottle case with pour spout.','<p><strong>Unit Details:</strong> 4 x 3.8L Gallon Bottle pack</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-32.png',1,0,0,0,'DOWNY FABRIC SOFTENER SUNRISE FRESH 4*3.8L Online Mart Price','Buy DOWNY FABRIC SOFTENER SUNRISE FRESH 4*3.8L with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(33,9,14,'PREMIER FACIAL TISSUE 2-PLY 48*200\'S','premier-facial-tissue-2-ply-48200s','PPR-PRE-01','48 Boxes x 200 Pulls Pack','mart box pack of 48 individual tissue boxes','10.5 kg',145.00,101.50,134.00,140,10,'PREMIER FACIAL TISSUE 2-PLY 48*200\'S. mart box pack of 48 individual tissue boxes.','<p><strong>Unit Details:</strong> 48 Boxes x 200 Pulls pack</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-33.png',1,1,0,1,'PREMIER FACIAL TISSUE 2-PLY 48*200\'S Online Mart Price','Buy PREMIER FACIAL TISSUE 2-PLY 48*200\'S with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(34,9,14,'PREMIER KITCHEN TOWEL 3-PLY 12*6 ROLLS','premier-kitchen-towel-3-ply-126-rolls','PPR-PRE-02','12 Packs x 6 Rolls (72 Rolls Pack)','72 super absorbent embossed kitchen rolls','8.6 kg',118.00,82.60,NULL,95,10,'PREMIER KITCHEN TOWEL 3-PLY 12*6 ROLLS. 72 super absorbent embossed kitchen rolls.','<p><strong>Unit Details:</strong> 12 Packs x 6 Rolls (72 Rolls pack)</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-34.png',1,0,1,0,'PREMIER KITCHEN TOWEL 3-PLY 12*6 ROLLS Online Mart Price','Buy PREMIER KITCHEN TOWEL 3-PLY 12*6 ROLLS with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(35,9,14,'KLEENEX ULTRA SOFT TOILET TISSUE 4*20 ROLLS','kleenex-ultra-soft-toilet-tissue-420-rolls','PPR-KLN-03','4 Bundles x 20 Rolls (80 Rolls Pack)','80 rolls pure virgin fiber bathroom rolls','9.2 kg',128.00,89.60,119.00,115,10,'KLEENEX ULTRA SOFT TOILET TISSUE 4*20 ROLLS. 80 rolls pure virgin fiber bathroom rolls.','<p><strong>Unit Details:</strong> 4 Bundles x 20 Rolls (80 Rolls pack)</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-35.png',1,1,0,1,'KLEENEX ULTRA SOFT TOILET TISSUE 4*20 ROLLS Online Mart Price','Buy KLEENEX ULTRA SOFT TOILET TISSUE 4*20 ROLLS with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(36,9,14,'PREMIER COMPACT INTERFOLD HAND TOWEL 20*250\'S','premier-compact-interfold-hand-towel-20250s','PPR-PRE-04','20 Packs x 250 Sheets (5,000 Sheets Pack)','Commercial dispenser folded paper towel sheets','7.8 kg',98.00,68.60,NULL,80,10,'PREMIER COMPACT INTERFOLD HAND TOWEL 20*250\'S. Commercial dispenser folded paper towel sheets.','<p><strong>Unit Details:</strong> 20 Packs x 250 Sheets (5,000 Sheets pack)</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-36.png',1,0,0,0,'PREMIER COMPACT INTERFOLD HAND TOWEL 20*250\'S Online Mart Price','Buy PREMIER COMPACT INTERFOLD HAND TOWEL 20*250\'S with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(37,10,15,'DETTOL ANTISEPTIC DISINFECTANT LIQUID 12*750ML','dettol-antiseptic-disinfectant-liquid-12750ml','SAN-DET-01','12 x 750ML Bottle Pack','12 bottles medical grade antiseptic liquid','10.2 kg',248.00,173.60,229.00,60,10,'DETTOL ANTISEPTIC DISINFECTANT LIQUID 12*750ML. 12 bottles medical grade antiseptic liquid.','<p><strong>Unit Details:</strong> 12 x 750ML Bottle pack</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-37.png',1,1,0,1,'DETTOL ANTISEPTIC DISINFECTANT LIQUID 12*750ML Online Mart Price','Buy DETTOL ANTISEPTIC DISINFECTANT LIQUID 12*750ML with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(38,10,15,'CLOROX REGULAR BLEACH DISINFECTANT 6*4L','clorox-regular-bleach-disinfectant-64l','SAN-CLX-02','6 x 4L Gallon Jugs Pack','6 commercial 4-Litre bleach jugs','25.5 kg',78.00,54.60,NULL,85,10,'CLOROX REGULAR BLEACH DISINFECTANT 6*4L. 6 commercial 4-Litre bleach jugs.','<p><strong>Unit Details:</strong> 6 x 4L Gallon Jugs pack</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-38.png',1,0,0,0,'CLOROX REGULAR BLEACH DISINFECTANT 6*4L Online Mart Price','Buy CLOROX REGULAR BLEACH DISINFECTANT 6*4L with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(39,10,16,'SUNLIGHT DISHWASHING LIQUID LIME 6*4L','sunlight-dishwashing-liquid-lime-64l','SAN-SUN-03','6 x 4L Value Jug Pack','6 bottles grease stripping lime dishwash (24 Litres total)','25.0 kg',96.00,67.20,89.90,95,10,'Sunlight Dishwashing Liquid Lime combines 100x grease-cutting lime power for sparkling clean dishes.','<p><strong>Product Details:</strong> 6 x 4L Value Jug Pack (24L Total)</p>\n<p>Sunlight Dishwashing Liquid Lime formulation provides real lime juice power that cuts through tough grease and oil 100x faster. Specially formulated to leave your dishes, cookware, and kitchen utensils sparkling clean with a refreshing citrus fragrance.</p>\n<p><strong>Key Highlights:</strong></p>\n<ul>\n<li><strong>Real Lime Power:</strong> Concentrated degreasing formula cuts through burnt grease and sticky residue effortlessly.</li>\n<li><strong>Long-Lasting Suds:</strong> Rich lather washes more plates per drop, providing superior value for household and food prep use.</li>\n<li><strong>Easy Rinse & Gentle:</strong> Formulated to rinse off cleanly without soap residue and gentle on hands.</li>\n<li><strong>Guaranteed Fresh Stock:</strong> Sourced directly from Unilever certified distributors with verified long shelf-life.</li>\n</ul>','products/product-39.png',1,0,1,1,'SUNLIGHT DISHWASHING LIQUID LIME 6*4L Online Mart Price','Buy SUNLIGHT DISHWASHING LIQUID LIME 6*4L with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40'),(40,10,15,'MR MUSCLE MULTI-SURFACE FLOOR CLEANER 6*3.7L','mr-muscle-multi-surface-floor-cleaner-637l','SAN-MRM-04','6 x 3.7L Jug Pack','6 bottles antibacterial floral floor cleaner','23.0 kg',82.00,57.40,NULL,90,10,'MR MUSCLE MULTI-SURFACE FLOOR CLEANER 6*3.7L. 6 bottles antibacterial floral floor cleaner.','<p><strong>Unit Details:</strong> 6 x 3.7L Jug pack</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial mart savings on groceries and everyday items.</p><ul><li>Authentic factory sealed pack</li><li>Guaranteed long expiry date</li><li>Carefully transported by our delivery team</li></ul>','products/product-40.png',1,0,0,0,'MR MUSCLE MULTI-SURFACE FLOOR CLEANER 6*3.7L Online Mart Price','Buy MR MUSCLE MULTI-SURFACE FLOOR CLEANER 6*3.7L with everyday mart savings with door-to-door delivery.',NULL,NULL,'index, follow',NULL,NULL,NULL,'summary_large_image',NULL,'Product','2026-10-31 21:33:27','2026-11-04 12:12:40');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('9Dfq7gAZ7flO2b066LF0UbPGvfPwEek6wc3atymg',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUmhxNTdpUzlYSGtTRjMzZEJHTlBqZW1vT3ZqN3FFS3ZFbkt1dDBHUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1794059567),('eTRQwcemaClWwajicg3A4ERBjJMPRvSqH4bybXtZ',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711','YTozOntzOjY6Il90b2tlbiI7czo0MDoibWlISnN0MkRQNXVYUk95TWtlbm9aUVVJd0hVTk1DWlBwdk81Z3VxdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1794062210),('IArA6kD11CzFsogFIcd3rPVl4CgAJPPSsPtnDHFs',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiMWF3YjZkYUF2TGVIcnBhV2x1NFJPWUI5WXREbnA1MDhFeXNHeTZnSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9ub3RpZmljYXRpb25zL3BvbGw/bGFzdF9vcmRlcl9pZD0xMCI7czo1OiJyb3V0ZSI7czoyNDoiYWRtaW4ubm90aWZpY2F0aW9ucy5wb2xsIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2NDoiNmRlYTE1ZWYyY2UwZjUxMzE3ODc0ODUyMDY0ZDc2MTc4NzUwYTQ3ZDBlZDdiNDdhMzkxNjI5Zjc5YzczYzRiZSI7fQ==',1794064786),('JX11Z2bazhASKMn895i1UlCrEO3jllFcPLrWyTrV',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiN01SaHhnZmZPczI4UFZ3cjhNM0hESjZXS0pLVXJzV0h1eGRYMVE2MyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3Byb2R1Y3RzL2NyZWF0ZSI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1794062033),('r2k8wdIV39xlEQQklZ5G87xVOhViWXgo4Ti7Mag7',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiY1g1Tjh6QWhsbjJKV0tHaEYwbmowaXdEYlh4blQ5YkF4dFc0d2FaZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1794060986),('UsXm4tqrdsSDbLqxN1S5Fnhj3gjF8kgMEYvup5W3',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOHdLUDBOWnZwSDZmVldmZWp3YjBmaTVTOTczejhlbHlyTE93blJ6QyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1794059560),('vbITG8RMaErWPdO91kSlz97h8lt6JtoxQnmoOYvE',NULL,'127.0.0.1','curl/8.21.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiU3pyZnBQdUxmYnBlQnNBYzZrUXBlVGJlRGN2d1NEaldMbGlWaHpQNCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1794060817),('zm8T9IwpqdjjKozsdjpY5GvfRVQ9BTLzw82ERN9e',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQjhLcG5aWWpldFl3MTRTZWNwV2JzQmZ0SWlhRVptcFNlRzJmOUxqMCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3Byb2R1Y3RzL2NyZWF0ZSI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQzOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vcHJvZHVjdHMvY3JlYXRlIjtzOjU6InJvdXRlIjtzOjIxOiJhZG1pbi5wcm9kdWN0cy5jcmVhdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1794060791);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci,
  `group` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`),
  KEY `settings_group_index` (`group`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'site_name','N&J mini mart','general','text','2026-10-31 21:33:24','2026-11-01 17:48:30'),(2,'site_tagline','Your Friendly Neighbourhood Mini Mart','general','text','2026-10-31 21:33:24','2026-11-01 17:48:30'),(3,'store_email','orders@99bulksales.my','general','text','2026-10-31 21:33:24','2026-10-31 21:33:24'),(4,'store_phone','+60 12-889 9900','general','text','2026-10-31 21:33:24','2026-10-31 21:33:24'),(5,'store_address','34, Jalan Perkasa 3, Taman Ungku Tun Aminah, 81300 Skudai, Johor Darul Ta\'zim, Malaysia','general','text','2026-10-31 21:33:24','2026-11-07 10:20:32'),(6,'company_registration_no','202401019988 (1458923-M)','general','text','2026-10-31 21:33:24','2026-10-31 21:33:24'),(7,'working_hours','Mon - Sat: 8:00 AM - 7:00 PM (Closed on Sundays)','general','text','2026-10-31 21:33:24','2026-10-31 21:33:24'),(8,'currency_symbol','RM','delivery','text','2026-10-31 21:33:24','2026-11-01 17:59:09'),(9,'currency_code','MYR','general','text','2026-10-31 21:33:24','2026-10-31 21:33:24'),(10,'currency_decimals','2','delivery','number','2026-10-31 21:33:25','2026-11-01 17:59:09'),(11,'order_number_prefix','BS','general','text','2026-10-31 21:33:25','2026-10-31 21:33:25'),(12,'return_window_days','7','general','number','2026-10-31 21:33:25','2026-10-31 21:33:25'),(13,'order_cutoff_time','4:00 PM','general','text','2026-10-31 21:33:25','2026-10-31 21:33:25'),(14,'delivery_enabled','1','delivery','boolean','2026-10-31 21:33:25','2026-11-01 20:04:19'),(15,'store_latitude','1.52055740','delivery','number','2026-10-31 21:33:25','2026-11-01 19:56:59'),(16,'store_longitude','103.66164310','delivery','number','2026-10-31 21:33:25','2026-11-01 19:56:59'),(17,'free_delivery_radius','2','delivery','number','2026-10-31 21:33:25','2026-11-01 17:59:08'),(18,'paid_delivery_radius','1','delivery','number','2026-10-31 21:33:25','2026-11-01 17:59:08'),(19,'paid_delivery_charge_mode','fixed','delivery','select','2026-10-31 21:33:25','2026-10-31 21:33:25'),(20,'paid_delivery_charge','5','delivery','number','2026-10-31 21:33:25','2026-11-01 17:59:08'),(21,'free_floor_limit','1','delivery','number','2026-10-31 21:33:25','2026-10-31 21:33:25'),(22,'extra_floor_charge','5','delivery','number','2026-10-31 21:33:25','2026-11-01 17:59:09'),(23,'max_floor_allowed','15','delivery','number','2026-10-31 21:33:25','2026-10-31 21:33:25'),(24,'min_order_value','100.00','delivery','number','2026-10-31 21:33:25','2026-10-31 21:33:25'),(25,'require_advance_payment','1','payment','boolean','2026-10-31 21:33:25','2026-10-31 21:33:25'),(26,'advance_percentage','10','payment','number','2026-10-31 21:33:25','2026-10-31 21:33:25'),(27,'qr_code_image','images/demo-qr.png','payment','image','2026-10-31 21:33:25','2026-10-31 21:33:25'),(28,'bank_account_details','Bank: Maybank Berhad\nAccount Name: N&J Mini Mart Enterprise\nAccount No: 5148 7921 6890\nDuitNow QR ID: 202401019988','payment','text','2026-10-31 21:33:26','2026-11-04 15:06:20'),(29,'payment_instructions','1. Scan the DuitNow QR code or transfer to our Maybank account.\n2. Pay the exact Advance Deposit shown below.\n3. Take a screenshot of the completed transfer.\n4. Enter the Transaction/Reference ID and upload the screenshot to confirm your order.','payment','textarea','2026-10-31 21:33:26','2026-10-31 21:33:26'),(30,'notify_email_enabled','1','notification','boolean','2026-10-31 21:33:26','2026-11-01 19:42:24'),(31,'admin_email','ss4871836@gmail.com','notification','text','2026-10-31 21:33:26','2026-11-01 19:42:24'),(32,'admin_phone','+60128899900','notification','text','2026-10-31 21:33:26','2026-10-31 21:33:26'),(33,'notify_telegram_enabled','0','notification','boolean','2026-10-31 21:33:26','2026-11-01 19:42:24'),(34,'telegram_bot_token',NULL,'notification','text','2026-10-31 21:33:26','2026-10-31 23:22:00'),(35,'telegram_chat_id',NULL,'notification','text','2026-10-31 21:33:26','2026-10-31 23:22:00'),(36,'notify_callmebot_enabled','1','notification','boolean','2026-10-31 21:33:26','2026-11-01 19:42:24'),(37,'callmebot_phone','+923176121524','notification','text','2026-10-31 21:33:26','2026-10-31 23:22:00'),(38,'callmebot_api_key',NULL,'notification','text','2026-10-31 21:33:26','2026-10-31 23:22:00'),(39,'seo_meta_title_template','%title% | %site_name%','seo','text','2026-10-31 21:33:26','2026-10-31 21:33:26'),(40,'seo_default_title','N&J Mini Mart - Groceries & Daily Household Essentials','seo','text','2026-10-31 21:33:26','2026-10-31 21:33:26'),(41,'seo_default_description','Shop groceries, drinks, cooking staples, snacks, and household cleaning supplies at N&J Mini Mart.','seo','textarea','2026-10-31 21:33:26','2026-10-31 21:33:26'),(42,'seo_default_keywords','mini mart, groceries, food essentials, beverages, snacks, puchong grocery','seo','text','2026-10-31 21:33:26','2026-10-31 21:33:26'),(43,'seo_default_robots','index, follow','seo','text','2026-10-31 21:33:26','2026-10-31 21:33:26'),(44,'google_search_console_tag','','seo','text','2026-10-31 21:33:26','2026-10-31 21:33:26'),(45,'bing_verification_tag','','seo','text','2026-10-31 21:33:26','2026-10-31 21:33:26'),(46,'google_analytics_id','','seo','text','2026-10-31 21:33:26','2026-10-31 21:33:26'),(47,'facebook_pixel_id','','seo','text','2026-10-31 21:33:26','2026-10-31 21:33:26'),(48,'custom_header_scripts','','seo','textarea','2026-10-31 21:33:26','2026-10-31 21:33:26'),(49,'custom_footer_scripts','','seo','textarea','2026-10-31 21:33:26','2026-10-31 21:33:26'),(50,'robots_txt_content','User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /account/\nDisallow: /checkout/\n\nSitemap: http://localhost:8000/sitemap.xml','seo','textarea','2026-10-31 21:33:26','2026-10-31 21:33:26'),(51,'color_primary','#0284c7','appearance','color','2026-10-31 21:33:26','2026-10-31 21:33:26'),(52,'color_primary_hover','#0369a1','appearance','color','2026-10-31 21:33:26','2026-10-31 21:33:26'),(53,'color_secondary','#0f172a','appearance','color','2026-10-31 21:33:26','2026-10-31 21:33:26'),(54,'color_accent','#f59e0b','appearance','color','2026-10-31 21:33:26','2026-10-31 21:33:26'),(55,'color_bg','#f8fafc','appearance','color','2026-10-31 21:33:26','2026-10-31 21:33:26'),(56,'color_text','#1e293b','appearance','color','2026-10-31 21:33:26','2026-10-31 21:33:26'),(57,'color_header_bg','#ffffff','appearance','color','2026-10-31 21:33:26','2026-10-31 21:33:26'),(58,'color_footer_bg','#0f172a','appearance','color','2026-10-31 21:33:26','2026-10-31 21:33:26'),(59,'color_button_text','#ffffff','appearance','color','2026-10-31 21:33:26','2026-10-31 21:33:26'),(60,'font_family','\'Open Sans\', sans-serif','appearance','select','2026-10-31 21:33:26','2026-11-01 17:35:28'),(61,'border_radius','0.5rem','appearance','text','2026-10-31 21:33:26','2026-11-01 17:35:52'),(62,'cookie_consent_enabled','1','appearance','boolean','2026-10-31 21:33:26','2026-10-31 21:33:26'),(63,'cookie_consent_title','We value your privacy','appearance','text','2026-10-31 21:33:26','2026-10-31 21:33:26'),(64,'cookie_consent_message','We use essential cookies to keep your shopping cart and order status secure. We only load analytics if you consent.','appearance','textarea','2026-10-31 21:33:26','2026-10-31 21:33:26'),(65,'site_logo','theme/NMN2dYrNGrGFrDk8RT0IGHfXUCKxx6XJSMD1RFzB.png','appearance','text','2026-11-01 17:48:30','2026-11-01 17:52:52'),(66,'site_logo_light','images/logo-light-bg.png','appearance','text','2026-11-01 17:48:30','2026-11-01 17:48:30'),(67,'site_favicon','theme/sPrEK3dSAMg5kfV9443hBU6qEzQN7rabeoxa98Gt.png','appearance','text','2026-11-01 17:52:52','2026-11-01 17:52:52');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_phone_index` (`phone`),
  KEY `users_role_index` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'N&I Store','nabeel@store.com','+60128899900','admin',1,'2026-11-01 17:30:52','$2y$12$FGl3t1OoYb5bXQWkd.IUSeorRLoZ0OUu1ZuYlL/FuAdHHoTJDUvR.',NULL,NULL,NULL,NULL,'2026-10-31 21:33:28','2026-11-04 19:09:18'),(2,'Dispatch Staff','staff@99bulksales.my','+60123344556','staff',1,'2026-11-01 17:30:52','$2y$12$sHLaEM3E1ltr5ICGeJCrvuRrujQscFfinPU1p1ElVPEMV2XcUirqm',NULL,NULL,NULL,NULL,'2026-10-31 21:33:28','2026-11-01 17:30:52'),(3,'Nabeel Ahmad','customer@gmail.com','+60112233445','customer',1,'2026-11-01 17:30:53','$2y$12$QG.RKYVKvkCRQLsX1P/wIuL4IitMReukDnWVUg3MGI57kdbYihXnK',NULL,NULL,NULL,NULL,'2026-10-31 21:33:28','2026-11-01 17:30:53'),(4,'mart Customer','customer@example.com','+60112233445','customer',1,NULL,'$2y$12$X8SNb4Vq4.O/wiEHGhUIbuAlnkiswEESeKetda1X05MzUnviicKIS',NULL,NULL,NULL,NULL,'2026-11-01 17:30:53','2026-11-01 17:30:53'),(6,'Weldon Harber','nitzsche.oran@example.com',NULL,'customer',1,NULL,'$2y$12$2KbltzYtywAGgcrS00xcMOdaRihP93yylPozf.DtobWFLb4IR9si.',NULL,NULL,NULL,'3Hq5uE3bEx','2026-11-02 16:41:04','2026-11-02 16:41:04');
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

-- Dump completed on 2026-11-07 20:20:43
