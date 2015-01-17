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
//
//$dbConfig['dbServer'] = 'admin'; 		/* Server */
//$dbConfig['dbUser'] = 'tes'; 				/* Username */
//$dbConfig['dbPassword'] = 'tes'; 				/* Password */
//$dbConfig['dbDatabase'] = 'test'; 		/* Database */

$examp = $_REQUEST["q"]; //query number

/* id column */
/* list the column names that are being sent to this script (Input variables)  the first one should be the primary key. */
/* columns array format:  $_POST['VARIABLE'] => 'DB column name' */

/*
$crudColumns =  array(
	'id'=>'id'
	,'kode'=>'kode_kelompok'
	,'nama'=>'nama_kelompok'
	,'keterangan'=>'keterangan'
);
$crudTableName = 'kelompok_kegiatan';
*/
$postConfig['id'] = $ID.'ID';
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
$crudConfig['view'] = 'view';				/* action DELETE keyword */
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
switch($postConfig['action']){
	case $crudConfig['read']:
		/* ----====|| ACTION = READ ||====----*/
		if($DEBUGMODE == 1){$firephp->info('READ','action');}
		/*query to count rows*/
		if ($postConfig['q'] <> q) {
		      $sql='select count(*) as numRows from '.$crudTableName.' where '.$crudFK.' = '.$postConfig[$crudFK];
            }
         else {
			   $sql='select count('.$postConfig['id'].') as numRows from '.$crudTableName;
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
		  $sql = 'select FIRST '.$intLimit.' SKIP '.$intStart.' '.implode(',',$crudColumns).' from '.$crudTableName.' where '.$crudFK.' = '.$postConfig[$crudFK];
            } 
            else  {
            //$sql = 'select FIRST '.$intLimit.' SKIP '.$intStart.' '.implode(',',$crudColumns).' from '.$crudTableName;
			$sql = "select 	pendataan_hotel.hotel_id,
							pendataan_spt.tgl_entry,
							pendaftaran.npwp,
							pendataan_hotel.hotel_nama,
							pendataan_hotel.hotel_alamat,
							pendataan_spt.jenis_pungutan,
							pendataan_spt.periode_awal,
							pendataan_spt.periode_akhir,
							rekening_kode.nama_rekening,
							pendataan_hotel.nominal,
							pendataan_hotel.persen_tarif,
							rekening_kode.kode_rekening
							 
					from pendataan_hotel pendataan_hotel 
						left join pendataan_spt pendataan_spt on pendataan_hotel.pendataan_id = pendataan_spt.pendataan_id
						left join pendaftaran pendaftaran on pendataan_spt.pendaftaran_id = pendaftaran.pendaftaran_id
						left join rekening_kode rekening_kode on pendataan_hotel.id_rekening = rekening_kode.id";
            };
        
		//if(!empty($crudTableJoin1)){
			//$sql .=".".$crudTableName." JOIN ".$crudTableJoin1.;
		//}
		
			/*if(!empty($crudTableJoin1)){
					$sql.=" ".$crudTableName." JOIN ".$crudTableJoin1." ".$crudTableJoin1." ON ".$crudTableName.".".$crudIdJoin1. "=" .$crudTableJoin1.".".$crudIdJoin1;
			}
	
			if(!empty($crudTableJoin1) && !empty($crudTableJoin2)){
	
					$sql.=" JOIN ".$crudTableJoin2." ".$crudTableJoin2." ON ".$crudTableJoin1.".".$crudIdJoin2. "=" .$crudTableJoin2.".".$crudIdJoin2;
	
			}
			if(!empty($crudTableJoin1) && !empty($crudTableJoin2) && !empty($crudTableJoin3)){
	
				   $sql.=" JOIN ".$crudTableJoin3." ".$crudTableJoin3." ON ".$crudTableName.".".$crudIdJoin3. "=" .$crudTableJoin3.".".$crudIdJoin3;
	
			}*/
			
		
		if(!empty($Filter) && $postConfig['search'] == 'false'){
			$sql .= " WHERE  ".$Filter." = '".$NilaiFilter."' ";
		}    
		
		if(!empty($Filter) && $postConfig['search'] == 'true'){
        	$sql .= " WHERE ".$Filter." = '".$NilaiFilter."' AND UPPER(" . $postConfig['searchField'] . ") " . fnSearchCondition($_POST['searchOper'], $postConfig['searchStr']);
		    };
		if(empty($Filter) && $postConfig['search'] == 'true'){
        	$sql .= " WHERE UPPER(" . $postConfig['searchField'] . ") " . fnSearchCondition($_POST['searchOper'], $postConfig['searchStr']);
		    };
        //if(!empty($crudTableJoin1)){
			$sql .= ' ORDER BY '.$crudTableName.'.'.$postConfig['id'].' ' . $postConfig['sortOrder']; 
		//}else{
		//	$sql .= ' ORDER BY '.$postConfig['id'].' ' . $postConfig['sortOrder']; 
		//}
		//echo $sql;
		//if($postConfig['search'] == true){ $sql .= ' where '.$searchCondition; }
		if($DEBUGMODE == 1){$firephp->info($sql,'query');}
		$result = gcms_query( $sql ) 
		or die($firephp->error('Couldn t execute query.'.mysql_error()));
		/*Create the output object*/
		$o->page = $intPage; 
		$o->total = $total_pages;
		$o->records = $count;
		$i=0;
		$no=1;
		while($row = gcms_fetch_row($result)) { 
			/* 1st column needs to be the id, even if it's not named ID */
			$o->rows[$i]['id']=$row[0];
			$data1 = $row;
			//"rows":[{"id":1,"cell":[1,"2","000123","000123","TES","Tes Alamat","Tes Lurah","Tes Camat","Tes Kabupaten"]}]
			/* assign the row contents to a row var. */
			$data1[11] = number_format((($row[9]*$row[10])/100),2,'.',',');
			$data1[9] = number_format($row[9],2,'.',',');
			array_unshift($data1,$no);
			$o->rows[$i][$crudConfig['row']]= $data1;
			$i++; $no++;
			//$o->rows[$i]['idx']=$i;
		}
		break;
        
		case $crudConfig['view']:
		?>
		<script>
		alert('xxxxxx');
		</script>
		<?
        break;
	    
		case $crudConfig['create']:
		/* ----====|| ACTION = CREATE ||====----*/
		if($DEBUGMODE == 1){$firephp->info('CREATE','action');}
		/*basic start to the insert query*/
//	    print_r( $crudColumnValues );
		unset($crudColumns['id']) ;		
		unset($crudColumnValues['id']) ;
        unset($crudColumns[$crudAutoField]);
        unset($crudColumnValues[$crudAutoField]);
//		echo '----';
//		print_r( $crudColumnValues );
		if ($postConfig['q'] <> q) 
		  $crudColumnValues[$crudFK] = $postConfig[$crudFK];
            
		$sql = 'insert into '.$crudTableName.'(';
		/*add the list of columns */
		$sql .= implode(',',$crudColumns);
		/*  !!! add any additional columns not defined in the column array here. */
		$sql .= ')VALUES(';
		/* add the values from POST vars */
		$sql .= implode(',',$crudColumnValues);
	
		/*  !!! add any additional columns not defined in the column array here. */
		$sql .= ')';
		if($DEBUGMODE == 1){$firephp->info($sql,'query');}
		//echo $sql;
		gcms_query( $sql ) 
		or die($firephp->error('Couldn t execute query.'.mysql_error()));
		break;
        
        
	case $crudConfig['update']:
		/* ----====|| ACTION = UPDATE ||====----*/
		if($DEBUGMODE == 1){$firephp->info('UPDATE','action');}
		print_r( $crudColumns );
		echo '----';
        unset($crudColumns[$crudAutoField]);
        unset($crudColumnValues[$crudAutoField]);
		print_r( $crudColumns );
		if ($postConfig['q'] <> q) 
		  $crudColumnValues[$crudFK] = $postConfig[$crudFK];
       	  
		$sql = 'update '.$crudTableName.' set ';
		/* create all of the update statements */
		foreach($crudColumns as $key => $value){ $updateArray[$key] = $value.'='.$crudColumnValues[$key]; };
		$sql .= implode(',',$updateArray);
		/* add any additonal update statements here */
		$sql .= ' where '.$postConfig['id'].' = '.$crudColumnValues['id'];
		if($DEBUGMODE == 1){$firephp->info($sql,'query');}
		gcms_query( $sql ) 
		//echo $sql;
		or die($firephp->error('Couldn t execute query.'.mysql_error()));
		break;
        
        
	case $crudConfig['delete']:
	//echo "delete";
		/* ----====|| ACTION = DELETE ||====----*/
		if($DEBUGMODE == 1){$firephp->info('DELETE','action');}
		print_r( $crudColumnValues );
		echo '----';
		print_r( $crudColumnValues );
		if ($postConfig['q'] <> q) 
		  $crudColumnValues[$crudFK] = $postConfig[$crudFK];

            
		$sql = 'Delete from '.$crudTableName.' where '.$postConfig['id'].' = '.$crudColumnValues['id'];
		
		//echo $crudColumnValues['id'];die;
		
		if($DEBUGMODE == 1){$firephp->info($sql,'query');}
		gcms_query( $sql ) 
		or die($firephp->error('Couldn t execute query.'.mysql_error()));
		break;
	}

/* ----====|| SEND OUTPUT ||====----*/
if($DEBUGMODE == 1){$firephp->info('End Of Script','status');}
print json_encode($o);

?>