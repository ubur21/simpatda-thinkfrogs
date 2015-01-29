<?php
class Feature_model extends CI_Model {

  var $data;
  
  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
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
	  $result = $this->db->query(" SELECT a.ID_BLN, a.DESK_BLN , b.npwpd,b.NAMA_WP,coalesce((select max(jumlah_pajak) from spt where npwpd = b.npwpd group by npwpd),0) as jml_pajak
FROM MST_BULAN a
left join wajib_pajak b on 1=1 
left join spt c on b.ID_WAJIB_PAJAK = c.ID_WAJIB_PAJAK and extract(month from c.tanggal_spt) <= 1 and extract(year from c.tanggal_spt) = '2015'
where a.id <= 1 and c.tanggal_spt is null ")->result();
	//$result = $this->db->get();
	return $result;
  }

}
