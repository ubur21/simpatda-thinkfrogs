<?php
class Sppd extends Controller 
{
	var $error_msg='';

	function Sppd()
	{
		parent::Controller();
		//$this->load->model('sppd_model', 'data_model');
		$this->error_msg='';
		$this->load->library('jqgridcss','','css');
	}

	function index()
	{		
		$data['title']          = "SIP TU - Surat Perintah Perjalanan Dinas";
		//$data['sub_menu']     = $this->load->view('sppd/menu');
		$data['main_content']   = 'sppd/daftar_sppd';
		//$data['main_content'] = 'sppd/index';		
		$data['current_link']   = 'daftar';
		
		//$data['user_data']['nomor_sppd']   = $this->get_no_sppd();
		//$data['user_data']['list_no_spt']  = $this->get_list_no_spt();
		$data['user_data']['module']         = 'sppd';
	
		$this->load->view('layout/template', $data);
		
	}
		
	function daftar_sppd()
	{
		$this->index();
	}
	
	function tambah_sppd()
	{
		$data['title']          = "SIP TU - Surat Perintah Perjalanan Dinas";
		$data['main_content']   = 'sppd/form_sppd';
		$data['current_link']   = 'tambah';
		
		$data['label_batal']  = 'Batal';
		$data['title_batal']  = 'Batal';
		$data['status_edit']  = '';
		$data['value_parm']   = '';
		
		$data['user_data']['module']       = 'sppd';
		$data['user_data']['nomor_sppd']   = $this->get_no_sppd();
		$data['user_data']['list_no_spt']  = $this->get_list_no_spt();
		
		$this->load->view('layout/template', $data);
	}
	
	function edit_sppd()
	{
		$id = $this->uri->segment(3);
		$this->load->model('sppd_model');
		
		$data['title']        = 'SIP TU - Surat Perintah Perjalanan Dinas';
		$data['main_content'] = 'sppd/form_sppd';
		$data['current_link'] = 'edit';
		$data['label_batal']  = 'Kembali';
		$data['title_batal']  = 'Kembali';
		$data['status_edit']  = 'true';
		$data['value_parm']   = $this->uri->segment(3);
		
		$data['user_data']    = $this->sppd_model->get_data_by_id($id);
		
		$data['user_data']['module'] = 'sppd';
		
		$data['user_data']['nomor_sppd']   = '';
		$data['user_data']['list_no_spt']  = $this->get_list_no_spt($data['user_data']['ID_SPT']);
		
		$this->load->view('layout/template', $data);
		
	}		
	
