-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2016 at 10:58 AM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spm_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `sc_friends`
--

CREATE TABLE `sc_friends` (
  `u_username` varchar(20) NOT NULL,
  `u_friend` varchar(20) NOT NULL,
  `u_chatlog` text NOT NULL,
  `u_lastu` text NOT NULL,
  `u_lastf` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sc_users`
--

CREATE TABLE `sc_users` (
  `u_name` varchar(20) NOT NULL,
  `u_surname` varchar(20) NOT NULL,
  `u_username` varchar(20) NOT NULL,
  `u_password` varchar(40) NOT NULL,
  `u_address` varchar(50) NOT NULL,
  `u_birthday` date NOT NULL,
  `u_salt` varchar(10) NOT NULL DEFAULT 'swagfodays'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sc_users`
--

INSERT INTO `sc_users` (`u_name`, `u_surname`, `u_username`, `u_password`, `u_address`, `u_birthday`, `u_salt`) VALUES
('bbb', 'bbb', 'bbb', '6381620b46812970ed2810671bddb50e6b725368', 'bbb@bbb.bb', '1992-05-16', 'g1ngsQVXvD'),
('coso', 'cosis', 'coso', 'be8fce58524a377367e4fc197bcf581261dded01', 'coso@uni.it', '1996-08-15', 'Vfv976w1iJ'),
('fff', 'fff', 'fff', '96285040cd9963aeaf9da55fe3d52a89393c30cc', 'ff@fffff.ff', '1992-10-05', 'ze8jV43yon'),
('ggg', 'ggg', 'ggg', '54c0e56318d65b2c5a262bc79882727886d85b67', 'ggg@opop.it', '1992-10-05', 'Ci2zYqLSxz'),
('l', 'l', 'luca', '5ed8120ae3c3f49cbe6eacc726014f71aa2e0fd7', 'luca@maila.it', '1990-02-08', 'rglxc3xzLd'),
('mFriend', 'mFriends', 'marco', 'baf9cf1281b878bae9ded31e3383b6d93fd0206f', 'marco@amicoluca.og', '1987-04-12', 'M19LOgbaWG'),
('asggsad', 'klgakagskjgdsa', 'ttt', '99ebdbd711b0e1854a6c2e93f759efc2af291fd0', 'asfdasf@gmail.com', '1992-12-08', 'swagfodays'),
('adasda', 'asdasd', 'ttt2', '99ebdbd711b0e1854a6c2e93f759efc2af291fd0', 'akjdfsajklfjdfsa', '1990-12-09', 'swagfodays');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sc_users`
--
ALTER TABLE `sc_users`
  ADD PRIMARY KEY (`u_username`),
  ADD UNIQUE KEY `u_address` (`u_address`),
  ADD UNIQUE KEY `u_address_2` (`u_address`),
  ADD UNIQUE KEY `u_username` (`u_username`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
