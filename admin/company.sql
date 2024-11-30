-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2024 at 11:20 AM
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
-- Database: `company`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'level 2',
  `email` varchar(200) NOT NULL,
  `password` varchar(500) NOT NULL,
  `Create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `active` tinyint(1) NOT NULL,
  `activation_code` varchar(255) DEFAULT NULL,
  `activated_at` datetime DEFAULT NULL,
  `activation_expiry` datetime DEFAULT NULL,
  `update_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `role`, `email`, `password`, `Create_at`, `active`, `activation_code`, `activated_at`, `activation_expiry`, `update_at`) VALUES
(26, 'test', 'level-2', 'test123@gmail.com', '123123', '2024-11-22 17:00:00', 0, '', NULL, '2024-11-23 16:16:26', '2024-11-26 06:20:46'),
(27, 'test', 'level-2', 'test1234@gmail.com', '$2y$10$Dsltr.wTakHJmCB8OyAOQ.QLe0UWh0LABVRum6eRDoFkxGj7gCqJq', '2024-11-23 09:29:55', 0, NULL, NULL, NULL, '2024-11-23 16:29:55'),
(33, 'tuan', 'level-0', 'viettuan19734@gmail.com', '$2y$10$VcxbSWZP98XjsDLPb9.wme8aD.0QAXTuYMs.owUTxne9Z/GyXRdaa', '2024-11-25 21:39:41', 1, NULL, NULL, NULL, '2024-11-26 04:40:06'),
(40, 'tuan', 'level 2', 'viettuan25555@gmail.com', '$2y$10$ExFZi1fnPptP9dpRH6FO8OKp2EkxST1gj95eFZ3d/RqAD.uMsg8x.', '2024-11-25 23:22:08', 1, NULL, '2024-11-26 06:22:33', NULL, '2024-11-26 06:24:38');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `comment` varchar(200) NOT NULL,
  `blogid` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `names` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `houses`
--

