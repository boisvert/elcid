-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 06, 2011 at 09:15 AM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: 'elcid_test'
--

--
-- Dumping data for table 'commands_given_tbl'
--


--
-- Dumping data for table 'files_tbl'
--

INSERT INTO files_tbl (file_key, file_date, file_author, file_name, file_path, file_description, file_active) VALUES
(1, '2011-05-05', 'samples', 'cut_down_rollover_example', 'users/samples', '', 1);

--
-- Dumping data for table 'file_rating_tbl'
--

INSERT INTO file_rating_tbl (file_id, user_id, rate) VALUES
(1, 'samples', 0);

--
-- Dumping data for table 'file_tags_tbl'
--

INSERT INTO file_tags_tbl (file_id, tag_id, file_tagger) VALUES
(1, 1, 'samples'),
(1, 2, 'samples'),
(1, 3, 'samples');

--
-- Dumping data for table 'file_uses_tbl'
--


--
-- Dumping data for table 'sessions_tbl'
--


--
-- Dumping data for table 'tags_tbl'
--

INSERT INTO tags_tbl (tags_key, tag_name, tag_requests, tag_creation_date, tag_author) VALUES
(1, 'javascript', 0, '2000-01-01', 'samples'),
(2, 'images', 0, '2000-01-01', 'samples'),
(3, 'easy', 0, '2000-01-01', 'samples');

--
-- Dumping data for table 'users_tbl'
--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
