<?php

class Menu_model extends Model {

	var $data;
	
	function Menu_model()
	{
		parent::Model();
		$this->groupmenu    = 'G_GROUPFRONTMENUS';		
		$this->subgroupmenu = 'G_SUBGROUPFRONTMENUS';
		$this->frontmenu    = 'G_FRONTMENUS';
		$this->granted      = 'G_GRANTEDFRONTMENUS';
	}
	
	function get_groupmenu()
	{
		$this->db->select('a.nid,a.bhide,a.cgroup');
		$this->db->from($this->groupmenu.' a');
		$this->db->order_by(' a.nurut, a.cgroup');
		$result = $this->db->get();
		return $result;
	}
	
	function get_subgroupmenu($nid_header)
	{
		$query = "select * from ".$this->subgroupmenu." where nid=".$this->db->escape($nid_header);
		$result = $this->db->query($query);
		return $result;
	}
	
	function get_frontmenu($id_group)
	{
		if(b_admin(b_getuserlogin()))
		{
			$cwhere="where a.nid_groupfrontmenus=".$this->db->escape($id_group)." and a.BHIDE=0";
		}
		else{
			$cwhere="left join ".$this->granted." as b on a.nid=b.nid_frontmenus
					where a.nid_groupfrontmenus=".$this->db->escape($id_group)." and 
					(b.nid_users='".b_getuserlogin()."' or a.bsecure=0)";
		}
		
		$query = "select a.nid,a.is_main,a.bsecure,a.bhide,a.cmenu,a.width,a.height,a.nid_header,a.cfunction,a.ci_controller,a.ci_func_controller from ".$this->frontmenu." as a $cwhere order by a.nid_header, a.NURUT";
		
		$result = $this->db->query($query);
		
		if($id_group==0) {

		}
		
		return $result;
		
	}
	
	function get_frontmenu_all()
	{		
		$query  = 'select a.nid,nid_parent,cmenu,ci_path,ci_controller,ci_func_controller,icon_class,is_main from '.$this->frontmenu.' as a ';
						
		$cwhere = ' a.bhide=0 ';
		
		if(b_admin(b_getuserlogin()))
		{
			
		}else{
		
			$query.= 'join '.$this->granted.' as d on d.nid_frontmenus=a.nid ';
		
			$cwhere.=' and d.nid_users='.$this->db->escape($this->session->userdata('SESS_USER_ID'));
			
		}
		$query.= ' where '.$cwhere;
		
		$query.=' order by a.nurut ';
		
		$result = $this->db->query($query);
		
		return $result;
		
	}
	
	function get_grantedmenu()
	{
	
	}
	
	function get_name_by_controller($controller)
	{
		$this->db->select('cmenu');
		$this->db->where('ci_controller',$controller);
		$result = $this->db->get($this->frontmenu);
		
		if($result->num_rows()>0)
		{	
			return $result->first_row();
			
		}else{
			return FALSE;
		}
		
	}
	
}

?>