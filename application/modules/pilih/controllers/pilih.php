<?php

class Pilih extends CI_Controller{
  public function __construct()
  {
    parent:: __construct();
    $this->load->model('pilih_model');
  }

  function index()
  {
  }

  function getrekening()
  {
    $opt = array(
      'multi',
      'mode',
      'id_spt',
      'tree',
      'lvl',
    );

    $sidx = $_REQUEST['sidx'];
    $sord = $_REQUEST['sord'];

    $req_param = array(
        "sort_by" => $sidx,
        "sort_direction" => $sord,
        "search" => $_REQUEST['_search'],
        "search_field" => isset($_REQUEST['searchField']) ? $_REQUEST['searchField'] : null,
        "search_operator" => isset($_REQUEST['searchOper']) ? $_REQUEST['searchOper'] : null,
        "search_str" => isset($_REQUEST['searchString']) ? $_REQUEST['searchString'] : null,
    );

    foreach ($opt as $key)
    {
      $req_param[$key] = $this->input->post( $key ) ? $this->input->post( $key ) : '0';
    }

    $result = $this->pilih_model->getRekening($req_param);
    $response = (object) NULL;
    $response->sql = $this->db->queries;
    if ($result){
      if ($req_param['mode'] == 'penyetoran')
      {
        for($i=0; $i<count($result); $i++){
          $response->rows[$i]['id'] = $result[$i]['ID_REKENING'];
          $response->rows[$i]['cell'] = array(
            $result[$i]['ID_REKENING'],
            $result[$i]['KODE_REKENING'],
            $result[$i]['NAMA_REKENING'],
            $result[$i]['NOMINAL'],
          );
        }
      }
      else
      {
        for($i=0; $i<count($result); $i++){
          $response->rows[$i]['id'] = $result[$i]['ID_REKENING'];
          $response->rows[$i]['cell'] = array(
            $result[$i]['ID_REKENING'],
            $result[$i]['KODE_REKENING'],
            $result[$i]['NAMA_REKENING'],
            $result[$i]['TARIF_RP'],
            $result[$i]['TARIF_PERSEN'],
          );
        }
      }
    }
    echo json_encode($response);
  }

  function rekening()
  {
    $opt = array(
      'multi',
      'mode',
      'id_spt',
      'tree',
    );

    foreach ($opt as $key)
    {
      $data['param'][$key] = $this->input->post( $key ) ? $this->input->post( $key ) : 0;
    }

    $data['dialogname'] = 'rekening';
    $data['colsearch'] = array(
        'nmrek' => 'Nama Rekening',
        'kdrek' => 'Kode Rekening',
    );
    
    if ($data['param']['mode'] == 'penyetoran')
    {
      $data['colnames'] = array('', 'Kode Rekening', 'Nama Rekening', 'Jumlah');
      $data['colmodel'] = array(
        array('name' => 'idrek', 'hidden' => true),
        array('name' => 'kdrek', 'width' => '110', 'sortable' => $data['param']['tree'] ? false :true),
        array('name' => 'nmrek', 'width' => '550', 'sortable' => $data['param']['tree'] ? false :true),
        array('name' => 'nom', 'width' => '150', 'formatter' => 'currency', 'align' => 'right', 'sortable' => $data['param']['tree'] ? false :true),
      );
    }
    else
    {
      $data['colnames'] = array('', 'Kode Rekening', 'Nama Rekening', 'Tarif Rp', 'Tarif %');
      $data['colmodel'] = array(
        array('name' => 'idrek', 'hidden' => true),
        array('name' => 'kdrek', 'width' => '110', 'sortable' => $data['param']['tree'] ? false :true),
        array('name' => 'nmrek', 'width' => '550', 'sortable' => $data['param']['tree'] ? false :true),
        array('name' => 'tarif_rp', 'hidden' => true),
        array('name' => 'tarif_persen', 'hidden' => true),
      );
    }
	
    $data['orderby'] = 'kdrek';
    $response = (object) NULL;
    $response->html = $this->load->view('v_pilih', $data, true);
    $response->grid = array(
      'url' => base_url().'pilih/get'.$data['dialogname'],
      'pager' => '#pgrDialog'.$data['dialogname'],
      'sortname' => $data['orderby'],
      'multiselect' => $data['param']['multi'],
      'colNames' => $data['colnames'],
      'colModel' => $data['colmodel'],
      'postData' => $data['param'],
    );

    echo json_encode($response);
  }

