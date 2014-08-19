-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 31, 2014 at 11:16 AM
-- Server version: 5.5.37
-- PHP Version: 5.3.10-1ubuntu3.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `victoria2`
--

-- --------------------------------------------------------

--
-- Table structure for table `m_lib_religion`
--

CREATE TABLE IF NOT EXISTS `m_lib_religion` (
  `religion_code` varchar(5) NOT NULL,
  `religion_desc` varchar(60) NOT NULL,
  PRIMARY KEY (`religion_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_lib_religion`
--

INSERT INTO `m_lib_religion` (`religion_code`, `religion_desc`) VALUES
('AGLIP', 'Aglipay'),
('ALLY', 'Alliance of Bible Christian Communities'),
('ANGLI', 'Anglican'),
('BAPTI', 'Baptist'),
('BRNAG', 'Born Again Christian'),
('BUDDH', 'Buddhism'),
('CATHO', 'Catholic'),
('CHOG', 'Church of God'),
('EVANG', 'Evangelical'),
('IGNIK', 'Iglesia ni Cristo'),
('JEWIT', 'Jehovahs Witness'),
('LRCM', 'Life Renewal Christian Ministry'),
('LUTHR', 'Lutheran'),
('METOD', 'Methodist'),
('MORMO', 'LDS-Mormons'),
('MUSLI', 'Islam'),
('PENTE', 'Pentecostal'),
('PROTE', 'Protestant'),
('SVDAY', 'Seventh Day Adventist'),
('UCCP', 'UCCP'),
('UNKNO', 'Unknown'),
('WESLY', 'Wesleyan'),
('XTIAN', 'Christian');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
