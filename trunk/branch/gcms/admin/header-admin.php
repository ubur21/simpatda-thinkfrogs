<?php
if ($_POST['csubmit']=="Login" /* or b_cookies_check()*/ )
    b_login($_POST['cuser'], $_POST['cpass'], $_POST['cremember']);

switch($_REQUEST['cact']) {
    case 'logout' : b_logout();
        break;	
}

if(b_logged()) {
    if($_REQUEST['csub']==0 and $_REQUEST['cact']==1) 
        $bdash=true;
    elseif ($_REQUEST['csub']>0) {
        $bdash=false;
        $csql="select cpath from ".PREFIX."moduls where nid='".$_REQUEST['csub']."'";
        $cpath=b_fetch($csql);
        $ainfo=b_readinit(str_replace(".php",".init.php",$cpath));   
    }  
    $csql="select nstatus from ".PREFIX."users where nid='".$_SESSION['nid_login']."'";
    //$bv_level=b_fetch($csql); 
    define("USERLEVEL",b_fetch($csql));
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>Administration</title>
    <link rel="stylesheet" type="text/css" href="styleadmin.css">
    <script language="javascript">
    <!--
    function focusit() {
        if(document.getElementById('focushere')) {
            document.getElementById('focushere').focus();
        }
    }
    window.onload = focusit;
    
    </script>
<?php  
    if(isset($cpath)) { 
        define('PATHEXTENSION',dirname($cpath)."/");
        include(str_replace(".php",".init.php",$cpath));
    }
?>

</head>
<body <?php echo defined('JSONLOAD')?"onload=\"".JSONLOAD."\"":"" ?>>
<div class="layar">
<table cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td align="left"><img src="../images/bandung.jpg"></td>
        <td align="right" valign="top">
    </tr>
</table>
</div>


<? if(b_logged()) { 
      $csql="select cname from ".PREFIX."users where nid=".$_SESSION['nid_login'];   
	  $info_left = '&nbsp;&nbsp;&nbsp;Logged as '.b_fetch($csql);
	  $info_right = '&nbsp;&nbsp;<a href="?cact=logout">[ Logout ]</a> <a href="../">[  Main Menu ]</a>&nbsp;&nbsp;';
   } 
?>
<table align="center" cellpadding="0" cellspacing="0" border="0" width="61%" style="padding-bottom:10px;padding-top:10px; background-color:#CCCCCC; border-bottom:1px solid #999999; border-top:1px solid #999999">
<tr>
<td align="left">
<div class="top_menu_admin">
<?=$info_left;?>
</div>
</td>
<td align="right" valign="top">
<div class="top_menu_admin">
<?=$info_right;?>
</div>
</td>
</tr>
</table>
<div class="layar">
<? if($debug_error<>"") { ?>
<br><div class="warning"><? echo $debug_error ?></div><br>
<? } ?>
<div class="layar2">
