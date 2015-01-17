<?
//session_start();
/* ===== fungsi-fungsi theme yang harus ada ================================================================== */

/* apa-apa yang harus dimasukkan ke dalam <HEAD> halaman utama */
function theme_head() {
  echo 
    '<script type="text/javascript" src="'.THEME_DIR.'/main.js"></script> '."\n".
    '<link rel="stylesheet" type="text/css" href="yui/build/menu/assets/skins/sam/menu.css"> '."\n".
    '<script type="text/javascript" src="yui/build/menu/menu.js"></script> '."\n".
    '<style type="text/css"> '."\n".
    '#gcms_main_menu { '."\n".
    '  margin: 0 0 10px 0; '."\n".
    '} '."\n".
    '</style> '."\n";
}

function header_view(){
$dir = THEME_DIR; 
if (b_logged()){
	$ainfouser = b_userinfo();
	$user_name = $ainfouser['name'];
	$spbu_no = $_SESSION['spbu_no'];
	$branch = $_SESSION['branch_name'];
	if($user_name == "Administrator"){
		$header = "&nbsp;&nbsp;Management ".$user_name."-Testing Server" ;
	}elseif(!$spbu_no){	
		$header = "&nbsp;&nbsp;Divisi Head Office - ".$user_name."-Testing Server";
	}else{
		$header = "&nbsp;&nbsp;Unit SPBU : ".$spbu_no." - ".$user_name."-Testing Server" ;
	}	
}
$content=<<<IDI
<TABLE WIDTH="1013" BORDER=0 CELLPADDING=0 CELLSPACING=0 style="padding:0px;margin:0px">
	<TR>
		<TD WIDTH=19 HEIGHT=20 style="background-image:url($dir/images/model1a_01.png)"></TD>
		<TD HEIGHT=20 style="background-image:url($dir/images/model1a_02.png)"></TD>
		<TD WIDTH=19 HEIGHT=20 style="background-image:url($dir/images/model1a_03.png)"></TD>
	</TR>
	<TR>
		<TD WIDTH=19 HEIGHT=155 style="background-image:url($dir/images/model1a_04.png)"></TD>
		<!--<TD bgcolor="#EFEFEF" id='header' style="background-image:url($dir/images/.png);"></TD>style="background-image:url($dir/images/xxx.png);background-color:#0099CC;background-image:url($dir/images/tick.png)" $header -->
		<TD style="" height="155" valign="bottom">
			
				<embed src="$dir/images/header3.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="975" height="155"></embed>
	
		</TD><!-- -->
		<TD WIDTH=19 HEIGHT=155 style="background-image:url($dir/images/model1a_06.png)"></TD>
	</TR>
	<TR>
	<TD WIDTH=19 HEIGHT=11 style="background-image:url($dir/images/model1a_07.png)">
	</TD>
	<TD HEIGHT=11 style="background-image:url($dir/images/model1a_08.png)">
	</TD>
	<TD WIDTH=19 HEIGHT=11 style="background-image:url($dir/images/model1a_09.png)">
	</TD>
</TR>
</TABLE>\n
IDI;
echo $content;
}

$extra_style_daftar=
'<style type="text/css">'."\n".
'#div_xxxx{'."\n".
'background-color:#EFEFEF;'."\n".
'}'."\n".
'#div_xxxx .yui-pg-container{'."\n".
'background-color:#DAD8C6;'."\n".
'margin:0;'."\n".
'padding:5px;'."\n".
'}'."\n".
'#filter_xxxx{'."\n".
'background-color:#F2F1E6;'."\n".
'}'."\n".
'.daftar_buttons{'."\n".
'background-attachment:scroll;'."\n".
'background-image:url('.THEME_DIR.'/images/form_header.png);'."\n".
'background-repeat:repeat;'."\n".
'} '."\n".
'</style>'."\n";

$extra_style_entri=
'<style type="text/css">'."\n".
'.entri_buttons{'."\n".
'background-attachment:scroll;'."\n".
'background-image:url('.THEME_DIR.'/images/form_header.png);'."\n".
'background-repeat:repeat;'."\n".
'border-top:none;'."\n".
'} '."\n".
'#content{'."\n".
'background-color:#F2F1E6;'."\n".
'} '."\n".
'#kanvas{'."\n".
'background-color:#ECEAD8;'."\n".
'padding:10;'."\n".
'height:90%;'."\n".
'border:1px solid #cccccc;'."\n".
'-moz-border-radius:10;'."\n".
'} '."\n".
'</style>'."\n";

$extra_anim='
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="35" height="30">
  <param name="movie" value="'.THEME_DIR.'/small_anim.swf" />
  <param name="quality" value="high" />
  <embed src="'.THEME_DIR.'/images/small_anim.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="35" height="30"></embed>
</object>';

/* dipanggil saat menampilkan halaman utama */
function theme_main_page() {
  echo '<div align="center">'."\n";
  header_view(); // this
  
  page_header(); // this

  /* memasukkan isi dari main page - harus ada */
  
  main_page_content(); // main.php
  
  /* --------------------------------------- */
  
  page_footer();
  echo '</div>'."\n";
}

