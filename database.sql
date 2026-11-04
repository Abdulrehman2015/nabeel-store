-- Database Export for InfinityFree Deployment
-- Generated: 2026-11-01 23:55:20
SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `activity_logs`;
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `activity_logs` VALUES
(1, 1, 'order.created', 'App\\Models\\Order', 1, '{\"grand_total\": 340.9, \"order_number\": \"BS-2026-000101\", \"advance_amount\": 34.09}', '2407:aa80:126:b0f0:9cd5:3294:fb71:39bb', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-01 04:20:06', '2026-11-01 04:20:06'),
(2, 1, 'settings.notifications_updated', NULL, NULL, NULL, '2407:aa80:126:b0f0:9cd5:3294:fb71:39bb', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-01 04:22:00', '2026-11-01 04:22:00'),
(3, 1, 'settings.appearance_updated', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-01 22:35:22', '2026-11-01 22:35:22'),
(4, 1, 'settings.appearance_updated', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-01 22:35:28', '2026-11-01 22:35:28'),
(5, 1, 'settings.appearance_updated', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-01 22:35:37', '2026-11-01 22:35:37'),
(6, 1, 'settings.appearance_updated', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-01 22:35:52', '2026-11-01 22:35:52'),
(7, 1, 'settings.appearance_updated', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-01 22:52:52', '2026-11-01 22:52:52'),
(8, 1, 'settings.delivery_updated', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '2026-11-01 22:59:09', '2026-11-01 22:59:09');

DROP TABLE IF EXISTS `banners`;
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

INSERT INTO `banners` VALUES
(1, 'Direct Wholesale Groceries in Bulk Cartons', 'Save up to 35% on genuine carton drinks, dairy, cooking oil, rice & cleaning supplies.', '⚡ DIRECT BULK DISCOUNTS', '/category/beverages-soft-drinks', 'Shop Bulk Drinks', 'banners/banner-1.jpg', NULL, 1, 1, '2026-11-01 02:33:27', '2026-11-01 03:34:41'),
(2, 'Commercial Cooking Oil & Food Essentials', '5kg 4-pack cartons and 17kg commercial catering tins with prompt delivery.', '🔥 RESTAURANTS & BUSINESSES', '/category/cooking-oil-essentials', 'View Cooking Oil', 'banners/banner-2.jpg', NULL, 2, 1, '2026-11-01 02:33:27', '2026-11-01 03:34:41'),
(3, 'Free Delivery Within 5 Kilometers', 'Direct doorstep delivery up to 1st floor at zero extra charge for local deliveries.', '🚚 LOCAL BULK DELIVERY', '/checkout', 'Check Delivery Zone', 'banners/banner-3.jpg', NULL, 3, 1, '2026-11-01 02:33:27', '2026-11-01 03:34:41');

DROP TABLE IF EXISTS `benefits`;
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

INSERT INTO `benefits` VALUES
(1, 'Factory Carton Wholesale Pricing', 'Unbeatable bulk carton savings direct from primary manufacturers and distributors without retail markups.', 'tag', 1, 1, '2026-11-01 02:33:27', '2026-11-01 02:33:27'),
(2, 'Free Local Delivery Radius', 'Enjoy free delivery within our 5km radius and nominal rates for adjoining zones calculated automatically.', 'truck', 2, 1, '2026-11-01 02:33:27', '2026-11-01 02:33:27'),
(3, 'Transparent Floor Charges', 'Clear, upfront floor handling rates. Free delivery up to 1st floor with minimal fee for upper levels.', 'building', 3, 1, '2026-11-01 02:33:27', '2026-11-01 02:33:27'),
(4, 'Secure DuitNow QR Advance', 'Pay only 10% advance deposit via instant QR to secure carton stock. Pay the remaining balance upon delivery.', 'shield-check', 4, 1, '2026-11-01 02:33:27', '2026-11-01 02:33:27');

DROP TABLE IF EXISTS `brands`;
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `brands` VALUES
(1, 'Coca-Cola', 'coca-cola', NULL, 1, '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(2, 'Fanta', 'fanta', NULL, 1, '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(3, '100 Plus', '100-plus', NULL, 1, '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(4, 'Milo', 'milo', NULL, 1, '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(5, 'Dutch Lady', 'dutch-lady', NULL, 1, '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(6, 'Buruh', 'buruh', NULL, 1, '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(7, 'Seri Murni', 'seri-murni', NULL, 1, '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(8, 'Maggi', 'maggi', NULL, 1, '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(9, 'Ayam Brand', 'ayam-brand', NULL, 1, '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(10, 'Julie\'s', 'julies', NULL, 1, '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(11, 'Nescafe', 'nescafe', NULL, 1, '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(12, 'Breeze', 'breeze', NULL, 1, '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(13, 'Dynamo', 'dynamo', NULL, 1, '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(14, 'Premier', 'premier', NULL, 1, '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(15, 'Dettol', 'dettol', NULL, 1, '2026-11-01 02:33:26', '2026-11-01 02:33:26');

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `cache` VALUES
('bulk-mart-cache-2b654a40db07c3215cf45bce2a81f7e5fd1ef3ea', 'i:2;', 1793506708),
('bulk-mart-cache-2b654a40db07c3215cf45bce2a81f7e5fd1ef3ea:timer', 'i:1793506708;', 1793506708),
('bulk-mart-cache-356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', 1793506865),
('bulk-mart-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1793506865;', 1793506865),
('bulk-mart-cache-5c785c036466adea360111aa28563bfd556b5fba', 'i:2;', 1793572598),
('bulk-mart-cache-5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1793572598;', 1793572598),
('bulk-mart-cache-app_settings_cache', 'a:67:{s:9:\"site_name\";s:13:\"N&J mini mart\";s:12:\"site_tagline\";s:41:\"Direct Wholesale Groceries & Case Cartons\";s:11:\"store_email\";s:21:\"orders@99bulksales.my\";s:11:\"store_phone\";s:15:\"+60 12-889 9900\";s:13:\"store_address\";s:82:\"Lot 45, Jalan Industri 3/1, Kawasan Perindustrian Puchong, 47100 Puchong, Selangor\";s:23:\"company_registration_no\";s:24:\"202401019988 (1458923-M)\";s:13:\"working_hours\";s:48:\"Mon - Sat: 8:00 AM - 7:00 PM (Closed on Sundays)\";s:15:\"currency_symbol\";s:2:\"RM\";s:13:\"currency_code\";s:3:\"MYR\";s:17:\"currency_decimals\";s:1:\"2\";s:19:\"order_number_prefix\";s:2:\"BS\";s:18:\"return_window_days\";s:1:\"7\";s:17:\"order_cutoff_time\";s:7:\"4:00 PM\";s:16:\"delivery_enabled\";s:1:\"1\";s:14:\"store_latitude\";s:6:\"3.0245\";s:15:\"store_longitude\";s:8:\"101.6166\";s:20:\"free_delivery_radius\";s:1:\"2\";s:20:\"paid_delivery_radius\";s:1:\"1\";s:25:\"paid_delivery_charge_mode\";s:5:\"fixed\";s:20:\"paid_delivery_charge\";s:1:\"5\";s:16:\"free_floor_limit\";s:1:\"1\";s:18:\"extra_floor_charge\";s:1:\"5\";s:17:\"max_floor_allowed\";s:2:\"15\";s:15:\"min_order_value\";s:6:\"100.00\";s:23:\"require_advance_payment\";s:1:\"1\";s:18:\"advance_percentage\";s:2:\"10\";s:13:\"qr_code_image\";s:18:\"images/demo-qr.png\";s:20:\"bank_account_details\";s:111:\"Bank: Maybank Berhad
Account Name: 99 Bulk Sales Sdn Bhd
Account No: 5148 7921 6890
DuitNow QR ID: 202401019988\";s:20:\"payment_instructions\";s:244:\"1. Scan the DuitNow QR code or transfer to our Maybank account.
2. Pay the exact Advance Deposit shown below.
3. Take a screenshot of the completed transfer.
4. Enter the Transaction/Reference ID and upload the screenshot to confirm your order.\";s:20:\"notify_email_enabled\";s:1:\"1\";s:11:\"admin_email\";s:20:\"admin@99bulksales.my\";s:11:\"admin_phone\";s:12:\"+60128899900\";s:23:\"notify_telegram_enabled\";s:1:\"0\";s:18:\"telegram_bot_token\";N;s:16:\"telegram_chat_id\";N;s:24:\"notify_callmebot_enabled\";s:1:\"1\";s:15:\"callmebot_phone\";s:13:\"+923176121524\";s:17:\"callmebot_api_key\";N;s:23:\"seo_meta_title_template\";s:21:\"%title% | %site_name%\";s:17:\"seo_default_title\";s:48:\"Wholesale Carton Bulk Sales & Household Supplies\";s:23:\"seo_default_description\";s:140:\"Shop groceries, drinks, canned food, cooking oil, detergent and paper goods in wholesale cartons with fast delivery direct to your doorstep.\";s:20:\"seo_default_keywords\";s:127:\"bulk groceries, wholesale carton drinks, fanta 12x1.25L, 100plus bulk, milo carton, cooking oil bulk, wholesale grocery puchong\";s:18:\"seo_default_robots\";s:13:\"index, follow\";s:25:\"google_search_console_tag\";s:0:\"\";s:21:\"bing_verification_tag\";s:0:\"\";s:19:\"google_analytics_id\";s:0:\"\";s:17:\"facebook_pixel_id\";s:0:\"\";s:21:\"custom_header_scripts\";s:0:\"\";s:21:\"custom_footer_scripts\";s:0:\"\";s:18:\"robots_txt_content\";s:125:\"User-agent: *
Allow: /
Disallow: /admin/
Disallow: /account/
Disallow: /checkout/

Sitemap: http://localhost:8000/sitemap.xml\";s:13:\"color_primary\";s:7:\"#0284c7\";s:19:\"color_primary_hover\";s:7:\"#0369a1\";s:15:\"color_secondary\";s:7:\"#0f172a\";s:12:\"color_accent\";s:7:\"#f59e0b\";s:8:\"color_bg\";s:7:\"#f8fafc\";s:10:\"color_text\";s:7:\"#1e293b\";s:15:\"color_header_bg\";s:7:\"#ffffff\";s:15:\"color_footer_bg\";s:7:\"#0f172a\";s:17:\"color_button_text\";s:7:\"#ffffff\";s:11:\"font_family\";s:23:\"\'Open Sans\', sans-serif\";s:13:\"border_radius\";s:6:\"0.5rem\";s:22:\"cookie_consent_enabled\";s:1:\"1\";s:20:\"cookie_consent_title\";s:21:\"We value your privacy\";s:22:\"cookie_consent_message\";s:115:\"We use essential cookies to keep your shopping cart and order status secure. We only load analytics if you consent.\";s:9:\"site_logo\";s:50:\"theme/NMN2dYrNGrGFrDk8RT0IGHfXUCKxx6XJSMD1RFzB.png\";s:15:\"site_logo_light\";s:24:\"images/logo-light-bg.png\";s:12:\"site_favicon\";s:50:\"theme/sPrEK3dSAMg5kfV9443hBU6qEzQN7rabeoxa98Gt.png\";}', 2108933949);

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `categories`;
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` VALUES
(1, NULL, 'Beverages & Soft Drinks', 'beverages-soft-drinks', 'Cartons of carbonated soft drinks, isotonic beverages, mineral water, and fruit juices.', 'categories/cat-1.png', 'drink', 1, 1, 'Beverages & Soft Drinks Wholesale Cartons | 99 Bulk Sales', 'Cartons of carbonated soft drinks, isotonic beverages, mineral water, and fruit juices. Best wholesale prices in cartons with prompt delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'CollectionPage', '2026-11-01 02:33:26', '2026-11-01 03:34:43'),
(2, NULL, 'Milk & Dairy Drinks', 'milk-dairy-drinks', 'UHT milk cartons, condensed milk, evaporated creamer, and malt chocolate drinks in bulk.', 'categories/cat-2.png', 'milk', 2, 1, 'Milk & Dairy Drinks Wholesale Cartons | 99 Bulk Sales', 'UHT milk cartons, condensed milk, evaporated creamer, and malt chocolate drinks in bulk. Best wholesale prices in cartons with prompt delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'CollectionPage', '2026-11-01 02:33:26', '2026-11-01 03:34:43'),
(3, NULL, 'Cooking Oil & Essentials', 'cooking-oil-essentials', 'Commercial 5kg/17kg cooking oil tins and cartons, pure ghee, and blended oils.', 'categories/cat-3.png', 'oil', 3, 1, 'Cooking Oil & Essentials Wholesale Cartons | 99 Bulk Sales', 'Commercial 5kg/17kg cooking oil tins and cartons, pure ghee, and blended oils. Best wholesale prices in cartons with prompt delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'CollectionPage', '2026-11-01 02:33:26', '2026-11-01 03:34:43'),
(4, NULL, 'Rice, Noodles & Grains', 'rice-noodles-grains', 'Bulk cartons of instant noodles, vermicelli, pasta, fragrant rice, and basmati.', 'categories/cat-4.png', 'noodle', 4, 1, 'Rice, Noodles & Grains Wholesale Cartons | 99 Bulk Sales', 'Bulk cartons of instant noodles, vermicelli, pasta, fragrant rice, and basmati. Best wholesale prices in cartons with prompt delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'CollectionPage', '2026-11-01 02:33:26', '2026-11-01 03:34:43'),
(5, NULL, 'Canned Food & Condiments', 'canned-food-condiments', 'Cases of canned sardines, tuna, baked beans, tomato paste, soya sauce, and chilli sauces.', 'categories/cat-5.png', 'can', 5, 1, 'Canned Food & Condiments Wholesale Cartons | 99 Bulk Sales', 'Cases of canned sardines, tuna, baked beans, tomato paste, soya sauce, and chilli sauces. Best wholesale prices in cartons with prompt delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'CollectionPage', '2026-11-01 02:33:26', '2026-11-01 03:34:43'),
(6, NULL, 'Biscuits, Snacks & Crackers', 'biscuits-snacks-crackers', 'Wholesale cartons of sandwich biscuits, potato chips, wafers, and snack packets.', 'categories/cat-6.png', 'snack', 6, 1, 'Biscuits, Snacks & Crackers Wholesale Cartons | 99 Bulk Sales', 'Wholesale cartons of sandwich biscuits, potato chips, wafers, and snack packets. Best wholesale prices in cartons with prompt delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'CollectionPage', '2026-11-01 02:33:26', '2026-11-01 03:34:43'),
(7, NULL, 'Coffee, Tea & Cocoa', 'coffee-tea-cocoa', 'Bulk 3-in-1 coffee cartons, premium tea bags, and hot chocolate mixes.', 'categories/cat-7.png', 'coffee', 7, 1, 'Coffee, Tea & Cocoa Wholesale Cartons | 99 Bulk Sales', 'Bulk 3-in-1 coffee cartons, premium tea bags, and hot chocolate mixes. Best wholesale prices in cartons with prompt delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'CollectionPage', '2026-11-01 02:33:26', '2026-11-01 03:34:43'),
(8, NULL, 'Laundry & Fabric Care', 'laundry-fabric-care', 'Liquid and powder detergent cartons, fabric softeners, and stain removers.', 'categories/cat-8.png', 'laundry', 8, 1, 'Laundry & Fabric Care Wholesale Cartons | 99 Bulk Sales', 'Liquid and powder detergent cartons, fabric softeners, and stain removers. Best wholesale prices in cartons with prompt delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'CollectionPage', '2026-11-01 02:33:26', '2026-11-01 03:34:43'),
(9, NULL, 'Household Paper & Tissues', 'household-paper-tissues', 'Cartons of facial tissues, kitchen towel rolls, and bulk toilet tissue rolls.', 'categories/cat-9.png', 'paper', 9, 1, 'Household Paper & Tissues Wholesale Cartons | 99 Bulk Sales', 'Cartons of facial tissues, kitchen towel rolls, and bulk toilet tissue rolls. Best wholesale prices in cartons with prompt delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'CollectionPage', '2026-11-01 02:33:26', '2026-11-01 03:34:43'),
(10, NULL, 'Sanitation & Disinfectants', 'sanitation-disinfectants', 'Commercial floor cleaners, bleach cases, dishwashing paste, and hand wash gallons.', 'categories/cat-10.png', 'cleaning', 10, 1, 'Sanitation & Disinfectants Wholesale Cartons | 99 Bulk Sales', 'Commercial floor cleaners, bleach cases, dishwashing paste, and hand wash gallons. Best wholesale prices in cartons with prompt delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'CollectionPage', '2026-11-01 02:33:26', '2026-11-01 03:34:43');

DROP TABLE IF EXISTS `contact_messages`;
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

DROP TABLE IF EXISTS `customer_addresses`;
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

INSERT INTO `customer_addresses` VALUES
(1, 3, 'Restaurant HQ', 'Nabeel Ahmad', '+60112233445', 'No. 18, Jalan BK 5A/2, Bandar Kinrara', 'Puchong, Selangor', 1, 'Near Kinrara Golf Club', '3.0380000', '101.6420000', 1, '2026-11-01 02:33:28', '2026-11-01 02:33:28');

DROP TABLE IF EXISTS `data_requests`;
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

DROP TABLE IF EXISTS `failed_jobs`;
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

DROP TABLE IF EXISTS `faqs`;
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

INSERT INTO `faqs` VALUES
(1, 'Delivery', 'How does the free delivery radius work?', 'We offer completely Free Delivery for addresses located within 5 km straight-line distance of our warehouse. For addresses between 5 km and 7 km, a nominal delivery charge is applied. Beyond 7 km, checkout is restricted.', 1, 1, '2026-11-01 02:33:27', '2026-11-01 02:33:27'),
(2, 'Delivery', 'How are floor charges calculated?', 'Delivery to Ground floor (Floor 0) and 1st floor (Floor 1) is completely FREE. For higher floors without a service lift or with stair delivery, a standard extra charge of RM 10.00 is charged per additional floor above Floor 1 (e.g. Floor 3 = 2 extra floors x RM 10 = RM 20).', 2, 1, '2026-11-01 02:33:27', '2026-11-01 02:33:27'),
(3, 'Payment', 'Why is an advance payment required?', 'Because bulk cartons are heavy, high-value goods reserved specifically for your order, we require a small 10% advance deposit via DuitNow QR. This secures your inventory allocation and delivery slot. The remaining 90% balance is paid on delivery.', 3, 1, '2026-11-01 02:33:27', '2026-11-01 02:33:27'),
(4, 'Payment', 'How do I submit payment proof?', 'During checkout, you will see our DuitNow QR code and the exact advance deposit amount. Scan and complete the transfer in your banking app, take a screenshot, and input the Transaction/Reference ID and upload the image before placing the order.', 4, 1, '2026-11-01 02:33:27', '2026-11-01 02:33:27'),
(5, 'Ordering', 'Can I order as a guest without creating an account?', 'Yes! You can check out as a guest. All you need is your name, phone number, and delivery address. You can track your order at any time using your Order Number and phone number.', 5, 1, '2026-11-01 02:33:27', '2026-11-01 02:33:27'),
(6, 'Ordering', 'What is the minimum order value?', 'The minimum order value for bulk sales is RM 100.00 per order to ensure carton logistics viability.', 6, 1, '2026-11-01 02:33:27', '2026-11-01 02:33:27');

DROP TABLE IF EXISTS `home_sections`;
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

INSERT INTO `home_sections` VALUES
(1, 'categories', 'Shop Wholesale by Category', 'Select a category to browse full carton listings and wholesale prices', 1, 1, NULL, '2026-11-01 02:33:27', '2026-11-01 02:33:27'),
(2, 'promo', 'Hot Promotions & Carton Deals', 'Special discounted prices on high-demand wholesale supplies', 2, 1, NULL, '2026-11-01 02:33:27', '2026-11-01 02:33:27'),
(3, 'new_arrivals', 'New Bulk Arrivals', 'Latest fresh stock and commercial items added to our warehouse', 3, 1, NULL, '2026-11-01 02:33:27', '2026-11-01 02:33:27'),
(4, 'featured', 'Featured Wholesale Staples', 'Everyday essential carton cartons trusted by local businesses and homes', 4, 1, NULL, '2026-11-01 02:33:27', '2026-11-01 02:33:27'),
(5, 'how_it_works', 'How Ordering Works', 'Straightforward, safe bulk delivery right to your door in 4 simple steps', 5, 1, NULL, '2026-11-01 02:33:27', '2026-11-01 02:33:27'),
(6, 'benefits', 'Why Choose 99 Bulk Sales?', 'Built for wholesale volume, reliable service, and transparent pricing', 6, 1, NULL, '2026-11-01 02:33:27', '2026-11-01 02:33:27'),
(7, 'faqs', 'Frequently Asked Questions', 'Everything you need to know about delivery, floor charges, and payment proof', 7, 1, NULL, '2026-11-01 02:33:27', '2026-11-01 02:33:27');

DROP TABLE IF EXISTS `how_it_works_steps`;
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

INSERT INTO `how_it_works_steps` VALUES
(1, 1, 'Browse Cartons & Add to Cart', 'Explore wholesale cartons of drinks, dairy, staples, and supplies. Add items to your cart with no complicated registration.', 'cart', 1, 1, '2026-11-01 02:33:27', '2026-11-01 02:33:27'),
(2, 2, 'Pin Delivery Location & Floor', 'Select your exact delivery address on the interactive map. Enter your floor number to see live free or paid delivery rates.', 'map-pin', 2, 1, '2026-11-01 02:33:27', '2026-11-01 02:33:27'),
(3, 3, 'Scan QR & Upload Advance Proof', 'Scan the DuitNow QR to pay the small advance deposit (default 10%), then upload your payment screenshot and reference ID.', 'qrcode', 3, 1, '2026-11-01 02:33:27', '2026-11-01 02:33:27'),
(4, 4, 'Quick Verification & Delivery', 'Our dispatch team verifies your proof immediately, prepares your carton goods, and delivers right to your floor with balance on delivery.', 'truck', 4, 1, '2026-11-01 02:33:27', '2026-11-01 02:33:27');

DROP TABLE IF EXISTS `job_batches`;
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

DROP TABLE IF EXISTS `jobs`;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_01_000010_create_settings_table', 1),
(5, '2026_01_01_000020_create_categories_table', 1),
(6, '2026_01_01_000030_create_brands_table', 1),
(7, '2026_01_01_000040_create_products_table', 1),
(8, '2026_01_01_000050_create_product_images_table', 1),
(9, '2026_01_01_000060_create_banners_and_cms_tables', 1),
(10, '2026_01_01_000070_create_customer_addresses_table', 1),
(11, '2026_01_01_000080_create_orders_and_order_items_tables', 1),
(12, '2026_01_01_000090_create_audit_and_notification_logs_tables', 1),
(13, '2026_11_01_041225_create_otp_codes_table', 2);

DROP TABLE IF EXISTS `notification_logs`;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `notification_logs` VALUES
(1, 1, 'email', 'admin@99bulksales.my', 'Order confirmation email sent to customer.', 'success', NULL, '2026-11-01 04:20:06', '2026-11-01 04:20:06'),
(2, 1, 'email', 'admin@99bulksales.my', 'Order alert email sent to admin.', 'success', NULL, '2026-11-01 04:20:06', '2026-11-01 04:20:06');

DROP TABLE IF EXISTS `order_histories`;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `order_histories` VALUES
(1, 1, NULL, 'pending_payment_verification', 'Order placed with payment proof (Ref: tesetes). Awaiting admin verification.', 1, '2026-11-01 04:20:06', '2026-11-01 04:20:06');

DROP TABLE IF EXISTS `order_items`;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `order_items` VALUES
(1, 1, 11, 'KNIFE BRAND BLENDED COOKING OIL 4*5KG', 'OIL-KNF-03', '4 x 5KG Bottle Carton', '138.00', 2, '276.00', 'products/product-11.png', '2026-11-01 04:20:06', '2026-11-01 04:20:06'),
(2, 1, 7, 'DUTCH LADY CHOCOLATE UHT 12*1L', 'DRY-DUT-03', '12 x 1L Tetra Brik Carton', '64.90', 1, '64.90', 'products/product-7.png', '2026-11-01 04:20:06', '2026-11-01 04:20:06');

DROP TABLE IF EXISTS `orders`;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `orders` VALUES
(1, 'BS-2026-000101', 1, 'Store Administrator', '+60128899900', 'admin@99bulksales.my', 'tts', 'Puchong, Selangor', 0, NULL, NULL, '3.0245000', '101.6166000', '0.00', 'free', '340.90', '0.00', '0.00', '340.90', '10.00', '34.09', '306.81', 'qr_advance', 'tesetes', 'payment_proofs/proof_20261101_042006_tsAHlr3pI7ZH9ABl.jpeg', 'pending_verification', NULL, NULL, NULL, 'pending_payment_verification', NULL, '{\"ip\": \"2407:aa80:126:b0f0:9cd5:3294:fb71:39bb\", \"timestamp\": \"2026-11-01T04:20:06+00:00\", \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36\", \"terms_accepted\": true}', '2407:aa80:126:b0f0:9cd5:3294:fb71:39bb', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', '052b3e23-0358-4dc3-8b23-685cf9a2c53c', '2026-11-01 04:20:06', '2026-11-01 04:20:06');

DROP TABLE IF EXISTS `otp_codes`;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `otp_codes` VALUES
(1, 4, 'customer@example.com', '$2y$12$7vOouOT8ii7TFS1jXfI5F.vqpUJwqLXF3w.dbPPumOmuQWzDl.Jfy', 'email_verification', 0, 5, '2026-11-01 22:45:55', NULL, '2026-11-01 22:35:56', '2026-11-01 22:35:56');

DROP TABLE IF EXISTS `page_versions`;
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

INSERT INTO `page_versions` VALUES
(1, 1, 1, 'Privacy Policy', '<h2>Privacy Policy for {store_name}</h2>
<p><strong>Last Updated: {last_updated}</strong></p>
<p>{store_name} (Company Registration No: {company_registration_no}) is committed to safeguarding the personal data of our customers and visitors in compliance with applicable Personal Data Protection laws.</p>

<h3>1. Data We Collect</h3>
<p>To process wholesale bulk orders and arrange doorstep carton logistics, we collect:</p>
<ul>
    <li><strong>Identity & Contact Information:</strong> Full name, phone number, and email address.</li>
    <li><strong>Delivery Information:</strong> Physical delivery address, city, floor level, landmarks, and geographic coordinates selected on our interactive map.</li>
    <li><strong>Payment Verification Data:</strong> Bank transaction reference IDs and payment confirmation screenshot receipts uploaded during checkout.</li>
    <li><strong>Device & Session Data:</strong> IP address, browser type, and cookie preferences.</li>
</ul>

<h3>2. Purpose of Collection</h3>
<p>We process your data strictly to verify advance payments, fulfill and dispatch carton deliveries to your floor, communicate order status updates, prevent fraudulent submissions, and meet statutory accounting regulations.</p>

<h3>3. Data Storage & Protection</h3>
<p>Payment receipt screenshots are maintained on private, non-public storage directories accessible solely by authorised administrators via cryptographically signed temporary tokens. Personal data is never sold, traded, or shared with third-party advertising brokers.</p>

<h3>4. Your Rights & Data Requests</h3>
<p>You possess the right to access your personal data, request corrections, export your purchase history, or request account deletion via our <a href=\"/data-request\">Data Request Page</a>.</p>

<h3>5. Contact Us</h3>
<p>For inquiries regarding this policy, contact our Data Privacy Officer at <a href=\"mailto:{store_email}\">{store_email}</a> or phone {store_phone}.</p>', NULL, 'Initial seeded template', '2026-11-01 02:33:27', '2026-11-01 02:33:27'),
(2, 2, 1, 'Terms & Conditions', '<h2>Terms and Conditions</h2>
<p><strong>Effective Date: {last_updated}</strong></p>
<p>Welcome to {store_name}. By accessing our bulk ordering platform or placing an order, you agree to comply with and be bound by the following Terms and Conditions.</p>

<h3>1. Wholesale Carton Supply</h3>
<p>Products on {store_name} are sold in manufacturer carton or case packaging as indicated in each product description (e.g., \"12*1.25L\", \"24*320ML\"). Individual unit splitting is not supported unless explicitly noted.</p>

<h3>2. Minimum Order Value</h3>
<p>The minimum order requirement across our store is {min_order_value}. Orders below this value cannot be processed through checkout.</p>

<h3>3. Order Placement & Advance Deposit</h3>
<p>Orders are officially placed only upon submission of valid advance payment proof (representing {advance_percentage} of the total order value). Submitting a falsified or duplicated payment receipt constitutes breach of terms and may lead to cancellation and blacklisting.</p>

<h3>4. Price & Availability</h3>
<p>While we make every endeavor to maintain accurate live inventory and wholesale pricing, we reserve the right to correct manifest clerical errors and notify you prior to dispatch.</p>

<h3>5. Governing Law</h3>
<p>These terms shall be governed by and construed in accordance with the laws of Malaysia, and parties submit to the exclusive jurisdiction of the competent courts.</p>', NULL, 'Initial seeded template', '2026-11-01 02:33:27', '2026-11-01 02:33:27'),
(3, 3, 1, 'Refund & Return Policy', '<h2>Refund and Return Policy</h2>
<p><strong>Last Updated: {last_updated}</strong></p>
<p>At {store_name}, customer satisfaction with our wholesale goods is our highest priority.</p>

<h3>1. Inspection Upon Delivery</h3>
<p>Because bulk goods are delivered via dedicated ground delivery, customers or their authorized representatives must inspect outer carton seals upon receipt before settling the final cash or transfer balance.</p>

<h3>2. Return Window</h3>
<p>Claims for missing items, damaged packaging during transit, or expired stock must be reported within <strong>{return_window_days}</strong> of delivery with clear photographic evidence.</p>

<h3>3. Non-Returnable Goods</h3>
<p>Perishable food items, opened cartons where individual units have been consumed or removed, and items damaged due to inappropriate storage after delivery cannot be returned.</p>

<h3>4. Refund Method</h3>
<p>Approved refunds for verified defective or non-delivered carton goods are processed via direct bank transfer or instant DuitNow back to the originating account within 3 to 5 business days.</p>', NULL, 'Initial seeded template', '2026-11-01 02:33:27', '2026-11-01 02:33:27'),
(4, 4, 1, 'Shipping & Delivery Policy', '<h2>Shipping and Delivery Policy</h2>
<p><strong>Last Updated: {last_updated}</strong></p>
<p>{store_name} operates specialized logistics vehicles suited for transporting heavy wholesale food and grocery cartons.</p>

<h3>1. Delivery Zones & Distance Calculations</h3>
<p>Delivery distance is calculated dynamically using straight-line geographical coordinates from our central fulfillment hub at {store_address}:</p>
<ul>
    <li><strong>Free Delivery Zone:</strong> Deliveries within <strong>{free_delivery_radius}</strong> receive 100% Free Shipping.</li>
    <li><strong>Paid Delivery Zone:</strong> Deliveries from <strong>{free_delivery_radius} up to {paid_delivery_radius}</strong> additional radius are serviced at nominal delivery rates calculated at checkout.</li>
    <li><strong>Beyond Serviceable Area:</strong> Addresses beyond our maximum serviceable radius cannot be accommodated via standard delivery.</li>
</ul>

<h3>2. Floor Level Policy & Charges</h3>
<p>Due to the heavy physical weight of wholesale cartons, floor level rules apply:</p>
<ul>
    <li><strong>Up to {free_floor_limit}:</strong> Completely <strong>FREE delivery</strong>.</li>
    <li><strong>Above {free_floor_limit}:</strong> Surcharge of <strong>{extra_floor_charge}</strong> per extra floor level.</li>
</ul>

<h3>3. Order Cut-Off Time</h3>
<p>Orders confirmed and payment-verified before <strong>{order_cutoff_time}</strong> are scheduled for next-day dispatch. Orders received after this cut-off time are batched for the subsequent dispatch cycle.</p>', NULL, 'Initial seeded template', '2026-11-01 02:33:27', '2026-11-01 02:33:27'),
(5, 5, 1, 'Payment Policy', '<h2>Payment Policy</h2>
<p><strong>Last Updated: {last_updated}</strong></p>
<p>To provide high wholesale value and eliminate third-party gateway fee markups, {store_name} utilizes a streamlined QR advance payment model.</p>

<h3>1. Advance Deposit Requirement</h3>
<p>Customers are required to transfer an advance deposit of <strong>{advance_percentage}</strong> of the grand total at the time of checkout. This deposit confirms your booking and reserves heavy warehouse inventory.</p>

<h3>2. How to Pay Advance</h3>
<ol>
    <li>Scan the displayed DuitNow QR code or initiate an instant bank transfer using your mobile banking application.</li>
    <li>Input the exact advance figure stated on your order summary.</li>
    <li>Save or screenshot the completed transaction confirmation.</li>
    <li>Submit the screenshot and unique Reference ID into the checkout portal.</li>
</ol>

<h3>3. Balance Payment on Delivery</h3>
<p>The remaining balance is payable upon delivery directly to our delivery team via Cash, DuitNow QR on delivery, or instant transfer upon receipt of cartons.</p>

<h3>4. Payment Rejection</h3>
<p>If a submitted payment screenshot is unreadable, invalid, or shows a mismatched transaction amount, our administrators will flag the order as <em>Payment Rejected</em> with a written reason. You may re-submit proof or contact customer care at {store_phone}.</p>', NULL, 'Initial seeded template', '2026-11-01 02:33:27', '2026-11-01 02:33:27'),
(6, 6, 1, 'Cancellation Policy', '<h2>Cancellation Policy</h2>
<p><strong>Last Updated: {last_updated}</strong></p>
<p>We understand plans change. Here is our policy regarding order cancellations:</p>

<h3>1. Cancellation Before Verification</h3>
<p>If your order is in <em>Pending Payment Verification</em> status and you wish to cancel, you may contact our customer support team immediately to cancel with full advance refund.</p>

<h3>2. Cancellation After Dispatch</h3>
<p>Once an order has been marked as <em>Out for Delivery</em>, the delivery run cannot be halted in transit. Cancellations at the doorstep may be subject to a logistics handling fee to cover vehicle dispatch and driver costs.</p>', NULL, 'Initial seeded template', '2026-11-01 02:33:28', '2026-11-01 02:33:28'),
(7, 7, 1, 'Cookie Policy', '<h2>Cookie Policy</h2>
<p><strong>Last Updated: {last_updated}</strong></p>
<p>{store_name} uses cookies and related browser storage technologies to deliver a secure, responsive browsing experience.</p>

<h3>1. Essential Cookies</h3>
<p>These cookies are technically indispensable for operating the website, preserving items in your shopping cart across pages, and protecting against CSRF attacks. They do not store personally identifiable promotional profiles.</p>

<h3>2. Analytics & Performance Cookies</h3>
<p>Non-essential analytics cookies (such as Google Analytics or visitor statistics) are loaded <strong>only after you grant explicit consent</strong> via our Cookie Consent banner.</p>

<h3>3. Managing Cookies</h3>
<p>You can customize your preferences or withdraw consent at any time via your browser settings or our cookie preference dialog.</p>', NULL, 'Initial seeded template', '2026-11-01 02:33:28', '2026-11-01 02:33:28'),
(8, 8, 1, 'Security Policy', '<h2>Security Policy</h2>
<p><strong>Last Updated: {last_updated}</strong></p>
<p>{store_name} implements defense-in-depth security principles to protect our customer transactions and infrastructure:</p>
<ul>
    <li><strong>Strict Access Controls:</strong> Payment screenshots and customer delivery addresses are stored in private storage partitions accessible solely by authenticated staff members through cryptographically signed temporary routes.</li>
    <li><strong>Encrypted Transport:</strong> All platform traffic is encrypted end-to-end via TLS/HTTPS with modern cipher suites and HSTS enforcement.</li>
    <li><strong>Server-Side Pricing Integrity:</strong> All product prices, delivery zone calculations, floor fees, and deposit requirements are strictly computed on the server during order creation to prevent client-side parameter tampering.</li>
    <li><strong>Rate Limiting & Abuse Prevention:</strong> Authentication, search queries, and payment proof submissions are throttled to avert brute-force and credential stuffing attacks.</li>
</ul>', NULL, 'Initial seeded template', '2026-11-01 02:33:28', '2026-11-01 02:33:28'),
(9, 9, 1, 'Disclaimer', '<h2>Website & Product Disclaimer</h2>
<p><strong>Last Updated: {last_updated}</strong></p>
<p>The information and product imagery presented on {store_name} are provided in good faith. Product carton packaging graphics and minor manufacturer reformulations may vary slightly from displayed stock photos without altering the core specification or carton unit count.</p>', NULL, 'Initial seeded template', '2026-11-01 02:33:28', '2026-11-01 02:33:28'),
(10, 10, 1, 'About Us', '<h2>About {store_name}</h2>
<p>{store_name} is a premier bulk grocery and household supplies distributor headquartered in Puchong, Selangor. Established to bridge the gap between heavy FMCG manufacturers and end-users, we empower small eateries, sundry grocers, offices, and large households to acquire everyday carton essentials at direct wholesale volume pricing.</p>

<h3>Our Mission</h3>
<p>To make wholesale bulk grocery shopping effortless, transparent, and economical through streamlined digital ordering and reliable local fleet delivery.</p>

<h3>Visit or Contact Our Distribution Center</h3>
<p>
    <strong>Address:</strong> {store_address}<br>
    <strong>Telephone:</strong> {store_phone}<br>
    <strong>Email:</strong> {store_email}<br>
    <strong>Business Hours:</strong> Monday through Saturday, 8:00 AM – 7:00 PM
</p>', NULL, 'Initial seeded template', '2026-11-01 02:33:28', '2026-11-01 02:33:28');

DROP TABLE IF EXISTS `pages`;
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

INSERT INTO `pages` VALUES
(1, 'Privacy Policy', 'privacy-policy', '<h2>Privacy Policy for {store_name}</h2>
<p><strong>Last Updated: {last_updated}</strong></p>
<p>{store_name} (Company Registration No: {company_registration_no}) is committed to safeguarding the personal data of our customers and visitors in compliance with applicable Personal Data Protection laws.</p>

<h3>1. Data We Collect</h3>
<p>To process wholesale bulk orders and arrange doorstep carton logistics, we collect:</p>
<ul>
    <li><strong>Identity & Contact Information:</strong> Full name, phone number, and email address.</li>
    <li><strong>Delivery Information:</strong> Physical delivery address, city, floor level, landmarks, and geographic coordinates selected on our interactive map.</li>
    <li><strong>Payment Verification Data:</strong> Bank transaction reference IDs and payment confirmation screenshot receipts uploaded during checkout.</li>
    <li><strong>Device & Session Data:</strong> IP address, browser type, and cookie preferences.</li>
</ul>

<h3>2. Purpose of Collection</h3>
<p>We process your data strictly to verify advance payments, fulfill and dispatch carton deliveries to your floor, communicate order status updates, prevent fraudulent submissions, and meet statutory accounting regulations.</p>

<h3>3. Data Storage & Protection</h3>
<p>Payment receipt screenshots are maintained on private, non-public storage directories accessible solely by authorised administrators via cryptographically signed temporary tokens. Personal data is never sold, traded, or shared with third-party advertising brokers.</p>

<h3>4. Your Rights & Data Requests</h3>
<p>You possess the right to access your personal data, request corrections, export your purchase history, or request account deletion via our <a href=\"/data-request\">Data Request Page</a>.</p>

<h3>5. Contact Us</h3>
<p>For inquiries regarding this policy, contact our Data Privacy Officer at <a href=\"mailto:{store_email}\">{store_email}</a> or phone {store_phone}.</p>', 1, 1, 1, 1, 1, 'Privacy Policy | {store_name}', 'Privacy Policy for {store_name}
Last Updated: {last_updated}
{store_name} (Company Registration No: {company_registration_no', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'WebPage', '2026-11-01 02:33:27', '2026-11-01 02:33:27'),
(2, 'Terms & Conditions', 'terms-and-conditions', '<h2>Terms and Conditions</h2>
<p><strong>Effective Date: {last_updated}</strong></p>
<p>Welcome to {store_name}. By accessing our bulk ordering platform or placing an order, you agree to comply with and be bound by the following Terms and Conditions.</p>

<h3>1. Wholesale Carton Supply</h3>
<p>Products on {store_name} are sold in manufacturer carton or case packaging as indicated in each product description (e.g., \"12*1.25L\", \"24*320ML\"). Individual unit splitting is not supported unless explicitly noted.</p>

<h3>2. Minimum Order Value</h3>
<p>The minimum order requirement across our store is {min_order_value}. Orders below this value cannot be processed through checkout.</p>

<h3>3. Order Placement & Advance Deposit</h3>
<p>Orders are officially placed only upon submission of valid advance payment proof (representing {advance_percentage} of the total order value). Submitting a falsified or duplicated payment receipt constitutes breach of terms and may lead to cancellation and blacklisting.</p>

<h3>4. Price & Availability</h3>
<p>While we make every endeavor to maintain accurate live inventory and wholesale pricing, we reserve the right to correct manifest clerical errors and notify you prior to dispatch.</p>

<h3>5. Governing Law</h3>
<p>These terms shall be governed by and construed in accordance with the laws of Malaysia, and parties submit to the exclusive jurisdiction of the competent courts.</p>', 1, 1, 1, 2, 1, 'Terms & Conditions | {store_name}', 'Terms and Conditions
Effective Date: {last_updated}
Welcome to {store_name}. By accessing our bulk ordering platform or plac', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'WebPage', '2026-11-01 02:33:27', '2026-11-01 02:33:27'),
(3, 'Refund & Return Policy', 'refund-and-return-policy', '<h2>Refund and Return Policy</h2>
<p><strong>Last Updated: {last_updated}</strong></p>
<p>At {store_name}, customer satisfaction with our wholesale goods is our highest priority.</p>

<h3>1. Inspection Upon Delivery</h3>
<p>Because bulk goods are delivered via dedicated ground delivery, customers or their authorized representatives must inspect outer carton seals upon receipt before settling the final cash or transfer balance.</p>

<h3>2. Return Window</h3>
<p>Claims for missing items, damaged packaging during transit, or expired stock must be reported within <strong>{return_window_days}</strong> of delivery with clear photographic evidence.</p>

<h3>3. Non-Returnable Goods</h3>
<p>Perishable food items, opened cartons where individual units have been consumed or removed, and items damaged due to inappropriate storage after delivery cannot be returned.</p>

<h3>4. Refund Method</h3>
<p>Approved refunds for verified defective or non-delivered carton goods are processed via direct bank transfer or instant DuitNow back to the originating account within 3 to 5 business days.</p>', 1, 1, 1, 3, 1, 'Refund & Return Policy | {store_name}', 'Refund and Return Policy
Last Updated: {last_updated}
At {store_name}, customer satisfaction with our wholesale goods is our', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'WebPage', '2026-11-01 02:33:27', '2026-11-01 02:33:27'),
(4, 'Shipping & Delivery Policy', 'shipping-and-delivery-policy', '<h2>Shipping and Delivery Policy</h2>
<p><strong>Last Updated: {last_updated}</strong></p>
<p>{store_name} operates specialized logistics vehicles suited for transporting heavy wholesale food and grocery cartons.</p>

<h3>1. Delivery Zones & Distance Calculations</h3>
<p>Delivery distance is calculated dynamically using straight-line geographical coordinates from our central fulfillment hub at {store_address}:</p>
<ul>
    <li><strong>Free Delivery Zone:</strong> Deliveries within <strong>{free_delivery_radius}</strong> receive 100% Free Shipping.</li>
    <li><strong>Paid Delivery Zone:</strong> Deliveries from <strong>{free_delivery_radius} up to {paid_delivery_radius}</strong> additional radius are serviced at nominal delivery rates calculated at checkout.</li>
    <li><strong>Beyond Serviceable Area:</strong> Addresses beyond our maximum serviceable radius cannot be accommodated via standard delivery.</li>
</ul>

<h3>2. Floor Level Policy & Charges</h3>
<p>Due to the heavy physical weight of wholesale cartons, floor level rules apply:</p>
<ul>
    <li><strong>Up to {free_floor_limit}:</strong> Completely <strong>FREE delivery</strong>.</li>
    <li><strong>Above {free_floor_limit}:</strong> Surcharge of <strong>{extra_floor_charge}</strong> per extra floor level.</li>
</ul>

<h3>3. Order Cut-Off Time</h3>
<p>Orders confirmed and payment-verified before <strong>{order_cutoff_time}</strong> are scheduled for next-day dispatch. Orders received after this cut-off time are batched for the subsequent dispatch cycle.</p>', 1, 1, 0, 4, 1, 'Shipping & Delivery Policy | {store_name}', 'Shipping and Delivery Policy
Last Updated: {last_updated}
{store_name} operates specialized logistics vehicles suited for tr', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'WebPage', '2026-11-01 02:33:27', '2026-11-01 02:33:27'),
(5, 'Payment Policy', 'payment-policy', '<h2>Payment Policy</h2>
<p><strong>Last Updated: {last_updated}</strong></p>
<p>To provide high wholesale value and eliminate third-party gateway fee markups, {store_name} utilizes a streamlined QR advance payment model.</p>

<h3>1. Advance Deposit Requirement</h3>
<p>Customers are required to transfer an advance deposit of <strong>{advance_percentage}</strong> of the grand total at the time of checkout. This deposit confirms your booking and reserves heavy warehouse inventory.</p>

<h3>2. How to Pay Advance</h3>
<ol>
    <li>Scan the displayed DuitNow QR code or initiate an instant bank transfer using your mobile banking application.</li>
    <li>Input the exact advance figure stated on your order summary.</li>
    <li>Save or screenshot the completed transaction confirmation.</li>
    <li>Submit the screenshot and unique Reference ID into the checkout portal.</li>
</ol>

<h3>3. Balance Payment on Delivery</h3>
<p>The remaining balance is payable upon delivery directly to our delivery team via Cash, DuitNow QR on delivery, or instant transfer upon receipt of cartons.</p>

<h3>4. Payment Rejection</h3>
<p>If a submitted payment screenshot is unreadable, invalid, or shows a mismatched transaction amount, our administrators will flag the order as <em>Payment Rejected</em> with a written reason. You may re-submit proof or contact customer care at {store_phone}.</p>', 1, 1, 0, 5, 1, 'Payment Policy | {store_name}', 'Payment Policy
Last Updated: {last_updated}
To provide high wholesale value and eliminate third-party gateway fee markups, {', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'WebPage', '2026-11-01 02:33:27', '2026-11-01 02:33:27'),
(6, 'Cancellation Policy', 'cancellation-policy', '<h2>Cancellation Policy</h2>
<p><strong>Last Updated: {last_updated}</strong></p>
<p>We understand plans change. Here is our policy regarding order cancellations:</p>

<h3>1. Cancellation Before Verification</h3>
<p>If your order is in <em>Pending Payment Verification</em> status and you wish to cancel, you may contact our customer support team immediately to cancel with full advance refund.</p>

<h3>2. Cancellation After Dispatch</h3>
<p>Once an order has been marked as <em>Out for Delivery</em>, the delivery run cannot be halted in transit. Cancellations at the doorstep may be subject to a logistics handling fee to cover vehicle dispatch and driver costs.</p>', 1, 1, 0, 6, 1, 'Cancellation Policy | {store_name}', 'Cancellation Policy
Last Updated: {last_updated}
We understand plans change. Here is our policy regarding order cancellation', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'WebPage', '2026-11-01 02:33:28', '2026-11-01 02:33:28'),
(7, 'Cookie Policy', 'cookie-policy', '<h2>Cookie Policy</h2>
<p><strong>Last Updated: {last_updated}</strong></p>
<p>{store_name} uses cookies and related browser storage technologies to deliver a secure, responsive browsing experience.</p>

<h3>1. Essential Cookies</h3>
<p>These cookies are technically indispensable for operating the website, preserving items in your shopping cart across pages, and protecting against CSRF attacks. They do not store personally identifiable promotional profiles.</p>

<h3>2. Analytics & Performance Cookies</h3>
<p>Non-essential analytics cookies (such as Google Analytics or visitor statistics) are loaded <strong>only after you grant explicit consent</strong> via our Cookie Consent banner.</p>

<h3>3. Managing Cookies</h3>
<p>You can customize your preferences or withdraw consent at any time via your browser settings or our cookie preference dialog.</p>', 1, 1, 0, 7, 1, 'Cookie Policy | {store_name}', 'Cookie Policy
Last Updated: {last_updated}
{store_name} uses cookies and related browser storage technologies to deliver a s', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'WebPage', '2026-11-01 02:33:28', '2026-11-01 02:33:28'),
(8, 'Security Policy', 'security-policy', '<h2>Security Policy</h2>
<p><strong>Last Updated: {last_updated}</strong></p>
<p>{store_name} implements defense-in-depth security principles to protect our customer transactions and infrastructure:</p>
<ul>
    <li><strong>Strict Access Controls:</strong> Payment screenshots and customer delivery addresses are stored in private storage partitions accessible solely by authenticated staff members through cryptographically signed temporary routes.</li>
    <li><strong>Encrypted Transport:</strong> All platform traffic is encrypted end-to-end via TLS/HTTPS with modern cipher suites and HSTS enforcement.</li>
    <li><strong>Server-Side Pricing Integrity:</strong> All product prices, delivery zone calculations, floor fees, and deposit requirements are strictly computed on the server during order creation to prevent client-side parameter tampering.</li>
    <li><strong>Rate Limiting & Abuse Prevention:</strong> Authentication, search queries, and payment proof submissions are throttled to avert brute-force and credential stuffing attacks.</li>
</ul>', 1, 1, 0, 8, 1, 'Security Policy | {store_name}', 'Security Policy
Last Updated: {last_updated}
{store_name} implements defense-in-depth security principles to protect our cus', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'WebPage', '2026-11-01 02:33:28', '2026-11-01 02:33:28'),
(9, 'Disclaimer', 'disclaimer', '<h2>Website & Product Disclaimer</h2>
<p><strong>Last Updated: {last_updated}</strong></p>
<p>The information and product imagery presented on {store_name} are provided in good faith. Product carton packaging graphics and minor manufacturer reformulations may vary slightly from displayed stock photos without altering the core specification or carton unit count.</p>', 1, 1, 0, 9, 1, 'Disclaimer | {store_name}', 'Website & Product Disclaimer
Last Updated: {last_updated}
The information and product imagery presented on {store_name} are ', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'WebPage', '2026-11-01 02:33:28', '2026-11-01 02:33:28'),
(10, 'About Us', 'about', '<h2>About {store_name}</h2>
<p>{store_name} is a premier bulk grocery and household supplies distributor headquartered in Puchong, Selangor. Established to bridge the gap between heavy FMCG manufacturers and end-users, we empower small eateries, sundry grocers, offices, and large households to acquire everyday carton essentials at direct wholesale volume pricing.</p>

<h3>Our Mission</h3>
<p>To make wholesale bulk grocery shopping effortless, transparent, and economical through streamlined digital ordering and reliable local fleet delivery.</p>

<h3>Visit or Contact Our Distribution Center</h3>
<p>
    <strong>Address:</strong> {store_address}<br>
    <strong>Telephone:</strong> {store_phone}<br>
    <strong>Email:</strong> {store_email}<br>
    <strong>Business Hours:</strong> Monday through Saturday, 8:00 AM – 7:00 PM
</p>', 1, 1, 0, 10, 1, 'About Us | {store_name}', 'About {store_name}
{store_name} is a premier bulk grocery and household supplies distributor headquartered in Puchong, Selangor. Established to brid', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'WebPage', '2026-11-01 02:33:28', '2026-11-01 02:33:28');

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `product_images`;
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

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `brand_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pack_size` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'e.g. FANTA 12*1.25L, Carton of 24',
  `unit_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'e.g. 12 bottles x 1.25 Litres',
  `weight_volume` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'e.g. 15 kg, 24 Litres',
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int NOT NULL DEFAULT '0',
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

INSERT INTO `products` VALUES
(1, 1, 2, 'FANTA ORANGE 12*1.25L', 'fanta-orange-12125l', 'BEV-FAN-01', '12 x 1.25L Bottle Carton', '12 bottles per carton (15 Litres total)', '15.5 kg', '38.50', '34.90', 120, 'Wholesale carton packaging for FANTA ORANGE 12*1.25L. 12 bottles per carton (15 Litres total).', '<p><strong>Wholesale Carton Pack:</strong> 12 x 1.25L Bottle Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-1.png', 1, 1, 0, 1, 'FANTA ORANGE 12*1.25L Wholesale Carton Bulk Price', 'Buy FANTA ORANGE 12*1.25L in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:26', '2026-11-01 03:34:41'),
(2, 1, 1, 'COCA-COLA ORIGINAL 24*320ML', 'coca-cola-original-24320ml', 'BEV-COC-02', '24 x 320ML Can Carton', '24 aluminium cans per carton', '8.2 kg', '42.00', '39.50', 200, 'Wholesale carton packaging for COCA-COLA ORIGINAL 24*320ML. 24 aluminium cans per carton.', '<p><strong>Wholesale Carton Pack:</strong> 24 x 320ML Can Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-2.png', 1, 1, 0, 1, 'COCA-COLA ORIGINAL 24*320ML Wholesale Carton Bulk Price', 'Buy COCA-COLA ORIGINAL 24*320ML in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:41'),
(3, 1, 3, '100 PLUS ISOTONIC 24*325ML', '100-plus-isotonic-24325ml', 'BEV-100-03', '24 x 325ML Can Carton', '24 aluminium cans per carton', '8.5 kg', '41.50', NULL, 180, 'Wholesale carton packaging for 100 PLUS ISOTONIC 24*325ML. 24 aluminium cans per carton.', '<p><strong>Wholesale Carton Pack:</strong> 24 x 325ML Can Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-3.png', 1, 1, 0, 0, '100 PLUS ISOTONIC 24*325ML Wholesale Carton Bulk Price', 'Buy 100 PLUS ISOTONIC 24*325ML in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:41'),
(4, 1, 1, 'SPRITE LEMON-LIME 12*1.25L', 'sprite-lemon-lime-12125l', 'BEV-SPR-04', '12 x 1.25L Bottle Carton', '12 PET bottles per carton', '15.5 kg', '38.50', '35.00', 90, 'Wholesale carton packaging for SPRITE LEMON-LIME 12*1.25L. 12 PET bottles per carton.', '<p><strong>Wholesale Carton Pack:</strong> 12 x 1.25L Bottle Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-4.png', 1, 0, 1, 1, 'SPRITE LEMON-LIME 12*1.25L Wholesale Carton Bulk Price', 'Buy SPRITE LEMON-LIME 12*1.25L in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:41'),
(5, 2, 4, 'MILO UHT CHOCOLATE MALT 24*200ML', 'milo-uht-chocolate-malt-24200ml', 'DRY-MIL-01', '24 x 200ML Tetra Pak Carton', '24 ready-to-drink tetra packs', '5.2 kg', '39.90', '36.80', 250, 'Wholesale carton packaging for MILO UHT CHOCOLATE MALT 24*200ML. 24 ready-to-drink tetra packs.', '<p><strong>Wholesale Carton Pack:</strong> 24 x 200ML Tetra Pak Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-5.png', 1, 1, 0, 1, 'MILO UHT CHOCOLATE MALT 24*200ML Wholesale Carton Bulk Price', 'Buy MILO UHT CHOCOLATE MALT 24*200ML in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:41'),
(6, 2, 5, 'DUTCH LADY FULL CREAM UHT 24*200ML', 'dutch-lady-full-cream-uht-24200ml', 'DRY-DUT-02', '24 x 200ML Tetra Pak Carton', '24 packets per carton', '5.1 kg', '37.50', NULL, 140, 'Wholesale carton packaging for DUTCH LADY FULL CREAM UHT 24*200ML. 24 packets per carton.', '<p><strong>Wholesale Carton Pack:</strong> 24 x 200ML Tetra Pak Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-6.png', 1, 0, 1, 0, 'DUTCH LADY FULL CREAM UHT 24*200ML Wholesale Carton Bulk Price', 'Buy DUTCH LADY FULL CREAM UHT 24*200ML in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:41'),
(7, 2, 5, 'DUTCH LADY CHOCOLATE UHT 12*1L', 'dutch-lady-chocolate-uht-121l', 'DRY-DUT-03', '12 x 1L Tetra Brik Carton', '12 1-Litre packs', '12.5 kg', '69.90', '64.90', 110, 'Wholesale carton packaging for DUTCH LADY CHOCOLATE UHT 12*1L. 12 1-Litre packs.', '<p><strong>Wholesale Carton Pack:</strong> 12 x 1L Tetra Brik Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-7.png', 1, 1, 0, 1, 'DUTCH LADY CHOCOLATE UHT 12*1L Wholesale Carton Bulk Price', 'Buy DUTCH LADY CHOCOLATE UHT 12*1L in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:41'),
(8, 2, 5, 'F&N SWEETENED CONDENSED MILK 48*500G', 'fn-sweetened-condensed-milk-48500g', 'DRY-FN-04', '48 x 500G Tin Carton', '48 tins bulk catering pack', '25.0 kg', '148.00', NULL, 65, 'Wholesale carton packaging for F&N SWEETENED CONDENSED MILK 48*500G. 48 tins bulk catering pack.', '<p><strong>Wholesale Carton Pack:</strong> 48 x 500G Tin Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-8.png', 1, 0, 0, 0, 'F&N SWEETENED CONDENSED MILK 48*500G Wholesale Carton Bulk Price', 'Buy F&N SWEETENED CONDENSED MILK 48*500G in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:41'),
(9, 3, 6, 'BURUH PURE COOKING OIL 4*5KG', 'buruh-pure-cooking-oil-45kg', 'OIL-BUR-01', '4 x 5KG Bottle Carton', '4 large 5kg bottles per carton', '20.5 kg', '118.00', '109.90', 150, 'Wholesale carton packaging for BURUH PURE COOKING OIL 4*5KG. 4 large 5kg bottles per carton.', '<p><strong>Wholesale Carton Pack:</strong> 4 x 5KG Bottle Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-9.png', 1, 1, 0, 1, 'BURUH PURE COOKING OIL 4*5KG Wholesale Carton Bulk Price', 'Buy BURUH PURE COOKING OIL 4*5KG in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:41'),
(10, 3, 7, 'SERI MURNI COOKING OIL 4*5KG', 'seri-murni-cooking-oil-45kg', 'OIL-SER-02', '4 x 5KG Bottle Carton', '4 bottles per carton', '20.5 kg', '122.00', NULL, 130, 'Wholesale carton packaging for SERI MURNI COOKING OIL 4*5KG. 4 bottles per carton.', '<p><strong>Wholesale Carton Pack:</strong> 4 x 5KG Bottle Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-10.png', 1, 0, 0, 0, 'SERI MURNI COOKING OIL 4*5KG Wholesale Carton Bulk Price', 'Buy SERI MURNI COOKING OIL 4*5KG in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:41'),
(11, 3, 6, 'KNIFE BRAND BLENDED COOKING OIL 4*5KG', 'knife-brand-blended-cooking-oil-45kg', 'OIL-KNF-03', '4 x 5KG Bottle Carton', '4 bottles per carton (rich in Vitamin E)', '20.8 kg', '145.00', '138.00', 85, 'Wholesale carton packaging for KNIFE BRAND BLENDED COOKING OIL 4*5KG. 4 bottles per carton (rich in Vitamin E).', '<p><strong>Wholesale Carton Pack:</strong> 4 x 5KG Bottle Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-11.png', 1, 1, 1, 1, 'KNIFE BRAND BLENDED COOKING OIL 4*5KG Wholesale Carton Bulk Price', 'Buy KNIFE BRAND BLENDED COOKING OIL 4*5KG in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(12, 3, 6, 'BURUH COOKING OIL 17KG TIN', 'buruh-cooking-oil-17kg-tin', 'OIL-BUR-04', '1 x 17KG Commercial Catering Tin', 'Commercial metal jerry tin for restaurants and caterers', '17.8 kg', '89.50', NULL, 70, 'Wholesale carton packaging for BURUH COOKING OIL 17KG TIN. Commercial metal jerry tin for restaurants and caterers.', '<p><strong>Wholesale Carton Pack:</strong> 1 x 17KG Commercial Catering Tin</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-12.png', 1, 0, 0, 0, 'BURUH COOKING OIL 17KG TIN Wholesale Carton Bulk Price', 'Buy BURUH COOKING OIL 17KG TIN in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(13, 4, 8, 'MAGGI 2-MINUTE NOODLE CURRY 12*5*79G', 'maggi-2-minute-noodle-curry-12579g', 'NDL-MAG-01', '12 Packs x 5 Bundles (60 Packets Carton)', '60 individual noodle packets per carton', '5.2 kg', '54.00', '49.90', 190, 'Wholesale carton packaging for MAGGI 2-MINUTE NOODLE CURRY 12*5*79G. 60 individual noodle packets per carton.', '<p><strong>Wholesale Carton Pack:</strong> 12 Packs x 5 Bundles (60 Packets Carton)</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-13.png', 1, 1, 0, 1, 'MAGGI 2-MINUTE NOODLE CURRY 12*5*79G Wholesale Carton Bulk Price', 'Buy MAGGI 2-MINUTE NOODLE CURRY 12*5*79G in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(14, 4, 8, 'MAGGI 2-MINUTE NOODLE CHICKEN 12*5*77G', 'maggi-2-minute-noodle-chicken-12577g', 'NDL-MAG-02', '12 Packs x 5 Bundles (60 Packets Carton)', '60 individual packets per carton', '5.0 kg', '54.00', NULL, 140, 'Wholesale carton packaging for MAGGI 2-MINUTE NOODLE CHICKEN 12*5*77G. 60 individual packets per carton.', '<p><strong>Wholesale Carton Pack:</strong> 12 Packs x 5 Bundles (60 Packets Carton)</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-14.png', 1, 0, 0, 0, 'MAGGI 2-MINUTE NOODLE CHICKEN 12*5*77G Wholesale Carton Bulk Price', 'Buy MAGGI 2-MINUTE NOODLE CHICKEN 12*5*77G in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(15, 4, 8, 'MAMEE CHEF LAKSA KARI 12*4*80G', 'mamee-chef-laksa-kari-12480g', 'NDL-MAM-03', '12 Bundles x 4 Packets (48 Packets)', 'La Mian authentic curry laksa paste', '4.2 kg', '66.00', '59.90', 95, 'Wholesale carton packaging for MAMEE CHEF LAKSA KARI 12*4*80G. La Mian authentic curry laksa paste.', '<p><strong>Wholesale Carton Pack:</strong> 12 Bundles x 4 Packets (48 Packets)</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-15.png', 1, 0, 1, 1, 'MAMEE CHEF LAKSA KARI 12*4*80G Wholesale Carton Bulk Price', 'Buy MAMEE CHEF LAKSA KARI 12*4*80G in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(16, 4, 8, 'JASMINE SUPER SPECIAL TEMPATAN 10KG (CARTON OF 3)', 'jasmine-super-special-tempatan-10kg-carton-of-3', 'RIC-JAS-04', '3 Bags x 10KG (30KG Wholesale)', '3 bags of 10kg local fragrant white rice', '30.2 kg', '115.00', NULL, 80, 'Wholesale carton packaging for JASMINE SUPER SPECIAL TEMPATAN 10KG (CARTON OF 3). 3 bags of 10kg local fragrant white rice.', '<p><strong>Wholesale Carton Pack:</strong> 3 Bags x 10KG (30KG Wholesale)</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-16.png', 1, 1, 0, 0, 'JASMINE SUPER SPECIAL TEMPATAN 10KG (CARTON OF 3) Wholesale Carton Bulk Price', 'Buy JASMINE SUPER SPECIAL TEMPATAN 10KG (CARTON OF 3) in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(17, 5, 9, 'AYAM BRAND SARDINES IN TOMATO SAUCE 24*425G', 'ayam-brand-sardines-in-tomato-sauce-24425g', 'CAN-AYM-01', '24 x 425G Big Tin Carton', '24 large round tins per wholesale carton', '11.5 kg', '195.00', '182.00', 80, 'Wholesale carton packaging for AYAM BRAND SARDINES IN TOMATO SAUCE 24*425G. 24 large round tins per wholesale carton.', '<p><strong>Wholesale Carton Pack:</strong> 24 x 425G Big Tin Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-17.png', 1, 1, 0, 1, 'AYAM BRAND SARDINES IN TOMATO SAUCE 24*425G Wholesale Carton Bulk Price', 'Buy AYAM BRAND SARDINES IN TOMATO SAUCE 24*425G in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(18, 5, 9, 'AYAM BRAND TUNA FLAKES IN OIL 24*150G', 'ayam-brand-tuna-flakes-in-oil-24150g', 'CAN-AYM-02', '24 x 150G Easy-Open Tin Carton', '24 cans wild caught skipjack tuna', '4.2 kg', '142.00', NULL, 110, 'Wholesale carton packaging for AYAM BRAND TUNA FLAKES IN OIL 24*150G. 24 cans wild caught skipjack tuna.', '<p><strong>Wholesale Carton Pack:</strong> 24 x 150G Easy-Open Tin Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-18.png', 1, 0, 1, 0, 'AYAM BRAND TUNA FLAKES IN OIL 24*150G Wholesale Carton Bulk Price', 'Buy AYAM BRAND TUNA FLAKES IN OIL 24*150G in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(19, 5, 9, 'AYAM BRAND BAKED BEANS 24*425G', 'ayam-brand-baked-beans-24425g', 'CAN-AYM-03', '24 x 425G Tin Carton', '24 cans baked beans in savory sauce', '11.6 kg', '112.00', '104.50', 120, 'Wholesale carton packaging for AYAM BRAND BAKED BEANS 24*425G. 24 cans baked beans in savory sauce.', '<p><strong>Wholesale Carton Pack:</strong> 24 x 425G Tin Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-19.png', 1, 0, 0, 1, 'AYAM BRAND BAKED BEANS 24*425G Wholesale Carton Bulk Price', 'Buy AYAM BRAND BAKED BEANS 24*425G in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(20, 5, 8, 'MAGGI TOMATO KETCHUP 12*475G', 'maggi-tomato-ketchup-12475g', 'CND-MAG-04', '12 x 475G Glass Bottle Carton', '12 bottles per carton', '8.0 kg', '45.00', NULL, 100, 'Wholesale carton packaging for MAGGI TOMATO KETCHUP 12*475G. 12 bottles per carton.', '<p><strong>Wholesale Carton Pack:</strong> 12 x 475G Glass Bottle Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-20.png', 1, 0, 0, 0, 'MAGGI TOMATO KETCHUP 12*475G Wholesale Carton Bulk Price', 'Buy MAGGI TOMATO KETCHUP 12*475G in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(21, 6, 10, 'JULIE\'S PEANUT BUTTER SANDWICH 24*360G', 'julies-peanut-butter-sandwich-24360g', 'SNK-JUL-01', '24 Packets x 360G Carton', '24 family packs with individual sachets', '9.4 kg', '135.00', '124.90', 115, 'Wholesale carton packaging for JULIE\'S PEANUT BUTTER SANDWICH 24*360G. 24 family packs with individual sachets.', '<p><strong>Wholesale Carton Pack:</strong> 24 Packets x 360G Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-21.png', 1, 1, 0, 1, 'JULIE\'S PEANUT BUTTER SANDWICH 24*360G Wholesale Carton Bulk Price', 'Buy JULIE\'S PEANUT BUTTER SANDWICH 24*360G in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(22, 6, 10, 'JULIE\'S CHEESE SANDWICH 24*336G', 'julies-cheese-sandwich-24336g', 'SNK-JUL-02', '24 Packets x 336G Carton', '24 packs of golden crispy cheese biscuits', '8.9 kg', '135.00', NULL, 90, 'Wholesale carton packaging for JULIE\'S CHEESE SANDWICH 24*336G. 24 packs of golden crispy cheese biscuits.', '<p><strong>Wholesale Carton Pack:</strong> 24 Packets x 336G Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-22.png', 1, 0, 0, 0, 'JULIE\'S CHEESE SANDWICH 24*336G Wholesale Carton Bulk Price', 'Buy JULIE\'S CHEESE SANDWICH 24*336G in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(23, 6, 10, 'OREO VANILLA COOKIES 24*119.6G', 'oreo-vanilla-cookies-241196g', 'SNK-ORE-03', '24 Rolls Carton', '24 roll packs classic cream sandwich', '3.5 kg', '68.00', '61.90', 170, 'Wholesale carton packaging for OREO VANILLA COOKIES 24*119.6G. 24 roll packs classic cream sandwich.', '<p><strong>Wholesale Carton Pack:</strong> 24 Rolls Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-23.png', 1, 1, 1, 1, 'OREO VANILLA COOKIES 24*119.6G Wholesale Carton Bulk Price', 'Buy OREO VANILLA COOKIES 24*119.6G in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(24, 6, 10, 'MUNCHY\'S CRACKERS PLUS ORIGINAL 12*300G', 'munchys-crackers-plus-original-12300g', 'SNK-MUN-04', '12 Packs x 300G Carton', '12 packs containing crisp whole wheat crackers', '4.1 kg', '48.00', NULL, 105, 'Wholesale carton packaging for MUNCHY\'S CRACKERS PLUS ORIGINAL 12*300G. 12 packs containing crisp whole wheat crackers.', '<p><strong>Wholesale Carton Pack:</strong> 12 Packs x 300G Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-24.png', 1, 0, 0, 0, 'MUNCHY\'S CRACKERS PLUS ORIGINAL 12*300G Wholesale Carton Bulk Price', 'Buy MUNCHY\'S CRACKERS PLUS ORIGINAL 12*300G in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(25, 7, 11, 'NESCAFE CLASSIC 3IN1 ORIGINAL 10*25*18G', 'nescafe-classic-3in1-original-102518g', 'COF-NES-01', '10 Bags x 25 Sticks (250 Sticks Carton)', '250 instant coffee sachets in 10 polybags', '5.2 kg', '119.00', '110.00', 160, 'Wholesale carton packaging for NESCAFE CLASSIC 3IN1 ORIGINAL 10*25*18G. 250 instant coffee sachets in 10 polybags.', '<p><strong>Wholesale Carton Pack:</strong> 10 Bags x 25 Sticks (250 Sticks Carton)</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-25.png', 1, 1, 0, 1, 'NESCAFE CLASSIC 3IN1 ORIGINAL 10*25*18G Wholesale Carton Bulk Price', 'Buy NESCAFE CLASSIC 3IN1 ORIGINAL 10*25*18G in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(26, 7, 11, 'NESCAFE GOLD BLEND SOLUBLE 6*200G', 'nescafe-gold-blend-soluble-6200g', 'COF-NES-02', '6 Glass Jars x 200G Carton', '6 jars premium freeze-dried coffee', '3.8 kg', '165.00', NULL, 75, 'Wholesale carton packaging for NESCAFE GOLD BLEND SOLUBLE 6*200G. 6 jars premium freeze-dried coffee.', '<p><strong>Wholesale Carton Pack:</strong> 6 Glass Jars x 200G Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-26.png', 1, 0, 1, 0, 'NESCAFE GOLD BLEND SOLUBLE 6*200G Wholesale Carton Bulk Price', 'Buy NESCAFE GOLD BLEND SOLUBLE 6*200G in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(27, 7, 11, 'BOH CAMERON HIGHLANDS TEA BAGS 12*100\'S', 'boh-cameron-highlands-tea-bags-12100s', 'TEA-BOH-03', '12 Boxes x 100 Potbags (1,200 Bags Carton)', 'Bulk catering pack for mamaks and offices', '3.2 kg', '125.00', '115.00', 90, 'Wholesale carton packaging for BOH CAMERON HIGHLANDS TEA BAGS 12*100\'S. Bulk catering pack for mamaks and offices.', '<p><strong>Wholesale Carton Pack:</strong> 12 Boxes x 100 Potbags (1,200 Bags Carton)</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-27.png', 1, 1, 0, 1, 'BOH CAMERON HIGHLANDS TEA BAGS 12*100\'S Wholesale Carton Bulk Price', 'Buy BOH CAMERON HIGHLANDS TEA BAGS 12*100\'S in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(28, 7, 4, 'MILO ACTIV-GO REFILL PACK 12*1KG', 'milo-activ-go-refill-pack-121kg', 'COF-MIL-04', '12 Bags x 1KG (12KG Carton)', '12 x 1kg chocolate malt powder pouches', '12.5 kg', '228.00', NULL, 85, 'Wholesale carton packaging for MILO ACTIV-GO REFILL PACK 12*1KG. 12 x 1kg chocolate malt powder pouches.', '<p><strong>Wholesale Carton Pack:</strong> 12 Bags x 1KG (12KG Carton)</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-28.png', 1, 0, 0, 0, 'MILO ACTIV-GO REFILL PACK 12*1KG Wholesale Carton Bulk Price', 'Buy MILO ACTIV-GO REFILL PACK 12*1KG in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(29, 8, 12, 'BREEZE LIQUID POWER CLEAN DETERGENT 4*3.6KG', 'breeze-liquid-power-clean-detergent-436kg', 'LND-BRZ-01', '4 x 3.6KG Bottle Carton', '4 heavy duty liquid detergent bottles', '15.5 kg', '95.00', '86.90', 130, 'Wholesale carton packaging for BREEZE LIQUID POWER CLEAN DETERGENT 4*3.6KG. 4 heavy duty liquid detergent bottles.', '<p><strong>Wholesale Carton Pack:</strong> 4 x 3.6KG Bottle Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-29.png', 1, 1, 0, 1, 'BREEZE LIQUID POWER CLEAN DETERGENT 4*3.6KG Wholesale Carton Bulk Price', 'Buy BREEZE LIQUID POWER CLEAN DETERGENT 4*3.6KG in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(30, 8, 13, 'DYNAMO POWER GEL REGULAR 4*2.6KG', 'dynamo-power-gel-regular-426kg', 'LND-DYN-02', '4 x 2.6KG Bottle Carton', '4 concentrated gel formula bottles', '11.2 kg', '88.00', NULL, 120, 'Wholesale carton packaging for DYNAMO POWER GEL REGULAR 4*2.6KG. 4 concentrated gel formula bottles.', '<p><strong>Wholesale Carton Pack:</strong> 4 x 2.6KG Bottle Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-30.png', 1, 0, 0, 0, 'DYNAMO POWER GEL REGULAR 4*2.6KG Wholesale Carton Bulk Price', 'Buy DYNAMO POWER GEL REGULAR 4*2.6KG in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(31, 8, 12, 'BREEZE COLOUR CARE DETERGENT POWDER 4*4.4KG', 'breeze-colour-care-detergent-powder-444kg', 'LND-BRZ-03', '4 x 4.4KG Bag Carton', '4 large bags per carton (17.6kg total)', '18.0 kg', '92.00', '84.50', 110, 'Wholesale carton packaging for BREEZE COLOUR CARE DETERGENT POWDER 4*4.4KG. 4 large bags per carton (17.6kg total).', '<p><strong>Wholesale Carton Pack:</strong> 4 x 4.4KG Bag Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-31.png', 1, 0, 1, 1, 'BREEZE COLOUR CARE DETERGENT POWDER 4*4.4KG Wholesale Carton Bulk Price', 'Buy BREEZE COLOUR CARE DETERGENT POWDER 4*4.4KG in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(32, 8, 12, 'DOWNY FABRIC SOFTENER SUNRISE FRESH 4*3.8L', 'downy-fabric-softener-sunrise-fresh-438l', 'LND-DWN-04', '4 x 3.8L Gallon Bottle Carton', 'Commercial bottle case with pour spout', '16.0 kg', '110.00', NULL, 70, 'Wholesale carton packaging for DOWNY FABRIC SOFTENER SUNRISE FRESH 4*3.8L. Commercial bottle case with pour spout.', '<p><strong>Wholesale Carton Pack:</strong> 4 x 3.8L Gallon Bottle Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-32.png', 1, 0, 0, 0, 'DOWNY FABRIC SOFTENER SUNRISE FRESH 4*3.8L Wholesale Carton Bulk Price', 'Buy DOWNY FABRIC SOFTENER SUNRISE FRESH 4*3.8L in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(33, 9, 14, 'PREMIER FACIAL TISSUE 2-PLY 48*200\'S', 'premier-facial-tissue-2-ply-48200s', 'PPR-PRE-01', '48 Boxes x 200 Pulls Carton', 'Wholesale box carton of 48 individual tissue boxes', '10.5 kg', '145.00', '134.00', 140, 'Wholesale carton packaging for PREMIER FACIAL TISSUE 2-PLY 48*200\'S. Wholesale box carton of 48 individual tissue boxes.', '<p><strong>Wholesale Carton Pack:</strong> 48 Boxes x 200 Pulls Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-33.png', 1, 1, 0, 1, 'PREMIER FACIAL TISSUE 2-PLY 48*200\'S Wholesale Carton Bulk Price', 'Buy PREMIER FACIAL TISSUE 2-PLY 48*200\'S in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(34, 9, 14, 'PREMIER KITCHEN TOWEL 3-PLY 12*6 ROLLS', 'premier-kitchen-towel-3-ply-126-rolls', 'PPR-PRE-02', '12 Packs x 6 Rolls (72 Rolls Carton)', '72 super absorbent embossed kitchen rolls', '8.6 kg', '118.00', NULL, 95, 'Wholesale carton packaging for PREMIER KITCHEN TOWEL 3-PLY 12*6 ROLLS. 72 super absorbent embossed kitchen rolls.', '<p><strong>Wholesale Carton Pack:</strong> 12 Packs x 6 Rolls (72 Rolls Carton)</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-34.png', 1, 0, 1, 0, 'PREMIER KITCHEN TOWEL 3-PLY 12*6 ROLLS Wholesale Carton Bulk Price', 'Buy PREMIER KITCHEN TOWEL 3-PLY 12*6 ROLLS in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(35, 9, 14, 'KLEENEX ULTRA SOFT TOILET TISSUE 4*20 ROLLS', 'kleenex-ultra-soft-toilet-tissue-420-rolls', 'PPR-KLN-03', '4 Bundles x 20 Rolls (80 Rolls Carton)', '80 rolls pure virgin fiber bathroom rolls', '9.2 kg', '128.00', '119.00', 115, 'Wholesale carton packaging for KLEENEX ULTRA SOFT TOILET TISSUE 4*20 ROLLS. 80 rolls pure virgin fiber bathroom rolls.', '<p><strong>Wholesale Carton Pack:</strong> 4 Bundles x 20 Rolls (80 Rolls Carton)</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-35.png', 1, 1, 0, 1, 'KLEENEX ULTRA SOFT TOILET TISSUE 4*20 ROLLS Wholesale Carton Bulk Price', 'Buy KLEENEX ULTRA SOFT TOILET TISSUE 4*20 ROLLS in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(36, 9, 14, 'PREMIER COMPACT INTERFOLD HAND TOWEL 20*250\'S', 'premier-compact-interfold-hand-towel-20250s', 'PPR-PRE-04', '20 Packs x 250 Sheets (5,000 Sheets Carton)', 'Commercial dispenser folded paper towel sheets', '7.8 kg', '98.00', NULL, 80, 'Wholesale carton packaging for PREMIER COMPACT INTERFOLD HAND TOWEL 20*250\'S. Commercial dispenser folded paper towel sheets.', '<p><strong>Wholesale Carton Pack:</strong> 20 Packs x 250 Sheets (5,000 Sheets Carton)</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-36.png', 1, 0, 0, 0, 'PREMIER COMPACT INTERFOLD HAND TOWEL 20*250\'S Wholesale Carton Bulk Price', 'Buy PREMIER COMPACT INTERFOLD HAND TOWEL 20*250\'S in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(37, 10, 15, 'DETTOL ANTISEPTIC DISINFECTANT LIQUID 12*750ML', 'dettol-antiseptic-disinfectant-liquid-12750ml', 'SAN-DET-01', '12 x 750ML Bottle Carton', '12 bottles medical grade antiseptic liquid', '10.2 kg', '248.00', '229.00', 60, 'Wholesale carton packaging for DETTOL ANTISEPTIC DISINFECTANT LIQUID 12*750ML. 12 bottles medical grade antiseptic liquid.', '<p><strong>Wholesale Carton Pack:</strong> 12 x 750ML Bottle Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-37.png', 1, 1, 0, 1, 'DETTOL ANTISEPTIC DISINFECTANT LIQUID 12*750ML Wholesale Carton Bulk Price', 'Buy DETTOL ANTISEPTIC DISINFECTANT LIQUID 12*750ML in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(38, 10, 15, 'CLOROX REGULAR BLEACH DISINFECTANT 6*4L', 'clorox-regular-bleach-disinfectant-64l', 'SAN-CLX-02', '6 x 4L Gallon Jugs Carton', '6 commercial 4-Litre bleach jugs', '25.5 kg', '78.00', NULL, 85, 'Wholesale carton packaging for CLOROX REGULAR BLEACH DISINFECTANT 6*4L. 6 commercial 4-Litre bleach jugs.', '<p><strong>Wholesale Carton Pack:</strong> 6 x 4L Gallon Jugs Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-38.png', 1, 0, 0, 0, 'CLOROX REGULAR BLEACH DISINFECTANT 6*4L Wholesale Carton Bulk Price', 'Buy CLOROX REGULAR BLEACH DISINFECTANT 6*4L in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(39, 10, 15, 'SUNLIGHT DISHWASHING LIQUID LIME 6*4L', 'sunlight-dishwashing-liquid-lime-64l', 'SAN-SUN-03', '6 x 4L Commercial Gallon Carton', '6 bottles grease stripping lime dishwash', '25.0 kg', '96.00', '89.90', 95, 'Wholesale carton packaging for SUNLIGHT DISHWASHING LIQUID LIME 6*4L. 6 bottles grease stripping lime dishwash.', '<p><strong>Wholesale Carton Pack:</strong> 6 x 4L Commercial Gallon Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-39.png', 1, 0, 1, 1, 'SUNLIGHT DISHWASHING LIQUID LIME 6*4L Wholesale Carton Bulk Price', 'Buy SUNLIGHT DISHWASHING LIQUID LIME 6*4L in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42'),
(40, 10, 15, 'MR MUSCLE MULTI-SURFACE FLOOR CLEANER 6*3.7L', 'mr-muscle-multi-surface-floor-cleaner-637l', 'SAN-MRM-04', '6 x 3.7L Jug Carton', '6 bottles antibacterial floral floor cleaner', '23.0 kg', '82.00', NULL, 90, 'Wholesale carton packaging for MR MUSCLE MULTI-SURFACE FLOOR CLEANER 6*3.7L. 6 bottles antibacterial floral floor cleaner.', '<p><strong>Wholesale Carton Pack:</strong> 6 x 3.7L Jug Carton</p><p>Ideal for restaurants, offices, sundry shops, mini-markets, and households looking for substantial wholesale savings on bulk grocery goods.</p><ul><li>Authentic factory sealed carton</li><li>Guaranteed long expiry date</li><li>Carefully transported by our bulk logistics team</li></ul>', 'products/product-40.png', 1, 0, 0, 0, 'MR MUSCLE MULTI-SURFACE FLOOR CLEANER 6*3.7L Wholesale Carton Bulk Price', 'Buy MR MUSCLE MULTI-SURFACE FLOOR CLEANER 6*3.7L in bulk cartons. Direct wholesale pricing with door-to-door delivery.', NULL, NULL, 'index, follow', NULL, NULL, NULL, 'summary_large_image', NULL, 'Product', '2026-11-01 02:33:27', '2026-11-01 03:34:42');

DROP TABLE IF EXISTS `sessions`;
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

INSERT INTO `sessions` VALUES
('4sS5UazZcKSZKgdObDpz5GsFexUd3ZalsHIdimbQ', 1, '127.0.0.1', '', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoibFN5VzBQWVVBUjNySm9QRGFMa3R6ZzU2YjZ3ZHR3dTFCa2JkWTB4TyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjQ6ImNhOTJmZjc3Mzk1YWRiZGE0N2RjMjkwMTJmMzY0MjA4YWI3MDA2Y2NhZDFjZGYxNDkzNTY5MDRmZWZiODU1Y2QiO30=', 1793572538),
('9JXfLQf448njTfgLWe3yy7UloKEYm2pT1PG4EJjs', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiT0xKelJWSGhrdnpNQUZNYm9hWndndGd4MlZtUnAyU1h0NVFzM2dwWiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1793575198),
('c2FVjefJeuiwm7nb2mPjeehGExFPLiHiJHGjvp8k', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicklFZU9naUtzb2Y4aUdHY01sd05PVk90N1JHQ3JSTkVSUWthRkZGYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1793575193),
('JRq8xyoamYlAJSKIi1WTkM83gSjAjxrqJyFKdyMT', 4, '127.0.0.1', '', 'YTo4OntzOjY6Il90b2tlbiI7czo0MDoiY0JicHlwSHVLWElXaXhNZHJMUjZPRlJ3UjlxMHQwVTdzSmZqRlAwZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjI6e2k6MDtzOjEyOiJkZXZfb3RwX2NvZGUiO2k6MTtzOjY6InN0YXR1cyI7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2NDoiY2I3OGIwNTg0YWUzOWRjN2Q5NzY3YjhiNDIxMDY4MTYyZWI1ZDZmMDk4ZWJlOGNhZDhiZThlOWIzOWI0ZmIwYSI7czoxMjoiZGV2X290cF9jb2RlIjtzOjY6IjMyMjY0MiI7czoyNjoicGVuZGluZ192ZXJpZmljYXRpb25fZW1haWwiO3M6MjA6ImN1c3RvbWVyQGV4YW1wbGUuY29tIjtzOjY6InN0YXR1cyI7czo2NDoiUGxlYXNlIGVudGVyIHlvdXIgNi1kaWdpdCB2ZXJpZmljYXRpb24gY29kZSB0byBjb21wbGV0ZSBzaWduIGluLiI7fQ==', 1793572556),
('jWP9nzSMDGAn3UVQrAgjtJNFZDYoFqLsRaKmGRYJ', NULL, '2407:aa80:126:b0f0:f15b:62eb:10e3:c22e', '', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoicENtakI5dHFHYjZQdWwyUEFvN3Z2WkhTU045Z0loN3lFcTBQdzN5eiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1793573933),
('mQiQw0uQg9YDLWMiurTbEGcBhdDskzhwPqqfq47h', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ05vVjlnb3dTWjJWZnozV2dGc3lpZEtxRkJHM0ZVZm9Lb1NUdFZ4ZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1793574737),
('nkyAwDs45uHlZi6Ke9HtjYujM2NbgdDSI3M65A06', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiOWduVzNyQnJvRncyWGlVYUExZmxPQk56Y0tZZlV6RG5vNDExanoyUCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9ub3RpZmljYXRpb25zL3BvbGwiO3M6NToicm91dGUiO3M6MjQ6ImFkbWluLm5vdGlmaWNhdGlvbnMucG9sbCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjQ6ImNhOTJmZjc3Mzk1YWRiZGE0N2RjMjkwMTJmMzY0MjA4YWI3MDA2Y2NhZDFjZGYxNDkzNTY5MDRmZWZiODU1Y2QiO30=', 1793577317),
('rcluc5dWNWnkUnXjUVUVLHBL0zJlmJgci8Z06frL', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRFlmMVloSGJtZ1pRVUVyTXR2WldubkF3OTB5TXp1YzFOc2tGUnpxZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1793571903),
('ROcdEgCUHLqgtdYTz7G1X5tz8XTIAnAmHKVXWFbY', NULL, '2407:aa80:126:b0f0:f15b:62eb:10e3:c22e', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRDZOYVpXMVFHYXRoaEhlazhiVTUxMlg5dlNmd2d6WlNqZWo5Tk9SdyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTc6Imh0dHBzOi8vYnJvdGhlcnMtc2lzdGVycy13b29kZW4tc2lnbmFscy50cnljbG91ZGZsYXJlLmNvbSI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1793574754),
('s9NjRETZXw46SbQ0I6OLDBtN4BIIJWK1g1WheRGC', NULL, '2407:aa80:126:b0f0:f15b:62eb:10e3:c22e', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoib3l1Sm5HamFnVUxHYUxLcjMwNFE3VHZLOW0yTVBlTWpaVVBuTEtIZyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTc6Imh0dHBzOi8vYnJvdGhlcnMtc2lzdGVycy13b29kZW4tc2lnbmFscy50cnljbG91ZGZsYXJlLmNvbSI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1793571913),
('TEr7fgKHRrHj4p94c4QgyeiovzjswTBllhUmGPsB', NULL, '127.0.0.1', '', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiQ09OdDBzRWV0VXhsTHdtQklVcXVDV2Rjc1NIVDdmUnNKTDhrclFZdCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1793573874),
('ZM7r37XWGR0Pp2GhQyxC26ybPK4vSO93uaTg5m9S', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVVlHcFlhRzdPSW94MzJrMTA2V2lybjU2ZUtHMm5XdEJNTTViZE85cyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jYXRlZ29yaWVzIjtzOjU6InJvdXRlIjtzOjE0OiJjYXRlZ29yeS5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1793574745);

DROP TABLE IF EXISTS `settings`;
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

INSERT INTO `settings` VALUES
(1, 'site_name', 'N&J mini mart', 'general', 'text', '2026-11-01 02:33:24', '2026-11-01 22:48:30'),
(2, 'site_tagline', 'Direct Wholesale Groceries & Case Cartons', 'general', 'text', '2026-11-01 02:33:24', '2026-11-01 22:48:30'),
(3, 'store_email', 'orders@99bulksales.my', 'general', 'text', '2026-11-01 02:33:24', '2026-11-01 02:33:24'),
(4, 'store_phone', '+60 12-889 9900', 'general', 'text', '2026-11-01 02:33:24', '2026-11-01 02:33:24'),
(5, 'store_address', 'Lot 45, Jalan Industri 3/1, Kawasan Perindustrian Puchong, 47100 Puchong, Selangor', 'general', 'text', '2026-11-01 02:33:24', '2026-11-01 02:33:24'),
(6, 'company_registration_no', '202401019988 (1458923-M)', 'general', 'text', '2026-11-01 02:33:24', '2026-11-01 02:33:24'),
(7, 'working_hours', 'Mon - Sat: 8:00 AM - 7:00 PM (Closed on Sundays)', 'general', 'text', '2026-11-01 02:33:24', '2026-11-01 02:33:24'),
(8, 'currency_symbol', 'RM', 'delivery', 'text', '2026-11-01 02:33:24', '2026-11-01 22:59:09'),
(9, 'currency_code', 'MYR', 'general', 'text', '2026-11-01 02:33:24', '2026-11-01 02:33:24'),
(10, 'currency_decimals', '2', 'delivery', 'number', '2026-11-01 02:33:25', '2026-11-01 22:59:09'),
(11, 'order_number_prefix', 'BS', 'general', 'text', '2026-11-01 02:33:25', '2026-11-01 02:33:25'),
(12, 'return_window_days', '7', 'general', 'number', '2026-11-01 02:33:25', '2026-11-01 02:33:25'),
(13, 'order_cutoff_time', '4:00 PM', 'general', 'text', '2026-11-01 02:33:25', '2026-11-01 02:33:25'),
(14, 'delivery_enabled', '1', 'delivery', 'boolean', '2026-11-01 02:33:25', '2026-11-01 22:59:08'),
(15, 'store_latitude', '3.0245', 'delivery', 'number', '2026-11-01 02:33:25', '2026-11-01 02:33:25'),
(16, 'store_longitude', '101.6166', 'delivery', 'number', '2026-11-01 02:33:25', '2026-11-01 02:33:25'),
(17, 'free_delivery_radius', '2', 'delivery', 'number', '2026-11-01 02:33:25', '2026-11-01 22:59:08'),
(18, 'paid_delivery_radius', '1', 'delivery', 'number', '2026-11-01 02:33:25', '2026-11-01 22:59:08'),
(19, 'paid_delivery_charge_mode', 'fixed', 'delivery', 'select', '2026-11-01 02:33:25', '2026-11-01 02:33:25'),
(20, 'paid_delivery_charge', '5', 'delivery', 'number', '2026-11-01 02:33:25', '2026-11-01 22:59:08'),
(21, 'free_floor_limit', '1', 'delivery', 'number', '2026-11-01 02:33:25', '2026-11-01 02:33:25'),
(22, 'extra_floor_charge', '5', 'delivery', 'number', '2026-11-01 02:33:25', '2026-11-01 22:59:09'),
(23, 'max_floor_allowed', '15', 'delivery', 'number', '2026-11-01 02:33:25', '2026-11-01 02:33:25'),
(24, 'min_order_value', '100.00', 'delivery', 'number', '2026-11-01 02:33:25', '2026-11-01 02:33:25'),
(25, 'require_advance_payment', '1', 'payment', 'boolean', '2026-11-01 02:33:25', '2026-11-01 02:33:25'),
(26, 'advance_percentage', '10', 'payment', 'number', '2026-11-01 02:33:25', '2026-11-01 02:33:25'),
(27, 'qr_code_image', 'images/demo-qr.png', 'payment', 'image', '2026-11-01 02:33:25', '2026-11-01 02:33:25'),
(28, 'bank_account_details', 'Bank: Maybank Berhad
Account Name: 99 Bulk Sales Sdn Bhd
Account No: 5148 7921 6890
DuitNow QR ID: 202401019988', 'payment', 'textarea', '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(29, 'payment_instructions', '1. Scan the DuitNow QR code or transfer to our Maybank account.
2. Pay the exact Advance Deposit shown below.
3. Take a screenshot of the completed transfer.
4. Enter the Transaction/Reference ID and upload the screenshot to confirm your order.', 'payment', 'textarea', '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(30, 'notify_email_enabled', '1', 'notification', 'boolean', '2026-11-01 02:33:26', '2026-11-01 04:22:00'),
(31, 'admin_email', 'admin@99bulksales.my', 'notification', 'text', '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(32, 'admin_phone', '+60128899900', 'notification', 'text', '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(33, 'notify_telegram_enabled', '0', 'notification', 'boolean', '2026-11-01 02:33:26', '2026-11-01 04:22:00'),
(34, 'telegram_bot_token', NULL, 'notification', 'text', '2026-11-01 02:33:26', '2026-11-01 04:22:00'),
(35, 'telegram_chat_id', NULL, 'notification', 'text', '2026-11-01 02:33:26', '2026-11-01 04:22:00'),
(36, 'notify_callmebot_enabled', '1', 'notification', 'boolean', '2026-11-01 02:33:26', '2026-11-01 04:22:00'),
(37, 'callmebot_phone', '+923176121524', 'notification', 'text', '2026-11-01 02:33:26', '2026-11-01 04:22:00'),
(38, 'callmebot_api_key', NULL, 'notification', 'text', '2026-11-01 02:33:26', '2026-11-01 04:22:00'),
(39, 'seo_meta_title_template', '%title% | %site_name%', 'seo', 'text', '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(40, 'seo_default_title', 'Wholesale Carton Bulk Sales & Household Supplies', 'seo', 'text', '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(41, 'seo_default_description', 'Shop groceries, drinks, canned food, cooking oil, detergent and paper goods in wholesale cartons with fast delivery direct to your doorstep.', 'seo', 'textarea', '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(42, 'seo_default_keywords', 'bulk groceries, wholesale carton drinks, fanta 12x1.25L, 100plus bulk, milo carton, cooking oil bulk, wholesale grocery puchong', 'seo', 'text', '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(43, 'seo_default_robots', 'index, follow', 'seo', 'text', '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(44, 'google_search_console_tag', '', 'seo', 'text', '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(45, 'bing_verification_tag', '', 'seo', 'text', '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(46, 'google_analytics_id', '', 'seo', 'text', '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(47, 'facebook_pixel_id', '', 'seo', 'text', '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(48, 'custom_header_scripts', '', 'seo', 'textarea', '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(49, 'custom_footer_scripts', '', 'seo', 'textarea', '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(50, 'robots_txt_content', 'User-agent: *
Allow: /
Disallow: /admin/
Disallow: /account/
Disallow: /checkout/

Sitemap: http://localhost:8000/sitemap.xml', 'seo', 'textarea', '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(51, 'color_primary', '#0284c7', 'appearance', 'color', '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(52, 'color_primary_hover', '#0369a1', 'appearance', 'color', '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(53, 'color_secondary', '#0f172a', 'appearance', 'color', '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(54, 'color_accent', '#f59e0b', 'appearance', 'color', '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(55, 'color_bg', '#f8fafc', 'appearance', 'color', '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(56, 'color_text', '#1e293b', 'appearance', 'color', '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(57, 'color_header_bg', '#ffffff', 'appearance', 'color', '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(58, 'color_footer_bg', '#0f172a', 'appearance', 'color', '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(59, 'color_button_text', '#ffffff', 'appearance', 'color', '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(60, 'font_family', '\'Open Sans\', sans-serif', 'appearance', 'select', '2026-11-01 02:33:26', '2026-11-01 22:35:28'),
(61, 'border_radius', '0.5rem', 'appearance', 'text', '2026-11-01 02:33:26', '2026-11-01 22:35:52'),
(62, 'cookie_consent_enabled', '1', 'appearance', 'boolean', '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(63, 'cookie_consent_title', 'We value your privacy', 'appearance', 'text', '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(64, 'cookie_consent_message', 'We use essential cookies to keep your shopping cart and order status secure. We only load analytics if you consent.', 'appearance', 'textarea', '2026-11-01 02:33:26', '2026-11-01 02:33:26'),
(65, 'site_logo', 'theme/NMN2dYrNGrGFrDk8RT0IGHfXUCKxx6XJSMD1RFzB.png', 'appearance', 'text', '2026-11-01 22:48:30', '2026-11-01 22:52:52'),
(66, 'site_logo_light', 'images/logo-light-bg.png', 'appearance', 'text', '2026-11-01 22:48:30', '2026-11-01 22:48:30'),
(67, 'site_favicon', 'theme/sPrEK3dSAMg5kfV9443hBU6qEzQN7rabeoxa98Gt.png', 'appearance', 'text', '2026-11-01 22:52:52', '2026-11-01 22:52:52');

DROP TABLE IF EXISTS `users`;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` VALUES
(1, 'Store Administrator', 'admin@99bulksales.my', '+60128899900', 'admin', 1, '2026-11-01 22:30:52', '$2y$12$5pqCkvbe.xcx0tvP3JpGS.0IwUuYhcTiBahSqWQH9Brj3pQCXM1T6', NULL, NULL, NULL, NULL, '2026-11-01 02:33:28', '2026-11-01 22:30:52'),
(2, 'Dispatch Staff', 'staff@99bulksales.my', '+60123344556', 'staff', 1, '2026-11-01 22:30:52', '$2y$12$sHLaEM3E1ltr5ICGeJCrvuRrujQscFfinPU1p1ElVPEMV2XcUirqm', NULL, NULL, NULL, NULL, '2026-11-01 02:33:28', '2026-11-01 22:30:52'),
(3, 'Nabeel Ahmad', 'customer@gmail.com', '+60112233445', 'customer', 1, '2026-11-01 22:30:53', '$2y$12$QG.RKYVKvkCRQLsX1P/wIuL4IitMReukDnWVUg3MGI57kdbYihXnK', NULL, NULL, NULL, NULL, '2026-11-01 02:33:28', '2026-11-01 22:30:53'),
(4, 'Wholesale Customer', 'customer@example.com', '+60112233445', 'customer', 1, NULL, '$2y$12$X8SNb4Vq4.O/wiEHGhUIbuAlnkiswEESeKetda1X05MzUnviicKIS', NULL, NULL, NULL, NULL, '2026-11-01 22:30:53', '2026-11-01 22:30:53');

SET FOREIGN_KEY_CHECKS=1;
