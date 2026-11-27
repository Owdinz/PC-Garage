-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2026 at 10:59 AM
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
-- Database: `pc_garage`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`cart_id`, `user_id`, `created_at`) VALUES
(1, 2, '2026-11-25 14:20:42');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_item_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_builds`
--

CREATE TABLE `custom_builds` (
  `build_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `build_name` varchar(255) DEFAULT NULL,
  `total_price` decimal(12,2) DEFAULT NULL,
  `total_wattage` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_build_components`
--

CREATE TABLE `custom_build_components` (
  `component_id` int(11) NOT NULL,
  `build_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `delivery_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `courier_name` varchar(100) DEFAULT NULL,
  `rider_name` varchar(100) DEFAULT NULL,
  `rider_contact` varchar(20) DEFAULT NULL,
  `current_location` varchar(255) DEFAULT NULL,
  `estimated_delivery` date DEFAULT NULL,
  `delivery_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(12,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `shipping_method` varchar(50) DEFAULT NULL,
  `order_status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `checkout_request_id` char(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_amount`, `payment_method`, `shipping_method`, `order_status`, `created_at`, `checkout_request_id`) VALUES
(1, 2, 219995.00, 'cod', 'standard', 'Pending', '2026-11-25 15:38:49', NULL),
(2, 2, 1795.00, 'cod', 'standard', 'Pending', '2026-11-25 17:07:13', 'bd47cbb27137cace390587f3ef7832ae9bd28a1970e5396bdd86739c12d1dfa9'),
(3, 2, 899990.00, 'cod', 'standard', 'Pending', '2026-11-25 17:13:57', 'c30048a31c4436becfc48f6e94a81460761511b6ef9b275ebea7c6295e7dfdce'),
(4, 2, 699580.00, 'cod', 'standard', 'Pending', '2026-11-25 19:19:40', '39924deca3e6f8b8494f8d0d01db44798a2770874b8640a3917da2aef5abff22'),
(5, 2, 242495.00, 'cod', 'standard', 'Pending', '2026-11-26 13:40:29', '4ebdcdd7c65a76f49ca33884ed8c1f0b87e7bf89a5fbb1b054cc18608e7c0e28');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 1, 1, 219995.00),
(2, 2, 9, 1, 1795.00),
(3, 3, 2, 2, 449995.00),
(4, 4, 1, 1, 219995.00),
(5, 4, 2, 1, 449995.00),
(6, 4, 4, 1, 7990.00),
(7, 4, 6, 3, 7200.00),
(8, 5, 1, 1, 219995.00),
(9, 5, 3, 1, 22500.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_shipping_details`
--

CREATE TABLE `order_shipping_details` (
  `shipping_detail_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `contact_number` varchar(30) NOT NULL,
  `email` varchar(255) NOT NULL,
  `shipping_address` text NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `delivery_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_shipping_details`
--

INSERT INTO `order_shipping_details` (`shipping_detail_id`, `order_id`, `full_name`, `contact_number`, `email`, `shipping_address`, `postal_code`, `delivery_notes`, `created_at`) VALUES
(1, 1, 'PGJ', '145231', 'pgj@gmail.com', 'Maganda St.', '4000', 'N/A', '2026-11-25 15:38:49'),
(2, 2, 'PGJ', '41-000-1234', 'pgj@gmail.com', 'Batumbakal Metro Manila', '2050', 'testing', '2026-11-25 17:07:13'),
(3, 3, 'PGJ', '111', 'pgj@gmail.com', 'Batumbakal Metro Manila', '2314', 'order test#2', '2026-11-25 17:13:57'),
(4, 4, 'tester_test', '2123', 'pgj@gmail.com', 'Maganda St.', '0123', 'test part 2 checkout', '2026-11-25 19:19:40'),
(5, 5, 'PGJ', '09123456789', 'pgj@gmail.com', 'Maganda St.', '2050', 'Call me this 09123456789 when arrived.', '2026-11-26 13:40:29');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `brand` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `image_url` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `category`, `brand`, `description`, `price`, `stock_quantity`, `image_url`) VALUES
(1, 'ASUS ROG STRIX SCAR 18', 'Laptop', 'ASUS', NULL, 219995.00, 7, NULL),
(2, 'RTX 5090 Astral', 'GPU', 'ASUS', NULL, 449995.00, 2, 'https://dynaquestpc.com/cdn/shop/files/240_cd13e66f-f0e7-4e8c-91ea-3f3b9713501b.png?v=1745902822&width=1080'),
(3, 'AMD Ryzen 7 7800X3D', 'CPU', 'AMD', NULL, 22500.00, 14, 'https://m.media-amazon.com/images/I/51HqC0rU9HL._AC_UF894,1000_QL80_.jpg'),
(4, 'Kingston Fury Beast DDR5', 'RAM', 'Kingston', NULL, 7990.00, 49, 'https://images-na.ssl-images-amazon.com/images/I/61yE2Ut4mBL.jpg'),
(5, 'Samsung 990 Pro 2TB', 'Storage', 'Samsung', NULL, 10500.00, 20, 'https://gameone.ph/media/catalog/product/mpiowebpcache/d378a0f20f83637cdb1392af8dc032a2/s/a/samsung_2tb_990_pro_pcie_4.0_nvme_m.2_ssd-4_1.webp'),
(6, 'Corsair RM750x', 'PSU', 'Corsair', 'Power Supply Unit', 7200.00, 7, 'https://assets.corsair.com/image/upload/f_auto,q_auto/content/CP-9020179-NA-RM750x-PSU-01.png'),
(7, 'ASUS TUF B650M Plus', 'Motherboard', 'ASUS', 'AM5 Motherboard', 11500.00, 5, 'https://noveltycomputech.com/cdn/shop/files/tuf-gaming-b850m-plus-image-2.jpg?v=1752822179&width=1214'),
(8, 'NZXT H5 Flow', 'PC Case', 'NZXT', 'Mid Tower Case', 4500.00, 8, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSmmg0X0VxXX0AtuyLXy8OTP8AexqYF05Lh0BUv4ZjfRS_sSSsOQAR9yIN5&s=10'),
(9, 'Logitech G304', 'Peripheral', 'Logitech', 'Wireless Gaming Mouse', 1795.00, 14, 'https://computerarenakh.com/image/catalog/1.DARA/0001-ROG%20RYUJIN%20III%202023/g304-black-original-cac.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `trade_requests`
--

CREATE TABLE `trade_requests` (
  `trade_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `part_name` varchar(255) DEFAULT NULL,
  `item_condition` varchar(50) DEFAULT NULL,
  `expected_price` decimal(12,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Under Review',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trade_requests`
--

INSERT INTO `trade_requests` (`trade_id`, `user_id`, `category`, `part_name`, `item_condition`, `expected_price`, `notes`, `status`, `submitted_at`) VALUES
(1, 2, 'gpu', 'GeForce RTX 5090', 'like-new', 4152.00, '', 'Under Review', '2026-11-24 18:29:01'),
(2, 2, 'cpu', 'AMD Ryzen 5 5600 6-Core 12-Thread', 'good', 10000.00, '', 'Under Review', '2026-11-24 18:33:45'),
(3, 3, 'gpu', 'GeForce RTX 5090', 'good', 10000.00, 'Final Testing of PC Garage before push \r\nTESTING delete later', 'Under Review', '2026-11-27 08:58:19');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password_hash`, `phone`, `address`, `city`, `created_at`) VALUES
(2, 'PGJ (BUYER)', 'pgj@gmail.com', '$2y$10$wufWEbYZz65M1bzl12WjTOnJ2zEdQUayK1rTLq9kbV.mNOdiZ/wLu', '09512342511', 'Maganda St.', 'Malolos Bulacan', '2026-11-24 14:59:50'),
(3, 'Pencil Lee', 'pencil@gmail.com', '$2y$10$AaocuwKcJYsNLIcs5DvaEu06i0FpU/txg68QhPtUavoldV.dxSEdO', NULL, NULL, NULL, '2026-11-27 08:57:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `custom_builds`
--
ALTER TABLE `custom_builds`
  ADD PRIMARY KEY (`build_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `custom_build_components`
--
ALTER TABLE `custom_build_components`
  ADD PRIMARY KEY (`component_id`),
  ADD KEY `build_id` (`build_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`delivery_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `unique_checkout_request` (`checkout_request_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `order_shipping_details`
--
ALTER TABLE `order_shipping_details`
  ADD PRIMARY KEY (`shipping_detail_id`),
  ADD UNIQUE KEY `unique_order_shipping` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `trade_requests`
--
ALTER TABLE `trade_requests`
  ADD PRIMARY KEY (`trade_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_builds`
--
ALTER TABLE `custom_builds`
  MODIFY `build_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_build_components`
--
ALTER TABLE `custom_build_components`
  MODIFY `component_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `delivery_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `order_shipping_details`
--
ALTER TABLE `order_shipping_details`
  MODIFY `shipping_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `trade_requests`
--
ALTER TABLE `trade_requests`
  MODIFY `trade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`cart_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `custom_builds`
--
ALTER TABLE `custom_builds`
  ADD CONSTRAINT `custom_builds_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `custom_build_components`
--
ALTER TABLE `custom_build_components`
  ADD CONSTRAINT `custom_build_components_ibfk_1` FOREIGN KEY (`build_id`) REFERENCES `custom_builds` (`build_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `custom_build_components_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD CONSTRAINT `deliveries_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_shipping_details`
--
ALTER TABLE `order_shipping_details`
  ADD CONSTRAINT `fk_order_shipping_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `trade_requests`
--
ALTER TABLE `trade_requests`
  ADD CONSTRAINT `trade_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
