<fieldset>
  <legend id="bc" data-bind="text: title"></legend>
</fieldset>

<form id="frm" method="post" action="<?php echo base_url(); ?>pajak_reklame/<?php echo $link_proses;?>">
  <fieldset>
    <div class="controls-row">
      <div class="control-group pull-left" data-bind="validationElement: kd_rek" >
        <label class="control-label" for="idrek">Jenis Pajak/Retribusi</label>
        <input type="text" class="span2" id="kd_rek" readonly="1" data-bind="value: kd_rek, executeOnEnter: pilih_rekening" required />
        <div class="controls span8 input-append">
          <input type="text" class="span8" id="nm_rek" readonly="1" data-bind="value: nm_rek, executeOnEnter: pilih_rekening" required />
          <span class="add-on" data-bind="visible: !isEdit() && canSave(),  click: pilih_rekening" ><i class="icon-folder-open"></i></span>
        </div>
      </div>
    </div>
    <div class="control-group" >
      <div class="control-group pull-left" data-bind="validationElement: npwpd" >
        <label class="control-label" for="npwpd">NPWPD</label>
        <div class="controls input-append">
          <input type="text" class="span8" id="npwpd" readonly="1" data-bind="value: npwpd, executeOnEnter: pilih_npwpd" required />
          <span class="add-on" data-bind="visible: !isEdit() && canSave(),  click: pilih_npwpd" ><i class="icon-folder-open"></i></span>
        </div>
      </div>
      <div class="control-group pull-left" style="margin-left:20px" data-bind="validationElement: tgl" >
        <label class="control-label" for="tgl">Tanggal</label>
        <input type="text" class="datepicker span2" id="tgl" data-bind="value: tgl" required />
      </div>
    </div>
  </fieldset>

  <fieldset>
    <legend>Wajib Pajak/Retribusi</legend>
    <div class="controls-row">
      <div class="control-group" data-bind="validationElement: nama" >
        <label class="control-label" for="nama">Nama</label>
        <input type="text" class="span10" id="nama" readonly="1" data-bind="value: nama" required />
      </div>
    </div>

    <div class="controls-row">
      <div class="control-group" data-bind="validationElement: alamat" >
        <label class="control-label" for="alamat">Alamat</label>
        <input type="text" class="span10" id="alamat" readonly="1" data-bind="value: alamat" required />
      </div>
    </div>

    <div class="controls-row" >
      <div class="control-group pull-left" data-bind="validationElement: kecamatan" >
        <label class="control-label" for="kecamatan">Kecamatan</label>
        <input type="text" class="span5" id="kecamatan" readonly="1" data-bind="value: kecamatan" required />
      </div>
      <div class="control-group pull-left" style="margin-left:20px" data-bind="validationElement: kelurahan" >
        <label class="control-label" for="kelurahan">Kelurahan</label>
        <input type="text" class="span5" id="kelurahan" readonly="1" data-bind="value: kelurahan" required />
      </div>
    </div>
  </fieldset>

  <fieldset>
    <legend>Perhitungan Pajak/Retribusi</legend>
    <div class="controls-row">
      <div class="control-group pull-left" data-bind="validationElement: nospt" >
        <label class="control-label" for="nospt">Nomor SPT</label>
        <input type="text" class="span3" id="nospt" data-bind="value: nospt" required readonly="true"/>
      </div>
      <div class="control-group pull-left" style="margin-left:20px;display:none;" data-bind="validationElement: status" >
        <label class="control-label" for="status">Status SPT</label>
        <select id="status" class="span3" data-bind="options: opsiStatus, optionsValue:'kode', optionsText:'uraian', value: status" /></select>
      </div>
    </div>

    <div class="controls-row">
      <div class="control-group pull-left" data-bind="validationElement: awal" >
        <label class="control-label" for="awal">Periode Awal</label>
        <input type="text" class="span2 datepicker" id="awal" data-bind="value: awal" required />
      </div>
      <div class="control-group pull-left" style="margin-left:20px" data-bind="validationElement: akhir" >
        <label class="control-label" for="akhir">Periode Akhir</label>
        <input type="text" class="span2 datepicker" id="akhir" data-bind="value: akhir" required />
      </div>
    </div>

    <div class="controls-row">
      <div class="control-group" data-bind="validationElement: uraian" >
        <label class="control-label" for="uraian">Judul</label>
        <input type="text" class="span10" id="uraian" data-bind="value: uraian" required />
      </div>
    </div>
    <div class="controls-row">
      <div class="control-group" data-bind="validationElement: lokasi" >
        <label class="control-label" for="lokasi">Lokasi</label>
        <input type="text" class="span10" id="lokasi" data-bind="value: lokasi" required />
      </div>
    </div>
    
    <div class="controls-row" >
      <div class="control-group pull-left" data-bind="validationElement: jml_reklame" >
        <label class="control-label" for="jml_reklame">Jumlah Reklame</label>
        <input type="text" class="span3" id="jml_reklame" data-bind="value: jml_reklame" required />
      </div>
      <div class="control-group pull-left" style="margin-left:20px" data-bind="validationElement: sisi" >
        <label class="control-label" for="sisi">Jumlah Sisi</label>
        <input type="text" class="span3" id="sisi" data-bind="value: sisi" required />
      </div>
      <div class="control-group pull-left" style="margin-left:20px" data-bind="validationElement: hari" >
        <label class="control-label" for="hari">Jumlah Hari</label>
        <input type="text" class="span3" id="hari" data-bind="value: hari" required />
      </div>
    </div>

    <div class="controls-row" >
      <div class="control-group pull-left" data-bind="validationElement: panjang" >
        <label class="control-label" for="panjang">Panjang (m)</label>
        <input type="text" class="span3" id="panjang" data-bind="value: panjang" required />
      </div>
      <div class="control-group pull-left" style="margin-left:20px" data-bind="validationElement: lebar" >
        <label class="control-label" for="lebar">Lebar (m)</label>
        <input type="text" class="span3" id="lebar" data-bind="value: lebar" required />
      </div>
      <div class="control-group pull-left" style="margin-left:20px" >
        <label class="control-label" for="luas">Luas (m2)</label>
        <input type="text" class="span3" id="luas" readonly="1" data-bind="value: luas"  />
      </div>
    </div>

    <div class="controls-row" >
      <div class="control-group pull-left" data-bind="validationElement: diskon" >
        <label class="control-label" for="diskon">Diskon (%)</label>
        <input type="text" class="span3 currency" id="diskon" data-bind="numeralvalue: diskon" required />
      </div>
      <div class="control-group pull-left" style="margin-left:20px" >
        <label class="control-label" for="tarif1">Tarif Pajak (Rp)</label>
        <input type="text" class="span3 currency" id="tarif1" readonly="1" data-bind="numeralvalue: tarif1"  />
      </div>
      <div class="control-group pull-left" style="margin-left:20px" >
        <label class="control-label" for="tarif2">Tarif Persen (%)</label>
        <input type="text" class="span3 currency" id="tarif2" readonly="1" data-bind="numeralvalue: tarif2"  />
      </div>
    </div>

	<div class="controls-row">
		<div class="control-group pull-left" data-bind="validationElement: nilai_strategis" >
			<label class="control-label" for="nilai_strategis">Nilai Strategis</label>
			<input type="text" class="span3 currency" id="nilai_strategis" data-bind="numeralvalue: nilai_strategis" required />
		</div>
		<div class="control-group pull-left" style="margin-left:20px" >
			<label class="control-label" for="jml">Jumlah Pajak</label>
			<input type="text" class="span3 currency" id="jml" readonly="1" data-bind="numeralvalue: jml" />
		</div>
	</div>
  </fieldset>
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
  $('.currency')
    .blur(function(){ $(this).formatCurrency(fmtCurrency); })
    .focus(function(){ $(this).toNumber(fmtCurrency); });
    
	$.datepicker.setDefaults($.datepicker.regional['id']);

	$('.datepicker#awal').datepicker({
		onSelect:function(selectedDate) {
			App.awal(selectedDate);
			$('.datepicker#akhir').datepicker("option", "minDate", selectedDate);
		}
	});
  
	$('.datepicker#akhir').datepicker({
		onSelect:function(selectedDate) {
			App.akhir(selectedDate);
			$('.datepicker#tgl').datepicker("option", "minDate", selectedDate);
		},
		minDate:$('.datepicker#awal').val(),
	});

	$('.datepicker#tgl').datepicker({
		onSelect:function(selectedDate) {
			App.tgl(selectedDate);
		},
		minDate:$('.datepicker#akhir').val(),
	});
  
});

  ko.validation.init({
    insertMessages: false,
    decorateElement: true,
    errorElementClass: 'error',
  });

  var Status = function(kode, uraian){
    this.kode = kode;
    this.uraian = uraian;
  }

  var ModelPendaftaran = function (){
    var self = this;
    self.opsiStatus = ko.observableArray([
        new Status('BARU', 'Baru'),
        new Status('KB', 'Kurang Bayar'),
        new Status('KBT', 'Kurang Bayar Tambahan'),
        new Status('LB', 'Lebih Bayar'),
        new Status('NIHIL', 'Nihil'),
    ]);
    self.modul = 'pendataan';
    self.akses_level = ko.observable(<?php echo isset($akses) ? $akses : 0 ?>);
    self.form = ko.observable('<?php echo isset($form) ? $form : '' ?>');
    self.id = ko.observable('<?php echo isset($data['ID_SPT']) ? $data['ID_SPT'] : 0 ?>');
    self.nospt = ko.observable('<?php echo isset($data['NOMOR_SPT']) ? $data['NOMOR_SPT'] : '' ?>')
      .extend({
        required: {params: true, message: 'Nomor SPT tidak boleh kosong'},
        maxLength: {params: 20, message: 'Nomor SPT tidak boleh melebihi 20 karakter'},
      });
    self.tgl = ko.observable('<?php echo isset($data['TANGGAL_SPT']) ? format_date($data['TANGGAL_SPT']) : '' ?>')
      .extend({
        required: {params: true, message: 'Tanggal SPT tidak boleh kosong'},
      });
    self.tipe = ko.observable('<?php echo isset($tipe) ? $tipe : '' ?>');
    self.idrek = ko.observable('<?php echo isset($data['ID_REKENING']) ? $data['ID_REKENING'] : 0 ?>')
    self.kd_rek = ko.observable('<?php echo isset($data['KODE_REKENING']) ? $data['KODE_REKENING'] : '' ?>')
      .extend({
        required: {params: true, message: 'Kode Rekening tidak boleh kosong'},
      });
    self.nm_rek = ko.observable('<?php echo isset($data['NAMA_REKENING']) ? $data['NAMA_REKENING'] : '' ?>')
      .extend({
        required: {params: true, message: 'Nama Rekening tidak boleh kosong'},
      });
    self.id_wp = ko.observable('<?php echo isset($data['ID_WAJIB_PAJAK']) ? $data['ID_WAJIB_PAJAK'] : 0 ?>');
    self.npwpd = ko.observable('<?php echo isset($data['NPWPD']) ? $data['NPWPD'] : '' ?>')
      .extend({
        required: {params: true, message: 'NPWPD tidak boleh kosong'},
      });
    self.nama = ko.observable('<?php echo isset($data['NAMA_WP']) ? $data['NAMA_WP'] : '' ?>')
      .extend({
        required: {params: true, message: 'Nama WP/WR tidak boleh kosong'},
      });
    self.alamat = ko.observable('<?php echo isset($data['ALAMAT_WP']) ? $data['ALAMAT_WP'] : '' ?>')
      .extend({
        required: {params: true, message: 'Alamat WP/WR tidak boleh kosong'},
      });
    self.id_kecamatan = ko.observable('<?php echo isset($data['ID_KECAMATAN']) ? $data['ID_KECAMATAN'] : 0 ?>');
    self.id_kelurahan = ko.observable('<?php echo isset($data['ID_KELURAHAN']) ? $data['ID_KELURAHAN'] : 0 ?>');
    self.kecamatan = ko.observable('<?php echo isset($data['NAMA_KECAMATAN']) ? $data['NAMA_KECAMATAN'] : '' ?>')
      .extend({
        required: {params: true, message: 'Kecamatan tidak boleh kosong'},
      });
    self.kelurahan = ko.observable('<?php echo isset($data['NAMA_KELURAHAN']) ? $data['NAMA_KELURAHAN'] : '' ?>')
      .extend({
        required: {params: true, message: 'Kelurahan tidak boleh kosong'},
      });
    self.status = ko.observable('<?php echo isset($data['STATUS_SPT']) ? $data['STATUS_SPT'] : '' ?>');
    self.omset = ko.observable('<?php echo isset($data['JUMLAH']) ? $data['JUMLAH'] : 0 ?>');
    self.awal = ko.observable('<?php echo isset($data['PERIODE_AWAL']) ? format_date($data['PERIODE_AWAL']) : '' ?>')
      .extend({
        required: {params: true, message: 'Periode Awal tidak boleh kosong'},
      });
    self.akhir = ko.observable('<?php echo isset($data['PERIODE_AKHIR']) ? format_date($data['PERIODE_AKHIR']) : '' ?>')
      .extend({
        required: {params: true, message: 'Periode Akhir tidak boleh kosong'},
      });
    self.lokasi = ko.observable('<?php echo isset($data['LOKASI']) ? $data['LOKASI'] : '' ?>')
      .extend({
        required: {params: true, message: 'Lokasi tidak boleh kosong'},
      });
    self.uraian = ko.observable('<?php echo isset($data['URAIAN']) ? $data['URAIAN'] : '' ?>')
      .extend({
        required: {params: true, message: 'Judul tidak boleh kosong'},
      });
    self.tarif1 = ko.observable('<?php echo isset($data['TARIF_RP']) ? $data['TARIF_RP'] : 0 ?>');
    self.tarif2 = ko.observable('<?php echo isset($data['TARIF_PERSEN']) ? $data['TARIF_PERSEN'] : 0 ?>');
    self.jml = ko.observable('<?php echo isset($data['JUMLAH_PAJAK']) ? $data['JUMLAH_PAJAK'] : 0 ?>');
    self.jml_reklame = ko.observable('<?php echo isset($data['JUMLAH_REKLAME']) ? $data['JUMLAH_REKLAME'] : 0 ?>')
      .extend({
        required: {params: true, message: 'Jumlah Reklame tidak boleh kosong'},
      });
    self.sisi = ko.observable('<?php echo isset($data['SISI']) ? $data['SISI'] : 0 ?>')
      .extend({
        required: {params: true, message: 'Jumlah Sisi tidak boleh kosong'},
      });
    self.hari = ko.observable('<?php echo isset($data['HARI']) ? $data['HARI'] : 0 ?>')
      .extend({
        required: {params: true, message: 'Jumlah Hari tidak boleh kosong'},
      });
    self.panjang = ko.observable('<?php echo isset($data['PANJANG']) ? $data['PANJANG'] : 0 ?>')
      .extend({
        required: {params: true, message: 'Panjang tidak boleh kosong'},
      });
    self.lebar = ko.observable('<?php echo isset($data['LEBAR']) ? $data['LEBAR'] : 0 ?>')
      .extend({
        required: {params: true, message: 'Lebar tidak boleh kosong'},
      });
    self.luas = ko.observable('<?php echo isset($data['LUAS']) ? $data['LUAS'] : 0 ?>');
    self.diskon = ko.observable('<?php echo isset($data['DISKON']) ? $data['DISKON'] : 0 ?>')
      .extend({
        required: {params: true, message: 'Diskon tidak boleh kosong'},
      });
	self.nilai_strategis = ko.observable('<?php echo isset($data['NILAI_STRATEGIS']) ? $data['NILAI_STRATEGIS'] : 0 ?>')
      .extend({
        required: {params: true, message: 'Nilai Strategis tidak boleh kosong'},
      });

    self.mode = ko.computed(function(){
      return self.id() > 0 ? 'edit' : 'new';
    });

    self.title = ko.computed(function(){
      return (self.mode() === 'edit' ? 'Edit ' : 'Entri ') + '<?php echo $header;?>';
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
    
    self.jml_reklame.subscribe(function(newValue){
      luas = self.panjang() * self.lebar();
      total = self.tarif1() * self.panjang() * self.lebar() * self.jml_reklame() * self.sisi() * self.hari();
      diskon = total * (self.diskon() / 100);
      pajak = (total - diskon) + self.nilai_strategis();
      self.luas(luas);
      self.jml(pajak);
    })

    self.sisi.subscribe(function(newValue){
      luas = self.panjang() * self.lebar();
      total = self.tarif1() * self.panjang() * self.lebar() * self.jml_reklame() * self.sisi() * self.hari();
      diskon = total * (self.diskon() / 100);
      pajak = (total - diskon) + self.nilai_strategis();
      self.luas(luas);
      self.jml(pajak);
    })

    self.diskon.subscribe(function(newValue){
      luas = self.panjang() * self.lebar();
      total = self.tarif1() * self.panjang() * self.lebar() * self.jml_reklame() * self.sisi() * self.hari();
      diskon = total * (self.diskon() / 100);
      pajak = (total - diskon) + self.nilai_strategis();
      self.luas(luas);
      self.jml(pajak);
    })

    self.hari.subscribe(function(newValue){
      luas = self.panjang() * self.lebar();
      total = self.tarif1() * self.panjang() * self.lebar() * self.jml_reklame() * self.sisi() * self.hari();
      diskon = total * (self.diskon() / 100);
      pajak = (total - diskon) + self.nilai_strategis();
      self.luas(luas);
      self.jml(pajak);
    })

    self.panjang.subscribe(function(newValue){
      luas = self.panjang() * self.lebar();
      total = self.tarif1() * self.panjang() * self.lebar() * self.jml_reklame() * self.sisi() * self.hari();
      diskon = total * (self.diskon() / 100);
      pajak = (total - diskon) + self.nilai_strategis();
      self.luas(luas);
      self.jml(pajak);
    })

    self.lebar.subscribe(function(newValue){
      luas = self.panjang() * self.lebar();
      total = self.tarif1() * self.panjang() * self.lebar() * self.jml_reklame() * self.sisi() * self.hari();
      diskon = total * (self.diskon() / 100);
      pajak = (total - diskon) + self.nilai_strategis();
      self.luas(luas);
      self.jml(pajak);
    })
	
	self.nilai_strategis.subscribe(function(newValue){
      luas = self.panjang() * self.lebar();
      total = self.tarif1() * self.panjang() * self.lebar() * self.jml_reklame() * self.sisi() * self.hari();
      diskon = total * (self.diskon() / 100);
      pajak = (total - diskon) + self.nilai_strategis();
      self.luas(luas);
      self.jml(pajak);
    })
	
	//add nana
	 $.getJSON(root+'Umum'+'/get_no_spt', function(data){
      if(self.isEdit() === false)
        return self.nospt(data); 
      else
        return self.nospt();
    });
  }

  var App = new ModelPendaftaran();

  App.prev = function(){
    if(App.tipe() === 'SA')
      show_prev_sa(modul, App.id(), App.tipe());
    else if(App.tipe() === 'OA')
      show_prev_oa(modul, App.id(), App.tipe());
  }

  App.next = function(){
    if(App.tipe() === 'SA')
      show_next_sa(modul, App.id(), App.tipe());
    else if(App.tipe() === 'OA')
      show_next_oa(modul, App.id(), App.tipe());
  }

  App.print = function(){
    preview({"tipe":"form", "id": App.id()});
  }

  App.back = function(){
	if(App.tipe() === 'SA')
      location.href = root+modul+'/daftar_sa';
    else if(App.tipe() === 'OA')
      location.href = root+modul+'/daftar_oa';
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

  App.pilih_rekening = function(){
    if (!App.canSave() || App.isEdit()) { return; }
    var option = {multi:0, mode:'pendataan_reklame'};
    Dialog.pilihRekening(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.idrek(rs.idrek);
      App.kd_rek(rs.kdrek);
      App.nm_rek(rs.nmrek);
      if (rs.tarif_rp === '') rs.tarif_rp = 0;
      App.tarif1(rs.tarif_rp);
      if (rs.tarif_persen === '') rs.tarif_persen = 0;
      App.tarif2(rs.tarif_persen);

      var total = App.tarif1() * App.panjang() * App.lebar() * App.jml_reklame() * App.sisi() * App.hari();
      var diskon = total * (App.diskon() / 100);
      //var pajak = total - diskon;
	  var pajak = (total - diskon) + App.nilai_strategis();
      App.jml(pajak);
    });
  }

  App.pilih_npwpd = function(){
    if (!App.canSave() || App.isEdit()) { return; }
    var option = {multi:0, mode:'pendataan_reklame_npwpd'}; //update nana
    Dialog.pilihNPWPD(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.id_wp(rs.id_wp);
      App.npwpd(rs.npwpd);
      App.nama(rs.nama_wp);
      App.alamat(rs.alamat_wp);
      App.id_kecamatan(rs.id_kecamatan);
      App.id_kelurahan(rs.id_kelurahan);
      App.kecamatan(rs.kecamatan);
      App.kelurahan(rs.kelurahan);
    });
  }

  ko.applyBindings(App);
  
</script>