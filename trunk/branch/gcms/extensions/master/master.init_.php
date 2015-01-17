<?php
/*
GROUP::Entri Data
NAME:: Kegiatan
TYPE:: frontend
LEVEL:: 1
DEPENDENCY:: 
INFO:: -
VERSION:: 0.1
AUTHOR::  umi
URL:: 
SOURCE:: 
*/
$expath = ".".str_replace("\\", "/", str_replace(realpath("."), "", dirname(__FILE__)))."/";

	function getFirstKelompok(){
        $id = 0;
        $sql = 'select first 1 id from kelompok_kegiatan order by kode_kelompok';
        $result = gcms_query($sql);
        while ($row = gcms_fetch_object($result)){
            $id = $row->id;
        }
        return $id;
	}
	
    function getSKPD(){
        $sql =' select * from skpd s order by s.kode_skpd ';
		$result=gcms_query($sql);
        $r = 0 ;
        $s = '';
        while ($row = gcms_fetch_object($result)) {
        $skpd = $row->kode_skpd." - ".$row->nama_skpd;
            $r > 0?$s .= ';': '';
            $s .= $row->id.":".$skpd;
           $r++ ;
           }
        return $s;
	}


	function getNamaIndikator($id){     
       
       $sql = 'select first 1 nama_indikator_1, nama_indikator_2, nama_indikator_3, nama_indikator_4 '.
              'from kelompok_kegiatan where id = '.$id;
       $result = gcms_query($sql);
      
        while ($row = gcms_fetch_object($result)){
            $nama[1] = $row->nama_indikator_1;
            $nama[2] = $row->nama_indikator_2;
            $nama[3] = $row->nama_indikator_3;
            $nama[4] = $row->nama_indikator_4;
        }
        return $nama;
    }
    
$id = isset($_POST['nid'])?$_POST['nid']:getFirstKelompok();
 
?> 
<link rel="stylesheet" type="text/css" media="screen" href="script/jquery/themes/ui.jqgrid.css" />
<script src="script/jquery/jquery.js" type="text/javascript"></script>
<script src="script/jquery/jquery-ui-1.7.1.custom.min.js" type="text/javascript"></script>
<script src="script/jquery/jquery.layout.js" type="text/javascript"></script>
<script src="script/jquery/grid.locale-id.js" type="text/javascript"></script>
<script src="script/jquery/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="script/jquery/jquery.tablednd.js" type="text/javascript"></script>

<script type="text/javascript" src="ajaxdo.js"></script>
<script type="text/javascript" src="./extensions/fastreport/fastreport.js"></script>
<script type="text/javascript"> 
var lastsel;
var opsiSKPD = "<?php echo getSKPD() ?>";
<?php
  $nama = getNamaIndikator($id);  
?>
jQuery(document).ready(function(){ 
    $("#nid").css("width", document.width - 300 < 300?300:document.width - 300);
}
);

jQuery(document).ready(function(){ 
  jQuery("#htmlTable").jqGrid({
    url:'<?php echo $expath ?>kegiatan_request.php?q=1&id_kelompok=<?php echo $id;?>',
    editurl:'<?php echo $expath ?>kegiatan_request.php?oper=edit',
    datatype: 'json',
    mtype: 'POST',
    colNames:['id',
    'id_kelompok',
    'SKPD',
    'Kode', 
    'Nama Kegiatan',
    'Belanja Pegawai',
    'Belanja Barang Jasa',
    'Belanja Modal',
    'Total',
    '<?php echo empty($nama[1])?'Indikator 1':$nama[1] ?>',
    '<?php echo empty($nama[2])?'Indikator 2':$nama[2] ?>', 
    '<?php echo empty($nama[3])?'Indikator 3':$nama[3] ?>',
    '<?php echo empty($nama[4])?'Indikator 4':$nama[4] ?>'],
    colModel :[{
         name:'id'
        ,index:'id'
        ,search:false
        ,width:20
    },{
         name:'id_kelompok'
        ,index:'id_kelompok'
        ,width:20
        ,search:false
        ,editable:true
    },{
         name:'id_skpd'
        ,index:'id_skpd'
        ,width:100              
        ,editable:true
        ,edittype:"select"
        ,formatter:customFormat
        ,editoptions:{value :opsiSKPD}
        },
    {
        name:'kode'
        ,index:'kode_kegiatan'
        ,width:80
        ,editable:true
        ,editrules: {required:true}
    },{
        name:'nama'
        ,index:'nama_kegiatan'
        ,width:400
        ,align:'left'
        ,editable:true
        ,edittype:"textarea"
        ,editoptions:{size:90}
        ,editrules: {required:true}
    },{
        name:'pegawai'
        ,index:'belanja_pegawai'
        ,width:120
        ,align:'right'
        ,editable:true
        ,formatter:'currency'
        ,editrules: {required:true, number:true}
    },
    {
        name:'barang_jasa'
        ,index:'belanja_barang_jasa'
        ,width:120
        ,align:'right'
        ,editable:true
        ,formatter : 'currency'
        ,editrules: {required:true, number:true}
    },
   {
        name:'modal'
        ,index:'belanja_modal'
        ,width:120
        ,align:'right'
        ,editable:true
        ,formatter : 'currency'
        ,editrules: {required:true, number:true }
    },
    {
        name:'total'
        ,index:'total'
        ,width:110
        ,align:'right'
        ,editable:false
        ,formatter : 'currency'
     },
    {
          name:'indikator_1'
        ,index:'indikator_1'
        ,width:80
        ,align:'left'
        ,editable:<?php echo empty($nama[1])? 'true':'true'; ?>
        ,formatter : 'number'
        ,editrules: {number:true}
    },{
          name:'indikator_2'
        ,index:'indikator_2'
        ,width:80
        ,align:'left'
        ,editable:<?php echo empty($nama[2])? 'true':'true'; ?>
        ,formatter : 'number'
        ,editrules: {number:true}
    },{
          name:'indikator_3'
        ,index:'indikator_3'
        ,width:80
        ,align:'left'
        ,editable:<?php echo empty($nama[3])? 'true':'true'; ?>
        ,formatter : 'number'
        ,editrules: {number:true}
    },{
          name:'indikator_4'
        ,index:'indikator_4'
        ,width:80
        ,align:'left'
        ,editable:<?php echo empty($nama[4])? 'true':'true'; ?>
        ,formatter : 'number'
        ,editrules: {number:true}
    }],
    pager: jQuery('#htmlPager'),
    height:350,
    rowNum:15,
    rowList:[15,30,45],
    sortname: 'id',
    sortorder: 'asc',
    shrinkToFit:false,
    viewrecords: true,
    caption: 'Kegiatan',
        onSelectRow: function(id){ 
            if(id && id!==lastsel){ 
                jQuery("#htmlTable").restoreRow(lastsel); 
                jQuery("#htmlTable").editRow(id,true, updateTotal); 
                lastsel=id; 
                }
             },
        gridComplete: function(){
                jQuery("#htmlTable").setGridWidth( document.width - 160 < 300?300:document.width - 160);
                return true;
            },
        afterSaveCell : function(){
                alert('saving');
            }
    }).navGrid('#htmlPager'
        ,{add:true,edit:false,del:true}
        ,{} // edit
        ,{height:350, width:610,onclickSubmit : encrypt } // add
        ,{} // delete
        ).hideCol(['id', 'id_kelompok']); /* end of on ready event */ 
}
);


