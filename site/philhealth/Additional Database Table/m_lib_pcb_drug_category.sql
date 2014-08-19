-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 31, 2014 at 11:20 AM
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
-- Table structure for table `m_lib_pcb_drug_category`
--

CREATE TABLE IF NOT EXISTS `m_lib_pcb_drug_category` (
  `cat_id` varchar(20) NOT NULL,
  `cat_name` varchar(100) NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_lib_pcb_drug_category`
--

INSERT INTO `m_lib_pcb_drug_category` (`cat_id`, `cat_name`) VALUES
('ACE', 'ACE Inhibitors'),
('ANALG', 'Analgesics'),
('ANTIBAC', 'Antibacterials'),
('ANTICHOLI', 'Anticholinergics'),
('ANTICONVUL', 'Anticonvulsant/ Antiepileptic'),
('ANTIFIB', 'Anti-Fibrinolytic'),
('ANTIFUN', 'Antifungal'),
('ANTIGO', 'Anti-Gout'),
('ANTIHISTA', 'Antihistamines'),
('ANTIINFLA', 'Anti-Inflammatory'),
('ANTIMOT', 'Antimotility'),
('ANTINEO', 'Antineoplastics and Immunosuppressives'),
('ANTIPARA', 'Antiparasitics'),
('ANTIPEP', 'Antipeptic Ulcer'),
('ANTITHROM', 'Antithrombotics'),
('ANTIVER', 'Anti-Vertigo'),
('ANTIVIR', 'Antiviral'),
('ARBS', 'Angiotensin-2-Receptor Blockers'),
('BETABLO', 'Beta Blockers'),
('BRONCHO', 'Bronchodilators'),
('CALBLO', 'Calcium Channel Blockers'),
('CORTICOS', 'Corticosteroid'),
('DIURE', 'Diuretics'),
('HYPERCHOLES', 'Hypercholesterolemia'),
('ORALHYPO', 'Oral Hypoglycemics'),
('REHYDR', 'Rehydration Solution'),
('VIT', 'Vitamins');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
