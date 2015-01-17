<?php

class Rapat extends Controller
{
	var $error_msg='';

	function Rapat()
	{
		parent::Controller();
		$this->error_msg='';
		$this->load->library('jqgridcss','','css');
		//$this->load->model('rapat_model');
	}

	function index()
	{		
		$data['title']          = "SIP TU - Dokument Rapat";
		//$data['sub_menu']     = $this->load->view('sppd/menu');
		$data['main_content']   = 'rapat/daftar_rapat';
		$data['current_link']   = 'index';
		$data['status_edit']  = '';
		$data['value_parm']   = '';
		$data['user_data']['module'] = 'rapat';
		
		$this->load->view('layout/template', $data);	
	}
	
	function daftar_rapat()
	{
		$this->index();
	}
		
	function tab()
	{		
		$tab = $this->uri->segment(3);
		//$data['id'] = $this->uri->segment(4);
		if( $tab ) $this->load->view('rapat/'.$tab);
		//$this->load->view('pegawai/display');
		
	}
	
	function tambah_rapat()
	{
		$data['title']        = "SIP TU - Rapat";
		//$data['sub_menu']   = 'sppd/menu';
		$data['main_content'] = 'rapat/index';
		$data['current_link'] = 'tambah';
		$data['label_batal']  = 'Batal';
		$data['title_batal']  = 'Batal';
		$data['status_edit']  = '';
		$data['value_parm']   = '';
		$data['user_data']['module']    = 'rapat';
	
		//$data['user_data']['nomor_spt'] = $this->get_no_spt();
		//$data['user_data']['module']    = 'spt';
		
		$this->load->view('layout/template', $data);
	}
	
	function edit_rapat()
	{
		$id = $this->uri->segment(3);
		if($id=='go_upload'){
			$this->go_upload($this->uri->segment(4));
		}else{
			$this->load->model('rapat_model');
			
			$data['title']        = "SIP TU - Rapat";
			//$data['sub_menu']   = 'sppd/menu';
			$data['main_content'] = 'rapat/index';
			$data['current_link'] = 'edit';
			$data['label_batal']  = 'Kembali';
			$data['title_batal']  = 'Kembali';
			$data['status_edit']  = 'true';
			$data['value_parm']   = $id;
			
			$data['user_data']    = $this->rapat_model->get_data_by_id($id);
			$data['user_data']['RESUME'] = $this->db->getBlob($data['user_data']['RESUME']);
			
			//$data['user_data']['nomor_spt'] = $this->get_no_spt();		
			$data['user_data']['nomor_rapat']   = '';
			$data['user_data']['module']      = 'rapat';
			
			$this->load->view('layout/template', $data);
		}
	}	
	
	function entri_rapat()
	{
		$this->load->model('rapat_model');

		if($this->input->post('edit')!='')
		{
			$this->edit_data_rapat($this->input->post('parm'));
			$parm = $this->input->post('parm');
			
		}else{
			
			$this->tambah_data_rapat();
			
			$parm = $this->rapat_model->get_id_rapat();
			
			if($this->error_msg=='')
			{
				//$this->do_upload($parm);
				
				$this->load->model('pegawai_model');
				$tmp = $this->pegawai_model->get_data_by_id($this->session->userdata('nid_pegawai'));
				
				$data['id_rapat']      = $parm;
				$data['nama_peserta']  = $tmp['NAMA_PEGAWAI'];
				$data['jabatan'] = '';
				$data['divisi'] = '';
				$data['instansi'] = '';
				$data['email'] = '';
				$data['hp'] = '';
							
				$this->load->model('rapat_peserta_model','peserta_rapat');
				$this->peserta_rapat->fill_data($data);
				
				if(!$this->peserta_rapat->insert_data())
				{
					$this->error_msg='Have Problem';
				}				
			}
		}		
		
		$arr_result = array(
						'errors'=> $this->error_msg,
						'parm'=>$parm,
						'state'=>false
					);
					
		echo json_encode($arr_result);
	}	
	
	function tambah_data_rapat()
	{
		/*if(!$this->rapat_model->check_no_rapat())
		{
			$this->error_msg = 'No. Rapat sudah ada';
		}
		else*/
		if(!$this->rapat_model->insert_data())
		{
			$this->error_msg ='have proble';
		}		
	}	
	
