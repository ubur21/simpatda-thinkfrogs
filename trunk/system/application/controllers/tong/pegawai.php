<?php
class Pegawai extends Controller {

	function Pegawai()
	{
		parent::Controller();
		$this->load->model('pegawai_model', 'data_model');
		$this->load->model('satker_model');
		$this->load->library('jqgridcss','','css');
		$this->defSatker = 1;
	}

	function index()
	{
		$data['title'] = "SIP TU - Pegawai";
		$data['list_satker'] = $this->list_satker($this->defSatker);
		$data['default_satker'] = $this->defSatker;
		$data['main_content'] = 'pegawai/display';
		$this->load->view('layout/template', $data);
	}

	function list_satker($id_satker)
	{
		if ($satker = $this->satker_model->get_all_data())
		{
			$list_satker = "<select name='list_satker' id='list_satker'>\n";
			for ($i = 0; $i < count($satker); $i++)
			{
				$list_satker .= "<option value='". $satker[$i]->ID_SATKER ."' ". (($satker[$i]->ID_SATKER == $satker) ? "selected='1'" : "") .">". $satker[$i]->NAMA_SATKER ."</option>\n";
			}
			$list_satker .= "</select>\n";
		}
		else
		{
			$list_satker = "";
		};
		return $list_satker;
	}

	// daftar untuk konsumsi jqgrid
	function get_daftar($id_satker)
	{
        $page = $_REQUEST['page']; // get the requested page 
        $limit = $_REQUEST['rows']; // get how many rows we want to have into the grid 
        $sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort 
        $sord = $_REQUEST['sord']; // get the direction if(!$sidx) $sidx =1;  
         
        $req_param = array (
				"id_satker" => $id_satker,
				"sort_by" => $sidx,
				"sort_direction" => $sord,
				"limit" => null,
				"search" => $_REQUEST['_search'],
				"search_field" => isset($_REQUEST['searchField'])?$_REQUEST['searchField']:null,
				"search_operator" => isset($_REQUEST['searchOper'])?$_REQUEST['searchOper']:null,
				"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null
		);     
           
        $row = $this->data_model->get_data($req_param, true)->result_array();
		$count = $row[0]['NUMROWS']; 
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
                
        for($i=0; $i<count($result); $i++){
            $response->rows[$i]['id']=$result[$i]['ID_PEGAWAI'];
            // data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
            $response->rows[$i]['cell']=array($result[$i]['ID_PEGAWAI'],
											$result[$i]['NIP'],
											$result[$i]['NAMA_PEGAWAI'],
											$result[$i]['KODE_PANGKAT'],
											$result[$i]['NAMA_SATKER']
                                        );
        }
        echo json_encode($response); 
	}
	
	function get_nama(){
		
		$nama = $_REQUEST['q'];	
		$query = $this->data_model->get_data_autocomplate($nama);
		if($query->num_rows() > 0)
		{
			$result = $query->result();
			foreach($result as $row)
			{
				$jabatan = ($row->NAMA_JABATAN_FUNGSIONAL!='') ? $row->NAMA_JABATAN_FUNGSIONAL : $row->NAMA_JABATAN_STRUKTURAL;
				echo $row->GELAR_DEPAN.' '.$row->NAMA_PEGAWAI.', '.$row->GELAR_BELAKANG.'|'.$row->ID_PEGAWAI.'#'.$jabatan."\n";
			}
		}		
	}

	function tab($tab, $id=0)
	{
		$data['id'] = $id;
		if( $tab ) $this->load->view('pegawai/'.$tab, $data);
	}

	/*
	KELUARGA
	*/
	function keluarga()
	{
		$this->load->model('keluarga_model');
		if($this->input->post('oper') === 'add'){
			$this->insert_keluarga( $this->input->post() );
		} else if($this->input->post('oper') === 'edit'){
			$this->update_keluarga( $this->input->post() );
		} else if($this->input->post('oper') === 'del'){
			$this->delete_keluarga( $this->input->post() );
		}
	}

