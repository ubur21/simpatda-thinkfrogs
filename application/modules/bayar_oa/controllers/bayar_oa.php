<?php

class Bayar_oa extends Base_Controller {

  public function __construct()
  {
    parent::__construct();
		$this->load->model('bayaroa_model','data_model');
  }

  public function index()
  {
    $this->daftar();
  }

  public function daftar()
  {
    $data['breadcrumbs'] = 'Daftar Pembayaran Official Assessment';
    $data['title'] = PRODUCT.' - '.$data['breadcrumbs'];
    $data['modul'] = 'bayar_oa';
    $data['main_content'] = 'bayaroa_view';
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
      $response->rows[$i]['id'] = $result[$i]['ID_PEMBAYARAN'];
      $response->rows[$i]['cell'] = array(
          $result[$i]['TANGGAL'],
          $result[$i]['NAMA_REKENING'],
          $result[$i]['NAMA_WP'],
          $result[$i]['NOMOR_KOHIR'],
          $result[$i]['JUMLAH_PAJAK'],
          $result[$i]['TELAH_DIBAYAR'],
          $result[$i]['SISA'],
      );
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  function getspt()
  {
    $id_spt = $this->input->post('id_spt') ? $this->input->post('id_spt') : 0;
    $idpjk = $this->input->post('idpjk') ? $this->input->post('idpjk') : 0;
	
    $result = $this->data_model->getSPT($id_spt, $idpjk);
    $response = (object) NULL;
    $response->sql = $this->db->queries;
    $response->len = count($result);
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->rows[$i]['id_spt'] = $result[$i]['ID_SPT'];
        $response->rows[$i]['kohir'] = $result[$i]['NOMOR_KOHIR'];
        $response->rows[$i]['rek'] = $result[$i]['NAMA_REKENING'];
        $response->rows[$i]['awal'] = $result[$i]['PERIODE_AWAL'];
        $response->rows[$i]['akhir'] = $result[$i]['PERIODE_AKHIR'];
        $response->rows[$i]['jml'] = $result[$i]['JUMLAH_PAJAK'];
        $response->rows[$i]['sisa'] = $result[$i]['JUMLAH_PAJAK'];
      }
    }

    echo json_encode($response);    
  }

  public function form($id=0)
  {
    $data['title'] = PRODUCT;
    $data['modul'] = 'bayar_oa';
    $data['header'] = 'Pembayaran ke BKP';
    $data['akses'] = $this->access;

    $data['main_content']='bayaroa_form';
    $this->load->view('layout/template',$data);
  }

  public function proses()
  {
    $response = (object) NULL;
    $this->load->library('form_validation');

    $this->form_validation->set_rules('namawp', 'Wajib Pajak/Retribusi', 'required|trim');
    $this->form_validation->set_rules('tgl', 'Tanggal', 'required|trim');
    $this->form_validation->set_rules('pajak', 'Jenis Pajak/Retribusi', 'required|trim');

    /* TODO : cek rincian ada isinya atau tidak */
 
    $this->form_validation->set_message('required', '%s tidak boleh kosong.');

    if ($this->form_validation->run() == TRUE){
      $this->data_model->fill_data();
      $success = $this->data_model->save_data();

      if (!$success)
      {
        $response->isSuccess = TRUE;
        $response->message = 'Data berhasil disimpan';
        $response->sql = $this->db->queries;
      }
      else
      {
        $response->isSuccess = FALSE;
        $response->message = 'Data gagal disimpan';
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

}