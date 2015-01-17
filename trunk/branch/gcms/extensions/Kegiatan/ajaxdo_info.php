<?  include('./../../../config.php');
	include('./../../../lib.php');
    
	if(!b_antisqlinjection($_GET['id_kelompok'])) die();
	if(!empty($_GET['nomor_id'])){
		$csql="select * from kegiatan where id_kelompok=".$_GET['nid'];
		if($nresult=gcms_query($csql)){
			$row=gcms_fetch_object($nresult);
?> 			document.getElementById('nid').value="<?=$row->id_kelompok?>";
			
<?		}
	}else{ 	?>
		document.getElementById('nid').value='';
<?	} ?>
