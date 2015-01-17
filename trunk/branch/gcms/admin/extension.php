<?php
if(isset($_POST['submit']) and b_admin(b_getuserlogin())) {
    if($_POST['submit']=="Disable") { 
        if(b_checkdisabledependency($_POST['path'])) {
            $csql="select nid from ".PREFIX."moduls where cpath='".$_POST['path']."'";  
            $nid_moduls=b_fetch($csql);
            $csql="delete from ".PREFIX."granted where nid_moduls='$nid_moduls'";
            gcms_query($csql);

            if($_POST['premove']=="remove") {
                $csql="select * from ".PREFIX."tableinstalled where  nid_moduls='$nid_moduls'";
                $nresult=gcms_query($csql);
                while($otable=gcms_fetch_object($nresult)) {
                    $csql2="drop table ".$otable->ctable;
                    gcms_query($csql2);
                }
            }
            $csql="delete from ".PREFIX."tableinstalled where nid_moduls='$nid_moduls'";
            gcms_query($csql);
            $csql="delete from ".PREFIX."moduls where cpath='".$_POST['path']."'";
        } else {
            $ainfoerror=b_readinit(str_replace(".php",".init.php",strtolower($_POST['path'])));
            $cerror="Extension <b>".$ainfoerror['name']." ".$ainfoerror['version']."</b> can't be disabled, 
                please check extension's dependency from the others<br/>";
        }
    } 
    if ($_POST['submit']=="Enable") {
        include("./../config.php");
        if(b_checkenabledependency($_POST['path'])) {
            // create list of current tables before process
            $nresult=gcms_list_tables(DATABASE);
            while($atables=gcms_fetch_row($nresult)) $atablesbefore[]=$atables[0];
            // find sql file and execute it
            $cpathsql=explode('/',$_POST['path']);
            $ncount = count($cpathsql);
            $cpathsql=array_slice($cpathsql, 0, $ncount-1);
            $cpathsql=implode("/", $cpathsql);
            foreach (b_getlistfile($cpathsql) as $cfile) { 
                if(eregi(".".DRIVER.".sql", $cfile)) {
                    // read the files
                    $fsql = @fopen($cfile, "r"); 
                    $csql = fread($fsql,filesize($cfile)); 
                    fclose($fsql); 
                    gcms_run_query_block($csql);
                }
            }
            // create list of current tables after process
            $nresult=gcms_list_tables(DATABASE);
            while($atables=gcms_fetch_row($nresult)) $atablesafter[]=$atables[0];
            // find the new tables name
            $atablenew=array_diff($atablesafter, $atablesbefore);
            $csql="insert into ".PREFIX."moduls (cpath) values ('".$_POST['path']."')";
        } else {
            $ainfoerror=b_readinit(str_replace(".php",".init.php",strtolower($_POST['path'])));
            $cerror="Extension <b>".$ainfoerror['name']." ".$ainfoerror['version']."</b> can't be enabled, 
                please check extension dependency<br/>";
        }
    }
    if($csql<>"") {
        gcms_query($csql);
        $nid=gcms_insert_id("moduls");
        // if new tables created
        if(count($atablenew)>0) {
            foreach($atablenew as $ctable) {
                $csql="insert into ".PREFIX."tableinstalled (nid_moduls, ctable)
                    values ('$nid', '$ctable')"; 
                gcms_query($csql);	
            }
        }
    }
    if($cerror=="") {
?>
<script language="Javascript">
var sURL = unescape(window.location.pathname);
window.location.href = sURL+"?cact=<?php echo $_REQUEST['cact'] ?>";
</script>
<?php
    }
}
?>
<div class="judul">Extension</div>
<?php if($cerror<>"") { ?>
<br><div class="warning"><?php echo $cerror ?></div><br>
<?php 
    } 
?>
<table class="table_admin">
<?php
$ext1 = b_getlistfile("./extensions");
$ext2 = b_getlistfile("../extensions");
if (is_array($ext1)) {
    if (is_array($ext2)) $exts = array_merge($ext1, $ext2);
    else $exts = $ext1;
}
else if (is_array($ext2)) $exts = $ext2;

if (is_array($exts)) { 
    foreach ($exts as $cfile) {
        if(eregi("init.php",$cfile)) {
        $ainfo=b_readinit($cfile);
        ob_start();
        require("$cfile");
        ob_end_clean();
        $cext=str_replace(".init","",strtolower($cfile));
        $csql="select nid from ".PREFIX."moduls where cpath='$cext'";
        $nresult=gcms_query($csql);
        if(gcms_fetch_row($nresult)){
            $cvalue="Disable";
            $ccolor="#EEEEEE";

/* COMPLETE REMOVE -- obsolete, dengan firebird tidak jalan, telebih kalau pake view, procedure, dan trigger

        $csql="select a.nid from ".PREFIX."moduls as a 
					inner join ".PREFIX."tableinstalled as b on a.nid=b.nid_moduls
					where a.cpath='$cext'";
	  		$nresult=gcms_query($csql);
		  	(gcms_fetch_row($nresult))?$cremove="<br><input type=\"checkbox\" name=\"premove\" value=\"remove\" dummy >Complete Remove"
										:$cremove="";
*/
        } 
        else { 
            $cvalue="Enable";
            $ccolor="";
            $cremove="";
        }
        ($ainfo['url']<>"")?$cby="<a href=\"".$ainfo['url']."\" target=\"_blank\">".$ainfo['author']."</a>":$cby=$ainfo['author'];
        ($ainfo['source']<>"")?$csource="<a href=\"".$ainfo['source']."\" target=\"_blank\">".$ainfo['source']."</a>":$csource=" - ";
?>
    <form action="<?php echo b_urlact() ?>" method="POST">   
    <tr><td valign="top" width="25%" bgcolor="<?php echo $ccolor ?>"><?php echo $ainfo['name'] ?>&nbsp;<?php echo $ainfo['version'] ?><br><br>
    <input type="hidden" name="path" value="<?php echo $cext ?>"> 	
    </td>
    <td bgcolor="<?php echo $ccolor ?>"><?php echo $ainfo['info'] ?><br>
    Dependency: <?php echo ($ainfo['dependency']<>"")?strtolower($ainfo['dependency']):" - " ?><br>
    by:&nbsp;<?php echo $cby ?><br>
    Source:&nbsp;<?php echo $csource ?>
    </td>
    <td align="left" valign="middle" bgcolor="<?php echo $ccolor ?>" width="20%">
<?php 
    (b_admin(b_getuserlogin()))?$cdisabled="":$cdisabled="disabled";
    $cremove=str_replace('dummy', $cdisabled, $cremove);
?>
    <input type="submit" name="submit" value="<?php echo $cvalue ?>" <?php echo $cdisabled ?>><?php echo $cremove ?>
    </td>
    </tr>
    </form>  
<?php
        }
	}
}
?>
</table>