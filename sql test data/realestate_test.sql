-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2021 at 03:42 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `realestate`
--

-- --------------------------------------------------------

--
-- Table structure for table `agent`
--

CREATE TABLE `agent` (
  `agent_ID` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `email` varchar(320) NOT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `mobile` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `agent`
--

INSERT INTO `agent` (`agent_ID`, `fname`, `lname`, `icon`, `email`, `phone`, `mobile`) VALUES
(1, 'Chris', 'Becker', '/uploads/agents/test/cbecker.jpg', 'cbecker@keyhomes.co.nz', '(07) 854 5555 ext. 02', '021 555 5555'),
(2, 'Ben', 'Mitchell', 'uploads/agents/test/bmitchell.jpg', 'bmitchell@keyhomes.co.nz', '(07) 854 5555 ext. 01', '021 555 5556');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `image_ID` int(11) NOT NULL,
  `property_ID` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`image_ID`, `property_ID`, `image`) VALUES
(1, 1, '/uploads/properties/test/0_0.jpg'),
(2, 1, '/uploads/properties/test/0_1.jpg'),
(3, 1, '/uploads/properties/test/0_2.jpg'),
(4, 1, '/uploads/properties/test/0_3.jpg'),
(5, 2, '/uploads/properties/test/1_0.jpg'),
(6, 2, '/uploads/properties/test/1_1.jpg'),
(7, 2, '/uploads/properties/test/1_2.jpg'),
(8, 2, '/uploads/properties/test/1_3.jpg'),
(9, 3, '/uploads/properties/test/2_0.jpg'),
(10, 3, '/uploads/properties/test/2_1.jpg'),
(11, 3, '/uploads/properties/test/2_2.jpg'),
(12, 3, '/uploads/properties/test/2_3.jpg'),
(13, 4, '/uploads/properties/test/3_0.jpg'),
(14, 4, '/uploads/properties/test/3_1.jpg'),
(15, 4, '/uploads/properties/test/3_2.jpg'),
(16, 4, '/uploads/properties/test/3_3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `property`
--

CREATE TABLE `property` (
  `property_ID` int(11) NOT NULL,
  `saleType` varchar(20) DEFAULT NULL,
  `price` float NOT NULL,
  `description` text DEFAULT NULL,
  `bedrooms` smallint(6) NOT NULL,
  `bathrooms` smallint(6) NOT NULL,
  `garage` smallint(6) NOT NULL,
  `agent_ID` int(11) DEFAULT NULL,
  `streetNum` varchar(10) NOT NULL,
  `street` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `postcode` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `property`
--

INSERT INTO `property` (`property_ID`, `saleType`, `price`, `description`, `bedrooms`, `bathrooms`, `garage`, `agent_ID`, `streetNum`, `street`, `city`, `postcode`) VALUES
(1, 'Sale', 690000, 'Large, three-bedroom house with a view located close to the Marina.', 3, 2, 2, 2, '15', 'Beach Road', 'Waikawa', '7220'),
(2, 'Sale', 690000, 'Three-bedroom family home in a quiet neighbourhood.', 3, 2, 2, 1, '18A', 'Cavan Street', 'Ngaruawahia', '3720'),
(3, 'Sale', 530000, 'Newly-renovated property with modern comforts, adjacent workshop, and large back yard.', 3, 2, 2, 2, '505', 'Wellington Road', 'Marton', '4710'),
(4, 'Auction', 0, 'Huge 4-bedroom home just begging to be renovated', 4, 2, 2, 1, '47a', 'Stanley Avenue', 'Auckland', '620');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_ID` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `isAdmin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_ID`, `username`, `password`, `isAdmin`) VALUES
(1, 'admin', '$2y$10$DbF6zrUhwVNiNF.Tp0RZfe53hjdZv3TLrEmBv.J.SgbpOZeD0INUa', 1);

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `user_ID` int(11) DEFAULT NULL,
  `property_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agent`
--
ALTER TABLE `agent`
  ADD PRIMARY KEY (`agent_ID`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`image_ID`),
  ADD KEY `fk_property` (`property_ID`);

--
-- Indexes for table `property`
--
ALTER TABLE `property`
  ADD PRIMARY KEY (`property_ID`),
  ADD KEY `fk_assAgent` (`agent_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_ID`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD KEY `fk_userWish` (`user_ID`),
  ADD KEY `fk_propertyWish` (`property_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agent`
--
ALTER TABLE `agent`
  MODIFY `agent_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `image_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `property`
--
ALTER TABLE `property`
  MODIFY `property_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gallery`
--
ALTER TABLE `gallery`
  ADD CONSTRAINT `fk_property` FOREIGN KEY (`property_ID`) REFERENCES `property` (`property_ID`) ON DELETE CASCADE;

--
-- Constraints for table `property`
--
ALTER TABLE `property`
  ADD CONSTRAINT `fk_assAgent` FOREIGN KEY (`agent_ID`) REFERENCES `agent` (`agent_ID`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `fk_propertyWish` FOREIGN KEY (`property_ID`) REFERENCES `property` (`property_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_userWish` FOREIGN KEY (`user_ID`) REFERENCES `users` (`user_ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
