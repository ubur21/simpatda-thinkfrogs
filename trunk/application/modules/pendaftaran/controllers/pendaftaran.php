<?php

class Pendaftaran extends Base_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('pendaftaran_model','data_model');
  }

  public function index()
  {
    $this->daftar();
  }

  public function daftar()
  {
    $data['title'] = $this->app['app_name'].' - '.'Pendaftaran';
    $data['modul'] = 'pendaftaran';
    $data['main_content'] = 'pendaftaran_view';
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
      $response->rows[$i]['id'] = $result[$i]['ID_WAJIB_PAJAK'];
      $response->rows[$i]['cell'] = array(
          $result[$i]['JENIS'],
          $result[$i]['GOLONGAN'],
          $result[$i]['NOMOR'],
          $result[$i]['NOMOR_REG'],
          $result[$i]['NAMA_WP'],
          $result[$i]['ALAMAT_WP'],
          $result[$i]['JENIS_USAHA'],
          $result[$i]['NPWPD'],
      );
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }
  
  public function get_no()
  {
    $result = ((int)$this->data_model->get_nomor()) + 1;
    
    $len = strlen($result);
    $nol = '';
    while($len < 6)
    {
      $nol .= '0';
      $len++;
    }
    $nomor = $nol;
    $nomor .= $result;
    echo json_encode($nomor);
  }
  
  public function get_kelurahan($kode)
  {
    $result = $this->data_model->get_daftar_kelurahan($kode)->result_array();
    $kelurahan = array();
    foreach($result as $row)
    {
      $kelurahan[$row['KODE_KELURAHAN']] = $row['NAMA_KELURAHAN'];
    }

    echo json_encode($kelurahan);
  }

  public function form($id=0)
  {
    $data['title'] = $this->app['app_name'];
    $data['modul'] = 'pendaftaran';
    $data['akses'] = $this->access;
    if ($id!==0)
    {
      $data['data'] = $this->data_model->get_data_by_id($id);
    }
    
    $data['kecamatan'] = $this->data_model->get_daftar_kecamatan()->result_array();
    $data['jenis_usaha'] = $this->data_model->get_jenis_usaha()->result_array();

    $data['main_content']='pendaftaran_form';
    $this->load->view('layout/template',$data);
  }

  public function proses()
  {
    $response = (object) NULL;
    $this->load->library('form_validation');

    $this->form_validation->set_rules('jenis', 'Tipe', 'required|trim');
    $this->form_validation->set_rules('gol', 'Golongan', 'required|trim');
    $this->form_validation->set_rules('no', 'Nomor', 'required|trim|max_length[10]|callback__cek_nomor');
    $this->form_validation->set_rules('noreg', 'No. Registrasi', 'required|trim|max_length[50]');
    $this->form_validation->set_rules('npwpd', 'NPWPD', 'required|trim|max_length[20]');
    $this->form_validation->set_rules('tgl', 'Tanggal NPWPD', 'required|trim');
    $this->form_validation->set_rules('tglkirim', 'Tanggal Kirim', 'required|trim');
    $this->form_validation->set_rules('tglkembali', 'Tanggal Kembali', 'required|trim');
    $this->form_validation->set_rules('nama', 'Nama WP/WR', 'required|trim|max_length[50]');
    $this->form_validation->set_rules('alamat', 'Alamat WP/WR', 'required|trim|max_length[50]');
    $this->form_validation->set_rules('kecamatan', 'Kecamatan WP/WR', 'required|integer');
    $this->form_validation->set_rules('kelurahan', 'Kelurahan WP/WR', 'required|integer');
    $this->form_validation->set_rules('telp', 'No Telepon WP/WR', 'required|trim|max_length[20]');
    $this->form_validation->set_rules('usaha', 'Jenis Usaha WP/WR', 'required|integer');
    $this->form_validation->set_rules('namap', 'Nama Pemilik', 'required|trim|max_length[50]');
    $this->form_validation->set_rules('alamatp', 'Alamat Pemilik', 'required|trim|max_length[50]');
    $this->form_validation->set_rules('kecamatanp', 'Kecamatan Pemilik', 'required|integer');
    $this->form_validation->set_rules('kelurahanp', 'Kelurahan Pemilik', 'required|integer');
    $this->form_validation->set_rules('telpp', 'No Telepon Pemilik', 'required|trim|max_length[20]');

    /* TODO : cek rincian ada isinya atau tidak */

    $this->form_validation->set_message('required', '%s tidak boleh kosong.');
    $this->form_validation->set_message('max_length', '%s tidak boleh melebihi %s karakter.');
    $this->form_validation->set_message('_cek_nomor', '%s sudah ada.');

    if ($this->form_validation->run() == TRUE){
      $tgl = strtotime(prepare_date($this->input->post('tgl')));
      $tglkirim = strtotime(prepare_date($this->input->post('tglkirim')));
      $tglkembali = strtotime(prepare_date($this->input->post('tglkembali')));
      
      if (($tglkirim - $tgl) < 0)
      {
        $response->isSuccess = FALSE;
        $response->message = 'Tanggal Kirim harus sama atau lebih dari Tanggal NPWPD';
      }
      else if (($tglkembali - $tglkirim) < 0)
      {
        $response->isSuccess = FALSE;
        $response->message = 'Tanggal Kembali harus sama atau lebih dari Tanggal Kirim';
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
      $response->message = 'Data tidak dapat terhapus karena sudah ada pendataan.';
    }
    echo json_encode($response);
  }

}