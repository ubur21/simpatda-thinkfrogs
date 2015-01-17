<?php
/*
GROUP::Entri Data
NAME:: Simulasi
TYPE:: frontend
LEVEL:: 1
DEPENDENCY:: 
INFO:: -
VERSION:: 0.1
AUTHOR::  widi
URL:: 
SOURCE:: 
*/
$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";


 
?> 
<script type="text/javascript">
function closeForm( x ) {
	var elem = document.getElementById( x );
	elem.className = '';
	elem.className = 'hidden';
}
function showForm( x ) {
	var elem = document.getElementById( x );
	elem.className = '';
	elem.className = 'show';
}
function func_Baru() {
	alert( 'Baru' );
}
function func_Edit() {
}
function func_Simpan() {
	var kodeLokasi = document.getElementById('kodeLokasi').value;
	alert( kodeLokasi );
}
function func_Keluar() {
}
function func_Cetak() {
}
function func_Hapus() {
}
</script>
