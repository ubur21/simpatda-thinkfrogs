<?

/*  ===== VAKLIDASI, CHECKING, etc ================================================================================*/

/**
 * Anti SQL Injection
 *
 * @param string $csql
 * @return boolean
 */
function b_antisqlinjection($csql) {
	// daftarkan perintah-perintah SQL yang tidak boleh ada
	// dalam query dimana SQL Injection mungkin dilakukan
	$aforbidden = array (
	"insert", "select", "update", "delete", "truncate",
	"replace", "drop", " or ", ";", "#", "--", "=" );
	// lakukan cek, input tidak mengandung perintah yang tidak boleh
	$breturn=true;
	foreach($aforbidden as $cforbidden) {
		if(strrpos($input, strtolower($cforbidden))) {
			$breturn=false;
			break;
		}
	}
	return $breturn;
}

function quote_smart($value)
{
   $value = str_replace('"',"'",trim($value));
   
   // Stripslashes
   if (get_magic_quotes_gpc()){
       $value = stripslashes($value);
   }
   // Quote if not integer
   if (!is_numeric($value)) {
       //$value = "'" . gcms_escape_string(sqlstr($value)) . "'";
	   $value = "'".gcms_escape_string($value)."'";
   }else{
       $value="'".$value."'";
   }
   return $value;
}

/**
 * Digunakan untuk melakukan validasi terhadap format sebuah email
 *
 * @param string $cemail
 * @return TRUE/FALSE
 */
