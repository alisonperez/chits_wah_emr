-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 31, 2014 at 11:19 AM
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
-- Table structure for table `m_lib_pe`
--

CREATE TABLE IF NOT EXISTS `m_lib_pe` (
  `category_type` varchar(20) NOT NULL,
  `category_code` varchar(20) NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_lib_pe`
--

INSERT INTO `m_lib_pe` (`category_type`, `category_code`, `category_name`) VALUES
('Skin', 'Skin01', 'Pallor'),
('Skin', 'Skin02', 'Rashes'),
('Skin', 'Skin03', 'Jaundice'),
('Skin', 'Skin04', 'Good SkinTurgor'),
('HEENT', 'HEENT01', 'Anicteric Sclerae'),
('HEENT', 'HEENT02', 'Pupils Briskly Reactive To Light'),
('HEENT', 'HEENT03', 'Aural Discharge'),
('HEENT', 'HEENT04', 'Intact Tympanic Membrane'),
('HEENT', 'HEENT05', 'Alar Flaring'),
('HEENT', 'HEENT06', 'Nasal Discharge'),
('HEENT', 'HEENT07', 'Tonsillopharyngeal Congestion'),
('HEENT', 'HEENT08', 'Hypertrophic Tonsils'),
('HEENT', 'HEENT09', 'Palpable Mass'),
('HEENT', 'HEENT10', 'Exudates'),
('Chest/Lungs', 'Chest/Lungs01', 'Symmetrical Chest Expansion'),
('Chest/Lungs', 'Chest/Lungs02', 'Clear Breathsounds'),
('Chest/Lungs', 'Chest/Lungs03', 'Reactions'),
('Chest/Lungs', 'Chest/Lungs04', 'Crackles/Rales'),
('Chest/Lungs', 'Chest/Lungs05', 'Wheezes'),
('Heart', 'Heart01', 'Adynamic Precordium'),
('Heart', 'Heart02', 'Normal Rate Regular Rhythm'),
('Heart', 'Heart03', 'Heaves/Thrills'),
('Heart', 'Heart04', 'Murmurs'),
('Abdomen', 'Abdomen01', 'Flat'),
('Abdomen', 'Abdomen02', 'Globular'),
('Abdomen', 'Abdomen03', 'Flabby'),
('Abdomen', 'Abdomen04', 'Muscle Guarding'),
('Abdomen', 'Abdomen05', 'Tenderness'),
('Abdomen', 'Abdomen06', 'Palpable Mass'),
('Extremities', 'Extremities01', 'Gross Deformity'),
('Extremities', 'Extremities02', 'Normal Gait'),
('Extremities', 'Extremities03', 'Full and Equal Pulses');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
