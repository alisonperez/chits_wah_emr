-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 19, 2013 at 10:42 AM
-- Server version: 5.5.22
-- PHP Version: 5.3.10-1ubuntu3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `chits_live`
--

-- --------------------------------------------------------

--
-- Table structure for table `m_lib_sms_appointment`
--

CREATE TABLE IF NOT EXISTS `m_lib_sms_appointment` (
  `appointment_id` int(10) NOT NULL AUTO_INCREMENT,
  `patient_id` int(7) NOT NULL,
  `cp_number` varchar(15) NOT NULL,
  `appt_code` int(3) NOT NULL,
  `date_followup_visit` date NOT NULL,
  `date_sending` date NOT NULL,
  `sms_message` text NOT NULL,
  `consult_id` int(10) NOT NULL,
  `user_id` int(6) NOT NULL,
  `date_recorded` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `activated` set('Y','N') NOT NULL,
  `sending_status` set('sent','not sent','upcoming') NOT NULL,
  `date_sent` datetime NOT NULL,
  PRIMARY KEY (`appointment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
