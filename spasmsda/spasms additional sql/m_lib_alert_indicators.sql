-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 19, 2013 at 10:36 AM
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
-- Table structure for table `m_lib_alert_indicators`
--

CREATE TABLE IF NOT EXISTS `m_lib_alert_indicators` (
  `alert_indicator_id` int(11) NOT NULL AUTO_INCREMENT,
  `main_indicator` varchar(20) NOT NULL,
  `sub_indicator` text NOT NULL,
  `efhsis_code` varchar(25) NOT NULL,
  PRIMARY KEY (`alert_indicator_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50 ;

--
-- Dumping data for table `m_lib_alert_indicators`
--

INSERT INTO `m_lib_alert_indicators` (`alert_indicator_id`, `main_indicator`, `sub_indicator`, `efhsis_code`) VALUES
(1, 'mc', 'Quality Prenatal Visit', ''),
(2, 'mc', 'Expected Date of Delivery', ''),
(3, 'mc', 'Postpartum Visit', ''),
(4, 'mc', 'Tetanus Toxoid Intake (CPAB)', ''),
(5, 'mc', 'Vitamin A Intake (20,000 unit)', ''),
(6, 'mc', 'Iron with Folic Acid Intake', ''),
(7, 'epi', 'BCG Immunization', ''),
(8, 'epi', 'DPT 1 Immunization', ''),
(9, 'epi', 'DPT 2 Immunization', ''),
(10, 'epi', 'DPT 3 Immunization', ''),
(11, 'epi', 'OPV 1 Immunization', ''),
(12, 'epi', 'OPV 2 Immunization', ''),
(13, 'epi', 'OPV 3 Immunization', ''),
(14, 'epi', 'Hepa B1 Immunization', ''),
(15, 'epi', 'Hepa B2 Immunization', ''),
(16, 'epi', 'Hepa B3 Immunization', ''),
(17, 'epi', 'Measles Immunization', ''),
(18, 'epi', 'Fully Immunized Child', ''),
(19, 'epi', 'Completely Immunized Child', ''),
(20, 'sick', 'Vitamin A Supplementation', ''),
(21, 'sick', 'Diarrhea Case for 6-11 and 12-72', ''),
(22, 'fp', 'Pill Intake Follow-Up', ''),
(23, 'fp', 'Condom Replenishment Follow-Up', ''),
(24, 'fp', 'IUD Follow-Up', ''),
(25, 'fp', 'Injectables Follow-Up', ''),
(26, 'fp', 'Pills Dropout Alert', ''),
(27, 'fp', 'Condom Dropout Alert', ''),
(28, 'fp', 'IUD Dropout Alert', ''),
(29, 'fp', 'Injectables Dropout Alert', ''),
(30, 'fp', 'Female Sterilization Dropout Alert', ''),
(31, 'fp', 'Male Sterilization Dropout Alert', ''),
(32, 'fp', 'NFP LAM Dropout Alert', ''),
(33, 'philhealth', 'PhilHealth Eligible Members', ''),
(34, 'tb', 'TB symptomatic for DSSM exam', ''),
(35, 'tb', 'TB positive patient for DSSM (end of 2nd month)', ''),
(36, 'tb', 'TB positive patient for DSSM (end of 4th month)', ''),
(37, 'tb', 'TB positive patient for DSSM (start of 6 month)', ''),
(38, 'tb', 'TB treatment completion', ''),
(39, 'tb', 'TB results confirmation (after 1 year cohort)', ''),
(40, 'mc', 'Post trimester alert', ''),
(41, 'epi', 'Pentavalent Vaccine 1', ''),
(42, 'epi', 'Pentavalent Vaccine 2', ''),
(43, 'epi', 'Pentavalent Vaccine 3', ''),
(44, 'epi', 'MMR', ''),
(45, 'epi', 'Rotavirus 1', ''),
(46, 'epi', 'Rotavirus 2', ''),
(48, 'appointment', 'General Consultation', ''),
(49, 'basic', 'Basic Statistics', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
