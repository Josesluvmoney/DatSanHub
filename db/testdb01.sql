-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 03, 2025 at 05:33 PM
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
-- Database: `testdb01`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `message` text COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `phone`, `subject`, `message`, `created_at`) VALUES
(1, '12', '312@gmail.com', '93128', '7812', 'jeue', '2025-01-03 16:18:04'),
(2, 'Tấn Dũng', 'dungh@gmail.com', '832818@gmail.com', '93291', 'TEESSTT', '2025-01-03 16:19:51'),
(3, 'Hiệp', 'hiepngoc@gmail.com', '03921738281', 'Test ', '01238jjaaa', '2025-01-03 16:22:32'),
(4, 'Hoa', 'NguyenHoa@gmail.com', '03921787631', 'teacup', '112:@3z', '2025-01-03 16:24:38'),
(5, 'à', 'Hao@gmail.com', '03921877', '73128', 'hfsjks', '2025-01-03 16:27:12'),
(6, 'Test', 'Test91@a.com', '083176661', 'asssa', 'hic', '2025-01-03 16:31:11'),
(7, 'testlanN', 'testlanN@gmail.copm', '0128381287', 'huhu', 'làm ơn đc đi', '2025-01-03 16:32:12'),
(8, 'Lần Cuối', 'huhu@gmail.com', '098319890', '088jjj', 'HICC', '2025-01-03 16:34:03'),
(9, 'Lần cuối nè', '213@gmail.com', '08371288', 'jụak;', '123213', '2025-01-03 16:34:27'),
(10, 'huỳnh dũng', '200@gmail.com', '902318', 'jfj', '9231-8', '2025-01-03 16:40:29'),
(11, 'huỳnh dũng', '200@gmail.com', '902318', 'jfj', '9231-8', '2025-01-03 16:41:13'),
(12, 'fa', 'fa@mgiua.com', '1922918', '0fhh', '0129\r\n', '2025-01-03 16:41:36'),
(13, '213', 'aoma@gmal.com', '0977120', '213', 'àa', '2025-01-03 16:43:46'),
(14, 'huyhnh01', '92j@gmail.com', '9123889-8', 'iuu', 'uii', '2025-01-03 16:44:54'),
(15, 'huyhnh01', '92j@gmail.com', '9123889-8', 'iuu', 'uii', '2025-01-03 16:46:32'),
(16, '12', '123@a.com', '902138', '93128', 'ids', '2025-01-03 16:46:43');

-- --------------------------------------------------------

--
-- Table structure for table `reg`
--

DROP TABLE IF EXISTS `reg`;
CREATE TABLE IF NOT EXISTS `reg` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fullname` varchar(255) COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `password` varchar(32) COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `reg`
--

INSERT INTO `reg` (`id`, `fullname`, `phone`, `password`, `created_at`) VALUES
(2, 'Huỳnh Tấn Dũng', '0963922597', 'c4ca4238a0b923820dcc509a6f75849b', '2025-01-03 17:27:32');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
