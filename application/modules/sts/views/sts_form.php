<fieldset>
  <legend id="bc" data-bind="text: 'Entry STS'"></legend>
</fieldset>

<form id="frm" method="post" action="<?php echo base_url(); ?>sks/proses">
  <div class="controls-row">
    <div class="control-group pull-left" data-bind="validationElement: kd_skpd" >
		<label class="control-label" for="kd_skpd">Kode SKPD</label>
		<input type="text" class="span2" id="kd_skpd" data-bind="value: kd_skpd, executeOnEnter: kd_skpd" required />
    </div>
	<div class="control-group pull-right" data-bind="validationElement: kd_skpd" >
		<label class="control-label" for="nama_skpd">Nama SKPD</label>
        <select type="text" class="span10" id="nama_skpd" data-bind="value: nama_skpd, executeOnEnter: nama_skpd" required >
			<option>Pilih Daftar</option>
		</select>
	</div>
  </div>

  <div class="controls-row" >
    <div class="control-group pull-left" data-bind="validationElement: no_sts">
      <label class="control-label" for="no_sts">Nomor STS</label>
      <input type="text" class="span2" id="no_sts" data-bind="value: no_sts" required />
	  
    </div>
    <div class="control-group pull-left" style="margin-top:20px" data-bind="validationElement: otm" >
	<input type="checkbox" id="otm" class="form-control" /> OTM
	</div>
    <div class="control-group pull-left" style="margin-left:20px" data-bind="validationElement: tgl_sts" >
      <label class="control-label" for="tgl_sts">Tanggal STS</label>
      <input type="text" class="datepicker span2" id="tgl_sts" data-bind="value: tgl_sts" required />
    </div>
	<div class="control-group pull-left" style="margin-left:20px" data-bind="validationElement: jurnal_akrual" >
      <label class="control-label" for="jurnal_akrual">Jurnal Akrual</label>
      <select type="text" class="span2" id="jurnal_akrual" data-bind="value: jurnal_akrual" required >
		<option>Pilih Daftar</option>
	  </select>
    </div>
  </div>
  
  
  <div class="controls-row" >
  <div class="control-group pull-left" data-bind="validationElement: no_akun_bendahara">
	  <div class="controls-row" >
		<div class="control-group pull-left" data-bind="validationElement: no_akun_bendahara">
		  <label class="control-label" for="no_akun_bendahara">Akun Bendahara Penerima</label>
		  <select type="text" class="span2" id="no_akun_bendahara" data-bind="value: no_akun_bendahara" required >
			<option>Pilih Daftar</option>
		  </select>
		</div>
		<div class="control-group pull-left" style="margin-left:20px" data-bind="validationElement: nama_bendahara_penerima" >
		  <label class="control-label" for="nama_bendahara_penerima">Nama Bendahara Penerima</label>
		  <select type="text" class="span2" id="nama_bendahara_penerima" data-bind="value: nama_bendahara_penerima" required >
			<option>Pilih Daftar</option>
		  </select>
		</div>
	  </div>
	  
	  <div class="controls-row" >
		<div class="control-group pull-left" data-bind="validationElement: akun_kas_daerah">
		  <label class="control-label" for="akun_kas_daerah">Akun Kas Daerah</label>
		  <select type="text" class="span2" id="akun_kas_daerah" data-bind="value: akun_kas_daerah" required >
			<option>Pilih Daftar</option>
		  </select>
		</div>
		<div class="control-group pull-left" style="margin-left:20px" data-bind="validationElement: nama_akun_kas_daerah" >
		  <label class="control-label" for="nama_akun_kas_daerah">Nama Akun Kas Daerah</label>
		  <select type="text" class="span2" id="nama_akun_kas_daerah" data-bind="value: nama_akun_kas_daerah" required >
			<option>Pilih Daftar</option>
		  </select>
		</div>
	  </div>
  </div>
  <div class="control-group pull-left" style="margin-left:20px" data-bind="validationElement: keterangan" >
		<label class="control-label" for="keterangan">Keterangan</label>
		  <textarea style="height:80px;" type="text" class="span7" id="keterangan" data-bind="value: keterangan" required >
		  </textarea>
  </div>
  </div>
	Rincian Setoran <input type="checkbox" id="rincian_setoran" class="form-control" /> Berdasar TBP
  <table id="grid"></table>
  <div id="pager"></div>

  <div class="controls-row pull-left">
  <div class="btn-group dropup">
    <button type="button" class="btn btn-primary" data-bind="enable: canSave, click: function(data, event){save(false, data, event) }" />Tambah</button>
  </div>
  <div class="btn-group dropup">
    <button type="button" class="btn btn-primary" data-bind="enable: canSave, click: function(data, event){save(false, data, event) }" />Ubah</button>
  </div>
  <div class="btn-group dropup">
    <button type="button" class="btn btn-primary" data-bind="enable: canSave, click: function(data, event){save(false, data, event) }" />Hapus</button>
  </div>
</div>
  
  <div class="controls-row pull-right">
		Total Setor : <input type="text" id="total_setor" class="form-control" />
  </div>
  
  
