
<?php
$title_form = explode('-',$title);
$title_form = trim($title_form['1']);

echo $xajax_js;

$dtidedit = null;
$dtidpenerima = null;

$dtidpengaduan = null;
$dtidtppengaduan = null;
$dtketpengaduan = null;

$dtidundangan = null;
$dttglundangan = null;
$dtwktundangan = null;
$dtlksundangan = null;

$dtiddispensasi = null;
$dttgldispensasi = null;
$dtals1dispensasi = null;
$dtals2dispensasi = null;

$isnomor = null;
$isagenda = null;
$istglsurat = null;
$istglterima = null;
$isjenis = null;
$isklasifikasi = null;
$issifat = null;
$isprioritas = null;
$isstatus = null;
$isbalasan = null;
$isperihal = null;
$isketerangan = null;
$istjuan = null;

if($this->session->userdata("IDSRT") == null){
    $dtidedit = null;
    $isnomor = null;
    $isagenda = null;
    $istglsurat = null;
    $istglterima = null;
    $isjenis = null;
    $isklasifikasi = null;
    $issifat = null;
    $isprioritas = null;
    $isstatus = null;
    $isbalasan = null;
    $isperihal = null;
    $isketerangan = null;
    $istjuan = null;
}
else{
    $dtidedit = $this->session->userdata("IDSRT");
    foreach ($dtedit->result_array() as $rowsurat){
        $isnomor = $rowsurat['SURAT_NO'];
        $isagenda = $rowsurat['SURAT_AGENDA'];
        $istglsurat = $rowsurat['SURAT_TANGGAL'];
        $istglterima = $rowsurat['SURAT_TANGGAL_TERIMA'];
        $isjenis = $rowsurat['SURAT_JENIS'];
        $isklasifikasi = $rowsurat['SURAT_KLASIFIKASI'];
        $issifat = $rowsurat['SURAT_SIFAT'];
        $isprioritas = $rowsurat['SURAT_PRIORITAS'];
        $isstatus = $rowsurat['SURAT_STATUS'];
        $isbalasan = $rowsurat['SURAT_BALASAN'];
        $isperihal = $rowsurat['SURAT_PERIHAL'];
        $isketerangan = $rowsurat['SURAT_KETERANGAN'];
        $istjuan = $rowsurat['SURAT_ASAL'];
    }
}

if($dtlistpenerima == null){
    $dtidpenerima = null;
}
else{
   $dtidpenerima = $dtlistpenerima;
}

if($dtpengaduan == null){
    $dtidpengaduan = null;
    $dtidtppengaduan = null;
}
else{
    foreach ($dtpengaduan->result() as $rpengaduan){
        $dtidpengaduan = $dtpengaduan;
        $dtidtppengaduan = $rpengaduan->PENGADUAN;
        $dtketpengaduan = $rpengaduan->KETERANGAN;
        $this->session->set_userdata("IDEDTSAMPING",$rpengaduan->ID);
    }    
}

if($dtundangan == null){
    $dtidundangan = null;
    $dttglundangan = null;
    $dtwktundangan = null;
    $dtlksundangan = null;
}
else{
    $dtidundangan = $dtundangan;
    foreach($dtundangan->result() as $rundangan){        
        $dttglundangan = $rundangan->TGL_UDG;
        $dtwktundangan = $rundangan->WAKTU;
        $dtlksundangan = $rundangan->TMP;
        $this->session->set_userdata("IDEDTSAMPING",$rundangan->ID);
    }
}

if($dtdispensasi == null){
    $dtiddispensasi = null;
    $dttgldispensasi = null;
    $dtals1dispensasi = null;
    $dtals2dispensasi = null;
}
else{
    $dtiddispensasi = $dtdispensasi;
    foreach ($dtdispensasi->result() as $rdsp1){
        $dttgldispensasi = $rdsp1->TGL_DISPENSASI;
        $dtals1dispensasi = $rdsp1->TYPE_ABSENSI;
        $dtals2dispensasi = $rdsp1->ALASAN;
        $this->session->set_userdata("IDEDTSAMPING",$rdsp1->ID);
    }    
}

if($dtidedit == null){
    $frmAtrribut = array("id"=>"frm_surat");
    echo form_open_multipart('surat/SaveSurat1', $frmAtrribut);
}
else{
    $frmAtrribut = array("id"=>"frm_surat");
    echo form_open_multipart('surat/SaveSurat2', $frmAtrribut);
}

