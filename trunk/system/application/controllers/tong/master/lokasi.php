<?php

class Lokasi extends Controller
{
	var $oresults = array();

	function Lokasi()
	{
		parent::Controller();
		$this->load->model('lokasi_model', 'data_model');
	}
	
	function index()
	{
		if(!isset($_POST['oper'])){
			$data['title'] = "Siptu - Master Lokasi";
			$data['main_content'] = 'master/lokasi';
			$this->load->view('layout/template', $data);
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

	function getselect()
	{
		$row = $this->data_model->get_all_data();
		echo "<select>";
		echo "<option value=''> --- </option>";
		for($i=0; $i<count($row); $i++){
			echo "<option value='". $row[$i]->ID_LOKASI ."'>". $row[$i]->KODE_LOKASI ." - ". $row[$i]->NAMA_LOKASI ."</option>";
		}
		echo "</select>";
	}

	function getselectv()
	{
		$row = $this->data_model->get_all_data();
		echo "<select>";
		echo "<option value=''> --- </option>";
		for($i=0; $i<count($row); $i++){
			echo "<option value='". $row[$i]->KODE_LOKASI ."'>". $row[$i]->KODE_LOKASI ." - ". $row[$i]->NAMA_LOKASI ."</option>";
		}
		echo "</select>";
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
        $responce->page = $page; 
        $responce->total = $total_pages; 
        $responce->records = $count;
                
        for($i=0; $i<count($result); $i++){
            $responce->rows[$i]['id']=$result[$i]['ID_LOKASI'];
            // data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
            $responce->rows[$i]['cell']=array($result[$i]['ID_LOKASI'],
											$result[$i]['INDUK_LOKASI'],
											$result[$i]['KODE_LOKASI'],
                                            $result[$i]['NAMA_LOKASI']
                                        );
        }
        echo json_encode($responce); 
    }

	function insert()
	{
		$error_msg = '';
		
		$this->data_model->fill_data();
		//Check for duplicate code
		if(!$this->data_model->check_code())
		{
			$error_msg = 'Kode lokasi telah digunakan';
		}
		//Insert Data
		elseif($this->data_model->insert_data()) 
		{
			$error_msg = '';
		}			
		echo json_encode(array('errors'=> $error_msg));

	}
			
	function update()
	{
		$id = $this->input->post('id');
		$error_msg = '';
		{
			$this->data_model->fill_data();
			if(!$this->data_model->check_code($id))
			{
				$error_msg = 'Kode lokasi telah digunakan';
			}
			//Update Data
			elseif($this->data_model->update_data($id))
			{
				$error_msg = '';
			}
			echo json_encode(array('errors'=> $error_msg, 'id'=> $id));
		}
	}

	function delete()
	{
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
				$error_msg = 'Terjadi kesalahan dalam menghapus data lokasi '.$data['nama_lokasi'].'. Harap coba lagi.';
			}
		}
		echo json_encode(array('errors'=> $error_msg));
	}

	/*
		dialog pilih kelompok
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
						"FROM     Lokasi ".
						"WHERE    Kode_Lokasi = '". trim($oresult->INDUK_LOKASI) ."' ".
						"ORDER BY Kode_Lokasi DESC";
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
						  "FROM     Lokasi ".
						  "WHERE    Lower( Nama_Lokasi ) LIKE '%" . strtolower( trim( $search ) ) . "%' ".
						  "ORDER BY Kode_Lokasi DESC";
			$this->track_parent( $strqrykelompok );
		}
		else {
			$strqrykelompok = "SELECT   * ".
						  "FROM     Lokasi ".
						  "WHERE    Induk_Lokasi ".($idparent ? "= '".$idparent."' " : "IS NULL ").
						  "ORDER BY Kode_Lokasi";
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
								  "FROM     Lokasi ".
								  "WHERE    ID_Lokasi IN (".$inputval.") ".
								  "ORDER BY Kode_Lokasi";
					$this->track_parent( $strqrykelompok );
				}
			}
		}
		echo "<script>\n";
		foreach ( $this->oresults as $oresult ) {
			$forcechk = in_array(trim($oresult->ID_LOKASI), $inputvals) ? "true" : "false";

			if (strlen(trim($oresult->KODE_LOKASI)) == 2) {
				echo "prop = '".addslashes(trim($oresult->NAMA_LOKASI))."';";
				echo "kab = '-';";
				echo "kec = '-';";
				echo "desa = '-';";
			}
			else if (strlen(trim($oresult->KODE_LOKASI)) == 4) {
				echo "xprop = document.getElementById( 'idname_". substr(trim($oresult->KODE_LOKASI), 0, 2)."' );";
				echo "prop = xprop ? xprop.innerHTML : '';";
				echo "kab = '". addslashes(trim($oresult->NAMA_LOKASI))."';";
				echo "kec = '-';";
				echo "desa = '-';";
			}
			else if (strlen(trim($oresult->KODE_LOKASI)) == 7) {
				echo "xprop = document.getElementById( 'idname_". substr(trim($oresult->KODE_LOKASI), 0, 2)."' );";
				echo "prop = xprop ? xprop.innerHTML : '';";
				echo "xkab = document.getElementById( 'idname_". substr(trim($oresult->KODE_LOKASI), 0, 4)."' );";
				echo "kab = xkab ? xkab.innerHTML : '';";
				echo "kec = '". addslashes(trim($oresult->NAMA_LOKASI))."';";
				echo "desa = '-';";
			}
			else if (strlen(trim($oresult->KODE_LOKASI)) == 10) {
				echo "xprop = document.getElementById( 'idname_". substr(trim($oresult->KODE_LOKASI), 0, 2)."' );";
				echo "prop = xprop ? xprop.innerHTML : '';";
				echo "xkab = document.getElementById( 'idname_". substr(trim($oresult->KODE_LOKASI), 0, 4)."' );";
				echo "kab = xkab ? xkab.innerHTML : '';";
				echo "xkec = document.getElementById( 'idname_". substr(trim($oresult->KODE_LOKASI), 0, 7)."' );";
				echo "kec = xkec ? xkec.innerHTML : '';";
				echo "desa = '". addslashes(trim($oresult->NAMA_LOKASI))."';";
			};
        echo "extra = '". ( $radio ? "&radio=1" : "" )."&prefix=".$prefix."&idparent=". urlencode(trim($oresult->KODE_LOKASI))."';";
        echo "add_row3( 'lokasi', '". trim($oresult->INDUK_LOKASI)."', '". trim($oresult->KODE_LOKASI)."', '". trim($oresult->ID_LOKASI)."', '". trim($oresult->KODE_LOKASI)."', '". addslashes(trim($oresult->NAMA_LOKASI))."', extra, ". $forcechk.", ". ( $radio ? "true" : "false" ).", false, '', new Array( new Array( 'iddesa_', desa ), new Array( 'idkec_', kec ), new Array( 'idkab_', kab ), new Array( 'idprop_', prop ) ) );";
        
		}
		echo "finalize( 'lokasi' );\n";
		echo "</script>\n";
	}

	function pilih_lokasi() 
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
		$this->load->view('master/pilih_lokasi', $data);
	}
	
	function get_lokasi()
	{
		$nama = $_REQUEST['q'];	
		$query = $this->data_model->get_data_autocomplate($nama);
		if($query->num_rows() > 0)
		{
			$result = $query->result();
			foreach($result as $row)
			{
				echo $row->NAMA_LOKASI.'|'.$row->NAMA_LOKASI."\n";
			}
		}
	}
}