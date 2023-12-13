-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 13, 2023 at 02:22 PM
-- Server version: 10.3.39-MariaDB-cll-lve
-- PHP Version: 8.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kamscode_steganografi`
--

-- --------------------------------------------------------

--
-- Table structure for table `rsa_keys`
--

CREATE TABLE `rsa_keys` (
  `user` varchar(8) NOT NULL,
  `public` varchar(135) NOT NULL,
  `private` varchar(135) NOT NULL,
  `dibuat` int(11) NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rsa_keys`
--

INSERT INTO `rsa_keys` (`user`, `public`, `private`, `dibuat`) VALUES
('dev', '-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAs9KEtDkUipD36wDJMqvu\nFPTvdwvBcZdJ/AO0SiOV9EsjuLqfFE16BPYa82rgTxk', '-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCz0oS0ORSKkPfr\nAMkyq+4U9O93C8Fxl0n8A7RKI5X0SyO4up8UTXoE9h', 2147483647);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(8) NOT NULL,
  `username` varchar(45) NOT NULL,
  `nama_lengkap` varchar(68) NOT NULL,
  `email` varchar(45) NOT NULL,
  `password` varchar(115) NOT NULL,
  `dibuat` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `nama_lengkap`, `email`, `password`, `dibuat`) VALUES
('5f339awe', 'kamscode', 'Fathurrahman', 'fathur.ashter15@gmail.com', '$2y$10$D1U7yCWtmdo/NwjmNoCHb.v3V41ngsd0heR2VPPqoYS/doe7c6/9i', '2023-05-01 12:00:05'),
('Ee8H3go8', 'dev', 'Testing register', 'dev@m.com', '$2y$10$gGjj0xDUwj1YYr86MM/8MefVoDLds0pSpqkosWfpJfOyQjB.pbjEG', '2023-06-06 10:09:44'),
('Kf5mXqpD', 'rahmi', 'vera rahmi', 'verarahmi789@gmail.com', '$2y$10$LLa/lv5JHgVGcwPEkvfMMek/EaK8Vll0ryhIqOz5a9s/CqnRM8WtO', '2023-06-06 19:00:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
