<?php
   $expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";
   $qy = 'select lurah_id, lurah_nama from kelurahan ';
   $data = gcms_query($qy); $value='';
   while($rs = gcms_fetch_object($data)){
		$value.="'$rs->lurah_id':'$rs->lurah_nama',";
   }
   
   if(isset($_REQUEST['page'])){
		$param='page='.$_REQUEST['page'];
   }else{
		$param='mod=pendaftaran&func=pemohon';
   }
?>

<script type="text/javascript"> 

function mycheck(val){
	if(parseFloat(value) >= 200 && parseFloat(value)<=300) { return [true,"",""]; } else { return [false,"The value should be between 200 and 300!",""]; } 
}

var lastsel;
jQuery(document).ready(function(){ 
	jQuery("#htmlTable").jqGrid({
		url:'request.php?<?=$param?>&sender=pendaftaran_pemohon',		
		editurl:'request.php?<?=$param?>&sender=pendaftaran_pemohon&oper=edit',
		datatype: 'json',
		mtype: 'POST',
		colNames:['id','NAMA',"NO. KTP","TEMPAT LAHIR","TANGGAL LAHIR","PEKERJAAN","ALAMAT","RT","RW","KODEPOS","DESA/KELURAHAN","NO. TELP","NO. HP"],
		colModel :[
			{ name:'id', index:'id',width:20  ,search:false },
			{ name:'nama',index:'nama',width:130,editable:true,edittype:'text',
			  editoptions: {size:30, maxlength: 100},editrules: {required:true}
			},
			{ name:'no_ktp' ,index:'no_ktp' ,width:100 ,align:'left',editable:true ,edittype:'text' ,
			  editoptions: {size:20, maxlength: 100},editrules: {required:true}
			},
			{ name:'tempat_lahir' ,index:'tempat_lahir' ,width:120 ,editable:true,edittype:'text',
			  editoptions: {size:30, maxlength: 100},editrules: {required:true}
			},
			{ name:'tanggal_lahir',index:'tanggal_lahir' ,align:'center',width:100 ,editable:true,formatter:'date', sorttype:"date",
			  formoptions:{ elmsuffix:" dd/mm/yyyy" }, 
			  editoptions: 
			  {size:10, maxlength:10,
			   dataInit:function(elm){
					//{dateFormat:'yy-mm-dd'}
					jQuery(elm).datepicker({showOn: 'focus',changeMonth: true, changeYear: true}); 
					jQuery('.ui-datepicker').css({'zIndex':'1200','font-size':'85%'});
				},
				defaultValue:function(){ 
					var date = currentDate();
					return date;
				} 
					
			  },
			  editrules: {custom:true,custom_func:mycheck}
			},
			{ name:'pekerjaan' ,index:'pekerjaan' ,width:120 ,editable:true,edittype:'text',
			  editoptions: {size:30, maxlength: 100},editrules: {required:true}
			},
			{ name:'alamat' ,index:'alamat' ,width:130 ,editable:true ,edittype:'textarea' ,
			  editoptions: {rows:"2",cols:"25"} ,editrules: {required:true}
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
			{ name:'desa',index:'desa',width:130,editable:true,edittype:'select',formatter:'select',
			  editoptions: {value:{<?=$value?>}},editrules: {required:true}
			},
			{ name:'no_telp',index:'no_telp',width:100,editable:true,edittype:'text',
			  editoptions: {size:12, maxlength: 12},editrules: {required:true}
			},
			{ name:'no_hp',index:'no_hp',width:100,editable:true ,edittype:'text' ,
			  editoptions: {size:12, maxlength: 12},editrules: {required:true}
			}
		],
		pager: jQuery('#htmlPager'),
		height:300,
		rownumbers: true, 
		rowNum:10,
		rowList:[5,10,20,30],
		shrinkToFit:false,
		sortname: 'nama',
		sortorder: 'asc',
		multiselect:true,
		multiboxonly: true,
		altRows:true,
		viewrecords: true,
		caption: 'DATA PEMOHON',
        onSelectRow: function(id){ 
            if(id && id!==lastsel){ 
				//jQuery("#grid_id").getGridParam('selarrrow'); // get id from multiselect
                //jQuery("#htmlTable").restoreRow(lastsel); 
                //jQuery("#htmlTable").editRow(id,true);
                //lastsel=id; 
            }
        },
        gridComplete: function(){
                jQuery("#htmlTable").setGridWidth( document.width - 160 < 300 ? 300:document.width - 160);
                return true;
        }
    }).navGrid('#htmlPager'
        ,{view:true,add:true,edit:true,del:true,viewtext:"Lihat",searchtext:"Cari",addtext:"Tambah",deltext:'Hapus',edittext:'Ubah',refreshtext:'Refresh'}
        ,{closeOnEscape:true} // edit
        ,{closeOnEscape:true,height:350,  width:500,reloadAfterSubmit:true} // add
        ,{closeOnEscape:true,reloadAfterSubmit:false} // del 
        ,{closeOnEscape:true,closeAfterSearch: true} // multipleSearch:true,
		
    ).hideCol('id');  /*end of on ready event */ 
	//closeAfterEdit:true,
	
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
