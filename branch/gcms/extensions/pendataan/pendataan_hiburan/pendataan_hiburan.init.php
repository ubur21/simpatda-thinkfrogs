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
$qy = "select id,kode_rekening, nama_rekening from rekening_kode where tipe!='' and kelompok!='' and jenis!='' and objek!='' and rincian!='' ";
   $data = gcms_query($qy); $value="'':'',";
   while($rs = gcms_fetch_object($data)){
		$value.="'$rs->kode_rekening - $rs->nama_rekening':'$rs->kode_rekening - $rs->nama_rekening',";
   }
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

});

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
		 url:'request.php?page=<?=$_REQUEST['page']?>&sender=DataHiburan+row='+cek,
		 editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=DataHiburan&oper=edit&row'+cek,
		 datatype: 'json',
		 mtype: 'POST',
		 colNames:['id','Kode Rekening','Dasar Pengenaan','Persentase','Pajak','pendataan id','id rekening'],
		 colModel :[
			{ name:'id' ,index:'id'	,width:20 ,search:false	},
			{ name:'kode_rekening', editable:true,edittype:'select',width:180,formatter:'select',editoptions:{
				value:{<?=$value?>},
				dataEvents:[
					{ type: 'change',
					  fn:function(e){
					  	//alert(this.value);
						jQuery.ajax({url:'request.php?mod=pendataan_hiburan&func=kode&sender=set_form&id='+this.value,dataType:'json',
							success: function(json){
								jQuery('input#dasar_pengenaan').val(parseInt(json.tarif));
								jQuery('input#persen_tarif').val(parseInt(json.persen));
								jQuery('input#id_rekening').val(json.id_rekening);
								var a = json.persen;
									b = json.tarif;
									pajak = (a*b)/100;
								jQuery('input#nominal').val(pajak);
								ProsesNominal(pajak);
							}
						});
					  }
					}
				]
			 }},
			{ name:'dasar_pengenaan',index:'dasar_pengenaan',width:180,editable:true,align:'right',edittype:'text',formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2},editrules:{required:true,integer:true}/*,
			  editoptions: {size:20,maxlength:10,dataEvents:[{ type: 'change', fn:function(e){hitungPajak();}}]*/
			 
			},
			{ name:'persen_tarif',index:'persen_tarif',width:80,editable:true,edittype:'text',align:'right',editrules:{required:true,number:true},formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2}/*,
			  editoptions: {size:20,maxlength:10,dataEvents:[{ type: 'change', fn:function(e){hitungPajak();}}]}*/
			},
			{ name:'nominal',index:'nominal',width:130,editable:true,edittype:'text',align:'right',editrules:{required:true,number:true},formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2}/*,
			  editoptions: {size:20,maxlength:10,dataEvents:[{ type: 'change', fn:function(e){hitungPajak();}}]}*/
			},
			{ name:'pendataan_idx' ,index:'pendataan_idx'	,width:20 ,search:false	
			},
			{ name:'id_rekening' ,index:'id_rekening'	,width:40 ,search:false,editable:true,edittype:'text'	
			},
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
				//jQuery("#htmlTable2").restoreRow(lastsel2); 
                //jQuery("#htmlTable2").editRow(id,true); 
                //lastsel2=id; 
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
	).hideCol(['id','pendataan_idx','id_rekening']);
});
function processDelete(x,y){
	//alert(x+'---'+y);
	//alert(YAHOO.lang.dump(x));
	alert(YAHOO.lang.dump(y));
	jQuery("#htmlTable5").setGridParam({url:"request.php?page=<?=$_REQUEST['page']?>&sender=ActDetailHotel&action=delete"});
	//return [true,'',null];
}
function ProsesNominal(xyz){
		var a=document.getElementById("pajak23").value;
		//dasar = jQuery('input#jumlah').val()*jQuery('input#tarif').val();
		pajak  = jQuery('input#dasar_pengenaan').val()*jQuery('input#persen_tarif').val()/100; id = jQuery('input#id').val();
		//jQuery('input#pengenaan').val(dasar);
		//alert(pajak);
		//tmp={}; total=0;
		//jQuery("#htmlTable5 > tbody > tr").each(function (){
		//	tmp = jQuery("#htmlTable5").getRowData(this.id);
		//	if(id!=tmp.id) total+=pajak;
			//alert("xxx"+pajak);
		//});
		//alert("yyyy"+pajak);
		total =Number(pajak)+Number(a);
		//jQuery('input#nominal').val(formatCurrency(total));
	document.getElementById("pajak23").value=total;
}

var lastsel6;
jQuery(document).ready(function(){ 
var cek = document.getElementById('row_cek_x').value;
jQuery("#htmlTable6").jqGrid(
	{
		 url:'request.php?page=<?=$_REQUEST['page']?>&sender=DataDetailHiburan+row='+cek,
		 editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=DataDetailHiburan&oper=edit&row'+cek,
		 datatype: 'json',
		 mtype: 'POST',
		 colNames:['id','Jumlah Meja','Jumlah Mesin','Rata-rata Jam','Tarif','pendataan id'],
		 colModel :[
			{ name:'id' ,index:'id'	,width:20 ,search:false	},
			{ name:'jumlah_meja', index:'jumlah_meja',editable:true,edittype:'text',width:180},
			{ name:'jumlah_mesin',index:'jumlah_mesin',width:180,editable:true,align:'right',edittype:'text',formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2},editrules:{required:true,integer:true}
			},
			{ name:'rata_jam',index:'rata_jam',width:80,editable:true,edittype:'text',align:'right',editrules:{required:true,number:true},formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2}
			},
			{ name:'tarif',index:'tarif',width:80,editable:true,edittype:'text',align:'right',editrules:{required:true,number:true},formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2}
			},
			{ name:'pendataan_idxx' ,index:'pendataan_idxx'	,width:20 ,search:false	
			}
		 ],
		pager: jQuery('#htmlPager6'),
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
            if(id && id!==lastsel6){ 
				
            }
        },
        gridComplete: function(){
			jQuery("#htmlTable6").setGridWidth( document.width - 500 < 100 ? 300 :document.width - 500);
            return true;
        }
    }).navGrid(
		'#htmlPager6',
		{ add:true,edit:true,del:true},
		{ bSubmit:"Ubah",bCancel:"Tutup",width:600,reloadAfterSubmit:false}, // edit
		{ bSubmit:"Tambah",bCancel:"Tutup",width:600,reloadAfterSubmit:false}, // add
		{ reloadAfterSubmit:false}, // del
		{}
	).hideCol(['id','pendataan_idxx']);
});
</script>
<!-- jquery date custom  end-->