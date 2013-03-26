-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Mar 26, 2013 at 01:10 PM
-- Server version: 5.5.29
-- PHP Version: 5.4.6-1ubuntu1.2

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
-- Table structure for table `tb_Select`
--

CREATE TABLE IF NOT EXISTS `tb_Select` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `Select_TXT` varchar(30) NOT NULL,
  `Select_Name` varchar(20) NOT NULL,
  `Select_Query` longtext NOT NULL,
  PRIMARY KEY (`index`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `tb_Select`
--

INSERT INTO `tb_Select` (`index`, `Select_TXT`, `Select_Name`, `Select_Query`) VALUES
(2, 'No EQSL Card', 'nocardEQSL', 'SELECT _dbWEB_._tbST_.State as `State`, _dbWEB_._tbST_.ST as `State` FROM _dbWEB_._tbST_ left outer join _DB_._tbHRD_ on _dbWEB_._tbST_.Country = _DB_._tbHRD_.COL_COUNTRY AND _dbWEB_._tbST_.ST = _DB_._tbHRD_.COL_STATE where\r\n( _dbWEB_._tbST_.sCountry  = "_Country_" )\r\n and \r\n col_state is not null and COL_EQSL_QSL_RCVD not in ( ''Y'' ) and col_state not in (select col_state from _DB_._tbHRD_ where col_state is not null and COL_EQSL_QSL_RCVD <> ''N'' and COL_EQSL_QSL_RCVD <> ''R''and COL_BAND LIKE "_Band_" and COL_MODE LIKE "_Mode_") group by 1,2\r\n'),
(3, 'Full', 'full', 'SELECT date(`COL_TIME_OFF`)AS`Date` ,`COL_CALL`AS`CallSign`,`COL_MODE`AS`Mode` ,`COL_BAND`AS`Band` ,`COL_GRIDSQUARE`AS`Grid` ,`COL_COUNTRY`AS`Country` ,`COL_STATE`AS`State` ,`COL_QTH`AS`QTH` \r\nFROM _tbHRD_\r\nWHERE COL_BAND LIKE"_Band_"\r\nAND COL_MODE LIKE"_Mode_"'),
(4, 'States to work', 'towork', 'SELECT _dbWEB_._tbST_.State as `State` , _dbWEB_._tbST_.ST as `State` , _dbWEB_._tbST_.Country as `Country` \r\n  FROM _dbWEB_._tbST_ left outer join  _DB_._tbHRD_ on _dbWEB_._tbST_.Country  = _DB_._tbHRD_.COL_COUNTRY AND \r\n_dbWEB_._tbST_.ST = _DB_._tbHRD_.COL_STATE  \r\nwhere \r\n_dbWEB_._tbST_.sCountry  = "_Country_" and col_state is null \r\ngroup by 1,2'),
(5, 'No LOTW ', 'nocardLOTW', 'SELECT _dbWEB_._tbST_.State as `State`, _dbWEB_._tbST_.ST as `State` FROM _dbWEB_._tbST_ left outer join _DB_._tbHRD_ on _dbWEB_._tbST_.Country = _DB_._tbHRD_.COL_COUNTRY AND\r\n _dbWEB_._tbST_.ST = _DB_._tbHRD_.COL_STATE where \r\n ( _dbWEB_._tbST_.sCountry  = "_Country_" ) \r\n and col_state is not null and COL_LOTW_QSL_RCVD not in ( ''Y'' ) and col_state not in (select col_state from _DB_._tbHRD_ where col_state is not null and \r\n COL_LOTW_QSL_RCVD <> ''N'' and COL_LOTW_QSL_RCVD <> ''R''and COL_BAND LIKE "_Band_" and COL_MODE LIKE "_Mode_") group by 1,2'),
(6, 'ITU Zones', 'zones', 'SELECT _dbWEB_.tb_zones.zones as `ITU Zone to Work`  \r\nFROM _dbWEB_.tb_zones left outer join \r\n_DB_._tbHRD_ on _dbWEB_.tb_zones.zones  = \r\n_DB_._tbHRD_.COL_ITUZ  \r\nwhere COL_ITUZ is null'),
(7, 'Callsign Lookup', 'callsign_lookup', 'SELECT `COL_CALL` as ''CALL'' FROM _tbHRD_ Where COL_CALL like ''%_CALL_SEARCH_%'''),
(8, 'Paper Card', 'paper_card', 'SELECT \r\n`COL_CALL` as ''Call'',\r\n`COL_QSL_RCVD` As ''Received'', `COL_QSL_SENT` as ''Sent''\r\n FROM _tbHRD_  where `COL_QSL_RCVD` = "Y" or `COL_QSL_SENT` = "Y"'),
(9, 'Received EQSL', 'eqsl_received', 'SELECT \r\n`COL_CALL` as ''Call'',\r\n`COL_EQSL_QSL_RCVD` as ''Received''\r\n FROM _tbHRD_  \r\nWhere `COL_EQSL_QSL_RCVD` = ''Y'''),
(10, 'Received LOTW', 'received_lotw', 'SELECT \r\n`COL_CALL` as ''Call'',\r\n`COL_LOTW_QSL_RCVD` as ''Confirmed''\r\n FROM _tbHRD_  \r\nWhere `COL_LOTW_QSL_RCVD` = ''V''');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
