

<form id="frm" method="post" action="<?php echo base_url(); ?>sts/proses">
<fieldset>
  <legend id="bc" > Surat Tanda Setoran</legend>
</fieldset>
  <div class="controls-row">
    <div class="control-group pull-left" data-bind="validationElement: kd_skpd">
			<label class="control-label" for="idrek">SKPD</label>
			<input type="hidden"  id="idskpd" data-bind="value: idskpd, executeOnEnter: pilih_skpd" required />
			<input type="text" class="span2" id="kd_skpd" readonly="1" data-bind="value: kd_skpd, executeOnEnter: pilih_skpd" required />
			<div class="controls span8 input-append">
			  <input type="text" class="span8" id="nama_skpd" readonly="1" data-bind="value: nama_skpd, executeOnEnter: pilih_skpd" required />
			  <span class="add-on" data-bind="visible: !isEdit() && canSave(),  click: pilih_skpd" ><i class="icon-folder-open"></i></span>
			</div>
		</div>
  </div>

  <div class="controls-row" >
    <div class="control-group pull-left" data-bind="validationElement: no_sts">
      <label class="control-label" for="no_sts">Nomor STS</label>
      <input type="text" class="span2" id="no_sts" name="no_sts" data-bind="value: no_sts" required />
	  
    </div>
    <div class="control-group pull-left" style="margin-top:20px" data-bind="validationElement: otm" >
	<input type="checkbox" id="otm" name="otm" class="form-control"  data-bind="value: otm" /> OTM
	
	</div>
    <div class="control-group pull-left" style="margin-left:20px" data-bind="validationElement: tgl_sts" >
      <label class="control-label" for="tgl_sts">Tanggal STS</label>
      <input type="text" class="datepicker span2" id="tgl_sts" name="tgl_sts" data-bind="value: tgl_sts" required />
    </div>
	<div class="control-group pull-left" data-bind="validationElement: jurnal_akrual" style="margin-left:20px" >
			<label class="control-label" for="jurnalakrual" style="margin-left:20px">Jurnal Akrual</label>
			<div class="controls span2 input-append" >
				<input type="hidden"  id="id_jurnal" data-bind="value: id_jurnal" required />
			  <input type="text" class="span2" id="jurnal_akrual" readonly="1" data-bind="value: jurnal_akrual, executeOnEnter: pilih_jurnal" required />
			  <span class="add-on" data-bind="visible: !isEdit() && canSave(),  click: pilih_jurnal" ><i class="icon-folder-open"></i></span>
			</div>
		</div>
  </div>
  
  
  <div class="controls-row" >
  <div class="control-group pull-left" >
	  <div class="controls-row" >
		<div class="control-group pull-left" data-bind="validationElement: akun_bendahara" style="margin-left:-20px;" >
			<div class="controls span3 input-append">
				<label class="control-label" for="namabendahara">Nama Bendahara</label>
				<input type="text" class="span3" id="nama_bendahara" readonly="1" data-bind="value: nama_bendahara" required />
			</div>
			<div class="controls span2 input-append">
			  <label class="control-label" for="akunbendahara">Akun Bendahara</label>
			  <input type="hidden"  id="id_bendahara" data-bind="value: id_bendahara" required />
			  <input type="text" class="span2" id="akun_bendahara" readonly="1" data-bind="value: akun_bendahara, executeOnEnter: pilih_bendahara" required />
			  <span class="add-on" data-bind="visible: !isEdit() && canSave(),  click: pilih_bendahara" ><i class="icon-folder-open"></i></span>
			</div>
		</div>
	  </div>
	  
	  <div class="controls-row" >
		<div class="control-group pull-left" data-bind="validationElement: akun_kasdaerah" style="margin-left:-20px;" >
			<div class="controls span3 input-append">
				<label class="control-label" for="namaakunkas">Nama Kas Daerah</label>
				<input type="text" class="span3" id="nama_bendahara" readonly="1" data-bind="value: nama_kasdaerah" required />
			</div>
			<div class="controls span2 input-append">
			  <label class="control-label" for="akunkasdaerah">Akun Kas Daerah</label>
			  
			  <input type="hidden"  id="id_kasdaerah" name="id_kasdaerah" data-bind="value: id_kasdaerah" required />
			  <input type="text" class="span2" id="akun_kasdaerah" readonly="1" data-bind="value: akun_kasdaerah, executeOnEnter: pilih_akunkas" required />
			  <span class="add-on" data-bind="visible: !isEdit() && canSave(),  click: pilih_akunkas" ><i class="icon-folder-open"></i></span>
			</div>
		</div>
	  </div>
  </div>
  
  <div class="control-group pull-left" style="margin-left:40px" data-bind="validationElement: keterangan" >
		<label class="control-label" for="keterangan">Keterangan</label>
		  <textarea style="height:80px;"  class="span5" id="keterangan" name="keterangan" data-bind="value: keterangan" required >
		  </textarea>
		  <input type="hidden" style="height:80px;"  class="span5" id="idspt" name="idspt" data-bind="value: idspt" required />
		  
  </div>
  </div>
	Rincian Setoran <input type="checkbox" id="rincian_setoran" name="rincian_setoran" class="form-control" value="1" /> Berdasar TBP
	<br/><br/>
  <table id="grid"></table>
  <div id="pager"></div>

