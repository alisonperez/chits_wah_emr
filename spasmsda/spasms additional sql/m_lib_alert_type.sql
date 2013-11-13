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
-- Table structure for table `m_lib_alert_type`
--

CREATE TABLE IF NOT EXISTS `m_lib_alert_type` (
  `alert_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` varchar(50) NOT NULL,
  `alert_indicator_id` int(2) NOT NULL,
  `date_pre` int(3) NOT NULL,
  `date_until` int(3) NOT NULL,
  `alert_message` text NOT NULL,
  `alert_action` text NOT NULL,
  `alert_actual_message` text NOT NULL,
  `date_basis` varchar(50) NOT NULL,
  `alert_url_redirect` text NOT NULL,
  `alert_flag_activate` set('Y','N') NOT NULL,
  PRIMARY KEY (`alert_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `m_lib_alert_type`
--

INSERT INTO `m_lib_alert_type` (`alert_id`, `module_id`, `alert_indicator_id`, `date_pre`, `date_until`, `alert_message`, `alert_action`, `alert_actual_message`, `date_basis`, `alert_url_redirect`, `alert_flag_activate`) VALUES
(1, 'mc', 1, 5, 1, 'Si $name ay dapat na magpa-prenatal check bago $date.Puntahan ang midwife sa inyong barangay health center sa umaga. Mahalaga ito para mapangalagaan ang inyong pagbubuntis.', '0', 'Ngayon $date ang huling araw ng kasalukuyang trimester ni $name. Ito ay mahalaga upang masigurado ang ligtas na pagbubuntis. Siguraduhing nakahanda ang birth plan.', '', '', 'Y'),
(22, 'fp', 22, 7, 0, 'Si $name ay dapat na MAGPAKONSULTA sa midwife sa barangay health center isang linggo bago maubos ang kasalukuyang iniinom na PILLS.Mahalaga ang araw-araw na pag inom ng pills para maiwasan ang pagbubuntis.Kumuha o bumili na ng PILLS.', '', 'Sa $date ay dapat ng makabili o makakuha ng bagong supply ng PILLS si $name. Huwag pansinin ang mensaheng ito kung merong nang supply ng PILLS. Mahalaga ang araw-araw na pag inom ng pills para maiwasan ang pagbubuntis.', '', '', 'Y'),
(4, 'epi', 7, 0, 0, '', '', 'Simula $date ay maaari nang magpabakuna ng BCG si $name.Siguraduhing updated ang bakuna upang maiwasan ang pagkakasakit.', '', '', 'Y'),
(2, 'mc', 40, 0, 0, '', '', 'Kung nakaligtaan ni $name na magpa-\r\nprenatal checkup, puntahan agad\r\nang midwife sa inyong barangay health\r\ncenter sa umaga.Ugaliing magpaprenatal\r\ncheckup upang masigurado ang ligtas na\r\npagbubuntis.', '', '', 'Y'),
(5, 'epi', 8, 0, 0, '', '', 'Simula $date ay maaari nang magpabakuna ng DPT 1 si $name.Siguraduhing updated ang bakuna upang maiwasan ang pagkakaroon ng Dipteria,Pertussis at Tetano.Bumisita sa inyong midwife sa barangay health center o rural health center.', '', '', 'Y'),
(3, 'mc', 2, 5, 1, 'Si $name ay malapit nang manganak.Ang expected date of delivery niya ay sa darating na $date.Bisitahin ang midwife para mapag-usapan ang iyong birth plan.', 'Congratulations $name kung ikaw ay naka panganak na.Makipagkita sa iyong midwife para mabigyan nararapat ng postpartum care.', 'Ngayon $date ang EDC ni $name.Maging mapag-matyag sa mga senyales ng panganganak.Ihanda ang mga kinakailangan sa nalalapit na panganganak.', '', '', 'Y'),
(6, 'epi', 14, 0, 0, '', '', 'Simula $date ay maaari nang magpabakuna ng HepaB1 si $name.Siguraduhing updated ang bakuna upang maiwasan ang hepatitis.Bumisita sa inyong midwife sa barangay health center o rural health center.', '', '', 'Y'),
(7, 'epi', 11, 0, 0, '', '', 'Simula $date ay maaari nang mabigyan ng OPV 1 si $name.Siguraduhing makompleto ang OPV para maiwasan ang polio o pagliit ng paa.Bumisita sa inyong midwife sa barangay health center o rural health center.', '', '', 'Y'),
(8, 'epi', 9, 0, 0, '', '', 'Simula $date ay maaari nang magpabakuna ng DPT 2 si $name.Siguraduhing updated ang bakuna upang maiwasan ang pagkakaroon ng Dipteria,Pertussis at Tetano.Bumisita sa inyong midwife sa barangay health center o rural health center.', '', '', 'Y'),
(9, 'epi', 10, 0, 0, '', '', 'Simula $date ay maaari nang magpabakuna ng DPT 3 si $name.Siguraduhing updated ang bakuna upang maiwasan ang pagkakaroon ng Dipteria,Pertussis at Tetano.Bumisita sa inyong midwife sa barangay health center o rural health center.', '', '', 'Y'),
(10, 'epi', 12, 0, 0, '', '', 'Simula $date ay maaari nang mabigyan ng OPV 2 si $name.Siguraduhing makompleto ang OPV para maiwasan ang polio o pagliit ng paa.Bumisita sa inyong midwife sa barangay health center o rural health center.', '', '', 'Y'),
(11, 'epi', 13, 0, 0, '', '', 'Simula $date ay maaari nang mabigyan ng OPV 3 si $name.Siguraduhing makompleto ang OPV para maiwasan ang polio o pagliit ng paa.Bumisita sa inyong midwife sa barangay health center o rural health center.', '', '', 'Y'),
(12, 'epi', 16, 0, 0, '', '', 'Simula $date ay maaari nang magpabakuna ng HepaB3 si $name.Siguraduhing updated ang bakuna upang maiwasan ang hepatitis.Bumisita sa inyong midwife sa barangay health center o rural health center.', '', '', 'Y'),
(13, 'epi', 15, 0, 0, '', '', 'Simula $date ay maaari nang magpabakuna ng HepaB2 si $name.Siguraduhing updated ang bakuna upang maiwasan ang hepatitis.Bumisita sa inyong midwife sa barangay health center o rural health center.', '', '', 'Y'),
(14, 'epi', 17, 0, 0, '', '', 'Simula $date ay maaari nang magpabakuna ng Measles vaccine si $name.Siguraduhing updated ang bakuna upang maiwasan ang pagkakaroon ng tigdas.Bumisita sa inyong midwife sa barangay health center o rural health center.', '', '', 'Y'),
(15, 'epi', 18, 0, 0, '', '', 'Salamat!Kumpleto na ang bakuna ni $name.Pakitago na lang po ang EPI card para sa inyong pansariling record.', '', '', 'Y'),
(16, 'epi', 41, 0, 0, '', '', 'Simula $date ay maaari nang mabigyan ng Penta 1 si $name.Siguraduhing makumpleto ang bakuna sa Penta para maiwasan ang pertusis,dipterya,tetanusat Hepa B.Bumisita sa inyong midwife sa barangay health center o rural health center.', '', '', 'Y'),
(17, 'epi', 42, 0, 0, '', '', 'Simula $date ay maaari nang mabigyan ng Penta 2 si $name.Siguraduhing makumpleto ang bakuna sa Penta para maiwasan ang pertusis,dipterya,tetanusat Hepa B.Bumisita sa inyong midwife sa barangay health center o rural health center.', '', '', 'Y'),
(18, 'epi', 43, 0, 0, '', '', 'Simula $date ay maaari nang mabigyan ng Penta 3 si $name.Siguraduhing makumpleto ang bakuna sa Penta para maiwasan ang pertusis,dipterya,tetanusat Hepa B.Bumisita sa inyong midwife sa barangay health center o rural health center.', '', '', 'Y'),
(19, 'epi', 44, 0, 0, '', '', 'Simula $date ay maaari nang magpabakuna ng MMR si $name.Ito ay magbibigay dagdag proteksyon laban sa tigdas at beke.Bumisita sa inyong midwife sa barangay health center o rural health center.', '', '', 'Y'),
(20, 'epi', 45, 0, 0, '', '', 'Simula $date ay maaari nang magpabakuna ng unang dose ng Rotavirus si $name.Ito ay mahalaga upang makaiwas sa diarrhea o malubhang pagtatae.Bumisita sa inyong midwife sa barangay health center o rural health center.', '', '', 'Y'),
(21, 'epi', 46, 0, 0, '', '', 'Simula $date ay maaari nang magpabakuna ng pangalawang dose ng Rotavirus si $name.Ito ay mahalaga upang makaiwas sa diarrhea o malubhang pagtatae.Bumisita sa inyong midwife sa barangay health center o rural health center.', '', '', 'Y'),
(23, 'fp', 26, 0, 1, '', 'Kung si $name ay hindi nakapag konsulta sa midwife sa  barangay health center,makipagkita kaagad sa midwife para mapag-usapan ang paggamit ng PILLS at iba pang mga kinakailangan para sa pagpaplano ng pamilya.', 'Kung si $name ay hindi nakapag konsulta sa midwife sa  barangay health center,makipagkita kaagad sa midwife para mapag-usapan ang paggamit ng PILLS at iba pang mga kinakailangan para sa pagpaplano ng pamilya.', '', '', 'Y'),
(25, 'fp', 25, 7, 0, 'Si $name ay dapat na MAGPAKONSULTA sa midwife sa barangay health center isang linggo bago ang susunod na schedule ng pag-inject ng DMPA o injectables.Mahalaga ang regular na pagpapa-inject ng DMPA para maiwasan ang pagbubuntis.', '', 'Sa $date ay dapat ng magpa-inject ng DMPA o injectables si $name. Huwag pansinin ang mensaheng ito kung tapos na. Mahalaga ang regular na pagpapa-inject ng DMPA para maiwasan ang pagbubuntis. Mula sa inyong $source,huwag magreply.Message code:$msgcode', '', '', 'Y'),
(27, 'fp', 29, 0, 1, '', 'Kung si $name ay hindi nakapag konsulta sa midwife sa barangay health center,makipagkita sa midwife kaagad para mapag-usapan ang paggamit ng Depo o injectables at iba pang pangangailangan para sa pagpaplano ng pamilya.', 'Kung si $name ay hindi nakapag konsulta sa midwife sa barangay health center,makipagkita sa midwife kaagad para mapag-usapan ang paggamit ng Depo o injectables at iba pang pangangailangan para sa pagpaplano ng pamilya.', '', '', 'Y'),
(28, 'fp', 24, 7, 0, 'Si $name ay dapat na MAGPAKONSULTA sa main health center bago o eksaktong $date upang matingnan ang IUD.Mahalaga ang regular na pagpapa-check ng IUD para matiyak kung ito ay nasa tamang posisyon.', '', 'Kung hindi pa nagpapacheck ng IUD si $name, siguraduhing bumisita sa midwife sa napag-usapang schedule. Mahalaga ang regular na pagpapa-check ng IUD para matiyak kung ito ay nasa tamang posisyon.', '', '', 'Y'),
(29, 'fp', 23, 7, 0, 'Si $name ay pina aalalahanan na bumili o kumuha ng CONDOM kung ang supply ay ubos na. Ang regular na paggamit ng CONDOM ay mahalaga para maiwasan ang pagbubuntis at tamang pagpaplano ng pamilya.', '', 'Si $name ay pina aalalahanan na bumili o kumuha ng CONDOM kung ang supply ay ubos na. Ang regular na paggamit ng CONDOM ay mahalaga para maiwasan ang pagbubuntis.', '', '', 'Y'),
(30, 'philhealth', 33, 0, 0, '', '', 'Maligayang kaarawan $name! Ito ay isang paalala na maaari ka nang magparehistro sa PhilHealth bilang myembro.Tumungo sa pinakamalapit na opisina ng PhilHealth at magdala ng valid ID.', '', '', 'Y');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
