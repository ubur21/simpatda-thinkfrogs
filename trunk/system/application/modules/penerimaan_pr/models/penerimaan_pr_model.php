<?php

class penerimaan_pr_model extends Model {

	var $data;
	var $data_rincian;
	var $select;

	function penerimaan_pr_model()
	{
		parent::Model();
		$this->_table = 'penerimaan_pr';
		$this->_pk    = 'penerimaan_pr_id';

		$this->_table_rincian = 'penerimaan_pr_content';
		$this->_pk_rincian    = 'penerimaan_pr_content_id';
		
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
		jqgrid_set_where($arr);
		$this->db->select('a.PENERIMAAN_PR_ID, a.jenis_pungutan, a.PENERIMAAN_PR_NO, a.TGL_PENERIMAAN, a.NOMINAL_PAJAK, sk.NAMA_SATKER, vd.NPWP, vd.PEMOHON ');
		$this->db->from($this->_table.' a');
		$this->db->join('penerimaan_pr_content aa','a.PENERIMAAN_PR_ID=aa.PENERIMAAN_PR_ID','left');
		$this->db->join('satker sk','sk.ID_SATKER=a.SKPD_ID','left');
		$this->db->join('v_pendaftaran vd','vd.PENDAFTARAN_ID=a.PENDAFTARAN_ID','left');
		$this->db->order_by('a.PENERIMAAN_PR_NO');
		$this->db->limit($arr['limit'], $arr['start']);
		return $this->db->get();
		$this->db->trans_complete();	
		
	}
	
	function pilih_penerimaan($arr)
	{		
		$this->db->trans_start();
		jqgrid_set_where($arr);
		$this->db->select('c.ID as id_rekening, c.KODE_REKENING, c.NAMA_REKENING, a.THN_PENERIMAAN, sum(b.NOMINAL) ');
		$this->db->from($this->_table.' a');
		$this->db->join($this->_table_rincian.' b','a.PENERIMAAN_PR_ID=b.PENERIMAAN_PR_ID','left');
		$this->db->join('rekening_kode c','b.ID_REKENING=c.ID','left');
		$this->db->join('sts_content d','d.ID_REKENING=b.ID_REKENING','left');
		if(isset($arr['group'])) $this->db->group_by($arr['group']);
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
	
	function insert_rincian_data()
	{
		$this->db->trans_start();
		$insert = $this->db->insert($this->_table_rincian, $this->data_rincian);
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
	
	function setID()
	{
		$result = $this->db->query('select gen_id(GEN_PENERIMAAN_PR,1) as maks from RDB$DATABASE');
		return $result->row()->MAKS;
	}

}

?>