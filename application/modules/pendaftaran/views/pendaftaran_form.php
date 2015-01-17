<fieldset>
  <legend id="bc" data-bind="text: title"></legend>
</fieldset>

<form id="frm" method="post" action="<?php echo base_url(); ?>pendaftaran/proses">
  <fieldset>
    <legend>Data Registrasi</legend>
    <div class="controls-row">
      <div class="control-group pull-left" >
        <label class="control-label" for="jenis">Tipe</label>
        <select id="jenis" class="span2" data-bind="options: opsiJenis, optionsValue:'kode', optionsText:'uraian', value: jenis" /></select>
      </div>
      <div class="control-group pull-left" style="margin-left:20px" >
        <label class="control-label" for="gol">Golongan</label>
        <select id="gol" class="span3" data-bind="options: opsiGolongan, optionsValue:'kode', optionsText:'uraian', value: gol" /></select>
      </div>
      <div class="control-group pull-left" style="margin-left:20px" data-bind="validationElement: no" >
        <label class="control-label" for="no">Nomor</label>
        <input type="text" id="no" class="span3" data-bind="value: no" required readonly="true" />
      </div>
      <div class="control-group pull-left" style="margin-left:20px" data-bind="validationElement: noreg" >
        <label class="control-label" for="noreg">No. Reg</label>
        <input type="text" id="noreg" class="span3" data-bind="value: noreg" required />
      </div>
    </div>

    <div class="control-group" >
      <div class="control-group pull-left" data-bind="validationElement: npwpd">
        <label class="control-label" for="npwpd">NPWPD</label>
        <input type="text" class="span3" id="npwpd" data-bind="value: npwpd" required readonly="true" />
      </div>
      <div class="control-group pull-left" style="margin-left:20px"  data-bind="validationElement: tgl" >
        <label class="control-label" for="tgl">Tanggal NPWPD</label>
        <input type="text" class="datepicker span2" id="tgl" data-bind="value: tgl" required />
      </div>
      <div class="control-group pull-left" style="margin-left:20px"  data-bind="validationElement: tglkirim" >
        <label class="control-label" for="tglkirim">Tanggal Kirim</label>
        <input type="text" class="datepicker span2" id="tglkirim" data-bind="value: tglkirim" />
      </div>
      <div class="control-group pull-left" style="margin-left:20px" data-bind="validationElement: tglkembali" >
        <label class="control-label" for="tglkembali">Tanggal Kembali</label>
        <input type="text" class="datepicker span2" id="tglkembali" data-bind="value: tglkembali" />
      </div>
    </div>
  </fieldset>

  
  <fieldset class="form-inline">
    <legend>Data WP/WR</legend>
    <div class="controls-row">
      <label class="control-label span2" for="nama">Nama</label>
      <div class="control-group pull-left" data-bind="validationElement: nama" >
        <input type="text" class="span10" id="nama" data-bind="value: nama" required />
      </div>
    </div>

    <div class="controls-row">
      <label class="control-label span2" for="alamat">Alamat</label>
      <div class="control-group pull-left" data-bind="validationElement: alamat" >
        <input type="text" class="span10" id="alamat" data-bind="value: alamat" required />
      </div>
    </div>

    <div class="controls-row" >
      <label class="control-label span2" for="kecamatan">Kecamatan / Kelurahan</label>
      <div class="controls">
        <div class="control-group pull-left"  data-bind="validationElement: kecamatan">
          <select id="kecamatan" class="span3" data-bind="options:opsiKecamatan, optionsValue:'kode', optionsText:'uraian', value:kecamatan" required /></select>
        </div>
        <div class="control-group pull-left span5"  data-bind="validationElement: kelurahan">
          <select id="kelurahan" class="span3" data-bind="options:opsiKelurahan, optionsValue:'kode', optionsText:'uraian', value:kelurahan" required /></select>
        </div>
      </div>
    </div>

    <div class="controls-row">
      <label class="control-label span2" for="telp">No. Telpon</label>
      <div class="control-group pull-left" data-bind="validationElement: telp" >
        <input type="text" class="span2" id="telp" data-bind="value: telp" />
      </div>
    </div>

    <div class="controls-row">
      <label class="control-label span2" for="usaha">Jenis Usaha</label>
      <div class="control-group pull-left" data-bind="validationElement: usaha" >
        <select class="span4" id="usaha" data-bind="options:opsiJenisUsaha, optionsValue:'kode', optionsText:'uraian', value: usaha" required ></select>
      </div>
    </div>
  </fieldset>

  <fieldset class="form-inline">
    <div class="controls-row">
      <legend>Data Pemilik
        <div class="pull-right">
          <input type="button" value="Salin" class="btn btn-primary" data-bind="click: copy" />
        </div>
      </legend>
    </div>
    
    <div class="controls-row">
      <label class="control-label span2" for="namap">Nama</label>
      <div class="control-group pull-left" data-bind="validationElement: namap" >
        <input type="text" class="span10" id="namap" data-bind="value: namap" />
      </div>
    </div>

    <div class="controls-row">
      <label class="control-label span2" for="alamatp">Alamat</label>
      <div class="control-group pull-left" data-bind="validationElement: alamatp" >
        <input type="text" class="span10" id="alamatp" data-bind="value: alamatp" />
      </div>
    </div>

    <div class="controls-row" >
      <label class="control-label span2" for="kecamatanp">Kecamatan / Kelurahan</label>
      <div class="controls">
        <div class="control-group pull-left" data-bind="validationElement: kecamatanp" >
          <select id="kecamatanp" class="span3" data-bind="options:opsiKecamatanp, optionsValue:'kode', optionsText:'uraian', value:kecamatanp" required /></select>
        </div>
        <div class="control-group pull-left span5"  data-bind="validationElement: kelurahanp">
          <select id="kelurahanp" class="span3" data-bind="options:opsiKelurahanp, optionsValue:'kode', optionsText:'uraian', value:kelurahanp" required /></select>
        </div>
      </div>
    </div>

    <div class="controls-row">
      <label class="control-label span2" for="telpp">No. Telpon</label>
      <div class="control-group pull-left" data-bind="validationElement: telpp" >
        <input type="text" class="span2" id="telpp" data-bind="value: telpp" />
      </div>
    </div>
  </fieldset>

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
</form>

