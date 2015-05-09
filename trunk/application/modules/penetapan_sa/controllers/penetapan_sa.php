<?php

class Penetapan_sa extends Base_Controller {

  public function __construct()
  {
    parent::__construct();
    $user_data = $this->session->userdata;
    $this->load->vars($user_data);

    $this->load->model('penetapan_sa_model','data_model');
  }

  public function index()
  {
    $data['breadcrumbs'] = 'Daftar Penetapan SA Pajak/Retribusi';
    $data['title'] = $this->app['app_name'].' - '.$data['breadcrumbs'];
    $data['modul'] = 'penetapan_sa';
    $data['main_content'] = 'penetapan_view';
    $this->load->view('layout/template',$data);
  }

  public function form($id=0)
  {
    $data['title'] = PRODUCT;
    $data['modul'] = 'penetapan_sa';
    $data['akses'] = $this->access;

    $data['main_content']='penetapan_form';
    $this->load->view('layout/template',$data);
  }
  
  public function get_daftar()
  {
    $page = $_REQUEST['page']; // get the requested page
    $limit = $_REQUEST['rows']; // get how many rows we want to have into the grid
    $sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort
    $sord = $_REQUEST['sord']; // get the direction if(!$sidx) $sidx =1;

    $req_param = array (
        "sort_by" => $sidx,
        "sort_direction" => $sord,
        "limit" => null,
        "search" => $_REQUEST['_search'],
        "search_field" => isset($_REQUEST['searchField'])?$_REQUEST['searchField']:null,
        "search_operator" => isset($_REQUEST['searchOper'])?$_REQUEST['searchOper']:null,
        "search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null
    );

    $count = $this->data_model->get_data($req_param, TRUE);
    $response = (object) NULL;
    if($count == 0) // tidak ada data
    {
      echo json_encode($response);
      return '';
    }

    $total_pages = ceil($count/$limit);

    if ($page > $total_pages)
    $page = $total_pages;
    $start = $limit * $page - $limit;
    if($start < 0) $start = 0;
    $req_param['limit'] = array(
        'start' => $start,
        'end' => $limit
    );

    $result = $this->data_model->get_data($req_param);

    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;

    for($i=0; $i<count($result); $i++)
    {
      $response->rows[$i]['id'] = $result[$i]['ID_SPT'];
      $response->rows[$i]['cell'] = array(
          $result[$i]['ID_SPT'],
          $result[$i]['NOMOR_KOHIR'],
          $result[$i]['NPWPD'],
          $result[$i]['NAMA_WP'],
          $result[$i]['NAMA_REKENING'],
          $result[$i]['JUMLAH_PAJAK'],
          $result[$i]['TANGGAL'],
          $result[$i]['BATAS_BAYAR'],
      );
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  function getnpwpd()
  {
    $idrek = $this->input->post('idrek') ? $this->input->post('idrek') : 0;
    $result = $this->data_model->getNPWPD($idrek);
    $response = (object) NULL;
    $response->sql = $this->db->queries;
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->rows[$i]['id_spt'] = $result[$i]['ID_SPT'];
        $response->rows[$i]['npwpd'] = $result[$i]['NPWPD'];
        $response->rows[$i]['nama_wp'] = $result[$i]['NAMA_WP'];
        $response->rows[$i]['id_wp'] = $result[$i]['ID_WAJIB_PAJAK'];
        $response->rows[$i]['nama_rek'] = $result[$i]['NAMA_REKENING'];
        $response->rows[$i]['jumlah'] = $result[$i]['JUMLAH_PAJAK'];
        $response->rows[$i]['awal'] = $result[$i]['PERIODE_AWAL'];
        $response->rows[$i]['akhir'] = $result[$i]['PERIODE_AKHIR'];
      }
    }

    echo json_encode($response);    
  }
  
  public function coba(){
	$hasil = $this->data_model->getDSpt(10);
	var_dump($hasil);
	echo $hasil[0]['ID_SPT'];
  }

  public function proses()
  {
    $response = (object) NULL;
    $this->load->library('form_validation');

    $this->form_validation->set_rules('kd_rek', 'Pajak/Retribusi', 'required|trim');
    $this->form_validation->set_rules('tgl', 'Tanggal Penetapan', 'required|trim');
    $this->form_validation->set_rules('batas', 'Tanggal Batas Bayar', 'required|trim');

    /* TODO : cek rincian ada isinya atau tidak */

    $this->form_validation->set_message('required', '%s tidak boleh kosong.');

    if ($this->form_validation->run() == TRUE){
      $this->data_model->fill_data();
      $success = $this->data_model->save_data();

      if (!$success)
      {
        $response->isSuccess = TRUE;
        $response->message = 'Data berhasil diproses';
        $response->sql = $this->db->queries;
      }
      else
      {
        $response->isSuccess = FALSE;
        $response->message = 'Data gagal diproses';
        $response->sql = $this->db->queries;
      }
    }
    else
    {
      $response->isSuccess = FALSE;
      $response->message = validation_errors();
    }

    echo json_encode($response);
  }

  public function _cek_nomor($nomor)
  {
    return $this->data_model->cek_duplikasi_nomor($nomor);
  }



   public function generateReport()
  {
    $id=$_REQUEST['id'];


    
    $data['data'] = $this->data_model->get_data_by_id(62);
    $this->load->view('report',$data);
    
    
    
    $html = $this->output->get_output();
    
    
    $this->load->library('dompdf_gen');
    
    
    $this->dompdf->load_html($html);
    
    $this->dompdf->render();
    
    
    $this->dompdf->stream("skpd_hotel.pdf",array('Attachment'=>0));
    
    
  }


}