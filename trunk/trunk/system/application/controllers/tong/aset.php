<?php
class aset extends Controller {
	var $oresults = array();
	
	function aset()
	{
		parent::Controller();
		$this->load->model('aset_model', 'data_model');
		$this->load->library('jqgridcss','','css');
		$this->load->library('firephp');
		$this->load->helper('geofunc');
	}

	function index()
	{	
		$data['title'] = "SIP TU - Aset";
		$data['main_content'] = 'aset/daftar_aset';
		$this->load->view('layout/template', $data);
	}
	function daftar()
	{	
		$data['title'] = "SIP TU - Aset";
		$data['main_content'] = 'aset/daftar_aset';
		$this->load->view('layout/template', $data);
	}

	function tambah()
	{
		$data['title'] = "SIP TU - ASET - TAMBAH";
		$data['main_content'] = 'aset/kib';
		$data['act']='tambah';
		$data['form_act']='';
		$data['user_data']=$this->data_model->get_data_by_id('0','A');//array();
		$data['num'] =0;
		$data['num_foto'] =0;
		$data['bujur'] ='';
		$data['lintang'] ='';
		$data['id_koordinat'] ='';
		$data['current_link'] = 'tambah';
		$this->load->view('layout/template', $data);
	}
	// daftar untuk konsumsi jqgrid
	
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
           
        $row = $this->data_model->get_daftar($req_param)->result_array();
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
          
        
        $result = $this->data_model->get_daftar($req_param)->result_array();
        // sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
        $response->page = $page; 
        $response->total = $total_pages; 
        $response->records = $count;
                
