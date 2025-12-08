-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 08, 2025 at 02:04 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pro1014`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `customer_id` int DEFAULT NULL,
  `driver_id` int DEFAULT NULL COMMENT 'ID tài xế',
  `version_id` int DEFAULT NULL,
  `booking_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `departure_date` date DEFAULT NULL,
  `original_price` decimal(15,2) DEFAULT NULL,
  `final_price` decimal(15,2) NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `discount_note` text,
  `status` varchar(50) DEFAULT 'pending',
  `notes` text,
  `internal_notes` text,
  `created_by` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `departure_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `tour_id`, `customer_id`, `driver_id`, `version_id`, `booking_date`, `departure_date`, `original_price`, `final_price`, `total_price`, `discount_note`, `status`, `notes`, `internal_notes`, `created_by`, `created_at`, `updated_at`, `departure_id`) VALUES
(32, 1, 5, NULL, 7, '2025-12-10 00:00:00', '2025-04-25', NULL, 116100000.00, 116100000.00, NULL, 'da_coc', '', NULL, 1, '2025-12-06 22:50:06', '2025-12-08 08:28:27', NULL),
(33, 1, 6, NULL, NULL, '2025-12-11 14:30:00', '2025-04-25', NULL, 103200000.00, 103200000.00, NULL, 'da_coc', NULL, NULL, 1, '2025-12-06 22:50:06', '2025-12-06 22:50:06', NULL),
(34, 2, 7, NULL, NULL, '2025-12-12 00:00:00', '2025-12-28', NULL, 62300000.00, 62300000.00, NULL, 'da_coc', '', NULL, 1, '2025-12-06 22:50:06', '2025-12-07 01:23:57', NULL),
(35, 2, 8, NULL, NULL, '2025-12-13 00:00:00', '2025-12-28', NULL, 53400000.00, 53400000.00, NULL, 'cho_xac_nhan', '', NULL, 1, '2025-12-06 22:50:06', '2025-12-07 01:24:55', NULL),
(36, 2, 9, NULL, NULL, '2025-12-14 11:20:00', '2025-12-28', NULL, 44500000.00, 44500000.00, NULL, 'da_coc', NULL, NULL, 1, '2025-12-06 22:50:06', '2025-12-06 22:50:06', NULL),
(37, 3, 10, NULL, NULL, '2025-12-15 10:00:00', '2026-01-10', NULL, 130800000.00, 130800000.00, NULL, 'da_coc', NULL, NULL, 1, '2025-12-06 22:50:06', '2025-12-06 22:50:06', NULL),
(38, 3, 11, NULL, NULL, '2025-12-16 15:30:00', '2026-01-10', NULL, 87200000.00, 87200000.00, NULL, 'cho_xac_nhan', NULL, NULL, 1, '2025-12-06 22:50:06', '2025-12-06 22:50:06', NULL),
(39, 4, 12, NULL, NULL, '2025-12-17 09:00:00', '2026-02-15', NULL, 99000000.00, 99000000.00, NULL, 'da_coc', NULL, NULL, 1, '2025-12-06 22:50:06', '2025-12-06 22:50:06', NULL),
(40, 4, 13, NULL, NULL, '2025-12-18 14:15:00', '2026-02-15', NULL, 69300000.00, 69300000.00, NULL, 'cho_xac_nhan', NULL, NULL, 1, '2025-12-06 22:50:06', '2025-12-06 22:50:06', NULL),
(41, 5, 14, NULL, NULL, '2025-12-19 10:30:00', '2026-03-20', NULL, 71100000.00, 71100000.00, NULL, 'da_coc', NULL, NULL, 1, '2025-12-06 22:50:06', '2025-12-06 22:50:06', NULL),
(43, 7, 6, NULL, NULL, '2025-12-21 11:00:00', '2026-04-10', NULL, 47200000.00, 47200000.00, NULL, 'da_coc', NULL, NULL, 1, '2025-12-06 22:50:06', '2025-12-06 22:50:06', NULL),
(44, 7, 7, NULL, NULL, '2025-12-22 13:45:00', '2026-04-10', NULL, 35400000.00, 35400000.00, NULL, 'da_coc', NULL, NULL, 1, '2025-12-06 22:50:06', '2025-12-07 00:52:03', NULL),
(45, 7, 8, 1, NULL, '2025-12-23 00:00:00', '2026-04-10', NULL, 22125000.00, 22125000.00, NULL, 'da_coc', '', NULL, 1, '2025-12-06 22:50:07', '2025-12-08 07:46:02', NULL),
(46, 2, 15, NULL, NULL, '2025-12-23 00:00:00', NULL, NULL, 8900000.00, 8900000.00, NULL, 'cho_xac_nhan', '', NULL, 1, '2025-12-08 08:18:48', '2025-12-08 08:18:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `booking_customers`
--

CREATE TABLE `booking_customers` (
  `id` int NOT NULL,
  `booking_id` int NOT NULL,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `id_card` varchar(50) DEFAULT NULL,
  `special_request` text,
  `room_type` varchar(100) DEFAULT NULL,
  `is_foc` tinyint DEFAULT '0',
  `passenger_type` enum('adult','child','infant') DEFAULT 'adult',
  `payment_status` enum('unpaid','partial','paid') DEFAULT 'unpaid',
  `payment_amount` decimal(15,2) DEFAULT '0.00',
  `payment_date` datetime DEFAULT NULL,
  `checkin_status` enum('not_arrived','checked_in','absent') DEFAULT 'not_arrived',
  `checkin_time` datetime DEFAULT NULL,
  `checkin_location` varchar(255) DEFAULT NULL,
  `checkin_notes` text,
  `checked_by` int DEFAULT NULL COMMENT 'User ID của HDV check-in'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `booking_customers`
--

INSERT INTO `booking_customers` (`id`, `booking_id`, `full_name`, `gender`, `birth_date`, `phone`, `id_card`, `special_request`, `room_type`, `is_foc`, `passenger_type`, `payment_status`, `payment_amount`, `payment_date`, `checkin_status`, `checkin_time`, `checkin_location`, `checkin_notes`, `checked_by`) VALUES
(122, 32, 'Nguyễn Minh Khách', 'Nam', '1985-05-15', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'checked_in', '2025-12-08 07:48:32', NULL, NULL, 3),
(123, 32, 'Trần Thị B', 'Nữ', '1987-08-20', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'checked_in', '2025-12-08 07:48:32', NULL, NULL, 3),
(124, 32, 'Lê Văn C', 'Nam', '1990-03-10', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'checked_in', '2025-12-08 07:48:32', NULL, NULL, 3),
(125, 32, 'Phạm Thị D', 'Nữ', '1988-12-05', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'checked_in', '2025-12-08 07:48:32', NULL, NULL, 3),
(126, 32, 'Hoàng Văn E', 'Nam', '1992-06-18', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'checked_in', '2025-12-08 07:48:32', NULL, NULL, 3),
(127, 32, 'Vũ Thị F', 'Nữ', '1989-09-25', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'checked_in', '2025-12-08 07:48:32', NULL, NULL, 3),
(128, 32, 'Đỗ Văn G', 'Nam', '2010-04-12', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'checked_in', '2025-12-08 07:48:32', NULL, NULL, 3),
(129, 32, 'Bùi Thị H', 'Nữ', '2012-07-08', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'checked_in', '2025-12-08 07:48:32', NULL, NULL, 3),
(130, 32, 'Phan Văn I', 'Nam', '2011-11-30', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'checked_in', '2025-12-08 07:48:32', NULL, NULL, 3),
(131, 32, 'Võ Thị K', 'Nữ', '2013-02-14', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'checked_in', '2025-12-08 07:48:32', NULL, NULL, 3),
(132, 33, 'Trần Thị Khách', 'Nữ', '1983-01-22', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'checked_in', '2025-12-08 07:48:32', NULL, NULL, 3),
(133, 33, 'Đinh Thị M', 'Nữ', '1984-05-17', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'checked_in', '2025-12-08 07:48:32', NULL, NULL, 3),
(134, 33, 'Trương Văn N', 'Nam', '1991-08-09', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'checked_in', '2025-12-08 07:48:32', NULL, NULL, 3),
(135, 33, 'Lý Thị O', 'Nữ', '1986-11-28', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'checked_in', '2025-12-08 07:48:32', NULL, NULL, 3),
(136, 33, 'Mai Văn P', 'Nam', '1993-03-15', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'checked_in', '2025-12-08 07:48:32', NULL, NULL, 3),
(137, 33, 'Hồ Thị Q', 'Nữ', '2014-06-20', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'checked_in', '2025-12-08 07:48:32', NULL, NULL, 3),
(138, 33, 'Tô Văn R', 'Nam', '2015-09-11', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'checked_in', '2025-12-08 07:48:32', NULL, NULL, 3),
(139, 33, 'Cao Thị S', 'Nữ', '2016-12-03', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'checked_in', '2025-12-08 07:48:32', NULL, NULL, 3),
(153, 36, 'Bùi Hải Khách', 'Nam', '1986-01-07', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(154, 36, 'Gia Thị HH', 'Nữ', '1988-04-19', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(155, 36, 'Ân Văn II', 'Nam', '1992-08-31', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(156, 36, 'Bình Thị JJ', 'Nữ', '2015-11-12', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(157, 36, 'Cát Văn KK', 'Nam', '2016-02-24', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(158, 37, 'Võ Thanh Khách', 'Nam', '1984-03-16', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(159, 37, 'Én Thị MM', 'Nữ', '1986-06-28', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(160, 37, 'Phong Văn NN', 'Nam', '1990-09-10', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(161, 37, 'Giang Thị OO', 'Nữ', '1988-12-22', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(162, 37, 'Hải Văn PP', 'Nam', '1992-03-05', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(163, 37, 'Ích Thị QQ', 'Nữ', '1989-06-17', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(164, 37, 'Kiên Văn RR', 'Nam', '1991-09-29', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(165, 37, 'Linh Thị SS', 'Nữ', '1993-12-11', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(166, 37, 'Minh Văn TT', 'Nam', '2010-04-23', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(167, 37, 'Ngân Thị UU', 'Nữ', '2012-07-05', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(168, 37, 'Oanh Văn VV', 'Nam', '2014-10-17', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(169, 37, 'Phúc Thị WW', 'Nữ', '2016-01-29', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(170, 38, 'Đỗ Minh Khách', 'Nam', '1985-05-11', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(171, 38, 'Rạng Thị YY', 'Nữ', '1987-08-23', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(172, 38, 'Sang Văn ZZ', 'Nam', '1991-11-05', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(173, 38, 'Tâm Thị AAA', 'Nữ', '1989-02-17', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(174, 38, 'Uyên Văn BBB', 'Nam', '1993-05-29', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(175, 38, 'Vân Thị CCC', 'Nữ', '2011-08-10', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(176, 38, 'Xuân Văn DDD', 'Nam', '2013-11-22', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(177, 38, 'Yến Thị EEE', 'Nữ', '2015-02-04', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(178, 39, 'Hoàng Lan Khách', 'Nữ', '1982-07-13', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(179, 39, 'Bảo Thị GGG', 'Nữ', '1984-10-25', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(180, 39, 'Cường Văn HHH', 'Nam', '1988-01-07', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(181, 39, 'Dung Thị III', 'Nữ', '1990-04-19', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(182, 39, 'Đức Văn JJJ', 'Nam', '1992-07-31', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(183, 39, 'Hà Thị KKK', 'Nữ', '1989-10-12', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(184, 39, 'Hùng Văn LLL', 'Nam', '2010-01-24', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(185, 39, 'Lan Thị MMM', 'Nữ', '2012-04-06', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(186, 39, 'Long Văn NNN', 'Nam', '2014-07-18', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(187, 39, 'Mai Thị OOO', 'Nữ', '2016-10-30', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(188, 40, 'Phan Thị Khách', 'Nữ', '1986-02-11', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(189, 40, 'Nga Thị QQQ', 'Nữ', '1988-05-23', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(190, 40, 'Phát Văn RRR', 'Nam', '1992-08-05', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(191, 40, 'Quyên Thị SSS', 'Nữ', '1990-11-17', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(192, 40, 'Sơn Văn TTT', 'Nam', '2011-02-28', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(193, 40, 'Thảo Thị UUU', 'Nữ', '2013-06-10', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(194, 40, 'Tuấn Văn VVV', 'Nam', '2015-09-22', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(195, 41, 'Vũ Ngọc Khách', 'Nam', '1983-04-15', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(196, 41, 'Vân Thị XXX', 'Nữ', '1985-07-27', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(197, 41, 'Vinh Văn YYY', 'Nam', '1989-10-09', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(198, 41, 'Xuân Thị ZZZ', 'Nữ', '1991-01-21', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(199, 41, 'Yên Văn AAAA', 'Nam', '1993-04-03', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(200, 41, 'Anh Thị BBBB', 'Nữ', '2010-07-15', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(201, 41, 'Bình Văn CCCC', 'Nam', '2012-10-27', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(202, 41, 'Chi Thị DDDD', 'Nữ', '2014-02-08', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(203, 41, 'Duy Văn EEEE', 'Nam', '2016-05-20', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(211, 43, 'Trần Thị Khách', 'Nữ', '1984-05-14', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(212, 43, 'Thủy Thị NNNN', 'Nữ', '1986-08-26', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(213, 43, 'Trí Văn OOOO', 'Nam', '1990-11-08', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(214, 43, 'Vy Thị PPPP', 'Nữ', '1988-02-20', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(215, 43, 'Đạt Văn QQQQ', 'Nam', '2010-05-02', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(216, 43, 'Hồng Thị RRRR', 'Nữ', '2012-08-14', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(217, 43, 'Khánh Văn SSSS', 'Nam', '2014-11-26', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(218, 43, 'Ly Thị TTTT', 'Nữ', '2016-03-09', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(219, 44, 'Lê Văn Khách', 'Nam', '1985-09-16', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(220, 44, 'Ngọc Thị VVVV', 'Nữ', '1987-12-28', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(221, 44, 'Phong Văn WWWW', 'Nam', '1991-04-10', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(222, 44, 'Trang Thị XXXX', 'Nữ', '1989-07-22', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(223, 44, 'Vũ Văn YYYY', 'Nam', '2011-10-04', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(224, 44, 'Yến Thị ZZZZ', 'Nữ', '2013-01-16', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(225, 45, 'Phạm Hữu Khách', 'Nam', '1986-03-18', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(226, 45, 'Châu Thị BBBBB', 'Nữ', '1988-06-30', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(227, 45, 'Dũng Văn CCCCC', 'Nam', '1992-09-12', NULL, NULL, NULL, NULL, 0, 'adult', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL),
(228, 45, 'Hằng Thị DDDDD', 'Nữ', '2012-12-24', NULL, NULL, NULL, NULL, 0, 'child', 'unpaid', 0.00, NULL, 'not_arrived', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `booking_price_adjustments`
--

CREATE TABLE `booking_price_adjustments` (
  `id` int NOT NULL,
  `booking_id` int NOT NULL,
  `adjust_type` enum('discount_cash','discount_percent','foc','surcharge','gift','other') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` varchar(500) NOT NULL,
  `created_by` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_suppliers_assignment`
--

CREATE TABLE `booking_suppliers_assignment` (
  `id` int NOT NULL,
  `booking_id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `service_type` varchar(255) DEFAULT NULL,
  `quantity` int DEFAULT '1',
  `price` decimal(15,2) DEFAULT NULL,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `booking_suppliers_assignment`
