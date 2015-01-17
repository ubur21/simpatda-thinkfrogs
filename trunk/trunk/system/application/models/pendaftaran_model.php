<?php
class pendaftaran_model extends Model {

	var $data;
	var $select;

	function pendaftaran_model()
	{
		parent::Model();
		$this->_table = 'pendaftaran';
		$this->_pk = 'pendaftaran_id';	
		
		$this->select = '*';
				
		//$this->fill_data();
	}
	
	function fill_data($arr,$action='')
	{
		$tgl_kartu   = prepare_date($this->input->post('tgl_kartu'));
		
		$tgl_kirim   = $this->input->post('tgl_kirim')!='' ? prepare_date($this->input->post('tgl_kirim')) : $tgl_kartu;
		$tgl_terima  = $this->input->post('tgl_terima')!='' ? prepare_date($this->input->post('tgl_terima')) : $tgl_kartu;
		$tgl_kembali = $this->input->post('tgl_kembali')!='' ? prepare_date($this->input->post('tgl_kembali')) : $tgl_kartu;
		$tgl_tutup   = $this->input->post('tgl_tutup')!='' ? prepare_date($this->input->post('tgl_tutup')) : $tgl_kartu;
		
		if(isset($action) && $action=='edit')
		{
		
			$this->data = array(
				'id_pemohon'=>$arr['pemohon']
				,'jenis_pendaftaran'=>$this->input->post('jenis_daftar')
				,'memo'=>$this->input->post('memo')
				,'objek_pdrd'=>$this->input->post('objek_pdrd')
				,'kode_usaha'=>$this->input->post('kode_usaha')
				,'tanggal_kartu'=>$tgl_kartu
				,'tanggal_terima'=>$tgl_terima
				,'tanggal_kembali'=>$tgl_kembali
				,'tanggal_kirim'=>$tgl_kirim
				,'tanggal_tutup'=>$tgl_tutup
				,'logs'=>'NOW'
				,'user_id'=>$this->session->userdata('SESS_USER_ID')
			);
		
		}else{
		
			$this->data = array(
				'id_pemohon'=>$arr['pemohon']
				,'jenis_pendaftaran'=>$this->input->post('jenis_daftar')
				,'nurut'=>$arr['nurut']
				,'no_pendaftaran'=>$arr['no_daftar']
				,'no_kartu'=>$arr['no_kartu']
				,'npwp'=>$arr['npwp']
				,'memo'=>$this->input->post('memo')
				,'objek_pdrd'=>$this->input->post('objek_pdrd')
				,'kode_usaha'=>$this->input->post('kode_usaha')
				,'tanggal_kartu'=>$tgl_kartu
				,'tanggal_terima'=>$tgl_terima
				,'tanggal_kembali'=>$tgl_kembali
				,'tanggal_kirim'=>$tgl_kirim
				,'tanggal_tutup'=>$tgl_tutup
				,'logs'=>'NOW'
				,'user_id'=>$this->session->userdata('SESS_USER_ID')
			);
		}
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
		if(isset($param['objek_pdrd'])) $this->db->where('objek_pdrd',$param['objek_pdrd']);
			
		$this->db->trans_start();
		$this->db->select($this->select);
		$this->db->from('v_pendaftaran');
		$this->db->order_by('nurut');
		return $this->db->get();
		$this->db->trans_complete();
	}

