<?php

class sts_model extends Model {

	var $data;
	var $data_rincian;

	var $select;
	
	function sts_model()
	{
		parent::Model();
		$this->_table    = 'sts';
		$this->_table_pk = 'sts';	
		
		$this->_table_rincian    = 'sts_content';
		$this->_table_rincian_pk = 'sts_content_id';
				
	}
	
	function fill_data($arr=array())
	{
		if($arr['ID']=='')
		{		
			$this->data = array(
				'tgl_penetapan'=>prepare_date($this->input->post('tgl_penetapan'))
				,'thn_penetapan'=>date('Y',strtotime($this->input->post('tgl_penetapan')))
				,'tgl_setor'=>prepare_date($this->input->post('tgl_setor'))
				,'memo'=>$this->input->post('memo')
				,'nominal_penetapan'=>0
				,'LOGS'=>'NOW'
				,'user_id'=>$this->session->userdata('SESS_USER_ID')
			);			
		
		}else{
		
			$this->data = array(
				'penetapan_pr_id'=>$arr['ID']
				,'no_penetapan'=>$arr['nomor']
				,'tgl_penetapan'=>prepare_date($this->input->post('tgl_penetapan'))
				,'thn_penetapan'=>date('Y',strtotime(prepare_date($this->input->post('tgl_penetapan'))))
				,'tgl_setor'=>prepare_date($this->input->post('tgl_setor'))
				,'memo'=>$this->input->post('memo')
				,'nominal_penetapan'=>0
				,'LOGS'=>'NOW'
				,'user_id'=>$this->session->userdata('SESS_USER_ID')
			);			
		}
	}
	
	function fill_rincian_data($arr=array())
	{
		if($arr['ID']=='')
		{
		
			$this->data_rincian = array(
				'pendataan_id'=>$arr['pendataan_id']
				,'nominal'=>$arr['nominal']
				,'jenis_pendataan'=>$arr['jenis_pendataan']
				,'jenis_pungutan'=>$arr['jenis_pungutan']
			);
			
		}else{
		
			$this->data_rincian = array(
				'penetapan_pr_id'=>$arr['ID']
				,'pendataan_id'=>$arr['pendataan_id']
				,'nominal'=>$arr['nominal']
				,'jenis_pendataan'=>$arr['jenis_pendataan']
				,'jenis_pungutan'=>$arr['jenis_pungutan']
			);
		
		}
	}
	
	function fill_detail_data($arr=array())
	{
		if($arr['ID']=='')
		{
			$this->data_detail = array(
				'pendataan_id'=>$arr['pendataan_id']
				,'pendataan_content_id'=>$arr['pendataan_content_id']
				,'id_rekening'=>$arr['id_rekening']
				,'persen_tarif'=>$arr['persen_tarif']
				,'dasar_tarif'=>$arr['dasar_tarif']
				,'dasar_pengenaan'=>$arr['dasar_pengenaan']
				,'kva'=>$arr['kva']
				,'diskon'=>$arr['diskon']
				,'jam'=>$arr['jam']
				,'nominal'=>$arr['nominal']
				,'tgl_jatuh_tempo'=>$arr['tgl_jatuh_tempo']
			);
			
		}else{
		
			$this->data_detail = array(
				'penetapan_pr_id'=>$arr['ID']
				,'pendataan_id'=>$arr['pendataan_id']
				,'pendataan_content_id'=>$arr['pendataan_content_id']
				,'id_rekening'=>$arr['id_rekening']
				,'persen_tarif'=>$arr['persen_tarif']
				,'dasar_tarif'=>$arr['dasar_tarif']
				,'dasar_pengenaan'=>$arr['dasar_pengenaan']
				,'kva'=>$arr['kva']
				,'diskon'=>$arr['diskon']
				,'jam'=>$arr['jam']
				,'nominal'=>$arr['nominal']
				,'tgl_jatuh_tempo'=>$arr['tgl_jatuh_tempo']
			);
		
		}
	}	
	
	function get_next_no()
	{
		$result = $this->db->query('select max(no_penetapan) as maks from '.$this->_table);
		$no 	= $result->row()->MAKS+1;
		return $no;
	}
	
	function setID()
	{
		$result = $this->db->query('select gen_id(GEN_STS,1) as maks from RDB$DATABASE');
		return $result->row()->MAKS;
	}
	
	function get_data($arr)
	{
		$this->db->trans_start();
		jqgrid_set_where($arr);
		
		$this->db->select('a.PENETAPAN_PR_ID, a.NO_PENETAPAN, a.TGL_PENETAPAN, a.TGL_SETOR, b.NOMINAL');
							
		$this->db->from($this->_table.' a');
		$this->db->join($this->_table_rincian.' b','a.PENETAPAN_PR_ID=b.PENETAPAN_PR_ID','left');

		$this->db->limit($arr['limit'], $arr['start']);
		return $this->db->get();
		$this->db->trans_complete();
		
	}
	
	function pilih_kohir($arr)
	{
		$this->db->trans_start();
		jqgrid_set_where($arr);
		
		$this->db->select('a.PENETAPAN_PR_ID, a.NO_PENETAPAN, a.TGL_PENETAPAN, a.TGL_SETOR, b.NOMINAL');
							
		$this->db->from('penetapan_pr a');
		$this->db->join('penetapan_pr_content b','a.PENETAPAN_PR_ID=b.PENETAPAN_PR_ID','left');
		$this->db->join('penerimaan_pr c','c.PENETAPAN_PR_ID=a.PENETAPAN_PR_ID','left');

		$this->db->limit($arr['limit'], $arr['start']);
		return $this->db->get();
		$this->db->trans_complete();		
	}		
	
	function get_sptprd($arr)
	{
		$this->db->trans_start();
		jqgrid_set_where($arr);
		$this->db->select('a.pendataan_id as id,
					a.pendataan_no,
					a.tgl_entry,
					a.npwp,
					a.pemohon,
					a.jenis_pendataan,
					a.jenis_pungutan,
					a.jenis_pendaftaran,
					a.spt_no,
					a.spt_tgl,
					a.nominal');
		
		$this->db->from('v_pendataan_spt a');
		$this->db->join('penetapan_pr_content b','b.pendataan_id=a.pendataan_id','left');
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
	
	function insert_detail_data()
	{
		//$this->db->trans_start();
		$insert = $this->db->insert($this->_table_detail, $this->data_detail);
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
		$this->db->where($this->_table_pk,$id);
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