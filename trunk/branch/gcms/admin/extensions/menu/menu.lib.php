<?php
/**
 * sebagian besar fungsi dipindahkan ke dalam library utama
 */

 /**
 * Action default pada form
 *
 * @return unknown
 */
function menu_urlpage() {
    return $_SERVER['PHP_SELF']."?page=".$_REQUEST['page'];
}

function m_menu_hello(){
    global $bva_levelname;
    echo "<b>Hello Word</b><br>User Level (USERLEVEL) : ".ucfirst($bva_levelname[USERLEVEL]).
        "<br>Path Extension (PATHEXTENSION) : ".PATHEXTENSION;
}

function m_menu_sayit($cparam){
    echo $cparam;
}
?>