	function insert_keluarga()
	{
		$error_msg = '';		
		
		$this->keluarga_model->fill_data();

		//Insert Data
		$this->keluarga_model->insert_data();
		echo json_encode(array('errors'=> $error_msg));
	}
			
	function update_keluarga()
	{
		$id = $this->input->post('id');
		$error_msg = '';
		{
			$this->keluarga_model->fill_data();
			//Update Data
			$this->keluarga_model->update_data($id);
			echo json_encode(array('errors'=> $error_msg, 'id'=> $id));
		}
	}

	function delete_keluarga()
	{
		$id = $this->input->post('id');
		
		$data = $this->keluarga_model->get_data_by_id($id);
		{
			if($this->keluarga_model->delete_data($id))
			{
				$error_msg = '';
			}
			else
			{
				$error_msg = 'Terjadi kesalahan dalam menghapus data Keluarga '.$data['NAMA'].'. Harap coba lagi.';
			}
		}
		echo json_encode(array('errors'=> $error_msg));
	}

	function get_keluarga()
	{
		$this->load->model('keluarga_model');
		$id = $this->uri->segment(3);

        $response = '';
		$result = $this->keluarga_model->get_data_by_fk($id)->result_array();        

        for($i=0; $i<count($result); $i++){
            $response->rows[$i]['id']=$result[$i]['ID_KELUARGA'];
            // data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
            $response->rows[$i]['cell']=array($result[$i]['ID_KELUARGA'],
											$result[$i]['ID_PEGAWAI'],
											$result[$i]['URUT'],
											$result[$i]['NAMA'],
											$result[$i]['TEMPAT_LAHIR'],
											$result[$i]['TGL_LAHIR'],
											$result[$i]['JENIS_KELAMIN'],
											$result[$i]['PENDIDIKAN'],
											$result[$i]['PEKERJAAN'],
											$result[$i]['STATUS_KELUARGA'],
											$result[$i]['STATUS_TUNJANGAN']
                                        );
        }
        echo json_encode($response); 
	}

	/*
	PANGKAT (GOLONGAN/RUANG)
	*/
	function pangkat()
	{
		$this->load->model('his_pangkat_model');
		if($this->input->post('oper') === 'add'){
			$this->insert_pangkat( $this->input->post() );
		} else if($this->input->post('oper') === 'edit'){
			$this->update_pangkat( $this->input->post() );
		} else if($this->input->post('oper') === 'del'){
			$this->delete_pangkat( $this->input->post() );
		}
	}

	function insert_pangkat()
	{
		$error_msg = '';		
		
		$this->his_pangkat_model->fill_data();

		//Insert Data
		$this->his_pangkat_model->insert_data();
		echo json_encode(array('errors'=> $error_msg));
	}
			
	function update_pangkat()
	{
		$id = $this->input->post('id');
		$error_msg = '';
		{
			$this->his_pangkat_model->fill_data();
			//Update Data
			$this->his_pangkat_model->update_data($id);
			echo json_encode(array('errors'=> $error_msg, 'id'=> $id));
		}
	}

	function delete_pangkat()
	{
		$id = $this->input->post('id');
		
		$data = $this->his_pangkat_model->get_data_by_id($id);
		{
			if($this->his_pangkat_model->delete_data($id))
			{
				$error_msg = '';
			}
			else
			{
				$error_msg = 'Terjadi kesalahan dalam menghapus data Pangkat '.$data['NAMA'].'. Harap coba lagi.';
			}
		}
		echo json_encode(array('errors'=> $error_msg));
	}

