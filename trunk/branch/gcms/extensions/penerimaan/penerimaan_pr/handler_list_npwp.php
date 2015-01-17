<?php
include "global.php";

/*
*|	jqGrid PHP MYSQL AJAX CRUD Template
*|	by: Anthony Aragues
*|	http://suddendevelopment.com
*|	Resources:
*|	jqGrid:  http://www.trirand.com/blog/
*|	jquery:  http://jquery.com/
*|
*|	this page is intended to be accessed through an ajax application. Not directly by a user.
*/
//require_once('../inc/JSON.php'); //Adds JSON functions if you are missing them in your PHP install
/*----====|| CONFIG ||====----*/
/* each AJAX framework may have differeny keywords for these features. */
/* NOTE: If you are creating multiple copies of this file I highly recommend you abstract this out to a separate file and include it where needed. */
$DEBUGMODE = 1;								/* Set to 1 if you want firephp output */

$examp = $_REQUEST["q"]; //query number

/* id column */
/* list the column names that are being sent to this script (Input variables)  the first one should be the primary key. */
/* columns array format:  $_POST['VARIABLE'] => 'DB column name' */

$postConfig['id'] = 'id';
$crudFK = $ID.'ID';
$postConfig[$crudFK] = $crudFK; 
$postConfig['q'] = 'q'; 

/*----====|| end CONFIG ||====----*/
/* jqGrid specifi settings, don;t need to be modified if using jqgrid  */
$postConfig['search'] = '_search'; 			/* search */
$postConfig['searchField'] = 'searchField'; /* searchField */
$postConfig['searchOper'] = 'searchOper'; 	/* searchOper */
$postConfig['searchStr'] = 'searchString'; 	/* searchString */
$postConfig['action'] = 'oper'; 			/* action variable */
$postConfig['sortColumn'] = 'sidx'; 		/* sort column */
$postConfig['sortOrder'] = 'sord'; 			/* sort order */
$postConfig['page'] = 'page'; 				/* current requested page */
$postConfig['limit'] = 'rows';				/* restrict number of rows to return */
$crudConfig['row'] = 'cell'; 				/* row data identifier */
$crudConfig['read'] = 'oper'; 				/* action READ keyword *//* set to be the same as action keyword for default */
$crudConfig['create'] = 'add';				/* action CREATE keyword */
$crudConfig['update'] = 'edit';				/* action UPDATE keyword */
$crudConfig['delete'] = 'del';				/* action DELETE keyword */
$crudConfig['totalPages'] = 'total';		/* total pages */
$crudConfig['totalRecords'] = 'records';	/* total records */
$crudConfig['responseSuccess'] = 'success';	/* total records */
$crudConfig['responseFailure'] = 'fail';	/* total records */
/* end of jqgrid specific settings */
$o=null;
/*----====|| SETUP firePHP ||====----*/
/*  http://www.firephp.org/  */
if($DEBUGMODE == 1){
	require_once('inc/FirePHP.class.php'); // adds nice logging library
	ob_start();
	$firephp = FirePHP::getInstance(true);
}
/*----====|| SETUP SEARCH CONDITION ||====----*/
function fnSearchCondition($searchOperation, $searchString){
    $searchString =  strtoupper($searchString);
	switch($searchOperation){
		case 'eq': $searchCondition = '= \''.$searchString.'\''; break;
		case 'ne': $searchCondition = '!= \''.$searchString.'\''; break;
		case 'bw': $searchCondition = 'LIKE \''.$searchString.'%\''; break;
		case 'ew': $searchCondition = 'LIKE \'%'.$searchString.'\''; break;
		case 'cn': $searchCondition = 'LIKE \'%'.$searchString.'%\''; break;
        case 'nc': $searchCondition = 'NOT LIKE \'%'.$searchString.'%\''; break;
		case 'lt': $searchCondition = '< \''.$searchString.'\''; break;
		case 'gt': $searchCondition = '> \''.$searchString.'\''; break;
		case 'le': $searchCondition = '<= \''.$searchString.'\''; break;
		case 'ge': $searchCondition = '>= \''.$searchString.'\''; break;
		
	}
	return $searchCondition;
}
/*----====|| INPUT VARIABLE CLEAN FUNCTION||====----*/
/* you can replace this with a call to a clean function you prfer, or leave it as is.*/
function fnCleanInputVar($string){
	//$string = mysql_real_escape_string($string);
	return $string;
}
/*----====|| GET and CLEAN THE POST VARIABLES ||====----*/
foreach ($postConfig as $key => $value){ 
	if(isset($_REQUEST[$value])){
		$postConfig[$key] = fnCleanInputVar($_REQUEST[$value]); 
	}
}
foreach ($crudColumns as $key => $value){ 
	if(isset($_REQUEST[$key])){        
        switch ($key) {
            case 'indikator_1':
            case 'indikator_2':
            case 'indikator_3':
            case 'indikator_4':
              if ( $_REQUEST[$key] == '' ){ $_REQUEST[$key] = 0; };
              $crudColumnValues[$key] = $_REQUEST[$key];
              break;
            default :
              $crudColumnValues[$key] = '\''.fnCleanInputVar($_REQUEST[$key]).'\'';
        }
	}
}

