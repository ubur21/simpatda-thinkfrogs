<?php	
	if(basename( __FILE__ )==basename( $_SERVER['PHP_SELF'])) die();
	
	function getListPemohon(){
		$qy = 'select pemohon_id,nama from pemohon';
		$data = gcms_query($qy); $opt="<option value=''></option>";
		while($rs = gcms_fetch_object($data)){
			$opt.="<option value='$rs->pemohon_id'>$rs->nama</option>";
		}
		return $opt;
	}

	function getListBadanUsaha(){
		$qy = 'select id,badan_nama from badan_usaha ';
		$data = gcms_query($qy); $opt="<option value=''></option>";
		while($rs = gcms_fetch_object($data)){
			$opt.="<option value='$rs->id'>$rs->badan_nama</option>";
		}
		return $opt;
	}

	function getLisKodeUsaha(){
		$qy = 'select id, kode, nama  from kode_usaha ';
		$data = gcms_query($qy); $opt='';
		while($rs = gcms_fetch_object($data)){
			$opt.="<option value='$rs->id'>($rs->kode) $rs->nama</option>";
		}
		return $opt;
	}

	function setNomorPendaftaran($tipe,$objek){

		//where jenis_pendaftaran='.quote_smart($tipe).' and objek_pdrd='.quote_smart($objek)

		$nmax = b_fetch('select max(nurut) from pendaftaran');

		$nex_no = $nmax+1;
		
		switch($tipe){
			case 'PAJAK': $PREFIX = 'P'; break;
			case 'RETRIBUSI': $PREFIX = 'R'; break;
			default : $PREFIX = 'x';
		}
		
		switch($objek){
			case 'PRIBADI': $objek_pajak='1'; break;
			case 'BU': $objek_pajak='2'; break;
		}
		
		if($_REQUEST['kod']!=''){
			$no_kd = b_fetch('select kode from kode_usaha where id='.quote_smart($_REQUEST['kod']));
		}else{
			$no_kd = '00';
		}
		//$nomor = $PREFIX.''.$objek_pajak.''.'/'.$no_kd.'/'.sprintf('%05d',$nex_no).'/'.date('mY');
		$nomor = $objek_pajak.''.sprintf('%05d',$nex_no);
		
		//$nomor_surat = chunk_split(date('Ymd').''.$kode_kecamatan.''.$kode_desa.''.$no_izin,3,".");
		return $nomor;
	}

	function setNoKartu($idx,$tipe,$objek,$tgl){
		//$nmax = b_fetch('select max(nurut) from pendaftaran where jenis_pendaftaran='.quote_smart($tipe).' and objek_pdrd='.quote_smart($objek));
		//$nex_no = $nmax+1;
		$nex_no = $idx;
		switch($tipe){
			case 'PAJAK': $PREFIX = 'P'; break;
			case 'RETRIBUSI': $PREFIX = 'R'; break;
			default : $PREFIX = 'x';
		}
		
		if(stripos($tgl,'-')){
			list($day,$month,$year) = explode('-',$tgl);	
		}elseif(stripos($tgl,'/')){
			list($day,$month,$year) = explode('/',$tgl);
		}

		$nomor = sprintf('%05d',$nex_no).'/'.$PREFIX.'/'.sprintf('%02d',$month).'/'.$year;
		return $nomor;
	}

	function setNoNPWP($idx,$tipe,$objek,$pemohon){
		$nex_no = $idx;
		switch($tipe){
			case 'PAJAK': $PREFIX = 'P'; break;
			case 'RETRIBUSI': $PREFIX = 'R'; break;
			default : $PREFIX = 'x';
		}
		
		switch($objek){
			case 'PRIBADI': 
				$objek_pajak='1'; 
				$qy_desa='select b.lurah_kode, c.camat_kode from pemohon a join kelurahan b on b.lurah_id=a.id_desa join kecamatan c on c.camat_id=b.lurah_kecamatan where a.pemohon_id='.quote_smart($pemohon);
				break;
			case 'BU': 
				$objek_pajak='2'; 
				$qy_desa='select b.lurah_kode, c.camat_kode from badan_usaha a join kelurahan b on b.lurah_id=a.badan_id_desa join kecamatan c on c.camat_id=b.lurah_kecamatan where a.id='.quote_smart($pemohon);			
				break;
		}
		
		if($_REQUEST['kod']!=''){
			$no_kd = b_fetch('select kode from kode_usaha where id='.quote_smart($_REQUEST['kod']));
		}else{
			$no_kd = '00';
		}
		
		$tm_data = gcms_query($qy_desa);
		$rs = gcms_fetch_object($tm_data);
		
		//$nomor = $PREFIX.''.$objek_pajak.''.'/'.$no_kd.'/'.sprintf('%05d',$nex_no).'/'.date('mY');
		$nomor = $PREFIX.''.$objek_pajak.''.sprintf('%05d',$nex_no).''.sprintf('%02d',$rs->lurah_kode).''.sprintf('%02d',$rs->camat_kode);
		
		//$nomor_surat = chunk_split(date('Ymd').''.$kode_kecamatan.''.$kode_desa.''.$no_izin,3,".");
		return $nomor;
	}
	
	function getIdRekening($str){
		if($str!=''){
			list($kode,$nama) = explode('-',$str);
			$kode = trim($kode);
			$nama = trim($nama);
			$id = b_fetch('select id from rekening_kode where kode_rekening='.quote_smart($kode).' and nama_rekening='.quote_smart($nama));
		}else $id=0;
		return $id;
	}
	
	function getListRekening(){
		$qy = "select id,kode_rekening, nama_rekening from rekening_kode where tipe!='' and kelompok!='' and jenis!='' and objek!='' and rincian!='' ";
		$data = gcms_query($qy); $option='';
		while($rs = gcms_fetch_object($data)){
			$option.="<option value='$rs->id'>$rs->nama_rekening</option>";
		}
		return $option;
	}	
	
	function getNoKohir($tgl=''){
		if($tgl=='') $thn_qy=date('Y');
		else list($day,$month,$thn_qy) = explode('/',$tgl);
		$max = b_fetch('select max(kohir_no) from ref_kohir where kohir_thn='.quote_smart($thn_qy));
		$max++;
		return $max;
	}
	
	function updateNoKohir($tgl=''){
		if($tgl=='') $thn_qy=date('Y');
		else list($day,$month,$thn_qy) = explode('/',$tgl);	
		$cek = b_fetch('select max(kohir_no) from ref_kohir where kohir_thn='.quote_smart($thn_qy));
		if(!$cek){
			$cek++;
			gcms_query('insert into ref_kohir(kohir_thn,kohir_no) values('.quote_smart($thn_qy).','.quote_smart($cek).')');
		}else{
			$cek++;
			gcms_query('update ref_kohir set kohir_no='.quote_smart($cek++).' where kohir_thn='.quote_smart($thn_qy));
		}
	}	
	
	function getNoPenerimaan($type,$tgl=''){
		if($tgl=='') $thn_qy=date('Y');
		else list($day,$month,$thn_qy) = explode('/',$tgl);	
		$max = b_fetch('select max(penerimaan_no) from ref_penerimaan_no where jenis_pungutan='.quote_smart($type).' and thn_spt='.quote_smart($thn_qy));
		$max++;
		return $max;
	}
	
	function updateNoPenerimaan($type,$tgl=''){
		if($tgl=='') $thn_qy=date('Y');
		else list($day,$month,$thn_qy) = explode('/',$tgl);
		$cek = b_fetch('select max(penerimaan_no) from ref_penerimaan_no where thn_spt='.quote_smart($thn_qy).' and jenis_pungutan='.quote_smart($type));
		if(!$cek){
			$cek++;
			gcms_query('insert into ref_penerimaan_no(jenis_pungutan,thn_spt,penerimaan_no) values('.quote_smart($type).','.quote_smart($thn_qy).','.quote_smart($cek).')');
		}else{
			$cek++;
			gcms_query('update ref_penerimaan_no set penerimaan_no='.quote_smart($cek++).' where thn_spt='.quote_smart($thn_qy).' and jenis_pungutan='.quote_smart($type));
		}	
	}
	
	function getNoPenyetoran($tgl=''){
		if($tgl=='') $thn_qy=date('Y');
		else list($day,$month,$thn_qy) = explode('/',$tgl);	
		$max = b_fetch('select max(penyetoran_no) from ref_penyetoran_no where thn_setor='.quote_smart($thn_qy));
		$max++;
		return $max;
	}
	
	function updateNoPenyetoran($tgl=''){
		if($tgl=='') $thn_qy=date('Y');
		else list($day,$month,$thn_qy) = explode('/',$tgl);
		$cek = b_fetch('select max(penyetoran_no) from ref_penyetoran_no where thn_setor='.quote_smart($thn_qy));
		if(!$cek){
			$cek++;
			gcms_query('insert into ref_penyetoran_no(thn_setor,penyetoran_no) values('.quote_smart($thn_qy).','.quote_smart($cek).')');
		}else{
			$cek++;
			gcms_query('update ref_penyetoran_no set penyetoran_no='.quote_smart($cek++).' where thn_setor='.quote_smart($thn_qy));
		}	
	}	
	
	function getKeteranganSPT(){
		$qy ='select id,keterangan from ref_keterangan_spt';
		$result = gcms_query($qy); $option='';
		while($rs = gcms_fetch_object($result)){
			$option.="<option value='$rs->id'>$rs->keterangan</option>";
		}
		return $option;
	}
	
	function getViaPembayaran(){
		$qy ='select * from ref_pembayaran ';
		$result = gcms_query($qy); $option='';
		while($rs = gcms_fetch_object($result)){
			$option.="<option value='$rs->kode'>$rs->keterangan</option>";
		}
		return $option;	
	}
		
	function getExpired($val,$max,$format='d/m/Y'){

		list($hari, $bulan, $tahun) = explode('/',$val);
		$tahun = (int)$tahun;
		$bulan = (int)$bulan;		
		$hari = (int)$hari;
		
		$sabtu_minggu='';
		$hari_kerja='';
		$nlibur = '';
		$close=0;
		while($hari_kerja <= $max){
			
			if($close){
				break;
			}
		
			if($bulan > 12){
				$bulan=1;
				$tahun++;
			}
			$tgl_merah = b_fetch("select tgl_merah from ref_tanggal_merah where id=".$bulan);
		
			$nmerah = explode(".",$tgl_merah);
		
			$nhari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

			$start = !empty($hari) ? $hari+1 : 1; 
		
			while($start <= $nhari){

				$nday = date("w", $f_day = mktime(0, 0, 0, $bulan,$start, $tahun));
			
				if($nday == 0 || $nday == 6) {
					$sabtu_minggu++;
				}else if(in_array($start,$nmerah)){
					$nlibur++;
				}else{
					$hari_kerja++;
				}
				if($hari_kerja==$max){
					return date($format,mktime(0,0,0,$bulan,$tgl,$tahun));
					$close=1;
				}				
				$start++;
				$tgl = $start;
				if($close) break;
			}
			$bulan++;
			$hari=0;
		}
		
	}
	
?>