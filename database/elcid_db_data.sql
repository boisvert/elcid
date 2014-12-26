--
-- Database: 'elcid_test'
--

--
-- Dumping data for table 'user'
--

INSERT INTO user VALUES('', '', '', '', NULL, 'example', 0);


--
-- Dumping data for table 'file'
--

INSERT INTO file VALUES (1, '2011-05-05', 'example', 'cut_down_rollover_example', 'users/example', '', 1, NULL);

--
-- Dumping data for table 'file_rating'
--

INSERT INTO file_rating VALUES(1, 'example', 0);

--
-- Dumping data for table 'tag'
--

INSERT INTO tag VALUES('images', 0, '2014-01-01', 'example');
INSERT INTO tag VALUES('javascript', 0, '2014-01-01', 'example');
INSERT INTO tag VALUES('easy', 0, '2014-01-01', 'example');

--
-- Dumping data for table 'file_tag'
--

INSERT INTO file_tag VALUES(1, 'images', 'example');
INSERT INTO file_tag VALUES(1, 'javascript', 'example');
INSERT INTO file_tag VALUES(1, 'easy', 'example');

