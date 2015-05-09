<?php

class Pajak_hiburan extends Base_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('pajak_hiburan_model', 'data_model');
  }

  public function index()
  {
  }
  
  public function daftar_oa()
  {
    $data['breadcrumbs'] = 'Daftar Pajak Hiburan (Official Assessment)';
    $data['title'] = PRODUCT.' - '.$data['breadcrumbs'];
    $data['modul'] = 'pajak_hiburan';
    $data['link_daftar'] = '/get_daftar_oa';
    $data['link_form'] = '/form_oa';
    $data['main_content'] = 'pajak_hiburan_view';
    $data['tipe'] = 'oa';
    $this->load->view('layout/template',$data);
  }  
  
  public function daftar_sa()
  {
    $data['breadcrumbs'] = 'Daftar Pajak Hiburan (Self Assessment)';
    $data['title'] = PRODUCT.' - '.$data['breadcrumbs'];
    $data['modul'] = 'pajak_hiburan';
    $data['link_daftar'] = '/get_daftar_sa';
    $data['link_form'] = '/form_sa';
    $data['main_content'] = 'pajak_hiburan_view';
    $data['tipe'] = 'sa';
    $this->load->view('layout/template',$data);
  } 

  public function get_daftar_oa()
  {
    $this->get_daftar('OA');
  }
  
  public function get_daftar_sa()
  {
    $this->get_daftar('SA');
  }

  private function get_daftar($tipe)
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
        "search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null,
        "tipe" => $tipe,
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
          $result[$i]['NOMOR_SPT'],
          $result[$i]['TANGGAL_SPT'],
          $result[$i]['PERIODE_AWAL'],
          $result[$i]['PERIODE_AKHIR'],
          $result[$i]['NAMA_REKENING'],
          $result[$i]['NAMA_WP'],
          $result[$i]['LOKASI'],
          $result[$i]['JUMLAH_PAJAK']
      );
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  
  function get_tarif_hiburan($jumlah)
  {
    switch($jumlah)
    {
      case $jumlah <= 100 : $kode = '001'; break;
      case $jumlah <= 500 : $kode = '002'; break;
      case $jumlah <= 1000 : $kode = '003'; break;
      default: $kode = null; break;
    }
    
    $result = $this->data_model->get_tarif_hiburan_by_kode($kode);
    if (count($result) > 0)
    {
      $tarif = $result['NOMINAL'];
    }
    else
    {
      $tarif = 0;
    }
    
    echo json_encode($tarif);
  }

  public function form_oa($id=0)
  {
    $data['title'] = $this->app['app_name'];
    $data['modul'] = 'pajak_hiburan';
    $data['tipe'] = 'OA';
    $data['link_proses'] = 'proses';
    $data['link_back'] = '/daftar_oa';
    $data['form'] = '/form_oa';
    $data['header'] = 'Pajak Hiburan (Official Assessment)';
    $data['akses'] = $this->access;
    if ($id!==0)
    {
      $data['data'] = $this->data_model->get_data_by_id($id);
    }

    $data['main_content']='pajak_hiburan_form';
    $this->load->view('layout/template',$data);
  }
  
  public function form_sa($id=0)
  {
    $data['title'] = $this->app['app_name'];
    $data['modul'] = 'pajak_hiburan';
    $data['tipe'] = 'SA';
    $data['link_proses'] = 'proses';
    $data['link_back'] = '/daftar_sa';
    $data['form'] = '/form_sa';
    $data['header'] = 'Pajak Hiburan (Self Assessment)';
    $data['akses'] = $this->access;
    if ($id!==0)
    {
      $data['data'] = $this->data_model->get_data_by_id($id);
    }

    $data['main_content']='pajak_hiburan_form';
    $this->load->view('layout/template',$data);
  }

  public function proses()
  {
    $response = (object) NULL;
    $this->load->library('form_validation');

    $this->form_validation->set_rules('nospt', 'Nomor SPT', 'required|trim|max_length[20]');
    $this->form_validation->set_rules('tgl', 'Tanggal', 'required|trim');
    $this->form_validation->set_rules('idrek', 'Jenis Pajak/Retribusi', 'required|trim');
    $this->form_validation->set_rules('npwpd', 'NPWPD', 'required|trim');
    $this->form_validation->set_rules('nama', 'Nama WP/WR', 'required|trim');
    $this->form_validation->set_rules('alamat', 'Alamat WP/WR', 'required|trim');
    $this->form_validation->set_rules('awal', 'Periode Awal', 'required|trim');
    $this->form_validation->set_rules('akhir', 'Periode Akhir', 'required|trim');
    $this->form_validation->set_rules('omset', 'Dasar Pengenaan', 'required|trim');
    $this->form_validation->set_rules('jml', 'Jumlah', 'required|trim');
    $this->form_validation->set_rules('lokasi', 'Lokasi', 'required|trim');
    $this->form_validation->set_rules('uraian', 'Uraian', 'required|trim');

    /* TODO : cek rincian ada isinya atau tidak */

    $this->form_validation->set_message('required', '%s tidak boleh kosong.');
    $this->form_validation->set_message('max_length', '%s tidak boleh melebihi %s karakter.');
    $this->form_validation->set_message('integer', '%s harus angka.');

    if ($this->form_validation->run() == TRUE){
      $awal = strtotime(prepare_date($this->input->post('awal')));
      $akhir = strtotime(prepare_date($this->input->post('akhir')));
      $tarif_hiburan = $this->input->post('tarif_hiburan');
      
      if ($akhir < $awal)
      {
        $response->isSuccess = FALSE;
        $response->message = 'Periode Akhir tidak boleh kurang dari Periode Awal';
      }
      else if($tarif_hiburan === '0')
      {
        $response->isSuccess = FALSE;
        $response->message = 'Tarif hiburan untuk jumlah tersebut belum ada';
      }
      else
      {
        $this->data_model->fill_data();
        $success = $this->data_model->save_data();

        if (!$success)
        {
          $response->isSuccess = TRUE;
          $response->message = 'Data berhasil disimpan';
          $response->id = $this->data_model->id;
          $response->sql = $this->db->queries;
          $response->tarif_hiburan = $tarif_hiburan;
        }
        else
        {
          $response->isSuccess = FALSE;
          $response->message = 'Data gagal disimpan';
          $response->sql = $this->db->queries;
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

  public function prev($id=0, $tipe)
  {
    $response = (object) NULL;
    $response->isSuccessful = FALSE;
    if ($id!==0)
    {
      $result = $this->data_model->get_prev_id($id, $tipe);
      if ($result)
      {
        $response->isSuccessful = TRUE;
        $response->id = $result;
      }
    }
    echo json_encode($response);
  }

  public function next($id=0, $tipe)
  {
    $response = (object) NULL;
    $response->isSuccessful = FALSE;
    if ($id!==0)
    {
      $result = $this->data_model->get_next_id($id, $tipe);
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
    $tipe = $this->input->post('tipe');
    $result = $this->data_model->check_dependency($id);
    $response = (object) NULL;
    if ($result) {
      // bisa dihapus
      $this->data_model->delete_data($id);
      $response->isSuccess = TRUE;
      $response->message = 'Data Pajak Hiburan (Self Assessment) telah dihapus.';
    }
    else{
      // ada dependensi, tampilkan pesan kesalahan
      $response->isSuccess = FALSE;
      $response->message = 'Data tidak dapat dihapus karena sudah ada penetapan.';
    }
    echo json_encode($response);
  }

}