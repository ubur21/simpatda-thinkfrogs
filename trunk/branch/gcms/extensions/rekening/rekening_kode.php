<? 
   $expath = "..".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";
   
   $qy = 'select id,tipe_kategori, nama_kategori from rekening_kategori ';
   $data = gcms_query($qy); $value='';
   while($rs = gcms_fetch_object($data)){
		$value.="'$rs->id':'$rs->nama_kategori',";
   }
?>

<script type="text/javascript"> 

function ajaxFieldGeneral(theData,theUrl,successContainer,fieldTarget,fieldVal) {
  //var img = ' <img src="'+BASIC_URL+'/public/images/loading.gif"  border="0" id="imgLoading">';
  jQuery("#imgLoading").replaceWith('');
  jQuery.ajax({
    type : "POST",
    url:theUrl, //buat action data ke url tujuan
    data : theData,
    beforeSend: function(){
      //jQuery(successContainer).after(img);
	},
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      alert(XMLHttpRequest.responseText);
      jQuery("#imgLoading").replaceWith('');
    },
    success: function(msg){
      if (msg.length > 0) {		
        jQuery(successContainer).html(msg);
        jQuery(successContainer).attr('value',msg);
        if(fieldTarget!='') jQuery(fieldTarget).attr('value',fieldVal);               
        jQuery("#imgLoading").replaceWith('');
      }
    }
  });

}

var lastsel;
//var countries = jQuery.ajax({'<?=$expath?>setList.php': $('#ajaxAllCountriesUrl').val(), async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Countries.');}}).responseText;
/*
,{
		name:'saldo'
		,index:'saldo_normal'
		,width:150
		,editable:true
        ,edittype:'select'
		,formatter:'select'
        ,editoptions: {value:{'-1':'Debit','1':'Kredit'}}
        ,editrules: {required:false}
	}*/

jQuery(document).ready(function(){
  
  jQuery("#htmlTable").jqGrid({
    url:'request.php?page=<?=$_REQUEST['page']?>&sender=rekening',
	//editurl:'<?php echo $expath ?>anggaran_request.php?oper=edit',
	editurl:'request.php?page=<?=$_REQUEST['page']?>&sender=rekening&oper=edit',
    datatype: 'json',
    mtype: 'POST',
    colNames:['id','TIPE',"KELOMPOK","JENIS","OBJEK","RINCIAN","SUB1","SUB2","SUB3","KODE REKENING","NAMA REKENING","KATEGORI REKENING"],
    colModel :[{
		name:'id'
		,index:'id'
		,width:20
        ,search:false
	},{
		name:'tipe'
		,index:'tipe'
		,width:50
		,editable:true
        ,edittype:'text'
        ,editoptions: {size:2, maxlength: 1}
        ,editrules: {required:true}
	},{
		name:'kelompok'
		,index:'kelompok'
		,width:80
		,editable:true
        ,edittype:'text'
        ,editoptions: {size:10, maxlength: 10}
        ,editrules: {required:false}
	},{
		name:'jenis'
		,index:'jenis'
		,width:50
		,editable:true
        ,edittype:'text'
        ,editoptions: {size:10, maxlength: 10}
        ,editrules: {required:false}
	},{
		name:'objek'
		,index:'objek'
		,width:50
		,editable:true
        ,edittype:'text'
        ,editoptions: {size:10, maxlength: 10}
        ,editrules: {required:false}
	},{
		name:'rincian'
		,index:'rincian'
		,width:80
		,editable:true
        ,edittype:'text'
        ,editoptions: {size:10, maxlength: 10}
        ,editrules: {required:false}
	},{
		name:'sub1'
		,index:'sub1'
		,width:50
		,editable:true
        ,edittype:'text'
        ,editoptions: {size:10, maxlength: 10}
        ,editrules: {required:false}
	},{
		name:'sub2'
		,index:'sub2'
		,width:50
		,editable:true
        ,edittype:'text'
        ,editoptions: {size:10, maxlength: 10}
        ,editrules: {required:false}
	},{
		name:'sub3'
		,index:'sub3'
		,width:50
		,editable:true
        ,edittype:'text'
        ,editoptions: {size:10, maxlength: 10}
        ,editrules: {required:false}
	},{
		name:'kode'
		,index:'kode_rekening'
		,width:120
		,editable:true
        ,edittype:'text'
        ,editoptions: {size:30, maxlength: 50}
        ,editrules: {required:false}
	},{
		name:'nama'
		,index:'nama_rekening'
		,width:180
		,editable:true
        ,edittype:'text'
        ,editoptions: {size:30, maxlength: 50}
        ,editrules: {required:true}
	},
	{ name:'kategori',index:'id_kategori',width:180,editable:true,edittype:'select',formatter:'select',editoptions: {value:{<?=$value?>}},editrules:{required:true}}
	],
    pager: jQuery('#htmlPager'),
    height:350,
    rowNum:15,
    rowList:[15,30,45],
    shrinkToFit:false,
    sortname: 'id',
    sortorder: 'asc',
	multiselect:true,
	multiboxonly: true,	
    viewrecords: true,
    caption: 'REKENING',
        onSelectRow: function(id){ 
            if(id && id!==lastsel){ 
                //jQuery("#htmlTable").restoreRow(lastsel); 
                //jQuery("#htmlTable").editRow(id,true); 
                lastsel=id;
				
            };
        },
		loadComplete: function() {
			//jQuery("#htmlTable").setColProp('nama', { editoptions: { value: countries} });
		},
        gridComplete: function(){
            jQuery("#htmlTable").setGridWidth( document.width - 100 < 400 ? 400 : document.width - 100);
            return true;
        }
    }).navGrid('#htmlPager' ,
		{add:true,edit:true,del:true},
		{
			afterShowForm:function(){
				jQuery('select#kategori').change(
					function(){
						ajaxFieldGeneral('','request.php?page=<?=$_REQUEST['page']?>&sender=getiperekening&id='+this.value, 'input#tipe');
					}
				); 
				jQuery('input#tipe').change(
					function(){
						ajaxFieldGeneral('','request.php?page=<?=$_REQUEST['page']?>&sender=getiperekening&val='+this.value, 'select#kategori');
					}
				); 			
				return [true]
			},		
			reloadAfterSubmit:true
		} // edit
		,{
		afterShowForm:function(){
			//jQuery('#kode').attr('disabled', 'disabled');
			jQuery('select#kategori').change(
				function(){
					ajaxFieldGeneral('','request.php?page=<?=$_REQUEST['page']?>&sender=getiperekening&id='+this.value, 'input#tipe');
				}
			); 
			jQuery('input#tipe').change(
				function(){
					ajaxFieldGeneral('','request.php?page=<?=$_REQUEST['page']?>&sender=getiperekening&val='+this.value, 'select#kategori');
				}
			); 			
			return [true]
		}, 
		reloadAfterSubmit:true,height:300,  width:500, url:''
		}// add
        ,{} // del
        ,{} // search
        
    ).hideCol('id');  /*end of on ready event */ 
}
);


