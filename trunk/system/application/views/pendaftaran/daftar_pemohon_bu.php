<script type="text/javascript"> 
var lastsel;
jQuery(document).ready(function(){ 
	jQuery("#htmlTable").jqGrid({
		url:'<?php echo site_url('pemohon_bu/daftar')?>',
		editurl:'<?php echo site_url('pemohon_bu/daftar')?>',
		datatype: 'json',
		mtype: 'POST',//,"NPWP"
		colNames:['id','NAMA BADAN',"TIPE","ALAMAT","TELP.","FAX.","KODEPOS","NAMA PEMILIK","NO. KTP","NPWP","TELP","HP","TEMPAT LAHIR","TGL. LAHIR","ALAMAT","RT","RW","KODEPOS","KELURAHAN"],
		colModel :[
			{ name:'id'	,index:'id' ,width:20 ,search:false },
			{ name:'nama_bu', index:'badan_nama', width:130, editable:true,edittype:'text',
			  editoptions: {size:30, maxlength: 100} ,editrules: {required:true}
			},
			{ name:'tipe_bu' ,index:'tipe_bu' ,width:80 ,editable:true ,edittype:'select' ,formatter:'select',
			  editoptions: {value:{'PT':'PT','CV':'CV','KOPERASI':'KOPERASI'} },
			  editrules: {required:true}
			},
			{ name:'alamat_bu',index:'badan_alamat',width:120,editable:true,edittype:'textarea',editoptions: {rows:"2",cols:"25"},
			  editrules: {required:true}
			},			
			{ name:'telp_bu',index:'telp_bu',width:80,editable:true ,edittype:'text',editoptions: {size:10, maxlength: 12},
			  editrules: {required:true}
			},
			{ name:'fax_bu'	,index:'fax_bu',width:80,editable:true ,edittype:'text' ,editoptions: {size:10, maxlength: 12},
			  editrules: {required:true}
			},			
			{ name:'kodepos_bu' ,index:'kodepos_bu',width:60 ,editable:true ,edittype:'text' ,
			  editoptions: {size:3, maxlength: 5} ,editrules: {required:true}
			},
			/*{ name:'badan_npwp',index:'badan_npwp',width:130,editable:true ,edittype:'text' ,editoptions: {size:20, maxlength: 12} ,
			  editrules: {required:true}
			},*/
			{ name:'pemilik_nama',index:'pemilik_nama',width:100 ,editable:true ,edittype:'text',editoptions: {size:10, maxlength: 12},
			  editrules: {required:true}
			},
			{ name:'pemilik_no_ktp',index:'pemilik_no_ktp',width:80 ,editable:true ,edittype:'text',editoptions: {size:10, maxlength: 12},
			  editrules: {required:true}
			},
			{ name:'pemilik_npwp',index:'pemilik_npwp',width:130,editable:true,edittype:'text' ,editoptions: {size:20, maxlength: 12},
			  editrules: {required:true}
			},			
			{ name:'pemilik_telp' ,index:'pemilik_telp',width:100,editable:true,edittype:'text',editoptions: {size:12, maxlength: 12},
			  editrules: {required:true}
			},
			{ name:'pemilik_hp',index:'pemilik_hp',width:100,editable:true ,edittype:'text' ,editoptions: {size:12, maxlength: 12},
			  editrules: {required:true}
			},			
			{ name:'pemilik_tmp_lahir',index:'pemilik_tmp_lahir',width:120 ,editable:true ,edittype:'text',editoptions: {size:10, maxlength: 12} ,
			  editrules: {required:true}
			},
			{ name:'pemilik_tgl_lahir',index:'pemilik_tgl_lahir' ,align:'center',width:100 ,editable:true,formatter:'date', sorttype:"date",
			  formoptions:{ elmsuffix:" dd/mm/yyyy" }, 
			  editoptions: 
			  {size:10, maxlength:10,
			   dataInit:function(elm){
					jQuery(elm).datepicker({showOn: 'focus',changeMonth: true, changeYear: true}); 
					jQuery('.ui-datepicker').css({'zIndex':'1200','font-size':'85%'});
				}				
			  }
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
			{ name:'pemilik_id_desa' ,index:'pemilik_id_desa',width:130	,editable:true ,edittype:'select' ,formatter:'select',
			  editoptions: {value:{<?php echo $desa?>}},editrules: {required:true}
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
			jQuery("#htmlTable").setGridWidth(1250);
			return true;
        }
    }).navGrid('#htmlPager'
        ,{view:true,add:true,edit:true,del:true,viewtext:"Lihat",searchtext:"Cari",addtext:"Tambah",deltext:'Hapus',edittext:'Ubah',refreshtext:'Refresh'}
        ,{height:500,width:450} // edit,beforeShowForm:setDesa
        ,{height:500,width:450} // add
        ,{} // del
        ,{closeOnEscape:true,closeAfterSearch: true} // multipleSearch:true,
    ).hideCol('id');
	
	function setDesa(){
		var data={}
		jQuery.post('<?php echo site_url('master/desa/getselect')?>', data ,function(result){
			jQuery.each(result, function(val, text){
				jQuery('select#pemilik_id_desa').append( new Option(text.nama,text.id,text.selected) );
			});			
		},'json');
	}
});
</script>
<table id="htmlTable" class="scroll"></table>
<div id="htmlPager" class="scroll"></div>