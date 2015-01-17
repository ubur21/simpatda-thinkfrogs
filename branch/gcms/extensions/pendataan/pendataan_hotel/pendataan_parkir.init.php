<?php
/*
GROUP::Entri Data
NAME:: SKPD
TYPE:: frontend
LEVEL:: 1
DEPENDENCY:: 
INFO:: -
VERSION:: 0.1
AUTHOR::  umi
URL:: 
SOURCE::
COMMENT 
*/
$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";
?> 
<script src="<?=$expath?>ajaxdo.js" type="text/javascript"></script>

<script src="script/jquery/jquery.js" type="text/javascript"></script>
<script src="script/jquery/grid.locale-id.js" type="text/javascript"></script>
<script src="script/jquery/jquery.jqGrid.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="screen" href="script/jquery/themes/ui.jqgrid.css" />

<script type="text/javascript" src="script/jquery/jquery.bgiframe.min.js"></script>
<script type="text/javascript" src="script/jquery/jquery.ajaxQueue.js"></script>
<script type="text/javascript" src="script/jquery/thickbox-compressed.js"></script>
<script type="text/javascript" src="script/jquery/jquery.autocomplete.min.js"></script>
<link rel="stylesheet" type="text/css" href="script/jquery/jquery.autocomplete.css" />

<script type="text/javascript" src="yui/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="yui/element/element-beta-min.js"></script>
<link rel="stylesheet" type="text/css" href="yui/tabview/assets/skins/sam/tabview.css" />
<script type="text/javascript" src="yui/tabview/tabview-min.js"></script>

<script type="text/javascript" src="script/calendar/calendar.js"></script>
<script type="text/javascript" src="script/calendar/calendar-en.js"></script>
<script type="text/javascript" src="script/calendar/calendar-setup.js"></script>

<link rel="stylesheet" type="text/css" media="all" href="script/calendar/calendar-system.css" title="win2k-cold-1" />

<!-- jquery date custom start-->
<script type="text/javascript">
$(document).ready( function() {
	//fungsi tanggal start//
	jquery_date( "tgl_proses", "btn_tgl_proses" );
	jquery_date( "tgl_entry", "btn_tgl_entry" );
	jquery_date( "periode_awal", "btn_periode_awal" );
	jquery_date( "periode_akhir", "btn_periode_akhir" );
	//fungsi tanggal end//
	
	//detail parkir start//
	ajax_do('<?=$expath?>SetData.php?rekening=parkir' );//var kodeRek;
		//tambah data//
		$("#tambah_detail").click( function() {
			//ambil id lama, tambah 1, simpan hasil di hidden id.//
			var id = document.getElementById('id_isi_detail').value; id=parseInt( id ) + 1; document.getElementById('id_isi_detail').value = id;

			var str = " <tr id='" + id + "' "
			         +" class='isi_detail'> ";
			
			var data = kodeRek;
			str += "<td>"+data+"</td><td><input class='field_angka' id='dasar_tarif' type='text' name='dasar_tarif' value='' onblur=\"hitung_pajak();roundNumber(this.value, 2);\" onkeyup=\"hitung_pajak();\" /></td><td><input class='field_angka' id='persen_tarif' type='text' name='persen_tarif' value='...' size='4' /></td><td><input class='field_angka' id='pajak' type='text' name='pajak' value='' size='10' /></td>";
			
			str += "<td><input type='button' onclick=\"hapus_data('"+id+"');\" name='hapus' value='hapus' />";
			
			$("#detail").append( str );
		});
		
		//hapus semua data//
		$("#hapus_detail_all").click( function() {
			$("tr.isi_detail").remove();
			
			//reset hidden id. to 0//
			document.getElementById('id_isi_detail').value=0;
		});
	//detail parkir end//
	
	//reset nomor spt 
	$("#reset_nomor_spt").click( function() {
		document.getElementById( 'nomor_spt' ).value = '';
	});
});

function get_ptarif( val ) {
	$("#persen_tarif").val( val );
	hitung_pajak();
}
function hitung_pajak () {
	var a = $("#dasar_tarif").val();
	var b = $("#persen_tarif").val();
	var pajak = (a*b)/100;
	document.getElementById( 'pajak' ).value = pajak.toFixed(2);//decimal 2 angka diblakang koma
	document.getElementById( 'pajak_view' ).value= pajak.toFixed(2);
}
function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	document.getElementById( 'dasar_tarif' ).value = result.toFixed(2);
}

function hapus_data( row ) {
	$("tr#"+row).remove();
}


function jquery_date( target, xbutton ) {
	Calendar.setup({
		inputField     :    target,      // id of the input field
		ifFormat       :    "%d-%m-%Y",       // format of the input field %m/%d/%Y %I:%M %p
		showsTime      :    false,            // will display a time selector
		button         :    xbutton,   // trigger for the calendar (button ID)
		singleClick    :    false,           // double-click mode
		step           :    1                // show all years in drop-down boxes (instead of every other year as default)
	});
}
</script>
<!-- jquery date custom  end-->
	