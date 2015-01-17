<?php 
$id = isset($_POST['nid'])?$_POST['nid']:getFirstKelompok();

$asb = array();
?>
<form action="" method="POST">
        <table>	
		<input type='hidden' name='csubmit' value='new'>
            <tr>
  		    <td><b>Kelompok Kegiatan : &nbsp;</b></td>	
			  <td><select id="nid" name="nid" title="Kelompok" width onchange="ajaxdo_pilih_kelompok(this.value);hapusinfo()">
              <?php echo getKel($id, $indi); 
              ?>                        
  
			  </select>
                </td>			
            </tr>

		</table>
        <br></br>

<table id="htmlTable" class="scroll"></table>
<div id="htmlPager" class="scroll"></div>
<br /><br />

   <div id="container_ss">
    <div id="panel_1" name ="panel_1" style = "display : hide;">
    
    <?php
    
    if(isset($_POST['proses'])) {
             ?>
        
            <div id="panel_2" >
            <?php 
            $asb = getASB2($id);              
            echo TampilASB2($asb); 
            ?></div> <!-- panel_2 -->
        
    </div><!-- panel_1 -->
        <?php
        }  
       
     ?>
    
</div><!-- container -->
<table>
    <tr>
        <td>
            <input type="submit" name="proses" value="Proses" onclick="ajaxdo_proses(document.getElementById('nid').value)"/>
            <input type="button" name="preview" value="Cetak ASB" onclick="printReport()"/>
            <input type="button" name="preview" value="Cetak Daftar ASB" onclick="printDaftar()"/>
        </td>
    </tr>
</table>
</form>