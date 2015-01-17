<?php

class Kecamatan extends Base_Controller {
  public function __construct()
  {
    parent::__construct();
    $this->load->model('kecamatan_model','data_model');
    $this->load->library('form_validation');
  }

  public function index()
  {
    $data['title'] = $this->app['app_name'].' - '.' Kecamatan';
    $data['daftar_url'] = base_url().'kecamatan/get_daftar';
    $data['ubah_url'] = base_url().'kecamatan/proses_form';
    $data['main_content'] = 'kecamatan_view';
    $data['akses'] = $this->access;
    $this->load->view('layout/template',$data);
  }

  public function proses_form()
  {
		$id = $this->input->post('id');
		$oper = $this->input->post('oper');
				
    $this->form_validation->set_rules('kode', 'Kode Kecamatan', 'required|trim|max_length[3]|callback__duplikasi_kode');
    $this->form_validation->set_rules('nama', 'Nama Kecamatan', 'required|trim|max_length[100]');

    $this->form_validation->set_message('required', '%s harus diisi');
    $this->form_validation->set_message('max_length', 'Karakter %s maksimum : %s');
    $this->form_validation->set_message('_duplikasi_kode', '%s tersebut sudah ada.');
	
    if ($this->form_validation->run()==TRUE)
		{
			if($oper == 'edit') {
				if($id == 'new') {
					$result = $this->data_model->check_data();
					if ($result == FALSE){
						$newid = $this->data_model->insert_data();
						$response->message = 'Kecamatan telah disimpan, silahkan anda tambahkan Kelurahan';
						$response->isSuccess = TRUE;
						$response->id = $newid;
					}
					else 
					{
						// Kode Kecamatan sudah ada, tampilkan pesan kesalahan
						$response->message = 'Kode Kecamatan tersebut sudah ada.';
						$response->isSuccess = FALSE;
					}
				} 
				else {
					$rst = $this->data_model->check_data2();
					if ($rst == TRUE){
						$newid= $this->data_model->update_data($id);
						$response->message = 'Kecamatan telah diubah.';
						$response->isSuccess = TRUE;
						$response->id = $newid;
					}
					else 
					{
						// Kode Kecamatan sudah ada, tampilkan pesan kesalahan
						$response->message = 'Kode Kecamatan tersebut sudah ada.';
						$response->isSuccess = FALSE;
					}
				}
			}
		}
		else 
		{
			$response->isSuccess = FALSE;
			$response->error = validation_errors();					
		}
		echo json_encode($response);		
  }