	function daftar_data_sppd()
	{
		if(!isset($_POST['oper']))
		{	
			$this->load->model('sppd_model');			
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
			   
			$row = $this->sppd_model->get_data($req_param)->result_array();
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
			  
			$result = $this->sppd_model->get_data($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
								
			for($i=0; $i<count($result); $i++){
			
				$blob_data = ibase_blob_info($result[$i]['MAKSUD']);
				$blob_hndl = ibase_blob_open($result[$i]['MAKSUD']);
				$maksud    = ibase_blob_get($blob_hndl, $blob_data[0]);
				
				$blob_data = ibase_blob_info($result[$i]['PASAL_ANGGARAN']);
				$blob_hndl = ibase_blob_open($result[$i]['PASAL_ANGGARAN']);
				$pasal     = ibase_blob_get($blob_hndl, $blob_data[0]);				
			
				$response->rows[$i]['id']=$result[$i]['ID_SPPD'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				//$result[$i]['KENDARAAN'],
				$response->rows[$i]['cell']=array($result[$i]['ID_SPPD'],
												$result[$i]['ID_SPT'],
												$result[$i]['SPPD_NO'],
												date('d/m/Y',strtotime($result[$i]['TANGGAL'])),
												$result[$i]['SPT_NO'],
												date('d/m/Y',strtotime($result[$i]['TANGGAL_SPT'])),
												$result[$i]['NOMINAL'],
												$result[$i]['KODE_REKENING'],
												$maksud,
												$result[$i]['DARI'].' - '.$result[$i]['TUJUAN'],
												date('d/m/Y',strtotime($result[$i]['TANGGAL_BERANGKAT'])).' - '.date('d/m/Y',strtotime($result[$i]['TANGGAL_PULANG'])),
												$result[$i]['NAMA_PEGAWAI_PEMBERI'],
												$result[$i]['NAMA_PEGAWAI_PENERIMA'],
												$pasal,												
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
				$this->hapus_sppd($id);
			}
		}	
	}
	
	function daftar_biaya()
	{
	
		if(!isset($_POST['oper'])){
	
			$this->load->model('sppd_biaya_model','biaya_model');
			
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
			   
			$row = $this->biaya_model->get_data($req_param)->result_array();
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
			  
			$result = $this->biaya_model->get_data($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++){
				
				//$response->rows[$i]['id']=$result[$i]['ID_SPPD_BIAYA'];
				$response->rows[$i]['id']=$result[$i]['KEPERLUAN'];
				
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array($result[$i]['ID_SPPD_BIAYA'],
												$result[$i]['ID_SPPD'],
												$result[$i]['KEPERLUAN'],
												$result[$i]['PESERTA'],
												$result[$i]['HARI'],
												$result[$i]['NOMINAL']
											);
			}
			echo json_encode($response);
			
		}else{
			$id = $this->input->post('id');
			if($_POST['oper'] === 'add'){
				$this->tambah_biaya();
			}else if($_POST['oper'] === 'edit'){
				$this->edit_biaya($id);
			} else if($_POST['oper'] === 'del'){
				$this->hapus_biaya($id);
			}
		}	
		//echo '{"page":"1","total":0,"records":null}';
	}

	function get_no_sppd()
	{
		$this->load->model('sppd_model');
		$nurut = $this->sppd_model->get_id_sppd();
		$nomor = $nurut+1;
		$nomor = sprintf('%04d',$nomor).'/DPPKA/SPPD';
		return $nomor;
	}
	
	function set_no_sppd()
	{
		echo $this->get_no_sppd();
	}
	
	function entri_sppd()
	{
		$this->load->model('sppd_model');
		//$this->spt_model->fill_data();
		if($this->input->post('edit')!=''){
			$this->edit_data_sppd($this->input->post('parm'));
			$parm = $this->input->post('parm');
		}else{
			$this->tambah_data_sppd();
			$parm = $this->sppd_model->get_id_sppd();
		}
		
		$arr_result = array(
						'errors'=> $this->error_msg,
						'parm'=>$parm,
						'state'=>false
					);
		echo json_encode($arr_result);
	}
	
	function tambah_data_sppd()
	{
		$this->load->model('sppd_model');
		if(!$this->sppd_model->check_no_sppd())
		{
			$this->error_msg = 'No. SPPD sudah ada';
		}
		elseif(!$this->sppd_model->insert_data())
		{
			$this->error_msg ='have proble';
		}	
	}
	
	function edit_data_sppd($id){
		$this->load->model('sppd_model');
		if(!$this->sppd_model->update_data($id)){
			$this->error_msg ='have proble';
		}
	}
	
	function hapus_sppd($id)
	{
		$this->load->model('sppd_biaya_model');
		$this->sppd_biaya_model->delete_all_data($id);
		
		$this->load->model('sppd_model');
		$this->sppd_model->delete_data($id);
	}
	
	function batal_sppd()
	{
		$id = $this->input->post('id');
		$this->load->model('sppd_biaya_model','biaya_model');
		$this->biaya_model->delete_all_data($id);
		
		$this->load->model('sppd_model');
		//$id = $this->uri->segment(3);
		$this->sppd_model->delete_data($id);
	}
		
	function tambah_biaya()
	{
		//$id = $this->uri->segment(3);
		$this->load->model('sppd_biaya_model','biaya_model');
		/*if(!$this->data_model->check_name())
		{
			$error_msg = 'Nama Status Pegawai telah digunakan';
		}*/
		if(!$this->biaya_model->insert_data()) 
		{
			$this->error_msg='Have Problem';
		}else{
			$this->biaya_model->set_total_biaya();
		}		
		$total = $this->biaya_model->get_total_biaya();		
		echo json_encode(array('errors'=> $this->error_msg,'total'=>$total));
	}

	function edit_biaya($id){
		$this->load->model('sppd_biaya_model','biaya_model');
		if(!$this->biaya_model->update_data($id))
		{
			$this->error_msg='Have Problem update ban !';
		}
		else{
			$this->biaya_model->set_total_biaya();
		}
		echo json_encode(array('errors'=> $this->error_msg,'total'=>$total));
	}
	
	function hapus_biaya($id){
		$this->load->model('sppd_biaya_model','biaya_model');
		if(!$this->biaya_model->delete_data($id)){
			$this->error_msg='Have Problem';
		}else{
			$this->biaya_model->set_total_biaya();
		}
		echo json_encode(array('errors'=> $this->error_msg,'total'=>$total));
	}	
	

	/* end modul SPPD */
	
	
	
	
	/* begin modul SPT */
	
	function spt(){
	
		$data['title']        = "SIP TU - Surat Perintah Tugas";
		//$data['sub_menu']   = 'sppd/menu';
		$data['main_content'] = 'sppd/daftar_spt';
		$data['current_link'] = 'daftar';

		//$data['user_data']['nomor_spt'] = $this->get_no_spt();
		$data['user_data']['module']    = 'spt';
		
		$this->load->view('layout/template', $data);
	}
	
	function daftar_spt()
	{
		$this->spt();
	}
	
	function tambah_spt()
	{
		$data['title']        = "SIP TU - Surat Perintah Tugas";
		//$data['sub_menu']   = 'sppd/menu';
		$data['main_content'] = 'sppd/form_spt';
		$data['current_link'] = 'tambah';
		$data['label_batal']  = 'Batal';
		$data['title_batal']  = 'Batal';
		$data['status_edit']  = '';
		$data['value_parm']   = '';
	
		$data['user_data']['nomor_spt'] = $this->get_no_spt();
		$data['user_data']['module']    = 'spt';
		
		$this->load->view('layout/template', $data);	
	}
	
	function edit_spt()
	{
		$id = $this->uri->segment(3);
		$this->load->model('spt_model');
		
		$data['title']        = "SIP TU - Surat Perintah Tugas";
		//$data['sub_menu']   = 'sppd/menu';
		$data['main_content'] = 'sppd/form_spt';
		$data['current_link'] = 'edit';
		$data['label_batal']  = 'Kembali';
		$data['title_batal']  = 'Kembali';
		$data['status_edit']  = 'true';
		$data['value_parm']   = $this->uri->segment(3);
		
		$data['user_data']    = $this->spt_model->get_data_by_id($id);
		
		//$data['user_data']['nomor_spt'] = $this->get_no_spt();		
		$data['user_data']['nomor_spt']   = '';
		$data['user_data']['module']      = 'spt';
		
		$this->load->view('layout/template', $data);			
	}
	
	function info_pengikut(){
	
		$param = $this->uri->segment(3);
		
		$value = strtolower($this->uri->segment(4));
		
		$this->load->model('pegawai_model');
		
		if($param=='nip'){
			$result = $this->pegawai_model->get_data_by_nip($value);
		}else{
			$result = $this->pegawai_model->get_data_by_name($value);
		}
		
		$gelar_belakang = isset($result['GELAR_BELAKANG']) ? ', '.$result['GELAR_BELAKANG'] : '';
	
		$return = array(
					'id'=>$result['ID_PEGAWAI'],
					'nip'=>$result['NIP'],
					'nama'=>$result['GELAR_DEPAN'].' '.$result['NAMA_PEGAWAI'].''.$gelar_belakang,
				);
				
		echo json_encode($return);
		
	}
	
	function get_list_no_spt($id='')
	{
		$this->load->model('spt_model');
		$list_no_spt = $this->spt_model->get_list_no_spt($id);
		return $list_no_spt;
	}
	
	function idle()
	{
		$this->load->model('spt_model');
		$query = $this->spt_model->is_idle();
		
		if($query->num_rows() > 0)
		{
			$arr['idle']=0;
			$row = $query->row();
			$arr['title'] = ($row->JENIS_KELAMIN=='P' ? 'Bpk':'Ibu').' ';
			$arr['title'].= ($row->GELAR_DEPAN!='' ? $row->GELAR_DEPAN.' ':'');
			$arr['title'].= ($row->NAMA_PEGAWAI);
			$arr['title'].= ($row->GELAR_BELAKANG!='' ? ' '.$row->GELAR_BELAKANG:'');			
			$arr['message'] = 'ada tugas ke '.$row->TUJUAN.' untuk '.$this->db->getBlob($row->MAKSUD).
							' dari tanggal '.$this->input->post('tgl1').' s/d '.$this->input->post('tgl2').'<br><br><a href="javascript:func_submit()" class="gritter-link">Tetap diproses ?</a>';
		
		}else{
			$arr['idle']=1;
		}
		echo json_encode($arr);
	}

	function spt_editable()
	{
		$id = $this->uri->segment('3');
		$this->load->model('sppd_model');
		$result = $this->sppd_model->editable($id);
		echo $result->COUNT;
	}
	
	function get_json_no_spt()
	{
		$this->load->model('spt_model');
		$list_no_spt = $this->spt_model->get_list_no_spt();
		
		$arr = array();
		
		$ii=0;
		
		$arr[$ii]['id']       = '';
		$arr[$ii]['label']    = ' ';
		$arr[$ii]['selected'] = 'selected';
		
		if ($list_no_spt->num_rows() > 0)
		{
		   
		   foreach ($list_no_spt->result() as $row)
		   {
				$ii++;
				$selected ='';
				$arr[$ii]['id']       = $row->ID_SPT;
				$arr[$ii]['label']    = $row->SPT_NO;
				$arr[$ii]['selected'] = $selected;
		   }
		}
		echo json_encode($arr);
	}
	
	function data_spt()
	{
		$id = $this->uri->segment(3);
		$this->load->model('spt_model');
		$row = $this->spt_model->get_data_by_id($id);
		
		$blob_data = ibase_blob_info($row['MAKSUD']);
		$blob_hndl = ibase_blob_open($row['MAKSUD']);
		$maksud    = ibase_blob_get($blob_hndl, $blob_data[0]);
		
		$row['TANGGAL']           = date('d/m/Y',strtotime($row['TANGGAL']));
		$row['TANGGAL_BERANGKAT'] = date('d/m/Y',strtotime($row['TANGGAL_BERANGKAT']));
		$row['TANGGAL_PULANG']    = date('d/m/Y',strtotime($row['TANGGAL_PULANG']));
		$row['MAKSUD']    		  = $maksud;
		$row['JABATAN_PEMBERI']   = ($row['JABATAN_STRUKTURAL_PEMBERI']) ? $row['JABATAN_STRUKTURAL_PEMBERI'] : $row['JABATAN_FUNGSIONAL_PEMBERI'];
		$row['JABATAN_PENERIMA']  = ($row['JABATAN_STRUKTURAL_PENERIMA']) ? $row['JABATAN_STRUKTURAL_PENERIMA'] : $row['JABATAN_FUNGSIONAL_PENERIMA'];
		
		if($row){
			$response=$row;
		}else{
			$response='{}';
		}
		
		echo json_encode($response);
	}
	
	function daftar_data_spt()
	{	
		if(!isset($_POST['oper']))
		{	
			$this->load->model('spt_model');
			
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
			   
			$row = $this->spt_model->get_data($req_param)->result_array();
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
			  
			$result = $this->spt_model->get_data($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
								
			for($i=0; $i<count($result); $i++){
			
				$blob_data = ibase_blob_info($result[$i]['MAKSUD']);
				$blob_hndl = ibase_blob_open($result[$i]['MAKSUD']);
				$maksud    = ibase_blob_get($blob_hndl, $blob_data[0]);
			
				$response->rows[$i]['id']=$result[$i]['ID_SPT'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array($result[$i]['ID_SPT'],
												$result[$i]['SPT_NO'],
												date('d/m/Y',strtotime($result[$i]['TANGGAL'])),
												$maksud,
												$result[$i]['DARI'].' - '.$result[$i]['TUJUAN'],
												date('d/m/Y',strtotime($result[$i]['TANGGAL_BERANGKAT'])).' - '.date('d/m/Y',strtotime($result[$i]['TANGGAL_PULANG'])),
												$result[$i]['KENDARAAN'],
												$result[$i]['NAMA_PEGAWAI_PEMBERI'],
												$result[$i]['ID_PEGAWAI_PEMBERI'],
												$result[$i]['NAMA_PEGAWAI_PENERIMA'],
												$result[$i]['ID_PEGAWAI_PENERIMA'],
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
				$this->hapus_spt($id);
			}
		}
	}
	
	function daftar_pengikut()
	{
	
		if(!isset($_POST['oper'])){
	
	
			$this->load->model('spt_pengikut_model','pengikut_model');
			
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
			   
			$row = $this->pengikut_model->get_data($req_param)->result_array();
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
			  
			$result = $this->pengikut_model->get_data($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
					
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['id']=$result[$i]['ID_SPT_PENGIKUT'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array($result[$i]['ID_SPT_PENGIKUT'],
												$result[$i]['ID_SPT'],
												$result[$i]['ID_PEGAWAI'],
												$result[$i]['NIP'],
												$result[$i]['NAMA_PEGAWAI']
											);
			}
			echo json_encode($response);
			
		}else{
			$id = $this->input->post('id');
			if($_POST['oper'] === 'add'){
				$this->tambah_pengikut();
			}else if($_POST['oper'] === 'edit'){
				$this->edit_pengikut($id);
			}else if($_POST['oper'] === 'del'){
				$this->hapus_pengikut($id);
			}
		}	
		//echo '{"page":"1","total":0,"records":null}';
	}
	
	function get_no_spt()
	{
		$this->load->model('spt_model');
		$nurut = $this->spt_model->get_id_spt();
		$nomor = $nurut+1;
		$nomor = sprintf('%04d',$nomor).'/DPPKA';
		return $nomor;
	}
	
	function set_no_spt()
	{
		echo $this->get_no_spt();
	}
	
	function entri_spt()
	{
		$this->load->model('spt_model');
		//$this->spt_model->fill_data();
		if($this->input->post('edit')!='')
		{
			$this->edit_data_spt($this->input->post('parm'));
			$parm = $this->input->post('parm');
			
		}else{
			
			$this->tambah_data_spt();
			
			$parm = $this->spt_model->get_id_spt();
			
			if($this->error_msg=='')
			{
			
				$this->load->model('pegawai_model');
				$tmp = $this->pegawai_model->get_data_by_id($this->input->post('id_pegawai_penerima'));
				
				$data['id_spt']      = $parm;
				$data['id_pegawai']  = $this->input->post('id_pegawai_penerima');
				$data['nip_pegawai'] = $tmp['NIP'];
				$data['nama_pegawai']= $this->input->post('pegawai_penerima');
				$data['jabatan']     = NULL;
				
				$this->load->model('spt_pengikut_model');
				$this->spt_pengikut_model->fill_data($data);
				
				if(!$this->spt_pengikut_model->insert_data())
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
	
	function tambah_data_spt()
	{
		if(!$this->spt_model->check_no_spt())
		{
			$this->error_msg = 'No. SPT sudah ada';
		}
		elseif(!$this->spt_model->insert_data())
		{
			$this->error_msg ='have proble';
		}	
	}
	
	function edit_data_spt($id){
		if(!$this->spt_model->update_data($id)){
			$this->error_msg ='have proble';
		}
	}
	
	function hapus_spt($id)
	{
		$this->load->model('spt_pengikut_model','pengikut_model');
		$this->pengikut_model->delete_all_data($id);
		
		$this->load->model('spt_model');
		$this->spt_model->delete_all_data($id);
	}
	
	function batal_spt()
	{
		$id = $this->input->post('id');
		$this->load->model('spt_pengikut_model','pengikut_model');
		$this->pengikut_model->delete_all_data($id);
		
		$this->load->model('spt_model');
		//$id = $this->uri->segment(3);
		$this->spt_model->delete_data($id);
	}
		
	function tambah_pengikut()
	{
		//$id = $this->uri->segment(3);
		$this->load->model('spt_pengikut_model','pengikut_model');
		/*if(!$this->data_model->check_name())
		{
			$error_msg = 'Nama Status Pegawai telah digunakan';
		}*/
		if(!$this->pengikut_model->insert_data()) 
		{
			$this->error_msg='Have Problem';
		}
		echo json_encode(array('errors'=> $this->error_msg));
	}

	function edit_pengikut($id){
		$this->load->model('spt_pengikut_model','pengikut_model');
		if(!$this->pengikut_model->update_data($id))
		{
			$this->error_msg='Have Problem';
		}
		echo json_encode(array('errors'=> $this->error_msg));
	}
	
	function hapus_pengikut($id){
		$this->load->model('spt_pengikut_model','pengikut_model');
		if(!$this->pengikut_model->delete_data($id)){
			$this->error_msg='Have Problem';
		}
		echo json_encode(array('errors'=> $this->error_msg));
	}
	
	function get_peserta(){
		
		//$id = $this->uri->segment(3);
		$id        = $this->input->post('spt');
		$keperluan = $this->input->post('keperluan');
	
		$this->load->model('spt_pengikut_model');
		
		if($keperluan!=''){
			//$row = $this->spt_pengikut_model->get_pengikut_edit($id,$keperluan);\			
			$row = $this->spt_pengikut_model->get_pengikut_add($id);			
		}else{			
			$row = $this->spt_pengikut_model->get_pengikut_add($id);			
		}
		$arr = array();
		for($i=0; $i<count($row); $i++){
			$arr[$i]['id'] = $row[$i]->ID_SPT_PENGIKUT;
			$arr[$i]['peserta']  = ($row[$i]->NIP_PEGAWAI!='') ? $row[$i]->NAMA_PEGAWAI.' ('.$row[$i]->NIP_PEGAWAI.')' : $row[$i]->NAMA_PEGAWAI;			
			$selected = $this->spt_pengikut_model->get_select_peserta_biaya($row[$i]->ID_SPT_PENGIKUT, $keperluan);
			$arr[$i]['selected'] = $selected;
		}
		echo json_encode($arr);			
	}

	/* end modul SPT */

}
?>