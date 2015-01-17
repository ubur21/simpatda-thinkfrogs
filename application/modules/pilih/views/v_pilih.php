<div class="controls">
  <select id="sfield<?php echo $dialogname;?>" class='span2'>
  <?php foreach($colsearch as $key => $value){
  echo '<option value="'.$key.'">'.$value.'</option>';
  }?>
  </select>
  <select id="soper<?php echo $dialogname;?>" class='span2'>
    <option value="cn">Memuat</option>
    <option value="bw">Diawali</option>
  </select>
  <input type="text" id="svalue<?php echo $dialogname;?>" class='span4'/>
  <input type="button" value="Filter" id="bfilter<?php echo $dialogname;?>" class='btn btn-primary' />
  <input type="button" value="Reset" id="bclear<?php echo $dialogname;?>" class='btn' />
</div>
<table id="grdDialog<?php echo $dialogname;?>"></table>
<div id="pgrDialog<?php echo $dialogname;?>"></div>