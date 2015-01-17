<script src="<?php echo base_url()?>assets/fr/fastreport.js" type="text/javascript" ></script>
<table class="layout">
<tr>
	<td><?php $this->load->view('pegawai/menu'); ?></td>
	<td>
		<div id="satker"><span>Satker</span><?php echo $list_satker; ?></div>
		<div>		
		<table id="grid"></table> 
		<div id="pager"></div>
			<div class="<?php echo $this->css->panel();?>">
				<a id="cetak" class="<?php echo $this->css->button();?>" 
				onclick="fastReportStart('Daftar Pegawai', 'daftar_pegawai', 'pdf', 'id_satker=' + id_satker, 1)">Cetak Daftar Pegawai
				<span class="<?php echo $this->css->iconprint();?>"></span>
				</a>
			</div>
		</div>
	</td>
</tr>
</table>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery("#grid").jqGrid({ 
			url:'<?php echo site_url('pegawai/get_daftar')."/".$default_satker; ?>', 
			editurl:'<?php echo site_url('pegawai')?>', 
			datatype: "json", 
			mtype: 'POST',
			colNames:['ID', 'NIP', 'Nama', 'Pangkat', 'Satker'], 
			colModel:[ 
				{name:'id',index:'id_pegawai', width:20, search:false, hidden:true}, 
				{name:'nip',index:'nip', width:100, align:"left"},
				{name:'nama',index:'nama_pegawai', width:150, align:"left"},
				{name:'pangkat',index:'kode_pangkat', width:40, align:"center"},
				{name:'satker',index:'nama_satker', width:100, align:"left"}
			], 
			rowNum:10, 
			rowList:[10,20,30], 
			rownumbers: true,
			pager: '#pager', 
			sortname: 'a.nip', 
			sortorder: "asc", 
			viewrecords: true, 
			gridview: true,
			multiselect: true,
			multiboxonly: true,
			width: 600,
			height: 230,
			caption:"Daftar Pegawai",
			ondblClickRow: function(id){ 
				location.href = "<?php echo site_url('pegawai/edit/')."/"; ?>" + id;
			}
		}); 
		jQuery("#grid").jqGrid('navGrid','#pager',{edit:false,add:false,del:true});
		
		$("#cetak").hover(
			function() {
				$(this).addClass("<?php echo $this->css->hover();?>");
			},
			function() {
				$(this).removeClass("<?php echo $this->css->hover();?>");
			}
		);
		
		id_satker = $("#list_satker").val();
		$("#list_satker").change(function(){
			$("#grid").jqGrid('setGridParam', {url: '<?php echo site_url('pegawai/get_daftar')?>/' + $('#list_satker').val()}).trigger("reloadGrid");
		});
	})
</script>