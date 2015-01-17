<?php

class pendaftaran extends Controller
{	
	var $error_msg;
	var $result_msg;
	
	function pendaftaran()
	{
		parent::Controller();
		$this->error_msg=''; $this->result_msg='';
		//$this->load->library('firephp');
		//$this->load->library('jqgridcss','','css');
		$this->load->model('pendaftaran_model','data_model');
		
		/*$this->external->set(array(
            'css' => base_url().'assets/css/jquery.autocomplete.css',
            'js' => array(base_url().'assets/script/jquery/jquery.autocomplete.min.js'
						  ,base_url().'assets/script/jquery/jquery.metadata.js'
						  ,base_url().'assets/script/jquery/jquery.maskedinput.js'
						  ,base_url().'assets/script/jquery/jquery.validate.min.js'
					),
        ));*/
		
	}
	
	function seeknpw()
	{
		
		$type = $this->uri->segment('3');
		$kode = $this->uri->segment('4');
		if($kode!=''){
			$id_usaha = $this->db->query('select id from kode_usaha where kode='.$this->db->escape($kode))->row()->ID;
			$owhere['where'] = " a.jenis_pendaftaran='".$type."' and a.id_usaha='".$id_usaha."'";
			
		}else $owhere['where'] = " a.jenis_pendaftaran='".$type."'";
		
		//$this->db->join('kode_usaha');
		
		$limit = jqgrid_set_limit('v_pendaftaran a',$owhere);
		
		$limit['where'] = $owhere['where'];
						
		$response->page = $limit['page']; 
		$response->total = $limit['total_pages'];
		$response->records = $limit['records'];
				
		$result = $this->data_model->seeknpw($limit)->result_array();
		
		for($i=0; $i<count($result); $i++){
					
			$response->rows[$i]['id']=$result[$i]['PENDAFTARAN_ID'];
			// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
			
			$periode_spt = (isset($result[$i]['TGL_KIRIM'])) ? date('Y',strtotime($result[$i]['TGL_KIRIM'])) : '';
			$tgl_spt     = (isset($result[$i]['TGL_KIRIM'])) ? format_date($result[$i]['TGL_KIRIM']): '';
			$no_spt      = (isset($result[$i]['SPT_NO'])) ? sprintf('%05d',$result[$i]['SPT_NO']) : '';
			
			$response->rows[$i]['cell']=array($result[$i]['PENDAFTARAN_ID'],
											$result[$i]['PEMOHON'],			
											substr($result[$i]['NPWP'],1,strlen($result[$i]['NPWP'])),
											$result[$i]['JENIS_USAHA'],
											$result[$i]['ALAMAT'],
											$result[$i]['NAMA_DESA'],
											$result[$i]['NAMA_KECAMATAN'],
											$periode_spt,
											$tgl_spt,
											$no_spt,
											$result[$i]['SPT_ID'],
										);
		}
		echo json_encode($response);				
	}
		
