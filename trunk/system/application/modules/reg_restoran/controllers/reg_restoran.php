<?php

class reg_restoran extends Controller
{	
	var $error_msg;
	var $result_msg;
	
	function reg_restoran()
	{
		parent::Controller();
		$this->error_msg=''; $this->result_msg='';
		//$this->load->library('firephp');
		//$this->load->library('jqgridcss','','css');
		$this->load->model('reg_restoran_model','data_model');
		
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
		
			$arr = array('is_save'=>1,'is_cancel'=>1);
			$data['mod']		  = 'reg_restoran';
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
			/*SELECT
			a.PENDATAAN_ID,
			a.JENIS_PUNGUTAN,
			a.PENDATAAN_NO,
			a.TGL_PROSES,
			b.TGL_KIRIM,
			vp.PEMOHON,
			vp.NPWP,
			pp.NO_PENETAPAN,
			pp.TGL_PENETAPAN
			from PENDATAAN_SPT a
			left join spt b on b.PENDAFTARAN_ID=a.PENDAFTARAN_ID
			left join v_pendaftaran vp on vp.PENDAFTARAN_ID=a.PENDAFTARAN_ID
			left join penetapan_pr_content ppc on ppc.pendataan_id=a.pendataan_id
			left join penetapan_pr pp on pp.penetapan_pr_id=ppc.penetapan_pr_id
			where a.JENIS_PENDATAAN='RETRIBUSI'*/
				
		if(!isset($_POST['oper']))
		{				
			$id = $this->uri->segment(3);
			
			$owhere['where'] = "a.jenis_pendataan='RESTORAN'";
			
			$limit = jqgrid_set_limit('pendataan_spt a',$owhere);
			
			$limit['where'] = $owhere['where'];
			
			$response->page = $limit['page']; 
			$response->total = $limit['total_pages'];
			$response->records = $limit['records'];		
			
			$result = $this->data_model->get_data($limit)->result_array();
								
			for($i=0; $i<count($result); $i++){
									
				$response->rows[$i]['id']=$result[$i]['PENDATAAN_ID'];
				
				$sistem_pungutan = $result[$i]['JENIS_PUNGUTAN']=='OFFICE' ? 'Office Assestment' : 'Selft Assestment';
				
				if(isset($result[$i]['TGL_KIRIM'])) $periode = date('Y',strtotime($result[$i]['TGL_KIRIM']));
				else $periode = date('Y',strtotime($result[$i]['TGL_PROSES']));
						
				$response->rows[$i]['cell']=array($result[$i]['PENDATAAN_ID'],
												$sistem_pungutan,
												$result[$i]['JENIS_PUNGUTAN'],
												sprintf('%05d',$result[$i]['PENDATAAN_NO']),
												format_date($result[$i]['PERIODE_AWAL']).' - '.format_date($result[$i]['PERIODE_AKHIR']),
												$result[$i]['NPWP'],
												$result[$i]['PEMOHON'],
												format_date($result[$i]['TGL_PENETAPAN']),
												$result[$i]['NO_PENETAPAN'],
												format_date($result[$i]['TGL_PENERIMAAN']),
												format_date($result[$i]['TGL_PROSES']),
												format_date($result[$i]['TGL_ENTRY']),
												substr($result[$i]['NPWP'],1,strlen($result[$i]['NPWP'])),
												$result[$i]['MEMO'],
												date('Y',strtotime($result[$i]['TGL_KIRIM'])),
												format_date($result[$i]['TGL_KIRIM']),
												sprintf('%05d',$result[$i]['SPT_NO']),
												$result[$i]['SPT_ID'],
												$result[$i]['PENDAFTARAN_ID'],
												$result[$i]['ALAMAT'],
												$result[$i]['NAMA_KECAMATAN'],
												$result[$i]['NAMA_DESA'],
												format_date($result[$i]['PERIODE_AWAL']),
												format_date($result[$i]['PERIODE_AKHIR']),
												$result[$i]['KODE_REKENING'],
												$result[$i]['NAMA_REKENING'],
												$result[$i]['ID_REKENING'],
												$result[$i]['PERSEN_TARIF'],
												$result[$i]['DASAR_PENGENAAN'],
												$result[$i]['NOMINAL'],
												$result[$i]['RESTORAN_NAMA'],
												$result[$i]['RESTORAN_ALAMAT'],												
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
	
	function getno()
	{
		$nomor = $this->data_model->get_next_no();
		echo sprintf('%05d',$nomor);
	}	
	
	function submit()
	{		
		$arr['ID']    = (isset($_REQUEST['action']) && $_REQUEST['action']=='edit') ? '' : $this->data_model->setID();
		$arr['nomor'] = (isset($_REQUEST['action']) && $_REQUEST['action']=='edit') ? '' : $this->data_model->get_next_no();
		
		$arr['total'] = str_replace(',','',$this->input->post('dasar_pengenaan'))*str_replace(',','',$this->input->post('persen_tarif'))/100;
				
		$this->data_model->fill_data($arr);
		
		$arr['id_rekening']    = $this->input->post('idrekening');
		$arr['restoran_nama']  = $this->input->post('nama_restoran');
		$arr['restoran_alamat']  = $this->input->post('alamat_restoran');
		$arr['restoran_id_desa']  = $this->input->post('restoran_id_desa');
		//$arr['dasar_tarif']	   = str_replace(',','',$this->input->post('dasar_tarif'));
		$arr['persen_tarif']   = str_replace(',','',$this->input->post('persen_tarif'));
		$arr['dasar_pengenaan']= str_replace(',','',$this->input->post('dasar_pengenaan'));
		$arr['nominal']		   = ($arr['dasar_pengenaan']*$arr['persen_tarif'])/100;
		
				
		$this->data_model->fill_rincian_data($arr);
				
		$this->db->trans_start();
		
		if(isset($_REQUEST['action']) && $_REQUEST['action']=='edit')
		{
			$id = $this->input->post('idmasters');
			
			if($this->data_model->update_data($id))
			{
				if($this->data_model->update_rincian_data($id))
				{				
					$this->result_msg.='<li>:: Data telah diupdate ::</li>';
					$this->db->trans_complete();
					
				}else{
					$this->result_msg.='<li>:: Error Update data ::</li>';
				}				

			}else{
				$this->result_msg.='<li>:: Gagal Update data ::</li>';
			}
			
			echo $this->result_msg;
			
		}else{
			
			if($this->data_model->insert_data())
			{
				if($this->data_model->insert_rincian_data())
				{
					$this->result_msg.='<li>:: Data telah tersimpan ::</li>';
					//echo '<li>:: Data telah tersimpan ::</li>';
					
				}else{
					$this->result_msg.='<li>:: Error tersimpan data ::</li>';
				}
								
			}else{
				$this->result_msg.='<li>:: Gagal tersimpan data ::</li>';
			}
			
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