/*----====|| INPUT VARIABLES ARE CLEAN AND CAN BE USED IN QUERIES||====----*/
/*----====|| ACTION SWITCH ||====----*/
if($DEBUGMODE == 1){$firephp->info($postConfig['action'],'action');}
switch($postConfig['action'])
{
	case $crudConfig['read']:
		/* ----====|| ACTION = READ ||====----*/
		if($DEBUGMODE == 1){$firephp->info('READ','action');}
		/*query to count rows*/
		if ($postConfig['q'] <> q) {
			$sql = 
			   'select count(*) as numRows from '.$crudTableName.' ';
        }
        else {
			if($_REQUEST['id']!=''){

				$sql = 'select
						count(*) as numRows
						from penetapan_pr a
						join penetapan_pr_content b on a.penetapan_pr_id=b.penetapan_pr_id
						join v_pendataan c on c.pendataan_id=b.pendataan_id
						left join penerimaan_pr d on a.penetapan_pr_id=d.penetapan_pr_id
						where a.penetapan_pr_id='.quote_smart($_REQUEST['id']).' and d.penetapan_pr_id is null ';
						
			}else{
			
				$sql = ' select
						count(*) as numRows
						from penetapan_pr a
						join penetapan_pr_content b on a.penetapan_pr_id=b.penetapan_pr_id
						join v_pendataan c on c.pendataan_id=b.pendataan_id
						left join penerimaan_pr d on a.penetapan_pr_id=d.penetapan_pr_id
						where d.penetapan_pr_id is null ';
			
			}
		}; 
		
		if($DEBUGMODE == 1){$firephp->info($sql,'query');}
        $result = gcms_query($sql);
		while($row = gcms_fetch_assoc($result)) $count+=$row["NUMROWS"];
          
		if($DEBUGMODE == 1){$firephp->info($count,'rows');}
        $intLimit = $postConfig['limit'];
        /*set the page count*/
            
		if( $count > 0 && $intLimit > 0) { $total_pages = ceil($count/$intLimit); } 
		else { $total_pages = 1; } 
        
		if($DEBUGMODE == 1){$firephp->info($total_pages,'total_pages');}
		$intPage = $postConfig['page'];
        
		if ($intPage > $total_pages){$intPage=$total_pages;}
		$intStart = (($intPage-1) * $intLimit);
        		
		if($postConfig['search'] == 'true'){
        	$search.= ' and UPPER('.$postConfig['searchField'].') '.fnSearchCondition($_POST['searchOper'], $postConfig['searchStr']);
		};
		
		/*Run the data query*/
		if ($postConfig['q'] <> q) {
							
        } 
        else{
			
			if($_REQUEST['id']!=''){

				$sql = 'select
						c.pendaftaran_id as id,
						c.npwp,
						c.nama
						from penetapan_pr a
						join penetapan_pr_content b on a.penetapan_pr_id=b.penetapan_pr_id
						join v_pendataan c on c.pendataan_id=b.pendataan_id
						left join penerimaan_pr d on a.penetapan_pr_id=d.penetapan_pr_id
						where d.penetapan_pr_id is null and a.penetapan_pr_id='.quote_smart($_REQUEST['id']);
						
			}else{
			
				$sql = ' select
						c.pendaftaran_id as id,
						c.npwp,
						c.nama
						from penetapan_pr a
						join penetapan_pr_content b on a.penetapan_pr_id=b.penetapan_pr_id
						join v_pendataan c on c.pendataan_id=b.pendataan_id 
						left join penerimaan_pr d on a.penetapan_pr_id=d.penetapan_pr_id
						where d.penetapan_pr_id is null	';
			
			}
			
        };
		//$sql .= ' ORDER BY ' . $postConfig['sortColumn'] . ' ' . $postConfig['sortOrder'];
		
		if($DEBUGMODE == 1){$firephp->info($sql,'query');}
		$result = gcms_query( $sql ) 
		or die($firephp->error('Couldn t execute query.'.mysql_error()));
		/*Create the output object*/
		$o->page = $intPage; 
		$o->total = $total_pages;
		$o->records = $count;
		$i=0;
		while($row = gcms_fetch_row($result)){ 
			/* 1st column needs to be the id, even if it's not named ID */
			$o->rows[$i]['id']=$row[0];
			/* assign the row contents to a row var. */
			$o->rows[$i][$crudConfig['row']]=$row;
			$i++;
		}
		break;
       
}

/* ----====|| SEND OUTPUT ||====----*/
if($DEBUGMODE == 1){$firephp->info('End Of Script','status');}
print json_encode($o);

?>