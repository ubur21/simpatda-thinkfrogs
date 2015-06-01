<?php

class Feature extends Base_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('feature_model','data_model');
  }

   public function pemeriksaan()
  {
    $data['title'] = PRODUCT;
    $data['modul'] = 'feature';
    $data['akses'] = $this->access;

    $data['main_content']='pemeriksaan';
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
      $response->rows[$i]['id'] = $row->ID_WAJIB_PAJAK;
	  $response->rows[$i]['flag'] = $row->FLAG;
      $response->rows[$i]['cell']=array(
        $row->NPWPD,
		$row->NAMA_WP,
		$row->NAMA_REKENING,
		$row->JUMLAH_PAJAK,
		$row->FLAG,
		$row->ID_REKENING,
      );
	  $i++;
    }
    echo json_encode($response);
  }
  
    public function form_teguran($id=0)
  {
    $data['title'] = $this->app['app_name'];
    $data['modul'] = 'Surat Teguran';
    $data['tipe'] = 'SA';
    $data['link_proses'] = 'proses';
    $data['link_back'] = '/daftar_sa';
    $data['form'] = '/form_teguran';
    $data['header'] = 'Surat Teguran';
    $data['akses'] = $this->access;
   

    $data['main_content']='surat_teguran_form';
    $this->load->view('layout/template',$data);
  }
  
  public function reminder()
  {
    $data['breadcrumbs'] = 'Reminder Pajak';
    $data['title'] = PRODUCT.' - '.$data['breadcrumbs'];
    $data['modul'] = 'feature';
   // $data['link_daftar'] = '/get_daftar_sa';
   $data['link_form'] = '/form_teguran';
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

  /*  
  function getspt()
	{
		$id_spt = $this->input->post('id_spt') ? $this->input->post('id_spt') : 0;
		$idpjk = $this->input->post('idpjk') ? $this->input->post('idpjk') : 0;
		$result = $this->data_model->getSPT($id_spt);
		$response = (object) NULL;
		$response->sql = $this->db->queries;
		$response->len = count($result);
		if ($result){
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['id_spt'] = $result[$i]['ID_SPT'];
				$response->rows[$i]['nospt'] = $result[$i]['NOMOR_SPT'];
				$response->rows[$i]['tglspt'] = $result[$i]['TANGGAL_SPT'];
				$response->rows[$i]['tglbayar'] = $result[$i]['TANGGAL'];
				$response->rows[$i]['rek'] = $result[$i]['NAMA_REKENING'];
				$response->rows[$i]['awal'] = $result[$i]['PERIODE_AWAL'];
				$response->rows[$i]['akhir'] = $result[$i]['PERIODE_AKHIR'];
				$response->rows[$i]['jml'] = $result[$i]['JUMLAH_PAJAK'];
				$response->rows[$i]['sisa'] = $result[$i]['JUMLAH_PAJAK'];
			}
		}
		echo json_encode($response);    
	}
	
	
	function getsptlngkp()
	{
		$id_spt = $this->input->post('id_spt') ? $this->input->post('id_spt') : 0;
		//$idpjk = $this->input->post('idpjk') ? $this->input->post('idpjk') : 0;
		$result = $this->data_model->getsptlngkp($id_spt);
		$response = (object) NULL;
		$response->sql = $this->db->queries;
		$response->len = count($result);
		//print_r($result);
		if ($result){
			for($i=0; $i<count($result); $i++){
				$result_bayar = $this->data_model->getsptbayar($result[$i]['ID_SPT']);
				if($result_bayar){
					$telah_bayar 	= $result_bayar['TELAH_DIBAYAR'];
					$denda 			= $result_bayar['DENDA'];
				}
				else{
					$telah_bayar = 0;
					$denda = 0;
				}
				$total 	= $result[$i]['JUMLAH_PAJAK'] + $denda;
				$sisa 	= ($result[$i]['JUMLAH_PAJAK'] + $denda) - $telah_bayar;
				
				$response->rows[$i]['id_spt'] 	= $result[$i]['ID_SPT'];
				$response->rows[$i]['nospt'] 	= $result[$i]['NOMOR_SPT'];
				$response->rows[$i]['tglspt'] = $result[$i]['TANGGAL_SPT'];
				$response->rows[$i]['tglbayar'] = $result[$i]['TANGGAL'];
				$response->rows[$i]['rek'] 		= $result[$i]['NAMA_REKENING'];
				$response->rows[$i]['awal'] 	= $result[$i]['PERIODE_AWAL'];
				$response->rows[$i]['akhir'] 	= $result[$i]['PERIODE_AKHIR'];
				$response->rows[$i]['jml'] 		= $result[$i]['JUMLAH_PAJAK'];				
				$response->rows[$i]['setor'] 	= $telah_bayar;
				$response->rows[$i]['denda'] 	= $denda;
				$response->rows[$i]['total'] 	= $total;
				$response->rows[$i]['sisa'] 	= $sisa;
			}
		}
		echo json_encode($response);    
	}*/
	
	
  public function cetak_teguran()
  {
	
    $data['data'] = $this->data_model->get_reminder2();
	
    $this->load->view('surat_teguran',$data);
    
   
    
    $html = $this->output->get_output();
    
    
    $this->load->library('dompdf_gen');
    
    
    $this->dompdf->load_html($html);
    
    $this->dompdf->render();
    
    
    $this->dompdf->stream("surat_teguran_".date('m-Y').".pdf",array('Attachment'=>0));
	
  }
  
  public function teguran()
  {
    $data['breadcrumbs'] = 'Surat Teguran Pajak';
    $data['title'] = PRODUCT.' - '.$data['breadcrumbs'];
    $data['modul'] = 'feature';
   $data['link_form'] = '/form_teguran';
    $data['main_content'] = 'surat_teguran_form';
    $this->load->view('layout/template',$data);
  } 
  
   public function daftar_teguran()
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

    $row = $this->data_model->get_teguran();
	
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
	
    $result = $this->data_model->get_teguran();
    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;

	$i = 0;
    foreach($result as $row)
    {
      $response->rows[$i]['id'] = $row->ID_WAJIB_PAJAK;
      $response->rows[$i]['cell']=array(
        $row->NPWPD,
		$row->NAMA_WP,
		$row->NAMA_REKENING,
		$row->JUMLAH_PAJAK,
		$row->TANGGAL_SPT
      );
	  $i++;
    }
    echo json_encode($response);
  }
  
  public function proses_teguran($id_wajib_pajak,$id_rekening)
  {
	
	$row = $this->data_model->proses_teguran_db($id_wajib_pajak);
	$this->reminder();
  }
	
}
