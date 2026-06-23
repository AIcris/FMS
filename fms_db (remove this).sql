-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 22, 2026 at 09:02 AM
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
-- Database: `fms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `user_id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `pasword_hash` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`user_id`, `firstname`, `lastname`, `username`, `pasword_hash`, `email`, `role`, `is_active`, `last_login`, `department_id`) VALUES
(2, 'Christian', 'Raguindin', 'christian', '$2y$10$qjPGuuQDUaKFwmo40aKsUemlJvskndAW9lWzSu8ChP0V/xZ7Hzg2K', 'grandrooster087@gmail.com', 'admin', 1, '2026-06-17 08:48:26', NULL),
(3, 'Christian', 'Raguindin', 'chris', '$2y$10$dNS4n4aH3pcVA6V7KfrFbe7TqMFgID3rrNlkQbAIGXjc6JVU3NMjq', 'christian.raguindin@clsu2.edu.ph', 'staff', 1, '2026-06-16 20:08:13', 2),
(4, 'John', 'Doe', 'johny', '$2y$10$x1tcng8hSfL/YDkxfARV7u8rMIdVeSoxxcGS6Zvgsbns7qjRE1uhG', 'john@gmail.com', 'staff', 1, '2026-06-09 14:59:07', 1),
(5, 'Christian', 'Raguindin', 'cris', '$2y$10$6ckfVTP0aW6uklQ9XFg0k.X7Ej/7jmpObSL/aK3jcjnabN3I20//y', 'christianraguindin007@gmail.com', 'staff', 1, NULL, 1),
(6, 'Jane', 'Doe', 'janedoe', '$2y$10$9HZkir8J05o9jbR317XA7uZQPl6u/CF27wGGuM/faW0UdmOG7UlHa', 'janedoe@gmail.com', 'staff', 1, NULL, 2),
(7, 'John', 'Mark', 'jmark', '$2y$10$lYqhYHrn0/v9OePMsB5haudNqnLRun2IkCdDEBcgHSZC9uodiP7bW', 'jmark@gmail.com', 'staff', 1, '2026-06-17 08:50:29', 1);

-- --------------------------------------------------------

--
-- Table structure for table `approved_report_distribution`
--

