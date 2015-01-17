<?php

class penerbitan_sk extends Controller
{	
	var $error_msg;
	var $result_msg;
	
	function penerbitan_sk()
	{
		parent::Controller();
		$this->error_msg=''; $this->result_msg='';
		
		$this->external->set(array(
            'css' => base_url().'assets/css/jquery.autocomplete.css',
            'js' => array(base_url().'assets/script/jquery/jquery.autocomplete.min.js'
						  ,base_url().'assets/script/jquery/jquery.metadata.js'
						  ,base_url().'assets/script/jquery/jquery.maskedinput.js'
						  ,base_url().'assets/script/jquery/jquery.validate.min.js'
						  ,base_url().'assets/fr/fastreport.js'
					),
        ));
		
	}
		
	function index()
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
	
	function daftar_sk()
	{
		
		if(b_logged()){
		
			$arr = array('is_print'=>1);
			$data['mod']		  = 'penerbitan_sk';
			$data['button_form']  = $this->load->view('element/button',$arr,true);
			//$data['title_form']   = get_name_menu($this->uri->segment(1));
			$data['title_form']   = 'CETAK SURAT KETETAPAN';
			$data['form'] 		  = $this->load->view('form_daftar_surat_ketetapan',$data,true);
			$data['main_content'] = $this->load->view('daftar',$data,true);
			
			//$data['main_content'] = $this->load->view('form_daftar_surat_ketetapan',$data,true);
			
			$this->load->vars($data);
		
		}else{
			$data['main_content'] = $this->load->view('login/login','',true);
		}
		
		$this->load->view($this->config->item('layout_dir').'/template', $data);		
	}
	
	function sk()
	{
		
		if(b_logged()){
		
			$arr = array('is_print'=>1);
			$data['mod']		  = 'penerbitan_sk';
			$data['button_form']  = $this->load->view('element/button',$arr,true);
			//$data['title_form']   = get_name_menu($this->uri->segment(1));
			$data['title_form']   = 'CETAK SURAT KETETAPAN';
			$data['form'] 		  = $this->load->view('form_surat_ketetapan',$data,true);
			$data['main_content'] = $this->load->view('daftar',$data,true);			
			//$data['main_content'] = $this->load->view('form_surat_ketetapan',$data,true);
			
			$this->load->vars($data);
		
		}else{
			$data['main_content'] = $this->load->view('login/login','',true);
		}
		
		$this->load->view($this->config->item('layout_dir').'/template', $data);		
	}
	
	function tunggakan()
	{
		
		if(b_logged()){
		
			$arr = array('is_print'=>1);
			
			$user = $this->db->query('select
					b.NAMA_LKP,
					b.NIP,
					c.NAMA as jabatan
					from g_users a
					left join pegawai b on a.PEGAWAI_ID=b.PEGAWAI_ID
					left join jabatan c on c.ID=b.JABATAN_ID
					where a.NID='.$this->db->escape($this->session->userdata('SESS_USER_ID')))->row();
					
			$data['nama_ptgs']    = $user->NAMA_LKP;
			$data['jabatan']      = $user->JABATAN;
			$data['nip']		  = $user->NIP;
			
			$data['mod']		  = 'penerbitan_sk';
			$data['button_form']  = $this->load->view('element/button',$arr,true);
			//$data['title_form']   = get_name_menu($this->uri->segment(1));
			$data['title_form']   = 'CETAK DAFTAR TUNGGAKAN';
			$data['form'] 		  = $this->load->view('form_daftar_tunggakan',$data,true);
			$data['main_content'] = $this->load->view('daftar',$data,true);						
			
			//$data['main_content'] = $this->load->view('form_daftar_tunggakan',$data,true);
			
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

}

?>