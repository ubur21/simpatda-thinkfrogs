<?php
class rekening_model extends Model {

	var $data;
	var $select;

	function rekening_model()
	{
		parent::Model();
		$this->_table = 'rekening_kode';
		$this->_pk = 'id';	
		
		$this->select = '*';
				
		$this->fill_data();
	}
	
	function fill_data()
	{
		$kode = explode('.',$this->input->post('kode'));
		
		switch(count($kode))
		{
			case 1: 
				$tipe=$kode[0];
				$kode_rekening = $tipe;
			break;
			case 2: 
				$tipe=$kode[0];
				$kelompok=$kode[1];
				$kode_rekening = $tipe.'.'.$kelompok;
			break;			
			case 3: 
				$tipe=$kode[0];
				$kelompok=$kode[1];
				$jenis=$kode[2];
				$kode_rekening = $tipe.'.'.$kelompok.'.'.$jenis;
			break;						
			case 4: 
				$tipe=$kode[0];
				$kelompok=$kode[1];
				$jenis=$kode[2];
				$objek=$kode[3];
				$kode_rekening = $tipe.'.'.$kelompok.'.'.$jenis.'.'.$objek;
			break;							
			case 5: 
				$tipe=$kode[0];
				$kelompok=$kode[1];
				$jenis=$kode[2];
				$objek=$kode[3];
				$rincian=$kode[4];
				$kode_rekening = $tipe.'.'.$kelompok.'.'.$jenis.'.'.$objek.'.'.$rincian;
			break;
			case 6: 
				$tipe=$kode[0];
				$kelompok=$kode[1];
				$jenis=$kode[2];
				$objek=$kode[3];
				$rincian=$kode[4];
				$sub1=$kode[5];
				$kode_rekening = $tipe.'.'.$kelompok.'.'.$jenis.'.'.$objek.'.'.$rincian.'.'.$sub1;
			break;													
			case 7: 
				$tipe=$kode[0];
				$kelompok=$kode[1];
				$jenis=$kode[2];
				$objek=$kode[3];
				$rincian=$kode[4];
				$sub1=$kode[5];
				$sub2=$kode[6];
				$kode_rekening = $tipe.'.'.$kelompok.'.'.$jenis.'.'.$objek.'.'.$rincian.'.'.$sub1.'.'.$sub2;
			break;																
			case 8: 
				$tipe=$kode[0];
				$kelompok=$kode[1];
				$jenis=$kode[2];
				$objek=$kode[3];
				$rincian=$kode[4];
				$sub1=$kode[5];
				$sub2=$kode[6];
				$sub3=$kode[7];
				$kode_rekening = $tipe.'.'.$kelompok.'.'.$jenis.'.'.$objek.'.'.$rincian.'.'.$sub1.'.'.$sub2.'.'.$sub3;
			break;							
		}
		$tarif = $this->input->post('tarif')!='' ? $this->input->post('tarif') : '0.00';
		$persen = $this->input->post('persen')!='' ? $this->input->post('persen') : '0.00';
		
		$this->data = array(
			'tipe'=>$tipe,
			'kelompok'=>$kelompok,
			'jenis'=>$jenis,
			'objek'=>$objek,
			'rincian'=>$rincian,
			'sub1'=>$sub1,
			'sub2'=>$sub2,
			'sub3'=>$sub3,
			'kode_rekening'=>$kode_rekening,
			'nama_rekening'=>$this->input->post('nama'),
			'persen_tarif'=>$tarif,
			'tarif_dasar'=>$persen
		);
	}
	
	function insert_data()
	{
		$this->db->trans_start();
		$insert = $this->db->insert($this->_table, $this->data);
		$this->db->trans_complete();
		return $insert;
	}	
	
	function update_data($id)
	{
		$this->db->trans_start();
		$this->db->where($this->_pk,$id);
		$update = $this->db->update($this->_table, $this->data);
		return $update;
		$this->db->trans_complete();
	}	

	function delete_data($id)
	{
		$this->db->trans_start();
		$id = explode(',',$id);
		$this->db->where_in($this->_pk,$id);
		$delete = $this->db->delete($this->_table);
		$this->db->trans_complete();
		return $delete;
	}
	
	function delete_all_data($id)
	{
		$this->db->where($this->_pk,$id);
		$delete = $this->db->delete($this->_table);
	}
	
	function get_droplist($arr='')
	{
		$this->db->trans_start();
		
		$this->db->select('a.id as id_rekening,a.nama_rekening,a.kode_rekening ');
		
		if(isset($arr['where'])) $this->db->where($arr['where']);
							
		$this->db->from($this->_table.' a');

		$query =  $this->db->get();
		
		if ($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
		$this->db->trans_complete();
	}
		
	function get_data($param)
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
			
		$this->db->trans_start();
		$this->db->select($this->select);
		$this->db->order_by('tipe,kelompok,jenis,objek,rincian');
		return $this->db->get($this->_table);
		$this->db->trans_complete();
	}

	function get_all_data()
	{
		$this->db->trans_start();
		$this->db->from($this->_table);
		$this->db->order_by('nama_rekening');
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
		$this->db->trans_complete();
	}

	function get_data_by_id($id)
	{
		$this->db->where_in('a.'.$this->_pk, $id);
		$this->db->select($this->select);
		$query = $this->db->get($this->_table.' a');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}
	}	
	
	function get_rekening($arr)
	{
		$this->db->trans_start();
		jqgrid_set_where($arr);
		$this->db->select('id,kode_rekening,nama_rekening,tarif_dasar,persen_tarif,tarif_tambahan');
		$this->db->from($this->_table);
		$this->db->limit($arr['limit'], $arr['start']);
		$this->db->order_by('tipe');
		$this->db->order_by('kelompok');
		$this->db->order_by('jenis');
		$this->db->order_by('objek');
		$this->db->order_by('rincian');	
		
		return $this->db->get();
		$this->db->trans_complete();	
	}
	
	function seek()
	{
	
	}
}

?>