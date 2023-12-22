-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 21, 2023 at 09:36 PM
-- Server version: 10.5.20-MariaDB
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id21484403_librarydatabase`
--

-- --------------------------------------------------------

--
-- Table structure for table `borrowed`
--

CREATE TABLE `borrowed` (
  `borrowID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `itemType` varchar(6) NOT NULL,
  `itemCopyID` int(11) NOT NULL,
  `borrowStatus` varchar(20) NOT NULL,
  `checkoutDate` datetime NOT NULL,
  `dueDate` datetime NOT NULL,
  `returnedDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowed`
--

INSERT INTO `borrowed` (`borrowID`, `userID`, `itemType`, `itemCopyID`, `borrowStatus`, `checkoutDate`, `dueDate`, `returnedDate`) VALUES
(1, 1, 'book', 1, 'checked out', '2023-11-08 10:28:49', '2023-11-15 10:28:49', NULL),
(2, 1, 'movie', 2, 'returned', '2023-10-01 10:29:47', '2023-10-08 10:29:47', '2023-10-04 10:29:47'),
(3, 3, 'movie', 4, 'returned', '2023-11-10 10:31:01', '2023-11-17 10:31:01', NULL),
(4, 3, 'book', 8, 'checked out', '2023-11-09 10:45:30', '2023-11-16 10:45:30', NULL),
(5, 3, 'movie', 4, 'checked out', '2023-11-10 10:31:01', '2023-11-17 10:31:01', NULL),
(6, 4, 'movie', 2, 'returned', '2023-09-18 21:23:40', '2023-09-25 21:23:40', '2023-09-21 21:23:40'),
(7, 4, 'movie', 3, 'checked out', '2023-10-01 21:24:49', '2023-10-08 21:24:49', '2023-10-05 21:24:49'),
(9, 8, 'book', 12, 'returned', '2023-11-14 00:23:59', '2023-11-21 00:23:59', '2023-11-14 00:25:20'),
(10, 4, 'movie', 3, 'returned', '2023-11-06 21:24:49', '2023-11-13 21:24:49', '2023-11-14 17:05:18'),
(11, 1, 'movie', 2, 'returned', '2023-10-09 10:29:47', '2023-10-16 10:29:47', '2023-10-12 10:29:47'),
(14, 2, 'book', 3, 'checked out', '2023-11-16 00:10:02', '2023-11-23 00:10:02', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `borrowed`
--
ALTER TABLE `borrowed`
  ADD PRIMARY KEY (`borrowID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `borrowed`
--
ALTER TABLE `borrowed`
  MODIFY `borrowID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
