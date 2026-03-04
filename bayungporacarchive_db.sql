-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 04, 2026 at 09:11 AM
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
(1, 'Jelly Concepcion', 'admin@gmail.com', '$2y$10$rMwweZl3R8kxl44.Y.zrs.EfzWwiqo6PlPSupCzl7colpMzTDOpaO', ''),
(3, 'Joys Ann', 'joys@gmail.com', '$2y$12$Zc5USbjNC3b6xQtreGLJvePek5OLs5aHoj/I6Rm8IDONTzio8YvcC', '');

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
(1, 'MENRO', 'Cat1.jpg', 'Archived', '2026-03-04 05:39:37'),
(2, 'LYDO', 'cutecat1.png', 'Active', '2026-03-04 06:43:16');

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
(0, 1, 'user@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 11:17 AM', '');

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
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 12:50 PM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 01:37 PM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 01:40 PM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 01:47 PM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 02:07 PM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 02:17 PM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 02:22 PM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 03:06 PM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 03:07 PM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 03:31 PM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 03:36 PM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-02-2026 03:55 PM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 08:46 AM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 08:48 AM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 09:08 AM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 09:24 AM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 02:05 PM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 02:55 PM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 03:30 PM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 03:37 PM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 03:39 PM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 03:39 PM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 03:42 PM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 04:20 PM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 04:28 PM', 'Mar-04-2026 02:49 PM'),
(0, 2, 'cla@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 04:35 PM', 'Mar-03-2026 04:35 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-03-2026 04:36 PM', 'Mar-04-2026 11:00 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 08:20 AM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 09:01 AM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 10:04 AM', 'Mar-04-2026 02:49 PM'),
(0, 3, 'joys@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 11:00 AM', 'Mar-04-2026 11:00 AM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 11:00 AM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 11:42 AM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 11:42 AM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 11:43 AM', 'Mar-04-2026 02:49 PM'),
(0, 1, 'admin@gmail.com', 'Has LoggedIn the system at', 'Has LoggedOut the system at', '::1', 'ADMInterns', 'Mar-04-2026 11:45 AM', 'Mar-04-2026 02:49 PM');

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
(1, 'User', 'user@gmail.com', '$2y$10$wlXqk6kCOEKuUwL/vULl7OiMe6c1dXyMGneJ38VCAHcxgHJChgEwW', ''),
(3, 'Luigi', 'luigi@gmail.com', '$2y$12$VUjrDPVlry7su2pEyZxFV.nw5L77SPwr73.DkaZwn.kwor0mLNYVO', '');

-- --------------------------------------------------------

--
-- Table structure for table `upload_files`
--

CREATE TABLE `upload_files` (
  `ID` int(11) NOT NULL,
  `NAME` varchar(200) NOT NULL,
  `SIZE` varchar(200) NOT NULL,
  `DOWNLOAD` varchar(200) NOT NULL,
  `TIMERS` varchar(200) NOT NULL,
  `ADMIN_STATUS` varchar(300) NOT NULL,
  `EMAIL` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `upload_files`
--

INSERT INTO `upload_files` (`ID`, `NAME`, `SIZE`, `DOWNLOAD`, `TIMERS`, `ADMIN_STATUS`, `EMAIL`) VALUES
(3, 'kjdfl.pdf', '10381', '1', 'Mar-03-2026 04:31 PM', 'Admin', 'Jelly Concepcion'),
(4, 'Untitled document.pdf', '11363', '0', 'Mar-03-2026 04:35 PM', 'Admin', 'Cla'),
(5, 'joys.pdf', '10381', '0', 'Mar-03-2026 04:36 PM', 'Admin', 'Joys Ann'),
(6, 'Ya.pdf', '10381', '0', 'Mar-04-2026 10:17 AM', 'Admin', 'Jelly Concepcion');

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
-- Indexes for table `login_user`
--
ALTER TABLE `login_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `upload_files`
--
ALTER TABLE `upload_files`
  ADD PRIMARY KEY (`ID`);

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
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `login_user`
--
ALTER TABLE `login_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `upload_files`
--
ALTER TABLE `upload_files`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
