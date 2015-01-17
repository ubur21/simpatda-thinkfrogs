<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
Class JqGridCss {
	function __construct(){
	}
	function box() {//for content
		return "form ui-tabs ui-widget ui-widget-content ui-corner-all";
	}
	function boxgrid() {
		return "ui-jqgrid ui-widget ui-widget-content ui-corner-all";
	}
	function bar() {//for menu title
		return "ui-jqgrid-titlebar ui-widget-header ui-corner-top ui-helper-clearfix";
	}
	function tab() {//for menu tab
		return "ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all";
	}
	function grid() {//for field grid
		return "ui-state-default ui-th-column ui-th-ltr";
	}
	function panel() {//for panel button
		return "EditButton ui-widget-content";
	}
	function pager() {//for bottom nav
		return "ui-state-default ui-jqgrid-pager ui-corner-bottom";
	}
	function button() {//for button
		return "fm-button ui-state-default ui-corner-all fm-button-icon-left";
	}
	function iconsave() {//for icon save
		return "ui-icon ui-icon-disk";
	}
	function iconclose() {//for icon close
		return "ui-icon ui-icon-close";
	}
	function iconprint() {//for icon print
		return "ui-icon ui-icon-print";
	}
	function iconcalendar() {//for icon calendar
		return "ui-icon ui-icon-calendar";
	}
	function iconsearch() {//for icon search
		return "ui-icon ui-icon-search";
	}
	function iconinfo() {//for icon info
		return "ui-icon ui-icon-info";
	}
	function iconplus() {//for icon close
		return "ui-icon ui-icon-plus";
	}
	function hover() {
		return "ui-state-hover";
	}
	//you can add here//
}
?>