-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 04, 2019 at 09:25 AM
-- Server version: 10.3.15-MariaDB
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `knu_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `ambulance`
--

CREATE TABLE `ambulance` (
  `idambulance` int(11) NOT NULL,
  `Driver_name` varchar(50) DEFAULT NULL,
  `driver_cell_phone` varchar(12) DEFAULT NULL,
  `ambulancecol` varchar(45) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `ambulancecol1` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ambulance_reserve`
--

CREATE TABLE `ambulance_reserve` (
  `id_reseve` int(5) NOT NULL,
  `ambulance_idambulance` int(11) NOT NULL,
  `User_idUser` int(11) NOT NULL,
  `user_longi` varchar(45) DEFAULT NULL,
  `user_lanti` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `faskes`
--

CREATE TABLE `faskes` (
  `idFaskes` int(11) NOT NULL,
  `Name` varchar(45) DEFAULT NULL,
  `Address` varchar(45) DEFAULT NULL,
  `Longitude` varchar(45) DEFAULT NULL,
  `Latitude` varchar(45) DEFAULT NULL,
  `idambulance` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `idUser` int(11) NOT NULL,
  `Name` varchar(45) DEFAULT NULL,
  `Phone_number` varchar(12) DEFAULT NULL,
  `Address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ambulance`
--
ALTER TABLE `ambulance`
  ADD PRIMARY KEY (`idambulance`);

--
-- Indexes for table `ambulance_reserve`
--
ALTER TABLE `ambulance_reserve`
  ADD PRIMARY KEY (`id_reseve`,`ambulance_idambulance`,`User_idUser`),
  ADD KEY `fk_ambulance_has_User_User1_idx` (`User_idUser`),
  ADD KEY `fk_ambulance_has_User_ambulance1_idx` (`ambulance_idambulance`);

--
-- Indexes for table `faskes`
--
ALTER TABLE `faskes`
  ADD PRIMARY KEY (`idFaskes`),
  ADD KEY `fk_Faskes_ambulance_idx` (`idambulance`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`idUser`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ambulance`
--
ALTER TABLE `ambulance`
  MODIFY `idambulance` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ambulance_reserve`
--
ALTER TABLE `ambulance_reserve`
  MODIFY `id_reseve` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faskes`
--
ALTER TABLE `faskes`
  MODIFY `idFaskes` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ambulance_reserve`
--
ALTER TABLE `ambulance_reserve`
  ADD CONSTRAINT `fk_ambulance_has_User_User1` FOREIGN KEY (`User_idUser`) REFERENCES `user` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ambulance_has_User_ambulance1` FOREIGN KEY (`ambulance_idambulance`) REFERENCES `ambulance` (`idambulance`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `faskes`
--
ALTER TABLE `faskes`
  ADD CONSTRAINT `fk_Faskes_ambulance` FOREIGN KEY (`idambulance`) REFERENCES `ambulance` (`idambulance`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
