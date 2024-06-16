-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Jun 16, 2024 at 11:18 AM
-- Server version: 10.5.25-MariaDB-ubu2004
-- PHP Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `myDb`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_number` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `service_type` tinytext NOT NULL,
  `service_name` tinytext NOT NULL,
  `total_price` float(11,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `order_id`, `invoice_id`, `product_id`, `service_type`, `service_name`, `total_price`) VALUES
(2, 20, 1842479708, 241450, 757767, 127, 'Hosting', 'Linux 2', 6),
(3, 20, 326495397, 241452, 757769, 127, 'Hosting', 'Linux 2', 6),
(4, 20, 2085516157, 241453, 757770, 127, 'Hosting', 'Linux 2', 6),
(5, 20, 2122484413, 241454, 757771, 127, 'Hosting', 'Linux 2', 65),
(6, 20, 62665447, 241511, 757839, 127, 'Hosting', 'Linux 2', 6),
(7, 20, 1271367195, 241512, 757840, 134, 'Hosting', 'Linux 64', 2675),
(8, 20, 1864081973, 241514, 757842, 256, 'Hosting', 'Windows 4', 21),
(9, 20, 1706837743, 241515, 757843, 127, 'Hosting', 'Linux 2', 6),
(10, 20, 1809154651, 241516, 757844, 140, 'Hosting', 'Windows 32', 225),
(11, 20, 2118421433, 241517, 757845, 140, 'Hosting', 'Windows 32', 225),
(12, 20, 1295483159, 241518, 757846, 140, 'Hosting', 'Windows 32', 225),
(13, 20, 1059669974, 241519, 757847, 256, 'Hosting', 'Windows 4', 21),
(14, 27, 714394772, 241520, 757848, 127, 'Hosting', 'Linux 2', 39);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `created_at`) VALUES
(20, 'test323@mail.ru', '$2y$10$RTy62.VJmXOEQt4C7i4lq.nnKFAUwOrKZMiYHIGZUryTW1G8iG8Km', '2024-06-10 11:48:33'),
(21, 'test666@mail.ru', '$2y$10$Pa.oAzb525etL1NTaKvA4.s7RU8YBmL9t6w10XbDWfMmDEfveINUS', '2024-06-13 11:36:18'),
(22, 'test111@mail.ru', '$2y$10$82wboAvoe9Z2taOEGw0KBuBxUzbkT4TtOoJf/qoSpuwHzo05916Sy', '2024-06-13 12:20:32'),
(23, 'test3@mail.ru', '$2y$10$6EabqCFeFZTUFps.M0zEjuCPgb3TO/jN4mu954OM1YmVh2aM68uuG', '2024-06-13 15:34:17'),
(24, 'test5@mail.ru', '$2y$10$grz2ZBkrsKEVLqeJMmPSju5FbnK.bB0flzfjUqrxTJPBtsaZWbAqa', '2024-06-14 09:11:36'),
(25, 'test6@mail.ru', '$2y$10$fqwwOxA9k1XadGj0qkleuO7cBwud8Z1g0bE/qCWXWfh49F9wTT3RW', '2024-06-15 05:44:00'),
(26, 'test999@mail.ru', '$2y$10$q.26.8px9wTVrMJ2eEMCFenUsa7I6ccM0Dr1VtiRKEwG/zJksRGLC', '2024-06-16 00:16:21'),
(27, 'iv123@mail.ru', '$2y$10$QwXtBUEToTYI7V85Y77.9OaVxSL5Xspn1C3Dq42/EiAaX6XW2rkAa', '2024-06-16 00:56:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
