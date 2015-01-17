<?php

$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

include "entri.php";
yuiBeginEntry("entri_penetapan_PajakWalet");

$include= 
		'<script type="text/javascript" src="lib.js"></script>'."\n";
		
		
gcms_add_to_head($include);

$qy = "select id,kode_rekening, nama_rekening from rekening_kode where tipe!='' and kelompok!='' and jenis!='' and objek!='' and rincian!='' ";
$data = gcms_query($qy); $value="'':'',";
while($rs = gcms_fetch_object($data)){
    $value.="'$rs->kode_rekening - $rs->nama_rekening':'$rs->kode_rekening - $rs->nama_rekening',";
}

?>
<script>
$(document).ready( function() {
    //alert( 'document ready!');
    
    var $tabs = $("#tabs").tabs();
    
    $("a[href=#tab3]").click(function() {
	$("#htmlTable").trigger("reloadGrid");
    });
    $("#btn_npwp").click( function() {
	 
	 
	var statusForm = $("#statusForm").val();
	
	if( statusForm == 'new' ) {
	    setNomor();   
	}else {
	    //do nothing
	}
	 
	 //showDialogNpwp();
	 $('#Npwp').dialog('open');
    });
    $('#Npwp').dialog({
	bgiframe:true,
	resizable: false,
	height:350,
	width:700,
	modal: true,
	autoOpen: false,
	buttons: {
		'Tutup': function() {
			$(this).dialog('close');
		}
	}
    });
    $('#tgl_proses').datepicker({changeMonth: true, changeYear: true});
    $('#tgl_entry').datepicker({changeMonth: true, changeYear: true});
    $('#periode_awal').datepicker({changeMonth: true, changeYear: true});
    $('#periode_akhir').datepicker({changeMonth: true, changeYear: true});
    
    jQuery('#proses').click(function() {
	objForm = document.getElementById('entri_penetapan_PajakWalet');
	var rows = jQuery('#gridDetail').getGridParam('records');
	if(saveEntry(objForm)) {
	    jQuery(objForm).ajaxSubmit({
		success: function(response) {
		    act = document.getElementById('caction').value;
		    tmp = {}; count=0; sv=0;
		    if(response!='!') {
			jQuery("#gridDetail > tbody > tr").each(function () {
			    tmp={};
			    tmp[this.id] = jQuery("#gridDetail").getRowData(this.id);
			    tmp[this.id].FK=response;
			    tmp[this.id].page=<?=$_REQUEST['page']?>;
			    tmp[this.id].mode='asyc';
			    if(act!='') tmp[this.id].action='edit';
			    tmp[this.id].rows=rows;
			    tmp[this.id].count=count;
			    tmp[this.id].detail=true;
			    tmp[this.id].sender='entri_penetapan_PajakWalet';
			    $.post("request.php", tmp[this.id],
				   function(data) {
					count++;
					if(data=='!') sv=0;
					else sv=1;
					if(rows==count) {
					    if(sv) {
						if(act!='') document.getElementById('caction').value='';
						alert('Data Telah Tersimpan..');
						jQuery("#gridDetail").setGridParam({url:'request.php?page=<?=$_REQUEST['page']?>&sender=default'}).trigger("reloadGrid");
						objForm.reset();
					    } else alert('cek lagi!');
					}
				   }
			    );
			});
		    }
		    ResetStatus();
		}
	    });
	}
    });
    
    jQuery('#batal').click(function(){	
	objForm = document.getElementById('entri_penetapan_PajakWalet');
	objForm.reset();
	jQuery('#proses').val('Simpan');
	jQuery('#batal').attr('disabled',true);
	jQuery("#gridDetail").setGridParam({url:'request.php?page=<?=$_REQUEST['page']?>&sender=default'}).trigger("reloadGrid");
	ResetStatus();
    });
    
});



var callback = {
    success: function(o) {
		//YAHOO.example.container.wait.hide();
		var formObject = document.getElementById("entri_penetapan_PajakWalet");
		var mesg = o.responseText.split('|');
        alert(mesg[0]);
        formObject.reset();
	},
    failure: function(o) {alert("!")}
}