	function get_pangkat()
	{
		$this->load->model('his_pangkat_model');
		$id = $this->uri->segment(3);

		$response = '';
		$result = $this->his_pangkat_model->get_data_by_fk($id)->result_array();        
        for($i=0; $i<count($result); $i++){
            $response->rows[$i]['id']=$result[$i]['ID_H_PANGKAT'];
            // data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
            $response->rows[$i]['cell']=array($result[$i]['ID_H_PANGKAT'],
											$result[$i]['ID_PEGAWAI'],
											$result[$i]['PANGKAT'],
											$result[$i]['TMT'],
											$result[$i]['GAJI_POKOK'],
											$result[$i]['MK_TAHUN'],
											$result[$i]['MK_BULAN'],
											$result[$i]['MK_TAHUN'] ." th ". $result[$i]['MK_BULAN']. " bl",
											$result[$i]['NO_SK'],
											$result[$i]['TGL_SK'],
											$result[$i]['PEJABAT_SK']
                                        );
        }
        echo json_encode($response); 
	}

	/*
	GAJI BERKALA
	*/
	function gaji()
	{
		$this->load->model('his_gaji_model');
		if($this->input->post('oper') === 'add'){
			$this->insert_gaji( $this->input->post() );
		} else if($this->input->post('oper') === 'edit'){
			$this->update_gaji( $this->input->post() );
		} else if($this->input->post('oper') === 'del'){
			$this->delete_gaji( $this->input->post() );
		}
	}

	function insert_gaji()
	{
		$error_msg = '';		
		
		$this->his_gaji_model->fill_data();

		//Insert Data
		$this->his_gaji_model->insert_data();
		echo json_encode(array('errors'=> $error_msg));
	}
			
	function update_gaji()
	{
		$id = $this->input->post('id');
		$error_msg = '';
		{
			$this->his_gaji_model->fill_data();
			//Update Data
			$this->his_gaji_model->update_data($id);
			echo json_encode(array('errors'=> $error_msg, 'id'=> $id));
		}
	}

	function delete_gaji()
	{
		$id = $this->input->post('id');
		
		$data = $this->his_gaji_model->get_data_by_id($id);
		{
			if($this->his_gaji_model->delete_data($id))
			{
				$error_msg = '';
			}
			else
			{
				$error_msg = 'Terjadi kesalahan dalam menghapus data Gaji '.$data['NAMA'].'. Harap coba lagi.';
			}
		}
		echo json_encode(array('errors'=> $error_msg));
	}

	function get_gaji()
	{
		$this->load->model('his_gaji_model');
		$id = $this->uri->segment(3);

		$response = '';
		$result = $this->his_gaji_model->get_data_by_fk($id)->result_array();        
        for($i=0; $i<count($result); $i++){
            $response->rows[$i]['id']=$result[$i]['ID_H_GAJI'];
            // data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
            $response->rows[$i]['cell']=array($result[$i]['ID_H_GAJI'],
											$result[$i]['ID_PEGAWAI'],
											$result[$i]['PANGKAT'],
											$result[$i]['TMT'],
											$result[$i]['GAJI_POKOK'],
											$result[$i]['MK_TAHUN'],
											$result[$i]['MK_BULAN'],
											$result[$i]['MK_TAHUN'] ." th ". $result[$i]['MK_BULAN']. " bl",
											$result[$i]['NO_SK'],
											$result[$i]['TGL_SK'],
											$result[$i]['PEJABAT_SK']
                                        );
        }
        echo json_encode($response); 
	}

	/*
	ORGANISASI
	*/
	function organisasi()
	{
		$this->load->model('his_organisasi_model');
		if($this->input->post('oper') === 'add'){
			$this->insert_organisasi( $this->input->post() );
		} else if($this->input->post('oper') === 'edit'){
			$this->update_organisasi( $this->input->post() );
		} else if($this->input->post('oper') === 'del'){
			$this->delete_organisasi( $this->input->post() );
		}
	}

	function insert_organisasi()
	{
		$error_msg = '';		
		
		$this->his_organisasi_model->fill_data();

		//Insert Data
		$this->his_organisasi_model->insert_data();
		echo json_encode(array('errors'=> $error_msg));
	}
			
