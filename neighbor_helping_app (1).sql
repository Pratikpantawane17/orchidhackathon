-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Apr 12, 2025 at 11:24 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `neighbor_helping_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `advertisements`
--

DROP TABLE IF EXISTS `advertisements`;
CREATE TABLE IF NOT EXISTS `advertisements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ad_type` enum('shop','job') NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `contact` varchar(255) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `escrow`
--

DROP TABLE IF EXISTS `escrow`;
CREATE TABLE IF NOT EXISTS `escrow` (
  `id` int NOT NULL AUTO_INCREMENT,
  `task_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('Pending','In Progress','Completed','Disputed') DEFAULT 'Pending',
  `task_poster_id` int NOT NULL,
  `service_provider_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  KEY `task_poster_id` (`task_poster_id`),
  KEY `service_provider_id` (`service_provider_id`)
);

--
-- Dumping data for table `escrow`
--

INSERT INTO `escrow` (`id`, `task_id`, `amount`, `status`, `task_poster_id`, `service_provider_id`) VALUES
(1, 1, 10.00, 'Pending', 0, NULL),
(2, 1, 56.00, 'Pending', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `request_id` int NOT NULL,
  `sender_id` int NOT NULL,
  `sender_name` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_sender_id` (`sender_id`),
  KEY `fk_request_id` (`request_id`)
);

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `request_id`, `sender_id`, `sender_name`, `content`, `created_at`) VALUES
(1, 1, 1, 'sanskar sontakke', 'hii', '2024-12-22 13:53:02'),
(2, 1, 2, 'ajinkya sontakke', 'hii', '2024-12-22 13:55:17'),
(3, 1, 3, '', 'hii', '2024-12-22 16:29:41'),
(4, 1, 3, '', 'i can help\r\n', '2024-12-22 16:29:53'),
(5, 2, 3, '', 'i can help\r\n', '2024-12-22 16:30:48'),
(6, 1, 1, '', 'hii evryone', '2024-12-22 16:58:53'),
(7, 3, 1, '', 'i can help you', '2024-12-22 17:01:32'),
(8, 6, 1, '', 'hii', '2024-12-23 07:12:45'),
(9, 1, 3, '', 'hii', '2024-12-25 08:41:40'),
(10, 1, 3, '', 'my name is', '2024-12-25 08:41:49'),
(11, 3, 6, '', 'hii', '2024-12-25 12:31:14'),
(12, 3, 6, '', 'hii', '2024-12-25 12:31:39'),
(13, 3, 6, '', 'hii', '2024-12-25 12:31:56'),
(14, 3, 1, '', 'hii', '2024-12-25 12:32:23'),
(15, 3, 1, '', 'hii', '2024-12-25 12:32:38'),
(16, 3, 3, '', 'hii', '2024-12-25 12:33:59'),
(17, 7, 3, '', 'hii', '2024-12-25 13:59:38'),
(18, 7, 3, '', 'my name is sanskar', '2024-12-25 13:59:49'),
(19, 3, 5, '', 'hii', '2024-12-25 15:04:59'),
(20, 3, 5, '', 'hii', '2024-12-25 15:05:14'),
(21, 9, 5, '', 'hii', '2024-12-27 08:31:05'),
(22, 6, 3, '', 'gii', '2024-12-30 13:15:20'),
(23, 14, 1, '', 'ff', '2025-01-05 17:20:44'),
(24, 14, 1, '', 'xs', '2025-01-05 17:20:50'),
(25, 13, 1, '', 'aa', '2025-01-05 17:22:48'),
(26, 14, 3, '', 'hii', '2025-02-23 02:42:17'),
(27, 14, 3, '', 'hii', '2025-03-26 11:28:12'),
(28, 16, 3, '', 'ddw', '2025-03-27 04:21:27'),
(29, 16, 5, '', 'hhiii', '2025-04-10 08:10:35'),
(30, 16, 14, '', 'jjjj', '2025-04-10 08:11:15'),
(31, 23, 14, '', 'give me no', '2025-04-10 08:13:19');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
);

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `content`, `created_at`) VALUES
(1, 3, 'Your request has been accepted!', '2024-12-24 05:45:57'),
(2, 3, 'Your request has been accepted!', '2024-12-24 05:45:59'),
(3, 3, 'Your request has been accepted!', '2024-12-24 05:46:00'),
(4, 3, 'Your request has been accepted!', '2024-12-24 05:46:02'),
(5, 3, 'Your request has been accepted!', '2024-12-24 05:46:04'),
(6, 3, 'Your request has been accepted!', '2024-12-24 05:46:11'),
(7, 3, 'Your request has been accepted!', '2024-12-24 05:47:28'),
(8, 3, 'Your request has been accepted!', '2024-12-24 05:47:29'),
(9, 3, 'Your request has been accepted!', '2024-12-24 05:47:31'),
(10, 1, 'Your request has been accepted!', '2024-12-24 05:48:00'),
(11, 1, 'Your request has been accepted!', '2024-12-24 05:48:01'),
(12, 3, 'Your request has been accepted!', '2024-12-24 05:48:33'),
(13, 3, 'Your request has been accepted!', '2024-12-24 05:48:38'),
(14, 3, 'Your request has been accepted!', '2024-12-24 05:48:39'),
(15, 3, 'Your request has been accepted!', '2024-12-24 05:48:40'),
(16, 3, 'Your request has been accepted!', '2024-12-24 05:48:41'),
(17, 3, 'Your request has been accepted!', '2024-12-24 05:48:42'),
(18, 3, 'Your request has been accepted by user 1!', '2024-12-24 05:54:21'),
(19, 5, 'Your request has been accepted by user 3!', '2024-12-25 12:17:00'),
(20, 5, 'Your request has been accepted by user 1!', '2024-12-25 12:17:29'),
(21, 5, 'Your request has been accepted by user 1!', '2024-12-25 12:17:44'),
(22, 5, 'Your request has been accepted by user 1!', '2024-12-25 12:17:46'),
(23, 5, 'Your request has been accepted by user 1!', '2024-12-25 12:17:48'),
(24, 5, 'Your request has been accepted by user 1!', '2024-12-25 12:17:55'),
(25, 5, 'Your request has been accepted by user 1!', '2024-12-25 12:17:56'),
(26, 5, 'Your request has been accepted by user 1!', '2024-12-25 12:17:57'),
(27, 5, 'Your request has been accepted by user 1!', '2024-12-25 12:17:57'),
(28, 5, 'Your request has been accepted by user 1!', '2024-12-25 12:17:58'),
(29, 5, 'Your request has been accepted by user 1!', '2024-12-25 12:18:10'),
(30, 5, 'Your request has been accepted by user 1!', '2024-12-25 12:18:18'),
(31, 5, 'Your request has been accepted by user 6!', '2024-12-25 12:19:42'),
(32, 5, 'Your request has been accepted by user 6!', '2024-12-25 12:19:51'),
(33, 5, 'Your request has been accepted by user 6!', '2024-12-25 12:19:54'),
(34, 5, 'Your request has been accepted by user 6!', '2024-12-25 12:19:58'),
(35, 5, 'Your request has been accepted by user 6!', '2024-12-25 12:20:12'),
(36, 5, 'Your request has been accepted by user 6!', '2024-12-25 12:20:16'),
(37, 5, 'Your request has been accepted by user 6!', '2024-12-25 12:25:40'),
(38, 3, 'Your request has been accepted by user 6!', '2024-12-25 12:25:59'),
(39, 3, 'Your request has been accepted by user 6!', '2024-12-25 12:26:05'),
(40, 3, 'Your request has been accepted by user 6!', '2024-12-25 12:26:06'),
(41, 5, 'Your request has been accepted by user 6!', '2024-12-25 12:26:10'),
(42, 3, 'Your request has been accepted by user 6!', '2024-12-25 12:28:18'),
(43, 3, 'Your request has been accepted by user 6!', '2024-12-25 12:28:20'),
(44, 3, 'Your request has been accepted by user 3!', '2024-12-25 12:33:31'),
(45, 3, 'Your request has been accepted by user 3!', '2024-12-25 12:33:37'),
(46, 3, 'Your request has been accepted by user 3!', '2024-12-25 12:33:39'),
(47, 3, 'Your request has been accepted by user 3!', '2024-12-25 12:38:44'),
(48, 3, 'Your request has been accepted by user 1!', '2024-12-25 12:38:49'),
(49, 3, 'Your request has been accepted by user 3!', '2024-12-25 12:38:51'),
(50, 3, 'Your request has been accepted by user 3!', '2024-12-25 12:39:02'),
(51, 3, 'Your request has been accepted by user 3!', '2024-12-25 12:39:03'),
(52, 3, 'Your request has been accepted by user 1!', '2024-12-25 12:39:09'),
(53, 3, 'Your request has been accepted by user 3!', '2024-12-25 12:39:29'),
(54, 3, 'Your request has been accepted by user 1!', '2024-12-25 12:39:31'),
(55, 3, 'Your request has been accepted by user 1!', '2024-12-25 12:39:36'),
(56, 3, 'Your request has been accepted by user 3!', '2024-12-25 12:41:27'),
(57, 3, 'Your request has been accepted by user 1!', '2024-12-25 12:41:30'),
(58, 3, 'Your request has been accepted by user 3!', '2024-12-25 12:41:42'),
(59, 3, 'Your request has been accepted by user 1!', '2024-12-25 12:43:54'),
(60, 3, 'Your request has been accepted by user 3!', '2024-12-25 12:46:54'),
(61, 3, 'Your request has been accepted by user 1!', '2024-12-25 12:47:03'),
(62, 3, 'Your request has been accepted by user 3!', '2024-12-25 12:47:08'),
(63, 3, 'Your request has been accepted by user 1!', '2024-12-25 12:47:13'),
(64, 3, 'Your request has been accepted by user 3!', '2024-12-25 12:47:22'),
(65, 5, 'Your request has been accepted by user 3!', '2024-12-25 12:48:23'),
(66, 5, 'Your request has been accepted by user 1!', '2024-12-25 12:48:25'),
(67, 5, 'Your request has been accepted by user 3!', '2024-12-25 12:48:38'),
(68, 5, 'Your request has been accepted by user 1!', '2024-12-25 12:49:13'),
(69, 5, 'Your request has been accepted by user 3!', '2024-12-25 13:59:50'),
(70, 3, 'Your request has been accepted by user ID 5!', '2024-12-25 15:01:41'),
(71, 1, 'Your request has been accepted by user ID 5!', '2024-12-25 15:01:59'),
(72, 1, 'Your request has been accepted by user ID 3!', '2024-12-30 13:15:25'),
(73, 1, 'Your request has been accepted by user ID 3!', '2025-01-05 11:33:37'),
(74, 1, 'Your request has been accepted by user ID 1!', '2025-01-05 17:18:58'),
(75, 3, 'Your request has been accepted by user ID 3!', '2025-03-27 04:21:28'),
(76, 14, 'Your request has been accepted by user ID 14!', '2025-04-10 08:12:55');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `request_id` int NOT NULL,
  `stripe_payment_intent_id` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','succeeded','failed') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `type` varchar(50) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
);

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `description`, `type`, `image`, `user_id`) VALUES
(7, 'hhd', 'dede', 'Advertisement', '6787968570561_c1.jpg', 8),
(6, 'jansevak', '20% discount', 'Advertisement', '6775804c7a943_images.jpg', 3),
(5, 'delivery boy', 'i need a delivery boy\r\ncontact detail:-8788946196', 'Job', '6772ddb31266e_retail-shop.jpg', NULL),
(14, 'pepsi', 'dw', 'Advertisement', '67e4d27c0d962_download.jpeg', 3),
(9, 'pepsi', '20% off on pepsi', 'Advertisement', '67e427939a1b8_download.jpeg', 3),
(10, 'pepsi', '20% off on pepsi', 'Advertisement', '67e4cd62a1903_download.jpeg', 3),
(11, 'pepsi', '20% off on pepsi', 'Advertisement', '67e4cdf66b12c_download.jpeg', 3),
(12, 'pepsi', '20% off on pepsi', 'Advertisement', '67e4cf7745de7_download.jpeg', 3),
(13, 'pepsi', '20% off on pepsi', 'Advertisement', '67e4d00a83762_download.jpeg', 3);

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

