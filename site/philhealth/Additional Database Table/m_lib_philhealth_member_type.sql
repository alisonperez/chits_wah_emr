-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 31, 2014 at 11:32 AM
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
-- Table structure for table `m_lib_philhealth_member_type`
--

CREATE TABLE IF NOT EXISTS `m_lib_philhealth_member_type` (
  `member_id` int(2) NOT NULL AUTO_INCREMENT,
  `member_label` text NOT NULL,
  `member_type` set('Y','N') NOT NULL,
  PRIMARY KEY (`member_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `m_lib_philhealth_member_type`
--

REPLACE INTO `m_lib_philhealth_member_type` (`member_id`, `member_label`, `member_type`) VALUES
(1, 'Member - Individually Paying', 'Y'),
(2, 'Member - Sponsored / Indigents', 'Y'),
(3, 'Member - OFW', 'Y'),
(4, 'Dependent', 'Y'),
(5, 'Member - Employer Payment', 'Y'),
(6, 'Not member', 'N'),
(7, 'Member - Sponsored-NHTS', 'Y'),
(8, 'Member - Sponsored-NGA', 'Y'),
(9, 'Member - Sponsored-LGU', 'Y'),
(10, 'Member - Sponsored-PRIVATE', 'Y'),
(11, 'Member - Organized Group', 'Y'),
(12, 'Member - Government Employee', 'Y'),
(13, 'Member - Lifetime', 'Y');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
