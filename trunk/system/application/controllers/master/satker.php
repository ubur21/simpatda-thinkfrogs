<?php

class Satker extends Controller {
	var $oresults = array();

	function Satker()
	{
		parent::Controller();
		$this->load->model('satker_model', 'data_model');
	}
	
	function index()
	{
		if(b_logged()){
			$row = $this->data_model->get_all_data();
			$satker = "'':'',";
			for($i=0; $i<count($row); $i++) $satker.="'".$row[$i]->ID_SATKER."':'".$row[$i]->KODE_SATKER." - ".$row[$i]->NAMA_SATKER."',";
			$data['list_satker'] = $satker;
			$data['main_content'] = $this->load->view('master/daftar_satker','',true);

		}else{
		
			$data['main_content'] = $this->load->view('login/login','',true);
			
		}
		$this->load->view($this->config->item('layout_dir').'/template', $data);
	}

	function getselect()
	{
		/*$row = $this->data_model->get_all_data();
		echo "<select>";
		echo "<option value=''> --- </option>";
		for($i=0; $i<count($row); $i++){
			echo "<option value='". $row[$i]->ID_SATKER ."'>". $row[$i]->KODE_SATKER ." - ". $row[$i]->NAMA_SATKER ."</option>";
		}
		echo "</select>";*/
	}

    function get_daftar()
    {	
		if(!isset($_POST['oper'])){
        
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
			$responce->page = $page; 
			$responce->total = $total_pages; 
			$responce->records = $count;
					
			for($i=0; $i<count($result); $i++){
				$responce->rows[$i]['id']=$result[$i]['ID_SATKER'];
				// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
				$responce->rows[$i]['cell']=array($result[$i]['ID_SATKER'],
												$result[$i]['ID_SATKER_PARENT'],
												$result[$i]['KODE_SATKER'],
												$result[$i]['NAMA_SATKER']
											);
			}
			echo json_encode($responce); 
			
		}else{
			$id = $this->input->post('id');
			if($_POST['oper'] === 'add'){
				$this->insert($_POST);
			} else if($_POST['oper'] === 'edit'){
				$this->update($id);
			} else if($_POST['oper'] === 'del'){
				$this->delete($id);
			}		
		}
    }

	function insert()
	{
		$error_msg = '';
		
		$this->data_model->fill_data();
		//Check for duplicate code
		if(!$this->data_model->check_code())
		{
			$error_msg = 'Kode satker telah digunakan';
		}
		//Insert Data
		elseif($this->data_model->insert_data()) 
		{
			$error_msg = '';
		}			
		echo json_encode(array('errors'=> $error_msg));

	}
			
	function update($id)
	{
		$error_msg = '';
		{
			$this->data_model->fill_data();
			if(!$this->data_model->check_code($id))
			{
				$error_msg = 'Kode satker telah digunakan';
			}
			//Update Data
			elseif($this->data_model->update_data($id))
			{
				$error_msg = '';
			}
			echo json_encode(array('errors'=> $error_msg, 'id'=> $id));
		}
	}

	function delete($id)
	{		
		$data = $this->data_model->get_data_by_id($id);
		{
			if($this->data_model->delete_data($id))
			{
				$error_msg = '';
			}
			else
			{
				$error_msg = 'Terjadi kesalahan dalam menghapus data satker '.$data['nama_satker'].'. Harap coba lagi.';
			}
		}
		echo json_encode(array('errors'=> $error_msg));
	}

	/*
		dialog pilih Satker
	*/
	function track_parent( $strqrykelompok ) {
		$oresultx = array();
		$this->db->trans_complete();
		if ( $nresult = $this->db->query( $strqrykelompok ) ) 
		{
			if ( $nresult->num_rows() > 0 ) {
				$oresult = $nresult->_fetch_object();
				while ( $oresult ) {
					$oresultx[] = $oresult;
					$strqrykelompok2 = 
						"SELECT   * ".
						"FROM     SATKER ".
						"WHERE    KODE_SATKER = '".trim($oresult->INDUK_SATKER)."' ".
						"ORDER BY KODE_SATKER DESC";
				if ( $nresult2 = $this->db->query( $strqrykelompok2 ) ) {
					if ( $nresult2->num_rows() > 0 ) {
						$oresult = $nresult2->_fetch_object();
						continue;
					}
				}
				$oresult = $nresult->_fetch_object();
			}
		}
		$this->db->trans_complete();
		$this->oresults = array_merge($this->oresults, array_reverse($oresultx));
		}
	}