<script>
function pad (str, max) {
  str = str.toString();
  return str.length < max ? pad("0" + str, max) : str;
}
$(document).ready(function() {
  $.datepicker.setDefaults($.datepicker.regional['id']);
  $('.datepicker#tgl').datepicker({
    onSelect:function(selectedDate) {
      App.tgl(selectedDate);
      $('.datepicker#tglkirim').datepicker("option", "minDate", selectedDate);
    }
  });
  
  $('.datepicker#tglkirim').datepicker({
    onSelect:function(selectedDate) {
      App.tglkirim(selectedDate);
      $('.datepicker#tglkembali').datepicker("option", "minDate", selectedDate);
    },
    minDate:$('.datepicker#tgl').val(),
  });
  
  $('.datepicker#tglkembali').datepicker({
    minDate:$('.datepicker#tglkirim').val(),
  });
  
});

  ko.validation.init({
    insertMessages: false,
    decorateElement: true,
    errorElementClass: 'error',
  });

  var Jenis = function(kode, uraian){
    this.kode = kode;
    this.uraian = uraian;
  }

  var Golongan = function(kode, uraian){
    this.kode = kode;
    this.uraian = uraian;
  }

  var JenisUsaha = function(kode, uraian){
    this.kode = kode;
    this.uraian = uraian;
  }

  var Kecamatan = function(kode, uraian){
    this.kode = kode;
    this.uraian = uraian;
  }
  
  var Kelurahan = function(kode, uraian){
    this.kode = kode;
    this.uraian = uraian;
  }

  var Kecamatanp = function(kode, uraian){
    this.kode = kode;
    this.uraian = uraian;
  }
  
  var Kelurahanp = function(kode, uraian){
    this.kode = kode;
    this.uraian = uraian;
  }

  var ModelPendaftaran = function (){
    var self = this;
    self.opsiJenis = ko.observableArray([
        new Jenis('P', 'Pajak'),
        new Jenis('R', 'Retribusi'),
    ]);
    self.opsiGolongan = ko.observableArray([
        new Golongan('1', 'Golongan 1 (Orang Pribadi)'),
        new Golongan('2', 'Golongan 2 (Badan)'),
    ]);
    self.opsiJenisUsaha = ko.observableArray([
      new JenisUsaha('', 'Pilih...'),
      <?php
      foreach($jenis_usaha as $key=>$val){
        echo "new JenisUsaha('".$val['ID_JENIS_USAHA']."','".$val['URAIAN']."'),";
      }
      ?>
    ]);

    self.modul = 'Pendaftaran';
    self.akses_level = ko.observable(<?php echo isset($akses) ? $akses : 0 ?>);
    self.id = ko.observable('<?php echo isset($data['ID_WAJIB_PAJAK']) ? $data['ID_WAJIB_PAJAK'] : 0 ?>');
    self.no = ko.observable('<?php echo isset($data['NOMOR']) ? $data['NOMOR'] : '' ?>')
      .extend({
        required: {params: true, message: 'Nomor tidak boleh kosong'},
        maxLength: {params: 10, message: 'Nomor tidak boleh melebihi 10 karakter'},
      });
    self.noreg = ko.observable('<?php echo isset($data['NOMOR_REG']) ? $data['NOMOR_REG'] : '' ?>')
      .extend({
        required: {params: true, message: 'Nomor Register tidak boleh kosong'},
        maxLength: {params: 50, message: 'Nomor Register tidak boleh melebihi 50 karakter'},
      });

    self.jenis = ko.observable('<?php echo isset($data['JENIS']) ? $data['JENIS'] : 0 ?>');
    self.gol = ko.observable('<?php echo isset($data['GOLONGAN']) ? $data['GOLONGAN'] : 0 ?>');
    self.usaha = ko.observable('<?php echo isset($data['ID_JENIS_USAHA']) ? $data['ID_JENIS_USAHA'] : 0 ?>')
      .extend({
        required: {params: true, message: 'Jenis Usaha tidak boleh kosong'},
      });
    self.nama = ko.observable('<?php echo isset($data['NAMA_WP']) ? $data['NAMA_WP'] : '' ?>')
      .extend({
        required: {params: true, message: 'Nama WP/WR tidak boleh kosong'},
        maxLength: {params: 50, message: 'Nama WP/WR tidak boleh melebihi 50 karakter'},
      });
    self.alamat = ko.observable('<?php echo isset($data['ALAMAT_WP']) ? $data['ALAMAT_WP'] : '' ?>')
      .extend({
        required: {params: true, message: 'Alamat WP/WR tidak boleh kosong'},
        maxLength: {params: 50, message: 'Alamat WP/WR tidak boleh melebihi 50 karakter'},
      });
    self.telp = ko.observable('<?php echo isset($data['NO_TELP']) ? $data['NO_TELP'] : '' ?>')
      .extend({
        required: {params: true, message: 'No Telepon WP/WR tidak boleh kosong'},
        maxLength: {params: 20, message: 'No Telepon WP/WR tidak boleh melebihi 20 karakter'},
      });

    self.opsiKecamatan = ko.observableArray([
      new Kecamatan('','Pilih...'),
      <?php
      foreach($kecamatan as $key=>$val){
        echo "new Kecamatan('".$val['KODE_KECAMATAN']."','".$val['NAMA_KECAMATAN']."'),";
      }
      ?>
    ]);
    self.kecamatan = ko.observable('<?php echo isset($data['KODE_KECAMATAN']) ? $data['KODE_KECAMATAN'] : 0 ?>')
      .extend({
        required: {params: true, message: 'Kecamatan WP/WR tidak boleh kosong'},
      });
    
    self.opsiKelurahan = ko.observableArray([ new Kelurahan('','Pilih...') ]);
    self.daftarKelurahan = ko.computed(function() {
      if (self.kecamatan() == '' || self.kecamatan() == undefined)
      {
        return self.opsiKelurahan.removeAll();
      }
      else
      {
	    
        $.getJSON(root+modul+'/get_kelurahan/'+self.kecamatan(), function(data){
          if (data != null) {
            self.opsiKelurahan.removeAll();
            self.opsiKelurahan.push(new Kelurahan('','Pilih...'));
            $.each(data, function(i,val) {
              return self.opsiKelurahan.push(new Kelurahan(i,val));
            });
          }
        });
      }
    }); 
    self.kelurahan = ko.observable()
      .extend({
        required: {params: true, message: 'Kelurahan WP/WR tidak boleh kosong'},
      });
   
    self.namap = ko.observable('<?php echo isset($data['NAMA_PEMILIK']) ? $data['NAMA_PEMILIK'] : '' ?>')
      .extend({
        required: {params: true, message: 'Nama Pemilik tidak boleh kosong'},
        maxLength: {params: 50, message: 'Nama Pemilik tidak boleh melebihi 50 karakter'},
      });
    self.alamatp = ko.observable('<?php echo isset($data['ALAMAT_PEMILIK']) ? $data['ALAMAT_PEMILIK'] : '' ?>')
      .extend({
        required: {params: true, message: 'Alamat Pemilik tidak boleh kosong'},
        maxLength: {params: 50, message: 'Alamat Pemilik tidak boleh melebihi 50 karakter'},
      });
    self.telpp = ko.observable('<?php echo isset($data['NO_TELP_PEMILIK']) ? $data['NO_TELP_PEMILIK'] : '' ?>')
      .extend({
        required: {params: true, message: 'No Telepon Pemilik tidak boleh kosong'},
        maxLength: {params: 20, message: 'No Telepon Pemilik tidak boleh melebihi 20 karakter'},
      });
    self.opsiKecamatanp = ko.observableArray([
      new Kecamatanp('','Pilih...'),
      <?php
      foreach($kecamatan as $key=>$val){
        echo "new Kecamatanp('".$val['KODE_KECAMATAN']."','".$val['NAMA_KECAMATAN']."'),";
      }
      ?>
    ]);
    self.kecamatanp = ko.observable('<?php echo isset($data['KODE_KECAMATAN_PEMILIK']) ? $data['KODE_KECAMATAN_PEMILIK'] : 0 ?>')
      .extend({
        required: {params: true, message: 'Kecamatan Pemilik tidak boleh kosong'},
      });

    self.kelurahanp = ko.observable()
      .extend({
        required: {params: true, message: 'Kelurahan Pemilik tidak boleh kosong'},
      });
    self.opsiKelurahanp = ko.observableArray([]);
    self.daftarKelurahanp = ko.computed(function() {
      if (self.kecamatanp() == '' || self.kecamatanp() == undefined)
      {
        return self.opsiKelurahanp.removeAll();
      }
      else
      {
        $.getJSON(root+modul+'/get_kelurahan/'+self.kecamatanp(), function(data){
          if (data != null) {
            self.opsiKelurahanp.removeAll();
            self.opsiKelurahanp.push(new Kelurahanp('','Pilih...'));
            $.each(data, function(i,val) {
              return self.opsiKelurahanp.push(new Kelurahanp(i,val));
            });
          }
        });
      }
    });
 
    self.telpp = ko.observable('<?php echo isset($data['NO_TELP_PEMILIK']) ? $data['NO_TELP_PEMILIK'] : '' ?>');

    self.tgl = ko.observable('<?php echo isset($data['TANGGAL_NPWPD']) ? format_date($data['TANGGAL_NPWPD']) : date('d/m/Y') ?>')
      .extend({
        required: {params: true, message: 'Tanggal NPWPD tidak boleh kosong'}
      });
    self.tglkirim = ko.observable('<?php echo isset($data['TANGGAL_DIKIRIM']) ? format_date($data['TANGGAL_DIKIRIM']) : '' ?>')
      .extend({
        required: {params: true, message: 'Tanggal Kirim tidak boleh kosong'}
      });
    self.tglkembali = ko.observable('<?php echo isset($data['TANGGAL_KEMBALI']) ? format_date($data['TANGGAL_KEMBALI']) : '' ?>')
      .extend({
        required: {params: true, message: 'Tanggal Kembali tidak boleh kosong'}
      });

    $.getJSON(root+modul+'/get_no', function(data){
      if(self.isEdit() === false)
        return self.no(data);
      else
        return self.no();
    });
	
    self.npwpd = ko.computed(function(){
     // return self.jenis() + '.' + self.gol() + '.' + self.no() + '.' + self.kecamatan() + '.' + self.kelurahan();
	 //by nana
	  return self.jenis() + '.' + self.gol() + '.' +  self.kecamatan() + '.' + self.kelurahan() + '.' + self.no() + '.' + pad(self.usaha(),3);
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

    self.canPrint = ko.computed(function(){
      return self.akses_level() >= 2 && self.mode() === 'edit';
    });

    self.canSave = ko.computed(function(){
      return self.akses_level() >= 3;
    });

    self.errors = ko.validation.group(self);
        
  }

  var App = new ModelPendaftaran();
    
  App.copy = function(){
    App.namap(App.nama());
    App.alamatp(App.alamat());
    App.kecamatanp(App.kecamatan());
    setTimeout(function(){
      App.kelurahanp(App.kelurahan());
    }, 1000);
    App.telpp(App.telp());
  }
  
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
  
  ko.applyBindings(App);
  
  setTimeout(function(){
    App.kelurahan('<?php echo isset($data['KODE_KELURAHAN']) ? $data['KODE_KELURAHAN'] : 0 ?>');
    App.kelurahanp('<?php echo isset($data['KODE_KELURAHAN_PEMILIK']) ? $data['KODE_KELURAHAN_PEMILIK'] : 0 ?>');
  },2000);
  
  
</script>
