-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 31, 2014 at 11:24 AM
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
-- Table structure for table `m_patient_history`
--

CREATE TABLE IF NOT EXISTS `m_patient_history` (
  `record_id` float NOT NULL AUTO_INCREMENT,
  `consult_id` float NOT NULL,
  `patient_id` float NOT NULL,
  `user_id` float NOT NULL,
  `pasthistory_id` varchar(100) NOT NULL,
  `familyhistory_id` varchar(100) NOT NULL,
  `medintake_id` varchar(100) NOT NULL,
  `menarche` float NOT NULL,
  `lmp` date NOT NULL,
  `period_duration` float NOT NULL,
  `cycle` float NOT NULL,
  `pads_perday` float NOT NULL,
  `onset_sexinter` float NOT NULL,
  `method_id` varchar(20) NOT NULL,
  `menopause` char(1) NOT NULL DEFAULT 'N',
  `meno_age` float NOT NULL,
  `smoking` char(1) NOT NULL,
  `pack_peryear` float NOT NULL,
  `alcohol` char(1) NOT NULL,
  `bottles_perday` float NOT NULL,
  `ill_drugs` char(1) NOT NULL,
  `history_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`record_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
