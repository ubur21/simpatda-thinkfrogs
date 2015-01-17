<?php
/*
GROUP:: manage
NAME:: User Management
TYPE:: Admin
LEVEL:: 1
INFO:: Membuat, mengedit atau menghapus user
VERSION:: 1.3
AUTHOR:: Riyogarta, Laksono
URL:: 
SOURCE:: 
*/
?>
<script type="text/javascript">
<!--
var formblock;
var forminputs;
var formselect;

function prepare() {
    formblock= document.getElementById('formedit');
    forminputs = formblock.getElementsByTagName('input');
    formselect = formblock.getElementsByTagName('select');
}

function select_all(name, value) {
    for (i = 0; i < forminputs.length; i++) {
        var regex = new RegExp(name, "i");
        if (regex.test(forminputs[i].getAttribute('name'))) {
            if (value == '1') {
                forminputs[i].checked = true;
            } else {
                forminputs[i].checked = false;
            }
        }
    }
}

function set_all(name, value) {
    for (i = 0; i < formselect.length; i++) {
        var regex = new RegExp(name, "i");
        if (regex.test(formselect[i].getAttribute('name'))) {
            formselect[i].value = value;
        }
    }
}

if (window.addEventListener) {
    window.addEventListener("load", prepare, false);
} else if (window.attachEvent) {
    window.attachEvent("onload", prepare)
} else if (document.getElementById) {
    window.onload = prepare;
}
//-->
</script>