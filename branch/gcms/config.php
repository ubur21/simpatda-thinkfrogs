<?php
define("DRIVER","firebird");
define("SERVER","localhost");
define("USER", "sysdba");
define("PASSWORD", "masterkey");
define("DATABASE", "C:/SIMTAP/htdocs/simpatda/db/simpatda.gdb");
define("PREFIX","g_");

define("LOCATION","http://localhost/simpatda");

define("WEB_TITLE","SIMPATDA");
define("THEME_DIR","default_theme");

/* Warning!! */
/* do not change configuration below */
define("bc_admin", 4);
define("bc_supervisor",3);
define("bc_editor",2);
define("bc_operator",1);
define("bc_inactive",0);
$bva_levelname=array("inactive", "operator", "editor", "supervisor", "administrator");

define("ROOT_LOCATION", "C:/SIMTAP/htdocs/simpatda/");
define("IMAGES_LOCATION", ROOT_LOCATION."images/");

require_once("db/db.php");
//gcms_pconnect(SERVER, USER, PASSWORD, DATABASE);
$fbird = new metafire(SERVER,DATABASE,USER,PASSWORD,1,0);
$fbird->Connect();
$fbdb = $fbird->intConn;
?>