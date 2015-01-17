<?php

/* file ini hanya untuk milih mau ke form atau mau ke main */

if (!isset($_REQUEST['form'])) include('main.php');
else include('form.php');
?>