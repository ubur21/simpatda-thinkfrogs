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
	//fungsi tanggal start//
	/*jquery_date( "date_1", "reset_tgl" );
	jquery_date( "date_2", "reset_tgl2" );
	jquery_date( "date_3", "reset_tgl3" );
	jquery_date( "date_4", "reset_tgl4" );*/
	//fungsi tanggal end//
	
	//detail parkir start//
// 	ajax_do('<?=$expath?>SetData.php?rekening=parkir' );//var kodeRek;
		//tambah data//
	/*	$("#tambah_detail").click( function() {
			//ambil id lama, tambah 1, simpan hasil di hidden id.//
			var id = document.getElementById('id_isi_detail').value; 
			id=parseInt( id ) + 1; 
			document.getElementById('id_isi_detail').value = id;
			//alert(id);
			var str = " <tr id='" + id + "' "
			         +" class='isi_detail'> ";			
			//var data = kodeRek;
			str += "<td><input  id=\"jumlah_meja"+id+"\" class='field_angka' type='text' name=\"jumlah_meja"+id+"\" size='30'></td><td><input class='field_angka' id=\"jumlah_kursi"+id+"\" type='text' name=\"jumlah_kursi"+id+"\" size='10' /></td><td><input class='field_angka' id=\"jumlah_tamu"+id+"\" type='text' name=\"jumlah_tamu"+id+"\" size='20' /></td>";
			
			str += "<td><input type='button' onclick=\"hapus_data('"+id+"');\" name='hapus' value='hapus' />";
			
			$("#detail").append( str );
		});
		
		//hapus semua data//
		$("#hapus_detail_all").click( function() {
			$("tr.isi_detail").remove();
			//reset hidden id. to 0//
			document.getElementById('id_isi_detail').value=0;
			document.getElementById( 'total_tarif' ).value=0.00;
		});
	*/
		
});
/*
function hapus_data( row ) {
	var cindex = Number(document.getElementById('id_isi_detail').value);
	if(row < cindex){
		alert("Maaf Anda Harus Hapus Data Berikutnya Terlebih Dahulu");
	}else{
		$("tr#"+row).remove();
		var row = cindex-1;
		document.getElementById('id_isi_detail').value=row;
	}
}
*/

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

var lastsel5;
	jQuery(document).ready(function(){ 
		var cek = document.getElementById('row_cek').value;
			jQuery("#htmlTable5").jqGrid(
				{
					 url:'request.php?page=<?=$_REQUEST['page']?>&sender=DataRestoran+row='+cek,
					 editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=DataRestoran&oper=edit&row'+cek,
					 datatype: 'json',
					 mtype: 'POST',
					 colNames:['id','Jumlah Meja','Jumlah Kursi','Jumlah Tamu','pendataan id'],
					 colModel :[
						{ name:'id' ,index:'id'	,width:20 ,search:false	},
						{ name:'jumlah_meja',index:'jumlah_meja',width:120,editable:true,edittype:'text',editrules:{required:true,integer:true}},
						{ name:'jumlah_kursi',index:'jumlah_kursi',width:80,editable:true,edittype:'text',editrules:{required:true,integer:true}},
						{ name:'jumlah_tamu_per_hari',index:'jumlah_tamu_per_hari',width:120,editable:true,edittype:'text',editrules:{required:true,integer:true}},
						{ name:'pendataan_idx' ,index:'pendataan_idx'	,width:20 ,search:false},
					 ],
					pager: jQuery('#htmlPager5'),
					height:110,
					rowNum:15,
					rowList:[5,10,15],
					rownumbers: true,
					multiselect:true,
					multiboxonly: true,
					altRows:true,
					shrinkToFit:false,
					sortname: 'id',
					sortorder: 'asc',
					viewrecords: true,
					caption: '',
					onSelectRow: function(id){ 
						if(id && id!==lastsel5){ 
						
						}
					},
					gridComplete: function(){
						jQuery("#htmlTable5").setGridWidth( document.width - 500 < 100 ? 300 :document.width - 500);
						return true;
					}
				}).navGrid(
					'#htmlPager5',
					{ add:true,edit:true,del:true},
					{ bSubmit:"Ubah",bCancel:"Tutup",width:600,reloadAfterSubmit:false}, // edit
					{ bSubmit:"Tambah",bCancel:"Tutup",width:600,reloadAfterSubmit:false}, // add
					{ reloadAfterSubmit:false,afterSubmit:processDelete}, // del
					{}
				).hideCol(['id','pendataan_idx']);
		}
	);
function processDelete(x,y){
	//alert(x+'---'+y);
	//alert(YAHOO.lang.dump(x));
	alert(YAHOO.lang.dump(y));
	jQuery("#htmlTable5").setGridParam({url:"request.php?page=<?=$_REQUEST['page']?>&sender=ActDetailHotel&action=delete"});
	//return [true,'',null];
}
</script>
<!-- jquery date custom  end-->