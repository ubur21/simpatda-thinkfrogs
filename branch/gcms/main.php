<?php
/* ========== HALAMAN UTAMA / pake window browser, bukan popup =============================== */
require_once("global.php");

// b_startup();

/* to be added : isi dari halaman login - welcome message, sekilas info, dll */
function login_page_content() {
   // echo "selamat datang";
}

/* isi dari halaman utama setelah login */
function main_page_content() {
    echo '<div id="main_content">';
    $lbl = array();
    $cnt = array();

    /* ambil isi .main tiap modul */
    $csql="select cpath from ".PREFIX."moduls";
    $nresult=gcms_query($csql);
    while($opath=gcms_fetch_object($nresult)) {
        $mainfile = translate_modul_path(str_replace(".php",".main.php",strtolower($opath->cpath)));
        if (file_exists($mainfile)) {
            $ainfo = b_readinit(translate_modul_path(str_replace(".php",".init.php",strtolower($opath->cpath))));
            $lbl[] = $ainfo['name'];
            $cnt[] = $mainfile;
        }
    }

    $sel = false;
    if (count($lbl) > 0) {
    echo
        '<div id="gcms_main_tab" class="yui-navset"> '."\n".
        '  <ul class="yui-nav"> '."\n";
        if ($_REQUEST['page'] || $_REQUEST['mod']) {
            $sel = true;
            echo
            '    <li class="selected"><a href="#gcms_main_tab_menu"><em>'. ($_REQUEST['page'] ? menu_get_title($_REQUEST['page']) : 'Utama').'</em></a></li> '."\n";
        }
        foreach ($lbl as $k => $v) {
            echo
            '    <li';
            if (!$sel) {
                $sel = true;
                echo ' class="selected"';
            }
            echo '><a href="#gcms_main_tab'.$k.'" ><em>'.$v.'</em></a></li> '."\n";
        }
        echo
        '  </ul> '."\n".
        '  <div class="yui-content"> '."\n";
        foreach ($cnt as $k => $v) {
            echo
            '    <div id="gcms_main_tab'.$k.'">';
            include $v;
            echo '</div> '."\n";
        }
        if ($_REQUEST['page'] || $_REQUEST['mod']) {
            echo
            '    <div id="gcms_main_tab_menu"><p>';
        }
    }

    /* masukkan isi halaman sesuai menu disini */
    if ($_REQUEST['page']) menu_get_content($_REQUEST['page']);
    else if ($_REQUEST['mod']) menu_get_content_by_mod($_REQUEST['mod'], $_REQUEST['func']);

    if (count($lbl) > 0) {
      if ($_REQUEST['page'] || $_REQUEST['mod']) {
        echo 
        '</p></div> '."\n";
      }
      echo
        '  </div> '."\n".
        '</div> '."\n";
    }
    echo '</div> <!-- main content -->'; 
}

/* informasi login : apa siapa kapan */
function login_info($class = "") {
    if(b_logged()) {
        $ainfouser = b_userinfo();
        echo
        '<div id="login_info" '.($class ? 'class="'.$class.'"' : '').'> '."\n".
        'Logged as <b>'.$ainfouser['name'].'</b> on '.$ainfouser['logged'].' from IP '.$ainfouser['ip'].'<br> '."\n".
        'Last Login on '.$ainfouser['lastlogin'].' until '.$ainfouser['lastlogout'].' from IP '.$ainfouser['ip'].'</div> <!-- login_info --> '."\n";
    }
}

/* ambil theme */
require_once(THEME_DIR."/theme.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo WEB_TITLE ?></title>
<link rel="shortcut icon" href="./images/favicon_garuda.ico" type="image/ico">
<script type="text/javascript" src="yui/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="yui/container/container_core-min.js"></script>
<link rel="stylesheet" type="text/css" href="gcms.css">
<script type="text/javascript" src="gcms.js"></script>
<script type="text/javascript" src="<?php echo THEME_DIR ?>/theme.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo THEME_DIR ?>/theme.css">
<?php theme_head(); ?>
<?php
    /* masukkan init halaman disini */
    if($_REQUEST['page']) menu_get_init($_REQUEST['page']);
    else if($_REQUEST['mod']) menu_get_init_by_mod($_REQUEST['mod']);
?>
</head>
<body class="yui-skin-sam" >

<?php $menus = load_menu(); 
//print_r($menus);
?>
<?php if (b_logged()) theme_main_page(); else theme_login_page() ?>
</body>
</html>
