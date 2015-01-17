<?php
class Dinas_model extends CI_Model {

	var $data;
	var $fieldmap = array();
	
	function __construct()
  {
    // Call the Model constructor
    parent::__construct();
		$this->_table= array('SKPD');
		$this->_pk='ID_SKPD';
		
		$this->fieldmap = array (
		'id' => 'ID_SKPD',
		'skpd' => 'KODE_SKPD',
		'namaskpd' => 'NAMA_SKPD'
		);
	}
	
	function fill_data()
	{
		foreach($this->fieldmap as $key => $value){
			switch ($key){
				case 'id' : $$key = $this->input->post($key) ? ($this->input->post($key)) : NULL; $$key == 'new' ? $$key = NULL : '';break;
				default : $$key = $this->input->post($key) ? $this->input->post($key) : NULL;
			}
			//if(isset($$key))
				$this->data[$value] = $$key;
		}
	}

	function get_data($param)
	{		
		if($param['search'] != null && $param['search'] === 'true'){
			// cek apakah search_field ada dalam fieldmap ?
			if (array_key_exists($param['search_field'], $this->fieldmap)) {
				$wh = "UPPER(".$this->fieldmap[$param['search_field']].")";
				$param['search_str'] = strtoupper($param['search_str']);
				switch ($param['search_operator']) {
					case "bw": // begin with
						$wh .= " LIKE '".$param['search_str']."%'";
						break;
					case "cn": // contain %param%
						$wh .= " LIKE '%".$param['search_str']."%'";
						break;
					default :
						$wh = "";
				}
				$this->db->where($wh);
			}
		}
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        ($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();
		
		$this->db->select('ID_SKPD, KODE_SKPD, NAMA_SKPD');
		$this->db->order_by ('KODE_SKPD');		
		$result = $this->db->get($this->_table)->result_array();
		$this->db->trans_complete();
		if(count($result)>0) {
			return $result;
		} else {
			return FALSE;
		}
	}
  
	function check_data()
	{
		$skpd	= $this->input->post('skpd');	$skpd=($skpd)?$skpd:null;
		
		$this->db->trans_start();		
		$result = $this->db->query("SELECT * FROM SKPD WHERE  kode_skpd = '$skpd'")->result_array();		
		if(!empty($result)){
			return TRUE;
		}
		else{
			return FALSE;
		}		
		$this->db->trans_complete();	
	}
	
	function check_data2()
	{
		$skpd	= $this->input->post('skpd');	$skpd=($skpd)?$skpd:null;
		
		$id = $this->db->query("select KODE_SKPD as KODE from SKPD WHERE ID_SKPD='".$this->input->post('id')."'")->row_array();
		
		$this->db->select('KODE_SKPD');
		$this->db->from('SKPD');
		$this->db->where('KODE_SKPD <>', $id['KODE']);
		$ada = $this->db->get()->result_array();
		
		$hasil = null;
		if(count($ada) > 0)
		{
			foreach($ada as $row)
			{
				$hasil[] = $row['KODE_SKPD'];
			}
		}
		
		$query = "SELECT * FROM SKPD WHERE  kode_skpd = '$skpd'";
		$result = $this->db->query($query);
		
		
		if($skpd == $id['KODE']){
			return TRUE;
		}
		else if (in_array($skpd, $hasil)) {
			return FALSE;
		}	
		else if(count($result->result_array()) > 0){
			return FALSE;
		}else{
			return TRUE;
		}
	}
  
	function insert_data()
	{
		$this->fill_data();
		$this->db->trans_start();	
		$result = $this->db->insert('SKPD', $this->data);
		$newid = $this->db->insert_id();
		$this->db->trans_complete();
		$this->db->select_max('ID_SKPD', 'ID');
		$id = $this->db->get('SKPD')->row_array();
		return $id['ID'];		
	}
	
	function update_data($id)
	{
		$this->fill_data();
		$this->db->trans_start();
		$this->db->where('ID_SKPD', $id);
		$result = $this->db->update('SKPD', $this->data);
		$this->db->trans_complete();
		return $result;
	}

	function check_dependency($id)
	{
		$this->db->trans_start();
		$this->db->select('a.ID_SKPD');
		$this->db->select('(select count(b.ID_SKPD) from ANGGARAN b where b.ID_SKPD = a.ID_SKPD) ANGGARAN_PAKAI');
		$this->db->select('(select count(b.ID_SKPD) from PEJABAT_SKPD b where b.ID_SKPD = a.ID_SKPD) PEJABAT_SKPD_PAKAI');
		$this->db->select('(select count(b.ID_SKPD) from PENYETORAN b where b.ID_SKPD = a.ID_SKPD) PENYETORAN_PAKAI');
		$this->db->where('a.ID_SKPD', $id);         
		$result = $this->db->get('SKPD a')->row_array();		
		$this->db->trans_complete();
		
		if ( $result['ANGGARAN_PAKAI'] > 0 || $result['PEJABAT_SKPD_PAKAI'] > 0 || $result['PENYETORAN_PAKAI'] > 0) 
    {
			return FALSE;
		} 
		else
		{
			return TRUE;
		}
	}
  
	function delete_data($id){
		$this->db->trans_start();
		$this->db->where_in('ID_SKPD', $id);
		$delete = $this->db->delete('SKPD');
		//return $delete;
		$this->db->trans_complete();
	}
  
	function fill_data_pejabat()
	{
		$JABATAN= $this->input->post('JABATAN'); $JABATAN=($JABATAN)?$JABATAN:null;
		$NAMA_PEJABAT= $this->input->post('NAMA_PEJABAT');$NAMA_PEJABAT=($NAMA_PEJABAT)?$NAMA_PEJABAT:null;
		$NIP = $this->input->post('NIP');$NIP=($NIP)?$NIP:null;
		$GOLONGAN = $this->input->post('GOLONGAN');$GOLONGAN=($GOLONGAN)?$GOLONGAN:null;
		$PANGKAT = $this->input->post('PANGKAT');$PANGKAT=($PANGKAT)?$PANGKAT:null;
		$AKTIF=$this->input->post('AKTIF');$AKTIF=($AKTIF)?$AKTIF:null;
		$ID_SKPD=$this->input->post('id');$ID_SKPD= $ID_SKPD ? $ID_SKPD:null;
		
		//print_r($AKTIF);die();
		if($_POST['AKTIF'] == '')
		{
			$AKTIF=1;
		}else{
			$AKTIF=$this->input->post('AKTIF');
		}
				
		$this->data_pejabat = array(
			'JABATAN' => $JABATAN,
			'NAMA_PEJABAT' => $NAMA_PEJABAT,
			'NIP' => $NIP,
			'GOLONGAN' => $GOLONGAN,
			'PANGKAT' => $PANGKAT,
			'AKTIF' => $AKTIF,
			'ID_SKPD' => $ID_SKPD
		);
		
		$this->data_pejabat_update = array(
			'JABATAN' => $JABATAN,
			'NAMA_PEJABAT' => $NAMA_PEJABAT,
			'NIP' => $NIP,
			'GOLONGAN' => $GOLONGAN,
			'PANGKAT' => $PANGKAT,
			'AKTIF' => $AKTIF
		);
	}
	
	function check_data_pejabat()
	{
		$nip	= $this->input->post('NIP');	$nip=($nip)?$nip:null;

		$this->db->trans_start();		
		$result = $this->db->query("SELECT * FROM PEJABAT_SKPD WHERE NIP = '$nip'")->result_array();		
		if(!empty($result)){
			return TRUE;
		}
		else{
			return FALSE;
		}		
		$this->db->trans_complete();	
	}
	
	function insert_data_pejabat()
	{
		$this->fill_data_pejabat();
		$this->db->trans_start();
		$insert = $this->db->insert('PEJABAT_SKPD', $this->data_pejabat);
		$newid = $this->db->insert_id();
		$this->db->trans_complete();
		return $newid;
	}

	function update_data_pejabat($ID_PEJABAT_SKPD)
	{
		$data = $this->fill_data_pejabat();
		$this->db->trans_start();
		$this->db->where('ID_PEJABAT_SKPD', $ID_PEJABAT_SKPD);
		$update = $this->db->update('PEJABAT_SKPD', $this->data_pejabat_update);
		return $update;
		$this->db->trans_complete();
	}

	function delete_data_pejabat($id){
		$this->db->trans_start();
		$this->db->where('ID_PEJABAT_SKPD', $id);
		$delete = $this->db->delete('PEJABAT_SKPD');
		$this->db->trans_complete();
		return $delete;
	}

	function get_data_pejabat($param,$ID_SKPD)
	{		
		if($param['search'] != null && $param['search'] === 'true'){
            $wh = "UPPER(".$param['search_field'].")";
			$param['search_str'] = strtoupper($param['search_str']);
            switch ($param['search_operator']) {
    			case "bw": // begin with
    				$wh .= " LIKE '".$param['search_str']."%'";
    				break;
    			case "ew": // end with
    				$wh .= " LIKE '%".$param['search_str']."'";
    				break;
    			case "cn": // contain %param%
    				$wh .= " LIKE '%".$param['search_str']."%'";
    				break;
    			case "eq": // equal =
   					$wh .= " = '".$param['search_str']."'";
    				break;
    			case "ne": // not equal
   					$wh .= " <> '".$param['search_str']."'";
    				break;
    			case "lt":
   					$wh .= " < '".$param['search_str']."'";
    				break;
    			case "le":
   					$wh .= " <= '".$param['search_str']."'";
    				break;
    			case "gt":
   					$wh .= " > '".$param['search_str']."'";
    				break;
    			case "ge":
   					$wh .= " >= '".$param['search_str']."'";
    				break;
    			default :
    				$wh = "";
    		}
            $this->db->where($wh);
		}
		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
        
        ($param['sort_by'] != null) ? $this->db->order_by($param['sort_by'], $param['sort_direction']) :'';
        
		//returns the query string
		$this->db->trans_start();
		
		$this->db->select(
			'*'		
			);
		$this->db->from('PEJABAT_SKPD as ps');
		$this->db->where('ps.ID_SKPD',$ID_SKPD);
		$result = $this->db->get();
		//die($this->db->last_query());
		
		return $result;
		$this->db->trans_complete();
		
	}

}
?>