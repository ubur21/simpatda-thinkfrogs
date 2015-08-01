<form id="frm" method="post" action="<?php echo base_url(); ?>tbp/proses">
  <fieldset>
	<legend>Data Registrasi</legend>
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
		
		<div class="control-group pull-left">
			<label class="control-label" for="gol">Nomor TBP</label>
			<input type="text" class="span2" id="nomor_tbp" data-bind="value: nomor_tbp" required />
			<input type="checkbox" name="chk_otm" id="chk_otm" data-bind="value: chk_otm" /> Otm
		</div>
		
		<div class="control-group pull-left" style="margin-left:20px" >
			<label class="control-label" for="jenis">Tanggal Bayar</label>
			<input type="text" class="datepicker span2" id="tgl_bayar" data-bind="value: tgl_bayar" required />
		</div>
		
		
		
		<div class="control-group pull-left" data-bind="validationElement: akun_bendahara" style="margin-left:20px;" >
			<div class="controls span2 input-append">
				<label class="control-label" for="namabendahara">Nama Bendahara</label>
				<input type="text" class="span2" id="nama_bendahara" readonly="1" data-bind="value: nama_bendahara" required />
			</div>
			<div class="controls span2 input-append">
			  <label class="control-label" for="akunbendahara">Akun Bendahara</label>
			  <input type="hidden"  id="id_bendahara" data-bind="value: id_bendahara" required />
			  <input type="text" class="span2" id="akun_bendahara" readonly="1" data-bind="value: akun_bendahara, executeOnEnter: pilih_bendahara" required />
			  <span class="add-on" data-bind="visible: !isEdit() && canSave(),  click: pilih_bendahara" ><i class="icon-folder-open"></i></span>
			</div>
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
		<div class="control-group pull-left" data-bind="validationElement: npwpd" >
			<label class="control-label" for="idrek">Nama WP</label>
			<input type="text" class="span2" id="nama" readonly="1" data-bind="value: nama" required />
			<div class="controls span2 input-append">
			  <input type="text" class="span2" id="npwpd" readonly="1" data-bind="value: npwpd, executeOnEnter: pilih_npwpd" required />
			  <span class="add-on" data-bind="visible: !isEdit() && canSave(),  click: pilih_npwpd" ><i class="icon-folder-open"></i></span>
			</div>
			
			<br><br>
			<div class="controls span6 input-append" style="margin-left:0px;">
				<label class="control-label" for="gol">Alamat</label>
				<input type="text" class="span5" id="alamat" data-bind="value: alamat" readonly="1" />
			</div>
		</div>
		
		<div class="control-group pull-left" style="margin-left:-75px" data-bind="validationElement: keterangan" >
			<label class="control-label" for="keterangan">Keterangan</label>
			<textarea style="height:80px;" type="text" class="span6" id="keterangan" data-bind="value: keterangan" required ></textarea>
		</div>
	</div>
  </fieldset>
  
  <fieldset>
    <legend>Rincian Pembayaran Non Ketetapan</legend>
	<div class="control-group pull-left span12" style="margin-left:0px">
		<table id="tbl_grid_ketetapan"></table>
		<div id="div_pager_ketetapan"></div>
		
	</div>
  </fieldset>
  
  <fieldset>
    <legend>Rincian Pembayaran  Ketetapan</legend>
	<div class="control-group pull-left span12" style="margin-left:0px">
		
		<table id="tbl_grid_nonketetapan"></table>
		<div id="div_pager_nonketetapan"></div>
		<br/>
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
		Total Setor : <input type="text" id="total_setor" name="total_setor" class="form-control currency" />
  </div>
  
	</div>
  </fieldset>
  
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
  $('.currency')
    .blur(function(){ $(this).formatCurrency(fmtCurrency); })
    .focus(function(){ $(this).toNumber(fmtCurrency); });
    
  $.datepicker.setDefaults($.datepicker.regional['id']);

	$('.datepicker#tgl_bayar').datepicker({
		onSelect:function(selectedDate) {
			App.tgl_bayar(selectedDate);
			$('.datepicker#tgl_bayar').datepicker("option", "minDate", selectedDate);
		},
	});


  
});

  $("#tbl_grid_ketetapan").jqGrid({
    url:'',
    datatype:'local',
    mtype:'POST',
    colNames:['', 'SKPD/SKRD/SPTPD', 'Jatuh Tempo', 'Kode Akun', 'Nama Akun', 'Nominal Ketetapan', 'Total Bayar Lalu', 'Nominal Bayar', 'Kurang Bayar', 'Denda'],
    colModel:[
        {name:'idspt', hidden:true},
        {name:'skpd', width:100},
        {name:'jatuh_tempo', formatter:'date', width:80},
        {name:'kode_akun', width:80,  align:'left'},
        {name:'nama_akun', width:80,  align:'left'},
        {name:'nominal_ketetapan', width:80,  formatter:'currency', align:'right'},
        {name:'total_bayarlalu', width:100, formatter:'currency', align:'right'},
		{name:'nominal_bayar', width:100, formatter:'currency', align:'right'},
		{name:'kurang_bayar', width:100, formatter:'currency', align:'right'},
		{name:'denda', width:100, formatter:'currency', align:'right'},
    ],
    pager:'#div_pager_ketetapan',
    rowNum:-1,
    scroll:true,
    rownumbers:true,
    viewrecords:true,
		multiselect:true,
    gridview:true,
    shrinkToFit:false,
    recordtext:'{2} baris',
    width:935,
    height:100,
    //onSelectRow: select_row,
    //onSelectAll: select_row,
  });
  
   $("#tbl_grid_ketetapan").jqGrid('bindKeys', {
  });

  $("#tbl_grid_ketetapan").jqGrid('navGrid', '#div_pager_ketetapan', {
    add:false,
    edit:false,
    del:false,
    search:false,
    refresh:false,
  },{},{},{},{});
  
  $("#tbl_grid_nonketetapan").jqGrid({
    url:'',
    datatype:'local',
    mtype:'POST',
    colNames:['', 'Kode Akun', 'Nama Akun', 'Nominal Bayar'],
    colModel:[
        {name:'idspt', hidden:true},
        {name:'kd_akun', width:150},
        {name:'nm_akun', width:100, formatter:'currency', align:'right'},
        {name:'nominal', width:100, formatter:'date', align:'center'},
    ],
    pager:'#div_pager_nonketetapan',
    rowNum:-1,
    scroll:true,
    rownumbers:true,
    viewrecords:true,
		multiselect:true,
    gridview:true,
    shrinkToFit:false,
    recordtext:'{2} baris',
    width:935,
    height:100,
    //onSelectRow: select_row,
    //onSelectAll: select_row,
  });
  
   $("#tbl_grid_nonketetapan").jqGrid('bindKeys', {
  });

  $("#tbl_grid_nonketetapan").jqGrid('navGrid', '#div_pager_nonketetapan', {
    add:false,
    edit:false,
    del:false,
    search:false,
    refresh:false,
  },{},{},{},{});
  
  var last;
  var purge = new Array();

  function refresh (timeoutPeriod)
  {
    refresh = setTimeout(function(){window.location.reload(true);},timeoutPeriod);
  }

  //ko
  ko.validation.init({
    insertMessages: true,
    decorateElement: true,
    errorElementClass: 'error',
  });

  var ModelTbp = function (){
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

  var App = new ModelTbp();

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
  
  
 App.pilih_npwpd = function(){
    if (!App.canSave() || App.isEdit()) { return; }
    var option = {multi:0, mode:'pendataan_restoran_npwpd'};
	
	var $list = $('#tbl_grid_ketetapan'), errmsg = [];
		
		// hapus dulu isi grid
		var rowIds = $list.jqGrid('getDataIDs');
		for(var i=0,len=rowIds.length;i<len;i++){
			var currRow = rowIds[i];
			$list.jqGrid('delRowData', currRow);
		}
		$list.trigger('reloadGrid');
		
    Dialog.pilihNPWPD(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.id_wp(rs.id_wp);
      App.npwpd(rs.npwpd);
      App.nama(rs.nama_wp);
      App.alamat(rs.alamat_wp);
	  
	  $.ajax({
				url: root+modul+'/getspt',
				type: 'post',
				dataType: 'json',
				data: {id_wp: App.id_wp()},
				success: function(response){
					var result = response.rows,
					len = response.len;
					if (len > 0) {
						// add grid dengan data spt sesuai pajak/retribusi
						for (i = 0; i < len; i++){
							$list.jqGrid('addRowData', 'idspt', [{'idspt':result[i]['id_spt'], 'skpd':result[i]['skpd'], 'jatuh_tempo':result[i]['jatuh_tempo'], 'kode_akun':result[i]['kode_akun'], 'nama_akun':result[i]['nama_akun'], 'nominal_ketetapan':result[i]['nominal_ketetapan'], 'total_bayarlalu':result[i]['total_bayarlalu'], 'nominal_bayar':result[i]['nominal_bayar'], 'kurang_bayar':result[i]['kurang_bayar'], 'denda':result[i]['denda']}]);
						}
						//hitungTotal();
						//hitungSisa();
						//jumlahAll();
					}
					else {
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
			});//end ajax
			
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