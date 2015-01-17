<?php
if(basename( __FILE__ )==basename( $_SERVER['PHP_SELF'])) die();

$bedit=false;
$cmess="";

if(isset($_POST['csubmit'])) {
    switch($_POST['csubmit']) {
        case 'Go'   	     : 
            ($_POST['nid']<>"")?$bedit=true:$bedit=false;
            break;
        case 'Apply Updates' : 
            $bedit=true;
            $csql="delete from ".PREFIX."grantedfrontmenus where nid_users='".$_POST['nid']."'";
            gcms_query($csql);
            $nloop=count($_POST['clevel']);
            for($ii=0;$ii<$nloop;$ii++){
                if($_POST['menu'][$ii]>0) {
                    $csql="insert into ".PREFIX."grantedfrontmenus (nid_users, nid_frontmenus, nstatus)
                        values('".$_POST['nid']."', '".$_POST['menu'][$ii]."', '".$_POST['clevel'][$ii]."')"; 
                    gcms_query($csql);
                }
            }
            $cmess2="User grant has been updated";
            //unset($_POST);
            //$bedit=false;
            break;
        case 'Cancel'        : 
            unset($_POST);
            $bmain=true;
            $bedit=false;
            break;
    }
}
?>

<?php 
if($cmess2<>"") {?>
<br>
<div class="warning"><ul><?php echo $cmess2 ?></ul></div><br/>
<?php 
} 
?>

<table class="table_admin2">
	<form action="<?php echo b_urlact()."&cpage=".$_REQUEST['cpage']  ?>#go" method="POST">
<?php
(!b_admin($_SESSION['nid_login']))
    ?$cwhere=" where (nstatus<'".USERLEVEL."' or nid<>'".$_SESSION['nid_login']."') and cuser<>'admin'"
    :$cwhere=" where cuser<>'admin'";

$csql="select * from ".PREFIX."users $cwhere order by cuser ";
$nresult=gcms_query($csql);
if(gcms_fetch_row($nresult)) {
?>
    <tr>
        <td width="20%">Username</td>
        <td>
            <select name="nid" class="field">
            <option value=""></option>
<?php
    while($ouser=gcms_fetch_object($nresult)) {
        ($ouser->nid==$_POST['nid'])?$cselect="selected":$cselect="";
        echo "<option value=\"$ouser->nid\" $cselect >$ouser->cname ($ouser->cuser)</option>";
        }
?>
            </select>&nbsp;&nbsp;<input type="submit" name="csubmit" value="Go"></td>
    </tr>
    </form>
<?php
} else {
?>
    <tr>
        <td>You can't edit anyone!</td>
    </tr>
<?php
}
?>
</table>

<?php 
if($bedit) { 
?>
<br>
<form action="<?php echo b_urlact()."&cpage=".$_REQUEST['cpage']  ?>" method="POST" name="myform" id="formedit">
    <table class="table_admin2">
    <input type="hidden" name="nid" value="<? echo $_POST['nid'] ?>">
<?php 
    if(USERLEVEL>=bc_supervisor and !b_admin($_POST['nid']) and $_SESSION['nid_login']<>$_POST['nid']) { ?>
    <tr>
        <td colspan="2"><h2>Grant Menu</h2></td>
    </tr>
    <tr>
        <td colspan="2">
        <table>
<?php
        $csql="select a.nid, a.cmenu, b.cgroup from ".PREFIX."frontmenus as a
                inner join ".PREFIX."groupfrontmenus as b on a.nid_groupfrontmenus=b.nid
                order by b.nurut, b.cgroup, a.nurut, a.cmenu";
        $nresult=gcms_query($csql);
        $i=0;
        while($omenus=gcms_fetch_object($nresult)) {
            (b_fetch("select count(nid) from ".PREFIX."grantedfrontmenus where nid_users='".$_POST['nid']."' and nid_frontmenus='$omenus->nid'")>0)?$ccheck="checked":$ccheck="";
?>
            <tr>
                <td><input type="checkbox" name="menu[<?php echo $i ?>]" value="<?php echo $omenus->nid ?>" <?php  echo $ccheck ?>>&nbsp;<?php echo $omenus->cgroup." - ".$omenus->cmenu ?>&nbsp;&nbsp;                </td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;Menu Level&nbsp;&nbsp;&nbsp;&nbsp;<select name="clevel[<?php echo $i ?>]">
<?php
            for($ii=0;$ii<USERLEVEL;$ii++) {
                if($check="checked") {
                    $nstatus=b_fetch("select nstatus from ".PREFIX."grantedfrontmenus where nid_users='".$_POST['nid']."' and nid_frontmenus='$omenus->nid'");
                ($nstatus==$ii)?$cselected="selected":$cselected="";
            }
?>
                    <option value="<? echo $ii ?>" <? echo $cselected ?>><? echo ucfirst($GLOBALS['bva_levelname'][$ii]) ?></option>
<?php
        }
?>
                    </select>
                </td>
<?php
        $i++;
}
?>
                </tr>
                <tr>
                    <td colspan="2"><hr></td>
                </tr>
                <tr>
                    <td><input type="checkbox" name="checkall" onclick="select_all('menu',this.checked)">&nbsp;Select All for</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;Menu Level&nbsp;&nbsp;&nbsp;&nbsp;<select name="clevelall" onclick="set_all('clevel',this.value)">
<?php
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
<?php 
} 
?>
    <tr>
        <td colspan="2"><br>
            <input type="submit" name="csubmit" value="Apply Updates">&nbsp;
			<input type="submit" name="csubmit" value="Cancel">
        </td>
    </tr>
</table>
</form>
<?php 
} 
?>