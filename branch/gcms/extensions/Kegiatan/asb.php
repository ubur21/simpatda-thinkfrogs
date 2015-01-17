<?php 
include("fungsi.php");
include("matrix.php");
include('../../global.php');

function reset_arr(&$array){
    if (count($array) > 0) 
    foreach ($array as $i => $value) {
        unset($array[$i]);
    }
}
  $csql = "select distinct * from kelompok_kegiatan k where k.id =".$_REQUEST[gid]." order by k.kode_kelompok ";
  $nresult=gcms_query($csql);
  while ($rox = gcms_fetch_object($nresult)) {
       $nama = array($rox->nama_indikator_1,$rox->nama_indikator_2,$rox->nama_indikator_3,$rox->nama_indikator_4) ;
    
    /*mereset data*/
    reset_arr( $a );
    reset_arr( $b );
    reset_arr( $c );
    reset_arr( $y );

    $sql ='SELECT id id, (a.BELANJA_PEGAWAI) pegawai, 
            (a.BELANJA_BARANG_JASA) barang, (a.BELANJA_MODAL) modal, 
            (a.belanja_pegawai+a.belanja_barang_jasa+a.belanja_modal) total,
             a.indikator_1, a.indikator_2, a.indikator_3, a.indikator_4            
            from kegiatan a where a.id_kelompok = '.$rox->id;
            
	$result=gcms_query($sql);
    $i = 0;    
    while ($row = gcms_fetch_object($result)) {
        $a[] = $row->pegawai;        
        $b[] = $row->barang;
        $c[] = $row->modal;      
        $tot[] = $row->total;    
        
        $ind[$i][] = 1;
        foreach($nama as $key=>$value){
          if ( $value != '' ) {
            switch ($key) {
              case 0 : $ind[$i][] = $row->indikator_1;break;
              case 1 : $ind[$i][] = $row->indikator_2;break;
              case 2 : $ind[$i][] = $row->indikator_3;break;
              case 3 : $ind[$i][] = $row->indikator_4;break;
            }            
          }
        }
        $i+=1;
   }  

   /*menghitung rata-rata */    
   $average_pegawai = mean ($a) ;
   $average_barang  = mean($b);
   $average_modal   = mean($c);
   
   /*menghitung standar deviasi */
   $standard_deviation_pegawai = standard_deviation_population ($a);
   $standard_deviation_barang = standard_deviation_population ($b);
   $standard_deviation_modal = standard_deviation_population ($c);



  } //while
 
             
$dataX = $ind;
$dataY = $tot;

                 
$M = new matrix( $dataX );
$X = $M->ArrayData;
$M = new matrix( $dataY );
$Y = $M->ArrayData;

$Xt = $M->Transpose($X);
$XtX = $M->MultiplyMatrix( $Xt, $X );
$XtY = $M->MultiplyMatrix( $Xt, $Y );
$XtXi = $M->InverseMatrix($XtX);
$b = $M->MultiplyMatrix( $XtXi, $XtY );


 $usql = "update kelompok_kegiatan set "
	      ."koefisien_1=".(empty($b[1][0])?'null':sprintf("%01.3f", $b[1][0]))
          .",koefisien_2=".(empty($b[2][0])?'null':sprintf("%01.3f", $b[2][0]))
          .",koefisien_3=".(empty($b[3][0])?'null':sprintf("%01.3f", $b[3][0]))
          .",koefisien_4=".(empty($b[4][0])?'null':sprintf("%01.3f", $b[4][0]))
	      .",konstanta=".(empty($b[0][0])?'null':sprintf("%01.3f", $b[0][0]))
          .",rata_pegawai=".(empty($average_pegawai)?'null':sprintf("%01.3f", $average_pegawai))
          .",rata_barang=".(empty($average_barang)?'null':sprintf("%01.3f", $average_barang))
          .",rata_modal=".(empty($average_modal)?'null':sprintf("%01.3f", $average_modal))
          .",std_deviasi_pegawai=".(empty($standard_deviation_pegawai)?'null':sprintf("%01.3f", $standard_deviation_pegawai))
          .",std_deviasi_barang=".(empty($standard_deviation_barang)?'null':sprintf("%01.3f", $standard_deviation_barang))
          .",std_deviasi_modal=".(empty($standard_deviation_modal)?'null':sprintf("%01.3f", $standard_deviation_modal))
		  ." where id=".$_REQUEST[gid];
          
 gcms_query($usql);
 echo $usql;
?> 


