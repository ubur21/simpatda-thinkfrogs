<?php

class reg_parkir_model extends Model {

	var $data;
	var $data_rincian;
	var $select;
	

	function reg_parkir_model()
	{
		parent::Model();
		$this->_table    = 'pendataan_spt';
		$this->_table_pk = 'pendataan_id';	
		
		$this->_table_rincian    = 'pendataan_pparkir';
		$this->_table_rincian_pk = 'pparkir_id';
			
	}
	
	function fill_data($arr=array())
	{
		if($arr['ID']=='')
		{
		
			$this->data = array(
				'pendaftaran_id'=>$this->input->post('pendaftaran_id')
				,'tgl_proses'=>prepare_date($this->input->post('tgl_proses'))
				,'tgl_entry'=>prepare_date($this->input->post('tgl_entry'))
				,'memo'=>$this->input->post('memo')
				,'jenis_pendataan'=>'PARKIR'
				,'spt_id'=>$this->input->post('spt_id')
				,'jenis_pungutan'=>$this->input->post('jenis_pungutan')
				,'periode_awal'=>prepare_date($this->input->post('periode_awal'))
				,'periode_akhir'=>prepare_date($this->input->post('periode_akhir'))						
				,'logs'=>'NOW'
				,'user_id'=>$this->session->userdata('SESS_USER_ID')
				,'nominal'=>$arr['total']
			);		
		
		}else{
		
			$this->data = array(
				'pendataan_id'=>$arr['ID']
				,'pendaftaran_id'=>$this->input->post('pendaftaran_id')
				,'pendataan_no'=>$arr['nomor']
				,'tgl_proses'=>prepare_date($this->input->post('tgl_proses'))
				,'tgl_entry'=>prepare_date($this->input->post('tgl_entry'))
				,'memo'=>$this->input->post('memo')
				,'jenis_pendataan'=>'PARKIR'
				,'spt_id'=>$this->input->post('spt_id')
				,'jenis_pungutan'=>$this->input->post('jenis_pungutan')
				,'periode_awal'=>prepare_date($this->input->post('periode_awal'))
				,'periode_akhir'=>prepare_date($this->input->post('periode_akhir'))						
				,'logs'=>'NOW'
				,'user_id'=>$this->session->userdata('SESS_USER_ID')
				,'nominal'=>$arr['total']
			);			
		}
	}
	
	function fill_rincian_data($arr=array())
	{
		if($arr['ID']=='')
		{
		
			$this->data_rincian = array(
				'id_rekening'=>$arr['id_rekening']
				,'persen_tarif'=>$arr['persen_tarif']
				,'dasar_pengenaan'=>$arr['dasar_pengenaan']
				,'nominal'=>$arr['nominal']
			);
			
		}else{
		
			$this->data_rincian = array(
				'pendataan_id'=>$arr['ID']
				,'id_rekening'=>$arr['id_rekening']
				,'persen_tarif'=>$arr['persen_tarif']
				,'dasar_pengenaan'=>$arr['dasar_pengenaan']
				,'nominal'=>$arr['nominal']
			);
		
		}
	}
	
	function get_next_no()
	{
		$result = $this->db->query('select max(pendataan_no) as maks from pendataan_spt');
		$no 	= $result->row()->MAKS+1;
		return $no;
	}
	
	function setID()
	{
		$result = $this->db->query('select gen_id(gen_pendataan_spt,1) as maks from RDB$DATABASE');
		return $result->row()->MAKS;
	}
	
	function get_data($arr)
	{
		$this->db->trans_start();
		jqgrid_set_where($arr);
		
		$this->db->select('a.PENDATAAN_ID,
							a.JENIS_PUNGUTAN,
							a.PENDATAAN_NO,
							a.TGL_PROSES,
							a.tgl_entry,
							a.memo,
							a.nominal,
							a.periode_awal,
							a.periode_akhir,
							b.TGL_KIRIM,
							b.spt_no,
							b.spt_id,
							vp.pendaftaran_id,
							vp.PEMOHON,
							vp.NPWP,
							vp.alamat,
							vp.nama_kecamatan,
							vp.nama_desa,							
							pp.NO_PENETAPAN,
							pp.TGL_PENETAPAN,
							pn.TGL_PENERIMAAN,							
						');
							
		$this->db->from($this->_table.' a');
		//$this->db->join($this->_table_rincian.' aa','aa.pendataan_id=a.pendataan_id','left');
		//$this->db->join('rekening_kode rk','rk.id=aa.id_rekening','left');
		$this->db->join('spt b','b.PENDAFTARAN_ID=a.PENDAFTARAN_ID','left');
		$this->db->join('v_pendaftaran vp','vp.PENDAFTARAN_ID=a.PENDAFTARAN_ID','left');
		$this->db->join('penetapan_pr_content ppc','ppc.pendataan_id=a.pendataan_id','left');
		$this->db->join('penetapan_pr pp','pp.penetapan_pr_id=ppc.penetapan_pr_id','left');
		$this->db->join('penerimaan_pr pn','pn.PENDATAAN_ID=ppc.PENDATAAN_ID','left');

		$this->db->limit($arr['limit'], $arr['start']);
		return $this->db->get();
		$this->db->trans_complete();
		
	}
	
	function insert_data()
	{
		//$this->db->trans_start();
		$insert = $this->db->insert($this->_table, $this->data);
		//$this->db->trans_complete();
		return $insert;	
	}
	
	function insert_rincian_data()
	{
		//$this->db->trans_start();
		$insert = $this->db->insert($this->_table_rincian, $this->data_rincian);
		//$this->db->trans_complete();
		return $insert;			
	}
	
	function update_data($id)
	{
		$this->db->trans_start();
		$this->db->where($this->_table_pk,$id);
		$update = $this->db->update($this->_table, $this->data);
		return $update;
		$this->db->trans_complete();
	}	
	
	function update_rincian_data($id)
	{
		$this->db->trans_start();
		//$this->db->where($this->_table_rincian_pk,$id);
		$this->db->where($this->_table_rincian_pk,$id);
		$update = $this->db->update($this->_table_rincian, $this->data_rincian);
		return $update;
		$this->db->trans_complete();
	}		

	function delete_data($id)
	{
		//$this->db->trans_start();
		$id = explode(',',$id);
		$this->db->where_in($this->_table_pk,$id);
		$delete = $this->db->delete($this->_table);
		//$this->db->trans_complete();
		return $delete;
	}	
	
	function delete_rincian_data($id)
	{
		//$this->db->trans_start();
		$id = explode(',',$id);
		//$this->db->where_in($this->_table_rincian_pk,$id);
		$this->db->where_in($this->_table_pk,$id);
		$delete = $this->db->delete($this->_table_rincian);
		//$this->db->trans_complete();
		return $delete;
	}	

	function isAbleDelete($id)
	{
		$result = $this->db->query('select count(*) from penetapan_pr_content where pendataan_id='.$this->db->escape($id));
		return $result->row()->COUNT;
	}

}

?>