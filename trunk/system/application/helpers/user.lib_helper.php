<?php

function b_admin($id)
{
	//$this->User_model->
	$CI = &get_instance();
	$CI->load->model('user_model');
	return $CI->user_model->get_status($id);
}

function b_getuserlogin()
{
    $CI = &get_instance();	
	if(b_logged())
        $nreturn=$CI->session->userdata('SESS_USER_ID');
    else
        $nreturn=0;
		
    return $nreturn;
}

function b_logged() {
	$CI = &get_instance();
    if($CI->session->userdata('SESS_USER_ID')!='') return TRUE;
	else return FALSE;
}

?>