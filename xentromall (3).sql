-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 02, 2025 at 06:08 AM
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
-- Database: `xentromall`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'admin@gmail.com', 'admin@gmail.com', '$2y$10$e0NRXQ6q6Xq6Q6q6Q6q6QO6q6Q6q6Q6q6Q6q6Q6q6Q6q6Q6q6Q6q', '2025-04-24 13:46:11'),
(3, 'admin123', 'adminadmin@gmail.com', '$2y$10$9hLvWDkQ5LcEUuU2figSdukSHEvUk7pDAb7xBustGsmEO8k8F7k9m', '2025-05-05 03:27:22');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date` date NOT NULL,
  `category` enum('upcoming_events','renewal','general') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `description`, `date`, `category`, `created_at`) VALUES
(1, 'Good Morning', 'pabatid ko  lang sa lahat ay magbayad na ', '2025-05-30', 'renewal', '2025-05-29 16:24:49'),
(2, 'Good afternoon', 'today meron tayung event na gaganapin so inaasahan lahat ay mag paparticipate', '2025-06-04', 'upcoming_events', '2025-05-29 17:47:22'),
(3, 'Remminders to payment', 'kinakailangan na nyu na magbayad na ', '2025-06-07', 'renewal', '2025-05-30 03:31:55'),
(4, ' Reminders', 'you need to send your payment asap', '2025-06-01', 'renewal', '2025-05-31 06:29:55');

-- --------------------------------------------------------

--
-- Table structure for table `application_submissions`
--

CREATE TABLE `application_submissions` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `letter_of_intent` varchar(255) NOT NULL,
  `business_profile` varchar(255) NOT NULL,
  `business_registration` varchar(255) NOT NULL,
  `valid_id` varchar(255) NOT NULL,
  `bir_registration` varchar(255) NOT NULL,
  `extended_bir_registration` varchar(255) DEFAULT NULL,
  `financial_statement` varchar(255) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `verification_status` enum('approved','rejected') DEFAULT NULL,
  `admin_feedback` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_submissions`
--

