-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2021 at 04:51 AM
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
  `icon` varchar(255) DEFAULT 'default.jpg',
  `email` varchar(320) NOT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `mobile` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `agent`
--

INSERT INTO `agent` (`agent_ID`, `fname`, `lname`, `icon`, `email`, `phone`, `mobile`) VALUES
(1, 'Chris', 'Becker', '1_cbecker.jpg', 'cbecker@keyhomes.co.nz', '(07) 854 5555 ext. 02', '021 555 5555'),
(2, 'Ben', 'Mitchell', '2_bmitchell.jpg', 'bmitchell@keyhomes.co.nz', '(07) 854 5555 ext. 01', '021 555 5556');

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
(1, 1, '1_12f43ac33624d9a6c1c7320fefd9af8a4019fafe.jpg'),
(2, 1, '1_4ff0b92f3f7b7e029f2fbfd09af163e7790df873.jpg'),
(3, 1, '1_cf2e489b2207d6fe09f5b3772f519719460d7be6.jpg'),
(4, 1, '1_6ab2269d6ffdb2a25b95777caa6eddde417101a6.jpg'),
(5, 2, '2_5edb8004b905470a54529b0d2f61c60a0c84730f.jpg'),
(6, 2, '2_4f5f5e0468e94b7bcd9d3616dfe6e30224b6d38a.jpg'),
(7, 2, '2_02bc460918a45ba0a2a5211491bbe36785ca57ec.jpg'),
(8, 2, '2_e95c8e6141d4933bf8144fbaa1ca3efaa7476fd8.jpg'),
(9, 3, '3_51b237ac7c39ed8a8bd5a0cae0ad1857c0c09526.jpg'),
(10, 3, '3_b1d1f28fc54329dd54213e5b8ca94d5c056896b0.jpg'),
(11, 3, '3_2f5949741d88cf4a420daa1c59f059ccd51f722e.jpg'),
(12, 3, '3_142b3c706696a34c7a2436243627b28174c6f0f7.jpg'),
(13, 4, '4_265aaae78d43c25bb83181cdccb4d305b46e80b2.jpg'),
(14, 4, '4_5eb3f2cb57d354eb19f6468383cb96f7686929dc.jpg'),
(15, 4, '4_213d1410ad499cb0c83e9ac8872b311fce16ba6e.jpg'),
(16, 4, '4_12e02a446ba6e3281e78c2bbd4abcb46f56da034.jpg');

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
(4, 'Auction', 0, 'Huge 4-bedroom home just begging to be renovated.', 4, 2, 2, 1, '47a', 'Stanley Avenue', 'Auckland', '620');

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
  MODIFY `agent_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `image_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `property`
--
ALTER TABLE `property`
  MODIFY `property_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
