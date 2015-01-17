<?php

	function setNomorPendaftaran($tipe,$objek){
			
		$CI = &get_instance();
		$CI->load->model('pendaftaran_model');
		$nurut = $CI->pendaftaran_model->get_no();
		$next_no = $nurut+1;
	
		switch($tipe){
			case 'PAJAK': $PREFIX = 'P'; break;
			case 'RETRIBUSI': $PREFIX = 'R'; break;
			default : $PREFIX = 'x';
		}
		//where jenis_pendaftaran='.$CI->db->escape($tipe).' and objek_pdrd='.$CI->db->escape($objek)
		
		switch($objek){
			case 'PRIBADI': $objek_pajak='1'; break;
			case 'BU': $objek_pajak='2'; break;
		}
		
		/*if($_REQUEST['kod']!=''){
			$no_kd = $CI->db->query('select kode from kode_usaha where id='.$CI->db->escape($_REQUEST['kod']));
		}else{
			$no_kd = '00';
		}*/
		
		//$nomor = $PREFIX.''.$objek_pajak.''.'/'.$no_kd.'/'.sprintf('%05d',$nex_no).'/'.date('mY');
		$nomor = $objek_pajak.''.sprintf('%05d',$next_no);
		
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
				//$qy_desa='select b.lurah_kode, c.camat_kode from pemohon a join kelurahan b on b.lurah_id=a.id_desa join kecamatan c on c.camat_id=b.lurah_kecamatan where a.pemohon_id='.$CI->db->escape($pemohon);
				break;
			case 'BU': 
				$objek_pajak='2'; 
				//$qy_desa='select b.lurah_kode, c.camat_kode from badan_usaha a join kelurahan b on b.lurah_id=a.badan_id_desa join kecamatan c on c.camat_id=b.lurah_kecamatan where a.id='.$CI->db->escape($pemohon);
				break;
		}
		/*$tm_data = gcms_query($qy_desa);
		$rs = gcms_fetch_object($tm_data);*/
				
		$CI = &get_instance();
		$CI->load->model('pendaftaran_model','data_model');
		
		$row = $CI->data_model->get_kode_lokasi($pemohon,$objek);		

		/*if($_REQUEST['kod']!=''){
			$no_kd = $CI->db->query('select kode from kode_usaha where id='.$CI->db->escape($_REQUEST['kod']));
		}else{
			$no_kd = '00';
		}*/
		//$nomor = $PREFIX.''.$objek_pajak.''.'/'.$no_kd.'/'.sprintf('%05d',$nex_no).'/'.date('mY');		
		$nomor = $PREFIX.''.$objek_pajak.''.sprintf('%05d',$nex_no).''.sprintf('%02d',$row->DESA_KODE).''.sprintf('%02d',$row->CAMAT_KODE);
		return $nomor;
		//return '888';
	}	
	
	function setNoKartu($idx,$tipe,$objek,$tgl){
		//$nmax = $CI->db->query('select max(nurut) from pendaftaran where jenis_pendaftaran='.$CI->db->escape($tipe).' and objek_pdrd='.$CI->db->escape($objek));
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
	
	function getExpired($val,$max,$format='d/m/Y')
	{
		$CI = &get_instance();	
		
		list($hari, $bulan, $tahun) = explode('/',$val);

		while($hari_kerja <= $max){
			
			if($close){
				break;
			}
		
			if($bulan > 12){
				$bulan=1;
				$tahun++;
			}
			//$tgl_merah = $CI->db->query("select tgl_merah from ref_tanggal_merah where id=".$bulan);
			$tgl_merah = $CI->db->query("select tgl_merah from ref_tanggal_merah where id=".$bulan)->row()->TGL_MERAH;
		
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

	function getViaPembayaran(){
		$CI = &get_instance();
		$qy ='select * from ref_pembayaran ';
		$result = $CI->db->query($qy);
		$option = "<option value=''></option>";
		if($result->num_rows()>0)
		{
			foreach($result->result() as $row)
			{	
				$option.="<option value='$row->KODE'>$row->KETERANGAN</option>";
			}
		}
		return $option;	
	}
	
	function getIdRekening($str){
		$CI = &get_instance();
		if($str!=''){
			list($kode,$nama) = explode('-',$str);
			$kode = trim($kode);
			$nama = trim($nama);
			//$id = $CI->db->query('select id from rekening_kode where kode_rekening='.$CI->db->escape($kode).' and nama_rekening='.$CI->db->escape($nama))->row()->ID;
			$id = $CI->db->query('select id from rekening_kode where kode_rekening='.$CI->db->escape($kode))->row()->ID;

		}else $id=0;
		return $id;
	}	
	
	function getNoKohir($tgl=''){
		$CI = &get_instance();	
		if($tgl=='') $thn_qy=date('Y');
		else list($day,$month,$thn_qy) = explode('/',$tgl);
		$max = $CI->db->query('select max(kohir_no) as maks from ref_kohir where kohir_thn='.$CI->db->escape($thn_qy))->row()->MAKS;
		$max++;
		return $max;
	}
	
	function updateNoKohir($tgl=''){
		$CI = &get_instance();		
		if($tgl=='') $thn_qy=date('Y');
		else list($day,$month,$thn_qy) = explode('/',$tgl);	
		$cek = $CI->db->query('select max(kohir_no) from ref_kohir where kohir_thn='.$CI->db->escape($thn_qy));
		if(!$cek){
			$cek++;
			$CI->db->query('insert into ref_kohir(kohir_thn,kohir_no) values('.$CI->db->escape($thn_qy).','.$CI->db->escape($cek).')');
		}else{
			$cek++;
			$CI->db->query('update ref_kohir set kohir_no='.$CI->db->escape($cek++).' where kohir_thn='.$CI->db->escape($thn_qy));
		}
	}		
	
	function getNoPenerimaan($type,$tgl=''){
		$CI = &get_instance();	
		if($tgl=='') $thn_qy=date('Y');
		else list($day,$month,$thn_qy) = explode('/',$tgl);			
		$max = $CI->db->query('select max(penerimaan_no) as maks from ref_penerimaan_no where jenis_pungutan='.$CI->db->escape($type).' and thn_spt='.$CI->db->escape($thn_qy))->row()->MAKS;
		$max++;
		return $max;
	}
	
	function updateNoPenerimaan($type,$tgl=''){
		$CI = &get_instance();	
		if($tgl=='') $thn_qy=date('Y');
		else list($day,$month,$thn_qy) = explode('/',$tgl);
		$cek = $CI->db->query('select max(penerimaan_no) as maks from ref_penerimaan_no where thn_spt='.$CI->db->escape($thn_qy).' and jenis_pungutan='.$CI->db->escape($type))->row()->MAKS;
		if(!$cek){
			$cek++;
			$CI->db->query('insert into ref_penerimaan_no(jenis_pungutan,thn_spt,penerimaan_no) values('.$CI->db->escape($type).','.$CI->db->escape($thn_qy).','.$CI->db->escape($cek).')');
		}else{
			$cek++;
			$CI->db->query('update ref_penerimaan_no set penerimaan_no='.$CI->db->escape($cek++).' where thn_spt='.$CI->db->escape($thn_qy).' and jenis_pungutan='.$CI->db->escape($type));
		}	
	}

	function getNoPenyetoran($tgl=''){
		$CI = &get_instance();	
		if($tgl=='') $thn_qy=date('Y');
		else list($day,$month,$thn_qy) = explode('/',$tgl);	
		$max = $CI->db->query('select max(penyetoran_no) as maks from ref_penyetoran_no where thn_setor='.$CI->db->escape($thn_qy))->row()->MAKS;
		$max++;
		return $max;
	}
	
	function updateNoPenyetoran($tgl=''){
		$CI = &get_instance();		
		if($tgl=='') $thn_qy=date('Y');
		else list($day,$month,$thn_qy) = explode('/',$tgl);
		$cek = $CI->db->query('select max(penyetoran_no) as maks from ref_penyetoran_no where thn_setor='.$CI->db->escape($thn_qy))->row()->MAKS;
		if(!$cek){
			$cek++;
			$CI->db->query('insert into ref_penyetoran_no(thn_setor,penyetoran_no) values('.$CI->db->escape($thn_qy).','.$CI->db->escape($cek).')');
		}else{
			$cek++;
			$CI->db->query('update ref_penyetoran_no set penyetoran_no='.$CI->db->escape($cek++).' where thn_setor='.$CI->db->escape($thn_qy));
		}	
	}	
?>