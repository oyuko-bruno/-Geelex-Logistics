-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2025 at 02:15 PM
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
-- Database: `geelex_logistics`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `pickup_location` varchar(255) NOT NULL,
  `item_type` varchar(100) NOT NULL,
  `destination_location` varchar(255) NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `booking_time` datetime DEFAULT current_timestamp(),
  `estimated_delivery_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `client_id`, `pickup_location`, `item_type`, `destination_location`, `booking_date`, `booking_time`, `estimated_delivery_time`) VALUES
(1, 1, 'Kiganjo', 'Fragile', 'Nakuru', '2025-04-13 15:16:02', '2025-04-13 18:26:58', NULL),
(2, 1, 'Kiganjo', 'Fragile', 'Nakuru', '2025-04-13 15:21:08', '2025-04-13 18:26:58', NULL),
(3, 1, 'kitui', 'Office Relocation', 'Mombasa', '2025-04-13 15:21:55', '2025-04-13 18:26:58', NULL),
(4, 1, 'Migori', 'House Moving', 'Nakuru', '2025-04-13 15:23:07', '2025-04-13 18:26:58', NULL),
(5, 1, 'Migori', 'House Moving', 'Nakuru', '2025-04-13 15:24:05', '2025-04-13 18:26:58', NULL),
(6, 1, 'kitui', 'Fragile', 'Nakuru', '2025-04-13 15:25:03', '2025-04-13 18:26:58', NULL),
(7, 1, 'Kiganjo', 'House Moving', 'Nakuru', '2025-04-13 15:28:54', '2025-04-13 17:28:54', '2025-04-13 19:28:54'),
(8, 1, 'Kiganjo', 'Fragile', 'Nakuru', '2025-04-13 15:29:49', '2025-04-13 17:29:49', '2025-04-13 19:29:49'),
(9, 1, 'Kiganjo', 'House Moving', 'Nakuru', '2025-04-13 15:31:22', '2025-04-13 17:31:22', '2025-04-13 19:31:22'),
(10, 1, 'kitui', 'Fragile', 'Nakuru', '2025-04-13 15:32:33', '2025-04-13 17:32:33', '2025-04-13 19:32:33'),
(11, 1, 'Kiganjo', 'Fragile', 'Nakuru', '2025-04-13 15:37:51', '2025-04-13 17:37:51', '2025-04-13 19:37:51'),
(12, 1, 'Kisumu', 'Office Relocation', 'Nakuru', '2025-04-13 15:40:50', '2025-04-13 17:40:50', '2025-04-13 19:40:50'),
(13, 1, 'Kiganjo', 'Fragile', 'Mombasa', '2025-04-13 15:41:29', '2025-04-13 17:41:29', '2025-04-13 19:41:29'),
(14, 1, 'Kiganjo', 'House Moving', 'Nakuru', '2025-04-13 15:45:27', '2025-04-13 17:45:27', '2025-04-13 19:45:27'),
(15, 1, 'kitui', 'Fragile', 'Nakuru', '2025-04-13 15:49:44', '2025-04-13 17:49:44', '2025-04-13 19:49:44'),
(16, 2, 'Kiganjo', 'Fragile', 'Nakuru', '2025-04-13 17:44:20', '2025-04-13 19:44:20', '2025-04-13 21:44:20'),
(18, 2, 'Migori', 'Hardware Moving', 'Mombasa', '2025-04-16 10:37:54', '2025-04-16 12:37:54', '2025-04-16 14:37:54');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_picture` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `full_name`, `email`, `password`, `phone_number`, `created_at`, `profile_picture`) VALUES
(1, 'Bruno', 'bruno@gmail.com', '$2y$10$8LcLniqxO1L/QTWoIDtGPOVBrXqczuPdz0V5uOAsQK6xjRqQS9EXm', '0792761073', '2025-04-13 11:47:27', 'default.png'),
(2, 'Terry Waswa m', 'terry@gmail.com', '$2y$10$bkobwJlWF9tSse3gS4Cz.evwjpc.Fupe1Q5eUQKVHcwwwGJSubutW', '0792761078', '2025-04-13 17:37:15', '1744799357_67ff867d20dd7.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
