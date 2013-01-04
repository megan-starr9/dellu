-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 31, 2012 at 01:49 AM
-- Server version: 5.5.20
-- PHP Version: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dellu`
--

-- --------------------------------------------------------

--
-- Table structure for table `forum__board`
--

CREATE TABLE IF NOT EXISTS `forum__board` (
  `boardid` int(11) NOT NULL AUTO_INCREMENT,
  `boardname` varchar(50) NOT NULL,
  `boardcat` int(11) NOT NULL,
  `boardaccesslevel` int(11) NOT NULL DEFAULT '0',
  `boardparent` int(11) NOT NULL DEFAULT '0',
  `boarddesc` text NOT NULL,
  PRIMARY KEY (`boardid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `forum__board`
--

INSERT INTO `forum__board` (`boardid`, `boardname`, `boardcat`, `boardaccesslevel`, `boardparent`, `boarddesc`) VALUES
(1, 'Updates', 1, 0, 0, 'Discuss any new site features/events here.'),
(2, 'Site Chatter', 2, 0, 0, 'Just the general site discussion board.'),
(3, 'RL Chatter', 2, 0, 0, 'Real life important to you?  Talk about it here ;).'),
(4, 'MOAR TEST', 2, 0, 2, 'Testing!!!!!!!  Bwahahaha'),
(5, 'Creative Team', 3, 1, 0, 'Board for the creative force!'),
(6, 'Coding', 3, 4, 0, 'Board for the awesome coders <3  Let''s give this a longer title just because we need to see what it looks like!  Teeesting!'),
(7, 'Test', 1, 0, 0, 'Wooooo'),
(8, 'Parent Test', 1, 0, 1, 'Testttting'),
(9, 'Admin Board!', 2, 3, 0, 'Hai'),
(10, 'Aha', 6, 5, 0, 'For some reason this category is hidden >>');

-- --------------------------------------------------------

--
-- Table structure for table `forum__category`
--

