<?php
/* ===== fungsi-fungsi theme yang harus ada ================================================================== */

/* apa-apa yang harus dimasukkan ke dalam <HEAD> halaman utama */
function theme_head() {
    echo 
    '<script type="text/javascript" src="'.THEME_DIR.'/main.js"></script> '."\n".
    '<link rel="stylesheet" type="text/css" href="yui/menu/assets/skins/sam/menu.css"> '."\n".
    '<script type="text/javascript" src="yui/menu/menu-min.js"></script> '."\n".
	'<link rel="stylesheet" type="text/css" media="screen" href="script/jquery/themes/pepper-grinder/jquery-ui-1.7.2.custom.css" />'."\n";
}

/* dipanggil saat menampilkan halaman utama */
function theme_main_page() {
    page_header();

    /* memasukkan isi dari main page - harus ada */
	//include "menu_left.php";
	
	main_page_content();
    /* --------------------------------------- */
	include "menu_center.php";
    page_footer();
	
    echo
    '<div id="wnd_list"></div> '."\n".
    '<div id="footer"> '."\n";
    login_info();
    echo '</div> <!-- footer -->'."\n";
    echo '</div> <!-- container-page -->'."\n";
}

/* dipanggil saat menampilkan halaman login 
 * form login harus menggunakan action="login.php"
 */

function theme_login_page() {
    page_header();
    include "loginpage.php";
    page_footer();
}

/* apa-apa yang harus dimasukkan ke dalam <HEAD> form */
function theme_form_head() {
    echo '<script type="text/javascript" src="'.THEME_DIR.'/form.js"></script> '."\n";
}

/* dipanggil saat menampilkan form 
 *
 * <!-- DOCUMENT TITLE --> harus ada karena akan di-replace sebelum di-flush
 *
 */
function theme_form_page() {
    echo 
    '<script type="text/javascript"> '."\n".
    //'setInterval("form_check_size()", 500); '."\n".
    '</script> '."\n";

    echo '<div id="container-page"> '."\n";

    /* pastikan harus ada ------------------||---------------------|                                */
   //echo '<div id="form_header"><!-- DOCUMENT TITLE --></div> '."\n";
	echo '<div id="header-center"><div id="header-left"><div id="header-right"><!-- DOCUMENT TITLE --></div></div></div> '."\n";
	
	//Menu Pada Header
	//echo '<table width="100%" align="center"><tr><td>';
	echo '<div>';
	//echo '<table align="right"><tr>';navigatorMenus();echo'</tr></table><br><br><br><br><br><br>'; 
	
    /* memasukkan isi dari form - harus ada */
    //echo '<div align="center">';echo $_REQUEST['page']; echo '</div>';
	//$cek=gcms_query('SELECT a.CMENU,b.CGROUP FROM '.PREFIX.'FRONTMENUS a join '.PREFIX.'GROUPFRONTMENUS b ON a.NID_GROUPFRONTMENUS = b.NID where a.NID='.$_REQUEST['page'].'');
	//$xx=gcms_fetch_object($cek);
	//echo '<div align="left" style="padding-left:10px;">';echo $xx->CGROUP.'-'.$xx->CMENU; echo '</div>';
	//menu_get_init_by_mod($_REQUEST['mod']);
	form_page_content();
	//echo '</td></tr></table>';
	echo '</div> <!-- main_content --> '."\n";
	//echo '<table align="right"><tr>';navigatorMenus();echo'</tr></table>'; 
    /* --------------------------------------- */
    echo '</div> <!-- container-page --> '."\n";
}

/* ============================================================================================================= */

function page_header() {
    global $menus;
	$header_link = 'href="?index.php"';
    echo '<div id="container-page"> '."\n".
    '<div id="header-center"><div id="header-left"><div id="header-right"><a '.$header_link.'>'.WEB_TITLE.'</a></div></div></div> '."\n";
    include "menu.php";
    echo '<div id="content"> '."\n";
}

function page_footer() {
    echo '</div> <!-- content -->'."\n";
}
?>