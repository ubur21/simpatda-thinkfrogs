<?php

class Umum extends Base_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('umum_model','data_model');
  }

     public function get_no_spt()
  {
    $result = ((int)$this->data_model->get_nomor_spt());
    
    $nomor = date('d/m/Y')."/".$result;
    echo json_encode($nomor);
  }
  

}