  function getskpd()
  {
    $page = $_REQUEST['page'];
    $limit = $_REQUEST['rows'];
    $sidx = $_REQUEST['sidx'];
    $sord = $_REQUEST['sord'];

    $req_param = array(
      "sort_by" => $sidx,
      "sort_direction" => $sord,
      "limit" => null,
      "search" => $_REQUEST['_search'],
      "search_field" => isset($_REQUEST['searchField']) ? $_REQUEST['searchField'] : null,
      "search_operator" => isset($_REQUEST['searchOper']) ? $_REQUEST['searchOper'] : null,
      "search_str" => isset($_REQUEST['searchString']) ? $_REQUEST['searchString'] : null,
    );

    $result = $this->pilih_model->getSKPD($req_param);
    $response = (object) NULL;
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->rows[$i]['id'] = $result[$i]['ID_SKPD'];
        $response->rows[$i]['cell'] = array(
          $result[$i]['ID_SKPD'],
          $result[$i]['KODE_SKPD'],
          $result[$i]['NAMA_SKPD'],
        );
      }
    }
    echo json_encode($response);
  }

  function skpd()
  {
    $multi = $this->input->post('multi') ? $this->input->post('multi') : 0;

    $data['dialogname'] = 'skpd';
    $data['colsearch'] = array('kode' => 'Kode SKPD', 'nama' => 'Nama SKPD');
    $data['colnames'] = array('', 'Kode SKPD', 'Nama SKPD');
    $data['colmodel'] = array(
      array('name' => 'id', 'hidden' => true),
      array('name' => 'kode', 'width' => '100', 'sortable' => true),
      array('name' => 'nama', 'width' => '580', 'sortable' => true),
    );
    $data['orderby'] = 'kode';
    $data['param'] = array(
      'multi' => $multi,
    );
    $response = (object) NULL;
    $response->html = $this->load->view('v_pilih', $data, true);
    $response->grid = array(
      'url' => base_url().'pilih/get'.$data['dialogname'],
      'pager' => '#pgrDialog'.$data['dialogname'],
      'sortname' => $data['orderby'],
      'multiselect' => $multi,
      'colNames' => $data['colnames'],
      'colModel' => $data['colmodel'],
      'postData' => $data['param'],
    );

    echo json_encode($response);
  }
  
  function getnpwpd()
  {
    $opt = array(
      'multi',
      'mode',
      'id_wp',
      'tree',
      'lvl',
    );

    $sidx = $_REQUEST['sidx'];
    $sord = $_REQUEST['sord'];

    $req_param = array(
        "sort_by" => $sidx,
        "sort_direction" => $sord,
        "search" => $_REQUEST['_search'],
        "search_field" => isset($_REQUEST['searchField']) ? $_REQUEST['searchField'] : null,
        "search_operator" => isset($_REQUEST['searchOper']) ? $_REQUEST['searchOper'] : null,
        "search_str" => isset($_REQUEST['searchString']) ? $_REQUEST['searchString'] : null,
    );

    foreach ($opt as $key)
    {
      $req_param[$key] = $this->input->post( $key ) ? $this->input->post( $key ) : '0';
    }

    $result = $this->pilih_model->getNPWPD($req_param);
    $response = (object) NULL;
    $response->sql = $this->db->queries;
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->rows[$i]['id'] = $result[$i]['ID_WAJIB_PAJAK'];
        $response->rows[$i]['cell'] = array(
          $result[$i]['ID_WAJIB_PAJAK'],
          $result[$i]['NPWPD'],
          $result[$i]['NAMA_WP'],
          $result[$i]['ALAMAT_WP'],
          $result[$i]['ID_KECAMATAN'],
          $result[$i]['ID_KELURAHAN'],
          $result[$i]['NAMA_KECAMATAN'],
          $result[$i]['NAMA_KELURAHAN'],
        );
      }
    }
    echo json_encode($response);
  }

  function npwpd()
  {
    $opt = array(
      'multi',
      'mode',
      'id_wp',
      'tree',
    );

    foreach ($opt as $key)
    {
      $data['param'][$key] = $this->input->post( $key ) ? $this->input->post( $key ) : 0;
    }

    $data['dialogname'] = 'npwpd';
    $data['colsearch'] = array(
        'nama_wp' => 'Nama WP',
        'npwpd' => 'NPWPD'
    );
    $data['colnames'] = array('', 'NPWPD', 'Nama WP/WR', 'Alamat', 'ID Kecamatan', 'ID Kelurahan',
                    'Kecamatan', 'Kelurahan');
                    
    $mode = $data['param']['mode'];
    if ($mode === 'pendataan' || $mode === 'angsuran'|| $mode === 'pendataan_hotel_npwpd'|| $mode === 'pendataan_restoran_npwpd'|| $mode === 'pendataan_hiburan_npwpd'|| $mode === 'pendataan_reklame_npwpd') //nana add pendataan_hotel_npwpd
    {
      $data['colmodel'] = array(
        array('name' => 'id_wp', 'hidden' => true),
        array('name' => 'npwpd', 'width' => '110', 'sortable' => $data['param']['tree'] ? false :true),
        array('name' => 'nama_wp', 'width' => '550', 'sortable' => $data['param']['tree'] ? false :true),
        array('name' => 'alamat_wp', 'hidden' => true),
        array('name' => 'id_kecamatan', 'hidden' => true),
        array('name' => 'id_kelurahan', 'hidden' => true),
        array('name' => 'kecamatan', 'hidden' => true),
        array('name' => 'kelurahan', 'hidden' => true),
      );
    }
	
    $data['orderby'] = 'npwpd';
    $response = (object) NULL;
    $response->html = $this->load->view('v_pilih', $data, true);
    $response->grid = array(
      'url' => base_url().'pilih/get'.$data['dialogname'],
      'pager' => '#pgrDialog'.$data['dialogname'],
      'sortname' => $data['orderby'],
      'multiselect' => $data['param']['multi'],
      'colNames' => $data['colnames'],
      'colModel' => $data['colmodel'],
      'postData' => $data['param'],
    );

    echo json_encode($response);
  }
  
  function jurnal()
  {
    $opt = array(
      'multi',
      'mode',
      'tree',
    );

    foreach ($opt as $key)
    {
      $data['param'][$key] = $this->input->post( $key ) ? $this->input->post( $key ) : 0;
    }

    $data['dialogname'] = 'jurnal';
    $data['colsearch'] = array(
        'kode' => 'KODE'
    );
    $data['colnames'] = array('', 'KODE');
                    
    $mode = $data['param']['mode'];

      $data['colmodel'] = array(
        array('name' => 'id_jurnal', 'hidden' => true),
        array('name' => 'kode', 'width' => '110', 'sortable' => $data['param']['tree'] ? false :true),
      );
	
    $data['orderby'] = 'kode';
    $response = (object) NULL;
    $response->html = $this->load->view('v_pilih', $data, true);
    $response->grid = array(
      'url' => base_url().'pilih/get'.$data['dialogname'],
      'pager' => '#pgrDialog'.$data['dialogname'],
      'sortname' => $data['orderby'],
      'multiselect' => $data['param']['multi'],
      'colNames' => $data['colnames'],
      'colModel' => $data['colmodel'],
      'postData' => $data['param'],
    );

    echo json_encode($response);
  }
  
  function getjurnal()
  {
    $opt = array(
      'multi',
      'mode',
      'tree',
    );

    $sidx = $_REQUEST['sidx'];
    $sord = $_REQUEST['sord'];

    $req_param = array(
        "sort_by" => $sidx,
        "sort_direction" => $sord,
        "search" => $_REQUEST['_search'],
        "search_field" => isset($_REQUEST['searchField']) ? $_REQUEST['searchField'] : null,
        "search_operator" => isset($_REQUEST['searchOper']) ? $_REQUEST['searchOper'] : null,
        "search_str" => isset($_REQUEST['searchString']) ? $_REQUEST['searchString'] : null,
    );

    foreach ($opt as $key)
    {
      $req_param[$key] = $this->input->post( $key ) ? $this->input->post( $key ) : '0';
    }

    $result = $this->pilih_model->getJURNAL($req_param);
    $response = (object) NULL;
    $response->sql = $this->db->queries;
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->rows[$i]['id'] = $result[$i]['ID'];
        $response->rows[$i]['cell'] = array(
          $result[$i]['ID'],
          $result[$i]['KODE'],
        );
      }
    }
    echo json_encode($response);
  }
  
  function bendahara()
  {
    $opt = array(
      'multi',
      'mode',
      'tree',
    );

    foreach ($opt as $key)
    {
      $data['param'][$key] = $this->input->post( $key ) ? $this->input->post( $key ) : 0;
    }

    $data['dialogname'] = 'bendahara';
    $data['colsearch'] = array(
        'kode' => 'KODE'
    );
    $data['colnames'] = array('', 'NAMA' ,'KODE');
                    
    $mode = $data['param']['mode'];

      $data['colmodel'] = array(
        array('name' => 'id_bendahara', 'hidden' => true),
        array('name' => 'nama_bendahara', 'width' => '110', 'sortable' => $data['param']['tree'] ? false :true),
        array('name' => 'akun_bendahara', 'width' => '110', 'sortable' => $data['param']['tree'] ? false :true),
      );
	
    $data['orderby'] = 'id_bendahara';
    $response = (object) NULL;
    $response->html = $this->load->view('v_pilih', $data, true);
    $response->grid = array(
      'url' => base_url().'pilih/get'.$data['dialogname'],
      'pager' => '#pgrDialog'.$data['dialogname'],
      'sortname' => $data['orderby'],
      'multiselect' => $data['param']['multi'],
      'colNames' => $data['colnames'],
      'colModel' => $data['colmodel'],
      'postData' => $data['param'],
    );

    echo json_encode($response);
  }
  
  function getbendahara()
  {
    $opt = array(
      'multi',
      'mode',
      'tree',
    );

    $sidx = $_REQUEST['sidx'];
    $sord = $_REQUEST['sord'];

    $req_param = array(
        "sort_by" => $sidx,
        "sort_direction" => $sord,
        "search" => $_REQUEST['_search'],
        "search_field" => isset($_REQUEST['searchField']) ? $_REQUEST['searchField'] : null,
        "search_operator" => isset($_REQUEST['searchOper']) ? $_REQUEST['searchOper'] : null,
        "search_str" => isset($_REQUEST['searchString']) ? $_REQUEST['searchString'] : null,
    );

    foreach ($opt as $key)
    {
      $req_param[$key] = $this->input->post( $key ) ? $this->input->post( $key ) : '0';
    }

    $result = $this->pilih_model->getBENDAHARA($req_param);
    $response = (object) NULL;
    $response->sql = $this->db->queries;
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->rows[$i]['id'] = $result[$i]['ID'];
        $response->rows[$i]['cell'] = array(
          $result[$i]['ID'],
          $result[$i]['NAMA'],
          $result[$i]['KODE'],
        );
      }
    }
    echo json_encode($response);
  }
  
	function getspt()
	{
		$opt = array(
			'multi',
			'mode',
			'id_spt',
			'id_wp',
			'tree',
			'lvl',
		);

		$sidx = $_REQUEST['sidx'];
		$sord = $_REQUEST['sord'];

		$req_param = array(
			"sort_by" => $sidx,
			"sort_direction" => $sord,
			"search" => $_REQUEST['_search'],
			"search_field" => isset($_REQUEST['searchField']) ? $_REQUEST['searchField'] : null,
			"search_operator" => isset($_REQUEST['searchOper']) ? $_REQUEST['searchOper'] : null,
			"search_str" => isset($_REQUEST['searchString']) ? $_REQUEST['searchString'] : null,
		);

		foreach ($opt as $key)
		{
		  $req_param[$key] = $this->input->post( $key ) ? $this->input->post( $key ) : '0';
		}

		$result = $this->pilih_model->getSPT($req_param);
		$response = (object) NULL;
		$response->sql = $this->db->queries;
		if ($result){
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['id'] = $result[$i]['ID_WAJIB_PAJAK'];
				$response->rows[$i]['cell'] = array(
					$result[$i]['ID_WAJIB_PAJAK'],
					$result[$i]['NAMA_WP'],
				);
			}
		}
		echo json_encode($response);
	}

	function spt()
	{
		$opt = array(
			'multi',
			'mode',
			'id_spt',
			'id_wp',
			'tree',
		);

		foreach ($opt as $key)
		{
			$data['param'][$key] = $this->input->post( $key ) ? $this->input->post( $key ) : 0;
		}

		$data['dialogname'] = 'spt';
		$data['colsearch'] = array(
			//'nospt' => 'Nomor SPT',
			'nama_wp' => 'Nama WP',
		);
		$data['colnames'] = array('', /*'',  'Nomor SPT', */ 'Nama WP/WR');
						
		$mode = $data['param']['mode'];
		if ($mode === 'bayar_sa')
		{
			$data['colmodel'] = array(
				array('name' => 'id', 'hidden' => true),
				//array('name' => 'id_wp', 'hidden' => true),
				//array('name' => 'no_spt', 'width' => '110', 'sortable' => $data['param']['tree'] ? false :true),
				array('name' => 'nama_wp', 'width' => '550', 'sortable' => $data['param']['tree'] ? false :true),
			);
		}
		
		if ($mode === 'bayar_oa')
		{
			$data['colmodel'] = array(
				array('name' => 'id', 'hidden' => true),
				//array('name' => 'id_wp', 'hidden' => true),
				//array('name' => 'no_spt', 'width' => '110', 'sortable' => $data['param']['tree'] ? false :true),
				array('name' => 'nama_wp', 'width' => '550', 'sortable' => $data['param']['tree'] ? false :true),
			);
		}
		
		$data['orderby'] = 'nospt';
		$response = (object) NULL;
		$response->html = $this->load->view('v_pilih', $data, true);
		$response->grid = array(
			'url' => base_url().'pilih/get'.$data['dialogname'],
			'pager' => '#pgrDialog'.$data['dialogname'],
			'sortname' => $data['orderby'],
			'multiselect' => $data['param']['multi'],
			'colNames' => $data['colnames'],
			'colModel' => $data['colmodel'],
			'postData' => $data['param'],
		);
		echo json_encode($response);
	}
	
	function getjenispajak()
	{
		$opt = array(
			'multi',
			'mode',
			'id_spt',
			'id_wp',
			'tree',
			'lvl',
		);

		$sidx = $_REQUEST['sidx'];
		$sord = $_REQUEST['sord'];

		$req_param = array(
			"sort_by" => $sidx,
			"sort_direction" => $sord,
			"search" => $_REQUEST['_search'],
			"search_field" => isset($_REQUEST['searchField']) ? $_REQUEST['searchField'] : null,
			"search_operator" => isset($_REQUEST['searchOper']) ? $_REQUEST['searchOper'] : null,
			"search_str" => isset($_REQUEST['searchString']) ? $_REQUEST['searchString'] : null,
		);

		foreach ($opt as $key)
		{
		  $req_param[$key] = $this->input->post( $key ) ? $this->input->post( $key ) : '0';
		}

		$result = $this->pilih_model->getjenispajak($req_param);
		$response = (object) NULL;
		$response->sql = $this->db->queries;
		if ($result){
			for($i=0; $i<count($result); $i++){
				$response->rows[$i]['id'] = $result[$i]['KODE_PR'];
				$response->rows[$i]['cell'] = array(
					$result[$i]['KODE_PR'],
					$result[$i]['NAMA_PR'],
				);
			}
		}
		echo json_encode($response);
	}

	function jenispajak()
	{
		$opt = array(
			'multi',
			'mode',
			'id_spt',
			'id_wp',
			'tree',
		);

		foreach ($opt as $key)
		{
			$data['param'][$key] = $this->input->post( $key ) ? $this->input->post( $key ) : 0;
		}

		$data['dialogname'] = 'jenispajak';
		$data['colsearch'] = array(
			'kode_pr' => 'Kode Pajak',
			'nama_pr' => 'Nama Pajak',
		);
		$data['colnames'] = array('Kode Pajak','Nama Pajak');
						
		$mode = $data['param']['mode'];
		if ($mode === 'bayar_pajak_sa')
		{
			$data['colmodel'] = array(
				array('name' => 'kode_pr', 'hidden' => false),
				array('name' => 'nama_pr', 'width' => '550', 'sortable' => $data['param']['tree'] ? false :true),
			);
		}
		
		if ($mode === 'bayar_pajak_oa')
		{
			$data['colmodel'] = array(
				array('name' => 'kode_pr', 'hidden' => false),
				array('name' => 'nama_pr', 'width' => '550', 'sortable' => $data['param']['tree'] ? false :true),
			);
		}
		
		$data['orderby'] = 'nospt';
		$response = (object) NULL;
		$response->html = $this->load->view('v_pilih', $data, true);
		$response->grid = array(
			'url' => base_url().'pilih/get'.$data['dialogname'],
			'pager' => '#pgrDialog'.$data['dialogname'],
			'sortname' => $data['orderby'],
			'multiselect' => $data['param']['multi'],
			'colNames' => $data['colnames'],
			'colModel' => $data['colmodel'],
			'postData' => $data['param'],
		);
		echo json_encode($response);
	}

  function getkohir()
  {
    $opt = array(
      'multi',
      'mode',
      'id_spt',
      'id_wp',
      'kohir',
      'tree',
      'lvl',
    );

    $sidx = $_REQUEST['sidx'];
    $sord = $_REQUEST['sord'];

    $req_param = array(
        "sort_by" => $sidx,
        "sort_direction" => $sord,
        "search" => $_REQUEST['_search'],
        "search_field" => isset($_REQUEST['searchField']) ? $_REQUEST['searchField'] : null,
        "search_operator" => isset($_REQUEST['searchOper']) ? $_REQUEST['searchOper'] : null,
        "search_str" => isset($_REQUEST['searchString']) ? $_REQUEST['searchString'] : null,
    );

    foreach ($opt as $key)
    {
      $req_param[$key] = $this->input->post( $key ) ? $this->input->post( $key ) : '0';
    }

    $result = $this->pilih_model->getKohir($req_param);
    $response = (object) NULL;
    $response->sql = $this->db->queries;
    if ($result){
      for($i=0; $i<count($result); $i++){
        $response->rows[$i]['id'] = $result[$i]['ID_SPT'];
        $response->rows[$i]['cell'] = array(
          $result[$i]['ID_SPT'],
          $result[$i]['ID_WAJIB_PAJAK'],
          $result[$i]['NOMOR'],
          $result[$i]['NAMA_WP'],
        );
      }
    }
    echo json_encode($response);
  }

  function kohir()
  {
    $opt = array(
      'multi',
      'mode',
      'id_spt',
      'id_wp',
      'kohir',
      'tree',
    );

    foreach ($opt as $key)
    {
      $data['param'][$key] = $this->input->post( $key ) ? $this->input->post( $key ) : 0;
    }

    $data['dialogname'] = 'kohir';
    $data['colsearch'] = array(
        'kohir' => 'Nomor Kohir',
        'nama_wp' => 'Nama WP',
    );
    $data['colnames'] = array('', '', 'Nomor Kohir', 'Nama WP/WR');
                    
    $mode = $data['param']['mode'];
    if ($mode === 'bayar_oa')
    {
      $data['colmodel'] = array(
        array('name' => 'id', 'hidden' => true),
        array('name' => 'id_wp', 'hidden' => true),
        array('name' => 'kohir', 'width' => '110', 'sortable' => $data['param']['tree'] ? false :true),
        array('name' => 'nama_wp', 'width' => '550', 'sortable' => $data['param']['tree'] ? false :true),
      );
    }
	
    $data['orderby'] = 'kohir';
    $response = (object) NULL;
    $response->html = $this->load->view('v_pilih', $data, true);
    $response->grid = array(
      'url' => base_url().'pilih/get'.$data['dialogname'],
      'pager' => '#pgrDialog'.$data['dialogname'],
      'sortname' => $data['orderby'],
      'multiselect' => $data['param']['multi'],
      'colNames' => $data['colnames'],
      'colModel' => $data['colmodel'],
      'postData' => $data['param'],
    );

    echo json_encode($response);
  }
}