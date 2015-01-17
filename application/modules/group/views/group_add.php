<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<style>

  ul
  {
    margin: 0;
  }

  #menu
  {
    /*float: left;*/
    width: 900px;
  }

  #menu li.menu
  {
    list-style: none;
    margin: 0 0 4px 0;
    padding: 10px;
    background-color: #ffffff;
    border: #CCCCCC solid 1px;
    color: #7d7d7d;
    font-size: 16px;
  }
  
  #menu ul li ul li
  {
    list-style: none;
    margin: 0 0 15px 15px;
    padding: 3px;
    background-color: #ffffff;
    color: #7d7d7d;
    font-size: 16px;
  }

</style>

<h4><?php //echo $caption; ?></h4>
<h4><legend id="bc" data-bind="text: title"></legend></h4>

<form id="frm" class="form-horizontal" method="post" action="<?php echo base_url(); ?>group/group_proses">
  <div class="control-group" data-bind="validationElement: gname">
    <label class="control-label" for="inputGname" >
      Group Name
    </label>
    <div class="controls">
      <input type="text" id="gname" placeholder="Group Name" data-bind="value: gname">
    </div>
  </div>
  
  <div class="control-group">
    <label class="control-label" for="inputGDesc" >
      Deskripsi
    </label>
    <div class="controls">
      <input type="text" id="gdesc" placeholder="Deskripsi" data-bind="value: gdesc">
    </div>
  </div>
  
  <div class="control-group">
    <label class="control-label" for="inputAkses">
      Hak Akses
    </label>
    <div class="controls">
	  Check All&nbsp;&nbsp;<input type="checkbox" name="item" id="item" data-bind="click: selectAll, checked: allSelected" /><br /><br />
	  
	  <div id='menu'>
    <ul data-bind="foreach: Menus">
    <li class="menu">
        <label class="checkbox inline"><input type="checkbox" data-bind="checked: is_checked, value: parent_id" /> <span data-bind="text: title"></span></label>
		<select data-bind="options: App.opsi, value: akses, optionsCaption: 'Pilih...', optionsText: 'text', optionsValue: 'id'" >
				</select>
		<div data-bind="visible: is_checked() == true">
		<ul data-bind="foreach: child">
			<li data-bind="visible: child_title !== '---'">
				<label class="checkbox inline">
					<input type="checkbox" data-bind="checked: is_checked, value: child_id" /> 
					<span data-bind="text: child_title"></span>
				</label>
				<select data-bind="options: App.opsi, value: akses, optionsCaption: 'Pilih...', optionsText: 'text', optionsValue: 'id'" >
				</select>
			</li>
		</ul>
		</div>
	</li>
	</ul>
	  </div>
    </div>
  </div>
  
  <div class="control-group">
    <div class="controls">
      <input type="button" id="save" value="Simpan" class="btn btn-primary" data-bind="enable: canSave, click: save" />
      <input type="button" id="save" value="Kembali" class="btn btn-primary" data-bind="click: back" />
    </div>
  </div>
</form>

<!--<pre data-bind="text: ko.toJSON($root, null, 2)"></pre>-->

<!--<script type="text/javascript" src="<?php echo base_url()."web/addons/js/jquery/jquery-1.3.2.min.js"; ?>"> </script>
<script type="text/javascript" src="<?php echo base_url()."web/addons/js/jquery/jquery-ui-1.7.1.custom.min.js"; ?>"></script>-->
<script type="text/javascript">
  //jQuery.noConflict();
  /*jQuery(document).ready(function()
    {

      jQuery(function()
        {
          jQuery("#menu ul li ul").sortable(
            {
              opacity: 0.6, cursor: 'move', update: function()
              {
                var order = jQuery(this).sortable("serialize") + '&action=updates';
                jQuery.post("<? echo base_url()."admin/system/update"; ?>", order, function(theResponse)
                  {
                    jQuery("#contentRight").html(theResponse);
                  });
              }
            });
        });

    });*/
	
	
	//ko
  ko.validation.init({
    insertMessages: false,
    decorateElement: true,
    errorElementClass: 'error',
  });  
 
  var ModelGroup = function (){
    var self = this;
    self.modul = 'Group';
    self.akses_level = ko.observable(03);
    self.id = ko.observable('<?php echo isset($data['ID'])?$data['ID']:0 ?>');
    self.gname = ko.observable('<?php echo isset($data['NAME'])?$data['NAME']:'' ?>')
      .extend({
        required: {params: true, message: 'Nama Group tidak boleh kosong'}
      });
    self.gdesc = ko.observable('<?php echo isset($data['DESCRIPTION'])?$data['DESCRIPTION']:'' ?>');
	
    

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
      return self.akses_level() >= 2;
    });

    self.canSave = ko.computed(function(){
      return self.akses_level() >= 3;
    });

    self.errors = ko.validation.group(self);
	
	self.Selected = ko.computed(function () {
        return ko.utils.arrayFilter(Menus, function (item) {
            return item.is_checked();
        });
    });
	self.opsi = ko.observableArray([{id:1, text:'Lihat'}, {id:2, text:'Cetak'}, {id:3, text:'Semua'}]);
	/*self.selectAll = ko.utils.arrayForEach(Menus, function(Menus) {
					 Menus.is_checked(true);
					});*/
	self.allSelected = ko.observable(false);
	self.selectAll = function() {
        var all = self.allSelected();

		//check all parent dan akses jadi 3 (semua)
        ko.utils.arrayForEach(Menus, function(Menus) {
           Menus.is_checked(!all); 
           Menus.akses('3'); 
		   
           //check all child dan akses jadi 3 (semua)
           ko.utils.arrayForEach(Menus.child, function(item)
             {
                item.is_checked(!all);
                item.akses('3');
             });
        });
        return true;
    };  

  }
  
  var Menus = 
	  [
	  
	  	<?php 
			if($group_menus)
			{
				$data = json_decode($group_menus);
				foreach($data as $value => $parent)
				{
		?>
		{
					parent_id : "<?php echo $parent->ID; ?>",
					title : "<?php echo $parent->TITLE; ?>",
					is_checked : ko.observable("<?php if($parent->is_checked == '0') echo false; else echo true; ?>"),
					akses: ko.observable("<?php echo $parent->akses; ?>"),
					child:[
							
								
							<?php
								foreach($parent->child as $child) 
								{
							?>
									{
										child_id : "<?php echo $child->ID; ?>",
										child_title : "<?php echo $child->TITLE; ?>",
										<?php 
											if(isset($child->is_checked))
											{
												if($child->is_checked == '0') $cek = false; else $cek = true;
											}
											else $cek = false;
										?>
										is_checked : ko.observable("<?php echo $cek; ?>"),	
										<?php 
											if(isset($child->akses))
											{
												$akses = $child->akses;
											}
											else $akses = '0';
										?>									
										akses: ko.observable("<?php echo $akses; ?>"),
									},
							<?php
								}
							?>
							
							
						  ],
												    
		},
					
		<?php
				}
			}
		?>
	  
	  ];
  

  var App = new ModelGroup();

  App.isValid = function(){
    var Status = true;

    if (!App.gname()) Status = false;

    return Status;
  }
 
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

        $.pnotify({
          title: res.isSuccess ? 'Sukses' : 'Gagal',
          text: res.message,
          type: res.isSuccess ? 'info' : 'error'
        });
      }
    });

  }

  App.init_select = function(element, callback){
    var data = {'text': $(element).attr('data-init')};
    callback(data);
  } 
 
ko.applyBindings(App);
	
</script>