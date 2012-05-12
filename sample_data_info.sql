-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 11, 2012 at 01:27 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `glocal6_data`
--

-- --------------------------------------------------------

--
-- Table structure for table `source`
--

CREATE TABLE IF NOT EXISTS `source` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '#db_NoDisplay ',
  `name` varchar(100) NOT NULL COMMENT 'Source Name',
  `short_name` varchar(25) DEFAULT NULL COMMENT '#db_NoDisplay ',
  `website` varchar(255) NOT NULL COMMENT 'Data Website',
  `title` varchar(255) NOT NULL COMMENT 'Data Title',
  `pub_date` varchar(10) DEFAULT NULL COMMENT '#db_NoDisplay ',
  `data_date` year(4) DEFAULT NULL COMMENT 'Date of data',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `source`
--

INSERT INTO `source` (`id`, `name`, `short_name`, `website`, `title`, `pub_date`, `data_date`) VALUES
(1, 'Center For Disease Control', 'CDC', 'http://www.cdc.gov', 'Deaths: Final Data for 2006', '0000-00-00', 2006),
(2, 'U.S. Environmental Protection Agency', 'EPA', 'http://www.epa.gov/osw/nonhaz/municipal/msw99.htm', 'Waste Generation, Recycling, and Disposal in the United States', '2011', 2010);

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE IF NOT EXISTS `tables` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `brief` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `date` date NOT NULL,
  `source` smallint(6) NOT NULL,
  `author` varchar(100) NOT NULL,
  `key` varchar(100) NOT NULL,
  `helper` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`id`, `name`, `brief`, `description`, `date`, `source`, `author`, `key`, `helper`) VALUES
(1, 'us_codes_cause', '', 'US mortality detailed causes', '2006-12-31', 1, 'admin', 'id', 1),
(2, 'us_codes_state', '', 'US states names and codes', '2006-12-31', 1, 'admin', 'id', 1),
(3, 'us_mortality_detail', 'US Mortality by detailed cause', 'US deaths by total, rate and age adjusted for states, causes or year.', '2006-12-31', 1, 'admin', 'id', 0),
(4, 'waste_total', 'Waste Generation, Recycling, and Disposal in the United States', 'Waste Generation, Recycling, and Disposal in the United States from EPA has Tables  for 2010', '2011-12-01', 2, 'admin', 'id', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
