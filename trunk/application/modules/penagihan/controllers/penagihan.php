<?php

class Penagihan extends Base_Controller {

  public function __construct()
  {
    parent::__construct();
    $user_data = $this->session->userdata;
    $this->load->vars($user_data);

    $this->load->model('penagihan_model','data_model');
  }

  public function index()
  {
    $data['breadcrumbs'] = 'Daftar Penagihan Pajak/Retribusi Daerah';
    $data['title'] = $this->app['app_name'].' - '.$data['breadcrumbs'];
    $data['modul'] = 'penagihan';
    $this->load->model('auth/login_model', 'auth');
    $data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
    $data['main_content'] = 'penagihan_view';
    $this->load->view('layout/template',$data);
  }

  function getnpwpd()
  {
    $result = $this->data_model->getNPWPD();
    $response = (object) NULL;
    $response->sql = $this->db->queries;
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->rows[$i]['id_spt'] = $result[$i]['ID_SPT'];
        $response->rows[$i]['tgl_penetapan'] = $result[$i]['TANGGAL'];
        $response->rows[$i]['tgl_batas'] = $result[$i]['BATAS_BAYAR'];
        $response->rows[$i]['nama_wp'] = $result[$i]['NAMA_WP'];
        $response->rows[$i]['alamat_wp'] = $result[$i]['ALAMAT_WP'];
        $response->rows[$i]['awal'] = $result[$i]['PERIODE_AWAL'];
        $response->rows[$i]['akhir'] = $result[$i]['PERIODE_AKHIR'];
        $response->rows[$i]['jumlah'] = $result[$i]['JUMLAH_PAJAK'];
      }
    }

    echo json_encode($response);    
  }

}