function setNomor() {
	//alert('tes');
	//generate nomor registrasi baru else tampilkan yang telah ada ( edit form )
	$.getJSON('<?=$expath?>SetData.php?setnomor=1', function(data) {
	    $.each(data, function(key, val) {
		$("#nomor").val( val['nomor'] );
	    });
	});
}

function Simpan() {	
	 var formObject= document.getElementById("entri_penetapan_PajakWalet");
			if(saveEntry(formObject)){
				YAHOO.util.Connect.setForm(formObject);
				
				var transaction = YAHOO.util.Connect.asyncRequest('POST',"request.php",callback,null);
				
			}
}
function ResetStatus() {
    $("#statusForm").val('new');
    $("#pendataanid").val( null );
    $("input[name=pwaletid[]]").remove();
    $("tr.isi_detail").remove();
    $("input[name=total]").val( 0.00 );
    $("input#nominal").val(formatCurrency(0));
    $('#proses').val('Simpan');
    $('#batal').attr('disabled',true);
    $('#caction').val('');
    $('#idmasters').val('');
    var objForm = document.getElementById('entri_penetapan_PajakWalet');
    jQuery("#gridDetail").setGridParam({url:'request.php?page=<?=$_REQUEST['page']?>&sender=default'}).trigger("reloadGrid");
    objForm.reset();
    //alert('Data Berhasil Diubah..');
}

function selectTab( id ) {
    var $tabs = $("#tabs").tabs();
    $tabs.tabs('select', id );
}
function in_array(string, array) {
    for(i=0;i<array.length;i++) {
	if(array[i]==string) {
	    return true;
	}
    }
    return false;
}
function processDelete(x,y){
		var total = 0;
		y=y.split(',');
		jQuery("#gridDetail > tbody > tr").each(function () {
		   if( !in_array(this.id,y) ) {
			tmp=jQuery("#gridDetail").getRowData(this.id);
			total+=Number(tmp.pajak);
		   }
		});
		jQuery('input#nominal').val(formatCurrency(total));
		//alert(YAHOO.lang.dump(x));
		//alert(YAHOO.lang.dump(y));
		return [true,'',null];
		//return false;
}
function processAdd(response, postdata){
		var old_val = jQuery('input#nominal').val();
		old_val = Number(removeCommas(old_val));
		var new_val = old_val+Number(postdata.pajak);
		jQuery('input#nominal').val(formatCurrency(new_val));
		var success = true; var message = ""; var new_id = "";
		return [success,message,new_id];
}
function processEdit(response, postdata){
		var total=0;
		jQuery("#gridDetail > tbody > tr").each(function (){
			if(this.id!=postdata.id){
				tmp = jQuery("#gridDetail").getRowData(this.id);
				total+=Number(tmp.pajak);
			}
		});
		total+=Number(postdata.pajak);
		jQuery('input#nominal').val(formatCurrency(total));
		var success = true; var message = ""; var new_id = "";
		return [success,message,new_id];
}
function hitungPajak(){
		dasar = jQuery('input#jumlah').val()*jQuery('input#tarif').val();
		//dasar = jQuery('input#pengenaan').val();
		pajak  = dasar*jQuery('input#persen').val()/100; id = jQuery('input#id').val();
		jQuery('input#pengenaan').val(dasar);
		jQuery('input#pajak').val(pajak); tmp={}; total=0;
		jQuery("#gridDetail > tbody > tr").each(function (){
			tmp = jQuery("#gridDetail").getRowData(this.id);
			if(id!=tmp.id) total+=Number(tmp.pajak);
		});
		total+=Number(pajak);
		//jQuery('input#nominal').val(formatCurrency(total));
}

