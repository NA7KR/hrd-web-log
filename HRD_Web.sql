-- phpMyAdmin SQL Dump
-- version 3.5.8.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: May 12, 2013 at 07:16 PM
-- Server version: 5.5.31-0ubuntu0.13.04.1
-- PHP Version: 5.4.9-4ubuntu2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `HRD_Web`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_awards`
--

CREATE TABLE IF NOT EXISTS `tb_awards` (
  `COL_PRIMARY_KEY` int(11) NOT NULL,
  `COL_Award` varchar(30) NOT NULL,
  `COL_File` varchar(20) NOT NULL,
  PRIMARY KEY (`COL_PRIMARY_KEY`),
  UNIQUE KEY `key` (`COL_PRIMARY_KEY`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_awards`
--

INSERT INTO `tb_awards` (`COL_PRIMARY_KEY`, `COL_Award`, `COL_File`) VALUES
(1, 'eDX', 'edx.jpg'),
(2, '10-10', '10-10.jpg'),
(3, '10-10', '10-10a.jpg'),
(4, 'North West Country Cousins', 'NCC.jpg'),
(5, 'Western Country Cousins', 'WCC.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tb_Bands_lookup`
--

CREATE TABLE IF NOT EXISTS `tb_Bands_lookup` (
  `key` int(11) NOT NULL AUTO_INCREMENT,
  `Col_Band` varchar(6) NOT NULL,
  `SSB_Mode` varchar(3) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `tb_Bands_lookup`
--

INSERT INTO `tb_Bands_lookup` (`key`, `Col_Band`, `SSB_Mode`) VALUES
(1, '160m', 'LSB'),
(2, '80m', 'LSB'),
(3, '60m', 'LSB'),
(4, '40m', 'LSB'),
(5, '30m', 'USB'),
(6, '20m', 'USB'),
(7, '17m', 'USB'),
(8, '15m', 'USB'),
(9, '12m', 'USB'),
(10, '10m', 'USB'),
(11, '6m', 'USB'),
(12, '2m', 'USB');

-- --------------------------------------------------------

--
-- Table structure for table `tb_Cards`
--

CREATE TABLE IF NOT EXISTS `tb_Cards` (
  `COL_PRIMARY_KEY` int(11) NOT NULL,
  `COL_File_Path_F` varchar(255) NOT NULL,
  `COL_File_Path_B` varchar(255) NOT NULL,
  `COL_File_Path_E` varchar(255) NOT NULL,
  UNIQUE KEY `Key` (`COL_PRIMARY_KEY`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_Cards`
--

INSERT INTO `tb_Cards` (`COL_PRIMARY_KEY`, `COL_File_Path_F`, `COL_File_Path_B`, `COL_File_Path_E`) VALUES
(0, '', '', ''),


-- --------------------------------------------------------

--
-- Table structure for table `tb_Select`
--

CREATE TABLE IF NOT EXISTS `tb_Select` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `Select_TXT` varchar(30) NOT NULL,
  `Select_Name` varchar(20) NOT NULL,
  `Select_Query` longtext NOT NULL,
  PRIMARY KEY (`index`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `tb_Select`
--

INSERT INTO `tb_Select` (`index`, `Select_TXT`, `Select_Name`, `Select_Query`) VALUES
(2, 'No EQSL Card', 'nocardEQSL', 'SELECT _dbWEB_._tbST_.State as `State`, _dbWEB_._tbST_.ST as `State` FROM _dbWEB_._tbST_ left outer join _DB_._tbHRD_ on _dbWEB_._tbST_.Country = _DB_._tbHRD_.COL_COUNTRY AND _dbWEB_._tbST_.ST = _DB_._tbHRD_.COL_STATE where\r\n( _dbWEB_._tbST_.sCountry  = "_Country_" )\r\n and \r\n col_state is not null and COL_EQSL_QSL_RCVD not in ( ''Y'' ) and col_state not in (select col_state from _DB_._tbHRD_ where col_state is not null and COL_EQSL_QSL_RCVD <> ''N'' and COL_EQSL_QSL_RCVD <> ''R''and COL_BAND LIKE "_Band_" and COL_MODE LIKE "_Mode_") group by 1,2\r\n'),
(3, 'Full', 'full', 'SELECT date(`COL_TIME_OFF`)AS`Date` ,`COL_CALL`AS`CallSign`,`COL_MODE`AS`Mode` ,`COL_BAND`AS`Band` ,`COL_GRIDSQUARE`AS`Grid` ,`COL_COUNTRY`AS`Country` ,`COL_STATE`AS`State` ,`COL_QTH`AS`QTH` \r\nFROM _tbHRD_\r\nWHERE COL_BAND LIKE"_Band_"\r\nAND COL_MODE LIKE"_Mode_"'),
(4, 'States to work', 'towork', 'SELECT _dbWEB_._tbST_.State as `State` , _dbWEB_._tbST_.ST as `State` , _dbWEB_._tbST_.Country as `Country` \r\n  FROM _dbWEB_._tbST_ left outer join  _DB_._tbHRD_ on _dbWEB_._tbST_.Country  = _DB_._tbHRD_.COL_COUNTRY AND \r\n_dbWEB_._tbST_.ST = _DB_._tbHRD_.COL_STATE  \r\nwhere \r\n_dbWEB_._tbST_.sCountry  = "_Country_" and col_state is null \r\ngroup by 1,2'),
(5, 'No LOTW ', 'nocardLOTW', 'SELECT _dbWEB_._tbST_.State as `State`, _dbWEB_._tbST_.ST as `State` FROM _dbWEB_._tbST_ left outer join _DB_._tbHRD_ on _dbWEB_._tbST_.Country = _DB_._tbHRD_.COL_COUNTRY AND\r\n _dbWEB_._tbST_.ST = _DB_._tbHRD_.COL_STATE where \r\n ( _dbWEB_._tbST_.sCountry  = "_Country_" ) \r\n and col_state is not null and COL_LOTW_QSL_RCVD not in ( ''Y'' ) and col_state not in (select col_state from _DB_._tbHRD_ where col_state is not null and \r\n COL_LOTW_QSL_RCVD <> ''N'' and COL_LOTW_QSL_RCVD <> ''R''and COL_BAND LIKE "_Band_" and COL_MODE LIKE "_Mode_") group by 1,2'),
(6, 'ITU Zones', 'zones', 'SELECT _dbWEB_.tb_zones.zones as `ITU Zone to Work`  \r\nFROM _dbWEB_.tb_zones left outer join \r\n_DB_._tbHRD_ on _dbWEB_.tb_zones.zones  = \r\n_DB_._tbHRD_.COL_ITUZ  \r\nwhere COL_ITUZ is null'),
(7, 'Callsign Lookup', 'callsign_lookup', 'SELECT\r\nCOL_CALL AS `Call`,\r\nCOL_BAND AS Band,\r\nCOL_State AS State,\r\nCOL_Country AS Country,\r\n_DB_._tbHRD_.COL_PRIMARY_KEY AS ID,\r\nCOL_TIME_OFF AS Date,\r\n\r\nCASE COL_EQSL_QSL_RCVD \r\n	When "Y" Then "Yes"\r\nend AS EQSL,\r\n\r\nCASE COL_LOTW_QSL_RCVD \r\n                When "V" Then "Yes"\r\nend AS LOTW,\r\n\r\nCASE COL_QSL_RCVD \r\nWhen "Y" Then "Yes"\r\nend AS QSL,\r\n\r\nCOL_MODE AS `Mode`,\r\nHRD_Web.tb_Cards.COL_File_Path_E AS ''E QSL'',\r\nHRD_Web.tb_Cards.COL_File_Path_F AS File,\r\nHRD_Web.tb_Cards.COL_File_Path_B AS ''File Back''\r\nFROM\r\n_DB_._tbHRD_\r\nLEFT OUTER JOIN HRD_Web.tb_Cards ON _DB_._tbHRD_.COL_PRIMARY_KEY = HRD_Web.tb_Cards.COL_PRIMARY_KEY\r\nwhere COL_CALL like ''%_CALL_SEARCH_%'''),
(8, 'Paper Card', 'paper_card', 'SELECT\r\n  tb_Cards.COL_PRIMARY_KEY as ''Log ID'',\r\n  _tbHRD_.COL_CALL as ''Call'',\r\n  tb_Cards.COL_File_Path_F as ''Card'',\r\n  tb_Cards.COL_File_Path_B as ''Back''\r\nFROM HRD_Web.tb_Cards\r\n  INNER JOIN _DB_._tbHRD_\r\n    ON tb_Cards.COL_PRIMARY_KEY = _tbHRD_.COL_PRIMARY_KEY\r\nWHERE tb_Cards.COL_File_Path_F <> ""'),
(9, 'Received EQSL', 'eqsl_received', 'SELECT\r\n  tb_Cards.COL_PRIMARY_KEY as ''Log ID'',\r\n  _tbHRD_.COL_CALL as ''Call'',\r\n  tb_Cards.COL_File_Path_E as ''Cards''\r\nFROM HRD_Web.tb_Cards\r\n  INNER JOIN _DB_._tbHRD_\r\n    ON tb_Cards.COL_PRIMARY_KEY = _tbHRD_.COL_PRIMARY_KEY\r\nWHERE tb_Cards.COL_File_Path_E <> ""'),
(10, 'Received LOTW', 'received_lotw', 'SELECT \r\n`COL_CALL` as ''Call'',\r\n`COL_LOTW_QSL_RCVD` as ''Confirmed''\r\n FROM _tbHRD_  \r\nWhere `COL_LOTW_QSL_RCVD` = ''V'''),
(11, 'Countrys Worked', 'Countrys_Worked', 'SELECT \r\nCOL_COUNTRY as ''Countrys Worked'' \r\nFROM _tbHRD_ \r\nWHERE 1 and COL_BAND LIKE "_Band_" and COL_MODE LIKE "_Mode_"  \r\ngroup by 1\r\n'),
(12, 'Awards', 'awards', 'SELECT COL_Award as ''Award'', COL_File as "File" FROM _dbWEB_.tb_awards WHERE 1');

-- --------------------------------------------------------

--
-- Table structure for table `tb_States_Countries`
--

CREATE TABLE IF NOT EXISTS `tb_States_Countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `State` char(50) NOT NULL,
  `ST` char(2) NOT NULL,
  `Country` char(30) NOT NULL,
  `sCountry` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=156 ;

--
-- Dumping data for table `tb_States_Countries`
--

INSERT INTO `tb_States_Countries` (`id`, `State`, `ST`, `Country`, `sCountry`) VALUES
(1, 'Alabama', 'AL', 'United States', 'USA'),
(3, 'Arizona', 'AZ', 'United States', 'USA'),
(4, 'Arkansas', 'AR', 'United States', 'USA'),
(5, 'California', 'CA', 'United States', 'USA'),
(6, 'Colorado', 'CO', 'United States', 'USA'),
(7, 'Connecticut', 'CT', 'United States', 'USA'),
(8, 'Delaware', 'DE', 'United States', 'USA'),
(9, 'Florida', 'FL', 'United States', 'USA'),
(10, 'Georgia', 'GA', 'United States', 'USA'),
(12, 'Idaho', 'ID', 'United States', 'USA'),
(13, 'Illinois', 'IL', 'United States', 'USA'),
(14, 'Indiana', 'IN', 'United States', 'USA'),
(15, 'Iowa', 'IA', 'United States', 'USA'),
(16, 'Kansas', 'KS', 'United States', 'USA'),
(17, 'Kentucky', 'KY', 'United States', 'USA'),
(18, 'Louisiana', 'LA', 'United States', 'USA'),
(19, 'Maine', 'ME', 'United States', 'USA'),
(20, 'Maryland', 'MD', 'United States', 'USA'),
(21, 'Massachusetts', 'MA', 'United States', 'USA'),
(22, 'Michigan', 'MI', 'United States', 'USA'),
(23, 'Minnesota', 'MN', 'United States', 'USA'),
(24, 'Mississippi', 'MS', 'United States', 'USA'),
(25, 'Missouri', 'MO', 'United States', 'USA'),
(26, 'Montana', 'MT', 'United States', 'USA'),
(27, 'Nebraska', 'NE', 'United States', 'USA'),
(28, 'Nevada', 'NV', 'United States', 'USA'),
(29, 'New Hampshire', 'NH', 'United States', 'USA'),
(30, 'New Jersey', 'NJ', 'United States', 'USA'),
(31, 'New Mexico', 'NM', 'United States', 'USA'),
(32, 'New York', 'NY', 'United States', 'USA'),
(33, 'North Carolina', 'NC', 'United States', 'USA'),
(34, 'North Dakota', 'ND', 'United States', 'USA'),
(35, 'Ohio', 'OH', 'United States', 'USA'),
(36, 'Oklahoma', 'OK', 'United States', 'USA'),
(37, 'Oregon', 'OR', 'United States', 'USA'),
(38, 'Pennsylvania', 'PA', 'United States', 'USA'),
(39, 'Rhode Island', 'RI', 'United States', 'USA'),
(40, 'South Carolina', 'SC', 'United States', 'USA'),
(41, 'South Dakota', 'SD', 'United States', 'USA'),
(42, 'Tennessee', 'TN', 'United States', 'USA'),
(43, 'Texas', 'TX', 'United States', 'USA'),
(44, 'Utah', 'UT', 'United States', 'USA'),
(45, 'Vermont', 'VT', 'United States', 'USA'),
(46, 'Virginia', 'VA', 'United States', 'USA'),
(47, 'Washington', 'WA', 'United States', 'USA'),
(48, 'West Virginia', 'WV', 'United States', 'USA'),
(49, 'Wisconsin', 'WI', 'United States', 'USA'),
(50, 'Wyoming', 'WY', 'United States', 'USA'),
(51, 'Alaska', 'AK', 'Alaska', 'USA'),
(52, 'Hawaii', 'HI', 'Hawaii', 'USA'),
(53, 'Alberta', 'AB', 'Canada', 'CA'),
(54, 'British Columbia', 'BC', 'Canada', 'CA'),
(55, 'Manitoba', 'MB', 'Canada', 'CA'),
(56, 'New Brunswick', 'NB', 'Canada', 'CA'),
(57, 'Newfoundland and Labrador', 'NL', 'Canada', 'CA'),
(58, 'Nova Scotia', 'NS', 'Canada', 'CA'),
(59, 'Ontario', 'ON', 'Canada', 'CA'),
(60, 'Prince Edward Island', 'PE', 'Canada', 'CA'),
(61, 'Quebec', 'QC', 'Canada', 'CA'),
(62, 'Saskatchewan', 'SK', 'Canada', 'CA'),
(63, 'Northwest Territories', 'NT', 'Canada', 'CA'),
(64, 'Nunavut', 'NU', 'Canada', 'CA'),
(65, 'Yukon Territory', 'YT', 'Canada', 'CA'),
(68, 'Bedfordshire', '', 'England', 'GB'),
(69, 'Buckinghamshire', '', 'England', 'GB'),
(70, 'Cambridgeshire', '', 'England', 'GB'),
(71, 'Cheshire', '', 'England', 'GB'),
(72, 'Cornwall and Isles of Scilly', '', 'England', 'GB'),
(73, 'Cumbria', '', 'England', 'GB'),
(74, 'Derbyshire', '', 'England', 'GB'),
(75, 'Devon', '', 'England', 'GB'),
(76, 'Dorset', '', 'England', 'GB'),
(77, 'Durham', '', 'England', 'GB'),
(78, 'East Sussex', '', 'England', 'GB'),
(79, 'Essex', '', 'England', 'GB'),
(80, 'Gloucestershire', '', 'England', 'GB'),
(81, 'Greater London', '', 'England', 'GB'),
(82, 'Greater Manchester', '', 'England', 'GB'),
(83, 'Hampshire', '', 'England', 'GB'),
(84, 'Hertfordshire', '', 'England', 'GB'),
(85, 'Kent', '', 'England', 'GB'),
(86, 'Lancashire', '', 'England', 'GB'),
(87, 'Leicestershire', '', 'England', 'GB'),
(88, 'Lincolnshire', '', 'England', 'GB'),
(89, 'Merseyside', '', 'England', 'GB'),
(90, 'Norfolk', '', 'England', 'GB'),
(91, 'North Yorkshire', '', 'England', 'GB'),
(92, 'Northamptonshire', '', 'England', 'GB'),
(93, 'Northumberland', '', 'England', 'GB'),
(94, 'Nottinghamshire', '', 'England', 'GB'),
(95, 'Oxfordshire', '', 'England', 'GB'),
(96, 'Shropshire', '', 'England', 'GB'),
(97, 'Somerset', '', 'England', 'GB'),
(98, 'South Yorkshire', '', 'England', 'GB'),
(99, 'Staffordshire', '', 'England', 'GB'),
(100, 'Suffolk', '', 'England', 'GB'),
(101, 'Surrey', '', 'England', 'GB'),
(102, 'Tyne and Wear', '', 'England', 'GB'),
(103, 'Warwickshire', '', 'England', 'GB'),
(104, 'West Midlands', '', 'England', 'GB'),
(105, 'West Sussex', '', 'England', 'GB'),
(106, 'West Yorkshire', '', 'England', 'GB'),
(107, 'Wiltshire', '', 'England', 'GB'),
(108, 'Worcestershire', '', 'England', 'GB'),
(109, 'Flintshire', '', 'Wales', 'GB'),
(110, 'Glamorgan', '', 'Wales', 'GB'),
(111, 'Merionethshire', '', 'Wales', 'GB'),
(112, 'Monmouthshire', '', 'Wales', 'GB'),
(113, 'Montgomeryshire', '', 'Wales', 'GB'),
(114, 'Pembrokeshire', '', 'Wales', 'GB'),
(115, 'Radnorshire', '', 'Wales', 'GB'),
(116, 'Anglesey', '', 'Wales', 'GB'),
(117, 'Breconshire', '', 'Wales', 'GB'),
(118, 'Caernarvonshire', '', 'Wales', 'GB'),
(119, 'Cardiganshire', '', 'Wales', 'GB'),
(120, 'Carmarthenshire', '', 'Wales', 'GB'),
(121, 'Denbighshire', '', 'Wales', 'GB'),
(122, 'Kirkcudbrightshire', '', 'Scotland', 'GB'),
(123, 'Lanarkshire', '', 'Scotland', 'GB'),
(124, 'Midlothian', '', 'Scotland', 'GB'),
(125, 'Moray', '', 'Scotland', 'GB'),
(126, 'Nairnshire', '', 'Scotland', 'GB'),
(127, 'Orkney', '', 'Scotland', 'GB'),
(128, 'Peebleshire', '', 'Scotland', 'GB'),
(129, 'Perthshire', '', 'Scotland', 'GB'),
(130, 'Renfrewshire', '', 'Scotland', 'GB'),
(131, 'Ross & Cromarty', '', 'Scotland', 'GB'),
(132, 'Roxburghshire', '', 'Scotland', 'GB'),
(133, 'Selkirkshire', '', 'Scotland', 'GB'),
(134, 'Shetland', '', 'Scotland', 'GB'),
(135, 'Stirlingshire', '', 'Scotland', 'GB'),
(136, 'Sutherland', '', 'Scotland', 'GB'),
(137, 'West Lothian', '', 'Scotland', 'GB'),
(138, 'Wigtownshire', '', 'Scotland', 'GB'),
(139, 'Aberdeenshire', '', 'Scotland', 'GB'),
(140, 'Angus', '', 'Scotland', 'GB'),
(141, 'Argyll', '', 'Scotland', 'GB'),
(142, 'Ayrshire', '', 'Scotland', 'GB'),
(143, 'Banffshire', '', 'Scotland', 'GB'),
(144, 'Berwickshire', '', 'Scotland', 'GB'),
(145, 'Bute', '', 'Scotland', 'GB'),
(146, 'Caithness', '', 'Scotland', 'GB'),
(147, 'Clackmannanshire', '', 'Scotland', 'GB'),
(148, 'Dumfriesshire', '', 'Scotland', 'GB'),
(149, 'Dumbartonshire', '', 'Scotland', 'GB'),
(150, 'East Lothian', '', 'Scotland', 'GB'),
(151, 'Fife', '', 'Scotland', 'GB'),
(152, 'Inverness', '', 'Scotland', 'GB'),
(153, 'Kincardineshire', '', 'Scotland', 'GB'),
(154, 'Kinross-shire', '', 'Scotland', 'GB'),
(155, 'London', '', 'England', 'GB');

-- --------------------------------------------------------

--
-- Table structure for table `tb_zones`
--

CREATE TABLE IF NOT EXISTS `tb_zones` (
  `key` int(11) NOT NULL AUTO_INCREMENT,
  `zones` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;

--
-- Dumping data for table `tb_zones`
--

INSERT INTO `tb_zones` (`key`, `zones`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10),
(11, 11),
(12, 12),
(13, 13),
(14, 14),
(15, 15),
(16, 16),
(17, 17),
(18, 18),
(19, 19),
(20, 20),
(21, 21),
(22, 22),
(23, 23),
(24, 24),
(25, 25),
(26, 26),
(27, 27),
(28, 28),
(29, 29),
(30, 30),
(31, 31),
(32, 32),
(33, 33),
(34, 34),
(35, 35),
(36, 36),
(37, 37),
(38, 38),
(39, 39),
(40, 40);

-- --------------------------------------------------------

--
-- Table structure for table `visit`
--

CREATE TABLE IF NOT EXISTS `visit` (
  `visitorID` int(10) NOT NULL AUTO_INCREMENT,
  `visitorIP` char(200) NOT NULL,
  `visitDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `visitURL` varchar(200) NOT NULL,
  `browser` char(200) NOT NULL,
  `version` char(200) NOT NULL,
  `os` char(200) NOT NULL,
  `osversion` char(200) NOT NULL,
  PRIMARY KEY (`visitorID`),
  UNIQUE KEY `visitorID` (`visitorID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

--
-- Dumping data for table `visit`
--



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