	function update_organisasi()
	{
		$id = $this->input->post('id');
		$error_msg = '';
		{
			$this->his_organisasi_model->fill_data();
			//Update Data
			$this->his_organisasi_model->update_data($id);
			echo json_encode(array('errors'=> $error_msg, 'id'=> $id));
		}
	}

	function delete_organisasi()
	{
		$id = $this->input->post('id');
		
		$data = $this->his_organisasi_model->get_data_by_id($id);
		{
			if($this->his_organisasi_model->delete_data($id))
			{
				$error_msg = '';
			}
			else
			{
				$error_msg = 'Terjadi kesalahan dalam menghapus data Organisasi '.$data['NAMA'].'. Harap coba lagi.';
			}
		}
		echo json_encode(array('errors'=> $error_msg));
	}

	function get_organisasi()
	{
		$this->load->model('his_organisasi_model');
		$id = $this->uri->segment(3);

		$response = '';
		$result = $this->his_organisasi_model->get_data_by_fk($id)->result_array();        
        for($i=0; $i<count($result); $i++){
            $response->rows[$i]['id']=$result[$i]['ID_H_ORGANISASI'];
            // data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
            $response->rows[$i]['cell']=array($result[$i]['ID_H_ORGANISASI'],
											$result[$i]['ID_PEGAWAI'],
											$result[$i]['NAMA_ORGANISASI'],
											$result[$i]['JABATAN'],
											$result[$i]['TAHUN_MULAI'],
											$result[$i]['TAHUN_SELESAI'],
											$result[$i]['TEMPAT'],
											$result[$i]['PIMPINAN']
                                        );
        }
        echo json_encode($response); 
	}

	/*
	KURSUS
	*/
	function kursus()
	{
		$this->load->model('his_kursus_model');
		if($this->input->post('oper') === 'add'){
			$this->insert_kursus( $this->input->post() );
		} else if($this->input->post('oper') === 'edit'){
			$this->update_kursus( $this->input->post() );
		} else if($this->input->post('oper') === 'del'){
			$this->delete_kursus( $this->input->post() );
		}
	}

	function insert_kursus()
	{
		$error_msg = '';		
		
		$this->his_kursus_model->fill_data();

		//Insert Data
		$this->his_kursus_model->insert_data();
		echo json_encode(array('errors'=> $error_msg));
	}
			
	function update_kursus()
	{
		$id = $this->input->post('id');
		$error_msg = '';
		{
			$this->his_kursus_model->fill_data();
			//Update Data
			$this->his_kursus_model->update_data($id);
			echo json_encode(array('errors'=> $error_msg, 'id'=> $id));
		}
	}

	function delete_kursus()
	{
		$id = $this->input->post('id');
		
		$data = $this->his_kursus_model->get_data_by_id($id);
		{
			if($this->his_kursus_model->delete_data($id))
			{
				$error_msg = '';
			}
			else
			{
				$error_msg = 'Terjadi kesalahan dalam menghapus data Kursus '.$data['NAMA'].'. Harap coba lagi.';
			}
		}
		echo json_encode(array('errors'=> $error_msg));
	}

	function get_kursus()
	{
		$this->load->model('his_kursus_model');
		$id = $this->uri->segment(3);

		$response = '';
		$result = $this->his_kursus_model->get_data_by_fk($id)->result_array();        
        for($i=0; $i<count($result); $i++){
            $response->rows[$i]['id']=$result[$i]['ID_H_KURSUS'];
            // data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
            $response->rows[$i]['cell']=array($result[$i]['ID_H_KURSUS'],
											$result[$i]['ID_PEGAWAI'],
											$result[$i]['NAMA_KURSUS'],
											$result[$i]['INSTITUSI'],
											$result[$i]['TGL_MULAI'],
											$result[$i]['TGL_SELESAI'],
											$result[$i]['TANDA_LULUS'],
											$result[$i]['TEMPAT'],
											$result[$i]['NILAI'],
											$result[$i]['KETERANGAN'],
                                        );
        }
        echo json_encode($response); 
	}

