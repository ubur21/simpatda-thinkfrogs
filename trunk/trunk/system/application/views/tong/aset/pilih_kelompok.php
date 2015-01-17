<table class="search-dialog" align="left" border="0">
	<tr>
		<th align="left" border="0" nowrap colspan="3">
			<input type="text" id="<?php echo $prefix;?>_search" style="width: 300px;" value="">
			<input type="button" id="<?php echo $prefix;?>_btn_search" value="Cari" onclick="search_child( '<?php echo $prefix;?>', '<?php echo "&prefix=".$prefix."&tag=".$tag.( $radio ? "&radio=1" : "" );?>' )">
		</th>
	</tr>
	<tr id="<?php echo $prefix;?>_row_">
		<th>&nbsp;</th>
		<th width="100px" align="center"><b>Kode</b></td>
		<th align="left"><b>Nama</b></td>
	</tr>
	<tr id="zzzzzzzzzz">
		<td colspan="3" id="<?php echo $prefix;?>_data"></td>
	</tr>
</table>
<div id="dialog-load"></div>

<script type="text/javascript" language="Javascripts">
<!--
<!--
	var _cphp_ = '<?php echo $prefix; ?>';
	var radio = <?php echo $radio; ?>;
	var trigval = $( '#<?php echo $idtrigger;?>' ).val();
	var inputval = $( '#<?php echo $idinput;?>' ).val();
	var extra = '&inputval='+inputval;
	var textall = '<?php echo $textall; ?>';

<?php 
	echo "_datas_ = new Array();\n";
	for($i = 0; $i < count($idhidden); $i++)
	{
		echo "_datas_[".$i."] = new Array();\n";
		$datas = explode("|", $idhidden[$i]);		
		for ($j = 0; $j < 4; $j++)
		{
			echo "_datas_[".$i."][".$j."] = '". (isset($datas[$j]) ? $datas[$j] : '') ."';\n";
		};
	}
?>
	
	/**
	* Ambil child dari suatu tree item
	* @param prefix prefix tree
	* @param idparent item id yang jadi parent
	* @param extra parameter tambahan
	*/
	function load_child( prefix, idparent, extra ) {
		$('#dialog-load').load('<?php echo site_url(); ?>/aset/get_child', { _cphp_: _cphp_, radio: radio, extra: extra });
	}
	load_child('kelompok', '', extra);

-->
</script>