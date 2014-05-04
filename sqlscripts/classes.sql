SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
CREATE TABLE IF NOT EXISTS `classes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(25) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=45 ;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`ID`, `NAME`) VALUES
(1,'Medic'),
(2,'Soldier'),
(3,'Heavy'),
(4,'Engineer'),
(5,'Pyro'),
(6,'Scout'),
(7,'Sniper'),
(8,'Spy'),
(9,'Demoman');