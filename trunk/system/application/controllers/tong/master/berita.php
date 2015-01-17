<?php

class Berita extends Controller {
	function Berita()
	{
		parent::Controller();
		$this->load->model('berita_model', 'data_model');
		$this->load->library('jqgridcss','','css');
	}
	function index()
	{
		if(!isset($_POST['oper'])){
			$data['title']='SIPTU - BERITA';
			$data['main_content']='master/berita';
			$this->load->view('layout/template',$data);
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
	function form($id='')
	{	
		if($id){
			$data['id']=$id;
			$data['user_data']=$this->data_model->get_data_by_id($id);
			if(isset($data['user_data']['DESKRIPSI'])) {
				$blob_data = ibase_blob_info($data['user_data']['DESKRIPSI']);
				$blob_hndl = ibase_blob_open($data['user_data']['DESKRIPSI']);
				$deskripsi = ibase_blob_get($blob_hndl, $blob_data[0]);
				$data['user_data']['DESKRIPSI']=$deskripsi;
			}
		}
		
		$data['title']='SIPTU - BERITA';
		$data['main_content']='master/berita';
		$this->load->view('layout/template',$data);
	}
	function tab()
	{
		$tab = $this->uri->segment(4);
		$this->load->view('master/'.$tab);
	}
	function proses($id='')
	{
		$error_msg=array('message'=>'Data Gagal Disimpan!.');
		
		$this->data_model->fill_data();
		
		if($id) {
			if( $this->data_model->check_id($id) ) {
				if( $this->data_model->update_data($id) ) 
				{
					$error_msg=array('message'=>'Data Berhasil Diedit!');
				}
			}
		}
		elseif( $this->data_model->insert_data() ) {
			$error_msg=array('message'=>'Data Berhasil Disimpan!');
		}
		
		// //print_r($error_msg);
		//$data['main_content']='master/berita';
		//$this->load->view('layout/template',$data);
		echo json_encode($error_msg);
		// //echo json_encode($data);
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
            // $blob_data = ibase_blob_info($result[$i]['DESKRIPSI']);
			// $blob_hndl = ibase_blob_open($result[$i]['DESKRIPSI']);
			// $deskripsi = ibase_blob_get($blob_hndl, $blob_data[0]);
			
			$response->rows[$i]['id']=$result[$i]['BERITA_ID'];

            // data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
            $response->rows[$i]['cell']=array($result[$i]['BERITA_ID'],
											$result[$i]['JUDUL'],
											$result[$i]['LOG'],
											$result[$i]['SUMBER']
                                        );
        }
        echo json_encode($response); 
	}
	function insert() {
	}
	function update() {
	}
	function delete() {
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
				$error_msg = 'Terjadi kesalahan dalam menghapus data Berita '.$data['judul'].'. Harap coba lagi.';
			}
		}
		echo json_encode(array('errors'=> $error_msg));
	}
	function genrss() {
	}
}
?>