--

INSERT INTO `booking_suppliers_assignment` (`id`, `booking_id`, `supplier_id`, `service_type`, `quantity`, `price`, `notes`) VALUES
(7, 45, 1, 'tour_operator', 1, 0.00, 'Supplier mặc định từ tour (tự động thêm)'),
(9, 32, 4, 'tour_operator', 1, 0.00, 'Supplier mặc định từ tour (tự động thêm)'),
(10, 33, 4, 'tour_operator', 1, 0.00, 'Supplier mặc định từ tour (tự động thêm)');

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` int NOT NULL,
  `full_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Họ tên tài xế',
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Số điện thoại',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email',
  `license_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Số bằng lái',
  `license_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại bằng lái (B1, B2, C, D, E...)',
  `vehicle_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại xe (4 chỗ, 7 chỗ, 16 chỗ, 29 chỗ, 45 chỗ...)',
  `vehicle_plate` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Biển số xe',
  `vehicle_brand` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hãng xe (Toyota, Ford, Hyundai...)',
  `status` enum('active','inactive','busy') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active' COMMENT 'Trạng thái: active=sẵn sàng, inactive=nghỉ, busy=đang có tour',
  `rating` decimal(3,2) DEFAULT '5.00' COMMENT 'Đánh giá (0-5 sao)',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Ghi chú',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng quản lý tài xế';

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `full_name`, `phone`, `email`, `license_number`, `license_type`, `vehicle_type`, `vehicle_plate`, `vehicle_brand`, `status`, `rating`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'Nguyễn Văn An', '0901234567', 'nguyenvanan@example.com', 'B1-123456', 'B2', '7 chỗ', '30A-12345', 'Toyota Innova', 'active', 5.00, NULL, '2025-12-01 15:21:10', '2025-12-01 15:21:10'),
(2, 'Trần Văn Bình', '0912345678', 'tranvanbinh@example.com', 'B1-234567', 'C', '16 chỗ', '30B-23456', 'Ford Transit', 'active', 5.00, NULL, '2025-12-01 15:21:10', '2025-12-01 15:21:10'),
(3, 'Lê Văn Cường', '0923456789', 'levancuong@example.com', 'B1-345678', 'D', '29 chỗ', '30C-34567', 'Hyundai County', 'active', 5.00, NULL, '2025-12-01 15:21:10', '2025-12-01 15:21:10'),
(4, 'Phạm Văn Dũng', '0934567890', 'phamvandung@example.com', 'B1-456789', 'D', '45 chỗ', '30D-45678', 'Thaco Universe', 'active', 5.00, NULL, '2025-12-01 15:21:10', '2025-12-01 15:21:10');

-- --------------------------------------------------------

--
-- Table structure for table `financial_reports`
--

CREATE TABLE `financial_reports` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `total_revenue` decimal(15,2) DEFAULT NULL,
  `total_expense` decimal(15,2) DEFAULT NULL,
  `profit` decimal(15,2) DEFAULT NULL,
  `report_date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `financial_reports`
