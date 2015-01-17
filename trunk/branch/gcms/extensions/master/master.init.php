<?php
/*
GROUP::Entri Data
NAME:: Simulasi
TYPE:: frontend
LEVEL:: 1
DEPENDENCY:: 
INFO:: -
VERSION:: 0.1
AUTHOR::  widi
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

</script>