CREATE TABLE `approved_report_distribution` (
  `distribution_id` int(11) NOT NULL,
  `recipient_type` varchar(100) DEFAULT NULL,
  `distribited_at` datetime DEFAULT NULL,
  `report_id` int(11) DEFAULT NULL,
  `recipient_user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`department_id`, `department_name`, `description`) VALUES
(1, 'Office of Admission', 'This is OAD'),
(2, 'Department of Information Technology', 'DIT'),
(3, 'Infirmary ', 'Health');

-- --------------------------------------------------------

--
-- Table structure for table `electronic_signature`
--

CREATE TABLE `electronic_signature` (
  `signature_id` int(11) NOT NULL,
  `signatory_role` varchar(100) DEFAULT NULL,
  `signing_order` int(11) DEFAULT NULL,
  `signature_image` blob DEFAULT NULL,
  `signed_at` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `report_id` int(11) DEFAULT NULL,
  `recipient_user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `response_id` int(11) NOT NULL,
  `control_number` varchar(100) DEFAULT NULL,
  `client_type` varchar(100) DEFAULT NULL,
  `client_classification` varchar(100) DEFAULT NULL,
  `transaction_type` varchar(100) DEFAULT NULL,
  `sex` varchar(20) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `region_of_residence` varchar(100) DEFAULT NULL,
  `service_availed` varchar(255) DEFAULT NULL,
  `name_of_office` varchar(255) DEFAULT NULL,
  `service_provider_name` varchar(255) DEFAULT NULL,
  `service_provider_position` varchar(255) DEFAULT NULL,
  `cc1` varchar(50) DEFAULT NULL,
  `cc2` varchar(50) DEFAULT NULL,
  `cc3` varchar(50) DEFAULT NULL,
  `sqd0` varchar(50) DEFAULT NULL,
  `sqd1` varchar(50) DEFAULT NULL,
  `sqd2` varchar(50) DEFAULT NULL,
  `sqd3` varchar(50) DEFAULT NULL,
  `sqd4` varchar(50) DEFAULT NULL,
  `sqd5` varchar(50) DEFAULT NULL,
  `sqd6` varchar(50) DEFAULT NULL,
  `sqd7` varchar(50) DEFAULT NULL,
  `sqd8` varchar(50) DEFAULT NULL,
  `experienced_harassment` varchar(10) DEFAULT NULL,
  `harassment_details` text DEFAULT NULL,
  `would_recommend_clsu` varchar(10) DEFAULT NULL,
  `suggestions` text DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `submitted_at` datetime DEFAULT current_timestamp(),
  `qr_id` int(11) DEFAULT NULL,
  `report_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`response_id`, `control_number`, `client_type`, `client_classification`, `transaction_type`, `sex`, `age`, `region_of_residence`, `service_availed`, `name_of_office`, `service_provider_name`, `service_provider_position`, `cc1`, `cc2`, `cc3`, `sqd0`, `sqd1`, `sqd2`, `sqd3`, `sqd4`, `sqd5`, `sqd6`, `sqd7`, `sqd8`, `experienced_harassment`, `harassment_details`, `would_recommend_clsu`, `suggestions`, `email_address`, `submitted_at`, `qr_id`, `report_id`) VALUES
(7, 'CLSU-20260616-4E2C92', 'Citizen', 'Student', 'Internal', 'Male', 22, 'Region 3', 'Good moral ', 'Office of Admission', 'Christian Raguindin', NULL, '2', 'Easy to see', 'Helped very much', '4', '5', '4', '5', '4', '5', '4', '5', '4', 'NO', NULL, 'YES', NULL, NULL, '2026-06-16 09:00:36', 1, NULL),
(8, 'CLSU-20260616-0908FF', 'Citizen', 'Student', 'Internal', 'Female', 22, 'Region 3', 'TOR', 'Department of Information Technology', 'Jane', NULL, '2', 'Somewhat easy to see', 'Helped very much', '4', '4', '4', '4', '4', '4', '4', '4', '4', 'NO', NULL, 'YES', NULL, NULL, '2026-06-16 09:09:52', 1, NULL),
(9, 'CLSU-20260616-E1B438', 'Government', 'Faculty Member', 'Internal', 'Male', 25, 'Region 3', 'TOR', 'Office of Admission', 'Jane', NULL, '1', 'Easy to see', 'Helped very much', '3', '3', '3', '3', '3', '3', '3', '3', '3', 'NO', NULL, 'YES', NULL, NULL, '2026-06-16 09:16:14', 1, NULL),
(10, 'CLSU-20260616-502155', 'Citizen', 'Student', 'Internal', 'Male', 22, 'Region 3', 'TOR', 'Department of Information Technology', 'John', NULL, '1', 'Easy to see', 'Helped very much', '1', '2', '3', '4', '5', 'N/A', '5', '4', '3', 'NO', NULL, 'YES', NULL, NULL, '2026-06-16 09:21:25', 1, NULL),
(11, 'CLSU-20260616-636A09', 'Citizen', 'Student', 'Internal', 'Male', 22, 'Region 3', 'TOR', 'Department of Information Technology', 'John', NULL, '4', 'N/A', 'N/A', 'N/A', '5', '4', '3', '2', '1', '2', '3', '4', 'NO', NULL, 'YES', NULL, NULL, '2026-06-16 09:25:58', 2, NULL),
(12, 'CLSU-20260616-858B59', 'Citizen', 'Student', 'Internal', 'Male', 32, 'Region 3', 'TOR', 'Department of Information Technology', 'John', NULL, '4', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'YES', 'hi', 'YES', NULL, NULL, '2026-06-16 09:31:36', 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `qr_code`
--

CREATE TABLE `qr_code` (
  `qr_id` int(11) NOT NULL,
  `qr_token` varchar(255) NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `expires_at` datetime DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `qr_code`
--

INSERT INTO `qr_code` (`qr_id`, `qr_token`, `label`, `is_active`, `created_at`, `expires_at`, `department_id`) VALUES
(1, 'mock-token-admission', 'Admission Test QR', 1, '2026-06-16 09:15:09', NULL, 1),
(2, 'mock-token-it', 'IT Dept Test QR', 1, '2026-06-16 09:15:09', NULL, 2),
(3, 'mock-token-infirmary', 'Infirmary Test QR', 1, '2026-06-16 09:15:09', NULL, 3);

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `report_id` int(11) NOT NULL,
  `month` varchar(20) NOT NULL,
  `year` int(11) NOT NULL,
  `summary` text DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `generated_at` datetime DEFAULT current_timestamp(),
  `department_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`report_id`, `month`, `year`, `summary`, `status`, `generated_at`, `department_id`, `user_id`) VALUES
