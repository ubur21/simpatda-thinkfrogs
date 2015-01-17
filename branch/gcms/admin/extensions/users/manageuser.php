<?php
if(basename( __FILE__ )==basename( $_SERVER['PHP_SELF'])) die();

$bmuka=true;
$bedit=false;
$cmess="";

if(isset($_POST['csub'])) {
    switch($_POST['csub']) {
        case 'Create User' : 
            if(trim($_POST['cuser'])=="")
                $cmess.="<li>Please choose an ID for your user</li>";
            if(trim($_POST['creal'])=="")
                $cmess.="<li>Please specify your real name</li>";
            if($_POST['cpass']<>$_POST['cconpass'])
                $cmess.="<li>Your password entries didn't match</li>";
            if(!b_emailcheck($_POST['cemail']))
                $cmess.="<li>Please specify your email address in correct format</li>";

            $csql="select count(nid) from ".PREFIX."users where cuser='".$_POST[cuser]."'";
            if(b_fetch($csql)>0) $cmess.="Username ".$_POST['cuser']." was exist<br>";
            if($cmess=="") {
                $csql="insert into ".PREFIX."users
                            (cuser, cpass, cname, cemail, nstatus) values
                            ('".$_POST['cuser']."','".md5($_POST['cpass'])."',
                            '".$_POST['creal']."', '".$_POST['cemail']."',
                            '".$_POST['clevel']."')";
                gcms_query($csql);
                unset($_POST);
                $cmess="User has been created";
            }
            break;
        case 'Edit User'   : 
            ($_POST['nid']<>"")?$bedit=true:$bedit=false;
            break;
        case 'Update User' : 
            $bedit=true;
            if($_POST['cpassedit']<>$_POST['cconpassedit'])
                $cmess2.="<li>Your password entries didn't match</li>";
            if(!b_emailcheck($_POST['cemailedit']))
                $cmess2.="<li>Please specify your email address in correct format</li>";
            if($cmess2=="") {
                (trim($_POST['cpassedit'])<>"")?$cpass=", cpass='".md5($_POST['cpassedit'])."' ":$cpass="";
                (trim($_POST['crealedit'])<>"")?$cname=", cname='".$_POST['crealedit']."' ":$cname="";
                (trim($_POST['cemailedit'])<>"")?$cemail=", cemail='".$_POST['cemailedit']."' ":$cemail="";
                (b_admin($_POST['nid'])?$cleveluser=4:$cleveluser=$_POST['cleveluser']);
                $csql="update ".PREFIX."users set cuser=cuser,
                        nstatus='".$cleveluser."' $cpass $cname $cemail
                        where  nid='".$_POST['nid']."'";
                gcms_query($csql);
                $csql="delete from ".PREFIX."granted where nid_users='".$_POST['nid']."'";
                gcms_query($csql);
		                         
                $nloop=count($_POST['clevel']);
                for($ii=0;$ii<$nloop;$ii++){
                    if($_POST['modul'][$ii]>0) {		                         		
                        $csql="insert into ".PREFIX."granted (nid_users, nid_moduls, nstatus)
                                    values ('".$_POST['nid']."', '".$_POST['modul'][$ii]."', '".$_POST['clevel'][$ii]."')";  		
                        gcms_query($csql);
                    }
                }
                $cmess2="User has been updated";
                //unset($_POST);
                //$bedit=false;
            }
            break;
        case 'Delete User' : 
            $csql="delete from ".PREFIX."granted where nid_users='".$_POST['nid']."'";
            gcms_query($csql);
            $csql="delete from ".PREFIX."users where nid='".$_POST['nid']."'";
            gcms_query($csql);
            $cmess2="User has been deleted";
            unset($_POST);
            $bedit=false;
            break;
		case 'Cancel'      : 
            unset($_POST);
            $bmain=true;
            $bedit=false;
            break;
    }
}

