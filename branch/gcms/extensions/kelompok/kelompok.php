<?php
 	if(basename( __FILE__ )==basename( $_SERVER['PHP_SELF'])) die();
?>

<table id="htmlTable" class="scroll"></table>
<div id="htmlPager" class="scroll"></div>

<table>
    <tr>
        <td><input id="btn_submit" type="button" name="preview" value="PREVIEW" onclick="printReport()"/>
        </td>
    </tr>
</table>