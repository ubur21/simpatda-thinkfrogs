<?
//session_start();
//session_start();
$Lifetime = 360000;
/*$Seperator = (strstr(strtoupper(substr(PHP_OS, 0, 3)), "WIN")) ? "\\" : "/";
$DirectoryPath = dirname(__FILE__) . "{$Seperator}SessionData";
is_dir($DirectoryPath) or mkdir($DirectoryPath, 0777);

if (ini_get("session.use_trans_sid") == true) {
    ini_set("url_rewriter.tags", "");
    ini_set("session.use_trans_sid", false);

}*/

//ini_set("session.gc_maxlifetime", $Lifetime);
//ini_set("session.gc_divisor", "1");
//ini_set("session.gc_probability", "1");
//ini_set("session.cookie_lifetime", "1");
//ini_set("session.save_path", "C:\WINDOWS\Temp");
session_start();
/* ========== HALAMAN UTAMA / pake window browser, bukan popup =============================== */

require_once("global.php");

// b_startup();
 $ainfouser = b_userinfo();
 $user_id = $ainfouser['user'] ;
 $spbu_id = $ainfouser['spbu_id'] ;
 if(!empty($spbu_id)){
 	$where = " AND spbu_id = '".$spbu_id."' ";
 }
 $csql="SELECT * FROM spbu where 1 $where ";
 
 $nresult=gcms_query($csql);
 $spbu = gcms_fetch_object($nresult);
 /*Begin Create Session*/
 $_SESSION['cost_centre_no'] 	= $spbu->cost_centre_no;
 $_SESSION['spbu_id']   		= $spbu->spbu_id;
 $_SESSION['spbu_no']     		= $spbu->spbu_no;
 $_SESSION['user_id']     		= $ainfouser['user'] ;
 $_SESSION['branch_name']    	= $spbu->branch_name;
 if(empty($_SESSION['spbu_no'])){
 	$_SESSION['capt_branch']     = "Divisi";
 }else{
 	$_SESSION['capt_branch']     = "SPBU";
 }
 /*Begin End Create Session*/

/* to be added : isi dari halaman login - welcome message, sekilas info, dll */
function login_page_content() {
}

/* isi dari halaman utama setelah login */
function main_page_content() {
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

    echo
      '<script type="text/javascript"> '."\n".
      '  var tabView = new YAHOO.widget.TabView("gcms_main_tab"); '."\n".
      '</script> '."\n";
}

/* informasi login : apa siapa kapan */
function login_info($class = "") {
  if(b_logged()) {
	  $ainfouser = b_userinfo();
    echo
      '<div '.($class ? 'class="'.$class.'"' : '').'> '."\n".
      'Logged as <b>'.$ainfouser['name'].'</b> on '.$ainfouser['logged'].' from IP '.$ainfouser['ip'].'<br> '."\n".
      'Last Login on '.$ainfouser['lastlogin'].' until '.$ainfouser['lastlogout'].' from IP '.$ainfouser['ip'].'&nbsp;&nbsp;<a href="http://groups.yahoo.com/group/bosretail/join" style="">Join Ke BOS Retail Group </a></div>'."\n";
	  //'<a href="http://groups.yahoo.com/group/bosretail/join" style="">Join Ke BOS Retail Group </a></div> '."\n";
  }else{
    echo '<a href="#" style="">&reg; Copyright PT. Pertamina Retail<a>';
  }	
}
$count_users = b_fetch('select count(*) from '.PREFIX.'users where nstatus=4');

