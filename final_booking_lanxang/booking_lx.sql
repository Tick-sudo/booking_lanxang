-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2025 at 11:14 AM
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
-- Database: `booking_lx`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking1`
--

CREATE TABLE `booking1` (
  `Booking_id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Lname` varchar(50) NOT NULL,
  `Id_plane` varchar(10) NOT NULL,
  `Fromm` varchar(20) NOT NULL,
  `Too` varchar(20) NOT NULL,
  `Booking_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `Booking_status` varchar(30) NOT NULL,
  `Tel` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `booking1`
--

INSERT INTO `booking1` (`Booking_id`, `Name`, `Lname`, `Id_plane`, `Fromm`, `Too`, `Booking_date`, `Booking_status`, `Tel`) VALUES
(1267, 'tick', 'seevongxay', '', 'PS', 'LPB', '0000-00-00 00:00:00', 'Confirmed', '59565132'),
(1272, 'tick', 'seevongxay', '10008097', 'PS', 'LPB', '0000-00-00 00:00:00', 'mkml', '59565133'),
(1274, 'Guest', 'Guest', '', 'joypop', 'joypop', '2025-05-16 12:28:32', 'Pending', '94313749');

-- --------------------------------------------------------

--
-- Table structure for table `customer1`
--

CREATE TABLE `customer1` (
  `ID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Lname` varchar(50) NOT NULL,
  `Gender` varchar(15) NOT NULL,
  `Date_of_birth` date NOT NULL,
  `Province` varchar(50) NOT NULL,
  `Country` varchar(70) NOT NULL,
  `Nationality` varchar(50) NOT NULL,
  `Address` varchar(50) NOT NULL,
  `Id_passport` varchar(30) NOT NULL,
  `Id_card` varchar(30) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Type_of_ticket` varchar(20) NOT NULL,
  `Tel` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `customer1`
--

INSERT INTO `customer1` (`ID`, `Name`, `Lname`, `Gender`, `Date_of_birth`, `Province`, `Country`, `Nationality`, `Address`, `Id_passport`, `Id_card`, `Email`, `Type_of_ticket`, `Tel`) VALUES
(10008034, 'tick', 'seevongxay', 'Male', '2000-02-02', 'lnt', '', 'lao', 'suanmone', 'p292929', '', 'tick@gmail.com', 'economy', '58589922'),
(10008035, 'tick', 'seevongxay', 'Male', '2000-02-02', 'lnt', '', 'lnt', 'suanmone', 'p292929', '', 'tick@gmail.com', 'economy', '58589922'),
(10008036, 'tick', 'seevongxay', 'Male', '2000-02-02', 'lnt', '', 'lnt', 'suanmone', 'p292929', '', 'tick@gmail.com', 'economy', '58589922'),
(10008037, 'tick', 'ดำไ', 'Male', '0021-02-01', 'กไๆก', '', 'ดๆำไกกไก', 'suanmone', '10008097', 'f5454', 'tick@gmail.com', 'ดไำฟกฟไ', '59565133');

-- --------------------------------------------------------

--
-- Table structure for table `employees1`
--

CREATE TABLE `employees1` (
  `ID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Lname` varchar(50) NOT NULL,
  `Gender` varchar(10) NOT NULL,
  `Date_of_birth` date NOT NULL,
  `Address` varchar(20) NOT NULL,
  `Education_background` varchar(30) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Tel` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `flight1`
--

CREATE TABLE `flight1` (
  `Id_plane` int(10) NOT NULL,
  `Fromm` varchar(20) NOT NULL,
  `Too` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `flight1`
--

INSERT INTO `flight1` (`Id_plane`, `Fromm`, `Too`) VALUES
(2585500, 'joypop', 'สม'),
(2585558, 'joypop', 'สม'),
(2585559, 'joypop', 'สม'),
(2585560, 'joypop', 'สม'),
(2585562, 'joypop', 'สม'),
(2585564, 'mm', 'k k'),
(5623363, 'joypop', 'สม');

-- --------------------------------------------------------

--
-- Table structure for table `pay1`
--

CREATE TABLE `pay1` (
  `Pay_id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Lname` varchar(50) NOT NULL,
  `Payment_status` varchar(30) NOT NULL,
  `Price` int(8) NOT NULL,
  `Currency_type` varchar(10) NOT NULL,
  `Pay_date` date NOT NULL,
  `Transaction_id` varchar(30) NOT NULL,
  `Tel` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket1`
--

CREATE TABLE `ticket1` (
  `Ticket_id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Lname` varchar(50) NOT NULL,
  `Fromm` varchar(20) NOT NULL,
  `Too` varchar(20) NOT NULL,
  `Flight` varchar(30) NOT NULL,
  `Seat` varchar(5) NOT NULL,
  `Boarding_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `ticket1`
--

INSERT INTO `ticket1` (`Ticket_id`, `Name`, `Lname`, `Fromm`, `Too`, `Flight`, `Seat`, `Boarding_time`) VALUES
(6, 'tick', 'seevongxay', 'PS', 'LPB', 'mkm', 'm8888', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking1`
--
ALTER TABLE `booking1`
  ADD PRIMARY KEY (`Booking_id`);

--
-- Indexes for table `customer1`
--
ALTER TABLE `customer1`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `employees1`
--
ALTER TABLE `employees1`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `flight1`
--
ALTER TABLE `flight1`
  ADD PRIMARY KEY (`Id_plane`);

--
-- Indexes for table `pay1`
--
ALTER TABLE `pay1`
  ADD PRIMARY KEY (`Pay_id`);

--
-- Indexes for table `ticket1`
--
ALTER TABLE `ticket1`
  ADD PRIMARY KEY (`Ticket_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking1`
--
ALTER TABLE `booking1`
  MODIFY `Booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1275;

--
-- AUTO_INCREMENT for table `customer1`
--
ALTER TABLE `customer1`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10008038;

--
-- AUTO_INCREMENT for table `employees1`
--
ALTER TABLE `employees1`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23256528;

--
-- AUTO_INCREMENT for table `flight1`
--
ALTER TABLE `flight1`
  MODIFY `Id_plane` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8989900;

--
-- AUTO_INCREMENT for table `pay1`
--
ALTER TABLE `pay1`
  MODIFY `Pay_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket1`
--
ALTER TABLE `ticket1`
  MODIFY `Ticket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