/* dipanggil saat menampilkan halaman login 
 * form login harus menggunakan action="login.php"
 */

function theme_login_page() {
  echo '<div align="center">'."\n";
  header_view();
  include "menu.php";
  page_header();
  include "loginpage.php";
  page_footer();
  echo '</div>';
}

/* apa-apa yang harus dimasukkan ke dalam <HEAD> form */
function theme_form_head() {
	echo '<link rel="stylesheet" type="text/css" href="'.THEME_DIR.'/theme.css">'."\n";
	echo '<script type="text/javascript" src="'.THEME_DIR.'/form.js"></script>'."\n";
}

/* dipanggil saat menampilkan form 
 *
 * <!-- DOCUMENT TITLE --> harus ada karena akan di-replace sebelum di-flush
 *
 */
function theme_form_page() {
  global $extra_anim;
  $extra_anim='';
  echo 
    '<script type="text/javascript"> '."\n".
    'setInterval("form_check_size()", 500); '."\n".
	'</script> '."\n";

  echo '<div id="container-page"> '."\n";

  /* pastikan harus ada ------------------|
                               |---------------------|                                */
  //echo '<div id="form_header"> <!-- DOCUMENT TITLE --> </div> '."\n";
  echo '<div>'.
  '<table cellpadding="0" border="0" cellspacing="0" width="100%"><tr><td id="form_header" width="100%"> <!-- DOCUMENT TITLE --> </td><td bgcolor="#FFB600">'.$extra_anim.'</td></tr></table></div>';

  /* memasukkan isi dari form - harus ada */
  form_page_content();
  /* --------------------------------------- */

  echo '</div> '."\n";
}

/* ============================================================================================================= */

function page_header() {
  global $menus;
  
  if (b_logged()){
  	echo '<div id="container-page2"> '."\n";
  	include "menu.php";
	echo '</div>'."\n";
  }
 
  echo 
  '<table cellpadding="0" cellspacing="0" border="0" style="width:1013">
  <tr>
    <td width="19" style="background-image:url('.THEME_DIR.'/images/shadowl.png);">&nbsp;</td>
  	<td bgcolor="#EFEFEF">'."\n";
  
  
  echo "\n".'<div id="content" style="background-color:#EFEFEF"> '."\n";
}

function page_footer($login=0) {
  $dir = THEME_DIR;
  /*if($login){ 
  	  echo '</div> '."\n";
  }else{	
	  echo '</div> '."\n";
  }*/
  
  echo
  '</td>
   	<td width="19" style="background-image:url('.THEME_DIR.'/images/shadowr.png)">&nbsp;</td>
  </tr>
  </table>';
  
  echo
    '<table width="1013" cellpadding="0" border="0" cellspacing="0">
	<TR>
	<TD WIDTH=19 HEIGHT=11 style="background-image:url('.$dir.'/images/model1a_07.png)">
	&nbsp;</TD>
	<TD HEIGHT=11 style="background-image:url('.$dir.'/images/model1a_08.png)">
	&nbsp;</TD>
	<TD WIDTH=19 HEIGHT=11 style="background-image:url('.$dir.'/images/model1a_09.png)">
	&nbsp;</TD>
	</TR>
	<tr>
	<td width="19" style="background-image:url('.$dir.'/images/shadowl.png)">&nbsp;</td>
	<td valign="middle">
	<div id="wnd_list">&nbsp;</div>
	</td>
	<td width="19" height="10" style="background-image:url('.$dir.'/images/shadowr.png)">&nbsp;</td>
	</tr>
	</table>'."\n".
	'<table width="1013" border=0 cellpadding="0" cellspacing="0">
	<TR>
		<TD WIDTH=19 HEIGHT=15 style="background-image:url('.$dir.'/images/model1a_19.png)">&nbsp;</TD>
		<TD HEIGHT=15 style="background-image:url('.$dir.'/images/model1a_20.png)">&nbsp;</TD>
		<TD WIDTH=19 HEIGHT=15 style="background-image:url('.$dir.'/images/model1a_21.png)">&nbsp;</TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="'.$dir.'/images/model1a_22.png" WIDTH=19 HEIGHT=56 ALT=""></TD>
		<TD style="background-image:url('.$dir.'/images/footer_bkgrnd2.png)">
			<div id="footer">'."\n";
			
			login_info();
	echo 	
		'</div>
		</TD>
		<TD><IMG SRC="'.$dir.'/images/model1a_24.png" WIDTH=19 HEIGHT=56 ALT=""></TD>
	</TR>
	<TR>
		<TD><IMG SRC="'.$dir.'/images/model1a_25.png" WIDTH=19 HEIGHT=21 ALT=""></TD>
		<TD HEIGHT=21 style="background-image:url('.$dir.'/images/model1a_26.png)">&nbsp;</TD>
		<TD><IMG SRC="'.$dir.'/images/model1a_27.png" WIDTH=19 HEIGHT=21 ALT=""></TD>
	</TR>
	</table>'."\n";
}


?>