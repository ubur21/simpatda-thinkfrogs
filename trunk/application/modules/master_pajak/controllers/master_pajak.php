<?php

class Master_pajak extends Base_Controller {
  public function __construct()
  {
    parent::__construct();
    $this->load->model('master_pajak_model','data_model');
  }

  public function index()
  {
    $data['title'] = $this->app['app_name'].' - '.' Konfigurasi Pajak';
    $data['daftar_url'] = base_url().'master_pajak/get_daftar';
    $data['ubah_url'] = base_url().'master_pajak/proses_form';
    $data['main_content'] = 'master_pajak_view';
    $data['akses'] = $this->access;
    $this->load->view('layout/template',$data);
  }

  public function proses_form()
  {
    $id = $this->input->post('id');
    $oper = $this->input->post('oper');

    // jika id atau oper tidak ada isinya, proses dihentikan
    if (!isset($id)) return;
    if (!isset($oper)) return;

    // proses update
	$this->data_model->update_data($id);
	$response->isSuccess = TRUE;
	$response->message = 'Konfigurasi Pajak telah diubah';
    echo json_encode($response);
  }
  
	
	

  public function get_daftar()
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

    $count = count($this->data_model->get_data($req_param, TRUE));
    $response = (object) NULL;
    if($count == 0) // tidak ada data
    {
      echo json_encode($response);
      return '';
    }

    if ($limit == -1)
    {
      $page = 1;
      $total_pages = 1;
      $req_param['limit'] = NULL;
    }
    else
    {
      $total_pages = ceil($count/$limit);

      if ($page > $total_pages)
      $page = $total_pages;
      $start = $limit * $page - $limit;
      if($start < 0) $start = 0;
      $req_param['limit'] = array(
          'start' => $start,
          'end' => $limit
      );
    }

    $result = $this->data_model->get_data($req_param);

    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;

    for($i=0; $i<count($result); $i++)
    {
		if($result[$i]['OA']==''){
			$result[$i]['OA'] = null;
		}
		else{
			$result[$i]['OA'] = $result[$i]['OA'];
		}
		
		if($result[$i]['SA']==''){
			$result[$i]['SA'] = null;
		}
		else{
			$result[$i]['SA'] = $result[$i]['SA'];
		}
		$response->rows[$i]['id'] 	= $result[$i]['KODE_PR'];
		$response->rows[$i]['cell'] = array(
			$result[$i]['KODE_PR'],
			$result[$i]['NAMA_PR'],
			$result[$i]['OA'],
			$result[$i]['SA'],
		);
    }
    $response->sql = $this->db->queries;
    echo json_encode($response);
  }


  function _duplikasi_kode($str)
  {
    $id = $this->input->post('id') ? $this->input->post('id') : 0;
    if ($id === 'new') $id = 0;

    return $this->data_model->check_duplication($str, $id);
  }
  
	public function simpan_rek() 
	{
		$result = $this->data_model->check_isi_rek();
		if ($result == TRUE) {
			$this->data_model->simpan_rek();
			$response->isSuccess = TRUE;
			$response->message = 'Rekening '.$this->input->post('kdrek').'('.$this->input->post('nmrek').') Telah Ditambahkan.';
		}
		else 
		{
			$response->isSuccess = FALSE;
			//$response->message = 'Rekening '.$this->input->post('nmrek').' Sudah Ada..';
			$response->message = 'Rekening '.$this->input->post('kdrek').'('.$this->input->post('nmrek').') Sudah Ada..';
		}
		
		echo json_encode($response);
	}
  
	public function get_daftar_rek($id)
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
		"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null,
		"id" => $id
		);
		
		$count = count($this->data_model->get_data_rek($req_param, TRUE));
		$response = (object) NULL;
		if($count == 0) // tidak ada data
		{
			echo json_encode($response);
			return '';
		}

		if ($limit == -1)
		{
			$page = 1;
			$total_pages = 1;
			$req_param['limit'] = NULL;
		}
		else
		{
			$total_pages = ceil($count/$limit);

			if ($page > $total_pages)
				$page = $total_pages;
				$start = $limit * $page - $limit;
				if($start < 0) $start = 0;
					$req_param['limit'] = array(
					'start' => $start,
					'end' => $limit
					);
		}

		$result = $this->data_model->get_data_rek($req_param);

		$response->page = $page;
		$response->total = $total_pages;
		$response->records = $count;

		for($i=0; $i<count($result); $i++)
		{
			if($result[$i]['KODE_PR'] != null){
				$response->rows[$i]['id'] 	= $i;
				$response->rows[$i]['cell'] = array(
					$result[$i]['KODE_PR'],
					$result[$i]['ID_REKENING'],
					$result[$i]['KODE_REKENING'],
					$result[$i]['NAMA_REKENING'],
				);
			}
		}
		echo json_encode($response);
	}
	
	public function hapus_rek() 
	{
		$id = $this->input->post('id');
		$idrek = $this->input->post('idrek');
		$result = $this->data_model->hapus_rek($id,$idrek);
		$response->isSuccess = TRUE;
		$response->message = 'Rekening Konfigurasi Pajak Telah Dihapus. ';
		echo json_encode($response);
	}
}