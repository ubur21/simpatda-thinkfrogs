-- phpMyAdmin SQL Dump
-- version 2.7.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jun 03, 2007 at 02:00 PM
-- Server version: 4.1.9
-- PHP Version: 5.2.0-dev
-- 
-- Database: `bcms`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `b_frontmenus`
-- 

CREATE TABLE IF NOT EXISTS `b_frontmenus` (
  `nid` int(11) NOT NULL auto_increment,
  `nid_groupfrontmenus` int(11) NOT NULL default '0',
  `cmenu` varchar(50) NOT NULL default '',
  `cfunction` varchar(50) NOT NULL default '',
  `cparam` varchar(100) NOT NULL default '',
  `nurut` tinyint(4) NOT NULL default '0',
  `bhide` tinyint(1) NOT NULL default '0',
  `bsecure` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`nid`)
) TYPE=MyISAM AUTO_INCREMENT=5 ;

ALTER TABLE b_frontmenus ADD width int(11) NOT NULL default '0';
ALTER TABLE b_frontmenus ADD height int(11) NOT NULL default '0';
ALTER TABLE b_frontmenus ADD is_main INTEGER int(11) NOT NULL default '0';