	/*
	DIKLAT
	*/
	function diklat()
	{
		$this->load->model('his_diklat_model');
		if($this->input->post('oper') === 'add'){
			$this->insert_diklat( $this->input->post() );
		} else if($this->input->post('oper') === 'edit'){
			$this->update_diklat( $this->input->post() );
		} else if($this->input->post('oper') === 'del'){
			$this->delete_diklat( $this->input->post() );
		}
	}

	function insert_diklat()
	{
		$error_msg = '';		
		
		$this->his_diklat_model->fill_data();

		//Insert Data
		$this->his_diklat_model->insert_data();
		echo json_encode(array('errors'=> $error_msg));
	}
			
	function update_diklat()
	{
		$id = $this->input->post('id');
		$error_msg = '';
		{
			$this->his_diklat_model->fill_data();
			//Update Data
			$this->his_diklat_model->update_data($id);
			echo json_encode(array('errors'=> $error_msg, 'id'=> $id));
		}
	}

	function delete_diklat()
	{
		$id = $this->input->post('id');
		
		$data = $this->his_diklat_model->get_data_by_id($id);
		{
			if($this->his_diklat_model->delete_data($id))
			{
				$error_msg = '';
			}
			else
			{
				$error_msg = 'Terjadi kesalahan dalam menghapus data Diklat '.$data['NAMA'].'. Harap coba lagi.';
			}
		}
		echo json_encode(array('errors'=> $error_msg));
	}

	function get_diklat()
	{
		$this->load->model('his_diklat_model');
		$id = $this->uri->segment(3);

		$response = '';
		$result = $this->his_diklat_model->get_data_by_fk($id)->result_array();        
        for($i=0; $i<count($result); $i++){
            $response->rows[$i]['id']=$result[$i]['ID_H_DIKLAT'];
            // data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
            $response->rows[$i]['cell']=array($result[$i]['ID_H_DIKLAT'],
											$result[$i]['ID_PEGAWAI'],
											$result[$i]['NAMA_DIKLAT'],
											$result[$i]['PENYELENGGARA'],
											$result[$i]['TGL_MULAI'],
											$result[$i]['TGL_SELESAI'],
											$result[$i]['TEMPAT'],											
											$result[$i]['NO_STTPP'],
											$result[$i]['TGL_STTPP'],
											$result[$i]['NILAI']
                                        );
        }
        echo json_encode($response); 
	}

	/*
	PENDIDIKAN
	*/
	function pendidikan()
	{
		$this->load->model('his_pendidikan_model');
		if($this->input->post('oper') === 'add'){
			$this->insert_pendidikan( $this->input->post() );
		} else if($this->input->post('oper') === 'edit'){
			$this->update_pendidikan( $this->input->post() );
		} else if($this->input->post('oper') === 'del'){
			$this->delete_pendidikan( $this->input->post() );
		}
	}

	function insert_pendidikan()
	{
		$error_msg = '';		
		
		$this->his_pendidikan_model->fill_data();

		//Insert Data
		$this->his_pendidikan_model->insert_data();
		echo json_encode(array('errors'=> $error_msg));
	}
			
	function update_pendidikan()
	{
		$id = $this->input->post('id');
		$error_msg = '';
		{
			$this->his_pendidikan_model->fill_data();
			//Update Data
			$this->his_pendidikan_model->update_data($id);
			echo json_encode(array('errors'=> $error_msg, 'id'=> $id));
		}
	}

	function delete_pendidikan()
	{
		$id = $this->input->post('id');
		
		$data = $this->his_pendidikan_model->get_data_by_id($id);
		{
			if($this->his_pendidikan_model->delete_data($id))
			{
				$error_msg = '';
			}
			else
			{
				$error_msg = 'Terjadi kesalahan dalam menghapus data Pendidikan '.$data['NAMA'].'. Harap coba lagi.';
			}
		}
		echo json_encode(array('errors'=> $error_msg));
	}

