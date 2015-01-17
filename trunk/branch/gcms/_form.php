<?php

/* inisialisasi */
require_once('global.php');

// b_startup();

/* tambahan skrip pada body onload */

$extraonload = "";

function gcms_add_on_load($onload)
{
    global $extraonload;
    $extraonload .= ";".$onload;
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
    echo '<script type="text/javascript"> '."\n";

    if ($get_title_func) {
        gcms_add_on_load($procname.";"); 
        echo
        'function '.$procname.' { '."\n".
        '  document.title = '.$get_title_func.'; '."\n".
        '  document.getElementById("form_header").innerHTML = document.title; '."\n".
        '} '."\n";
    }
    echo '</script> '."\n";
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
gcms_add_on_load("resize_divs();");

function form_page_content() {
    echo '<div id="content"> '."\n";
    /* masukkan isi halaman disini */
    if ($_REQUEST['page']) menu_get_content($_REQUEST['page']);
    else if ($_REQUEST['mod']) menu_get_content_by_mod($_REQUEST['mod'], $_REQUEST['func']);
    echo '</div> <!-- content --> '."\n";
}

require_once(THEME_DIR."/theme.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title><!-- DOCUMENT TITLE --></title>
    <link rel="stylesheet" type="text/css" href="yui/build/reset-fonts-grids/reset-fonts-grids.css">
    <link rel="stylesheet" type="text/css" href="yui/build/fonts/fonts-min.css" />
    <script type="text/javascript" src="yui/build/yahoo/yahoo-min.js"></script>
    <script type="text/javascript" src="yui/build/yahoo-dom-event/yahoo-dom-event.js"></script>
    <script type="text/javascript" src="yui/build/element/element-beta-min.js"></script>
    <script type="text/javascript" src="yui/build/connection/connection-min.js"></script>
    <script type="text/javascript" src="yui/build/datasource/datasource-beta-min.js"></script>
    <script type="text/javascript" src="yui/build/json/json-min.js"></script>
    <script type="text/javascript" src="yui/build/dragdrop/dragdrop-min.js"></script>
    <link rel="stylesheet" type="text/css" href="yui/build/button/assets/skins/sam/button.css" />
    <script type="text/javascript" src="yui/build/button/button-min.js"></script>
    <script type="text/javascript" src="yui/build/container/container_core.js"></script>
    <link rel="stylesheet" type="text/css" href="yui/build/tabview/assets/skins/sam/tabview.css" />
    <script type="text/javascript" src="yui/build/tabview/tabview-min.js"></script>
    <link rel="stylesheet" type="text/css" href="gcms.css">
    <script type="text/javascript" src="gcms.js"></script>
    <script type="text/javascript" src="<?php echo THEME_DIR ?>/theme.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo THEME_DIR ?>/theme.css">
    <?php theme_form_head(); ?>
    <!-- EXTRA HEAD -->
    <?php
    /* masukkan init halaman disini */
    if($_REQUEST['page']) menu_get_init($_REQUEST['page']);
    else if($_REQUEST['mod']) menu_get_init_by_mod($_REQUEST['mod']);
    ?>

</head>
<body class="yui-skin-sam" onLoad="<!-- BODY ONLOAD -->;window.opener.addSubWindow(window, '<!-- FORM_ICON -->');" 
      onUnload="window.opener.removeSubWindow(window);">
<?php theme_form_page() ?>
</body>
</html>