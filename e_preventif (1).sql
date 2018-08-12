-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 13, 2018 at 05:39 AM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 7.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `e_preventif`
--

-- --------------------------------------------------------

--
-- Table structure for table `ep_master_area`
--

CREATE TABLE `ep_master_area` (
  `id` int(10) UNSIGNED NOT NULL,
  `regional_id` int(11) NOT NULL,
  `area_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ep_master_area`
--

INSERT INTO `ep_master_area` (`id`, `regional_id`, `area_name`, `created_at`, `updated_at`) VALUES
(1, 3, 'Jimbaran', '2018-07-16 09:52:57', '2018-07-16 09:53:15'),
(2, 2, 'Gatot Subroto Barat', '2018-07-26 21:30:14', '2018-07-26 21:30:14');

-- --------------------------------------------------------

--
-- Table structure for table `ep_master_regional`
--

CREATE TABLE `ep_master_regional` (
  `id` int(10) UNSIGNED NOT NULL,
  `regional_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ep_master_regional`
--

INSERT INTO `ep_master_regional` (`id`, `regional_name`, `created_at`, `updated_at`) VALUES
(2, 'Denpasar', '2018-07-14 11:22:39', '2018-07-14 11:22:39'),
(3, 'Badung', '2018-07-14 11:22:48', '2018-07-14 11:22:48');

-- --------------------------------------------------------

--
-- Table structure for table `ep_master_site`
--

CREATE TABLE `ep_master_site` (
  `id` int(10) UNSIGNED NOT NULL,
  `site_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `site_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `regional_id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `longitude` text COLLATE utf8mb4_unicode_ci,
  `latitude` text COLLATE utf8mb4_unicode_ci,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ep_master_site`
--

INSERT INTO `ep_master_site` (`id`, `site_name`, `site_id`, `regional_id`, `area_id`, `longitude`, `latitude`, `address`, `created_at`, `updated_at`) VALUES
(11, 'djfakdjsf', '48278', 3, 1, '112.75194738576658', '-7.332224235584186', 'Jl. Rungkut Industri IV No.1, Kutisari, Tenggilis Mejoyo, Kota SBY, Jawa Timur 60291, Indonesia', '2018-07-27 08:43:54', '2018-07-27 08:43:54'),
(12, 'dhjsaf', '778', 2, 2, '112.75731180379637', '-7.333245780440407', 'Jl. Rungkut Industri IV No.5, Kota SBY, Jawa Timur, Indonesia', '2018-07-27 16:23:12', '2018-07-27 16:35:41');

-- --------------------------------------------------------

--
-- Table structure for table `ep_roles`
--

CREATE TABLE `ep_roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ep_roles`
--

INSERT INTO `ep_roles` (`id`, `role_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', NULL, NULL),
(3, 'Engineer', 'user level engineer', '2018-07-13 22:07:58', '2018-07-14 10:54:58');

-- --------------------------------------------------------

--
-- Table structure for table `ep_task`
--

CREATE TABLE `ep_task` (
  `id` int(10) UNSIGNED NOT NULL,
  `site_id` int(11) NOT NULL,
  `regional_id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `engineer_id` int(11) NOT NULL,
  `date_task` date NOT NULL,
  `is_finish` tinyint(1) NOT NULL DEFAULT '0',
  `date_finish` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ep_task`
--

INSERT INTO `ep_task` (`id`, `site_id`, `regional_id`, `area_id`, `engineer_id`, `date_task`, `is_finish`, `date_finish`, `created_at`, `updated_at`) VALUES
(1, 12, 2, 2, 3, '2018-08-31', 0, NULL, '2018-08-12 15:14:01', '2018-08-12 15:14:01');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2018_07_10_051605_create_table_role', 1),
(4, '2018_07_10_052051_create_table_master_area', 1),
(5, '2018_07_10_052252_create_table_master_site', 1),
(6, '2018_07_10_053329_create_table_master_regional', 1),
(7, '2018_08_05_065325_create_table_task', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int(11) NOT NULL,
  `position` enum('Admin','Supervisor','Engineer') COLLATE utf8mb4_unicode_ci NOT NULL,
  `regional_id` int(11) DEFAULT NULL,
  `area_id` int(11) NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role_id`, `position`, `regional_id`, `area_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$rYUTXCD5Doh9P2VdhPlO/OuX7uJEc0Zz8HQdmvQIOsZuFCYYIhBSW', 1, 'Admin', 2, 0, 'AGuBgTuj27kKwGKTKRVNRqghK5MQYE8Cde8qc02Cr8veQDwfqOy4yxrI9w33', '2018-06-30 17:00:00', '2018-06-30 17:00:00'),
(3, 'Febri', 'febri@gmail.com', '$2y$10$riTLaAUa2lM4epQTAzbIeOlha/uOtUePPnDlVKih23dn3bdxvfbCu', 1, 'Engineer', 2, 2, NULL, '2018-07-16 09:18:52', '2018-07-16 09:18:52'),
(4, 'Rino', 'rino@gmail.com', '$2y$10$V.C6VxDDl7epfI7cgaitOeXBI8ravAmScbdhD6dO067UphzGNDcMy', 3, 'Engineer', 3, 1, NULL, '2018-07-16 09:20:45', '2018-07-16 09:20:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ep_master_area`
--
ALTER TABLE `ep_master_area`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ep_master_regional`
--
ALTER TABLE `ep_master_regional`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ep_master_site`
--
ALTER TABLE `ep_master_site`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ep_roles`
--
ALTER TABLE `ep_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ep_task`
--
ALTER TABLE `ep_task`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ep_master_area`
--
ALTER TABLE `ep_master_area`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ep_master_regional`
--
ALTER TABLE `ep_master_regional`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ep_master_site`
--
ALTER TABLE `ep_master_site`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `ep_roles`
--
ALTER TABLE `ep_roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ep_task`
--
ALTER TABLE `ep_task`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
