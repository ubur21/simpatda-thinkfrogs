<!DOCTYPE html>
<html>
<head>
  <meta charset='utf-8'>
  <title><?php if(isset($title)) { echo $title; }?></title>
  <script type="text/javascript" src="<?php echo base_url()?>assets/script/jquery.js"></script>
  <link href="<?php echo base_url()?>assets/css/bootstrap.css" rel="stylesheet" media="screen">
  <link href="<?php echo base_url()?>assets/css/login-box.css" rel="stylesheet" media="screen">
</head>
<body>
  <div id="login-box">
    <?php echo form_open('auth/process_login', array('class' => 'form-login_box')); ?>
    <h2>SIMPATDA</h2>

    <div id="garuda"></div>
    <div class="modal-body">
      <label for="username">Username</label>
      <input type="text" class="span2" required="required" name="username" id="username" value="" /><br />
      <label>Password</label>
      <input type="password" class="span2" name="password" id="password" value="" /><br />
      <?php 
      /* simpatda tidak menggunakan tahun
      $opt = 'id="tahun" class="span2"' ;
      echo form_dropdown('tahun', $option_tahun, $tahun_kini, $opt); 
      */
      ?> 
      <button type="submit" id="login" class="btn btn-success">Login</button>
      </div>
     <?php echo form_close(); ?>
  </div>

  <!-- Notification -->
  <?php
  $error = $this->session->flashdata('message');
  if(isset($error) && $error != '')
    echo '<div class="alert alert-error""><align="center">'.$error.'</align></div>';
  ?>
  <!-- /Notification -->

<script>
$(document).ready(function () {
  $('#username').focus();
  <?php
  /* simpatda tidak menggunakan tahun
  $("#tahun").val(<?php echo $tahun_kini ?>);
  */
  ?>
});
</script>

</body>
</html>
