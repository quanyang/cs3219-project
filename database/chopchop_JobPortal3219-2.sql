-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 13, 2015 at 05:54 PM
-- Server version: 5.5.42-cll
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+08:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `CVIA`
--

-- --------------------------------------------------------

--
-- Table structure for table `Applicants`
--

CREATE TABLE IF NOT EXISTS `Applicants` (
  `applicantId` int(11) NOT NULL AUTO_INCREMENT,
  `applicantName` varchar(100) NOT NULL,
  `applicantEmail` varchar(150) DEFAULT NULL,
  `applicantContact` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`applicantId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ApplicantScores`
--

CREATE TABLE IF NOT EXISTS `ApplicantScores` (
  `applicantId` int(11) NOT NULL,
  `jobRequirementId` int(11) NOT NULL,
  `scores` decimal(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `JobDescriptions`
--

CREATE TABLE IF NOT EXISTS `JobDescriptions` (
  `jobDescriptionId` int(11) NOT NULL AUTO_INCREMENT,
  `jobDescriptionContent` text NOT NULL,
  PRIMARY KEY (`jobDescriptionId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `JobRequirementDetails`
--

CREATE TABLE IF NOT EXISTS `JobRequirementDetails` (
  `jobRequirementId` int(11) NOT NULL,
  `keyword` varchar(200) NOT NULL,
  `keywordCategory` varchar(200) NOT NULL,
  `weightage` decimal(10,2) NOT NULL,
  `isMinimumRequirement` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `JobRequirements`
--

CREATE TABLE IF NOT EXISTS `JobRequirements` (
  `jobRequirementId` int(11) NOT NULL AUTO_INCREMENT,
  `jobDescriptionId` int(11) NOT NULL,
  `isAvailable` tinyint(1) NOT NULL,
  PRIMARY KEY (`jobRequirementId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