INSERT INTO `application_submissions` (`id`, `user_id`, `letter_of_intent`, `business_profile`, `business_registration`, `valid_id`, `bir_registration`, `extended_bir_registration`, `financial_statement`, `submitted_at`, `status`, `verification_status`, `admin_feedback`) VALUES
(1, 4, 'uploads/applications/4/681218a30f4f7_registration-form-and-login.jpg', 'uploads/applications/4/681218a30f680_registration-form-and-login.jpg', 'uploads/applications/4/681218a30f7b6_registration-form-and-login.jpg', 'uploads/applications/4/681218a30f9e4_registration-form-and-login.jpg', 'uploads/applications/4/681218a30fb45_registration-form-and-login.jpg', NULL, 'uploads/applications/4/681218a30fc93_Screenshot 2025-04-30 193925.png', '2025-04-30 12:33:39', 'pending', NULL, NULL),
(6, 4, '', '', '', '', '', 'uploads/applications/4/681447587118f_681339dd5898a_landing page 1 (1).avif', '', '2025-05-02 04:17:28', 'pending', NULL, NULL),
(7, 2, '', '', '', '', '', 'uploads/applications/2/6835135dd8c26_act.dia.1.png', '', '2025-05-27 01:20:29', 'pending', NULL, NULL),
(8, 18, '', '', '', '', '', 'uploads/applications/18/683a9d2169897_bir.jpg', '', '2025-05-31 06:09:37', 'pending', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_requests`
--

CREATE TABLE `maintenance_requests` (
  `id` int(11) NOT NULL,
  `tenant_name` varchar(100) NOT NULL,
  `unit_number` varchar(50) NOT NULL,
  `issue_description` text NOT NULL,
  `category` varchar(50) NOT NULL,
  `urgency` varchar(20) NOT NULL,
  `photos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`photos`)),
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenance_requests`
--

INSERT INTO `maintenance_requests` (`id`, `tenant_name`, `unit_number`, `issue_description`, `category`, `urgency`, `photos`, `submitted_at`, `status`) VALUES
(1, 'sarah@gmail.com', 'Room101', 'electricity', 'Electrical', 'Medium', '[\"uploads\\/maintenance_requests\\/sarah_gmail_com\\/68122226b609e_tenant dashboard.png\"]', '2025-04-30 13:14:14', 'pending'),
(2, 'abby123', '1102', 'dgwgw', 'Elevator', 'High', '[\"uploads\\/maintenance_requests\\/abby123\\/68133aa79a6dc_landing page 1.avif\"]', '2025-05-01 09:11:03', 'pending'),
(3, 'abby123', 'room102', 'kjadhkakas', 'Landscaping', 'Low', '[\"uploads\\/maintenance_requests\\/abby123\\/68143cbf90239_681339dd5898a_landing page 1.avif\"]', '2025-05-02 03:32:15', 'pending'),
(4, 'admin123', '', '', '', '', NULL, '2025-05-30 12:16:55', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `message` varchar(255) NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `is_read`, `created_at`) VALUES
(1, 13, 'Your payment (ID: 2) has been approved.', 1, '2025-05-29 16:56:57'),
(2, 13, 'Your payment has been declined.', 1, '2025-05-29 17:00:16'),
(3, 4, 'Your payment has been completed.', 0, '2025-05-29 17:02:38'),
(4, 18, 'Your payment has been completed.', 0, '2025-05-31 06:25:55');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `token`, `created_at`) VALUES
(1, 1, '9dce3b47f7085dde269dba3a364b9c8a', '2025-05-05 06:38:46'),
(2, 1, '769bef2d79c9ad9f27da69b19c28f800', '2025-05-05 06:38:57'),
(3, 1, 'e79296b451d71c2f97a85862921be1bb', '2025-05-05 06:51:30'),
(4, 1, '301de4359ceca3b237be959310c6ff73', '2025-05-05 06:52:17'),
(5, 1, 'ecc79cde872b057b75be9ac6e11c1c43', '2025-05-05 06:55:43'),
(6, 1, '1c22e31b335c04a51940f586491adf85', '2025-05-05 06:56:15'),
(7, 1, 'd9a3770f4a9fa64fd8155a84ac8a8fe3', '2025-05-05 06:57:41'),
(8, 1, '18290e92425d4cfb8e25a6de6acd4ce3', '2025-05-05 06:58:15'),
(9, 1, 'a9ba26a5b2377150a4ca999273209a56', '2025-05-05 06:58:55'),
(10, 1, '97a35f00df4a5adf0e460cf058692e55', '2025-05-05 07:00:42'),
(11, 1, 'b54d20a1b8191073b2f576c3656957a6', '2025-05-08 12:34:57'),
(12, 1, '1d2a952f939a56fffc34eec60aedc262', '2025-05-08 12:37:12'),
(13, 1, 'e3474a920fc29332579e7d53192d36d4', '2025-05-08 12:37:28'),
(14, 7, '2028247d19ab61ed0621b5074096b023', '2025-05-08 14:30:53'),
(15, 1, '5c0addd4f9c4b08b7de381f85003e7c6', '2025-05-26 14:04:25'),
(16, 1, 'e71a7099534a3514939d19005a85c4cf', '2025-05-29 09:55:11');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `payment_image` varchar(255) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `payment_image`, `payment_date`, `status`) VALUES
(1, 4, 'uploads/payments/4/6812cd438de68_registration-form-and-login.jpg', '2025-05-01 01:24:19', 'approved'),
(2, 13, 'uploads/payments/13/6838784ecf131_globe-5g-1.jpg', '2025-05-29 15:07:58', 'declined'),
(3, 16, 'uploads/payments/16/683a7dc990102_bir.jpg', '2025-05-31 03:55:53', 'pending'),
(4, 18, 'uploads/payments/18/683aa0c8275c5_Brown and Black Mminimal Business Proposal.png', '2025-05-31 06:25:12', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `renewal_requests`
--

CREATE TABLE `renewal_requests` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `renewal_date` date NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `renewal_requests`
--

INSERT INTO `renewal_requests` (`id`, `tenant_id`, `renewal_date`, `submitted_at`, `status`) VALUES
(2, 0, '2025-05-27', '2025-05-27 02:07:48', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'tenant',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `lease_start_date` date DEFAULT NULL,
  `lease_expiration_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenant_details`
--

CREATE TABLE `tenant_details` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `tradename` varchar(255) NOT NULL,
  `store_premises` varchar(255) NOT NULL,
  `store_location` varchar(255) NOT NULL,
  `ownership` varchar(50) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `business_address` text NOT NULL,
  `tin` varchar(50) DEFAULT NULL,
  `office_tel` varchar(50) DEFAULT NULL,
  `tenant_representative` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `contact_tel` varchar(50) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `prepared_by` varchar(255) DEFAULT NULL,
  `business_type` varchar(50) DEFAULT NULL,
  `documents` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT NULL,
  `admin_feedback` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenant_details`