	public function hapus() 
	{
		$id = $this->input->post('id');
    $result = $this->data_model->check_dependency($id);
    if ($result == FALSE) {
      $delete = $this->data_model->delete_data($id);
      if($delete == TRUE) {
        // bisa dihapus      
        $response->message = 'Kecamatan telah dihapus';
        $response->isSuccess = TRUE;
      } else {
        // tidak bisa dihapus      
        $response->message = 'Kecamatan tidak bisa dihapus';
        $response->isSuccess = FALSE;
      }
      $response->sql = $this->db->queries;
    } 
    else 
    {
      // ada dependensi, tampilkan pesan kesalahan
      $response->message = 'Kecamatan tidak bisa dihapus, masih dipakai di tabel lain.';
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
        "search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null
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
      $response->rows[$i]['id'] = $result[$i]['ID_KECAMATAN'];
      $response->rows[$i]['cell'] = array(
          $result[$i]['ID_KECAMATAN'],
          $result[$i]['KODE_KECAMATAN'],
          $result[$i]['NAMA_KECAMATAN'],
      );
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }

  public function proses_form_kelurahan()
  {
		$id = $this->input->post('id');
		$oper = $this->input->post('oper');
				
    $this->form_validation->set_rules('kode_kel', 'Kode Kelurahan', 'required|trim|max_length[3]|callback__duplikasi_kode');
    $this->form_validation->set_rules('nama_kel', 'Nama Kelurahan', 'required|trim|max_length[100]');

    $this->form_validation->set_message('required', '%s harus diisi');
    $this->form_validation->set_message('max_length', 'Karakter %s maksimum : %s');
    $this->form_validation->set_message('_duplikasi_kode', '%s tersebut sudah ada.');
	
    if ($this->form_validation->run()==TRUE)
		{
			if($oper == 'edit') {
				if($id == 'new') {
					$result = $this->data_model->check_data_kelurahan();
					if ($result == FALSE){
						$newid = $this->data_model->insert_data_kelurahan();
						$response->message = 'Kelurahan telah disimpan';
						$response->isSuccess = TRUE;
						$response->id = $newid;
					}
					else 
					{
						// Kode Kelurahan sudah ada, tampilkan pesan kesalahan
						$response->message = 'Kode Kelurahan tersebut sudah ada.';
						$response->isSuccess = FALSE;
					}
				} 
				else {
					$rst = $this->data_model->check_data_kelurahan2();
					if ($rst == TRUE){
						$newid= $this->data_model->update_data_kelurahan($id);
						$response->message = 'Kelurahan telah diubah.';
						$response->isSuccess = TRUE;
						$response->id = $newid;
					}
					else 
					{
						// Kode Kelurahan sudah ada, tampilkan pesan kesalahan
						$response->message = 'Kode Kelurahan tersebut sudah ada.';
						$response->isSuccess = FALSE;
					}
				}
			}
		}
		else 
		{
			$response->isSuccess = FALSE;
			$response->error = validation_errors();					
		}
		echo json_encode($response);		
  }

	public function hapus_kelurahan() 
	{
		$id = $this->input->post('id');
    $result = $this->data_model->check_dependency_kelurahan($id);
    if ($result == FALSE) {
      $delete = $this->data_model->delete_data_kelurahan($id);
      if($delete == TRUE) {
        // bisa dihapus      
        $response->message = 'Kelurahan telah dihapus';
        $response->isSuccess = TRUE;
      } else {
        // tidak bisa dihapus      
        $response->message = 'Kelurahan tidak bisa dihapus';
        $response->isSuccess = FALSE;
      }
      $response->sql = $this->db->queries;
    } 
    else 
    {
      // ada dependensi, tampilkan pesan kesalahan
      $response->message = 'Kelurahan tidak bisa dihapus, masih dipakai di tabel lain.';
      $response->isSuccess = FALSE;			
    }
		echo json_encode($response);
	}

	public function get_daftar_kelurahan($ID_KECAMATAN='') 
	{
		if(!isset($_POST['oper']))
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
			   
			$row = $this->data_model->get_data_kelurahan($req_param,$ID_KECAMATAN);
			$count = count($row); 
			if( $count >0 ) { 
				$total_pages = ceil($count/$limit); 
			} else { 
				$total_pages = 0; 
			} 
			if ($page > $total_pages) 
				$page=$total_pages; 
			$start = $limit*$page - $limit; // do not put $limit*($page - 1) 
			if($start <0) $start = 0;
			$req_param['limit'] = array(
						'start' => $start,
						'end' => $limit
			);
			
			$result = $this->data_model->get_data_kelurahan($req_param,$ID_KECAMATAN);
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++)
			{
				$response->rows[$i]['id']=$result[$i]['ID_KELURAHAN'];
				$response->rows[$i]['cell']=array(
                        $result[$i]['ID_KELURAHAN'],
												$result[$i]['KODE_KELURAHAN'],
												$result[$i]['NAMA_KELURAHAN'],
											);
			}
      $response->sql = $this->db->queries;
			echo json_encode($response); 
		}
	}

	public function session_id()
	{
		$ID_KECAMATAN = $_POST['ID_KECAMATAN'];
		$this->session->set_userdata('ID_KECAMATAN',$ID_KECAMATAN);
	}

}