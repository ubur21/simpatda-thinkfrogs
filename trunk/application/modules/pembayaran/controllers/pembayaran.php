<?php

class Pembayaran extends Admin_Controller {

  public function __construct()
  {
    parent::__construct();
    $user_data = $this->session->userdata;
    $this->load->vars($user_data);

    $this->load->model('pembayaran_model','data_model');
  }

  public function index()
  {
    $this->daftar();
  }

  public function daftar()
  {
    $data['breadcrumbs'] = 'Daftar Pembayaran';
    $data['title'] = PRODUCT.' - '.$data['breadcrumbs'];
    $data['modul'] = 'pembayaran';
    $data['main_content'] = 'pembayaran_view';
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
          $result[$i]['NOMOR_SPT'],
          $result[$i]['NOMOR_KOHIR'],
          $result[$i]['NPWPD'],
          $result[$i]['TANGGAL_SPT'],
          $result[$i]['TANGGAL_BAYAR'],
          $result[$i]['JUMLAH_BAYAR'],
      );
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  public function form($id=0)
  {
    $data['title'] = PRODUCT;
    $data['modul'] = 'pembayaran';
    $data['header'] = 'Pembayaran ke BKP';
    $this->load->model('auth/login_model', 'auth');
    $data['akses'] = $this->auth->get_level_akses($this->uri->slash_segment(1));
    if ($id!==0)
    {
      $data['data'] = $this->data_model->get_data_by_id($id);
    }

    $data['main_content']='pembayaran_form';
    $this->load->view('layout/template',$data);
  }

  public function proses()
  {
    $response = (object) NULL;
    $this->load->library('form_validation');

    $this->form_validation->set_rules('no', 'Nomor', 'required|trim|max_length[50]|callback__cek_nomor');
    $this->form_validation->set_rules('tgl', 'Tanggal', 'required|trim');
    $this->form_validation->set_rules('nominal', 'Nominal', 'required|trim');
    $this->form_validation->set_rules('id_skpd', 'SKPD', 'required|trim|integer');
    $this->form_validation->set_rules('id_sd', 'Sumber Dana', 'required|trim|integer');
    $this->form_validation->set_rules('deskripsi', 'Keterangan', 'trim');
    $this->form_validation->set_rules('kaskpd', 'Kepala SKPD', 'trim|integer');
    $this->form_validation->set_rules('bt', 'Bendahara Penerimaan', 'trim|integer');

    /* TODO : cek rincian ada isinya atau tidak */

    $this->form_validation->set_message('required', '%s tidak boleh kosong.');
    $this->form_validation->set_message('max_length', '%s tidak boleh melebihi %s karakter.');
    $this->form_validation->set_message('_cek_nomor', '%s sudah ada.');

    if ($this->form_validation->run() == TRUE){
      $this->data_model->fill_data();
      $success = $this->data_model->save_data();

      if (!$success)
      {
        $response->isSuccess = TRUE;
        $response->message = 'Data berhasil disimpan';
        $response->id = $this->data_model->id;
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

  public function _cek_nomor($nomor)
  {
    return $this->data_model->cek_duplikasi_nomor($nomor);
  }

  public function prev($id=0)
  {
    $response = (object) NULL;
    $response->isSuccessful = FALSE;
    if ($id!==0)
    {
      $result = $this->data_model->get_prev_id($id);
      if ($result)
      {
        $response->isSuccessful = TRUE;
        $response->id = $result;
      }
    }
    echo json_encode($response);
  }

  public function next($id=0)
  {
    $response = (object) NULL;
    $response->isSuccessful = FALSE;
    if ($id!==0)
    {
      $result = $this->data_model->get_next_id($id);
      if ($result)
      {
        $response->isSuccessful = TRUE;
        $response->id = $result;
      }
    }
    echo json_encode($response);
  }

  public function hapus()
  {
    $id = $this->input->post('id');
    $result = $this->data_model->check_dependency($id);
    $response = (object) NULL;
    if ($result) {
      // bisa dihapus
      $this->data_model->delete_data($id);
      $response->isSuccess = TRUE;
      $response->message = 'Wajib Pajak/Retribusi telah dihapus';
    }
    else{
      // ada dependensi, tampilkan pesan kesalahan
      $response->isSuccess = FALSE;
      $response->Message = 'Wajib Pajak/Retribusi masih memiliki data. Tidak bisa dihapus.';
    }
    echo json_encode($response);
  }

}