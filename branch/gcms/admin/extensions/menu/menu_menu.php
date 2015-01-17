<?php
if(basename( __FILE__ )==basename( $_SERVER['PHP_SELF'])) die();

$bwarning=false;
if(isset($_POST['csubmit'])) {
    $nloop=count($_POST['cmenu']);
    for($ii=0;$ii<$nloop;$ii++) {
        ($_POST['bismain'][$ii])?$bismain=1:$bismain=0; 
        ($_POST['bhide'][$ii])?$bhide=1:$bhide=0; 
        ($_POST['bsecure'][$ii])?$bsecure=1:$bsecure=0;
        if(isset($_POST[nid][$ii])) {
            if($_POST['bdelete'][$ii]) {
                $csql="delete from ".PREFIX."grantedfrontmenus where nid_frontmenus='".$_POST['nid'][$ii]."'";
                gcms_query($csql);
                $csql="delete from ".PREFIX."frontmenus where nid='".$_POST['nid'][$ii]."'";
                gcms_query($csql);
            } else {
                $csql="Update ".PREFIX."frontmenus
                            set 	nid_groupfrontmenus='".$_POST['cgroup'][$ii]."',
                            cmenu='".addslashes($_POST['cmenu'][$ii])."',
                            cfunction='".$_POST['cfunc'][$ii]."',
                            cparam='".addslashes($_POST['cparam'][$ii])."',			           
                            nurut='".$_POST['nurut'][$ii]."',
                            is_main='".$bismain."',
                            bhide='".$bhide."',
                            bsecure='".$bsecure."'
                            where nid='".$_POST['nid'][$ii]."'";
                gcms_query($csql);
            }
        } else {
            if(trim($_POST['cmenu'][$ii])<>"") {
                $csql="insert into ".PREFIX."frontmenus
                            (nid_groupfrontmenus, cmenu, cfunction, cparam, nurut, is_main, bhide, bsecure) values 
                            ('".$_POST['cgroup'][$ii]."',
                            '".addslashes($_POST['cmenu'][$ii])."',
                            '".$_POST['cfunc'][$ii]."',
                            '".addslashes($_POST['cparam'][$ii])."',			       	   
                            '".$_POST['nurut'][$ii]."', 
                            '".$bismain."',
                            '".$bhide."',
                            '".$bsecure."')";
                gcms_query($csql);
            }
        }
    }
    $bwarning=true;
}
if($bwarning) {
?>
<br><div class="warning">Menu has been updated</div><br>
<?php	
}
?>
<table class="table_admin2">
	<tr>
		<th>Group</th>
		<th>Menu</th>
		<th>Function</th>
		<th>Parameter</th>
		<th>Number</th>
        <th>Main</th>
		<th>Hide</th>
		<th>Secure</th>
		<th>Delete</th>
	</tr>	

<form name="formedit" action="<?php b_urlact()."&cpage=".$_REQUEST['cpage']  ?>" method="POST">

