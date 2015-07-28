<?php

class Tbp extends Base_Controller {

  public function __construct()
  {
    parent::__construct();
		$this->load->model('tbp_model','data_model');
  }

  public function index()
  {
    $data['breadcrumbs'] = 'Daftar Tanda Bukti Setoran';
    $data['title'] = PRODUCT.' - '.$data['breadcrumbs'];
    $data['modul'] = 'tbp';
    $data['main_content'] = 'tbp_index';
    $this->load->view('layout/template',$data);
  }
  
}//end class

?>
  

