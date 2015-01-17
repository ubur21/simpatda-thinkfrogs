<?php

class rekening extends Controller
{	
	var $error_msg;
	
	function rekening()
	{
		parent::Controller();
		$this->error_msg='';
		$this->load->library('firephp');
		$this->load->model('rekening_model','data_model');
	}
	
	function index()
	{		
		if(b_logged()){
			$this->load->model('kategori_rekening_model','kategori');
			$row = $this->kategori->get_all_data(); $kategori='';
			for($i=0; $i<count($row); $i++) $kategori.="'".$row[$i]->ID."':'".$row[$i]->NAMA_KATEGORI."',";
			$row = $this->data_model->get_all_data(); $rekening="'':'',";
			for($i=0; $i<count($row); $i++) $rekening.="'".$row[$i]->ID."':'".$row[$i]->NAMA_REKENING."',";
			$data['kategori'] = $kategori;
			$data['rekening'] = $rekening;
			
			$data['main_content'] = $this->load->view('master/daftar_rekening','',true);
			
		}else{
			$data['main_content'] = $this->load->view('login/login','',true);
		}
		$this->load->view($this->config->item('layout_dir').'/template', $data);
	}
	
	function detail_selectgrid()
	{
		$id = $this->uri->segment(4);
		
		$qry = 'select persen_tarif, tarif_dasar from rekening_kode where id='.$this->db->escape($id);
		
		$result = $this->db->query($qry)->row();
		
		$arr = array(
					'persen'=>$result->PERSEN_TARIF,
					'tarif'=>$result->TARIF_DASAR,
				);
				
		echo json_encode($arr);
	}
	
	function gridselect()
	{
		$filter = $this->uri->segment(4);
		
		//$filter = html_entity_decode($filter);
		
		$filter = str_replace('_','%',$filter);
		
		$limit['where'] = " lower(a.nama_rekening) like '%".$filter."%' ";
				
		$row = $this->data_model->get_droplist($limit);
		
		//print_r($this->db->queries);
		
		echo "<select>";
		echo "<option value=''> --- </option>";
		for($i=0; $i<count($row); $i++){
			echo "<option value='". $row[$i]->ID_REKENING ."'>". $row[$i]->KODE_REKENING ." - ". $row[$i]->NAMA_REKENING ."</option>";
		}
		echo "</select>";
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
				
				/*$response->rows[$i]['cell']=array($result[$i]['ID'],
												$result[$i]['ID_KATEGORI'],
												$result[$i]['ID_PARENT'],
												$result[$i]['TIPE'],
												$result[$i]['KELOMPOK'],
												$result[$i]['JENIS'],
												$result[$i]['OBJEK'],
												$result[$i]['RINCIAN'],
												$result[$i]['KODE_REKENING'],
												$result[$i]['NAMA_REKENING']
											);*/
											
				$response->rows[$i]['cell']=array($result[$i]['ID'],
											$result[$i]['KODE_REKENING'],			
											$result[$i]['NAMA_REKENING']
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
	
	function daftar_mastr()
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
				
				/*$response->rows[$i]['cell']=array($result[$i]['ID'],
												$result[$i]['ID_KATEGORI'],
												$result[$i]['ID_PARENT'],
												$result[$i]['TIPE'],
												$result[$i]['KELOMPOK'],
												$result[$i]['JENIS'],
												$result[$i]['OBJEK'],
												$result[$i]['RINCIAN'],
												$result[$i]['KODE_REKENING'],
												$result[$i]['NAMA_REKENING']
											);*/
											
				$response->rows[$i]['cell']=array($result[$i]['ID'],
											$result[$i]['KODE_REKENING'],			
											$result[$i]['NAMA_REKENING'],
											$result[$i]['TARIF_DASAR'],
											$result[$i]['PERSEN_TARIF']
										);											
			}
			echo json_encode($response);
			
		}else{
			//$this->data_model->fill_data();
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
	
	function get_rekening()
	{
		$owhere['where'] = "tipe!='' and kelompok!='' and jenis!='' and objek!='' and rincian!='' and tipe='4' ";
		
		$limit = jqgrid_set_limit('rekening_kode',$owhere);
		
		$response->page = $limit['page']; 
		$response->total = $limit['total_pages'];
		$response->records = $limit['records'];		
		
		$limit['where'] = $owhere['where'];
		
		$result = $this->data_model->get_rekening($limit)->result_array();
				
		for($i=0; $i<count($result); $i++){
					
			$response->rows[$i]['id']=$result[$i]['ID'];
			// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
			$response->rows[$i]['cell']=array($result[$i]['ID'],
											$result[$i]['KODE_REKENING'],			
											$result[$i]['NAMA_REKENING'],
											number_format($result[$i]['TARIF_DASAR'],2,'.',','),
											$result[$i]['PERSEN_TARIF'],
										);
		}
		echo json_encode($response);
	}
	
	function seek()
	{
		$owhere="tipe!='' and kelompok!='' and jenis!='' and objek!='' and rincian!='' ";
		
		$limit = jqgrid_set_limit('rekening_kode',$owhere);
		
		$response->page = $limit['page']; 
		$response->total = $limit['total_pages'];
		$response->records = $limit['records'];
		
		$result = $this->data_model->get_rekening($limit)->result_array();
		
		for($i=0; $i<count($result); $i++){
					
			$response->rows[$i]['id']=$result[$i]['ID'];
			// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
			$response->rows[$i]['cell']=array($result[$i]['ID'],
											$result[$i]['KODE_REKENING'],			
											$result[$i]['NAMA_REKENING']
										);
		}
		echo json_encode($response);						
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
				
	}	
}

?>