</script>
<div style='padding:5px;'>
	<!--<fieldset>
	<legend>Form Detail Rekening</legend>
		<div id='asb_simulasi_form'>
			<div class="singleSide">
				<fieldset class="mainForm">
				<label class="leftField"><span>Kode Rekening</span>&nbsp;Tipe:<b class="wajib">*</b><input type="text" name="noRek[]" value="" size="3" maxlength="3" />&nbsp;Kelompok:<input type="text" name="noRek[]" value="" size="3" maxlength="3" />&nbsp;Jenis:<input type="text" name="noRek[]" value="" size="3" maxlength="3" />&nbsp;Objek:<input type="text" name="noRek[]" value="" size="3" maxlength="3" />&nbsp;Rincian:<input type="text" name="noRek[]" value="" size="3" maxlength="3" /></label>
				<label class="leftField"><span>Sub</span>&nbsp;1:<input type="text" name="sub[]" value="" size="3" maxlength="3" />&nbsp;2:<input type="text" name="sub[]" value="" size="3" maxlength="3" />&nbsp;3:<input type="text" name="sub[]" value="" size="3" maxlength="3" /></label>
				<label class="leftField"><span>Nama Rekening&nbsp;<b class="wajib">*</b></span><input type="text" name="nmRekening" value="" size="40" maxlength="100" /></label>
				<label class="leftField"><span>Kategori Rekening&nbsp;<b class="wajib">*</b></span><select name="katRekening"><option value="Kategori Rekening">Kategori Rekening</option></select></label>
				<label class="leftField"><span>% Tarif</span><input type="text" name="tarif" value="" size="5" maxlength="10" /></label>
				<label class="leftField"><span>Tarif Dasar</span><input type="text" name="tarifDasar" value="" size="5" maxlength="10" /></label>
				<label class="leftField"><span>Volume Dasar</span><input type="text" name="volDasar" value="" size="5" maxlength="10" /></label>
				<label class="leftField"><span>Tarif Tambahan</span><input type="text" name="tarifTambahan" value="" size="5" maxlength="10" /></label>
				<label class="leftField"><span>Nomor Perda</span><select name="noPerda"><option value="Nomor Perda">Nomor Perda</option></select>
				<label class="leftField"><span>Tanggal Perda</span><select name="tglPerda"><option value="Tanggal Perda">Tanggal Perda</option></select>
				<label class="leftField"><p><b class="wajib">*</b>&nbsp;Wajib Diisi</p></label>
				</fieldset>
			</div>
			<div class="footer_space">&nbsp;</div>
		</div>		
	</fieldset>-->
	<fieldset>
	<legend>Daftar</legend>	
	<div id='asb_simulasi_form'>
		<div style='padding:5px'>
		<table id="htmlTable" class="scroll"></table>
		<div id="htmlPager" class="scroll"></div>
		</div>
	</div>
	</fieldset>
</div>


         