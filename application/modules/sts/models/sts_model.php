<?php
class Sts_model extends CI_Model {
	
	function get_daftarsts(){
		$sql = " SELECT * FROM STS ";
		$result = $this->db->query($sql)->result();
		return $result;
	}
	
	function getlistTBP(){
		$sql = " SELECT A.ID,A.TOTAL_BAYAR AS NOMINAL,  B.KODE_REKENING AS IDAKUN, B.NAMA_REKENING AS NAMA,0 AS SISA
FROM TBP A
INNER JOIN REKENING B ON A.ID_REKENING = B.ID_REKENING ";
		$result = $this->db->query($sql)->result_array();
		return $result;
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
		
		$this->db->trans_start();
		for($i=0;$i < count($idspts);$i++){
			$result = $this->db->query(" INSERT INTO STS (ID_JENIS_PAJAK,NOMOR_STS,TGL_SETOR,ID_JURNAL,ID_BENDAHARA, ID_AKUN_KAS,KETERANGAN, TBP_ID) VALUES (".$idskpd.",'".$no_sts."','".prepare_date($tgl_sts)."',".$id_jurnal.",".$id_bendahara.",".$id_kasdaerah.",'".$keterangan."',".(int)$idspts[$i].")");
			$update = $this->db->query("UPDATE TBP SET IS_STS=1 WHERE ID =".(int)$idspts[$i]);
		}
				
		$this->db->trans_complete();
	}
}