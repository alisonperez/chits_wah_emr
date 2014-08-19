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
-- Table structure for table `m_lib_pcb_drugs`
--

CREATE TABLE IF NOT EXISTS `m_lib_pcb_drugs` (
  `record_id` float NOT NULL AUTO_INCREMENT,
  `cat_id` varchar(20) NOT NULL,
  `generic_name` varchar(100) NOT NULL,
  `dosage_id` float NOT NULL,
  `form_id` float NOT NULL,
  PRIMARY KEY (`record_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=75 ;

--
-- Dumping data for table `m_lib_pcb_drugs`
--

INSERT INTO `m_lib_pcb_drugs` (`record_id`, `cat_id`, `generic_name`, `dosage_id`, `form_id`) VALUES
(1, 'ANALG', 'Paracetamol', 0, 0),
(2, 'ANALG', 'Tramadol', 0, 0),
(3, 'ANTIINFLA', 'Celecoxib', 0, 0),
(4, 'ANTIINFLA', 'Diclofenac Sodium', 0, 0),
(5, 'ANTIINFLA', 'Mefenamic Acid', 0, 0),
(6, 'ANTIINFLA', 'Naproxen', 0, 0),
(7, 'ANTIBAC', 'Amoxicillin', 0, 0),
(8, 'ANTIBAC', 'Cefalexin', 0, 0),
(9, 'ANTIBAC', 'Ceftazidime', 0, 0),
(10, 'ANTIBAC', 'Ceftriaxone', 0, 0),
(11, 'ANTIBAC', 'Cefuroxime', 0, 0),
(12, 'ANTIBAC', 'Clarithromycin', 0, 0),
(13, 'ANTIBAC', 'Clindamycin', 0, 0),
(14, 'ANTIBAC', 'Co-Amoxiclav', 0, 0),
(15, 'ANTIBAC', 'Erythromycin', 0, 0),
(16, 'ANTIBAC', 'Metronidazole', 0, 0),
(17, 'ANTIBAC', 'Ofloxacin', 0, 0),
(18, 'ANTIFUN', 'Fluconazole', 0, 0),
(19, 'ANTIPARA', 'Mebendazole', 0, 0),
(20, 'ANTIVIR', 'Aciclovir', 0, 0),
(21, 'ACE', 'Captopril', 0, 0),
(22, 'ACE', 'Enalapril', 0, 0),
(23, 'BETABLO', 'Atenolol', 0, 0),
(24, 'BETABLO', 'Metoprolol', 0, 0),
(25, 'CALBLO', 'Amlodipine', 0, 0),
(26, 'CALBLO', 'Felodipine', 0, 0),
(27, 'ARBS', 'Losartan', 0, 0),
(28, 'ARBS', 'Losartan + Hydrochlorothiazide', 0, 0),
(29, 'ANTITHROM', 'Aspirin', 0, 0),
(30, 'DIURE', 'Furosemide', 0, 0),
(31, 'HYPERCHOLES', 'Simvastatin', 0, 0),
(32, 'ORALHYPO', 'Glibenclamide', 0, 0),
(33, 'ORALHYPO', 'Metformin', 0, 0),
(34, 'ANTIFIB', 'Tranexamic Acid', 0, 0),
(35, 'BRONCHO', 'Lagundi', 0, 0),
(36, 'BRONCHO', 'Salbutamol', 0, 0),
(37, 'CORTICOS', 'Prednisone', 0, 0),
(38, 'ANTICONVUL', 'Carbamazepine', 0, 0),
(39, 'ANTIHISTA', 'Cetirizine', 0, 0),
(40, 'ANTIHISTA', 'Chlorphenamine', 0, 0),
(41, 'ANTIHISTA', 'Diphenhydramine', 0, 0),
(42, 'ANTIHISTA', 'Loratadine', 0, 0),
(43, 'ANTIVER', 'Cinnarizine', 0, 0),
(44, 'ANTIGO', 'Allopurinol', 0, 0),
(45, 'ANTICHOLI', 'Dicycloverine', 0, 0),
(46, 'ANTICHOLI', 'Hyoscine', 0, 0),
(47, 'ANTIMOT', 'Loperamide', 0, 0),
(48, 'ANTIPEP', 'Aluminum Hydroxide + Magnesium Hydroxide', 0, 0),
(49, 'ANTIPEP', 'Omeprazole', 0, 0),
(50, 'ANTIPEP', 'Ranitidine', 0, 0),
(51, 'VIT', 'Ascorbic Acid', 0, 0),
(52, 'VIT', 'Multivitamins', 0, 0),
(53, 'VIT', 'Vitamin B1 B6 B12', 0, 0),
(54, 'REHYDR', 'Oral Rehydration Salts', 0, 0),
(55, 'ANTINEO', 'Bleomycin', 0, 0),
(56, 'ANTINEO', 'Carboplatin', 0, 0),
(57, 'ANTINEO', 'Cisplatin', 0, 0),
(58, 'ANTINEO', 'Cyclophosphamide', 0, 0),
(59, 'ANTINEO', 'Cytarabine', 0, 0),
(60, 'ANTINEO', 'Dactinomycin Powder', 0, 0),
(61, 'ANTINEO', 'Docetaxel', 0, 0),
(62, 'ANTINEO', 'Doxorubicin', 0, 0),
(63, 'ANTINEO', 'Etoposide', 0, 0),
(64, 'ANTINEO', 'Filgrastim', 0, 0),
(65, 'ANTINEO', 'Flourouracil', 0, 0),
(66, 'ANTINEO', 'Ifosfamide', 0, 0),
(67, 'ANTINEO', 'L-asparaginase', 0, 0),
(68, 'ANTINEO', 'Methotrexate', 0, 0),
(69, 'ANTINEO', 'Tamoxifen', 0, 0),
(70, 'ANTINEO', 'Vinblastine', 0, 0),
(71, 'ANTINEO', 'Vincristine', 0, 0),
(72, 'ANTINEO', 'Calcium Folinate', 0, 0),
(73, 'ANTINEO', 'Mesna', 0, 0),
(74, 'ANTINEO', 'Ondansetron', 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
