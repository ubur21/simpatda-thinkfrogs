<?php

class Sts extends Base_Controller {

  public function __construct()
  {
    parent::__construct();
		$this->load->model('sts_model','data_model');
  }

  public function index()
  {
    $data['breadcrumbs'] = 'Daftar Surat Tanda Setoran';
    $data['title'] = PRODUCT.' - '.$data['breadcrumbs'];
    $data['modul'] = 'sts';
    $data['main_content'] = 'sts_index';
    $this->load->view('layout/template',$data);
  }
  
   public function daftar_sts()
  {
	$response = (object) NULL;
	  
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

    $row = $this->data_model->get_daftarsts();
	
    if(!$row) // tidak ada data
    {
	 
      $response->total = 0;
      $response->records = 0;
      echo json_encode($response);
      return '';
    }

    $count = count($row);
    $total_pages = ceil($count/$limit);

    if ($page > $total_pages)
      $page=$total_pages;
    $start = $limit*$page - $limit; // do not put $limit*($page - 1)
    if($start <0) $start = 0;
    $req_param['limit'] = array(
          'start' => $start,
          'end' => $limit
    );
	
    $result = $this->data_model->get_daftarsts();
    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;

	$i = 0;
    foreach($result as $row)
    {
      $response->rows[$i]['id'] = $row->ID;
      $response->rows[$i]['cell'] = array(
        $row->NOMOR_STS,
		$row->TGL_SETOR,
      );
	  $i++;
    }
    echo json_encode($response);
  }
  
	public function create()
  {
    $data['breadcrumbs'] = 'Tambah Surat Tanda Setoran';
    $data['title'] = $this->app['app_name'];
    $data['modul'] = 'sts';
    $data['main_content'] = 'sts_form';
    $this->load->view('layout/template',$data);
  }
  
  public function update($no_sts)
  {
	
	$data['data'] = $this->data_model->get_sts_one($no_sts);
    $data['breadcrumbs'] = 'Ubah Surat Tanda Setoran';
    $data['title'] = $this->app['app_name'];
    $data['modul'] = 'sts';
	$data['mode'] = 'update';
    $data['main_content'] = 'sts_form';
	$data['tbp'] = $this->data_model->get_sts($no_sts);
    $this->load->view('layout/template',$data);
  }
  
  function getlisttbp()
	{
		$result = $this->data_model->getlistTBP();
		$response = (object) NULL;
		//$response->sql = $this->db->queries;
		$response->len = count($result);
		if ($result){
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['idsts'] = $result[$i]['ID'];
				$response->rows[$i]['noakun'] = $result[$i]['IDAKUN'];
				$response->rows[$i]['nama'] = $result[$i]['NAMA'];
				$response->rows[$i]['nominal'] = $result[$i]['NOMINAL'];
				$response->rows[$i]['sisa'] = $result[$i]['SISA'];
			}
		}

		echo json_encode($response);    
	}
	
	function getlisttbp_uppdate()
	{
		$con = $this->data_model->get_sts($_POST['no_sts']);
		foreach ($con as $item){
			$a[] = $item['TBP_ID'];
		}
		$id = implode(",",$a);
		$result = $this->data_model->getlistTBP2($id);
		$response = (object) NULL;
		//$response->sql = $this->db->queries;
		$response->len = count($result);
		if ($result){
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['idsts'] = $result[$i]['ID'];
				$response->rows[$i]['noakun'] = $result[$i]['IDAKUN'];
				$response->rows[$i]['nama'] = $result[$i]['NAMA'];
				$response->rows[$i]['nominal'] = $result[$i]['NOMINAL'];
				$response->rows[$i]['sisa'] = $result[$i]['SISA'];
			}
		}

		echo json_encode($response);    
	}
	
	public function proses()
  {
    $response = (object) NULL;
      
      
        $success = $this->data_model->save_data();

        if (!$success)
        {
          $response->isSuccess = TRUE;
          $response->message = 'Data berhasil disimpan';
          $response->id = $this->data_model->id;
          $response->sql = $this->db->queries;
        }
        else
        {
          $response->isSuccess = FALSE;
          $response->message = 'Data gagal disimpan';
          $response->sql = $this->db->queries;
        }

    echo json_encode($response);
  }
  
  public function delete()
  {
    $response = (object) NULL;
      
      
        $success = $this->data_model->delete_data();

        if (!$success)
        {
          $response->isSuccess = TRUE;
          $response->message = 'Data berhasil dihapus';
          $response->id = $this->data_model->id;
          $response->sql = $this->db->queries;
        }
        else
        {
          $response->isSuccess = FALSE;
          $response->message = 'Data gagal dihapus';
          $response->sql = $this->db->queries;
        }

    echo json_encode($response);
  }
  
}//end class

?>