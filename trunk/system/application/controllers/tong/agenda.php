<?php

class Agenda extends Controller
{
	var $error_msg='';

	function Sppd()
	{
		parent::Controller();
		$this->error_msg='';
		$this->load->library('jqgridcss','','css');
		//$this->load->model('agenda_model');
	}

	function index()
	{		
		$data['title']          = "SIP TU - Agenda Kerja";
		//$data['sub_menu']     = $this->load->view('sppd/menu');
		$data['main_content']   = 'agenda/index';
		$data['current_link']   = 'index';
		
		$data['user_data']['module'] = 'agenda';
	
		$this->load->view('layout/template', $data);
		
	}
	
	function edit()
	{
		$this->load->model('agenda_model');
		
		$result = $this->agenda_model->get_data_by_id($_REQUEST['id']);
		if($result->num_rows() > 0)
		{
			//$event = $result->row_array();
			$event = $result->row();
			$event->DESKRIPSI = $this->db->getBlob($event->DESKRIPSI);
			$data['event'] = $event;
		}else{
			$data['event'] = NULL;
		}
		
		$this->load->view('agenda/edit_agenda',$data);
		
	}
	
	function tambah()
	{
		$this->load->view('agenda/edit_agenda');
	}
	
	function notice()
	{
		$this->load->model('agenda_model');
		$result = $this->agenda_model->notice();
		if($result->num_rows() > 0)
		{
			//$data = $result->row();
			$ii=0;
			$arr['notice'] = 1;
			foreach($result->result() as $row)
			{
				$stime      = DBStamp2PhpTime($row->TGL_AWAL);
				$stpartdate = date('d/m/Y',$stime);
				$stparttime = date('H:i',$stime);
				
				$etime      = DBStamp2PhpTime($row->TGL_AKHIR);
				$etpartdate = date('d/m/Y',$etime);
				$etparttime = date('H:i',$etime);
				
				
				//$arr[$ii]['stpartdate'] = $stpartdate;
				$arr[$ii]['stpartdate'] = $stpartdate.' '.$stparttime;
				$arr[$ii]['stparttime'] = $stparttime;
				$arr[$ii]['etpartdate'] = $etpartdate;
				$arr[$ii]['etparttime'] = $etparttime;
				$arr[$ii]['subject']    = $row->SUBJECT;
				$ii++;				
			}
			$arr['rows']=$ii;
			//$arr['message']='';
			
		}else{
			$arr['notice']=0;
			$arr['messaage']='';
		}
		echo json_encode($arr);
	}
	
	function datafeed()
	{
		header('Content-type:text/javascript;charset=UTF-8');
		/*$param  = $this->uri->segment('3');
		$tmp    = explode($param);
		$method = $tmp[1];*/
		$method = $this->uri->segment('3');
		//$method = $_GET["method"];
		switch ($method)
		{
			case "add":
				$ret = $this->addCalendar($this->input->post('CalendarStartTime'), $this->input->post('CalendarEndTime'), $this->input->post('CalendarTitle'), $this->input->post('IsAllDayEvent'),$this->input->post('status'));
				break;
			case "list":
				$ret = $this->listCalendar($this->input->post('showdate'), $this->input->post('viewtype'));
				break;
			case "update":
				$ret = $this->updateCalendar($this->input->post('calendarId'), $this->input->post('CalendarStartTime'), $this->input->post('CalendarEndTime'));
				break; 
			case "remove":
				$ret = $this->removeCalendar($this->input->post('calendarId'));
				break;
			case "adddetails":
				//$id = $this->input->get('id');
				$id = $this->uri->segment('4');
				$st = $this->input->post('stpartdate') . " " . $this->input->post('stparttime');
				$et = $this->input->post('etpartdate') . " " . $this->input->post('etparttime');
				if($id){
					$ret = $this->updateDetailedCalendar($id, $st, $et, 
						$this->input->post('Subject'), $this->input->post('IsAllDayEvent') ? 1 : 0, $this->input->post('Description'), 
						$this->input->post('Location'), $this->input->post('colorvalue'), $this->input->post('timezone'));
				}else{
					$ret = $this->addDetailedCalendar($st, $et,                    
						$this->input->post('Subject'), $this->input->post('IsAllDayEvent')?1:0, $this->input->post('Description'), 
						$this->input->post('Location'), $this->input->post('colorvalue'), 
						$this->input->post('status'), $this->input->post('timezone')
					);
				}
			break;
		}
		echo json_encode($ret);
	}
	
	function addCalendar($st, $et, $sub, $ade,$status)
	{
		$ret = array();
		$this->load->model('agenda_model');
		try{

			if(!$this->agenda_model->insert_data($sub,$st,$et,$ade,$status))
			{
				$ret['IsSuccess'] = false;
				$ret['Msg'] = 'Error';
			}else{
				$ret['IsSuccess'] = true;
				$ret['Msg'] = 'add success';
				$ret['Data'] = $this->agenda_model->get_id();
			}
						
		}catch(Exception $e){
			$ret['IsSuccess'] = false;
			$ret['Msg'] = $e->getMessage();
		}
		return $ret;
	}

