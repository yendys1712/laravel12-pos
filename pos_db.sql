-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 16, 2025 at 12:47 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pos_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `barcode` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `barcode`, `price`, `created_at`, `updated_at`, `quantity`, `image`, `image_url`) VALUES
(1, 'RICE', '46546854654', 12.00, '2025-08-01 16:00:00', NULL, 68, 'images/1754109107_rice.jpg', NULL),
(2, 'FEEDS', '524654657468', 25.00, '2025-08-01 16:00:00', NULL, 544, 'images/1754109148_feeds.jpg', NULL),
(3, 'TANDUAY', '6546846887', 150.00, '2025-08-01 16:00:00', NULL, 22, 'images/1754109096_tanduay.jpg', NULL),
(4, 'SUGAR', '35465484822', 15.00, '2025-08-01 16:00:00', NULL, 499, 'images/1754300596_sugar.jpg', NULL),
(6, 'RED HORSE BEER', '5465456222', 130.00, '2025-08-03 16:00:00', NULL, 37, 'uploads/items/1754311570_rh.jpg', NULL),
(64, 'Coke', '123456789', 25.00, '2025-08-14 21:26:53', NULL, 99, 'images/1755252057_coke.jpg', NULL),
(65, 'Pepsi', '415454', 100.00, '2025-08-14 21:26:53', NULL, 99, 'images/1755252097_pepsi.jpg', NULL),
(66, 'Rice', '6545488', 52.00, '2025-08-14 21:26:53', NULL, 150, 'images/1755253846_rice.jpg', NULL),
(67, 'RICE 1 KILO', '6454685468', 52.00, '2025-08-15 12:27:04', NULL, 12, 'uploads/items/1755260824_rice.jpg', NULL),
(68, 'RICE 5 KILO', '15454777', 2500.00, '2025-08-15 12:44:49', NULL, 12, 'uploads/items/1755261889_rice.jpg', NULL),
(69, 'COKE 1 LITER', '54854875222', 90.00, '2025-08-15 12:46:23', NULL, 48, 'uploads/items/1755261983_coke.jpg', NULL),
(70, 'GSM 1 LITTER', '548725225', 240.00, '2025-08-15 12:49:24', NULL, 123, 'uploads/items/1755262164_GSM.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `item_histories`
--

CREATE TABLE `item_histories` (
  `id` bigint(20) UNSIGNED DEFAULT NULL,
  `item_id_his` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `action` enum('add','edit','delete') NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` double NOT NULL,
  `notes` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_histories`
--

INSERT INTO `item_histories` (`id`, `item_id_his`, `item_name`, `action`, `user_id`, `quantity`, `price`, `notes`, `created_at`, `updated_at`) VALUES
(NULL, 1, 'TANDUAY', 'add', NULL, 0, 0, '', '2025-08-02 04:25:30', '2025-08-02 04:25:30'),
(NULL, 2, 'TANDUAY', 'edit', NULL, 0, 0, '', '2025-08-02 04:31:36', '2025-08-02 04:31:36'),
(NULL, 3, 'RICE', 'edit', NULL, 0, 0, '', '2025-08-02 04:31:47', '2025-08-02 04:31:47'),
(NULL, 4, 'FEEDS', 'edit', NULL, 0, 0, '', '2025-08-02 04:32:28', '2025-08-02 04:32:28'),
(NULL, 5, 'RICE', 'edit', NULL, 0, 0, '', '2025-08-02 09:28:21', '2025-08-02 09:28:21'),
(NULL, 6, 'TANDUAY', 'edit', NULL, 0, 0, '', '2025-08-02 09:28:32', '2025-08-02 09:28:32'),
(NULL, 7, 'SUGAR', 'add', NULL, 0, 0, '', '2025-08-02 09:32:54', '2025-08-02 09:32:54'),
(NULL, 8, 'SUGAR', 'edit', NULL, 0, 0, '', '2025-08-02 09:33:04', '2025-08-02 09:33:04'),
(NULL, 9, 'CORN BEEF', 'add', NULL, 0, 0, '', '2025-08-02 13:07:58', '2025-08-02 13:07:58'),
(NULL, 10, 'FEEDS', 'edit', NULL, 0, 0, '', '2025-08-04 08:05:09', '2025-08-04 08:05:09'),
(NULL, 11, 'SUGAR', 'edit', NULL, 0, 0, '', '2025-08-04 09:43:16', '2025-08-04 09:43:16'),
(NULL, 12, 'CORN BEEF', 'edit', NULL, 0, 0, '', '2025-08-04 09:43:28', '2025-08-04 09:43:28'),
(NULL, 13, 'RED HORSE BEER', 'add', 1, 0, 0, '', '2025-08-04 12:46:10', '2025-08-04 12:46:10'),
(NULL, 14, 'GSM', 'add', 1, 0, 0, '', '2025-08-14 05:03:21', '2025-08-14 05:03:21'),
(NULL, 15, 'GSM', 'edit', 1, 0, 0, '', '2025-08-14 05:03:56', '2025-08-14 05:03:56'),
(NULL, 16, 'Coke', 'edit', 1, 0, 0, '', '2025-08-14 06:50:50', '2025-08-14 06:50:50'),
(NULL, 17, 'Coke', 'add', 1, 1, 100, '25', '2025-08-15 07:17:58', '2025-08-15 07:17:58'),
(NULL, 18, 'Pepsi', 'add', 1, 1, 100, '100', '2025-08-15 07:17:58', '2025-08-15 07:17:58'),
(NULL, 19, 'Rice', 'add', 1, 1, 100, '52', '2025-08-15 07:17:58', '2025-08-15 07:17:58'),
(NULL, 20, 'Coke', 'add', 1, 1, 100, '25', '2025-08-15 07:18:27', '2025-08-15 07:18:27'),
(NULL, 21, 'Pepsi', 'add', 1, 1, 100, '100', '2025-08-15 07:18:27', '2025-08-15 07:18:27'),
(NULL, 22, 'Rice', 'add', 1, 1, 100, '52', '2025-08-15 07:18:27', '2025-08-15 07:18:27'),
(NULL, 23, 'Coke', 'add', 1, 100, 25, 'Empty', '2025-08-15 09:01:06', '2025-08-15 09:01:06'),
(NULL, 24, 'Pepsi', 'add', 1, 100, 100, 'Empty', '2025-08-15 09:01:06', '2025-08-15 09:01:06'),
(NULL, 25, 'Rice', 'add', 1, 100, 52, 'Empty', '2025-08-15 09:01:06', '2025-08-15 09:01:06'),
(NULL, 26, 'Coke', 'add', 1, 100, 25, 'Empty', '2025-08-15 09:19:55', '2025-08-15 09:19:55'),
(NULL, 27, 'Pepsi', 'add', 1, 100, 100, 'Empty', '2025-08-15 09:19:55', '2025-08-15 09:19:55'),
(NULL, 28, 'Rice', 'add', 1, 100, 52, 'Empty', '2025-08-15 09:19:55', '2025-08-15 09:19:55'),
(NULL, 29, 'Coke', 'add', 1, 100, 25, 'Bulk added', '2025-08-15 09:26:53', '2025-08-15 09:26:53'),
(NULL, 30, 'Pepsi', 'add', 1, 100, 100, 'Bulk added', '2025-08-15 09:26:53', '2025-08-15 09:26:53'),
(NULL, 31, 'Rice', 'add', 1, 100, 52, 'Bulk added', '2025-08-15 09:26:53', '2025-08-15 09:26:53'),
(NULL, 32, 'Rice', 'edit', NULL, 150, 52, 'Edit', '2025-08-15 10:11:55', '2025-08-15 10:11:55'),
(NULL, 33, 'Rice', 'edit', NULL, 150, 52, 'Edit', '2025-08-15 10:30:46', '2025-08-15 10:30:46'),
(NULL, 34, 'GSM 1 LITTER', 'add', NULL, 123, 240, 'Added manually', '2025-08-15 12:49:24', '2025-08-15 12:49:24');

-- --------------------------------------------------------

--
-- Table structure for table `item_sale`
--

CREATE TABLE `item_sale` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cashier` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_sale`
--

INSERT INTO `item_sale` (`id`, `sale_id`, `item_id`, `user_id`, `item_name`, `quantity`, `price`, `discount`, `cashier`, `created_at`, `updated_at`) VALUES
(44, 48, 1, 0, 'RICE', 1, 12.00, 0.00, 'SYDNEY', '2025-08-04 12:52:56', '2025-08-04 12:52:56'),
(45, 49, 6, 0, 'RED HORSE BEER', 1, 130.00, 0.00, 'SYDNEY', '2025-08-04 13:07:25', '2025-08-04 13:07:25'),
(46, 51, 6, 0, 'RED HORSE BEER', 1, 130.00, 0.00, 'SYDNEY', '2025-08-04 13:10:42', '2025-08-04 13:10:42'),
(48, 53, 1, 0, 'RICE', 1, 12.00, 0.00, 'Elyza', '2025-08-04 15:45:40', '2025-08-04 15:45:40'),
(50, 54, 1, 0, 'RICE', 1, 12.00, 0.00, 'Elyza', '2025-08-04 15:47:08', '2025-08-04 15:47:08'),
(51, 54, 4, 0, 'SUGAR', 1, 15.00, 0.00, 'Elyza', '2025-08-04 15:47:08', '2025-08-04 15:47:08'),
(52, 54, 3, 0, 'TANDUAY', 1, 150.00, 0.00, 'Elyza', '2025-08-04 15:47:08', '2025-08-04 15:47:08'),
(53, 54, 6, 0, 'RED HORSE BEER', 1, 130.00, 0.00, 'Elyza', '2025-08-04 15:47:08', '2025-08-04 15:47:08'),
(55, 55, 1, 0, 'RICE', 1, 12.00, 0.00, 'Elyza', '2025-08-04 15:47:08', '2025-08-04 15:47:08'),
(56, 55, 4, 0, 'SUGAR', 1, 15.00, 0.00, 'Elyza', '2025-08-04 15:47:08', '2025-08-04 15:47:08'),
(57, 55, 3, 0, 'TANDUAY', 1, 150.00, 0.00, 'Elyza', '2025-08-04 15:47:08', '2025-08-04 15:47:08'),
(58, 55, 6, 0, 'RED HORSE BEER', 1, 130.00, 0.00, 'Elyza', '2025-08-04 15:47:08', '2025-08-04 15:47:08'),
(60, 56, 1, 0, 'RICE', 1, 12.00, 0.00, 'Elyza', '2025-08-04 15:47:08', '2025-08-04 15:47:08'),
(61, 56, 4, 0, 'SUGAR', 1, 15.00, 0.00, 'Elyza', '2025-08-04 15:47:08', '2025-08-04 15:47:08'),
(62, 56, 3, 0, 'TANDUAY', 1, 150.00, 0.00, 'Elyza', '2025-08-04 15:47:08', '2025-08-04 15:47:08'),
(63, 56, 6, 0, 'RED HORSE BEER', 1, 130.00, 0.00, 'Elyza', '2025-08-04 15:47:08', '2025-08-04 15:47:08'),
(65, 57, 1, 0, 'RICE', 1, 12.00, 0.00, 'Elyza', '2025-08-04 15:47:08', '2025-08-04 15:47:08'),
(66, 57, 4, 0, 'SUGAR', 1, 15.00, 0.00, 'Elyza', '2025-08-04 15:47:08', '2025-08-04 15:47:08'),
(67, 57, 3, 0, 'TANDUAY', 1, 150.00, 0.00, 'Elyza', '2025-08-04 15:47:08', '2025-08-04 15:47:08'),
(68, 57, 6, 2, 'RED HORSE BEER', 1, 130.00, 0.00, 'Elyza', '2025-08-04 15:47:08', '2025-08-04 15:47:08'),
(70, 58, 1, 0, 'RICE', 1, 12.00, 0.00, 'Elyza', '2025-08-04 15:47:08', '2025-08-04 15:47:08'),
(71, 58, 4, 0, 'SUGAR', 1, 15.00, 0.00, 'Elyza', '2025-08-04 15:47:08', '2025-08-04 15:47:08'),
(72, 58, 3, 0, 'TANDUAY', 1, 150.00, 0.00, 'Elyza', '2025-08-04 15:47:08', '2025-08-04 15:47:08'),
(73, 58, 6, 0, 'RED HORSE BEER', 1, 130.00, 0.00, 'Elyza', '2025-08-04 15:47:08', '2025-08-04 15:47:08'),
(74, 59, 1, 0, 'RICE', 1, 12.00, 0.00, 'Elyza', '2025-08-04 15:50:11', '2025-08-04 15:50:11'),
(75, 59, 6, 0, 'RED HORSE BEER', 1, 130.00, 0.00, 'Elyza', '2025-08-04 15:50:11', '2025-08-04 15:50:11'),
(76, 60, 1, 0, 'RICE', 1, 12.00, 0.00, 'Elyza', '2025-08-04 15:50:11', '2025-08-04 15:50:11'),
(77, 60, 6, 0, 'RED HORSE BEER', 1, 130.00, 0.00, 'Elyza', '2025-08-04 15:50:11', '2025-08-04 15:50:11'),
(78, 62, 1, 2, 'RICE', 1, 12.00, 0.00, 'Elyza', '2025-08-04 16:00:27', '2025-08-04 16:00:27'),
(79, 63, 6, 2, 'RED HORSE BEER', 1, 130.00, 0.00, 'Elyza', '2025-08-04 16:01:38', '2025-08-04 16:01:38'),
(80, 64, 1, 2, 'RICE', 1, 12.00, 0.00, 'Elyza', '2025-08-04 16:07:24', '2025-08-04 16:07:24'),
(81, 65, 6, 2, 'RED HORSE BEER', 1, 130.00, 0.00, 'Elyza', '2025-08-04 16:08:25', '2025-08-04 16:08:25'),
(82, 66, 1, 2, 'RICE', 1, 12.00, 0.00, 'Elyza', '2025-08-04 16:11:46', '2025-08-04 16:11:46'),
(83, 67, 2, 2, 'FEEDS', 1, 25.00, 0.00, 'Elyza', '2025-08-04 16:23:27', '2025-08-04 16:23:27'),
(84, 68, 2, 2, 'FEEDS', 1, 25.00, 0.00, 'Elyza', '2025-08-04 16:27:55', '2025-08-04 16:27:55'),
(85, 69, 2, 2, 'FEEDS', 1, 25.00, 0.00, 'Elyza', '2025-08-04 16:33:29', '2025-08-04 16:33:29'),
(86, 70, 2, 2, 'FEEDS', 1, 25.00, 0.00, 'Elyza', '2025-08-04 16:35:24', '2025-08-04 16:35:24'),
(87, 71, 2, 2, 'FEEDS', 1, 25.00, 0.00, 'Elyza', '2025-08-04 16:36:02', '2025-08-04 16:36:02'),
(88, 72, 2, 1, 'FEEDS', 1, 25.00, 0.00, 'SYDNEY', '2025-08-14 04:45:28', '2025-08-14 04:45:28'),
(91, 75, 64, 1, 'Coke', 1, 25.00, 0.00, 'Cashier', '2025-08-15 10:34:16', '2025-08-15 10:34:16'),
(92, 76, 65, 1, 'Pepsi', 1, 100.00, 0.00, 'Cashier', '2025-08-15 11:17:08', '2025-08-15 11:17:08'),
(93, 77, 1, 1, 'RICE', 1, 12.00, 0.00, 'Cashier', '2025-08-15 12:19:46', '2025-08-15 12:19:46');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(8, '0001_01_01_000000_create_users_table', 1),
(9, '0001_01_01_000001_create_cache_table', 1),
(10, '0001_01_01_000002_create_jobs_table', 1),
(11, '2025_07_02_075904_create_items_table', 1),
(12, '2025_07_02_080846_add_quantity_to_items_table', 1),
(13, '2025_07_02_094705_add_barcode_to_items_table', 1),
(14, '2025_07_02_134028_add_image_to_items_table', 1),
(15, '2025_07_08_195024_add_image_url_to_items_table', 2),
(16, '2025_07_11_090816_add_role_to_users_table', 3),
(17, '2025_08_02_121552_create_item_sale_table', 4),
(18, '2025_08_02_122040_item_histories_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `item_id` int(255) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `discount` int(11) DEFAULT NULL,
  `cashier` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `user_id`, `customer_id`, `item_id`, `item_name`, `total_price`, `quantity`, `discount`, `cashier`, `created_at`, `updated_at`) VALUES
(48, 1, NULL, NULL, NULL, 12.00, 1, NULL, 'SYDNEY', '2025-08-04 12:52:56', '2025-08-04 12:52:56'),
(49, 1, NULL, NULL, NULL, 130.00, 1, NULL, 'SYDNEY', '2025-08-04 13:07:25', '2025-08-04 13:07:25'),
(50, 1, NULL, NULL, NULL, 130.00, 1, NULL, 'SYDNEY', '2025-08-04 13:10:07', '2025-08-04 13:10:07'),
(51, 1, NULL, NULL, NULL, 130.00, 1, NULL, 'SYDNEY', '2025-08-04 13:10:42', '2025-08-04 13:10:42'),
(52, 1, NULL, NULL, NULL, 26.00, 1, NULL, 'SYDNEY', '2025-08-04 13:46:47', '2025-08-04 13:46:47'),
(53, NULL, NULL, NULL, NULL, 12.00, 1, NULL, 'Elyza', '2025-08-04 15:45:40', '2025-08-04 15:45:40'),
(54, NULL, NULL, NULL, NULL, 26.00, 1, NULL, 'Elyza', '2025-08-04 15:47:08', '2025-08-04 15:47:08'),
(55, NULL, NULL, NULL, NULL, 12.00, 1, NULL, 'Elyza', '2025-08-04 15:47:08', '2025-08-04 15:47:08'),
(56, NULL, NULL, NULL, NULL, 15.00, 1, NULL, 'Elyza', '2025-08-04 15:47:08', '2025-08-04 15:47:08'),
(57, NULL, NULL, NULL, NULL, 150.00, 1, NULL, 'Elyza', '2025-08-04 15:47:08', '2025-08-04 15:47:08'),
(58, NULL, NULL, NULL, NULL, 130.00, 1, NULL, 'Elyza', '2025-08-04 15:47:08', '2025-08-04 15:47:08'),
(59, NULL, NULL, NULL, NULL, 12.00, 1, NULL, 'Elyza', '2025-08-04 15:50:11', '2025-08-04 15:50:11'),
(60, NULL, NULL, NULL, NULL, 130.00, 1, NULL, 'Elyza', '2025-08-04 15:50:11', '2025-08-04 15:50:11'),
(61, NULL, NULL, NULL, NULL, 12.00, 1, NULL, 'Elyza', '2025-08-04 15:57:51', '2025-08-04 15:57:51'),
(62, NULL, NULL, NULL, NULL, 12.00, 1, NULL, 'Elyza', '2025-08-04 16:00:27', '2025-08-04 16:00:27'),
(63, 2, NULL, NULL, NULL, 130.00, 1, NULL, 'Elyza', '2025-08-04 16:01:38', '2025-08-04 16:01:38'),
(64, NULL, NULL, NULL, NULL, 12.00, 1, NULL, 'Elyza', '2025-08-04 16:07:24', '2025-08-04 16:07:24'),
(65, 2, NULL, NULL, NULL, 130.00, 1, NULL, 'Elyza', '2025-08-04 16:08:25', '2025-08-04 16:08:25'),
(66, 2, NULL, NULL, NULL, 12.00, 1, NULL, 'Elyza', '2025-08-04 16:11:46', '2025-08-04 16:11:46'),
(67, 2, NULL, NULL, NULL, 25.00, 1, NULL, 'Elyza', '2025-08-04 16:23:27', '2025-08-04 16:23:27'),
(68, 2, NULL, NULL, NULL, 25.00, 1, NULL, 'Elyza', '2025-08-04 16:27:55', '2025-08-04 16:27:55'),
(69, 2, NULL, NULL, NULL, 25.00, 1, NULL, 'Elyza', '2025-08-04 16:33:29', '2025-08-04 16:33:29'),
(70, 2, NULL, NULL, NULL, 25.00, 1, NULL, 'Elyza', '2025-08-04 16:35:24', '2025-08-04 16:35:24'),
(71, 2, NULL, NULL, NULL, 25.00, 1, NULL, 'Elyza', '2025-08-04 16:36:02', '2025-08-04 16:36:02'),
(72, 1, NULL, NULL, NULL, 25.00, 1, NULL, 'SYDNEY', '2025-08-14 04:45:28', '2025-08-14 04:45:28'),
(73, 1, NULL, NULL, NULL, 160.00, 1, NULL, 'SYDNEY', '2025-08-14 05:04:17', '2025-08-14 05:04:17'),
(74, 1, NULL, NULL, NULL, 25.00, 1, NULL, 'Cashier', '2025-08-15 09:21:09', '2025-08-15 09:21:09'),
(75, 1, NULL, NULL, NULL, 25.00, 1, NULL, 'Cashier', '2025-08-15 10:34:15', '2025-08-15 10:34:15'),
(76, 1, NULL, NULL, NULL, 100.00, 1, NULL, 'Cashier', '2025-08-15 11:17:08', '2025-08-15 11:17:08'),
(77, 1, NULL, NULL, NULL, 12.00, 1, NULL, 'Cashier', '2025-08-15 12:19:46', '2025-08-15 12:19:46');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('UYRbdgQlnsxDEdms9M36Mjl8sRV29yYtisVo4YyK', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaDJvcGZWT2pQNHU5cklYYXNjQUdKMzRBY2dNcnQweVBrT1JSNmh0bCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9sb2NhbGhvc3QvaXRlbV9oaXN0b3JpZXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjExOiJpbXBvcnRfZGF0YSI7YTozOntpOjA7YTo1OntpOjA7czo0OiJDb2tlIjtpOjE7ZDoxMjM0NTY3ODk7aToyO2Q6MjU7aTozO2Q6MTAwO2k6NDtzOjEwOiIyMDI1LTE1LTA4Ijt9aToxO2E6NTp7aTowO3M6NToiUGVwc2kiO2k6MTtkOjQxNTQ1NDtpOjI7ZDoxMDA7aTozO2Q6MTAwO2k6NDtzOjEwOiIyMDI1LTE1LTA4Ijt9aToyO2E6NTp7aTowO3M6NDoiUmljZSI7aToxO2Q6NjU0NTQ4ODtpOjI7ZDo1MjtpOjM7ZDoxMDA7aTo0O3M6MTA6IjIwMjUtMTUtMDgiO319fQ==', 1755265776);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'cashier'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`) VALUES
(1, 'SYDNEY', 'sydney07nesnia@gmail.com', '2025-08-04 11:37:01', '$2y$12$9CFhv11n/iSV5Gp4bDQSq.Kxp5O8hyWqB7B77LaHoEXsIBzRsGwpm', NULL, NULL, '2025-08-04 11:40:55', 'cashier'),
(2, 'Elyza', 'elyza.trina@gmail.com', NULL, '$2y$12$QvnNuUEu6MFDXo4DQHQf3OKqJyxwmLeCcAg5C/RyIAth9mKu.ZycS', NULL, '2025-08-04 11:46:32', '2025-08-04 15:53:20', 'cashier'),
(3, 'zyler', 'zyler@gmail.com', NULL, '$2y$12$skdPADWs24G/slXo51/oxOm.y.NkhO4xLnqTt8Pd8eaFhubDwjMdq', NULL, '2025-08-04 12:01:49', '2025-08-04 12:01:49', 'cashier');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `items_barcode_unique` (`barcode`);

--
-- Indexes for table `item_histories`
--
ALTER TABLE `item_histories`
  ADD PRIMARY KEY (`item_id_his`),
  ADD KEY `item_histories_id_foreign` (`id`),
  ADD KEY `item_histories_user_id_foreign` (`user_id`);

--
-- Indexes for table `item_sale`
--
ALTER TABLE `item_sale`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_sale_sale_id_foreign` (`sale_id`),
  ADD KEY `item_sale_item_id_foreign` (`item_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `item_histories`
--
ALTER TABLE `item_histories`
  MODIFY `item_id_his` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `item_sale`
--
ALTER TABLE `item_sale`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `item_histories`
--
ALTER TABLE `item_histories`
  ADD CONSTRAINT `item_histories_id_foreign` FOREIGN KEY (`id`) REFERENCES `items` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `item_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `item_sale`
--
ALTER TABLE `item_sale`
  ADD CONSTRAINT `item_sale_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_sale_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
