<?php

class Status_keluarga extends Controller {

	function Status_keluarga()
	{
		parent::Controller();
		$this->load->model('status_keluarga_model', 'data_model');
	}
	
	function index()
	{
		if(!isset($_POST['oper'])){
			$data['title'] = "SIPTU - Status Keluarga";
			$data['main_content'] = 'master/status_keluarga';
			$this->load->view('layout/template', $data);
		}
		else {
			if($_POST['oper'] === 'add'){
				$this->insert($_POST);
			} else if($_POST['oper'] === 'edit'){
				$this->update($_POST);
			} else if($_POST['oper'] === 'del'){
				$this->delete($_POST);
			}
		}
	}

	function getselect()
	{
		$row = $this->data_model->get_all_data();
		echo "<select>";
		for($i=0; $i<count($row); $i++){
			echo "<option value='". $row[$i]->ID_STATUS_KELUARGA ."'>". $row[$i]->NAMA_STATUS_KELUARGA ."</option>";
		}
		echo "</select>";
	}

	function getselectv()
	{
		$row = $this->data_model->get_all_data();
		echo "<select>";
		for($i=0; $i<count($row); $i++){
			echo "<option value='". $row[$i]->NAMA_STATUS_KELUARGA ."'>". $row[$i]->NAMA_STATUS_KELUARGA ."</option>";
		}
		echo "</select>";
	}

    function get_daftar()
    {        
        $page = $_REQUEST['page']; // get the requested page 
        $limit = $_REQUEST['rows']; // get how many rows we want to have into the grid 
        $sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort 
        $sord = $_REQUEST['sord']; // get the direction if(!$sidx) $sidx =1;  
         
        $req_param = array (
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
        
        $req_param['limit'] = array(
                    'start' => $start,
                    'end' => $limit
        );
          
        
        $result = $this->data_model->get_data($req_param)->result_array();
        // sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
        $response->page = $page; 
        $response->total = $total_pages; 
        $response->records = $count;
		if($start <0) $start = 0;
        for($i=0; $i<count($result); $i++){
            $response->rows[$i]['id']=$result[$i]['ID_PANGKAT'];
            // data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
            $response->rows[$i]['cell']=array($result[$i]['ID_PANGKAT'],
											$result[$i]['GOLONGAN'],
											$result[$i]['RUANG'],
											$result[$i]['KODE_PANGKAT'],
											$result[$i]['NAMA_PANGKAT']
                                        );
        }
        echo json_encode($response); 
    }

	function insert()
	{
		$error_msg = '';
		
		$this->data_model->fill_data();
		//Check for duplicate name
		if(!$this->data_model->check_name())
		{
			$error_msg = 'Nama Pangkat telah digunakan';
		}
		//Check for duplicate code
		elseif(!$this->data_model->check_code())
		{
			$error_msg = 'Kode Pangkat telah digunakan';
		}
		//Insert Data
		elseif($this->data_model->insert_data()) 
		{
			$error_msg = '';
		}			
		echo json_encode(array('errors'=> $error_msg));

	}
			
	function update()
	{
		$id = $this->input->post('id');
		$error_msg = '';
		{
			$this->data_model->fill_data();
			//Check for duplicate name
			if(!$this->data_model->check_name($id))
			{
				$error_msg = 'Nama Pangkat telah digunakan';
			}		
			//Check for duplicate code
			elseif(!$this->data_model->check_code($id))
			{
				$error_msg = 'Kode Pangkat telah digunakan';
			}
			//Update Data
			elseif($this->data_model->update_data($id))
			{
				$error_msg = '';
			}
			echo json_encode(array('errors'=> $error_msg, 'id'=> $id));
		}
	}

	function delete()
	{
		$id = $this->input->post('id');
		$id = explode(',', $id);
		
		$data = $this->data_model->get_data_by_id($id);
		{
			if($this->data_model->delete_data($id))
			{
				$error_msg = '';
			}
			else
			{
				$error_msg = 'Terjadi kesalahan dalam menghapus data Pangkat '.$data['NAMA_PANGKAT'].'. Harap coba lagi.';
			}
		}
		echo json_encode(array('errors'=> $error_msg));
	}

}
/* End of file pangkat.php */
/* Location: ./application/controllers/pangkat.php */
