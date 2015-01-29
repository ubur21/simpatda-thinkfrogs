<fieldset>
  <legend id="bc" data-bind="text: title"></legend>
</fieldset>

<form id="frm" method="post" action="<?php echo base_url(); ?>bayar_sa/proses">
  <div class="controls-row">
    <div class="control-group input-append" data-bind="validationElement: namawp" >
      <label class="control-label" for="namawp">Wajib Pajak/Retribusi</label>
      <input type="text" id="namawp" readonly="1" class="span10" data-bind="value: namawp" required />
      <span class="add-on" data-bind="click: pilih_npwpd" ><i class="icon-folder-open"></i></span>
    </div>
  </div>

  <div class="controls-row">
    <div class="control-group"  data-bind="validationElement: tgl" >
		<label class="control-label" for="tgl">Tanggal Pembayaran</label>
		<input type="text" class="span2 datepicker" id="tgl" data-bind="value: tgl" required style="float:left" />
		&nbsp;<label style="float:left">s/d</label> &nbsp;<input type="text" class="span2 datepicker" id="tgl2" data-bind="value: tgl2" required />
    </div>
  </div>

  <table id="grid"></table>
  <div id="pager"></div>

</form>

<script>
$(document).ready(function() {
  $.datepicker.setDefaults($.datepicker.regional['id']);
  $('.datepicker').datepicker();
});

var ModelPendaftaran = function (){};

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
  
  App.pilih_npwpd = function(){
    alert('x');
    var option = {multi:0, mode:'pendataan_hotel_npwpd'}; //update nana
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
	
  
</script>