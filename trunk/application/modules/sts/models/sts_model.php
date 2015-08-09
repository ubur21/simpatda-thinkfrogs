<?php
class Sts_model extends CI_Model {
	
	function get_daftarsts(){
		$sql = " SELECT distinct NOMOR_STS, TGL_SETOR, TOTAL_SETOR  FROM STS ";
		$result = $this->db->query($sql)->result();
		return $result;
	}
	
	function get_sts($nosts){
		$sql = " SELECT * FROM STS where NOMOR_STS='".$nosts."'";
		$result = $this->db->query($sql)->result_array();
		return $result;
	}
	
	function get_sts_one($nosts){
		$sql = " SELECT * FROM STS where NOMOR_STS='".$nosts."'";
		$result = $this->db->query($sql)->row_array();
		return $result;
	}
	
	function getlistTBP(){
		$sql = " SELECT A.ID,A.TOTAL_BAYAR AS NOMINAL,  B.KODE_REKENING AS IDAKUN, B.NAMA_REKENING AS NAMA,0 AS SISA
FROM TBP A
LEFT JOIN SPT C on A.ID_SPT = C.ID_SPT
INNER JOIN REKENING B ON A.ID_REKENING = B.ID_REKENING OR c.ID_REKENING = B.ID_REKENING ";
		$result = $this->db->query($sql)->result_array();
		return $result;
	}
		
	function getlistTBP2($id){
		$sql = " SELECT A.ID,A.TOTAL_BAYAR AS NOMINAL,  B.KODE_REKENING AS IDAKUN, B.NAMA_REKENING AS NAMA,0 AS SISA
FROM TBP A
INNER JOIN REKENING B ON A.ID_REKENING = B.ID_REKENING WHERE A.ID IN (".$id.")";
		$result = $this->db->query($sql)->result_array();
		return $result;
	}
	
	function delete_data(){
		$id = $this->input->post('id');
		$this->db->trans_start();
		$result = $this->db->query("DELETE FROM STS WHERE TBP_ID IN(".$id.")");
		$this->db->trans_complete();
	}
	
	function save_data(){
		$idspts = $this->input->post('idspt');
		$idskpd = $this->input->post('idskpd');
		$no_sts = $this->input->post('no_sts');
		$tgl_sts = $this->input->post('tgl_sts');
		$id_jurnal = $this->input->post('id_jurnal');
		$id_bendahara = $this->input->post('id_bendahara');
		$id_kasdaerah = $this->input->post('id_kasdaerah');
		$keterangan = $this->input->post('keterangan');
		$total_setor = $this->input->post('rincian_setoran');
		
		$this->db->trans_start();
		for($i=0;$i < count($idspts);$i++){
			$result = $this->db->query(" INSERT INTO STS (ID_JENIS_PAJAK,NOMOR_STS,TGL_SETOR,ID_JURNAL,ID_BENDAHARA, ID_AKUN_KAS,KETERANGAN, TBP_ID, TOTAL_SETOR) VALUES (".$idskpd.",'".$no_sts."','".prepare_date($tgl_sts)."',".$id_jurnal.",".$id_bendahara.",".$id_kasdaerah.",'".$keterangan."',".(int)$idspts[$i].",'".(int)$total_setor."')");
			$update = $this->db->query("UPDATE TBP SET IS_STS=1 WHERE ID =".(int)$idspts[$i]);
		}
				
		$this->db->trans_complete();
	}
}