var lastsel,lastsel2;
jQuery(document).ready(function(){ 
  jQuery("#htmlTable").jqGrid({
    url:'request.php?page=<?=$_REQUEST['page']?>&sender=penetapan_PajakWalet',
    editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=penetapan_PajakWalet&oper=edit',
    datatype: 'json',
    mtype: 'POST',
    //colNames:['id','No. Pendataan','Tanggal Proses', 'Tanggal Entri','NPWPD/NPWRD','Nama WP/WR', 'Alamat','Sistem Pemungutan','Periode Penjualan Awal', 'Periode Penjualan Akhir','Pajak'],
    //colNames:['id','pendataan_no','tanggal_proses', 'tanggal_entry','pungutan','periode_awal', 'periode_akhir'],
    colNames:['No.','id','Tanggal Entri','NPWPD/NPWRD','Nama WP/WR','Alamat','Sistem Pemungutan','Periode Penjualan Awal', 'Periode Penjualan Akhir' ],
    colModel :[
	       { name:'no',index:'nomor',width:20,search:false },{ name:'id',index:'id',width:20,search:false },
	       { name:'tglentri',index:'tglentri',width:120,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true} },
	       { name:'npwpdnpwrd',index:'npwpdnpwrd',width:100,editable:false,edittype:'text',editoptions:{size:50,maxlength:50},editrules:{required:true} },
	       { name:'nama',index:'nama',width:150,editable:false,edittype:'text',editoptions:{size:50,maxlength:50},editrules:{required:true} },
	       { name:'alamat',index:'alamat',width:240,editable:false,edittype:'text',editoptions:{size:50,maxlength:50},editrules:{required:true} },
	       { name:'pungutan',index:'pungutan',width:80,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true} },
	       { name:'periode_awal',index:'periode_awal',width:180,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}},
	       { name:'periode_akhir',index:'periode_akhir',width:180,editable:false,edittype:'text',editoptions: {size:50, maxlength: 50},editrules: {required:true}}
	      ],
    pager: jQuery('#htmlPager'),
    height:300,
    rowNum:5,
    rowList:[5,10,15],
    shrinkToFit:false,
    sortname: 'id',
    sortorder: 'asc',
    viewrecords: true,
    caption: 'Data Pendataan Pajak Walet',
	subGrid:true,
	multiselect:true,
	multiboxonly:true,
	toolbar:[true,'top'],
	footerrow:true,userDataOnFooter:true,
	subGridRowExpanded:function(subgrid_id,id) {
	    var subgrid_table_id, pager_id;
	    subgrid_table_id=subgrid_id+'_t';
	    pager_id='p_'+subgrid_table_id;
	    $("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");
	    jQuery("#"+subgrid_table_id).jqGrid({
		url:"request.php?mod=pendataan_walet&func=list&sender=DataWalet&action=DetailWaletList&val="+id,
		datatype:"json",
		rownumbers:true,
		colNames:['id','Kode Rekening','Jumlah','Tarif','Dasar Pengenaan','Persentase','Pajak'],
		colModel:[
		  { name:'id',index:'id',width:20,key:true,search:false},
		  { name:'rekening',index:'rekening',width:200,search:false,align:'left' },
		  { name:'jumlah',index:'jumlah',width:100,search:false,align:'right'},
		  { name:'tarif',index:'tarif',width:100,search:false,align:'right'},
		  { name:'pengenaan',index:'pengenaan',width:120,search:false,align:'right' },
		  { name:'persen',index:'persen',width:100,search:false,align:'right' },
		  { name:'pajak',index:'pajak',width:100,align:'right',formatter:'currency',formatoptions:{decimalSeparator:'.',thousandsSeparator:',',decimalPlaces:2}}
		],
		rowNum:20,pager:pager_id,sortname:'num',sortorder:'asc',height:'100%',footerrow:true,userDataOnFooter:true
	    });
	    jQuery("#"+subgrid_table_id)
		.navGrid("#"+pager_id,{edit:false,add:false,del:false,search:false})
		.hideCol(['id']);
	},
	subGridRowColapsed:function(subgrid_id, row_id) {
	     subgrid_table_id=subgrid_id+"_t";
	     jQuery("#"+subgrid_table_id).remove();
	},
    ondblClickRow: function(id) {
	    jQuery("#gridDetail").setGridParam({url:"request.php?page=<?=$_REQUEST['page']?>&sender=default&action=get_list&id="+id}).trigger("reloadGrid");
	    $.getJSON('request.php?page=<?=$_REQUEST['page']?>&edit=umum&id='+id, function(data) {
		    $.each( data, function( index, entry ) {
			$('#proses').val('Ubah');
			$('#batal').attr('disabled',false);
			$('#caction').val('edit');
			$('#idmasters').val(id);
			
			$("#pendataanid").val( entry['pendataanid'] );
			$("#nomor").val( entry['noreg'] );
			$("#tgl_proses").val( entry['tglproses'] );
			$("#tgl_entry").val( entry['tglentry'] );
			$("#periode_awal").val( entry['pawal'] );
			$("#periode_akhir").val( entry['pakhir'] );
			$("#periode_spt").val( entry['pspt'] );
			$("#nomor_spt").val( entry['nospt'] );
			$("#npwpd_npwrd1").val( entry['npwp'] );
			$("#nama_wp_wr").val( entry['nama'] );
			$("#alamat").val( entry['alamat'] );
			$("#kel").val( entry['kelurahan']);
			$("#kec").val( entry['kecamatan']);
			$("#kab").val( entry['kabupaten']);
			$("#nominal").val(formatCurrency(entry['total']));
			
			for( var i =0; i<$("#jenis_pungutan option").length; i ++ ) {
			     $("#jenis_pungutan option[value='"+entry['pungutan']+"']").attr('selected','selected' );
			}
			
		    });
		    
		    selectTab(0);
		    $("#statusForm").val( 'edit' );
		});
		
	    jQuery("#gridDetail").setGridParam({url:'request.php?page=<?=$_REQUEST['page']?>&sender=default'});
	},
        gridComplete: function(){
                jQuery("#htmlTable").setGridWidth( document.width - 160 < 300?300:document.width - 120);
                return true;
            }
    }).navGrid('#htmlPager'
        ,{add:false,edit:false,del:true,view:true}
        ,{} // edit
        ,{height:120,  width:500} // add
        ,{} // del
        ,{} // search
    ).hideCol('id');  /*end of on ready event */

    jQuery("#gridDetail").jqGrid({
	url:'request.php?page=<?=$_REQUEST['page']?>&sender=default',
	editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=default&oper=edit',
	datatype:'json',
	mtype:'POST',
	colNames:['id','Kode Rekening','Jumlah','Tarif Dasar','Dasar Pengenaan','Persentase','Pajak'],
	colModel:[
	    { name:'id', index:'id',width:20,search:false },
	    { name:'rekening',index:'rekening', editable:true,edittype:'select',formatter:'select',
		editoptions:
		{
		    value:{<?=$value?>},
		    dataEvents:
		    [
			{ 	type: 'change',
			    fn:function(e){
				jQuery.ajax({url:'request.php?mod=rekening&func=kode&sender=set_form&id='+this.value,dataType:'json',
				    success: function(json){
					jQuery('input#tarif').val(json.tarif);
					jQuery('input#persen').val(json.persen);
					hitungPajak();
				    }
				});
			    }
			}
		    ]
		} 
	    },
	    { name:'jumlah',index:'jumlah',width:80,editable:true,edittype:'text',editrules:{required:true,integer:true},
	      editoptions: {size:20,maxlength:10,dataEvents:[{ type: 'change', fn:function(e){hitungPajak();}}]
	      },
	    },
	   { name:'tarif',index:'tarif',width:80,editable:true,edittype:'text',align:'right',editrules:{required:true,number:true},formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2},
			  editoptions: {size:20,maxlength:10,dataEvents:[{ type: 'change', fn:function(e){hitungPajak();}}]}
			},
	   { 	name:'pengenaan',index:'pengenaan',width:120,editable:true,edittype:'text',align:'right',editrules: {required:true,number:true},formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2},
		editoptions: {size:20,maxlength:10,dataEvents:[{ type: 'change', fn:function(e){hitungPajak();}}]}
	    },
	      { 	name:'persen',index:'persen',width:80,editable:true,edittype:'text',align:'center',editrules: {required:true,number:true},
		editoptions:{size:10,maxlength:3,dataEvents:[{ type: 'change', fn:function(e){hitungPajak();}}]}
	    },
	    { 	name:'pajak',index:'pajak',width:80,editable:true,edittype:'text',align:'right',
		editoptions:{size:20,maxlength:10,readonly:'readonly'},
		editrules:{required:true,number:true},
		formatter:'currency',formatoptions:{decimalSeparator:".",thousandsSeparator:",",decimalPlaces:2}
	    }
	],
	pager: jQuery("#pagerDetail"),
	height:110,
	rowNum:20,
	rowList:[20,30,40],
	rownumbers: true,
	multiselect:true,
	multiboxonly: true,
	altRows:true,
	shrinkToFit:true,
	sortname: 'id',
	sortorder: 'asc',
	viewrecords: true,
	caption: '',
        onSelectRow: function(id){ 
            if(id && id!==lastsel2){ 
		
            }
        },
	gridComplete: function(){
	    jQuery("#gridDetail").setGridWidth( document.width - 500 < 100 ? 300 :document.width - 500);
            return true;
        }
    }).navGrid(
		'#gridDetail',
		{ add:true,edit:true,del:true,search:false,refresh:false},
		{ bSubmit:"Submit",bCancel:"Tutup",width:600,reloadAfterSubmit:false,afterSubmit:processEdit}, // edit
		{ bSubmit:"Submit",bCancel:"Tutup",width:600,reloadAfterSubmit:false, afterSubmit:processAdd }, // add
		{ reloadAfterSubmit:false,afterSubmit:processDelete}, // del
		{}
	).hideCol(['id']);
  

}
);/*end of on ready event */

