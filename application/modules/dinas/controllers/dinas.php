<?php

class Dinas extends Base_Controller {
  public function __construct()
  {
    parent::__construct();

    $this->load->model('dinas_model','data_model');
    $this->load->library('form_validation');
  }

  public function index()
  {
    $data['title'] = $this->app['app_name'].' - '.' Data Dinas';
    $data['main_content'] = 'dinas_view';
    $data['akses'] = $this->access;
    $this->load->view('layout/template',$data);
  }

	public function proses_form() 
	{
		$id = $this->input->post('id');
		$oper = $this->input->post('oper');
		
		if ($oper == 'del'){
			$this->hapus($id);
			return '';
		}		
		
		$this->form_validation->set_rules('skpd', 'SKPD', 'required|trim|max_length[10]');
		$this->form_validation->set_rules('namaskpd', 'NAMA', 'required|trim|max_length[100]');
	
    if ($this->form_validation->run()==TRUE)
		{
			if($oper == 'edit') {
				if($id == 'new') {
					$result = $this->data_model->check_data();
					if ($result == FALSE){
						$newid = $this->data_model->insert_data();
						$response->message = 'SKPD telah disimpan,silahkan anda tambahkan pejabat SKPD';
						$response->isSuccess = TRUE;
						$response->id = $newid;
					}
					else 
					{
						// SKPD sudah ada, tampilkan pesan kesalahan
						$response->message = 'SKPD tersebut sudah ada.';
						$response->isSuccess = FALSE;
					}
				} 
				else {
					$rst = $this->data_model->check_data2();
					if ($rst == TRUE){
						$newid= $this->data_model->update_data($id);
						$response->message = 'SKPD telah diubah.';
						$response->isSuccess = TRUE;
						$response->id = $newid;
					}
					else 
					{
						// SKPD sudah ada, tampilkan pesan kesalahan
						$response->message = 'SKPD tersebut sudah ada.';
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
		//$id = $id[0];	
    foreach($id as $key) {
      $result = $this->data_model->check_dependency($key);
      $query = $this->db->query("select KODE_SKPD from SKPD where ID_SKPD='$key'")->result_array();
      foreach($query as $row) $kd_skpd = $row['KODE_SKPD'];
      if ($result) {
        // bisa dihapus
        $this->data_model->delete_data($key);
        $response->message[] = 'SKPD '.$kd_skpd.' telah dihapus';
        $response->isSuccess[] = TRUE;
      } 
      else 
      {
        // ada dependensi, tampilkan pesan kesalahan
        $response->message[] = 'SKPD '.$kd_skpd.' tidak bisa dihapus, masih dipakai di tabel lain.';
        $response->isSuccess[] = FALSE;			
      }
    }
		echo json_encode($response);
	}

	public function get_daftar()
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
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null,
					
			);     
			   
			$row = $this->data_model->get_data($req_param);
			if(!$row)
			{
				$response->total = 0; 
				$response->records = 0;
				echo json_encode($response);
				return '';
			}
			$count = count($row); 
			$total_pages = ceil($count/$limit); 
			if ($page > $total_pages) 
				$page=$total_pages; 

			$result = $this->data_model->get_data($req_param);
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
			
			$z=0;
			for($i=0; $i<count($result); $i++)
			{				
				if($result[$i]['ID_SKPD'] != '')
				{
          $response->rows[$z]['id']=$result[$i]['ID_SKPD'];
          // data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
          $response->rows[$z]['cell']=array($result[$i]['ID_SKPD'],
                          $result[$i]['KODE_SKPD'],
                          $result[$i]['NAMA_SKPD']
                        );
          $z++;
				}
			}
			echo json_encode($response); 
		}
	}

	public function proses_form_pejabat() 
	{
		$ID_PEJABAT_SKPD = $this->input->post('ID_PEJABAT_SKPD');
		$oper = $this->input->post('oper');
		$id = $this->input->post('id');

		if($oper == 'del') 
		{
			$this->hapus_pejabat($id);
			return '';
		} 
		
		$this->form_validation->set_rules('JABATAN', 'JABATAN', 'required|trim|max_length[50]');
		$this->form_validation->set_rules('NAMA_PEJABAT', 'NAMA PEJABAT', 'required|trim|max_length[100]');
		$this->form_validation->set_rules('NIP', 'NIP', 'required|trim|max_length[100]');
		$this->form_validation->set_rules('GOLONGAN', 'GOLONGAN', 'required|trim|max_length[10]');
		$this->form_validation->set_rules('PANGKAT', 'PANGKAT', 'required|trim|max_length[100]');
		//$this->form_validation->set_rules('nip', 'NIP', 'callback_duplikasi_kode');		
		
		if ($this->form_validation->run()==TRUE)
		{
			if($oper == 'edit') {
				if(!$ID_PEJABAT_SKPD) {
					$result = $this->data_model->check_data_pejabat();
					if ($result == FALSE){
						$newid = $this->data_model->insert_data_pejabat();
						$response->message = 'Pejabat SKPD telah disimpan';
						$response->isSuccess = TRUE;
						$response->id = $newid;
					}
					else 
					{
						// Pejabat SKPD sudah ada, tampilkan pesan kesalahan
						$response->message = 'NIP ada yang sama';
						$response->isSuccess = FALSE;
					}
				} 
				else {
					$rst = $this->data_model->check_data_pejabat();
					if ($rst == FALSE){
						$newid= $this->data_model->update_data_pejabat($id);
						$response->message = 'Pejabat SKPD telah diubah.';
						$response->isSuccess = TRUE;
						$response->id = $newid;
					}
					else 
					{
						// Pejabat SKPD sudah ada, tampilkan pesan kesalahan
						$response->message = 'NIP ada yang sama';
						$response->isSuccess = FALSE;
					}
				}
			}
		}
		else 
		{
			$response->isSuccess = FALSE;
			$response->message = 'Pejabat SKPD gagal disimpan, Silahkan lengkapi Nama, Jabatan, dan NIP.';
			//$response->error = validation_errors();							
		}
		echo json_encode($response);
	}

	public function hapus_pejabat()
	{		
			$id=$this->input->post('id');
			$this->data_model->delete_data_pejabat($id);
			$response->message = 'Pejabat SKPD telah dihapus';
			$response->isSuccess = TRUE;
			echo json_encode($response);
	}

	public function get_daftar_pejabat($ID_SKPD='') 
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
			   
			$row = $this->data_model->get_data_pejabat($req_param,$ID_SKPD)->result_array();
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
			
			$result = $this->data_model->get_data_pejabat($req_param,$ID_SKPD)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++)
			{
				$response->rows[$i]['id']=$result[$i]['ID_PEJABAT_SKPD'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				if($result[$i]['AKTIF'] == 1)
				{
				$AKTIF = 'Aktif';
				}
				else if($result[$i]['AKTIF'] == 0)
				{
				$AKTIF = 'Tidak Aktif';
				}

				$response->rows[$i]['cell']=array($result[$i]['ID_PEJABAT_SKPD'],
												$result[$i]['JABATAN'],
												$result[$i]['NAMA_PEJABAT'],
												$result[$i]['NIP'],
												$result[$i]['GOLONGAN'],
												$result[$i]['PANGKAT'],
												$AKTIF
											);
			}
			echo json_encode($response); 
		}
	}
  
	public function session_id()
	{
		$ID_SKPD = $_POST['ID_SKPD'];
		$this->session->set_userdata('ID_SKPD',$ID_SKPD);
	}
  
}