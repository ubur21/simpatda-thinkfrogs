<?php
if ($this->session->userdata('login') != true){
  redirect ('login');
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8'>
    <title><?php if(isset($title)) { echo $title; }?></title>

    <link href="<?php echo base_url()?>assets/css/jquery-ui.css" rel="stylesheet" media="screen">
    <link href="<?php echo base_url()?>assets/css/ui.jqgrid.css" rel="stylesheet" media="screen">
    <link href="<?php echo base_url()?>assets/css/bootstrap.css" rel="stylesheet" media="screen">
    <link href="<?php echo base_url()?>assets/css/select2.css" rel="stylesheet" media="screen">
    <link href="<?php echo base_url()?>assets/css/pnotify.css" rel="stylesheet" media="screen">
    <link href="<?php echo base_url()?>assets/css/main.css" rel="stylesheet" media="screen">
    <link href="<?php echo base_url()?>assets/css/jquery.autocomplete.css" rel="stylesheet" media="screen">
	<link href="<?php echo base_url()?>assets/css/bootstrap_upload/bootstrap-fileupload.min.css" rel="stylesheet" media="screen">
    <link href="<?php echo base_url()?>assets/css/jquery.treeview.css" rel="stylesheet" media="screen">
	
    <script type="text/javascript" src="<?php echo base_url()?>assets/script/jquery.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/script/jquery-ui.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/script/grid.locale-id.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/script/jqgrid.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/script/bootstrap.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/script/select2.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/script/formatCurrency.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/script/pnotify.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/script/numeral.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/script/numeral.id.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/script/jquery.autocomplete.js"></script>
	<script src="<?php echo base_url()?>assets/script/jquery.tmpl.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/script/knockout.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/script/knockout.validation.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/script/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url()?>assets/script/bootstrap-fileupload.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url()?>assets/script/moment.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url()?>assets/script/jquery.treeview.js"></script>
    <script type="text/javascript">
      var root = '<?php echo base_url();?>';
      var modul = '<?php echo (isset($modul) ? $modul : '');?>';
    </script>
  </head>
<body>
  <!--START NAVBAR -->
  <div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
      <div class="container">
        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>
        <a class="brand" href="#">SIMPATDA</a>
        <span class="brand"><?php echo isset($nama_pemda) ? $nama_pemda : ""?></span><span class="brand"><?php echo isset($tahun) ? $tahun : ""?> - <?php echo isset($status) ? $status : ""?></span>
        <div class="nav-collapse">
          <ul class="nav pull-right">
            <li>
              <div class="btn-group" style="margin-top:7px;">  <a class="medium twitter button radius" style="text-decoration:none;"><i style="font-size:14px; padding-top:3px; padding-right:5px;" class="icon-envelope"></i>(5) Messages</a> <a href="<?php echo base_url()?>logout" class="medium twitter button radius" style="text-decoration:none;"><i style="font-size:16px; padding-top:3px; padding-right:5px;" class="icon-off"></i>Log out</a> </div>
            </li>
            <li class="dropdown">
              <a href="pages.htm" class="dropdown-toggle" data-toggle="dropdown">
                <span style="padding-right:10px; width:30px;">
                
                  <?php
                      $icon = $this->Group_model->get_one('ICON','USERS','ID = '.$this->session->userdata('id_user'));
                      
                      if($icon != '')
                      {
                  ?>
                          <img src="<?php echo base_url().'assets/img/user/'.$icon; ?>" style="width:30px;" alt="" />
                  <?php      
                      }
                      else
                      {
                  ?>
                          <img src="<?php echo base_url();?>assets/img/user.png" style="width:30px;" alt="" />
                  <?php      
                      }
                  ?>
                  
                  
                </span>
				<?php //echo isset($nama_operator) ? $nama_operator : ""
					echo $this->session->userdata('nama_operator');
				?><b class="caret"></b>
              </a>
              <ul class="dropdown-menu">
                <li>
                  <a href="<?php echo base_url()?>auth/user/<?php echo $this->session->userdata('id_user'); ?>"><i style="font-size:14px; padding-top:3px; padding-right:5px;" class="icon-user"></i>My Account</a>
                </li>
                <li>
                  <a href="#"><i style="font-size:14px; padding-top:3px; padding-right:5px;" class="icon-lock"></i>Privacy Settings</a>
                </li>
                <li>
                  <a href="error.htm"><i style="font-size:14px; padding-top:3px; padding-right:5px;" class="icon-cogs"></i>System Settings</a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  
	<div class="navbar navbar-inverse">
		<div class="navbar-inner">
			<div class="container">
				<ul class="nav nav-pills">
					<li>
					<a href="#">
					<i class="icon-dashboard icon-large"></i>Beranda</a>
					</li>
					<?php
					$menu = $this->Group_model->get_backend_menu($this->session->userdata('group'));
					foreach($menu as $x => $value)
					{
						if($value['child'])
						{
						?>
							<li class="dropdown">
								<a href="<?php echo base_url().$value['menu_link']; ?>" class="dropdown-toggle" data-toggle="dropdown">
								<i class="icon-th icon-large">
								</i><?php echo $value['menu_title']; ?>
								<b class="caret">
								</b>
								</a>

								<ul class="dropdown-menu">
									<?php
									foreach($value['child'] as $key => $child)
									{
										if($child['TITLE'] == '---')
										{
										?>
											<li class="divider"></li>
											<?php
										}
										else
										{
											?>
											<li>
												<a href="<?php echo base_url().$child['LINK']; ?>">
												<?php echo $child['TITLE']; ?>
											</a>
											</li>
										<?php  
										}
									}
									?>
								</ul>
							</li>
						<?php
						}
						else
						{
						?>
							<li>
								<a href="<?php echo base_url().$value['menu_link']; ?>">
								<i class="icon-dashboard icon-large">
								</i><?php echo $value['menu_title']; ?>
								</a>
							</li>
						<?php
						}
					}
				?>
				</ul>
			</div>
		</div>
	</div>
	<!--END NAVBAR -->
  

  <!--START SUB-NAVBAR -->
  <!--<div class="navbar navbar-inverse">
    <div class="navbar-inner">
      <div class="container">
        <ul class="nav nav-pills">
          <li>
            <a href="#">
              <i class="icon-dashboard icon-large"></i>Beranda</a>
          </li>
          <li class="dropdown">
            <a href="" class="dropdown-toggle" data-toggle="dropdown">
              <i class="icon-th icon-large"></i>Data Dasar<b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="<?php echo base_url();?>pejabat_daerah">Pejabat Daerah</a></li>
              <li><a href="<?php echo base_url();?>dinas">Data Dinas</a></li>
              <li><a href="<?php echo base_url();?>kecamatan">Data Kecamatan</a></li>
              <li><a href="<?php echo base_url();?>rekening">Kode Rekening</a></li>
              <li><a href="<?php echo base_url();?>anggaran">Data Anggaran</a></li>
              <li><a href="<?php echo base_url();?>tarif_air">Data Air Tanah</a></li>
              <li><a href="<?php echo base_url();?>jenis_usaha">Data Jenis Usaha</a></li>
            </ul>
          </li>
          <li class="dropdown">
            <a href="" class="dropdown-toggle" data-toggle="dropdown">
              <i class="icon-th icon-large"></i>Pendaftaran<b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="<?php echo base_url();?>pendaftaran">Wajib Pajak/Retribusi</a></li>
            </ul>
          </li>
			<li class="dropdown">
				<a href="" class="dropdown-toggle" data-toggle="dropdown">
					<i class="icon-th icon-large"></i>Pendataan<b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li class="dropdown-submenu">
							<a tabindex="-1" href="#">Pendataan Self Assessment</a>
							<ul class="dropdown-menu">
								<li><a tabindex="-1" href="<?php echo base_url();?>pendataan/daftar_sa">Pajak Self Assessment</a></li>
								<li><a tabindex="-1" href="<?php echo base_url();?>pajak_reklame">Pajak Reklame</a></li>
								<li><a tabindex="-1" href="<?php echo base_url();?>pendataan/daftar_oa_hotel">Pajak Hotel</a></li>
								<li><a tabindex="-1" href="<?php echo base_url();?>pendataan/daftar_oa_restoran">Pajak Restoran</a></li>
								<li><a tabindex="-1" href="<?php echo base_url();?>pendataan/daftar_oa_hiburan">Pajak Hiburan</a></li>
								<li><a tabindex="-1" href="<?php echo base_url();?>pendataan/daftar_oa_parkir">Pajak Parkir</a></li>
								<li><a tabindex="-1" href="<?php echo base_url();?>pajak_mineral">Pajak Mineral Non Logam dan Batuan</a></li>
								
							</ul>
						</li>
						<li class="dropdown-submenu">
							<a tabindex="-1" href="#">Pendataan Official Assessment</a>
							<ul class="dropdown-menu">
								<li><a tabindex="-1" href="<?php echo base_url();?>pajak_reklame">Pajak Reklame</a></li>
								<li><a tabindex="-1" href="<?php echo base_url();?>pajak_air">Pajak Air Bawah Tanah</a></li>
							</ul>
						</li>
					</ul>
			</li>
          <li class="dropdown">
            <a href="" class="dropdown-toggle" data-toggle="dropdown">
              <i class="icon-th icon-large"></i>Penetapan<b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="<?php echo base_url();?>penetapan">Penetapan Pajak/Retribusi</a></li>
            </ul>
          </li>
          <li class="dropdown">
            <a href="" class="dropdown-toggle" data-toggle="dropdown">
              <i class="icon-th icon-large"></i>Bendahara<b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="<?php echo base_url();?>bayar_sa">Pembayaran Self Assessment</a></li>
              <li class="divider"></li>
              <li><a href="<?php echo base_url();?>bayar_oa">Pembayaran Official Assesment</a></li>
              <li><a href="<?php echo base_url();?>angsuran">Pembayaran Angsuran</a></li>
              <li><a href="<?php echo base_url();?>bayar_lain">Pembayaran Lain-lain</a></li>
              <li class="divider"></li>
              <li><a href="<?php echo base_url();?>sts">Penyetoran ke Bank</a></li>
            </ul>
          </li>
          <li class="dropdown">
            <a href="<?php echo base_url();?>laporan"><i class="icon-th icon-large"></i>Laporan</b></a>
          </li>
        </ul>
      </div>
    </div>
  </div>-->
  <!--END NAVBAR -->

  <!--START MAIN-CONTENT -->
  <div class="container">
