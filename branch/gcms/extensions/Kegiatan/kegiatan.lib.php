<?php
	if(basename( __FILE__ )==basename( $_SERVER['PHP_SELF'])) die();
	function m_kegiatan(){
		include("kegiatan.php" );
	}

    
 
function getKel($id){
    $hasil = "";
    $sql = "select * from kelompok_kegiatan kk order by kode_kelompok";
	$result = gcms_query($sql);
	
	while($data = gcms_fetch_object($result)) {
		if($data->id == $id) { $selected = 'selected'; }
		else { $selected = ''; }
		$hasil .= '<option value="'.$data->id.'" '.$selected.'>'.$data->nama_kelompok.'</option>';	
              
	}
    return $hasil;

}

function getASB2($id){
    $sql = "select kk.* from kelompok_kegiatan kk where kk.id = ".$id."";
	$result = gcms_query($sql);	
	
    while($data = gcms_fetch_object($result)) {
		$ret["belanja"]["pegawai"]["rerata"] = $data->rata_pegawai;
        $ret["belanja"]["pegawai"]["deviasi"] = $data->std_deviasi_pegawai;
        $ret["belanja"]["barang"]["rerata"] = $data->rata_barang;
        $ret["belanja"]["barang"]["deviasi"] = $data->std_deviasi_barang;
        $ret["belanja"]["modal"]["rerata"] = $data->rata_modal;
        $ret["belanja"]["modal"]["deviasi"] = $data->std_deviasi_modal;
        
        for( $i=1; $i<=4; $i++){
            $nama = "nama_indikator_".$i;
            $koef = "koefisien_".$i;
            if( !empty($data->$nama) ){
                $ret["indikator"][$i]["nama"] = $data->$nama;
                $ret["indikator"][$i]["koef"] = $data->$koef;
            }
        }
        $ret["konstanta"] = $data->konstanta;
        
	}
    //  $ret .= "<input id='btn' type='submit' name='asb' value='asb'></div></div> <!-- asb -->\n";
    //$ret .= "";
    return $ret;
   

}

function TampilASB2($asb){
    
    $bel[1] = "Belanja Pegawai";
    $bel[2] = "Belanja Barang";
    $bel[3] = "Belanja Modal";

    
   if ($asb[konstanta] == 0 ) {
    echo ' <b><font size="+2">Jumlah Kegiatan Kurang dari Jumlah Indikator</b> <br> ';
    echo ' <b>Silahkan Tambahkan Kegiatan Baru... !!!</font></b> <br> ';
    }    
?>

<div>
<h3>Analisis Standar Belanja</h3>
    <div id="asb_info">
        <span style="margin-bottom:2px;display:block;">Pengendali belanja (Cost driver)</span>
        <ul id="ul">
<?php
    foreach ($asb[indikator] as $key => $indi) {
       echo "<li>".$indi[nama]."</li>\n";
    }
?>
        </ul>
        <span class="judul">Satuan pengendali belanja tetap (Fixed cost)</span>
        <ul id="ul">
            <li>= Rp. <?php echo b_fmtAngka($asb[konstanta]) ?> per Kegiatan</li>
        </ul>
        <span class="judul">Satuan pengendali belanja variabel (Variable cost)</span>
        <ul id="ul">
<?php
    foreach ($asb[indikator] as $key => $indi) {
       echo "<li>Rp. ".b_fmtAngka($indi[koef])." per ".$indi[nama]."</li>\n";
    }
?>
        </ul>
        <span class="judul">Rumus Penghitungan Belanja Total</span>
        <ul id="ul">
            <li>Belanja Tetap + Belanja Variabel</li>
            <li>Rp. <?php 
    echo  b_fmtAngka($asb[konstanta]);
        
    foreach ($asb[indikator] as $key => $indi) {
        echo " + (".b_fmtAngka($indi[koef])." x ".$indi[nama].")";
    }
?>
            </li>
        </ul>
        <table id="tabel_BAOB_ASB" cellpadding="5">
        <caption class="caption" style="padding-bottom:5px;">Tabel Batasan Alokasi Obyek Belanja ASB</caption>
        <tr>
            <th align="center" style="border-bottom:1px solid #000000;border-right:1px solid #000000;">No</th>
            <th align="center" style="border-bottom:1px solid #000000;border-right:1px solid #000000;">Jenis Belanja</th>
            <th align="center" style="border-bottom:1px solid #000000;border-right:1px solid #000000;">Rata-rata</th>
            <th align="center" style="border-bottom:1px solid #000000;border-right:1px solid #000000;">Batas Bawah</th>
            <th align="center" style="border-bottom:1px solid #000000">Batas Atas</th>
        </tr>        
<?php
    $j = 1;
    foreach ($asb[belanja] as $key => $belanja) {
        echo "<tr>";
        echo "<td style='border-right:1px dotted #000000;'>".$j."</td>\n";
        echo "<td style='border-right:1px dotted #000000;'>".$bel[$j]."</td>\n";
        echo "<td align='right' style='border-right:1px dotted #000000;'>".b_fmtAngka($belanja[rerata])."</td>\n";
        echo "<td align='right' style='border-right:1px dotted #000000;'>".b_fmtAngka($belanja[rerata] - $belanja[deviasi])."</td>\n";
        echo "<td align='right'>".b_fmtAngka($belanja[rerata] + $belanja[deviasi])."</td>\n";
        echo "<tr>\n";
        $j += 1;
    }
?>
        </tr>
        </table>
    </div> <!-- asb_info -->
<?php
}

 ?>
