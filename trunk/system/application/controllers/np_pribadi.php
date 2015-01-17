<?php

class np_pribadi extends Controller
{	
	var $error_msg;
	var $result_msg;
	
	function np_pribadi()
	{
		parent::Controller();
		$this->error_msg=''; $this->result_msg='';
		//$this->load->library('firephp');
		//$this->load->library('jqgridcss','','css');
		$this->load->model('pendaftaran_model','data_model');
		
		$this->external->set(array(
            'css' => base_url().'assets/css/jquery.autocomplete.css',
            'js' => array(base_url().'assets/script/jquery/jquery.autocomplete.min.js'
						  ,base_url().'assets/script/jquery/jquery.metadata.js'
						  ,base_url().'assets/script/jquery/jquery.maskedinput.js'
						  ,base_url().'assets/script/jquery/jquery.validate.min.js'
					),
        ));
	}
		
	function index()
	{		
		if(b_logged()){
			$this->load->model('kode_usaha_model','kode_usaha');
			$result = $this->kode_usaha->get_all_data();
			$data['data_form']['opt_kode_usaha']='';
			if($result){
				foreach($result as $row){
					$data['data_form']['opt_kode_usaha'].="<option value='$row->ID'>$row->NAMA</option>";
				}
			}
			$data['title_form']   = get_name_menu($this->uri->segment(1));

			$arr = array('is_save'=>1,'is_cancel'=>1);
			
			$data['button_form']  = $this->load->view('element/button',$arr,true);
			
			$data['form']         = $this->load->view('pendaftaran/form_np_pribadi',$data,true);
			
			$data['main_content'] = $this->load->view('pendaftaran/daftar_np_pribadi',$data,true);
			
		}else{
			//$data['main_content'] = 'login/login';
		}
		$this->load->view($this->config->item('layout_dir').'/template', $data);
	}
	
	function daftar()
	{
		if(!isset($_POST['oper']))
		{				
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
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null,
					"objek_pdrd"=>"PRIBADI"
			);
			   
			$row = $this->data_model->get_data($req_param)->result_array();
						
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
			  
			$result = $this->data_model->get_data($req_param)->result_array();
			// sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
			$response->page = $page; 
			$response->total = $total_pages; 
			$response->records = $count;
								
			for($i=0; $i<count($result); $i++){
						
				$response->rows[$i]['id']=$result[$i]['PENDAFTARAN_ID'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array($result[$i]['PENDAFTARAN_ID'],
												format_date($result[$i]['TANGGAL_KARTU']),
												$result[$i]['NO_PENDAFTARAN'],												
												$result[$i]['NPWP'],
												$result[$i]['JENIS_PENDAFTARAN'],												
												$result[$i]['PEMOHON'],
												$result[$i]['JENIS_USAHA']
											);
			}
			echo json_encode($response);
			
		}else{		
			$id = $this->input->post('id');
			if($_POST['oper'] === 'add'){
				$this->insert();
			}else if($_POST['oper'] === 'edit'){
				$this->update($id);
			} else if($_POST['oper'] === 'del'){
				$this->delete($id);
			}
		}	
	}
	
	function getno()
	{
		$action = $this->input->post('action');
		if($action=='edit'){
			echo '';
		}else{
			$nomor = setNomorPendaftaran($this->input->post('typ'),$this->input->post('obj'));
			echo $nomor;
		}
	}	
	
	function form()
	{		
		if($_REQUEST['action']=='edit')
		{
			
			$pemohon = $this->IU_Pribadi();
			
			$id = $this->input->post('idmasters');
			
			$arr = array('pemohon'=>$pemohon);
					
			$this->data_model->fill_data($arr,'edit');
									
			if($this->data_model->update_data($id))
			{
				$this->result_msg.='<li>:: Data telah diupdate ::</li>';
				//echo '<li>:: Data telah tersimpan ::</li>';
			}else{
				$this->result_msg.='<li>:: Gagal update data ::</li>';
			}
			
			echo $this->result_msg;
					
		}else{
		
			$pemohon = $this->IU_Pribadi();
		
			//$cek = b_fetch('select count(*) from pendaftaran where no_pendaftaran='.quote_smart($_REQUEST['nomor']));
			$cek = $this->data_model->cek_no_pendaftaran($this->input->post('nomor'));
			
			if($cek > 0){
			
				$nomor = setNomorPendaftaran($this->input->post('jenis_daftar'),$this->input->post('objek_pdrd'));
				$nmax = (int)substr($nomor,1,strlen($nomor)-1);
				
				$this->result_msg.= '<li>::'.$this->input->post('nomor').' sudah terdaftar, diganti dengan '.$nomor."::</li>";
				//echo '<li>Nomor Baru</li>';
						
			}else{

				$nomor = $this->input->post('nomor');
				$nmax = (int)substr($nomor,1);
				//echo '<li>Nomor Form</li>';
			}		
			
			$npwp    = setNoNPWP($nmax,$this->input->post('jenis_daftar'),$this->input->post('objek_pdrd'),$pemohon);
			$nokartu = setNoKartu($nmax,$this->input->post('jenis_daftar'),$this->input->post('objek_pdrd'),$this->input->post('tgl_kartu'));
				
			//echo '<li>Set NPWP & No Kartu</li>';
			
			$arr = array(
						'pemohon'=>$pemohon
						,'nurut'=>$nmax
						,'no_daftar'=>$nomor
						,'no_kartu'=>$nokartu
						,'npwp'=>$npwp
					);
					
			$this->data_model->fill_data($arr);
			
			if($this->data_model->insert_data())
			{
				$this->result_msg.='<li>:: Data telah tersimpan ::</li>';
				//echo '<li>:: Data telah tersimpan ::</li>';
			}else{
				$this->result_msg.='<li>:: Gagal tersimpan data ::</li>';
			}
			
			echo $this->result_msg;
					
			/*$this->form_validation->set_rules('nomor', 'No. Pendaftaran', 'required');
			$this->form_validation->set_rules('jenis_daftar', 'Jenis Pendaftaran', 'required');
			
			if ($this->form_validation->run() == FALSE)
			{
				$this->index();
			}
			else
			{
				$this->index();
			}	*/
			//print_r($_POST);
			//echo json_encode(array('msg'=>'Heloow'));
		}
	}	
	
