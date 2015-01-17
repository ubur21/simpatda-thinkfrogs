<?php

class np_pribadi extends Controller
{	
	var $error_msg;
	
	function np_pribadi()
	{
		parent::Controller();
		$this->error_msg='';
		$this->load->library('firephp');
		//$this->load->model('np_pribadi_model','data_model');
		$this->load->model('data_pribadi_model','data_model');
	}
	
	function index()
	{		
		if(b_logged()){
			/*$this->load->model('desa_model');
			$arr = array();
			$row = $this->desa_model->get_all_data();
			for($i=0; $i<count($row); $i++){
				//$arr[$i]['id']   = $row[$i]->ID;
				//$arr[$i]['nama'] = $row[$i]->DESA_NAMA.'('.$row[$i]->DESA_KODE.')';
				$desa.="'".$row[$i]->ID."':'".$row[$i]->DESA_NAMA." (".$row[$i]->DESA_KODE.")',";
			}
			$data['desa'] = $desa;*/
			$data['main_content'] = 'pendaftaran/daftar_np_pribadi';
		}else{
			$data['main_content'] = 'login/login';
		}
		$this->load->view($this->config->item('layout_dir').'/template', $data);
		//show('',$data);
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
						
				$response->rows[$i]['id']=$result[$i]['PRIBADI_ID'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$response->rows[$i]['cell']=array($result[$i]['PRIBADI_ID'],
												$result[$i]['NAMA'],
												$result[$i]['NO_KTP'],
												$result[$i]['TEMPAT_LAHIR'],
												$result[$i]['TANGGAL_LAHIR'],
												$result[$i]['PEKERJAAN'],
												$result[$i]['ALAMAT'],
												$result[$i]['RT'],
												$result[$i]['RW'],
												$result[$i]['KODEPOS'],
												$result[$i]['ID_DESA'],
												$result[$i]['NO_TELP'],
												$result[$i]['NO_HP']
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
}

?>