/* ambil theme */
require_once(THEME_DIR."/theme.php");
if (b_logged()){
	$ainfouser = b_userinfo();
	$user_name = $ainfouser['name'];
	$spbu_no = $_SESSION['spbu_no'];
	$branch = $_SESSION['branch_name'];
	if($user_name == "Administrator"){
		$header = "&nbsp;&nbsp;Management ".$user_name ;
	}elseif(!$spbu_no){	
		$header = "&nbsp;&nbsp;Divisi Head Office - ".$user_name ;
	}else{
		$header = "&nbsp;&nbsp;Unit SPBU : ".$spbu_no." - ".$user_name ;
	}	
	$memo = b_fetch('select memo from memorandum WHERE status = 1');
}
?>
<html>
<head>
<title><?= WEB_TITLE ?></title>
<link rel="shortcut icon" href="./images/controlpanel.png" type="image/ico">
<link rel="stylesheet" type="text/css" href="gcms.css">
<script type="text/javascript" src="gcms.js"></script>
<script type="text/javascript" src="<?= THEME_DIR ?>/theme.js"></script>
<link rel="stylesheet" type="text/css" href="<?= THEME_DIR ?>/theme.css">
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
<script type="text/javascript" src="yui/build/animation/animation.js"></script>
<? theme_head(); ?>
<?
/* masukkan init halaman disini */
if($_REQUEST['page']) menu_get_init($_REQUEST['page']);
else if($_REQUEST['mod']) menu_get_init_by_mod($_REQUEST['mod']);
?>
</head>
<SCRIPT language=JavaScript>
var speed = 50 
var pause = 1600 
var timerID = null
var bannerRunning = false
var ar = new Array()
ar[0] = "Selamat Datang Di Back Office System V1.18"
ar[1] = "<?= WEB_TITLE ?>"
ar[2] = "<?= $xuser ?>"
var message = 0
var state = ""
clearState()
function stopBanner() {	
	if (bannerRunning)		
	clearTimeout(timerID)	
	timerRunning = false
}
function startBanner() {	
	stopBanner()	
	showBanner()
}

function clearState() {	
	state = ""	
	for (var i = 0; i < ar[message].length; ++i) {		
		state += "0"	
	}
}

function showBanner() {	
	if (getString()) {		
		message++		
	if (ar.length <= message)			
		message = 0		
		clearState()		
		timerID = setTimeout("showBanner()", pause)	
	} 
	else {		
		var str = ""		
	for (var j = 0; j < state.length; ++j) {			
		str += (state.charAt(j) == "1") ? ar[message].charAt(j) : "     "		
	}		
	window.status = str		
	timerID = setTimeout("showBanner()", speed)	
	}
}

function getString() {	
	var full = true	
	for (var j = 0; j < state.length; ++j) {		
		if (state.charAt(j) == 0)			
		full = false	
	}	
	if (full) return true	
	while (1) {		
		var num = getRandom(ar[message].length)		
	if (state.charAt(num) == "0")			
		break	
	}	
	state = state.substring(0, num) + "1" + state.substring(num + 1, state.length)	
	return false
}

function getRandom(max) {	
	var now = new Date()		
	var num = now.getTime() * now.getSeconds() * Math.random()	
	return num % max
}
startBanner()

var textDisplay = new Array ("Back Office System V1.18", "Pertamina Retail"); 
var textCount=2; 
var x=1; 
var y=0; 
var z=1; 
function animate() 
{ 
window.document.title=textDisplay[y].substr(0, x)+""; 
if (z==0) x--; 
if (z==1) x++; 
if (x==-1) {z=1;x=0;y++;y=y%textCount;} 
if (x==textDisplay[y].length+10) {z=0;x=textDisplay[y].length;} 
parent.window.document.title=textDisplay[y].substr(0, x)+""; 
setTimeout("animate()",75); 
} 


var texts = new Array(
					  			"<font  color='{COLOR}' ><?= $header ?></font>"
					  
					  	
					  );
var bgcolor = "#FF0000"; // background color, must be valid browser hex color (not color names)
var fcolor = "#FFFF66";  // foreground or font color
var steps = 60; // number of steps to fade
var show = 500; // milliseconds to display message
var sleep = 600; // milliseconds to pause inbetween messages
var loop = stop; // true = continue to display messages, false = stop at last message
</SCRIPT>
<script language="JavaScript" src="fader.js"></script>
<body class="yui-skin-sam" style="padding:0; margin:0;"  onLoad="animate();progres_bar();fade();">
<? 

	$menus = load_menu(); 
	if (b_logged()) theme_main_page(); else theme_login_page() ;
	include("form_dialog.php");
?>
</body>
</html>
