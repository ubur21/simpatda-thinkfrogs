<?php

include "./../../../config.php";
include "./../../../lib.php";

if(!empty($_GET['Npwp'])){
 
 	$cr="select p.pendaftaran_id,
					p.no_pendaftaran,p.npwp,
					ph.nama,ph.alamat,ph.id_desa,s.spt_id,
					s.spt_no,p.tanggal_kembali,k.lurah_nama,kc.camat_nama,k.lurah_kecamatan,
					p.jenis_pendaftaran
					from pendaftaran p left join pemohon ph
						on p.id_pemohon = ph.pemohon_id
						left join spt s on p.pendaftaran_id = s.pendaftaran_id
						left join kelurahan k on ph.id_desa = k.lurah_id
						left join kecamatan kc on k.lurah_kecamatan = kc.camat_id
					where p.pendaftaran_id ='".$_GET['Npwp']."'";
	$xx=gcms_query($cr);
	$row=gcms_fetch_object($xx);
	if($row->tanggal_kembali=="" || $row->tanggal_kembali==NULL){
	$TglAkhir = "";
	}else{
	$TglAkhir = date("Y",strtotime($row->periode_akhir));
	}
	if($row->jenis_pendaftaran =="PAJAK"){
		$jenis = "PAJAK";
	}else{
		$jenis = "RETRIBUSI";
	}
	?>
	document.getElementById('kode_npwp').value='<?=$jenis?>';
	document.getElementById('npwpd_npwrd1').value='<?=$row->npwp?>';
	document.getElementById('npwpd_npwrd2').value='<?=$row->no_pendaftaran?>';
	document.getElementById('npwpd_npwrd3').value='<?=sprintf("%02d",$row->id_desa)?>';
	document.getElementById('npwpd_npwrd4').value='<?=sprintf("%02d",$row->lurah_kecamatan)?>';
	document.getElementById('nama_wp_wr').value='<?=$row->nama?>';
	document.getElementById('Alamat').value='<?=showMultiLine($row->alamat)?>';
	document.getElementById('id_desa').value='<?=$row->id_desa?>';
	document.getElementById('pendaftaran_id').value='<?=$row->pendaftaran_id?>';
	document.getElementById('Periode').value='<?=$TglAkhir?>';
	document.getElementById('NomorSpt').value='<?=$row->spt_no?>';
	document.getElementById('Kel').value='<?=$row->lurah_nama?>';
	document.getElementById('Kec').value='<?=$row->camat_nama?>';
	document.getElementById('Kab').value='';
	document.getElementById('SptIdHid').value='<?=$row->spt_id?>';
	//document.getElementById('pendataan_id_hid').value='<?=$row->pendataan_id?>';
	//alert(<?=$row->pendataan_id?>);
	
	<?
	if(!empty($row->pendataan_id)){
		$count = b_fetch("select count(*) from pendataan_restoran_detail where pendataan_id ='".$row->pendataan_id."'");
		if($count){
			?>
	/*		$("tr.isi_detail").remove();	
			for(id=1; id <= <?=$count?>; id++){
				//id=parseInt( id ) + 1; 
				//document.getElementById('id_isi_detail').value = id;
				//alert(id);
				var str = " <tr id='" + id + "' "
						 +" class='isi_detail'> ";			
				//var data = kodeRek;
				str += "<td><input type='hidden' id=\"DetailHid"+id+"\" name=\"DetailHid"+id+"\" ><input  id=\"jumlah_meja"+id+"\" type='text' name=\"jumlah_meja"+id+"\" value='' size='30'></td><td><input class='field_angka' id=\"jumlah_kursi"+id+"\" type='text' name=\"jumlah_kursi"+id+"\" size='10'></td><td><input class='field_angka' id=\"jumlah_tamu"+id+"\" type='text' name=\"jumlah_tamu"+id+"\" size='20' /></td>";
				
				str += "<td><input type='button' onclick=\"hapus_data('"+id+"');\" name='hapus' value='hapus' />";
				
				$("#detail").append( str );
			}
			
			document.getElementById('id_isi_detail').value='<?=$count?>';
	*/
			//alert(<?=$count?>);
			<?
			$cari_detail_restoran = "select * from pendataan_restoran_detail where pendataan_id ='".$row->pendataan_id."'";
			$query_detail_restoran = gcms_query($cari_detail_restoran);
			$i=1;
			$total = 0;
			while($rows = gcms_fetch_object($query_detail_restoran)){
			//$total += $rows->jumlah_kamar*$rows->tarif_kamar;
			?>
	//		document.getElementById('DetailHid'+<?=$i?>).value='<?=$rows->restoran_detail_id?>';
	//		document.getElementById('jumlah_meja'+<?=$i?>).value='<?=$rows->jumlah_meja?>';
	//		document.getElementById('jumlah_kursi'+<?=$i?>).value=formatCurrency('<?=$rows->jumlah_kursi?>');
	//		document.getElementById('jumlah_tamu'+<?=$i?>).value=formatCurrency('<?=$rows->jumlah_tamu_per_hari?>');
			<?
			$i++;
			}
			?>
			//document.getElementById('total_tarif').value=formatCurrency('<?=$total?>');
			<?
		}
	}
}
if(!empty($_GET['RekeningRestoran'])){
	$cr="select * from rekening_kode where id='".$_GET['RekeningRestoran']."'";
	$xx=gcms_query($cr);
	$row=gcms_fetch_object($xx);
	$replace = explode(".",$row->kode_rekening);
	?>
	document.getElementById('KodeRekening1').value='<?=$replace[0].$replace[1].$replace[2]?>';
	document.getElementById('KodeRekening2').value='<?=$replace[3]?>';
	document.getElementById('KodeRekening3').value='<?=$replace[4]?>';
	document.getElementById('IdRekening').value='<?=$row->id?>';
	document.getElementById('persen').value=formatCurrency('<?=$row->persen_tarif?>');
	document.getElementById('Tarif').value=formatCurrency('<?=$row->tarif_dasar?>');
	document.getElementById('Pajak').value=formatCurrency('<?=($row->tarif_dasar*$row->persen_tarif)/100?>');
	document.getElementById('NamaRekening').value='<?=$row->nama_rekening?>';
	<?
}
if( $_GET['rekening'] ) {
	/* belum ada proses
	if( $_GET['rekening'] == 'parkir' ) {
		$tipe = '4';
		$objek = '07';
	}
	*/
	$query = "SELECT a.kode_rekening, a.nama_rekening,  a.persen_tarif from rekening_kode a";
	$result = gcms_query( $query );
	
	
	$str='<select id="kode_rekening" name="kode_rekening" onchange="get_ptarif(this.value);" >';
	$str.='<option value="..." selected>....</option>';
	
	while( $rows = gcms_fetch_object( $result ) ) {
		if( strlen( $rows->nama_rekening ) > 25 ) {
			$rows->nama_rekening = substr( $rows->nama_rekening, 0, 24 );
		}
		$nama_rek = strtolower( $rows->nama_rekening );

		$str.='<option value="'.$rows->persen_tarif.'">'.$rows->kode_rekening." - ".$nama_rek.'</option>';
	}
	
	$str.='</select>';
	?>
	var kodeRek='<?php echo $str;?>';
	<?php
}
if(!empty($_GET['DataForm'])){
	$idPendataan = b_fetch("select pendataan_id from pendataan_restoran where restoran_id ='".$_GET['DataForm']."'");
	$count = b_fetch("select count(*) from pendataan_restoran_detail where pendataan_id ='".$idPendataan."'");
		if($count){
			?>
		/*	$("tr.isi_detail").remove();
			for(id=1; id <= <?=$count?>; id++){
			//alert(id);
				var str = " <tr id='" + id + "' "
						 +" class='isi_detail'> ";			
				//var data = kodeRek;
				str += "<td><input type='hidden' id=\"DetailHid"+id+"\" name=\"DetailHid"+id+"\" ><input  id=\"jumlah_meja"+id+"\" type='text' name=\"jumlah_meja"+id+"\" value='' size='30'></td><td><input class='field_angka' id=\"jumlah_kursi"+id+"\" type='text' name=\"jumlah_kursi"+id+"\" size='10'></td><td><input class='field_angka' id=\"jumlah_tamu"+id+"\" type='text' name=\"jumlah_tamu"+id+"\" size='20' /></td>";
				
				str += "<td><input type='button' onclick=\"hapus_data('"+id+"');\" name='hapus' value='hapus' />";
				
				$("#detail").append( str );
			}
			
			document.getElementById('id_isi_detail').value='<?=$count?>';
		*/
		<? 
		
			$cari_master ="select * from pendataan_restoran pr
							left join pendataan_spt ps on pr.pendataan_id = ps.pendataan_id
							left join pendataan_restoran_detail pd on ps.pendataan_id = pd.pendataan_id
							left join pendaftaran p on ps.pendaftaran_id = p.pendaftaran_id
							left join rekening_kode rk on pr.id_rekening = rk.id
							left join pemohon pm on p.id_pemohon = pm.pemohon_id 
							left join spt s on p.pendaftaran_id = s.pendaftaran_id
							left join kelurahan k on pm.id_desa = k.lurah_id
							left join kecamatan kc on k.lurah_kecamatan = kc.camat_id
							where pr.restoran_id ='".$_GET['DataForm']."'";
			$query_master = gcms_query($cari_master);
			
			$i=1;
			//$total = 0;
			while($row = gcms_fetch_object($query_master)){
				//$total += $row->jumlah_kamar*$row->tarif_kamar;
				$NoReg = sprintf("%06d",$row->restoran_id);
				$TglProses = date("d-m-Y",strtotime($row->tgl_proses));
				$TglEntri = date("d-m-Y",strtotime($row->tgl_entry));
				$IdEdit = $row->restoran_id;
				$id_desa = $row->restoran_id_desa;
				$pendaftaran_id = $row->pendaftaran_id;
				$id_rekening = $row->id_rekening;
				$pendataan_id_hid = $row->pendataan_id;
				$Npwp = $row->npwp;
				$periodeSpt1 = $row->spt_no;
				if($row->tgl_kembali=="" || $row->tgl_kembali ==NULL){
				$Tgl = "";
				}else{
				$Tgl = date("Y",strtotime($row->tgl_kembali));
				}
				$periodeSpt2 = $Tgl;
				$memo = showMultiLine($row->memo);
				$nama = $row->restoran_nama;
				$alamat = showMultiLine($row->restoran_alamat);
				$kelurahan = $row->lurah_nama;
				$kecamatan = $row->camat_nama;
				$kabupaten = '';
				$sistem_pemungutan = $row->jenis_pungutan;
				$periodeTransaksi1 = date("d-m-Y",strtotime($row->periode_awal));
				$periodeTransaksi2 = date("d-m-Y",strtotime($row->periode_akhir));
				$replace = explode(".",$row->kode_rekening);
				$KodeRek1 = $replace[0].$replace[1].$replace[2];
				$KodeRek2 = $replace[3];
				$KodeRek3 = $replace[4];
				$NamaRek = $row->nama_rekening;
				$TarifDasar = $row->dasar_pengenaan;
				$Persen = $row->persen_tarif;
				$Pajak = ($row->dasar_pengenaan*$row->persen_tarif)/100;
				?>
		//		document.getElementById('DetailHid'+<?=$i?>).value='<?=$row->restoran_detail_id?>';
		//		document.getElementById('jumlah_meja'+<?=$i?>).value='<?=$row->jumlah_meja?>';
		//		document.getElementById('jumlah_kursi'+<?=$i?>).value=formatCurrency('<?=$row->jumlah_kursi?>');
		//		document.getElementById('jumlah_tamu'+<?=$i?>).value=formatCurrency('<?=$row->jumlah_tamu_per_hari?>');
				<?
				$i++;
			}
			
		}else{
			$cari_master ="select * from pendataan_restoran pr
							left join pendataan_spt ps on pr.pendataan_id = ps.pendataan_id
							left join pendataan_restoran_detail pd on ps.pendataan_id = pd.pendataan_id
							left join pendaftaran p on ps.pendaftaran_id = p.pendaftaran_id
							left join rekening_kode rk on pr.id_rekening = rk.id
							left join pemohon pm on p.id_pemohon = pm.pemohon_id 
							left join spt s on p.pendaftaran_id = s.pendaftaran_id
							left join kelurahan k on pm.id_desa = k.lurah_id
							left join kecamatan kc on k.lurah_kecamatan = kc.camat_id
							where pr.restoran_id ='".$_GET['DataForm']."'";
			$query_master = gcms_query($cari_master);
			
			//$total = 0;
			$row = gcms_fetch_object($query_master);
				//$total += $row->jumlah_kamar*$row->tarif_kamar;
				$NoReg = sprintf("%06d",$row->restoran_id);
				$TglProses = date("d-m-Y",strtotime($row->tgl_proses));
				$TglEntri = date("d-m-Y",strtotime($row->tgl_entry));
				$IdEdit = $row->restoran_id;
				$id_desa = $row->restoran_id_desa;
				$pendaftaran_id = $row->pendaftaran_id;
				$id_rekening = $row->id_rekening;
				$pendataan_id_hid = $row->pendataan_id;
				$Npwp = $row->npwp;
				$periodeSpt1 = $row->spt_no;
				if($row->tgl_kembali=="" || $row->tgl_kembali ==NULL){
				$Tgl = "";
				}else{
				$Tgl = date("Y",strtotime($row->tgl_kembali));
				}
				$periodeSpt2 = $Tgl;
				$memo = showMultiLine($row->memo);
				$nama = $row->restoran_nama;
				$alamat = showMultiLine($row->restoran_alamat);
				$kelurahan = $row->lurah_nama;
				$kecamatan = $row->camat_nama;
				$kabupaten = '';
				$sistem_pemungutan = $row->jenis_pungutan;
				$periodeTransaksi1 = date("d-m-Y",strtotime($row->periode_awal));
				$periodeTransaksi2 = date("d-m-Y",strtotime($row->periode_akhir));
				$replace = explode(".",$row->kode_rekening);
				$KodeRek1 = $replace[0].$replace[1].$replace[2];
				$KodeRek2 = $replace[3];
				$KodeRek3 = $replace[4];
				$NamaRek = $row->nama_rekening;
				$TarifDasar = $row->dasar_pengenaan;
				$Persen = $row->persen_tarif;
				$Pajak = ($row->dasar_pengenaan*$row->persen_tarif)/100;
				
			}//End If $count
		?>
				document.getElementById('IdEdit').value='<?=$IdEdit?>';
				document.getElementById('pendataan_id_hid').value='<?=$pendataan_id_hid?>';
				document.getElementById('id_desa').value='<?=$id_desa?>';
				document.getElementById('pendaftaran_id').value='<?=$pendaftaran_id?>';
				document.getElementById('IdRekening').value='<?=$id_rekening?>';
				document.getElementById('NoRegForm').value='<?=$NoReg?>';
				document.getElementById('memo').value='<?=$memo?>';
				document.getElementById('date_1').value='<?=$TglProses?>';
				document.getElementById('date_2').value='<?=$TglEntri?>';
				document.getElementById('npwpd_npwrd1').value='<?=$Npwp?>';
				document.getElementById('Periode').value='<?=$periodeSpt2?>';
				document.getElementById('NomorSpt').value='<?=$periodeSpt1?>';
				document.getElementById('nama_wp_wr').value='<?=$nama?>';
				document.getElementById('Alamat').value='<?=$alamat?>';
				document.getElementById('Kel').value='<?=$kelurahan?>';
				document.getElementById('Kec').value='<?=$kecamatan?>';
				document.getElementById('Kab').value='';
				document.getElementById('SystemPemungutan').value='<?=$sistem_pemungutan?>';
				document.getElementById('date_3').value='<?=$periodeTransaksi1?>';
				document.getElementById('date_4').value='<?=$periodeTransaksi2?>';
				document.getElementById('KodeRekening1').value='<?=$KodeRek1?>';
				document.getElementById('KodeRekening2').value='<?=$KodeRek2?>';
				document.getElementById('KodeRekening3').value='<?=$KodeRek3?>';
				document.getElementById('NamaRekening').value='<?=$NamaRek?>';
				document.getElementById('Tarif').value=formatCurrency('<?=$TarifDasar?>');
				document.getElementById('persen').value=formatCurrency('<?=$Persen?>');
				document.getElementById('Pajak').value=formatCurrency('<?=$Pajak?>');
				//document.getElementById('total_tarif').value=formatCurrency('<?=$total?>');
			<?
}
?>
function hitung_tarif(x) {
	var cindex = document.getElementById('id_isi_detail').value;
	var jum=0;
	for(i=1;i <=cindex;i++){
	//alert(cindex);
		var a = document.getElementById('tarif_kamar'+i).value;
		var	b = Number(document.getElementById('jumlah_kamar'+i).value);
		var c = a.replace(/,/g,'');
		//alert(c+''+b);
		jum+=c*b;
	 //	totalTarifKamar = a*b;
	}
	//alert(jum);
	document.getElementById( 'total_tarif' ).value= formatCurrency(jum);
}