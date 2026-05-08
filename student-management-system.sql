-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2026 at 12:51 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student-management-system`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` enum('Present','Absent') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `student_id`, `date`, `status`) VALUES
(1, 1, '2026-03-05', 'Present'),
(4, 1, '2026-03-10', 'Present'),
(5, 2, '2026-03-10', 'Absent'),
(6, 3, '2026-03-10', 'Absent'),
(7, 4, '2026-03-10', 'Present'),
(9, 6, '2026-03-10', 'Absent'),
(10, 7, '2026-03-10', 'Present'),
(13, 10, '2026-03-10', 'Present'),
(15, 12, '2026-03-10', 'Present'),
(28, 13, '2026-03-10', 'Absent'),
(94, 1, '2026-03-12', 'Present'),
(95, 2, '2026-03-12', 'Present'),
(96, 3, '2026-03-12', 'Present'),
(97, 4, '2026-03-12', 'Present'),
(99, 6, '2026-03-12', 'Present'),
(100, 7, '2026-03-12', 'Present'),
(101, 13, '2026-03-12', 'Present'),
(102, 10, '2026-03-12', 'Present'),
(103, 1, '2026-03-19', 'Absent'),
(104, 2, '2026-03-19', 'Present'),
(105, 3, '2026-03-19', 'Present'),
(106, 4, '2026-03-19', 'Present'),
(108, 6, '2026-03-19', 'Present'),
(109, 7, '2026-03-19', 'Present'),
(112, 10, '2026-03-19', 'Absent'),
(114, 12, '2026-03-19', 'Absent'),
(115, 13, '2026-03-19', 'Present'),
(116, 1, '2026-03-18', 'Absent'),
(117, 2, '2026-03-18', 'Absent'),
(118, 3, '2026-03-18', 'Present'),
(119, 4, '2026-03-18', 'Present'),
(121, 6, '2026-03-18', 'Present'),
(122, 7, '2026-03-18', 'Present'),
(125, 10, '2026-03-18', 'Present'),
(127, 12, '2026-03-18', 'Present'),
(128, 13, '2026-03-18', 'Present'),
(129, 1, '2026-03-17', 'Absent'),
(130, 2, '2026-03-17', 'Present'),
(131, 3, '2026-03-17', 'Present'),
(132, 4, '2026-03-17', 'Present'),
(134, 6, '2026-03-17', 'Present'),
(135, 7, '2026-03-17', 'Present'),
(138, 10, '2026-03-17', 'Present'),
(140, 12, '2026-03-17', 'Present'),
(141, 13, '2026-03-17', 'Present'),
(142, 1, '2026-03-16', 'Absent'),
(143, 2, '2026-03-16', 'Present'),
(144, 3, '2026-03-16', 'Present'),
(145, 4, '2026-03-16', 'Present'),
(147, 6, '2026-03-16', 'Present'),
(148, 7, '2026-03-16', 'Present'),
(151, 10, '2026-03-16', 'Present'),
(153, 12, '2026-03-16', 'Present'),
(154, 13, '2026-03-16', 'Present'),
(155, 1, '2026-03-13', 'Absent'),
(156, 2, '2026-03-13', 'Present'),
(157, 3, '2026-03-13', 'Present'),
(158, 4, '2026-03-13', 'Present'),
(160, 6, '2026-03-13', 'Present'),
(161, 7, '2026-03-13', 'Present'),
(164, 10, '2026-03-13', 'Present'),
(166, 12, '2026-03-13', 'Present'),
(167, 13, '2026-03-13', 'Present'),
(171, 12, '2026-03-12', 'Present'),
(172, 1, '2026-03-11', 'Absent'),
(173, 2, '2026-03-11', 'Present'),
(174, 3, '2026-03-11', 'Present'),
(175, 4, '2026-03-11', 'Present'),
(177, 6, '2026-03-11', 'Present'),
(178, 7, '2026-03-11', 'Present'),
(181, 10, '2026-03-11', 'Present'),
(183, 12, '2026-03-11', 'Present'),
(184, 13, '2026-03-11', 'Present'),
(185, 1, '2026-03-23', 'Present'),
(186, 2, '2026-03-23', 'Present'),
(187, 3, '2026-03-23', 'Absent'),
(188, 4, '2026-03-23', 'Present'),
(190, 6, '2026-03-23', 'Present'),
(191, 7, '2026-03-23', 'Present'),
(194, 10, '2026-03-23', 'Present'),
(196, 12, '2026-03-23', 'Present'),
(197, 13, '2026-03-23', 'Present'),
(198, 14, '2026-03-23', 'Present'),
(199, 1, '2026-03-24', 'Absent'),
(200, 2, '2026-03-24', 'Absent'),
(201, 3, '2026-03-24', 'Present'),
(202, 4, '2026-03-24', 'Present'),
(203, 6, '2026-03-24', 'Present'),
(204, 7, '2026-03-24', 'Present'),
(205, 10, '2026-03-24', 'Present'),
(206, 12, '2026-03-24', 'Present'),
(207, 13, '2026-03-24', 'Present'),
(208, 14, '2026-03-24', 'Present'),
(209, 1, '2026-03-27', 'Absent'),
(210, 2, '2026-03-27', 'Absent'),
(211, 3, '2026-03-27', 'Present'),
(212, 4, '2026-03-27', 'Present'),
(213, 6, '2026-03-27', 'Present'),
(214, 7, '2026-03-27', 'Present'),
(215, 10, '2026-03-27', 'Present'),
(216, 12, '2026-03-27', 'Present'),
(217, 13, '2026-03-27', 'Absent'),
(218, 14, '2026-03-27', 'Absent'),
(219, 1, '2026-03-28', 'Absent'),
(220, 2, '2026-03-28', 'Absent'),
(221, 3, '2026-03-28', 'Present'),
(222, 4, '2026-03-28', 'Present'),
(223, 6, '2026-03-28', 'Present'),
(224, 7, '2026-03-28', 'Present'),
(225, 10, '2026-03-28', 'Present'),
(226, 12, '2026-03-28', 'Present'),
(227, 13, '2026-03-28', 'Absent'),
(228, 14, '2026-03-28', 'Absent'),
(229, 1, '2026-03-30', 'Absent'),
(230, 2, '2026-03-30', 'Absent'),
(231, 3, '2026-03-30', 'Present'),
(232, 4, '2026-03-30', 'Present'),
(233, 6, '2026-03-30', 'Present'),
(234, 7, '2026-03-30', 'Present'),
(235, 10, '2026-03-30', 'Present'),
(236, 12, '2026-03-30', 'Present'),
(237, 13, '2026-03-30', 'Absent'),
(238, 14, '2026-03-30', 'Present'),
(239, 1, '2026-03-31', 'Absent'),
(240, 2, '2026-03-31', 'Absent'),
(241, 3, '2026-03-31', 'Present'),
(242, 4, '2026-03-31', 'Present'),
(243, 6, '2026-03-31', 'Present'),
(244, 7, '2026-03-31', 'Present'),
(245, 10, '2026-03-31', 'Present'),
(246, 12, '2026-03-31', 'Present'),
(247, 13, '2026-03-31', 'Absent'),
(248, 14, '2026-03-31', 'Present'),
(249, 1, '2026-04-30', 'Present'),
(250, 2, '2026-04-30', 'Absent'),
(251, 3, '2026-04-30', 'Present'),
(252, 4, '2026-04-30', 'Present'),
(253, 6, '2026-04-30', 'Present'),
(254, 7, '2026-04-30', 'Absent'),
(255, 10, '2026-04-30', 'Present'),
(256, 12, '2026-04-30', 'Present'),
(257, 13, '2026-04-30', 'Absent'),
(258, 14, '2026-04-30', 'Present'),
(259, 15, '2026-04-30', 'Present'),
(260, 1, '2026-05-02', 'Absent'),
(261, 2, '2026-05-02', ''),
(262, 3, '2026-05-02', 'Present'),
(263, 4, '2026-05-02', 'Present'),
(264, 6, '2026-05-02', 'Present'),
(265, 7, '2026-05-02', 'Present'),
(266, 10, '2026-05-02', 'Present'),
(267, 12, '2026-05-02', 'Present'),
(268, 13, '2026-05-02', ''),
(269, 14, '2026-05-02', 'Present'),
(270, 15, '2026-05-02', 'Present');

