<?php
//experiment
ob_start("ob_gzhandler");
session_start(); 
session_regenerate_id(); 
include("./../config.php"); 
include("./../lib.php"); 

b_startup();

if(!b_antisqlinjection($_REQUEST['csub'])) die();

include('header-admin.php');  

if(!b_logged()) include('login.php'); 
else { 
    include ('menu.php');   
    if($_REQUEST['cact']==0) include('dashboard.php');
    else {
        if($bdash) include('extension.php');
        else if(isset($cpath)) { 
?>
<div class="judul"><?php echo $ainfo['name'] ?></div>
<?php
            $ainfo=b_readinit(str_replace(".php",".init.php",$cpath)); 
            $csql="select nstatus from ".PREFIX."granted where nid_users='".$_SESSION['nid_login']."' and nid_moduls='".$_REQUEST['csub']."'";
            if(b_fetch($csql)>=$ainfo['level'] or b_admin(b_getuserlogin())) {
                if(file_exists($cpath)) {
                    include($cpath);	 
                }
            }
        }
    }
}
?>
<?php include('footer-admin.php') ?>