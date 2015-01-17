<?php

class Auth extends Controller
{
	function Auth()
    {
		parent::Controller();
		$CI = &get_instance();
	}
	
	function index()
    {	    	
    	//$this->login();
		redirect('', 'location');
    }
		
	function login()
	{
		if (!$this->login_user_validation())
		{
			$this->session->set_userdata('ERRMSG_ARR', validation_errors());
			//redirect('', 'location');
		}else{
			
			$this->load->model('user_model');
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$query = $this->user_model->validate_login($username, $password);
			
			if($query->num_rows > 0)
			{
				$row = $query->row();
				$data = array(
					'SESS_USER_ID' => $row->NID,
					'SESS_USER_NAME' => $row->CUSER,
					'SESS_LEVEL' => $row->NSTATUS
				);
				$this->session->set_userdata($data);
				//redirect('', 'location');
			}
			else // incorrect username or password
			{
				$data = array(
					'SESS_LOGIN_STATEMENT' => 'Login Gagal ;)',
					'ERRMSG_ARR' => "Username dan/atau Password salah !"
				);
				$this->session->set_userdata($data);
				//redirect('', 'location');
			}
		}
		//redirect('', 'location');
		$this->index();
	}
	
	function login_user_validation()
	{
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		return $this->form_validation->run();
	}		
	
	function logout()
    {
        $this->session->unset_userdata('SESS_USER_ID');
		$this->session->unset_userdata('SESS_FIRST_NAME');
		$this->session->unset_userdata('SESS_LEVEL');
		$this->session->unset_userdata('ERRMSG_ARR');
		//$this->session->unset_userdata('SESS_LOGIN_STATEMENT');
		//$this->session->set_userdata('SESS_LOGIN_STATEMENT', 'Anda Telah Logout ;)');
		redirect('', 'location');
    }
		
	function register()
	{
	
	}
	
	function activation()
	{
	
	}
	
	function forgotten_password()
	{
	
	}
	
	function forgotten_password_reset()
	{
	
	}
	
	function changepassword()
	{
	
	}
	
	function isValidUser()
	{
        if ($this->CI->db_session AND $this->CI->config->item('FAL'))
        {
            if ($this->getUserName() != '')
            	return true;
        }
        // if user not activated or not existent
        return false;		
	}	
	
}


?>