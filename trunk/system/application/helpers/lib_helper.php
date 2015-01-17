<?php

	function setNomorPendaftaran($tipe,$objek){
	
		$CI = &get_instance();
		$this->load->model('pendaftaran_model')

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
?>