        for($i=0; $i<count($result); $i++){
            $response->rows[$i]['id']=$result[$i]['ASET_ID'];
            // data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
            $response->rows[$i]['cell']=array($result[$i]['ASET_ID'],
											$result[$i]['KELOMPOK_ID'],
											$result[$i]['URAIAN'],
											$result[$i]['NAMAASET'],
											$result[$i]['TGLPEROLEHAN'],
											$result[$i]['NILAIPEROLEHAN']
                                        );
        }
        echo json_encode($response); 
	}
	
	function get_kab()
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
           
        $row = $this->data_model->get_kab($req_param);//->result_array();
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
          
        
        $result = $this->data_model->get_kab($req_param);//->result_array();
        // sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
        $response->page = $page; 
        $response->total = $total_pages; 
        $response->records = $count;
                
        for($i=0; $i<count($result); $i++){
            $response->rows[$i]['id']=$result[$i]['id'];
            // data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
            $response->rows[$i]['cell']=array($result[$i]['id'],
											$result[$i]['kode'],
											$result[$i]['nama']
                                        );
        }
        echo json_encode($response); 
	}
	
	function get_sat_ker()
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
           
        $row = $this->data_model->get_sat_ker($req_param)->result_array();
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
          
        
        $result = $this->data_model->get_sat_ker($req_param)->result_array();
        // sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
        $response->page = $page; 
        $response->total = $total_pages; 
        $response->records = $count;
                
        for($i=0; $i<count($result); $i++){
            $response->rows[$i]['id']=$result[$i]['ID_SATKER'];
            // data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
            $response->rows[$i]['cell']=array($result[$i]['ID_SATKER'],$result[$i]['KODE_SATKER'],
											$result[$i]['NAMA_SATKER']
                                        );
        }
        echo json_encode($response); 
	}
	
	function get_data_aset()
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
           
        $row = $this->data_model->get_data_aset($req_param)->result_array();
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
          
        
        $result = $this->data_model->get_data_aset($req_param)->result_array();
        // sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
        $response->page = $page; 
        $response->total = $total_pages; 
        $response->records = $count;
                
        for($i=0; $i<count($result); $i++){
            $response->rows[$i]['id']=$result[$i]['KELOMPOK_ID'];
            // data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
            $response->rows[$i]['cell']=array($result[$i]['KODE'],$result[$i]['KELOMPOK_ID'],
											$result[$i]['URAIAN']
                                        );
        }
        echo json_encode($response); 
	}
	
	function get_foto($id){
		/*$data = explode(" ",$this->data_model->get_foto($id));
		for($i=0;$i<count($data);$i++){
			$data_foto[$i] = ibase_blob_echo($data[$i]);
		}*/
		return $this->data_model->get_foto($id);
	}

	function edit()
	{
		$id = $this->uri->segment(3);
		if(empty($id)){
			$data['title'] = "Edit Aset";
			$data['main_content'] = 'aset/daftar_aset';
			$data['current_link'] = 'edit';
			$this->load->view('layout/template', $data);
		}else{
			$data['title'] = "Edit Aset";
			$data['main_content'] = 'aset/kib';
			$data['act'] = 'edit';
			$data['current_link'] = 'edit';
			$data['form_act'] = 'update/'.$id;
			$kode = $this->db->query('select KELOMPOK_ID,LOKASI_ID from aset where ASET_ID =\''.$id.'\' ');
			$result = $kode->_fetch_object();
			$tipe = $this->db->query('select TIPEASET from KELOMPOK where KELOMPOK_ID =\''.$result->KELOMPOK_ID.'\' ');
			$result_aset = $tipe->_fetch_object();
			$tipe = $result_aset->TIPEASET;
			$data['user_data'] = $this->data_model->get_data_by_id($id,$tipe);
			$data['addres'] = explode("|-|",$this->data_model->get_address($result->LOKASI_ID));
			$bujur = explode("-",$this->data_model->get_bujur($id));
			$garis_bujur = explode(" ",$bujur[1]);
			$id_koordinat = explode(" ",$bujur[2]);
			$lintang = explode(" ",$this->data_model->get_lintang($id));
			for($i=0;$i<count($garis_bujur);$i++){
				$data['bujur'][$i] = num_to_subordinate($garis_bujur[$i]);
				$data['id_koordinat'][$i] = $id_koordinat[$i];
				$data['lintang'][$i] = num_to_subordinate($lintang[$i]);
			}
			$data['num'] = $bujur[0];
			$num_foto = explode(" ",$this->data_model->get_foto($id));
			$data['id_foto'] = $this->data_model->get_foto_id($id);
			$data['num_foto'] = count($data['id_foto']);//count($num_foto);
			$this->firephp->log(count($data['id_foto']));
			$this->load->view('layout/template', $data);
			//$this->view_kelompok($id,$result->KELOMPOK_ID);	
			//GetForm();
		}
	}
	//tgl 19 juli 2010
	//ibu dona hrd tgl 16 makn siank...
	function alamat($lokasi_id)
	{
		$data['alamat_user'] = $this->data_model->get_address($lokasi_id);
	}
		
	function entri_aset()
	{	//echo 'key';
		
		//$this->firephp->log("def");
		$this->load->model('aset_model');
		$this->aset_model->fill_data();
		
		if($this->input->post('id_aset')!=''){
			$x = $this->input->post('id_aset');
			$id = $this->aset_model->update_data($x);
			if($id <= 0)
			{
				$this->firephp->log("abc");
			}else{
				$sq = $this->db->query("select kelompok_id from aset where aset_id = '".$x."'");
				$rslt = $sq->_fetch_object();
				$tipe = $this->db->query("select TIPEASET from KELOMPOK where KELOMPOK_ID ='".$rslt->KELOMPOK_ID."' ");
				$result_aset = $tipe->_fetch_object();
				if(!$this->aset_model->update_detail($x,$result_aset->TIPEASET)){
					$this->error_msg ='ADA MASALAH';
				}
				//$this->firephp->log($this->aset_model->update_detail($x,$result_aset->TIPEASET));
				if($this->input->post('cindex') > 0 ){
					$count = $this->input->post('cindex');
					$this->input->post('cindex');
					$this->save_image($x,$count);
				}
				if($this->input->post('index_koordinat') > 0 ) {
					$count = $this->input->post('index_koordinat');
					$sql = $this->aset_model->insert_koordinat($x,$count);
					//$this->firephp->log($sql, 'query');
					//$this->save_koordinat($x,$count);
				}
			}
		}else{
			//$this->firephp->log("TAMBAH");
			$this->tambah_aset();
		}
	}
	
	function tambah_aset()
	{ 
		$this->load->model('aset_model');
		if(!$this->aset_model->check_no_aset())
		{
			$this->error_msg = 'No. Aset sudah ada';
		}
		$id = $this->aset_model->insert_data();
		$this->firephp->log($id);
		if($id <= 0)
		{
			$this->error_msg ='have proble';
		}else{
			$cek = $this->db->query("select count(*) as row from aset where aset_id = '".$id."'");
			$aset = $cek->_fetch_object();
			if($aset->ROW > 0){
				$id_kelompok = explode("|",$this->input->post('idkelompok'));
				$tipe = $this->db->query('select TIPEASET from KELOMPOK where KELOMPOK_ID =\''.$id_kelompok[0].'\' ');
				$result_aset = $tipe->_fetch_object();
				if(!$this->aset_model->insert_detail($fk,$result_aset->TIPEASET)){
					$this->error_msg ='ADA MASALAH';
				}
			}
		}
		if($this->input->post('cindex') > 0 ){
			$count = $this->input->post('cindex');
			$this->save_image($id,$count);
		}
		if($this->input->post('index_koordinat') > 0 ) {
			$count = $this->input->post('index_koordinat');
			$sql = $this->aset_model->insert_koordinat($id,$count);
		}
	}

	function save_image($id,$count){
	    
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		//$config['max_size']	= '100';
		//$config['max_width']  = '1024';
		//$config['max_height']  = '768';
		
		$this->load->library('upload', $config);
		
		foreach ( $_FILES as $field => $value )
		{
			
			if ( !$this->upload->do_upload($field))
			{
				$error = array('error' => $this->upload->display_errors());
				
				$this->firephp->log( $error, 'upload gagal');
			
			}else{
				
				$data = array('upload_data' => $this->upload->data());					
				$path_content = $data['upload_data']['full_path'];
				$path_content = str_replace('/','\\',$path_content);
									
				$xx = $this->db->conn_id;
				
				if($path_content!=''){							
					$blh = ibase_blob_create($xx);
					ibase_blob_add($blh, file_get_contents($path_content));
					$query = 'insert into foto(ASET_ID,DATAFOTO,THUMBFOTO,MEDIUMFOTO) values('.$id.',?,?,?)';
				}
				
				$blobid = ibase_blob_close($blh);
				if(ibase_query($xx,$query, $blobid, $blobid, $blobid))
				{
					$this->firephp->log('tersimpan');
				}else{
					$this->firephp->log('tidak tersimpan');
				}		
			}
		}
	}
	
	/*
		dialog pilih kelompok
	*/
	function track_parent( $strquerykelompok )
	{
		$oresultx = array();
		$where = "";
		if( $strquerykelompok ) $where .= "WHERE ".$strquerykelompok;
		$strqrykelompok2 = "
			with recursive test(lvl, kelompok_id, golongan, bidang, kelompok, sub, subsub, kode, uraian, kode_induk) as (
			  select 1 lvl, kelompok_id, golongan, bidang, kelompok, sub, subsub, kode, uraian, kode_induk
			  from kelompok
			  $where
			  
			  union all
			  
			  select a.lvl + 1, b.kelompok_id, b.golongan, b.bidang, b.kelompok, b.sub, b.subsub, b.kode, b.uraian, b.kode_induk
			  from kelompok as b
			  join test a on a.kode_induk = b.kode
			)
			select distinct kelompok_id, golongan, bidang, kelompok, sub, subsub, kode, uraian
			from test
			order by kode
		";
		$this->db->trans_start();
		if ( $nresult2 = $this->db->query( $strqrykelompok2 ) )
		{
			$oresultx = $nresult2->result_object();

			$this->oresults = array_merge($this->oresults,  $oresultx );
		}
		$this->db->trans_complete();
	}

	function get_child()
	{
		$search = $inputval = $idparent = '';
		$tag = $idgol = $idbid = $idkel = $idsub = '';
		$radio = isset($_REQUEST['radio']) ? $_REQUEST['radio'] : "0";;
		$extra = isset($_REQUEST['extra']) ? $_REQUEST['extra'] : "";
		if ($extra)
			parse_str($extra);

		$inputvals = array();

		if ($search) {
			$strqrykelompok = "Lower( Uraian ) LIKE '%" . strtolower( trim( $search ) ) . "%' ";
			$this->track_parent( $strqrykelompok );
		}
		else {
			$strqrykelompok = "SELECT   * ".
						  "FROM     Kelompok ".
						  "WHERE    ".($idgol ? "Golongan = '".$idgol."' " : "Bidang   IS NULL ").
						  "AND      ".($idbid ? "Bidang   = '".$idbid."' " : "Kelompok IS NULL ").
						  "AND      ".($idkel ? "Kelompok = '".$idkel."' " : "Sub      IS NULL ").
						  "AND      ".($idsub ? "Sub      = '".$idsub."' " : "SubSub   IS NULL ").
						  "ORDER BY Kode";
			$this->db->trans_start();
			if ( $nresult = $this->db->query( $strqrykelompok ) ) {
				if ( $nresult->num_rows() > 0 ) {
					while ($oresult = $nresult->_fetch_object()) $this->oresults[] = $oresult;
				}
			}
			$this->db->trans_complete();
			if ($inputval) {
				$inputvals = explode(",", $inputval);
				$strqrykelompok = "kelompok_id in(0";
				foreach ($inputvals as $inp) {
					$kd = explode("|", $inp);
					$strqrykelompok .= ",".$kd[0];
				}
				$strqrykelompok .= ")";
				$this->track_parent( $strqrykelompok );
			}
		}
		echo "<script>\n";
		foreach ( $this->oresults as $oresult ) {
			$thisvalue = trim($oresult->KELOMPOK_ID)."|".trim($oresult->GOLONGAN)."|".trim($oresult->BIDANG)."|".trim($oresult->KELOMPOK)."|".trim($oresult->SUB)."|".trim($oresult->SUBSUB);
			$forcechk = in_array($thisvalue, $inputvals) ? "true" : "false";
			$idparent = ( trim($oresult->BIDANG) ? trim($oresult->GOLONGAN) : "" )."_".
					( trim($oresult->KELOMPOK) ? trim($oresult->BIDANG) : "" )."_".
					( trim($oresult->SUB) ? trim($oresult->KELOMPOK) : "" )."_".
					( trim($oresult->SUBSUB) ? trim($oresult->SUB) : "" )."_";
			if ( $idparent == "____" ) $idparent = "";
			$idthis = trim($oresult->GOLONGAN)."_".trim($oresult->BIDANG)."_".trim($oresult->KELOMPOK)."_".trim($oresult->SUB)."_".trim($oresult->SUBSUB);
			if (!trim($oresult->SUBSUB)) {
				echo "var extra = '&idgol=".urlencode(trim($oresult->GOLONGAN))."&idbid=".urlencode(trim($oresult->BIDANG))."&idkel=".urlencode(trim($oresult->KELOMPOK))."&idsub=".urlencode(trim($oresult->SUB))."';\n";
			}
			else {
				echo "var extra = '';\n"; 
			}
			echo "extra += '".( $radio ? '&radio=1' : '' )."';\n";
			echo "add_row4( 'kelompok', '$idparent', '$idthis', '$thisvalue', '".trim($oresult->KODE)."', '".addslashes(trim($oresult->URAIAN))."', extra, $forcechk, ".($radio ? 'true' : 'false').", ".($radio && !trim($oresult->SUBSUB) ? 'true' : 'false').", '', new Array(), '".$tag."' );\n";
		}
		echo "finalize( 'kelompok' );\n";
		echo "</script>\n";
	}

	function pilih_kelompok() 
	{
		$data['radio'] 		= $this->input->post('radio'); 	// pilihan berupa radio atau checkbox => radio = 1 ; checkbox = 0
        $data['tag'] 		= $this->input->post('tag'); 	// 
        $data['search'] 	= $this->input->post('search'); // 
        $data['prefix'] 	= $this->input->post('prefix'); // prefix 
		$data['idtrigger'] 	= $this->input->post('idtrigger'); // tombol
		$data['idinput'] 	= $this->input->post('idinput'); // input 
		$data['inputval'] 	= $this->input->post('inputval'); 
		$data['idhidden'] 	= $this->input->post('idhidden'); 
		$data['textall'] 	= $this->input->post('textall'); 
		$this->load->view('aset/pilih_kelompok', $data);
	}

	function view_kelompok($aset_id,$kel_id) 
	{
		/*if(!empty($kel_id)){
			$id = $aset_id;
		}else{
			$id = $this->uri->segment(3);
		}
		if(!empty($aset_id)){
			$id_aset = $aset_id;
		}else{
			$id_aset = $this->uri->segment(4);
		}*/
		$id = $this->uri->segment(3);
		$id_aset = $this->uri->segment(4);
		$query = $this->db->query('select Kode,TipeAset from Kelompok where Kelompok_ID =\''.$id.'\' ');
		$oresult = $query->_fetch_object();
		$fungsi =  $oresult->TIPEASET;
		if(!empty($id_aset)){
			$aset = $this->data_model->get_aset($id_aset,$fungsi);
			$qy = $this->db->query("select NOMORREG from ASET where ASET_ID ='".$id_aset."'");
			$row = $qy->_fetch_object();
			//$this->firephp->log( $this->db->queries, 'sql'); 
			$register = sprintf("%03d",$row->NOMORREG);
		}else{
			$aset = $this->db->query('select count(*) AS row from aset where KELOMPOK_ID =\''.$id.'\' ');
			$result_aset = $aset->_fetch_object();
			$register = sprintf("%03d",$result_aset->ROW+1);
		}
		
		$this->load->model('rincian_aset_model','data','tes');
		$data = $this->data->$fungsi($id_aset,$id)."|".$oresult->KODE."|".$register;
		//$this->firephp->log($data);
		echo $data;
	}

	function sub_kelompok($sub){
		$this->load->model('rincian_aset_model','data');
		$data = $this->data->$sub();
		echo $data;
	}
}
?>