	function addDetailedCalendar($st, $et, $sub, $ade, $dscr, $loc, $color, $status, $tz)
	{
		$ret = array();
		$this->load->model('agenda_model');
		try{
			if(!$this->agenda_model->insert_detail($st, $et, $sub, $ade, $dscr, $loc, $color, $status, $tz))
			{
				$ret['IsSuccess'] = false;
				$ret['Msg'] = 'Error';
			}else{
				$ret['IsSuccess'] = true;
				$ret['Msg'] = 'Kegiatan Telah ditambahkan';
				$ret['Data'] = $this->agenda_model->get_id();			
			}
			
		}catch(Exception $e){
			$ret['IsSuccess'] = false;
			$ret['Msg'] = $e->getMessage();
		}
		return $ret;
	}

	function listCalendarByRange($sd, $ed)
	{
		$ret = array();
		$ret['events'] = array();
		$ret["issort"] = true;
		$ret["start"]  = date("d/m/Y H:i", $sd);
		$ret["end"]    = date("d/m/Y H:i", $ed);
		$ret['error']  = null;
		$this->load->model('agenda_model');
		try
		{			
			$result = $this->agenda_model->get_data($sd,$ed);
			
			if ($result->num_rows() > 0)
			{
				foreach ($result->result() as $row)
				{					
					/*$arr   = date_parse($row->TGL_AWAL);
					$time1 = mktime($arr["hour"],$arr["minute"],$arr["second"],$arr["month"],$arr["day"],$arr["year"]);
					$arr   = date_parse($row->TGL_AKHIR);
					$time2 = mktime($arr["hour"],$arr["minute"],$arr["second"],$arr["month"],$arr["day"],$arr["year"]);*/
					
					$ret['events'][] = array(
									$row->ID_AGENDA,
									$row->SUBJECT,
									php2JsTime(DBStamp2PhpTime($row->TGL_AWAL) ),
									php2JsTime(DBStamp2PhpTime($row->TGL_AKHIR) ),
									$row->IS_ALLDAYEVENT,
									0, //more than one day event
									0,//$row->InstanceType Recurring event
									$row->WARNA_AGENDA,
									1,//editable
									$row->LOKASI,
									''//$attends
							);
				}
			} 			
			
		}catch(Exception $e){
			$ret['error'] = $e->getMessage();
		}
		return $ret;
	}

	function listCalendar($day, $type)
	{
		$phpTime = js2PhpTime($day);
		//echo $phpTime . "+" . $type;
		switch($type){
			case "month":
				$st = mktime(0, 0, 0, date("m", $phpTime), 1, date("Y", $phpTime));
				$et = mktime(0, 0, -1, date("m", $phpTime)+1, 1, date("Y", $phpTime));
			break;
			case "week":
				//suppose first day of a week is monday 
				$monday  =  date("d", $phpTime) - date('N', $phpTime) + 1;
				//echo date('N', $phpTime);
				$st = mktime(0,0,0,date("m", $phpTime), $monday, date("Y", $phpTime));
				$et = mktime(0,0,-1,date("m", $phpTime), $monday+7, date("Y", $phpTime));
			break;
			case "day":
				$st = mktime(0, 0, 0, date("m", $phpTime), date("d", $phpTime), date("Y", $phpTime));
				$et = mktime(0, 0, -1, date("m", $phpTime), date("d", $phpTime)+1, date("Y", $phpTime));
			break;
		}
		//echo $st . "--" . $et;
		return $this->listCalendarByRange($st, $et);
	}

	function updateCalendar($id, $st, $et)
	{
		$ret = array();
		$this->load->model('agenda_model');
		try{
			if(!$this->agenda_model->update_data($id, $st, $et))
			{
				$ret['IsSuccess'] = false;
				$ret['Msg'] = mysql_error();			
			}else{
				$ret['IsSuccess'] = true;
				$ret['Msg'] = 'Kegiatan telah diperbaharui';
			}
			
		}catch(Exception $e){
			$ret['IsSuccess'] = false;
			$ret['Msg'] = $e->getMessage();
		}
		return $ret;
	}

	function updateDetailedCalendar($id, $st, $et, $sub, $ade, $dscr, $loc, $color, $tz)
	{
		$ret = array();
		try{
			$this->load->model('agenda_model');			
			if(!$this->agenda_model->update_detail($id, $st, $et, $sub, $ade, $dscr, $loc, $color, $tz))
			{
				$ret['IsSuccess'] = false;
				$ret['Msg'] = mysql_error();			
			}else{
				$ret['IsSuccess'] = true;
				$ret['Msg'] = 'Kegiatan telah diperbaharui';
			}
			
		}catch(Exception $e){
			$ret['IsSuccess'] = false;
			$ret['Msg'] = $e->getMessage();
		}
		return $ret;
	}

	function removeCalendar($id)
	{
		$ret = array();
		try{
			$this->load->model('agenda_model');
			if(!$this->agenda_model->delete_data($id))
			{
				$ret['IsSuccess'] = false;
				$ret['Msg'] = 'error hapus';
			}else{
				$ret['IsSuccess'] = true;
				$ret['Msg'] = 'Succefully';			
			}
		}catch(Exception $e){
			$ret['IsSuccess'] = false;
			$ret['Msg'] = $e->getMessage();
		}
		return $ret;
	}
		
}

?>