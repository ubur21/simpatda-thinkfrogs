<ul id="adminmenu">
<?php
if (!$_REQUEST['cact']) $cact = 0;
else $cact = $_REQUEST['cact'];
$csql="select cgroup from ".PREFIX."groupmenus where nid='".$cact."'";
$cgroup=b_fetch($csql);

if($_REQUEST['cact']>0) {
    $creff="<a href=\"?cact=0\">Dashboard</a>";
} else {
    $creff="<a href=\"?cact=0\" class=\"current\">Dashboard</a>";   
}
?>
<li <?php echo $ccurrent ?>><?php echo $creff ?></li>
<?php
if($bv_level>bc_inactive or true) {
    $csubmenu="<ul id=\"submenu\">";
    $ccurrent=""; $creff="";

    if($_REQUEST['cact']==1) {
        if($_REQUEST['csub']>0) {
            $creff="<a href=\"?cact=".$_REQUEST['cact']."&csub=0\">Extensions</a>\n";
        } else {
            $creff="<a href=\"?cact=".$_REQUEST['cact']."&csub=0\" class=\"current\">Extensions</a>\n";    
        }
    }
    $csubmenu.="<li ".$ccurrent.">".$creff."</li>";
    
    if(b_admin(b_getuserlogin())) {
        $csql="select * from ".PREFIX."moduls";
    } else {
        $csql="select a.nid, a.cpath, b.nstatus from ".PREFIX."moduls as a
                     inner join ".PREFIX."granted as b on 
                     a.nid=b.nid_moduls and b.nid_users='".$_SESSION['nid_login']."'";
    }     
    $nresult=gcms_query($csql);
    $agroup[]="";
    while($omoduls=gcms_fetch_object($nresult)) {
        $ainfo_menu=b_readinit(str_replace(".php",".init.php",strtolower($omoduls->cpath)));
	    
        if($omoduls->nstatus>=$ainfo_menu['level'] or b_admin(b_getuserlogin())) {
            $ccurrent="";  $creff="";  
            if(!in_array($ainfo_menu['group'],$agroup)) $agroup[]=$ainfo_menu['group']; 
            if(trim(strtolower($ainfo_menu['type']))=="admin" and 
              trim(strtolower($ainfo_menu['group']))==strtolower($cgroup) and 
              $ainfo_menu['grant']<=$bv_level) {
                if($_REQUEST['csub']==$omoduls->nid ) {
                    $creff="<a href=\"?cact=".$_REQUEST['cact']."&csub=$omoduls->nid\" class=\"current\">".trim($ainfo_menu['name'])."</a>";
                } else {
                    $creff="<a href=\"?cact=".$_REQUEST['cact']."&csub=$omoduls->nid\">".trim($ainfo_menu['name'])."</a>";
                }
            }
            $csubmenu.="<li ".$ccurrent.">".$creff."</li>\r\n";
        }
    }
    
    $cgroups="('Manage'";
    foreach($agroup as $cgroups_temp) 
        if($cgroups_temp<>"") $cgroups.=",'".$cgroups_temp."'";
    $cgroups.=")";
    
    $csql="select * from ".PREFIX."groupmenus where cgroup in ".$cgroups." order by nurut";
    $nresult=gcms_query($csql);
    while($omenu=gcms_fetch_object($nresult)) {
        if($_REQUEST['cact']==$omenu->nid) {
            $creff="<a href=\"?cact=$omenu->nid\" class=\"current\">".$omenu->cgroup."</a>";       
        } else {
            $creff="<a href=\"?cact=$omenu->nid\">".$omenu->cgroup."</a>";
        }
?>
    <li <?php echo $ccurrent ?> ><?php echo $creff ?></li>
<?php
    } 
?> 
    </ul>
<?php
    echo $csubmenu;	
}
?>
</ul>
<hr>