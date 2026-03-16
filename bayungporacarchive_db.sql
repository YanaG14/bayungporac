-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 16, 2026 at 02:18 AM
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
-- Database: `bayungporacarchive_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_login`
--

CREATE TABLE `admin_login` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `admin_user` text NOT NULL,
  `admin_password` text NOT NULL,
  `admin_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin_login`
--

INSERT INTO `admin_login` (`id`, `name`, `admin_user`, `admin_password`, `admin_status`) VALUES
(1, 'Jelly D. Concepcion', 'jelly@gmail.com', '$2y$10$WX1C92bWXnikzCdVGKYkD.DDP39fMKJWm16gS5RaeHYuYnxaE/yXi', ''),
(3, 'Joys Ann B. Calam', 'joys@gmail.com', '$2y$10$psmG0yfbFuNE0k6.6ixH4.lRTPKoRW5Pf9aoWNVd9JOs1yPrt3XSG', ''),
(4, 'Fina L. Sagum', 'adm.0003@gmail.com', '$2y$12$AR4vws4b.C9DtLjBUcP7p.BIQwvPmZiGYOuk5KjB23NSqbNICzRmi', 'Archived'),
(5, 'Michaella L. De Leon', 'adm.staff0005@gmail.com', '$2y$12$z2MN0nRKhEzcP47Oh1/eSehXuDKTkeqx7iP1oC7CTLBlxHSQshkVG', ''),
(6, 'John Kenneth T. Pineda', 'adm.staff0004@gmail.com', '$2y$12$AGWqmadcWklP3RXNTIVhWepGhhcL1jULlw2/.xpjM7TLYxcr2oN7m', ''),
(7, 'Robin A. Santiago', 'adm.staff0002@gmail.com', '$2y$12$Qn.jHv8StaXhRehCKVg6L.K9D.kLQZ5X9MsaM7Syp7YOYsDxDGCXG', ''),
(8, 'Test 1', 'test@gmail.com', '$2y$10$WzxaRAJsJVktKl5o2Sc2DO7MUe1yg7MMsJdirFNGX9IiiH0pq.bD2', ''),
(9, 'Test 2', 'test2@gmail.com', '$2y$12$7RMOZi9YW3dl6wAq7MG1A.nht1bje.wpuTvJ1N3hyYmm.DMfByQPa', '');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(150) NOT NULL,
  `department_img` varchar(255) DEFAULT NULL,
  `department_status` enum('Active','Archived') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`, `department_img`, `department_status`, `created_at`) VALUES
(1, 'MENRO', 'MENRO Logo.png', 'Active', '2026-03-04 05:39:37'),
(2, 'MUNICIPAL LOCAL YOUTH DEVELOPMENT OFFICE', 'LYDO LOGO.jpg', 'Active', '2026-03-04 06:43:16'),
(3, 'MUNICIPAL HEALTH OFFICE', 'RHU_LOGO.png', 'Active', '2026-03-05 03:06:46'),
(4, 'OFFICE OF THE MUNICIPAL ADMINISTRATOR', 'admin_logo.png', 'Active', '2026-03-10 06:46:07'),
(5, 'MUNICIPAL ENGINEERING OFFICE', 'MEO LOGO.png', 'Active', '2026-03-11 01:20:32'),
(6, 'MUNICIPAL GENERAL OFFICE', 'GSO LOGO.png', 'Active', '2026-03-11 01:56:09'),
(7, 'MUNICIPAL MAYOR\'S OFFICE', 'MO LOGO.png', 'Active', '2026-03-11 04:05:24'),
(8, 'MUNICIPAL ACCOUNTING OFFICE', 'MAO LOGO.png', 'Active', '2026-03-11 04:06:07'),
(9, 'MUNICIPAL TREASURER\'S OFFICE', 'MTO LOGO.png', 'Active', '2026-03-11 04:16:17'),
(10, 'MUNICIPAL PLANNING AND DEVELOPMENT OFFICE', 'MPDO - LOGO.png', 'Active', '2026-03-11 05:07:50'),
(11, 'MUNICIPAL TOURISM, ARTS, AND CULTURE OFFICE', 'TourismLogo22x22.jpg', 'Active', '2026-03-11 05:08:50'),
(12, 'HUMAN RESOURCE MANAGEMENT OFFICE', 'PORAC HRMO LOGO.png', 'Active', '2026-03-11 05:10:34'),
(13, 'MUNICIPAL POPULATION OFFICE', 'MPO LOGO.jpg', 'Active', '2026-03-11 05:11:31'),
(14, 'MUNICIPAL AGRICULTURAL SERVICES OFFICE', '953d8c63-a463-467d-9d8d-2e8ee8e8bd26.jfif', 'Active', '2026-03-11 05:12:33'),
(15, 'trial', 'joys (1).pdf', 'Active', '2026-03-12 07:25:33'),
(16, 'TEST 1', 'cutecat1.png', 'Archived', '2026-03-13 02:16:25');

-- --------------------------------------------------------

--
-- Table structure for table `file_departments`
--

CREATE TABLE `file_departments` (
  `id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `file_departments`
