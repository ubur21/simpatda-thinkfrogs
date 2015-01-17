-- phpMyAdmin SQL Dump
-- version 2.7.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jun 03, 2007 at 02:02 PM
-- Server version: 4.1.9
-- PHP Version: 5.2.0-dev
-- 
-- Database: `bcms`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `b_groupfrontmenus`
-- 

CREATE TABLE IF NOT EXISTS `b_groupfrontmenus` (
  `nid` int(11) NOT NULL auto_increment,
  `cgroup` varchar(50) NOT NULL default '',
  `nurut` tinyint(4) NOT NULL default '0',
  `bhide` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`nid`)
) TYPE=MyISAM AUTO_INCREMENT=10 ;
