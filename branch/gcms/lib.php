<?php
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
       
	    if(strtoupper($value)=='NULL'){
	   		$value = gcms_escape_string($value);
	    }else{
			$value = "'".gcms_escape_string($value)."'";
		}
		
   }else{
       $value= $value;
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
		else {
			$_SESSION['error_login'] = "username atau password tidak sesuai";
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
    $csql="select max(nid) from ".PREFIX."history where nid_users=".$_SESSION['nid_login'];
    $nid_histtory=b_fetch($csql);
    $csql="update ".PREFIX."history set dlogout=".gcms_now()." where nid=".$nid_histtory;
    gcms_query($csql);
    foreach ($_SESSION as $k => $v) unset($_SESSION[$k]);
//	if(REMEMBER) b_cookies_destroy();
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
        $csql="select cname, cuser, cemail
                    from ".PREFIX."users where nid='".$_SESSION['nid_login']."'";
        $nresult=gcms_query($csql);
        $ouser=gcms_fetch_object($nresult);
        $auser['name']=$ouser->cname;
        $auser['user']=$ouser->cuser;
        $auser['email']=$ouser->cemail;

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

function b_fmtAngka($arg1){
        return number_format($arg1, 2, ',', '.');
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
            $GLOBALS['bv_pathextension']=dirname($initfile)."/";
            define('PATHEXTENSION_STARTUP',dirname($initfile)."/");
            call_user_func($cfunction, "");
        }
    }
}


/* VERY INEFFICIENT - membaca semua isi library dari semua ekstensi terdaftar 
 * seharusnya hanya library terkait saja yang perlu dibaca
 * 
 * --- DI-DISABLE dan hanya load fungsi-fungsi yang terkait menunya saja ---
 */

// Baca Library tambahan 

$csql="select * from g_moduls";
$nresult=gcms_query($csql);
//echo $csql;
while($omoduls=gcms_fetch_object($nresult)) {
    $cfile=str_replace(".php",".lib.php",strtolower($omoduls->cpath));
    $cfile3= translate_modul_path($cfile);
    if ($cfile3) include($cfile3);
}

/* shortcut menu ( front menu ) */
function shortcutMenus() {
	$imgPath = 'default_theme/templates/images/header/';
	$url = "index.php?page=9";
	$array = array('NPWP Pribadi'=>'icon-48-pend_p.png','NPWP BU'=>'icon-48-pend_bu.png','Pendataan SPT'=>'icon-48-menulist.png','LHP'=>'icon-48-lhp.png','Penetapan Retribusi Pajak'=>'icon-48-penetapan.png','Penetapan STPD/STRD'=>'icon-48-penetapan_stprd.png','Penerimaan Setoran'=>'icon-48-setoran.png','Setoran ke Bank'=>'icon-48-setoran_bank.png','Persedian Awal Benda Berharga'=>'icon-48-bb_persediaan.png','Surat Permintaan Benda Berharga'=>'icon-48-bb_surat.png','Order Benda Berharga'=>'icon-48-bb_order.png','Tanda Terima Benda Berharga'=>'icon-48-bb_terima.png','Bukti Pengeluaran Benda Berharga'=>'icon-48-bb_bukti.png','Setoran Benda Berharga'=>'icon-48-bb_setor.png');

	foreach( $array as $key=>$val ) {
	//menu show in dashboard
	  $dump .= "<div class=\"panelMenu\"><a href=\"".$url."\" alt=\"shortcut menu\"><img class=\"iconMenu\" src=\"".$imgPath.$val."\" /><span class=\"labelMenu\">".$key."</span></a></div>";
	}
	//$dump.='<fieldset id=\"info-admin\"><label><span class=\"field-info\"></span></label></fieldset>';
	echo $dump;
}

function adminInfo() {
    $labelInfo=array('#','kode','nama','nip','jabatan','login terakhir');
	$dump = "<fieldset id=\"info-admin\">";
	foreach( $labelInfo as $key => $val ) {
		$dump .= "<label><span class=\"field-info\">".ucwords( $val )."</span></label>";
	}
	$dump .= "</fieldset>";
	echo $dump;
}
function titleInfo() {
	$title = 'Tes Page';
	$img = 'icon-48-pemda.png';
	$imgPath = 'default_theme/templates/images/header/';
	$dump = "<div id=\"titleInfo\"><img class=\"iconMenu\" src=\"".$imgPath.$img."\" /><h1>".$title."</h1></div>";
	return $dump;
}

