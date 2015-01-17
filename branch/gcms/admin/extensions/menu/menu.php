<?php
if(basename( __FILE__ )==basename( $_SERVER['PHP_SELF'])) die();
?>
<div id="navsite">
    <ul>
        <li><a id="<?php if($_REQUEST['cpage']=="group") echo "current"?>" href="<?php echo b_urlact()."&cpage=group"?>">Group Menu</a></li>
        <li><a id="<?php if($_REQUEST['cpage']=="menu") echo "current"?>" href="<?php echo b_urlact()."&cpage=menu"?>">Menu</a></li>
        <li><a id="<?php if($_REQUEST['cpage']=="granter" or $_REQUEST['cpage']=="") echo "current"?>" href="<?php echo b_urlact()."&cpage=granter"?>">Menu Granter</a></li>
    </ul>
</div>
<?php
switch ($_REQUEST['cpage']){
    case "group"    :   b_pathinclude("menu_group.php");
        break;
    case "menu"     :   b_pathinclude("menu_menu.php");
        break;
    case "granter"  :   b_pathinclude("menu_granter.php");
        break;
    default             :   b_pathinclude("menu_granter.php");
        break;
}
?>