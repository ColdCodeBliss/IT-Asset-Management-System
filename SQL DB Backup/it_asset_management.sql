-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2025 at 03:37 AM
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
-- Database: `it_asset_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id` int(11) NOT NULL,
  `asset_tag` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `original_cost` decimal(10,2) DEFAULT NULL,
  `current_value` decimal(10,2) DEFAULT NULL,
  `status` enum('In Use','In Repair','Decommissioned','Available') DEFAULT 'Available',
  `assigned_to` int(11) DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`id`, `asset_tag`, `name`, `category`, `description`, `purchase_date`, `original_cost`, `current_value`, `status`, `assigned_to`, `last_updated`) VALUES
(1, 'LT-001', 'Dell Latitude 5520', 'Laptop', '15-inch business laptop', '2023-03-10', 1200.00, 1000.00, 'In Use', 1, '2025-05-27 00:09:37'),
(2, 'MN-100', 'LG UltraFine Monitor', 'Monitor', '27\" 4K IPS monitor', '2022-07-20', 600.00, 450.00, 'In Use', 2, '2025-05-27 00:09:37'),
(3, 'RT-340', 'Netgear Nighthawk X6', 'Router', 'Tri-band WiFi router', '2021-10-05', 250.00, 120.00, 'In Repair', NULL, '2025-05-27 00:09:37'),
(4, 'KB-755', 'Logitech MX Keys', 'Keyboard', 'Wireless illuminated keyboard', '2022-12-15', 99.00, 80.00, 'Available', NULL, '2025-05-27 00:09:37'),
(6, 'LT-102', 'HP EliteBook 840', 'Laptop', '14-inch business ultrabook', '2024-01-15', 1150.00, 1100.00, 'In Use', 6, '2025-05-27 00:54:10'),
(7, 'MN-200', 'Dell P2723QE', 'Monitor', '27\" 4K USB-C display', '2023-08-22', 500.00, 470.00, 'Available', NULL, '2025-05-27 00:54:10'),
(8, 'RT-401', 'TP-Link Archer AX6000', 'Router', 'Dual-band high-speed router', '2023-06-12', 300.00, 250.00, 'In Use', 8, '2025-05-27 00:54:10'),
(9, 'KB-888', 'Keychron K8 Pro', 'Keyboard', 'Wireless mechanical keyboard', '2024-02-05', 99.00, 95.00, 'Available', NULL, '2025-05-27 00:54:10'),
(10, 'MC-301', 'Blue Yeti USB Microphone', 'Microphone', 'Professional-grade podcast mic', '2023-09-01', 130.00, 120.00, 'In Use', 7, '2025-05-27 00:54:10');

-- --------------------------------------------------------

--
-- Table structure for table `asset_assignments`
--

CREATE TABLE `asset_assignments` (
  `id` int(11) NOT NULL,
  `asset_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `assigned_date` date DEFAULT NULL,
  `unassigned_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `asset_assignments`
--

INSERT INTO `asset_assignments` (`id`, `asset_id`, `employee_id`, `assigned_date`, `unassigned_date`, `notes`) VALUES
(6, 1, 1, '2023-03-15', NULL, 'Issued upon onboarding'),
(7, 2, 2, '2022-08-01', NULL, 'Assigned for dual-screen setup'),
(8, 3, 3, '2022-01-10', '2023-01-10', 'Temporarily used during network upgrade'),
(9, 6, 6, '2024-04-10', NULL, 'Issued new laptop for hybrid work'),
(10, 8, 7, '2024-04-18', NULL, 'Assigned microphone for remote webinars'),
(11, 9, 8, '2024-04-21', NULL, 'Router deployed for remote site');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `first_name`, `last_name`, `email`, `department`) VALUES
(1, 'Alice', 'Nguyen', 'alice.nguyen@example.com', 'IT Support'),
(2, 'Brian', 'Jackson', 'brian.jackson@example.com', 'Cybersecurity'),
(3, 'Carla', 'Torres', 'carla.torres@example.com', 'Networking'),
(4, 'Derek', 'Stone', 'derek.stone@example.com', 'Development'),
(5, 'Eva', 'Patel', 'eva.patel@example.com', 'Systems Administration'),
(6, 'Janelle', 'Reed', 'janelle.reed@example.com', 'Help Desk'),
(7, 'Marcus', 'Lin', 'marcus.lin@example.com', 'Infrastructure'),
(8, 'Olivia', 'Zhang', 'olivia.zhang@example.com', 'Cybersecurity'),
(9, 'Noah', 'Rivera', 'noah.rivera@example.com', 'Development'),
(10, 'Priya', 'Nair', 'priya.nair@example.com', 'Networking');

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_logs`
--

CREATE TABLE `maintenance_logs` (
  `id` int(11) NOT NULL,
  `asset_id` int(11) DEFAULT NULL,
  `maintenance_date` date DEFAULT NULL,
  `issue_description` text DEFAULT NULL,
  `resolution` text DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `performed_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `asset_tag` (`asset_tag`);

--
-- Indexes for table `asset_assignments`
--
ALTER TABLE `asset_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `asset_id` (`asset_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `maintenance_logs`
--
ALTER TABLE `maintenance_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `asset_id` (`asset_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `asset_assignments`
--
ALTER TABLE `asset_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `maintenance_logs`
--
ALTER TABLE `maintenance_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `asset_assignments`
--
ALTER TABLE `asset_assignments`
  ADD CONSTRAINT `asset_assignments_ibfk_1` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`),
  ADD CONSTRAINT `asset_assignments_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`);

--
-- Constraints for table `maintenance_logs`
--
ALTER TABLE `maintenance_logs`
  ADD CONSTRAINT `maintenance_logs_ibfk_1` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