function b_emailcheck($cemail) {
	return (eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$",$cemail));
}

/**
 * Digunakan untuk melakukan validasi atas hak seorang user untuk menggunakan sebuah modul
 *
 * @param integer $nid
 * @param string $modulsname
 * @return TRUE/FALSE
 */
function b_modulgranted($nid_user, $modul) {
	$breturn=true;
	if($nid_user!=1) {
		$csql="select a.nid
			   from ".PREFIX."granted a
			   inner join ".PREFIX."moduls b on a.nid_moduls=b.nid
		       where a.nid_users='$nid_user' and b.nid='$modul'";
		$nresult=gcms_query($csql);
		if(!gcms_fetch_row($nresult)) $breturn=false;
	}
	return $breturn;
}


/* =================================================================================== */


/* ==== ALAT-ALAT BANTU SEPUTAR FILE =============================================================================== */


/**
 * Mendapatkan list file dari suatu direktori
 * default: ekstension
 *
 * @param string $cdir
 * @return Array
 */
function b_getlistfile($cdir="") {
	if($dirhandle=opendir($cdir)) {
		while ($cfile = readdir($dirhandle)) {
			if (substr($cfile, 0, 1) != ".") {
				if(is_dir($cdir."/".$cfile)) {
					$alistfiletemp=b_getlistfile($cdir."/".$cfile);
					if(count($alistfiletemp)>0)
						foreach($alistfiletemp as $cfiletemp) $alistfile[]=$cfiletemp;
				} else {
        			$alistfile[]=$cdir."/".$cfile;
				}
			}
		}
	}
	return $alistfile;
}

/**
 * Mendapatkan extension file
 *
 * @param string $cfilename
 * @return string
 */
function b_getextension($cfilename) {
	return substr(strrchr($cfilename, "."), 1);
}


/* =================================================================================== */


/* ==== ALAT-ALAT BANTU SEPUTAR SQL =============================================================================== */

/**
 * Mendapatkan nilai dari querry
 *
 * @param string $csql
 * @return string
 */
function b_fetch($csql) {
    $csql=str_replace("from"," as xxxxxxxxxx from ",$csql);
    $nresult=gcms_query($csql);
    $oreturn=gcms_fetch_object($nresult);
    return $oreturn->xxxxxxxxxx;
}

/* =================================================================================== */


/* ==== ALAT-ALAT BANTU SEPUTAR MODUL & EXTENSION ================================================= */

/**
 * Baca keterangan dari file init
 *
 * @param string $cfile
 */
function b_readinit($cfile) {
	if(file_exists($cfile)){
		if (!is_file ($cfile)) return "" ;
		$handle=fopen($cfile,"r");
		$cread = fread($handle, filesize($cfile));
		fclose($handle);
		$aline=explode("\n",$cread);
		foreach($aline as $craw) {
			$araw=explode("::",$craw);
        	if(trim($araw[0])=="*/") break;
        	else if(trim($araw[0])<>"/*")
        		$ainfo[strtolower(trim($araw[0]))]=trim($araw[1]);
		}
		return $ainfo;
	}
}

/**
 * Check apakah sebuah extension bisa di aktifkan dilihat dari dependency-nya
 *
 * @param unknown_type $cpath
 */
function b_checkenabledependency($cpath) {
	$breturn=true;
	$ainfo=b_readinit(str_replace(".php",".init.php",strtolower($cpath)));
	if(trim($ainfo['dependency'])<>"") {
		$adependency=explode(",",$ainfo['dependency']);
		$csql="select cpath from ".PREFIX."moduls";
		$nresult=gcms_query($csql);
		while($opath=gcms_fetch_object($nresult)) {
      $cpath = strtolower($opath->cpath);
      $mod = basename($cpath);
      $mod = substr($mod, 0, strrpos($mod, ".php"));
	  $amoduls[]=$mod;
			$ainfo=b_readinit(str_replace(".php",".init.php",$cpath));
			$amoduls[]=trim(strtolower($ainfo['name']));
		}

		//echo $amoduls;
		//if (!is_file ($amoduls)) return "" ;
		foreach($adependency as $cmodul)
			if(in_array(trim(strtolower($cmodul)), $amoduls)) $nexist++;
		if($nexist<>count($adependency)) $breturn=false;
	}
	return $breturn;
}

/**
 * Check apakah sebuah extension bisa di nonaktifkan dilihat dari dependency-nya
 *
 * @param unknown_type $cpath
 */
function b_checkdisabledependency($cpath) {
	$breturn=true;
	$ainfo=b_readinit(str_replace(".php",".init.php",strtolower($cpath)));
	$cmodul=trim(strtolower($ainfo['name']));
	$csql="select cpath from ".PREFIX."moduls where cpath<>'".$cpath."'";
	$nresult=gcms_query($csql);
	while($opath=gcms_fetch_object($nresult)) {
		$ainfo=b_readinit(str_replace(".php",".init.php",strtolower($opath->cpath)));
		if(trim($ainfo['dependency'])<>"")
			$adependecy[]=trim(strtolower($ainfo['dependency']));
	}
	if(count($adependecy)>0) {
		foreach($adependecy as $cdependecy)
			$amoduls=explode(",",$cdependecy);
		foreach($amoduls as $ctemp)
			$amoduls2[]=trim($ctemp);
		if(in_array($cmodul,$amoduls2)) $breturn=false;
	}
	return $breturn;
}

// cari modul path
function translate_modul_path($cfile) {
			$cfile=str_replace("../", "", $cfile);
			$cfile=str_replace("./", "", $cfile);

			$cfile3 = ROOT_LOCATION.$cfile;
			if(!file_exists($cfile3)) {
  			$cfile3 = ROOT_LOCATION.$cfile;
	  		if(!file_exists($cfile3)) {
    			$cfile3 = ROOT_LOCATION."admin/".$cfile;
		    	if(!file_exists($cfile3)) {
      			$cfile3 = ROOT_LOCATION."admin/".$cfile;
		    	  if(!file_exists($cfile3)) {
              $cfile3 = "";
            }
          }
        }
      }
      return $cfile3;
}

/**
 * Mengambil path directory tempat sebuah extension aktif berada
 *
 * @param string $cfileinclude
 */
function b_pathinclude($cfileinclude){
	$csql="select cpath from ".PREFIX."moduls where nid='".$_REQUEST['csub']."'";
	include(dirname(b_fetch($csql))."/".$cfileinclude);
}


/* =================================================================================== */


/* ==== ALAT-ALAT BANTU LAIN-LAIN =============================================================================== */


/**
 * Mengembalikan parameter index.php (admin)
 *
 * @return string
 */
function b_urlact() {
	return $_SERVER['PHP_SELF']."?cact=".$_REQUEST['cact']."&csub=".$_REQUEST['csub'];
}

/**
 * Menghancurkan cookies - OBSOLETE
 *
 */
 /*
function b_cookies_destroy() {
	setcookie('a',"",time()-3600);
	setcookie('b',"",time()-3600);
	setcookie('c',"",time()-3600);
	setcookie('d',"",time()-3600);
}
*/

/**
 * Membuat cookies -- OBSOLETE
 *
 */
/*
function b_cookies_create() {
	b_include('config.php');
	if(REMEMBER) {
		setcookie('a',base64_encode($_POST['cpass']),time()+60*60*24*30);
		setcookie('b',base64_encode($_POST['cuser']),time()+60*60*24*30);
		setcookie('c',md5($_POST['cpass']),time()+60*60*24*30);
		setcookie('d',md5($_POST['cuser']),time()+60*60*24*30);
	}
}
*/

/**
 * Cek keberadaan cookies -- OBSOLETE
 *
 * @return boolean
 *
 */
 /*
function b_cookies_check() {
	b_include('config.php');
	if(REMEMBER) {
   		if(isset($_COOKIE['a']) and isset($_COOKIE['b']) and isset($_COOKIE['c']) and isset($_COOKIE['d'])) {
     		if(md5(base64_decode($_COOKIE['a']))==$_COOKIE['d'] and md5(base64_decode($_COOKIE['b']))==$_COOKIE['d'])
     			$bcookies=true;
     		else {
   	    		b_cookies_destroy();
   	    		$bcookies=false;
     		}
   		}
	}
	return $bcookies;
}
*/

/* ======== HAL-HAL TERKAIT USER DAN LOGIN ================================================================= */

/**
 * Login
 *
 * @param string $cuser
 * @param string $cuser
 * @param boolean $bcookies
 *
 * @return boolean
 *
 */
function b_login($cuser, $cpass) {
	$breturn=false;
	if(b_antisqlinjection($cuser) and b_antisqlinjection($cpass)) {
/*
    if(b_cookies_check())
	   		$csql='select nid from '.PREFIX	.'users where cuser=\''.base64_decode($_COOKIE['b']).'\' and cpass=\''.md5(base64_decode($_COOKIE['a'])).'\'';
		else
*/
    $csql='select nid from '.PREFIX	.'users where cuser=\''.$cuser.'\' and cpass=\''.md5($cpass).'\'';
		$nid=b_fetch($csql);
		if(trim($nid)<>'') {
			if(isset($_SESSION['nid_login'])) unset($_SESSION['nid_login']);
				$_SESSION['nid_login']=$nid;
			$csql="insert into ".PREFIX."history (nid_users, dlogin, cip) ".
					"values ('$nid', ".gcms_now().", '".$_SERVER["REMOTE_ADDR"]."')";
			gcms_query($csql);
			$breturn=true;
		}
//		if(REMEMBER) b_cookies_create();
	}
	return $breturn;
}

/**
 * logout
 *
 */
function b_logout() {
	b_include('config.php');
	$csql="select max(nid) from ".PREFIX."history where nid_users='".$_SESSION['nid_login']."'";
	$nid_histtory=b_fetch($csql);
	$csql="update ".PREFIX."history set dlogout=".gcms_now()." where nid='$nid_histtory'";
	gcms_query($csql);
  foreach ($_SESSION as $k => $v) unset($_SESSION[$k]);
 // if(REMEMBER) b_cookies_destroy();
  session_destroy();
}

/**
 * Cek apakah sudah login atau belum
 *
 * @return boolean
 *
 */
function b_logged() {
	return isset($_SESSION['nid_login']);
}

/**
 * Mendapatkan ID user yang sedang login
 *
 * @return number ID
 *
 */
function b_getuserlogin() {
	if(b_logged())
		$nreturn=$_SESSION['nid_login'];
	else
		$nreturn=0;
	return $nreturn;
}


/**
 * Cek apakah user memiliki akses sebagai admin
 *
 * @param integer $nid
 * @return boolean
 */
function b_admin($nid) {
	$csql="select nstatus from ".PREFIX."users where nid='$nid'";
	(b_fetch($csql)==4)?$breturn=true:$breturn=false;
    return $breturn;
}

/**
 * Mendapatkan info user
 *
 * @return aray (name,user,email)
 */
function b_userinfo() {
	if(b_logged()) {
		$csql="select spbu_id,cname, cuser, cemail
				from ".PREFIX."users where nid='".$_SESSION['nid_login']."'";
		$nresult=gcms_query($csql);
		$ouser=gcms_fetch_object($nresult);
		$auser['name']=$ouser->cname;
		$auser['user']=$ouser->cuser;
		$auser['email']=$ouser->cemail;
		$auser['spbu_id']=$ouser->spbu_id;

		$csql="select max(nid) from ".PREFIX."history where nid_users='".$_SESSION['nid_login']."'";
		$nid_histtory=b_fetch($csql);
    if (!$nid_histtory) $nid_histtory = 0;
		$csql="select dlogin as dlogin_now
				from ".PREFIX."history where nid='$nid_histtory'";
		$nresult=gcms_query($csql);
		$ouser=gcms_fetch_object($nresult);
		$auser['logged']= strftime("%a, %B %d, %Y %T", strtotime($ouser->dlogin_now));

		$csql="select max(nid) from ".PREFIX."history where nid_users='".$_SESSION['nid_login']."' and nid<>'$nid_histtory'";
		$nid_histtory=b_fetch($csql);
    if (!$nid_histtory) $nid_histtory = 0;
		$csql="select dlogin as dlogin_last, dlogout as dlogout_last, cip
				from ".PREFIX."history where nid='$nid_histtory'";
		$nresult=gcms_query($csql);
		$ouser=gcms_fetch_object($nresult);

		$auser['lastlogin']=strftime("%a, %B %d, %Y %T", strtotime($ouser->dlogin_last));
		$auser['lastlogout']=strftime("%a, %B %d, %Y %T", strtotime($ouser->dlogout_last));
		$auser['ip']=$ouser->cip;
		return $auser;
	}
}


/* ======================================================================================================== */



/* =========== include dan require : berhubungan dengan file lain ============================================ */

/**
 * Sama dengan fungsi Include, tetapi melakukan pengecekan file exist terlebih dahulu
 *
 * @param string $cfile
 */
function b_include($cfile) {
	if (!is_file ($cfile)) return "" ;
	if(file_exists($cfile))
	
		include($cfile);
}

/**
 * Sama dengan fungsi require_once, tetapi melakukan pengecekan file exist terlebih dahulu
 *
 * @param string $cfile
 */
function g_require_once($cfile) {
	if(file_exists($cfile))
		require_once($cfile);
}

/* ============================================================================================== */

/* ===== CONFIG (?) ================================================================================= */

/**
 * Mengambil konfigurasi
 *
 * @return unknown
 * @param variant $config
 */
function b_getconfig($config) {
	$csql="select * from ".PREFIX."config where cname='".$config."'";
	$nreturn=gcms_query($csql);
	$oconfig=gcms_fetch_object($nreturn);
	if(strtolower(substr($config,0,1))=='c') $return=$oconfig->cconfig;
	if(strtolower(substr($config,0,1))=='n') $return=$oconfig->nconfig;
	if(strtolower(substr($config,0,1))=='d') $return=$oconfig->dconfig;
	return $return;
}

/**
 * Menyimpan config
 *
 * @param string $config
 * @param variant $value
 */
function b_writeconfig($config, $value) {
	$csql="select * from ".PREFIX."config where cname='".$config."'";
	$nresult=gcms_query($csql);
	if(!gcms_fetch_rows($nresult)) {
		switch(strtolower(substr($config,0,1))) {
			case 'c'		: $csql="insert into ".PREFIX."config (cname, cconfig) values ('$config','$value')";
						  	  break;
			case 'n'		: $csql="insert into ".PREFIX."config (cname, nconfig) values ('$config','$value')";
						      break;
			case 'd'		: $csql="insert into ".PREFIX."config (cname, dconfig) values ('$config','$value')";
						      break;
		}
	} else {
		switch(strtolower(substr($config,0,1))) {
			case 'c'		: $csql="update ".PREFIX."config set cconfig='$value' where cname='".$config."'";
						  	  break;
			case 'n'		: $csql="update ".PREFIX."config set nconfig='$value' where cname='".$config."'";
						      break;
			case 'd'		: $csql="update ".PREFIX."config set dconfig='$value' where cname='".$config."'";
						      break;
		}
	}
	gcms_query($csql);
}

/**
 * hapus config
 *
 * @param string $config
 */
function b_deleteconfig($config) {
	$csql="delete from ".PREFIX."config where cname='".$config."'";
	gcms_query($csql);
}

/**
 * SubFunction: m_doselect()
 * Main module: Delivery Order
 * Create combobox, tag select
 *
 * @param string  <b>$cvarname</b> : variable's name, select tag's name
 * @param variant <b>$cprevval</b> : previous value
 * @param string  <b>$ctblname</b> : table's name
 * @param string  <b>$cfldkeys</b> : field key
 * @param string  <b>$cwhere</b>   : where clause
 * @param string  <b>$cfldviews</b>: fields to be viewed, seperated by comma
 * @param string  <b>$ajaxdo</b>   : ajax function in string, if there, ajax will execute on onchange event
 * @return none
 */
function b_htmlselect() {
	$narg = func_num_args();
	$aarg = func_get_args();
	if( $narg>3 ) {
	$cvarname = $aarg[0];
	$cprevval = $aarg[1];
	$ctblname = $aarg[2];
	$cfldkeys = $aarg[3];
	$cwhere   = ($narg>4)?$aarg[4]:'';
	$cfldview = explode(",", ($narg>5)?$aarg[5]:$aarg[3]);
	$cajaxdo  = "";
	if( $narg>6 ) $cajaxdo = $aarg[6];
		?><select name="<?=$cvarname;?>" id="<?=$cvarname;?>" style="width: 155px;"<?=( trim($cajaxdo)=="" )?"":" onchange=\"".$cajaxdo."\"";?>><?
		?><option style="width:315px" value=""<?=(trim($cprevval)=="")?" selected":"";?>></option><?
		$csqlselect = "SELECT * FROM ".PREFIX.$ctblname;
		if( trim($cwhere)!="" ) {
			$csqlselect .= " WHERE ".$cwhere;
		}
		// echo $csqlselect.'<br>';
		$nsqlselect = gcms_query( $csqlselect );
    while( $osqlselect = gcms_fetch_object( $nsqlselect ) ) {
			$strfldview = $cfldview;
			?><option value="<?=$osqlselect->$cfldkeys;?>" style="width:100%"<?=(($osqlselect->$cfldkeys==$cprevval)?" selected":"");?>><?
			$nfldcount = count( $cfldview );
			$nfldloop = 0;
			for( $nfldloop=0; $nfldloop<$nfldcount; $nfldloop++ ) {
				$xfldview = $cfldview[$nfldloop];
				echo (($nfldloop>0)?" - ":"").$osqlselect->$xfldview;
			}
			?></option><?
		}
		?></select><?
	}
}


/* ==== ALAT BANTU SEPUTAR ANGKA ==================================================================== */

function terbilang_in($nx) {
	$nx = abs($nx);
	$cangka = array("", "satu", "dua", "tiga", "empat", "lima",
					"enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	if ($nx <12) {
		$ctemp = " ". $cangka[$nx];
	} else if ($nx <20) {
		$ctemp = terbilang_in($nx - 10). " belas";
	} else if ($nx <100) {
		$ctemp = terbilang_in($nx/10)." puluh". terbilang_in($nx % 10);
	} else if ($nx <200) {
		$ctemp = " seratus" . terbilang_in($nx - 100);
	} else if ($nx <1000) {
		$ctemp = terbilang_in($nx/100) . " ratus" . terbilang_in($nx % 100);
	} else if ($nx <2000) {
		$ctemp = " seribu" . terbilang_in($nx - 1000);
	} else if ($nx <1000000) {
		$ctemp = terbilang_in($nx/1000) . " ribu" . terbilang_in($nx % 1000);
	} else if ($nx <1000000000) {
		$ctemp = terbilang_in($nx/1000000) . " juta" . terbilang_in($nx % 1000000);
	} else if ($nx <1000000000000) {
		$ctemp = terbilang_in($nx/1000000000) . " milyar" . terbilang_in(fmod($nx,1000000000));
	} else if ($nx <1000000000000000) {
		$ctemp = terbilang_in($nx/1000000000000) . " trilyun" . terbilang_in(fmod($nx,1000000000000));
	}
	return $ctemp;
}

function terbilang_en($nx, $bpuluh=false) {
	$nx = abs($nx);
	if($bpuluh)
		$cangka=array("", "", "twenty", "thirty", "fourty", "fifty", "sixty", "seventy", "eighty", "ninety");
	else
		$cangka = array("", "one", "two", "three", "four", "five",
					"six", "seven", "eight", "nine", "ten", 
					"eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen",
					"seventeen", "eighteen", "nineteen");
	if ($nx <20) {
		$ctemp = " ". $cangka[$nx];
	} else if ($nx <100) {
		$ctemp = terbilang_en($nx/10, true). terbilang_en($nx % 10);
	} else if ($nx <1000) {
		$ctemp = terbilang_en($nx/100)." hundred" . terbilang_en($nx % 100);
	} else if ($nx <1000000) {
		$ctemp = terbilang_en($nx/1000) . " thousand" . terbilang_en($nx % 1000);
	} else if ($nx <1000000000) {
		$ctemp = terbilang_en($nx/1000000) . " milyard" . terbilang_en($nx % 1000000);
	} else if ($nx <1000000000000) {
		$ctemp = terbilang_en($nx/1000000000) . " billion" . terbilang_en(fmod($nx,1000000000));
	} else if ($nx <1000000000000000) {
		$ctemp = terbilang_en($nx/1000000000000) . " trillion" . terbilang_en(fmod($nx,1000000000000));
	}
	return $ctemp;
}

function b_tulisbilangan($nx, $clang="in")
 {
	if($clang=="in") {
		if($nx<0) $chasil = "minus ". trim(terbilang_in($nx));
		else $chasil = trim(terbilang_in($nx));
	} elseif($clang="en") {
		if($nx<0) $chasil = "negative ". trim(terbilang_en($nx));
		else $chasil = trim(terbilang_en($nx));
	}
	return ucwords($chasil);
}

/* =================================================================================================================== */

/* ---- extension menu dimasukkan ke dalam fungsi utama ---------- */

require_once("lib_menu.php");



/* ==== OBSOLETE - to be deleted ===================================================================================== */

/**
 * Fungsi untuk memanggil fungsi-fungsi start-up
 *
 */
function b_startup() {
	$afunction=get_defined_functions();
	foreach($afunction['user'] as $cfunction) {
		if(strtolower(substr($cfunction,0,2))=="s_") {
			if(function_exists($cfunction))
				$ctempath=PATHEXTENSION;
				$ctemp=substr($cfunction,2,strlen($cfunction)-2);
				$csql="select cpath from ".PREFIX."moduls where cpath like '%".substr($ctemp,0,strpos($ctemp,"_")).".php'";
				$initfile=str_replace(".php",".init.php",strtolower(b_fetch($csql)));
        $initfile= translate_modul_path($initfile);
				$GLOBALS['bv_pathextension']=dirname($initfile)."/";
				define('PATHEXTENSION_STARTUP',dirname($initfile)."/");
				call_user_func($cfunction, "");
		}
	}
}

function setSiscoyUnit($unit){
	switch($unit){
		case '6' : $coa_siscos_unit =  '1032020000'; break;
		case '7' : $coa_siscos_unit =  '1032020001'; break;
		case '8' : $coa_siscos_unit =  '1032020002'; break;
		case '9' : $coa_siscos_unit =  '1032020003'; break;
		case '10' : $coa_siscos_unit = '1032020004'; break;
		case '11' : $coa_siscos_unit = '1032020006'; break;
		case '12' : $coa_siscos_unit = '1032020008'; break;
		case '13' : $coa_siscos_unit = '1032020007'; break;
		case '14' : $coa_siscos_unit = '1032020017'; break;
		case '15' : $coa_siscos_unit = '1032020009'; break;
		case '16' : $coa_siscos_unit = '1032020015'; break;
		case '17' : $coa_siscos_unit = '1032020018'; break;
		case '18' : $coa_siscos_unit = '1032020014'; break;
		case '19' : $coa_siscos_unit = '1032020022'; break;
		case '20' : $coa_siscos_unit = '1032020016'; break;
		case '21' : $coa_siscos_unit = '1032020019'; break;
		case '22' : $coa_siscos_unit = '1032020010'; break;
		case '23' : $coa_siscos_unit = '1032020012'; break;
		case '24' : $coa_siscos_unit = '1032020013'; break;
		case '25' : $coa_siscos_unit = '1032020021'; break;
		case '26' : $coa_siscos_unit = '1032020025'; break;
		case '27' : $coa_siscos_unit = '1032020026'; break;
		case '28' : $coa_siscos_unit = '1032020027'; break;
		case '29' : $coa_siscos_unit = '1032020032'; break;
		case '30' : $coa_siscos_unit = '1032020030'; break;
		case '31' : $coa_siscos_unit = '1032020031'; break;
		case '32' : $coa_siscos_unit = '1032020034'; break;
		case '33' : $coa_siscos_unit = '1032020033'; break;
		case '34' : $coa_siscos_unit = '1032020040'; break;
		case '35' : $coa_siscos_unit = '1032020037'; break;
		case '36' : $coa_siscos_unit = '1032020038'; break;
		case '37' : $coa_siscos_unit = '1032020035'; break;
		case '38' : $coa_siscos_unit = '1032020021'; break;
		case '39' : $coa_siscos_unit = '1032020039'; break;
		case '40' : $coa_siscos_unit = '1032020041'; break;
		case '41' : $coa_siscos_unit = '1032020042'; break;
		case '44' : $coa_siscos_unit = '1032020045'; break;
		case '46' : $coa_siscos_unit = '1032020029'; break;
		case '100' : $coa_siscos_unit = '1032020020'; break;
		case '101' : $coa_siscos_unit = '1032020043'; break;
		case '102' : $coa_siscos_unit = '1032020043'; break;
		case '103' : $coa_siscos_unit = '1032020043'; break;
		case '104' : $coa_siscos_unit = '1032020043'; break;
		case '105' : $coa_siscos_unit = '1032020043'; break;
		case '106' : $coa_siscos_unit = '1032020043'; break;
		case '107' : $coa_siscos_unit = '1032020005'; break;
		case '108' : $coa_siscos_unit = '1032020011'; break;
		case '109' : $coa_siscos_unit = '1032020024'; break;
		case '110' : $coa_siscos_unit = '1032020023'; break;
		
	}
	return $coa_siscos_unit;
}


/* VERY INEFFICIENT - membaca semua isi library dari semua ekstensi terdaftar 
 * seharusnya hanya library terkait saja yang perlu dibaca
 * 
 * --- DI-DISABLE dan hanya load fungsi-fungsi yang terkait menunya saja ---
 */

// Baca Library tambahan 

/*
$csql="select * from ".PREFIX."moduls";
$nresult=gcms_query($csql);
while($omoduls=gcms_fetch_object($nresult)) {
	$cfile=str_replace(".php",".lib.php",strtolower($omoduls->cpath));
  $cfile3= translate_modul_path($cfile);
  if ($cfile3) include($cfile3);
}
*/
function sqlvalue($val, $quote=true)
{
  if ($quote)
    $tmp = sqlstr($val);
  else
    $tmp = $val;
  if ($tmp == "")
    $tmp = "NULL";
  elseif ($quote)
    $tmp = "'".$tmp."'";
  return $tmp;
}

function showMultiLine($data){

	$find= array("\r\n","\r","\n","\n\r");
	$replace = "\\n";
	if(!empty($data)){
		$data = str_replace($find,$replace,$data);
		return str_replace('\\','\\\\',$data);
	}else{
		return '';
	}
}

function replaceEscapeCar($data){

	$find= array("\r\n","\r","\n","\n\r");
	$replace = "\\n";
	if(!empty($data)){
		return str_replace($find,$replace,$data);
	}else{
		return '';
	}
}

function sqlstr($val)
{
  if(strlen($val)>1){
	if(strlen($val)==substr_count($val," ")){
		return str_replace("'", "''", $val);
	}else{
		return str_replace("'", "''", trim($val));
	} 
  }else{
  	return str_replace("'", "''", $val);
  }
}

function html_calendar($id){
	echo '
		<img src="./images/calbtn.gif" alt="Calendar" id="show_'.$id.'" align="middle" style="cursor:pointer;vertical-align:middle;">
   		<div id="container_'.$id.'">
           <div class="hd">Tanggal '.$id.'</div>
      	   <div class="bd">
              <div id="cal_'.$id.'"></div>
      	   </div>
        </div>';
}

function format_currency($value){
    $formated = 'RP '.ereg_replace(',', '.', number_format($value));
    return $formated;
}

function formatDate($val,$f=1){
	if($f==1){
		$temp = explode("/",$val);
		return $temp[2].'-'.$temp[1].'-'.$temp[0];
	}
    if($f==2){
        $temp = explode("-",$val);
		return $temp[2].'/'.$temp[1].'/'.$temp[0];
    }
}
?>