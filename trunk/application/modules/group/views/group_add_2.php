<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
?>

<style>

  ul
  {
    margin: 0;
  }

  #contentLeft
  {
    /*float: left;*/
    width: 900px;
  }

  #contentLeft li
  {
    list-style: none;
    margin: 0 0 4px 0;
/*    padding: 10px;*/
    background-color: #ffffff;
    border: #CCCCCC solid 0px;
    color: #7d7d7d;
    font-size: 16px;
  }
  
  #contentLeft ul li ul li
  {
/*    list-style:circle;*/
    margin: 0 0 4px 0px;
/*    padding: 10px;*/
	  border: none;
    background-color: #ffffff;
    color: #7d7d7d;
    font-size: 16px;
  }
  
  /*body { margin: 50px; background: url(assets/bglight.png); }*/
		.well { background: #fff; text-align: center; }
		.modal { text-align: left; }
  
</style>
<h4><?php echo 'Menu'; ?><!--<legend id="bc" data-bind="text: title"></legend>--></h4>
<form>
  <fieldset>
    <input type="checkbox" data-bind="checked: is_checked" /> Option
    <button data-toggle="modal" href="#form-content" data-bind="click: parent_menu,visible: is_checked() == true" value="0" class="btn btn-success">
      Tambah Menu
    </button>
    <span data-bind="visible: is_checked() == true"><a href="<?php echo base_url().'group/groups/'.$this->session->userdata('group'); ?>"> menu terkait</a></span>
  </fieldset>
</form>

<?php
  $no = 1;
?>

  <div id='contentLeft'>
  <ul id="navigasi">
  <?php
    foreach($group_menu as $x => $hasil)
    {
  ?>
    
    <li id="itemz_<?php echo $hasil['ID']; ?>">
        &nbsp;<strong><?php echo $hasil['TITLE']; ?></strong>
        <!--&nbsp;&nbsp;&nbsp;<a href = "<?php echo base_url().'group/menu/'.$hasil['ID']; ?>">Add Child</a>
        <br/>-->
        <button data-toggle="modal" href="#form-content" data-bind="click: id_menu, visible: is_checked() == true" value="<?php echo $hasil['ID']; ?>" class="btn btn-link">Add Child</button>
        <button data-toggle="modal" href="#form-content" data-bind="click: parent_menu, visible: is_checked() == true" value="<?php echo $hasil['ID']; ?>" class="btn btn-link">Edit Menu</button>
        &nbsp;&nbsp;&nbsp;<a data-bind="visible: is_checked() == true" href = "<?php echo base_url().'group/add_separator/'.$hasil['ID']; ?>">Add Divider</a>
        <button data-toggle="modal" data-bind="click: deleted, visible: is_checked() == true" value="<?php echo $hasil['ID']; ?>" class="btn btn-link">Delete</button>
        
  <?php
    $no++;
    $checked = "";
  ?>
      <ul>
  <?php
    foreach($hasil['child'] as $sub)
    {
      if($sub['TITLE'] != '---')
      {
        $title = $sub['TITLE'];
        $edit  = 'Edit';
      }
      else
      {
        $title = '======================';
        $edit  = '';
      }
   ?>
        <li id="itemz_<?php echo $sub['ID']; ?>">
        <div class="controls">
        &nbsp;&nbsp;&nbsp;<?php echo $title; ?>
        <?php if($edit != '') { ?>
          <button data-toggle="modal" href="#form-content" data-bind="click: id_menu, visible: is_checked() == true" value="<?php echo $sub['ID']; ?>" class="btn btn-link"><?php echo $edit; ?></button>
        <?php } ?>
          <!--<a href = "<?php echo base_url().'group/menu/'.$sub['ID']; ?>"><?php echo $edit; ?></a>-->
          <!--<a href = "<?php echo base_url().'group/delete_menu/'.$sub['ID']; ?>" onclick="return confirm('Apakah Anda yakin akan menghapus menu?');">delete</a>-->
          <button data-toggle="modal" data-bind="click: deleted, visible: is_checked() == true" value="<?php echo $sub['ID']; ?>" class="btn btn-link">Delete</button>
          </div>
        </li>
   <?php
      $no++;
    }
   ?>
      </ul>
   </li>
  <?php
    }
  ?>
  </ul>
  </div>
  
  <div id="form-content" class="modal hide fade in" style="display: none;">
  <script>
  
  </script>
			<div class="modal-header">
				<a class="close" data-dismiss="modal"><i class="icon-arrow-up"></i></a>
				<h3><legend id="bc" data-bind="text: title"></legend></h3>
			</div>
      <div id="spinner" data-bind="visible: spinner === 1">
        <img src="<?php echo base_url()?>assets/img/ajax-loader.gif" alt="Loading..."/>
      </div>
    <form id="frm" class="form-horizontal" method="post" action="<?php echo base_url(); ?>group/menu_proses">
      <div class="control-group" data-bind="validationElement: n_menu">
        <label class="control-label" for="inputUsername" >
          Nama Menu
        </label>
        <div class="controls">
          <input type="text" id="n_menu" placeholder="nama menu" data-bind="value: n_menu">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="inputName">
          Link
        </label>
        <div class="controls">
          <input type="text" id="links" placeholder="Link" data-bind="value: links" >
        </div>
      </div>

      <div data-bind="visible: mode() === 'new'">
        <div class="control-group">
          <label class="control-label" for="inputGUser">
            Parent Menu
          </label>
          <div class="controls">
            <span data-bind="text: parent_title"></span>
          </div>
        </div>
      </div>

      <div class="control-group" data-bind="validationElement: aktif">
        <label class="control-label" for="inputSKPD">
          Status
        </label>
        <div class="controls">

          <input type="radio" data-bind="checked: aktif" value="1" />(aktif)
          <input type="radio" data-bind="checked: aktif" value="0" />(tidak aktif)
          
        </div>
      </div>
    </form>
			<div class="modal-footer">
				<input class="btn btn-success" type="submit" value="Simpan" id="submit" data-bind="enable: canSave, click: save">
				<a href="#" class="btn" data-dismiss="modal">Tutup</a>
			</div>
		</div>    
    
<!--<pre data-bind="text: ko.toJSON($root, null, 2)"></pre>-->

<script type="text/javascript">
  function refresh (timeoutPeriod)
  {
    refresh = setTimeout(function(){window.location.reload(true);},timeoutPeriod);
  }

  //jQuery.noConflict();
  jQuery(document).ready(function()
    {

      jQuery(function()
        {
          jQuery("#contentLeft ul").sortable(
            {
              opacity: 0.6, cursor: 'move', update: function()
              {
                var order = jQuery(this).sortable("serialize") + '&action=updates';
                jQuery.post("<?php echo base_url()."group/update"; ?>", order, function(theResponse)
                  {
                    jQuery("#contentRight").html(theResponse);
                  });
              }
            });
        });

      jQuery(function()
        {
          jQuery("#contentLeft ul li ul").sortable(
            {
              opacity: 0.6, cursor: 'move', update: function()
              {
                var order = jQuery(this).sortable("serialize") + '&action=updates';
                jQuery.post("<?php echo base_url()."group/update"; ?>", order, function(theResponse)
                  {
                    jQuery("#contentRight").html(theResponse);
                  });
              }
            });
        });
        
        jQuery(function()
        {
          jQuery("#contentLeft ul li ul li ul").sortable(
            {
              opacity: 0.6, cursor: 'move', update: function()
              {
                var order = jQuery(this).sortable("serialize") + '&action=updates';
                jQuery.post("<?php echo base_url()."group/update"; ?>", order, function(theResponse)
                  {
                    jQuery("#contentRight").html(theResponse);
                  });
              }
            });
        });
        
        jQuery("#navigasi").treeview({
        collapsed: true,
        unique: true,
        persist: "location"
        });

    });
    
	
	
//ko
  ko.validation.init({
    insertMessages: false,
    decorateElement: true,
    errorElementClass: 'error',
  });  
 
  var ModelGroup = function (){
    var self = this;
    self.modul = 'Menu';
    self.akses_level = ko.observable(03);
    self.id = ko.observable('');
    self.is_checked = ko.observable(false);
    self.ids= ko.observable("");
    self.n_menu = ko.observable('')
      .extend({
        required: {params: true, message: 'Nama Menu tidak boleh kosong'}
      });
    self.aktif = ko.observable('0');
    self.links  = ko.observable('#');
    
    self.parent = ko.observable('0');
    self.parent_title = ko.observable('');
    self.parentEdit = ko.observable('0');
    self.spinner = ko.observable('1');
    
    self.id_menu = function(data, element)
    {           
      self.ids($(element.target).val());
      var idm = $(element.target).val();
      data =
      {
        id: idm,
      };
      $.ajax({
      url: '<?php echo base_url();?>group/menu_by_id',
      type: 'post',
      dataType: 'json',
      data: data,
      success: function(res)
      {
        if (res)
        {
          self.n_menu(res.title);
          self.aktif(res.aktif);
          self.links(res.links);
          self.parent(res.parent_id); 
                    
           //add child
           if(res.parent_id == '0')
           {
             self.n_menu('');
             self.parent_title(res.title);
             self.parent(res.parent_id);
           }
        }
      }
            }); 
    }
    
    self.parent_menu = function(data, element)
    {           
           
      self.ids($(element.target).val());
      var idm = $(element.target).val();
      data =
      {
        id: idm,
      };
      
      if(idm != 0)
      {
        $.ajax(
          {
            url: '<?php echo base_url();?>group/menu_by_id',
            type: 'post',
            dataType: 'json',
            data: data,
            success: function(res)
            {
              if (res)
              {
                self.n_menu(res.title);
                self.aktif(res.aktif);
                self.links(res.links);
                self.parent(res.parent_id);
                self.parentEdit('1');
              }
            }
          });
      }
      else
      {
        self.aktif(1);
        self.spinner(0);
      }
       
    }
        
    self.mode = ko.computed(function(){
      return self.parentEdit() > 0 || self.parent() > 0 ? 'edit' : 'new';
    });

    self.title = ko.computed(function(){
      return (self.mode() === 'edit' ? 'Edit ' : 'Entri ') + self.modul;
    });     
	
	 self.isEdit = ko.computed(function(){
      return self.mode() === 'edit';
    });

    self.canPrint = ko.computed(function(){
      return self.akses_level() >= 2;
    });

    self.canSave = ko.computed(function(){
      return self.akses_level() >= 3;
    });

    self.errors = ko.validation.group(self);
}  

  var App = new ModelGroup();

  /*App.isValid = function(){
    var Status = true;

    if (!App.gname()) Status = false;

    return Status;
  }*/
 
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

    $.ajax({
      url: $frm.attr('action'),
      type: 'post',
      dataType: 'json',
      data: data,
      success: function(res, xhr){
        if (res.id) App.id(res.id);
        
        if (res.isSuccess)
        {
          refresh('2400');
        }
        
        $.pnotify({
          title: res.isSuccess ? 'Sukses' : 'Gagal',
          text: res.message,
          type: res.isSuccess ? 'info' : 'error'
        });
      }
    });

  }
  
  App.deleted = function(data, element){
    
    var idm = $(element.target).val();
    data =
    {
      id: idm,
    };
    var agree=confirm("Apakah Anda yakin akan menghapus menu?");
    if (agree)
		{
      $.ajax({
        url: '<?php echo base_url();?>group/delete_menu',
        type: 'post',
        dataType: 'json',
        data: data,
        success: function(res, xhr){
          if (res.id) App.id(res.id);
          
          if (res.isSuccess)
          {
            refresh('2400');
          }
          
          $.pnotify({
            title: res.isSuccess ? 'Sukses' : 'Gagal',
            text: res.message,
            type: res.isSuccess ? 'info' : 'error'
          });
        }
      });
    }

  }   

  App.init_select = function(element, callback){
    var data = {'text': $(element).attr('data-init')};
    callback(data);
  } 
 
ko.applyBindings(App);

  $('#spinner').ajaxStart(function ()
    {
      $(this).fadeIn('fast');
    }).ajaxStop(function ()
    {
      $(this).stop().fadeOut('fast');
    });

</script>