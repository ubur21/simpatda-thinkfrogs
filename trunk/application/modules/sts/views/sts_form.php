

<form id="frm" method="post" action="<?php echo base_url(); ?>sks/proses">
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
  </div>
  </div>
	Rincian Setoran <input type="checkbox" id="rincian_setoran" class="form-control" /> Berdasar TBP
	<br/><br/>
  <table id="grid"></table>
  <div id="pager"></div>

<br />
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
		Total Setor : <input type="text" id="total_setor" name="total_setor"  class="form-control currency" />
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

 var ModelSts = function (){
    var self = this;
    self.modul = 'Tbp';
    self.akses_level = ko.observable(03);
    self.id = ko.observable('<?php echo isset($data['ID'])?$data['ID']:0 ?>');
	
    self.id_jurnal = ko.observable('<?php echo isset($data['ID'])?$data['ID']:0 ?>');
    self.id_bendahara = ko.observable('<?php echo isset($data['ID'])?$data['ID']:0 ?>');
	
	self.nomor_tbp = ko.observable('<?php echo isset($tipe) ? $tipe : '' ?>');
	self.tgl_bayar = ko.observable('<?php echo isset($tipe) ? $tipe : '' ?>');
	self.alamat = ko.observable('<?php echo isset($tipe) ? $tipe : '' ?>');
	
	self.nama_bendahara = ko.observable('<?php echo isset($tipe) ? $tipe : '' ?>');
	self.akun_bendahara = ko.observable('<?php echo isset($tipe) ? $tipe : '' ?>');
	self.jurnal_akrual = ko.observable('<?php echo isset($tipe) ? $tipe : '' ?>');
	
	self.nama_kasdaerah = ko.observable('<?php echo isset($tipe) ? $tipe : '' ?>');
	self.akun_kasdaerah = ko.observable('<?php echo isset($tipe) ? $tipe : '' ?>');
	self.id_kasdaerah = ko.observable('<?php echo isset($tipe) ? $tipe : '' ?>');
	
	self.tgl_sts = ko.observable('<?php echo isset($tipe) ? $tipe : '' ?>');
	self.no_sts = ko.observable('<?php echo isset($tipe) ? $tipe : '' ?>');
	self.otm = ko.observable('<?php echo isset($tipe) ? $tipe : '' ?>');
	
	    self.id_wp = ko.observable('<?php echo isset($data['ID_WAJIB_PAJAK']) ? $data['ID_WAJIB_PAJAK'] : 0 ?>');
    self.npwpd = ko.observable('<?php echo isset($data['NPWPD']) ? $data['NPWPD'] : '' ?>')
      .extend({
        required: {params: true, message: 'NPWPD tidak boleh kosong'},
      });
    self.nama = ko.observable('<?php echo isset($data['NAMA_WP']) ? $data['NAMA_WP'] : '' ?>')
      .extend({
        required: {params: true, message: 'Nama WP/WR tidak boleh kosong'},
      });
	
	self.idskpd = ko.observable('<?php echo isset($data['ID_SKPD']) ? $data['ID_SKPD'] : 0 ?>')
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
  
 App.pilih_npwpd = function(){
    if (!App.canSave() || App.isEdit()) { return; }
    var option = {multi:0, mode:'pendataan_restoran_npwpd'};
    Dialog.pilihNPWPD(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.id_wp(rs.id_wp);
      App.npwpd(rs.npwpd);
      App.nama(rs.nama_wp);
      App.alamat(rs.alamat_wp);
    });
  }
  	
  
  App.hapus_icon = function(){
    var agree=confirm("Apakah Anda yakin akan menghapus icon?");
    if(agree)
    {
      $.ajax(
            {
              url: '<?php echo base_url()?>group/icon',
              type: 'post',
              dataType: 'json',
              data: {id:App.id()},
              success: function(res, xhr)
              {
                if (res.id) App.id(res.id);

                $.pnotify(
                  {
                    title: res.isSuccess ? 'Sukses' : 'Gagal',
                    text: res.message,
                    type: res.isSuccess ? 'info' : 'error'
                  });
                  refresh('2400');
              }
            });
    }

  }

  ko.applyBindings(App);

</script>