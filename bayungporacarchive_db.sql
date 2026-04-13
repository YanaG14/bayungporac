-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 13, 2026 at 09:39 AM
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
  `admin_status` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'Records Administrator',
  `otp_code` varchar(6) DEFAULT NULL,
  `otp_verified` tinyint(1) NOT NULL DEFAULT 0,
  `otp_reset` varchar(6) DEFAULT NULL,
  `otp_reset_created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin_login`
--

INSERT INTO `admin_login` (`id`, `name`, `admin_user`, `admin_password`, `admin_status`, `role`, `otp_code`, `otp_verified`, `otp_reset`, `otp_reset_created`) VALUES
(1, 'Jelly Concepcion', 'jelly@gmail.com', '$2y$10$WX1C92bWXnikzCdVGKYkD.DDP39fMKJWm16gS5RaeHYuYnxaE/yXi', '', 'Records Administrator', NULL, 1, NULL, NULL),
(3, 'Joys Ann B. Calam', 'joys@gmail.com', '$2y$10$psmG0yfbFuNE0k6.6ixH4.lRTPKoRW5Pf9aoWNVd9JOs1yPrt3XSG', '', 'System Administrator', NULL, 1, NULL, NULL),
(4, 'Fina L. Sagum', 'adm.0003@gmail.com', '$2y$12$AR4vws4b.C9DtLjBUcP7p.BIQwvPmZiGYOuk5KjB23NSqbNICzRmi', 'Archived', 'Records Administrator', NULL, 0, NULL, NULL),
(5, 'Michaella L. De Leon', 'adm.staff0005@gmail.com', '$2y$12$z2MN0nRKhEzcP47Oh1/eSehXuDKTkeqx7iP1oC7CTLBlxHSQshkVG', '', 'Records Administrator', NULL, 0, NULL, NULL),
(6, 'John Kenneth T. Pineda', 'adm.staff0004@gmail.com', '$2y$12$AGWqmadcWklP3RXNTIVhWepGhhcL1jULlw2/.xpjM7TLYxcr2oN7m', '', 'Records Administrator', NULL, 0, NULL, NULL),
(7, 'Robin A. Santiago', 'adm.staff0002@gmail.com', '$2y$12$Qn.jHv8StaXhRehCKVg6L.K9D.kLQZ5X9MsaM7Syp7YOYsDxDGCXG', '', 'Records Administrator', NULL, 0, NULL, NULL),
(25, 'Joys Ann Calam', 'joysradmin@gmail.com', '$2y$12$spX9htgehrrUnAN5BSreKexwJ3Oh.kEbaATSHxYAKuuw2Jc/xsnLe', 'Admin', 'Records Administrator', '989169', 0, NULL, NULL),
(27, 'Test1', 'yanayanaya14@gmail.com', '$2y$10$oyQgt2u.J325newf3DVRve99TnM3HZCq7PNyznjFougfRk.pALAMy', 'Admin', 'Records Administrator', NULL, 1, '143213', '2026-04-07 10:53:59');

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
(1, 'MUNICIPAL ENVIRONMENTAL NATURAL RESOURCES OFFICE', 'MENRO Logo.png', 'Archived', '2026-03-04 05:39:37'),
(2, 'MUNICIPAL LOCAL YOUTH DEVELOPMENT OFFICE', 'LYDO LOGO.jpg', 'Archived', '2026-03-04 06:43:16'),
(3, 'MUNICIPAL HEALTH OFFICE', 'RHU_LOGO.png', 'Active', '2026-03-05 03:06:46'),
(4, 'OFFICE OF THE MUNICIPAL ADMINISTRATOR', 'admin_logo.png', 'Archived', '2026-03-10 06:46:07'),
(5, 'MUNICIPAL ENGINEERING OFFICE', 'MEO LOGO.png', 'Archived', '2026-03-11 01:20:32'),
(6, 'MUNICIPAL GENERAL OFFICE', 'GSO LOGO.png', 'Active', '2026-03-11 01:56:09'),
(7, 'MUNICIPAL MAYOR\'S OFFICE', 'MO LOGO.png', 'Active', '2026-03-11 04:05:24'),
(8, 'MUNICIPAL ACCOUNTING OFFICE', 'MAO LOGO.png', 'Active', '2026-03-11 04:06:07'),
(9, 'MUNICIPAL TREASURER\'S OFFICE', 'MTO LOGO.png', 'Active', '2026-03-11 04:16:17'),
(10, 'MUNICIPAL PLANNING AND DEVELOPMENT OFFICE', 'MPDO - LOGO.png', 'Active', '2026-03-11 05:07:50'),
(11, 'MUNICIPAL TOURISM, ARTS, AND CULTURE OFFICE', 'TourismLogo22x22.jpg', 'Active', '2026-03-11 05:08:50'),
(12, 'HUMAN RESOURCE MANAGEMENT OFFICE', 'PORAC HRMO LOGO.png', 'Active', '2026-03-11 05:10:34'),
(13, 'MUNICIPAL POPULATION OFFICE', 'MPO LOGO.jpg', 'Active', '2026-03-11 05:11:31'),
(14, 'MUNICIPAL AGRICULTURAL SERVICES OFFICE', '953d8c63-a463-467d-9d8d-2e8ee8e8bd26.jfif', 'Active', '2026-03-11 05:12:33');

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
(5, 13, 3),
(6, 13, 5),
(7, 14, 3),
(8, 14, 5),
(9, 15, 3),
(10, 15, 13),
(11, 16, 2),
(14, 19, 3),
(15, 19, 5),
(16, 19, 6),
(17, 20, 3),
(18, 20, 5),
(19, 20, 6),
(20, 22, 3),
(21, 23, 3),
(22, 24, 3),
(23, 25, 3);

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
(38, 'Crystal', 'Active', '2026-03-17 01:29:13');

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
(28, 29, 3),
(40, 5, 2),
(54, 10, 10),
(56, 9, 3),
(57, 38, 3),
(58, 14, 3),
(59, 4, 3);

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
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-13-2026 04:21 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.178.141', '10.50.178.141', 'Mar-17-2026 09:14 AM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 02:40 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 02:46 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 02:51 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 02:52 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 02:54 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 02:54 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 03:08 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 03:10 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 03:11 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 03:13 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 03:14 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 03:54 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-18-2026 03:08 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-18-2026 03:09 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-18-2026 03:17 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-18-2026 03:19 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-18-2026 03:21 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-18-2026 05:04 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-18-2026 05:09 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 03:06 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 03:09 PM', ''),
(0, 21, 'jelsdizoncon@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 04:07 PM', ''),
(0, 21, 'jelsdizoncon@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 04:08 PM', ''),
(0, 23, 'jelsdizoncon@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 04:13 PM', ''),
(0, 23, 'jelsdizoncon@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 04:15 PM', ''),
(0, 24, 'jelsdizoncon@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 04:48 PM', ''),
(0, 25, 'jelsdizoncon@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 05:22 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-23-2026 02:35 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-23-2026 02:40 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-23-2026 02:47 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-23-2026 02:53 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-23-2026 02:55 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-23-2026 03:03 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-23-2026 03:06 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-24-2026 01:32 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-25-2026 01:03 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-26-2026 08:28 AM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-26-2026 01:21 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-26-2026 02:34 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-30-2026 10:34 AM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-30-2026 11:25 AM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-30-2026 11:27 AM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-30-2026 11:43 AM', ''),
(0, 27, 'jelsdizoncon@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Apr-06-2026 03:13 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Apr-08-2026 09:21 AM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Apr-13-2026 12:28 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Apr-13-2026 01:40 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Apr-13-2026 02:33 PM', ''),
(0, 3, 'luigi@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Apr-13-2026 02:35 PM', '');

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
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 12:50 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 01:37 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 01:40 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 01:47 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 02:07 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 02:17 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 02:22 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 03:06 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 03:07 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 03:31 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 03:36 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 03:55 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 08:46 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 08:48 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 09:08 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 09:24 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 02:05 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 02:55 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 03:30 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 03:37 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 03:39 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 03:39 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 03:42 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 04:20 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 04:28 PM', 'Apr-08-2026 09:53 AM'),
(0, 2, 'cla@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 04:35 PM', 'Mar-03-2026 04:35 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 04:36 PM', 'Apr-06-2026 03:00 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 08:20 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 09:01 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 10:04 AM', 'Apr-08-2026 09:53 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 11:00 AM', 'Apr-06-2026 03:00 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 11:00 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 11:42 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 11:42 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 11:43 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 11:45 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-05-2026 10:36 AM', 'Apr-08-2026 09:53 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-05-2026 10:58 AM', 'Apr-06-2026 03:00 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-05-2026 11:18 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-06-2026 08:39 AM', 'Apr-08-2026 09:53 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-06-2026 12:34 PM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-06-2026 12:38 PM', 'Apr-06-2026 03:00 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-06-2026 01:22 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-06-2026 01:23 PM', 'Apr-08-2026 09:53 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-06-2026 02:04 PM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-06-2026 02:04 PM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-09-2026 09:22 AM', 'Apr-06-2026 03:00 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-09-2026 10:33 AM', 'Apr-08-2026 09:53 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-09-2026 11:48 AM', 'Apr-06-2026 03:00 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-09-2026 01:32 PM', 'Apr-08-2026 09:53 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 08:33 AM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 08:39 AM', 'Apr-06-2026 03:00 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 08:50 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 08:51 AM', 'Apr-08-2026 09:53 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 08:53 AM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 12:48 PM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 12:51 PM', 'Apr-06-2026 03:00 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 01:38 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 02:27 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 03:43 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 03:44 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 03:47 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 03:48 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 04:38 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 04:38 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 04:47 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-11-2026 08:16 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-11-2026 08:16 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-11-2026 09:12 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-11-2026 10:50 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-11-2026 10:50 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-11-2026 04:01 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-11-2026 04:17 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 08:43 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 08:43 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 08:50 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 08:52 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 09:19 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 09:22 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 09:22 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 09:34 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 09:44 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 12:58 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 01:39 PM', 'Apr-08-2026 09:53 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 01:41 PM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 01:42 PM', 'Apr-06-2026 03:00 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 01:42 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 01:43 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 01:44 PM', 'Apr-08-2026 09:53 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 01:48 PM', 'Apr-06-2026 03:00 PM'),
(0, 7, 'adm.staff0002@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 01:49 PM', 'Mar-13-2026 03:32 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 02:25 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 02:39 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-12-2026 04:20 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-13-2026 08:51 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-13-2026 09:48 AM', 'Apr-08-2026 09:53 AM'),
(0, 8, 'test@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-13-2026 12:14 PM', 'Mar-13-2026 02:07 PM'),
(0, 8, 'test@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-13-2026 12:27 PM', 'Mar-13-2026 02:07 PM'),
(0, 8, 'test@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-13-2026 12:39 PM', 'Mar-13-2026 02:07 PM'),
(0, 8, 'test@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-13-2026 12:40 PM', 'Mar-13-2026 02:07 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-13-2026 02:08 PM', 'Apr-08-2026 09:53 AM'),
(0, 7, 'adm.staff0002@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-13-2026 02:10 PM', 'Mar-13-2026 03:32 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-13-2026 03:32 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 09:12 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.178.141', '10.50.178.141', 'Mar-17-2026 09:12 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.178.141', '10.50.178.141', 'Mar-17-2026 09:13 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.178.141', '10.50.178.141', 'Mar-17-2026 09:17 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 10:32 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 11:41 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 11:49 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 11:50 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 11:50 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 11:52 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 12:00 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 12:06 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 01:08 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 01:10 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 01:16 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 01:20 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 01:21 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 01:22 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 02:33 PM', 'Apr-08-2026 09:53 AM'),
(0, 11, 'test1009@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 02:37 PM', 'Mar-18-2026 05:15 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 02:37 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 02:42 PM', 'Apr-08-2026 09:53 AM'),
(0, 11, 'test1009@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 02:42 PM', 'Mar-18-2026 05:15 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 02:46 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 02:47 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 02:55 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 02:56 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 02:57 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 02:58 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 02:58 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 03:00 PM', 'Apr-08-2026 09:53 AM'),
(0, 11, 'test1009@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 03:01 PM', 'Mar-18-2026 05:15 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 03:01 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 03:04 PM', 'Apr-08-2026 09:53 AM'),
(0, 11, 'test1009@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 03:05 PM', 'Mar-18-2026 05:15 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 03:08 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 03:11 PM', 'Apr-08-2026 09:53 AM'),
(0, 11, 'test1009@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 03:11 PM', 'Mar-18-2026 05:15 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 03:45 PM', 'Apr-08-2026 09:53 AM'),
(0, 11, 'test1009@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 03:46 PM', 'Mar-18-2026 05:15 PM'),
(0, 11, 'test1009@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 03:48 PM', 'Mar-18-2026 05:15 PM'),
(0, 11, 'test1009@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 03:49 PM', 'Mar-18-2026 05:15 PM'),
(0, 11, 'test1009@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 03:53 PM', 'Mar-18-2026 05:15 PM'),
(0, 11, 'test1009@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 03:53 PM', 'Mar-18-2026 05:15 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-17-2026 03:53 PM', 'Apr-08-2026 09:53 AM'),
(0, 11, 'test1009@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-17-2026 04:36 PM', 'Mar-18-2026 05:15 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'LAPTOP-9VQHFE0A', 'Mar-18-2026 02:58 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.178.141', '10.50.178.141', 'Mar-18-2026 03:02 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-18-2026 03:08 PM', 'Apr-08-2026 09:53 AM'),
(0, 11, 'test1009@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-18-2026 03:14 PM', 'Mar-18-2026 05:15 PM'),
(0, 11, 'test1009@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-18-2026 03:23 PM', 'Mar-18-2026 05:15 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-18-2026 04:32 PM', 'Apr-08-2026 09:53 AM'),
(0, 14, 'jellydizonconcepcion@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-18-2026 04:47 PM', 'Mar-18-2026 04:49 PM'),
(0, 11, 'test1009@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-18-2026 04:55 PM', 'Mar-18-2026 05:15 PM'),
(0, 15, 'joysanncalam259@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-18-2026 04:59 PM', 'Mar-19-2026 03:14 PM'),
(0, 11, 'test1009@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-18-2026 05:04 PM', 'Mar-18-2026 05:15 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.178.141', '10.50.178.141', 'Mar-18-2026 05:04 PM', 'Apr-08-2026 09:53 AM'),
(0, 11, 'test1009@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-18-2026 05:07 PM', 'Mar-18-2026 05:15 PM'),
(0, 15, 'joysanncalam259@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-18-2026 05:08 PM', 'Mar-19-2026 03:14 PM'),
(0, 11, 'test1009@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-18-2026 05:10 PM', 'Mar-18-2026 05:15 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.178.141', '10.50.178.141', 'Mar-18-2026 05:13 PM', 'Apr-08-2026 09:53 AM'),
(0, 11, 'test1009@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-18-2026 05:15 PM', 'Mar-18-2026 05:15 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-18-2026 05:15 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-18-2026 05:17 PM', 'Apr-08-2026 09:53 AM'),
(0, 3, 'Joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 09:42 AM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 09:46 AM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'Joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 01:30 PM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'Joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 02:08 PM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'Joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 02:17 PM', 'Apr-06-2026 03:00 PM'),
(0, 15, 'joysanncalam259@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 02:40 PM', 'Mar-19-2026 03:14 PM'),
(0, 15, 'joysanncalam259@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 02:56 PM', 'Mar-19-2026 03:14 PM'),
(0, 15, 'joysanncalam259@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 03:03 PM', 'Mar-19-2026 03:14 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 03:04 PM', 'Apr-08-2026 09:53 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 03:05 PM', 'Apr-06-2026 03:00 PM'),
(0, 15, 'joysanncalam259@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 03:13 PM', 'Mar-19-2026 03:14 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 03:13 PM', 'Apr-08-2026 09:53 AM'),
(0, 15, 'joysanncalam259@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 03:13 PM', 'Mar-19-2026 03:14 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 03:14 PM', 'Apr-06-2026 03:00 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 04:02 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 04:07 PM', 'Apr-08-2026 09:53 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 04:09 PM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 04:12 PM', 'Apr-06-2026 03:00 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 04:19 PM', 'Apr-08-2026 09:53 AM'),
(0, 18, 'jelsdizoncon@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 04:22 PM', 'Mar-19-2026 04:22 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 04:22 PM', 'Apr-06-2026 03:00 PM'),
(0, 19, 'jelsdizoncon@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 04:24 PM', ''),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 04:58 PM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 04:58 PM', 'Apr-06-2026 03:00 PM'),
(0, 20, 'jelsdizoncon@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 05:01 PM', 'Mar-19-2026 05:12 PM'),
(0, 20, 'jelsdizoncon@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 05:11 PM', 'Mar-19-2026 05:12 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 05:24 PM', 'Apr-06-2026 03:00 PM'),
(0, 21, 'jelsdizoncon@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 05:26 PM', ''),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 05:35 PM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 05:35 PM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 05:38 PM', 'Apr-06-2026 03:00 PM'),
(0, 22, 'jelsdizoncon@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 05:47 PM', 'Mar-19-2026 05:51 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 06:05 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-19-2026 06:18 PM', 'Apr-08-2026 09:53 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-23-2026 10:16 AM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-23-2026 10:48 AM', 'Apr-06-2026 03:00 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-23-2026 11:46 AM', 'Apr-08-2026 09:53 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-23-2026 11:59 AM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-23-2026 12:02 PM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-23-2026 02:28 PM', 'Apr-06-2026 03:00 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-23-2026 02:47 PM', 'Apr-08-2026 09:53 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-23-2026 02:47 PM', 'Apr-06-2026 03:00 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-23-2026 02:50 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-23-2026 02:54 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-23-2026 03:01 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-23-2026 03:04 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-23-2026 03:08 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-23-2026 03:22 PM', 'Apr-08-2026 09:53 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-23-2026 03:23 PM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-23-2026 03:25 PM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-23-2026 03:54 PM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-24-2026 09:39 AM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-24-2026 11:39 AM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-25-2026 08:32 AM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-25-2026 10:25 AM', 'Apr-06-2026 03:00 PM'),
(0, 11, 'test1009@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-25-2026 10:37 AM', ''),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-25-2026 11:54 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-25-2026 01:30 PM', 'Apr-08-2026 09:53 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-25-2026 01:43 PM', 'Apr-06-2026 03:00 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-25-2026 02:33 PM', 'Apr-08-2026 09:53 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-25-2026 02:56 PM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-26-2026 08:25 AM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-26-2026 08:52 AM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-26-2026 12:09 PM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-26-2026 12:14 PM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-26-2026 01:38 PM', 'Apr-06-2026 03:00 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-26-2026 02:30 PM', 'Apr-08-2026 09:53 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-26-2026 03:12 PM', 'Apr-06-2026 03:00 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Mar-26-2026 03:13 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-26-2026 05:40 PM', 'Apr-08-2026 09:53 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-30-2026 08:49 AM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-30-2026 10:34 AM', 'Apr-06-2026 03:00 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-30-2026 11:15 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-30-2026 11:26 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-30-2026 11:43 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-30-2026 01:13 PM', 'Apr-08-2026 09:53 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-30-2026 03:36 PM', 'Apr-06-2026 03:00 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-30-2026 03:46 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-30-2026 03:48 PM', 'Apr-08-2026 09:53 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-31-2026 08:28 AM', 'Apr-06-2026 03:00 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-31-2026 08:29 AM', 'Apr-08-2026 09:53 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-31-2026 11:41 AM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Mar-31-2026 05:11 PM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Apr-06-2026 08:51 AM', 'Apr-06-2026 03:00 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Apr-06-2026 08:52 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Apr-06-2026 09:46 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Apr-06-2026 09:51 AM', 'Apr-08-2026 09:53 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Apr-06-2026 10:11 AM', 'Apr-06-2026 03:00 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Apr-06-2026 10:14 AM', 'Apr-08-2026 09:53 AM'),
(0, 26, 'yanayanaya14@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Apr-06-2026 12:00 PM', 'Apr-06-2026 01:42 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Apr-06-2026 12:56 PM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Apr-06-2026 12:56 PM', 'Apr-06-2026 03:00 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Apr-06-2026 01:24 PM', 'Apr-06-2026 03:00 PM'),
(0, 26, 'yanayanaya14@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Apr-06-2026 01:42 PM', 'Apr-06-2026 01:42 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Apr-06-2026 02:58 PM', 'Apr-06-2026 03:00 PM'),
(0, 27, 'yanayanaya14@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Apr-06-2026 03:14 PM', 'Apr-06-2026 03:14 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Apr-06-2026 03:37 PM', 'Apr-08-2026 09:53 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Apr-06-2026 03:48 PM', ''),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.139.159', 'LAPTOP-9VQHFE0A.lan', 'Apr-06-2026 04:12 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Apr-07-2026 03:23 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.178.141', '10.50.178.141', 'Apr-07-2026 04:13 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.178.141', '10.50.178.141', 'Apr-07-2026 04:15 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Apr-07-2026 04:58 PM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Apr-08-2026 08:50 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Apr-08-2026 09:22 AM', 'Apr-08-2026 09:53 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '10.50.128.29', 'ADMInterns.lan', 'Apr-08-2026 09:54 AM', ''),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Apr-13-2026 10:22 AM', ''),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Apr-13-2026 11:25 AM', ''),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Apr-13-2026 12:30 PM', ''),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Apr-13-2026 01:17 PM', '');

