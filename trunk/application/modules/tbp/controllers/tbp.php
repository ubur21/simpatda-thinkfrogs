<?php

class Tbp extends Base_Controller {

  public function __construct()
  {
    parent::__construct();
		$this->load->model('tbp_model','data_model');
  }

  public function index()
  {
    $data['breadcrumbs'] = 'Daftar Tanda Bukti Setoran';
    $data['title'] = PRODUCT.' - '.$data['breadcrumbs'];
    $data['modul'] = 'tbp';
    $data['main_content'] = 'tbp_index';
    $this->load->view('layout/template',$data);
  }
  
  public function daftar_tbp()
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

    $row = $this->data_model->get_daftartbp();
	
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
	
    $result = $this->data_model->get_daftartbp();
    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;

	$i = 0;
    foreach($result as $row)
    {
      $response->rows[$i]['id'] = $row->ID;
      $response->rows[$i]['cell'] = array(
        $row->NOMOR_TBP,
		$row->TGL_BAYAR,
		$row->TOTAL_BAYAR,
      );
	  $i++;
    }
    echo json_encode($response);
  }
  
}//end class

?>
  

