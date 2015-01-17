  <fieldset>
    <legend>Konfigurasi Pajak</legend>
  </fieldset>

  <table id="grid"></table>
  <div id="pager"></div>
  <br/><p></p>
  <table id="tableRek"></table>
  <div id="pagerRek"></div>
<div id="rincian"></div>	
<script type="text/javascript">
$(document).ready(function() {
  $("#grid").jqGrid({
    url:'<?php echo $daftar_url?>',
    editurl:'<?php echo $ubah_url?>',
    datatype:'json',
    mtype:'POST',
    colNames:['Kode', 'Jenis Pajak', 'OA', 'SA' ],
    colModel:[
        {name:'kode_pr', index:'kode_pr', width:150, editable:false, edittype:'text', editoptions:{size:5,class:'span2'}, editrules:{required:true}},
        {name:'nama_pr', index:'nama_pr', width:350, editable:false, edittype:'text', editoptions:{size:100,class:'span7'}, editrules:{required:true}},
        {name:'oa', index:'oa', width:50,edittype:'checkbox',formatter: "checkbox",editoptions: { value:"1:0"},editable:true,formatoptions: {disabled : true}},
        {name:'sa', index:'sa', width:50,edittype:'checkbox',formatter: "checkbox",editoptions: { value:"1:0"},editable:true,formatoptions: {disabled : true}}
    ],
    rowNum:-1,
    scroll:true,
    rownumbers:true,
    pager:'#pager',
    viewrecords:true,
    gridview:true,
    shrinkToFit:false,
    width:800,
    height:200,
	caption:'Pajak',
	onSelectRow:function(id){
		var id = $("#grid").jqGrid('getGridParam','selrow'); 
		if (id) { 
			var url = "<?php echo base_url()?>master_pajak/get_daftar_rek"+"/"+id;
			$('#tableRek').setGridParam({url:url}).trigger("reloadGrid");
			$("#tableRek").jqGrid('setGridParam').trigger("reloadGrid",[{page:1}]);
		}
	}
  });

  Grid.init({
    grid: '#grid',
    pager: '#pager',
    akses: <?php echo isset($akses) ? $akses : 0; ?>
  });
});