</script>

<div class="demo">
    <div id="tabs">
	    <ul>
 		<li class="selected"><a href="#tab1"><em>Form</em></a></li>
		<li><a href="#tab3"><em>Daftar</em></a></li>
	    </ul>
		<div id="tab1">
		<!--CONTENT-->
		    <div id="hidden_master" class="hidden">
			<input type="hidden" name="statusForm" id="statusForm" value="new" />
			<input type="hidden" name="pendaftaran_id" id='pendaftaran_id' value="" />
			<input type="hidden" name="pendataanid" id='pendataanid'  />
			<input type="hidden" name="pwaletid" id='pwaletid' />
			<input type="hidden" name="id_desa" id='id_desa' value=""  />
			<input type="hidden" name="jenis_pendataan" id="jenis_pendataan" value="WALET" />
		    </div>
		    <div style="padding:5px;">
		      <fieldset>
			<legend>Isian SPTPD Pajak Walet</legend>
			<div class="form_master">
				<fieldset class="form_frame">
				<div><label>No. Reg Form</label><input title="noreg" type="text" name="nomor" id='nomor' size='10' maxlength='10' value="000000" readonly/></div>
				<div><label>Tanggal Proses</label><input title="tglproses" id="tgl_proses" type="text"  name="tgl_proses" value="" size="10" maxlength="10" /></div>
				<div><label>Tanggal Entri</label><input id="tgl_entry" type="text"  name="tgl_entry" value="" size="10" maxlength="10" /></div>
				<div><label>NPWPD/NPWRD</label>
				<input type="text" name="npwpd_npwrd1" id='npwpd_npwrd1' value="" size="20" maxlength="50" />
				<input id="btn_npwp" type="button" name="btn_npwp_dialog" value="..." /></div>
				<div><label>Periode SPT</label><input id="periode_spt" type="text" name="periode_spt" value="" size="4" maxlength="4" /></div>
				<div><label>Nomor SPT</label><input id="nomor_spt" type="text" name="nomor_spt" value="" size="10" maxlength="10" /><input type="button" id="btn_nomor_spt" name="btn_nomor_spt" value="..." /><input id="reset_nomor_spt" type="button" name="reset_nomor_spt" value="kosongkan" /></div>
				<div><label>Nama WP/WR</label><input type="text" name="nama_wp_wr" id="nama_wp_wr" value="" size="25" />
				<div><input id="proses" class="btn" type="button" name="proses" value="Simpan" />
				<input id="batal" class="btn" type="button" name="batal" value="Batal" disabled ></div>
				</fieldset>
				<fieldset class="form_frame">
				<div><label>Alamat</label><textarea name="alamat" id="alamat" col="4" row="3"></textarea></div>
				<div><label>Kelurahan</label><input type="text" name="kel" id='kel' value=""  /></div>
				<div><label>Kecamatan</label><input type="text" name="kec" id='kec' value=""  /></div>
			    	<div><label>Kabupaten</label><input type="text" name="kab" id='kab' value="" /></div>
				<div><label>Sistem Pemungutan</label><select title="jenis_pungutan" id="jenis_pungutan" name="jenis_pungutan" size="1"><option value="Pilih" >Pilih</option><option value="OFFICIAL" >Official Assesment</option><option value="SELF" >Self Assesment</option></select></div>
				<div><label>Periode Penjualan</label><input title="periode_awal" type="text"  id="periode_awal" name="periode_awal" value="" size="10" maxlength="10" /><span>s/d</span><input title="periode_akhir" type="text"  id="periode_akhir" name="periode_akhir" value="" size="10" maxlength="10" /><div>
				</fieldset>
			</div>
		      </fieldset>
		    </div>
		    <div style="padding:5px;">
			<fieldset>
			    <legend>Detail Kegiatan</legend>					
			    <div class="form_master">
			    <fieldset>
				    <div>
					<div><span style='font-weight:bold;font-size:25px'>Total</span>
					<input type='text' name='rp' id='rp' value='Rp.' style="font-weight: bold; font-size: 25px; color: rgb(24, 245, 24); background-color: black; text-align:center;padding-right:0;margin-right:0" size='1'>
					<input type="text" style="font-weight: bold; font-size: 25px; color: rgb(24, 245, 24); background-color: black; text-align: right; padding-left:0;margin-left:0" readonly="true" value="0.00" size="13" class="inputbox" id="nominal" name="nominal"></div>
					<table id="gridDetail" class="scroll"></table>
					 <div id="pagerDetail" class="scroll"></div>
				    </div>
			    </fieldset>
			    </div>
			</fieldset>
		    </div>
			<div class="footer_space">&nbsp;</div>
		<!--CONTENT-->
		</div>
		<div id="tab3">
			<div style='padding:5px;'>
				<fieldset>
				<legend>Daftar</legend>
					<div id='asb_simulasi_form'>
						<div style='padding:5px'>
							<table id="htmlTable" class="scroll"></table>
							<div id="htmlPager" class="scroll"></div>
						</div>
					</div>
					<div id="div_daftar_npwp" align="center"></div>
				</fieldset>
			</div>
		</div>
		
