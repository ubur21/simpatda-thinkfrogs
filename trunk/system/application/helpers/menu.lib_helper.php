<?php

/**
 * Mengambil data menu
 */
function load_menu_x()
{
	$i = 0;
  	$menus = array();
	$CI = &get_instance();	
	$CI->load->model('menu_model');
	
	//$this->Menu_model->

	$rs_group = $CI->menu_model->get_groupmenu();
	
	if ($rs_group->num_rows() > 0)
	{
		foreach ($rs_group->result() as $ogroup)
		{
			$rs_menu = $CI->menu_model->get_frontmenu($ogroup->NID);
			
			if($rs_menu->num_rows() > 0)
			{
				if($ogroup->BHIDE==0)
				{
					$menus[$i][0] = str_replace(" ","&nbsp;",stripslashes($ogroup->CGROUP));
					$j = 1; $k=0; $id_lama=' '; $flag_g='';
					
					foreach ($rs_menu->result() as $omenu)
					{
						if($omenu->BSECURE and b_logged() and $omenu->BHIDE==0)
						{
							$nm = str_replace(".", "_", str_replace("-", "_", str_replace(" ", "_", stripslashes($omenu->CMENU))));
							if (!$omenu->WIDTH) $omenu->WIDTH = 700;
							if (!$omenu->HEIGHT) $omenu->HEIGHT = 500;
							if (isset($omenu->NID_HEADER))
							{
								if($omenu->NID_HEADER!=$id_lama)
								{
									if($flag_g==1) $j++;
									$k=0;
									$rs_submenu = $CI->menu_model->get_subgroupmenu($omenu->NID_HEADER);
									
									$nama_skpd = $rs_submenu->row()->CNAME;
									
									$menus[$i][$j][$k]=$nama_skpd;
									$flag_g=1;
								}
								$k++;
								$id_lama = $omenu->NID_HEADER;
								if ($omenu->IS_MAIN)
								{
									$menus[$i][$j][$k][0]="index.php?page=".$omenu->NID;
								}
								else{
									$menus[$i][$j][$k][0]="javascript:gcms_open_form('form.php?page=".$omenu->NID."','".$nm."',".$omenu->WIDTH.",".$omenu->HEIGHT.")";
								}
								$menus[$i][$j][$k][1]=stripslashes($omenu->CMENU);
							}
							else{
								if ($omenu->IS_MAIN){
									$menus[$i][$j][0] = "index.php?page=".$omenu->NID;
								}else {
									$menus[$i][$j][0] = "javascript:gcms_open_form('form.php?page=".$omenu->NID."','".$nm."',".$omenu->WIDTH.",".$omenu->HEIGHT.")";
								}
								$menus[$i][$j][1] = stripslashes($omenu->CMENU);
								$j++;
								$flag_g=0;							
							}						
							$i++;	
						}						
					}					
				}			
			}			
		}
	} 
	
	$rs_menu = $CI->menu_model->get_frontmenu('0');
	if($rs_menu->num_rows() > 0)
	{
		foreach ($rs_menu->result() as $omenu)
		{
			if($omenu->BSECURE and b_logged() and $omenu->BHIDE==0)
			{
				$nm = str_replace(".", "_", str_replace("-", "_", str_replace(" ", "_", stripslashes($omenu->CMENU))));
				if (!$omenu->WIDTH) $omenu->WIDTH = 700;
				if (!$omenu->HEIGHT) $omenu->HEIGHT = 500;
				
				$menus[$i][0] = stripslashes($omenu->CMENU);
				
				if ($omenu->IS_MAIN) {
					$menus[$i][1][0] = "index.php?page=".$omenu->NID;
				}else {
					$menus[$i][1][0] = "javascript:gcms_open_form('form.php?page=".$omenu->NID."','".$nm."',".$omenu->WIDTH.",".$omenu->HEIGHT.")";
				}
				$i++; 
			}
		}
	}
		
  	return $menus;
}

function load_menus()
{
	$menus = array();
	$CI = &get_instance();
	$CI->load->model('menu_model');
	
	$rs_menu = $CI->menu_model->get_frontmenu_all();
	$menus = array(); $tmp = array();
	if($rs_menu->num_rows()>0)
	{		
		foreach($rs_menu->result() as $omenu)
		{
			$menus[$omenu->NID_PARENT][] = $omenu;
		}
		
	}	
	return $menus;
}

function format_menu($data, $parent = 0) {
	static $i = 1; $start=0;
	$tab = str_repeat("\t\t", $i);	
	if (isset($data[$parent])) {
		$start++;
		$html = ($start==1) ? "\n$tab<ul id='menu'>" : "\n$tab<ul>";
		$i++;
		foreach ($data[$parent] as $v) {
			//$child = get_menu($data, $v->id);
			$child = format_menu($data, $v->NID);
			$html .= ($child) ? "\n\t$tab<li class='node'>" : "\n\t$tab<li>";
			//$html .= '<a href="'.$v->url.'">'.$v->title.'</a>';
			
			$url = '';
			
			$url.= (isset($v->CI_PATH) ? $v->CI_PATH : '');
			$url.= '/'.$v->CI_CONTROLLER;
			$url.= (!isset($v->CI_FUNC_CONTROLLER) || $v->CI_FUNC_CONTROLLER=='index') ? '' : '/'.$v->CI_FUNC_CONTROLLER;
			
			$url = site_url($url);
						
			if($v->IS_MAIN){
				$html .= '<a href="'.$url.'" '.($child ? '' : ' class="'.$v->ICON_CLASS.'"' ).'>'.$v->CMENU.'</a>';
			}else{
				$html .= '<a href="#" onclick="javascript:window.open(\''.site_url().''.$v->CI_CONTROLLER.'/'.$module.'\',\''.$module.'\',500,500);"'.($child ? '' : ' class="'.$v->ICON_CLASS.'"' ).')>'.$v->CMENU.'</a>';
			}
			
			if ($child) {
				$i--;
				$html .= $child;
				$html .= "\n\t$tab";
			}
			$html .= '</li>';
		}
		$html .= "\n$tab</ul>";
		return $html;
	} else {
		return false;
	}
}

function get_name_menu($controller)
{
	$CI = &get_instance();
	$CI->load->model('menu_model');
	$result = $CI->menu_model->get_name_by_controller($controller);
	return $result->CMENU;
}

function setAction()
{

}

?>