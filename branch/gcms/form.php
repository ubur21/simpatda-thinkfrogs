<?
error_reporting (1) ;
/* inisialisasi */
require_once('global.php');

// b_startup();

/* tambahan skrip pada body onload */

$extraonload = "";

function gcms_add_on_load($onload)
{
  global $extraonload;
  $n = explode(";",$extraonload);
  if(count($n)==1){
  	 $extraonload=$onload; 
  }else{
  	 $extraonload.=$onload.";";
  }
}

/* tambahan pada head */

$extrahead = "";

function gcms_add_to_head($head)
{
  global $extrahead;
  $extrahead .= $head;
}

/* judul form */

$document_title = "";

if(isset($_REQUEST['page'])) {
   if(function_exists(menu_get_title)) $document_title = menu_get_title($_REQUEST['page']);
}

/* set title */
function gcms_set_title($title) {
  global $document_title;
  $document_title = $title;
}

/* nambahin ke title */
function gcms_append_title($title) {
  global $document_title;
  $document_title .= $title;
}

/* membuat fungsi javaskrip yang akan mengupdate title */
function init_update_title($procname, $get_title_func) {
  echo
'<script type="text/javascript"> '."\n";

  if ($get_title_func) {
    gcms_add_on_load($procname); 
    echo
'function '.$procname.' { '."\n".
'  document.title = '.$get_title_func.'; '."\n".
'  document.getElementById("form_header").innerHTML = document.title; '."\n".
'} '."\n";
  }

  echo
'</script> '."\n";

}

/* icon untuk form */
$form_icon = "";

function set_form_icon($icon) {
  global $form_icon;
  $form_icon = $icon;
}

/* callback output buffer yang digunakan mengisi tambahan-tambahan pada title, head, onload */

function gcms_ob_callback($buffer) 
{
  global $extrahead, $extraonload, $document_title, $form_icon;
  $buffer = (str_replace("<!-- EXTRA HEAD -->", $extrahead, $buffer));
  $buffer = (str_replace("<!-- BODY ONLOAD -->", $extraonload, $buffer));
  $buffer = (str_replace("<!-- DOCUMENT TITLE -->", $document_title, $buffer));
  $buffer = (str_replace("<!-- FORM_ICON -->", $form_icon, $buffer));
  return $buffer;
}

ob_start("gcms_ob_callback");

/* register penyesuaian ukuran div dengan windownya */
//gcms_add_on_load("resize_divs();");

function form_page_content() {

  echo '<div id="content"> '."\n";
  /* masukkan isi halaman disini */
  if ($_REQUEST['page']) menu_get_content($_REQUEST['page']);
  else if ($_REQUEST['mod']) menu_get_content_by_mod($_REQUEST['mod'], $_REQUEST['func']);
  echo '</div> '."\n";
}

require_once(THEME_DIR."/theme.php");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<title><?php echo WEB_TITLE ?></title>
<link rel="shortcut icon" href="./images/favicon_garuda.ico" type="image/ico">
<script type="text/javascript" src="yui/yahoo/yahoo-min.js"></script>
<script type="text/javascript" src="yui/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="yui/element/element-beta-min.js"></script>
<script type="text/javascript" src="yui/connection/connection-min.js"></script>
<!--<script type="text/javascript" src="yui/datasource/datasource-beta-min.js"></script>-->
<script type="text/javascript" src="yui/datasource/datasource-min.js"></script>
<script type="text/javascript" src="yui/json/json-min.js"></script>
<script type="text/javascript" src="yui/dragdrop/dragdrop-min.js"></script>
<script type="text/javascript" src="yui/button/button-min.js"></script>
<script type="text/javascript" src="yui/container/container_core.js"></script>
<script type="text/javascript" src="yui/container/container-min.js"></script>
<script type="text/javascript" src="yui/calendar/calendar-min.js"></script>
<!--<script type="text/javascript" src="script/niceform/niceforms.js"></script>-->
<link rel="stylesheet" type="text/css" href="yui/container/assets/skins/sam/container.css" />
<link rel="stylesheet" type="text/css" href="yui/calendar/assets/skins/sam/calendar.css" />
<!--<link rel="stylesheet" type="text/css" href="script/niceform/niceforms-default.css" />-->

<link rel="stylesheet" type="text/css" href="gcms.css">
<script type="text/javascript" src="gcms.js"></script>
<script type="text/javascript" src="<?php echo THEME_DIR ?>/theme.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo THEME_DIR ?>/theme.css">
<!-- EXTRA HEAD --> 
<?php 
	theme_head(); 
	theme_form_head(); //Edit HendA

    /* masukkan init halaman disini */
    if($_REQUEST['page']) menu_get_init($_REQUEST['page']);
    else if($_REQUEST['mod']) menu_get_init_by_mod($_REQUEST['mod']);
?>
</head>
<!--<body class="yui-skin-sam" >progres_bar();-->
<body class="yui-skin-sam" onLoad="<!-- BODY ONLOAD -->window.opener.addSubWindow(window, '<!-- FORM_ICON -->');" onUnload="window.opener.removeSubWindow(window);">

<?php //$menus = load_menu();
		
theme_form_page();//Edit HendA
		
?>
<?php //if (b_logged()) theme_main_page(); else theme_login_page() ?>
</body>
</html>