	/*function index()
	{		
		if(b_logged()){
		
			$arr = array('is_save'=>1,'is_cancel'=>1);
			$data['mod']		  = 'spt';
			$data['button_form']  = $this->load->view('element/button',$arr,true);
			$data['title_form']   = get_name_menu($this->uri->segment(1));
			$data['form'] 		  = $this->load->view('form',$data,true);
			$data['main_content'] = $this->load->view('daftar',$data,true);
								
			$this->load->vars($data);
			
		}else{
			$data['main_content'] = $this->load->view('login/login','',true);
		}
		
		$this->load->view($this->config->item('layout_dir').'/template', $data);

	}
	
	function js()
	{
		$this->load->view('js/script.js');
	}
	
	function set_npwprd()
	{
		$this->load->model('pendaftaran_model','MDaftar');
		
		$type = $this->uri->segment('3');
		
		$owhere = "jenis_pendaftaran='".$type."'";
		
		$limit = jqgrid_set_limit('v_pendaftaran',$owhere);
				
		$response->page = $limit['page']; 
		$response->total = $limit['total_pages'];
		$response->records = $limit['records'];
				
		$result = $this->MDaftar->get_npwprd($limit)->result_array();

		for($i=0; $i<count($result); $i++){
					
			$response->rows[$i]['id']=$result[$i]['PENDAFTARAN_ID'];
			// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
			$response->rows[$i]['cell']=array($result[$i]['PENDAFTARAN_ID'],
											$result[$i]['PEMOHON'],			
											substr($result[$i]['NPWP'],1,strlen($result[$i]['NPWP'])),
											$result[$i]['JENIS_USAHA'],
											$result[$i]['ALAMAT'],
											$result[$i]['NAMA_DESA'],
											$result[$i]['NAMA_KECAMATAN'],
										);
		}
		echo json_encode($response);		
	}
	
	function set_npwrd()
	{
		$this->load->model('pendaftaran_model','MDaftar');
		
		$limit = jqgrid_set_limit('v_npwrd');
						
		$response->page = $limit['page']; 
		$response->total = $limit['total_pages'];
		$response->records = $limit['records'];
				
		$result = $this->MDaftar->get_npwrd($limit)->result_array();
		
		for($i=0; $i<count($result); $i++){
					
			$response->rows[$i]['id']=$result[$i]['ID'];
			// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
			$response->rows[$i]['cell']=array($result[$i]['ID'],
											$result[$i]['NAMA'],												
											$result[$i]['NPWP'],
											$result[$i]['JENIS_USAHA']
										);
		}
		echo json_encode($response);
	}
	
	function set_npwpd()
	{			
		$this->load->model('pendaftaran_model','MDaftar');
		
		$limit = jqgrid_set_limit('v_npwpd');
						
		$response->page = $limit['page']; 
		$response->total = $limit['total_pages'];
		$response->records = $limit['records'];
				
		$result = $this->MDaftar->get_npwpd($limit)->result_array();
		
		for($i=0; $i<count($result); $i++){
					
			$response->rows[$i]['id']=$result[$i]['ID'];
			// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
			$response->rows[$i]['cell']=array($result[$i]['ID'],
											$result[$i]['NAMA'],												
											$result[$i]['NPWP'],
											$result[$i]['JENIS_USAHA']
										);
		}
		echo json_encode($response);
	}
	
	function daftar()
	{
		if(!isset($_POST['oper']))
		{				
			$id = $this->uri->segment(3);

			$limit = jqgrid_set_limit('spt');
			
			$response->page = $limit['page']; 
			$response->total = $limit['total_pages'];
			$response->records = $limit['records'];		
								  			  
			$result = $this->data_model->get_data($limit)->result_array();
								
			for($i=0; $i<count($result); $i++){
						
				$response->rows[$i]['id']=$result[$i]['SPT_ID'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				
				$sistem_pungutan = $result[$i]['JENIS_PUNGUTAN']=='OFFICE' ? 'Office Assestment' : 'Selft Assestment';
				
				$response->rows[$i]['cell']=array($result[$i]['SPT_ID'],
												sprintf('%05d',$result[$i]['SPT_NO']),
												format_date($result[$i]['TGL_KIRIM']),
												$sistem_pungutan,	
												substr($result[$i]['NPWP'],1,strlen($result[$i]['NPWP'])),
												$result[$i]['PEMOHON'],
												$result[$i]['JENIS_PUNGUTAN'],
												$result[$i]['ALAMAT'],
												$result[$i]['NAMA_KECAMATAN'],
												$result[$i]['NAMA_DESA'],
												$result[$i]['TGL_KEMBALI'],
												$result[$i]['KODE_REKENING'],
												$result[$i]['NAMA_REKENING'],
												$result[$i]['PENERIMA_NAMA'],
												$result[$i]['PENERIMA_ALAMAT'],
												$result[$i]['MEMO'],
												$result[$i]['PENDAFTARAN_ID'],
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
		$nomor = $this->data_model->get_next_no();
		echo sprintf('%05d',$nomor);
	}	
	
	function post()
	{
		$this->data_model->fill_data();
		
		if(isset($_REQUEST['action']) && $_REQUEST['action']=='edit')
		{
			if($this->data_model->update_data())
			{
				$this->result_msg.='<li>:: Data telah diupdate ::</li>';
				//echo '<li>:: Data telah tersimpan ::</li>';
			}else{
				$this->result_msg.='<li>:: Gagal Update data ::</li>';
			}
			
			echo $this->result_msg;
			
		}else{
			
			if($this->data_model->insert_data())
			{
				$this->result_msg.='<li>:: Data telah tersimpan ::</li>';
				//echo '<li>:: Data telah tersimpan ::</li>';
			}else{
				$this->result_msg.='<li>:: Gagal tersimpan data ::</li>';
			}
			
			echo $this->result_msg;			
		}
	}
	
	function form()
	{		
		$this->post();
	}*/		
}

?>