--

INSERT INTO `tenant_details` (`id`, `user_id`, `tradename`, `store_premises`, `store_location`, `ownership`, `company_name`, `business_address`, `tin`, `office_tel`, `tenant_representative`, `contact_person`, `position`, `contact_tel`, `mobile`, `email`, `prepared_by`, `business_type`, `documents`, `created_at`, `status`, `admin_feedback`) VALUES
(1, 1, 'nuciti', 'haha', 'masagna', 'Corporation', 'jag', 'mamamamaa', '2333', '09926265229', 'jairo', '0991988767', 'manager', '09982324787', '09926265229', 'pogi@gmail.com', 'jk', 'corporation', 'uploads/680a3e987670f-PLACES AND INFO\'S.pdf', '2025-04-24 13:37:28', 'declined', 'your request has been decline'),
(2, 3, 'nuciti', 'haha', 'masagna', 'Corporation', 'jag', 'mamamamaa', '2333', '09926265229', 'jairo', '0991988767', 'manager', '09982324787', '09926265229', 'sarahuto@gmmail.com', 'jairo', 'corporation', 'uploads/680a4248331ac-PLACES AND INFO\'S.pdf', '2025-04-24 13:53:12', 'approved', 'your request has been approved'),
(9, 13, 'ABC Retail Store', 'Retail Sales of Clothing and Accessories', 'Main Branch: 123 Main St, Cityville', 'Corporation', 'ABC Retail, Inc.', '123 Main St, Cityville, State, ZIP Code', '123-456-789', '(123) 456-7890', 'John Doe', 'Jane Smith', 'Store Manager', '(123) 456-7891', '09926265229', 'jairopogirobiso@gmail.com', 'john', 'corporation', 'uploads/683748fabd1a9-Document.pdf', '2025-05-28 17:33:46', 'approved', 'ewan sayo'),
(10, 14, 'ABC Retail Store', 'adds', 'nauajn', 'Sole Proprietor', 'ABC Retail, Inc.', '123 Main St, Cityville, State, ZIP Code', '2333', '09926265229', 'john', 'Jane Smith', 'Store Manager', '09882786722', '12345678908', 'admin@gmail.com', 'pogi', 'franchisee', 'uploads/68389c5859950-Document.pdf', '2025-05-29 17:41:44', 'approved', 'your request has been approved'),
(11, 16, 'ABC Retail Store', 'adds', 'nauajn', 'Corporation', 'ABC Retail, Inc.', '123 Main St, Cityville, State, ZIP Code', '2333', '09926265229', 'john', 'Jane Smith', 'Store Manager', '09882786722', '', 'bcddd@gmail.com', 'pogi', 'corporation', 'uploads/683a712db16fa-Document.pdf', '2025-05-31 03:02:05', NULL, NULL),
(12, 17, 'ABC Gadget Hub', 'adds retail', 'naujan masagana', 'Sole Proprietor', 'affionado', 'naujan masagana purok 1', '123-456-789-000', '(043) 123-4567', 'sarah', 'pablo', 'manager', '(043) 123-4567', '0910540784', 'adds@gmail.com', 'james smith', 'sole', 'uploads/683a9c27c1623-Document.pdf', '2025-05-31 06:05:27', NULL, NULL),
(13, 18, 'ABC Gadget Hub', 'adds retail', 'naujan masagana', 'Sole Proprietor', 'affionado', 'naujan masagana purok 1', '123-456-789-000', '(043) 123-4567', 'sarah', 'pablo', 'manager', '(043) 123-4567', '0910540784', 'adds@gmail.com', 'james smith', 'sole', 'uploads/683a9c3edfb5c-Document.pdf', '2025-05-31 06:05:50', 'approved', 'your request has been approved');

