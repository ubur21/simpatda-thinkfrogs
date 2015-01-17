-- phpMyAdmin SQL Dump
-- version 2.7.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jun 03, 2007 at 02:01 PM
-- Server version: 4.1.9
-- PHP Version: 5.2.0-dev
-- 
-- Database: `bcms`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `b_grantedfrontmenus`
-- 

CREATE TABLE IF NOT EXISTS `b_grantedfrontmenus` (
  `nid` int(11) NOT NULL auto_increment,
  `nid_users` int(11) NOT NULL default '0',
  `nid_frontmenus` int(11) NOT NULL default '0',
  `nstatus` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`nid`)
) TYPE=MyISAM AUTO_INCREMENT=30 ;
