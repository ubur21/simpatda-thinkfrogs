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
$exception = array('nomor');

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
switch($postConfig['action']){
	case $crudConfig['read']:
		/* ----====|| ACTION = READ ||====----*/
		if($DEBUGMODE == 1){$firephp->info('READ','action');}
		/*query to count rows*/
		if ($postConfig['q'] <> q) {
		      $sql='select count(*) as numRows from '.$crudTableName.' where '.$crudFK.' = '.$postConfig[$crudFK];
			  //$sql = 'select count(*) as numRows from anggaran_tahun ';
        }
        else {
			  $sql='select count('.$crudFK.') as numRows from '.$crudTableName;
			  //$sql = 'select count(*) as numRows from anggaran_tahun ';
		}; 
		
		if($DEBUGMODE == 1){$firephp->info($sql,'query');}
        $result = gcms_query($sql);
        $row = gcms_fetch_assoc($result);
        $count = $row["NUMROWS"];
            
		if($DEBUGMODE == 1){$firephp->info($count,'rows');}
        $intLimit = $postConfig['limit'];
        /*set the page count*/
            
		if( $count > 0 && $intLimit > 0) { $total_pages = ceil($count/$intLimit); } 
		else { $total_pages = 1; } 
        
		if($DEBUGMODE == 1){$firephp->info($total_pages,'total_pages');}
		$intPage = $postConfig['page'];
        
		if ($intPage > $total_pages){$intPage=$total_pages;}
		$intStart = (($intPage-1) * $intLimit);
        
		/*Run the data query*/
		if ($postConfig['q'] <> q) {
			//$sql = 'select FIRST '.$intLimit.' SKIP '.$intStart.' '.implode(',',$crudColumns).' from '.$crudTableName.' where '.$crudFK.' = '.$postConfig[$crudFK];
			
			$sql = 'SELECT 
				  PEMOHON.PEMOHON_ID,
				  PEMOHON.NAMA,
				  PEMOHON.NO_KTP,
				  PEMOHON.TEMPAT_LAHIR,
				  PEMOHON.TANGGAL_LAHIR,
				  PEMOHON.PEKERJAAN,
				  PEMOHON.ALAMAT,
				  PEMOHON.RT,
				  PEMOHON.RW,
				  PEMOHON.KODEPOS,
				  PEMOHON.ID_DESA,
				  PEMOHON.NO_TELP,
				  PEMOHON.NO_HP
				FROM
				  PEMOHON
				  INNER JOIN KELURAHAN ON (PEMOHON.ID_DESA = KELURAHAN.LURAH_ID)';
			
        } 
        else  {
            //$sql = 'select FIRST '.$intLimit.' SKIP '.$intStart.' '.implode(',',$crudColumns).' from '.$crudTableName;
			$sql = 'SELECT 
				  PEMOHON.PEMOHON_ID,
				  PEMOHON.NAMA,
				  PEMOHON.NO_KTP,
				  PEMOHON.TEMPAT_LAHIR,
				  PEMOHON.TANGGAL_LAHIR,
				  PEMOHON.PEKERJAAN,
				  PEMOHON.ALAMAT,
				  PEMOHON.RT,
				  PEMOHON.RW,
				  PEMOHON.KODEPOS,
				  PEMOHON.ID_DESA,
				  PEMOHON.NO_TELP,
				  PEMOHON.NO_HP
				FROM
				  PEMOHON
				  INNER JOIN KELURAHAN ON (PEMOHON.ID_DESA = KELURAHAN.LURAH_ID)';
			
        };
            
		if($postConfig['search'] == 'true'){
        	$sql .= ' WHERE UPPER('.$postConfig['searchField'].') '.fnSearchCondition($_POST['searchOper'], $postConfig['searchStr']);
		};
            
		$sql .= ' ORDER BY ' . $postConfig['sortColumn'] . ' ' . $postConfig['sortOrder']; 
		
		//if($postConfig['search'] == true){ $sql .= ' where '.$searchCondition; }
		if($DEBUGMODE == 1){$firephp->info($sql,'query');}
		$result = gcms_query( $sql ) 
		or die($firephp->error('Couldn t execute query.'.mysql_error()));
		/*Create the output object*/
		$o->page = $intPage; 
		$o->total = $total_pages;
		$o->records = $count;
		$i=0;
		while($row = gcms_fetch_row($result)) { 
			/* 1st column needs to be the id, even if it's not named ID */
			$o->rows[$i]['id']=$row[0];
			/* assign the row contents to a row var. */
			$o->rows[$i][$crudConfig['row']]=$row;
			$i++;
		}
		break;
        
        
	    case $crudConfig['create']:
		/* ----====|| ACTION = CREATE ||====----*/
		if($DEBUGMODE == 1){$firephp->info('CREATE','action');}
		/*basic start to the insert query*/
		//print_r( $crudColumnValues );
		unset($crudColumns['id']) ;		
		unset($crudColumnValues['id']) ;
        unset($crudColumns[$crudAutoField]);
        unset($crudColumnValues[$crudAutoField]);
		//print_r( $crudColumnValues );
		if ($postConfig['q'] <> q) 
		  $crudColumnValues[$crudFK] = $postConfig[$crudFK];
        
		$exception = array(strtolower($crudFK));
		$other_request = array('id_desa'=>$_REQUEST['desa']);
		$a = $fbird->FBInsert($crudTableName,$other_request,$exception);

		break;
        
        
	case $crudConfig['update']:
		/* ----====|| ACTION = UPDATE ||====----*/
		if($DEBUGMODE == 1){$firephp->info('UPDATE','action');}
	
        unset($crudColumns[$crudAutoField]);
        unset($crudColumnValues[$crudAutoField]);
		
		if ($postConfig['q'] <> q) 
		  $crudColumnValues[$crudFK] = $postConfig[$crudFK];
       	  
		$exception = array(strtolower($crudFK));
		$other_request = array('id_desa'=>$_REQUEST['desa']);
		$str_where = ' where '.$crudFK.'='.quote_smart($_REQUEST['id']);	
		$a = $fbird->FBUpdate($crudTableName,$other_request,$exception,$str_where);
	
		break;
        
        
	case $crudConfig['delete']:
		/* ----====|| ACTION = DELETE ||====----*/
		if($DEBUGMODE == 1){$firephp->info('DELETE','action');}

		if ($postConfig['q'] <> q) $crudColumnValues[$crudFK] = $postConfig[$crudFK];

		$val = substr(trim($crudColumnValues['id']),1,count($crudColumnValues['id'])-2);
		
		$id_del = explode(',',$val);
		foreach($id_del as $del){
			$cek = b_fetch('select count(*) from pendaftaran where id_pemohon='.quote_smart($del).' and objek_pdrd=\'PRIBADI\'');
			
			if(!$cek){
		
				$sql = 'Delete from '.$crudTableName.' where '.$crudFK.' = '.quote_smart($del);
				if($DEBUGMODE == 1){$firephp->info($sql,'query');}
				gcms_query( $sql ) or die($firephp->error('Couldn t execute query.'.mysql_error()));
			
			}
		}
		 
		break;
	}

/* ----====|| SEND OUTPUT ||====----*/
if($DEBUGMODE == 1){$firephp->info('End Of Script','status');}
print json_encode($o);

?>