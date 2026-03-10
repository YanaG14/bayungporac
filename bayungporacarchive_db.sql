-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2026 at 06:52 AM
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
(1, 'Jelly D. Concepcion', 'jelly@gmail.com', '$2y$10$Z.cC/NBMLIrgo9Jj9rmOseFbyhkdixVrQueJ9DJ0SC.0c1xU90PUG', ''),
(3, 'Joys Ann B. Calam', 'joys@gmail.com', '$2y$10$psmG0yfbFuNE0k6.6ixH4.lRTPKoRW5Pf9aoWNVd9JOs1yPrt3XSG', '');

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
(1, 'MENRO', 'MENRO Logo.png', 'Archived', '2026-03-04 05:39:37'),
(2, 'LYDO', 'LYDO LOGO.jpg', 'Active', '2026-03-04 06:43:16'),
(3, 'RHU', 'RHU LOGO.png', 'Active', '2026-03-05 03:06:46');

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
(1, 10, 2);

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
(4, 'Letters', 'Archived', '2026-03-10 05:45:53'),
(5, 'Certifications', 'Active', '2026-03-10 05:47:28');

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
(1, 4, 1),
(2, 4, 2),
(3, 5, 1);

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
(0, 1, 'yana@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 12:49 PM', '');

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
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 12:50 PM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 01:37 PM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 01:40 PM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 01:47 PM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 02:07 PM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 02:17 PM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 02:22 PM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 03:06 PM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 03:07 PM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 03:31 PM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 03:36 PM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 03:55 PM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 08:46 AM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 08:48 AM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 09:08 AM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 09:24 AM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 02:05 PM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 02:55 PM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 03:30 PM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 03:37 PM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 03:39 PM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 03:39 PM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 03:42 PM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 04:20 PM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 04:28 PM', 'Mar-09-2026 11:43 AM'),
(0, 2, 'cla@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 04:35 PM', 'Mar-03-2026 04:35 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 04:36 PM', 'Mar-10-2026 01:37 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 08:20 AM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 09:01 AM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 10:04 AM', 'Mar-09-2026 11:43 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 11:00 AM', 'Mar-10-2026 01:37 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 11:00 AM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 11:42 AM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 11:42 AM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 11:43 AM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 11:45 AM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-05-2026 10:36 AM', 'Mar-09-2026 11:43 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-05-2026 10:58 AM', 'Mar-10-2026 01:37 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-05-2026 11:18 AM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-06-2026 08:39 AM', 'Mar-09-2026 11:43 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-06-2026 12:34 PM', 'Mar-10-2026 01:37 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-06-2026 12:38 PM', 'Mar-10-2026 01:37 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-06-2026 01:22 PM', 'Mar-09-2026 11:43 AM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-06-2026 01:23 PM', 'Mar-09-2026 11:43 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-06-2026 02:04 PM', 'Mar-10-2026 01:37 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-06-2026 02:04 PM', 'Mar-10-2026 01:37 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-09-2026 09:22 AM', 'Mar-10-2026 01:37 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-09-2026 10:33 AM', 'Mar-09-2026 11:43 AM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-09-2026 11:48 AM', 'Mar-10-2026 01:37 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-09-2026 01:32 PM', ''),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 08:33 AM', 'Mar-10-2026 01:37 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 08:39 AM', 'Mar-10-2026 01:37 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 08:50 AM', ''),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 08:51 AM', ''),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 08:53 AM', 'Mar-10-2026 01:37 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 12:48 PM', 'Mar-10-2026 01:37 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 12:51 PM', 'Mar-10-2026 01:37 PM'),
(0, 1, 'jelly@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-10-2026 01:38 PM', '');

-- --------------------------------------------------------

--
-- Table structure for table `login_user`
--

CREATE TABLE `login_user` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `email_address` text NOT NULL,
  `user_password` text NOT NULL,
  `user_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `login_user`
--

INSERT INTO `login_user` (`id`, `name`, `email_address`, `user_password`, `user_status`) VALUES
(1, 'Diana Lhiz G. Balastigue', 'yana@gmail.com', '$2y$10$L9aJR00hrNhhH4tujFMOIuVo/QOFR1THFSoF1JqbqPWdx1ujvapXu', ''),
(3, 'Luigi G. Capulong', 'luigi@gmail.com', '$2y$10$tEiKBHzXvjj5iEXCcnVkdOMfs9GHMwiAh7uHT6vHd5Mgpoy0y.kFe', ''),
(4, 'Patrick James M. Flores', 'patrick@gmail.com', '$2y$12$zCgkEFlKpKRwHFIwtjUwKuvAjf8H3Z8RosizjN1kfiSYkHmBEkoJu', ''),
(5, 'Alexa V. Ridore', 'alexa@gmail.com', '$2y$12$YRWYytTVzofz8fePvnulmeDfkGRo7WlKcV8CWQe3Eg6koxW4DyUNi', ''),
(6, 'Victoria L. Mansanas', 'victoria@gmail.com', '$2y$12$7qHBKoBZIot30Nz4eFDvA.9c0dvFOsARAG6yDKFRFdxAuaYvgCpKi', ''),
(7, 'Vladimir D. Foreigner', 'vladimir@gmail.com', '$2y$12$JaBAB0XbmVOx3lgDrLXqHOoKvZmPr4F26oMQyRYrUnpWuOXskXSyu', ''),
(8, 'Denver G. Henzon', 'denver@gmail.com', '$2y$12$tLrdZSeLzBvpUHMQjk2qRe5/zB1g/ayHYFUqw6E3zpRy2wzH1gf82', ''),
(9, 'Gia H. Kensas', 'gia@gmail.com', '$2y$12$F1XwIJiDBJXJSs6ge6jA.eYvrULPTuez9LCe67QZNj4VpOH6L.Ro6', ''),
(10, 'Danica E. Santos', 'danica@gmail.com', '$2y$12$OdoHxtQSz5S4SmRhraWxo.wm6fxDm1lONP5Kzt94hkLeC6QYVCUOS', ''),
(11, 'Michelle L. Dee', 'michelle@gmail.com', '$2y$12$eHXi2pxZLihb7HlLiZDzh.XxpI.bsGiX9AL3B4CjSMNMpGsFkOg6e', ''),
(12, 'Eleanor W. Sarmiento', 'eleanor@gmail.com', '$2y$12$9c.KjK3oqP3rGvA6A3dHj.BGeszSrtkJdd.mK1TCo2LjWvOUoyrZG', '');

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
(10, 5, 'joys.pdf', '', '10381', 1, 'Mar-10-2026 06:47 AM', 'Admin', '1', 'Active');

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
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `file_departments`
--
ALTER TABLE `file_departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `folders`
--
ALTER TABLE `folders`
  MODIFY `folder_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `folder_departments`
--
ALTER TABLE `folder_departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `login_user`
--
ALTER TABLE `login_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `upload_files`
--
ALTER TABLE `upload_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
-- Constraints for table `upload_files`
--
ALTER TABLE `upload_files`
  ADD CONSTRAINT `fk_upload_folder` FOREIGN KEY (`folder_id`) REFERENCES `folders` (`folder_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
