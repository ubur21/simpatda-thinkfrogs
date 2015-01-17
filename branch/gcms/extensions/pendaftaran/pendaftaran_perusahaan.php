<?php
   $expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";
   $qy = 'select lurah_id, lurah_nama from kelurahan order by lurah_nama asc ';
   $data = gcms_query($qy); $value='';
   while($rs = gcms_fetch_object($data)){
		$value.="'$rs->lurah_id':'$rs->lurah_nama',";
   }
   
   if(isset($_REQUEST['page'])){
		$param='page='.$_REQUEST['page'];
   }else{
		$param='mod=pendaftaran&func=badan';
   }
   
?>

<script type="text/javascript"> 
var lastsel;
jQuery(document).ready(function(){ 
	jQuery("#htmlTable").jqGrid({
		url:'request.php?<?=$param?>&sender=pendaftaran_badan',
		editurl:'request.php?<?=$param?>&sender=pendaftaran_badan&oper=edit',
		datatype: 'json',
		mtype: 'POST',
		colNames:['id','NAMA BADAN',"TIPE","ALAMAT","RT","RW","KODEPOS","KELURAHAN","TELP. BADAN","FAX BADAN","NPWP","PEMILIK","ALAMAT PEMILIK","RT","RW","KODEPOS","KELURAHAN","TELP","HP","NPWP"],
		colModel :[
			{ name:'id'	,index:'id' ,width:20 ,search:false },
			{ name:'nama', index:'badan_nama', width:130, editable:true,edittype:'text',
			  editoptions: {size:30, maxlength: 100} ,editrules: {required:true}
			},
			{ name:'badan_tipe' ,index:'badan_tipe' ,width:80 ,editable:true ,edittype:'select' ,formatter:'select',
			  editoptions: {value:{'PT':'PT','CV':'CV','KOPERASI':'KOPERASI'} },
			  editrules: {required:true}
			},
			{ name:'alamat',index:'badan_alamat',width:120,editable:true,edittype:'textarea',editoptions: {rows:"2",cols:"25"},
			  editrules: {required:true}
			},
			{ name:'rt' ,index:'rt'	,width:30,editable:true ,edittype:'text' ,
			  editoptions: {size:3, maxlength:3} ,editrules: {required:true}
			},
			{ name:'rw' ,index:'rw'	,width:30 ,editable:true ,edittype:'text' ,
			  editoptions: {size:3, maxlength: 3} ,editrules: {required:true}
			},
			{ name:'kodepos' ,index:'kodepos',width:60 ,editable:true ,edittype:'text' ,
			  editoptions: {size:3, maxlength: 5} ,editrules: {required:true}
			},			
			{ name:'badan_id_desa',index:'badan_id_desa',width:100,editable:true ,edittype:'select',formatter:'select',
			  editoptions: {value:{<?=$value?>}},editrules: {required:true}
			},
			{ name:'badan_telp',index:'badan_telp',width:80,editable:true ,edittype:'text',editoptions: {size:10, maxlength: 12},
			  editrules: {required:true}
			},
			{ name:'badan_fax'	,index:'badan_fax',width:80	,editable:true ,edittype:'text' ,editoptions: {size:10, maxlength: 12},
			  editrules: {required:true}
			},
			{ name:'badan_npwp',index:'badan_npwp',width:130,editable:true ,edittype:'text' ,editoptions: {size:20, maxlength: 12} ,
			  editrules: {required:true}
			},
			{ name:'pemilik_nama',index:'pemilik_nama',width:80 ,editable:true ,edittype:'text',editoptions: {size:10, maxlength: 12} ,
			  editrules: {required:true}
			},
			{ name:'pemilik_alamat',index:'pemilik_alamat',width:120,editable:true,edittype:'textarea' ,editoptions: {rows:"2",cols:"25"},
			  editrules: {required:true}
			},
			{ name:'pemilik_rt' ,index:'pemilik_rt'	,width:30,editable:true ,edittype:'text' ,
			  editoptions: {size:3, maxlength:3} ,editrules: {required:true}
			},
			{ name:'pemilik_rw' ,index:'pemilik_rw'	,width:30 ,editable:true ,edittype:'text' ,
			  editoptions: {size:3, maxlength: 3} ,editrules: {required:true}
			},
			{ name:'pemilik_kodepos' ,index:'pemilik_kodepos',width:60 ,editable:true ,edittype:'text' ,
			  editoptions: {size:3, maxlength: 5} ,editrules: {required:true}
			},			
			{ name:'pemilik_id_desa' ,index:'pemilik_id_desa',width:130	,editable:true ,edittype:'select' ,formatter:'select' ,
			  editoptions: {value:{<?=$value?>}},editrules: {required:true}
			},
			{ name:'pemilik_telp' ,index:'pemilik_telp',width:100,editable:true,edittype:'text',editoptions: {size:12, maxlength: 12},
			  editrules: {required:true}
			},
			{ name:'pemilik_hp',index:'pemilik_hp',width:100,editable:true ,edittype:'text' ,editoptions: {size:12, maxlength: 12},
			  editrules: {required:true}
			},
			{ name:'pemilik_npwp',index:'pemilik_npwp',width:130,editable:true,edittype:'text' ,editoptions: {size:20, maxlength: 12},
			  editrules: {required:true}
			}
		],
		pager: jQuery('#htmlPager'),
		height:300,
		rownumbers: true,
		rowNum:15,
		rowList:[15,30,45],
		shrinkToFit:false,
		sortname: 'nama',
		sortorder: 'asc',
		viewrecords: true,
		multiselect:true,
		multiboxonly: true,
		caption: 'DATA BADAN USAHA',
        onSelectRow: function(id){ 
            if(id && id!==lastsel){ 
                //jQuery("#htmlTable").restoreRow(lastsel); 
                //jQuery("#htmlTable").editRow(id,true); 
                lastsel=id; 
			}
        },
        gridComplete: function(){
			jQuery("#htmlTable").setGridWidth( document.width - 100 < 300?300:document.width - 100);
			return true;
        }
    }).navGrid('#htmlPager'
        ,{view:true,add:true,edit:true,del:true,viewtext:"Lihat",searchtext:"Cari",addtext:"Tambah",deltext:'Hapus',edittext:'Ubah',refreshtext:'Refresh'}
        ,{} // edit
        ,{height:500,width:450} // add
        ,{} // del
        ,{closeOnEscape:true,closeAfterSearch: true} // multipleSearch:true,
        
    ).hideCol('id');  /*end of on ready event */ 
});
</script>
<div style='padding:5px;'>
	<fieldset>
	<legend>DAFTAR</legend>
		<div id='asb_simulasi_form'>
			<div style='padding:5px'>
				<table id="htmlTable" class="scroll"></table>
				<div id="htmlPager" class="scroll"></div>
			</div>
		</div>		
	</fieldset>
</div>
