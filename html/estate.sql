-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 21, 2014 at 12:15 PM
-- Server version: 5.5.37-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `estate`
--

-- --------------------------------------------------------

--
-- Table structure for table `agencies`
--

CREATE TABLE IF NOT EXISTS `agencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `photo` varchar(255) COLLATE utf8_bin NOT NULL,
  `brief` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `photoId` (`photo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dumping data for table `agencies`
--

INSERT INTO `agencies` (`id`, `name`, `photo`, `brief`) VALUES
(1, 'fereira', 'http://img01.imovelweb.com.br//logos/755474_imovelweblogo.jpg', 'a brief offereira');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE IF NOT EXISTS `faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) COLLATE utf8_bin NOT NULL,
  `answer` text COLLATE utf8_bin NOT NULL,
  `nice` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `un` (`nice`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=15 ;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `nice`) VALUES
(1, 'sample question', 'sample answer', 12),
(2, 'sample q2', 'sample a2Другото му задължение изобщо не беше шутовско. „Дък си има неговия меч, аз — перото и пергамента.“ Гриф му беше заповядал да изреди всичко, което знае за дракони. Задачата беше почти непосилна, но Тирион се трудеше над нея всеки ден и драскаше най-старателно с перото. ', 32),
(4, 'In this tutorial, we will see', 'In this tutorial, we will see the "recommended" way to pick objects in a classical game engine - which might not be your case. The idea is that the game engine will need a physics engine anyway, and all physics engine have functions to intersect a ray with the scene.', 0),
(5, 'questmore', 'This iPhone tutorial is built with old version (2.x) of SDK. Simulator library contains some unused functions but this blocks execution on Simulator. So running in Simulator will fire some error related thread functions. Because there are a lot of changes after the tutorial made, you can''t run tutorial even with recent build of Newton. Anyway, you can run the tutorial on Device without any problem. I confirmed this compiled and worked with iOS SDK 4.3 / Xcode4 for Device. Of course, you need iOS developer license to run on Device. ', 11),
(13, 'cmdq', 'cmdVal', 130),
(14, 'adfasdfasdfasd', 'asdfasdf asdfjhaskdjfhf asdfhkfjasdh jsdfakfhsad', 13);

-- --------------------------------------------------------

--
-- Table structure for table `offer`
--

CREATE TABLE IF NOT EXISTS `offer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agencyId` int(11) NOT NULL,
  `frontPhotoId` int(11) NOT NULL,
  `area` int(11) NOT NULL,
  `rooms` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `ansoon` int(11) NOT NULL,
  `brief` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4 ;

--
-- Dumping data for table `offer`
--

INSERT INTO `offer` (`id`, `agencyId`, `frontPhotoId`, `area`, `rooms`, `price`, `type`, `ansoon`, `brief`) VALUES
(1, 1, 1, 25, 5, 25000, 4, 1, ''),
(2, 1, 1, 25, 1, 500000, 2, 0, 'California, prepare nerd for invasion: BlizzCon is taking over the Anaheim Convention Center again November 7 and 8!  Should be an epic time my friends!  We’re really looking forward to yet another amazing year at BlizzCon with all of you! Tickets go on sale in two batch'),
(3, 1, 25, 120, 4, 40000, 4, 0, 'As you can see we have selected everything from the Customers (first table). For all rows from Customers, which don’t have a match in the Sales (second table), the SalesPerCustomer column has amount NULL (NULL means a column contains nothing).');

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE IF NOT EXISTS `photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ofr_id` int(11) NOT NULL,
  `path` varchar(255) COLLATE utf8_bin NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ofr_id` (`ofr_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dumping data for table `photos`
--

INSERT INTO `photos` (`id`, `ofr_id`, `path`, `type`) VALUES
(1, 0, 'http://www.aceshowbiz.com/images/news/casper-smart-confirms-jennifer-lopez-will-return-to-american-idol.jpg', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