</div>

<div class="hidden" id="dialog1">
	<div class="hd"></div>
	<div class="bd">
		<div id="container-page"> 
			
			<div id="content">
				
						<table style="width: 10%">
							<tr><td>
							
								<fieldset>
								<legend>Daftar</legend>
									<div id='asb_simulasi_form'>
										<div style='padding:5px'>
										    <div id="Npwp">
											<table id="htmlTable2" class="scroll"></table>
											<div id="htmlPager2" class="scroll"></div>
										    </div>	
										 </div>
									</div>
									
								</fieldset>
							
							</td>
							<td>&nbsp;
							<input type="hidden" id="ConvertId" />
							</td><td width="1" align="right" valign="top"><!--<input id="btn_pilihCoa_refresh" type="button" value="Refresh">-->
							</td></tr>
						</table>
					
					<div id="fakehead_pilihCoa" class="fake_head"></div>
					
					<div id="buttons_pilihCoa" class="daftar_buttons">
						<table style="width: 100%"><tr><td align="left"></td><td align="right"></td></tr></table>
					</div>
				
			</div>
	</div>
</div>
<script type="text/javascript">
var el = document.getElementById( 'dialog1' );
el.className='';
</script>

<script>

YAHOO.namespace("hendra");
    

function OpenSpt(){
	gcms_open_form('form.php?mod=penetapan&func=open_spt','detail',"availWidth", "availHeight");
}


