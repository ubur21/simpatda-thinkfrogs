<?php

class Feature extends Base_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('feature_model','data_model');
  }

   public function pemeriksaan()
  {
    $data['breadcrumbs'] = 'Pemeriksaan Pajak';
   // $data['title'] = PRODUCT.' - '.$data['breadcrumbs'];
    $data['modul'] = 'feature';
   // $data['link_daftar'] = '/get_daftar_sa';
   // $data['link_form'] = '/form_sa';
    $data['main_content'] = 'pemeriksaan';
    //$data['tipe'] = 'sa';
    $this->load->view('layout/template',$data);
  } 
  
  public function aging()
  {
    $data['breadcrumbs'] = 'Report Aging';
   // $data['title'] = PRODUCT.' - '.$data['breadcrumbs'];
    $data['modul'] = 'feature';
   // $data['link_daftar'] = '/get_daftar_sa';
   // $data['link_form'] = '/form_sa';
    $data['main_content'] = 'aging';
    //$data['tipe'] = 'sa';
    $this->load->view('layout/template',$data);
  } 
  
  public function reminder()
  {
    $data['breadcrumbs'] = 'Reminder Pajak';
   // $data['title'] = PRODUCT.' - '.$data['breadcrumbs'];
    $data['modul'] = 'feature';
   // $data['link_daftar'] = '/get_daftar_sa';
   // $data['link_form'] = '/form_sa';
    $data['main_content'] = 'reminder';
    //$data['tipe'] = 'sa';
    $this->load->view('layout/template',$data);
  } 

}
