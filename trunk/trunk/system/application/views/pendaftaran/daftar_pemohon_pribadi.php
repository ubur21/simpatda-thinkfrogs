<script type="text/javascript"> 
var lastsel;
jQuery(document).ready(function(){
	jQuery("#htmlTable").jqGrid({
		url:'<?php echo site_url('pemohon_pribadi/daftar')?>',
		editurl:'<?php echo site_url('pemohon_pribadi/daftar')?>',
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
			{ name:'tgl_lahir',index:'tgl_lahir' ,align:'center',width:100 ,editable:true,formatter:'date', sorttype:"date",
			  formoptions:{ elmsuffix:" dd/mm/yyyy" }, 
			  editoptions: 
			  {size:10, maxlength:10,
			   dataInit:function(elm){
					jQuery(elm).datepicker({showOn: 'focus',changeMonth: true, changeYear: true}); 
					jQuery('.ui-datepicker').css({'zIndex':'1200','font-size':'85%'});
				}				
			  }
			},
			{ name:'pekerjaan' ,index:'pekerjaan' ,width:120 ,editable:true,edittype:'text',
			  editoptions: {size:30, maxlength: 100},editrules: {required:true}
			},
			{ name:'alamat' ,index:'alamat' ,width:130 ,editable:true ,edittype:'textarea' ,
			  editoptions: {rows:"2",cols:"25"} ,editrules: {required:true}
			},
			{ name:'rt' ,index:'rt'	,width:30,editable:true ,edittype:'text',
			  editoptions: {size:3, maxlength:3} ,editrules: {required:true}
			},
			{ name:'rw' ,index:'rw'	,width:30 ,editable:true ,edittype:'text',
			  editoptions: {size:3, maxlength: 3} ,editrules: {required:true}
			},
			{ name:'kodepos' ,index:'kodepos',width:60 ,editable:true ,edittype:'text',
			  editoptions: {size:3, maxlength: 5} ,editrules: {required:true}
			},
			{ name:'iddesa' ,index:'iddesa',width:130,editable:true ,edittype:'select' ,formatter:'select',
			  editoptions: {value:{<?php echo $desa?>}},editrules: {required:true}
			},			
			{ name:'telp',index:'telp',width:90,editable:true,edittype:'text',
			  editoptions: {size:12, maxlength: 12},editrules: {required:true}
			},
			{ name:'no_hp',index:'no_hp',width:100,editable:true ,edittype:'text',
			  editoptions: {size:12, maxlength: 12},editrules: {required:true}
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
		caption: 'DATA PEMOHON PRIBADI',
        onSelectRow: function(id){ 
            if(id && id!==lastsel){ 
				//jQuery("#grid_id").getGridParam('selarrrow'); // get id from multiselect
                //jQuery("#htmlTable").restoreRow(lastsel);
                //jQuery("#htmlTable").editRow(id,true);
                //lastsel=id;
            }
        },
        gridComplete: function(){
                jQuery("#htmlTable").setGridWidth(1250);
                return true;
        }
    }).navGrid('#htmlPager'
        ,{view:true,add:true,edit:true,del:true,viewtext:"Lihat",searchtext:"Cari",addtext:"Tambah",deltext:'Hapus',edittext:'Ubah',refreshtext:'Refresh'}
        ,{closeOnEscape:true,height:350, width:500} // edit ,beforeShowForm:setDesa
        ,{closeOnEscape:true,height:350, width:500} // add
        ,{closeOnEscape:true,reloadAfterSubmit:false} // del 
        ,{closeOnEscape:true,closeAfterSearch: true} // multipleSearch:true,		
    ).hideCol('id');

	function setDesa(){
		var data={};jQuery('select#desa').empty();
		jQuery.post('<?php echo site_url('master/desa/getselect')?>', data ,function(result){
			jQuery.each(result, function(val, text){
				jQuery('select#desa').append( new Option(text.nama,text.id,text.selected) );
			});			
		},'json');
	}	
});
</script>
<table id="htmlTable" class="scroll"></table>
<div id="htmlPager" class="scroll"></div>