function showDialogNpwp(){
	YAHOO.hendra.dialog1.show();
	$("#Npwp").style.display="inherit";
	
}
/*function showDialogProduk(){
	
	YAHOO.hendra.dialog1.show();
}

*/function init() {
	var handleSubmit = function() {
		this.cancel();
		//window.close();
        
	};
	var handleCancel = function() {
		this.cancel();
	};
	
	// Instantiate the Dialog
	YAHOO.hendra.dialog1 = new YAHOO.widget.Dialog(
		"dialog1", 
		{ width : "870px",
		  fixedcenter : true,
		  visible : false, 
		  constraintoviewport : true,
		  buttons : [ { text:"Tutup", handler:handleCancel } ]
		});

	// Validate the entries in the form to require that both first and last name are entered
	YAHOO.hendra.dialog1.validate = function() {
		var data = this.getData();
		if (data.firstname == "" || data.lastname == "") {
			alert("Please enter your first and last names.");
			return false;
		} else {
			return true;
		}
	};

	// Wire up the success and failure handlers
	//YAHOO.hendra.dialog1.callback = { success: handleSuccess, failure: handleFailure };
	
	// Render the Dialog
	YAHOO.hendra.dialog1.setHeader("Daftar NPWP");
	YAHOO.hendra.dialog1.render();

	YAHOO.util.Event.addListener("open", "click", YAHOO.hendra.dialog1.show, YAHOO.hendra.dialog1, true);
	YAHOO.util.Event.addListener("hide", "click", YAHOO.hendra.dialog1.hide, YAHOO.hendra.dialog1, true);
}

