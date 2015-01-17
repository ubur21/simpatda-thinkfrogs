<?php

class kode_usaha extends Controller
{	
	var $error_msg;
	
	function kode_usaha()
	{
		parent::Controller();
		$this->error_msg='';
		$this->load->library('firephp');
		$this->load->model('kode_usaha_model','data_model');
	}
	
	function index()
	{		
		if(b_logged()){
			$data['main_content'] = $this->load->view('master/daftar_kodeusaha','',true);
		}else{
			$data['main_content'] = $this->load->view('login/login','',true);
		}
		$this->load->view($this->config->item('layout_dir').'/template', $data);
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
												$result[$i]['KODE'],
												$result[$i]['NAMA']
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
			$this->error_msg='Error insert desa';
		}
		echo json_encode(array('errors'=> $this->error_msg));
	}
	
	function update($id)
	{
		if(!$this->data_model->update_data($id))
		{
			$this->error_msg='Error update desa';
		}
		echo json_encode(array('errors'=> $this->error_msg));	
	}
	
	function delete($id)
	{
		if(!$this->data_model->delete_data($id))
		{
			$this->error_msg='Error delete desa';
		}
		echo json_encode(array('errors'=> $this->error_msg));	
	}
}

?>