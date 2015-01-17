<script src="<?php echo base_url()?>assets/fr/fastreport.js" type="text/javascript" ></script>
<table class="layout">
    <tr>
        <td><?php $this->load->view('surat/menu'); ?></td>
        <td>
            <div>
                <table id="grid"></table>
                <div id="pager"></div>
                <div class="<?php echo $this->css->panel();?>">
                    <a id="cetak" class="<?php echo $this->css->button();?>"
                       onclick="fastReportStart('Daftar Surat', 'SURAT_NO', 'pdf', 'id=1', 1)">Cetak Daftar Surat
                        <span class="<?php echo $this->css->iconprint();?>"></span>
                    </a>
                </div>
            </div>
        </td>
    </tr>
</table>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery("#grid").jqGrid({
            url:'<?php echo site_url('surat/get_daftar')?>',
            editurl:'<?php echo site_url('surat')?>',
            datatype: "json",
            mtype: 'POST',
            colNames:['ID', 'Nomor Surat', 'Nomor Agenda', 'Tanggal Surat', 'Tanggal Terima','Jenis Surat','Sifat Surat', 'Klasifikasi Surat'],
            colModel:[
                {name:'a.ID',index:'a.ID', width:20, search:false, hidden:true},
                {name:'SURAT_NO',index:'SURAT_NO', width:100, align:"left", editable:false},
                {name:'surat_agenda',index:'surat_agenda', width:100, align:"left", editable:false},
                {name:'surat_agenda',index:'surat_agenda', width:100, align:"left", editable:false},
                {name:'tgl_terima',index:'tgl_terima', width:100, align:"left", editable:false},
                {name:'j_jenis',index:'j_jenis', width:100, align:"left", editable:false},
                {name:'s_sifat',index:'s_sifat', width:100, align:"left", editable:false},
                {name:'k_klasifikasi',index:'k_klasifikasi', width:100, align:"left", editable:false}
            ],
            rowNum:10,
            rowList:[10,20,30],
            rownumbers: true,
            pager: '#pager',
            sortname: 'a.ID',
            sortorder: "asc",
            viewrecords: true,
            gridview: true,
            multiselect: true,
            multiboxonly: true,
            width: 800,
            height: 230,
            caption:"Daftar Surat",
            ondblClickRow: function(id){
                location.href = "<?php echo site_url('surat/suratedit/')."/"; ?>" + id;
            }
        });
        jQuery("#grid").jqGrid('navGrid','#pager',{edit:false,add:false,del:true});

        $("#cetak").hover(
        function() {
            $(this).addClass("<?php echo $this->css->hover();?>");
        },
        function() {
            $(this).removeClass("<?php echo $this->css->hover();?>");
        }
    );
    })
</script>