	function get_all_data()
	{
		$this->db->trans_start();
		$this->db->from($this->_table);
		$this->db->order_by('nurut');
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
		$this->db->order_by('nurut');
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
	
	function get_kode_lokasi($id_pemohon,$tipe_pemohon)
	{
		$this->db->select('desa_kode,camat_kode');
		$this->db->from('V_KODE_LOKASI_PEMOHON');
		$this->db->where('id_pemohon',$id_pemohon);
		$this->db->where('tipe_pemohon',$tipe_pemohon);
		$result = $this->db->get();
		if($result->num_rows()>0){
			return $result->row();
		}else{ return FALSE; }
		
	}
	
	function get_data_pendaftaran_pribadi($id)
	{
		$this->db->select('a.jenis_pendaftaran,
			a.no_pendaftaran, 
			a.no_kartu, 
			a.npwp, 
			a.memo, 
			a.objek_pdrd, 
			a.tanggal_kartu, 
			a.tanggal_terima, 
			a.tanggal_kembali, 
			a.tanggal_kirim, 
			a.tanggal_tutup, 
			b.pribadi_id,
			b.nama,
			b.no_ktp,
			b.alamat,
			b.no_telp,
			b.TANGGAL_LAHIR,b.TEMPAT_LAHIR,b.PEKERJAAN,b.NO_HP,b.RT,b.RW,b.KODEPOS,
			c.desa_nama,
			c.id as ID_DESA,
			d.camat_nama,
			e.id as kode_usaha');
			
		$this->db->from($this->_table.' a');
		$this->db->join('pribadi b','a.id_pemohon=b.pribadi_id','left');
		$this->db->join('desa c','c.id=b.id_desa','left');
		$this->db->join('kecamatan d','d.camat_id=c.id','left');
		$this->db->join('kode_usaha e','e.id=a.kode_usaha','left');
		$this->db->where('a.pendaftaran_id',$id);
		$result = $this->db->get();
					
		if($result->num_rows()>0)
		{
			return $result->result();
		}else{
			return FALSE;
		}
		
	}
	
	function get_data_pendaftaran_bu($id)
	{
		$this->db->select('a.jenis_pendaftaran,
			a.no_pendaftaran, 
			a.no_kartu, 
			a.npwp, 
			a.memo, 
			a.objek_pdrd, 
			a.tanggal_kartu, 
			a.tanggal_terima, 
			a.tanggal_kembali, 
			a.tanggal_kirim, 
			a.tanggal_tutup,
			b.id as bu_id,
			b.NAMA as NAMA_BU,
			b.BADAN_TIPE as tipe_bu,
			b.BADAN_TELP,
			b.BADAN_FAX,
			b.ALAMAT,
			b.KODEPOS,
			b.PEMILIK_NAMA,
			b.PEMILIK_TMP_LAHIR,
			b.PEMILIK_TGL_LAHIR,
			b.PEMILIK_NO_KTP,
			b.pemilik_npwp,
			b.PEMILIK_TELP,
			b.PEMILIK_HP,
			b.PEMILIK_ALAMAT,
			b.PEMILIK_RT,
			b.PEMILIK_RW,
			b.PEMILIK_KODEPOS,
			b.PEMILIK_ID_DESA,
			c.desa_nama,
			c.id as ID_DESA,
			d.camat_nama,
			e.id as kode_usaha');
			
		$this->db->from($this->_table.' a');
		$this->db->join('badan_usaha b','a.id_pemohon=b.id','left');
		$this->db->join('desa c','c.id=b.PEMILIK_ID_DESA','left');
		$this->db->join('kecamatan d','d.camat_id=c.id','left');
		$this->db->join('kode_usaha e','e.id=a.kode_usaha','left');
		$this->db->where('a.pendaftaran_id',$id);
		$result = $this->db->get();		
					
		if($result->num_rows()>0)
		{
			return $result->result();
		}else{
			return FALSE;
		}
		
	}	

	function cek_no_pendaftaran($nomor)
	{
		$result = $this->db->query('select count(*) from pendaftaran where no_pendaftaran='.$this->db->escape($nomor));
		return $result->row()->COUNT;
	}
	
	function get_nurut()
	{
		$this->db->select('max(nurut)');
		$result = $this->db->get($this->_table);
		if($result->num_row()>0)
		{
			return $result->row();
		}else{
			return 1;
		}
	}
	
	function get_no(){
		$this->db->select_max('nurut');
		$this->db->from($this->_table);
		
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->row()->NURUT;
		}else{
			return 0;
		}
	}
		
	function get_npwprd($arr)
	{		
		$this->db->trans_start();
		jqgrid_set_where($arr);
		if(isset($arr['type'])) $this->db->where('jenis_pendaftaran',$arr['type']);
		else $this->db->where('jenis_pendaftaran','PAJAK');
		$this->db->select('pendaftaran_id,pemohon,npwp,jenis_usaha,alamat,nama_desa,nama_kecamatan');
		$this->db->from('v_pendaftaran');
		$this->db->limit($arr['limit'], $arr['start']);
		//$this->db->order_by('pemohon');
		return $this->db->get();
		$this->db->trans_complete();
	}
	
	function get_npwpd($arr)
	{		
		$this->db->trans_start();
		jqgrid_set_where();
		$this->db->from('v_npwpd');
		$this->db->limit($arr['limit'], $arr['start']);
		$this->db->order_by('nama');
		return $this->db->get();
		$this->db->trans_complete();
	}
	
	function get_npwrd($arr)
	{		
		$this->db->trans_start();
		jqgrid_set_where();
		$this->db->from('v_npwrd');
		$this->db->limit($arr['limit'], $arr['start']);
		$this->db->order_by('nama');
		return $this->db->get();
		$this->db->trans_complete();
	}
	
	function seeknpw($arr)
	{
		$this->db->trans_start();
		jqgrid_set_where($arr);
		$this->db->select('a.pendaftaran_id,a.pemohon,npwp,a.jenis_usaha,a.alamat,a.nama_desa,a.nama_kecamatan,b.tgl_kirim,b.spt_no,b.spt_id');
		$this->db->from('v_pendaftaran a');
		$this->db->join('spt b','b.pendaftaran_id=a.pendaftaran_id','left');
		$this->db->limit($arr['limit'], $arr['start']);
		//$this->db->order_by('pemohon');
		return $this->db->get();
		$this->db->trans_complete();	
	}
}

?>