if($bmuka) {
?>

<?php 
    if($cmess<>"") {
?>
<br>
<div class="warning"><ul><?php echo $cmess ?></ul></div>
<?php 
} ?>

<h1>Create User</h1>
<table class="table_admin2">
	<form action="<?php echo b_urlact() ?>" method="POST">
	<tr>
		<td width="20%">User ID</td>
		<td><input type="text" name="cuser" size="20" value="<?php echo $_POST['cuser'] ?>" class="field" id="focushere">
        &nbsp;&nbsp;User Management Level&nbsp;&nbsp;<select name="clevel">
<?
for($ii=0;$ii<USERLEVEL;$ii++) {
?>
            <option value="<?php echo $ii ?>" <?php echo $cselected ?>><?php echo ucfirst($GLOBALS['bva_levelname'][$ii]) ?></option>
<?
}
?>
            </select>
        </td>
	</tr>
	<tr>
		<td>Password</td>
		<td><input type="password" name="cpass" size="10" class="field"></td>
	</tr>
    <tr>
		<td>Confirm Password</td>
		<td><input type="password" name="cconpass" size="10" class="field"></td>
	</tr>
	<tr>
		<td>Real Name</td>
		<td><input type="text" name="creal" size="40" value="<?php echo $_POST['creal'] ?>" class="field"></td>
	</tr>
    <tr>
		<td>Email</td>
		<td><input type="text" name="cemail" size="30" value="<?php echo $_POST['cemail'] ?>" class="field"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="csub" value="Create User"></td>
	</tr>
	</form>
</table>

<?php if($cmess2<>"") {?>
<br>
<div class="warning"><ul><?php echo $cmess2 ?></ul></div>
<?php } ?>

<h1><a name="edituser">Edit User</a></h1>
<table class="table_admin2">
	<form action="<?php echo b_urlact() ?>#edituser" method="POST">
<?php
(!b_admin(b_getuserlogin()))?$cwhere=" where nstatus<'".USERLEVEL."' or nid='".$_SESSION['nid_login']."'":$cwhere="";
$csql="select * from ".PREFIX."users $cwhere order by cuser ";
$nresult=gcms_query($csql);
$xxx = false;
while($ouser=gcms_fetch_object($nresult)) {
  if (!$xxx) {
    $xxx = true;
?>
	<tr>
		<td width="20%">Username</td>
		<td>
		<select name="nid" class="field">
			<option value=""></option>
<?php
  }
  ($ouser->nid==$_POST['nid'])?$cselect="selected":$cselect="";
		echo "<option value=\"$ouser->nid\" $cselect >$ouser->cname ($ouser->cuser)</option>";
}
?>
			</select>
			&nbsp;&nbsp;<input type="submit" name="csub" value="Edit User"></td>
	</tr>
	</form>

<?php
if (!$xxx) {
?>
	<tr>
		<td>You can't edit anyone!</td>
	</tr>
<?php
}
?>
</table>
<?php } ?>