?>
<table class="layout">
    <tr>
        <td><?php $this->load->view('surat/menu'); ?></td>
        <td>
            <div class="form">
                <div class="trigger"><div class="<?php echo $this->css->bar();?>" style="padding:3px;"><?php echo form_label('Tulis Surat','tulis surat'); ?></div></div>
                <div class="toggle_aset_default">
                    <?php                    
                    ?>
                    <fieldset>
                        <legend></legend>
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td id="tbl_main" width="20%">
                                    Nomor Surat
                                </td>
                                <td id="tbl_main" width="1%">
                                    :
                                </td>
                                <td id="tbl_main" width="75%" style="vertical-align: middle">
                                    <?php

                                    if($dtidedit == null){
                                        echo "<input id=txtbox1 name=t_nsurat type=text onchange=xajax_c_nsurat(xajax.getFormValues('frm_surat')) /> <label id=lblnsurat style=font-size: 10pt; font-weight: bold; color: red></label> <input type=button id=tblnsurat name=tbl01 value='Gunakan nomor ini' style=display:none onclick=xajax_svsrt(xajax.getFormValues('frm_surat')) />";
                                    }
                                    else{
                                        echo "<input id=txtbox1 name=t_nsurat value='$isnomor' type=text onchange=xajax_c_nsurat(xajax.getFormValues('frm_surat')) /> <label id=lblnsurat style=font-size: 10pt; font-weight: bold; color: red></label> <input type=button id=tblnsurat name=tbl01 value='Gunakan nomor ini' style=display:none onclick=xajax_svsrt(xajax.getFormValues('frm_surat')) />";
                                    }
                                    ?>
                                    <!--<input id="txtbox1" name="t_nsurat" type="text" onchange="xajax_c_nsurat(xajax.getFormValues('frm_surat'))"/> <label id="lblnsurat" style=" font-size: 10pt; font-weight: bold; color: red"></label> <input type="button" id="tblnsurat" name="tbl01" value="Gunakan nomor ini" style="display:none" onclick="xajax_svsrt(xajax.getFormValues('frm_surat'))"/>-->
                                </td>
                            </tr>
                            <tr>
                                <td id="tbl_main" width="20%">
                                    Nomor Agenda
                                </td>
                                <td id="tbl_main" width="1%">
                                    :
                                </td>
                                <td id="tbl_main" width="75%">
                                    <?php
                                        if($dtidedit == null){
                                            echo "<input id=txtbox name=t_nagenda type=text onchange=xajax_c_nagenda(xajax.getFormValues('frm_surat')) /> <label id=lblagenda style=font-size: 10pt; font-weight: bold; color: red></label>";
                                        }
                                        else{
                                            echo "<input id=txtbox name=t_nagenda value='$isagenda' type=text onchange=xajax_c_nagenda(xajax.getFormValues('frm_surat')) /> <label id=lblagenda style=font-size: 10pt; font-weight: bold; color: red></label>";
                                        }
                                    ?>                                    
                                </td>
                            </tr>
                            <tr>
                                <td id="tbl_main" width="20%">
                                    Tanggal Surat
                                </td>
                                <td id="tbl_main" width="1%">
                                    :
                                </td>
                                <td id="tbl_main" width="75%">
                                    <?php
                                        if($dtidedit == null){
                                            echo "<input id=txtdate name=t_ntanggal type=text />";
                                        }
                                        else{
                                            $fmt = "d/m/Y";
                                            $tglsrt =  strtotime($istglsurat);
                                            $fixtglsrt = date($fmt,$tglsrt);
                                            echo "<input id=txtdate name=t_ntanggal type=text value=$fixtglsrt />";
                                        }
                                    ?>                                    
                                </td>
                            </tr>
                            <tr>
                                <td id="tbl_main" width="20%">
                                    Tanggal Terima
                                </td>
                                <td id="tbl_main" width="1%">
                                    :
                                </td>
                                <td id="tbl_main" width="75%">
                                    <?php
                                    if($dtidedit == null){
                                        echo "<input id=txtdate5 name=t_ntanggaltrima type=text />";
                                    }
                                    else{
                                        $fmt = "d/m/Y";
                                        $tglsrttrm =  strtotime($istglterima);
                                        $fixtglsrttrm = date($fmt,$tglsrttrm);
                                        echo "<input id=txtdate5 name=t_ntanggaltrima type=text value='$fixtglsrttrm' />";
                                    }
                                    ?>
                                    
                                </td>
                            </tr>
                            <tr>
                                <td id="tbl_main" width="20%">
                                    Jenis Surat
                                </td>
                                <td id="tbl_main" width="1%">
                                    :
                                </td>
                                <td id="tbl_main" width="75%">
                                    <select id="cmb1" name="cmb_jns" onchange="xajax_GetJenis(this[this.selectedIndex].value);">
                                        <?php
                                            if($dtidedit == null) {
                                                if($dtjns->num_rows() > 0) {
                                                    foreach ($dtjns->result() as $rjns) {
                                                        echo "<option value='$rjns->J_KODE' >";
                                                        echo $rjns->J_JENIS;
                                                        echo "</option>";
                                                    }
                                                }
                                            }
                                            else {
                                                if($dtjns->num_rows() > 0) {
                                                    foreach ($dtjns->result() as $rjns) {
                                                        if($rjns->J_KODE == $isjenis){
                                                            echo "<option value='$rjns->J_KODE' selected=selected >";
                                                            echo $rjns->J_JENIS;
                                                            echo "</option>";
                                                        }else{
                                                            echo "<option value='$rjns->J_KODE' >";
                                                            echo $rjns->J_JENIS;
                                                            echo "</option>";
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td id="tbl_main" width="20%">
                                    <div id="d_jns01"></div>
                                </td>
                                <td id="tbl_main" width="1%">
                                    <div id="d_jns02"></div>
                                </td>                                
                                <td id="tbl_main" width="75%">
                                    <div id="div_pengaduan" style="<?php if($isjenis == 02){echo "display:inherit";}else{echo "display:none";} ?>">
                                        <table id="tbl_pengaduan">
                                            <tr>
                                                <td>
                                                    Status Pengaduan
                                                </td>
                                                <td>
                                                    :
                                                </td>
                                                <td>
                                                    <?php
                                                    if($dtidpengaduan == null){
                                                        if($dtpgd->num_rows() > 0) {
                                                            foreach ($dtpgd->result() as $rpgd) {
                                                                echo "<input id='r_pgd' name='t_pgd' type='radio' value=$rpgd->ID />$rpgd->desc";
                                                            }
                                                        }
                                                    }
                                                    else{
                                                        if($dtpgd->num_rows() > 0) {
                                                            foreach ($dtpgd->result() as $rpgd) {
                                                                if($rpgd->ID == $dtidtppengaduan){
                                                                    echo "<input id='r_pgd' name='t_pgd' type='radio' value=$rpgd->ID checked />$rpgd->desc ";
                                                                }
                                                                else{
                                                                    echo "<input id='r_pgd' name='t_pgd' type='radio' value=$rpgd->ID />$rpgd->desc ";
                                                                }
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top">
                                                    Keterangan Pengaduan
                                                </td>
                                                <td style="vertical-align: top">
                                                    :
                                                </td>
                                                <td>
                                                    <?php
                                                    if($dtidpengaduan == null){
                                                        echo form_textarea($t_ketpenganduan);
                                                    }
                                                    else{
                                                        echo form_textarea($t_ketpenganduan,$dtketpengaduan);
                                                    }                                                    
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div id="div_undangan" style="<?php if($isjenis == 03){echo "display:inherit";}else{echo "display:none";} ?>">
                                        <table id="tbl_undangan">
                                            <tr>
                                                <td>
                                                    Tanggal
                                                </td>
                                                <td>
                                                    :
                                                </td>
                                                <td>
                                                    <?php
                                                        if($dtidundangan == null){
                                                            echo "<input id=txtdate1 name=tgl_undangan type=text />";
                                                        }
                                                        else{
                                                            $fmt = "d/m/Y";
                                                            $temptglundangan = strtotime($dttglundangan);
                                                            $fixtglundangan = date($fmt,$temptglundangan);
                                                            echo "<input id=txtdate1 name=tgl_undangan type=text value='$fixtglundangan' />";
                                                        }
                                                    ?>                                                    
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Waktu
                                                </td>
                                                <td>
                                                    :
                                                </td>
                                                <td>
                                                    <?php
                                                        if($dtidundangan == null){
                                                            echo "<input id=date2 name=wkt_undangan type=text />";
                                                        }
                                                        else{
                                                            echo "<input id=date2 name=wkt_undangan type=text value='$dtwktundangan' />";
                                                        }
                                                    ?>                                                    
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Lokasi
                                                </td>
                                                <td>
                                                    :
                                                </td>
                                                <td>
                                                    <?php
                                                        if($dtidundangan == null){
                                                            echo "<input id=txtbox name=t_tempatundangan type=text />";
                                                        }
                                                        else{
                                                            echo "<input id=txtbox name=t_tempatundangan type=text value='$dtlksundangan' />";
                                                        }
                                                    ?>                                                    
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div id="div_surattugas" style="display: none;">

                                    </div>
                                    <div id="div_suratdispensasi" style="<?php if($isjenis == 05){echo "display:inherit";}else{echo "display:none";} ?>">
                                        <table id="tbl_dispensasi">
                                            <tr>
                                                <td>
                                                    Dispensasi untuk tanggal
                                                </td>
                                                <td>
                                                    :
                                                </td>
                                                <td>
                                                    <?php
                                                    if($dtiddispensasi == null){
                                                        echo "<input id=txtdate2 name=tgl_dispensasi type=text />";
                                                    }
                                                    else{                                                        
                                                        $fmt = "d/m/Y";
                                                        $tmpdispensasi = strtotime($dttgldispensasi);
                                                        $fixtgldispensasi = date($fmt,$tmpdispensasi);
                                                        echo "<input id=txtdate2 name=tgl_dispensasi type=text value='$fixtgldispensasi' />";
                                                    }
                                                    ?>
                                                    
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Dispensasi Karena
                                                </td>
                                                <td>
                                                    :
                                                </td>
                                                <td>
                                                    <?php
                                                    if($dtiddispensasi == null){
                                                        if($dtabs->num_rows() == NULL) {
                                                            echo "<select>";
                                                            echo "<option>Tidak ada data</option>";
                                                            echo "</select>";
                                                        }
                                                        elseif ($dtabs->num_rows() != NULL) {
                                                            echo "<select id=cmb name=cmb_typeabsen >";
                                                            foreach ($dtabs->result() as $rabs) {
                                                            echo "<option value=$rabs->ID >$rabs->KEPENTINGAN</option>";
                                                        }
                                                        echo "</select>";
                                                        }
                                                    }
                                                    else{
                                                        echo "<select id=cmb name=cmb_typeabsen >";
                                                            foreach ($dtabs->result() as $rabs) {
                                                                echo $dtals1dispensasi;
                                                                if($rabs->ID == $dtals1dispensasi){
                                                                    echo "<option value=$rabs->ID selected=selected >$rabs->KEPENTINGAN</option>";
                                                                }
                                                                else{
                                                                    echo "<option value=$rabs->ID >$rabs->KEPENTINGAN</option>";
                                                                    echo $dtals1dispensasi;
                                                                }
                                                            }
                                                        echo "</select>";
                                                    }
//                                                    ?>                                                    
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top">
                                                    Alasan / Kepentingan
                                                </td>
                                                <td style="vertical-align: top">
                                                    :
                                                </td>
                                                <td>
                                                    <?php
                                                    if($dtiddispensasi == null){
                                                        echo form_textarea($t_ketdispensasi);
                                                    }
                                                    else{
                                                        echo form_textarea($t_ketdispensasi,$dtals2dispensasi);
                                                    }                                                    
                                                    ?>

                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div id="div_balasan" style="display: none;">

                                    </div>
                                    <div id="div_cuti" style="<?php if($isjenis == 07){echo "display:inherit; width: 100%";}else{echo "display:none; width: 100%";} ?> " >
                                        <table id="tbl_cuti" border="0" width="100%">
                                            <tr>
                                                <td>
                                                    Jenis Cuti
                                                </td>
                                                <td>
                                                    :
                                                </td>
                                                <td>
                                                    <?php
                                                    if($dtcutitype->num_rows() == NULL) {
                                                        echo "<select>";
                                                        echo "<option></option>";
                                                        echo "</select>";

                                                    }
                                                    elseif ($dtcutitype->num_rows() != NULL) {
                                                        echo "<select id=cmb name=cmb_ct >";
                                                        foreach ($dtcutitype->result() as $rcuti) {
                                                            echo "<option value=$rcuti->ID>$rcuti->CUTI_TYPE</option>";
                                                        }
                                                        echo "</select>";
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Dari Tanggal
                                                </td>
                                                <td>
                                                    :
                                                </td>
                                                <td>
                                                    <input id="txtdate3" name="tgl_cuti1" type="text" />
                                                </td>
                                                <td>
                                                    Sampai Dengan
                                                </td>
                                                <td>
                                                    :
                                                </td>
                                                <td>
                                                    <input id="txtdate4" name="tgl_cuti2" type="text" /> <input type="button" id="button01" value="Validasi" onclick="xajax_KalkulasiCuti(xajax.getFormValues('frm_surat'))" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Jumlah Hari
                                                </td>
                                                <td>
                                                    :
                                                </td>
                                                <td>
                                                    <input type="text" id="t_jml" name="t_jml" size="10" />
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td id="tbl_main" width="20%">
                                    Klasifikasi Surat
                                </td>
                                <td id="tbl_main" width="1%">
                                    :
                                </td>
                                <td id="tbl_main" width="75%">
                                    <select id="cmb2" name="cmb_klasifikasi">
                                        <?php
                                        if($dtidedit == null){
                                            if($dtklf->num_rows() > 0) {
                                            foreach ($dtklf->result() as $rklf) {
                                                echo "<option value='$rklf->K_KODE' >";
                                                echo $rklf->K_KLASIFIKASI;
                                                echo "</option>";
                                                }
                                            }
                                        }
                                        else{
                                            if($dtklf->num_rows() > 0) {
                                            foreach ($dtklf->result() as $rklf) {
                                                    if($rklf->K_KODE == $isklasifikasi){
                                                        echo "<option value='$rklf->K_KODE' selected=selected >";
                                                        echo $rklf->K_KLASIFIKASI;
                                                        echo "</option>";
                                                    }
                                                    else{
                                                        echo "<option value='$rklf->K_KODE' >";
                                                        echo $rklf->K_KLASIFIKASI;
                                                        echo "</option>";
                                                    }
                                                }
                                            }
                                        }
                                        
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td id="tbl_main" width="20%">
                                    Sifat Surat
                                </td>
                                <td id="tbl_main" width="1%">
                                    :
                                </td>
                                <td id="tbl_main" width="75%">
                                    <select id="cmb3" name="cmb_sft">
                                        <?php
                                        if($dtidedit == null){
                                            if($dtsft->num_rows() > 0) {
                                                foreach ($dtsft->result() as $rsft) {
                                                    echo "<option value='$rsft->S_KODE' >";
                                                    echo $rsft->S_SIFAT;
                                                    echo "</option>";
                                                }                                                
                                            }
                                        }
                                        else{
                                            if($dtsft->num_rows() > 0) {
                                                foreach ($dtsft->result() as $rsft) {
                                                    if($rsft->S_KODE == $issifat){
                                                        echo "<option value='$rsft->S_KODE' selected=selected >";
                                                        echo $rsft->S_SIFAT;
                                                        echo "</option>";
                                                    }
                                                    else{
                                                        echo "<option value='$rsft->S_KODE' >";
                                                        echo $rsft->S_SIFAT;
                                                        echo "</option>";
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td id="tbl_main" width="20%">
                                    Prioritas Surat
                                </td>
                                <td id="tbl_main" width="1%">
                                    :
                                </td>
                                <td id="tbl_main" width="75%">
                                    <select id="cmb4" name="cmb_prior">
                                        <?php
                                        if($dtidedit == null){
                                            if($dtprt->num_rows() > 0 ) {
                                                foreach ($dtprt->result() as $rprt) {
                                                    echo "<option value='$rprt->P_KODE' >";
                                                    echo $rprt->P_PRIORITAS;
                                                    echo "</option>";
                                                }                                                
                                            }                                            
                                        }
                                        else{
                                            if($dtprt->num_rows() > 0 ) {
                                                foreach ($dtprt->result() as $rprt) {
                                                    if($rprt->P_KODE == $isprioritas){
                                                        echo "<option value='$rprt->P_KODE' selected=selected >";
                                                        echo $rprt->P_PRIORITAS;
                                                        echo "</option>";
                                                    }
                                                    else{
                                                        echo "<option value='$rprt->P_KODE' >";
                                                        echo $rprt->P_PRIORITAS;
                                                        echo "</option>";
                                                    }
                                                }
                                            }                                            
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td id="tbl_main" width="20%">
                                    Status Surat
                                </td>
                                <td id="tbl_main" width="1%">
                                    :
                                </td>
                                <td id="tbl_main" width="75%">
                                    <select id="cmb5" name="cmb_state">
                                        <?php
                                        if($dtidedit == null){
                                            if($dtsts->num_rows() > 0 ) {
                                                foreach ($dtsts->result() as $rsts) {
                                                    echo "<option value='$rsts->S_KODE' >";
                                                    echo $rsts->S_STATUS;
                                                    echo "</option>";
                                                }
                                            }
                                        }
                                        else{
                                            if($dtsts->num_rows() > 0 ) {
                                                foreach ($dtsts->result() as $rsts) {
                                                    if($rsts->S_KODE == $isstatus){
                                                        echo "<option value='$rsts->S_KODE' selected=selected >";
                                                        echo $rsts->S_STATUS;
                                                        echo "</option>";
                                                    }
                                                    else{
                                                        echo "<option value='$rsts->S_KODE' >";
                                                        echo $rsts->S_STATUS;
                                                        echo "</option>";
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td id="tbl_main" width="20%">
                                    Status Balasan
                                </td>
                                <td id="tbl_main" width="1%">
                                    :
                                </td>
                                <td id="tbl_main" width="75%">
                                    <select id="cmb6" name="cmb_bls">
                                        <?php
                                        if($dtidedit == null){
                                            if($dtbls->num_rows() > 0 ) {
                                                foreach ($dtbls->result() as $rbls) {
                                                    echo "<option value='$rbls->S_KODE' >";
                                                    echo $rbls->S_BALASAN;
                                                    echo "</option>";
                                                }
                                            }
                                        }
                                        else{
                                            if($dtbls->num_rows() > 0 ) {                                                
                                                foreach ($dtbls->result() as $rbls) {
                                                    if($rbls->S_KODE == $isbalasan){
                                                        echo "<option value='$rbls->S_KODE' selected=selected >";
                                                        echo $rbls->S_BALASAN;
                                                        echo "</option>";
                                                    }
                                                    else{
                                                        echo "<option value='$rbls->S_KODE' >";
                                                        echo $rbls->S_BALASAN;
                                                        echo "</option>";
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td id="tbl_main" width="20%" style="vertical-align: top">
                                    Perihal
                                </td>
                                <td id="tbl_main" width="1%" style="vertical-align: top">
                                    :
                                </td>
                                <td id="tbl_main" width="75%">
                                    <?php
                                    if($dtidedit == null){
                                        echo form_textarea($t_perihal);
                                    }
                                    else{
                                        echo form_textarea($t_perihal,$isperihal);
                                    }                                    
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td id="tbl_main" width="20%" style="vertical-align: top;">
                                    Keterangan
                                </td>
                                <td id="tbl_main" width="1%" style="vertical-align: top;">
                                    :
                                </td>
                                <td id="tbl_main" width="75%" style="vertical-align: top;">
                                    <?php
                                    if($dtidedit == null){
                                        echo form_textarea($t_keterangan);
                                    }
                                    else{
                                        echo form_textarea($t_keterangan,$isketerangan);
                                    }
                                    
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td id="tbl_main" width="20%">
                                    File Lampiran
                                </td>
                                <td id="tbl_main" width="1%">
                                    :
                                </td>
                                <td id="tbl_main" width="75%">
                                    <?php echo form_upload('file_surat','','id=file_up'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td id="tbl_main" width="20%">
                                    Tujuan Surat
                                </td>
                                <td id="tbl_main" width="1%">
                                    :
                                </td>
                                <td id="tbl_main" width="75%" style="vertical-align: middle">
                                    <?php
                                    if($dtidedit == null){
                                        echo "<input id=r_tujuan1 name=r_internal type=radio value=1 onchange=xajax_updatecontent_01(this.value) />Internal <input id=r_tujuan2 name=r_internal type=radio value=2 onclick=xajax_updatecontent_01(this.value)  /> Eksternal";
                                    }
                                    else{
                                        if($istjuan == 1){
                                            echo "<input id=r_tujuan1 name=r_internal type=radio value=1 onchange=xajax_updatecontent_01(this.value) checked />Internal <input id=r_tujuan2 name=r_internal type=radio value=2 onclick=xajax_updatecontent_01(this.value)  /> Eksternal";
                                        }
                                        elseif($istjuan == 2){
                                            echo "<input id=r_tujuan1 name=r_internal type=radio value=1 onchange=xajax_updatecontent_01(this.value) />Internal <input id=r_tujuan2 name=r_internal type=radio value=2 onclick=xajax_updatecontent_01(this.value) checked /> Eksternal";
                                        }                                        
                                    }
                                    ?>                                    
                                </td>
                            </tr>
                            <tr>
                                <td id="tbl_main" width="20%" style="vertical-align: top">
                                    Organisasi / Bagian / Unit Tujuan
                                </td>
                                <td id="tbl_main" width="1%" style="vertical-align: top">
                                    :
                                </td>
                                <td id="idcontent" width="75%" style="vertical-align: top">
                                    
                                </td>
                            </tr>
                            <tr>
                                <td id="tbl_main" width="20%" style="vertical-align: top">
                                    Nama Penerima
                                </td>
                                <td id="tbl_main" width="1%" style="vertical-align: top">
                                    :
                                </td>
                                <td id="td_npenerima" width="75%" style="vertical-align: top">

                                </td>
                            </tr>
                            <tr>
                                <td id="tbl_main" width="20%">

                                </td>
                                <td id="tbl_main" width="1%">

                                </td>
                                <td id="tbl_main" width="75%">

                                </td>
                            </tr>
                            <tr>
                                <td id="tbl_main" width="20%" style="vertical-align:top;">
                                    Organisasi / Bagian / Unit yang mengirim
                                </td>
                                <td id="tbl_main" width="1%" style="vertical-align:top;">
                                    :
                                </td>
                                <td id="tbl_main" width="75%" style="vertical-align:top;">
                                    <div id="div_orgpengirim"></div>
                                </td>
                            </tr>
                            <tr>
                                <td id="tbl_main" width="20%" style="vertical-align:top;">
                                    Nama Pengirim
                                </td>
                                <td id="tbl_main" width="1%" style="vertical-align:top;">
                                    :
                                </td>
                                <td id="tbl_main" width="75%" style="vertical-align:top;">
                                    <div id="div_perspengirim"></div>
                                </td>
                            </tr>
                            <tr>
                                <td id="tbl_main" width="20%" style="vertical-align:top;">

                                </td>
                                <td id="tbl_main" width="1%" style="vertical-align:top;">

                                </td>
                                <td id="tbl_main" width="75%" style="vertical-align:top;">
                                    <div id="div_button">                                        
                                               <input type="button" value="Simpan" id="btn_01" onclick="xajax_SavePenerima(xajax.getFormValues('frm_surat')); xajax_GetListPenerima(xajax.getFormValues('frm_surat'))" />
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td id="tbl_main" width="20%">
                                    <?php
                                    if($dtidpenerima == null){

                                    }
                                    else{
                                        echo "Daftar Penerima";
                                    }
                                    ?>
                                </td>
                                <td id="tbl_main" width="1%">
                                    <?php
                                    if($dtidpenerima == null){

                                    }
                                    else{
                                        echo ":";
                                    }
                                    ?>
                                </td>
                                <td id="tbl_main" width="75%">
                                    <div id="div_daftarpenerima" align="top">
                                        <?php
                                        if($dtidpenerima == null){

                                        }
                                        else{
                                            echo "<ul>";
                                            foreach ($dtidpenerima->result() as $rpenerima){
                                                if($istjuan == 1){
                                                    echo "<li id=list1 > $rpenerima->NAMA_PEGAWAI   - $rpenerima->NAMA_SATKER - <input type=checkbox id=c_del name=c_del value='$rpenerima->ID' onclick=xajax_Hapus_PenerimaSurat(this.value); />Hapus";
                                                }
                                                elseif($istjuan){
                                                    echo "<li id=list1 > $rpenerima->EKS_ORG - $rpenerima->EKS_NAME - <input type=checkbox id=c_del name=c_del value='$rpenerima->ID' onclick=xajax_Hapus_PenerimaSurat(this.value); />Hapus";
                                                }                                                
                                            }
                                            echo "<ul>";
                                        }
                                        ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td id="tbl_main" width="20%" style="vertical-align:top">
                                    <div id="dlistpenerima01" style="vertical-align:top">

                                    </div>
                                </td>
                                <td id="tbl_main" width="1%" style="vertical-align:top">
                                    <div id="dlistpenerima02" style="vertical-align:top">

                                    </div>
                                </td>
                                <td id="tbl_main" width="75%" style="vertical-align:top">
                                    <div id="dlistpenerima03" style="vertical-align:top">

                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td id="tbl_main" width="20%" style="vertical-align:top" colspan="3">
                                    <br/>
                                </td>                                
                            </tr>
                            <tr>
                                <td id="tbl_main" width="100%" style="vertical-align:top" colspan="3" align="center">                                    
                                    <?php echo form_submit("Simpan","Simpan","id=btn_01"); echo form_reset("Reset","Batal","id=btn_01");?>                                    
                                </td>
                            </tr>
                        </table>
                    </fieldset>                    
                </div>
            </div>
        </td>
    </tr>
</table>
<?php
echo form_close();
?>
<style>
#btn_01{
    width: 100px;
}
</style>

<script type="text/javascript">

    var lastsel,lastsel2,lastsel3;

    function setPeserta(iurl)
    {
        jQuery.get(iurl,function(result){
            jQuery('input#pegawai').attr('value',result.id);
            jQuery('input#nip_pegawai').attr('value',result.nip);
            jQuery('input#nama_pegawai').attr('value',result.nama);
        },'json');
    }

    jQuery(document).ready(function()
    {

        $("#tabs").tabs();
        $('#txtdate').datepicker({changeMonth: true, changeYear: true});
        $('#txtdate1').datepicker({changeMonth: true, changeYear: true});
        $('#txtdate2').datepicker({changeMonth: true, changeYear: true});
        $('#txtdate3').datepicker({changeMonth: true, changeYear: true});
        $('#txtdate4').datepicker({changeMonth: true, changeYear: true});
        $('#txtdate5').datepicker({changeMonth: true, changeYear: true});

        $('#tanggal_spt').datepicker({changeMonth: true, changeYear: true});
        $('#dari_tanggal').datepicker({changeMonth: true, changeYear: true});
        $('#sd_tanggal').datepicker({changeMonth: true, changeYear: true});

        var validation = function(){
            //$('#form_sppd').validate({ errorLabelContainer: "#error", wrapper: "li", rules: { tanggal_spt: "required", pejabat_pemberi: "required" } });
            //alert('before submit');
        }

        $('#seek_pejabat').click(function(){
            $('#dialog').dialog('open');
        });

        $("#dialog").dialog({
            bgiframe: true,
            resizable: false,
            height:385,
            width:550,
            modal: true,
            autoOpen: false,
            //buttons: { 'Tutup': function() { $(this).dialog('close'); } }
        });

        $('#simpan').hover(
        function() {
            $(this).addClass("<?php echo $this->css->hover();?>");
        },
        function() {
            $(this).removeClass("<?php echo $this->css->hover();?>");
        }
    );

        $('#simpan').click(function(){
            var frmObj = document.getElementById('form_spt');
            if($(this).attr('title')=='Selesai'){
                if($('#edit').val()!=''){
                    location.href = "<?php echo site_url('sppd/daftar_spt'); ?>";
                }else{
                    $(frmObj).resetForm();
                    $.get('<?php echo site_url('sppd/set_no_spt')?>',function(result){$('#spt_no').val(result)});
                    $('#simpan').html('Simpan<span class="<?php echo $this->css->iconsave();?>"></span>');
                    jQuery('#simpan').attr('title','Simpan');
                    $('#grid_pengikut').setGridParam({url:'<?php echo site_url('sppd/daftar_pengikut')?>'}).trigger('reloadGrid');
                    $("#tabs").tabs('select',0);
                }
            }else{
                jQuery(frmObj).ajaxSubmit({
                    dataType:'json',
                    beforeSubmit:validation,
                    success: function(data){
                        if(data.errors=='' && data.parm!=''){
                            $('#parm').val(data.parm);
                            var old_url = $('#grid_pengikut').getGridParam('url');
                            var new_url = old_url+'/'+$('#parm').val();
                            $('#grid_pengikut').setGridParam({url:new_url}).trigger('reloadGrid');
                        }else{
                            alert(data.errors);
                        }
                        if(!data.state){
                            $("#tabs").tabs('select',1);
                            $('#simpan').html('Selesai<span class="<?php echo $this->css->iconsave();?>"></span>');
                            jQuery('#simpan').attr('title','Selesai');
                        }
                    }
                });
            }
        });

        $('#batal').hover(
        function() {
            $(this).addClass("<?php echo $this->css->hover();?>");
        },
        function() {
            $(this).removeClass("<?php echo $this->css->hover();?>");
        }
    );

        $('#batal').click(function(){
            if($(this).attr('title')=='Kembali'){
                location.href = "<?php echo site_url('sppd/daftar_spt'); ?>";
            }else{
                var parm = $('#parm').val(); var frmObj = document.getElementById('form_spt');
                if(parm!='' && $('#edit').val()==''){
                    //$.get("<?php echo site_url('sppd/batal_spt')?>/"+parm);
                    var data = {}; data.id=parm;
                    $.post("<?php echo site_url('sppd/batal_spt')?>",data);
                    $('#simpan').html('Simpan<span class="<?php echo $this->css->iconsave();?>"></span>');
                    $('#grid_pengikut').setGridParam({url:'<?php echo site_url('sppd/daftar_pengikut')?>'}).trigger('reloadGrid');
                    $.get('<?php echo site_url('sppd/set_no_spt')?>',function(result){$('#spt_no').val(result)});
                }
                $(frmObj).resetForm();
                $('#edit').val('');
                $("#tabs").tabs('select',0);
                jQuery('#simpan').attr('title','Simpan');
            }
        });

        $('#form_spt').ajaxForm(function() {
            alert("Thank you for your comment!");
        });

        $('#form_spt').submit(function() {
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function(data) {
                    alert(data);
                }
            })
            return false;
        });

        $("#pejabat_pemberi").autocomplete("<?php echo site_url('pegawai/get_nama')?>", {
            width: 340, scrollHeight: 220, scroll: true, scrollHeight: 300,	selectFirst: false
        });

        $("#pejabat_pemberi").result(function(event, data, formatted) {
            if(data){
                jQuery('#pejabat_pemberi').val(data[0]);
                tmp = data[1].split('#');
                jQuery('#id_pejabat_pemberi').val(tmp[0]);
                jQuery('#jabatan_pejabat').val(tmp[1]);
            }
        });

        $("#pegawai_penerima").autocomplete("<?php echo site_url('pegawai/get_nama')?>", {
            width: 340, scrollHeight: 220, scroll: true, scrollHeight: 300,	selectFirst: false
        });

        $("#pegawai_penerima").result(function(event, data, formatted) {
            if(data){
                jQuery('#pegawai_penerima').val(data[0]);
                tmp = data[1].split('#');
                jQuery('#id_pegawai_penerima').val(tmp[0]);
                jQuery('#jabatan_pegawai').val(tmp[1]);
            }
        });


        /*jQuery('#form_spt').ajaxSubmit({
                success: function(response){
                        alert(response);
                }
        });*/

        $('#btn_tutup').click(function(){
            $('#dialog').dialog('close');
        });

        jQuery("#grid_pegawai").jqGrid({
            url:'<?php echo site_url('pegawai/get_daftar')?>',
            editurl:'<?php echo site_url('pegawai')?>',
            datatype: "json",
            mtype: 'POST',
            colNames:['ID', 'NIP', 'Nama'],
            colModel:[
                {name:'id',index:'id_pegawai', search:false, hidden:true},
                {name:'nip',index:'nip', width:25, align:"left", editable:true, edittype:'text'},
                {name:'nama',index:'nama_pegawai', width:60, align:"left"},
            ],
            rowNum:10,
            rowList:[10,20,30],
            rownumbers: true,
            pager: '#pager_pegawai',
            sortname: 'a.nip',
            sortorder: "asc",
            viewrecords: true,
            gridview: true,
            multiselect: true,
            multiboxonly: true,
            width:530,
            height:230,
            ondblClickRow: function(id){
                location.href = "<?php echo site_url('pegawai/edit/')."/"; ?>" + id;
            }
        }).navGrid('#pager_pegawai',{edit:false,add:false,del:false,add:false});

        jQuery("#grid_pengikut").jqGrid({
            url:'<?php echo site_url('sppd/daftar_pengikut')?>',
            editurl:'<?php echo site_url('sppd/daftar_pengikut')?>',
            datatype: 'json',
            mtype: 'POST',
            colNames:['id','idfk','pegawai','NIP','Nama'],
            colModel :[
                { name:'id' ,index:'id',search:false },
                { name:'idfk',index:'idfk',hidden:true,editable:true },
                { name:'pegawai',index:'pegawai',hidden:true,editable:true },
                { name:'nip_pegawai',index:'nip_pegawai',width:150,editable:true,edittype:'text', editoptions: {size:40} },
                { name:'nama_pegawai',index:'nama_pegawai',width:300,editable:true,edittype:'text',editrules:{required:true}, editoptions: {size:40} },
                //{ name:'jabatan',index:'jabatan',width:80,editable:true,edittype:'text',editrules:{required:true}, editoptions: {size:40},readonly:true },
            ],
            pager: jQuery('#pager_pengikut'),
            height: 230,
            rowNum:10,
            rowList:[10,20,30],
            rownumbers: true,
            multiselect:true,
            multiboxonly: true,
            altRows:true,
            shrinkToFit:false,
            sortname: 'nama_pegawai',
            sortorder: 'asc',
            viewrecords: true,
            caption: '',
            onSelectRow: function(id){ },
            gridComplete: function(){ jQuery("#grid_pengikut").setGridWidth(530); return true; }

        }).navGrid(
        '#pager_pengikut',
        { add:true,edit:true,del:true},
        { width:600,beforeSubmit:func_before,
            afterShowForm:function(){
                jQuery('input#nip_pegawai').blur( function(){ setPeserta('<?php echo site_url('sppd/info_pengikut/nip')?>/'+this.value); } );
                jQuery('input#nama_pegawai').blur( function(){ setPeserta('<?php echo site_url('sppd/info_pengikut/nama/')?>/'+this.value); } );
                return [true]
            },
        }, // edit,afterSubmit:processEdit,bSubmit:"Ubah",bCancel:"Tutup",

        { width:600,beforeSubmit:func_before,
            afterShowForm:function(){
                jQuery('input#nip_pegawai').blur( function(){ setPeserta('<?php echo site_url('sppd/info_pengikut/nip')?>/'+this.value); } );
                jQuery('input#nama_pegawai').blur( function(){ setPeserta('<?php echo site_url('sppd/info_pengikut/nama/')?>/'+this.value); } );
                return [true]
            },
        }, // add,afterSubmit:processAdd,bSubmit:"Tambah",bCancel:"Tutup",,reloadAfterSubmit:false

        { reloadAfterSubmit:false}, // del,afterSubmit:processDelete
        {}
    ).hideCol(['id']);

        function func_before(a,b){
            a.idfk = $('#parm').val();
            return ['true','true'];
        }

    });
</script>