	function get_child()
	{
		$search = $inputval = $idparent = '';
		$tag = $idgol = $idbid = $idkel = $idsub = '';
		$radio = isset($_REQUEST['radio']) ? $_REQUEST['radio'] : "0";;
		$extra = isset($_REQUEST['extra']) ? $_REQUEST['extra'] : "";
		$prefix = isset($_REQUEST['prefix']) ? $_REQUEST['prefix'] : "";
		if ($extra)
			parse_str($extra);

		$inputvals = array();

		if ($search) {
			$strqrykelompok = "SELECT   * ".
						  "FROM     SATKER ".
						  "WHERE    Lower( NAMA_SATKER ) LIKE '%" . strtolower( trim( $search ) ) . "%' ".
						  "ORDER BY KODE_SATKER DESC";
			$this->track_parent( $strqrykelompok );
		}
		else {
			$strqrykelompok = "SELECT   * ".
						  "FROM     SATKER ".
						  "WHERE    INDUK_SATKER ".($idparent ? " = '".$idparent."' " : " IS NULL ").
						  "ORDER BY KODE_SATKER";
			$this->db->trans_start();
			if ( $nresult = $this->db->query( $strqrykelompok ) ) {
				if ( $nresult->num_rows() > 0 ) {
					while ($oresult = $nresult->_fetch_object()) $this->oresults[] = $oresult;
				}
			}
			$this->db->trans_complete();
			if ($inputval) {
				$inputvals = explode(",", $inputval);
				foreach ($inputvals as $inp) {
					$kd = explode("|", $inp);
					$strqrykelompok = 
								  "SELECT   * ".
								  "FROM     SATKER ".
								  "WHERE    ID_SATKER IN (".$inputval.") ".
								  "ORDER BY KODE_SATKER";
					$this->track_parent( $strqrykelompok );
				}
			}
		}
		echo "<script>\n";
		foreach ( $this->oresults as $oresult ) {
			$forcechk = in_array(trim($oresult->ID_SATKER), $inputvals) ? "true" : "false";

			if (strlen(trim($oresult->KODE_SATKER)) == 2) {
				echo "x1 = '".addslashes(trim($oresult->NAMA_SATKER))."';";
				echo "x2 = '-';";
				echo "x3 = '-';";
				echo "x4 = '-';";
			}
			else if (strlen(trim($oresult->KODE_SATKER)) == 4) {
				echo "xx1 = document.getElementById( 'idname_". substr(trim($oresult->KODE_SATKER), 0, 2)."' );";
				echo "x1 = xx1 ? xx1.innerHTML : '';";
				echo "x2 = '". addslashes(trim($oresult->KODE_SATKER))."';";
				echo "x3 = '-';";
				echo "x4 = '-';";
			}
			else if (strlen(trim($oresult->KODE_SATKER)) == 7) {
				echo "xx1 = document.getElementById( 'idname_". substr(trim($oresult->KODE_SATKER), 0, 2)."' );";
				echo "x1 = xx1 ? xx1.innerHTML : '';";
				echo "xx2 = document.getElementById( 'idname_". substr(trim($oresult->KODE_SATKER), 0, 4)."' );";
				echo "x2 = xx2 ? xx2.innerHTML : '';";
				echo "x3 = '". addslashes(trim($oresult->KODE_SATKER))."';";
				echo "x4 = '-';";
			}
			else if (strlen(trim($oresult->KODE_SATKER)) == 10) {
				echo "xx1 = document.getElementById( 'idname_". substr(trim($oresult->KODE_SATKER), 0, 2)."' );";
				echo "x1 = xx1 ? xx1.innerHTML : '';";
				echo "xx2 = document.getElementById( 'idname_". substr(trim($oresult->KODE_SATKER), 0, 4)."' );";
				echo "x2 = xx2 ? xx2.innerHTML : '';";
				echo "xx3 = document.getElementById( 'idname_". substr(trim($oresult->KODE_SATKER), 0, 7)."' );";
				echo "x3 = xx3 ? xx3.innerHTML : '';";
				echo "x4 = '". addslashes(trim($oresult->KODE_SATKER))."';";
			};
        echo "extra = '". ( $radio ? "&radio=1" : "" )."&prefix=".$prefix."&idparent=". urlencode(trim($oresult->KODE_SATKER))."';";
        echo "add_row3( 'satker', '". trim($oresult->INDUK_SATKER)."', '". trim($oresult->KODE_SATKER)."', '". trim($oresult->ID_SATKER)."', '". trim($oresult->KODE_SATKER)."', '". addslashes(trim($oresult->NAMA_SATKER))."', extra, ". $forcechk.", ". ( $radio ? "true" : "false" ).", false, '', new Array( new Array( 'idx1_', x1 ), new Array( 'idx2_', x2 ), new Array( 'idx3', x3 ), new Array( 'idx4', x4 ) ) );";
        
		}
		echo "finalize( 'satker' );\n";
		echo "</script>\n";
	}

	function pilih_satker() 
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
		$this->load->view('master/pilih_satker', $data);
	}
}
/* End of file satker.php */
/* Location: ./application/controllers/satker.php */
