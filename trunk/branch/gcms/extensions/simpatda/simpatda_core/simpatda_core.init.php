<?php
/*
GROUP:: Simpatda
NAME:: Simpatda Core
TYPE:: backend
LEVEL:: 1
DEPENDENCY:: 
INFO:: -
VERSION:: 0.1
AUTHOR::  Banu
URL:: 
SOURCE::
COMMENT 
*/
$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

?> 
<link rel="stylesheet" type="text/css" media="screen" href="script/jquery/themes/ui.jqgrid.css" />
<script src="script/jquery/jquery.js" type="text/javascript"></script>
<script src="script/jquery/grid.locale-id.js" type="text/javascript"></script>
<script src="script/jquery/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="<?=$expath?>ajaxdo.js" type="text/javascript"></script>