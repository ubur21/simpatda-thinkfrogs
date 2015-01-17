<?php
class Surat extends Controller {
    
    function Surat() {
        parent::Controller();
        $this->load->model('M_Surat','data_model');
        $this->load->library('jqgridcss','','css');
        $this->load->library('xajax');
        $this->load->library('firephp');
        $this->xajax->registerFunction(array('updatecontent_01',&$this,'updatecontent_01'));
        $this->xajax->registerFunction(array('updatecontent_02',&$this,'updatecontent_02'));
        $this->xajax->registerFunction(array('GetNPenerima',&$this,'GetNPenerima'));
        $this->xajax->registerFunction(array('SavePenerima',&$this,'SavePenerima'));
        $this->xajax->registerFunction(array('GetListPenerima',&$this,'GetListPenerima'));
        $this->xajax->registerFunction(array('GetNPengirim',&$this,'GetNPengirim'));
        $this->xajax->registerFunction(array('GetJenis',&$this,'GetJenis'));
        $this->xajax->registerFunction(array('c_nsurat',&$this,'c_nsurat'));
        $this->xajax->registerFunction(array('c_nagenda',&$this,'c_nagenda'));
        $this->xajax->registerFunction(array('svsrt',&$this,'svsrt'));
        $this->xajax->registerFunction(array('Hapus_PenerimaSurat',&$this,'Hapus_PenerimaSurat'));
        $this->xajax->registerFunction(array('KalkulasiCuti',&$this,'KalkulasiCuti'));
        $this->xajax->registerFunction(array('SaveSurat2',&$this,'SaveSurat2'));
        $this->xajax->processRequest();
    
    }

    function index() {
        $data = array("title"=>"SIP TU - DAFTAR SURAT",
                "main_content"=>"surat/daftar",
                "current_link"=>"daftar",
                "user_data"=>"surat",
                "module"=>"surat");

        $this->session->unset_userdata("IDEDTSAMPING");
        $this->session->unset_userdata("IDEDT");
        $this->load->view('layout/template', $data);
    }

    function daftar() {
        $this->index();
    }

