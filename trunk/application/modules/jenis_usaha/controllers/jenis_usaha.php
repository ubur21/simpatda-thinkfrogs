<?php

class Jenis_usaha extends Base_Controller {
  public function __construct()
  {
    parent::__construct();
    $this->load->model('jenis_usaha_model','data_model');
  }

  public function index()
  {
    $data['title'] = $this->app['app_name'].' - '.' Jenis Usaha';
    $data['daftar_url'] = base_url().'jenis_usaha/get_daftar';
    $data['ubah_url'] = base_url().'jenis_usaha/proses_form';
    $data['main_content'] = 'jenis_usaha_view';
    $data['akses'] = $this->access;
    $this->load->view('layout/template',$data);
  }

  public function proses_form()
  {
    $id = $this->input->post('id');
    $oper = $this->input->post('oper');

    // jika id atau oper tidak ada isinya, proses dihentikan
    if (!isset($id)) return;
    if (!isset($oper)) return;

    // proses hapus
    if ($oper === 'del'){
      // cek dependensi
      $result = $this->data_model->check_dependency($id);

      if($result){
        $this->data_model->delete_data($id);
        $response->isSuccess = TRUE;
        $response->message = 'Jenis Usaha sudah dihapus';
      }
      else
      {
        $response->isSuccess = FALSE;
        $response->message = 'Jenis Usaha tidak dapat dihapus, masih dipakai di tabel lain';
      }

      echo json_encode($response);
      return;
    }

    $this->load->library('form_validation');
    $this->form_validation->set_rules('kode', 'Kode Jenis Usaha', 'required|trim|max_lenght[5]|callback__duplikasi_kode');
    $this->form_validation->set_rules('nama', 'Uraian', 'required|trim|max_lenght[100]');

    $this->form_validation->set_message('_duplikasi_kode', '%s sudah ada.');

    if($this->form_validation->run() === TRUE)
    {
      if($oper === 'add')
      {
          $newid = $this->data_model->insert_data();
          $response->isSuccess = TRUE;
          $response->message = 'Jenis Usaha telah disimpan';
          $response->id = $newid;
      }
      else if ($oper === 'edit')
      {
        $this->data_model->update_data($id);
        $response->isSuccess = TRUE;
        $response->message = 'Jenis Usaha telah diubah';
      }
    }
    else
    {
      $response->isSuccess = FALSE;
      $response->message = validation_errors();
    }
    echo json_encode($response);
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

    $count = count($this->data_model->get_data($req_param, TRUE));
    $response = (object) NULL;
    if($count == 0) // tidak ada data
    {
      echo json_encode($response);
      return '';
    }

    if ($limit == -1)
    {
      $page = 1;
      $total_pages = 1;
      $req_param['limit'] = NULL;
    }
    else
    {
      $total_pages = ceil($count/$limit);

      if ($page > $total_pages)
      $page = $total_pages;
      $start = $limit * $page - $limit;
      if($start < 0) $start = 0;
      $req_param['limit'] = array(
          'start' => $start,
          'end' => $limit
      );
    }

    $result = $this->data_model->get_data($req_param);

    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;

    for($i=0; $i<count($result); $i++)
    {
      $response->rows[$i]['id'] = $result[$i]['ID_JENIS_USAHA'];
      $response->rows[$i]['cell'] = array(
          $result[$i]['KODE'],
          $result[$i]['URAIAN'],
      );
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }


  function _duplikasi_kode($str)
  {
    $id = $this->input->post('id') ? $this->input->post('id') : 0;
    if ($id === 'new') $id = 0;

    return $this->data_model->check_duplication($str, $id);
  }
}