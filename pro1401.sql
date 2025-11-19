-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th10 10, 2025 lúc 12:28 AM
-- Phiên bản máy phục vụ: 8.4.3
-- Phiên bản PHP: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `pro1401`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bookings`
--

CREATE TABLE `bookings` (
  `id` int NOT NULL,
  `tour_id` int DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `version_id` int DEFAULT NULL,
  `booking_date` datetime DEFAULT NULL,
  `total_price` decimal(15,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `bookings`
--

INSERT INTO `bookings` (`id`, `tour_id`, `customer_id`, `version_id`, `booking_date`, `total_price`, `status`, `notes`) VALUES
(1, 1, 3, 1, '2025-11-06 23:07:34', 3700000.00, 'cho_xac_nhan', ''),
(2, 2, 3, 2, '2025-11-06 23:07:34', 8500000.00, 'da_coc', 'Đã đặt cọc 30%'),
(3, 3, 3, NULL, '2025-11-06 23:07:34', 5200000.00, 'hoan_tat', 'Đã thanh toán đủ');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `booking_customers`
--

CREATE TABLE `booking_customers` (
  `id` int NOT NULL,
  `booking_id` int DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `id_card` varchar(50) DEFAULT NULL,
  `special_request` text,
  `room_type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `booking_customers`
--

INSERT INTO `booking_customers` (`id`, `booking_id`, `name`, `gender`, `birth_date`, `phone`, `id_card`, `special_request`, `room_type`) VALUES
(1, 1, 'Le Thi X', 'F', '1995-03-12', '0907777777', '012345678', 'Ăn chay', 'đôi'),
(2, 2, 'Nguyen Van Y', 'M', '1990-08-20', '0908888888', '987654321', '', 'ghép'),
(3, 3, 'Tran Thi Z', 'F', '1987-01-15', '0909999999', '111111111', 'Dị ứng hải sản', 'đơn');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `financial_reports`
--

