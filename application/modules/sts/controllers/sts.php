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
    $data['title'] = 'form';
    $data['modul'] = 'sts';
    $data['main_content'] = 'sts_form';
    $this->load->view('layout/template',$data);
  }
  
}//end class

?>