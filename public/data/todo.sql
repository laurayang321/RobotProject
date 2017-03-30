-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 14, 2017 at 08:40 AM
-- Server version: 5.7.13
-- PHP Version: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `todo`
--

-- --------------------------------------------------------

--
-- Table structure for table `parts`
--

DROP TABLE IF EXISTS `parts`;
CREATE TABLE `parts` (
  `id` varchar(6) NOT NULL PRIMARY KEY,
  `model` varchar(2) DEFAULT NULL,
  `pieceId` int(1) DEFAULT NULL,
  `plant` varchar(10) DEFAULT NULL,
  `stamp` timestamp NOT NULL,

  `partName` varchar(5) DEFAULT NULL,
  `line` varchar(60) DEFAULT NULL,
  `pieceName` varchar(60) DEFAULT NULL,
  `pic` varchar(60) DEFAULT NULL,
  `status` int(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--SELECT * FROM `parts` WHERE 1
--INSERT parts VALUES("259b6c","r",1,"lemon", "2017-03-29 17:15:07", "R1", "household", "top", "r1.jpeg", 1);
--INSERT parts VALUES("447aac","w",2,"lemon", "2017-03-29 17:11:29", "W2", "butler", "torso", "w2.jpeg", 1);
--INSERT parts VALUES("12bbfd","c",3,"lemon", "2017-03-29 17:15:07", "C1", "companion", "bottom", "c3.jpeg", 1);


-- --------------------------------------------------------


--
-- Table structure for table `session`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
        `id` varchar(128) NOT NULL,
        `ip_address` varchar(45) NOT NULL,
        `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
        `data` blob NOT NULL,
        KEY `ci_sessions_timestamp` (`timestamp`)
);
ALTER TABLE ci_sessions ADD PRIMARY KEY (id);


-- --------------------------------------------------------
--
-- Table structure for table `purchasepartsrecords`
--

DROP TABLE IF EXISTS `purchasepartsrecords`;
CREATE TABLE `purchasepartsrecords` (
    `id` int(4) NOT NULL,
    `partonecacode` varchar(8) NOT NULL,
    `parttwocacode` varchar(8) NOT NULL,
    `partthreecacode` varchar(8) NOT NULL,
    `partfourcacode` varchar(8) NOT NULL,
    `partfivecacode` varchar(8) NOT NULL,
    `partsixcacode` varchar(8) NOT NULL,
    `partsevencacode` varchar(8) NOT NULL,
    `parteightcacode` varchar(8) NOT NULL,
    `partninecacode` varchar(8) NOT NULL,
    `parttencacode` varchar(8) NOT NULL,
    `cost` int(4) NOT NULL,
    `datetime` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
--
-- Table structure for table `returnpartrecords`
--

DROP TABLE IF EXISTS `returnpartrecords`;
CREATE TABLE `returnpartrecords` (
    `id` int(4) NOT NULL,
    `partcacode` varchar(8) NOT NULL,
    `earning` int(4) NOT NULL,
    `datetime` timestamp DEFAULT CURRENT_TIMESTAMP
                ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;





















INSERT INTO `tasks` (`id`, `task`, `priority`, `size`, `group`, `deadline`, `status`, `flag`) VALUES
(1, 'COMP1234 assignment', 3, 2, 2, '20170219', '1', ''),
(2, 'Mow the lawn', 2, 2, 1, '', '2', ''),
(3, 'Wash the car', 2, 2, 1, '', '', ''),
(4, 'Paint the fence', 1, 2, 1, '', '', ''),
(5, 'Study for midterms', 3, 3, 2, '', '', ''),
(6, 'Intramural hockey game', 1, 2, 4, '', '', ''),
(7, 'Canucks hockey game', 3, 3, 4, '20170305', '', ''),
(8, 'Buy steel-toed boots', 2, 1, 3, '', '', ''),
(9, 'Learn French', 1, 3, 3, '20161231', '1', ''),
(10, 'Hit the gym', 2, 1, 4, '', '', ''),
(11, 'Pay bills', 3, 1, 1, '', '', ''),
(12, 'Meet George', 2, 1, 1, '', '', ''),
(13, 'Buy milk & bread', 2, 1, 1, '', '', '1'),
(14, 'Read War & Peace', 1, 3, 1, '', '', ''),
(15, 'Organize the study', 1, 4, 1, '', '', '');