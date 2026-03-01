-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 28, 2026 at 09:17 PM
-- Server version: 10.6.24-MariaDB-cll-lve
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `auth_users`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_log`
--

CREATE TABLE `audit_log` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `device_type` varchar(100) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `error_type` varchar(100) NOT NULL,
  `error_message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `audit_log`
--

INSERT INTO `audit_log` (`id`, `ip_address`, `device_type`, `user_email`, `error_type`, `error_message`, `created_at`) VALUES
(20, '103.238.230.194', 'Desktop', 'adityach0523@gmail.com', 'Login Success', 'User logged in via POST', '2025-08-13 13:53:36'),
(21, '103.238.230.194', 'Desktop', 'adityach0523@gmail.com', 'Login Success', 'User logged in via POST', '2025-08-13 16:09:42'),
(22, '103.238.230.194', 'Desktop', 'adityach0523@gmail.com', 'Login Success', 'User logged in via POST', '2025-08-13 16:15:21'),
(23, '103.238.230.194', 'Desktop', NULL, 'Authentication Error', 'No user found for username: aditya_3515', '2025-08-13 16:15:39'),
(24, '103.238.230.194', 'Desktop', NULL, 'General Exception', 'Login error: Invalid credentials. Please try again.', '2025-08-13 16:15:39'),
(25, '103.238.230.194', 'Desktop', 'adityach0523@gmail.com', 'Duplicate User Error', 'Username or email already registered.', '2025-08-13 16:16:28'),
(26, '103.238.230.194', 'Desktop', 'adityach0523@gmail.com', 'Login Success', 'User logged in via POST', '2025-08-13 16:23:11'),
(27, '103.238.230.194', 'Desktop', 'adityach0523@gmail.com', 'Login Success', 'User logged in via POST', '2025-08-13 16:47:42'),
(28, '103.238.230.194', 'Desktop', 'adityach0523@gmail.com', 'Login Success', 'User logged in via POST', '2025-08-13 17:53:34'),
(29, '103.238.230.194', 'Desktop', 'adityach0523@gmail.com', 'Login Success', 'User logged in via POST', '2025-08-13 18:31:32'),
(30, '103.238.230.194', 'Desktop', 'adityach0523@gmail.com', 'Login Success', 'User logged in via POST', '2025-08-13 18:56:29'),
(31, '103.238.230.194', 'Desktop', 'adityach0523@gmail.com', 'Authentication Error', 'Invalid password for username: aditya_3515_', '2025-08-13 20:28:09'),
(32, '103.238.230.194', 'Desktop', NULL, 'General Exception', 'Login error: Invalid credentials. Please try again.', '2025-08-13 20:28:17'),
(33, '103.238.230.194', 'Desktop', 'adityach0523@gmail.com', 'Login Success', 'User logged in via POST', '2025-08-13 20:28:48'),
(34, '103.238.230.194', 'Desktop', 'adityach0523@gmail.com', 'Authentication Error', 'Invalid password for username: aditya_3515_', '2025-08-14 05:30:31'),
(35, '103.238.230.194', 'Desktop', NULL, 'General Exception', 'Login error: Invalid credentials. Please try again.', '2025-08-14 05:30:39'),
(36, '103.238.230.194', 'Desktop', 'adityach0523@gmail.com', 'Login Success', 'User logged in via POST', '2025-08-14 05:31:06'),
(37, '103.238.230.194', 'Desktop', 'adityach0523@gmail.com', 'Authentication Error', 'Invalid password for username: aditya_3515_', '2025-08-14 06:03:44'),
(38, '103.238.230.194', 'Desktop', NULL, 'General Exception', 'Login error: Invalid credentials. Please try again.', '2025-08-14 06:03:53'),
(39, '103.238.230.194', 'Desktop', 'adityach0523@gmail.com', 'Login Success', 'User logged in via POST', '2025-08-14 06:04:39'),
(40, '103.238.230.194', 'Desktop', 'adityach0523@gmail.com', 'Login Success', 'User logged in via POST', '2025-08-14 06:18:42'),
(41, '103.238.230.194', 'Desktop', 'adityach0523@gmail.com', 'Login Success', 'User logged in via POST', '2025-08-14 06:20:30'),
(42, '223.187.49.255', 'Desktop', 'adityach0523@gmail.com', 'Authentication Error', 'Invalid password for username: aditya_3515_', '2025-08-22 04:16:30'),
(43, '223.187.49.255', 'Desktop', NULL, 'General Exception', 'Login error: Invalid credentials. Please try again.', '2025-08-22 04:16:38'),
(44, '223.187.49.255', 'Desktop', 'adityach0523@gmail.com', 'Login Success', 'User logged in via POST', '2025-08-22 04:17:02'),
(45, '223.187.49.231', 'Desktop', 'adityach0523@gmail.com', 'Authentication Error', 'Invalid password for username: aditya_3515_', '2025-08-23 17:02:10'),
(46, '223.187.49.231', 'Desktop', NULL, 'General Exception', 'Login error: Invalid credentials. Please try again.', '2025-08-23 17:02:18'),
(47, '223.187.49.231', 'Desktop', 'adityach0523@gmail.com', 'Authentication Error', 'Invalid password for username: aditya_3515_', '2025-08-23 17:02:36'),
(48, '223.187.49.231', 'Desktop', NULL, 'General Exception', 'Login error: Invalid credentials. Please try again.', '2025-08-23 17:02:44'),
(49, '223.187.49.231', 'Desktop', 'adityach0523@gmail.com', 'Login Success', 'User logged in via POST', '2025-08-23 17:03:08'),
(50, '49.205.100.189', 'Desktop', 'adityach0523@gmail.com', 'Login Success', 'User logged in via POST', '2025-08-24 08:00:07'),
(51, '103.238.230.194', 'Desktop', NULL, 'Authentication Error', 'No user found for username: aditya_3515_', '2025-09-01 09:13:51'),
(52, '103.238.230.194', 'Desktop', NULL, 'General Exception', 'Login error: Invalid credentials. Please try again.', '2025-09-01 09:13:51'),
(53, '103.238.230.194', 'Desktop', 'adityach0523@gmail.com', 'Registration Success', 'User registered successfully, verification email queued', '2025-09-01 09:28:26'),
(54, '103.238.230.194', 'Desktop', 'adityach0523@gmail.com', 'Verification Error', 'Email verification needed. Please check your inbox and confirm your email address.', '2025-09-01 09:29:51'),
(55, '103.238.230.194', 'Desktop', NULL, 'General Exception', 'Login error: Email verification needed. Please check your inbox and confirm your email address.', '2025-09-01 09:29:51'),
(56, '103.238.230.194', 'Desktop', 'adityach0523@gmail.com', 'Login Success', 'User logged in via POST', '2025-09-01 09:30:36'),
(57, '103.238.230.194', 'Desktop', 'adityach0523@gmail.com', 'Login Success', 'User logged in via POST', '2025-09-01 09:31:17'),
(58, '103.238.230.194', 'Desktop', 'adityach0523@gmail.com', 'Login Success', 'User logged in via POST', '2025-09-01 09:32:12'),
(59, '103.238.230.194', 'Desktop', 'adityach0523@gmail.com', 'Login Success', 'User logged in via POST', '2025-09-01 09:32:41'),
(60, '103.238.230.194', 'Desktop', 'adityach0523@gmail.com', 'Login Success', 'User logged in via POST', '2025-09-01 09:33:34'),
(61, '103.238.230.194', 'Desktop', NULL, 'API Exception', 'Login error: Invalid API response: Missing is_verified field', '2025-11-27 14:01:52'),
(62, '103.238.230.194', 'Desktop', NULL, 'API Exception', 'Login error: Invalid API response: Missing is_verified field', '2025-11-27 14:02:41'),
(63, '103.238.230.194', 'Desktop', NULL, 'API Exception', 'Login error: Invalid API response: Missing is_verified field', '2025-11-27 14:34:45'),
(64, '103.238.230.194', 'Desktop', NULL, 'API Exception', 'Login error: Invalid API response: Missing is_verified field', '2025-11-27 14:41:17'),
(65, '103.238.230.194', 'Desktop', 'adityach0523@gmail.com', 'Login Success', 'User logged in via POST', '2025-11-27 14:48:46'),
(66, '103.238.230.194', 'Desktop', 'adityach0523@gmail.com', 'Login Success', 'User logged in via POST', '2025-11-29 08:21:03'),
(67, '103.238.230.194', 'Desktop', NULL, 'Authentication Error', 'No user found for username: 99220040084', '2025-11-29 12:49:04'),
(68, '103.238.230.194', 'Desktop', NULL, 'General Exception', 'Login error: Invalid credentials. Please try again.', '2025-11-29 12:49:04'),
(69, '103.238.230.194', 'Desktop', 'adityach0523@gmail.com', 'Login Success', 'User logged in via POST', '2025-11-29 12:49:39'),
(70, '103.238.230.194', 'Desktop', 'adityach0523@gmail.com', 'Login Success', 'User logged in via POST', '2025-11-30 06:05:19');