DROP TABLE IF EXISTS `requests`;
CREATE TABLE IF NOT EXISTS `requests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(50) NOT NULL,
  `user_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('pending','in_progress','completed') DEFAULT 'pending',
  `helper_id` int DEFAULT NULL,
  `acceptor_id` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `acceptor_id` (`acceptor_id`)
);

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `title`, `description`, `category`, `user_id`, `created_at`, `latitude`, `longitude`, `amount`, `status`, `helper_id`, `acceptor_id`, `updated_at`) VALUES
(16, 'i need woodcutter', 'i need woodcutter of my garden, any one interested contact me ', 'other', 3, '2025-02-23 02:44:25', 16.8456, 74.6016, 0.00, 'pending', NULL, 3, '2025-03-27 04:21:28'),
(14, 'gardan maintanace', 'I require the services of  5 people for garden maintenance, including tree trimming and cutting.\r\naddress:near xyz complex,abc road,pqr', 'other', 1, '2025-01-05 10:22:01', 19.1603, 77.3094, 0.00, 'pending', NULL, 3, '2025-01-05 11:33:37'),
(13, 'grocerry', 'i want the grocerry. at can give you 50rs.\r\nlist:\r\nCarrot-1/4kg\r\nSpinach-1/4kg\r\nPotato-1kg', 'groceries', 1, '2025-01-05 10:12:18', 19.1603, 77.3094, 0.00, 'pending', NULL, 1, '2025-01-05 17:18:58'),
(23, 'ww', 'ss', 'groceries', 14, '2025-04-10 08:12:22', 19.1631, 77.3144, 0.00, 'pending', NULL, 14, '2025-04-10 08:12:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `role` enum('poster','provider') NOT NULL,
  `wallet_balance` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
);

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `profile_photo`, `role`, `wallet_balance`) VALUES
(1, 'sanskar sontakke', 'sanskarsontakke06@gmail.com', '$2y$10$7HqyTUi13eOO7SPwJuwVeeW8pbLnYWNtBINBlyivm46bRrpPICdoC', 'uploads/photo_677b7fc5a49ec3.03002090.jpg', 'poster', 0.00),
(2, 'ajinkya sontakke', 'sanskarsontakke123@gmail.com', '$2y$10$8h0lmS1CuhvBfkIocB2U8uz848m4OpVcbBhSDG/7Uauqnja0JI/zK', NULL, 'poster', 0.00),
(3, 'samiksha sontakke', 's@gmail.com', '$2y$10$zyORQHlj0xBX/V1jxqnEbeR3VBOrTtIrGVr2OXEH3HM7KKPmi4Ani', 'uploads/photo_67e4d02dc120f0.60927234.jpg', 'poster', 0.00),
(12, 'abc', 'abc@gmail.com', '$2y$10$BeVA0oA6XQ.A6yt7vEgW0..ENSweu09U9p.Rt8RxfXcwz662Dq8AG', NULL, 'poster', 0.00),
(5, 'balaji', 'b@gmail.com', '$2y$10$EmR118.wAGVEjbH/J4uPvOsmf2Xzi7wWzjgmJbJCAQl4KGXzp8xEi', NULL, 'poster', 0.00),
(6, 'shakuntala', 'sa@gmail.com', '$2y$10$joiuC9XA2Z8.7YS8s5A36./chBIdPc.seFYlk9aQb.AQmHfZYK11S', NULL, 'poster', 0.00),
(7, 'sneha', 'sn@gmail.com', '$2y$10$P.6Pleg/eu9GlfhSvz387.zWLD.60lOKS6qZrLz8forllaZKW2JK6', NULL, 'poster', 0.00),
(8, 'siddheshwar', 'sidhh@gmail.com', '$2y$10$VoVZRMvFhW9pK5N6mxcUlOIga.XMBo3LxnmRTGIMHfHARHS8F4EXS', NULL, 'poster', 0.00),
(9, 'Sanskar Balaji Sontakke', 'pqr@gmail.com', '$2y$10$vKrdjATcApDJsj4j8xVlbOh0c6vodDIuEAVnWPSPk0yDa7ixQFrKe', NULL, 'poster', 0.00),
(11, 'xyz', 'xyz@gmail.com', '$2y$10$w3PlEjQvqVkk5vpR.JHlSOU0goe8f0bY03ByJAkVZGwfzQfcQR92C', NULL, 'poster', 0.00),
(13, 'sanskar sontakke', 'sanskarsontakke123s@gmail.com', '$2y$10$jA8pfEu8bbez.09icMEafOGRDfqGSYS2UNWMX41E05zjUoFGXkIPW', NULL, 'poster', 0.00),
(14, 'pratik', 'p@gmail.com', '$2y$10$XqIbyxOBEGVUy10wcWe7rOwHH9DJchELiSCRxZB3lfNPgCCIoKYKu', NULL, 'poster', 0.00);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
