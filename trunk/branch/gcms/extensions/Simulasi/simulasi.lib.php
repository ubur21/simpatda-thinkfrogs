<?php
	if(basename( __FILE__ )==basename( $_SERVER['PHP_SELF'])) die();
	function m_simulasi()
	{
		include("simulasi.php" );
	}


function getKelompok($id){
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

function getASB($id){
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
    return $ret;
}

function TampilASB($asb){
    $bel[1] = "Belanja Pegawai";
    $bel[2] = "Belanja Barang";
    $bel[3] = "Belanja Modal";
?>

<div>
<h3>Analisis Standar Belanja</h3>
    <div id="asb_info">
        <span style="margin-bottom:2px;display:block;">Pengendali belanja (Cost driver)</span>
        <ul id="ul">
<?php
    foreach ($asb[indikator] as $key => $ind) {
       echo "<li>".$ind[nama]."</li>\n";
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
    foreach ($asb[indikator] as $key => $ind) {
       echo "<li>Rp. ".b_fmtAngka($ind[koef])." per ".$ind[nama]."</li>\n";
    }
?>
        </ul>
        <span class="judul">Rumus Penghitungan Belanja Total</span>
        <ul id="ul">
            <li>Belanja Tetap + Belanja Variabel</li>
            <li>Rp. <?php 
    echo  b_fmtAngka($asb[konstanta]);
        
    foreach ($asb[indikator] as $key => $ind) {
        echo " + (".b_fmtAngka($ind[koef])." x ".$ind[nama].")";
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

function HitungSimulasi($asb){
    $indikator[1] = $_REQUEST['indikator_1'];
	$indikator[2] = $_REQUEST['indikator_2'];
    $indikator[3] = $_REQUEST['indikator_3'];
	$indikator[4] = $_REQUEST['indikator_4'];
    
    $total_belanja = $asb[belanja][pegawai][rerata] +
                               $asb[belanja][barang][rerata] +
                               $asb[belanja][modal][rerata];
?>
<div id='asb_hasil'>
    <table>
    <h3>Hasil Simulasi Kegiatan <b><?php echo ucwords($_REQUEST['kegiatan']); ?></b></h3>
    <tr>
        <td valign="top">Total Belanja</td>
        <td>= 
<?php 
    $total = 0;
    echo b_fmtAngka( $asb[konstanta] );
    foreach ($asb[indikator] as $key => $ind) {
        echo " + (".b_fmtAngka( $indikator[$key] )." x ".b_fmtAngka( $ind[koef] ).")";
        $total += $indikator[$key] * $ind[koef];
    }
?><br /><span class="bold"> = Rp. 
<?php
    echo b_fmtAngka($total);
?>
        </span>
		</td>
    </tr>
    <tr>
        <td valign="top">Rerata Belanja Pegawai</td>
        <td>= 
<?php
    echo sprintf("%01.3f", $asb[belanja][pegawai][rerata] / $total_belanja * 100). "% x ".b_fmtAngka( $total )."<br />\n";
    echo "<span class='bold'>= Rp. ".b_fmtAngka( sprintf("%01.3f", ($asb[belanja][pegawai][rerata] / $total_belanja) * $total)  );
?>    </span>
        </td>
    </tr>
    <tr>
        <td valign="top">Rerata Belanja Barang</td>
        <td> = 
<?php
    echo sprintf("%01.3f", $asb[belanja][barang][rerata] / $total_belanja * 100). "% x ".b_fmtAngka( $total )."<br />\n";
    echo "<span class='bold'>= Rp. ".b_fmtAngka( sprintf("%01.3f", ($asb[belanja][barang][rerata]  / $total_belanja) * $total) );
?>    </span>
        </td>
    </tr>
    <tr>
        <td valign="top">Rerata Belanja Modal</td>
        <td>= 
<?php
    echo sprintf("%01.3f", $asb[belanja][modal][rerata] / $total_belanja * 100). "% x ".b_fmtAngka( $total )."<br />\n";
    echo "<span class='bold'>= Rp. ".b_fmtAngka( sprintf("%01.3f", ($asb[belanja][modal][rerata]  / $total_belanja) * $total) );
?>    </span>
        </td>
    </tr>
    </table>
</div> <!-- asb_hasil -->
<?php
}

function TampilSimulasi($asb){
    $kegiatan = isset($_REQUEST['kegiatan'])?$_REQUEST['kegiatan']:"";
    $hasil =
    "<div id='asb_simulasi'>
        <h3 class='h'>Simulasi Analisis Standar Belanja</h3>\n
        <div id='asb_simulasi_form'>
		<label  for='kegiatan'><span class='fixedsize'>Nama Kegiatan</span></label>
        <input class='field_custom' type='text' id='kegiatan' name='kegiatan' value='".$kegiatan."' /><br />\n";
    foreach ($asb[indikator] as $key => $ind) {
        $lbl = isset($_REQUEST[indikator_.$key])?$_REQUEST[indikator_.$key]:"";
       $hasil .= " <label  for='indikator_".$key."'><span class='fixedsize' >".$ind[nama]."</span></label>
        <input class='field_custom' type='text' id='indikator_".$key."' name='indikator_".$key."' value='".$lbl."' /><br />\n";
    }
    $hasil .= "<input id='btn' type='submit' name='simulasi' value='simulasi'></div></div> <!-- asb_simulasi -->\n";
    $hasil .= "";
    return $hasil;
}

 ?>