function navigatorMenus() {
	$imgPath = 'default_theme/templates/images/toolbar/';
	$url = "index.php?page=9";
	$array ='';
	//$array .='<table><tr>';
	//$array .='<td>';
	//$array .='Baru'=>'icon-32-print.png';
	//$array .='</td><td>';
	//$array .='Edit'=>'icon-32-print.png';
	//$array .='</td><td>';
	//$array .='Hapus'=>'icon-32-print.png';
	//$array .='</td><td>';
	//$array .='Cetak'=>'icon-32-print.png';
	//$array .='</td></tr></table>';
	
	$array = array('Baru'=>'icon-32-print.png','Simpan'=>'icon-32-print.png','Edit'=>'icon-32-print.png','Hapus'=>'icon-32-print.png','Cetak'=>'icon-32-print.png','Keluar'=>'icon-32-print.png');
	
	$dump = "<div class=\"navContainer\">";
	//$dump .= titleInfo();
	foreach( $array as $key => $val ) {
		$dump .= "<div class=\"navMenu\" style='cursor:pointer;' ><a href=\"".$url."\" alt=\"navigator menu\"><td><img class=\"iconMenu\" onclick='func_".$key."()' style='cursor:pointer;' src=\"".$imgPath.$val."\" /><br><span class=\"labelMenu\" onclick='func_".$key."()' style='cursor:pointer;'>".$key."</span></td></a></div>";
	}
	$dump .= "<div style=\"clear:both;\"></div></div>";
	echo $dump;
}
function mainForm() {
	$dump = "";
	echo $dump;
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
function daftar_button(){
	$imgPath = 'default_theme/templates/images/toolbar/';
	$url = "index.php?page=9";
	$array ='';
	$array = array('Baru'=>'icon-32-new.png','Edit'=>'icon-32-edit.png','Hapus'=>'icon-32-delete6.png','Cetak'=>'icon-32-print.png','Keluar'=>'icon-32-close.png');
	
	$dump = "<div class=\"navContainer\">";
	//$dump .= titleInfo();
	foreach( $array as $key => $val ) {
		$dump .= "<div class=\"navMenu\" style='cursor:pointer;' ><a href=\"".$url."\" alt=\"navigator menu\"><td><img class=\"iconMenu\" onclick='func_".$key."()' style='cursor:pointer;' src=\"".$imgPath.$val."\" /><br><span class=\"labelMenu\" onclick='func_".$key."()' style='cursor:pointer;'>".$key."</span></td></a></div>";
	}
	$dump .= "<div style=\"clear:both;\"></div></div>";
	echo $dump;
	echo 
	'<script type="text/javascript"> '."\n".
	
	'function func_Baru(){'."\n".
	//alert('baru');
	'//gcms_open_form("form.php?page="+<?=$_REQUEST["page"]?>+"&action=tambah,"Master",600,800);'."\n".
	'//gcms_open_form("form.php?page="+<?=$_REQUEST["page"]?>+"&action=tambah","Master",600,800);'."\n".
	'gcms_open_form("form.php?page='.$_REQUEST['page'].'&action=tambah", "Master", 600, 800); '."\n".
	'//alert("coba");'."\n".
	'} '."\n".

    '</script> '."\n";
}
function button_form(){
	$imgPath = 'default_theme/templates/images/toolbar/';
	$url = "index.php?page=9";
	$array ='';
	$array = array('Baru'=>'icon-32-new.png','Simpan'=>'icon-32-save.png','Cetak'=>'icon-32-print.png','Keluar'=>'icon-32-close.png');
	
	$dump = "<div class=\"navContainer\">";
	//$dump .= titleInfo();
	foreach( $array as $key => $val ) {
		$dump .= "<div class=\"navMenu\" style='cursor:pointer;' ><a href=\"".$url."\" alt=\"navigator menu\"><td><img class=\"iconMenu\" onclick='func_".$key."()' style='cursor:pointer;' src=\"".$imgPath.$val."\" /><br><span class=\"labelMenu\" onclick='func_".$key."()' style='cursor:pointer;'>".$key."</span></td></a></div>";
	}
	$dump .= "<div style=\"clear:both;\"></div></div>";
	echo $dump;
}

function blkDate($field){
	if(stripos($field,'-')){
		sscanf($field,'%d-%d-%d',$d,$m,$y);
	}elseif(stripos($field,'/')){
		sscanf($field,'%d/%d/%d',$d,$m,$y);
	}
	if($field=='') return 'NULL';
	else return $d= $y.'/'.$m.'/'.$d;
}

function formatDate($tgl,$format='d/m/Y'){
	if($tgl=='' || is_null($tgl)){
		return '';
	}else{
		return date($format,strtotime($tgl));
	}
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
?>