-- --------------------------------------------------------

--
-- Table structure for table `fees`
--

CREATE TABLE `fees` (
  `fee_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `status` enum('Paid','Pending') DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fees`
--

INSERT INTO `fees` (`fee_id`, `student_id`, `amount`, `payment_date`, `status`, `date`) VALUES
(1, 2, 10.00, NULL, 'Paid', NULL),
(2, 1, 2000.00, NULL, 'Pending', NULL),
(3, 1, 3000.00, NULL, NULL, '2026-03-12'),
(4, 1, 1000.00, NULL, NULL, '2026-03-12'),
(5, 2, 7000.00, NULL, NULL, '2026-03-17'),
(6, 3, 11000.00, NULL, NULL, '2026-03-17'),
(7, 2, 1000.00, NULL, NULL, '2026-03-19'),
(8, 4, 2000.00, NULL, NULL, '2026-03-19'),
(9, 1, 500.00, NULL, NULL, '2026-03-23'),
(10, 1, 500.00, NULL, NULL, '2026-03-23'),
(11, 1, 2000.00, NULL, NULL, '2026-04-17'),
(12, 1, 1000.00, NULL, NULL, '2026-04-30'),
(13, 4, 5000.00, NULL, NULL, '2026-04-30');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `result_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  `grade` varchar(5) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `subject` varchar(50) DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`result_id`, `student_id`, `marks`, `grade`, `status`, `subject`, `class`) VALUES
(1, 1, 79, 'B', 'PASS', 'Science', 'Pre - 1'),
(2, 1, 40, 'F', 'PASS', 'Science', 'Pre - 1'),
(3, 12, 88, 'A', 'PASS', 'Science', 'Pre - 1'),
(4, 2, 100, 'A', 'PASS', 'Science', 'Pre - 1'),
(6, 6, 75, 'B', 'PASS', 'Science', 'Pre - 1'),
(7, 4, 62, 'C', 'PASS', 'Science', 'Pre - 1');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `first_name`, `last_name`, `email`, `phone`) VALUES
(1, 'Sushmita', 'kc', 'sushmitachhetri68@gmail.com', '81934790'),
(2, 'Sujan', 'Adhikari', 'sujanadhikari053@gmail.com', '7688990'),
(3, 'Sujata', 'Chhetri', 'sujata456@gmail.com', '32345457'),
(4, 'Anu', 'Gaire', 'anugaire45@gmail.com', '97798362'),
(6, 'Sushant', 'Adhikari', 'sushantadhi34@gmail.com', '83476662'),
(7, 'Deepa', 'KC', 'deepa34@gmail.com', '63673265'),
(10, 'Kriti', 'Khadka', 'kriti67@gmail.com', '53436767'),
(12, 'Israt', 'Khan', 'israt99@gmail.com', '78798776'),
(13, 'Abishek', 'Achammi', 'abishekachhami987@gmail.com', '72892000'),
(14, 'Bernice', 'bryan', 'bernice34@gmail.com', '78973656'),
(15, 'Dambar', 'Chhetri', 'dambae45@gmail.com', '76897612');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(0, 'admin', '$2a$10$S1UQBYvPmTNIIKrMOTChVedaeYfRHZOEJK8oOnLpfMPJFSWc2bi0y');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD UNIQUE KEY `student_id` (`student_id`,`date`);

--
-- Indexes for table `fees`
--
ALTER TABLE `fees`
  ADD PRIMARY KEY (`fee_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`result_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=271;

--
-- AUTO_INCREMENT for table `fees`
--
ALTER TABLE `fees`
  MODIFY `fee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `result_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `fees`
--
ALTER TABLE `fees`
  ADD CONSTRAINT `fees_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