function ajaxdo_pilih_kelompok(bid){

        old_url = jQuery("#htmlTable").getGridParam("url");
        new_url = old_url + '?q=1&id_kelompok='+bid;
        jQuery("#htmlTable").setGridParam({url:new_url});
        jQuery("#htmlTable").trigger("reloadGrid");
        //jQuery("#htmlTable").setGridParam({url:old_url});

        jQuery.ajax({
           url:"<?php echo $expath ?>getnama.php",
           data:"bid=" +bid,
           success: function(html){            
                var indi = html;
                var nama = indi.split(';');
                for(var i=1; i<nama.length+1;i++){
                    var nama_ = nama[i-1].split(":");
                    if (trim(nama_[1]) == "") {
                        jQuery("#htmlTable").setLabel("indikator_"+i, "Indikator "+(i));
                    } else {
                        jQuery("#htmlTable").setLabel("indikator_"+i, nama_[1]);
                    }
                }
            }
        }
        );
        
    };
  
     
function hapusinfo(){
        el=document.getElementById("panel_1")
        el.style.display="none"

    };

function encrypt(eparams) {
    var sr = jQuery("#htmlTable").getGridParam('selrow');
    rowdata = jQuery("#htmlTable").getRowData(sr);
          return {id_kelompok: $( '#nid' ).val()};
};

function ajaxdo_proses(bid){
        var cdo='<?php echo $expath ?>asb.php?gid='+bid;
	//	alert( bid );
        ajax_do(cdo);
    }
    
function printReport(){
        var nameFile,template;
                nameFile="ASB";
                template="ASB.fr3";
        var key = "id="+document.getElementById('nid').value;
        var att = 1;
            fastReportStart(nameFile, template, 'pdf', key, att);
    }    
    
function printDaftar(){
        var nameFile,template;
                nameFile="LIST_ASB";
                template="LIST_ASB.fr3";
        var key = "id="+document.getElementById('nid').value;
        var att = 1;
            fastReportStart(nameFile, template, 'pdf', key, att);
    }

function customFormat(cData){
    cData = cData + "";
    var so = opsiSKPD.split(';');
    var sv = [];
    var ret = '';
    for(var i=0; i<so.length;i++){
        sv = so[i].split(":");
        if($.trim(sv[0]) == $.trim(cData)) {
            ret = sv[1];
            break;
        }
        if($.trim(sv[1]) == $.trim(cData)) {
            ret = sv[1];
            break;
        }
    }
    return ret;
}

var updateTotal = function(id){
     jQuery('#'+id+"_"+"pegawai").change(function(){
        var modal = 0;
        var barang = 0;
        modal = jQuery("#"+id+"_modal").val();
        barang = jQuery("#"+id+"_barang_jasa").val();
        
        jQuery("#htmlTable").setCell(id, 'total', parseFloat(this.value) + parseFloat(modal) + parseFloat(barang));
     }
     );
     jQuery('#'+id+"_"+"barang_jasa").change(function(){
        var modal = 0;
        var barang = 0;
        modal = jQuery("#"+id+"_modal").val();
        pegawai = jQuery("#"+id+"_pegawai").val();
        
        jQuery("#htmlTable").setCell(id, 'total', parseFloat(this.value) + parseFloat(modal) + parseFloat(pegawai));
     }
     );
     jQuery('#'+id+"_"+"modal").change(function(){
        var modal = 0;
        var barang = 0;
        pegawai = jQuery("#"+id+"_pegawai").val();
        barang = jQuery("#"+id+"_barang_jasa").val();
        
        jQuery("#htmlTable").setCell(id, 'total', parseFloat(this.value) + parseFloat(pegawai) + parseFloat(barang));
     }
     );
}

function ajaxdo_set_label (bid){
 jQuery("#htmlTable").setCell(id_kelompok, 'indikator_1', parseFloat(this.value));

}

</script> 
