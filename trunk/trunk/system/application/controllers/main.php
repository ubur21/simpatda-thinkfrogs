<?php
class Main extends Controller {

	var $theme_dir;
	var $layout_dir;
	var $title;
	var $main_menu; 
	var $theme_menu;

	function Main()
	{
		parent::Controller();
		
		$this->CI=&get_instance();
		
		$this->_init();
	}
	
	function _init(){
		if (!$this->CI->config->item('active_site'))
		{			
			$this->CI->lang->load('app.lang');
			$website_name = $this->CI->config->item('aplication_name');
			$message = sprintf(
				$this->CI->lang->line('FAL_turned_off_message')
				,$website_name
			);
			$data['website_name'] = $website_name;
			$data['message'] = $message;
            echo $this->CI->load->view($this->CI->config->item('layout_dir').'/turned_off', $data, true);
            exit;
		}
	}	
	
	function index()
	{
	    if(b_logged()){
			$data['main_content'] = $this->load->view('panel/panel','',true);
		}else{
			$data['main_content'] = $this->load->view('login/login','',true);
		}
		
		$this->load->view($this->config->item('layout_dir').'/template', $data);		

	}
}
?>