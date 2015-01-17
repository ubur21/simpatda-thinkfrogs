<?php

class Rekening extends Base_Controller {
  public function __construct()
  {
    parent::__construct();

    $this->load->model('rekening_model','data_model');
    $this->load->library('form_validation');
  }

  public function index()
  {
    $data['title'] = $this->app['app_name'].' - '.' Kode Rekening';
    $data['main_content'] = 'rekening_view';
    $data['akses'] = $this->access;
    $this->load->view('layout/template',$data);
  }

	public function proses_form() 
	{
		$id = $this->input->post('id');
		$oper = $this->input->post('oper');
				
		$this->form_validation->set_rules('tipe', 'Tipe', 'required|trim|max_length[5]');
		$this->form_validation->set_rules('kel', 'Kelompok', 'required|trim|max_length[5]');
		$this->form_validation->set_rules('jenis', 'Jenis', 'required|trim|max_length[5]');
		$this->form_validation->set_rules('objek', 'Objek', 'required|trim|max_length[5]');
		$this->form_validation->set_rules('kode', 'Kode Rekening', 'required|trim|max_length[50]');
		$this->form_validation->set_rules('nama', 'Uraian', 'required|trim|max_length[100]');
	
    $this->form_validation->set_message('required', '%s harus diisi');
    $this->form_validation->set_message('max_length', 'Karakter %s maksimum : %s');

    if ($this->form_validation->run()==TRUE)
		{
      if($oper == 'edit') {
        $tarif_rp = trim($this->input->post('tarif_rp'));
        $tarif_persen = trim($this->input->post('tarif_persen'));
        if($tarif_rp == '' && $tarif_persen == '')
        {
          // harus diisi antara tarif_rp dengan tarif_% atau diisi keduanya
          $response->message = 'Isi salah satu antara Tarif Rp dengan Tarif %. Atau isi keduanya.';
          $response->isSuccess = FALSE;
        }
        else
        {
          $this->data_model->fill_data();
          if($id == 'new') {
            $result = $this->data_model->check_data();
            if ($result == FALSE){
              $this->data_model->insert_data();
              $response->message = 'Kode Rekening telah disimpan';
              $response->isSuccess = TRUE;
              $response->id = $this->data_model->id;
            }
            else 
            {
              // Kode Rekening sudah ada, tampilkan pesan kesalahan
              $response->message = 'Kode Rekening tersebut sudah ada.';
              $response->isSuccess = FALSE;
            }
          } 
          else {
            $rst = $this->data_model->check_data2();
            if ($rst == TRUE){
              $newid= $this->data_model->update_data($id);
              $response->message = 'Kode Rekening telah diubah.';
              $response->isSuccess = TRUE;
              $response->id = $newid;
            }
            else 
            {
              // Kode Rekening sudah ada, tampilkan pesan kesalahan
              $response->message = 'Kode Rekening tersebut sudah ada.';
              $response->isSuccess = FALSE;
            }
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

	public function hapus() 
	{
		$id = $this->input->post('id');
    $result = $this->data_model->check_dependency($id);
    if ($result == TRUE) {
      $delete = $this->data_model->delete_data($id);
      if($delete == TRUE) {
        // bisa dihapus      
        $response->message = 'Kode Rekening telah dihapus';
        $response->isSuccess = TRUE;
      } else {
        // tidak bisa dihapus      
        $response->message = 'Kode Rekening tidak bisa dihapus';
        $response->isSuccess = FALSE;
      }
      $response->sql = $this->db->queries;
    } 
    else 
    {
      // ada dependensi, tampilkan pesan kesalahan
      $response->message = 'Kode Rekening tidak bisa dihapus, masih dipakai di tabel lain.';
      $response->isSuccess = FALSE;			
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
        "search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null,
        
    );     
       
    $count = $this->data_model->get_data($req_param, TRUE);
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
      $response->rows[$i]['id']=$result[$i]['ID_REKENING'];
      // data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
      $response->rows[$i]['cell']=array(
                      $result[$i]['ID_REKENING'],
                      $result[$i]['TIPE'],
                      $result[$i]['KELOMPOK'],
                      $result[$i]['JENIS'],
                      $result[$i]['OBJEK'],
                      $result[$i]['RINCIAN'],
                      $result[$i]['SUB1'],
                      $result[$i]['SUB2'],
                      $result[$i]['SUB3'],
                      $result[$i]['KODE_REKENING'],
                      $result[$i]['NAMA_REKENING'],
                      $result[$i]['TARIF_RP'],
                      $result[$i]['TARIF_PERSEN']
                    );
    }
    echo json_encode($response); 
	}

	public function session_id()
	{
		$ID_REKENING = $_POST['ID_REKENING'];
		$this->session->set_userdata('ID_REKENING',$ID_REKENING);
	}
  
}