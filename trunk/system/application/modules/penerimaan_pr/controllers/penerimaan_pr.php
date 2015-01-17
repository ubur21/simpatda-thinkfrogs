<?php

class penerimaan_pr extends Controller
{	
	var $error_msg;
	var $result_msg;
	
	function penerimaan_pr()
	{
		parent::Controller();
		$this->error_msg=''; $this->result_msg='';
		
		$this->external->set(array(
            'css' => base_url().'assets/css/jquery.autocomplete.css',
            'js' => array(base_url().'assets/script/jquery/jquery.autocomplete.min.js'
						  ,base_url().'assets/script/jquery/jquery.metadata.js'
						  ,base_url().'assets/script/jquery/jquery.maskedinput.js'
						  ,base_url().'assets/script/jquery/jquery.validate.min.js'
						  ,base_url().'assets/script/jquery/jquery.formatCurrency-1.4.0.js'
						  ,base_url().'assets/fr/fastreport.js'
					),
        ));
		
		$this->load->model('penerimaan_pr_model','data_model');
		
	}
		
	function index()
	{		
		/*if(b_logged()){
		
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
		
		$this->load->view($this->config->item('layout_dir').'/template', $data);*/
	}
	
	function office()
	{
		
		if(b_logged()){
		
			$arr = array('is_save'=>1,'is_cancel'=>1,'is_print'=>1);
			$data['title_form']   = 'OFFICE ASSESMENT';
			$data['mod']		  = 'penerimaan_pr';
			$data['button_form']  = $this->load->view('element/button',$arr,true);
			//$data['title_form']   = get_name_menu($this->uri->segment(1));
			$data['form'] 		  = $this->load->view('penerimaan_office',$data,true);
			$data['main_content'] = $this->load->view('daftar',$data,true);
						
			$this->load->vars($data);
		
		}else{
			$data['main_content'] = $this->load->view('login/login','',true);
		}
		
		$this->load->view($this->config->item('layout_dir').'/template', $data);		
	}
	
	function self()
	{
		
		if(b_logged()){
		
			$arr = array('is_save'=>1,'is_cancel'=>1);
			$data['title_form']   = 'SELF ASSESMENT';
			$data['mod']		  = 'penerimaan_pr';
			$data['button_form']  = $this->load->view('element/button',$arr,true);
			//$data['title_form']   = get_name_menu($this->uri->segment(1));
			$data['form'] 		  = $this->load->view('penerimaan_self',$data,true);
			$data['main_content'] = $this->load->view('daftar',$data,true);			
			
			$this->load->vars($data);
		
		}else{
			$data['main_content'] = $this->load->view('login/login','',true);
		}
		
		$this->load->view($this->config->item('layout_dir').'/template', $data);		
	}
		
	function form_office()
	{
		$this->db->trans_start();
		
		list($day,$month,$year) = explode('/',$_REQUEST['tgl_penerimaan']);
		
		$arr['ID']= $this->data_model->setID();
		
		$no_bayar = getNoPenerimaan($_REQUEST['jenis_pungutan'],$_REQUEST['tgl_penerimaan']);
		
		$this->data_model->data = array(
										'penerimaan_pr_id'=>$arr['ID']
										,'penetapan_pr_id'=>$this->input->post('id_kohir')
										,'pendataan_id'=>$this->input->post('id_spt')
										,'pendaftaran_id'=>$this->input->post('id_npwp')
										,'pemohon_id'=>$this->input->post('id_pemohon')
										,'skpd_id'=>$this->input->post('id_dinas')
										,'penerimaan_pr_no'=>$no_bayar
										,'tgl_penerimaan'=>prepare_date($_REQUEST['tgl_penerimaan'])
										,'thn_penerimaan'=>date('Y',strtotime(prepare_date($_REQUEST['tgl_penerimaan'])))
										,'bank_no'=>$this->input->post('bank_no')
										,'jenis_pungutan'=>$this->input->post('jenis_pungutan')
										,'nominal_pajak'=>str_replace(',','',$this->input->post('nominal_pajak'))
										,'id_ref_pembayaran'=>$this->input->post('cpenyetoran')
										,'keterangan'=>$this->input->post('keterangan')
										,'logs'=>'NOW'
										,'user_id'=>$this->session->userdata('SESS_USER_ID')										
									);
		
		$hsl = $this->data_model->insert_data();
		
		$qy = 'select id_rekening, nominal from v_pendataan_content where pendataan_id='.$this->db->escape($_REQUEST['id_spt']);
		
		$result = $this->db->query($qy);
		
		if($result->num_rows()>0)
		{
			foreach($result->result() as $row)
			{
			
				$this->data_model->data_rincian = array(
										'penerimaan_pr_id'=>$arr['ID']
										,'id_rekening'=>$row->ID_REKENING
										,'nominal'=>$row->NOMINAL
									);
									
				$hsl = $this->data_model->insert_rincian_data();
			}
		}
		
		if($hsl) $this->result_msg='<li>:: Data telah tersimpan ::</li>';
		else $this->result_msg='<li>:: Error tersimpan data ::</li>';
		
		updateNoPenerimaan($_REQUEST['jenis_pungutan'],$_REQUEST['tgl_penerimaan']);
		
		echo $this->result_msg;
		
		$this->db->trans_complete();
		
	}
	
