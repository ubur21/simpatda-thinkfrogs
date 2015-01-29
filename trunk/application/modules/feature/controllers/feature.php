<?php

class Feature extends Base_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('feature_model','data_model');
  }

   public function pemeriksaan()
  {
    $data['breadcrumbs'] = 'Pemeriksaan Pajak';
    $data['title'] = PRODUCT.' - '.$data['breadcrumbs'];
    $data['modul'] = 'feature';
   // $data['link_daftar'] = '/get_daftar_sa';
   // $data['link_form'] = '/form_sa';
    $data['main_content'] = 'pemeriksaan';
    //$data['tipe'] = 'sa';
    $this->load->view('layout/template',$data);
  } 
  
  public function aging()
  {
    $data['breadcrumbs'] = 'Report Aging';
    $data['title'] = PRODUCT.' - '.$data['breadcrumbs'];
    $data['modul'] = 'feature';
   // $data['link_daftar'] = '/get_daftar_sa';
   // $data['link_form'] = '/form_sa';
    $data['main_content'] = 'aging';
    //$data['tipe'] = 'sa';
    $this->load->view('layout/template',$data);
  } 
  
  public function daftar_aging()
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

    $row = $this->data_model->get_aging();
	
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

    $result = $this->data_model->get_aging();
    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;

	$i = 0;
    foreach($result as $row)
    {
     
      $response->rows[$i]['cell']=array(
        $row->NPWPD,
		$row->NAMA_WP,
		$row->NAMA_REKENING,
		$row->TGL_TERBIT,
		$row->JUMLAH_PAJAK
      );
	  $i++;
    }
    echo json_encode($response);
  }
  
  public function daftar_reminder()
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

    $row = $this->data_model->get_reminder();
	
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

    $result = $this->data_model->get_reminder();
    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;

	$i = 0;
    foreach($result as $row)
    {
     
      $response->rows[$i]['cell']=array(
        $row->ID_BLN,
		$row->DESK_BLN,
		$row->NPWPD,
		$row->NAMA_WP,
		$row->JML_PAJAK
      );
	  $i++;
    }
    echo json_encode($response);
  }
  
  public function reminder()
  {
    $data['breadcrumbs'] = 'Reminder Pajak';
    $data['title'] = PRODUCT.' - '.$data['breadcrumbs'];
    $data['modul'] = 'feature';
   // $data['link_daftar'] = '/get_daftar_sa';
   // $data['link_form'] = '/form_sa';
    $data['main_content'] = 'reminder';
    //$data['tipe'] = 'sa';
    $this->load->view('layout/template',$data);
  } 
  
  public function cetak_aging()
  {
	//$data['main_content'] = 'aging_report';
	$aging = $this->data_model->get_aging();
	//var_dump($data);
	$data['judul'] = 'xxxxxx';
	$data['aging'] = $aging;
	$this->load->view('aging_report',$data);
  }

}
