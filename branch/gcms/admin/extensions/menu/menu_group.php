<?php
if(basename( __FILE__ )==basename( $_SERVER['PHP_SELF'])) die();

$bwarning=false;
if(isset($_POST['csubmit'])) {
    $nloop=count($_POST['cgroup']);
    for($ii=0;$ii<$nloop;$ii++) {
        ($_POST['bhide'][$ii])?$bhide=1:$bhide=0; 
        if(isset($_POST[nid][$ii])) {
            if($_POST['bdelete'][$ii]) {
                $csql="delete from ".PREFIX."frontmenus where nid_groupfrontmenus='".$_POST['nid'][$ii]."'";
                gcms_query($csql);
                $csql="delete from ".PREFIX."groupfrontmenus where nid='".$_POST['nid'][$ii]."'";
            } else
                $csql="Update ".PREFIX."groupfrontmenus
                            set cgroup='".addslashes($_POST['cgroup'][$ii])."',
                            nurut='".$_POST['nurut'][$ii]."',
                            bhide='".$bhide."'
                            where nid='".$_POST['nid'][$ii]."'";
        } else {
            if(trim($_POST['cgroup'][$ii])<>"")
                $csql="insert into ".PREFIX."groupfrontmenus
                    (cgroup, nurut, bhide) values 
                    ('".addslashes($_POST['cgroup'][$ii])."', '".$_POST['nurut'][$ii]."', '".$bhide."')";
            else 
                $csql="select * from ".PREFIX."groupfrontmenus;";
        }
        gcms_query($csql);
    }
    $bwarning=true;
}
if($bwarning) {
?>
<br><div class="warning">Content has been updated</div><br>
<?php	
}
?>
<table class="table_admin2">
<form action="<?php b_urlact()."&cpage=".$_REQUEST['cpage'] ?>" method="POST">
<tr>
    <th>Name of Group</th>
    <th>Number</th>
    <th>Hide</th>
    <th>Delete</th>
</tr>
<?php
$csql="select * from ".PREFIX."groupfrontmenus order by nurut";
$nresult=gcms_query($csql);
$nindex=0;
while($ogroup=gcms_fetch_object($nresult)) {
?>
    <input type="hidden" name="nid<?php echo "[".$nindex."]" ?>" value="<?php echo $ogroup->nid ?>">
<tr>
    <td><input type="text" name="cgroup<?php echo "[".$nindex."]" ?>" value="<?php echo stripslashes($ogroup->cgroup) ?>" size="50"></td>
    <td><input type="text" name="nurut<?php echo "[".$nindex."]" ?>" value="<?php echo $ogroup->nurut ?>" size="1"></td>
    <td><input type="checkbox" name="bhide<?php echo "[".$nindex."]" ?>" <?php if($ogroup->bhide) echo checked ?>></td>
<?php
    if(USERLEVEL>=bc_supervisor) {
?>
    <td><input type="checkbox" name="bdelete<?php echo "[".$nindex."]" ?>"></td>
</tr>
<?php
    }
    $nindex++;
}
?>
<tr>
    <td colspan="7"><br><hr><br></td>
</tr>
<tr>
    <th colspan="4">New Group</th>
</tr>
<tr>
    <td><input type="text" name="cgroup<?php echo "[".$nindex."]" ?>" size="50"></td>
    <td><input type="text" name="nurut<?php echo "[".$nindex."]" ?>" value="0" size="1"></td>
    <td colspan="2"><input type="checkbox" name="bhide<?php echo "[".$nindex."]" ?>" <?php if($ogroup->bhide) echo checked ?>></td>
</tr>
<tr>
    <td colspan="4"><input type="submit" name="csubmit" value="Apply Updates"></td>
</tr>
</form>
</table>