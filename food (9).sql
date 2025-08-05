-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2025 at 11:36 AM
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
-- Database: `food`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `food_items_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `role_as` tinyint(4) NOT NULL,
  `otp` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `name`, `email`, `password`, `role_as`, `otp`, `status`, `created_at`) VALUES
(2, 'admin', 'admin@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$TUhOTlNpbmIwVll4SzdCbQ$rViiKA99Uu1bgbOXUT9PjbZccOo8oIkx9jQjX4hloqw', 1, '', 1, '2025-05-29 14:43:12'),
(3, 'Prajwol', 'prajwol@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$eHpacThsNEt6OEhGektNVA$kyWOey3HxFC0geIYYmgcl194AZKGZVexjsfjS7rdm9c', 0, '', 1, '2025-05-27 17:49:55'),
(9, 'Ashwin Chaudhary', 'bisojchaudhary111@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$ODcuTGI3ajFyaDFBRHJHTA$yZjTN2ejBN3B1LdU4keBx05tKpwAMxljQF2iHi9Qhjw', 0, '529501', 1, '2025-05-29 06:44:09'),
(12, 'Ashwin Chau', 'ashwinchaudhary511@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$d3hMVGZWSi4wYnY5RFliOA$hyj8YVyKron7gBJr4C1xnml/tW8DVWx0eGZN9+PFrec', 0, '522750', 1, '2025-08-05 08:11:18'),
(13, 'ashwin', 'ashwinchaudhary360@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$OGJqTFcwV0VGR3pNaDVDZw$Kgwvd7dixttlITsDe5qtzXx+86R6T8K7lIeLRnZYQDE', 0, '', 1, '2025-05-27 17:37:41'),
(14, 'ashwin', 'ashwinchaudhary202@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$SkhLWVJYZ1RrZUJDcUplSA$fyZbjFLDG03uQl1AaHwt9OKQ4pR6/zH9R5KnUYRKH/k', 0, '', 1, '2025-05-27 17:48:19'),
(15, 'Prajwol', 'prajwol1023@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$aGZRRzFwOWN2Z1owOWp2Qg$Ws8Y1/iQ2YQspapAbisaTcgSXFRe/12/+f1NsSrR46A', 0, '', 1, '2025-06-01 01:15:30'),
(16, 'Pratik', 'prateekgiree7@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$UXVvcjJ3ejRaY2tRT3RZNw$ed3Gz+b1lQNaaFu9fWxOff/gRNfa167MQwSA4Cclytg', 0, '', 1, '2025-07-30 01:54:16');

-- --------------------------------------------------------

--
-- Table structure for table `food_items`
--

CREATE TABLE `food_items` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `description` mediumtext NOT NULL,
  `image` varchar(200) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `trending` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food_items`
--

INSERT INTO `food_items` (`id`, `menu_id`, `name`, `slug`, `description`, `image`, `quantity`, `price`, `status`, `trending`, `created_at`) VALUES
(5, 5, 'Chicken Momo', 'chicken momo', 'dvfbbt', '1747837626.png', 0, 200, 0, 1, '2025-08-05 08:31:29'),
(6, 5, 'Buff Momo', 'buff momo', 'cdvfbgn', '1747837693.png', 0, 100, 0, 1, '2025-08-05 08:31:30'),
(7, 6, 'Chicken Pizza', 'chicken pizza', 'scdvvf', '1747837754.png', 0, 400, 0, 1, '2025-08-04 02:28:52'),
(9, 5, 'Cardio', 'cardio', 'bgnhnmhm', '1754323424.png', 186, 60, 0, 0, '2025-08-05 09:23:59');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `description` mediumtext NOT NULL,
  `status` tinyint(4) NOT NULL,
  `popular` tinyint(4) NOT NULL,
  `image` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `name`, `slug`, `description`, `status`, `popular`, `image`, `created_at`) VALUES
(5, 'Momo', 'momo', 'jbcsjkcd', 0, 1, '1754322580.png', '2025-08-05 08:26:37'),
(6, 'Pizza', 'pizza', 'dhucdnvjkd', 0, 0, '1747837540.png', '2025-05-21 14:26:02'),
(7, 'Cake', 'cake', 'cdvfbb', 0, 1, '1747840804.png', '2025-05-21 15:20:04'),
(8, 'Burger', 'burger', 'dcdvv', 0, 1, '1747840896.png', '2025-05-21 15:21:36'),
(9, 'Pasta', 'pasta', 'scdvcdvb', 0, 1, '1747841102.png', '2025-05-21 15:25:34');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_no` varchar(200) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `zipcode` varchar(200) NOT NULL,
  `address` varchar(200) NOT NULL,
  `total_price` int(11) NOT NULL,
  `payment_mode` varchar(200) NOT NULL,
  `notes` mediumtext NOT NULL,
  `cancel_reason` mediumtext NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_no`, `customer_id`, `name`, `email`, `phone`, `zipcode`, `address`, `total_price`, `payment_mode`, `notes`, `cancel_reason`, `status`, `created_at`) VALUES
