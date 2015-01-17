<?php

function set_param_jqgrid($param)
{
	$CI = &get_instance();
	/*
	$page = $_REQUEST['page']; // get the requested page 
	$limit = $_REQUEST['rows']; // get how many rows we want to have into the grid 
	$sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort 
	$sord = $_REQUEST['sord']; // get the direction if(!$sidx) $sidx =1;  
	 
	$req_param = array (
			"id"=>$id,
			"sort_by" => $sidx,
			"sort_direction" => $sord,
			"limit" => null,
			"search" => $_REQUEST['_search'],
			"search_field" => isset($_REQUEST['searchField'])?$_REQUEST['searchField']:null,
			"search_operator" => isset($_REQUEST['searchOper'])?$_REQUEST['searchOper']:null,
			"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null,
			"objek_pdrd"=>"PRIBADI"
	);*/
	

}

function jqgrid_set_where($owhere='')
{
	$CI = &get_instance();
	
	$sidx  = $_REQUEST['sidx']; // get index row - i.e. user click to sort 
	$sord  = $_REQUEST['sord']; // get the direction if(!$sidx) $sidx =1;  
	 
	$param = array (
		"sort_by" => $sidx,
		"sort_direction" => $sord,
		"limit" => null,
		"search" => $_REQUEST['_search'],
		"search_field" => isset($_REQUEST['searchField'])?$_REQUEST['searchField']:null,
		"search_operator" => isset($_REQUEST['searchOper'])?$_REQUEST['searchOper']:null,
		"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null
	);
	
	if($param['search'] != null && $param['search'] === 'true')
	{
		$wh = "UPPER(".$param['search_field'].")";
		$param['search_str'] = strtoupper($param['search_str']);
		switch ($param['search_operator']) {
			case "bw": // begin with
				$wh .= " LIKE '".$param['search_str']."%'";
				break;
			case "ew": // end with
				$wh .= " LIKE '%".$param['search_str']."'";
				break;
			case "cn": // contain %param%
				$wh .= " LIKE '%".$param['search_str']."%'";
				break;
			case "eq": // equal =
				$wh .= " = '".$param['search_str']."'";
				break;
			case "ne": // not equal
				$wh .= " <> '".$param['search_str']."'";
				break;
			case "lt":
				$wh .= " < '".$param['search_str']."'";
				break;
			case "le":
				$wh .= " <= '".$param['search_str']."'";
				break;
			case "gt":
				$wh .= " > '".$param['search_str']."'";
				break;
			case "ge":
				$wh .= " >= '".$param['search_str']."'";
				break;
			default :
				$wh = "";
		}
		$CI->db->where($wh);
	}
	//($param['limit'] != null ? $CI->db->limit($param['limit']['end'], $param['limit']['start']) : '');
	
	if(isset($owhere['where']) && $owhere['where']!='') $CI->db->where($owhere['where']);
	
	($param['sort_by'] != null) ? $CI->db->order_by($param['sort_by'], $param['sort_direction']) :'';	
	
}

function jqgrid_set_limit($table,$owhere='')
{
	$CI = &get_instance();
					
	$page  = $_REQUEST['page']; // get the requested page 
	$limit = $_REQUEST['rows']; // get how many rows we want to have into the grid 
		
	$CI->db->trans_start();

	jqgrid_set_where($owhere);
	
	//if(isset($owhere) && $owhere!='') $CI->db->where($owhere);
	
	$CI->db->from($table);
	$CI->db->select('count(*)');
	if(isset($owhere['group'])) $CI->db->group_by($owhere['group']);
	$row = $CI->db->get()->row()->COUNT;
	$CI->db->trans_complete();
	
	//$count = count($row); 
	$count = $row;
	
	if( $count >0 ) { 
		$total_pages = ceil($count/$limit); 
	} else { 
		$total_pages = 0; 
	} 
	if ($page > $total_pages) $page=$total_pages; 
	
	$start = $limit*$page - $limit; // do not put $limit*($page - 1) 
	if($start <0) $start = 0;
		
	return array('start'=>$start,'limit'=>$limit,'page'=>$page,'total_pages'=>$total_pages,'records'=>$count);
	
	//$CI->db->limit($limit, $start);
	
}

?>