-- --------------------------------------------------------

--
-- Table structure for table `homepage_about`
--

CREATE TABLE `homepage_about` (
  `about_id` int(11) NOT NULL,
  `content` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `homepage_about`
--

INSERT INTO `homepage_about` (`about_id`, `content`) VALUES
(1, 'The name “Porac” is derived from the word “purac” after the abundant Purac tress encountered by the first settlers and is believed to be the first town established in Pampanga. The then Provincial Surveyor Don Ramon N. Orozco in 1879 called the river in this place “Poraq River” in his sketch of the nearby town of Floridablanca. Puraq later became what is now known as Porac on September 16, 1867.');

-- --------------------------------------------------------

--
-- Table structure for table `homepage_events`
--

CREATE TABLE `homepage_events` (
  `event_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `homepage_events`
--

INSERT INTO `homepage_events` (`event_id`, `title`, `description`, `status`) VALUES
(1, 'Power Interruption Notice', 'The Municipal Government informs all residents that there will be a scheduled power interruption in certain areas due to maintenance and upgrading of electrical lines. This temporary interruption is necessary to ensure the continued improvement of the power distribution system and to prevent future technical issues. Residents are advised to prepare in advance by charging essential devices, securing electrical appliances, and planning activities accordingly. The interruption will last for a specified duration and power will be restored immediately upon completion of the maintenance work. Everyone is asked for their understanding and cooperation during this period as these efforts aim to provide more reliable and efficient electricity service to the community.', 'Active'),
(2, 'Municipal Announcement', 'The Municipal Government informs all residents of important updates regarding ongoing programs and upcoming activities within the community. Citizens are encouraged to stay informed about scheduled events, public services, and any new guidelines issued by the local government. This announcement may include details such as health services, infrastructure projects, community events, and public safety reminders. All residents are advised to cooperate and participate in municipal initiatives to help maintain order, safety, and progress in the locality. For further information or clarification, the public may coordinate with the designated municipal offices or visit official communication channels regularly.', 'Active'),
(3, 'BAYUNG PORAC ARCHIVE SYSTEM UPDATE NOTICE', 'Good day everyone!  We would like to inform all concerned individuals that there will be an upcoming update regarding our current system and services. This initiative is part of our continuous effort to improve efficiency, enhance user experience, and ensure that all operations are running smoothly and securely.  During this period, some features may be temporarily unavailable or may experience slight delays. We kindly ask for your patience and understanding as we work on these improvements. Rest assured that our team is doing its best to minimize any inconvenience and to complete the update as quickly as possible.  We encourage everyone to stay tuned for further announcements and updates. Your cooperation and support are highly appreciated as we strive to provide better service for all.  Thank you for your understanding.  — Management', 'Active'),
(10, 'Water Supply Advisory', 'The Municipal Government informs all residents that there will be a temporary interruption and low water pressure in certain areas due to maintenance and improvement works on the water distribution system. This activity is necessary to ensure the continuous delivery of safe and clean water services to the community. Residents are advised to store enough water in advance and use it wisely during the affected period. Normal water supply will resume once the maintenance work is completed. The public is requested to understand and cooperate as these improvements are made for the long-term benefit of the community.', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `homepage_featured`
--

CREATE TABLE `homepage_featured` (
  `featured_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `homepage_featured`
--

INSERT INTO `homepage_featured` (`featured_id`, `title`, `image`, `status`) VALUES
(1, 'Bayung Porac Kasalang Bayan 2026', 'kasalan.jpg', 'Active'),
(2, 'Zumba Session ', 'zumba.jpg', 'Active'),
(3, 'Anniversary', 'anniversary.jpg', 'Active'),
(4, 'Flag Raising Ceremony', 'flag.jpg', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `homepage_profiles`
--

CREATE TABLE `homepage_profiles` (
  `profile_id` int(11) NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `homepage_profiles`
--

INSERT INTO `homepage_profiles` (`profile_id`, `role`, `name`, `description`, `image`, `status`) VALUES
(1, 'Municipal Mayor', 'Mayor Jaime V. Capil', 'Mayor', 'pic6.jfif', 'Active'),
(2, 'Municipal Vice Mayor', 'Trisha Angelie G. Capil', 'vice', 'pic5.jpg', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `homepage_slides`
--

CREATE TABLE `homepage_slides` (
  `slide_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `homepage_slides`
--

INSERT INTO `homepage_slides` (`slide_id`, `image`, `caption`, `description`, `status`) VALUES
(2, 'HOMEPAGE2.webp', ' Porac gets Seal of Good Local Governance', 'Porac gets Seal of Good Local Governance', 'Active'),
(3, 'HOMEPAGE4.webp', 'At the Municipal Hall of Porac Pampanga', 'At the Municipal Hall of Porac Pampanga', 'Active'),
(4, 'HOMEPAGE5.webp', 'Municipality of Porac for being Awardee of 2019 DILG Seal of Good Local Governance', 'Municipality of Porac for being Awardee of 2019 DILG Seal of Good Local Governance', 'Active'),
(6, 'HOMEPAGE8.webp', 'Art exhibit marks Porac 427th founding anniversary celebration', 'Art exhibit marks Porac 427th founding anniversary celebration', 'Active'),
(8, 'HOMEPAGE9.webp', 'Explaining the Plans and Programs of the Mayor for the Municipality of Porac', 'Explaining the Plans and Programs of the Mayor for the Municipality of Porac', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `letters`
--

CREATE TABLE `letters` (
  `id` int(11) NOT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `date_received` date DEFAULT NULL,
  `subject` text DEFAULT NULL,
  `sender` varchar(255) DEFAULT NULL,
  `source` enum('Internal','External') DEFAULT NULL,
  `file_name` varchar(100) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `status` enum('Open','Done') DEFAULT 'Open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `department_id` int(11) NOT NULL,
  `download` int(11) DEFAULT NULL,
  `letter_status` varchar(100) NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `letters`
--

INSERT INTO `letters` (`id`, `reference_no`, `date_received`, `subject`, `sender`, `source`, `file_name`, `file_path`, `status`, `created_at`, `department_id`, `download`, `letter_status`) VALUES
(15, '123432', '2026-04-13', 'TEST1', 'Joys Ann B. Calam', 'Internal', 'Ya (1).pdf', '1776057038_Ya (1).pdf', 'Open', '2026-04-13 05:10:38', 0, NULL, 'Active'),
(16, '09876', '2026-04-13', 'TEST1', 'Joys Ann B. Calam', 'External', 'sample code.pdf', '1776057547_sample code.pdf', 'Open', '2026-04-13 05:19:07', 0, NULL, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `letter_departments`
--

CREATE TABLE `letter_departments` (
  `id` int(11) NOT NULL,
  `letter_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `letter_departments`
--

INSERT INTO `letter_departments` (`id`, `letter_id`, `department_id`, `created_at`) VALUES
(1, 14, 3, '2026-04-13 04:49:34'),
(2, 14, 5, '2026-04-13 04:49:34'),
(3, 8, 3, '2026-04-13 05:00:46'),
(4, 15, 3, '2026-04-13 05:10:38'),
(5, 15, 5, '2026-04-13 05:10:38'),
(6, 16, 3, '2026-04-13 05:19:07'),
(7, 16, 6, '2026-04-13 05:19:07');

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
  `department_id` int(11) DEFAULT NULL,
  `otp_code` int(6) DEFAULT NULL,
  `otp_verified` tinyint(1) NOT NULL DEFAULT 0,
  `otp_reset` varchar(6) DEFAULT NULL,
  `otp_reset_created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `login_user`
--

INSERT INTO `login_user` (`id`, `name`, `email_address`, `user_password`, `user_status`, `department_id`, `otp_code`, `otp_verified`, `otp_reset`, `otp_reset_created`) VALUES
(1, 'Diana Lhiz G. Balastigue', 'yana@gmail.com', '$2y$10$rWW8aRzePJwU6HoFbLdChOIQ.gsf9mNlFctEW8/ckdlBEFhBQE/1K', '', 2, NULL, 0, NULL, NULL),
(3, 'Luigi G. Capulong', 'luigi@gmail.com', '$2y$10$tEiKBHzXvjj5iEXCcnVkdOMfs9GHMwiAh7uHT6vHd5Mgpoy0y.kFe', '', 3, NULL, 1, NULL, NULL),
(4, 'Patrick James M. Flores', 'patrick@gmail.com', '$2y$12$zCgkEFlKpKRwHFIwtjUwKuvAjf8H3Z8RosizjN1kfiSYkHmBEkoJu', '', 3, NULL, 0, NULL, NULL),
(6, 'Victoria L. Mansanas', 'victoria@gmail.com', '$2y$12$7qHBKoBZIot30Nz4eFDvA.9c0dvFOsARAG6yDKFRFdxAuaYvgCpKi', '', 3, NULL, 0, NULL, NULL),
(7, 'Vladimir D. Foreigner', 'vladimir@gmail.com', '$2y$12$JaBAB0XbmVOx3lgDrLXqHOoKvZmPr4F26oMQyRYrUnpWuOXskXSyu', '', 3, NULL, 0, NULL, NULL),
(8, 'Denver G. Henzon', 'denver@gmail.com', '$2y$12$tLrdZSeLzBvpUHMQjk2qRe5/zB1g/ayHYFUqw6E3zpRy2wzH1gf82', '', 3, NULL, 0, NULL, NULL),
(9, 'Gia H. Kensas', 'gia@gmail.com', '$2y$12$F1XwIJiDBJXJSs6ge6jA.eYvrULPTuez9LCe67QZNj4VpOH6L.Ro6', '', 3, NULL, 0, NULL, NULL),
(10, 'Danica E. Santos', 'danica@gmail.com', '', '', 9, NULL, 0, NULL, NULL),
(11, 'Michelle L. Dee', 'michelle@gmail.com', '$2y$12$eHXi2pxZLihb7HlLiZDzh.XxpI.bsGiX9AL3B4CjSMNMpGsFkOg6e', 'Archived', 3, NULL, 0, NULL, NULL),
(12, 'Eleanor W. Sarmiento', 'eleanor@gmail.com', '$2y$12$9c.KjK3oqP3rGvA6A3dHj.BGeszSrtkJdd.mK1TCo2LjWvOUoyrZG', '', 3, NULL, 0, NULL, NULL),
(13, 'Clarenz James D. Fusilero', 'clarenz@gmail.com', '$2y$12$uFYpNRW2JHb3z684eXLBmO8FGPYlN20lDiXt0Dpw29gFIpgyM8Eka', 'Archived', 3, NULL, 0, NULL, NULL),
(14, 'Lily M. Rosana', 'lily@gmail.com', '$2y$12$2Sl.kzyFb4my6R0YTxfcNu.2KGSeaQA4nkOsZY5Isz1P8FGd52eAm', '', 2, NULL, 0, NULL, NULL);

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
(13, 9, 'Sample 2.pdf', '1773915712_Sample 2.pdf', '12817', 2, 'Mar-19-2026 11:21 AM', 'Admin', '1', 'Active'),
(14, 9, 'Sample 1.pdf', '1773915712_Sample 1.pdf', '12594', 5, 'Mar-19-2026 11:21 AM', 'Admin', '1', 'Active'),
(15, 29, 'Sample 4.pdf', '1774249540_Sample 4.pdf', '12716', 1, 'Mar-23-2026 08:05 AM', 'Admin', '1', 'Active'),
(16, 9, 'letter for soft copy photos (officials).docx', '1774926993_letter for soft copy photos (officials).docx', '7125', 1, 'Mar-31-2026 05:16 AM', 'Admin', '1', 'Active'),
(19, 9, 'Sample docs.docx', '1775547061_Sample docs.docx', '6505', 0, 'Apr-07-2026 09:31 AM', 'Admin', '1', 'Active'),
(20, 9, 'Sample pdf.pdf', '1775547061_Sample pdf.pdf', '13088', 0, 'Apr-07-2026 09:31 AM', 'Admin', '1', 'Active'),
(21, 9, 'Untitled spreadsheet.xlsx', '1775547454_Untitled spreadsheet.xlsx', '5053', 0, 'Apr-07-2026 09:37 AM', 'Admin', '1', 'Active'),
(22, 9, 'simple_excel.xlsx', '1775547646_simple_excel.xlsx', '4857', 0, 'Apr-07-2026 09:40 AM', 'Admin', '1', 'Active'),
(23, 9, 'BSIT 2A FINAL REQUIREMENTS.xlsx', '1775548198_BSIT 2A FINAL REQUIREMENTS.xlsx', '23793', 1, 'Apr-07-2026 09:49 AM', 'Admin', '1', 'Active'),
(24, 9, 'sample code docs.docx', '1775548871_sample code docs.docx', '10985', 0, 'Apr-07-2026 10:01 AM', 'Admin', '1', 'Active'),
(25, 9, 'sample code.pdf', '1775548871_sample code.pdf', '80103', 1, 'Apr-07-2026 10:01 AM', 'Admin', '1', 'Active');

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
-- Indexes for table `homepage_about`
--
ALTER TABLE `homepage_about`
  ADD PRIMARY KEY (`about_id`);

--
-- Indexes for table `homepage_events`
--
ALTER TABLE `homepage_events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `homepage_featured`
--
ALTER TABLE `homepage_featured`
  ADD PRIMARY KEY (`featured_id`);

--
-- Indexes for table `homepage_profiles`
--
ALTER TABLE `homepage_profiles`
  ADD PRIMARY KEY (`profile_id`);

--
-- Indexes for table `homepage_slides`
--
ALTER TABLE `homepage_slides`
  ADD PRIMARY KEY (`slide_id`);

--
-- Indexes for table `letters`
--
ALTER TABLE `letters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `letter_departments`
--
ALTER TABLE `letter_departments`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `file_departments`
--
ALTER TABLE `file_departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `folders`
--
ALTER TABLE `folders`
  MODIFY `folder_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `folder_departments`
--
ALTER TABLE `folder_departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `homepage_about`
--
ALTER TABLE `homepage_about`
  MODIFY `about_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `homepage_events`
--
ALTER TABLE `homepage_events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `homepage_featured`
--
ALTER TABLE `homepage_featured`
  MODIFY `featured_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `homepage_profiles`
--
ALTER TABLE `homepage_profiles`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `homepage_slides`
--
ALTER TABLE `homepage_slides`
  MODIFY `slide_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `letters`
--
ALTER TABLE `letters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `letter_departments`
--
ALTER TABLE `letter_departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `login_user`
--
ALTER TABLE `login_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `upload_files`
--
ALTER TABLE `upload_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

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
