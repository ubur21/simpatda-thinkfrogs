<?php

class ref_desa extends Controller
{	
	function ref_desa()
	{
		parent::Controller();
		$this->load->library('firephp');
		$this->load->model('desa_model','data_model');
	}
	
	function index()
	{		
		if(b_logged()){
			$data['main_content'] = $this->load->view('master/desa','',true);
		}else{
			$data['main_content'] = $this->load->view('login/login','',true);
		}
		$this->load->view($this->config->item('layout_dir').'/template', $data);
		//show('',$data);
	}
	
	function getselect()
	{
		$arr = array();
		$row = $this->data_model->get_all_data();
		
		for($i=0; $i<count($row); $i++){
			$arr[$i]['id']   = $row[$i]->ID;
			$arr[$i]['nama'] = $row[$i]->DESA_NAMA.'('.$row[$i]->DESA_KODE.')';
			//$selected = $this->spt_pengikut_model->get_select_peserta_biaya($row[$i]->ID_SPT_PENGIKUT, $keperluan);
			$arr[$i]['selected'] = '';
		}
		return json_encode($arr);
				
		/*echo "<select>";
		echo "<option value=''> --- </option>";
		for($i=0; $i<count($row); $i++){
			echo "<option value='". $row[$i]->ID."'>". $row[$i]->NAMA_DESA ." (". $row[$i]->KODE_DESA .") </option>";
		}
		echo "</select>";*/
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
					"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null
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
						
				$response->rows[$i]['id']=$result[$i]['ID'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array($result[$i]['ID'],
												$result[$i]['NAMA'],
												$result[$i]['BADAN_TIPE'],
												$result[$i]['ALAMAT'],
												$result[$i]['KODEPOS'],
												$result[$i]['BADAN_TELP'],
												$result[$i]['BADAN_FAX'],
												$result[$i]['BADAN_NPWP'],
												$result[$i]['PEMILIK_NAMA'],
												$result[$i]['PEMILIK_NO_KPT'],
												$result[$i]['PEMILIK_TGL_LAHIR'],
												$result[$i]['PEMILIK_ALAMAT'],
												$result[$i]['PEMILIK_RT'],
												$result[$i]['PEMILIK_RW'],
												$result[$i]['PEMILIK_KODEPOS'],
												$result[$i]['PEMILIK_ID_DESA'],
												$result[$i]['PEMILIK_TELP'],
												$result[$i]['PEMILIK_HP'],
												$result[$i]['PEMILIK_NPWP']
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
}

?>