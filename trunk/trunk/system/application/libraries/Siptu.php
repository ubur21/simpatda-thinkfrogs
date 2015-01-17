<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_Siptu {
		
	function CI_Siptu()
	{

		
	}
		
	function b_admin()
	{		
		$CI = &get_instance();
		$CI->load->model('user_model');		
		
		return $CI->user_model->get_status($CI->session->userdata('nid_login'));
	}

	function b_getuserlogin()
	{
		$CI = &get_instance();
		
		if($this->b_logged())
			$nreturn=$CI->session->userdata('nid_login');
		else
			$nreturn=0;
			
		return $nreturn;
	}

	function b_logged() {
		$CI = &get_instance();
		if($CI->session->userdata('nid_login') > 0 )
		{
			return true;
		}
		else return false;
	}


	/**
	 * Mengambil data menu
	 */
	function load_menu(){
		$i = 0; $menus = array();
		$CI = &get_instance();
		$CI->load->model('menu_model');
		
		$rs_menu = $CI->menu_model->get_frontmenu_all();
		$ii=0; $jj=0; $kk=0;
		$menus = array(); $tmp = array();
		if($rs_menu->num_rows()>0)
		{
			foreach($rs_menu->result() as $omenu)
			{
				$menus[$ii]['nama_group']   = $omenu->CGROUP;
				$menus[$ii]['id_group']     = $omenu->ID_GROUP;
				$menus[$ii]['nama_sub']     = $omenu->NAMA_SUB;
				$menus[$ii]['id_sub']       = $omenu->ID_SUB;
				$menus[$ii]['nid']          = $omenu->NID;
				$menus[$ii]['menu']	        = $omenu->CMENU;
				$menus[$ii]['ci_path']      = $omenu->CI_PATH;
				$menus[$ii]['ci_controller']= $omenu->CI_CONTROLLER;
				
				$menus[$ii]['ci_func_controller']  = $omenu->CI_FUNC_CONTROLLER;
				
				$menus[$ii]['nid_groupfrontmenus'] = $omenu->NID_GROUPFRONTMENUS;
				$menus[$ii]['nid_header']          = $omenu->NID_HEADER;
				
				$menus[$ii]['is_main'] = $omenu->IS_MAIN;
								
				$ii++;
			}
			
		}
		
		return $menus;
	}

}

?>