	function get_pendidikan()
	{
		$this->load->model('his_pendidikan_model');
		$id = $this->uri->segment(3);

		$response = '';
		$result = $this->his_pendidikan_model->get_data_by_fk($id)->result_array();        
        for($i=0; $i<count($result); $i++){
            $response->rows[$i]['id']=$result[$i]['ID_H_PENDIDIKAN'];
            // data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
            $response->rows[$i]['cell']=array($result[$i]['ID_H_PENDIDIKAN'],
											$result[$i]['ID_PEGAWAI'],
											$result[$i]['TINGKAT'],
											$result[$i]['JURUSAN'],
											$result[$i]['INSTITUSI'],
											$result[$i]['TAHUN_LULUS'],
											$result[$i]['TANDA_LULUS'],
											$result[$i]['TEMPAT'],
											$result[$i]['PIMPINAN']
                                        );
        }
        echo json_encode($response); 
	}

	/*
	PENGHARGAAN
	*/
	function penghargaan()
	{
		$this->load->model('his_penghargaan_model');
		if($this->input->post('oper') === 'add'){
			$this->insert_penghargaan( $this->input->post() );
		} else if($this->input->post('oper') === 'edit'){
			$this->update_penghargaan( $this->input->post() );
		} else if($this->input->post('oper') === 'del'){
			$this->delete_penghargaan( $this->input->post() );
		}
	}

	function insert_penghargaan()
	{
		$error_msg = '';		
		
		$this->his_penghargaan_model->fill_data();

		//Insert Data
		$this->his_penghargaan_model->insert_data();
		echo json_encode(array('errors'=> $error_msg));
	}
			
	function update_penghargaan()
	{
		$id = $this->input->post('id');
		$error_msg = '';
		{
			$this->his_penghargaan_model->fill_data();
			//Update Data
			$this->his_penghargaan_model->update_data($id);
			echo json_encode(array('errors'=> $error_msg, 'id'=> $id));
		}
	}

	function delete_penghargaan()
	{
		$id = $this->input->post('id');
		
		$data = $this->his_penghargaan_model->get_data_by_id($id);
		{
			if($this->his_penghargaan_model->delete_data($id))
			{
				$error_msg = '';
			}
			else
			{
				$error_msg = 'Terjadi kesalahan dalam menghapus data Penghargaan '.$data['NAMA'].'. Harap coba lagi.';
			}
		}
		echo json_encode(array('errors'=> $error_msg));
	}

	function get_penghargaan()
	{
		$this->load->model('his_penghargaan_model');
		$id = $this->uri->segment(3);

		$response = '';
		$result = $this->his_penghargaan_model->get_data_by_fk($id)->result_array();        
        for($i=0; $i<count($result); $i++){
            $response->rows[$i]['id']=$result[$i]['ID_H_PENGHARGAAN'];
            // data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
            $response->rows[$i]['cell']=array($result[$i]['ID_H_PENGHARGAAN'],
											$result[$i]['ID_PEGAWAI'],
											$result[$i]['NAMA_PENGHARGAAN'],
											$result[$i]['TAHUN'],
											$result[$i]['PEMBERI'],
											$result[$i]['KETERANGAN']
                                        );
        }
        echo json_encode($response); 
	}

	/*
	JABATAN
	*/
	function jabatan()
	{
		$this->load->model('his_jabatan_model');
		if($this->input->post('oper') === 'add'){
			$this->insert_jabatan( $this->input->post() );
		} else if($this->input->post('oper') === 'edit'){
			$this->update_jabatan( $this->input->post() );
		} else if($this->input->post('oper') === 'del'){
			$this->delete_jabatan( $this->input->post() );
		}
	}

	function insert_jabatan()
	{
		$error_msg = '';		
		
		$this->his_jabatan_model->fill_data();

		//Insert Data
		$this->his_jabatan_model->insert_data();
		echo json_encode(array('errors'=> $error_msg));
	}
			
