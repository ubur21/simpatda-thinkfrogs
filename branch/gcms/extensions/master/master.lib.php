<?php
	if(basename( __FILE__ )==basename( $_SERVER['PHP_SELF'])) die();
	function m_master_pemda(){
		if($_REQUEST['action']=="tambah"){
			include "pemda.php";
		}else{
			//include "list_pemda.php";
			include 'master_pemda.php';
		}
		//gcms_open_form('./docs/Manual ASB.pdf','Manual Book',800,900)
		//return "javascript:gcms_open_form('../extensions/master/master.php','TES',800,900)";
	}
	
 	function m_master_SatuanKerja(){
    	if($_REQUEST['action']=="tambah"){
			include "satuan_kerja.php";
		}else{
			include "list_satuan_kerja.php";
		}
	}
	
	function m_master_kodeusaha(){
		include 'kode_usaha.php';
	}
	
	function m_master_posanggaran(){
		include 'pos_anggaran.php';
	}	
	
	function m_master_pangkat(){
		include 'master_pangkat.php';
	}		
	
	function m_master_golongan(){
		include 'master_golongan.php';
	}			
	
	function m_master_jabatan(){
		include 'master_jabatan.php';
	}			
	
	function m_master_pejabat(){
		include 'master_pejabat.php';
	}			
	
	function m_master_kecamatan_list(){
			//echo "kecamatanList";
			if($_REQUEST['action']=="tambah"){
				include "kecamatan.php";
			}else{
				include "list_kecamatan.php";
			}
	}
	function m_master_kelurahan_list(){
			//echo "kecamatanList";
			if($_REQUEST['action']=="tambah"){
				include "kelurahan.php";
			}else{
				include "list_kelurahan.php";
			}
	}
	function m_master_anggaran_list(){
			//echo "kecamatanList";
			if($_REQUEST['action']=="tambah"){
				include "anggaran.php";
			}else{
				include "list_anggaran.php";
			}
	}
	function m_master_spt(){
			//echo "kecamatanList";
			if($_REQUEST['action']=="tambah"){
				include "keterangan_spt.php";
			}else{
				include "list_keterangan_spt.php";
			}
	}
	function m_master_DataPrinter(){
			//echo "kecamatanList";
			if($_REQUEST['action']=="tambah"){
				include "frm_printer.php";
			}else{
				include "list_printer.php";
			}
	}
?>