<br />
  <div class="controls-row pull-left">
  <div class="btn-group dropup">
    <button type="button" class="btn btn-primary" data-bind="visible: !isEdit() && canSave(),  click: pilih_pajakmanual"  />Tambah</button>
  </div>
  <div class="btn-group dropup">
    <button type="button" class="btn btn-primary" data-bind="enable: canSave, click: select_row" />Ubah</button>
  </div>
  <div class="btn-group dropup">
    <button type="button" class="btn btn-primary" data-bind="visible:isEdit() ,enable: canSave, click: delete_row" />Hapus</button>
  </div>
</div>
  
  <div class="controls-row pull-right">
		Total Setor : <input type="text" id="total_setor" name="total_setor"  class="form-control currency" data-bind="value: rincian_setoran" required />
  </div>
  
  <br/>
  <br/>
  <br/>
  <fieldset>
  <br/>
    <legend></legend>
<div class="controls-row pull-right">
  <div class="btn-group dropup">
    <button type="button" class="btn btn-primary" data-bind="enable: canSave, click: function(data, event){save(false, data, event) }" />Proses</button>
  </div>
  <div class="btn-group dropup">
    <button type="button" class="btn btn-primary" data-bind="enable: canPrint, click: print" >Cetak</button>
    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" data-bind="enable: canPrint">
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
      <li><a href="#">PDF</a></li>
      <li><a href="#">XLS</a></li>
    </ul>
  </div>
  <input type="button" id="back" value="Kembali" class="btn btn-primary" data-bind="click: back" />
</div>
</fieldset>
  
</form>



