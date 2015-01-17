<table class="table_admin2">
	<tr>
		<th align="left" colspan="2"><h2>Bandung Function</h2><br></th>
	</tr>
	<tr>
		<th align="left">Name Function</th>
		<th align="left"></th>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
<?php
// Bandung function list
$afunction=get_defined_functions();	
$nloop=count($afunction['user']); 
foreach($afunction['user'] as $cfuntion) {
	if(strtolower(substr($cfuntion,0,2))=="b_") {
?>
	<tr>
		<td><?php echo $cfuntion ?></td>
		<td></td>
	</tr>
<?php
	}
}
?>
	<tr>
		<th align="left" colspan="2"><br><br><h2>Menu Function</h2><br></th>
	</tr>
	<tr>
		<th align="left">Name Function</th>
		<th align="left"></th>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
<?php
// Menu function list
$afunction=get_defined_functions();	
$nloop=count($afunction['user']); 
foreach($afunction['user'] as $cfuntion) {
	if(strtolower(substr($cfuntion,0,2))=="m_") {
?>
	<tr>
		<td><?php echo $cfuntion ?></td>
		<td></td>
	</tr>
<?php
	}
}
?>
	<tr>
		<th align="left" colspan="2"><br><br><h2>Startup Function</h2><br></th>
	</tr>
	<tr>
		<th align="left">Name Function</th>
		<th align="left"></th>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
<?php
$afunction=get_defined_functions();	
$nloop=count($afunction['user']); 
foreach($afunction['user'] as $cfuntion) {
	if(strtolower(substr($cfuntion,0,2))=="s_") {
?>
	<tr>
		<td><?php echo $cfuntion ?></td>
		<td></td>
	</tr>
<?php
	}
}
?>
	<tr>
		<th align="left" colspan="2"><br><br><h2>Others Function</h2><br></th>
	</tr>
	<tr>
		<th align="left">Name Function</th>
		<th align="left"></th>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
<?php
$afunction=get_defined_functions();	
$nloop=count($afunction['user']); 
foreach($afunction['user'] as $cfuntion) {
	if(strtolower(substr($cfuntion,0,2))<>"m_" and 
	   strtolower(substr($cfuntion,0,2))<>"b_" and 
	   strtolower(substr($cfuntion,0,2))<>"s_") {
?>
	<tr>
		<td><?php echo $cfuntion ?></td>
		<td></td>
	</tr>
<?php
	}
}
?>
</table>