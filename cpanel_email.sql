-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 30, 2020 at 03:01 PM
-- Server version: 8.0.20-0ubuntu0.19.10.1
-- PHP Version: 7.2.30-1+ubuntu19.10.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cpanel_email`
--

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `notifier_id` int NOT NULL DEFAULT '0',
  `notifier_type` text,
  `recipient_id` int NOT NULL DEFAULT '0',
  `type` varchar(100) NOT NULL DEFAULT '',
  `text` text,
  `url` varchar(3000) NOT NULL DEFAULT '',
  `seen` varchar(50) NOT NULL DEFAULT '0',
  `time` varchar(50) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `notifier_id`, `notifier_type`, `recipient_id`, `type`, `text`, `url`, `seen`, `time`) VALUES
(363, 5, 'customer', 49, 'overstayed_reservation', '', 'http://hms.te/room/reserved_room/2/5', '1590585519', '1590571734'),
(364, 8, 'customer', 49, 'overstayed_reservation', '', 'http://hms.te/room/reserved_room/3/8', '1590585519', '1590581583');

-- --------------------------------------------------------

--
-- Table structure for table `privileges`
--

CREATE TABLE `privileges` (
  `id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `info` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `permissions` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

--
-- Dumping data for table `privileges`
--

INSERT INTO `privileges` (`id`, `title`, `info`, `permissions`) VALUES
(2, 'Admin', 'This privilege grants the user all permissions', 'eyIwIjoibWFuYWdlLXByaXZpbGVnZSIsIjEiOiJyb29tcyIsIjIiOiJyb29tLXNhbGVzIiwiMyI6InJvb20tdHlwZXMiLCI0IjoiY3VzdG9tZXJzIn0='),
(5, 'Default', 'The default permission', 'eyIwIjoiZGVmYXVsdCJ9'),
(6, 'Super User', 'This privilege grants user permission to become awsome', 'eyIwIjoiZGVmYXVsdCIsIjEiOiJkYXNoYm9hcmQifQ==');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `setting_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `setting_value` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES
(1, 'site_name', 'Kaduna State Media Coporation Email Suite'),
(2, 'payment_ref_pref', 'HSPRS-'),
(3, 'site_currency', 'NGN'),
(4, 'ip_interval', '2'),
(5, 'currency_symbol', '₦'),
(6, 'site_logo', 'uploads/sites/site_logo.png'),
(7, 'contact_email', 'hayattregencysuites@gmail.com'),
(8, 'contact_phone', '09031987876'),
(9, 'contact_address', '13 Somewhere in kaduna'),
(10, 'contact_facebook', 'https://facebook.com/hayatt'),
(11, 'checkout_info', 'In the above code you’ll notice a pair of variables. In a case like this, the entire chunk of data between these pairs would be repeated multiple times, corresponding to the number of rows in the “blog_entries” element of the parameters array.'),
(12, 'paystack_public', 'pk_test_36a1170f08ef500b7c55315ea6792864953b4ca0'),
(13, 'paystack_secret', 'sk_test_bbc81f998f1151d1d34f8ff0e4ae4ef1fa3a9784'),
(14, 'restrict_creation', '1'),
(15, 'contact_twitter', 'https://twitter.com/hayattregencysuites'),
(16, 'contact_instagram', 'https://instagram.com/hayattregency'),
(17, 'contact_days', 'Mon to Fri 9am to 6 pm'),
(18, 'site_name_abbr', 'KSMCES'),
(22, 'favicon', 'uploads/sites/favicon.jpg'),
(25, 'site_country', 'Nigeria'),
(27, 'debt_tolerance', '3'),
(28, 'primary_server', 'hoolicontech.com'),
(29, 'server_dir', 'hoolicontech.com'),
(30, 'cpanel_host', '209.126.127.196'),
(31, 'cpanel_username', 'hoolicon'),
(33, 'cpanel_password', 'friendship1A@');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(50) CHARACTER SET utf32 COLLATE utf32_general_ci NOT NULL,
  `password_def` varchar(128) DEFAULT NULL,
  `firstname` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `lastname` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `phone` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `country` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `state` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `city` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `role` int NOT NULL DEFAULT '1',
  `role_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `username`, `password`, `password_def`, `firstname`, `lastname`, `phone`, `email`, `address`, `country`, `state`, `city`, `image`, `role`, `role_id`) VALUES
(122, 'mygames.ng', 'friendship1A@', '', 'Iruruantaziba', 'Obi', '09031983482', 'mygames.ng@hoolicontech.com', NULL, NULL, NULL, NULL, NULL, 1, 6);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipient_id` (`recipient_id`),
  ADD KEY `type` (`type`),
  ADD KEY `notifier_id` (`notifier_id`);

--
-- Indexes for table `privileges`
--
ALTER TABLE `privileges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `login` (`username`,`password`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=365;
--
-- AUTO_INCREMENT for table `privileges`
--
ALTER TABLE `privileges`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
