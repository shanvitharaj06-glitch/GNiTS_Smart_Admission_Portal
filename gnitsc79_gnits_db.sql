-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 10, 2026 at 11:17 AM
-- Server version: 8.0.45-36
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gnitsc79_gnits_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'Admin', '$2y$12$U.zkIzh9cv5x0OqMeD2f8uWPbHYrQrChKl.UCzven9YZUjEI.1ruq'),
(2, 'Admin', '$2y$12$U.zkIzh9cv5x0OqMeD2f8uWPbHYrQrChKl.UCzven9YZUjEI.1ruq');

-- --------------------------------------------------------

--
-- Table structure for table `jee`
--

CREATE TABLE `jee` (
  `id` int NOT NULL,
  `reference_no` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gender` enum('F','M','O') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `father` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mother` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `community` enum('SC','ST','OBC','EWS','GENERAL','OC','OBC','BC-A','BC-B','BC-C','BC-D','BC-E') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address1` text COLLATE utf8mb4_general_ci,
  `address2` text COLLATE utf8mb4_general_ci,
  `city` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `state` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `zip` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `country` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `confirm_email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `aadhar` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mobile` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `board` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `passing_year` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tenth` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `intermediate_hall_ticket` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `inter_percentage` decimal(5,2) DEFAULT NULL,
  `inter_group_percentage` decimal(5,2) DEFAULT NULL,
  `total_marks` int DEFAULT NULL,
  `group_marks` int DEFAULT NULL,
  `jee_hall_ticket` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jee_rank` int DEFAULT NULL,
  `jee_percentile` decimal(5,2) DEFAULT NULL,
  `eamcet_hall_ticket` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `eamcet_rank` int DEFAULT NULL,
  `pref1` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pref2` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pref3` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pref4` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pref5` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `inter` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jee_card` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `eamcet_card` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `stud_sign` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `parent_sign` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `txnid` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payu_status` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payment_status` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `payment_amount` decimal(10,2) DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Pending','Approved','Rejected') COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `allotted_branch` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jee`
--

INSERT INTO `jee` (`id`, `reference_no`, `name`, `gender`, `dob`, `father`, `mother`, `community`, `address1`, `address2`, `city`, `state`, `zip`, `country`, `email`, `confirm_email`, `aadhar`, `mobile`, `board`, `passing_year`, `tenth`, `intermediate_hall_ticket`, `inter_percentage`, `inter_group_percentage`, `total_marks`, `group_marks`, `jee_hall_ticket`, `jee_rank`, `jee_percentile`, `eamcet_hall_ticket`, `eamcet_rank`, `pref1`, `pref2`, `pref3`, `pref4`, `pref5`, `photo`, `inter`, `jee_card`, `eamcet_card`, `stud_sign`, `parent_sign`, `txnid`, `payu_status`, `payment_status`, `payment_amount`, `payment_date`, `created_at`, `status`, `allotted_branch`) VALUES
(8, 'JEE8', 'DHEEKSHITHA JEEDIPALLI', 'F', '2006-01-31', 'RAMESH', 'SNIKITHA', 'SC', 'modikunta', '', 'Badrachalm', 'Telangana', '504231', 'India', 'dheekshithajeedipalli@gmail.com', 'dheekshithajeedipalli@gmail.com', '306294847574', '6303436722', 'CBSE', '2025-05', '69df88a7d4b8f_1776257191.pdf', '6839275108', 92.66, 98.76, 902, 542, 'TS0015726748', 899997, 71.23, '7889763214', 77980, 'CSE', 'ECE', 'EEE', 'ETM', 'IT', '69df88a7d4a53_1776257191.jpeg', '69df88a7d4c29_1776257191.pdf', '69df88a7d4ca1_1776257191.pdf', '69df88a7d4d12_1776257191.pdf', '69df88a7d4d7c_1776257191.jpeg', '69df88a7d4ddf_1776257191.jpeg', 'TXN613849', 'success', 'Paid', 1.00, '2026-04-15 18:54:10', '2026-04-15 12:46:31', 'Approved', 'CSE'),
(9, 'JEE9', 'BANOTHU SHIVANI', 'F', '2006-11-16', 'BANOTHU RAJU', 'BANOTHU SUNITHA', 'ST', 'ashok colony road no 1 hanamkonda', 'warangal', 'hanamkonda', 'telengana', '506001', 'India', 'Shivanibanothu9@gmail.com', 'Shivanibanothu9@gmail.com', '306294847574', '6303436722', 'State Board', '2025-05', '69dfa295ca54b_1776263829.pdf', '4556321221', 98.07, 99.89, 955, 550, 'TS0115726744', 90064, 77.08, '9729471110', 8096, 'CSE', 'ECE', 'EEE', 'ETM', 'CSM', '69dfa295ca064_1776263829.jpeg', '69dfa295ca5b4_1776263829.pdf', '69dfa295ca603_1776263829.pdf', '69dfa295ca65d_1776263829.pdf', '69dfa295ca6d0_1776263829.jpeg', '69dfa295ca78a_1776263829.jpeg', 'TXN126933', 'success', 'Paid', 1.00, '2026-04-15 20:24:59', '2026-04-15 14:37:09', 'Approved', 'EEE'),
(10, 'JEE10', 'NAMANI RADHIKA', 'F', '2006-06-06', 'HITESH', 'SNIKITHA', 'OBC', 'mncl', 'mm', 'srp', 'Telangana', '500001', 'India', 'radhika11678@gmail.com', 'radhika11678@gmail.com', '478913219033', '6303436722', 'State Board', '2025-03', '69dfab21094b4_1776266017.pdf', '6819372919', 90.00, 85.33, 900, 400, 'TS0892745772', 122300, 71.01, '89311239183', 90010, 'CSE', 'ECE', 'EEE', 'ETM', 'CSM', '69dfab2109318_1776266017.jpeg', '69dfab210955e_1776266017.pdf', '69dfab21095d3_1776266017.pdf', '69dfab2109639_1776266017.pdf', '69dfab2109696_1776266017.jpeg', '69dfab210972a_1776266017.jpeg', 'TXN257009', 'success', 'Paid', 1.00, '2026-04-15 20:48:31', '2026-04-15 15:13:37', 'Approved', NULL),
(16, 'JEE16', 'DHEEKSHITHA JEEDIPALLI', 'F', '2006-03-09', 'RAMESH', 'PAVITHRA', 'SC', 'modikunta', 'pentlavelly,kollpapur', 'Badrachalm', 'Telangana', '504231', 'India', 'shanvitharaj06@gmail.com', 'shanvitharaj06@gmail.com', '421117079122', '6303436722', 'DHSE', '2025-03', '69e511e29c3b1_1776620002.pdf', '8492057241', 90.90, 90.29, 986, 565, '6742924672', 97987, 90.00, '8911279126', 90868, 'CSE', 'ECE', 'EEE', 'CSM', 'IT', '69e511e29c23b_1776620002.jpeg', '69e511e29c41c_1776620002.pdf', '69e511e29c6f4_1776620002.pdf', '69e511e29c748_1776620002.pdf', '69e511e29c78d_1776620002.jpeg', '69e511e29c7d0_1776620002.jpeg', 'TXN508737', 'Pending', 'Pending', NULL, NULL, '2026-04-19 17:33:22', 'Pending', NULL),
(17, 'JEE17', 'NANDINI VELPULA', 'F', '2006-12-11', 'VELPULA NARSAIAH', 'VELPULA KALAVATHI', 'SC', 'pentalvelly', 'pentlavelly,kollpapur', 'nagarkurnool', 'Telangana', '509105', 'India', 'nandinivelpula5@gmail.com', 'nandinivelpula5@gmail.com', '421117079122', '6303436722', 'TSBIE', '2026-03', '69e73e96923d9_1776762518.pdf', '8735183719', 90.65, 92.45, 891, 500, '7862947639', 99099, 70.23, '7868317468', 909760, 'CSE', 'ECE', 'EEE', 'CSM', 'IT', '69e73e9692269_1776762518.jpeg', '69e73e9692943_1776762518.pdf', '69e73e9692994_1776762518.pdf', '69e73e96929dd_1776762518.pdf', '69e73e9692ab3_1776762518.jpeg', '69e73e9692b21_1776762518.jpeg', 'TXN806588', 'success', 'Paid', 1.00, '2026-04-21 14:39:23', '2026-04-21 09:08:38', 'Approved', 'CSE');

-- --------------------------------------------------------

--
-- Table structure for table `nri`
--

CREATE TABLE `nri` (
  `id` int NOT NULL,
  `reference_no` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gender` enum('F','M','O') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `father` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mother` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `community` enum('SC','ST','OBC','EWS','GENERAL','OC','OBC','BC-A','BC-B','BC-C','BC-D','BC-E') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address1` text COLLATE utf8mb4_general_ci,
  `address2` text COLLATE utf8mb4_general_ci,
  `city` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `state` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `zip` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `country` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `confirm_email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `aadhar` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mobile` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `board` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `passing_year` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `total_marks` int DEFAULT NULL,
  `group_marks` int DEFAULT NULL,
  `intermediate_hall_ticket` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `inter_percentage` decimal(5,2) DEFAULT NULL,
  `inter_group_percentage` decimal(5,2) DEFAULT NULL,
  `pref1` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pref2` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pref3` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pref4` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pref5` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `relationship` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sponsor_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `place_country` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `eamcet_rank` int DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tenth` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `inter` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `passport` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `eamcet_card` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nri_letter` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nri_driving` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `stud_sign` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `parent_sign` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `txnid` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payu_status` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payment_status` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `payment_amount` decimal(10,2) DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Pending','Approved','Rejected') COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `allotted_branch` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nri`
--

INSERT INTO `nri` (`id`, `reference_no`, `name`, `gender`, `dob`, `father`, `mother`, `community`, `address1`, `address2`, `city`, `state`, `zip`, `country`, `email`, `confirm_email`, `aadhar`, `mobile`, `board`, `passing_year`, `total_marks`, `group_marks`, `intermediate_hall_ticket`, `inter_percentage`, `inter_group_percentage`, `pref1`, `pref2`, `pref3`, `pref4`, `pref5`, `relationship`, `sponsor_name`, `place_country`, `eamcet_rank`, `photo`, `tenth`, `inter`, `passport`, `eamcet_card`, `nri_letter`, `nri_driving`, `stud_sign`, `parent_sign`, `txnid`, `payu_status`, `payment_status`, `payment_amount`, `payment_date`, `created_at`, `status`, `allotted_branch`) VALUES
(3, 'NRI_69de97e567fe9', 'SHANVITHA BORLAKUNTA', 'F', '2006-04-29', 'BORLAKUNTA RAYALINGU', 'BORLAKUNTA SYAMALA', 'SC', 'Al hamra Colony', '', 'Hyderabad', 'Telangana', '500001', 'India', 'shanvitharaj06@gmail.com', 'shanvitharaj06@gmail.com', '452796277904', '6303436722', 'TG BIE', '2026-03', 894, 490, '8123854099', 88.64, 90.65, 'CSE', 'ECE', 'EEE', 'ETM', 'CSD', 'Parent', 'Velpula Nandini', 'India', 89943, '69de97e568009_1776195557.jpeg', '69de97e568173_1776195557.pdf', '69de97e5681dc_1776195557.pdf', '69de97e568229_1776195557.pdf', '69de97e568272_1776195557.pdf', '69de97e56831e_1776195557.pdf', '69de97e5683ca_1776195557.pdf', '69de97e56840f_1776195557.jpeg', '69de97e56844e_1776195557.jpeg', 'TXN719761776446233', 'success', 'Paid', 1.00, '2026-04-17 22:47:33', '2026-04-14 19:39:17', 'Approved', 'CSE'),
(4, 'NRI4', 'NANDINI VELPULA', 'F', '2006-12-15', 'HITESH', 'SNIKITHA', 'SC', 'pentalvelly', 'pentlavelly,kollpapur', 'nagarkurnool', 'Telangana', '509105', 'India', 'nandinivelpula5@gmail.com', 'nandinivelpula5@gmail.com', '421117079122', '6303436722', 'TG BIE', '2025-03', 890, 498, '5775334866', 89.77, 85.98, 'CSE', 'ECE', 'EEE', 'CSD', 'IT', 'Parent', 'hitesh', 'India', 90076, '69dfb635e8e69_1776268853.jpeg', '69dfb635e9067_1776268853.pdf', '69dfb635e90ef_1776268853.pdf', '69dfb635e9155_1776268853.pdf', '69dfb635e91ac_1776268853.pdf', '69dfb635e920a_1776268853.pdf', '69dfb635e9263_1776268853.pdf', '69dfb635e92bb_1776268853.jpeg', '69dfb635e9310_1776268853.jpeg', 'TXN329554', 'success', 'Paid', 1.00, '2026-04-15 21:34:28', '2026-04-15 16:00:53', 'Approved', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `oci`
--

CREATE TABLE `oci` (
  `id` int NOT NULL,
  `reference_no` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gender` enum('F','M','O') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `father` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mother` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `community` enum('SC','ST','OBC','EWS','GENERAL','OC','OBC','BC-A','BC-B','BC-C','BC-D','BC-E') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mobile` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `aadhar` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `confirm_email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `address1` text COLLATE utf8mb4_general_ci,
  `address2` text COLLATE utf8mb4_general_ci,
  `city` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `state` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `zip` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `country` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `board` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `passing_year` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `total_marks` int DEFAULT NULL,
  `group_marks` int DEFAULT NULL,
  `intermediate_hall_ticket` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `inter_percentage` decimal(5,2) DEFAULT NULL,
  `inter_group_percentage` decimal(5,2) DEFAULT NULL,
  `pref1` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pref2` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pref3` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pref4` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pref5` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `place_country` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ssc` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `inter` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `passport` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address_proof` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `stud_sign` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `parent_sign` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `txnid` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payu_status` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payment_status` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `payment_amount` decimal(10,2) DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Pending','Approved','Rejected') COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `allotted_branch` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `oci`
--

INSERT INTO `oci` (`id`, `reference_no`, `name`, `gender`, `dob`, `father`, `mother`, `community`, `mobile`, `aadhar`, `email`, `confirm_email`, `address1`, `address2`, `city`, `state`, `zip`, `country`, `board`, `passing_year`, `total_marks`, `group_marks`, `intermediate_hall_ticket`, `inter_percentage`, `inter_group_percentage`, `pref1`, `pref2`, `pref3`, `pref4`, `pref5`, `place_country`, `photo`, `ssc`, `inter`, `passport`, `address_proof`, `stud_sign`, `parent_sign`, `txnid`, `payu_status`, `payment_status`, `payment_amount`, `payment_date`, `created_at`, `status`, `allotted_branch`) VALUES
(1, 'OCI1', 'NANDINI VELPULA', 'F', '2006-03-02', 'ramesh', 'snikitha', 'SC', '6303436722', NULL, 'nandinivelpula5@gmail.com', 'nandinivelpula5@gmail.com', 'pentalvelly', 'pentlavelly,kollpapur', 'nagarkurnool', 'Telangana', '509105', 'India', 'TG BIE', '2025-03', 790, 348, '8492057241', 72.88, 70.22, 'ECE', 'EEE', 'CSE', 'CSM', 'CSD', 'India', '69dfc3f6e499c_1776272374.jpeg', '69dfc3f6e4ab3_1776272374.pdf', '69dfc3f6e4b27_1776272374.pdf', '69dfc3f6e4b8d_1776272374.pdf', NULL, '69dfc3f6e4c47_1776272374.jpeg', '69dfc3f6e4cd9_1776272374.jpeg', 'TXN806674', 'success', 'Paid', 1.00, '2026-04-15 22:32:35', '2026-04-15 16:59:34', 'Approved', 'IT'),
(2, 'OCI2', 'PAVANI', 'F', '2006-04-06', 'BANOTHU RAJU', 'BANOTHU SUNITHA', 'ST', '6303436722', '421117079122', 'banothupavani44@gmail.com', 'banothupavani44@gmail.com', 'Al hamra Colony', '', 'Hyderabad', 'Telangana', '500001', 'India', 'TSBIE', '2025-03', 880, 391, '7876324643', 82.12, 80.54, 'EEE', 'ECE', 'CSE', 'ETM', 'CSD', 'India', '69e3467d67b88_1776502397.jpeg', '69e3467d67cb7_1776502397.pdf', '69e3467d67d33_1776502397.pdf', '69e3467d67d90_1776502397.pdf', '69e3467d67dda_1776502397.pdf', '69e3467d67e23_1776502397.jpeg', '69e3467d67e69_1776502397.jpeg', 'TXN566579', 'success', 'Paid', 1.00, '2026-04-18 14:23:55', '2026-04-18 08:53:17', 'Pending', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jee`
--
ALTER TABLE `jee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reference_no` (`reference_no`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `nri`
--
ALTER TABLE `nri`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reference_no` (`reference_no`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `oci`
--
ALTER TABLE `oci`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reference_no` (`reference_no`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jee`
--
ALTER TABLE `jee`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `nri`
--
ALTER TABLE `nri`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `oci`
--
ALTER TABLE `oci`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