	function update_jabatan()
	{
		$id = $this->input->post('id');
		$error_msg = '';
		{
			$this->his_jabatan_model->fill_data();
			//Update Data
			$this->his_jabatan_model->update_data($id);
			echo json_encode(array('errors'=> $error_msg, 'id'=> $id));
		}
	}

	function delete_jabatan()
	{
		$id = $this->input->post('id');
		
		$data = $this->his_jabatan_model->get_data_by_id($id);
		{
			if($this->his_jabatan_model->delete_data($id))
			{
				$error_msg = '';
			}
			else
			{
				$error_msg = 'Terjadi kesalahan dalam menghapus data Jabatan '.$data['NAMA'].'. Harap coba lagi.';
			}
		}
		echo json_encode(array('errors'=> $error_msg));
	}

	function get_jabatan()
	{
		$this->load->model('his_jabatan_model');
		$id = $this->uri->segment(3);

		$response = '';
		$result = $this->his_jabatan_model->get_data_by_fk($id)->result_array();        
        for($i=0; $i<count($result); $i++){
            $response->rows[$i]['id']=$result[$i]['ID_H_JABATAN'];
            // data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
            $response->rows[$i]['cell']=array($result[$i]['ID_H_JABATAN'],
											$result[$i]['ID_PEGAWAI'],
											$result[$i]['JABATAN'],
											$result[$i]['TGL_MULAI'],
											$result[$i]['TGL_BERHENTI'],
											$result[$i]['PANGKAT'],
											$result[$i]['ESELON'],
											$result[$i]['GAJI_POKOK'],
											$result[$i]['NO_SK'],
											$result[$i]['TGL_SK'],
											$result[$i]['PEJABAT_SK'],
                                        );
        }
        echo json_encode($response); 
	}

	/*
	DUK
	*/
	function duk()
	{
		$data['title'] = "SIP TU - Daftar Urut Kepangkatan";
		$data['list_satker'] = $this->list_satker($this->defSatker);
		$data['default_satker'] = $this->defSatker;
		$data['main_content'] = 'pegawai/duk';
		$data['current_link'] = 'duk';
		$this->load->view('layout/template', $data);
	}

	function get_duk($id_satker)
	{
        $page = $_REQUEST['page']; // get the requested page 
        $limit = $_REQUEST['rows']; // get how many rows we want to have into the grid 
         
        $req_param = array (
				"id_satker" => $id_satker,
				"limit" => null,
		);     
           
        $row = $this->data_model->get_duk($req_param, true)->result_array();
		$count = $row[0]['NUMROWS']; 
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
          
        
        $result = $this->data_model->get_duk($req_param)->result_array();
        // sekarang format data dari dB sehingga sesuai yang diinginkan oleh jqGrid dalam hal ini pakai JSON format
        $response->page = $page; 
        $response->total = $total_pages; 
        $response->records = $count;
                
        for($i=0; $i<count($result); $i++){
            $response->rows[$i]['id']=$result[$i]['ID_PEGAWAI'];
            // data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)
            $response->rows[$i]['cell']=array($result[$i]['ID_PEGAWAI'],
											$result[$i]['NAMA_PEGAWAI'],
											$result[$i]['NIP'],
											$result[$i]['TANGGAL_LAHIR'],
											$result[$i]['KODE_PANGKAT'],
											$result[$i]['TMT_GOL'],
											$result[$i]['JABATAN'],
											$result[$i]['TMT_JABATAN'],
											$result[$i]['MK_TAHUN']. " th ".$result[$i]['MK_BULAN']. " bln",
											$result[$i]['PENDIDIKAN'],
											$result[$i]['TAHUN_LULUS']
                                        );
        }
        echo json_encode($response); 
	}
	/*
	KGB
	*/
	function kgb()
	{
	
	}
	/*
	Data pegawai
	*/
	function insert()
	{
		$error_msg = '';
		
		$this->data_model->fill_data();
		//Check for duplicate nip
		if(!$this->data_model->check_nip())
		{
			$error_msg = 'NIP Pegawai telah digunakan';
		}
		//Insert Data
		elseif($this->data_model->insert_data()) 
		{
			$error_msg = '';
		}			
		echo json_encode(array('errors'=> $error_msg));

	}
			