	function set_form_office_assesment()
	{
		$id = $this->uri->segment(3);
		
		$qy = 'select
					a.no_penetapan,
					a.nominal_penetapan,
					c.pendaftaran_id,
					c.pendataan_id,
					c.pemohon_id,
					c.npwp,
					c.pemohon,
					c.alamat,
					c.kelurahan,
					c.kecamatan
					from penetapan_pr a
					join penetapan_pr_content b on a.penetapan_pr_id=b.penetapan_pr_id
					join v_pendataan_spt c on c.pendataan_id=b.pendataan_id
					where a.penetapan_pr_id='.$this->db->escape($id);
					
		$rs = $this->db->query($qy)->row();
		
		$arr = array(
			'no_kohir'=>$rs->NO_PENETAPAN,
			'nominal_pajak'=>$rs->NOMINAL_PENETAPAN,
			'no_pokok'=>$rs->NPWP,
			'id_npwp'=>$rs->PENDAFTARAN_ID,
			'id_spt'=>$rs->PENDATAAN_ID,
			'nama_pemohon'=>$rs->PEMOHON,
			'id_pemohon'=>$rs->PEMOHON_ID,
			'alamat'=>$rs->ALAMAT,
			'kecamatan'=>$rs->KECAMATAN,
			'kelurahan'=>$rs->KELURAHAN
		);
		
		echo json_encode($arr);
				
	}
	
	function set_form_self_assesment()
	{
		
	}
	
	function sts()
	{			
		$ids = $this->uri->segment(3);
		
		$this->db->join('penerimaan_pr_content b','a.PENERIMAAN_PR_ID=b.PENERIMAAN_PR_ID','left');
		$this->db->join('rekening_kode c','b.ID_REKENING=c.ID','left');
		$this->db->join('sts_content d','d.ID_REKENING=b.ID_REKENING','left');
		
		$owhere['where'] = "d.STS_CONTENT_ID is null and a.id_ref_pembayaran='BD' ";
		
		if(isset($ids) && $ids!='')
		{
			$id_arr = explode('_',$ids); $param='';
					
			foreach($id_arr as $id=>$value){
				if($value!='') $param.=$value.',';
			}
			$param = trim($param);
			$param = substr($param,0,strlen($param)-1);
			
			$cwhere = " and b.id_rekening not in ($param)";
			
			$owhere['where'].= $cwhere;
		}			
		//$owhere['group'] = array('b.id_rekening','a.thn_penerimaan','c.kode_rekening','c.nama_rekening');
		
		$owhere['group'] = 'b.ID_REKENING, a.THN_PENERIMAAN, c.ID, c.KODE_REKENING, c.NAMA_REKENING';
		
		$limit = jqgrid_set_limit('penerimaan_pr a',$owhere);
				
		$limit['where'] = $owhere['where'];
		$limit['group'] = $owhere['group'];
		
		$response->page = $limit['page']; 
		$response->total = $limit['total_pages'];
		$response->records = $limit['records'];		
		
		$result = $this->data_model->pilih_penerimaan($limit)->result_array();
									
		for($i=0; $i<count($result); $i++){
								
			$response->rows[$i]['id']=$result[$i]['ID_REKENING'];
																			
			$response->rows[$i]['cell']=array($result[$i]['ID_REKENING'],
											$result[$i]['KODE_REKENING'],
											$result[$i]['NAMA_REKENING'],
											date('Y',strtotime($result[$i]['THN_PENERIMAAN'])),
											$result[$i]['SUM']
										);
		}
		echo json_encode($response);	
	
	}
	
	function daftar()
	{	
		$ids = $this->uri->segment(3);
		
		$this->db->join('penerimaan_pr_content aa','a.PENERIMAAN_PR_ID=aa.PENERIMAAN_PR_ID','left');
		$this->db->join('satker sk','sk.ID_SATKER=a.SKPD_ID','left');
		$this->db->join('v_pendaftaran vd','vd.PENDAFTARAN_ID=a.PENDAFTARAN_ID','left');
		
		//$owhere['where'] = "d.STS_CONTENT_ID is null and a.id_ref_pembayaran='BD' ";
		
		//$owhere['group'] = array('b.id_rekening','a.thn_penerimaan','c.kode_rekening','c.nama_rekening');
		
		//$owhere['group'] = 'b.ID_REKENING, a.THN_PENERIMAAN, c.ID, c.KODE_REKENING, c.NAMA_REKENING';
		
		$limit = jqgrid_set_limit('PENERIMAAN_PR a');
		
		//$limit['where'] = $owhere['where'];
		//$limit['group'] = $owhere['group'];
		
		$response->page = $limit['page']; 
		$response->total = $limit['total_pages'];
		$response->records = $limit['records'];		
		
		$result = $this->data_model->get_data($limit)->result_array();
									
		for($i=0; $i<count($result); $i++){
								
			$response->rows[$i]['id']=$result[$i]['PENERIMAAN_PR_ID'];
											
			$jenis_pungutan = ($result[$i]['JENIS_PUNGUTAN']=='SELF') ? 'Self Assesment' : 'Office Assesment';
											
			$response->rows[$i]['cell']=array($result[$i]['PENERIMAAN_PR_ID'],
											$result[$i]['PENERIMAAN_PR_NO'],
											format_date($result[$i]['TGL_PENERIMAAN']),
											$jenis_pungutan,
											$result[$i]['NOMINAL_PAJAK'],
											$result[$i]['NAMA_SATKER'],
											$result[$i]['NPWP'],
											$result[$i]['PEMOHON']
										);
		}
		echo json_encode($response);		
	}
		
	function js()
	{
		$this->load->view('js/script.js');
	}

}

?>