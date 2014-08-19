-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 07, 2014 at 10:40 AM
-- Server version: 5.5.37
-- PHP Version: 5.3.10-1ubuntu3.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `philhealth`
--
CREATE DATABASE `philhealth` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `philhealth`;

-- --------------------------------------------------------

--
-- Table structure for table `m_consult_philhealth_a1`
--

CREATE TABLE IF NOT EXISTS `m_consult_philhealth_a1` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `m_consult_philhealth_a2`
--

CREATE TABLE IF NOT EXISTS `m_consult_philhealth_a2` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `hf_id` varchar(30) NOT NULL,
  `patient_id` int(6) NOT NULL,
  `pcgc_municipality` varchar(30) NOT NULL,
  `pcgc_prov` varchar(30) NOT NULL,
  `psgc_region` varchar(30) NOT NULL,
  `hf_philhealth_id` varchar(30) NOT NULL,
  `start_month` set('1','2','3','4','5','6','7','8','9','10','11','12') NOT NULL,
  `end_month` set('1','2','3','4','5','6','7','8','9','10','11','12') NOT NULL,
  `year` year(4) NOT NULL,
  `tbl1_sp-nhts` int(10) DEFAULT NULL,
  `tbl1_sp-lgu` int(10) DEFAULT NULL,
  `tbl1_sp-nga` int(10) DEFAULT NULL,
  `tbl1_sp-private` int(10) DEFAULT NULL,
  `tbl1_ipp-og` int(10) DEFAULT NULL,
  `tbl1_ipp-ofw` int(10) DEFAULT NULL,
  `tbl1_non-phic` int(10) DEFAULT NULL,
  `tbl2_row1_col1` int(10) DEFAULT NULL,
  `tbl2_row1_col2` int(10) DEFAULT NULL,
  `tbl2_row1_col3` int(10) DEFAULT NULL,
  `tbl2_row2_col1` int(10) DEFAULT NULL,
  `tbl2_row2_col2` int(10) DEFAULT NULL,
  `tbl2_row2_col3` int(10) DEFAULT NULL,
  `tbl2_row3_col1` int(10) DEFAULT NULL,
  `tbl2_row3_col2` int(10) DEFAULT NULL,
  `tbl2_row3_col3` int(10) DEFAULT NULL,
  `tbl2_row4_col1` int(10) DEFAULT NULL,
  `tbl2_row4_col2` int(10) DEFAULT NULL,
  `tbl2_row4_col3` int(10) DEFAULT NULL,
  `tbl2_row5_col1` int(10) DEFAULT NULL,
  `tbl2_row5_col2` int(10) DEFAULT NULL,
  `tbl2_row5_col3` int(10) DEFAULT NULL,
  `tbl2_row6_col1` int(10) DEFAULT NULL,
  `tbl2_row6_col2` int(10) DEFAULT NULL,
  `tbl2_row6_col3` int(10) DEFAULT NULL,
  `tbl2_row7_col1` int(10) DEFAULT NULL,
  `tbl2_row7_col2` int(10) DEFAULT NULL,
  `tbl2_row7_col3` int(10) DEFAULT NULL,
  `tbl3_row1_col1` int(10) DEFAULT NULL,
  `tbl3_row1_col2` int(10) DEFAULT NULL,
  `tbl3_row2_col1` int(10) DEFAULT NULL,
  `tbl3_row2_col2` int(10) DEFAULT NULL,
  `tbl4_row1_col1` int(10) DEFAULT NULL,
  `tbl4_row1_col2` int(10) DEFAULT NULL,
  `tbl4_row1_col3` int(10) DEFAULT NULL,
  `tbl4_row1_col4` int(10) DEFAULT NULL,
  `tbl4_row1_col5` int(10) DEFAULT NULL,
  `tbl4_row1_col6` int(10) DEFAULT NULL,
  `tbl4_row2_col1` int(10) DEFAULT NULL,
  `tbl4_row2_col2` int(10) DEFAULT NULL,
  `tbl4_row2_col3` int(10) DEFAULT NULL,
  `tbl4_row2_col4` int(10) DEFAULT NULL,
  `tbl4_row2_col5` int(10) DEFAULT NULL,
  `tbl4_row2_col6` int(10) DEFAULT NULL,
  `tbl4_row3_col1` int(10) DEFAULT NULL,
  `tbl4_row3_col2` int(10) DEFAULT NULL,
  `tbl4_row3_col3` int(10) DEFAULT NULL,
  `tbl4_row3_col4` int(10) DEFAULT NULL,
  `tbl4_row3_col5` int(10) DEFAULT NULL,
  `tbl4_row3_col6` int(10) DEFAULT NULL,
  `tbl4_row4_col1` int(10) DEFAULT NULL,
  `tbl4_row4_col2` int(10) DEFAULT NULL,
  `tbl4_row4_col3` int(10) DEFAULT NULL,
  `tbl4_row4_col4` int(10) DEFAULT NULL,
  `tbl4_row4_col5` int(10) DEFAULT NULL,
  `tbl4_row4_col6` int(10) DEFAULT NULL,
  `tbl4_row5_col1` int(10) DEFAULT NULL,
  `tbl4_row5_col2` int(10) DEFAULT NULL,
  `tbl4_row5_col3` int(10) DEFAULT NULL,
  `tbl4_row5_col4` int(10) DEFAULT NULL,
  `tbl4_row5_col5` int(10) DEFAULT NULL,
  `tbl4_row5_col6` int(10) DEFAULT NULL,
  `tbl5_row1_col1` int(10) DEFAULT NULL,
  `tbl5_row1_col2` int(10) DEFAULT NULL,
  `tbl5_row1_col3` int(10) DEFAULT NULL,
  `tbl5_row1_col4` int(10) DEFAULT NULL,
  `tbl5_row1_col5` int(10) DEFAULT NULL,
  `tbl5_row1_col6` int(10) DEFAULT NULL,
  `tbl5_row1_col7` int(10) DEFAULT NULL,
  `tbl5_row2_col1` int(10) DEFAULT NULL,
  `tbl5_row2_col2` int(10) DEFAULT NULL,
  `tbl5_row2_col3` int(10) DEFAULT NULL,
  `tbl5_row2_col4` int(10) DEFAULT NULL,
  `tbl5_row2_col5` int(10) DEFAULT NULL,
  `tbl5_row2_col6` int(10) DEFAULT NULL,
  `tbl5_row2_col7` int(10) DEFAULT NULL,
  `tbl5_row3_col1` int(10) DEFAULT NULL,
  `tbl5_row3_col2` int(10) DEFAULT NULL,
  `tbl5_row3_col3` int(10) DEFAULT NULL,
  `tbl5_row3_col4` int(10) DEFAULT NULL,
  `tbl5_row3_col5` int(10) DEFAULT NULL,
  `tbl5_row3_col6` int(10) DEFAULT NULL,
  `tbl5_row3_col7` int(10) DEFAULT NULL,
  `tbl5_row4_col1` int(10) DEFAULT NULL,
  `tbl5_row4_col2` int(10) DEFAULT NULL,
  `tbl5_row4_col3` int(10) DEFAULT NULL,
  `tbl5_row4_col4` int(10) DEFAULT NULL,
  `tbl5_row4_col5` int(10) DEFAULT NULL,
  `tbl5_row4_col6` int(10) DEFAULT NULL,
  `tbl5_row4_col7` int(10) DEFAULT NULL,
  `tbl5_row5_col1` int(10) DEFAULT NULL,
  `tbl5_row5_col2` int(10) DEFAULT NULL,
  `tbl5_row5_col3` int(10) DEFAULT NULL,
  `tbl5_row5_col4` int(10) DEFAULT NULL,
  `tbl5_row5_col5` int(10) DEFAULT NULL,
  `tbl5_row5_col6` int(10) DEFAULT NULL,
  `tbl5_row5_col7` int(10) DEFAULT NULL,
  PRIMARY KEY (`record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `m_consult_philhealth_a3`
--

CREATE TABLE IF NOT EXISTS `m_consult_philhealth_a3` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `hf_id` varchar(30) NOT NULL,
  `patient_id` int(6) NOT NULL,
  `pcgc_municipality` varchar(30) NOT NULL,
  `pcgc_prov` varchar(30) NOT NULL,
  `psgc_region` varchar(30) NOT NULL,
  `hf_philhealth_id` varchar(30) NOT NULL,
  `patient_name` text,
  `family_address` text,
  `patient_age` int(3) DEFAULT NULL,
  `patient_gender` set('M','F') DEFAULT NULL,
  `phic_membership` set('M','D','N') DEFAULT NULL,
  `membership_type` set('sp-nhts','sp-nga','sp-lgu','sp-private','ipp-og','ipp-ofw','ipp-voluntary','emp-government','emp-private','lifetime') DEFAULT NULL,
  `tbl1_row1_col1` datetime DEFAULT NULL,
  `tbl1_row1_col2` datetime DEFAULT NULL,
  `tbl1_row1_col3` datetime DEFAULT NULL,
  `tbl1_row1_col4` datetime DEFAULT NULL,
  `tbl1_row2_col1` datetime DEFAULT NULL,
  `tbl1_row2_col2` datetime DEFAULT NULL,
  `tbl1_row2_col3` datetime DEFAULT NULL,
  `tbl1_row2_col4` datetime DEFAULT NULL,
  `tbl1_row3_col1` datetime DEFAULT NULL,
  `tbl1_row3_col2` datetime DEFAULT NULL,
  `tbl1_row3_col3` datetime DEFAULT NULL,
  `tbl1_row3_col4` datetime DEFAULT NULL,
  `tbl1_row4_col1` datetime DEFAULT NULL,
  `tbl1_row4_col2` datetime DEFAULT NULL,
  `tbl1_row4_col3` datetime DEFAULT NULL,
  `tbl1_row4_col4` datetime DEFAULT NULL,
  PRIMARY KEY (`record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `m_consult_philhealth_a3_checkup`
--

CREATE TABLE IF NOT EXISTS `m_consult_philhealth_a3_checkup` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `count` int(6) DEFAULT NULL,
  `date_recorded` datetime DEFAULT NULL,
  `history` text,
  `physical_exam` text,
  `assessment` text,
  `treatment` text,
  PRIMARY KEY (`record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `m_consult_philhealth_a3_deservices`
--

CREATE TABLE IF NOT EXISTS `m_consult_philhealth_a3_deservices` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `count` int(6) DEFAULT NULL,
  `date_recorded` datetime DEFAULT NULL,
  `diagnosis` text,
  `type` text,
  `give` text,
  `referred` text,
  `remarks` text,
  PRIMARY KEY (`record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `m_consult_philhealth_a3_opservices`
--

CREATE TABLE IF NOT EXISTS `m_consult_philhealth_a3_opservices` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `count` int(6) DEFAULT NULL,
  `date_recorded` datetime DEFAULT NULL,
  `diagnosis` text,
  `type` text,
  `remarks` text,
  PRIMARY KEY (`record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `m_consult_philhealth_a3_oservices`
--

CREATE TABLE IF NOT EXISTS `m_consult_philhealth_a3_oservices` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `count` int(6) DEFAULT NULL,
  `date_recorded` datetime DEFAULT NULL,
  `diagnosis` text,
  `type` text,
  `remarks` text,
  PRIMARY KEY (`record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `m_consult_philhealth_a4`
--

CREATE TABLE IF NOT EXISTS `m_consult_philhealth_a4` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `hf_id` varchar(30) NOT NULL,
  `pcgc_municipality` varchar(30) NOT NULL,
  `pcgc_prov` varchar(30) NOT NULL,
  `psgc_region` varchar(30) NOT NULL,
  `hf_philhealth_id` varchar(30) NOT NULL,
  `pcb_participation_id` int(10) DEFAULT NULL,
  `start_month` set('1','2','3','4','5','6','7','8','9','10','11','12') NOT NULL,
  `end_month` set('1','2','3','4','5','6','7','8','9','10','11','12') NOT NULL,
  `year` year(4) NOT NULL,
  `tbl4_row1_col1` int(10) DEFAULT NULL,
  `tbl4_row1_col2` int(10) DEFAULT NULL,
  `tbl4_row2_col1` int(10) DEFAULT NULL,
  `tbl4_row2_col2` int(10) DEFAULT NULL,
  `tbl4_row3_col1` int(10) DEFAULT NULL,
  `tbl4_row3_col2` int(10) DEFAULT NULL,
  `tbl4_row4_col1` int(10) DEFAULT NULL,
  `tbl4_row4_col2` int(10) DEFAULT NULL,
  `tbl5_row1_col1` int(10) DEFAULT NULL,
  `tbl5_row1_col2` int(10) DEFAULT NULL,
  `tbl5_row1_col3` int(10) DEFAULT NULL,
  `tbl5_row2_col1` int(10) DEFAULT NULL,
  `tbl5_row2_col2` int(10) DEFAULT NULL,
  `tbl5_row2_col3` int(10) DEFAULT NULL,
  `tbl5_row3_col1` int(10) DEFAULT NULL,
  `tbl5_row3_col2` int(10) DEFAULT NULL,
  `tbl5_row3_col3` int(10) DEFAULT NULL,
  `tbl6_row1_col1` int(10) DEFAULT NULL,
  `tbl6_row1_col2` int(10) DEFAULT NULL,
  `tbl6_row1_col3` int(10) DEFAULT NULL,
  `tbl6_row1_col4` int(10) DEFAULT NULL,
  `tbl6_row2_col1` int(10) DEFAULT NULL,
  `tbl6_row2_col2` int(10) DEFAULT NULL,
  `tbl6_row2_col3` int(10) DEFAULT NULL,
  `tbl6_row2_col4` int(10) DEFAULT NULL,
  `tbl6_row3_col1` int(10) DEFAULT NULL,
  `tbl6_row3_col2` int(10) DEFAULT NULL,
  `tbl6_row3_col3` int(10) DEFAULT NULL,
  `tbl6_row3_col4` int(10) DEFAULT NULL,
  `tbl6_row4_col1` int(10) DEFAULT NULL,
  `tbl6_row4_col2` int(10) DEFAULT NULL,
  `tbl6_row4_col3` int(10) DEFAULT NULL,
  `tbl6_row4_col4` int(10) DEFAULT NULL,
  `tbl6_row5_col1` int(10) DEFAULT NULL,
  `tbl6_row5_col2` int(10) DEFAULT NULL,
  `tbl6_row5_col3` int(10) DEFAULT NULL,
  `tbl6_row5_col4` int(10) DEFAULT NULL,
  `tbl6_row6_col1` int(10) DEFAULT NULL,
  `tbl6_row6_col2` int(10) DEFAULT NULL,
  `tbl6_row6_col3` int(10) DEFAULT NULL,
  `tbl6_row6_col4` int(10) DEFAULT NULL,
  `tbl6_row7_col1` int(10) DEFAULT NULL,
  `tbl6_row7_col2` int(10) DEFAULT NULL,
  `tbl6_row7_col3` int(10) DEFAULT NULL,
  `tbl6_row7_col4` int(10) DEFAULT NULL,
  `tbl6_row8_col1` int(10) DEFAULT NULL,
  `tbl6_row8_col2` int(10) DEFAULT NULL,
  `tbl6_row8_col3` int(10) DEFAULT NULL,
  `tbl6_row8_col4` int(10) DEFAULT NULL,
  `tbl6_row9_col1` int(10) DEFAULT NULL,
  `tbl6_row9_col2` int(10) DEFAULT NULL,
  `tbl6_row9_col3` int(10) DEFAULT NULL,
  `tbl6_row9_col4` int(10) DEFAULT NULL,
  `tbl6_row10_col1` int(10) DEFAULT NULL,
  `tbl6_row10_col2` int(10) DEFAULT NULL,
  `tbl6_row10_col3` int(10) DEFAULT NULL,
  `tbl6_row10_col4` int(10) DEFAULT NULL,
  `tbl6_row11_col1` int(10) DEFAULT NULL,
  `tbl6_row11_col2` int(10) DEFAULT NULL,
  `tbl6_row11_col3` int(10) DEFAULT NULL,
  `tbl6_row11_col4` int(10) DEFAULT NULL,
  `tbl6_row12_col1` int(10) DEFAULT NULL,
  `tbl6_row12_col2` int(10) DEFAULT NULL,
  `tbl6_row12_col3` int(10) DEFAULT NULL,
  `tbl6_row12_col4` int(10) DEFAULT NULL,
  `tbl6_row13_col1` int(10) DEFAULT NULL,
  `tbl6_row13_col2` int(10) DEFAULT NULL,
  `tbl6_row13_col3` int(10) DEFAULT NULL,
  `tbl6_row13_col4` int(10) DEFAULT NULL,
  `tbl6_row14_col1` int(10) DEFAULT NULL,
  `tbl6_row14_col2` int(10) DEFAULT NULL,
  `tbl6_row14_col3` int(10) DEFAULT NULL,
  `tbl6_row14_col4` int(10) DEFAULT NULL,
  `tbl6_row15_col1` int(10) DEFAULT NULL,
  `tbl6_row15_col2` int(10) DEFAULT NULL,
  `tbl6_row15_col3` int(10) DEFAULT NULL,
  `tbl6_row15_col4` int(10) DEFAULT NULL,
  `tbl6_row16_col1` int(10) DEFAULT NULL,
  `tbl6_row16_col2` int(10) DEFAULT NULL,
  `tbl6_row16_col3` int(10) DEFAULT NULL,
  `tbl6_row16_col4` int(10) DEFAULT NULL,
  `tbl8_row1_col1` int(10) DEFAULT NULL,
  `tbl8_row1_col2` int(10) DEFAULT NULL,
  `tbl8_row2_col1` int(10) DEFAULT NULL,
  `tbl8_row2_col2` int(10) DEFAULT NULL,
  `tbl8_row3_col1` int(10) DEFAULT NULL,
  `tbl8_row3_col2` int(10) DEFAULT NULL,
  `tbl8_row4_col1` int(10) DEFAULT NULL,
  `tbl8_row4_col2` int(10) DEFAULT NULL,
  `tbl8_row5_col1` int(10) DEFAULT NULL,
  `tbl8_row5_col2` int(10) DEFAULT NULL,
  `tbl8_row6_col1` int(10) DEFAULT NULL,
  `tbl8_row6_col2` int(10) DEFAULT NULL,
  `tbl8_row7_col1` int(10) DEFAULT NULL,
  `tbl8_row7_col2` int(10) DEFAULT NULL,
  `tbl8_row8_col1` int(10) DEFAULT NULL,
  `tbl8_row8_col2` int(10) DEFAULT NULL,
  `tbl8_row9_col1` int(10) DEFAULT NULL,
  `tbl8_row9_col2` int(10) DEFAULT NULL,
  `tbl8_row10_col1` int(10) DEFAULT NULL,
  `tbl8_row10_col2` int(10) DEFAULT NULL,
  PRIMARY KEY (`record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `m_consult_philhealth_a4_meds`
--

CREATE TABLE IF NOT EXISTS `m_consult_philhealth_a4_meds` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` set('asthma','age','urti','uti','neb') DEFAULT NULL,
  `membership_type` set('M','D') DEFAULT NULL,
  `count` int(6) DEFAULT NULL,
  `date_recorded` datetime DEFAULT NULL,
  PRIMARY KEY (`record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `m_consult_philhealth_a5`
--

CREATE TABLE IF NOT EXISTS `m_consult_philhealth_a5` (
  `record_id` int(7) NOT NULL AUTO_INCREMENT,
  `patient_id` int(6) NOT NULL,
  `family_id` int(6) NOT NULL,
  `hf_philhealth_id` varchar(30) NOT NULL,
  `date_recorded` datetime NOT NULL,
  `membership_type` set('M','D') NOT NULL,
  `patient_gender` set('M','F') NOT NULL,
  `patient_age` int(3) NOT NULL,
  `diagnosis` text NOT NULL,
  `bg_1` set('Y','N') NOT NULL COMMENT 'Consultation',
  `bg_2` set('Y','N') NOT NULL COMMENT 'Visual Inspection with Acetic Acid',
  `bg_3` set('Y','N') NOT NULL COMMENT 'Regular BP Measurement',
  `bg_4` set('Y','N') NOT NULL COMMENT 'Breastfeeding Program Education',
  `bg_5` set('Y','N') NOT NULL COMMENT 'Periodic Clinical Breast Examination',
  `bg_6` set('Y','N') NOT NULL COMMENT 'Counselling for Lifestyle Modification',
  `bg_7` set('Y','N') NOT NULL COMMENT 'Counselling for Smoking Cessation',
  `bg_8` set('Y','N') NOT NULL COMMENT 'Body Measurements',
  `bg_9` set('Y','N') NOT NULL COMMENT 'Digital Rectal Exam',
  `bg_10` set('Y','N') NOT NULL COMMENT 'CBC',
  `bg_11` set('Y','N') NOT NULL COMMENT 'Urinalysis',
  `bg_12` set('Y','N') NOT NULL COMMENT 'Fecalysis',
  `bg_13` set('Y','N') NOT NULL COMMENT 'Sputum Microscopy',
  `bg_14` set('Y','N') NOT NULL COMMENT 'FBS',
  `bg_15` set('Y','N') NOT NULL COMMENT 'Lipid Profile',
  `bg_16` set('Y','N') NOT NULL COMMENT 'Chest X-Ray',
  `medicines_given` text NOT NULL,
  PRIMARY KEY (`record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `m_lib_philhealth_pcb_indicators`
--

CREATE TABLE IF NOT EXISTS `m_lib_philhealth_pcb_indicators` (
  `indicator_id` int(3) NOT NULL AUTO_INCREMENT,
  `Indicator` text NOT NULL,
  `Category` text NOT NULL,
  `dataSet` text NOT NULL,
  `dataElement` text NOT NULL,
  `categoryOptionCombo` text NOT NULL,
  `db_id` text NOT NULL,
  `table_source` text NOT NULL,
  PRIMARY KEY (`indicator_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=121 ;

--
-- Dumping data for table `m_lib_philhealth_pcb_indicators`
--

INSERT INTO `m_lib_philhealth_pcb_indicators` (`indicator_id`, `Indicator`, `Category`, `dataSet`, `dataElement`, `categoryOptionCombo`, `db_id`, `table_source`) VALUES
(1, 'History Of Diagnosis Of Hypertension', 'Male, Uncategorized, Member', 'bazOE3Zgw8O', 'nkOlqRCq8J9', 'Gb0BGTbfg19', '', 'm_consult_philhealth_a2'),
(2, 'History Of Diagnosis Of Hypertension', 'Male, Uncategorized, Dependent', 'bazOE3Zgw8O', 'nkOlqRCq8J9', 'kmAfodCt6AZ', '', 'm_consult_philhealth_a2'),
(3, 'History Of Diagnosis Of Hypertension', 'Female, Pregnant, Dependent', 'bazOE3Zgw8O', 'nkOlqRCq8J9', 'MUQxq9J7UYH', '', 'm_consult_philhealth_a2'),
(4, 'History Of Diagnosis Of Hypertension', 'Female, Non-Pregnant, Member', 'bazOE3Zgw8O', 'nkOlqRCq8J9', 'RJCP3pTXPpd', '', 'm_consult_philhealth_a2'),
(5, 'History Of Diagnosis Of Hypertension', 'Female, Non-Pregnant, Dependent', 'bazOE3Zgw8O', 'nkOlqRCq8J9', 'tF0Ng9gqkUW', '', 'm_consult_philhealth_a2'),
(6, 'History Of Diagnosis Of Hypertension', 'Female, Pregnant, Member', 'bazOE3Zgw8O', 'nkOlqRCq8J9', 'gfWVfTtytf3', '', 'm_consult_philhealth_a2'),
(7, 'Age - Sex Distribution', '60 Years And Above, Male', 'bazOE3Zgw8O', 'NCq15lhqnuo', 'v27BmfkE9yM', '', 'm_consult_philhealth_a2'),
(8, 'Age - Sex Distribution', '25 - 59 Years, Male', 'bazOE3Zgw8O', 'NCq15lhqnuo', 'jQqJmpVotsc', '', 'm_consult_philhealth_a2'),
(9, 'Age - Sex Distribution', '60 Years And Above, Female', 'bazOE3Zgw8O', 'NCq15lhqnuo', 'vP5gsrG4pZM', '', 'm_consult_philhealth_a2'),
(10, 'Age - Sex Distribution', '25 - 59 Years, Female', 'bazOE3Zgw8O', 'NCq15lhqnuo', 'MNsjmluOX4O', '', 'm_consult_philhealth_a2'),
(11, 'Age - Sex Distribution', '2 - 5 Years, Female', 'bazOE3Zgw8O', 'NCq15lhqnuo', 'KBpuiPCfwl2', '', 'm_consult_philhealth_a2'),
(12, 'Age - Sex Distribution', '16 - 24 Years, Female', 'bazOE3Zgw8O', 'NCq15lhqnuo', 'm4DCDIzvEEs', '', 'm_consult_philhealth_a2'),
(13, 'Age - Sex Distribution', '6 - 15 Years, Male', 'bazOE3Zgw8O', 'NCq15lhqnuo', 'WgqPg1sOB2P', '', 'm_consult_philhealth_a2'),
(14, 'Age - Sex Distribution', '0 - 1 Years, Female', 'bazOE3Zgw8O', 'NCq15lhqnuo', 'YDEW3nm5WC7', '', 'm_consult_philhealth_a2'),
(15, 'Age - Sex Distribution', '6 - 15 Years, Female', 'bazOE3Zgw8O', 'NCq15lhqnuo', 'Stpt6peYojW', '', 'm_consult_philhealth_a2'),
(16, 'Age - Sex Distribution', '0 - 1 Years, Male', 'bazOE3Zgw8O', 'NCq15lhqnuo', 'qYSsxO6u0zf', '', 'm_consult_philhealth_a2'),
(17, 'Age - Sex Distribution', '2 - 5 Years, Male', 'bazOE3Zgw8O', 'NCq15lhqnuo', 'cOxEffR1G4w', '', 'm_consult_philhealth_a2'),
(18, 'Age - Sex Distribution', '16 - 24 Years, Male', 'bazOE3Zgw8O', 'NCq15lhqnuo', 'zQTU2sW2Lo5', '', 'm_consult_philhealth_a2'),
(19, 'Breast Cancer Screening', 'Dependent', 'bazOE3Zgw8O', 'VkZjE6L652o', 'nVh4nHYTIPn', '', 'm_consult_philhealth_a2'),
(20, 'Breast Cancer Screening', 'Member', 'bazOE3Zgw8O', 'VkZjE6L652o', 'jU2qMGkPJ6p', '', 'm_consult_philhealth_a2'),
(21, 'Intake Of Hypertensive Medicine', 'Male, Uncategorized, Member', 'bazOE3Zgw8O', 'LMpAtaSUrMx', 'Gb0BGTbfg19', '', 'm_consult_philhealth_a2'),
(22, 'Intake Of Hypertensive Medicine', 'Male, Uncategorized, Dependent', 'bazOE3Zgw8O', 'LMpAtaSUrMx', 'kmAfodCt6AZ', '', 'm_consult_philhealth_a2'),
(23, 'Intake Of Hypertensive Medicine', 'Female, Pregnant, Dependent', 'bazOE3Zgw8O', 'LMpAtaSUrMx', 'MUQxq9J7UYH', '', 'm_consult_philhealth_a2'),
(24, 'Intake Of Hypertensive Medicine', 'Female, Non-Pregnant, Member', 'bazOE3Zgw8O', 'LMpAtaSUrMx', 'RJCP3pTXPpd', '', 'm_consult_philhealth_a2'),
(25, 'Intake Of Hypertensive Medicine', 'Female, Non-Pregnant, Dependent', 'bazOE3Zgw8O', 'LMpAtaSUrMx', 'tF0Ng9gqkUW', '', 'm_consult_philhealth_a2'),
(26, 'Intake Of Hypertensive Medicine', 'Female, Pregnant, Member', 'bazOE3Zgw8O', 'LMpAtaSUrMx', 'gfWVfTtytf3', '', 'm_consult_philhealth_a2'),
(27, 'Adult With BP > 180/120 MmHg', 'Male, Uncategorized, Member', 'bazOE3Zgw8O', 'omNTBZemMAf', 'Gb0BGTbfg19', '', 'm_consult_philhealth_a2'),
(28, 'Adult With BP > 180/120 MmHg', 'Male, Uncategorized, Dependent', 'bazOE3Zgw8O', 'omNTBZemMAf', 'kmAfodCt6AZ', '', 'm_consult_philhealth_a2'),
(29, 'Adult With BP > 180/120 MmHg', 'Female, Pregnant, Dependent', 'bazOE3Zgw8O', 'omNTBZemMAf', 'MUQxq9J7UYH', '', 'm_consult_philhealth_a2'),
(30, 'Adult With BP > 180/120 MmHg', 'Female, Non-Pregnant, Member', 'bazOE3Zgw8O', 'omNTBZemMAf', 'RJCP3pTXPpd', '', 'm_consult_philhealth_a2'),
(31, 'Adult With BP > 180/120 MmHg', 'Female, Non-Pregnant, Dependent', 'bazOE3Zgw8O', 'omNTBZemMAf', 'tF0Ng9gqkUW', '', 'm_consult_philhealth_a2'),
(32, 'Adult With BP > 180/120 MmHg', 'Female, Pregnant, Member', 'bazOE3Zgw8O', 'omNTBZemMAf', 'gfWVfTtytf3', '', 'm_consult_philhealth_a2'),
(33, 'History Of Diagnosis Of Diabetes', 'Male, Member', 'bazOE3Zgw8O', 'V0mNENVblkQ', 'JBMEkIpAf0I', '', 'm_consult_philhealth_a2'),
(34, 'History Of Diagnosis Of Diabetes', 'Male, Dependent', 'bazOE3Zgw8O', 'V0mNENVblkQ', 'CPfb5EpH7bu', '', 'm_consult_philhealth_a2'),
(35, 'History Of Diagnosis Of Diabetes', 'Female, Member', 'bazOE3Zgw8O', 'V0mNENVblkQ', 'UvGPjTHZ9BC', '', 'm_consult_philhealth_a2'),
(36, 'History Of Diagnosis Of Diabetes', 'Female, Dependent', 'bazOE3Zgw8O', 'V0mNENVblkQ', 'LsNCrxHrs2H', '', 'm_consult_philhealth_a2'),
(37, 'Intake Of Oral Hypoglycemics', 'Male, Member', 'bazOE3Zgw8O', 'Mv8vNVM5neg', 'JBMEkIpAf0I', '', 'm_consult_philhealth_a2'),
(38, 'Intake Of Oral Hypoglycemics', 'Male, Dependent', 'bazOE3Zgw8O', 'Mv8vNVM5neg', 'CPfb5EpH7bu', '', 'm_consult_philhealth_a2'),
(39, 'Intake Of Oral Hypoglycemics', 'Female, Member', 'bazOE3Zgw8O', 'Mv8vNVM5neg', 'UvGPjTHZ9BC', '', 'm_consult_philhealth_a2'),
(40, 'Intake Of Oral Hypoglycemics', 'Female, Dependent', 'bazOE3Zgw8O', 'Mv8vNVM5neg', 'LsNCrxHrs2H', '', 'm_consult_philhealth_a2'),
(41, 'Diabetes Mellitus Cases With Signs And Symptoms Of Polyuria, Polydipsia And Weight Loss', 'Male, Member', 'bazOE3Zgw8O', 'NSOch8Lp4T0', 'JBMEkIpAf0I', '', 'm_consult_philhealth_a2'),
(42, 'Diabetes Mellitus Cases With Signs And Symptoms Of Polyuria, Polydipsia And Weight Loss', 'Male, Dependent', 'bazOE3Zgw8O', 'NSOch8Lp4T0', 'CPfb5EpH7bu', '', 'm_consult_philhealth_a2'),
(43, 'Diabetes Mellitus Cases With Signs And Symptoms Of Polyuria, Polydipsia And Weight Loss', 'Female, Member', 'bazOE3Zgw8O', 'NSOch8Lp4T0', 'UvGPjTHZ9BC', '', 'm_consult_philhealth_a2'),
(44, 'Diabetes Mellitus Cases With Signs And Symptoms Of Polyuria, Polydipsia And Weight Loss', 'Female, Dependent', 'bazOE3Zgw8O', 'NSOch8Lp4T0', 'LsNCrxHrs2H', '', 'm_consult_philhealth_a2'),
(45, 'Cervical Cancer Screening', 'Dependent', 'bazOE3Zgw8O', 'mEbkCXYVNBZ', 'nVh4nHYTIPn', '', 'm_consult_philhealth_a2'),
(46, 'Cervical Cancer Screening', 'Member', 'bazOE3Zgw8O', 'mEbkCXYVNBZ', 'jU2qMGkPJ6p', '', 'm_consult_philhealth_a2'),
(47, 'Adult With BP < 140/90 MmHg ', 'Male, Uncategorized, Member', 'bazOE3Zgw8O', 'yxoqRQbAWQf', 'Gb0BGTbfg19', '', 'm_consult_philhealth_a2'),
(48, 'Adult With BP < 140/90 MmHg ', 'Male, Uncategorized, Dependent', 'bazOE3Zgw8O', 'yxoqRQbAWQf', 'kmAfodCt6AZ', '', 'm_consult_philhealth_a2'),
(49, 'Adult With BP < 140/90 MmHg ', 'Female, Pregnant, Dependent', 'bazOE3Zgw8O', 'yxoqRQbAWQf', 'MUQxq9J7UYH', '', 'm_consult_philhealth_a2'),
(50, 'Adult With BP < 140/90 MmHg ', 'Female, Non-Pregnant, Member', 'bazOE3Zgw8O', 'yxoqRQbAWQf', 'RJCP3pTXPpd', '', 'm_consult_philhealth_a2'),
(51, 'Adult With BP < 140/90 MmHg ', 'Female, Non-Pregnant, Dependent', 'bazOE3Zgw8O', 'yxoqRQbAWQf', 'tF0Ng9gqkUW', '', 'm_consult_philhealth_a2'),
(52, 'Adult With BP < 140/90 MmHg ', 'Female, Pregnant, Member', 'bazOE3Zgw8O', 'yxoqRQbAWQf', 'gfWVfTtytf3', '', 'm_consult_philhealth_a2'),
(53, 'Waist Circumference ( >=80 Cm For Female And <=90 Cm For Male)', 'Male, Member', 'bazOE3Zgw8O', 'dQDiSnU4UtT', 'JBMEkIpAf0I', '', 'm_consult_philhealth_a2'),
(54, 'Waist Circumference ( >=80 Cm For Female And <=90 Cm For Male)', 'Male, Dependent', 'bazOE3Zgw8O', 'dQDiSnU4UtT', 'CPfb5EpH7bu', '', 'm_consult_philhealth_a2'),
(55, 'Waist Circumference ( >=80 Cm For Female And <=90 Cm For Male)', 'Female, Member', 'bazOE3Zgw8O', 'dQDiSnU4UtT', 'UvGPjTHZ9BC', '', 'm_consult_philhealth_a2'),
(56, 'Waist Circumference ( >=80 Cm For Female And <=90 Cm For Male)', 'Female, Dependent', 'bazOE3Zgw8O', 'dQDiSnU4UtT', 'LsNCrxHrs2H', '', 'm_consult_philhealth_a2'),
(57, 'Adult With BP >/= 140/90 But Less Than 180/120 MmHg', 'Male, Uncategorized, Member', 'bazOE3Zgw8O', 'LLPnUoQZH88', 'Gb0BGTbfg19', '', 'm_consult_philhealth_a2'),
(58, 'Adult With BP >/= 140/90 But Less Than 180/120 MmHg', 'Male, Uncategorized, Dependent', 'bazOE3Zgw8O', 'LLPnUoQZH88', 'kmAfodCt6AZ', '', 'm_consult_philhealth_a2'),
(59, 'Adult With BP >/= 140/90 But Less Than 180/120 MmHg', 'Female, Pregnant, Dependent', 'bazOE3Zgw8O', 'LLPnUoQZH88', 'MUQxq9J7UYH', '', 'm_consult_philhealth_a2'),
(60, 'Adult With BP >/= 140/90 But Less Than 180/120 MmHg', 'Female, Non-Pregnant, Member', 'bazOE3Zgw8O', 'LLPnUoQZH88', 'RJCP3pTXPpd', '', 'm_consult_philhealth_a2'),
(61, 'Adult With BP >/= 140/90 But Less Than 180/120 MmHg', 'Female, Non-Pregnant, Dependent', 'bazOE3Zgw8O', 'LLPnUoQZH88', 'tF0Ng9gqkUW', '', 'm_consult_philhealth_a2'),
(62, 'Adult With BP >/= 140/90 But Less Than 180/120 MmHg', 'Female, Pregnant, Member', 'bazOE3Zgw8O', 'LLPnUoQZH88', 'gfWVfTtytf3', '', 'm_consult_philhealth_a2'),
(63, 'Breastfeeding Program Education', 'Given, Dependent', 'oHOA3f6lU1a', 'HAAmlK8a8Kz', 'myfBUBEhsTM', '', 'm_consult_philhealth_a4'),
(64, 'Breastfeeding Program Education', 'Given, Member', 'oHOA3f6lU1a', 'HAAmlK8a8Kz', 'MATQcQq5AV3', '', 'm_consult_philhealth_a4'),
(65, 'Counselling For Lifestyle Modification', 'Given, Dependent', 'oHOA3f6lU1a', 'RTl5mPvpksT', 'myfBUBEhsTM', '', 'm_consult_philhealth_a4'),
(66, 'Counselling For Lifestyle Modification', 'Given, Member', 'oHOA3f6lU1a', 'RTl5mPvpksT', 'MATQcQq5AV3', '', 'm_consult_philhealth_a4'),
(67, 'Counselling For Smoking Cessation', 'Given, Dependent', 'oHOA3f6lU1a', 'PuT0ufKGINQ', 'myfBUBEhsTM', '', 'm_consult_philhealth_a4'),
(68, 'Counselling For Smoking Cessation', 'Given, Member', 'oHOA3f6lU1a', 'PuT0ufKGINQ', 'MATQcQq5AV3', '', 'm_consult_philhealth_a4'),
(69, 'Body Measurements', 'Given, Dependent', 'oHOA3f6lU1a', 'nuIxJr4vLpY', 'myfBUBEhsTM', '', 'm_consult_philhealth_a4'),
(70, 'Body Measurements', 'Given, Member', 'oHOA3f6lU1a', 'nuIxJr4vLpY', 'MATQcQq5AV3', '', 'm_consult_philhealth_a4'),
(71, 'Digital Rectal Examination', 'Given, Dependent', 'oHOA3f6lU1a', 'w1vhtqyBm9P', 'myfBUBEhsTM', '', 'm_consult_philhealth_a4'),
(72, 'Digital Rectal Examination', 'Given, Member', 'oHOA3f6lU1a', 'w1vhtqyBm9P', 'MATQcQq5AV3', '', 'm_consult_philhealth_a4'),
(73, 'Complete Blood Count', 'Given, Dependent', 'oHOA3f6lU1a', 't4G5Vy61EAT', 'myfBUBEhsTM', '', 'm_consult_philhealth_a4'),
(74, 'Complete Blood Count', 'Referred, Member', 'oHOA3f6lU1a', 't4G5Vy61EAT', 'NaXjTi9CCC6', '', 'm_consult_philhealth_a4'),
(75, 'Complete Blood Count', 'Given, Member', 'oHOA3f6lU1a', 't4G5Vy61EAT', 'MATQcQq5AV3', '', 'm_consult_philhealth_a4'),
(76, 'Complete Blood Count', 'Referred, Dependent', 'oHOA3f6lU1a', 't4G5Vy61EAT', 'ieraUcKsaLc', '', 'm_consult_philhealth_a4'),
(77, 'Fecalysis', 'Given, Dependent', 'oHOA3f6lU1a', 'a5P1EQa8K0h', 'myfBUBEhsTM', '', 'm_consult_philhealth_a4'),
(78, 'Fecalysis', 'Referred, Member', 'oHOA3f6lU1a', 'a5P1EQa8K0h', 'NaXjTi9CCC6', '', 'm_consult_philhealth_a4'),
(79, 'Fecalysis', 'Given, Member', 'oHOA3f6lU1a', 'a5P1EQa8K0h', 'MATQcQq5AV3', '', 'm_consult_philhealth_a4'),
(80, 'Fecalysis', 'Referred, Dependent', 'oHOA3f6lU1a', 'a5P1EQa8K0h', 'ieraUcKsaLc', '', 'm_consult_philhealth_a4'),
(81, 'Sputum Microscopy', 'Given, Dependent', 'oHOA3f6lU1a', 'L6QO1We0kZn', 'myfBUBEhsTM', '', 'm_consult_philhealth_a4'),
(82, 'Sputum Microscopy', 'Referred, Member', 'oHOA3f6lU1a', 'L6QO1We0kZn', 'NaXjTi9CCC6', '', 'm_consult_philhealth_a4'),
(83, 'Sputum Microscopy', 'Given, Member', 'oHOA3f6lU1a', 'L6QO1We0kZn', 'MATQcQq5AV3', '', 'm_consult_philhealth_a4'),
(84, 'Sputum Microscopy', 'Referred, Dependent', 'oHOA3f6lU1a', 'L6QO1We0kZn', 'ieraUcKsaLc', '', 'm_consult_philhealth_a4'),
(85, 'Fasting Blood Sugar', 'Given, Dependent', 'oHOA3f6lU1a', 'QEsqit8TEFb', 'myfBUBEhsTM', '', 'm_consult_philhealth_a4'),
(86, 'Fasting Blood Sugar', 'Referred, Member', 'oHOA3f6lU1a', 'QEsqit8TEFb', 'NaXjTi9CCC6', '', 'm_consult_philhealth_a4'),
(87, 'Fasting Blood Sugar', 'Given, Member', 'oHOA3f6lU1a', 'QEsqit8TEFb', 'MATQcQq5AV3', '', 'm_consult_philhealth_a4'),
(88, 'Fasting Blood Sugar', 'Referred, Dependent', 'oHOA3f6lU1a', 'QEsqit8TEFb', 'ieraUcKsaLc', '', 'm_consult_philhealth_a4'),
(89, 'Lipid Profile', 'Given, Dependent', 'oHOA3f6lU1a', 'kR3CKnUI51F', 'myfBUBEhsTM', '', 'm_consult_philhealth_a4'),
(90, 'Lipid Profile', 'Referred, Member', 'oHOA3f6lU1a', 'kR3CKnUI51F', 'NaXjTi9CCC6', '', 'm_consult_philhealth_a4'),
(91, 'Lipid Profile', 'Given, Member', 'oHOA3f6lU1a', 'kR3CKnUI51F', 'MATQcQq5AV3', '', 'm_consult_philhealth_a4'),
(92, 'Lipid Profile', 'Referred, Dependent', 'oHOA3f6lU1a', 'kR3CKnUI51F', 'ieraUcKsaLc', '', 'm_consult_philhealth_a4'),
(93, 'Chest X-Ray', 'Given, Dependent', 'oHOA3f6lU1a', 'Lbet2CYNK36', 'myfBUBEhsTM', '', 'm_consult_philhealth_a4'),
(94, 'Chest X-Ray', 'Referred, Member', 'oHOA3f6lU1a', 'Lbet2CYNK36', 'NaXjTi9CCC6', '', 'm_consult_philhealth_a4'),
(95, 'Chest X-Ray', 'Given, Member', 'oHOA3f6lU1a', 'Lbet2CYNK36', 'MATQcQq5AV3', '', 'm_consult_philhealth_a4'),
(96, 'Chest X-Ray', 'Referred, Dependent', 'oHOA3f6lU1a', 'Lbet2CYNK36', 'ieraUcKsaLc', '', 'm_consult_philhealth_a4'),
(97, 'Members', 'Male', 'oHOA3f6lU1a', 'L98ovMQnYdT', 'HrYigPmbhB0', '', 'm_consult_philhealth_a4'),
(98, 'Members', 'Female', 'oHOA3f6lU1a', 'L98ovMQnYdT', 'cQBIzjRpaWQ', '', 'm_consult_philhealth_a4'),
(99, 'Consultation', 'Given, Dependent', 'oHOA3f6lU1a', 'e53GxHPT4Kh', 'myfBUBEhsTM', '', 'm_consult_philhealth_a4'),
(100, 'Consultation', 'Given, Member', 'oHOA3f6lU1a', 'e53GxHPT4Kh', 'MATQcQq5AV3', '', 'm_consult_philhealth_a4'),
(101, 'Urinalysis', 'Given, Dependent', 'oHOA3f6lU1a', 'nqaYpMZqNDy', 'myfBUBEhsTM', '', 'm_consult_philhealth_a4'),
(102, 'Urinalysis', 'Referred, Member', 'oHOA3f6lU1a', 'nqaYpMZqNDy', 'NaXjTi9CCC6', '', 'm_consult_philhealth_a4'),
(103, 'Urinalysis', 'Given, Member', 'oHOA3f6lU1a', 'nqaYpMZqNDy', 'MATQcQq5AV3', '', 'm_consult_philhealth_a4'),
(104, 'Urinalysis', 'Referred, Dependent', 'oHOA3f6lU1a', 'nqaYpMZqNDy', 'ieraUcKsaLc', '', 'm_consult_philhealth_a4'),
(105, 'Dependents', 'Male', 'oHOA3f6lU1a', 'DBtghZ7PNV4', 'HrYigPmbhB0', '', 'm_consult_philhealth_a4'),
(106, 'Dependents', 'Female', 'oHOA3f6lU1a', 'DBtghZ7PNV4', 'cQBIzjRpaWQ', '', 'm_consult_philhealth_a4'),
(107, 'Periodic Clinical Breast Examination', 'Given, Dependent', 'oHOA3f6lU1a', 'lete0wu7xDK', 'myfBUBEhsTM', '', 'm_consult_philhealth_a4'),
(108, 'Periodic Clinical Breast Examination', 'Given, Member', 'oHOA3f6lU1a', 'lete0wu7xDK', 'MATQcQq5AV3', '', 'm_consult_philhealth_a4'),
(109, 'Visual Inspection With Acetic Acid', 'Given, Dependent', 'oHOA3f6lU1a', 'bkeKrklaWli', 'myfBUBEhsTM', '', 'm_consult_philhealth_a4'),
(110, 'Visual Inspection With Acetic Acid', 'Given, Member', 'oHOA3f6lU1a', 'bkeKrklaWli', 'MATQcQq5AV3', '', 'm_consult_philhealth_a4'),
(111, 'Obligated Target Hypertensive BP Measurement', 'Default', 'oHOA3f6lU1a', 'l7DHoJcQoQS', 'beBubnoFLUo', '', 'm_consult_philhealth_a4'),
(112, 'Regular BP Measurement', 'Given, Dependent', 'oHOA3f6lU1a', 'FdXvwwJgqJg', 'myfBUBEhsTM', '', 'm_consult_philhealth_a4'),
(113, 'Regular BP Measurement', 'Given, Member', 'oHOA3f6lU1a', 'FdXvwwJgqJg', 'MATQcQq5AV3', '', 'm_consult_philhealth_a4'),
(114, 'Obligated Target Periodic Clinical Breast Examination', 'Default', 'oHOA3f6lU1a', 'mNHn69WbUuK', 'beBubnoFLUo', '', 'm_consult_philhealth_a4'),
(115, 'Obligated Target Visual Inspection With Acetic Acid', 'Default', 'oHOA3f6lU1a', 'b1zdv5AOYJ7', 'beBubnoFLUo', '', 'm_consult_philhealth_a4'),
(116, 'Obligated Target Non-Hypertensive BP Measurement', 'Default', 'oHOA3f6lU1a', 'TBUeG5Fk6rQ', 'beBubnoFLUo', '', 'm_consult_philhealth_a4'),
(117, 'Obligated Accomplishment Visual Inspection With Acetic Acid', 'Default', 'oHOA3f6lU1a', 'sppufBew1g2', 'beBubnoFLUo', '', 'm_consult_philhealth_a4'),
(118, 'Obligated Accomplishment Periodic Clinical Breast Examination', 'Default', 'oHOA3f6lU1a', 'paZqkLNSRmi', 'beBubnoFLUo', '', 'm_consult_philhealth_a4'),
(119, 'Obligated Accomplishment Non-Hypertensive BP Measurement', 'Default', 'oHOA3f6lU1a', 'VU2FxpChAGB', 'beBubnoFLUo', '', 'm_consult_philhealth_a4'),
(120, 'Obligated Accomplishment Hypertensive BP Measurement', 'Default', 'oHOA3f6lU1a', 'f2R7BDsMIB4', 'beBubnoFLUo', '', 'm_consult_philhealth_a4');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
