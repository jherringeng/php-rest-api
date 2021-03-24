-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2021 at 03:51 PM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `users`
--
-- Following for online DB
-- USE dbs1672596;

-- --------------------------------------------------------

--
-- Table structure for table `api_keys`
--

CREATE TABLE `api_keys` (
  `id` int(4) NOT NULL,
  `api_key` char(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `api_keys`
--

INSERT INTO `api_keys` (`id`, `api_key`) VALUES
(1, 'wpf0okfhmjoyb3v0gw16');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(3) NOT NULL,
  `first_name` char(50) DEFAULT NULL,
  `surname` char(50) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `phone` char(20) DEFAULT NULL,
  `email` char(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `surname`, `dob`, `phone`, `email`) VALUES
(1, 'Bruce', 'Wayne', '1945-01-26', '07827 667 047', 'bwayne@wayne.com'),
(2, 'Jonathan', 'Herring', '1984-02-29', '07827 667 047', 'jherring_eng@yahoo.co.uk'),
(3, 'Clark', 'Kent', '1950-01-26', '07827 667 047', 'ckent@daily-planet.com'),
(4, 'Gareth', 'Herring', '1982-01-02', '07777 777 777', 'example@example.com'),
(5, 'Ann', 'Example', '2002-09-10', '07777 777 777', 'example@example.com'),
(6, 'Diana', 'Prince', '1900-07-03', '07777 888 999', 'dprince@vanda.co.uk'),
(7, 'Tony', 'Yoboah', '1970-10-08', '07777 888 999', 'tyeboah@premier.co.uk'),
(8, 'Clint', 'Barton', '1980-05-30', '07890 123 456', 'cbarton@avengers.com'),
(9, 'Steve', 'Rogers', '1920-08-02', '07777 666 555', 'srogers@avengers.com');
--
-- Indexes for dumped tables
--

--
-- Indexes for table `api_keys`
--
ALTER TABLE `api_keys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
