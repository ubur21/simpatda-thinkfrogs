<?php

class Pemda extends CI_Controller {

	public function __construct() 
	{
		parent::__construct();
		$this->load->model('datapemerintahdaerah_model','data_model');
		$this->load->helper('url');
	}

	public function index() 
	{
		$data['SYS_ADMIN_LOGIN'] = $this->session->userdata('SYS_ADMIN_LOGIN');
		$data['user_data']	= $this->data_model->get_all_data();	
		
		$data['title']='SIP APBD - Daftar Data Pemerintah Daerah';
		$data['main_content']='datapemerintahdaerah_form';
		$this->load->view('layout/template',$data);
	}

	public function proses_form() 
	{
		//$this->update();
		$cek = $this->data_model->update_data();
		if($cek)
		{
			$respon->sukses = 'Data telah berhasil diperbaharui.';
		}
		else
		{
			$respon->error = 'Data gagal diperbaharui.';
		}
		
		echo json_encode($respon);
		//redirect('pemda/index'); 
	}

	public function update() {
		$this->data_model->update_data();
		//$this->session->set_flashdata('message','<B> <font color="black" size="2px"> <center> Update data berhasil! </center></font></B>');	
	}

	public function logo() 
	{
		$this->db->select('LOGO');				
		$logo = $this->db->get('PEMDA');
		$result = $logo->result_array();
		$image = $this->db->get_blob($result[0]['LOGO']);
		if ($logo->num_rows() > 0)
		{
			header('Content-type: image/bmp');
			echo $image;
		}
	}

	public function simpan_logo($image=0)
	{
		$fileName = $_FILES['image']['name']; 
		$fileSize = $_FILES['image']['size'];  
		$fileError = $_FILES['image']['error'];  
		
		if($fileSize > 0 || $fileError == 0){  
			$move = move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/tmp/'.$fileName);  

			$this->data_model->save_logo($image);

			if($move)
			{ 
				$response->success = 'Upload berhasil!';
			}
			else
			{  
				$response->success = 'Upload gagal!';
			}  
		}else{  
			$response->success = 'Upload gagal!';
		}
		//echo json_encode($response);		
		$this->index();
		redirect('pemda');	
	}
	
	public function hapus_logo()
	{
		$this->data_model->delete_logo();
		$this->index();
	}
	
	public function uploadtmp()
	{
		$data['action'] = $this->input->post('action');
		
		if(count($_FILES)>0)
		{
			$config['upload_path'] = './uploads/tmp/';
			
			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			$ext = $this->upload->get_extension($_FILES['filefieldname']['name']);
			$old_name   = $_FILES['filefieldname']['name'];
			$time_stamp = date('d.m.Y.H.i.s');
			$new_name   = $time_stamp.$ext;
			
			$tmp_name   = $_FILES['filefieldname']['tmp_name'];
						
			if ( ! @move_uploaded_file($_FILES['filefieldname']['tmp_name'], $this->upload->upload_path.$new_name))
			{
				if ( ! @copy($_FILES['filefieldname']['tmp_name'], $this->upload->upload_path.$new_name))
				{
					 $is_upload = FALSE;
					 
				}
				else
				{
					echo 'upload';
				}
			}
			else
			{ 
				?>
				<script>			
					var str = '<li><a class="del_tmp" onclick="delete_tmp(this)"></a><input type="hidden" name="LOGO[]" value="<?php echo $new_name?>" ><input type="text" name="keterangan_logo[]" id="keterangan_logo" value="Keterangan" onfocus="fokus_ket_logo(this)" onblur="blur_ket_logo(this)" size="20"><a href="#" class="screenshot" id="logopemda" rel="<?php echo base_url()?>uploads/tmp/<?php echo $new_name?>"> <?php echo $old_name ?></a></li>';
									
					parent.document.getElementById('photosxx').innerHTML+=str;
					
					parent.screenshotPreview();
				</script>
				<?php
			}
		}
		else
		{
			echo 'gagal';
		}
	}
}