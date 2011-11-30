-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 07, 2011 at 11:12 AM
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

-- --------------------------------------------------------

--
-- Table structure for table 'commands_given_tbl'
--

CREATE TABLE commands_given_tbl (
  command varchar(255) NOT NULL DEFAULT '',
  file_use_id varchar(255) NOT NULL DEFAULT '',
  command_time time NOT NULL DEFAULT '00:00:00',
  PRIMARY KEY (file_use_id,command_time)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'files_tbl'
--

CREATE TABLE files_tbl (
  file_key int(11) NOT NULL AUTO_INCREMENT,
  file_date date NOT NULL,
  file_author varchar(255) NOT NULL DEFAULT 'guest',
  file_name varchar(255) NOT NULL DEFAULT '',
  file_path varchar(255) NOT NULL DEFAULT 'samples',
  file_description text NOT NULL,
  file_active tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (file_key),
  UNIQUE KEY file_name (file_name,file_path)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'file_rating_tbl'
--

CREATE TABLE file_rating_tbl (
  file_id int(11) NOT NULL,
  user_id varchar(255) NOT NULL,
  rate int(1) NOT NULL,
  PRIMARY KEY (file_id,user_id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'file_tags_tbl'
--

CREATE TABLE file_tags_tbl (
  file_id int(11) NOT NULL DEFAULT '0',
  tag_id int(11) NOT NULL DEFAULT '0',
  file_tagger varchar(255) NOT NULL DEFAULT 'guest',
  PRIMARY KEY (file_id,tag_id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'file_uses_tbl'
--

CREATE TABLE file_uses_tbl (
  file_id int(11) NOT NULL DEFAULT '0',
  file_use_key varchar(255) NOT NULL DEFAULT '',
  session_id varchar(255) NOT NULL DEFAULT '',
  file_use_edit varchar(3) NOT NULL DEFAULT 'off',
  file_use_time time NOT NULL DEFAULT '00:00:00',
  PRIMARY KEY (file_use_key)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'sessions_tbl'
--

CREATE TABLE sessions_tbl (
  session_key varchar(255) NOT NULL DEFAULT '',
  session_date date NOT NULL DEFAULT '2000-01-01',
  session_time time NOT NULL DEFAULT '00:00:00',
  session_user varchar(255) NOT NULL DEFAULT 'guest',
  client_ip varchar(255) NOT NULL DEFAULT '',
  client_host_name varchar(255) NOT NULL DEFAULT '',
  client_description varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (session_key)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'tags_tbl'
--

CREATE TABLE tags_tbl (
  tags_key int(11) NOT NULL AUTO_INCREMENT,
  tag_name varchar(255) NOT NULL DEFAULT '',
  tag_requests int(11) NOT NULL DEFAULT '0',
  tag_creation_date date NOT NULL DEFAULT '2000-01-01',
  tag_author varchar(255) NOT NULL DEFAULT 'guest',
  PRIMARY KEY (tags_key),
  UNIQUE KEY tag_name (tag_name)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'users_tbl'
--

CREATE TABLE users_tbl (
  country varchar(255) NOT NULL DEFAULT '',
  e_mail varchar(255) NOT NULL DEFAULT '',
  first_name varchar(255) NOT NULL DEFAULT '',
  last_name varchar(255) NOT NULL DEFAULT '',
  pwd varchar(255) DEFAULT NULL,
  user_name varchar(255) NOT NULL DEFAULT '',
  user_level int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (user_name)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