(19, 'foodhub4696969696', 2, 'Ashwin Chaudhary', 'ashwinchaudhary511@gmail.com', '9896969696', '234', 'Rapti-09', 1000, 'COD', '', '', 2, '2025-05-30 14:59:20'),
(20, 'foodhub3196969696', 2, 'Ashwin Chaudhary', 'ashwinchaudhary511@gmail.com', '9896969696', '234', 'Rapti-09', 900, 'COD', '', '', 2, '2025-05-30 15:31:01'),
(21, 'foodhub6196969696', 2, 'Ashwin Chaudhary', 'ashwinchaudhary511@gmail.com', '9896969696', '234', 'Rapti-09', 400, 'COD', '', '', 2, '2025-05-31 05:53:11'),
(22, 'foodhub1596969696', 2, 'ashwin', 'ashwinchaudhary511@gmail.com', '9896969696', '234', 'Rapti-09', 800, 'COD', '', '', 2, '2025-08-01 02:29:10'),
(23, 'foodhub3696969696', 15, 'ashwin', 'ashwinchaudhary511@gmail.com', '9896969696', '234', 'Rapti-09', 400, 'COD', '', '', 2, '2025-06-01 01:22:07'),
(24, 'foodhub7196969696', 2, 'ashwin', 'ashwinchaudhary511@gmail.com', '9896969696', '234', 'Rapti-09', 1000, 'COD', '', 'no money', 3, '2025-06-01 02:20:27'),
(25, 'foodhub8696969696', 16, 'ashwin', 'ashwinchaudhary511@gmail.com', '9896969696', '234', 'Rapti-09', 800, 'COD', '', '', 2, '2025-08-05 02:21:02'),
(26, 'foodhub2196969696', 12, 'ashwin', 'ashwinchaudhary511@gmail.com', '9896969696', '234', 'Rapti-09', 300, 'COD', '', '', 2, '2025-08-05 02:37:28'),
(27, 'foodhub6996969696', 12, 'ashwin', 'ashwinchaudhary511@gmail.com', '9896969696', '234', 'Rapti-09', 1000, 'COD', '', 'fbfbfb', 3, '2025-08-05 02:38:30'),
(28, 'foodhub1396969696', 12, 'Ashwin Chaudhary', 'ashwinchaudhary511@gmail.com', '9896969696', '234', 'Rapti-09', 1000, 'COD', '', '', 2, '2025-08-05 09:27:06'),
(29, 'foodhub6496969696', 12, 'Ashwin Chaudhary', 'ashwinchaudhary511@gmail.com', '9896969696', '234', 'Rapti-09', 2200, 'COD', '', '', 2, '2025-08-05 07:52:36'),
(30, 'foodhub7996969696', 12, 'Ashwin Chaudhary', 'ashwinchaudhary511@gmail.com', '9896969696', '234', 'Rapti-09', 700, 'COD', '', 'dvv', 3, '2025-08-05 08:31:38'),
(31, 'foodhub6296969696', 12, 'Ashwin Chaudhary', 'ashwinchaudhary511@gmail.com', '9896969696', '234', 'Rapti-09', 300, 'COD', '', 'scdd', 3, '2025-08-05 08:43:41'),
(32, 'foodhub3596969696', 12, 'Ashwin Chaudhary', 'ashwinchaudhary511@gmail.com', '9896969696', '234', 'Rapti-09', 300, 'COD', '', '695', 3, '2025-08-05 08:58:06'),
(33, 'foodhub4896969696', 12, 'Ashwin Chaudhary', 'ashwinchaudhary511@gmail.com', '9896969696', '234', 'Rapti-09', 240, 'COD', '', 'nhmhm', 3, '2025-08-05 09:18:21'),
(34, 'foodhub5696969696', 12, 'ashwin', 'ashwinchaudhary511@gmail.com', '9896969696', '234', 'Rapti-09', 360, 'COD', '', 'hbtgn', 3, '2025-08-05 09:20:37'),
(35, 'foodhub1496969696', 12, 'Ashwin Chaudhary', 'ashwinchaudhary511@gmail.com', '9896969696', '234', 'Rapti-09', 240, 'COD', '', 'ngngn', 3, '2025-08-05 09:21:40'),
(36, 'foodhub5396969696', 12, 'Ashwin Chaudhary', 'ashwinchaudhary511@gmail.com', '9896969696', '234', 'Rapti-09', 240, 'COD', '', '', 1, '2025-08-05 09:24:14');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `food_items_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `food_items_id`, `quantity`, `price`, `created_at`) VALUES
(20, 19, 5, 5, 200, '2025-05-30 14:58:51'),
(21, 20, 6, 9, 100, '2025-05-30 15:30:49'),
(22, 21, 6, 4, 100, '2025-05-31 05:53:02'),
(23, 22, 5, 4, 200, '2025-05-31 06:30:14'),
(24, 23, 6, 4, 100, '2025-06-01 01:16:32'),
(25, 24, 5, 5, 200, '2025-06-01 02:11:05'),
(26, 25, 5, 4, 200, '2025-07-30 01:55:09'),
(27, 26, 6, 3, 100, '2025-08-05 02:37:03'),
(28, 27, 5, 5, 200, '2025-08-05 02:38:11'),
(29, 28, 5, 5, 200, '2025-08-05 02:41:04'),
(30, 29, 5, 11, 200, '2025-08-05 07:51:27'),
(31, 30, 5, 3, 200, '2025-08-05 08:31:29'),
(32, 30, 6, 1, 100, '2025-08-05 08:31:29'),
(33, 31, 9, 5, 60, '2025-08-05 08:33:13'),
(34, 32, 9, 5, 60, '2025-08-05 08:55:01'),
(35, 33, 9, 4, 60, '2025-08-05 09:05:25'),
(36, 34, 9, 6, 60, '2025-08-05 09:20:22'),
(37, 35, 9, 4, 60, '2025-08-05 09:21:28'),
(38, 36, 9, 4, 60, '2025-08-05 09:23:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `food_items_id` (`food_items_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `food_items`
--
ALTER TABLE `food_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `food_items_ibfk_1` (`menu_id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `food_items_id` (`food_items_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `food_items`
--
ALTER TABLE `food_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`food_items_id`) REFERENCES `food_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `food_items`
--
ALTER TABLE `food_items`
  ADD CONSTRAINT `food_items_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`food_items_id`) REFERENCES `food_items` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
