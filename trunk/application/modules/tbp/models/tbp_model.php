<?php
class Tbp_model extends CI_Model {
	function get_daftartbp(){
		$sql = " SELECT * FROM TBP ";
		$result = $this->db->query($sql)->result();
		return $result;
	}
	
	function getSPT($id_wp){
		$sql = " select c.id_spt,c.nomor_spt as skpd,c.periode_akhir as jatuh_tempo,d.KODE_REKENING as kode_akun
,d.NAMA_REKENING as nama_akun,c.jumlah_pajak as nominal_ketetapan,coalesce(e.TOTAL_BAYAR,0) total_bayarlalu
,coalesce(e.TOTAL_BAYAR,0) nominal_bayar,0.00 as kurang_bayar,0.00 as denda
from  wajib_pajak a
inner join jenis_usaha b on a.ID_JENIS_USAHA = b.ID_JENIS_USAHA
inner join spt c on a.ID_WAJIB_PAJAK = c.ID_WAJIB_PAJAK
inner join REKENING d on c.ID_REKENING = d.ID_REKENING
left join TBP e on c.ID_SPT = e.ID_SPT
where a.id_wajib_pajak = $id_wp ";
		$result = $this->db->query($sql)->result_array();
		return $result;
	}
	
	function getPAJAKOA($id_rekening){
		$sql = " SELECT coalesce(sum(b.JUMLAH_PAJAK),0) as NOMINAL_BAYAR,a.NAMA_REKENING as NAMA_AKUN, a.ID_REKENING as KODE_AKUN,a.KODE_REKENING
FROM REKENING a
inner join spt b on a.ID_REKENING = b.ID_REKENING
where (a.objek = '04' OR a.objek = '08') and b.id_rekening = '$id_rekening'
group by a.NAMA_REKENING,a.ID_REKENING,a.KODE_REKENING";
		$result = $this->db->query($sql)->result_array();
		return $result;
	}
	
}