YAHOO.util.Event.onDOMReady(init);


var lastsel2;
jQuery(document).ready(function(){ 
  jQuery("#htmlTable2").jqGrid({
    url:'request.php?page=<?=$_REQUEST['page']?>&sender=daftarnpwp',
	editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=daftarnpwp&oper=edit',
    datatype: 'json',
    mtype: 'POST',
    colNames:['id','No Pendaftaran','NPWP','Nama ','Alamat'],
    colModel :[{
		name:'id'
		,index:'id'
		,width:20
        ,search:false

	},{
		name:'NoPendaftaran'
		,index:'NoPendaftaran'
		,width:100
		,editable:false
        ,edittype:'text'
        ,editoptions: {size:50, maxlength: 50}
        //,editrules: {required:true}
	},{
		name:'NPWP'
		,index:'NPWP'
		,width:80
		,editable:false
        ,edittype:'text'
        ,editoptions: {size:50, maxlength: 50}
        ,editrules: {required:true}
	},{
		name:'Nama'
		,index:'Nama'
		,width:180
		,editable:false
        ,edittype:'text'
        ,editoptions: {size:50, maxlength: 50}
        ,editrules: {required:true}
	},{
		name:'Alamat'
		,index:'Alamat'
		,width:180
		,editable:false
        ,edittype:'text'
        
	}/*,{
		name:'Kelurahan'
		,index:'Kelurahan'
		,width:100
		,editable:false
        ,edittype:'text'
        
	},{
		name:'Kecamatan'
		,index:'Kecamatan'
		,width:100
		,editable:false
        ,edittype:'text'
        ,editoptions: {size:50, maxlength: 50}
        ,editrules: {required:true}
	},{
		name:'Kabupaten'
		,index:'Kabupaten'
		,width:100
		,editable:false
        ,edittype:'text'
        ,editoptions: {size:50, maxlength: 50}
        ,editrules: {required:true}
	}*/
	
	],
    pager: jQuery('#htmlPager2'),
    height:100,
	width:600,
    rowNum:5,
    rowList:[5,10,15],
    shrinkToFit:false,
    sortname: 'id',
    sortorder: 'asc',
    viewrecords: true,
    caption: 'Data NPWP',
        onSelectRow: function(id){ 
            if(id && id!==lastsel2){ 
			//alert(id);
                jQuery("#htmlTable2").restoreRow(lastsel2); 
                jQuery("#htmlTable2").editRow(id,true); 
                lastsel2=id; 
				ajax_do('<?=$expath?>SetData.php?Npwp='+id);
				//tutup();
				$("#Npwp").dialog('close');
                }
             },
        gridComplete: function(){
                jQuery("#htmlTable2").setGridWidth( document.width - 500 < 100?100:document.width - 500);
                return true;
            }
    }).navGrid('#htmlPager2'
        ,{add:true,edit:false,del:true}
        ,{} // edit
        ,{height:120,  width:300} // add
        ,{} // del
        ,{} // search
        
        ).hideCol(['id']);  /*end of on ready event */ 
}
);
function tutup(){
//window.close();
}
</script>
<? yuiEndEntry() ?>
