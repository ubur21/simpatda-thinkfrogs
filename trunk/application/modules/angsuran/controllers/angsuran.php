<?php

class Angsuran extends Base_Controller {

  public function __construct()
  {
    parent::__construct();
		$this->load->model('angsuran_model','data_model');
  }

  public function index()
  {
    $this->daftar();
  }

  public function daftar()
  {
    $data['breadcrumbs'] = 'Daftar Pembayaran Angsuran';
    $data['title'] = PRODUCT.' - '.$data['breadcrumbs'];
    $data['modul'] = 'angsuran';
    $data['main_content'] = 'angsuran_view';
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
      $sisa = $result[$i]['JUMLAH_PAJAK'] - $result[$i]['ANGSURAN'];
      $response->rows[$i]['id'] = $result[$i]['ID_SPT'];
      $response->rows[$i]['cell'] = array(
          $result[$i]['NOMOR_KOHIR'],
          $result[$i]['NAMA_REKENING'],
          $result[$i]['NAMA_WP'],
          $result[$i]['JUMLAH_PAJAK'],
          $result[$i]['ANGSURAN'],
          $sisa
      );
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  function get_kohir()
  {
    $result = $this->data_model->get_nomor_kohir();
    $response = (object) NULL;
    $response->sql = $this->db->queries;
    $response->len = count($result);
    if($response->len > 0) // tidak ada data
    {
      foreach ($result as $row) {
        $response->id_spt = $row['ID_SPT'];
        $response->kohir = $row['NOMOR_KOHIR'];
      }
    }
    

    echo json_encode($response);
  }

  function get_ketetapan()
  {
    $result = $this->data_model->get_jml_ketetapan();
    $response = (object) NULL;
    $response->sql = $this->db->queries;
    $response->len = count($result);
    if($response->len > 0) // tidak ada data
    {
      foreach ($result as $row) {
        $response->ketetapan = $row['JUMLAH_PAJAK'];
      }
    }

    echo json_encode($response);

  }

  function get_angsuran()
  {
    $result = $this->data_model->get_data_angsuran();
    $response = (object) NULL;
    $response->sql = $this->db->queries;
    $response->len = count($result);
    if ($result){
      $terbayar = 0;
      for($i=0; $i<count($result); $i++){
        $response->rows[$i]['angsuranke'] = $i + 1;
        $response->rows[$i]['id_bayar'] = $result[$i]['ID_PEMBAYARAN'];
        $response->rows[$i]['tgl'] = $result[$i]['TANGGAL'];
        $response->rows[$i]['terbayar'] = $terbayar;
        $response->rows[$i]['angsuran'] = $result[$i]['TELAH_DIBAYAR'];
        $response->rows[$i]['jml_bayar'] = $result[$i]['JUMLAH_BAYAR'];
        $terbayar = $terbayar + $result[$i]['TELAH_DIBAYAR'];
      }
    }

    echo json_encode($response);    
  }

  public function form($id=0)
  {
    $data['title'] = PRODUCT;
    $data['modul'] = 'angsuran';
    $data['header'] = 'Pembayaran Angsuran';
    $data['akses'] = $this->access;
    if ($id!==0)
    {
      $data['data'] = $this->data_model->get_data_by_id($id);
      $data['lunas'] = $this->data_model->is_lunas($id);
    }

    $data['main_content']='angsuran_form';
    $this->load->view('layout/template',$data);
  }

  public function proses()
  {
    $response = (object) NULL;
    $this->load->library('form_validation');

    $this->form_validation->set_rules('namawp', 'Wajib Pajak/Retribusi', 'required|trim');
    $this->form_validation->set_rules('tgl', 'Tanggal', 'required|trim');
    $this->form_validation->set_rules('pajak', 'Jenis Pajak/Retribusi', 'required|trim');
    $this->form_validation->set_rules('kohir', 'Nomor Kohir', 'required|trim');
    $this->form_validation->set_rules('angsuran', 'Angsuran', 'required|trim|greater_than[0]|numeric');

    /* TODO : cek rincian ada isinya atau tidak */
 
    $this->form_validation->set_message('required', '%s tidak boleh kosong.');
    $this->form_validation->set_message('greater_than', '%s harus lebih dari 0.');
    $this->form_validation->set_message('numeric', '%s harus numeric.');

    if ($this->form_validation->run() == TRUE){      
      $this->data_model->fill_data();
      $cek_lebih = $this->data_model->cek_lebih();
      $cek_lunas = $this->data_model->cek_lunas();
      
      if ($cek_lunas == TRUE)
      {
        $response->isSuccess = FALSE;
        $response->message = 'Data gagal disimpan, Angsuran sudah lunas';
        $response->sql = $this->db->queries;
      }
      else
      {
        if ($cek_lebih == TRUE)
        {
          $response->isSuccess = FALSE;
          $response->message = 'Data gagal disimpan, Angsuran terlalu banyak';
          $response->sql = $this->db->queries;
        }
        else
        {
          $success = $this->data_model->save_data();

          if (!$success)
          {
            $response->isSuccess = TRUE;
            $response->message = 'Data berhasil disimpan';
            $response->sql = $this->db->queries;
            $response->id_spt = $this->data_model->id_spt;
            $response->lunas = $this->data_model->lunas;
          }
          else
          {
            $response->isSuccess = FALSE;
            $response->message = 'Data gagal disimpan';
            $response->sql = $this->db->queries;
          }
        }
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