<?php if($bedit) { ?>
<br><form action="<?php echo b_urlact() ?>" method="POST" name="myform" id="formedit">
<table class="table_admin2">
	
		<input type="hidden" name="nid" value="<?php echo $_POST['nid'] ?>">
<?php
$csql="select * from ".PREFIX."users where nid='".$_POST['nid']."'";
$nresult=gcms_query($csql);
$ouser=gcms_fetch_object($nresult);
?>
	<tr>
		<td width="20%">User ID</td>
		<td><input type="text" name="cuser" size="20" value="<? echo $ouser->cuser ?>" disabled>
<?php 
if(!b_admin($_POST['nid']) and b_getuserlogin()<>$_POST['nid']) {
?>        
		&nbsp;&nbsp;User Management Level&nbsp;&nbsp;
        <select name="cleveluser">
<?php
		for($ii=0;$ii<USERLEVEL;$ii++) {
    		($ii==$ouser->nstatus)?$cselected="selected":$cselected="";
?>
            	<option value="<?php echo $ii ?>" <?php echo $cselected ?>><?php echo ucfirst($GLOBALS['bva_levelname'][$ii]) ?></option>
<?php
	}
	}
?>
        </td>
	</tr>
	<tr>
		<td>New Password</td>
		<td><input type="password" name="cpassedit" size="10" class="field">&nbsp;&nbsp;<em>Leave this blank if you wouldn't change your password</em></td>
	</tr>
    <tr>
		<td>Confirm Password</td>
		<td><input type="password" name="cconpassedit" size="10" class="field"></td>
	</tr>
	<tr>
		<td>Real Name</td>
		<td><input type="text" name="crealedit" size="40" value="<?php echo $ouser->cname ?>" class="field"></td>
	</tr>
    <tr>
		<td>Email</td>
		<td><input type="text" name="cemailedit" size="30" value="<?php echo $ouser->cemail ?>" class="field"></td>
	</tr>

<?php if(USERLEVEL>=bc_supervisor and !b_admin($_POST['nid']) and b_getuserlogin()<>$_POST['nid']) { ?>
	<tr>
		<td colspan="2"><h2>Grant Modul</h2></td>
	</tr>
	<tr>
		<td colspan="2">
		<table>
<?php
$csql="select * from ".PREFIX."moduls order by nid";
$nresult=gcms_query($csql);
$i=0;
while($omoduls=gcms_fetch_object($nresult)) {
	(b_fetch("select count(nid) from ".PREFIX."granted where nid_users='".$_POST['nid']."' and nid_moduls='$omoduls->nid'")>0)?$ccheck="checked":$ccheck="";
	$ainfo=b_readinit(str_replace(".php",".init.php",$omoduls->cpath));
	if(($ainfo['level']<=USERLEVEL or b_admin(b_getuserlogin())) and $ainfo['type']<>"lib") { 
		//(strtolower($ainfo['type'])=="admin")?$ctype="(Admin)":$ctype="(Frontend)";
		if(strtolower($ainfo['type'])=="admin") {
?>
			<tr>
				<td><input type="checkbox" name="modul[<?php echo $i ?>]" value="<?php echo $omoduls->nid ?>" <?php echo $ccheck ?>>&nbsp;
				<?php echo $ainfo['name'] ?>&nbsp;&nbsp;
				</td>
				<td>&nbsp;&nbsp;Modul Level&nbsp;&nbsp;&nbsp;&nbsp;<select name="clevel[<?php echo $i ?>]"><?php
for($ii=$ainfo['level'];$ii<USERLEVEL;$ii++) {
	if($check="checked") {
		$nstatus=b_fetch("select nstatus from ".PREFIX."granted where nid_users='".$_POST['nid']."' and nid_moduls='$omoduls->nid'");
		($nstatus==$ii)?$cselected="selected":$cselected="";
	}
?>
    				<option value="<?php echo $ii ?>" <?php echo $cselected ?>><?php echo ucfirst($GLOBALS['bva_levelname'][$ii]) ?></option>
<?php
}
?>
					</select>
				</td>
			</tr>
<?php
		$i++;
		}
	
	}
}
?>
			<tr>
				<td colspan="2"><hr></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="checkall" onclick="select_all('modul',this.checked)">&nbsp;Select All for
				</td>
				<td>&nbsp;&nbsp;Modul Level&nbsp;&nbsp;&nbsp;&nbsp;<select name="levelall" onclick="set_all('clevel',this.value)"><?php
for($ii=0;$ii<USERLEVEL;$ii++) {
?>
    				<option value="<?php echo $ii ?>" <?php echo $cselected ?>><?php echo ucfirst($GLOBALS['bva_levelname'][$ii]) ?></option>
<?php
}
?>
					</select>
				</td>
			</tr>
		</table>
		</td>
	</tr>
<?php } ?>
	<tr>
		<td colspan="2"><br><input type="submit" name="csub" value="Update User">&nbsp;
<?php (b_admin($_POST['nid'])?$cdisabled="disabled":$cdisabled="") ?>
                        <input type="submit" name="csub" value="Delete User" onclick="return confirm('Are you sure to delete this user?')" <?php echo $cdisabled ?>>&nbsp;
						<input type="submit" name="csub" value="Cancel">
		</td>
	</tr>
	
</table></form>
<?php } ?>