(1, 'January', 2026, '{\"total_responses\":0,\"report_type\":\"Monthly Departmental CS Measurement\",\"status_log\":\"Draft initialized by Focal Person\"}', 'Draft', '2026-06-17 09:00:34', 1, 7);

-- --------------------------------------------------------

--
-- Table structure for table `sentiment_analysis`
--

CREATE TABLE `sentiment_analysis` (
  `sentiment_id` int(11) NOT NULL,
  `sentiment` varchar(50) NOT NULL,
  `confidence_score` decimal(5,4) DEFAULT NULL,
  `analyzed_at` datetime DEFAULT current_timestamp(),
  `response_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `approved_report_distribution`
--
ALTER TABLE `approved_report_distribution`
  ADD PRIMARY KEY (`distribution_id`),
  ADD KEY `report_id` (`report_id`),
  ADD KEY `recipient_user_id` (`recipient_user_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `electronic_signature`
--
ALTER TABLE `electronic_signature`
  ADD PRIMARY KEY (`signature_id`),
  ADD KEY `report_id` (`report_id`),
  ADD KEY `recipient_user_id` (`recipient_user_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`response_id`),
  ADD UNIQUE KEY `control_number` (`control_number`),
  ADD KEY `qr_id` (`qr_id`),
  ADD KEY `fk_feedback_report` (`report_id`);

--
-- Indexes for table `qr_code`
--
ALTER TABLE `qr_code`
  ADD PRIMARY KEY (`qr_id`),
  ADD UNIQUE KEY `qr_token` (`qr_token`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sentiment_analysis`
--
ALTER TABLE `sentiment_analysis`
  ADD PRIMARY KEY (`sentiment_id`),
  ADD KEY `response_id` (`response_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `approved_report_distribution`
--
ALTER TABLE `approved_report_distribution`
  MODIFY `distribution_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `electronic_signature`
--
ALTER TABLE `electronic_signature`
  MODIFY `signature_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `response_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `qr_code`
--
ALTER TABLE `qr_code`
  MODIFY `qr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sentiment_analysis`
--
ALTER TABLE `sentiment_analysis`
  MODIFY `sentiment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `account_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`);

--
-- Constraints for table `approved_report_distribution`
--
ALTER TABLE `approved_report_distribution`
  ADD CONSTRAINT `approved_report_distribution_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `report` (`report_id`),
  ADD CONSTRAINT `approved_report_distribution_ibfk_2` FOREIGN KEY (`recipient_user_id`) REFERENCES `account` (`user_id`);

--
-- Constraints for table `electronic_signature`
--
ALTER TABLE `electronic_signature`
  ADD CONSTRAINT `electronic_signature_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `report` (`report_id`),
  ADD CONSTRAINT `electronic_signature_ibfk_2` FOREIGN KEY (`recipient_user_id`) REFERENCES `account` (`user_id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`qr_id`) REFERENCES `qr_code` (`qr_id`),
  ADD CONSTRAINT `fk_feedback_report` FOREIGN KEY (`report_id`) REFERENCES `report` (`report_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `qr_code`
--
ALTER TABLE `qr_code`
  ADD CONSTRAINT `qr_code_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`);

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`),
  ADD CONSTRAINT `report_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `account` (`user_id`);

--
-- Constraints for table `sentiment_analysis`
--
ALTER TABLE `sentiment_analysis`
  ADD CONSTRAINT `sentiment_analysis_ibfk_1` FOREIGN KEY (`response_id`) REFERENCES `feedback` (`response_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