--

INSERT INTO `financial_reports` (`id`, `tour_id`, `total_revenue`, `total_expense`, `profit`, `report_date`) VALUES
(1, 1, 46600000.00, 38500000.00, 8100000.00, '2025-12-19 00:00:00'),
(2, 1, 68600000.00, 56200000.00, 12400000.00, '2025-05-02 00:00:00'),
(3, 2, 30600000.00, 21800000.00, 8800000.00, '2025-12-23 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `guides`
--

CREATE TABLE `guides` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `languages` varchar(255) DEFAULT NULL,
  `experience_years` int DEFAULT NULL,
  `rating` float DEFAULT '0',
  `health_status` varchar(100) DEFAULT NULL,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `guides`
--

INSERT INTO `guides` (`id`, `user_id`, `languages`, `experience_years`, `rating`, `health_status`, `notes`) VALUES
(1, 2, 'Tiếng Việt, Tiếng Anh', 5, 4.8, 'Tốt', 'Chuyên tour miền Bắc'),
(2, 3, 'Tiếng Việt, Tiếng Pháp', 8, 4.9, 'Tốt', 'Chuyên tour miền Trung'),
(3, 4, 'Tiếng Việt, Tiếng Trung', 4, 4.7, 'Tốt', 'Mới nhưng rất nhiệt tình');

-- --------------------------------------------------------

--
-- Table structure for table `itineraries`
--

CREATE TABLE `itineraries` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `day_label` varchar(100) DEFAULT NULL,
  `day_number` int DEFAULT NULL,
  `time_start` time DEFAULT NULL,
  `time_end` time DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `activities` text,
  `image_url` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `itineraries`
--

INSERT INTO `itineraries` (`id`, `tour_id`, `day_label`, `day_number`, `time_start`, `time_end`, `title`, `description`, `activities`, `image_url`) VALUES
(40, 1, 'Ngày 1', 1, '06:00:00', '22:00:00', 'Hà Nội – Sapa – Cát Cát', '', '', ''),
(41, 1, 'Ngày 2', 2, '07:00:00', '17:00:00', 'Chinh phục Fansipan', '', '', ''),
(42, 1, 'Ngày 3', 3, '08:00:00', '16:00:00', 'Sapa – Hạ Long', '', '', ''),
(43, 1, 'Ngày 4', 4, '06:30:00', '16:00:00', 'Khám phá Vịnh Hạ Long', '', '', ''),
(44, 1, 'Ngày 5', 5, '07:00:00', '11:00:00', 'Hạ Long – Hà Nội', '', '', ''),
(55, 14, '', 1, '06:00:00', '08:00:00', 'Đón ở sân bay', '', '', ''),
(56, 9, 'Ngày 1', 1, '07:00:00', '15:00:00', 'Hà Nội - Lạng Sơn - Cao Bằng: \"Chạm vào di sản\"', 'Xe và hướng dẫn viên Vietravel đón đoàn tại điểm hẹn, khởi hành cho hành trình “Tinh hoa cực Bắc - Sắc màu vùng cao” - khám phá trọn vẹn vẻ đẹp Việt Nam từ kinh đô ngàn năm văn hiến đến địa đầu Tổ quốc, với những di tích văn hóa lịch sử và cảnh quan thiên nhiên hùng vĩ.\"\n\n07:30 : Đón Quý khách tại điểm hẹn số 1 - Vietravel Hà Nội (03 Hai Bà Trưng).\n07:45 : Đón Quý khách tại điểm hẹn số 2 - Khách sạn (đối với đoàn khách đi Xuyên Việt và đoàn khách đến trước 1 đêm).\nXe khởi hành đưa Quý khách đi tham quan:\n\nHoàng Thành Thăng Long: Một quần thể kiến ​​trúc đồ họa được xây dựng bởi các triều vua trong nhiều giai đoạn lịch sử, nơi đây đã trở thành di tích quan trọng bậc nhất trong hệ thống các di tích Việt Nam và được UNESCO công nhận là di sản văn hóa thế giới vào năm 2010. Quý khách có thể trải nghiệm ảnh chụp trong các bộ trang phục cổ của Việt Nam (chi phí thuê trang phục tự túc).\nTrên đường đi Lạng Sơn - Công viên địa chất toàn cầu vừa được Unesco công nhận tháng 6/2025, Quý khách sẽ dừng chân nghỉ ngơi tại Trạm dừng nghỉ Hoa Hồi, Chi Lăng - không gian thoáng đãng và trưng bày nhiều sản vật địa phương, đặc biệt nổi tiếng về tinh dầu hoa hồi.\nThưởng thức các đặc sản nổi tiếng của Lạng Sơn như phở chua, vịt quay, …\nChụp ảnh lưu niệm bên cột mốc 1116 tại cửa khẩu Hữu Nghị (tên cũ là Ải Nam Quan), một trong những cửa khẩu quan trọng trong giao thương với Trung Quốc ở phía Bắc.\nTiếp tục hành trình đến Cao Bằng, Quý khách nhận phòng khách sạn nghỉ ngơi và dùng cơm chiều. Vào buổi tối, Quý khách có thể tự do dạo chơi tại phố đi bộ Kim Đồng (mở cửa vào thứ 6 và thứ 7 hàng tuần), mua sắm các đặc sản như: thạch đen, miến dong, hồng hương,... hoặc thư giãn với dịch vụ massage chân, cổ vai gáy.\n\nNghỉ đêm tại Cao Bằng', 'Xe và hướng dẫn viên Vietravel đón đoàn tại điểm hẹn, khởi hành cho hành trình “Tinh hoa cực Bắc - Sắc màu vùng cao” - khám phá trọn vẹn vẻ đẹp Việt Nam từ kinh đô ngàn năm văn hiến đến địa đầu Tổ quốc, với những di tích văn hóa lịch sử và cảnh quan thiên nhiên hùng vĩ.\"\n\n07:30 : Đón Quý khách tại điểm hẹn số 1 - Vietravel Hà Nội (03 Hai Bà Trưng).\n07:45 : Đón Quý khách tại điểm hẹn số 2 - Khách sạn (đối với đoàn khách đi Xuyên Việt và đoàn khách đến trước 1 đêm).\nXe khởi hành đưa Quý khách đi tham quan:\n\nHoàng Thành Thăng Long: Một quần thể kiến ​​trúc đồ họa được xây dựng bởi các triều vua trong nhiều giai đoạn lịch sử, nơi đây đã trở thành di tích quan trọng bậc nhất trong hệ thống các di tích Việt Nam và được UNESCO công nhận là di sản văn hóa thế giới vào năm 2010. Quý khách có thể trải nghiệm ảnh chụp trong các bộ trang phục cổ của Việt Nam (chi phí thuê trang phục tự túc).\nTrên đường đi Lạng Sơn - Công viên địa chất toàn cầu vừa được Unesco công nhận tháng 6/2025, Quý khách sẽ dừng chân nghỉ ngơi tại Trạm dừng nghỉ Hoa Hồi, Chi Lăng - không gian thoáng đãng và trưng bày nhiều sản vật địa phương, đặc biệt nổi tiếng về tinh dầu hoa hồi.\nThưởng thức các đặc sản nổi tiếng của Lạng Sơn như phở chua, vịt quay, …\nChụp ảnh lưu niệm bên cột mốc 1116 tại cửa khẩu Hữu Nghị (tên cũ là Ải Nam Quan), một trong những cửa khẩu quan trọng trong giao thương với Trung Quốc ở phía Bắc.\nTiếp tục hành trình đến Cao Bằng, Quý khách nhận phòng khách sạn nghỉ ngơi và dùng cơm chiều. Vào buổi tối, Quý khách có thể tự do dạo chơi tại phố đi bộ Kim Đồng (mở cửa vào thứ 6 và thứ 7 hàng tuần), mua sắm các đặc sản như: thạch đen, miến dong, hồng hương,... hoặc thư giãn với dịch vụ massage chân, cổ vai gáy.\n\nNghỉ đêm tại Cao Bằng', ''),
(57, 9, 'Ngày 2', 2, '07:00:00', '13:00:00', 'Non nước Cao Bằng - Trùng Khánh', '', '', ''),
(58, 9, 'Ngày 3', 3, '07:30:00', '12:00:00', 'Cao Bằng - Mèo Vạc - Đồng Văn', '', '', ''),
(59, 9, 'Ngày 4', 4, '07:00:00', '17:00:00', 'Đồng Văn - Yên Minh - Quản Bạ - Hà Giang', '', '', ''),
(60, 9, 'Ngày 5', 5, '00:00:00', '00:00:00', 'Hà Giang - Tuyên Quang - Hà Nội', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `message` text NOT NULL,
  `link` varchar(512) DEFAULT NULL,
  `is_read` tinyint DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `link`, `is_read`, `created_at`) VALUES
(1, 1, 'Booking mới #B001 - Đoàn 20 khách Sapa 15/12', '/admin/bookings/1', 0, '2025-11-20 10:35:00'),
(2, 1, 'Khách vừa thanh toán đủ booking #B003', '/admin/bookings/3', 0, '2025-11-26 10:05:00'),
(3, 2, 'Bạn được phân công dẫn tour Sapa khởi hành 15/12/2025', '/guide/tours/1', 0, '2025-11-21 09:00:00'),
(4, 2, 'Vui lòng cập nhật nhật ký tour ngày 16/12', '/guide/logs/new?booking_id=1', 1, '2025-12-16 20:00:00'),
(5, 1, 'Tour Hạ Long 19/12 đã kết thúc – HDV đánh giá 5 sao', '/admin/tour-logs/3', 0, '2025-12-19 21:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text,
  `rating` float DEFAULT '0',
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `type`, `contact_person`, `phone`, `email`, `address`, `rating`, `description`) VALUES
(1, 'Khách sạn Sunflower Hà Nội', 'hotel', 'Chị Lan', '02438558888', 'lan@sunflower.com', '12 P. Lý Thái Tổ, Hà Nội', 4.6, NULL),
(2, 'Nhà hàng Hương Sen Sapa', 'restaurant', 'Anh Hùng', '0214388999', 'hung@huongsen.com', 'Sapa, Lào Cai', 4.4, NULL),
(3, 'Tàu Paradise Hạ Long', 'cruise', 'Chị Mai', '02033845888', 'mai@paradise.com', 'Tuần Châu, Quảng Ninh', 4.8, NULL),
(4, 'Xe Limousine 29 chỗ VIP', 'transport', 'Anh Tuấn', '0912345678', 'tuan@limousine.vn', 'Hà Nội', 4.7, NULL),
(5, 'Khách sạn Silk Path Huế', 'hotel', 'Chị Hương', '02343889999', 'huong@silkpath.com', 'Huế', 4.9, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `supplier_contracts`
--

CREATE TABLE `supplier_contracts` (
  `id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `contract_name` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `price_info` text,
  `status` varchar(50) DEFAULT NULL,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `supplier_contracts`
--

INSERT INTO `supplier_contracts` (`id`, `supplier_id`, `contract_name`, `start_date`, `end_date`, `price_info`, `status`, `notes`) VALUES
(1, 1, 'Hợp đồng năm 2025', '2025-01-01', '2025-12-31', 'Phòng Standard: 1.100.000đ/phòng/đêm', 'active', 'Giảm 5% nếu thanh toán trước'),
(2, 3, 'Hợp đồng du thuyền 2025', '2025-01-01', '2025-12-31', 'Cabin Deluxe 4.500.000đ/khách/2N1Đ', 'active', 'Bao gồm tất cả bữa ăn');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_feedbacks`
--

CREATE TABLE `supplier_feedbacks` (
  `id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `guide_id` int NOT NULL,
  `rating` int DEFAULT NULL,
  `comment` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `supplier_feedbacks`
--

INSERT INTO `supplier_feedbacks` (`id`, `supplier_id`, `guide_id`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 2, 5, 'Phòng sạch sẽ, nhân viên thân thiện, ăn sáng ngon', '2025-12-19 18:00:00'),
(2, 3, 2, 5, 'Tàu đẹp, đồ ăn ngon, hoạt động đúng lịch trình', '2025-12-19 20:00:00'),
(3, 5, 3, 4, 'Khách sạn đẹp nhưng wifi hơi yếu ở tầng cao', '2025-12-23 17:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `tours`
--

CREATE TABLE `tours` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` int NOT NULL,
  `supplier_id` int DEFAULT NULL COMMENT 'Supplier mặc định của tour',
  `description` text,
  `base_price` decimal(15,2) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tours`
--

INSERT INTO `tours` (`id`, `name`, `category_id`, `supplier_id`, `description`, `base_price`, `created_at`, `updated_at`) VALUES
(1, 'Hà Nội - Sapa - Fansipan - Hạ Long 5N4Đ', 1, 4, 'Tour miền Bắc đẹp nhất 2025, leo Fansipan bằng cáp treo', 12900000.00, '2025-11-26 12:06:20', '2025-12-07 10:07:13'),
(2, 'Đà Nẵng - Hội An - Huế 4N3Đ', 2, NULL, 'Khám phá miền Trung di sản, tắm biển Mỹ Khê', 8900000.00, '2025-11-26 12:06:20', '2025-12-08 01:29:34'),
(3, 'Sài Gòn - Phú Quốc 4N3Đ bay thẳng', 3, NULL, 'Nghỉ dưỡng 4 sao, buffet sáng, vé máy bay khứ hồi', 10900000.00, '2025-11-26 12:06:20', '2025-12-06 10:18:39'),
(4, 'Siem Reap - Angkor Wat 4N3Đ', 4, NULL, 'Khám phá kỳ quan thế giới, bay Vietnam Airlines', 9900000.00, '2025-11-26 12:06:20', '2025-12-06 10:24:10'),
(5, 'Hà Nội - Hà Giang - Lũng Cú - Đồng Văn - Mèo Vạc 4N3Đ', 1, NULL, 'Vòng cung cực Bắc, mùa hoa tam giác mạch', 7900000.00, '2025-11-26 12:06:20', '2025-12-06 14:34:00'),
(7, 'Sapa – Fansipan 3N2Đ', 1, 1, '', 5900000.00, '2025-11-29 00:14:38', '2025-12-07 03:53:39'),
(9, 'Hà Nội - Lạng Sơn - Cao Bằng - Hà Giang 5N4Đ', 1, 4, '', 11590000.00, '2025-12-07 17:28:45', '2025-12-07 16:27:42'),
(14, 'TP HCM', 3, NULL, '', 1900000.00, '2025-12-07 21:22:13', '2025-12-07 21:22:13');

-- --------------------------------------------------------

--
-- Table structure for table `tour_assignments`
--

CREATE TABLE `tour_assignments` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `guide_id` int NOT NULL,
  `driver_name` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_assignments`
--

INSERT INTO `tour_assignments` (`id`, `tour_id`, `guide_id`, `driver_name`, `start_date`, `end_date`, `status`) VALUES
(8, 1, 2, NULL, '2025-12-10', NULL, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `tour_categories`
--

CREATE TABLE `tour_categories` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text,
  `icon` varchar(512) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_categories`
--

INSERT INTO `tour_categories` (`id`, `name`, `slug`, `description`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'Miền Bắc', 'mien-bac', 'Hà Nội - Sapa - Hạ Long - Ninh Bình', 'icons/north.svg', '2025-11-26 12:06:20', '2025-11-26 12:06:20'),
(2, 'Miền Trung', 'mien-trung', 'Huế - Đà Nẵng - Hội An - Phong Nha', 'icons/central.svg', '2025-11-26 12:06:20', '2025-11-26 12:06:20'),
(3, 'Miền Nam', 'mien-nam', 'Sài Gòn - Phú Quốc - Cần Thơ - Mũi Né', 'icons/south.svg', '2025-11-26 12:06:20', '2025-11-26 12:06:20'),
(4, 'Campuchia', 'campuchia', 'Siem Reap - Phnom Penh', 'icons/cambodia.svg', '2025-11-26 12:06:20', '2025-11-26 12:06:20'),
(5, 'Lào', 'lao', 'Luang Prabang - Vang Vieng - Viêng Chăn', 'icons/laos.svg', '2025-11-26 12:06:20', '2025-11-26 12:06:20');

-- --------------------------------------------------------

--
-- Table structure for table `tour_departures`
--

CREATE TABLE `tour_departures` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `version_id` int DEFAULT NULL,
  `departure_date` date NOT NULL,
  `max_seats` int DEFAULT '40',
  `booked_seats` int DEFAULT '0',
  `price_adult` decimal(12,2) DEFAULT NULL,
  `price_child` decimal(12,2) DEFAULT NULL,
  `price_infant` decimal(12,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'open',
  `notes` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_departures`
--

INSERT INTO `tour_departures` (`id`, `tour_id`, `version_id`, `departure_date`, `max_seats`, `booked_seats`, `price_adult`, `price_child`, `price_infant`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(10, 1, NULL, '2025-04-25', 40, 0, 0.00, 0.00, NULL, 'open', NULL, '2025-12-07 10:07:14', '2025-12-07 17:07:14'),
(11, 1, NULL, '2025-04-30', 35, 0, 0.00, 0.00, NULL, 'open', NULL, '2025-12-07 10:07:14', '2025-12-07 17:07:14'),
(12, 1, NULL, '2025-06-15', 40, 0, 0.00, 0.00, NULL, 'open', NULL, '2025-12-07 10:07:14', '2025-12-07 17:07:14'),
(13, 2, NULL, '2025-12-23', 40, 0, 0.00, 0.00, NULL, 'open', NULL, '2025-12-08 01:29:34', '2025-12-08 08:29:34');

-- --------------------------------------------------------

--
-- Table structure for table `tour_feedbacks`
--

CREATE TABLE `tour_feedbacks` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `user_id` int NOT NULL,
  `rating` int DEFAULT NULL,
  `comment` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_feedbacks`
--

INSERT INTO `tour_feedbacks` (`id`, `tour_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 1, 5, 'Tour tuyệt vời, HDV rất nhiệt tình!', '2025-10-20 10:00:00'),
(2, 2, 1, 4, 'Đẹp nhưng thời gian di chuyển hơi dài', '2025-11-10 14:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `tour_gallery_images`
--

CREATE TABLE `tour_gallery_images` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `image_url` varchar(512) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `main_img` tinyint DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_gallery_images`
--

INSERT INTO `tour_gallery_images` (`id`, `tour_id`, `image_url`, `caption`, `sort_order`, `main_img`, `created_at`) VALUES
(9, 7, 'tours/tour_6933fc5806f1f.webp', '', 1, 1, '2025-12-06 16:50:16'),
(10, 1, 'tours/tour_6934016a88840.webp', '', 1, 1, '2025-12-06 17:11:54'),
(11, 2, 'tours/tour_69340255e456c.webp', '', 1, 1, '2025-12-06 17:15:49'),
(12, 3, 'tours/tour_693402ff1956f.webp', '', 1, 1, '2025-12-06 17:18:39'),
(13, 4, 'tours/tour_6934044abfb33.webp', '', 1, 1, '2025-12-06 17:24:10'),
(14, 5, 'tours/tour_69340700dbd00.webp', '', 1, 1, '2025-12-06 17:35:44'),
(15, 9, 'tours/tour_693556ea708a3.webp', '', 1, 1, '2025-12-07 17:28:58'),
(17, 14, 'tours/tour_main_69358d9542e5c.webp', '', 1, 1, '2025-12-07 21:22:13'),
(18, 14, 'tours/tour_69358d9544776.webp', '', 2, 0, '2025-12-07 21:22:13'),
(19, 14, 'tours/tour_69358d95463b9.webp', '', 3, 0, '2025-12-07 21:22:13'),
(20, 14, 'tours/tour_69358d954780d.webp', '', 4, 0, '2025-12-07 21:22:13'),
(21, 14, 'tours/tour_69358d954922a.webp', '', 5, 0, '2025-12-07 21:22:13'),
(22, 14, 'tours/tour_69358d9549d39.webp', '', 6, 0, '2025-12-07 21:22:13');

-- --------------------------------------------------------

--
-- Table structure for table `tour_logs`
--

CREATE TABLE `tour_logs` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `guide_id` int DEFAULT NULL,
  `date` datetime DEFAULT CURRENT_TIMESTAMP,
  `description` text,
  `issue` text,
  `solution` text,
  `customer_feedback` text,
  `weather` varchar(100) DEFAULT NULL,
  `incident` text,
  `health_status` varchar(255) DEFAULT NULL,
  `special_activity` text,
  `handling_notes` text,
  `guide_rating` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_logs`
--

INSERT INTO `tour_logs` (`id`, `tour_id`, `guide_id`, `date`, `description`, `issue`, `solution`, `customer_feedback`, `weather`, `incident`, `health_status`, `special_activity`, `handling_notes`, `guide_rating`) VALUES
(1, 1, 2, '2025-12-15 20:00:00', 'Ngày 1: Đón đoàn tại Hà Nội, lên xe Sapa', NULL, NULL, 'Đoàn rất vui vẻ, đúng giờ', 'Mưa nhỏ', NULL, 'Tốt', 'Tham quan bản Cát Cát', 'Đoàn ăn tối đặc sản thắng cố', 5),
(2, 1, 2, '2025-12-16 19:30:00', 'Ngày 2: Leo Fansipan bằng cáp treo', '1 khách bị say độ cao nhẹ', 'Cho nghỉ + uống trà gừng', 'Rất thích đỉnh núi, chụp ảnh đẹp', 'Nắng đẹp', NULL, 'Tốt', 'Check-in đỉnh Fansipan', 'Đoàn tặng hoa HDV', 5),
(3, 1, 2, '2025-12-18 21:00:00', 'Ngày 4: Khám phá Vịnh Hạ Long', NULL, NULL, 'Hang Sửng Sốt rất đẹp, kayak vui', 'Nắng ấm', NULL, 'Tốt', 'Tắm biển Titop', 'Đoàn hát karaoke trên tàu', 5),
(4, 2, 3, '2025-12-20 18:00:00', 'Ngày 1: Đón đoàn tại Đà Nẵng, về Huế', 'Gặp tắc đường cầu Rồng', 'Đi đường vòng qua hầm Hải Vân', 'Vẫn vui vẻ, HDV kể chuyện hay', 'Mưa phùn', NULL, 'Tốt', 'Tham quan Đại Nội về đêm', 'Đoàn thích nghe đàn tranh', 4);

-- --------------------------------------------------------

--
-- Table structure for table `tour_partner_services`
--

CREATE TABLE `tour_partner_services` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `service_type` varchar(255) DEFAULT NULL,
  `partner_name` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `notes` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_partner_services`
--

INSERT INTO `tour_partner_services` (`id`, `tour_id`, `service_type`, `partner_name`, `contact`, `notes`, `created_at`) VALUES
(7, 3, 'restaurant', 'Vinpearl Resort Phú Quốc', '02973888888', '', '2025-12-06 10:18:39'),
(8, 1, 'hotel', 'Khách sạn Sunflower Hà Nội', '', '', '2025-12-07 10:07:14'),
(9, 14, 'restaurant', '', '02033845888', '', '2025-12-07 14:22:13'),
(10, 2, 'hotel', 'Silk Path Grand Huế', '', '', '2025-12-08 01:29:34');

-- --------------------------------------------------------

--
-- Table structure for table `tour_policies`
--

CREATE TABLE `tour_policies` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_policies`
--

INSERT INTO `tour_policies` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Chính sách hủy tour', 'chinh-sach-huy-tour', 'Hủy trước 30 ngày: hoàn 100%, 15-29 ngày: hoàn 70%...', '2025-11-26 12:06:20', '2025-11-26 12:06:20'),
(2, 'Chính sách trẻ em', 'chinh-sach-tre-em', 'Trẻ dưới 5 tuổi miễn phí, 5-10 tuổi tính 75%...', '2025-11-26 12:06:20', '2025-11-26 12:06:20'),
(3, 'Chính sách bảo hiểm', 'chinh-sach-bao-hiem', 'Bảo hiểm du lịch toàn bộ hành trình, mức bồi thường tối đa 200 triệu', '2025-11-26 12:06:20', '2025-11-26 12:06:20'),
(4, 'Điều khoản thanh toán', 'dieu-khoan-thanh-toan', 'Đặt cọc 50%, thanh toán hết trước 15 ngày khởi hành', '2025-11-26 12:06:20', '2025-11-26 12:06:20');

-- --------------------------------------------------------

--
-- Table structure for table `tour_policy_assignments`
--

CREATE TABLE `tour_policy_assignments` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `policy_id` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_policy_assignments`
--

INSERT INTO `tour_policy_assignments` (`id`, `tour_id`, `policy_id`, `created_at`) VALUES
(47, 3, 1, '2025-12-06 10:18:39'),
(48, 3, 2, '2025-12-06 10:18:39'),
(49, 3, 3, '2025-12-06 10:18:39'),
(50, 3, 4, '2025-12-06 10:18:39'),
(55, 5, 1, '2025-12-06 14:34:00'),
(56, 5, 2, '2025-12-06 14:34:00'),
(57, 5, 3, '2025-12-06 14:34:00'),
(58, 5, 4, '2025-12-06 14:34:00'),
(59, 7, 1, '2025-12-07 03:53:40'),
(60, 7, 2, '2025-12-07 03:53:40'),
(61, 7, 3, '2025-12-07 03:53:40'),
(62, 7, 4, '2025-12-07 03:53:40'),
(63, 1, 1, '2025-12-07 10:07:14'),
(64, 1, 2, '2025-12-07 10:07:14'),
(65, 1, 3, '2025-12-07 10:07:14'),
(66, 1, 4, '2025-12-07 10:07:14'),
(75, 14, 1, '2025-12-07 14:22:13'),
(76, 14, 2, '2025-12-07 14:22:13'),
(77, 14, 3, '2025-12-07 14:22:13'),
(78, 14, 4, '2025-12-07 14:22:13'),
(79, 9, 1, '2025-12-07 16:27:42'),
(80, 9, 2, '2025-12-07 16:27:42'),
(81, 9, 3, '2025-12-07 16:27:42'),
(82, 9, 4, '2025-12-07 16:27:42'),
(83, 2, 1, '2025-12-08 01:29:34'),
(84, 2, 2, '2025-12-08 01:29:34'),
(85, 2, 3, '2025-12-08 01:29:34'),
(86, 2, 4, '2025-12-08 01:29:34');

-- --------------------------------------------------------

--
-- Table structure for table `tour_pricing_options`
--

CREATE TABLE `tour_pricing_options` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `description` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_pricing_options`
--

INSERT INTO `tour_pricing_options` (`id`, `tour_id`, `label`, `description`, `created_at`) VALUES
(25, 3, 'Gói nghỉ dưỡng', 'Resort 4 sao ven biển', '2025-12-06 10:18:39'),
(26, 7, 'Người khuyế tật', 'Miễn phí', '2025-12-07 03:53:39');

-- --------------------------------------------------------

--
-- Table structure for table `tour_versions`
--

CREATE TABLE `tour_versions` (
  `id` int NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_versions`
--

INSERT INTO `tour_versions` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Tiêu chuẩn', 'Phiên bản tour tiêu chuẩn', 'active', '2025-11-28 21:22:49', '2025-11-28 21:22:49'),
(2, 'Cao cấp', 'Phiên bản tour cao cấp', 'active', '2025-11-28 21:22:49', '2025-11-28 21:22:49'),
(3, 'VIP', 'Phiên bản tour VIP', 'active', '2025-11-28 21:22:49', '2025-11-28 21:22:49'),
(4, 'Bình thường', NULL, 'active', '2025-11-29 00:14:38', '2025-11-29 00:14:38'),
(5, 'Lễ 30/4 - Phụ thu', NULL, 'active', '2025-11-29 00:14:38', '2025-11-29 00:14:38'),
(7, 'Mùa đông 2025', '', 'active', '2025-12-05 01:33:21', '2025-12-07 07:43:54');

-- --------------------------------------------------------

--
-- Table structure for table `tour_version_prices`
--

CREATE TABLE `tour_version_prices` (
  `id` int NOT NULL,
  `version_id` int DEFAULT NULL,
  `tour_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `adult_percent` decimal(5,2) DEFAULT '0.00' COMMENT '% tăng/giảm giá người lớn',
  `child_percent` decimal(5,2) DEFAULT '0.00' COMMENT '% tăng/giảm giá trẻ em',
  `infant_percent` decimal(5,2) DEFAULT '0.00' COMMENT '% tăng/giảm giá em bé',
  `child_base_percent` decimal(5,2) DEFAULT '75.00' COMMENT 'Trẻ em = % giá người lớn',
  `infant_base_percent` decimal(5,2) DEFAULT '50.00' COMMENT 'Em bé = % giá người lớn'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_version_prices`
--

INSERT INTO `tour_version_prices` (`id`, `version_id`, `tour_id`, `created_at`, `adult_percent`, `child_percent`, `infant_percent`, `child_base_percent`, `infant_base_percent`) VALUES
(1, 7, NULL, '2025-12-05 01:33:21', 0.00, 0.00, 0.00, 75.00, 50.00);

-- --------------------------------------------------------

--
-- Table structure for table `tour_version_prices_backup_20251207`
--

CREATE TABLE `tour_version_prices_backup_20251207` (
  `id` int NOT NULL DEFAULT '0',
  `version_id` int DEFAULT NULL,
  `tour_id` int DEFAULT NULL,
  `price_adult` decimal(15,2) DEFAULT NULL,
  `price_child` decimal(15,2) DEFAULT NULL,
  `price_infant` decimal(15,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_version_prices_backup_20251207`
--

INSERT INTO `tour_version_prices_backup_20251207` (`id`, `version_id`, `tour_id`, `price_adult`, `price_child`, `price_infant`, `created_at`) VALUES
(1, 7, NULL, 100000.00, 50000.00, 10000.00, '2025-12-05 01:33:21');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int NOT NULL,
  `booking_id` int NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `method` varchar(50) DEFAULT NULL,
  `description` text,
  `date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('admin','guide','customer') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `avatar` varchar(512) DEFAULT NULL,
  `is_active` tinyint DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `phone`, `role`, `password_hash`, `avatar`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Kiên', 'admin@company.com', '0909123456', 'admin', '$2y$10$tjeVGJKb11GpupcrPsvp8ujKv0mIJkZyKBWfC3GdEH3tKC.7gf4Za', 'avatars/avatar_1_1765021970.jpg', 1, '2025-11-26 12:06:20', '2025-12-08 20:52:10'),
(2, 'Trần Thị HDV1', 'hdv1@company.com', '0909123457', 'guide', '1234', 'avatar/hdv1.jpg', 1, '2025-11-26 12:06:20', '2025-11-26 12:10:10'),
(3, 'Lê Văn HDV2', 'hdv2@company.com', '0909123458', 'guide', '$2y$10$I9wPdE.4241TTrr6s5x51.YFdN7ezw8bnBfHc9cmBvkBeFHGSfb5u', 'avatar/hdv2.jpg', 1, '2025-11-26 12:06:20', '2025-12-06 18:15:01'),
(4, 'Phạm Minh HDV3', 'hdv3@company.com', '0909123459', 'guide', '1234', 'avatar/hdv3.jpg', 1, '2025-11-26 12:06:20', '2025-11-26 12:10:16'),
(5, 'Nguyễn Minh Khách', 'customer1@company.com', '0909000001', 'customer', '1234', 'avatar/customer1.jpg', 1, '2025-12-01 08:20:58', '2025-12-01 08:20:58'),
(6, 'Trần Thị Khách', 'customer2@company.com', '0909000002', 'customer', '1234', 'avatar/customer2.jpg', 1, '2025-12-01 08:20:58', '2025-12-01 08:20:58'),
(7, 'Lê Văn Khách', 'customer3@company.com', '0909000003', 'customer', '1234', 'avatar/customer3.jpg', 1, '2025-12-01 08:20:58', '2025-12-01 08:20:58'),
(8, 'Phạm Hữu Khách', 'customer4@company.com', '0909000004', 'customer', '1234', 'avatar/customer4.jpg', 1, '2025-12-01 08:20:58', '2025-12-01 08:20:58'),
(9, 'Bùi Hải Khách', 'customer5@company.com', '0909000005', 'customer', '1234', 'avatar/customer5.jpg', 1, '2025-12-01 08:20:58', '2025-12-01 08:20:58'),
(10, 'Võ Thanh Khách', 'customer6@company.com', '0909000006', 'customer', '1234', 'avatar/customer6.jpg', 1, '2025-12-01 08:20:58', '2025-12-01 08:20:58'),
(11, 'Đỗ Minh Khách', 'customer7@company.com', '0909000007', 'customer', '1234', 'avatar/customer7.jpg', 1, '2025-12-01 08:20:58', '2025-12-01 08:20:58'),
(12, 'Hoàng Lan Khách', 'customer8@company.com', '0909000008', 'customer', '1234', 'avatar/customer8.jpg', 1, '2025-12-01 08:20:58', '2025-12-01 08:20:58'),
(13, 'Phan Thị Khách', 'customer9@company.com', '0909000009', 'customer', '1234', 'avatar/customer9.jpg', 1, '2025-12-01 08:20:58', '2025-12-01 08:20:58'),
(14, 'Vũ Ngọc Khách', 'customer10@company.com', '0909000010', 'customer', '1234', 'avatar/customer10.jpg', 1, '2025-12-01 08:20:58', '2025-12-01 08:20:58'),
(15, 'Đỗ Việt Hùng', 'h@gmail.com', '0958994567', 'customer', '$2y$10$K6OPERRzuc4A/ogi1DRDhu.wNVw6M7FBtiBCRTMNOM/fX/D2a3eEe', NULL, 1, '2025-12-06 18:43:19', '2025-12-06 18:43:19');

-- --------------------------------------------------------

--
-- Table structure for table `version_dynamic_pricing`
--

CREATE TABLE `version_dynamic_pricing` (
  `id` int NOT NULL,
  `version_id` int DEFAULT NULL,
  `departure_id` int DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `notes` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `adjust_type` enum('discount','surcharge') DEFAULT 'discount',
  `amount_type` enum('cash','percent') DEFAULT 'cash',
  `amount` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `version_dynamic_pricing`
--

INSERT INTO `version_dynamic_pricing` (`id`, `version_id`, `departure_id`, `start_date`, `end_date`, `notes`, `created_at`, `adjust_type`, `amount_type`, `amount`) VALUES
(3, NULL, NULL, '2025-04-28', '2025-05-04', 'Giá lễ 30/4', '2025-11-26 12:06:20', 'discount', 'cash', NULL),
(4, NULL, NULL, '2025-12-27', '2026-01-05', 'Tết Dương lịch + Âm lịch', '2025-11-26 12:06:20', 'discount', 'cash', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `bookings_ibfk_3` (`version_id`),
  ADD KEY `fk_bookings_departure` (`departure_id`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Indexes for table `booking_customers`
--
ALTER TABLE `booking_customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `booking_price_adjustments`
--
ALTER TABLE `booking_price_adjustments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `booking_suppliers_assignment`
--
ALTER TABLE `booking_suppliers_assignment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `license_number` (`license_number`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `financial_reports`
--
ALTER TABLE `financial_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `guides`
--
ALTER TABLE `guides`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `itineraries`
--
ALTER TABLE `itineraries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier_contracts`
--
ALTER TABLE `supplier_contracts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `supplier_feedbacks`
--
ALTER TABLE `supplier_feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `guide_id` (`guide_id`);

--
-- Indexes for table `tours`
--
ALTER TABLE `tours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `idx_supplier_id` (`supplier_id`);

--
-- Indexes for table `tour_assignments`
--
ALTER TABLE `tour_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `guide_id` (`guide_id`);

--
-- Indexes for table `tour_categories`
--
ALTER TABLE `tour_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `tour_departures`
--
ALTER TABLE `tour_departures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tour_date` (`tour_id`,`departure_date`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `fk_departures_version` (`version_id`);

--
-- Indexes for table `tour_feedbacks`
--
ALTER TABLE `tour_feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tour_gallery_images`
--
ALTER TABLE `tour_gallery_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `tour_logs`
--
ALTER TABLE `tour_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `guide_id` (`guide_id`);

--
-- Indexes for table `tour_partner_services`
--
ALTER TABLE `tour_partner_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `tour_policies`
--
ALTER TABLE `tour_policies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `tour_policy_assignments`
--
ALTER TABLE `tour_policy_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `policy_id` (`policy_id`);

--
-- Indexes for table `tour_pricing_options`
--
ALTER TABLE `tour_pricing_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `tour_versions`
--
ALTER TABLE `tour_versions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tour_version_prices`
--
ALTER TABLE `tour_version_prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `version_id` (`version_id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `version_dynamic_pricing`
--
ALTER TABLE `version_dynamic_pricing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_vdp_version` (`version_id`),
  ADD KEY `fk_vdp_departure` (`departure_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `booking_customers`
--
ALTER TABLE `booking_customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=230;

--
-- AUTO_INCREMENT for table `booking_price_adjustments`
--
ALTER TABLE `booking_price_adjustments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `booking_suppliers_assignment`
--
ALTER TABLE `booking_suppliers_assignment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `financial_reports`
--
ALTER TABLE `financial_reports`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `guides`
--
ALTER TABLE `guides`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `itineraries`
--
ALTER TABLE `itineraries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `supplier_contracts`
--
ALTER TABLE `supplier_contracts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `supplier_feedbacks`
--
ALTER TABLE `supplier_feedbacks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tours`
--
ALTER TABLE `tours`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tour_assignments`
--
ALTER TABLE `tour_assignments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tour_categories`
--
ALTER TABLE `tour_categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tour_departures`
--
ALTER TABLE `tour_departures`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tour_feedbacks`
--
ALTER TABLE `tour_feedbacks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tour_gallery_images`
--
ALTER TABLE `tour_gallery_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tour_logs`
--
ALTER TABLE `tour_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tour_partner_services`
--
ALTER TABLE `tour_partner_services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tour_policies`
--
ALTER TABLE `tour_policies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tour_policy_assignments`
--
ALTER TABLE `tour_policy_assignments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `tour_pricing_options`
--
ALTER TABLE `tour_pricing_options`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tour_versions`
--
ALTER TABLE `tour_versions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tour_version_prices`
--
ALTER TABLE `tour_version_prices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `version_dynamic_pricing`
--
ALTER TABLE `version_dynamic_pricing`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`version_id`) REFERENCES `tour_versions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `fk_bookings_departure` FOREIGN KEY (`departure_id`) REFERENCES `tour_departures` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_bookings_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `booking_customers`
--
ALTER TABLE `booking_customers`
  ADD CONSTRAINT `booking_customers_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_price_adjustments`
--
ALTER TABLE `booking_price_adjustments`
  ADD CONSTRAINT `booking_price_adjustments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_price_adjustments_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT;

--
-- Constraints for table `booking_suppliers_assignment`
--
ALTER TABLE `booking_suppliers_assignment`
  ADD CONSTRAINT `booking_suppliers_assignment_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_suppliers_assignment_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `financial_reports`
--
ALTER TABLE `financial_reports`
  ADD CONSTRAINT `financial_reports_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `guides`
--
ALTER TABLE `guides`
  ADD CONSTRAINT `guides_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `itineraries`
--
ALTER TABLE `itineraries`
  ADD CONSTRAINT `itineraries_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `supplier_contracts`
--
ALTER TABLE `supplier_contracts`
  ADD CONSTRAINT `supplier_contracts_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supplier_feedbacks`
--
ALTER TABLE `supplier_feedbacks`
  ADD CONSTRAINT `supplier_feedbacks_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_feedbacks_ibfk_2` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tours`
--
ALTER TABLE `tours`
  ADD CONSTRAINT `fk_tours_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tours_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `tour_categories` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `tour_assignments`
--
ALTER TABLE `tour_assignments`
  ADD CONSTRAINT `tour_assignments_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tour_assignments_ibfk_2` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `tour_departures`
--
ALTER TABLE `tour_departures`
  ADD CONSTRAINT `fk_departures_version` FOREIGN KEY (`version_id`) REFERENCES `tour_versions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_td_tour` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_td_version` FOREIGN KEY (`version_id`) REFERENCES `tour_versions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tour_feedbacks`
--
ALTER TABLE `tour_feedbacks`
  ADD CONSTRAINT `tour_feedbacks_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tour_feedbacks_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `tour_gallery_images`
--
ALTER TABLE `tour_gallery_images`
  ADD CONSTRAINT `tour_gallery_images_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tour_logs`
--
ALTER TABLE `tour_logs`
  ADD CONSTRAINT `tour_logs_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tour_logs_ibfk_2` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tour_partner_services`
--
ALTER TABLE `tour_partner_services`
  ADD CONSTRAINT `tour_partner_services_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tour_policy_assignments`
--
ALTER TABLE `tour_policy_assignments`
  ADD CONSTRAINT `tour_policy_assignments_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tour_policy_assignments_ibfk_2` FOREIGN KEY (`policy_id`) REFERENCES `tour_policies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tour_pricing_options`
--
ALTER TABLE `tour_pricing_options`
  ADD CONSTRAINT `tour_pricing_options_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tour_version_prices`
--
ALTER TABLE `tour_version_prices`
  ADD CONSTRAINT `tour_version_prices_ibfk_1` FOREIGN KEY (`version_id`) REFERENCES `tour_versions` (`id`),
  ADD CONSTRAINT `tour_version_prices_ibfk_2` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `version_dynamic_pricing`
--
ALTER TABLE `version_dynamic_pricing`
  ADD CONSTRAINT `fk_vdp_departure` FOREIGN KEY (`departure_id`) REFERENCES `tour_departures` (`id`),
  ADD CONSTRAINT `fk_vdp_version` FOREIGN KEY (`version_id`) REFERENCES `tour_versions` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