<?php
	$csql=	"select a.nid, a.nid_groupfrontmenus, a.cmenu, a.cfunction, a.cparam, a.nurut, a.is_main, a.bhide, a.bsecure 
			from  ".PREFIX."frontmenus as a
			inner join ".PREFIX."groupfrontmenus as b on a.nid_groupfrontmenus=b.nid
			order by b.nurut, b.cgroup, a.nurut, a.cmenu";
	$nresult=gcms_query($csql);
	$nindex=0;
	while ($ogroup=gcms_fetch_object($nresult)) {
?>
	<tr>
		<td><select name="cgroup[<?php echo $nindex ?>]" style="width:105px">
				<option style="width:200px"></option>
<?php		
		$csql2="select nid, cgroup, bhide from  ".PREFIX."groupfrontmenus order by nurut";
		$nresult2=gcms_query($csql2);
		while ($ogroup2=gcms_fetch_object($nresult2)) {
			($ogroup2->bhide)?$copt=$ogroup2->cgroup." (hide)":$copt=$ogroup2->cgroup;
			($ogroup2->nid==$ogroup->nid_groupfrontmenus)?$cselected="selected":$cselected="";
?>
			<option value="<?php echo $ogroup2->nid ?>" <?php echo $cselected ?>><? echo $copt ?></option>
<?php
		}
?>
			</select></td>
		<td><input type="text" name="cmenu[<?php echo $nindex ?>]" size="20" value="<?php echo $ogroup->cmenu?>"></td>
		<td><select name="cfunc[<?php echo $nindex ?>]" style="width:105px">
			<option value="" style="width:200px"></option>
<?php
		$afunction=get_defined_functions();	
		$nloop=count($afunction['user']); 
		for($ii=0;$ii<$nloop;$ii++) {
			if(strtolower(substr($afunction['user'][$ii],0,2))=="m_") {
				($ogroup->cfunction==$afunction['user'][$ii])?$cselected="selected":$cselected="";
	
?>
			<option value="<?php echo $afunction['user'][$ii] ?>" <?php echo $cselected ?>><?php echo $afunction['user'][$ii] ?></option>
<?php
			}
		}
?>
		
			</select></td>
		<td><input type="text" name="cparam[<?php echo $nindex ?>]" size="25" value="<?php echo stripslashes($ogroup->cparam) ?>"></td>
		<td><input type="text" name="nurut[<?php echo $nindex ?>]" size="4" value="<?php echo $ogroup->nurut ?>"></td>
<?php
			($ogroup->is_main)?$ccheck="checked":$ccheck="";
?>
        <td><input type="checkbox" name="bismain[<?php echo $nindex ?>]" <?php echo $ccheck ?>></td>
<?php
		if(USERLEVEL>=bc_supervisor) {
			($ogroup->bhide)?$ccheck="checked":$ccheck=""; 
?>
		<td><input type="checkbox" name="bhide[<?php echo $nindex ?>]" <?php echo $ccheck ?>></td>
<?php
			($ogroup->bsecure)?$ccheck="checked":$ccheck="";
?>
		<td><input type="checkbox" name="bsecure[<?php echo $nindex ?>]" <?php echo $ccheck ?>></td>
		<td><input type="checkbox" name="bdelete[<?php echo $nindex ?>]"></td>
		<input type="hidden" name="nid[<?php echo $nindex ?>]" value="<?php echo $ogroup->nid ?>">		
	</tr>

<?php
		}
	$nindex++;
	}
?>
	
	<tr>
		<td colspan="10"><br><hr><br></td>
	</tr>

	<tr><th colspan="7">New Menu</th></tr>
	<tr>
		<td><select name="cgroup[<? echo $nindex ?>]" style="width:105px">
			<option style="width:200px"></option>
<?php
	$csql="select nid, cgroup, bhide from  ".PREFIX."groupfrontmenus order by nurut";
	$nresult=gcms_query($csql);
	while ($ogroup=gcms_fetch_object($nresult)) {
		($ogroup->bhide)?$copt=$ogroup->cgroup." (hide)":$copt=$ogroup->cgroup;
?>
			<option value="<?php echo $ogroup->nid ?>"><?php echo $copt ?></option>
<?php
		}
?>		
		</select></td>
		<td><input type="text" name="cmenu[<?php echo $nindex ?>]" size="20"></td>
		<td><select name="cfunc[<?php echo $nindex ?>]" style="width:105px">
			<option value="" style="width:200px"></option>
<?php
	$afunction=get_defined_functions();	
	$nloop=count($afunction['user']); 
	for($ii=0;$ii<$nloop;$ii++) {
		if(strtolower(substr($afunction['user'][$ii],0,2))=="m_") {
?>
			<option value="<?php echo $afunction['user'][$ii] ?>"><?php echo $afunction['user'][$ii] ?></option>
<?php
		}
	}
?>
		
		</select></td>
		<td><input type="text" name="cparam[<?php echo $nindex ?>]" size="25"></td>
		<td><input type="text" name="nurut[<?php echo $nindex ?>]" size="4" value="0"></td>
        <td><input type="checkbox" name="bismain[<?php echo $nindex ?>]"></td>
		<td><input type="checkbox" name="bhide[<?php echo $nindex ?>]"></td>
		<td colspan="2"><input type="checkbox" name="bsecure[<?php echo $nindex ?>]" checked></td>
	</tr>
	<tr><td colspan="8"><input type="submit" name="csubmit" value="Apply Updates"></td></tr>
</form>
</table>