Grid = (function(){
	var options = {
		grid: '#grid',
		pager: '#pager',
		akses: 0
	};

	var self = this, grd, $grd, pgr, last, akses, edit_url;

	initialize = function(opt){
		for(var prop in opt){
			if(opt.hasOwnProperty(prop)){
				options[prop] = opt[prop];
			}
		}

		grd = options.grid;
		$grd = $(grd);
		pager = options.pager;
		akses = options.akses;

		$grd.jqGrid('setGridParam', {
			//onSelectRow: self.restore_row,
			ondblClickRow: self.edit_row,
		});
		
		$grd.jqGrid('bindKeys', { 'onEnter': self.edit_row});
		$grd.jqGrid('navGrid', pager,{
			add: false,
			edit: akses === 3,
			edittext: 'Ubah',
			editfunc: self.edit_row,
			del: false,
			search: false,
			refresh: true,
			refreshtext:'Refresh',
		},{},{},{},{});

		edit_url = $grd.jqGrid('getGridParam', 'editurl');
	}

	aftersavefunc = function(id, resp){
		var o = $.parseJSON(resp.responseText),
			newid = o.id,
			$t = $grd[0],
			ind = $grd.jqGrid("getInd", id,true);

		$.pnotify({
		  title: o.isSuccess ? 'Sukses' : 'Gagal',
		  text: o.message,
		  type: o.isSuccess ? 'info' : 'error'
		});

		$(ind).attr("id", newid);
		if ($t.p.selrow === id) {
		  $t.p.selrow = newid;
		}
		if ($.isArray($t.p.selarrrow)) {
		  var i = $.inArray(id, $t.p.selarrrow);
		  if (i>=0) {
			$t.p.selarrrow[i] = newid;
		  }
		}
		if ($t.p.multiselect) {
		  var newCboxId = "jqg_" + $t.p.id + "_" + newid;
		  $("input.cbox",ind)
			.attr("id", newCboxId)
			.attr("name", newCboxId);
		}

		$grd.jqGrid('setRowData', newid, {'id':newid});
		$('#' + newid).removeClass('jqgrid-new-row');
		last = newid;
		$grd.focus();
		$grd.setSelection(newid);
	}

	successfunc = function(resp){
		var o = $.parseJSON(resp.responseText);

		return o.isSuccess;
	}

	errorfunc = function(id, resp){
		var o = $.parseJSON(resp.responseText);
		$.pnotify({
			title: o.isSuccess ? 'Sukses' : 'Gagal',
			text: o.message ? o.message : 'Server tidak bisa diakses',
			type: resp.status = 200 ? 'warn' : 'error'
		  });
		return true;
	}

	edit_row = function(id){
		/* hanya boleh jika punya hak akses */
		if (akses !== 3) return;

		restore_row(last);
		$grd.jqGrid('editRow', id, true, null, successfunc, null, null, aftersavefunc, errorfunc, null);
		last = id;
	}

	restore_row = function(id){
		if(id && id !== last){
			$grd.jqGrid('restoreRow', last);
			last = null;
		}
	}
  
	return {
		init: initialize,
		edit: edit_row,
		restore: restore_row,
	}
}());
last = null;
	$("#tableRek").jqGrid({
		url:'',
		editurl:'',
		datatype:'json',
		mtype:'POST',
		colNames:['ID','ID_REKENING','KODE REKENING','NAMA REKENING'],
		colModel:[
			{name:'ID',index:'ID',width:5,search:false,hidden:true,sortable: false},
			{name:'ID_REKENING',index:'ID_REKENING',width:5,search:false,hidden:true,sortable: false},
			{name:'KODE_REKENING',index:'KODE_REKENING',width:150,search:false,sortable: false},
			{name:'NAMA_REKENING',index:'NAMA_REKENING',width:500,search:false,sortable: false}
		],
		rowNum:-1,
		scroll:1,
		pager:'#pagerRek',
		shrinkToFit:false,
		viewrecords:true,
		gridview:true,
		width:'800',
		height:'220',
		caption:'Rekening',
		onSelectRow:function(id){
			if(id !== last)
			{
				$('#tableRek').jqGrid('saveRow', last, sukses, 'clientArray', null, after_save_rincian);
			}
		},
		ondblClickRow: function(id){
			$('#tableRek').jqGrid('restoreRow', last);
			$('#tableRek').jqGrid('editRow', id, true, null, null, 'clientArray', null, after_save_rincian, null, after_restore);
			last = id;
		}
	});
	$("#tableRek").jqGrid('bindKeys', {
		onEnter: function(id) {

			$("#tableRek").jqGrid('editRow',id,true,null, null, 'clientArray', function(){
				setTimeout(function(){
					$("#tableRek").focus();
				},100);
			},after_save_rincian, null, after_restore);
		}
	});
	$("#tableRek").jqGrid( 'navGrid', '#pagerRek', {
		add:true,
		addtext:'Tambah',
		addfunc:addRincianPendapatan,
		edit:false,
		del:true,
		deltext:'Hapus',
		delfunc:HapusRinciPendapatan,
		search:false,
		refresh:true,
		refreshtext:'Refresh'
	});
	
	function after_restore()
	{
		last = null;
	}
	
	function after_save_rincian()
	{
		nom_ang();
		last = null;
		ubah = true;
		$(this).jqGrid('setSelection',"1");
		$(this).focus();
	}
	
	function sukses()
	{
		$('#tableRek').jqGrid('restoreRow', last);
	}
	
	function addRincianPendapatan()
	{
		var id = $("#grid").jqGrid('getGridParam','selrow'); 
		if(id){
			var option = {multi:1, mode:'master_pajak'};
			Dialog.pilihRekening(option, function(obj, select){
				var ids = $(obj).jqGrid('getGridParam','selarrrow');
				if(ids.length > 0){
					for (i = 0; i < ids.length; i++){
						var rs = $(obj).jqGrid('getRowData', ids[i]);
						addRowSorted($("#tableRek"), {'id':'ID_REKENING', 'sortName':['KODE_REKENING']}, {'ID':id,'ID_REKENING':rs.idrek, 'KODE_REKENING':rs.kdrek, 'NAMA_REKENING':rs.nmrek});
						$.ajax({
							url: '<?php echo base_url()?>master_pajak/simpan_rek', 
							data: { id:id,idrek:rs.idrek,nmrek:rs.nmrek,kdrek:rs.kdrek},
							success: function(response){
									var msg = $.parseJSON(response);
									$.pnotify({
										title: msg.isSuccess ? 'Sukses' : 'Gagal',
										text: msg.message,
										type: msg.isSuccess ? 'info' : 'error'
									});
									$("#tableRek").trigger("reloadGrid");
								},
							type: "post", 
							dataType: "html"
						});
					}
				}
				else{
					alert("Silahkan pilih salah satu data.");
				}
			});
		}
		else{
			alert('Silahkan Pilih Master Pajak Terlebih Dahulu..');
		}
	}
	function HapusRinciPendapatan(id)
	{
		if(id)
		{
			var rr = $("#tableRek").jqGrid('getRowData', id);
			var answer = confirm('Hapus dari daftar?');
			if(answer == true)
			{
				//$('#tableRek').jqGrid('delRowData', id);
				jQuery.ajax({
					url: '<?php echo base_url()?>master_pajak/hapus_rek', 
					data: { id: rr.ID,idrek: rr.ID_REKENING},
					success: function(response){
							var msg = jQuery.parseJSON(response);
							$.pnotify({
								title: msg.isSuccess ? 'Sukses' : 'Gagal',
								text: msg.message,
								type: msg.isSuccess ? 'info' : 'error'
							});
							$("#tableRek").trigger("reloadGrid");
						},
					type: "post", 
					dataType: "html"
				});
			}
		}
	}

</script>