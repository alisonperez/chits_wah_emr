-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 31, 2014 at 11:33 AM
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
-- Table structure for table `m_consult_notes_pe`
--

CREATE TABLE IF NOT EXISTS `m_consult_notes_pe` (
  `pe_id` float NOT NULL AUTO_INCREMENT,
  `notes_id` float NOT NULL,
  `consult_id` float NOT NULL,
  `patient_id` float NOT NULL,
  `user_id` float NOT NULL,
  `breast_screen` varchar(10) NOT NULL,
  `breast_remarks` varchar(255) NOT NULL,
  `skin_code` varchar(100) NOT NULL,
  `skin_remarks` varchar(255) NOT NULL,
  `heent_code` varchar(100) NOT NULL,
  `heent_remarks` varchar(255) NOT NULL,
  `chest_code` varchar(100) NOT NULL,
  `chest_remarks` varchar(255) NOT NULL,
  `heart_code` varchar(100) NOT NULL,
  `heart_remarks` varchar(255) NOT NULL,
  `abdomen_code` varchar(100) NOT NULL,
  `abdomen_remarks` varchar(255) NOT NULL,
  `extremities_code` varchar(100) NOT NULL,
  `extremities_remarks` varchar(255) NOT NULL,
  `pe_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pe_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