CREATE TABLE `financial_reports` (
  `id` int NOT NULL,
  `tour_id` int DEFAULT NULL,
  `total_revenue` decimal(15,2) DEFAULT NULL,
  `total_expense` decimal(15,2) DEFAULT NULL,
  `profit` decimal(15,2) DEFAULT NULL,
  `report_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `financial_reports`
--

INSERT INTO `financial_reports` (`id`, `tour_id`, `total_revenue`, `total_expense`, `profit`, `report_date`) VALUES
(1, 1, 10000000.00, 7000000.00, 3000000.00, '2025-11-06 23:07:35'),
(2, 2, 25000000.00, 20000000.00, 5000000.00, '2025-11-06 23:07:35'),
(3, 3, 15000000.00, 9000000.00, 6000000.00, '2025-11-06 23:07:35');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `guides`
--

CREATE TABLE `guides` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `languages` varchar(255) DEFAULT NULL,
  `experience_years` int DEFAULT NULL,
  `rating` float DEFAULT NULL,
  `health_status` varchar(255) DEFAULT NULL,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `guides`
--

INSERT INTO `guides` (`id`, `user_id`, `languages`, `experience_years`, `rating`, `health_status`, `notes`) VALUES
(1, 2, 'Tiếng Việt, Tiếng Anh', 3, 4.8, 'Tốt', ''),
(2, 2, 'Tiếng Việt, Tiếng Trung', 5, 4.6, 'Tốt', 'Chuyên tour TQ'),
(3, 2, 'Tiếng Việt', 2, 4.4, 'Tốt', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `itineraries`
--

CREATE TABLE `itineraries` (
  `id` int NOT NULL,
  `tour_id` int DEFAULT NULL,
  `day_number` int DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `activities` text,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `itineraries`
--

INSERT INTO `itineraries` (`id`, `tour_id`, `day_number`, `title`, `activities`, `image_url`) VALUES
(1, 1, 1, 'Ngày 1 Đà Nẵng', 'Thăm biển Mỹ Khê', 'img1.jpg'),
(2, 1, 2, 'Ngày 2 Bà Nà Hills', 'Tham quan cầu vàng', 'img2.jpg'),
(3, 2, 1, 'Ngày 1 Singapore', 'Gardens by the Bay', 'img3.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `message` text,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `link`, `is_read`, `created_at`) VALUES
(1, 1, 'Có booking mới', '/admin/bookings', 0, '2025-11-06 23:07:35'),
(2, 3, 'Đơn tour của bạn đã xác nhận', '/my-bookings', 0, '2025-11-06 23:07:35'),
(3, 2, 'Bạn được phân công hướng dẫn tour mới', '/guide/tours', 0, '2025-11-06 23:07:35');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `rating` float DEFAULT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `type`, `contact_person`, `phone`, `email`, `address`, `rating`, `description`) VALUES
(1, 'Hotel Sunrise', 'hotel', 'Mr. Phong', '0904444444', 'sunrise@hotel.com', 'Da Nang', 4.5, 'Khách sạn gần biển'),
(2, 'TravelExpress', 'transport', 'Ms. Hoa', '0905555555', 'contact@travelx.com', 'HCM', 4, 'Dịch vụ xe du lịch'),
(3, 'VisaPro', 'visa', 'Mr. Tuan', '0906666666', 'support@visapro.com', 'Ha Noi', 4.2, 'Dịch vụ hỗ trợ visa');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `supplier_contracts`
--

CREATE TABLE `supplier_contracts` (
  `id` int NOT NULL,
  `supplier_id` int DEFAULT NULL,
  `contract_name` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `price_info` text,
  `status` varchar(50) DEFAULT NULL,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `supplier_contracts`
--

INSERT INTO `supplier_contracts` (`id`, `supplier_id`, `contract_name`, `start_date`, `end_date`, `price_info`, `status`, `notes`) VALUES
(1, 1, 'Hợp đồng Khách Sạn 2025', '2025-01-01', '2025-12-31', 'Giá phòng ưu đãi', 'active', ''),
(2, 2, 'Hợp đồng Xe 2025', '2025-01-01', '2025-12-31', 'Giá xe theo KM', 'active', ''),
(3, 3, 'Hợp đồng Visa 2025', '2025-01-01', '2025-12-31', 'Giá dịch vụ cố định', 'expired', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `supplier_feedbacks`
--

CREATE TABLE `supplier_feedbacks` (
  `id` int NOT NULL,
  `supplier_id` int DEFAULT NULL,
  `guide_id` int DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `comment` text,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `supplier_feedbacks`
--

INSERT INTO `supplier_feedbacks` (`id`, `supplier_id`, `guide_id`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 1, 5, 'Khách sạn sạch sẽ', '2025-11-06 23:07:35'),
(2, 2, 2, 4, 'Xe chất lượng ổn', '2025-11-06 23:07:35'),
(3, 3, 3, 5, 'Xử lý visa nhanh', '2025-11-06 23:07:35');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tours`
--

CREATE TABLE `tours` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `description` text,
  `base_price` decimal(15,2) DEFAULT NULL,
  `policy` text,
  `supplier_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `tours`
--

INSERT INTO `tours` (`id`, `name`, `type`, `description`, `base_price`, `policy`, `supplier_id`, `created_at`, `updated_at`) VALUES
(1, 'Tour Da Nang 3N2D', 'trong_nuoc', 'Thăm quan Đà Nẵng', 3500000.00, 'Không hoàn hủy', 1, '2025-11-06 23:07:34', '2025-11-06 23:07:34'),
(2, 'Tour Singapore 4N3D', 'quoc_te', 'Khám phá Singapore hiện đại', 9000000.00, 'Hoàn 50% trước 7 ngày', 2, '2025-11-06 23:07:34', '2025-11-06 23:07:34'),
(3, 'Tour Đặt Theo Yêu Cầu', 'theo_yeu_cau', 'Tùy chỉnh lịch trình', 5000000.00, 'Liên hệ tư vấn', 3, '2025-11-06 23:07:34', '2025-11-06 23:07:34');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_assignments`
--

CREATE TABLE `tour_assignments` (
  `id` int NOT NULL,
  `tour_id` int DEFAULT NULL,
  `guide_id` int DEFAULT NULL,
  `driver_name` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `tour_assignments`
--

INSERT INTO `tour_assignments` (`id`, `tour_id`, `guide_id`, `driver_name`, `start_date`, `end_date`, `status`) VALUES
(1, 1, 1, 'Bác Lân', '2025-06-01', '2025-06-03', 'planned'),
(2, 2, 2, 'Anh Tuấn', '2025-03-05', '2025-03-09', 'ongoing'),
(3, 3, 3, 'Chú Hải', '2025-12-05', '2025-12-08', 'completed');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_feedbacks`
--

CREATE TABLE `tour_feedbacks` (
  `id` int NOT NULL,
  `tour_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `comment` text,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `tour_feedbacks`
--

INSERT INTO `tour_feedbacks` (`id`, `tour_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 3, 5, 'Tour rất tuyệt!', '2025-11-06 23:07:34'),
(2, 2, 3, 4, 'Singapore đẹp nhưng hơi đắt', '2025-11-06 23:07:34'),
(3, 3, 3, 5, 'Chất lượng dịch vụ tốt', '2025-11-06 23:07:34');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_logs`
--

CREATE TABLE `tour_logs` (
  `id` int NOT NULL,
  `tour_id` int DEFAULT NULL,
  `guide_id` int DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `description` text,
  `issue` text,
  `solution` text,
  `customer_feedback` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `tour_logs`
--

INSERT INTO `tour_logs` (`id`, `tour_id`, `guide_id`, `date`, `description`, `issue`, `solution`, `customer_feedback`) VALUES
(1, 1, 1, '2025-11-06 23:07:35', 'Ngày 1 di chuyển ok', '', '', ''),
(2, 2, 2, '2025-11-06 23:07:35', 'Ngày 2 tham quan đảo', 'Trời mưa', 'Đổi thời gian', 'Khách hài lòng'),
(3, 3, 3, '2025-11-06 23:07:35', 'Ngày cuối trả phòng', '', '', 'Khách vui');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_versions`
--

CREATE TABLE `tour_versions` (
  `id` int NOT NULL,
  `tour_id` int DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `price` decimal(15,2) DEFAULT NULL,
  `notes` text,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `tour_versions`
--

INSERT INTO `tour_versions` (`id`, `tour_id`, `name`, `start_date`, `end_date`, `price`, `notes`, `created_at`) VALUES
(1, 1, 'Mùa Hè', '2025-06-01', '2025-08-30', 3700000.00, 'Áp dụng lễ 2/9', '2025-11-06 23:07:34'),
(2, 2, 'Khuyến Mãi', '2025-03-01', '2025-03-31', 8500000.00, 'Giảm giá tháng 3', '2025-11-06 23:07:34'),
(3, 3, 'Đặc Biệt', '2025-12-01', '2025-12-31', 5200000.00, 'Gói cao cấp', '2025-11-06 23:07:34');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `transactions`
--

CREATE TABLE `transactions` (
  `id` int NOT NULL,
  `booking_id` int DEFAULT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `method` varchar(100) DEFAULT NULL,
  `description` text,
  `date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `transactions`
--

INSERT INTO `transactions` (`id`, `booking_id`, `amount`, `type`, `method`, `description`, `date`) VALUES
(1, 1, 1000000.00, 'thu', 'chuyển khoản', 'Tiền cọc', '2025-11-06 23:07:35'),
(2, 2, 3000000.00, 'thu', 'tiền mặt', 'Đặt cọc', '2025-11-06 23:07:35'),
(3, 3, 5200000.00, 'thu', 'online', 'Thanh toán đủ', '2025-11-06 23:07:35');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `role`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Nguyen Van A', 'a@example.com', '0901111111', 'admin', '123456', '2025-11-06 23:07:33', '2025-11-06 23:07:33'),
(2, 'Tran Thi B', 'b@example.com', '0902222222', 'hdv', '123456', '2025-11-06 23:07:33', '2025-11-06 23:07:33'),
(3, 'Le Van C', 'c@example.com', '0903333333', 'customer', '123456', '2025-11-06 23:07:33', '2025-11-06 23:07:33');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `version_id` (`version_id`);

--
-- Chỉ mục cho bảng `booking_customers`
--
ALTER TABLE `booking_customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Chỉ mục cho bảng `financial_reports`
--
ALTER TABLE `financial_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Chỉ mục cho bảng `guides`
--
ALTER TABLE `guides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `itineraries`
--
ALTER TABLE `itineraries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Chỉ mục cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `supplier_contracts`
--
ALTER TABLE `supplier_contracts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Chỉ mục cho bảng `supplier_feedbacks`
--
ALTER TABLE `supplier_feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `guide_id` (`guide_id`);

--
-- Chỉ mục cho bảng `tours`
--
ALTER TABLE `tours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Chỉ mục cho bảng `tour_assignments`
--
ALTER TABLE `tour_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `guide_id` (`guide_id`);

--
-- Chỉ mục cho bảng `tour_feedbacks`
--
ALTER TABLE `tour_feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `tour_logs`
--
ALTER TABLE `tour_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `guide_id` (`guide_id`);

--
-- Chỉ mục cho bảng `tour_versions`
--
ALTER TABLE `tour_versions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Chỉ mục cho bảng `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `booking_customers`
--
ALTER TABLE `booking_customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `financial_reports`
--
ALTER TABLE `financial_reports`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `guides`
--
ALTER TABLE `guides`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `itineraries`
--
ALTER TABLE `itineraries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `supplier_contracts`
--
ALTER TABLE `supplier_contracts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `supplier_feedbacks`
--
ALTER TABLE `supplier_feedbacks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `tours`
--
ALTER TABLE `tours`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `tour_assignments`
--
ALTER TABLE `tour_assignments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `tour_feedbacks`
--
ALTER TABLE `tour_feedbacks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `tour_logs`
--
ALTER TABLE `tour_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `tour_versions`
--
ALTER TABLE `tour_versions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`version_id`) REFERENCES `tour_versions` (`id`);

--
-- Các ràng buộc cho bảng `booking_customers`
--
ALTER TABLE `booking_customers`
  ADD CONSTRAINT `booking_customers_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);

--
-- Các ràng buộc cho bảng `financial_reports`
--
ALTER TABLE `financial_reports`
  ADD CONSTRAINT `financial_reports_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`);

--
-- Các ràng buộc cho bảng `guides`
--
ALTER TABLE `guides`
  ADD CONSTRAINT `guides_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `itineraries`
--
ALTER TABLE `itineraries`
  ADD CONSTRAINT `itineraries_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`);

--
-- Các ràng buộc cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `supplier_contracts`
--
ALTER TABLE `supplier_contracts`
  ADD CONSTRAINT `supplier_contracts_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`);

--
-- Các ràng buộc cho bảng `supplier_feedbacks`
--
ALTER TABLE `supplier_feedbacks`
  ADD CONSTRAINT `supplier_feedbacks_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  ADD CONSTRAINT `supplier_feedbacks_ibfk_2` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`);

--
-- Các ràng buộc cho bảng `tours`
--
ALTER TABLE `tours`
  ADD CONSTRAINT `tours_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`);

--
-- Các ràng buộc cho bảng `tour_assignments`
--
ALTER TABLE `tour_assignments`
  ADD CONSTRAINT `tour_assignments_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`),
  ADD CONSTRAINT `tour_assignments_ibfk_2` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`);

--
-- Các ràng buộc cho bảng `tour_feedbacks`
--
ALTER TABLE `tour_feedbacks`
  ADD CONSTRAINT `tour_feedbacks_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`),
  ADD CONSTRAINT `tour_feedbacks_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `tour_logs`
--
ALTER TABLE `tour_logs`
  ADD CONSTRAINT `tour_logs_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`),
  ADD CONSTRAINT `tour_logs_ibfk_2` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`);

--
-- Các ràng buộc cho bảng `tour_versions`
--
ALTER TABLE `tour_versions`
  ADD CONSTRAINT `tour_versions_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`);

--
-- Các ràng buộc cho bảng `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