	function edit_data_rapat($id){
		if(!$this->rapat_model->update_data($id)){
			$this->error_msg ='have proble';
		}
	}
	
	
	function daftar_data_rapat()
	{	
		if(!isset($_POST['oper']))
		{	
			$this->load->model('rapat_model');
			
			$id = $this->uri->segment(3);
			
			$page = $_REQUEST['page']; // get the requested page 
			$limit = $_REQUEST['rows']; // get how many rows we want to have into the grid 
			$sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort 
			$sord = $_REQUEST['sord']; // get the direction if(!$sidx) $sidx =1;  
			 
			$req_param = array (
					"id"=>$id,
					"sort_by" => $sidx,
					"sort_direction" => $sord,
					"limit" => null,
					"search" => $_REQUEST['_search'],
					"search_field" => isset($_REQUEST['searchField'])?$_REQUEST['searchField']:null,
					"search_operator" => isset($_REQUEST['searchOper'])?$_REQUEST['searchOper']:null,
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null
			);
			   
			$row = $this->rapat_model->get_data($req_param)->result_array();
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
			  
			$result = $this->rapat_model->get_data($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
								
			for($i=0; $i<count($result); $i++){
					
				$response->rows[$i]['id']=$result[$i]['ID_RAPAT'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array($result[$i]['ID_RAPAT'],
												$result[$i]['RAPAT_NO'],
												date('d/m/Y',strtotime($result[$i]['STAMP'])),
												date('H:i',strtotime($result[$i]['STAMP'])),
												$result[$i]['TEMA'],
												$result[$i]['LOKASI']								
											);
			}
			echo json_encode($response);
			
		}else{
			$id = $this->input->post('id');
			if($_POST['oper'] === 'add'){
				//$this->tambah_data_spt();
			}else if($_POST['oper'] === 'edit'){
				//$this->edit_data_spt($id);
			} else if($_POST['oper'] === 'del'){
				$this->hapus_data_rapat($id);
			}
		}
	}
	
	function hapus_data_rapat($id)
	{
		$id = explode(',',$id);		
		$this->load->model('rapat_file_model','file_rapat');
		$this->file_rapat->delete_all_data($id);
		
		$this->load->model('rapat_peserta_model','peserta_rapat');
		$this->peserta_rapat->delete_all_data($id);
		
		$this->load->model('rapat_model');
		$this->rapat_model->delete_data($id);
	}
		
	/*
		PESERTA RAPAT
	*/
	
	function daftar_peserta()
	{	
		if(!isset($_POST['oper']))
		{
		
			$this->load->model('rapat_peserta_model','peserta_rapat');
			
			$id = $this->uri->segment(3);
			
			$page = $_REQUEST['page']; // get the requested page 
			$limit = $_REQUEST['rows']; // get how many rows we want to have into the grid 
			$sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort 
			$sord = $_REQUEST['sord']; // get the direction if(!$sidx) $sidx =1;  
			 
			$req_param = array (
					"id"=>$id,
					"sort_by" => $sidx,
					"sort_direction" => $sord,
					"limit" => null,
					"search" => $_REQUEST['_search'],
					"search_field" => isset($_REQUEST['searchField'])?$_REQUEST['searchField']:null,
					"search_operator" => isset($_REQUEST['searchOper'])?$_REQUEST['searchOper']:null,
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null
			);     
			   
			$row = $this->peserta_rapat->get_data($req_param)->result_array();
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
			  
			$result = $this->peserta_rapat->get_data($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['id']=$result[$i]['ID_RAPAT_PESERTA'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array($result[$i]['ID_RAPAT_PESERTA'],
												$result[$i]['ID_RAPAT'],
												$result[$i]['NAMA_PESERTA'],
												$result[$i]['JABATAN'],
												$result[$i]['DIVISI'],
												$result[$i]['INSTANSI'],
												$result[$i]['EMAIL'],
												$result[$i]['HP'],
											);
			}
			echo json_encode($response);
			
		}else{		
			$id = $this->input->post('id');
			if($_POST['oper'] === 'add'){
				$this->tambah_peserta();
			}else if($_POST['oper'] === 'edit'){
				$this->edit_peserta($id);
			}else if($_POST['oper'] === 'del'){
				$this->hapus_peserta($id);
			}
		}	
		//echo '{"page":"1","total":0,"records":null}';
	}
	
	function tambah_peserta()
	{
		//$id = $this->uri->segment(3);
		$this->load->model('rapat_peserta_model','peserta_rapat');
		/*if(!$this->data_model->check_name())
		{
			$error_msg = 'Nama Status Pegawai telah digunakan';
		}*/
		if(!$this->peserta_rapat->insert_data()) 
		{
			$this->error_msg='Have Problem';
		}
		echo json_encode(array('errors'=> $this->error_msg));
	}

	function edit_peserta($id){
		$this->load->model('rapat_peserta_model','peserta_rapat');
		if(!$this->peserta_rapat->update_data($id))
		{
			$this->error_msg='Have Problem';
		}
		echo json_encode(array('errors'=> $this->error_msg));
	}
	
	function hapus_peserta($id){
		$id = explode(',', $id);
		$this->load->model('rapat_peserta_model','peserta_rapat');
		if(!$this->peserta_rapat->delete_data($id)){
			$this->error_msg='Have Problem';
		}
		echo json_encode(array('errors'=> $this->error_msg));
	}
	

	/*
		DATA UPLOAD
	*/

	function config_upload()
	{
		$config['upload_path'] = base_url().'assets/data_upload/rapat/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '100';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';

        $this->load->library('upload', $config);
		$this->upload->initialize($config);
        //$this->load->library('Multi_upload');
	
	}	
	
	function go_upload($pid='')
	{
		//$this->config_upload();
		//$ext = $this->findexts($_FILES['Filedata']['name']);
				
		//$config['upload_path'] = base_url().'assets/data_upload/rapat/'.basename($_FILES['Filedata']['name']);
		//$config['upload_path'] = $_SERVER["DOCUMENT_ROOT"].'uploads/'.basename($_FILES['Filedata']['name']);
		//$config['upload_path'] = $_SERVER["DOCUMENT_ROOT"].'uploads/'.$new_name;
		$config['upload_path'] = './uploads/';
		//$config['upload_path'] = $_SERVER["DOCUMENT_ROOT"].'uploads';
		$config['allowed_types'] = 'gif|jpg|png|pdf';
		//$config['max_size']	= '100';
		//$config['max_width']  = '1024';
		//$config['max_height']  = '768';

        $this->load->library('upload', $config);
		$this->upload->initialize($config);				

		$ext = $this->upload->get_extension($_FILES['Filedata']['name']);		
		$new_name = date('d.m.Y.H.i.s').$ext;
				
		$blh = ibase_blob_create($this->db->conn_id);
		ibase_blob_add($blh, file_get_contents($_FILES['Filedata']['tmp_name']));
		$blobid = ibase_blob_close($blh);		
		$is_upload = TRUE;
		
		if ( ! @copy($_FILES['Filedata']['tmp_name'], $this->upload->upload_path.$new_name))
		{
			if ( ! @move_uploaded_file($_FILES['Filedata']['tmp_name'], $this->upload->upload_path.$new_name))
			{
				 $is_upload = FALSE;
				 
			}
		}
		
		//if (!$this->upload->do_upload('Filedata'))
		//if(!move_uploaded_file($_FILES['Filedata']['tmp_name'], $_SERVER["DOCUMENT_ROOT"].'uploads/'))			
		//if(!move_uploaded_file($_FILES['Filedata']['tmp_name'], $this->upload->upload_path))
		if(!$is_upload)
		{
			$error = $this->upload->display_errors();
			//$this->session->set_flashdata('error','<div class="error">'.$error.'</div>');	
			echo $error;
			
		}else{	
		
			//$this->load->model('rapat_file_model','file_rapat');			
			$id_rapat = isset($pid) ? $pid : $this->uri->segment('3');
		
			$data = array(
				'id_rapat'=>$id_rapat,
				'nama_file'=>$_FILES['Filedata']['name'],
				'lokasi_file'=>$this->upload->upload_path,
				'nama_tmp'=>$new_name,
				'data_file'=>$_FILES['Filedata']['tmp_name'],
				'tipe_file'=>$ext
			);
			
			$query = 'insert into rapat_file(id_rapat,nama_file,nama_tmp,data_file,lokasi_file,tipe_file) values('.
					 $this->db->escape($data['id_rapat']).','.
					 $this->db->escape($data['nama_file']).','.
					 $this->db->escape($data['nama_tmp']).',?,'.
					 $this->db->escape($data['lokasi_file']).','.
					 $this->db->escape($data['tipe_file']).')';
					 
			$this->db->trans_start();
			$insert = ibase_query($this->db->conn_id, $query, $blobid);
			$this->db->trans_complete();
		
			//$this->file_rapat->insert_data($data);			
			//$file = $this->upload->data();
		
			//$file_list[] = array(
			//'name' => $CI->upload->file_name,
			//'file' => $CI->upload->upload_path.$CI->upload->file_name,
			//'size' => $CI->upload->file_size,
			//'type' => $CI->upload->file_type,
			//'ext' => $CI->upload->file_ext,

			/*Array
			(
				[Filedata] => Array
				(
					[name] => 2319233390_62fbfac7f5_b.jpg
					[type] => application/octet-stream
					[tmp_name] => C:\xampp\tmp\php2096.tmp
					[error] => 0
					[size] => 143337
				)
			)*/
		}
	}
	
	function get_file()
	{
		$id = $this->uri->segment(3);
		$this->load->model('rapat_file_model','file_rapat');
		$result = $this->file_rapat->get_data_by_id($id);
		if($result->num_rows()>0)
		{
			$row = $result->row();
			$content = $this->db->getBlob($row->DATA_FILE);
			header('Content-Disposition:attachment; filename="'.$row->NAMA_FILE.'"');
			header('Content-Type: '.get_mime_by_extension($row->NAMA_FILE));
			//header('Content-Length: '.filesize($this->thisdir."/output/".$filename));
			header('Cache-Control: no-store');
			echo $content;
		}		
	}	
	
	function daftar_file()
	{	
		if(!isset($_POST['oper']))
		{
		
			$this->load->model('rapat_file_model','file_rapat');
			
			$id = $this->uri->segment(3);
			
			$page = $_REQUEST['page']; // get the requested page 
			$limit = $_REQUEST['rows']; // get how many rows we want to have into the grid 
			$sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort 
			$sord = $_REQUEST['sord']; // get the direction if(!$sidx) $sidx =1;  
			 
			$req_param = array (
					"id"=>$id,
					"sort_by" => $sidx,
					"sort_direction" => $sord,
					"limit" => null,
					"search" => $_REQUEST['_search'],
					"search_field" => isset($_REQUEST['searchField'])?$_REQUEST['searchField']:null,
					"search_operator" => isset($_REQUEST['searchOper'])?$_REQUEST['searchOper']:null,
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null
			);     
			   
			$row = $this->file_rapat->get_data($req_param)->result_array();
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
			  
			$result = $this->file_rapat->get_data($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['id']=$result[$i]['ID_RAPAT_FILE'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array($result[$i]['ID_RAPAT_FILE'],
												$result[$i]['ID_RAPAT'],
												$result[$i]['NAMA_FILE']
											);
			}
			echo json_encode($response);
			
		}else{
		
			$id = $this->input->post('id');
						
			if($_POST['oper'] === 'add'){
				$this->tambah_file();
			}else if($_POST['oper'] === 'edit'){
				$this->edit_file($id);
			}else if($_POST['oper'] === 'del'){
				$this->hapus_file($id);
			}
		}	
		//echo '{"page":"1","total":0,"records":null}';
	}
	
	function tambah_file(){}
	
	function edit_file(){}
	
	function hapus_file($id){
	
		$id = explode(',', $id);
		$this->load->model('rapat_file_model','rapat_file');
		if(!$this->rapat_file->delete_data($id)){
			$this->error_msg='Have Problem';
		}
		echo json_encode(array('errors'=> $this->error_msg));
	}
}

?>