	function IU_Pribadi()
	{
		$this->load->model('data_pribadi_model','pribadi');
		
		if($this->input->post('pemohon')!='')
		{
			$pemohon = $this->input->post('pemohon');
			$this->pribadi->update_data($pemohon);
			
		}else{
			// pemohon tdk ada di DB
			$this->load->model('data_pribadi_model','pribadi');
			$this->pribadi->insert_data();
			$pemohon = $this->pribadi->get_last_id();
		}
		return $pemohon;
	}
	
	function insert()
	{
		if(!$this->data_model->insert_data())
		{
			$this->error_msg='Error insert data pemohon pribadi';
		}
		echo json_encode(array('error'=>$this->error_msg=''));
	}
	
	function update($id)
	{
		if(!$this->data_model->update_data($id))
		{
			$this->error_msg='Error edit data pemohon pribadi';
		}
		echo json_encode(array('error'=>$this->error_msg=''));
	}
	
	function delete($id)
	{
		if(!$this->data_model->delete_data($id))
		{
			$this->error_msg='Error delete data pemohon pribadi';
		}		
		echo json_encode(array('error'=>$this->error_msg=''));
	}
	
	function seekname()
	{
		$nama = $_REQUEST['q'];
		$this->load->model('data_pribadi_model','pribadi');
		//print_r($this->db->queries);
		$query = $this->pribadi->seekname($nama);
		if($query->num_rows() > 0)
		{
			$result = $query->result();
			foreach($result as $row)
			{
				echo $row->NAMA.'|'.$row->PRIBADI_ID."\n";
			}
		}			
	}
	
	function view()
	{
		$id = $this->uri->segment(3);
		$dt = $this->data_model->get_data_pendaftaran_pribadi($id); 
				
		$arr= array();
		
		if($dt)
		{
			foreach($dt as $rs)
			{
										
				$arr = array(
						"nomor"=>$rs->NO_PENDAFTARAN,
						"jenis"=>$rs->JENIS_PENDAFTARAN,
						"npwp"=>$rs->NPWP,
						"objek_pdrd"=>$rs->OBJEK_PDRD,
						"pemohon"=>$rs->PRIBADI_ID,
						"no_ktp"=>$rs->NO_KTP,
						"nama_pemohon"=>$rs->NAMA,
						"tempat_lahir"=>$rs->TEMPAT_LAHIR,
						"tanggal_lahir"=>format_date($rs->TANGGAL_LAHIR),
						"pekerjaan"=>$rs->PEKERJAAN,
						"no_hp"=>$rs->NO_HP,
						"rt"=>$rs->RT,
						"rw"=>$rs->RW,
						"kodepos"=>$rs->KODEPOS,
						"alamat"=>$this->db->getBlob($rs->ALAMAT),
						"id_desa"=>$rs->ID_DESA,
						"kelurahan"=>$rs->DESA_NAMA,
						"kecamatan"=>$rs->CAMAT_NAMA,
						"telp"=>$rs->NO_TELP,
						"kode_usaha"=>$rs->KODE_USAHA,
						"tanggal_kartu"=>format_date($rs->TANGGAL_KARTU),
						"tanggal_terima"=>format_date($rs->TANGGAL_TERIMA),
						"tanggal_kirim"=>format_date($rs->TANGGAL_KIRIM),
						"tanggal_kembali"=>format_date($rs->TANGGAL_KEMBALI),
						"tanggal_tutup"=>format_date($rs->TANGGAL_TUTUP),
						"memo"=>$rs->MEMO
				);
				
			}
			
			$arr['result']='ok';
			
		}else
		{
			$arr['result']='0';
		
		}
		echo json_encode($arr);
	}
	
}

?>