    function get_daftar() {
        $page = $_REQUEST['page']; // get the requested page
        $limit = $_REQUEST['rows']; // get how many rows we want to have into the grid
        $sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort
        $sord = $_REQUEST['sord']; // get the direction if(!$sidx) $sidx =1;

        $req_param = array (
                "sort_by" => $sidx,
                "sort_direction" => $sord,
                "limit" => null,
                "search" => $_REQUEST['_search'],
                "search_field" => isset($_REQUEST['searchField'])?$_REQUEST['searchField']:null,
                "search_operator" => isset($_REQUEST['searchOper'])?$_REQUEST['searchOper']:null,
                "search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null
        );

        $row = $this->data_model->get_data($req_param)->result_array();
        $count = count($row);
        if( $count >0 ) {
            $total_pages = ceil($count/$limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages)
            $page=$total_pages;
        $start = $limit*$page - $limit; // do not put $limit*($page - 1)
        if($start <0) $start = 0;
        $req_param['limit'] = array(
                'start' => $start,
                'end' => $limit
        );


        $result = $this->data_model->get_data($req_param)->result_array();
        // sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
        $response->page = $page;
        $response->total = $total_pages;
        $response->records = $count;

        for($i=0; $i<count($result); $i++) {
            $response->rows[$i]['id']=$result[$i]['ID'];
            // data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
            $response->rows[$i]['cell']=array($result[$i]['ID'],
                    $result[$i]['SURAT_NO'],
                    $result[$i]['SURAT_AGENDA'],
                    $result[$i]['SURAT_TANGGAL'],
                    $result[$i]['TGL_TERIMA'],
                    $result[$i]['J_JENIS'],
                    $result[$i]['S_SIFAT'],
                    $result[$i]['K_KLASIFIKASI']

            );
        }
        echo json_encode($response);
    }

    function tambah() {
        //$this->session->unset_userdata("IDEDTSAMPING");
        $this->session->unset_userdata("IDSRT");
        $id = $this->uri->segment(3);
        $this->firephp->log($id);
        $this->load->model("M_Surat");
        $data = array("title"=>"SIP TU - Surat - Tambah Surat",
                "sub_menu"=>"surat/menu",
                "main_content"=>"surat/form",
                "act"=>"tambah",
                "form_act"=>"",
                "user_data"=>"surat",
                "module"=>"surat",
                "current_link"=>"tambah",
                "dt_instansi"  => $this->M_Surat->GetInstansiList(),
                "xajax_js"     => $this->xajax->getJavascript("../../scripts/js/"),
                "dtjns"        => $this->M_Surat->GetJenisSuratList(),
                "dtklf"        => $this->M_Surat->GetKlasifikasiList(),
                "dtsft"        => $this->M_Surat->GetSifatList(),
                "dtprt"        => $this->M_Surat->GetPrioritasList(),
                "dtsts"        => $this->M_Surat->GetStatusList(),
                "dtbls"        => $this->M_Surat->GetBalasanList(),
                "dtpgd"        => $this->M_Surat->GetAllTipePengaduan(),
                "dtabs"      => $this->M_Surat->GetTypeAbsens(),
                "dtcutitype"   => $this->M_Surat->GetTypeCuti(),
                "dtedit" =>null,
                "dtlistpenerima" =>null,
                "dtpengaduan"=>null,
                "dtundangan"=>null,
                "dtdispensasi"=>null);
        $data ["t_perihal"]  = array('name' => 't_perihal','id'=>'tp','cols'=>'50','rows'=>'3');
        $data ["t_keterangan"]  = array('name' => 't_keterangan','id'=>'tk','cols'=>'50','rows'=>'3');
        $data ["t_ketpenganduan"]  = array('name' => 't_ketpgd','id'=>'pgdt','cols'=>'50','rows'=>'3');
        $data ["t_ketdispensasi"]  = array('name' => 't_ketdsp','id'=>'pgdp','cols'=>'50','rows'=>'3');
        $this->load->view('layout/template', $data);
    }

    function updatecontent_01($kode) {
        $objResponse = new xajaxResponse();
        if($kode == "1") {
            $objResponse = new xajaxResponse();
            $content = $this->BuildOrgnisasiList();
            $content2 = $this->GetOrganisasiPengirimList();            
            //$contentbutton = "<input type='button' value='Simpan' id=btn_01 onclick='xajax_SavePenerima(xajax.getFormValues(frm_surat)); xajax_GetListPenerima(xajax.getFormValues('frm_surat'));' />";
            //$contentbutton .= "<input type='button' value='Hapus' id=btn_01 onclick='xajax_Hapus_PenerimaSurat(xajax.getFormValues(frm_surat)); xajax_GetListPenerima(xajax.getFormValues(frm_surat));' />";
            $objResponse->Assign("idcontent","innerHTML", $content);
            $objResponse->Assign("div_orgpengirim","innerHTML", $content2);
            $objResponse->Assign("td_npenerima", "innerHTML","");
            $objResponse->Assign("div_perspengirim", "innerHTML","");
            //$objResponse->Assign("div_button", "innerHTML",$contentbutton);
            $this->session->set_userdata('sesasal',1);
        }
        elseif($kode == "2") {
            $content = $this->BuildOrgnisasiList();
            $contentpenerima = "<input id='txtbox' name='t_npenerima' type='text' />";
            $contentpenerima .= "<input type='submit' value='Simpan' id=btn_01 />";
            $contentpenerima .= "<input type='submit' value='Hapus' id=btn_01 />";
            $contentpengirim1 = "<input id='txtbox' name='t_orgpengirim' type='text' />";
            $contentpengirim2 = "<input id='txtbox' name='t_perspengirim' type='text' />";            
            //$contentbutton = "<input type='button' value='Simpan' id=btn_01 onclick='xajax_SavePenerima(xajax.getFormValues(frm_surat)); xajax_GetListPenerima(xajax.getFormValues('frm_surat'));' />";
            //$contentbutton .= "<input type='button' value='Hapus' id=btn_01 onclick='xajax_Hapus_PenerimaSurat(xajax.getFormValues(frm_surat)); xajax_GetListPenerima(xajax.getFormValues(frm_surat));' />";
            $objResponse->Assign("idcontent","innerHTML", $contentpengirim1);
            $objResponse->Assign("td_npenerima", "innerHTML",$contentpengirim2);
            $objResponse->Assign("div_orgpengirim","innerHTML", $content);
            $objResponse->Assign("div_perspengirim","innerHTML", "");
            //$objResponse->Assign("div_button", "innerHTML",$contentbutton);
            $this->session->set_userdata('sesasal',2);
        }
        return $objResponse;
    }

    function GetNPenerima($kode) {
        $objResponse = new xajaxResponse();
        if($this->session->userdata('sesasal')==1) {
            if($kode != '0') {
                $contentpenerima = $this->BuildPenerimaList($kode);
                $objResponse->Assign("td_npenerima", "innerHTML",$contentpenerima );
            }
            else {
                $objResponse->Assign("td_npenerima", "innerHTML","");
            }
        }
        elseif($this->session->userdata('sesasal')==2) {
            if($kode != '0') {
                $contentpenerima = $this->BuildPenerimaList($kode);
                $objResponse->Assign("div_perspengirim", "innerHTML",$contentpenerima );
            }
            else {
                $objResponse->Assign("div_perspengirim", "innerHTML","");
            }
        }

        return $objResponse;
    }

    function GetNPengirim($kode) {
        $objResponse = new xajaxResponse();
        if($kode != '0') {
            $contentpenerima = $this->BuildPengirimList($kode);
            $objResponse->Assign("div_perspengirim", "innerHTML",$contentpenerima );
        }
        else {
            $objResponse->Assign("div_perspengirim", "innerHTML","");
        }
        return $objResponse;
    }

    function BuildOrgnisasiList() {
        $this->load->model("M_Surat");
        $query = $this->M_Surat->GetInstansiList();
        $OutPut = "<select id='dr_organisasi' name='dr_org[]' onchange='xajax_GetNPenerima(this[this.selectedIndex].value)'>";
        $OutPut .= $this->new_option('Silahkan Pilih','0');
        if($query->num_rows() > 0 ) {
            foreach ($query->result() as $row) {
                $OutPut .= $this->new_option($row->NAMA_SATKER,$row->ID_SATKER);
            }
        }
        $OutPut .= "</select>";
        return $OutPut;
    }

    function BuildPenerimaList($kode) {
        $this->load->model("M_Surat");
        $query = $this->M_Surat->GetUserByInstansi($kode);
        $OutPut = "<table>";
        $OutPut .= "<tr>";
        $OutPut .= "<td>";
        $OutPut .= "<select id='dr_anggotaorganisasi' name='t_npenerima[]' >";
        $OutPut .= $this->new_option('Silahkan Pilih','0');
        if($query->num_rows() > 0 ) {
            foreach ($query->result() as $row) {
                $OutPut .= $this->new_option($row->NAMA_PEGAWAI,$row->NIP);
            }
        }
        $OutPut .= "</select>";
        $OutPut .= "</td>";
        $OutPut .= "</tr>";
        $OutPut .= "</table>";
        return $OutPut;
    }

    function BuildPengirimList($kode) {
        $this->load->model("M_Surat");
        $query = $this->M_Surat->GetUserByInstansi($kode);
        $OutPut = "<table>";
        $OutPut .= "<tr>";
        $OutPut .= "<td>";
        $OutPut .= "<select id='dr_anggotaorganisasi' name='t_npengirim[]' >";
        $OutPut .= $this->new_option('Silahkan Pilih','0');
        if($query->num_rows() > 0 ) {
            foreach ($query->result() as $row) {
                $OutPut .= $this->new_option($row->NAMA_PEGAWAI,$row->NIP);
            }
        }
        $OutPut .= "</select>";
        $OutPut .= "</td>";
        $OutPut .= "</tr>";
        $OutPut .= "</table>";
        return $OutPut;
    }

    function GetOrganisasiPengirimList() {
        $query = $this->M_Surat->GetInstansiList();
        $OutPut = "<select id='dr_organisasi' name='t_orpengirim[]' onchange='xajax_GetNPengirim(this[this.selectedIndex].value)'>";
        $OutPut .= $this->new_option('Silahkan Pilih','0');
        if($query->num_rows() > 0 ) {
            foreach ($query->result() as $row) {
                $OutPut .= $this->new_option($row->NAMA_SATKER,$row->ID_SATKER);
            }
        }
        $OutPut .= "</select>";
        return $OutPut;
    }

    function new_option($text, $value) {
        $output = "<option value=\"" . $value . "\"";
        $output .= ">" . $text . "</option>";

        return ($output);
    }

    function new_li($text1,$text2,$text3) {
        $output = "<a href=></a>";
        $output = "<li id=list1 >" . $text1 ."   -   ". $text2 . "   -   "."<input type='checkbox' id='c_del' name='c_del' value='$text3' onclick=xajax_Hapus_PenerimaSurat(this.value); />Hapus";
        return ($output);
    }

    function SavePenerima($form_data) {
        $this->load->library('encrypt');
        $this->load->model('M_Surat');
        $objResponse = new xajaxResponse();
        $stateform = $form_data['r_internal'][0];

        if($stateform == 1) {
            $save = array('NIP_PENERIMA' => $form_data['t_npenerima'][0],
                    'INSTANSI_PENERIMA' => $form_data['dr_org'][0],
                    'NIP_PENGIRIM' => $form_data['t_npengirim'][0],
                    'INSTANSI_PENGIRIM' => $form_data['t_orpengirim'][0],
                    'SURAT_ID'=> $this->session->userdata("IDSRT"));
            $this->db->insert("SURAT_PENERIMA",$save);
        }
        elseif($stateform == 2) {
            $save = array('NIP_PENGIRIM' => $form_data['t_npenerima'][0],
                    'INSTANSI_PENGIRIM' => $form_data['dr_org'][0],                    
                    'EKS_ORG' => $form_data['t_perspengirim'],
                    'EKS_NAME' => $form_data['t_orgpengirim'],
                    'SURAT_ID'=>$this->session->userdata("IDSRT"));
            $this->db->insert("SURAT_PENERIMA",$save);
        }
        return $objResponse;
    }

    function GetListPenerima($form_data) {
        $objresponse = new xajaxResponse();
        $key = $this->session->userdata("IDSRT");
        $content1 = "Daftar Penerima";
        $content2 = ":";        
        $content3 = $this->BuildListPenerima($key);        
        $objresponse->Assign("dlistpenerima01", "innerHTML",$content1);
        $objresponse->Assign("dlistpenerima02", "innerHTML",$content2);
        $objresponse->Assign("dlistpenerima03", "innerHTML",$content3);
        return $objresponse;
    }

    function BuildListPenerima($id) {
        $this->load->model("M_Surat");
        $query = $this->M_Surat->GetDaftarPenerima($id);        
        if($this->session->userdata('sesasal')== 1) {
            $out = "<ul>";
            foreach ($query->result_array() as $row) {
                $out .= $this->new_li($row['NAMA_PEGAWAI'], $row['NAMA_SATKER'], $row['ID']);
            }
            $out .= "</ul>";
            return $out;
        }
        elseif($this->session->userdata('sesasal')== 2) {
            $out = "<ul>";
            foreach ($query->result_array() as $row) {
                $out .= $this->new_li($row['EKS_ORG'], $row['EKS_NAME'], $row['ID']);
            }
            $out .= "</ul>";
            return $out;
        }
        $this->firephp->log($out);
    }
    // End of Prosedur AJAX Penerima dan Pengirim

    //Begin if Prosedure Jenis Surat

    function GetJenis($kode) {
        $respdrobj = new xajaxResponse();
        if($kode == "00") {
            $respdrobj->assign("div_undangan", "style.display", "none");
            $respdrobj->assign("div_pengaduan", "style.display", "none");
            $respdrobj->assign("div_suratdispensasi", "style.display", "none");
            $respdrobj->assign("div_cuti", "style.display", "none");
        }
        // Surat Undangan
        elseif($kode == "02") {
            $respdrobj->assign("div_undangan", "style.display", "none");
            $respdrobj->assign("div_pengaduan", "style.display", "inherit");
            $respdrobj->assign("div_suratdispensasi", "style.display", "none");
            $respdrobj->assign("div_cuti", "style.display", "none");
        }
        elseif($kode == "03") {
            $respdrobj->assign("div_undangan", "style.display", "inherit");
            $respdrobj->assign("div_pengaduan", "style.display", "none");
            $respdrobj->assign("div_suratdispensasi", "style.display", "none");
            $respdrobj->assign("div_cuti", "style.display", "none");
        }
        elseif($kode == "04") {
            $respdrobj->assign("div_undangan", "style.display", "none");
            $respdrobj->assign("div_pengaduan", "style.display", "none");
            $respdrobj->assign("div_suratdispensasi", "style.display", "none");
            $respdrobj->assign("div_cuti", "style.display", "none");
        }
        elseif($kode == "05") {
            $respdrobj->assign("div_undangan", "style.display", "none");
            $respdrobj->assign("div_pengaduan", "style.display", "none");
            $respdrobj->assign("div_suratdispensasi", "style.display", "inherit");
            $respdrobj->assign("div_cuti", "style.display", "none");
        }
        elseif($kode == "06") {
            $respdrobj->assign("div_undangan", "style.display", "none");
            $respdrobj->assign("div_pengaduan", "style.display", "none");
            $respdrobj->assign("div_suratdispensasi", "style.display", "none");
            $respdrobj->assign("div_cuti", "style.display", "none");
        }
        elseif($kode == "07") {
            $respdrobj->assign("div_undangan", "style.display", "none");
            $respdrobj->assign("div_pengaduan", "style.display", "none");
            $respdrobj->assign("div_suratdispensasi", "style.display", "none");
            $respdrobj->assign("div_cuti", "style.display", "inherit");
        }
        else {
            $respdrobj->assign("div_undangan", "style.display", "none");
            $respdrobj->assign("div_pengaduan", "style.display", "none");
            $respdrobj->assign("div_suratdispensasi", "style.display", "none");
            $respdrobj->assign("div_cuti", "style.display", "none");
        }
        return $respdrobj;
    }
    //End of prosedure jenis surat

    function c_nsurat($form_data) {
        $this->load->model("M_Surat");
        $objresponse = new xajaxResponse();
        $key = $form_data['t_nsurat'];
        $this->firephp->log($key);
        $content3 = $this->GetIDSurat($key);
        if($content3 == NULL) {
            $objresponse->assign("lblnsurat", "innerHTML", "Nomor belum gunakan");
            $objresponse->assign("lblnsurat", "style.color", "green");
            $objresponse->assign("tblnsurat", "style.display", "inherit");
        }
        elseif($content3 != NULL) {
            $objresponse->assign("lblnsurat", "innerHTML", "Nomor sudah digunakan");
            $objresponse->assign("lblnsurat", "style.color", "red");
            $objresponse->assign("tblnsurat", "style.display", "none");
        }
        return $objresponse;
    }

    function c_nagenda($form_data) {
        $objresponse = new xajaxResponse();
        $key = $form_data['t_nagenda'];
        $content3 = $this->GetIDAgenda($key);
        if($content3 == NULL) {
            $objresponse->assign("lblnagenda", "innerHTML", "Nomor belum gunakan");
            $objresponse->assign("lblnagenda", "style.color", "green");
        }
        elseif($content3 != NULL) {
            $objresponse->assign("lblnagenda", "innerHTML", "Nomor sudah digunakan!");
            $objresponse->assign("lblnagenda", "style.color", "red");
        }
        return $objresponse;
    }

    function GetIDSurat($id) {
        $this->load->model("M_Surat");
        $query = $this->M_Surat->GetSuratByNomor($id);
        $this->firephp->log($query->num_rows(), 'jumlah');
        $output = NULL;
        if($query->num_rows() == NULL) {
            $output = NULL;
            $this->firephp->log('output null');
        }
        else {
            $this->firephp->log($query, 'data');
            $this->firephp->log($query->result_array(), 'data array');
            $this->firephp->log($query->result_object(), 'data object');
            foreach ($query->result_array() as $row) {
                $this->firephp->log($row, 'row');
                $output = $row['ID'];
                $this->firephp->log($output, 'output');
            }
        }
        $this->firephp->log($output);
        return $output;
    }

    function GetIDAgenda($id) {
        $this->load->model("M_Surat");
        $query = $this->M_Surat->GetSuratByAgenda($id);
        $output = NULL;
        if($query->num_rows() == NULL) {
            $output = NULL;
        }
         else {            
            foreach ($query->result_array() as $row) {               
                $output = $row['ID'];               
            }
        }

//        elseif ($query->num_rows() != NULL) {
//            foreach ($query->result() as $row) {
//                $output = $row->id;
//            }
//        }
        return $output;
    }

    function svsrt($form_data) {
        $this->load->model('M_Surat');
        $objresponse = new xajaxResponse();
        $save = array("surat_no"=>$form_data["t_nsurat"],
                "U_ID"=>"none",
                "IS_DELETE"=> "1");
        $this->db->insert("SURAT",$save);

        //$this->M_Surat->fill_nomorsurat($form_data);
        //$this->M_Surat->insert_nomor();

        $key = $form_data['t_nsurat'];
        $content3 = $this->GetIDSurat($form_data['t_nsurat']);
        $objresponse->assign("lblnsurat", "innerHTML", "");
        $objresponse->assign("tblnsurat", "style.display", "none");
        return $objresponse;
    }

    function Hapus_PenerimaSurat($id) {
        $objresponse = new xajaxResponse();
        $this->load->model("M_Surat");
        //$id = $form_data['c_del'];
        $this->M_Surat->DeletePenerima($id);
        $key = $this->session->userdata("IDSRT");
        $content = $this->BuildListPenerima($key);
        $objresponse->Assign("dlistpenerima03", "innerHTML",$content);
        return $objresponse;
    }

    function SaveSurat1() {
        $this->load->library("encrypt");
        $fmt = "Y-m-d";
        $tgl1 = strtotime($this->input->post("t_ntanggal"));
        $tgl2 = strtotime($this->input->post("tgl_undangan"));
        $tgl3 = strtotime($this->input->post("tgl_dispensasi"));
        $tgl4 = strtotime($this->input->post("t_ntanggaltrima"));
        $fixfmt = date($fmt, $tgl1);
        $fixudg = date($fmt, $tgl2);
        $fixdsp = date($fmt, $tgl3);
        $fixtrima = date($fmt,$tgl4);

        $dtnomor = $this->input->post("t_nsurat");
        $jns = $this->input->post("cmb_jns");

        if($jns == "00") {

        }
        elseif($jns == "02") {
            // SAVE SURAT PENGADUAN
            $savepengaduan = array("surat_id"=>$this->session->userdata("IDSRT"),
                    "pengaduan"=>$this->input->post("t_pgd"),
                    "keterangan"=>$this->input->post("t_ketpgd"));
            $save = array("surat_no"=>$this->input->post("t_nsurat"),
                    "surat_agenda"=>$this->input->post("t_nagenda"),
                    "surat_tanggal"=>implode("-",array_reverse(explode("/",$this->input->post('t_ntanggal')))),
                    "surat_tanggal_terima"=>implode("-",array_reverse(explode("/",$this->input->post('t_ntanggaltrima')))),
                    "surat_perihal"=>$this->input->post("t_perihal"),
                    "surat_asal"=>$this->input->post("r_internal"),
                    "surat_jenis"=>$this->input->post("cmb_jns"),
                    "surat_klasifikasi"=>$this->input->post("cmb_klasifikasi"),
                    "surat_sifat"=>$this->input->post("cmb_sft"),
                    "surat_prioritas"=>$this->input->post("cmb_prior"),
                    "surat_status"=>$this->input->post("cmb_state"),
                    "surat_balasan"=>$this->input->post("cmb_bls"),
                    "surat_keterangan"=>$this->input->post("t_keterangan"));

            $this->db->insert("surat_pengaduan",$savepengaduan);
            $this->db->where("id",$this->session->userdata("IDSRT"));
            $this->db->update("surat",$save);

            $this->session->unset_userdata("IDSRT");
            redirect("surat/tambah","refresh");
        }
        elseif($jns == "03") {
            // SAVE SURAT UNDANGAN
            $saveudg = array("surat_id"=>$this->session->userdata("IDSRT"),
                    "tgl_udg"=>implode("-",array_reverse(explode("/",$this->input->post('tgl_undangan')))),
                    "waktu"=>$this->input->post("wkt_undangan"),
                    "tmp"=>$this->input->post("t_tempatundangan"));

            $save = array("surat_no"=>$this->input->post("t_nsurat"),
                    "surat_agenda"=>$this->input->post("t_nagenda"),
                    "surat_tanggal"=>implode("-",array_reverse(explode("/",$this->input->post('t_ntanggal')))),
                    "surat_tanggal_terima"=>implode("-",array_reverse(explode("/",$this->input->post('t_ntanggaltrima')))),
                    "surat_perihal"=>$this->input->post("t_perihal"),
                    "surat_asal"=>$this->input->post("r_internal"),
                    "surat_jenis"=>$this->input->post("cmb_jns"),
                    "surat_klasifikasi"=>$this->input->post("cmb_klasifikasi"),
                    "surat_sifat"=>$this->input->post("cmb_sft"),
                    "surat_prioritas"=>$this->input->post("cmb_prior"),
                    "surat_status"=>$this->input->post("cmb_state"),
                    "surat_balasan"=>$this->input->post("cmb_bls"),
                    "surat_keterangan"=>$this->input->post("t_keterangan"));

            $this->db->insert("surat_undangan",$saveudg);
            $this->db->where("id",$this->session->userdata("IDSRT"));
            $this->db->update("surat",$save);

            $this->session->unset_userdata("IDSRT");
            redirect("surat/tambah","refresh");
        }
        elseif($jns == "05") {
            // SAVE SURAT DISPENSASI
            $savedispensasi = array("surat_id"=>$this->session->userdata("IDSRT"),
                    "tgl_dispensasi"=>implode("-",array_reverse(explode("/",$this->input->post('tgl_dispensasi')))),
                    "type_absensi"=>$this->input->post("cmb_typeabsen"),
                    "alasan"=>$this->input->post("t_ketdsp"));

            $save = array("surat_no"=>$this->input->post("t_nsurat"),
                    "surat_agenda"=>$this->input->post("t_nagenda"),
                    "surat_tanggal"=>implode("-",array_reverse(explode("/",$this->input->post('t_ntanggal')))),
                    "surat_tanggal_terima"=>implode("-",array_reverse(explode("/",$this->input->post('t_ntanggaltrima')))),
                    "surat_perihal"=>$this->input->post("t_perihal"),
                    "surat_asal"=>$this->input->post("r_internal"),
                    "surat_jenis"=>$this->input->post("cmb_jns"),
                    "surat_klasifikasi"=>$this->input->post("cmb_klasifikasi"),
                    "surat_sifat"=>$this->input->post("cmb_sft"),
                    "surat_prioritas"=>$this->input->post("cmb_prior"),
                    "surat_status"=>$this->input->post("cmb_state"),
                    "surat_balasan"=>$this->input->post("cmb_bls"),
                    "surat_keterangan"=>$this->input->post("t_keterangan"));

            $this->db->insert("surat_dispensasi",$savedispensasi);
            $this->db->where("id",$this->session->userdata("IDSRT"));
            $this->db->update("surat",$save);

            $this->session->unset_userdata("IDSRT");
            redirect("surat/tambah","refresh");

        }
        elseif ($jns == "07") {
            // SAVE SURAT PERMOHONAN CUTI
            $fmtct = "Y-m-d";
            $tglct1 = strtotime($this->input->post("tgl_cuti1"));
            $tglct2 = strtotime($this->input->post("tgl_cuti2"));

            $tglct3 = idate($this->input->post("tgl_cuti1"));
            $tglct4 = idate($this->input->post("tgl_cuti2"));

            $fixtglct1 = date($fmtct,$tglct1);
            $fixtglct2 = date($fmtct,$tglct2);

            $jumlahct = $tglct4 - $tglct3;

            $savecuti = array("surat_id"=>$this->session->userdata("IDSRT"),
                    "j_cuti"=>$this->input->post("cmb_ct"),
                    "cuti1"=>implode("-",array_reverse(explode("/",$this->input->post('tgl_cuti1')))),
                    "cuti2"=>implode("-",array_reverse(explode("/",$this->input->post('tgl_cuti2')))),
                    "jumlah"=>$jumlahct,
                    "is_approve"=> 0);

            $save = array("surat_no"=>$this->input->post("t_nsurat"),
                    "surat_agenda"=>$this->input->post("t_nagenda"),
                    "surat_tanggal"=>implode("-",array_reverse(explode("/",$this->input->post('t_ntanggal')))),
                    "surat_tanggal_terima"=>implode("-",array_reverse(explode("/",$this->input->post('t_ntanggaltrima')))),
                    "surat_perihal"=>$this->input->post("t_perihal"),
                    "surat_asal"=>$this->input->post("r_internal"),
                    "surat_jenis"=>$this->input->post("cmb_jns"),
                    "surat_klasifikasi"=>$this->input->post("cmb_klasifikasi"),
                    "surat_sifat"=>$this->input->post("cmb_sft"),
                    "surat_prioritas"=>$this->input->post("cmb_prior"),
                    "surat_status"=>$this->input->post("cmb_state"),
                    "surat_balasan"=>$this->input->post("cmb_bls"),
                    "surat_keterangan"=>$this->input->post("t_keterangan"));

            $this->db->insert("surat_cuti",$savecuti);
            $this->db->where("id",$this->session->userdata("IDSRT"));
            $this->db->update("surat",$save);

            $this->session->unset_userdata("IDSRT");
            redirect("surat/tambah","refresh");
        }
        else {

            $save = array("surat_no"=>$this->input->post("t_nsurat"),
                    "surat_agenda"=>$this->input->post("t_nagenda"),
                    "surat_tanggal"=>implode("-",array_reverse(explode("/",$this->input->post('t_ntanggal')))),
                    "surat_tanggal_terima"=>implode("-",array_reverse(explode("/",$this->input->post('t_ntanggaltrima')))),
                    "surat_perihal"=>$this->input->post("t_perihal"),
                    "surat_asal"=>$this->input->post("r_internal"),
                    "surat_jenis"=>$this->input->post("cmb_jns"),
                    "surat_klasifikasi"=>$this->input->post("cmb_klasifikasi"),
                    "surat_sifat"=>$this->input->post("cmb_sft"),
                    "surat_prioritas"=>$this->input->post("cmb_prior"),
                    "surat_status"=>$this->input->post("cmb_state"),
                    "surat_balasan"=>$this->input->post("cmb_bls"),
                    "surat_keterangan"=>$this->input->post("t_keterangan"));

            $this->db->where("id",$this->session->userdata("IDSRT"));
            $this->db->update("surat",$save);

            $this->session->unset_userdata("IDSRT");
            redirect("surat/tambah","refresh");
        }
    }

    function SaveSurat2() {
        $this->load->library("encrypt");
        $fmt = "Y-m-d";
        $tgl1 = strtotime($this->input->post("t_ntanggal"));
        $tgl2 = strtotime($this->input->post("tgl_undangan"));
        $tgl3 = strtotime($this->input->post("tgl_dispensasi"));
        $tgl4 = strtotime($this->input->post("t_ntanggaltrima"));
        $fixfmt = date($fmt, $tgl1);
        $fixudg = date($fmt, $tgl2);
        $fixdsp = date($fmt, $tgl3);
        $fixtrima = date($fmt, $tgl4);

        $dtnomor = $this->input->post("t_nsurat");
        $jns = $this->input->post("cmb_jns");

        if($jns == "00") {

        }
        elseif($jns == "02") {
            // SAVE SURAT PENGADUAN
            $savepengaduan = array("surat_id"=>$this->session->userdata("IDSRT"),
                    "pengaduan"=>$this->input->post("t_pgd"),
                    "keterangan"=>$this->input->post("t_ketpgd"));
            $save = array("surat_no"=>$this->input->post("t_nsurat"),
                    "surat_agenda"=>$this->input->post("t_nagenda"),                    
                    "surat_tanggal"=>implode("-",array_reverse(explode("/",$this->input->post('t_ntanggal')))),
                    "surat_tanggal_terima"=>implode("-",array_reverse(explode("/",$this->input->post('t_ntanggaltrima')))),
                    "surat_perihal"=>$this->input->post("t_perihal"),
                    "surat_asal"=>$this->input->post("r_internal"),
                    "surat_jenis"=>$this->input->post("cmb_jns"),
                    "surat_klasifikasi"=>$this->input->post("cmb_klasifikasi"),
                    "surat_sifat"=>$this->input->post("cmb_sft"),
                    "surat_prioritas"=>$this->input->post("cmb_prior"),
                    "surat_status"=>$this->input->post("cmb_state"),
                    "surat_balasan"=>$this->input->post("cmb_bls"),
                    "surat_keterangan"=>$this->input->post("t_keterangan"));

            $this->db->where("id",$this->session->userdata("IDEDTSAMPING"));
            $this->db->update("surat_pengaduan",$savepengaduan);
            
            $this->db->where("id",$this->session->userdata("IDSRT"));
            $this->db->update("surat",$save);

            $this->session->unset_userdata("IDEDTSAMPING");
            $this->session->unset_userdata("IDSRT");
            redirect("surat/tambah","refresh");
        }
        elseif($jns == "03") {
            // SAVE SURAT UNDANGAN
            $saveudg = array("surat_id"=>$this->session->userdata("IDSRT"),
                    "tgl_udg"=>implode("-",array_reverse(explode("/",$this->input->post('tgl_undangan')))),
                    "waktu"=>$this->input->post("wkt_undangan"),
                    "tmp"=>$this->input->post("t_tempatundangan"));

            $save = array("surat_no"=>$this->input->post("t_nsurat"),
                    "surat_agenda"=>$this->input->post("t_nagenda"),
                    "surat_tanggal"=>implode("-",array_reverse(explode("/",$this->input->post('t_ntanggal')))),
                    "surat_tanggal_terima"=>implode("-",array_reverse(explode("/",$this->input->post('t_ntanggaltrima')))),
                    "surat_perihal"=>$this->input->post("t_perihal"),
                    "surat_asal"=>$this->input->post("r_internal"),
                    "surat_jenis"=>$this->input->post("cmb_jns"),
                    "surat_klasifikasi"=>$this->input->post("cmb_klasifikasi"),
                    "surat_sifat"=>$this->input->post("cmb_sft"),
                    "surat_prioritas"=>$this->input->post("cmb_prior"),
                    "surat_status"=>$this->input->post("cmb_state"),
                    "surat_balasan"=>$this->input->post("cmb_bls"),
                    "surat_keterangan"=>$this->input->post("t_keterangan"));

            $this->db->where("id",$this->session->userdata("IDEDTSAMPING"));
            $this->db->update("surat_undangan",$saveudg);
            $this->db->where("id",$this->session->userdata("IDSRT"));
            $this->db->update("surat",$save);

            $this->session->unset_userdata("IDEDTSAMPING");
            $this->session->unset_userdata("IDSRT");
            redirect("surat/tambah","refresh");
        }
        elseif($jns == "05") {
            // SAVE SURAT DISPENSASI
            $savedispensasi = array("surat_id"=>$this->session->userdata("IDSRT"),
                    "tgl_dispensasi"=>implode("-",array_reverse(explode("/",$this->input->post('tgl_dispensasi')))),
                    "type_absensi"=>$this->input->post("cmb_typeabsen"),
                    "alasan"=>$this->input->post("t_ketdsp"));

            $save = array("surat_no"=>$this->input->post("t_nsurat"),
                    "surat_agenda"=>$this->input->post("t_nagenda"),
                    "surat_tanggal"=>implode("-",array_reverse(explode("/",$this->input->post('t_ntanggal')))),
                    "surat_tanggal_terima"=>implode("-",array_reverse(explode("/",$this->input->post('t_ntanggaltrima')))),
                    "surat_perihal"=>$this->input->post("t_perihal"),
                    "surat_asal"=>$this->input->post("r_internal"),
                    "surat_jenis"=>$this->input->post("cmb_jns"),
                    "surat_klasifikasi"=>$this->input->post("cmb_klasifikasi"),
                    "surat_sifat"=>$this->input->post("cmb_sft"),
                    "surat_prioritas"=>$this->input->post("cmb_prior"),
                    "surat_status"=>$this->input->post("cmb_state"),
                    "surat_balasan"=>$this->input->post("cmb_bls"),
                    "surat_keterangan"=>$this->input->post("t_keterangan"));

            $this->db->where("id",$this->session->userdata("IDEDTSAMPING"));
            $this->db->update("surat_dispensasi",$savedispensasi);
            $this->db->where("id",$this->session->userdata("IDSRT"));
            $this->db->update("surat",$save);

            $this->session->unset_userdata("IDEDTSAMPING");
            $this->session->unset_userdata("IDSRT");
            redirect("surat/tambah","refresh");

        }
        elseif ($jns == "07") {
            // SAVE SURAT PERMOHONAN CUTI
            $fmtct = "Y-m-d";
            $tglct1 = strtotime($this->input->post("tgl_cuti1"));
            $tglct2 = strtotime($this->input->post("tgl_cuti2"));

            $tglct3 = idate($this->input->post("tgl_cuti1"));
            $tglct4 = idate($this->input->post("tgl_cuti2"));

            $fixtglct1 = date($fmtct,$tglct1);
            $fixtglct2 = date($fmtct,$tglct2);

            $jumlahct = $tglct4 - $tglct3;

            $savecuti = array("surat_id"=>$this->session->userdata("IDSRT"),
                    "j_cuti"=>$this->input->post("cmb_ct"),
                    "cuti1"=>implode("-",array_reverse(explode("/",$this->input->post('tgl_cuti1')))),
                    "cuti2"=>implode("-",array_reverse(explode("/",$this->input->post('tgl_cuti2')))),
                    "jumlah"=>$jumlahct,
                    "is_approve"=> 0);

            $save = array("surat_no"=>$this->input->post("t_nsurat"),
                    "surat_agenda"=>$this->input->post("t_nagenda"),
                    "surat_tanggal"=>implode("-",array_reverse(explode("/",$this->input->post('t_ntanggal')))),
                    "surat_tanggal_terima"=>implode("-",array_reverse(explode("/",$this->input->post('t_ntanggaltrima')))),
                    "surat_perihal"=>$this->input->post("t_perihal"),
                    "surat_asal"=>$this->input->post("r_internal"),
                    "surat_jenis"=>$this->input->post("cmb_jns"),
                    "surat_klasifikasi"=>$this->input->post("cmb_klasifikasi"),
                    "surat_sifat"=>$this->input->post("cmb_sft"),
                    "surat_prioritas"=>$this->input->post("cmb_prior"),
                    "surat_status"=>$this->input->post("cmb_state"),
                    "surat_balasan"=>$this->input->post("cmb_bls"),
                    "surat_keterangan"=>$this->input->post("t_keterangan"));

            $this->db->where("id",$this->session->userdata("IDEDTSAMPING"));
            $this->db->update("surat_cuti",$savecuti);
            $this->db->where("id",$this->session->userdata("IDSRT"));
            $this->db->update("surat",$save);

            $this->session->unset_userdata("IDEDTSAMPING");
            $this->session->unset_userdata("IDSRT");
            redirect("surat/tambah","refresh");
        }
        else {

            $save = array("surat_no"=>$this->input->post("t_nsurat"),
                    "surat_agenda"=>$this->input->post("t_nagenda"),
                    "surat_tanggal"=>implode("-",array_reverse(explode("/",$this->input->post('t_ntanggal')))),
                    "surat_tanggal_terima"=>implode("-",array_reverse(explode("/",$this->input->post('t_ntanggaltrima')))),
                    "surat_perihal"=>$this->input->post("t_perihal"),
                    "surat_asal"=>$this->input->post("r_internal"),
                    "surat_jenis"=>$this->input->post("cmb_jns"),
                    "surat_klasifikasi"=>$this->input->post("cmb_klasifikasi"),
                    "surat_sifat"=>$this->input->post("cmb_sft"),
                    "surat_prioritas"=>$this->input->post("cmb_prior"),
                    "surat_status"=>$this->input->post("cmb_state"),
                    "surat_balasan"=>$this->input->post("cmb_bls"),
                    "surat_keterangan"=>$this->input->post("t_keterangan"));

            $this->db->where("id",$this->session->userdata("IDSRT"));
            $this->db->update("surat",$save);
            $this->session->unset_userdata("IDEDTSAMPING");
            $this->session->unset_userdata("IDSRT");
            redirect("surat/tambah","refresh");
        }
    }

    function edit($id=''){
        $id = $this->uri->segment(3);
        $this->firephp->log($id);
        $this->load->model("M_Surat");
        $data = array("title"=>"SIP TU - Surat - Edit Surat",
                "sub_menu"=>"surat/menu",
                "main_content"=>"surat/form",
                "act"=>"edit",
                "form_act"=>"",
                "user_data"=>"surat",
                "module"=>"surat",
                "current_link"=>"edit",
                "dt_instansi"  => $this->M_Surat->GetInstansiList(),
                "xajax_js"     => $this->xajax->getJavascript("../../scripts/js/"),
                "dtjns"        => $this->M_Surat->GetJenisSuratList(),
                "dtklf"        => $this->M_Surat->GetKlasifikasiList(),
                "dtsft"        => $this->M_Surat->GetSifatList(),
                "dtprt"        => $this->M_Surat->GetPrioritasList(),
                "dtsts"        => $this->M_Surat->GetStatusList(),
                "dtbls"        => $this->M_Surat->GetBalasanList(),
                "dtpgd"        => $this->M_Surat->GetAllTipePengaduan(),
                "dtabs"      => $this->M_Surat->GetTypeAbsens(),
                "dtcutitype"   => $this->M_Surat->GetTypeCuti(),
                "dtlistpenerima" =>$this->M_Surat->GetDaftarPenerima($id));
        $data ["t_perihal"]  = array('name' => 't_perihal','id'=>'tp','cols'=>'50','rows'=>'3');
        $data ["t_keterangan"]  = array('name' => 't_keterangan','id'=>'tk','cols'=>'50','rows'=>'3');
        $data ["t_ketpenganduan"]  = array('name' => 't_ketpgd','id'=>'pgdt','cols'=>'50','rows'=>'3');
        $data ["t_ketdispensasi"]  = array('name' => 't_ketdsp','id'=>'pgdp','cols'=>'50','rows'=>'3');
        $this->load->view('layout/template', $data);
    }

    function SuratEdit($id){
                $id = $this->uri->segment(3);
                $this->firephp->log($id);
                $this->load->model("M_Surat");
                $data = array("title"=>"SIP TU - Surat - Edit Surat",
                "sub_menu"=>"surat/menu",
                "main_content"=>"surat/form",
                "act"=>"edit",
                "form_act"=>"",
                "user_data"=>"surat",
                "module"=>"surat",
                "current_link"=>"edit",
                "dt_instansi"  => $this->M_Surat->GetInstansiList(),
                "xajax_js"     => $this->xajax->getJavascript("../../../scripts/js/"),
                "dtjns"        => $this->M_Surat->GetJenisSuratList(),
                "dtklf"        => $this->M_Surat->GetKlasifikasiList(),
                "dtsft"        => $this->M_Surat->GetSifatList(),
                "dtprt"        => $this->M_Surat->GetPrioritasList(),
                "dtsts"        => $this->M_Surat->GetStatusList(),
                "dtbls"        => $this->M_Surat->GetBalasanList(),
                "dtpgd"        => $this->M_Surat->GetAllTipePengaduan(),
                "dtabs"      => $this->M_Surat->GetTypeAbsens(),
                "dtcutitype"   => $this->M_Surat->GetTypeCuti(),
                "dtedit" =>$this->M_Surat->GetById($id),
                "dtlistpenerima" =>$this->M_Surat->GetDaftarPenerima($id),
                "dtpengaduan"=>$this->M_Surat->SuratPengaduan($id),
                "dtundangan"=>$this->M_Surat->SuratUndangan($id),
                "dtdispensasi"=>$this->M_Surat->SuratDispensasi($id));
        $data ["t_perihal"]  = array('name' => 't_perihal','id'=>'tp','cols'=>'50','rows'=>'3');
        $data ["t_keterangan"]  = array('name' => 't_keterangan','id'=>'tk','cols'=>'50','rows'=>'3');
        $data ["t_ketpenganduan"]  = array('name' => 't_ketpgd','id'=>'pgdt','cols'=>'50','rows'=>'3');
        $data ["t_ketdispensasi"]  = array('name' => 't_ketdsp','id'=>'pgdp','cols'=>'50','rows'=>'3');

        $this->load->view('layout/template', $data);
    }
    
    function KalkulasiCuti($form_data) {
        $objresponse = new xajaxResponse();

        $fmt = "m-d-Y";

        $fmt2 = "m-d-Y";

        $tglct1 = strtotime($form_data["tgl_cuti1"]);
        $tglct2 = strtotime($form_data["tgl_cuti2"]);

        $fix1 = date($fmt2,$tglct1);
        $fix2 = date($fmt2,$tglct2);
//
//        $jumlah = $fix2 - $fix1;

        //$jumlah = $this->calculateWorkingDaysInMonth($form_data["tgl_cuti1"], $form_data["tgl_cuti2"]);
        //$jumlah = $this->calculateWork($form_data["tgl_cuti1"], $form_data["tgl_cuti2"]);
        $jumlah = $this->calculateWork($fix2, $fix1);

        $objresponse->assign("t_jml","value",$jumlah);
        return $objresponse;

    }

    function calculateWork($startdt, $enddt) {
        //$this->days = intval((strtotime($this->date1) - strtotime($this->date2)) / 86400);

        $jumhr = $this->days = intval((strtotime($startdt) -   strtotime($enddt)) / 86400);
        return $jumhr;
    }

    function calculateWorkingDaysInMonth($year = '', $month = '') {
        //in case no values are passed to the function, use the current month and year
        if ($year == '') {
            $year = date('Y');
        }
        if ($month == '') {
            $month = date('m');
        }
        //create a start and an end datetime value based on the input year
        $startdate = strtotime($year . '-' . $month . '-01');
        $enddate = strtotime('+' . (date('t',$startdate) - 1). ' days',$startdate);
        $currentdate = $startdate;
        //get the total number of days in the month
        $return = intval((date('t',$startdate)),10);
        //loop through the dates, from the start date to the end date
        while ($currentdate <= $enddate) {
            //if you encounter a Saturday or Sunday, remove from the total days count
            if ((date('D',$currentdate) == 'Sat') || (date('D',$currentdate) == 'Sun')) {
                $return = $return - 1;
            }
            $currentdate = strtotime('+1 day', $currentdate);
        } //end date walk loop
        //return the number of working days
        return $return;
    }
}
?>
