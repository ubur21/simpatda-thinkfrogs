<?php

class sts extends Controller
{	
	var $error_msg;
	var $result_msg;
	
	function sts()
	{
		parent::Controller();
		$this->error_msg=''; $this->result_msg='';
		//$this->load->library('firephp');
		//$this->load->library('jqgridcss','','css');
		$this->load->model('sts_model','data_model');
		
		$this->external->set(array(
            'css' => base_url().'assets/css/jquery.autocomplete.css',
            'js' => array(base_url().'assets/script/jquery/jquery.autocomplete.min.js'
						  ,base_url().'assets/script/jquery/jquery.metadata.js'
						  ,base_url().'assets/script/jquery/jquery.maskedinput.js'
						  ,base_url().'assets/script/jquery/jquery.validate.min.js'
						  ,base_url().'assets/script/jquery/jquery.formatCurrency-1.4.0.js'
					),
        ));
		
	}
		
	function index()
	{		
		if(b_logged()){
		
			//$tgl = date('d/m/Y');
			
			//$jatem_bayar = $this->db->query('select jatem_bayar from ref_jatuh_tempo')->row()->JATEM_BAYAR;
			
			//$tgl_tempo = getExpired($tgl,$jatem_bayar);
			
			$arr = array('is_save'=>1,'is_cancel'=>1);
			//$data['tgl_penetapan']= $tgl;
			//$data['tgl_setor']	  = $tgl_tempo;
			$data['mod']		  = 'sts';
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
	
	function daftar()
	{		
		
		if(!isset($_POST['oper']))
		{				
			$id = $this->uri->segment(3);
			
			//$owhere['where'] = "a.jenis_pendataan='RETRIBUSI'";
			
			$this->db->join('penetapan_pr_content b','a.penetapan_pr_id=b.penetapan_pr_id','left');
			$limit = jqgrid_set_limit('penetapan_pr a');
			
			//$limit['where'] = $owhere['where'];
			
			$response->page = $limit['page']; 
			$response->total = $limit['total_pages'];
			$response->records = $limit['records'];		
			
			$result = $this->data_model->get_data($limit)->result_array();
								
			for($i=0; $i<count($result); $i++){
									
				$response->rows[$i]['id']=$result[$i]['PENETAPAN_PR_ID'];
																				
				$response->rows[$i]['cell']=array($result[$i]['PENETAPAN_PR_ID'],
												$result[$i]['NO_PENETAPAN'],
												format_date($result[$i]['TGL_PENETAPAN']),
												format_date($result[$i]['TGL_SETOR']),
												number_format($result[$i]['NOMINAL'],2,'.',',')
											);
			}
			echo json_encode($response);
			
		}else{		
			$id = $this->input->post('id');
			if($_POST['oper'] === 'add'){
			
				$this->insert();
				
			}else if($_POST['oper'] === 'edit'){
			
				$this->update($id);
				
			}else if($_POST['oper'] === 'del'){
			
				if(!$this->data_model->isAbleDelete())
				{
					$this->db->trans_start();				
					if($this->data_model->delete_rincian_data($id)){
						if($this->data_model->delete_data($id))
						{
							$this->db->trans_complete();
						}
					}
				}
			}
		}	
	}
	
	function pilih_kohir()
	{
		$id = $this->uri->segment(3);
			
		$this->db->join('penetapan_pr_content b','a.penetapan_pr_id=b.penetapan_pr_id','left');
		$this->db->join('penerimaan_pr c','c.PENETAPAN_PR_ID=a.PENETAPAN_PR_ID','left');
		
		$owhere['where'] = "c.PENETAPAN_PR_ID is null";
		
		$limit = jqgrid_set_limit('penetapan_pr a',$owhere);
		
		$limit['where'] = $owhere['where'];
		
		$response->page = $limit['page']; 
		$response->total = $limit['total_pages'];
		$response->records = $limit['records'];		
		
		$result = $this->data_model->pilih_kohir($limit)->result_array();
							
		for($i=0; $i<count($result); $i++){
								
			$response->rows[$i]['id']=$result[$i]['PENETAPAN_PR_ID'];
																			
			$response->rows[$i]['cell']=array($result[$i]['PENETAPAN_PR_ID'],
											$result[$i]['NO_PENETAPAN'],
											format_date($result[$i]['TGL_PENETAPAN']),
											format_date($result[$i]['TGL_SETOR']),
											number_format($result[$i]['NOMINAL'],2,'.',',')
										);
		}
		echo json_encode($response);	
	
	}	
	
	function grid_form()
	{
		$response->page = 1; 
		$response->total = 0;
		$response->records = 0;	
		
		echo json_encode($response);
	}
	
	function sptprd()
	{		
		$owhere['where'] = "b.pendataan_id is null";
		
		$this->db->join('penetapan_pr_content b','b.pendataan_id=a.pendataan_id','left');
		
		$limit = jqgrid_set_limit('v_pendataan_spt a',$owhere);
		
		$limit['where'] = $owhere['where'];
						
		$response->page = $limit['page']; 
		$response->total = $limit['total_pages'];
		$response->records = $limit['records'];
				
		$result = $this->data_model->get_sptprd($limit)->result_array();
				
		for($i=0; $i<count($result); $i++){
		
			$sistem_pungutan = $result[$i]['JENIS_PUNGUTAN']=='OFFICE' ? 'Office Assesment' : 'Selft Assesment';
					
			$response->rows[$i]['id']=$result[$i]['ID'];
			// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
			$response->rows[$i]['cell']=array($result[$i]['ID'],
											$result[$i]['PENDATAAN_NO'],												
											$result[$i]['TGL_ENTRY'],
											$result[$i]['NPWP'],
											$result[$i]['PEMOHON'],
											$result[$i]['JENIS_PENDATAAN'],
											$sistem_pungutan,
											$result[$i]['JENIS_PENDAFTARAN'],
											$result[$i]['SPT_NO'],
											$result[$i]['TGL_SPT'],
											$result[$i]['NOMINAL']
										);
		}
		echo json_encode($response);	
	}
	
	function getno()
	{
		$nomor = $this->data_model->get_next_no();
		echo sprintf('%05d',$nomor);
	}	
	
	function submit()
	{	
						
		$this->db->trans_start();
		
		if(isset($_REQUEST['action']) && $_REQUEST['action']=='edit')
		{

			
		}else{
		
			$arr['ID'] = $this->data_model->setID();
			
			$sts_no = getNoPenyetoran();
			
			$this->data_model->data = array(
											'sts_id'=>$arr['ID']
											,'sts_no'=>$sts_no
											,'sts_thn'=>date('Y',strtotime(prepare_date($this->input->post('tgl_setor'))))
											,'sts_tgl'=>prepare_date($this->input->post('tgl_setor'))
											,'nominal'=>str_replace(',','',$this->input->post('nominal_pajak'))
											,'keterangan'=>$this->input->post('keterangan')
											,'logs'=>'NOW'
											,'user_id'=>$this->session->userdata('SESS_USER_ID')
											,'no_bukti'=>$this->input->post('no_bukti')
										);
										
			$this->data_model->insert_data(); $ii=0;
					
			foreach($_REQUEST['idrows'] as $idx=>$value)
			{								
				$this->data_model->data_rincian = array(
													'sts_id'=>$arr['ID']
													,'id_rekening'=>$value
													,'nominal'=>$_REQUEST['nominal_rek'][$ii]
												);
				$ii++;
				
				$hsl = $this->data_model->insert_rincian_data();
											
			}

			if($hsl) $this->result_msg='<li>:: Data telah tersimpan ::</li>';
			else $this->result_msg='<li>:: Error tersimpan data ::</li>';			
			
			echo $this->result_msg;			
		}
		$this->db->trans_complete();
		
	}
	
	function form()
	{		
		$this->submit();
	}

}

?>