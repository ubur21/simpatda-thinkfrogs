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

<script>

var lastsel;
jQuery(document).ready(function(){ 
  jQuery("#htmlTable1").jqGrid({
    url:'request.php?page=<?=$_REQUEST['page']?>&sender=daftar_teguran',
	editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=daftar_teguran&oper=edit',
    datatype: 'json',
    mtype: 'POST',
    colNames:['id','NPWPD/NPWRD','Nama Tertagih','Nama Petugas','Nominal'],
    colModel :[{name:'id',index:'id',width:20,search:false},
			   {name:'npwp',index:'npwp',width:70,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50}},
			   {name:'nama_tagih',index:'nama_tagih',width:150,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50}},
			   {name:'nama_petugas',index:'nama_petugas',width:120,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}},
			   {name:'nominal',index:'nominal',width:80,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}}
			 ],
    pager: jQuery('#htmlPager1'),
    height:100,
	width:600,
    rowNum:5,
    rowList:[5,10,15],
    shrinkToFit:true,
    sortname: 'id',
	rownumbers: true,
    sortorder: 'asc',
    viewrecords: true,
    caption: 'Data Teguran',
        onSelectRow: function(id){ 
            if(id && id!==lastsel){ 
			//alert(id);
                jQuery("#htmlTable1").restoreRow(lastsel); 
                jQuery("#htmlTable1").editRow(id,true);
				jQuery('#id_cetak').val(id);
				jQuery('#button_cetak').attr('disabled',false);
         
           }
        },
		ondblClickRow: function(id){ 
		//alert(id);
            if(id && id!==lastse1){
				jQuery.post("request.php?page=<?=$_REQUEST['page']?>&sender=get_DataFormTeguran&edit="+id, {},
					function(data){
			//		alert(data);						
						jQuery('#proses').val('Ubah');
						jQuery('#batal').attr('disabled',false);
						jQuery('#caction').val('edit');
						jQuery('#IdEdit').val('edit');
						jQuery('#idmasters').val(id);
						jQuery('#NoRegForm').val(data.nomor);
						jQuery('#nama_wp_wr').val(data.nama);
						jQuery('#nama_petugas').val(data.nama_petugas);
						jQuery('#petugas_id').val(data.petugas_id);
						jQuery('#SptIdHid').val(data.spt_id);
						jQuery('#Periode').val(data.periode_spt);
						jQuery('#NomorSpt').val(data.spt_no);
						jQuery('#kode_npwp').val(data.kode_npwp);
						jQuery('#npwpd_npwrd1').val(data.npwp1);
						jQuery('#npwpd_npwrd2').val(data.npwp4);
						jQuery('#npwpd_npwrd3').val(data.npwp3);
						jQuery('#npwpd_npwrd4').val(data.npwp2);
						jQuery('#date_1').val(data.tanggal_penetapan);
						jQuery('#date_2').val(data.tanggal_jatuh_tempo);
						jQuery('#date_3').val(data.tgl_tagih);
						jQuery('#keterangan').val(data.keterangan);
						jQuery('#nominal').val(data.nominal);
						jQuery('#penetapan_pr_id').val(data.id_pr);
						jQuery('#IdTeguranHid').val(id);
						jQuery('#badan_id').val(data.badan_id);
						jQuery("#tabs").tabs('select', 0);
						//alert(id);
				}, "json");
				
            }
        },
        gridComplete: function(){
                jQuery("#htmlTable1").setGridWidth( document.width - 500 < 100?100:document.width - 500);
                return true;
        }
    }).navGrid('#htmlPager1'
        ,{add:false,edit:false,del:true}
        ,{} // edit
        ,{height:120,  width:300} // add
        ,{} // del
        ,{} // search
        
        ).hideCol(['id']);  /*end of on ready event */ 
});

function printReport(){
	var nameFile,template;
			nameFile="laporan_teguran";
			template="report_teguran.fr3";
	var key = "id="+document.getElementById('id_cetak').value;
	var att = 1;
		fastReportStart(nameFile, template, 'pdf', key, att);
}
</script>