CREATE TABLE `houses` (
  `houseID` int(11) NOT NULL,
  `house_name` text NOT NULL,
  `number_of_rooms` int(10) NOT NULL DEFAULT 1,
  `rent_amount` double NOT NULL,
  `location` text NOT NULL,
  `num_of_bedrooms` int(10) NOT NULL,
  `house_status` varchar(50) NOT NULL DEFAULT 'Vacant'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `houses`
--

INSERT INTO `houses` (`houseID`, `house_name`, `number_of_rooms`, `rent_amount`, `location`, `num_of_bedrooms`, `house_status`) VALUES
(11, 'Nhà đỏ', 2, 1000000, 'Hanoi', 2, 'Vacant'),
(12, 'Nhà xanh', 0, 700000, 'HCM City', 5, 'Occupied'),
(13, 'NHÀ TRẮNG', 12, 300000000, 'Amurica', 2, 'Vacant');

-- --------------------------------------------------------

--
-- Table structure for table `house_pics`
--

CREATE TABLE `house_pics` (
  `pic_id` int(11) NOT NULL,
  `pic_name` text NOT NULL,
  `house_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `invoiceNumber` varchar(50) NOT NULL,
  `tenantID` int(11) NOT NULL,
  `dateOfInvoice` text NOT NULL,
  `dateDue` text NOT NULL,
  `amountDue` int(11) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'unpaid',
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`invoiceNumber`, `tenantID`, `dateOfInvoice`, `dateDue`, `amountDue`, `status`, `comment`) VALUES
('INV20231019132349', 18, '2024-11-1', '2024-12-1', 4500000, 'paid', 'Please pay rent for this month'),
('INV20231019133401', 19, '2024-11-1', '2024-12-1', 700000, 'unpaid', 'This is the rent invoice for this month'),
('INV20231019134119', 20, '2024-11-1', '2024-12-1', 0, 'paid', 'This is the rent invoice for this month');

-- --------------------------------------------------------

--
-- Stand-in structure for view `invoicesview`
-- (See below for the actual view)
--
CREATE TABLE `invoicesview` (
`invoiceNumber` varchar(50)
,`tenant_name` text
,`tenantID` int(11)
,`phone_number` varchar(13)
,`amountDue` int(11)
,`dateOfInvoice` text
,`dateDue` text
,`status` varchar(50)
,`comment` text
);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `location_name` text NOT NULL,
  `geo_id` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `location_name`, `geo_id`) VALUES
(1, 'Hanoi', ''),
(2, 'HCM City', ''),
(3, 'Amurica', '');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `paymentID` int(11) NOT NULL,
  `tenantID` int(11) NOT NULL,
  `invoiceNumber` varchar(50) NOT NULL,
  `expectedAmount` int(11) NOT NULL,
  `amountPaid` int(11) NOT NULL,
  `balance` int(11) NOT NULL,
  `mpesaCode` varchar(30) NOT NULL DEFAULT 'None',
  `dateofPayment` text NOT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`paymentID`, `tenantID`, `invoiceNumber`, `expectedAmount`, `amountPaid`, `balance`, `mpesaCode`, `dateofPayment`, `comment`) VALUES
(13, 18, 'INV20231019132349', 1000000, 700000, 3000, 'gh3423h', '2024-10-19', 'partial payment for the invoice'),
(14, 18, 'INV20231019132349', 3000000, 750000, -4500, 'ty28393io', '2024-10-19', 'another payment'),
(15, 19, 'INV20231019133401', 10000000, 3000000, 7000, 'sdsd434', '2024-10-19', 'partial payment'),
(16, 20, 'INV20231019134119', 700000, 70000, 0, 'er23456', '2024-10-19', 'rent settlement');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `author` varchar(200) NOT NULL,
  `title` varchar(400) NOT NULL,
  `content` text NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `author`, `title`, `content`, `date`) VALUES
(7, 'Duong Anh', 'Something title', 'Something something\r\n', '2024-11-01 15:28:31');

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `subscribers`
--

INSERT INTO `subscribers` (`id`, `email`, `date`) VALUES
(3, 'abc@gmail.com', '2024-11-01 18:21:30'),
(4, 'def@gmail.com', '2024-11-01 18:21:30'),
(6, 'ghk@gmail.com', '2024-11-01 01:49:21');

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `tenantID` int(11) NOT NULL,
  `houseNumber` int(10) NOT NULL,
  `tenant_name` text NOT NULL,
  `email` text NOT NULL,
  `phone_number` varchar(13) NOT NULL,
  `agreement_file` text DEFAULT NULL,
  `dateAdmitted` text DEFAULT NULL,
  `account` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL DEFAULT '123456'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`tenantID`, `houseNumber`, `tenant_name`, `email`, `phone_number`, `agreement_file`, `dateAdmitted`, `account`, `password`) VALUES
(18, 11, 'This', 'abc@gmail.com', '123456789', NULL, '2024-11-08', '600000', '123456'),
(19, 12, 'is', 'def@gmail.com', '987654321', NULL, '2024-11-08', '-700000', '694321'),
(22, 11, 'ng dung', 'test123@gmail.com', '64200360933', NULL, '2024-11-21 06:12:51', '', '123123');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `actor` text DEFAULT NULL,
  `time` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `seen` varchar(10) NOT NULL DEFAULT 'NO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `actor`, `time`, `description`, `seen`) VALUES
(21, 'Admin (example)', '2024-11-01 : 13:18:23', 'something something', 'YES');

-- --------------------------------------------------------

--
-- Structure for view `invoicesview`
--
DROP TABLE IF EXISTS `invoicesview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `invoicesview`  AS SELECT `invoices`.`invoiceNumber` AS `invoiceNumber`, `tenants`.`tenant_name` AS `tenant_name`, `invoices`.`tenantID` AS `tenantID`, `tenants`.`phone_number` AS `phone_number`, `invoices`.`amountDue` AS `amountDue`, `invoices`.`dateOfInvoice` AS `dateOfInvoice`, `invoices`.`dateDue` AS `dateDue`, `invoices`.`status` AS `status`, `invoices`.`comment` AS `comment` FROM (`invoices` left join `tenants` on(`invoices`.`tenantID` = `tenants`.`tenantID`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogid` (`blogid`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `houses`
--
ALTER TABLE `houses`
  ADD PRIMARY KEY (`houseID`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`invoiceNumber`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`paymentID`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`tenantID`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `tenantID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
