<?php 

class Home extends CI_Controller{
	public function __construct()
	{
		parent:: __construct();
    
    $user_data = $this->session->userdata;
    $this->load->vars($user_data);
	}
	
	function index()
	{
		$data['title'] = 'SIMPATDA';
		$data['main_content'] = 'v_home';
		$this->load->view('layout/template', $data);
	}
}