	function update()
	{
		$id = $this->input->post('id');
		$this->firephp->log($id, 'id');
		$error_msg = '';
		{
			$this->data_model->fill_data();
			if(!$this->data_model->check_nip($id))
			{
				$error_msg = 'NIP Pegawai telah digunakan';
			}		
			//Update Data
			elseif($this->data_model->update_data($id))
			{
				$error_msg = '';
			}
			echo json_encode(array('errors'=> $error_msg, 'id'=> $id));
		}
	}

	function delete()
	{
		$id = $this->input->post('id');
		$id = explode(',', $id);
		
		$data = $this->data_model->get_data_by_id($id);
		{
			if($this->data_model->delete_data($id))
			{
				$error_msg = '';
			}
			else
			{
				$error_msg = 'Terjadi kesalahan dalam menghapus data Pegawai '.$data['NAMA_PEGAWAI'].'. Harap coba lagi.';
			}
		}
		echo json_encode(array('errors'=> $error_msg));
	}

	function daftar()
	{
		$data['title'] = "SIP TU - Pegawai - Daftar Pegawai";
		$data['sub_menu'] = 'pegawai/menu';
		$data['main_content'] = 'pegawai/display';
		$data['current_link'] = 'daftar';
		$data['list_satker'] = $this->list_satker($this->defSatker);
		$data['default_satker'] = $this->defSatker;
		$this->load->view('layout/template', $data);
	}
	
	function tambah()
	{  
		$id = $this->uri->segment(3);
		$data['title'] = "SIP TU - Pegawai - Tambah Pegawai";
		$data['sub_menu'] = 'pegawai/menu';
		$data['main_content'] = 'pegawai/form';
		$data['act']='tambah';
		$data['form_act']='';
		$data['user_data']=$this->data_model->get_data_by_id($id);
		$data['current_link'] = 'tambah';
		$data['agama']=$this->get_list_data('agama_model','agama');
		$data['pangkat']=$this->get_list_data('pangkat_model','pangkat');
		$data['jenjang']=$this->get_list_data('pendidikan_model','jenjang');
		$data['status']=$this->get_list_data('status_pegawai_model','status');
		$data['kedudukan']=$this->get_list_data('kedudukan_model','kedudukan');
		$this->load->view('layout/template', $data);
	}
	
	function edit($id='')
	{
		$this->load->model('agama_model');
		$this->load->model('pangkat_model');
		$this->load->model('pendidikan_model');
		$this->load->model('status_pegawai_model');
		$this->load->model('kedudukan_model');
		
		$id = $this->uri->segment(3);
		$data['title'] = "SIP TU - Pegawai - Ubah Pegawai";
		$data['sub_menu'] = 'pegawai/menu';
		$data['main_content'] = 'pegawai/form';
		$data['current_link'] = 'edit';
		$data['act'] = 'edit';
		$data['form_act'] = 'update/'.$id;
		$data['user_data'] = $this->data_model->get_data_by_id($id);		
		$data['agama']=$this->agama_model->get_all_data();
		$data['pangkat']=$this->pangkat_model->get_all_data();
		$data['jenjang']=$this->pendidikan_model->get_all_data();
		$data['status']=$this->status_pegawai_model->get_all_data();
		$data['kedudukan']=$this->kedudukan_model->get_all_data();
		$this->load->view('layout/template', $data);
	}
	
	function hapus()
	{  	
		$data['title'] = "SIP TU - Pegawai - Hapus Pegawai";
		$data['sub_menu'] = 'pegawai/menu';
		$data['main_content'] = 'pegawai/display';
		$data['current_link'] = 'hapus';
		$this->load->view('layout/template', $data);
	}
	
	function get_list_data($model, $alias) {
		$this->load->model($model,$alias);
		return $this->$alias->get_all_data();
	}

}
?>