CREATE TABLE IF NOT EXISTS `forum__category` (
  `catid` int(11) NOT NULL AUTO_INCREMENT,
  `catname` varchar(50) NOT NULL,
  `cataccesslevel` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `forum__category`
--

INSERT INTO `forum__category` (`catid`, `catname`, `cataccesslevel`) VALUES
(1, 'Featured', 0),
(2, 'General', 0),
(3, 'Staff', 1),
(4, 'Test Category', 0),
(6, 'Owner thingss!', 5);

-- --------------------------------------------------------

--
-- Table structure for table `forum__post`
--

CREATE TABLE IF NOT EXISTS `forum__post` (
  `postid` int(11) NOT NULL AUTO_INCREMENT,
  `postuser` int(11) NOT NULL,
  `posttopic` int(11) NOT NULL,
  `posttext` text NOT NULL,
  `postdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `posteditdate` timestamp NULL DEFAULT NULL,
  `postreported` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`postid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Dumping data for table `forum__post`
--

INSERT INTO `forum__post` (`postid`, `postuser`, `posttopic`, `posttext`, `postdate`, `posteditdate`, `postreported`) VALUES
(1, 1, 1, 'Hi there', '2012-07-01 23:43:44', '0000-00-00 00:00:00', 0),
(3, 1, 1, 'la la la la la', '2012-05-11 19:35:06', NULL, 0),
(4, 2, 2, 'Staff only! (Creative Team)', '2012-05-11 20:50:16', NULL, 0),
(5, 1, 3, '=D', '2012-05-11 21:26:14', '2012-08-19 03:38:35', 0),
(6, 1, 3, 'Yes ma''am!', '2012-05-11 21:26:32', NULL, 0),
(7, 1, 4, 'cuz we awesome ;)', '2012-05-15 17:10:35', NULL, 0),
(10, 1, 1, 'waaaaa', '2012-05-15 18:48:39', NULL, 0),
(13, 1, 3, 'all cases blocked.. maybe >>', '2012-05-15 18:51:46', NULL, 0),
(14, 2, 3, 'Test', '2012-05-28 21:07:21', NULL, 0),
(15, 2, 3, 'Test again... does it leave page?', '2012-05-28 21:07:30', NULL, 0),
(16, 2, 3, '[b]hey[/b]', '2012-06-23 17:48:00', NULL, 0),
(17, 2, 3, '<del><strong>Hello There!!!!  And you are?</strong></del>', '2012-06-23 18:18:06', '2012-08-19 02:56:16', 0),
(18, 2, 3, '[b]<div style="width:30px;background-color:#000">hell0</div>[/b]', '2012-06-23 18:26:23', NULL, 0),
(19, 2, 1, 'Avatars work now!!!!<br>\r\nIsn''t that so flipping exciting!?\r\n<br><br>\r\nEdit:: Very exciting!!!!! YAYAYAYAY', '2012-06-23 20:35:06', NULL, 0),
(21, 2, 2, 'Again checking that this functions correctly!', '2012-06-24 04:23:42', NULL, 0),
(22, 2, 5, 'Hello', '2012-06-24 04:26:48', NULL, 0),
(23, 2, 5, 'Ohai', '2012-06-24 04:26:56', NULL, 0),
(24, 4, 5, 'Wai hello', '2012-06-24 04:27:26', NULL, 0),
(25, 2, 4, 'BAHHHHHHHHH Humbug =D', '2012-07-02 03:10:20', NULL, 0),
(26, 2, 3, 'Post!', '2012-07-03 02:29:11', NULL, 0),
(27, 2, 3, 'And again', '2012-07-03 02:29:18', NULL, 0),
(28, 2, 3, 'and a3', '2012-07-03 02:29:24', NULL, 0),
(29, 2, 3, 'and a 4', '2012-07-03 02:29:42', NULL, 0),
(30, 2, 3, 'and a 5', '2012-07-03 02:29:48', NULL, 0),
(31, 2, 3, 'Test...', '2012-07-03 02:41:52', NULL, 0),
(34, 2, 3, 'test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test ', '2012-07-03 03:01:47', NULL, 0),
(35, 2, 3, 'ohmai', '2012-07-28 04:02:05', NULL, 0),
(37, 2, 7, 'NUUUU', '2012-08-17 04:55:03', NULL, 0),
(39, 2, 9, 'heyyyy', '2012-08-18 22:26:52', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `forum__topic`
--

CREATE TABLE IF NOT EXISTS `forum__topic` (
  `topicid` int(11) NOT NULL AUTO_INCREMENT,
  `topicname` varchar(50) NOT NULL,
  `topicboard` int(11) NOT NULL,
  `topicuser` int(11) NOT NULL,
  `topicdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `topicsticky` tinyint(1) NOT NULL DEFAULT '0',
  `topiclock` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`topicid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `forum__topic`
--

INSERT INTO `forum__topic` (`topicid`, `topicname`, `topicboard`, `topicuser`, `topicdate`, `topicsticky`, `topiclock`) VALUES
(1, 'Testing', 2, 1, '2012-08-18 22:46:32', 0, 0),
(2, 'Staff Check!', 5, 2, '2012-05-11 20:48:35', 0, 0),
(3, 'Newww Topic!', 1, 1, '2012-08-18 22:06:22', 0, 0),
(4, 'Coders unite', 6, 1, '2012-05-15 17:10:32', 1, 0),
(5, 'Heya', 3, 2, '2012-08-18 22:07:10', 0, 0),
(7, '=D', 4, 2, '2012-08-17 04:55:03', 0, 0),
(9, 'OHAI', 6, 2, '2012-08-18 22:26:51', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `item__explore`
--

CREATE TABLE IF NOT EXISTS `item__explore` (
  `itemid` int(11) NOT NULL DEFAULT '1',
  `locationid` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item__list`
--

CREATE TABLE IF NOT EXISTS `item__list` (
  `itemid` int(11) NOT NULL AUTO_INCREMENT,
  `itemname` varchar(50) NOT NULL,
  `itemdesc` text NOT NULL,
  `itemtype` int(11) NOT NULL DEFAULT '0',
  `itemrarity` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `item__list`
--

INSERT INTO `item__list` (`itemid`, `itemname`, `itemdesc`, `itemtype`, `itemrarity`) VALUES
(1, 'Cola', 'ENERGYYYYY', 1, 30),
(6, 'Hey', 'Hi', 1, 300),
(7, 'Wooo', 'AHHHH', 1, 400),
(8, 'Happy Ness', 'Notta', 1, 1000);

-- --------------------------------------------------------

--
-- Table structure for table `item__market`
--

CREATE TABLE IF NOT EXISTS `item__market` (
  `userid` int(11) NOT NULL DEFAULT '1',
  `itemid` int(11) NOT NULL DEFAULT '1',
  `pricesite` int(11) NOT NULL DEFAULT '0',
  `pricereal` int(11) NOT NULL DEFAULT '0',
  `quantity` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item__shop`
--

CREATE TABLE IF NOT EXISTS `item__shop` (
  `shopid` int(11) NOT NULL DEFAULT '1',
  `itemid` int(11) NOT NULL DEFAULT '1',
  `pricesite` int(11) NOT NULL DEFAULT '0',
  `pricereal` int(11) NOT NULL DEFAULT '0',
  `pricebonus` int(11) NOT NULL DEFAULT '0',
  `quantity` int(11) NOT NULL DEFAULT '1',
  `restock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item__shop`
--

INSERT INTO `item__shop` (`shopid`, `itemid`, `pricesite`, `pricereal`, `pricebonus`, `quantity`, `restock`) VALUES
(4, 1, 5, 0, 0, 14, 0),
(4, 7, 0, 50, 0, 2, 0),
(6, 1, 0, 200, 0, 14, 50),
(4, 7, 500000, 0, 0, 100, 100);

-- --------------------------------------------------------

--
-- Table structure for table `item__type`
--

CREATE TABLE IF NOT EXISTS `item__type` (
  `typeid` int(11) NOT NULL AUTO_INCREMENT,
  `typename` varchar(50) NOT NULL,
  `typedesc` text NOT NULL,
  PRIMARY KEY (`typeid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `item__type`
--

INSERT INTO `item__type` (`typeid`, `typename`, `typedesc`) VALUES
(1, 'Energy', 'WAAAAAAAAAAAAAAAA');

-- --------------------------------------------------------

--
-- Table structure for table `item__user`
--

CREATE TABLE IF NOT EXISTS `item__user` (
  `userid` int(11) NOT NULL DEFAULT '1',
  `itemid` int(11) NOT NULL DEFAULT '1',
  `quantity` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item__user`
--

INSERT INTO `item__user` (`userid`, `itemid`, `quantity`) VALUES
(2, 1, 53),
(2, 8, 29),
(2, 8, 29),
(2, 7, 1),
(2, 6, 1),
(2, 8, 29),
(2, 8, 29);

-- --------------------------------------------------------

--
-- Table structure for table `news__comments`
--

CREATE TABLE IF NOT EXISTS `news__comments` (
  `commentid` int(11) NOT NULL AUTO_INCREMENT,
  `commentnews` int(11) NOT NULL,
  `commentpost` text NOT NULL,
  `commentuser` int(11) NOT NULL,
  `commentdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `commentreport` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`commentid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `news__comments`
--

INSERT INTO `news__comments` (`commentid`, `commentnews`, `commentpost`, `commentuser`, `commentdate`, `commentreport`) VALUES
(4, 1, 'Hai', 2, '2012-05-30 01:06:31', 0),
(5, 1, 'Very cool', 2, '2012-05-30 01:07:57', 0),
(6, 1, 'Yep!', 2, '2012-05-30 01:09:53', 0),
(7, 21, 'hi', 2, '2012-09-15 16:19:41', 0),
(8, 21, 'hey', 2, '2012-09-15 16:19:59', 0);

-- --------------------------------------------------------

--
-- Table structure for table `news__update`
--

CREATE TABLE IF NOT EXISTS `news__update` (
  `updateid` int(11) NOT NULL AUTO_INCREMENT,
  `updatetitle` varchar(40) NOT NULL,
  `updatepost` text NOT NULL,
  `updatedate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updateposter` int(11) NOT NULL,
  `updateeditdate` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`updateid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `news__update`
--

INSERT INTO `news__update` (`updateid`, `updatetitle`, `updatepost`, `updatedate`, `updateposter`, `updateeditdate`) VALUES
(1, 'NEWS', 'NEWS NEWS NEWS!\r\n\r\nHoorah!', '2012-05-12 04:17:37', 1, NULL),
(2, 'News', 'It''s working!', '2012-05-30 01:46:20', 2, NULL),
(3, 'Almost....', 'Well, it will be once I order it', '2012-05-30 01:47:17', 2, NULL),
(4, 'One more', 'yay', '2012-05-30 01:48:36', 2, NULL),
(5, 'Woo', 'hi', '2012-05-30 01:51:05', 2, NULL),
(7, 'Hoo', 'hey', '2012-05-30 01:53:38', 2, NULL),
(8, 'Update', 'I just kinda want to see what this looks like long!<br>', '2012-06-22 03:15:48', 2, NULL),
(9, 'Blah', 'Don''t hit enter.  Anyway, lots of text here.  Got Forum styles started and profile style up (sorta!)<br><br>Hopefully soon we will get user administration and shop administration complete!  Gotta get some javascript learned also so awesome things can happen, and gotta get form styles worked out.  Lots to do!<br><br>Perhaps we will be openable before the end of July?!  I really hope so!  If not, I guess we will have to deal, since this summer has gotten a lot busier than I thought it would!<br><br>Ciao! =^.^=', '2012-06-22 03:18:12', 2, NULL),
(10, 'Hello', 'This is gonna post a ton!', '2012-07-03 02:09:43', 2, NULL),
(11, 'hello', 'again!', '2012-07-03 02:10:00', 2, NULL),
(12, 'wa', 'and again!', '2012-07-03 02:10:06', 2, NULL),
(13, 'Page test', '1', '2012-07-03 02:16:03', 2, NULL),
(14, 'Page test', '2', '2012-07-03 02:16:09', 2, NULL),
(15, 'Page test', '3', '2012-07-03 02:16:14', 2, NULL),
(16, 'Page test', '4', '2012-07-03 02:16:19', 2, NULL),
(17, 'Page test', '5', '2012-07-03 02:16:25', 2, NULL),
(18, 'Page test', '6', '2012-07-03 02:16:31', 2, NULL),
(19, 'Page test', '7', '2012-07-03 02:16:35', 2, NULL),
(20, 'Page test', '8', '2012-07-03 02:21:56', 2, NULL),
(21, 'Page test', '9', '2012-07-03 02:22:02', 2, NULL),
(22, 'Page test', '10', '2012-07-03 02:22:07', 2, NULL),
(23, 'Page test', '11', '2012-07-03 02:22:10', 2, NULL),
(24, 'Page test ', '12', '2012-07-03 02:22:16', 2, NULL),
(25, 'Page test', '13', '2012-07-03 02:22:24', 2, NULL),
(26, 'My Turn!', 'I thought I would give an update since you guys are being so fantastically patient!<br><br>\r\nIt might not look like much is going on in the next few days, but believe me, it is!  I am getting things switched over to a much easier programming style (organization ftw) and therefore features will come a whole lot faster once I am done.  I am also getting a lot of the administration features more fine-tuned so that Tala and Cheshire can actually begin running things on here!  <br><br>\r\nAs Tala said below, with classes having started my schedule will be spotty at best, but I will try to post a news occasionally so that you guys all know what is what.  <3333 Loves!!!', '2012-09-15 20:36:40', 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `message` varchar(500) NOT NULL,
  `new` tinyint(1) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `userid`, `message`, `new`, `date`) VALUES
(1, 2, 'You have a new breeding request!', 0, '0000-00-00 00:00:00'),
(2, 4, '2 has sent you a new message! <a href=/dellusionary/community/pm/index/14>View conversation.</a>', 0, '2012-07-28 05:04:52'),
(3, 2, '4 has sent you a response! <a href=/dellusionary/community/pm/index/14>View conversation.</a>', 0, '2012-07-28 05:06:38'),
(4, 2, '2 has sent you a response! <a href=/dellusionary/community/pm/index/9>View conversation.</a>', 0, '2012-07-28 05:19:41'),
(5, 2, '2 has sent you a response! <a href=/dellusionary/community/pm/index/9>View conversation.</a>', 0, '2012-07-28 05:28:15'),
(6, 2, '<a href=/dellusionary/user/index/4>4</a> has sent you a response! <a href=/dellusionary/community/pm/index/14>View conversation.</a>', 0, '2012-07-28 05:52:11'),
(7, 2, '<a href=/dellusionary/user/index/2>2</a> has sent you a new message! <a href=/dellusionary/community/pm/index/15>View conversation.</a>', 0, '2012-07-28 06:05:25'),
(8, 2, '<a href=/dellusionary/user/index/1>Blargh</a> has sent you a new message! <a href=/dellusionary/community/pm/index/16>View conversation.</a>', 0, '2012-08-08 00:16:55'),
(9, 1, 'A new suggestion has been made that may pertain to you! Check the Admin Panel to see!', 0, '2012-08-09 04:39:39'),
(10, 2, 'A new suggestion has been made that may pertain to you! Check the Admin Panel to see!', 0, '2012-08-09 04:39:39'),
(11, 9, 'A new suggestion has been made that may pertain to you! Check the Admin Panel to see!', 0, '2012-08-09 04:39:39'),
(12, 1, 'A new suggestion has been made that may pertain to you! Check the Admin Panel to see!', 0, '2012-08-09 04:40:52'),
(13, 2, 'A new suggestion has been made that may pertain to you! Check the Admin Panel to see!', 0, '2012-08-09 04:40:52'),
(14, 9, 'A new suggestion has been made that may pertain to you! Check the Admin Panel to see!', 0, '2012-08-09 04:40:52'),
(15, 1, 'A new suggestion has been made that may pertain to you! Check the Admin Panel to see!', 0, '2012-08-09 05:00:43'),
(16, 4, 'A new suggestion has been made that may pertain to you! Check the Admin Panel to see!', 1, '2012-08-09 05:00:43');

-- --------------------------------------------------------

--
-- Table structure for table `pets__colorations`
--

CREATE TABLE IF NOT EXISTS `pets__colorations` (
  `colorid` int(11) NOT NULL AUTO_INCREMENT,
  `colorspecies` int(11) NOT NULL DEFAULT '1',
  `colorname` varchar(50) NOT NULL,
  `colordesc` text NOT NULL,
  `colorartist` int(11) NOT NULL,
  `colorrarity` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`colorid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `pets__colorations`
--

INSERT INTO `pets__colorations` (`colorid`, `colorspecies`, `colorname`, `colordesc`, `colorartist`, `colorrarity`) VALUES
(1, 0, 'Unique', 'Your very own custom, one-of-a-kind coloration!', 0, 0),
(2, 5, 'Luna Moth', '', 0, 50),
(3, 3, 'Test', 'ohet', 0, 0),
(4, 5, 'Test', 'lk', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `pets__owned`
--

CREATE TABLE IF NOT EXISTS `pets__owned` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `gender` enum('Male','Female') NOT NULL DEFAULT 'Male',
  `birthdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `owner` int(11) NOT NULL,
  `generation` int(11) NOT NULL DEFAULT '1',
  `species` int(11) NOT NULL DEFAULT '1',
  `coloration` int(11) NOT NULL DEFAULT '1',
  `level` int(11) NOT NULL DEFAULT '0',
  `exp` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `pets__owned`
--

INSERT INTO `pets__owned` (`id`, `name`, `gender`, `birthdate`, `owner`, `generation`, `species`, `coloration`, `level`, `exp`) VALUES
(1, 'Serien', 'Female', '2012-05-09 03:28:32', 2, 1, 1, 1, 0, 0),
(2, 'Aura', 'Female', '2012-07-04 22:07:15', 2, 1, 1, 1, 50, 300000),
(3, 'Test', 'Male', '2012-07-05 00:40:10', 2, 1, 5, 2, 0, 0),
(4, 'Hai', 'Female', '2012-08-14 05:09:57', 2, 1, 5, 2, 0, 0),
(5, 'Unnamed', 'Male', '2012-12-16 02:43:06', 2, 1, 5, 2, 0, 0),
(6, 'Nameless', 'Male', '2012-12-16 02:51:34', 2, 1, 5, 2, 0, 0),
(7, 'HAI', 'Female', '2012-12-16 02:53:21', 2, 1, 5, 2, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `pets__shop`
--

CREATE TABLE IF NOT EXISTS `pets__shop` (
  `petid` int(11) NOT NULL DEFAULT '1',
  `shopid` int(11) NOT NULL DEFAULT '1',
  `pricesite` int(11) NOT NULL DEFAULT '0',
  `pricereal` int(11) NOT NULL DEFAULT '0',
  `pricebonus` int(11) NOT NULL DEFAULT '0',
  `quantity` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pets__shop`
--

INSERT INTO `pets__shop` (`petid`, `shopid`, `pricesite`, `pricereal`, `pricebonus`, `quantity`) VALUES
(2, 1, 0, 20, 0, 0),
(3, 4, 300, 0, 0, 2),
(3, 4, 0, 40, 0, 300),
(4, 2, 0, 600, 0, 12);

-- --------------------------------------------------------

--
-- Table structure for table `pets__species`
--

CREATE TABLE IF NOT EXISTS `pets__species` (
  `speciesid` int(11) NOT NULL AUTO_INCREMENT,
  `speciesname` varchar(20) NOT NULL,
  `speciesdesc` text NOT NULL,
  `speciesrarity` int(11) NOT NULL DEFAULT '0',
  `speciesartist` int(11) NOT NULL,
  PRIMARY KEY (`speciesid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `pets__species`
--

INSERT INTO `pets__species` (`speciesid`, `speciesname`, `speciesdesc`, `speciesrarity`, `speciesartist`) VALUES
(1, 'Custom', 'A custom species.', 0, 0),
(2, 'Wolf', '=D', 30, 0),
(3, 'Tiger', 'Rawr!', 70, 0),
(4, 'Eagle', 'birdy', 30, 0),
(5, 'Butterfly', '', 20, 0),
(6, 'Bug', 'TESTIE', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `pm__convo`
--

CREATE TABLE IF NOT EXISTS `pm__convo` (
  `convoid` int(11) NOT NULL AUTO_INCREMENT,
  `convosubject` varchar(40) NOT NULL,
  `convostarter` int(11) NOT NULL,
  `convoreceiver` int(11) NOT NULL,
  `convodate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`convoid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `pm__convo`
--

INSERT INTO `pm__convo` (`convoid`, `convosubject`, `convostarter`, `convoreceiver`, `convodate`) VALUES
(1, 'Hey', 1, 2, '2012-07-28 04:58:10'),
(6, 'Herro', 1, 4, '0000-00-00 00:00:00'),
(7, 'Sheesh', 1, 2, '0000-00-00 00:00:00'),
(8, 'Test', 1, 5, '0000-00-00 00:00:00'),
(9, 'hi', 2, 2, '2012-07-28 05:28:15'),
(10, 'Date check', 2, 3, '2012-07-28 03:54:04'),
(11, 'Notify check ', 2, 4, '2012-07-28 05:00:48'),
(12, '><', 2, 4, '2012-07-28 05:02:13'),
(13, 'ugh', 2, 4, '2012-07-28 05:03:27'),
(14, 'last try', 2, 4, '2012-07-28 05:52:11'),
(15, 'Hi self', 2, 2, '2012-07-28 06:05:25'),
(16, 'Check', 1, 2, '2012-08-08 00:16:55');

-- --------------------------------------------------------

--
-- Table structure for table `pm__post`
--

CREATE TABLE IF NOT EXISTS `pm__post` (
  `pmid` int(11) NOT NULL AUTO_INCREMENT,
  `pmconvo` int(11) NOT NULL,
  `pmauthor` int(11) NOT NULL,
  `pmtext` text NOT NULL,
  `pmdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `new` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=47 ;

--
-- Dumping data for table `pm__post`
--

INSERT INTO `pm__post` (`pmid`, `pmconvo`, `pmauthor`, `pmtext`, `pmdate`, `new`) VALUES
(1, 1, 1, 'Wai hello!!', '2012-05-20 02:48:15', 0),
(2, 1, 2, 'Yep', '2012-05-20 02:48:15', 0),
(3, 6, 1, 'Woooooooo', '2012-05-20 18:47:43', 0),
(4, 6, 1, 'Hi!', '2012-05-20 19:00:10', 0),
(5, 6, 1, 'Oh mai', '2012-05-20 19:01:39', 0),
(6, 6, 4, 'Why hello there self!', '2012-05-20 19:05:53', 0),
(7, 7, 1, 'Hi', '2012-05-20 20:07:27', 0),
(8, 1, 1, '=P', '2012-05-20 20:09:13', 0),
(9, 7, 1, 'eeeeeee', '2012-05-20 20:09:35', 0),
(11, 8, 1, 'testing duplicate message block on refresh ^^;', '2012-05-20 20:10:26', 0),
(12, 7, 1, 'Bwahaha', '2012-05-20 21:44:13', 0),
(13, 6, 1, 'Another', '2012-05-20 21:58:23', 0),
(14, 6, 1, 'Wooo should disappear!', '2012-05-20 21:58:34', 0),
(15, 8, 1, 'la la la la', '2012-05-20 22:29:58', 0),
(16, 8, 1, 'ooooooo I like this!', '2012-05-20 22:30:04', 0),
(17, 7, 2, 'Merp', '2012-06-22 03:50:09', 0),
(18, 7, 2, 'Hi!', '2012-07-28 03:00:05', 0),
(19, 9, 2, 'hola', '2012-07-28 03:15:50', 1),
(20, 1, 2, 'Testing new message update!!! hehehe', '2012-07-28 03:17:22', 0),
(21, 1, 1, 'Loook, it works!!!!!!  So exciting!', '2012-07-28 03:20:20', 0),
(22, 1, 2, 'Now, does this work?  This should say new for you and not for me!!!!  WOO hehe', '2012-07-28 03:37:41', 0),
(23, 1, 1, 'IT WORKED!', '2012-07-28 03:38:10', 0),
(24, 10, 2, 'Convo date check!', '2012-07-28 03:47:44', 0),
(25, 10, 2, 'And again!', '2012-07-28 03:48:57', 0),
(26, 10, 2, 'one more!?', '2012-07-28 03:50:04', 0),
(27, 10, 2, 'ohai', '2012-07-28 03:50:26', 0),
(28, 10, 2, 'Once more with feeling!', '2012-07-28 03:51:32', 0),
(29, 10, 2, 'SUCCESSSSS', '2012-07-28 03:52:46', 0),
(30, 10, 3, 'You blew up my message! =0\r\n\r\nhehe =P', '2012-07-28 03:54:04', 0),
(31, 1, 2, 'Now does this work? >>', '2012-07-28 04:51:06', 0),
(32, 1, 2, 'Again...', '2012-07-28 04:53:09', 0),
(33, 1, 2, 'Hellllllooooo', '2012-07-28 04:54:19', 0),
(34, 1, 2, 'hiya', '2012-07-28 04:56:27', 0),
(35, 1, 2, 'one more try ><', '2012-07-28 04:58:10', 0),
(36, 0, 2, 'I need notifications! XP', '2012-07-28 04:59:24', 1),
(37, 11, 2, 'HHHHHHHHHH XP', '2012-07-28 05:00:48', 0),
(38, 12, 2, 'This should be working.... pissin me off X,x', '2012-07-28 05:02:13', 0),
(39, 13, 2, '1 more', '2012-07-28 05:03:27', 0),
(40, 14, 2, 'if this doesn''t work I quit... XXXXX[', '2012-07-28 05:04:52', 0),
(41, 14, 4, 'HOMG finally.  quotes.. REALLY?!!! LMAO', '2012-07-28 05:06:38', 0),
(42, 9, 2, 'Talkin to meself!', '2012-07-28 05:19:42', 1),
(43, 9, 2, 'hooooollllaaaaa', '2012-07-28 05:28:15', 1),
(44, 14, 4, 'HEHEHEHEHHEHEHE', '2012-07-28 05:52:11', 0),
(45, 15, 2, 'Hehe', '2012-07-28 06:05:25', 1),
(46, 16, 1, 'Notification should have my name?', '2012-08-08 00:16:55', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ranks`
--

CREATE TABLE IF NOT EXISTS `ranks` (
  `rankid` int(11) NOT NULL,
  `ranktitle` varchar(20) NOT NULL,
  `ranklevel` int(11) NOT NULL,
  `rankpay` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rankid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ranks`
--

INSERT INTO `ranks` (`rankid`, `ranktitle`, `ranklevel`, `rankpay`) VALUES
(1, 'Owner', 5, 5000),
(2, 'Coder', 5, 4000),
(3, 'Head Admin', 5, 0),
(4, 'Administrator', 4, 3000),
(5, 'Head Mod', 3, 0),
(6, 'Moderator', 3, 2000),
(7, 'Head Artist', 2, 0),
(8, 'Staff Artist', 2, 0),
(9, 'Member', 0, 0),
(10, 'Banned', -1, 0),
(11, 'Frozen', -1, 0),
(12, 'Creative Team', 0, 1000);

-- --------------------------------------------------------

--
-- Table structure for table `shop__category`
--

CREATE TABLE IF NOT EXISTS `shop__category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `shop__category`
--

INSERT INTO `shop__category` (`id`, `name`) VALUES
(1, 'Pet'),
(2, 'Seasonal'),
(3, 'General'),
(4, 'Cat!');

-- --------------------------------------------------------

--
-- Table structure for table `shop__list`
--

CREATE TABLE IF NOT EXISTS `shop__list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `category` int(11) NOT NULL DEFAULT '1',
  `description` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `shop__list`
--

INSERT INTO `shop__list` (`id`, `name`, `category`, `description`) VALUES
(1, 'Pet Shop', 1, 'Buying new petssss!'),
(2, '-Site Cash- Shop', 3, 'Items that can be bought for site currency.'),
(3, 'Seasonal Shop', 2, 'Seasonal Items'),
(4, 'Summer!', 2, 'Summer Items!'),
(5, 'Magic Shop', 3, 'SHINY!'),
(6, 'New', 1, 'Hello');

-- --------------------------------------------------------

--
-- Table structure for table `suggestions`
--

CREATE TABLE IF NOT EXISTS `suggestions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `contents` text NOT NULL,
  `staff` int(11) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `suggestions`
--

INSERT INTO `suggestions` (`id`, `user`, `contents`, `staff`, `date`) VALUES
(1, 9, 'i think things need to be better! hehe', 2, '0000-00-00 00:00:00'),
(2, 9, 'Code things fool!', 2, '0000-00-00 00:00:00'),
(3, 9, 'Get on these!', 8, '2012-08-09 05:00:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `displayname` varchar(40) NOT NULL,
  `password` varchar(150) NOT NULL,
  `joindate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `rank` int(11) NOT NULL DEFAULT '0',
  `email` varchar(60) NOT NULL,
  `userbio` text NOT NULL,
  `activepet` int(11) NOT NULL,
  `bits` int(11) NOT NULL DEFAULT '0',
  `bobs` int(11) NOT NULL DEFAULT '0',
  `template` varchar(30) NOT NULL DEFAULT 'default',
  `online` tinyint(1) NOT NULL DEFAULT '0',
  `staffbio` varchar(1000) DEFAULT NULL,
  `recentactivity` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `username`, `displayname`, `password`, `joindate`, `rank`, `email`, `userbio`, `activepet`, `bits`, `bobs`, `template`, `online`, `staffbio`, `recentactivity`) VALUES
(1, 'Owner', 'Blargh', '876cd8975e9f4f8a1d7b79332728edd9', '2012-05-09 03:30:14', 1, 'test@test.com', '', 1, 999, 0, 'default', 0, NULL, '0000-00-00 00:00:00'),
(2, 'Coder', 'Eriensay', 'd5ae923f46d11b6968c20480e91a1a96', '2012-05-20 19:03:05', 2, 'blah blah', 'This is mah profile! Welcome!\r\n<img src=''http://media.tumblr.com/tumblr_ln3wmzVC4m1qacz72.gif''>', 2, 4885, 130, 'default', 1, 'Hey! I handle the programming of this lovely site.  Anything to do with the code comes from me, including bug fixes and feature updates.  Feel free to contact me about any technical issues you may have, and if I don''t respond, gently spam me.  I sometimes get busy!\r\n<br><br>\r\nPlease do keep in mind however that I am working on this site while juggling a full college schedule and four jobs. I will try my best however to make this site as awesome as possible!', '2012-12-16 03:55:55'),
(4, 'Artist', '^.^', '0345c0e63010dcd0d786232905aa85ef', '2012-05-20 19:04:18', 8, '', '', 0, 0, 0, 'default', 0, NULL, '0000-00-00 00:00:00'),
(5, 'Frozen', 'Noes!', '65e5f8567467c0b02f405b58603e8e0b', '2012-05-20 19:04:42', 11, '', '', 0, 0, 0, 'default', 0, NULL, '0000-00-00 00:00:00'),
(6, 'Moderator', 'Moddzzz', 'd63fffc6362f0db9708c3c7dc50c9368', '2012-05-28 04:34:23', 6, 'hiya@hey.com', '', 0, 0, 50, 'default', 0, NULL, '0000-00-00 00:00:00'),
(7, 'Creative', 'ohay', 'd0d0645f52ecf147dd0deecb515c6d0c', '2012-05-20 19:03:43', 12, '', '', 0, 0, 0, 'default', 0, NULL, '0000-00-00 00:00:00'),
(8, 'Oh', 'Yes', '1293c9e89f0a3d3d186be88bf86632ab', '2012-08-09 03:27:15', 10, '', '', 0, 0, 0, 'default', 0, NULL, '0000-00-00 00:00:00'),
(9, 'lucky', 'Luck!', '361597b31fdbfc75585e731b0b107001', '2012-08-09 03:29:46', 2, '', '', 0, 0, 50, 'default', 0, NULL, '0000-00-00 00:00:00');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
