-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Generation Time: Dec 26, 2014 at 09:00 PM

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: 'elcid_test'
--

-- --------------------------------------------------------

--
-- Table structure for table 'command'
--

CREATE TABLE command (
  session_id varchar(255) NOT NULL DEFAULT '',
  load_id bigint(20) NOT NULL,
  command_time time NOT NULL DEFAULT '00:00:00',
  command varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (session_id,load_id,command_time),
  KEY load_id (load_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'file'
--

CREATE TABLE file (
  file_id int(11) NOT NULL AUTO_INCREMENT,
  file_date date NOT NULL,
  file_author varchar(255) DEFAULT NULL,
  file_name varchar(255) NOT NULL DEFAULT '',
  file_path varchar(255) NOT NULL DEFAULT 'samples',
  file_description text NOT NULL,
  file_active tinyint(1) NOT NULL DEFAULT '1',
  file_clicks int(11) DEFAULT NULL,
  PRIMARY KEY (file_id),
  UNIQUE KEY file_name (file_name,file_path),
  KEY file_author (file_author)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'file_rating'
--

CREATE TABLE file_rating (
  file_id int(11) NOT NULL,
  user_id varchar(255) NOT NULL,
  rate int(1) NOT NULL,
  PRIMARY KEY (file_id,user_id),
  KEY user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'file_tag'
--

CREATE TABLE file_tag (
  file_id int(11) NOT NULL DEFAULT '0',
  tag varchar(255) NOT NULL,
  file_tagger varchar(255) NOT NULL DEFAULT 'guest',
  PRIMARY KEY (file_id,tag),
  KEY tag (tag),
  KEY file_tagger (file_tagger)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'file_use'
--

CREATE TABLE file_use (
  session_id varchar(255) NOT NULL DEFAULT '',
  load_id bigint(20) NOT NULL,
  file_id int(11) NOT NULL DEFAULT '0',
  file_use_edit tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (session_id,load_id),
  KEY file_id (file_id),
  KEY load_id (load_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'page_load'
--

CREATE TABLE page_load (
  session_id varchar(255) NOT NULL,
  load_id bigint(255) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `time` time NOT NULL,
  url varchar(255) NOT NULL,
  query_string varchar(255) NOT NULL,
  referer varchar(255) NOT NULL,
  PRIMARY KEY (load_id,session_id),
  KEY session_id (session_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'session'
--

CREATE TABLE `session` (
  session_id varchar(255) NOT NULL DEFAULT '',
  session_date date NOT NULL DEFAULT '2000-01-01',
  session_time time NOT NULL DEFAULT '00:00:00',
  session_user varchar(255) DEFAULT NULL,
  client_ip varchar(255) NOT NULL DEFAULT '',
  client_host_name varchar(255) NOT NULL DEFAULT '',
  client_description varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (session_id),
  KEY session_user (session_user)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'tag'
--

CREATE TABLE tag (
  tag varchar(255) NOT NULL DEFAULT '',
  tag_requests int(11) NOT NULL DEFAULT '0',
  tag_creation_date date NOT NULL DEFAULT '2014-01-01',
  tag_author varchar(255) NOT NULL,
  PRIMARY KEY (tag),
  UNIQUE KEY tag_name (tag),
  KEY tag_author (tag_author)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'user'
--

CREATE TABLE user (
  country varchar(255) NOT NULL DEFAULT '',
  e_mail varchar(255) NOT NULL DEFAULT '',
  first_name varchar(255) NOT NULL DEFAULT '',
  last_name varchar(255) NOT NULL DEFAULT '',
  pwd varchar(255) DEFAULT NULL,
  user_id varchar(255) NOT NULL DEFAULT '',
  user_level int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table command
--
ALTER TABLE command
  ADD CONSTRAINT command_ibfk_1 FOREIGN KEY (session_id) REFERENCES file_use (session_id),
  ADD CONSTRAINT command_ibfk_2 FOREIGN KEY (load_id) REFERENCES file_use (load_id);

--
-- Constraints for table file
--
ALTER TABLE file
  ADD CONSTRAINT file_ibfk_1 FOREIGN KEY (file_author) REFERENCES `user` (user_id);

--
-- Constraints for table file_rating
--
ALTER TABLE file_rating
  ADD CONSTRAINT file_rating_ibfk_1 FOREIGN KEY (file_id) REFERENCES file (file_id),
  ADD CONSTRAINT file_rating_ibfk_2 FOREIGN KEY (user_id) REFERENCES `user` (user_id);

--
-- Constraints for table file_tag
--
ALTER TABLE file_tag
  ADD CONSTRAINT file_tag_ibfk_1 FOREIGN KEY (file_tagger) REFERENCES `user` (user_id),
  ADD CONSTRAINT file_tag_ibfk_2 FOREIGN KEY (tag) REFERENCES tag (tag),
  ADD CONSTRAINT file_tag_ibfk_3 FOREIGN KEY (file_tagger) REFERENCES `user` (user_id),
  ADD CONSTRAINT file_tag_ibfk_4 FOREIGN KEY (file_id) REFERENCES file (file_id);

--
-- Constraints for table file_use
--
ALTER TABLE file_use
  ADD CONSTRAINT file_use_ibfk_2 FOREIGN KEY (file_id) REFERENCES file (file_id),
  ADD CONSTRAINT file_use_ibfk_3 FOREIGN KEY (session_id) REFERENCES page_load (session_id),
  ADD CONSTRAINT file_use_ibfk_4 FOREIGN KEY (load_id) REFERENCES page_load (load_id);

--
-- Constraints for table page_load
--
ALTER TABLE page_load
  ADD CONSTRAINT page_load_ibfk_1 FOREIGN KEY (session_id) REFERENCES session (session_id);

--
-- Constraints for table session
--
ALTER TABLE session
  ADD CONSTRAINT session_ibfk_1 FOREIGN KEY (session_user) REFERENCES `user` (user_id);

--
-- Constraints for table tag
--
ALTER TABLE tag
  ADD CONSTRAINT tag_ibfk_1 FOREIGN KEY (tag_author) REFERENCES `user` (user_id);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
