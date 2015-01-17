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

<script type="text/javascript" src="script/jquery/jquery.js"></script>
<script type="text/javascript" src="script/jquery/grid.locale-id.js"></script>
<script type="text/javascript" src="script/jquery/jquery.jqGrid.min.js"></script>

<script type="text/javascript" src="script/jquery/jquery.form.js"></script>
<script type="text/javascript" src="script/jquery/jquery.bgiframe.min.js"></script>
<script type="text/javascript" src="script/jquery/jquery.ajaxQueue.js"></script>
<script type="text/javascript" src="script/jquery/thickbox-compressed.js"></script>
<script type="text/javascript" src="script/jquery/jquery.autocomplete.min.js"></script>
<link rel="stylesheet" type="text/css" href="script/jquery/jquery.autocomplete.css" />

<script type="text/javascript" src="script/jquery/jquery-ui-1.7.1.custom.min.js"></script>
<script type="text/javascript" src="script/jquery/ui/i18n/ui.datepicker-id.js"></script>
<script type="text/javascript" src="script/jquery/ui/minified/ui.tabs.min.js"></script>
<script type="text/javascript" src="script/jquery/ui/minified/ui.draggable.min.js"></script>
<script type="text/javascript" src="script/jquery/ui/minified/ui.resizable.min.js"></script>
<script type="text/javascript" src="script/jquery/ui/minified/ui.dialog.min.js"></script>
<script type="text/javascript" src="script/jquery/ui/minified/effects.core.min.js"></script>
<script type="text/javascript" src="script/jquery/ui/minified/effects.highlight.min.js"></script>

<link rel="stylesheet" type="text/css" href="script/jquery/themes/humanity/jquery-ui-1.7.2.custom.css" />
<link rel="stylesheet" type="text/css" media="screen" href="script/jquery/themes/ui.jqgrid.css" />

<script type="text/javascript" src="yui/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="yui/element/element-beta-min.js"></script>
<script type="text/javascript" src="yui/tabview/tabview-min.js"></script>
<link rel="stylesheet" type="text/css" href="yui/tabview/assets/skins/sam/tabview.css" />

<script type="text/javascript" src="script/calendar/calendar.js"></script>
<script type="text/javascript" src="script/calendar/calendar-en.js"></script>
<script type="text/javascript" src="script/calendar/calendar-setup.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="script/calendar/calendar-system.css" title="win2k-cold-1" />

<!-- jquery date custom start-->
<script type="text/javascript">
$(document).ready( function() {
	
	//detail walet start//
		//tambah data//
		$("#tambah_detail").click( function() {
			//ambil id lama, tambah 1, simpan hasil di hidden id.//
			var id = document.getElementById('id_isi_detail').value; id=parseInt( id ) + 1; document.getElementById('id_isi_detail').value = id;

			setSingleRowDetail( id );
			
		});
		
		//hapus semua data//
		$("#hapus_detail_all").click( function() {
			$("tr.isi_detail").remove();
			$("input[name=total]").val( '0.00' );
			//reset hidden id. to 0//
			document.getElementById('id_isi_detail').value=0;
		});
	//detail walet end//
	
	//reset nomor spt 
	$("#reset_nomor_spt").click( function() {
		document.getElementById( 'nomor_spt' ).value = '';
	});
});

function get_ptarif( idrek, id ) {
	$.getJSON('<?=$expath?>SetData.php?persentarif='+idrek, function(data) {
		$.each( data, function( index, entry) {
			$("#persen_tarif"+id).val( entry['tarif'] );
			$("#tdasar"+id).val( entry['tdasar'] );
		});
		
		hitung_pajak( id );
	});
	
}

function hitung_pajak( id ) {
	if(  $("#persen_tarif"+id).val() == '...' ) { return false; }
	else {
		//var dasartarif=$("#dasar_tarif"+id).val();
		var persentarif=$("#persen_tarif"+id).val();
		var tdasar=$("#tdasar"+id).val();
		var jumlah=$("#jumlah"+id).val();
		var dasartarif=jumlah*tdasar;
		
		var pajak = persentarif*dasartarif*0.01;
		var totalpajak=0;
		var batas= $("input[name='pajak[]']").length;
		
		$("#pajak"+id).val( pajak.toFixed(2) );
		$("#dasar_tarif"+id).val( dasartarif );
		
		for(var i=1;i<=batas;i++ ) {
			var a = Number( $("#dasar_tarif"+i).val() );
			var b = Number( $("#persen_tarif"+i).val() );
			totalpajak = totalpajak + ( a * b ) / 100;
		}
		
		
		$("#total_pajak").val( totalpajak.toFixed(2) );	
	}
}


function hapus_data( row ) {
	$("tr#"+row).remove();
	var id_now = $("#id_isi_detail").val();
	id_now-=1;
	$("#id_isi_detail").val( id_now );
	hitung_pajak( id_now );
}

function setSingleRowDetail( id ) {
	$.getJSON('<?=$expath?>SetData.php?walet=yes&id='+id, function(data) {
				
		var str = " <tr id='" + id + "' " +" class='isi_detail'> ";
				
		var select;
                select = '<select name="kode_rekening[]" onchange="get_ptarif(this.value,'+id+');" >';
		select+= '<option value="null" selected>....</option>';
		$.each( data, function( key, val ) {
			//select+='<option value="'+val['persen']+'">'+val['koderek']+'</option>';
			select+='<option value="'+val['idrek']+'">'+val['koderek']+'</option>';
		});
		select +='</select>';
				
		str += "<td>"+select+"</td>"
		+"<td><input title='lokasi[]' class='field_angka' id='lokasi"+id+"' type='text' name='lokasi[]' value='' size='4' /></td>"
		+"<td><input title='jumlah[]' class='field_angka' id='jumlah"+id+"' type='text' name='jumlahx[]' value='' size='4' onblur=\"hitung_pajak('"+id+"');\" /></td>"
		+"<td><input title='tdasar[]' class='field_angka' id='tdasar"+id+"' type='text' name='tdasar[]' value='' size='4' /></td>"
		+"<td><input title='dasar pengenaan[]' class='field_angka' id='dasar_tarif"+id+"' type='text' name='dtarif[]' value='' /></td>"
		+"<td><input title='persen tarif[]' class='field_angka' id='persen_tarif"+id+"' type='text' name='ptarif[]' value='...' size='4' /></td>"
		+"<td><input class='field_angka' id='pajak"+id+"' type='text' name='pajak[]' value='' size='10' /></td>"
		+"<td><input type='button' onclick=\"hapus_data('"+id+"');\" name='hapus' value='hapus' />";
			
		$("#detail").append( str );
				
        });       
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
	