--

INSERT INTO `file_departments` (`id`, `file_id`, `department_id`) VALUES
(2, 11, 3),
(3, 12, 1),
(4, 12, 2);

-- --------------------------------------------------------

--
-- Table structure for table `folders`
--

CREATE TABLE `folders` (
  `folder_id` int(11) NOT NULL,
  `folder_name` varchar(150) NOT NULL,
  `folder_status` enum('Active','Archived') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `folders`
--

INSERT INTO `folders` (`folder_id`, `folder_name`, `folder_status`, `created_at`) VALUES
(4, 'Letters', 'Active', '2026-03-10 05:45:53'),
(5, 'Certifications', 'Active', '2026-03-10 05:47:28'),
(6, 'aaa', 'Archived', '2026-03-11 01:04:43'),
(7, 'em', 'Archived', '2026-03-11 01:23:47'),
(8, 'eeee', 'Archived', '2026-03-11 01:25:42'),
(9, 'Business Permit and Licensing Office', 'Active', '2026-03-11 02:57:54'),
(10, 'DILG', 'Active', '2026-03-11 02:59:33'),
(11, 'ECO Protect', 'Active', '2026-03-11 03:00:02'),
(12, 'Executive Order', 'Active', '2026-03-11 03:00:38'),
(13, 'General Service Office', 'Active', '2026-03-11 03:01:02'),
(14, 'Joint Inspection Team', 'Active', '2026-03-11 03:01:49'),
(15, 'Lucky South 99', 'Active', '2026-03-11 03:03:46'),
(16, 'LYDO', 'Active', '2026-03-11 03:04:00'),
(17, 'MCRO', 'Active', '2026-03-11 03:04:15'),
(18, 'Memorandum', 'Active', '2026-03-11 03:04:34'),
(19, 'MENRO', 'Active', '2026-03-11 03:04:49'),
(20, 'MMT', 'Active', '2026-03-11 03:05:36'),
(21, 'MPDO', 'Active', '2026-03-11 03:05:48'),
(22, 'MSWDO', 'Active', '2026-03-11 03:06:12'),
(23, 'MUNICIPAL ENGINEERING OFFICE', 'Active', '2026-03-11 03:06:45'),
(24, 'OFFICE ORDER', 'Active', '2026-03-11 03:07:13'),
(25, 'OTHER CORRESPONDENCES', 'Active', '2026-03-11 03:07:37'),
(26, 'PRIME WASTE SOLUTION', 'Active', '2026-03-11 03:08:43'),
(27, 'PUBLIC MARKET', 'Active', '2026-03-11 03:09:09'),
(28, 'SB', 'Active', '2026-03-11 03:09:27'),
(29, 'Municipal Health Office', 'Active', '2026-03-12 05:54:35'),
(30, 'TestFolder1', 'Archived', '2026-03-13 06:20:33'),
(31, 'TestFolder', 'Active', '2026-03-13 06:21:04'),
(32, 'TestFolder2', 'Archived', '2026-03-13 06:21:35'),
(33, 'Test4', 'Active', '2026-03-13 06:25:46'),
(34, 'Test1', 'Active', '2026-03-13 06:28:32'),
(35, 'TestFolder3', 'Active', '2026-03-13 06:57:56'),
(36, 'MENRO1', 'Archived', '2026-03-13 07:07:08'),
(37, 'MENRO2', 'Archived', '2026-03-13 07:08:15');

-- --------------------------------------------------------

--
-- Table structure for table `folder_departments`
--

CREATE TABLE `folder_departments` (
  `id` int(11) NOT NULL,
  `folder_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `folder_departments`
--

INSERT INTO `folder_departments` (`id`, `folder_id`, `department_id`) VALUES
(19, 6, 1),
(20, 7, 1),
(24, 8, 1),
(28, 29, 3),
(40, 5, 2),
(44, 36, 2),
(45, 36, 3),
(46, 37, 2),
(47, 37, 3);

-- --------------------------------------------------------

--
-- Table structure for table `history_log`
--

CREATE TABLE `history_log` (
  `log_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `email_address` text NOT NULL,
  `action` varchar(100) NOT NULL,
  `actions` varchar(200) NOT NULL DEFAULT 'Has LoggedOut the system at',
  `ip` text NOT NULL,
  `host` text NOT NULL,
  `login_time` varchar(200) NOT NULL,
  `logout_time` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `history_log`
--

INSERT INTO `history_log` (`log_id`, `id`, `email_address`, `action`, `actions`, `ip`, `host`, `login_time`, `logout_time`) VALUES
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 10:59 AM', ''),
(0, 1, 'user@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 11:01 AM', ''),
(0, 1, 'user@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 11:04 AM', ''),
(0, 1, 'user@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 11:17 AM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-05-2026 01:36 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-05-2026 03:09 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-06-2026 08:34 AM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-06-2026 01:22 PM', ''),
(0, 1, 'yana@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-09-2026 09:22 AM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-09-2026 11:43 AM', ''),
(0, 1, 'yana@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-09-2026 11:45 AM', ''),
(0, 1, 'yana@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-09-2026 12:06 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-09-2026 01:30 PM', ''),
(0, 1, 'yana@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 08:29 AM', ''),
(0, 1, 'yana@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 08:38 AM', ''),
(0, 1, 'yana@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 08:54 AM', ''),
(0, 1, 'yana@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 11:11 AM', ''),
(0, 1, 'yana@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 12:49 PM', ''),
(0, 1, 'yana@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 02:23 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 03:39 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 03:51 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 03:54 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 04:37 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 04:40 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-11-2026 09:12 AM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-11-2026 10:51 AM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-11-2026 04:17 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 01:53 PM', ''),
(0, 4, 'patrick@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 02:15 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 02:15 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 02:47 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-13-2026 04:21 PM', '');

-- --------------------------------------------------------

--
-- Table structure for table `history_log1`
--

CREATE TABLE `history_log1` (
  `log_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `admin_user` text NOT NULL,
  `action` varchar(100) NOT NULL,
  `actions` varchar(200) NOT NULL DEFAULT 'Has LoggedOut the system at',
  `ip` text NOT NULL,
  `host` text NOT NULL,
  `login_time` varchar(200) NOT NULL,
  `logout_time` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `history_log1`
--

INSERT INTO `history_log1` (`log_id`, `id`, `admin_user`, `action`, `actions`, `ip`, `host`, `login_time`, `logout_time`) VALUES
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 12:50 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 01:37 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 01:40 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 01:47 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 02:07 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 02:17 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 02:22 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 03:06 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 03:07 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 03:31 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 03:36 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 03:55 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 08:46 AM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 08:48 AM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 09:08 AM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 09:24 AM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 02:05 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 02:55 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 03:30 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 03:37 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 03:39 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 03:39 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 03:42 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 04:20 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 04:28 PM', 'Mar-13-2026 02:08 PM'),
(0, 2, 'cla@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 04:35 PM', 'Mar-03-2026 04:35 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 04:36 PM', 'Mar-12-2026 01:48 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 08:20 AM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 09:01 AM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 10:04 AM', 'Mar-13-2026 02:08 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 11:00 AM', 'Mar-12-2026 01:48 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 11:00 AM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 11:42 AM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 11:42 AM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 11:43 AM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 11:45 AM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-05-2026 10:36 AM', 'Mar-13-2026 02:08 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-05-2026 10:58 AM', 'Mar-12-2026 01:48 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-05-2026 11:18 AM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-06-2026 08:39 AM', 'Mar-13-2026 02:08 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-06-2026 12:34 PM', 'Mar-12-2026 01:48 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-06-2026 12:38 PM', 'Mar-12-2026 01:48 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-06-2026 01:22 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-06-2026 01:23 PM', 'Mar-13-2026 02:08 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-06-2026 02:04 PM', 'Mar-12-2026 01:48 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-06-2026 02:04 PM', 'Mar-12-2026 01:48 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-09-2026 09:22 AM', 'Mar-12-2026 01:48 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-09-2026 10:33 AM', 'Mar-13-2026 02:08 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-09-2026 11:48 AM', 'Mar-12-2026 01:48 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-09-2026 01:32 PM', 'Mar-13-2026 02:08 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 08:33 AM', 'Mar-12-2026 01:48 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 08:39 AM', 'Mar-12-2026 01:48 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 08:50 AM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 08:51 AM', 'Mar-13-2026 02:08 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 08:53 AM', 'Mar-12-2026 01:48 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 12:48 PM', 'Mar-12-2026 01:48 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 12:51 PM', 'Mar-12-2026 01:48 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 01:38 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 02:27 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 03:43 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 03:44 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 03:47 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 03:48 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 04:38 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 04:38 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 04:47 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-11-2026 08:16 AM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-11-2026 08:16 AM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-11-2026 09:12 AM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-11-2026 10:50 AM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-11-2026 10:50 AM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-11-2026 04:01 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-11-2026 04:17 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 08:43 AM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 08:43 AM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 08:50 AM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 08:52 AM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 09:19 AM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 09:22 AM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 09:22 AM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 09:34 AM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 09:44 AM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 12:58 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 01:39 PM', 'Mar-13-2026 02:08 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 01:41 PM', 'Mar-12-2026 01:48 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 01:42 PM', 'Mar-12-2026 01:48 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 01:42 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 01:43 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 01:44 PM', 'Mar-13-2026 02:08 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 01:48 PM', 'Mar-12-2026 01:48 PM'),
(0, 7, 'adm.staff0002@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 01:49 PM', 'Mar-13-2026 03:32 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 02:25 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 02:39 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 04:20 PM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-13-2026 08:51 AM', 'Mar-13-2026 02:08 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-13-2026 09:48 AM', 'Mar-13-2026 02:08 PM'),
(0, 8, 'test@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-13-2026 12:14 PM', 'Mar-13-2026 02:07 PM'),
(0, 8, 'test@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-13-2026 12:27 PM', 'Mar-13-2026 02:07 PM'),
(0, 8, 'test@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-13-2026 12:39 PM', 'Mar-13-2026 02:07 PM'),
(0, 8, 'test@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-13-2026 12:40 PM', 'Mar-13-2026 02:07 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-13-2026 02:08 PM', 'Mar-13-2026 02:08 PM'),
(0, 7, 'adm.staff0002@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-13-2026 02:10 PM', 'Mar-13-2026 03:32 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-13-2026 03:32 PM', '');

-- --------------------------------------------------------

--
-- Table structure for table `login_user`
--

CREATE TABLE `login_user` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `email_address` text NOT NULL,
  `user_password` text NOT NULL,
  `user_status` varchar(50) NOT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `login_user`
--

INSERT INTO `login_user` (`id`, `name`, `email_address`, `user_password`, `user_status`, `department_id`) VALUES
(1, 'Diana Lhiz G. Balastigue', 'yana@gmail.com', '$2y$10$rWW8aRzePJwU6HoFbLdChOIQ.gsf9mNlFctEW8/ckdlBEFhBQE/1K', '', 2),
(3, 'Luigi G. Capulong', 'luigi@gmail.com', '$2y$10$tEiKBHzXvjj5iEXCcnVkdOMfs9GHMwiAh7uHT6vHd5Mgpoy0y.kFe', '', 3),
(4, 'Patrick James M. Flores', 'patrick@gmail.com', '$2y$12$zCgkEFlKpKRwHFIwtjUwKuvAjf8H3Z8RosizjN1kfiSYkHmBEkoJu', '', 3),
(6, 'Victoria L. Mansanas', 'victoria@gmail.com', '$2y$12$7qHBKoBZIot30Nz4eFDvA.9c0dvFOsARAG6yDKFRFdxAuaYvgCpKi', '', 3),
(7, 'Vladimir D. Foreigner', 'vladimir@gmail.com', '$2y$12$JaBAB0XbmVOx3lgDrLXqHOoKvZmPr4F26oMQyRYrUnpWuOXskXSyu', '', 3),
(8, 'Denver G. Henzon', 'denver@gmail.com', '$2y$12$tLrdZSeLzBvpUHMQjk2qRe5/zB1g/ayHYFUqw6E3zpRy2wzH1gf82', '', 3),
(9, 'Gia H. Kensas', 'gia@gmail.com', '$2y$12$F1XwIJiDBJXJSs6ge6jA.eYvrULPTuez9LCe67QZNj4VpOH6L.Ro6', '', 3),
(10, 'Danica E. Santos', 'danica@gmail.com', '$2y$10$Zj6GJrrX3fCe6xfi3QzewOmzHl2B8tmcc5t051NJhxeweBgddXGAW', '', 9),
(11, 'Michelle L. Dee', 'michelle@gmail.com', '$2y$12$eHXi2pxZLihb7HlLiZDzh.XxpI.bsGiX9AL3B4CjSMNMpGsFkOg6e', 'Archived', 3),
(12, 'Eleanor W. Sarmiento', 'eleanor@gmail.com', '$2y$12$9c.KjK3oqP3rGvA6A3dHj.BGeszSrtkJdd.mK1TCo2LjWvOUoyrZG', '', 3),
(13, 'Clarenz James D. Fusilero', 'clarenz@gmail.com', '$2y$12$uFYpNRW2JHb3z684eXLBmO8FGPYlN20lDiXt0Dpw29gFIpgyM8Eka', 'Archived', 3),
(14, 'Lily M. Rosana', 'lily@gmail.com', '$2y$12$2Sl.kzyFb4my6R0YTxfcNu.2KGSeaQA4nkOsZY5Isz1P8FGd52eAm', '', 2);

-- --------------------------------------------------------

--
-- Table structure for table `upload_files`
--

CREATE TABLE `upload_files` (
  `id` int(11) NOT NULL,
  `folder_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `size` varchar(200) NOT NULL,
  `download` int(11) DEFAULT 0,
  `timers` varchar(200) NOT NULL,
  `admin_status` varchar(300) NOT NULL,
  `email` text NOT NULL,
  `status` enum('Active','Archived') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `upload_files`
--

INSERT INTO `upload_files` (`id`, `folder_id`, `name`, `file_path`, `size`, `download`, `timers`, `admin_status`, `email`, `status`) VALUES
(11, 5, 'Ya.pdf', '1773123701_Ya.pdf', '10381', 5, 'Mar-10-2026 07:21 AM', 'Admin', '1', 'Active'),
(12, 9, 'joys.pdf', '1773278605_joys.pdf', '10381', 1, 'Mar-12-2026 02:23 AM', 'Admin', '1', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_login`
--
ALTER TABLE `admin_login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`),
  ADD UNIQUE KEY `department_name` (`department_name`);

--
-- Indexes for table `file_departments`
--
ALTER TABLE `file_departments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_id` (`file_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `folders`
--
ALTER TABLE `folders`
  ADD PRIMARY KEY (`folder_id`),
  ADD UNIQUE KEY `unique_folder_name` (`folder_name`);

--
-- Indexes for table `folder_departments`
--
ALTER TABLE `folder_departments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `folder_id` (`folder_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `login_user`
--
ALTER TABLE `login_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_department` (`department_id`);

--
-- Indexes for table `upload_files`
--
ALTER TABLE `upload_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_folder_id` (`folder_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_login`
--
ALTER TABLE `admin_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `file_departments`
--
ALTER TABLE `file_departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `folders`
--
ALTER TABLE `folders`
  MODIFY `folder_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `folder_departments`
--
ALTER TABLE `folder_departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `login_user`
--
ALTER TABLE `login_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `upload_files`
--
ALTER TABLE `upload_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `file_departments`
--
ALTER TABLE `file_departments`
  ADD CONSTRAINT `file_departments_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `upload_files` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `file_departments_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE CASCADE;

--
-- Constraints for table `folder_departments`
--
ALTER TABLE `folder_departments`
  ADD CONSTRAINT `folder_departments_ibfk_1` FOREIGN KEY (`folder_id`) REFERENCES `folders` (`folder_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `folder_departments_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE CASCADE;

--
-- Constraints for table `login_user`
--
ALTER TABLE `login_user`
  ADD CONSTRAINT `fk_user_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `upload_files`
--
ALTER TABLE `upload_files`
  ADD CONSTRAINT `fk_upload_folder` FOREIGN KEY (`folder_id`) REFERENCES `folders` (`folder_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
