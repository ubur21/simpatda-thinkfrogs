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
				onclick="fastReportStart('Daftar DUK', 'daftar_duk', 'pdf', 'id_satker=' + id_satker, 0)">Cetak DUK
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
			url:'<?php echo site_url('pegawai/get_duk')."/".$default_satker; ?>', 
			datatype: "json", 
			mtype: 'POST',
			colNames:['ID', 'Nama', 'NIP', 'Tgl Lahir', 'Gol/Ruang', 'TMT Gol.', 'Jabatan', 'TMT Jabatan', 'Masa Kerja', 'Pendidikan', 'Tahun Lulus'], 
			colModel:[ 
				{name:'id',index:'id_pegawai', width:20, search:false, hidden:true}, 				
				{name:'nama',index:'nama_pegawai', width:200, align:"left"},
				{name:'nip',index:'nip', width:130, align:"left"},
				{name:'tgl_lahir',index:'tgl_lahir', width:80, align:"center", datefmt: 'dd/mm/yyyy', formatter:'date'},
				{name:'pangkat',index:'pangkat', width:60, align:"center"},
				{name:'tmt',index:'tmt', width:80, align:"center"},
				{name:'jabatan',index:'jabatan', width:100, align:"left"},
				{name:'tmt_jabatan',index:'tmt_jabatan', width:80, align:"center"},
				{name:'masa_kerja',index:'masa_kerja', width:80, align:"center"},
				{name:'pendidikan',index:'pendidikan', width:100, align:"left"},
				{name:'lulus',index:'lulus', width:80, align:"left"}
			], 
			rowNum:10, 
			rowList:[10,20,30,50,80,100], 
			rownumbers: true,
			pager: '#pager', 
			viewrecords: true, 
			gridview: true,
			shrinkToFit: false,
			//width: 600,
			height: "auto",
			caption:"Daftar Urut Kepangkatan"
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
			$("#grid").jqGrid('setGridParam', {url: '<?php echo site_url('pegawai/get_duk')?>/' + $('#list_satker').val()}).trigger("reloadGrid");
			id_satker = this.value;
		});
	})
</script>