-- --------------------------------------------------------

--
-- Table structure for table `tenant_lease_dates`
--

CREATE TABLE `tenant_lease_dates` (
  `tenant_id` int(11) NOT NULL,
  `lease_start_date` date NOT NULL,
  `lease_expiration_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'tenant',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'jairopogirobiso@gmail.com', 'jairopogirobiso@gmail.com', '$2y$10$w8k2iHmPtS0yKbhMXb2fhOmqwZLNwPjljqGiMfsFU9NVeXNFEkjMy', 'tenant', '2025-04-24 13:37:28'),
(2, 'admin@gmail.com', 'admin@gmail.com', '$2y$10$e0NRXQ6q6Xq6Q6q6Q6q6QO6q6Q6q6Q6q6Q6q6Q6q6Q6q6Q6q6Q6q', 'admin', '2025-04-24 13:44:06'),
(3, 'pogirobiso@gmail.com', 'pogirobiso@gmail.com', '$2y$10$86JVnKnabnf6i5aAdfGvDuvLh3WDMGsBs.bknqzYQH63F2PSmYRSS', 'tenant', '2025-04-24 13:53:12'),
(4, 'abby123', 'sarah@gmail.com', '$2y$10$9hLvWDkQ5LcEUuU2figSdukSHEvUk7pDAb7xBustGsmEO8k8F7k9m', 'tenant', '2025-04-30 11:21:28'),
(6, 'admin123', 'jairopogi@gmail.com', '$2y$10$.RniuuHAXycN45gZVBKWUOGc1iGxKSymhWEoAvsmOTAV5RUX5fYbS', 'admin', '2025-05-05 04:44:28'),
(7, 'jhaneloumagbuhosramos@gmail.com', 'jhaneloumagbuhosramos@gmail.com', '$2y$10$DYX8pg/w0CULjooNguZea.Fht.9CMlB9jGe9knLUq6JDDPgd0E.LO', 'tenant', '2025-05-08 14:28:48'),
(9, 'ppc@gmail.com', 'ppc@gmail.com', '$2y$10$FwjCdk38FDpS.sWREeOqveOmJNvJzOHX1QWbnADsaaoe.ij.xsi3C', 'tenant', '2025-05-08 14:30:00'),
(10, 'jaigalac@gmail.com', 'jaigalac@gmail.com', '$2y$10$5Hazc8VkofjQHeibQhsUz.myyqatWH9gu84G/5byp.IvwxsYfeBHu', 'tenant', '2025-05-15 12:58:56'),
(11, 'abby@gmail.com', 'abby@gmail.com', '$2y$10$BmHz1/PjtWgfcuXj/VKMROHV/.FdQRhRjt7KWQOxAHAklLkfRgrtq', 'tenant', '2025-05-15 13:20:36'),
(13, 'robiso@gmail.com', 'robiso@gmail.com', '$2y$10$kw3tk5.Tl4cQCRijUGVMtuBwXGQQbc1HnYD/k3vKk5/99qnUM4HKe', 'tenant', '2025-05-28 17:33:46'),
(14, 'pablo@gmail.com', 'pablo@gmail.com', '$2y$10$ln49Cg.SfR7aaxWhJtyluOb1igBAihrEmnK3gYPJCr0gzKaBuAjiy', 'tenant', '2025-05-29 17:41:44'),
(15, 'superadmin', 'superadmin@gmail.com', '$2y$10$xjRLQ2ZHVisNnuBvCon3pefmu1wW0Wx8uRObiphVSpZMpGot8Sz8y', 'admin', '2025-05-30 03:39:25'),
(16, 'bcd@gmail.com', 'bcd@gmail.com', '$2y$10$gI4tdHl4X5wWOMTOtFGCZeP1lzbWyEy.3t7i56UN7e0wdNGrFwUtK', 'tenant', '2025-05-31 03:02:05'),
(17, 'bcc@gmail.com', 'bcc@gmail.com', '$2y$10$EsrhxhO./aYEBeJ/QkB0B.4IIa7vxWDWeHlXodLjvxGmrbU2a4sJW', 'tenant', '2025-05-31 06:05:27'),
(18, 'acc@gmail.com', 'acc@gmail.com', '$2y$10$Ge8ApCPMjya9VBf7lBq0h.bJVdJqo.gF.UQ5h8Ue3Cq9pZbPht/wi', 'tenant', '2025-05-31 06:05:50');

-- --------------------------------------------------------

--
-- Table structure for table `work_permits`
--

CREATE TABLE `work_permits` (
  `permit_no` int(11) NOT NULL,
  `date_filed` date NOT NULL,
  `tenant_name` varchar(255) NOT NULL,
  `scope_of_work` text NOT NULL,
  `security_posting` tinyint(1) DEFAULT 0,
  `rate_security` decimal(10,2) DEFAULT NULL,
  `charge_security` enum('With Charge','No Charge') DEFAULT NULL,
  `janitorial_deployment` tinyint(1) DEFAULT 0,
  `rate_janitorial` decimal(10,2) DEFAULT NULL,
  `charge_janitorial` enum('With Charge','No Charge') DEFAULT NULL,
  `maintenance` tinyint(1) DEFAULT 0,
  `rate_maintenance` decimal(10,2) DEFAULT NULL,
  `charge_maintenance` enum('With Charge','No Charge') DEFAULT NULL,
  `personnel` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `work_permits`
--

INSERT INTO `work_permits` (`permit_no`, `date_filed`, `tenant_name`, `scope_of_work`, `security_posting`, `rate_security`, `charge_security`, `janitorial_deployment`, `rate_janitorial`, `charge_janitorial`, `maintenance`, `rate_maintenance`, `charge_maintenance`, `personnel`, `created_at`) VALUES
(7888, '2025-06-02', 'sarah', 'may sira yung airon ko', 1, 45.00, 'No Charge', 0, 0.00, NULL, 0, 0.00, NULL, 'smith', '2025-05-31 06:33:53'),
(8989, '2025-05-30', ',m', 'h,;LALKmx.,AMX.', 1, 5.00, 'With Charge', 0, 0.00, NULL, 0, 0.00, NULL, 'GGG', '2025-05-29 15:38:32'),
(343535, '2025-05-13', 'sarahuto', 'jdjhfkhfsn,dsnflk', 0, 0.00, NULL, 0, 0.00, NULL, 1, 5.00, 'With Charge', 'jai', '2025-05-13 14:00:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `application_submissions`
--
ALTER TABLE `application_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `renewal_requests`
--
ALTER TABLE `renewal_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_id` (`tenant_id`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tenant_details`
--
ALTER TABLE `tenant_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tenant_lease_dates`
--
ALTER TABLE `tenant_lease_dates`
  ADD PRIMARY KEY (`tenant_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `work_permits`
--
ALTER TABLE `work_permits`
  ADD PRIMARY KEY (`permit_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `application_submissions`
--
ALTER TABLE `application_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `renewal_requests`
--
ALTER TABLE `renewal_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenant_details`
--
ALTER TABLE `tenant_details`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `work_permits`
--
ALTER TABLE `work_permits`
  MODIFY `permit_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=343536;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `application_submissions`
--
ALTER TABLE `application_submissions`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tenant_details`
--
ALTER TABLE `tenant_details`
  ADD CONSTRAINT `tenant_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tenant_lease_dates`
--
ALTER TABLE `tenant_lease_dates`
  ADD CONSTRAINT `tenant_lease_dates_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
