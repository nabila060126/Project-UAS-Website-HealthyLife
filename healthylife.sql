-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 25, 2025 at 08:17 AM
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
-- Database: `healthylife`
--

-- --------------------------------------------------------

--
-- Table structure for table `daily_note`
--

CREATE TABLE `daily_note` (
  `note_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `note_date` date NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `category` enum('Olahraga','Nutrisi','Tidur','Pekerjaan','Personal','Kesehatan') DEFAULT NULL,
  `priority` enum('Rendah','Sedang','Tinggi','Urgent') DEFAULT NULL,
  `is_completed` tinyint(1) DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daily_note`
--

INSERT INTO `daily_note` (`note_id`, `user_id`, `note_date`, `title`, `description`, `category`, `priority`, `is_completed`, `updated_at`, `created_at`) VALUES
(11, 2, '2025-12-22', 'Senin Ceria', 'Senin ini asyik sekali', 'Personal', 'Sedang', 0, '2025-12-22 07:37:40', '2025-12-22 07:37:40');

-- --------------------------------------------------------

--
-- Table structure for table `exercise_log`
--

CREATE TABLE `exercise_log` (
  `exercise_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `exercise_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `exercise_type` varchar(100) DEFAULT NULL,
  `duration_minutes` int(11) DEFAULT NULL,
  `intensity` enum('Ringan','Sedang','Berat','Sangat Berat') DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exercise_log`
--

INSERT INTO `exercise_log` (`exercise_id`, `user_id`, `exercise_date`, `start_time`, `exercise_type`, `duration_minutes`, `intensity`, `notes`) VALUES
(7, 2, '2025-12-22', '06:35:00', 'JOGGING', 60, 'Sedang', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `health_goal`
--

CREATE TABLE `health_goal` (
  `goal_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `goal_type` enum('Berat Badan','Olahraga','Nutrisi','Tidur','Air Minum') DEFAULT NULL,
  `goal_title` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `target_value` float DEFAULT NULL,
  `current_value` float DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `target_date` date DEFAULT NULL,
  `status` enum('Aktif','Selesai','Gagal','Ditunda') DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `health_goal`
--

INSERT INTO `health_goal` (`goal_id`, `user_id`, `goal_type`, `goal_title`, `description`, `target_value`, `current_value`, `start_date`, `target_date`, `status`) VALUES
(6, 2, 'Berat Badan', 'TAHUN BARU BADAN IDEAL', 'AKU INGIN BADAN IDEAL DI TAHUN BARU', 45, 40, '2025-12-22', '2025-01-22', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `meal_log`
--

CREATE TABLE `meal_log` (
  `meal_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `meal_date` date NOT NULL,
  `meal_time` time NOT NULL,
  `meal_type` enum('Sarapan','Makan Siang','Makan Malam','Snack') DEFAULT NULL,
  `food_name` varchar(150) DEFAULT NULL,
  `calories` float DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meal_log`
--

INSERT INTO `meal_log` (`meal_id`, `user_id`, `meal_date`, `meal_time`, `meal_type`, `food_name`, `calories`, `notes`) VALUES
(7, 2, '2025-12-22', '07:34:00', 'Sarapan', 'PECEL', 350, 'Pecel khas mbok yem emang enak');

-- --------------------------------------------------------

--
-- Table structure for table `sleep_log`
--

CREATE TABLE `sleep_log` (
  `sleep_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `sleep_date` date NOT NULL,
  `bedtime` time DEFAULT NULL,
  `wake_time` time DEFAULT NULL,
  `duration_hours` float DEFAULT NULL,
  `quality_rating` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sleep_log`
--

INSERT INTO `sleep_log` (`sleep_id`, `user_id`, `sleep_date`, `bedtime`, `wake_time`, `duration_hours`, `quality_rating`, `notes`) VALUES
(5, 2, '2025-12-22', '22:35:00', '05:35:00', 7, 8, 'NYENYAK SEKALI');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `height_cm` float DEFAULT NULL,
  `weight_kg` float DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `gender`, `birth_date`, `height_cm`, `weight_kg`, `created_at`) VALUES
(2, 'Muhammad Ikbar Nur Irsyad', 'irsyadikbar@gmail.com', 'Unesajaya', 'Laki-laki', '2005-05-18', 160, 38, '2025-12-07 14:09:33');

-- --------------------------------------------------------

--
-- Table structure for table `water_intake`
--

CREATE TABLE `water_intake` (
  `intake_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `intake_date` date NOT NULL,
  `intake_time` time NOT NULL,
  `amount_ml` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `water_intake`
--

INSERT INTO `water_intake` (`intake_id`, `user_id`, `intake_date`, `intake_time`, `amount_ml`) VALUES
(5, 2, '2025-12-22', '09:34:00', 700);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `daily_note`
--
ALTER TABLE `daily_note`
  ADD PRIMARY KEY (`note_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `exercise_log`
--
ALTER TABLE `exercise_log`
  ADD PRIMARY KEY (`exercise_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `health_goal`
--
ALTER TABLE `health_goal`
  ADD PRIMARY KEY (`goal_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `meal_log`
--
ALTER TABLE `meal_log`
  ADD PRIMARY KEY (`meal_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sleep_log`
--
ALTER TABLE `sleep_log`
  ADD PRIMARY KEY (`sleep_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `water_intake`
--
ALTER TABLE `water_intake`
  ADD PRIMARY KEY (`intake_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `daily_note`
--
ALTER TABLE `daily_note`
  MODIFY `note_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `exercise_log`
--
ALTER TABLE `exercise_log`
  MODIFY `exercise_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `health_goal`
--
ALTER TABLE `health_goal`
  MODIFY `goal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `meal_log`
--
ALTER TABLE `meal_log`
  MODIFY `meal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sleep_log`
--
ALTER TABLE `sleep_log`
  MODIFY `sleep_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `water_intake`
--
ALTER TABLE `water_intake`
  MODIFY `intake_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `daily_note`
--
ALTER TABLE `daily_note`
  ADD CONSTRAINT `daily_note_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `exercise_log`
--
ALTER TABLE `exercise_log`
  ADD CONSTRAINT `exercise_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `health_goal`
--
ALTER TABLE `health_goal`
  ADD CONSTRAINT `health_goal_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `meal_log`
--
ALTER TABLE `meal_log`
  ADD CONSTRAINT `meal_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `sleep_log`
--
ALTER TABLE `sleep_log`
  ADD CONSTRAINT `sleep_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `water_intake`
--
ALTER TABLE `water_intake`
  ADD CONSTRAINT `water_intake_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
