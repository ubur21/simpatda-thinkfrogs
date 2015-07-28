<?php
class Feature_model extends CI_Model {

  var $data;
  
  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
  }
  
  
  function getSPT($id_spt)
	{
		$this->db->select('ID_REKENING');
		$this->db->from('rekening_pr');
		//$this->db->where('kode_pr', $idpjk);
		$ada = $this->db->get()->result_array();
		$hasil = null;
		if(count($ada) > 0)
		{
			foreach($ada as $row)
			{
				$hasil[] = $row['ID_REKENING'];
			}
		}
				
		$this->db->select('
			r.id_spt,
			r.nomor_spt,
			r.tanggal_spt,
			p.tanggal,
			s.nama_rekening,
			r.periode_awal,
			r.periode_akhir,
			r.jumlah_pajak
		');
		$this->db->distinct();
		$this->db->from('spt r');
		$this->db->join('rekening s', 'r.id_rekening = s.id_rekening');
		$this->db->join('pembayaran p', 'r.id_spt = p.id_spt','left');
		$this->db->where('r.id_wajib_pajak', $id_spt);
		$this->db->where('r.tipe', 'SA');
		$this->db->where('r.tanggal_lunas is null');
		$this->db->where_in('r.id_rekening',$hasil);
		$this->db->where('r.id_spt not in (select p.id_spt from pembayaran p)');
		$result = $this->db->get()->result_array();

		return $result;
	}
	
	function getsptlngkp($id_spt)
	{
		$this->db->select('ID_REKENING');
		$this->db->from('rekening_pr');
		//$this->db->where('kode_pr', $idpjk);
		$ada = $this->db->get()->result_array();
		$hasil = null;
		if(count($ada) > 0)
		{
			foreach($ada as $row)
			{
				$hasil[] = $row['ID_REKENING'];
			}
		}
				
		$this->db->select('
			r.id_spt,
			r.nomor_spt,
			r.tanggal_spt,
			p.tanggal,
			s.nama_rekening,
			r.periode_awal,
			r.periode_akhir,
			r.jumlah_pajak
		');
		//$this->db->distinct();
		$this->db->from('spt r');
		$this->db->join('rekening s', 'r.id_rekening = s.id_rekening');
		$this->db->join('pembayaran p', 'r.id_spt = p.id_spt','left');
		$this->db->where('r.id_wajib_pajak', $id_spt);
		$this->db->where('r.tipe', 'SA');
		$this->db->where_in('r.id_rekening',$hasil);
		$this->db->order_by('r.nomor_spt');
		$result = $this->db->get()->result_array();

		return $result;
	}
	
	function getsptbayar($id_spt)
	{				
		$this->db->select('TELAH_DIBAYAR,DENDA');
		$this->db->from('pembayaran');
		$this->db->where('id_spt', $id_spt);
		$result = $this->db->get()->row_array();
		if($result){
			return $result;
		}
		else{
			return FALSE;
		}
		
	}
	
  function get_aging()
  {
	  $result = $this->db->query(" SELECT XX.* ,YY.PERIODE_AWAL AS TGL_TERBIT,COALESCE(YY.JUMLAH_PAJAK,0) JUMLAH_PAJAK
,datediff(month, YY.PERIODE_AWAL,cast('Now' as date) ) BLN_BLM_BAYAR
,( CASE WHEN  datediff(month, YY.PERIODE_AWAL,cast('Now' as date)) = 0 THEN 1 
        ELSE 
        datediff(month, YY.PERIODE_AWAL,cast('Now' as date))
   END
 ) * COALESCE(YY.JUMLAH_PAJAK,0) AS TOTAL_YG_HARUS_BAYAR  FROM (
    SELECT MIN(AA.ID_SPT) AS ID_SPT,AA.NAMA_WP,AA.NPWPD,AA.TIPE,AA.ID_REKENING,AA.NAMA_REKENING
        FROM (
            SELECT 
            a.ID_SPT
            ,a.NAMA_WP
            ,a.NPWPD
            ,CASE WHEN cast('Now' as date) >= b.TANGGAL  THEN 'LUNAS'
                ELSE 'HUTANG'
             END AS IS_BAYAR
            ,a.TIPE 
            ,a.ID_REKENING 
            ,a.PERIODE_AWAL as TGL_TERBIT
            ,c.NAMA_REKENING
            ,COALESCE(a.JUMLAH_PAJAK,0) AS JUMLAH_PAJAK
            --,a.*
            FROM SPT a
            LEFT JOIN PEMBAYARAN b ON a.ID_SPT = b.ID_SPT
            LEFT JOIN REKENING c ON a.ID_REKENING = c.ID_REKENING
            ORDER BY IS_BAYAR,a.NPWPD
        )AA
        WHERE AA.IS_BAYAR = 'HUTANG'
        GROUP BY NAMA_WP,NPWPD,TIPE,ID_REKENING,NAMA_REKENING
)XX INNER JOIN SPT YY ON XX.ID_SPT = YY.ID_SPT ORDER BY XX.TIPE DESC ")->result();
	//$result = $this->db->get();
	return $result;
  }
  
  function get_reminder()
  {
	$sql = "
select a.NAMA_WP, a.ID_WAJIB_PAJAK,a.NPWPD,max_spt.id_spt,max_spt.tanggal_spt,c.NAMA_REKENING,max_spt.jumlah_pajak, d.PERIODE_AWAL, spt_bln_berjalan.TANGGAL_SPT,'1' as flag,max_spt.id_rekening
from 
WAJIB_PAJAK a inner join ( -- cek jumlah pajak terakhir sebagai dasar perhitungan
    select a.id_max as id_spt , b.TANGGAL_SPT,b.ID_WAJIB_PAJAK,b.ID_REKENING,b.JUMLAH_PAJAK from 
    (
        select max(id_spt) id_max from spt where tipe='SA' group by id_rekening
    )a inner join spt b on a.id_max = b.ID_SPT
)max_spt on a.ID_WAJIB_PAJAK = max_spt.id_wajib_pajak
inner join REKENING c on max_spt.id_rekening = c.ID_REKENING
left join spt spt_bln_berjalan on a.ID_WAJIB_PAJAK = spt_bln_berjalan.ID_WAJIB_PAJAK --cek apakah bulan berjalan, sudah melaporkan SPTPD
          and max_spt.id_spt = spt_bln_berjalan.id_spt
          and extract(month from spt_bln_berjalan.TANGGAL_SPT) = extract(month from current_timestamp) 
          and extract(year from spt_bln_berjalan.TANGGAL_SPT) = extract(year from current_timestamp) 
left join teguran d on a.ID_WAJIB_PAJAK = d.ID_WAJIB_PAJAK 
        and extract(month from current_timestamp) = extract(month from d.tanggal_spt)
        and extract(year from current_timestamp) = extract(year from d.tanggal_spt)
		 where d.TANGGAL_SPT is null
";
		
	  $result = $this->db->query($sql)->result();
	//$result = $this->db->get();  where d.tanggal_spt is null
	return $result;
  }
  
  function get_teguran()
  {
	$sql = "
select b.NAMA_WP,b.NPWPD, a.* ,c.NAMA_REKENING
from teguran a
inner join WAJIB_PAJAK b on a.ID_WAJIB_PAJAK = b.ID_WAJIB_PAJAK
inner join REKENING c on a.id_rekening = c.ID_REKENING
";
		
	  $result = $this->db->query($sql)->result();
	//$result = $this->db->get();  where d.tanggal_spt is null
	return $result;
  }
  
   function get_reminder2()
  {
	$sql = "
select a.ID_WAJIB_PAJAK,a.alamat_wp,a.NAMA_WP,a.NPWPD,C.NAMA_REKENING,b.jumlah_pajak,b.ID_REKENING,b.TIPE,b.STATUS_SPT,b.LOKASI,b.URAIAN,b.TARIF_RP,b.TARIF_PERSEN,b.JUMLAH,b.NOMOR_SPT
from 
wajib_pajak a 
left join (select a.ID_WAJIB_PAJAK,a.jumlah_pajak,a.id_rekening,a.TIPE,a.STATUS_SPT,a.LOKASI,a.URAIAN,a.TARIF_RP,a.TARIF_PERSEN,a.JUMLAH,a.NOMOR_SPT
from
spt a inner join (
select max(id_spt) id_max from spt group by id_wajib_pajak
) b on a.id_spt = b.id_max) b on  a.ID_WAJIB_PAJAK = b.ID_WAJIB_PAJAK
LEFT JOIN REKENING C ON B.ID_REKENING = C.ID_REKENING
left join spt d on a.id_wajib_pajak = d.id_wajib_pajak and extract(month from d.tanggal_spt) = extract(month from current_timestamp)
and extract(year from d.tanggal_spt) = extract(year from current_timestamp)
left join teguran e on a.ID_WAJIB_PAJAK = e.ID_WAJIB_PAJAK and  extract(month from current_timestamp) = extract(month from e.periode_awal)
where e.PERIODE_AWAL is null and b.tipe='SA'

";
	  $result = $this->db->query($sql)->result();
		foreach($result as $row)
		{
			$result2 = $this->db->query("insert into teguran(id_wajib_pajak,periode_awal,periode_akhir,periode_telat,periode_cetak) values ('".$row->ID_WAJIB_PAJAK."','01.".date('m.Y')."','".date('t.m.Y')."','20.".date('m.Y')."','".date('d.m.Y')."')");
			
			$result3 = $this->db->query("insert into spt(ID_WAJIB_PAJAK, ID_REKENING, TANGGAL_SPT, NOMOR_SPT, TIPE, STATUS_SPT, NAMA_WP, ALAMAT_WP, LOKASI, URAIAN, PERIODE_AWAL, PERIODE_AKHIR, TARIF_RP, TARIF_PERSEN, JUMLAH_PAJAK, NPWPD) values ('".$row->ID_WAJIB_PAJAK."','".$row->ID_REKENING."','".date('d.m.Y')."','".$row->NOMOR_SPT."','".$row->TIPE."','".$row->STATUS_SPT."','".$row->NAMA_WP."','".$row->ALAMAT_WP."','".$row->LOKASI."','".$row->URAIAN."','01.".date('m.Y')."','".date('t.m.Y')."','".$row->TARIF_RP."','".$row->TARIF_PERSEN."','".$row->JUMLAH_PAJAK."','".$row->NPWPD."')");
		}
	return $result;
  }
  
  function proses_teguran_db($id_wajib_pajak,$id_rekening)
  {
	$result = $this->db->query(" insert into teguran(id_wajib_pajak,tanggal_spt,id_rekening) values ('".$id_wajib_pajak."','".date('d.m.Y')."','".$id_rekening."') ");
	return $result;
  }

}