<script>
$(document).ready(function() {
  $.datepicker.setDefaults($.datepicker.regional['id']);

  $('.datepicker#tgl').datepicker({
    onSelect:function(selectedDate) {
      App.tgl(selectedDate);
      $('.datepicker#tgl_sts').datepicker("option", "minDate", selectedDate);
      var aDate = selectedDate.split('/'),
          bDate = new Date(aDate[2], aDate[1]-1, aDate[0]),
          cDate = new Date(bDate.getTime() + 30*24*60*60*1000);
      var dd = cDate.getDate(), mm = cDate.getMonth()+1, yy = cDate.getFullYear();
      var dDate = dd + '/' + mm + '/' + yy;
      App.tgl_sts(dDate);
    }
  });
  
  $('.datepicker#tgl_sts').datepicker({
    onSelect:function(selectedDate) {
      App.tgl_sts(selectedDate);
    },
    minDate:$('.datepicker#tgl').val(),
  });
});

  $("#grid").jqGrid({
    url:'',
    datatype:'local',
    mtype:'POST',
    colNames:['id sts', 'Kode Akun', 'Nama Akun', 'Nominal Setoran', 'Sisa Belum Setor'],
    colModel:[
        {name:'idsts', hidden:true},
        {name:'noakun', width:150},
        {name:'nama', width:200},
		{name:'nominal', width:100, formatter:'currency', align:'right', editable:true, 
          editoptions:{size:50,class:'span2'}},
		{name:'sisa', width:100, formatter:'currency', align:'right', editable:true, 
          editoptions:{size:50,class:'span2'}},
    ],
    pager:'#pager',
    rowNum:-1,
    scroll:true,
    rownumbers:true,
    viewrecords:true,
		multiselect:true,
    gridview:true,
    shrinkToFit:true,
    recordtext:'{2} baris',
    width:675,
    height:150,
    onSelectRow: select_row,
    onSelectAll: select_row,
	ondblClickRow: edit_row,
  });

  $("#grid").jqGrid('bindKeys', {
  });

  $("#grid").jqGrid('navGrid', '#pager', {
    add:false,
    edit:true,
    del:false,
	editfunc: edit_row,
    search:false,
    refresh:false,
  },{},{},{},{});
  
  function edit_row(id){
    $(this).jqGrid('saveRow', null, null, 'clientArray', null, null);
    $(this).jqGrid('editRow', id, true, null, null, 'clientArray', null, null);
    last = id;
  }
  
  $("#grid").jqGrid('bindKeys', {
    "onEnter": select_row
  });
  
  function select_row()
  {
    var id = $("#grid").jqGrid('getGridParam', 'selarrrow');
    App.idspt(id);
	if(id.length)
	{
		var total = 0;
		for (var i=0;i<id.length;i++)  // For Multiple Delete of row
			{
				total += parseInt(jQuery("#grid").jqGrid('getCell',id[i],'nominal'));
				App.rincian_setoran(total); 
			}
	}
  }
  
  function delete_row()
  {
    var id = $("#grid").jqGrid('getGridParam', 'selarrrow');
    
	$.ajax(
            {
              url: 'sts/delete',
              type: 'post',
              dataType: 'json',
              data: 'id='+id,
              success: function(res, xhr)
              {
                $('#grid').trigger('reloadGrid');
              }
            });
  }

  ko.validation.init({
    insertMessages: false,
    decorateElement: true,
    errorElementClass: 'error',
  });

 var ModelSts = function (){
    var self = this;
    self.modul = 'Tbp';
    self.akses_level = ko.observable(03);
    self.id = ko.observable('<?php echo isset($data['ID'])?$data['ID']:0 ?>');
	
    self.id_jurnal = ko.observable('<?php echo isset($data['ID_JURNAL'])?$data['ID_JURNAL']:0 ?>');
    self.id_bendahara = ko.observable('<?php echo isset($data['ID_BENDAHARA'])?$data['ID_BENDAHARA']:0 ?>');
	
	self.nomor_tbp = ko.observable('<?php echo isset($tipe) ? $tipe : '' ?>');
	self.tgl_bayar = ko.observable('<?php echo isset($data['TGL_SETOR'])?$data['TGL_SETOR']:0 ?>');
	self.alamat = ko.observable('<?php echo isset($tipe) ? $tipe : '' ?>');
	
	self.nama_bendahara = ko.observable('<?php echo isset($tipe) ? $tipe : '' ?>');
	self.akun_bendahara = ko.observable('<?php echo isset($tipe) ? $tipe : '' ?>');
	self.idspt = ko.observable('<?php echo isset($tipe) ? $tipe : '' ?>');
	self.jurnal_akrual = ko.observable('<?php echo isset($tipe) ? $tipe : '' ?>');
	self.rincian_setoran = ko.observable('<?php echo isset($data['TOTAL_SETOR'])?$data['TOTAL_SETOR']:0 ?>');
	
	self.nama_kasdaerah = ko.observable('<?php echo isset($tipe) ? $tipe : '' ?>');
	self.akun_kasdaerah = ko.observable('<?php echo isset($tipe) ? $tipe : '' ?>');
	self.id_kasdaerah = ko.observable('<?php echo isset($tipe) ? $tipe : '' ?>');
	
	self.tgl_sts = ko.observable('<?php echo isset($data['TGL_SETOR'])?$data['TGL_SETOR']:0 ?>');
	self.no_sts = ko.observable('<?php echo isset($data['NOMOR_STS'])?$data['NOMOR_STS']:0 ?>');
	self.otm = ko.observable('<?php echo isset($tipe) ? $tipe : '' ?>');
	self.keterangan = ko.observable('<?php echo isset($data['KETERANGAN'])?$data['KETERANGAN']:0 ?>');
	
	    self.id_wp = ko.observable('<?php echo isset($data['ID_WAJIB_PAJAK']) ? $data['ID_WAJIB_PAJAK'] : 0 ?>');

	
	self.idskpd = ko.observable('<?php echo isset($data['ID_JENIS_PAJAK']) ? $data['ID_JENIS_PAJAK'] : 0 ?>')
    self.kd_skpd = ko.observable('<?php echo isset($data['KODE_SKPD']) ? $data['KODE_SKPD'] : '' ?>')
      .extend({
        required: {params: true, message: 'Kode SKPD tidak boleh kosong'},
      });
    self.nama_skpd = ko.observable('<?php echo isset($data['NAMA_SKPD']) ? $data['NAMA_SKPD'] : '' ?>')
      .extend({
        required: {params: true, message: 'Nama SKPD tidak boleh kosong'},
    });  
	  
    self.mode = ko.computed(function(){
      return self.id() > 0 ? 'edit' : 'new';
    });

    self.title = ko.computed(function(){
      return (self.mode() === 'edit' ? 'Edit ' : 'Entri ') + self.modul;
    });

   self.isEdit = ko.computed(function(){
      return self.mode() === 'edit';
    });

  self.isEnable = ko.computed(function(){
      return self.mode() === 'edit';
    });

    self.canSave = ko.computed(function(){
      return self.akses_level() >= 3;
    });

    self.errors = ko.validation.group(self);
  } //end ModelTbp

  var App = new ModelSts();

  App.back = function(){
    location.href = root+modul;
  }

  App.save = function(){
    var $frm = $('#frm'),
      data = JSON.parse(ko.toJSON(App));

    if (!App.isValid()) {
      App.errors.showAllMessages();;
      return ;
    }

    var file = document.getElementById('image').files[0];
    var formData = new FormData($('form#frm')[0]);

    //tanpa image
    if(App.image() == "")
    {
      $.ajax(
            {
              url: $frm.attr('action'),
              type: 'post',
              dataType: 'json',
              data: data,
              success: function(res, xhr)
              {
                if (res.id) App.id(res.id);

                $.pnotify(
                  {
                    title: res.isSuccess ? 'Sukses' : 'Gagal',
                    text: res.message,
                    type: res.isSuccess ? 'info' : 'error'
                  });
              }
            });
    }
    //dengan image
    else
    {
      $.ajax(
        {
          url: App.url,
          type: 'post',
          dataType: 'json',
          data: formData,
          cache: false,
          contentType: false,
          processData: false,
          success: function(res)
          {
            $.pnotify(
              {
                title: res.isSuccess ? 'Sukses' : 'Gagal',
                text: res.message,
                type: res.isSuccess ? 'info' : 'error'
              });
            if(res.isSuccess == true)
            {
              $.ajax(
                {
                  url: $frm.attr('action'),
                  type: 'post',
                  dataType: 'json',
                  data: $.extend(data,{icon:res.filename}),
                  success: function(res, xhr)
                  {
                    if (res.id) App.id(res.id);

                    $.pnotify(
                      {
                        title: res.isSuccess ? 'Sukses' : 'Gagal',
                        text: res.message,
                        type: res.isSuccess ? 'info' : 'error'
                      });
                  }
                });
            }

          }
        });
    }
  }
	

	
  
    
  App.pilih_skpd = function(){
    if (!App.canSave() || App.isEdit()) { return; }
    var option = {multi:0, mode:'skpd'};
    Dialog.pilihSKPD(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.idskpd(rs.id);
      App.kd_skpd(rs.kode);
      App.nama_skpd(rs.nama);
	  
	  $.getJSON(root+'group/get_jabatan_skpd/'+rs.id, function(data){
          if (data != null) {
			
            
            $.each(data, function(i,val) {
              var html = '<option value="'+i+'">'+val+'</option>';
			  $("#jabatan").append(html);
            });
			
          }
        });
	  
    });
  }
  
  if('<?php echo $mode; ?>' == 'update'){
	var $list = $('#grid'), errmsg = [];
	$.ajax({
				url: root+modul+'/getlisttbp_uppdate',
				type: 'post',
				dataType: 'json',
				data: 'no_sts='+<?php echo $data['NOMOR_STS']; ?>,
				success: function(response){
					var result = response.rows,
					len = response.len;
					if (len > 0) {
						$list.clearGridData();
						for (i = 0; i < len; i++){
							$list.jqGrid('addRowData', 'idsts', [{'idsts':result[i]['idsts'], 'noakun':result[i]['noakun'], 'nama':result[i]['nama'], 'nominal':result[i]['nominal'], 'sisa':result[i]['sisa']}]);
							$("#jqg_grid_" + result[i]['idsts']).prop('checked', true);
						}
						//hitungTotal();
						//hitungSisa();
						//jumlahAll();
					}
					else {
						if (App.id_spt() !== 0 && App.idpjk() !== 0) {
							errmsg.push('SPT dari WP/WR dan Pajak tsb tidak ada yang belum lunas.');
							App.errors.showAllMessages();
							if (errmsg.length > 0) {
								$.pnotify({
									title: 'Perhatian',
									text: errmsg.join('</br>'),
									type: 'warning'
								});
								return false;
							}
						}
					}
				}
			});
  }
  
  $('#rincian_setoran').click(function(){
		var $list = $('#grid'), errmsg = [];
		var isChecked = jQuery('#rincian_setoran').is(':checked');
		console.log(isChecked);
		if(isChecked){
			$.ajax({
				url: root+modul+'/getlisttbp',
				type: 'post',
				dataType: 'json',
				data: {},
				success: function(response){
					var result = response.rows,
					len = response.len;
					if (len > 0) {
						$list.clearGridData();
						for (i = 0; i < len; i++){
							$list.jqGrid('addRowData', 'idsts', [{'idsts':result[i]['idsts'], 'noakun':result[i]['noakun'], 'nama':result[i]['nama'], 'nominal':result[i]['nominal'], 'sisa':result[i]['sisa']}]);
						}
						//hitungTotal();
						//hitungSisa();
						//jumlahAll();
					}
					else {
						if (App.id_spt() !== 0 && App.idpjk() !== 0) {
							errmsg.push('SPT dari WP/WR dan Pajak tsb tidak ada yang belum lunas.');
							App.errors.showAllMessages();
							if (errmsg.length > 0) {
								$.pnotify({
									title: 'Perhatian',
									text: errmsg.join('</br>'),
									type: 'warning'
								});
								return false;
							}
						}
					}
				}
			});
		}
	}); //end check
	
	App.pilih_pajakmanual = function(){
    if (!App.canSave() || App.isEdit()) { return; }
    var option = {multi:0, mode:'pendataan_restoran_npwpd'};
	
	var $list = $('#grid'), errmsg = [];
		
		// hapus dulu isi grid
		var rowIds = $list.jqGrid('getDataIDs');
		for(var i=0,len=rowIds.length;i<len;i++){
			var currRow = rowIds[i];
			$list.jqGrid('addRowData', currRow);
		}
		//$list.trigger('reloadGrid');
		
    Dialog.pilihPAJAKMANUAL(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
	  
	  $list.jqGrid('addRowData', 'idsts', [{'idsts': rs.id_rekening, 'noakun':rs.noakun, 'nama':rs.nama, 'nominal':'0', 'sisa':'0'}]);
			
    });
  } //end app pilih_pajakmanual
  
   App.pilih_bendahara = function(){
    if (!App.canSave() || App.isEdit()) { return; }
    var option = {multi:0, mode:'jurnal_tbp'};
    Dialog.pilihBENDAHARA(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.id_bendahara(rs.id_bendahara);
      App.akun_bendahara(rs.akun_bendahara);
      App.nama_bendahara(rs.nama_bendahara);
    });
  }
  
   App.pilih_jurnal = function(){
    if (!App.canSave() || App.isEdit()) { return; }
    var option = {multi:0, mode:'jurnal_tbp'};
    Dialog.pilihJURNAL(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.id_jurnal(rs.id_jurnal);
      App.jurnal_akrual(rs.kode);
    });
  }
  
  App.pilih_akunkas = function(){
    if (!App.canSave() || App.isEdit()) { return; }
    var option = {multi:0, mode:'kasdaerah'};
    Dialog.pilihKASDAERAH(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.id_kasdaerah(rs.id_kasdaerah);
      App.akun_kasdaerah(rs.akun_kasdaerah);
      App.nama_kasdaerah(rs.nama_kasdaerah);
    });
  }
  

  	
App.formValidation = function(){
    var errmsg = [];  
    if (!App.isValid()){
      errmsg.push('Ada kolom yang belum diisi dengan benar. Silakan diperbaiki.');
      App.errors.showAllMessages();
    }

    if (errmsg.length > 0) {
      $.pnotify({
        title: 'Perhatian',
        text: errmsg.join('</br>'),
        type: 'warning'
      });
      return false;
    }
    return true;
  }
  
  App.save = function(createNew){
    if (!App.formValidation()){ return }

    var $frm = $('#frm'),
        data = JSON.parse(ko.toJSON(App));

    $.ajax({
      url: $frm.attr('action'),
      type: 'post',
      dataType: 'json',
      data: data,
      success: function(res, xhr){
        if (res.isSuccess){
          if (res.id) App.id(res.id);
        }

        $.pnotify({
          title: res.isSuccess ? 'Sukses' : 'Gagal',
          text: res.message,
          type: res.isSuccess ? 'info' : 'error'
        });

        if (createNew) location.href = root+modul+App.form();
      }
    });
  }

  ko.applyBindings(App);

</script>