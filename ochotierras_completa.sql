-- MySQL dump 10.13  Distrib 9.4.0, for macos15.4 (arm64)
--
-- Host: localhost    Database: ochotierras_local
-- ------------------------------------------------------
-- Server version	9.4.0

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
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel-cache-77de68daecd823babbb58edb1c8e14d7106e83bb','i:1;',1767626878),('laravel-cache-77de68daecd823babbb58edb1c8e14d7106e83bb:timer','i:1767626878;',1767626878),('laravel-cache-livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3','i:1;',1767621613),('laravel-cache-livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer','i:1767621613;',1767621613);
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
  PRIMARY KEY (`key`)
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
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_en` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Reserva Especial','reserva-especial',1,1,'2025-12-23 03:54:40','2025-12-23 03:54:40',NULL,NULL),(2,'Reserva Privada','reserva-privada',2,1,'2025-12-23 03:54:40','2025-12-23 03:54:40',NULL,NULL),(3,'Gran Reserva','gran-reserva',3,1,'2025-12-23 03:54:40','2025-12-23 03:54:40',NULL,NULL);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_type` enum('percent','fixed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
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
-- Table structure for table `hero_sections`
--

DROP TABLE IF EXISTS `hero_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hero_sections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtitle_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_primary_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_primary_text_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_primary_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_secondary_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_secondary_text_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_secondary_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  CONSTRAINT `hero_sections_chk_1` CHECK (json_valid(`images`))
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hero_sections`
--

LOCK TABLES `hero_sections` WRITE;
/*!40000 ALTER TABLE `hero_sections` DISABLE KEYS */;
INSERT INTO `hero_sections` VALUES (2,'VIÑA OCHOTIERRAS','OCHOTIERRAS VINEYARD','En el corazón del Valle del Limarí','In the heart of the Limarí Valley','Nuestra Viña','Our Vineyard','/nosotros','Tienda Online','Online Store','/tienda','[\"hero-images\\/01KD55E8BVB7F5C9MHM3SEWTYE.jpeg\"]',1,'2025-12-19 08:17:38','2025-12-23 13:39:06',0),(3,'VISÍTANOS','VISIT US','Conoce nuestra Viña','Get to know our vineyard','Reserva un Tour','Book a Tour','/turismo','Más información','More information','/turismo','[\"hero-images\\/01KD55Y1VZZ28KBC5VZ8KNPR3N.jpeg\"]',1,'2025-12-19 08:18:48','2025-12-23 13:41:41',0);
/*!40000 ALTER TABLE `hero_sections` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (1,'default','{\"uuid\":\"14d0e1c7-d36e-417a-8e7c-8edf855329fa\",\"displayName\":\"App\\\\Jobs\\\\TranslateProduct\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\TranslateProduct\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\TranslateProduct\\\":1:{s:7:\\\"product\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Product\\\";s:2:\\\"id\\\";i:13;s:9:\\\"relations\\\";a:1:{i:0;s:11:\\\"bundleItems\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}}\"},\"createdAt\":1767578703,\"delay\":null}',0,NULL,1767578703,1767578703),(2,'default','{\"uuid\":\"957026a0-a4ff-48c2-80f4-5073ef1be802\",\"displayName\":\"App\\\\Jobs\\\\TranslateProduct\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\TranslateProduct\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\TranslateProduct\\\":1:{s:7:\\\"product\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Product\\\";s:2:\\\"id\\\";i:14;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}}\"},\"createdAt\":1767579893,\"delay\":null}',0,NULL,1767579893,1767579893),(3,'default','{\"uuid\":\"2d55bd5e-4e29-4030-8257-1df111d28da1\",\"displayName\":\"App\\\\Jobs\\\\TranslateProduct\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\TranslateProduct\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\TranslateProduct\\\":1:{s:7:\\\"product\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Product\\\";s:2:\\\"id\\\";i:14;s:9:\\\"relations\\\";a:1:{i:0;s:17:\\\"bundleConnections\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}}\"},\"createdAt\":1767580603,\"delay\":null}',0,NULL,1767580603,1767580603),(4,'default','{\"uuid\":\"9216559c-7d26-4acc-9531-512efdf436e8\",\"displayName\":\"App\\\\Jobs\\\\TranslateProduct\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\TranslateProduct\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\TranslateProduct\\\":1:{s:7:\\\"product\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Product\\\";s:2:\\\"id\\\";i:15;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}}\"},\"createdAt\":1767581126,\"delay\":null}',0,NULL,1767581126,1767581126),(5,'default','{\"uuid\":\"fda2f127-1486-4157-af11-fb543350e645\",\"displayName\":\"App\\\\Jobs\\\\TranslateProduct\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\TranslateProduct\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\TranslateProduct\\\":1:{s:7:\\\"product\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Product\\\";s:2:\\\"id\\\";i:16;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}}\"},\"createdAt\":1767581315,\"delay\":null}',0,NULL,1767581315,1767581315),(6,'default','{\"uuid\":\"1f04833a-2695-4688-99b4-2e5011c53dda\",\"displayName\":\"App\\\\Jobs\\\\TranslateProduct\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\TranslateProduct\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\TranslateProduct\\\":1:{s:7:\\\"product\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Product\\\";s:2:\\\"id\\\";i:17;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}}\"},\"createdAt\":1767582163,\"delay\":null}',0,NULL,1767582163,1767582163);
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
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_12_16_155040_create_products_table',1),(5,'2025_12_18_150056_create_hero_sections_table',1),(6,'2025_12_18_160113_add_details_to_products_table',1),(7,'2025_12_18_161518_add_collection_description_to_products_table',1),(8,'2025_12_18_180846_add_stock_to_products_table',1),(9,'2025_12_18_180847_create_orders_table',1),(10,'2025_12_18_180848_create_order_items_table',1),(11,'2025_12_19_150325_create_coupons_table',2),(12,'2025_12_19_150846_add_tracking_to_orders_table',2),(13,'2025_12_19_161510_create_shipping_zones_table',2),(14,'2025_12_19_195714_add_sort_order_to_hero_sections_table',2),(15,'2025_12_19_214000_create_categories_table',3),(16,'2025_12_19_215006_add_category_and_technical_sheet_to_products_table',4),(17,'2025_12_19_215006_create_categories_table',5),(18,'2025_12_19_230000_make_accent_color_nullable_in_products_table',5),(19,'2025_12_22_162424_add_technical_details_to_products_table',5),(20,'2025_12_22_164948_update_products_for_winery',5),(21,'2025_12_22_170140_add_gallery_to_products_table',5),(22,'2025_12_22_200000_ensure_orders_table_structure',6),(23,'2025_12_23_060000_ensure_product_analysis_columns',7),(24,'2025_12_23_070000_ensure_hero_english_fields',8),(25,'2025_12_23_080000_ensure_seo_collection_fields',9),(26,'2025_12_23_035152_add_english_fields_to_tables',10),(27,'2025_12_23_090000_ensure_inventory_columns',10),(28,'2026_01_05_021639_create_product_bundles_table',11),(29,'2026_01_05_135304_create_store_banners_table',11),(31,'2026_01_05_145008_add_highlighted_text_to_store_banners_table',12);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
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
  `quantity` int NOT NULL,
  `price` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
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
  `user_id` bigint unsigned DEFAULT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_shipping` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `courier_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tracking_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total` int NOT NULL,
  `site_transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marketing_opt_in` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `shipping_address` text COLLATE utf8mb4_unicode_ci,
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_site_transaction_id_unique` (`site_transaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
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
-- Table structure for table `product_bundles`
--

DROP TABLE IF EXISTS `product_bundles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_bundles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bundle_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_bundles_bundle_id_foreign` (`bundle_id`),
  KEY `product_bundles_product_id_foreign` (`product_id`),
  CONSTRAINT `product_bundles_bundle_id_foreign` FOREIGN KEY (`bundle_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_bundles_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_bundles`
--

LOCK TABLES `product_bundles` WRITE;
/*!40000 ALTER TABLE `product_bundles` DISABLE KEYS */;
INSERT INTO `product_bundles` VALUES (1,14,9,3,'2026-01-05 05:36:43','2026-01-05 05:36:43'),(2,14,12,3,'2026-01-05 05:36:43','2026-01-05 05:36:43'),(3,15,10,3,'2026-01-05 05:45:26','2026-01-05 05:45:26'),(4,15,9,3,'2026-01-05 05:45:26','2026-01-05 05:45:26'),(5,16,11,3,'2026-01-05 05:48:35','2026-01-05 05:48:35'),(6,16,12,3,'2026-01-05 05:48:35','2026-01-05 05:48:35'),(7,17,7,1,'2026-01-05 06:02:43','2026-01-05 06:02:43'),(8,17,8,1,'2026-01-05 06:02:43','2026-01-05 06:02:43'),(9,17,9,1,'2026-01-05 06:02:43','2026-01-05 06:02:43'),(10,17,10,1,'2026-01-05 06:02:43','2026-01-05 06:02:43'),(11,17,11,1,'2026-01-05 06:02:43','2026-01-05 06:02:43'),(12,17,12,1,'2026-01-05 06:02:43','2026-01-05 06:02:43');
/*!40000 ALTER TABLE `product_bundles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `featured_description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gallery` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `accent_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `technical_sheet` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vintage_year` int DEFAULT NULL,
  `strain` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `units_per_box` int NOT NULL DEFAULT '6',
  `is_pack` tinyint(1) NOT NULL DEFAULT '0',
  `technical_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `harvest_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `harvest_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `origin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vineyard_location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `presentation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `closure_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `varietal_composition` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aging_potential` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wood_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alcohol` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `residual_sugar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_ph` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `volatile_acidity` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_acidity` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tasting_notes` text COLLATE utf8mb4_unicode_ci,
  `awards` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_en` text COLLATE utf8mb4_unicode_ci,
  `short_description_en` text COLLATE utf8mb4_unicode_ci,
  `tasting_notes_en` text COLLATE utf8mb4_unicode_ci,
  `pairing_en` text COLLATE utf8mb4_unicode_ci,
  `service_temp_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  CONSTRAINT `products_chk_1` CHECK (json_valid(`gallery`)),
  CONSTRAINT `products_chk_2` CHECK (json_valid(`technical_details`)),
  CONSTRAINT `products_chk_3` CHECK (json_valid(`awards`))
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (7,'Reserva Especial Cabernet Sauvignon','Cosecha 2019','Tinto','reserva-especial-cabernet-sauvignon-2019','De un brillante y profundo color rubí con una intensa concentración. Contiene un aroma con notas dulces de cassis, frambuesa fresca y menta, con una leve insinuación de nota herbal. Sobresalen notas de coco, vainilla y aromas florales de hierba silvestre y mineralidad.','De un brillante y profundo color rubí con una intensa concentración. ',66000.00,100,'products/01KD5N8HRYRQBP6VZFT7K6HEVW.webp','[]',NULL,NULL,NULL,1,1,'2025-12-23 03:54:40','2025-12-31 03:23:10',1,NULL,2019,'100% Cabernet Sauvignon',6,0,'{\"aging_potential\":\"10 a 12 meses en Roble Franc\\u00e9s\",\"analysis\":{\"Alcohol\":\"13,5\\u00b0\",\"Az\\u00facar Residual\":\"3,4 g\\/l\",\"pH\":\"3,79\",\"Acidez Vol\\u00e1til\":\"0,83 g\\/l\",\"Acidez Total\":\"3,71 g\\/l\"},\"tasting_notes\":\"De un brillante y profundo color rub\\u00ed con una intensa concentraci\\u00f3n. Contiene un aroma con notas dulces de cassis, frambuesa fresca y menta, con una leve insinuaci\\u00f3n de nota herbal. Sobresalen notas de coco, vainilla y aromas florales de hierba silvestre y mineralidad. En el paladar destacan las frutas negras maduras, cassis y frambuesa, con granito mineral. Pimienta blanca y crema de chocolate, con notas florales persistentes, bien integradas con taninos maduros y elegantes con fina vainilla.\",\"awards\":[{\"award\":\"95 PUNTOS: Gu\\u00eda Palacio de Hierro, M\\u00e9xico 2013 (Reserva Single Vineyard Cab. Sauv 2011).\"},{\"award\":\"90 PUNTOS: Gu\\u00eda Pe\\u00f1\\u00edn 2012.\"},{\"award\":\"SILVER: Monde Selection Bruxelles 2008.\"},{\"award\":\"COMMENDED: International Wine Challenge, London 2008.\"},{\"award\":\"90 PUNTOS: Gu\\u00eda Descorchados 2010.\"}]}','2019','Manual','Valle del Limarí, Viñedos propios','Viñedo Parcela 44',NULL,'Corcho natural','100% Cabernet Sauvignon','10 a 12 meses en Roble Francés',NULL,'13,5°','3,4 g/l','3,79','0,83 g/l','3,71 g/l','De un brillante y profundo color rubí con una intensa concentración. Contiene un aroma con notas dulces de cassis, frambuesa fresca y menta, con una leve insinuación de nota herbal. Sobresalen notas de coco, vainilla y aromas florales de hierba silvestre y mineralidad. En el paladar destacan las frutas negras maduras, cassis y frambuesa, con granito mineral. Pimienta blanca y crema de chocolate, con notas florales persistentes, bien integradas con taninos maduros y elegantes con fina vainilla.','[\"95 PUNTOS: Gu\\u00eda Palacio de Hierro, M\\u00e9xico 2013 (Reserva Single Vineyard Cab. Sauv 2011).\",\"90 PUNTOS: Gu\\u00eda Pe\\u00f1\\u00edn 2012.\",\"SILVER: Monde Selection Bruxelles 2008.\",\"COMMENDED: International Wine Challenge, London 2008.\",\"90 PUNTOS: Gu\\u00eda Descorchados 2010.\"]',NULL,'Special Reserve Cabernet Sauvignon','Of a bright and deep ruby ​​color with an intense concentration. It contains an aroma with sweet notes of cassis, fresh raspberry and mint, with a slight hint of a herbal note. Notes of coconut, vanilla and floral aromas of wild grass and minerality stand out.',NULL,'Of a bright and deep ruby ​​color with an intense concentration. It contains an aroma with sweet notes of cassis, fresh raspberry and mint, with a slight hint of a herbal note. Notes of coconut, vanilla and floral aromas of wild grass and minerality stand out. On the palate, ripe black fruits, cassis and raspberry stand out, with mineral granite. White pepper and chocolate cream, with persistent floral notes, well integrated with ripe and elegant tannins with fine vanilla.',NULL,NULL),(8,'Reserva Especial Ensamblaje','Cabernet S. / Syrah - Cosecha 2022','Tinto','reserva-especial-ensamblaje','De color brillante y profundo color rubí con una intensa concentración. Con aroma a notas dulces de cassis, frambuesa fresca y menta. Sobresalen notas de coco, vainilla y aromas florales de hierba silvestre y mineralidad.','De color brillante y profundo color rubí con una intensa concentración. ',66000.00,100,'products/01KD5NKPKTBW3EZ087N6C5FRSG.webp','[]','#f2dfb3',NULL,NULL,1,1,'2025-12-23 03:54:40','2025-12-23 18:15:58',1,NULL,2022,'Cabernet Sauvignon / Syrah',6,0,'{\"aging_potential\":null,\"analysis\":{\"alcohol\":\"12,5\\u00b0\",\"residual_sugar\":\"1,98 g\\/l\",\"total_ph\":\"3,71\",\"volatile_acidity\":\"0,76 g\\/l\",\"total_acidity\":\"4,28 g\\/l\"},\"tasting_notes\":\"De color brillante y profundo color rub\\u00ed con una intensa concentraci\\u00f3n. Con aroma a notas dulces de cassis, frambuesa fresca y menta, con una leve insinuaci\\u00f3n de nota herbal. Sobresalen notas de coco, vainilla y aromas florales de hierba silvestre y mineralidad. En el paladar destacan las frutas negras maduras, cassis y frambuesa, con granito mineral. Pimienta blanca y crema de chocolate, bien integradas con taninos maduros y elegantes.\",\"awards\":[]}',NULL,NULL,'Valle del Limarí, Viñedos propios',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Special Reserve Assembly','Bright and deep ruby ​​color with intense concentration. With aroma of sweet notes of cassis, fresh raspberry and mint. Notes of coconut, vanilla and floral aromas of wild grass and minerality stand out.',NULL,NULL,NULL,NULL),(9,'Reserva Privada Syrah','Cosecha 2020','Tinto','reserva-privada-syrah-2020','Es un vino equilibrado, con notas a frutos rojos, taninos suaves y un color rojo intenso. Se describe como \"Una experiencia que no puedes dejar de vivir\".','Es un vino equilibrado, con notas a frutos rojos, taninos suaves y un color rojo intenso.',78000.00,80,'products/01KD5NNCY7372BW7YBPX69YRH2.webp','[]',NULL,NULL,NULL,1,1,'2025-12-23 03:54:40','2025-12-23 18:16:45',2,NULL,2020,'100% Syrah',6,0,'{\"aging_potential\":null,\"analysis\":{\"alcohol\":\"15,0\\u00b0\",\"residual_sugar\":\"2,75 g\\/l\",\"total_ph\":\"3,71\",\"volatile_acidity\":\"0,62 g\\/l\",\"total_acidity\":\"3,08 g\\/l\"},\"tasting_notes\":\"Resultado de la cosecha de uvas de un peque\\u00f1o vi\\u00f1edo caracterizado por suelo franco arenoso y alta mineralidad. Es un vino equilibrado, con notas a frutos rojos, taninos suaves y un color rojo intenso. Se describe como \\\"Una experiencia que no puedes dejar de vivir\\\".\",\"awards\":[{\"award\":\"92 PUNTOS: Gu\\u00eda Pe\\u00f1\\u00edn 2013\"},{\"award\":\"96 PUNTOS: Gu\\u00eda Palacio de Hierro, 2013 (M\\u00e9xico)\"},{\"award\":\"90 PUNTOS: Gu\\u00eda Pe\\u00f1\\u00edn 2012\"},{\"award\":\"88 PUNTOS: Stephen Tanzer, May 2011\"},{\"award\":\"SILVER MEDAL: Concours Mondial Bruxelles-Chile 2007\"},{\"award\":\"SILVER: Grand Catador Hyatt 2008\"}]}',NULL,NULL,'Valle del Limarí, Viñedos propios',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Syrah Private Reserve','It is a balanced wine, with notes of red fruits, soft tannins and an intense red color. It is described as \"An experience you cannot miss.\"',NULL,NULL,NULL,NULL),(10,'Reserva Privada Carmenere','Cosecha 2021','Tinto','reserva-privada-carmenere-2021','De color violeta intenso y profundo. En nariz resaltan notas a frutas rojas maduras y un final especiado. En boca muestra notas frescas y dulces con sabor a cereza y tabaco.','En boca muestra notas frescas y dulces con sabor a cereza y tabaco.',78000.00,80,'products/01KD5NPJW84M65FHQE5FBME323.webp','[]',NULL,NULL,NULL,1,1,'2025-12-23 03:54:40','2025-12-23 18:17:13',2,NULL,2021,'100% Carmenere',6,0,'{\"aging_potential\":null,\"analysis\":{\"alcohol\":\"14,5\\u00b0\",\"residual_sugar\":\"1,81 g\\/l\",\"total_ph\":\"3,79\",\"volatile_acidity\":\"0,79 g\\/l\",\"total_acidity\":\"3,76 g\\/l\"},\"tasting_notes\":\"De color violeta intenso y profundo. En nariz resaltan notas a frutas rojas maduras y un final especiado. En boca muestra notas frescas y dulces con sabor a cereza y tabaco, con un buen equilibrio entre la fruta y el roble franc\\u00e9s, y una buena acidez. Presenta taninos suaves y redondos con un buen volumen en boca. Ideal para carnes como cordero y guisos. Servir a 16\\u00b0C.\",\"awards\":[]}',NULL,NULL,'Valle del Limarí, Viñedos propios',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Carmenere Private Reserve','Intense and deep violet color. On the nose, notes of ripe red fruits and a spicy finish stand out. In the mouth it shows fresh and sweet notes with cherry and tobacco flavors.',NULL,NULL,NULL,NULL),(11,'Gran Reserva Ensamblaje','24 Barricas - Cosecha 2020','Tinto','gran-reserva-ensamblaje-24-barricas-2020','De color rubí profundo, con aroma intenso, complejo y elegante. Prevalecen notas ahumadas con acentuados aromas de mora, cassis, chocolate negro.','De color rubí profundo, con aroma intenso, complejo y elegante. ',126000.00,50,'products/01KD5NQD9CHF09A27GP49NPK6Q.webp','[]',NULL,NULL,NULL,1,1,'2025-12-23 03:54:40','2025-12-31 03:23:20',3,NULL,2020,'74% Cabernet Sauvignon / 25% Carmenere',6,0,'{\"aging_potential\":null,\"analysis\":{\"alcohol\":\"15,0\\u00b0\",\"residual_sugar\":\"2,03 g\\/l\",\"total_ph\":\"3,77\",\"volatile_acidity\":\"1,07 g\\/l\",\"total_acidity\":\"4,34 g\\/l\"},\"tasting_notes\":\"De color rub\\u00ed profundo, con aroma intenso, complejo y elegante. Prevalecen notas ahumadas con acentuados aromas de mora, cassis, chocolate negro, pimienta negra, clavo de olor y cardamomo. En el paladar se siente un excelente cuerpo, de taninos amables. Ofrece gran cantidad de sabores de frutos negros, concentrado y jugoso. Termina con buena amplitud y persistencia, dejando un retrogusto de frutos negros. Gran final y persistencia.\",\"awards\":[{\"award\":\"90 PUNTOS: Gu\\u00eda Pe\\u00f1\\u00edn 2013 (Gran Reserva Ensamblaje 2010)\"},{\"award\":\"92 PUNTOS: Gu\\u00eda Pe\\u00f1\\u00edn 2013\"}]}',NULL,NULL,'Valle del Limarí, Viñedos propios',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Great Reserve Assembly','Deep ruby ​​in color, with an intense, complex and elegant aroma. Smoked notes prevail with accentuated aromas of blackberry, cassis, and dark chocolate.',NULL,NULL,NULL,NULL),(12,'Gran Reserva Syrah','10 Barricas - Cosecha 2022','Tinto','gran-reserva-syrah-10-barricas-2022','De color rojo oscuro y un aroma complejo, con varias capas aromáticas. Intensamente perfumado a confitura de cerezas negras, rosas secas, incienso y regaliz.','De color rojo oscuro y un aroma complejo, con varias capas aromáticas. ',126000.00,50,'products/01KD5NRHTG305GXTTHN8NP4AJV.webp','[]',NULL,NULL,NULL,1,1,'2025-12-23 03:54:40','2025-12-23 18:18:17',3,NULL,2022,'100% Syrah',6,0,'{\"aging_potential\":null,\"analysis\":{\"alcohol\":\"15,5\\u00b0\",\"residual_sugar\":\"2,55 g\\/l\",\"total_ph\":\"3,72\",\"volatile_acidity\":\"0,64 g\\/l\",\"total_acidity\":\"3,38 g\\/l\"},\"tasting_notes\":\"De color rojo oscuro y un aroma complejo, con varias capas arom\\u00e1ticas. Intensamente perfumado a confitura de cerezas negras, rosas secas, incienso y regaliz, con notas de humo y un ligero toque de aceite de oliva. En el paladar se siente profundamente concentrado y aterciopelado, de excelente cuerpo, con sabores dulces de mora, grosella, higos secos y pimienta negra, enmarcados por suaves taninos. Final suave, con una impresionante claridad y persistencia.\",\"awards\":[{\"award\":\"98 PUNTOS: Gu\\u00eda Palacio de Hierro 2013, M\\u00e9xico\"},{\"award\":\"92 PUNTOS: Gu\\u00eda Pe\\u00f1\\u00edn 2013\"},{\"award\":\"91 PUNTOS: Stephen Tanzer\\u2019s\"}]}',NULL,NULL,'Valle del Limarí, Viñedos propios',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Gran Reserva Syrah','Dark red in color and a complex aroma, with several aromatic layers. Intensely scented with black cherry jam, dried roses, incense and licorice.',NULL,NULL,NULL,NULL),(14,'Pack Ochotierras Syrah',NULL,NULL,'pack-ochotierras-syrah','Caja Especial con una selección de nuestros mejores vinos. Este pack Incluye:\n\n3 Gran Reserva Syrah\n3 Reserva Privada Syrah',NULL,102000.00,100,NULL,'[]',NULL,NULL,NULL,1,0,'2026-01-05 05:24:53','2026-01-05 05:36:43',NULL,NULL,NULL,NULL,6,1,'{\"aging_potential\":null,\"analysis\":[],\"tasting_notes\":null,\"awards\":[]}',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(15,'Pack Reserva Privada',NULL,NULL,'pack-reserva-privada','Caja Especial con una selección de nuestros mejores vinos. Este pack Incluye:\n\n3 Reserva Especial Carmenere\n3 Reserva Privada Syrah',NULL,78000.00,100,NULL,'[]',NULL,NULL,NULL,1,0,'2026-01-05 05:45:26','2026-01-05 05:45:26',NULL,NULL,NULL,NULL,6,1,'{\"aging_potential\":null,\"analysis\":[],\"tasting_notes\":null,\"awards\":[]}',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(16,'Pack Gran Reserva',NULL,NULL,'pack-gran-reserva','Caja Especial con una selección de nuestros mejores vinos. Este pack Incluye:\n\n3 Un 24 barricas ensamblaje\n3 Un 10 Barricas Syrah',NULL,126000.00,100,NULL,'[]',NULL,NULL,NULL,1,0,'2026-01-05 05:48:35','2026-01-05 05:48:35',NULL,NULL,NULL,NULL,6,1,'{\"aging_potential\":null,\"analysis\":[],\"tasting_notes\":null,\"awards\":[]}',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(17,'Pack Ochotierras',NULL,NULL,'pack-ochotierras','Caja Especial con una selección de nuestros mejores vinos. Este pack Incluye:\n\n1 Gran Reserva Syrah\n1 Gran Reserva Ensamblaje\n1 Reserva Privada Syrah\n1 Reserva Especial Cabernet S.\n1 Reserva Especial Ensamblaje\n1 Reserva Privada Carmenere',NULL,90000.00,0,NULL,'[]',NULL,NULL,NULL,1,0,'2026-01-05 06:02:43','2026-01-05 06:02:43',NULL,NULL,NULL,NULL,6,1,'{\"aging_potential\":null,\"analysis\":[],\"tasting_notes\":null,\"awards\":[]}',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
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
INSERT INTO `sessions` VALUES ('9RpgI3J1OfhU3xquqX1mcH1p0jDO279WANsbOtpD',3,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','YTo3OntzOjY6Il90b2tlbiI7czo0MDoiR0lNQ3N3ODV3SGhKNXllSVVsSThnclBjakUwUXdsNWJJNXhDTkZqayI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQ4OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vc3RvcmUtYmFubmVycy8xL2VkaXQiO3M6NToicm91dGUiO3M6NDM6ImZpbGFtZW50LmFkbWluLnJlc291cmNlcy5zdG9yZS1iYW5uZXJzLmVkaXQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkV0h4RUh4akNKbDJNMjVyeC5obDBFdW5ZQU81d05oMnBhZGdUMXJNNjhyeGFJbmRDYUE0NTIiO3M6ODoiZmlsYW1lbnQiO2E6MDp7fX0=',1767627574),('tAWN4JUDFYZSDRs1t1AxtwSONLwLNsUUaGAVjprv',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoieTNNYUplQjlFWElSUjY0YjBtSkhmdWZUOE8zOWhkcTI5R3dMUnJYQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1767621550);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipping_zones`
--

DROP TABLE IF EXISTS `shipping_zones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipping_zones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `regions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `price` int NOT NULL DEFAULT '0',
  `is_free_shipping` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `shipping_zones_chk_1` CHECK (json_valid(`regions`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipping_zones`
--

LOCK TABLES `shipping_zones` WRITE;
/*!40000 ALTER TABLE `shipping_zones` DISABLE KEYS */;
/*!40000 ALTER TABLE `shipping_zones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `store_banners`
--

DROP TABLE IF EXISTS `store_banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `store_banners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pre_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `highlighted_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cta_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cta_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `store_banners`
--

LOCK TABLES `store_banners` WRITE;
/*!40000 ALTER TABLE `store_banners` DISABLE KEYS */;
INSERT INTO `store_banners` VALUES (1,'Desde el Limarí para Chile','Bienvenidos a nuestra Tienda','Tienda','Nuestros mejores vinos disponibles para todas las regiones del país.','Ver Productos','/tienda#packs','store-banners/01KE7C5SZ9RCV6THM39TYYBYAG.jpeg',NULL,1,0,'2026-01-05 17:03:25','2026-01-05 18:39:26'),(2,'Nuestros mejores Vinos','Nuevo Pack Ochotierras','Ochotierras','Una selección de nuestros mejores vinos','Comprar','/tienda/pack/pack-ochotierras','store-banners/01KE7CA7PMNM3M028D8F5KGS69.jpeg',NULL,1,0,'2026-01-05 18:27:21','2026-01-05 18:27:21');
/*!40000 ALTER TABLE `store_banners` ENABLE KEYS */;
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
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Rodrigo Olivares','info@ochotierras.cl',NULL,'$2y$12$NlBpUOyZ8CgCPyv2C7naquUtw/w5FOaSCboi/ZPFrellZvp2y7MPW',NULL,'2025-12-19 06:04:28','2025-12-19 06:04:28'),(2,'Test User','test@example.com','2025-12-23 03:54:40','$2y$12$HdudPNMeuMfhyTl6Q0/U2Oo1A3M.zYF3MYxcYMheuhemK68mDpIEO','PMuv1z5C1h1By5VfqfQG9z15GWRBuMRqXQm40nKnPt1yqBNP5gUipyY14Q2Y','2025-12-23 03:54:40','2025-12-23 04:13:04'),(3,'Ronald Cuellar','contacto@ochotierras.cl',NULL,'$2y$12$WHxEHxjCJl2M25rx.hl0EunYAO5wNh2padgT1rM68rxaIndCaA452',NULL,'2025-12-23 13:18:49','2025-12-23 13:18:49');
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

-- Dump completed on 2026-01-05 13:12:04
