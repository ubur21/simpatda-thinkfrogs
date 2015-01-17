<?php

class Anggaran extends Base_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('anggaran_model','data_model');
  }

  public function index()
  {
    $this->daftar();
  }

  public function daftar()
  {
    $data['title'] = $this->app['app_name'].' - '.'Anggaran';
    $data['modul'] = 'anggaran';
    $data['main_content'] = 'anggaran_view';
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

    // apakah isi data array is null ?
    $data = $this->data_model->get_data($req_param);
    if($data[0]['ID_ANGGARAN'] === null) {
      $count = 0;
    } else {
      $count = count($this->data_model->get_data($req_param, TRUE));
    }
    
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
      $response->rows[$i]['id'] = $result[$i]['ID_ANGGARAN'];
      $response->rows[$i]['cell'] = array(
          $result[$i]['ID_ANGGARAN'],
          $result[$i]['ID_SKPD'],
          $result[$i]['TAHUN'],
          $result[$i]['KODE_SKPD'],
          $result[$i]['NAMA_SKPD'],
          $result[$i]['MURNI'],
          $result[$i]['PAK'],
      );
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }
  
   public function form($id=0)
  {
    $data['title'] = $this->app['app_name'];
    $data['modul'] = 'anggaran';
    $data['akses'] = $this->access;
    if ($id!==0)
    {
      $data['data'] = $this->data_model->get_data_by_id($id);
    }
    
    $data['main_content']='anggaran_form';
    $this->load->view('layout/template',$data);
  }

  public function proses()
  {
     $response = (object) NULL;
    $this->load->library('form_validation');

    $this->form_validation->set_rules('tahun', 'Tahun', 'required|trim|integer|max_length[4]');
    $this->form_validation->set_rules('id_skpd', 'SKPD', 'required|trim');
    /* TODO : cek rincian ada isinya atau tidak */

    $this->form_validation->set_message('required', '%s tidak boleh kosong.');
    $this->form_validation->set_message('max_length', '%s tidak boleh melebihi %s karakter.');
    $this->form_validation->set_message('integer', '%s harus angka.');

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

  public function rinci($id=0)
  {
    $result = $this->data_model->get_rinci_by_id($id);

    $response = (object) NULL;
    if ($result){
      for($i=0; $i<count($result); $i++)
      {
        $response->rows[$i]['id'] = $result[$i]['ID_REKENING'];
        $response->rows[$i]['cell']=array(
                        $result[$i]['ID_REKENING'],
                        $result[$i]['KODE_REKENING'],
                        $result[$i]['NAMA_REKENING'],
                        $result[$i]['PAGU_MURNI'],
                        $result[$i]['PAGU_PAK'],
                    );
      }
    }
    echo json_encode($response);
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
    $response = (object) NULL;
    $result = $this->data_model->delete_data($id);
    if (!$result) {
      // bisa dihapus
      $response->isSuccess = TRUE;
      $response->message = 'Data Anggaran telah dihapus';
    }
    else{
      // ada dependensi, tampilkan pesan kesalahan
      $response->isSuccess = FALSE;
      $response->message = 'Data Anggaran tidak bisa dihapus.';
    }
    echo json_encode($response);
  }

}