</form>
<br />
<br />
<br />
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
    colNames:['', 'Kode Akun', 'Nama Akun', 'Nominal Setoran', 'Sisa Belum Setor'],
    colModel:[
        {name:'idakun', hidden:true},
        {name:'noakun', width:150},
        {name:'nama', width:200},
        {name:'nominal', width:150},
        {name:'sisa', width:100, formatter:'currency', align:'right'},
    ],
    pager:'#pager',
    rowNum:-1,
    scroll:true,
    rownumbers:true,
    viewrecords:true,
		multiselect:true,
    gridview:true,
    shrinkToFit:false,
    recordtext:'{2} baris',
    width:675,
    height:150,
    onSelectRow: select_row,
    onSelectAll: select_row,
  });

  $("#grid").jqGrid('bindKeys', {
  });

  $("#grid").jqGrid('navGrid', '#pager', {
    add:false,
    edit:false,
    del:false,
    search:false,
    refresh:false,
  },{},{},{},{});
  
  function select_row(id)
  {
    var idnya = $("#grid").jqGrid('getGridParam', 'selarrrow');
    App.idspt(idnya);
  }

  ko.validation.init({
    insertMessages: false,
    decorateElement: true,
    errorElementClass: 'error',
  });

  var ModelPendataan = function (){
    var self = this;
    self.modul = 'Penetapan';
    self.akses_level = ko.observable(<?php echo isset($akses) ? $akses : 0 ?>);
    self.id = ko.observable();
    self.tgl = ko.observable('<?php echo date('d/m/Y') ?>')
      .extend({
        required: {params: true, message: 'Tanggal penetapan tidak boleh kosong'}
      });
    self.batas = ko.observable('<?php echo date('d/m/Y', strtotime(' + 30 days')) ?>')
      .extend({
        required: {params: true, message: 'Tanggal batas bayar tidak boleh kosong'}
      });
    self.idrek = ko.observable(0);
    self.kd_rek = ko.observable()
      .extend({
        required: {params: true, message: 'Pajak/Retribusi tidak boleh kosong'}
      });
    self.pajak = ko.observable();
    self.idspt = ko.observableArray();

    self.mode = ko.computed(function(){
      return self.id() > 0 ? 'edit' : 'new';
    });

    self.title = ko.computed(function(){
      return (self.mode() === 'edit' ? 'Edit ' : 'Entri ') + self.modul;
    });

   self.isEdit = ko.computed(function(){
      return self.mode() === 'edit';
    });

    self.canPrint = ko.computed(function(){
      return self.akses_level() >= 2 && self.mode() === 'edit';
    });

    self.canSave = ko.computed(function(){
      return self.akses_level() >= 3;
    });

    self.errors = ko.validation.group(self);

  }

  var App = new ModelPendataan();

  App.prev = function(){
    show_prev(modul, App.id());
  }

  App.next = function(){
    show_next(modul, App.id());
  }

  App.print = function(){
    preview({"tipe":"form", "id": App.id()});
  }

  App.back = function(){
    location.href = root+modul;
  }

  App.formValidation = function(){
    var grid = $('#grid'), errmsg = [];
    
    if (grid.jqGrid('getGridParam', 'selarrrow').length === 0) {
      errmsg.push('Belum ada SPT yang dipilih');
    }

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

  App.save = function(){
    if (!App.formValidation()){ return }

    var $frm = $('#frm'),
        data = JSON.parse(ko.toJSON(App));

    $.ajax({
      url: $frm.attr('action'),
      type: 'post',
      dataType: 'json',
      data: data,
      success: function(res, xhr){
        $.pnotify({
          title: res.isSuccess ? 'Sukses' : 'Gagal',
          text: res.message,
          type: res.isSuccess ? 'info' : 'error'
        });
        
        setTimeout(function() { window.location = root+modul}, 2000);
      }
    });
  }

  App.pilih_rekening = function(){
    if (!App.canSave() || App.isEdit()) { return; }
    var option = {multi:0, mode:'penetapan_oa'};
    var $list = $('#grid');
    
    // hapus dulu isi grid
    var rowIds = $list.jqGrid('getDataIDs');
    for(var i=0,len=rowIds.length;i<len;i++){
      var currRow = rowIds[i];
      $list.jqGrid('delRowData', currRow);
    }
    $list.trigger('reloadGrid');

    Dialog.pilihRekening(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.idrek(rs.idrek);
      App.kd_rek(rs.kdrek);
      App.pajak(rs.nmrek);
      
      $.ajax({
        url: root+modul+'/getnpwpd',
        type: 'post',
        dataType: 'json',
        data: {idrek: rs.idrek},
        success: function(response){
          var result = response.rows;
          if (result !== undefined) {
            // add grid dengan data npwpd sesuai pajak/retribusi
            for (i = 0; i < result.length; i++){
              $list.jqGrid('addRowData', 'idakun', [{'idakun':result[i]['id_akun'], 'noakun':result[i]['noakun'], 'nama':result[i]['nama'], 'nominal':result[i]['nominal'], 'sisa':result[i]['sisa']}]);
            }
          }
        }
      });
    });
  }

  ko.applyBindings(App);
</script>