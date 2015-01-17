<fieldset>
  <legend id="bc" data-bind="text: title"></legend>
</fieldset>

<form id="frm" method="post" action="<?php echo base_url(); ?>bayar_lain/proses">
  <div class="controls-row">
    <div class="control-group"  data-bind="validationElement: tgl" >
      <label class="control-label" for="tgl">Tanggal Pembayaran</label>
      <input type="text" class="span2 datepicker" id="tgl" data-bind="value: tgl" required />
    </div>
  </div>

  <div class="controls-row">
    <div class="control-group input-append" data-bind="validationElement: nm_skpd" >
      <label class="control-label" for="nm_skpd">SKPD</label>
      <input type="text" id="nm_skpd" readonly="1" style="margin-bottom:10px" class="span10" data-bind="value: nm_skpd" required />
      <span class="add-on" data-bind="visible: !isEdit() && canSave(), click: pilih_skpd" ><i class="icon-folder-open"></i></span>
    </div>
  </div>

  <div class="controls-row" >
    <div class="control-group pull-left" data-bind="validationElement: kd_rek" >
      <label class="control-label" for="idrek">Rekening</label>
      <input type="text" class="span2" id="kd_rek" readonly="1" data-bind="value: kd_rek, executeOnEnter: pilih_rekening" required />
      <div class="controls span8 input-append">
        <input type="text" class="span8" id="nm_rek" readonly="1" data-bind="value: nm_rek, executeOnEnter: pilih_rekening" required />
        <span class="add-on" data-bind="visible: !isEdit() && canSave(), click: pilih_rekening" ><i class="icon-folder-open"></i></span>
      </div>
    </div>
  </div>

  <div class="controls-row">
    <div class="control-group" data-bind="validationElement: nama" >
      <label class="control-label" for="nama">Nama Penyetor</label>
      <input type="text" class="span10" id="nama" data-bind="value: nama" required />
    </div>
  </div>

  <div class="controls-row">
    <div class="control-group" data-bind="validationElement: alamat" >
      <label class="control-label" for="alamat">Alamat</label>
      <input type="text" class="span10" id="alamat" data-bind="value: alamat" required />
    </div>
  </div>
  
  <div class="controls-row">
    <div class="control-group" data-bind="validationElement: jml_bayar" >
      <label class="control-label" for="jml_bayar">Jumlah Pembayaran</label>
      <input type="text" class="span2 currency" id="jml_bayar" data-bind="numeralvalue: jml_bayar" required />
    </div>
  </div>

  <div class="controls-row">
    <div class="control-group" data-bind="validationElement: keterangan" >
      <label class="control-label" for="keterangan">Keterangan</label>
      <input type="text" class="span10" id="keterangan" data-bind="value: keterangan" required />
    </div>
  </div>
  
</form>

<div class="controls-row pull-right">
  <input type="button" value="Sebelumnya" class="btn btn-primary" data-bind="click: prev" />
  <input type="button" value="Berikutnya" class="btn btn-primary" data-bind="click: next" />
  <div class="btn-group dropup">
    <button type="button" class="btn btn-primary" data-bind="enable: canSave, click: function(data, event){save(false, data, event) }" />Simpan</button>
    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" data-bind="enable: canSave">
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
      <li><a href="#" data-bind="enable: canSave, click: function(data, event){save(true, data, event) }" >Simpan & Buat Baru</a></li>
    </ul>
  </div>
  <input type="button" id="back" value="Kembali" class="btn btn-primary" data-bind="click: back" />
</div>

<script>
$(document).ready(function() {
  $.datepicker.setDefaults($.datepicker.regional['id']);
  $('.datepicker').datepicker();
});

  ko.validation.init({
    insertMessages: false,
    decorateElement: true,
    errorElementClass: 'error',
  });

  var ModelBayarLain = function (){
    var self = this;
    self.modul = 'bayar_lain';
    self.akses_level = ko.observable(<?php echo isset($akses) ? $akses : 0 ?>);
    self.id = ko.observable('<?php echo isset($data['ID_PEMBAYARAN_LAIN']) ? $data['ID_PEMBAYARAN_LAIN'] : 0; ?>');
    self.id_skpd = ko.observable('<?php echo isset($data['ID_SKPD']) ? $data['ID_SKPD'] : 0; ?>');
    self.nm_skpd = ko.observable('<?php echo isset($data['NAMA_SKPD']) ? $data['NAMA_SKPD'] : ''; ?>')
      .extend({
        required: {params: true, message: 'SKPD tidak boleh kosong'},
      });
    self.nama = ko.observable('<?php echo isset($data['NAMA_PENYETOR']) ? $data['NAMA_PENYETOR'] : ''; ?>')
      .extend({
        required: {params: true, message: 'Nama Penyetor tidak boleh kosong'},
      });
    self.alamat = ko.observable('<?php echo isset($data['ALAMAT']) ? $data['ALAMAT'] : ''; ?>')
      .extend({
        required: {params: true, message: 'Alamat tidak boleh kosong'},
      });
    self.idrek = ko.observable('<?php echo isset($data['ID_REKENING']) ? $data['ID_REKENING'] : ''; ?>');
    self.kd_rek = ko.observable('<?php echo isset($data['KODE_REKENING']) ? $data['KODE_REKENING'] : ''; ?>')
      .extend({
        required: {params: true, message: 'Kode Rekening tidak boleh kosong'},
      });
    self.nm_rek = ko.observable('<?php echo isset($data['NAMA_REKENING']) ? $data['NAMA_REKENING'] : ''; ?>')
      .extend({
        required: {params: true, message: 'Nama Rekening tidak boleh kosong'},
      });
    self.tgl = ko.observable('<?php echo isset($data['TANGGAL']) ? format_date($data['TANGGAL']):date('d/m/Y') ?>')
      .extend({
        required: {params: true, message: 'Tanggal Pembayaran tidak boleh kosong'},
      });
    self.jml_bayar = ko.observable(<?php echo isset($data['JUMLAH_BAYAR']) ? $data['JUMLAH_BAYAR']: 0; ?>)
      .extend({
        required: {params: true, message: 'Jumlah Pembayaran tidak boleh kosong'},
      });
    self.keterangan = ko.observable('<?php echo isset($data['KETERANGAN']) ? $data['KETERANGAN']: ''; ?>')
      .extend({
        required: {params: true, message: 'Keterangan tidak boleh kosong'},
      });

    self.mode = ko.computed(function(){
      return self.id() > 0 ? 'edit' : 'new';
    });

    self.title = '<?php echo isset($header) ? $header : '' ?>';

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

  var App = new ModelBayarLain();

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

        if (createNew) location.href = root+modul+'/form/';
      }
    });
  }
  
  App.pilih_skpd = function(){
    if (!App.canSave() || App.isEdit()) { return; }
    var option = {multi:0, mode:'bayar_lain'};
    Dialog.pilihSKPD(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.id_skpd(rs.id);
//      App.kd_skpd(rs.kode);
      App.nm_skpd(rs.nama);
    });
  }

  App.pilih_rekening = function(){
    if (!App.canSave() || App.isEdit()) { return; }
    var option = {multi:0, mode:'bayar_lain'};
    Dialog.pilihRekening(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.idrek(rs.idrek);
      App.kd_rek(rs.kdrek);
      App.nm_rek(rs.nmrek);
    });
  }

  ko.applyBindings(App);
</script>