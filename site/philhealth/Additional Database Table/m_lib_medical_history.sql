-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 31, 2014 at 11:23 AM
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
-- Table structure for table `m_lib_medical_history`
--

CREATE TABLE IF NOT EXISTS `m_lib_medical_history` (
  `history_id` float NOT NULL AUTO_INCREMENT,
  `history_name` varchar(50) NOT NULL,
  PRIMARY KEY (`history_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `m_lib_medical_history`
--

INSERT INTO `m_lib_medical_history` (`history_id`, `history_name`) VALUES
(1, 'Alergy'),
(2, 'Asthma'),
(3, 'Cancer'),
(4, 'Cerebrovascular'),
(5, 'Coronary Artery Disease'),
(6, 'Diabetes Mellitus'),
(7, 'Emphysema'),
(8, 'Epilepsy/Seizure Disorder'),
(9, 'Hepatitis'),
(10, 'Hyperlipidemia'),
(11, 'Hypertension'),
(12, 'Peptic Ulcer Disease'),
(13, 'Pneumonia'),
(14, 'Thyroid Disease'),
(15, 'Tuberculosis'),
(16, 'Urinary Tract Infection');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
