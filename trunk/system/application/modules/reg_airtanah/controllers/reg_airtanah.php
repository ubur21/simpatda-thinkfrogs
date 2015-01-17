<?php

class reg_airtanah extends Controller
{	
	var $error_msg;
	var $result_msg;
	
	function reg_airtanah()
	{
		parent::Controller();
		$this->error_msg=''; $this->result_msg='';
		//$this->load->library('firephp');
		//$this->load->library('jqgridcss','','css');
		$this->load->model('reg_airtanah_model','data_model');
		
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
			$data['mod']		  = 'reg_airtanah';
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
			
			$owhere['where'] = "a.jenis_pendataan='AIRTANAH' ";
			
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
												$result[$i]['NOMINAL']
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
							
						}
					}
					$this->db->trans_complete();
				}
			}
		}	
	}
	
	function setrekening()
	{
		$filter = $this->uri->segment(4);
		
		//$filter = html_entity_decode($filter);
		
		//$filter = str_replace('_','%',$filter);
		
		//$limit['where'] = " lower(a.nama_rekening) like '%air%tanah%' ";
				
		$row = $this->data_model->get_rekening();
		
		//print_r($this->db->queries);
		
		echo "<select>";
		echo "<option value=''> --- </option>";
		for($i=0; $i<count($row); $i++){
			echo "<option value='". $row[$i]->ID_REKENING ."'>". $row[$i]->KODE_REKENING ." - ". $row[$i]->NAMA_REKENING ."</option>";
		}
		echo "</select>";
	}
	
	function grid_form()
	{
		$id = $this->uri->segment(3);
		
		if(isset($id) && $id!=''){
		
			$id = $this->uri->segment(3);
			
			$owhere['where'] = " a.pendataan_id=".$this->db->escape($id);
			
			$limit = jqgrid_set_limit('pendataan_air a',$owhere);
			
			$limit['where'] = $owhere['where'];
			
			$response->page = $limit['page']; 
			$response->total = $limit['total_pages'];
			$response->records = $limit['records'];
			
			$query  = 'select a.*, b.kode_rekening,b.nama_rekening from pendataan_air a left join rekening_kode b on b.id=a.id_rekening where a.pendataan_id='.$this->db->escape($id);
			
			$result = $this->db->query($query);
			
			if ($result->num_rows() > 0)
			{  $i=0;
			   foreach ($result->result() as $row)
			   {
					$response->rows[$i]['id']=$row->AIR_ID;
					
					$response->rows[$i]['cell']=array(
													$row->AIR_ID,
													//$row->ID_REKENING,
													$row->KODE_REKENING.' - '.$row->NAMA_REKENING,
													$row->JUMLAH,
													$row->DASAR_TARIF,
													$row->DASAR_PENGENAAN,
													$row->PERSEN_TARIF,
													$row->NOMINAL
												);
				    $i++;
			   }
			} 			
			
			echo json_encode($response);
		
		}else{
		
			echo '{"page":"1","total":0,"records":null}';
			
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
		
		//$arr['total'] = str_replace(',','',$this->input->post('dasar_pengenaan'))*str_replace(',','',$this->input->post('persen_tarif'))/100;
		$arr['total'] = 0;
				
		$this->data_model->fill_data($arr);
		
		foreach($_REQUEST['idrows'] as $idx=>$value)
		{
			$arr[$idx]['pparkir_id']     =  $value;
			$arr[$idx]['ID']     		 = $arr['ID'];
			//$arr[$idx]['id_rekening']  = $_REQUEST['kode_rekening'][$idx];
			
			$arr[$idx]['id_rekening']    = getIdRekening($_REQUEST['kode_rekening'][$idx]);
			
			$arr[$idx]['dasar_pengenaan'] = $_REQUEST['dasar_pengenaan'][$idx];
			$arr[$idx]['dasar_tarif'] 	  = $_REQUEST['dasar_tarif'][$idx];
			$arr[$idx]['jumlah'] 	      = $_REQUEST['jumlah'][$idx];			
			$arr[$idx]['persen_tarif']    = $_REQUEST['persen_tarif'][$idx];
			$arr[$idx]['nominal']         = $_REQUEST['pajak'][$idx];
			
			$total+=$arr[$idx]['nominal'];
			
		}
		
		$arr['total'] =  $total;
						
		$this->db->trans_start();
		
		if($_REQUEST['action']=='edit')
		{
			$id = $this->input->post('idmasters');
			
			if($this->data_model->update_data($id))
			{
				$this->data_model->delete_rincian_data($id);
				
				foreach($_REQUEST['idrows'] as $idx=>$value)
				{
					$this->data_model->fill_rincian_data($arr[$idx]);
					
					//if($this->data_model->update_rincian_data($arr[$idx]['pparkir_id']))
					if($this->data_model->insert_rincian_data())
					{				
						$this->result_msg ='<li>:: Data telah diupdate ::</li>';
						$this->db->trans_complete();
						
					}else{
						$this->result_msg ='<li>:: Error Update data ::</li>';
					}
					
				}
				
				$this->db->query('update pendataan_spt set nominal='.$this->db->escape($arr['total']).' where pendataan_id='.$this->db->escape($id));

			}else{
				$this->result_msg.='<li>:: Gagal Update data ::</li>';
			}
			
			echo $this->result_msg;
			
		}else{
			
			if($this->data_model->insert_data())
			{
				
				foreach($_REQUEST['idrows'] as $idx=>$value)
				{
					$this->data_model->fill_rincian_data($arr[$idx]);
					
					if($this->data_model->insert_rincian_data())
					{
						$this->result_msg='<li>:: Data telah tersimpan ::</li>';
						
					}else{
						$this->result_msg='<li>:: Error tersimpan data ::</li>';
					}
				}
								
			}else{
				$this->result_msg='<li>:: Gagal tersimpan data ::</li>';
			}
			
			$this->db->query('update pendataan_spt set nominal='.$this->db->escape($arr['total']).' where pendataan_id='.$this->db->escape($arr['ID']));
			
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