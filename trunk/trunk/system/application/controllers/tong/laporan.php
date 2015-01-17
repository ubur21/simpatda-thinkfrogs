<?php
class Laporan extends Controller {

	function Laporan()
	{
		parent::Controller();
	}
	
	function index()
	{
		$data['title'] = "SIP TU - Laporan";
		$data['main_content'] = 'laporan/index';
		$this->load->view('layout/template', $data);
	}
}
?>