-- --------------------------------------------------------

--
-- Table structure for table `email_queue`
--

CREATE TABLE `email_queue` (
  `id` int(11) NOT NULL,
  `recipient` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('pending','sent','failed') NOT NULL DEFAULT 'pending',
  `retry_count` int(11) NOT NULL DEFAULT 0,
  `error_message` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_queue`
--

INSERT INTO `email_queue` (`id`, `recipient`, `subject`, `message`, `status`, `retry_count`, `error_message`, `created_at`, `updated_at`, `sent_at`) VALUES
(67, 'adityach0523@gmail.com', 'New Login to Your Bhavani Crafts Account', '&lt;html&gt;\r\n&lt;body&gt;\r\n&lt;div style=&quot;max-width:600px;margin:30px auto;background:#fff;padding:25px;border-radius:10px;box-shadow:0 0 10px rgba(0,0,0,0.05);border-top:5px solid #b33c00;font-family:Arial,sans-serif;&quot;&gt;\r\n  &lt;div style=&quot;text-align:center;padding-bottom:20px;&quot;&gt;\r\n    &lt;h2 style=&quot;margin:0;color:#b33c00;&quot;&gt;New Login Detected&lt;/h2&gt;\r\n    &lt;p style=&quot;color:#444;&quot;&gt;A new login to your Bhavani Crafts account was detected.&lt;/p&gt;\r\n  &lt;/div&gt;\r\n  &lt;div style=&quot;font-size:15px;color:#333;&quot;&gt;\r\n    &lt;p&gt;&lt;strong&gt;Username:&lt;/strong&gt; aditya_3515_&lt;/p&gt;\r\n    &lt;p&gt;&lt;strong&gt;Email:&lt;/strong&gt; adityach0523@gmail.com&lt;/p&gt;\r\n    &lt;p&gt;&lt;strong&gt;IP Address:&lt;/strong&gt; 103.238.230.194&lt;/p&gt;\r\n    &lt;p&gt;&lt;strong&gt;Location:&lt;/strong&gt; Madurai, Tamil Nadu, India&lt;/p&gt;\r\n    &lt;p&gt;&lt;strong&gt;Device:&lt;/strong&gt; Desktop&lt;/p&gt;\r\n    &lt;p style=&quot;margin-top:15px;color:#c00;&quot;&gt;&lt;strong&gt;If this wasn&#039;t you, please &lt;a href=&quot;https://bhavanicrafts.com/security&quot; style=&quot;color:#b33c00;text-decoration:none;&quot;&gt;secure your account&lt;/a&gt; immediately.&lt;/strong&gt;&lt;/p&gt;\r\n  &lt;/div&gt;\r\n  &lt;div style=&quot;margin-top:30px;font-size:13px;text-align:center;color:#888;&quot;&gt;\r\n    © Bhavani Crafts | Powered by &lt;a href=&quot;https://awarcrown.com&quot; style=&quot;color:#b33c00;text-decoration:none;&quot;&gt;Awarcrown Corporations LLP&lt;/a&gt;\r\n  &lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;/body&gt;\r\n&lt;/html&gt;', 'sent', 0, NULL, '2025-11-27 07:48:37', NULL, '2025-11-27 07:48:46'),
(68, 'adityach0523@gmail.com', 'New Login to Your Bhavani Crafts Account', '&lt;html&gt;\r\n&lt;body&gt;\r\n&lt;div style=&quot;max-width:600px;margin:30px auto;background:#fff;padding:25px;border-radius:10px;box-shadow:0 0 10px rgba(0,0,0,0.05);border-top:5px solid #b33c00;font-family:Arial,sans-serif;&quot;&gt;\r\n  &lt;div style=&quot;text-align:center;padding-bottom:20px;&quot;&gt;\r\n    &lt;h2 style=&quot;margin:0;color:#b33c00;&quot;&gt;New Login Detected&lt;/h2&gt;\r\n    &lt;p style=&quot;color:#444;&quot;&gt;A new login to your Bhavani Crafts account was detected.&lt;/p&gt;\r\n  &lt;/div&gt;\r\n  &lt;div style=&quot;font-size:15px;color:#333;&quot;&gt;\r\n    &lt;p&gt;&lt;strong&gt;Username:&lt;/strong&gt; aditya_3515_&lt;/p&gt;\r\n    &lt;p&gt;&lt;strong&gt;Email:&lt;/strong&gt; adityach0523@gmail.com&lt;/p&gt;\r\n    &lt;p&gt;&lt;strong&gt;IP Address:&lt;/strong&gt; 103.238.230.194&lt;/p&gt;\r\n    &lt;p&gt;&lt;strong&gt;Location:&lt;/strong&gt; Madurai, Tamil Nadu, India&lt;/p&gt;\r\n    &lt;p&gt;&lt;strong&gt;Device:&lt;/strong&gt; Desktop&lt;/p&gt;\r\n    &lt;p style=&quot;margin-top:15px;color:#c00;&quot;&gt;&lt;strong&gt;If this wasn&#039;t you, please &lt;a href=&quot;https://bhavanicrafts.com/security&quot; style=&quot;color:#b33c00;text-decoration:none;&quot;&gt;secure your account&lt;/a&gt; immediately.&lt;/strong&gt;&lt;/p&gt;\r\n  &lt;/div&gt;\r\n  &lt;div style=&quot;margin-top:30px;font-size:13px;text-align:center;color:#888;&quot;&gt;\r\n    © Bhavani Crafts | Powered by &lt;a href=&quot;https://awarcrown.com&quot; style=&quot;color:#b33c00;text-decoration:none;&quot;&gt;Awarcrown Corporations LLP&lt;/a&gt;\r\n  &lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;/body&gt;\r\n&lt;/html&gt;', 'sent', 0, NULL, '2025-11-29 01:20:55', NULL, '2025-11-29 01:21:03'),
(69, 'adityach0523@gmail.com', 'New Login to Your Bhavani Crafts Account', '&lt;html&gt;\r\n&lt;body&gt;\r\n&lt;div style=&quot;max-width:600px;margin:30px auto;background:#fff;padding:25px;border-radius:10px;box-shadow:0 0 10px rgba(0,0,0,0.05);border-top:5px solid #b33c00;font-family:Arial,sans-serif;&quot;&gt;\r\n  &lt;div style=&quot;text-align:center;padding-bottom:20px;&quot;&gt;\r\n    &lt;h2 style=&quot;margin:0;color:#b33c00;&quot;&gt;New Login Detected&lt;/h2&gt;\r\n    &lt;p style=&quot;color:#444;&quot;&gt;A new login to your Bhavani Crafts account was detected.&lt;/p&gt;\r\n  &lt;/div&gt;\r\n  &lt;div style=&quot;font-size:15px;color:#333;&quot;&gt;\r\n    &lt;p&gt;&lt;strong&gt;Username:&lt;/strong&gt; aditya_3515_&lt;/p&gt;\r\n    &lt;p&gt;&lt;strong&gt;Email:&lt;/strong&gt; adityach0523@gmail.com&lt;/p&gt;\r\n    &lt;p&gt;&lt;strong&gt;IP Address:&lt;/strong&gt; 103.238.230.194&lt;/p&gt;\r\n    &lt;p&gt;&lt;strong&gt;Location:&lt;/strong&gt; Madurai, Tamil Nadu, India&lt;/p&gt;\r\n    &lt;p&gt;&lt;strong&gt;Device:&lt;/strong&gt; Desktop&lt;/p&gt;\r\n    &lt;p style=&quot;margin-top:15px;color:#c00;&quot;&gt;&lt;strong&gt;If this wasn&#039;t you, please &lt;a href=&quot;https://bhavanicrafts.com/security&quot; style=&quot;color:#b33c00;text-decoration:none;&quot;&gt;secure your account&lt;/a&gt; immediately.&lt;/strong&gt;&lt;/p&gt;\r\n  &lt;/div&gt;\r\n  &lt;div style=&quot;margin-top:30px;font-size:13px;text-align:center;color:#888;&quot;&gt;\r\n    © Bhavani Crafts | Powered by &lt;a href=&quot;https://awarcrown.com&quot; style=&quot;color:#b33c00;text-decoration:none;&quot;&gt;Awarcrown Corporations LLP&lt;/a&gt;\r\n  &lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;/body&gt;\r\n&lt;/html&gt;', 'sent', 0, NULL, '2025-11-29 05:49:31', NULL, '2025-11-29 05:49:39'),
(70, 'adityach0523@gmail.com', 'New Login to Your Bhavani Crafts Account', '&lt;html&gt;\r\n&lt;body&gt;\r\n&lt;div style=&quot;max-width:600px;margin:30px auto;background:#fff;padding:25px;border-radius:10px;box-shadow:0 0 10px rgba(0,0,0,0.05);border-top:5px solid #b33c00;font-family:Arial,sans-serif;&quot;&gt;\r\n  &lt;div style=&quot;text-align:center;padding-bottom:20px;&quot;&gt;\r\n    &lt;h2 style=&quot;margin:0;color:#b33c00;&quot;&gt;New Login Detected&lt;/h2&gt;\r\n    &lt;p style=&quot;color:#444;&quot;&gt;A new login to your Bhavani Crafts account was detected.&lt;/p&gt;\r\n  &lt;/div&gt;\r\n  &lt;div style=&quot;font-size:15px;color:#333;&quot;&gt;\r\n    &lt;p&gt;&lt;strong&gt;Username:&lt;/strong&gt; aditya_3515_&lt;/p&gt;\r\n    &lt;p&gt;&lt;strong&gt;Email:&lt;/strong&gt; adityach0523@gmail.com&lt;/p&gt;\r\n    &lt;p&gt;&lt;strong&gt;IP Address:&lt;/strong&gt; 103.238.230.194&lt;/p&gt;\r\n    &lt;p&gt;&lt;strong&gt;Location:&lt;/strong&gt; Madurai, Tamil Nadu, India&lt;/p&gt;\r\n    &lt;p&gt;&lt;strong&gt;Device:&lt;/strong&gt; Desktop&lt;/p&gt;\r\n    &lt;p style=&quot;margin-top:15px;color:#c00;&quot;&gt;&lt;strong&gt;If this wasn&#039;t you, please &lt;a href=&quot;https://bhavanicrafts.com/security&quot; style=&quot;color:#b33c00;text-decoration:none;&quot;&gt;secure your account&lt;/a&gt; immediately.&lt;/strong&gt;&lt;/p&gt;\r\n  &lt;/div&gt;\r\n  &lt;div style=&quot;margin-top:30px;font-size:13px;text-align:center;color:#888;&quot;&gt;\r\n    © Bhavani Crafts | Powered by &lt;a href=&quot;https://awarcrown.com&quot; style=&quot;color:#b33c00;text-decoration:none;&quot;&gt;Awarcrown Corporations LLP&lt;/a&gt;\r\n  &lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;/body&gt;\r\n&lt;/html&gt;', 'sent', 0, NULL, '2025-11-29 23:05:11', NULL, '2025-11-29 23:05:19');

-- --------------------------------------------------------

--
-- Table structure for table `otps`
--

CREATE TABLE `otps` (
  `email` varchar(255) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `expires` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `otp_verification`
--

CREATE TABLE `otp_verification` (
  `id` int(11) NOT NULL,
  `intern_id` varchar(50) DEFAULT NULL,
  `otp` varchar(6) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(100) NOT NULL,
  `expiry` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `token`, `expiry`) VALUES
(0, 22, '8fdfa1811f260c8f934e9442dd7eff2481574d98b9a0cd456d789f61e56f3ee85de7783e1b98ebeee116843e27d54c278abc', '2025-11-27 14:38:20');

-- --------------------------------------------------------

--
-- Table structure for table `remember_tokens`
--

CREATE TABLE `remember_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_agent` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `remember_tokens`
--

INSERT INTO `remember_tokens` (`id`, `user_id`, `token`, `created_at`, `user_agent`, `ip_address`) VALUES
(1, 18, '$argon2id$v=19$m=65536,t=4,p=1$RmhnWDVOcDYyS1YxNXFsdQ$+FDOVM8d9M', '2025-08-13 18:56:29', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '103.238.230.194');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `policy` varchar(255) DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `verification_token` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `token_expiry` varchar(255) DEFAULT NULL,
  `session_token` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `created_at`, `policy`, `name`, `verification_token`, `is_verified`, `token_expiry`, `session_token`) VALUES
(22, 'aditya_3515_', 'adityach0523@gmail.com', '$2y$12$lp6sWjkuqjc6mdUqTG0q8uBHJRN8EIB/rp1HEOvpAXpTvZJvmZFka', '2025-09-01 09:28:26', '1', 'aditya', NULL, 1, NULL, 'ea2051cce9c14223a87769aa019b049d66516d4f437f85ae2353418d71122609');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_queue`
--
ALTER TABLE `email_queue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `email_queue`
--
ALTER TABLE `email_queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
