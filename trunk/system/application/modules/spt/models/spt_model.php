<?php

class spt_model extends Model {

	var $data;
	var $select;

	function spt_model()
	{
		parent::Model();
		$this->_table = 'spt';
		$this->_pk = 'spt_id';	
		
		$this->select = '*';
				
		$this->fill_data();
	}
	
	function fill_data()
	{
		$this->data = array(
			'pendaftaran_id'=>$this->input->post('pendaftaran_id')
			,'spt_no'=>(int)$this->input->post('nomor')
			,'jenis_pungutan'=>$this->input->post('jenis_pungutan')
			,'kode_rekening'=>$this->input->post('kode_rekening')
			,'tgl_kirim'=>prepare_date($this->input->post('tgl_spt'))
			,'tgl_kembali'=>prepare_date($this->input->post('tgl_kembali'))
			,'penerima_nama'=>$this->input->post('nama_penerima')
			,'penerima_alamat'=>$this->input->post('alamat_penerima')
			,'memo'=>$this->input->post('memo')			
		);
	}
	
	function get_next_no()
	{
		$result = $this->db->query('select max(spt_no) as maks from spt');
		$no 	= $result->row()->MAKS+1;
		return $no;
	}
	
	function get_data($arr)
	{
		$this->db->trans_start();
		//jqgrid_set_where();
		$this->db->select('a.*,b.npwp,b.pemohon,b.alamat,b.nama_desa,b.nama_kecamatan,c.nama_rekening');
		$this->db->from($this->_table.' a');
		$this->db->join('v_pendaftaran b','a.pendaftaran_id=b.pendaftaran_id','left');
		$this->db->join('rekening_kode c','c.kode_rekening=a.kode_rekening','left');
		$this->db->order_by('spt_no');
		$this->db->limit($arr['limit'], $arr['start']);
		return $this->db->get();
		$this->db->trans_complete();	
		
	}
	
	function pilih_spt($arr)
	{
		$this->db->trans_start();
		jqgrid_set_where($arr);
		$this->db->select('a.PENDATAAN_ID,
							a.PENDATAAN_NO,
							a.TGL_ENTRY,
							a.NOMINAL,
							aa.PEMOHON,
							aa.NPWP,
							aa.ALAMAT,
							aa.KELURAHAN,
							aa.KECAMATAN,
							aa.PENDATAAN_ID,
							aa.PENDAFTARAN_ID,
							aa.PEMOHON_ID
							');
							
		$this->db->from('pendataan_spt a ');
		$this->db->join('v_pendataan_spt aa','aa.PENDATAAN_ID=a.PENDATAAN_ID','left');
		$this->db->join('penerimaan_pr b','a.PENDATAAN_ID=b.PENDATAAN_ID','left');
	
		//$this->db->order_by('spt_no');
		$this->db->limit($arr['limit'], $arr['start']);
		return $this->db->get();
		$this->db->trans_complete();	
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

}

?>