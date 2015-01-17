<?
/*************************************************************/
// Filename	: metafire.lib.php                             
// Version	: 0.3                                         
// Author       : Meta Nurwidyanto                            
// Date         : 09/17/2002                                   
// Description  : Firebird DB Abstraction Layer                 
/**************************************************************/

class metafire {

	public $erro;
	public $erro_msg;
	
	//public $linhas;
	public $rows;
	private $coln;
	
	private $type_string = array('CHAR','VARCHAR');
	public $param_key = array();
	public $param_value = array();	
	public $param_opr = array();
	public $name_fields = array();
	
	private $tipocampo;
	private $tamcampo;

	var $strHost;
	var $strUser;
	var $strDatabase;
	var $intDebug;
	var $intUsePersistent;
	var $intTrans;
	var $intTransStatus;
	var $intCommit;

	var $intConn;
	var $intQuery;
	var $aryResult;
	var $aryRecord;
	
	// Constructor 
	//function metafire($strHost = 'localhost', $strDatabase = '/opt/interbase/examples/employee.gdb', $strUser = 'sysdba', $strPassword = 'd4rk5ky', $intUsePersistent = 1, $intDebug = 1) {
	function metafire($strHost = 'localhost', $strDatabase = 'C:/SIMTAP/htdocs/simpatda/db/simpatda.gdb', $strUser = 'sysdba', $strPassword = 'masterkey', $intUsePersistent = 1, $intDebug = 1) {
		$this->strHost = $strHost;
		$this->strDatabase = $strDatabase;
		$this->strUser = $strUser;
		$this->strPassword = $strPassword;
		$this->intUsePersistent = $intUsePersistent;
		$this->intDebug = $intDebug;

	} // end of Constructor

	//Connector
	function Connect() {

		if (!$this->intConn) {
			if ($this->intDebug) {
				echo "Establishing connection...		\n";
			}
				
			if($this->intUsePersistent) 
			{	
				$this->intConn = @ibase_pconnect($this->strHost . ':' . $this->strDatabase, $this->strUser, $this->strPassword)
							or die("Error:" . ibase_errmsg() . "<br>\n");
			} else {
				
				$this->intConn = @ibase_connect($this->strHost . ':' . $this->strDatabase, $this->strUser, $this->strPassword)
							or die("Error:" . ibase_errmsg() . "<br>\n");
			}
		}
		
		if (!$this->intConn) {
			if ($this->intDebug) {
				echo "Error establishing connection!\n";
			}
		} else {
			if ($this->intDebug) {
				echo "Connection established!<br>";
			}
		}
		
		return $this->intConn;
			
	}
	// end of Connector
	
	public function FBSelect($query){

		$result=0;
		
		//if($this->Connect()){
		if (!$this->intConn) {
			$this->intConn=$this->Connect();
		}
		
		if($this->intConn){
	  
			$query_array =explode(" ",strtolower($query));
			//if (in_array('select',$query_array) || in_array('insert',$query_array) || in_array('delete',$query_array) || in_array('update',$query_array)) {

			if(in_array('select',$query_array)) {
				$query_temp=preg_replace("/select .* from (.*)/si","select count(*) from \\1",$query);
				$query_temp=preg_replace("/order by .*/si"," \\1",$query_temp);
			
				//$this->result=@ibase_query($this->conex,$query);
				$this->Query($query);
			
				//if($this->result) {
				if($this->intQuery){
					
					$this->erro=0;
					$this->erro_msg='Query telah jalan';
				
					$this->coln=ibase_num_fields($this->intQuery);
					
					$result=@ibase_query($this->intConn,$query_temp);
					//$result=@ibase_query($this->Connect(),$query_temp);
					$count = @ibase_fetch_object($result); 
					//$this->linhas=$count->COUNT;
					$this->rows=$count->COUNT; 
					
					//$result=$this->result;
					$result=$this->intQuery;

				} else {
					$this->erro=ibase_errcode();
					$this->erro_msg=ibase_errmsg();
				}
			
			} else {
				$this->erro=99;
				$this->erro_msg='Query Gak Support';
			}    
			return $result;
		} 
	}	
	
	public function FBInsert($tableName,$other_request,$exception,$intDebug=0) {
	
		$nfield=0; $nother=0; $nexception = count($exception);
		
		if($this->isTableExist($tableName)) {
			
			$this->erro=0; $this->erro_msg=''; $this->rows=0;
		 
			if($this->erro==0) {
					   
				$qry_temp="INSERT INTO $tableName (";
				
				$sql = "select FIRST 1 SKIP 1 * from $tableName ";
				$intQuery=$this->Query($sql,0,1);	
				$coln = $this->GetNumFields($intQuery); 
				$this->setFieldTable($intQuery);
				
				foreach($this->name_fields as $values){
					if(!in_array($values,array_change_key_case($exception,CASE_LOWER))){
						$qry_temp.=$values.',';
					}
				}
				$qry_temp.=')';
				$qry_temp = str_replace(',)',')',$qry_temp);
				
				$qry_temp.=' values (';

				for ($i=0 ; $i < $coln ; $i++) { 
				
					$col_info = $this->GetFieldInfo($intQuery,$i);
					$col_info['name'] = strtolower($col_info['name']);
					
					if(!in_array($col_info['name'],array_change_key_case($exception,CASE_LOWER))){
					
						if(array_key_exists($col_info['name'],$_REQUEST)){
							
							$nfield++;
							if($col_info['type']=='DATE'){
								if($_REQUEST[$col_info['name']]==''){
									$qry_temp.='NULL,';
								}else{
									$qry_temp.=$this->quote_smart($this->formatDate($_REQUEST[$col_info['name']])).',';
								}
							}
							elseif(!in_array($col_info['type'],$this->type_string)){
								$qry_temp.=$this->quote_smart(str_replace(',','',$_REQUEST[$col_info['name']])).',';
							}else{
								$qry_temp.=$this->quote_smart($_REQUEST[$col_info['name']]).',';
							}
						}
						if(array_key_exists(strtolower($col_info['name']),array_change_key_case($other_request,CASE_LOWER))){
							$nother++;
							if($col_info['type']=='DATE'){
								if($other_request[$col_info['name']]==''){
									$qry_temp.='NULL,';
								}else{
									$qry_temp.=$this->quote_smart($this->formatDate($other_request[$col_info['name']])).',';
								}
							}
							elseif(!in_array($col_info['type'],$this->type_string)){
								$qry_temp.=$this->quote_smart(str_replace(',','',$other_request[$col_info['name']])).',';
							}
							else{
								$qry_temp.=$this->quote_smart($other_request[$col_info['name']]).',';
							}
						}
						if($col_info['type']=='TIMESTAMP'){
							$qry_temp.=$this->quote_smart('NOW').','; $nfield++;
						}
						if($col_info['name']=='user_id'){
							$qry_temp.=$this->quote_smart(b_getuserlogin()).','; $nfield++;
						}
					}
				}
							
				$qry_temp.=')';
				$qry_temp = str_replace(',)',')',$qry_temp);
				
				if($intDebug){
					echo $qry_temp."\n";
				}else{
					if($coln-$nexception!=$nfield+$nother){
						echo 'Parameter Query Invalid'."\n";
						echo $coln.'#'.$nexception."\n".$nfield.'#'.$nother."\n";
					}else{						
						//$this->result =@$this->Query($qry_temp,1,0);
						//$this->result = $this->PreQuery($qry_temp,1);
						$this->result = ibase_query($qry_temp);
						if($this->result){
							$this->erro=0;
							$this->erro_msg='Executing Insert Query Success !'; 
						} else {
							$this->erro=ibase_errcode();
							$this->erro_msg=ibase_errmsg();
						}
					}
				}
			}             
		 		 
		} else {
			$this->erro=99;
			$this->erro_msg='Table doesn\'t exist !';  	
		}
		return $this->result;
		$this->FreeQueryResult($this->result);
	}

	//++
	public function FBUpdate($tableName,$other_request,$exception,$str_where,$intDebug=0) {
	
		$nfield=0; $nother=0; $nexception = count($exception);
		
		if($this->isTableExist($tableName)) {
			
			$this->erro=0; $this->erro_msg=''; $this->rows=0;
		 
			if($this->erro==0) {
					   
				$qry_temp="UPDATE $tableName SET ";
				
				$sql = "select FIRST 1 SKIP 1 * from $tableName ";
				$intQuery=$this->Query($sql,0,1);	
				$coln = $this->GetNumFields($intQuery); 
				$this->setFieldTable($intQuery);
							 
				for ($i=0 ; $i < $coln ; $i++) { 
				
					$col_info = $this->GetFieldInfo($intQuery,$i);
					$col_info['name'] = strtolower($col_info['name']);
					
					if(!in_array($col_info['name'],array_change_key_case($exception,CASE_LOWER))){
					
						if(array_key_exists($col_info['name'],$_REQUEST)){
							$nfield++;
							if($col_info['type']=='DATE'){
								if($_REQUEST[$col_info['name']]==''){
									$qry_temp.=$col_info['name'].'=NULL,';
								}else{
									$qry_temp.=$col_info['name'].'='.$this->quote_smart($this->formatDate($_REQUEST[$col_info['name']])).',';
								}
								
							}
							elseif(!in_array($col_info['type'],$this->type_string)){
								$qry_temp.=$col_info['name'].'='.$this->quote_smart(str_replace(',','',$_REQUEST[$col_info['name']])).',';
								
							}else{
								$qry_temp.=$col_info['name'].'='.$this->quote_smart($_REQUEST[$col_info['name']]).',';
								
							}
							
						}
						
						if(array_key_exists(strtolower($col_info['name']),array_change_key_case($other_request,CASE_LOWER))){
							$nother++;
							if($col_info['type']=='DATE'){
								if($other_request[$col_info['name']]==''){
									$qry_temp.=$col_info['name'].'=NULL,';
								}else{
									$qry_temp.=$col_info['name'].'='.$this->quote_smart($this->formatDate($other_request[$col_info['name']])).',';
								}
							}
							elseif(!in_array($col_info['type'],$this->type_string)){
								$qry_temp.=$col_info['name'].'='.$this->quote_smart(str_replace(',','',$other_request[$col_info['name']])).',';
							}
							else{
								$qry_temp.=$col_info['name'].'='.$this->quote_smart($other_request[$col_info['name']]).',';
							}
						}
						if($col_info['type']=='TIMESTAMP'){
							$qry_temp.=$col_info['name'].'='.$this->quote_smart('NOW').','; $nfield++;
						}
						if($col_info['name']=='user_id'){
							$qry_temp.=$col_info['name'].'='.$this->quote_smart(b_getuserlogin()).','; $nfield++;
						}
					}
				}
				$qry_temp.=')';
				$qry_temp = str_replace(',)',' ',$qry_temp);
				$qry_temp.=' '.$str_where.' ';
				
				if($intDebug){
					echo $qry_temp;
						
				}else{
					if($coln-$nexception!=$nfield+$nother){
						echo '$coln: '.$coln."\n";
						echo '$nexception: '.$nexception."\n";
						echo '$nfield: '.$nfield."\n";
						echo '$nother: '.$nother."\n";
						
						echo 'Parameter Query Invalid'."\n";
					}else{	
						//$this->result=@$this->Query($qry_temp,0,1);
						$this->result = ibase_query($qry_temp);
						if($this->result) {
							$this->erro=0;
							$this->erro_msg='Executing Insert Query Success !'; 
						} else {
							$this->erro=ibase_errcode();
							$this->erro_msg=ibase_errmsg();
						}
					}
				}
				
			}             
		 		 
		} else {
			$this->erro=99;
			$this->erro_msg='Table doesn\'t exist !';  	
		}
		return $this->result;
	}	
	
	//if using Prepared Query with or without transaction
	function PreQuery($strSQL, $intTrans=0) {
		if (!$this->intConn) {
			$this->intConn=$this->Connect();
		}
		
		$this->strQuery= $strSQL;
		if ($this->intDebug) {
			echo "Preparing query...		<br>";
		}
		
		if ($intTrans==0) {
			$this->intQuery=ibase_prepare($this->intConn,$this->strQuery);
		} else {
			$this->intQuery=$this->Transaction($this->intConn);
			$this->intQuery=ibase_prepare($this->intTrans,$this->strQuery);
			ibase_execute($this->intQuery);
		}
		
		return $this->intQuery;
	}
	
	function Query($strSQL, $intUseTransaction =0, $intAutoCommit = 1) {
	
		if (!$this->intConn) {
			$this->intConn=$this->Connect();
		}
		
		$this->strQuery= $strSQL;
		if ($this->intDebug) {
			echo "Executing query...		<br>";
		}
		
		if  ($intUseTransaction==0) {
			$this->intQuery=ibase_query($this->intConn,$this->strQuery) or die ("error :".ibase_errmsg()."! <br>");
			$this->intTransStatus++;
		} else {
			$this->intCommit=$intAutoCommit;
			$this->intTrans=$this->Transaction($this->intQuery);
			$this->intQuery= ibase_query($this->intTrans,$this->strQuery); 
			
			if (ibase_errmsg()) {
				$this->intTrans=$this->RollbackTransaction($this->intTrans);
				echo "Error !<br> Query: <br><b>".$this->strQuery."</b><br> cannot be executed! <br>";
				return $this->intQuery;
			}
			
			//if ($this->intCommit==1 && $this->intTransStatus==0) {
			if ($this->intCommit==1) {
				$this->intQuery=$this->CommitTransaction($this->intTrans);
			}	
		}
	
		if ($this->intDebug && $this->intTransStatus>0) {
			echo "Query executed!		<br>";
		}		
		
		return $this->intQuery;
	}

	// if using transaction	
	function Transaction($intQuery) {
		if ($this->intDebug) {
			echo "Use transaction...		<br>";
		}
	
		$this->intTrans=ibase_trans($this->intConn);
		$this->intTranStatus=0;
		
		return $this->intTrans;
	}
	
	//end of transaction
	
	//if Commiting transaction
	function CommitTransaction($intTrans) {
		if ($this->intDebug) {
			echo "Committing transaction...		<br>";
		}
		
		$this->intTrans=ibase_commit($this->intTrans);
		$this->intTransStatus++;
		
		return $this->intQuery;
	}
	
	//Rollback Transaction if there's some errors
	function RollbackTransaction($intTrans) {
		if ($this->intDebug) {
			echo "Rolling back transaction...	 <br>";
		}
	
		$this->intTrans=ibase_rollback($this->intTrans);
		$this->intTransStatus--;
		
		return $this->intQuery;
	}
		
	//Free prepared query
	function FreePreQuery($intPreQuery) {
		$this->intQuery=ibase_free_query($intPreQuery);
		
		if ($this->intDebug) {
			echo "Query prepared free...		<br>";
		}
	}
	
	//Free query result
	function FreeQueryResult($intQuery) {
	$this->intQuery=@ibase_free_result($intQuery);
		
		if ($this->intDebug) {
			echo "Query result free...		<br>";
		}
		
	}
	
	//Closing database connection
	function CloseConnection($intConn) {
		$this->intQuery=ibase_close($intConn);
		
		if ($this->intDebug) {
			echo "Database connection closed!		<br>";
		}
	}

	//++
	private function setFieldTable($intQuery){
		unset($this->name_fields);
		$coln = $this->GetNumFields($intQuery); 
		for ($i=0 ; $i < $coln ; $i++) { 
			$col_info = $this->GetFieldInfo($intQuery,$i);
			$col_info['name'] = strtolower($col_info['name']);
			$this->name_fields[$i]=$col_info['name'];
		}	
	}

	//++
	public function getNColumn(){
		return $this->coln;
	}

	//++
	public function errorMessage(){
		echo $this->erro."\n".
		     $this->erro_msg;
	}
			
	//++
	public function setGenerator($str){
		$intQuery = $this->Query('select gen_id('.$str.',1) as nmax from RDB$DATABASE');
		$this->fetchObject($intQuery);
		return $this->fetchRow('nmax');
	}		
	
	//++
	public function quote_smart($value)
	{
		$value = str_replace('"',"'",trim($value));
	   
		// Stripslashes
		if (get_magic_quotes_gpc()){
		   $value = stripslashes($value);
		}
		// Quote if not integer
		if (!is_numeric($value)) {
			if(strtoupper($value)=='NULL'){
				$value = gcms_escape_string($value);
			}else{
				$value = "'".gcms_escape_string($value)."'";
			}
		}else{
			$value= $value;
		}
		return $value;
	}
	
	//++
	public function formatDate($value){
		if(stripos($value,'-')){
			sscanf($value,'%d-%d-%d',$d,$m,$y);
		}elseif(stripos($value,'/')){
			sscanf($value,'%d/%d/%d',$d,$m,$y);
		}
		return $y.'-'.$m.'-'.$d;
	}
	
    //Getting number of fields
	function GetNumFields($intQuery) {
		if ($this->intDebug) {
			echo "Getting number of fields!		<br>";
		}
		
		$this->intFields=ibase_num_fields($intQuery);
		
		return $this->intFields;
	}
	
	//Getting field info
	function GetFieldInfo($intQuery,$i) {
	if ($this->intDebug) {
			echo "Getting field info!		<br>";
		}
		
		$this->strField=ibase_field_info($intQuery,$i);
		
		return $this->strField;
	}

        //Query with Paging
	function PagingSelect($strTable,$aryField,$strClause=' ',$intMax,$intOffset) {
		if (!$this->intConn) {
			$this->intConn=$this->Connect();
		}		
		
		if ($this->intDebug) {
			echo "Paging query.......		<br>";
		}
		
		//building sql script;
		$this->intMax=$intMax;
		$this->strTable = $strTable;
		$this->aryField = $aryField;
		$this->intOffset=$intOffset;
		$this->strSQL="select first ".$this->intMax." skip ".$this->intOffset." ".$this->aryField. " from ".$this->strTable."  ".$strClause;
		
		//executing sql script;
		$this->intQuery=$this->Query($this->strSQL);
		
		return $this->intQuery;
	}
	
         //get All row until EOF
	function getRow($intQuery) { 
		if ($this->intDebug) { 
			echo "Fetching row.......		<br>"; 
		} 
		 
		$this->aryRecord = @ibase_fetch_row($intQuery);  
		if ($this->intDebug && $this->aryRecord) echo "OK <br>\n\n";  
		elseif ($this->intDebug) echo "EOF <br>\n\n";  
		 
		return $this->aryRecord; 
	}  

	// from class.interbase.php by Vinicius Gallafrio/BR - vinicius@72k.net 
	//----------------------------------------------------------------------------
	function fetchObject($intQuery) {
		if ($this->intDebug) {
			echo "Fetching object.......		<br>";
		}
		
		$this->aryRecord = @ibase_fetch_object($intQuery); 
		if ($this->intDebug && $this->aryRecord) echo "OK <br>\n\n"; 
		elseif ($this->intDebug) echo "EOF <br>\n\n"; 

		return $this->aryRecord;
	}
	
	function fetchRow($fieldname) {
		$fieldname = strtoupper($fieldname); 
		if ($this->aryRecord->$fieldname) { 
			return $this->aryRecord->$fieldname; 
		} else {  
		/** 
		 * look to the field writing in lower 
		 */	 
			$field = strtolower($field); 
			return $this->aryRecord->$fieldname; 
		} 
	
	}
	
	/*
	+ ambil 1 baris dan 1 kolom saja;
	*/
	public function beFetch($sql){
		$sql = str_replace("from"," as xxxxxxxxxx from ",$sql);
		$intQuery = $this->Query($sql);
		$this->fetchObject($intQuery);
		return $this->fetchRow('xxxxxxxxxx');
	}
	
	/*
	+ cek table ada di DB
	*/
	private function isTableExist($tabela) {	
	  
		$sql="select count(*) as jum from RDB\$RELATIONS where lower(RDB\$RELATION_NAME) = '$tabela'";
	  
		//$result = ibase_query($this->conex,$sql); 
		$this->Query($sql);
	  
		//$count = ibase_fetch_object($result); 
		$this->fetchObject($this->intQuery);
		//$count = ibase_fetch_assoc($result);
		
		$tr = $this->fetchRow('jum');
		//$tr    = $count->COBA;
		//$tr    = $count['COBA'];

		if($tr==1) 
			{ return true; }
		else 
			{ return false; }  
	}	

	private function TratarCampo($tipo, $campo) {
		switch ($tipo)  {
			
			case 'STRING': 
				$campo=$this->addslashes_sql(trim($campo));
				return $campo;  
			break;
		 
			case 'INTEGER': 
				$matriz=str_split($campo);
				$erro=0;
				for ($i=0; $i<count($matriz); $i++) {    
					if( (ord($matriz[$i])<48) || (ord($matriz[$i])>57) ) {
						$erro=1;	
					}
				}           
				if($erro==0) { 
					return (int)$campo;   
				} else {
					return null;	
				}        
			break;
		 
			case 'FLOAT':
				// verifica se tem virgula decimal
				if (strpos($campo,",") > 0) {
					// retira o ponto de milhar, se tiver
					$campo = str_replace(".","",$campo);
					// troca a virgula decimal por ponto decimal
					$campo = str_replace(",",".",$campo);           
				} 
				$matriz=str_split($campo);
				$erro=0;
				for ($i=0; $i<count($matriz); $i++) {    
					if( (ord($matriz[$i])<46) || (ord($matriz[$i])>57) ) {
						$erro=1;	
					}
				}           
				if($erro==0) { 
					return (double)$campo;          	
				} else {
					return null;	
				}        
			break;     
		 
			case 'DATE':
				$datatrans=explode("/",$campo);
				if (@checkdate($datatrans[1],$datatrans[0],$datatrans[2])) {
					return $datatrans[0].'.'.$datatrans[1].'.'.$datatrans[2];
				} else {
					return 'NULL';//null;
				}  
			break;     
		  
			case 'TIMESTAMP':
				$datatrans=explode("/",substr($campo,0,9));        
				if (@checkdate($datatrans[1],$datatrans[0],$datatrans[2])) {
					return $datatrans[0].'.'.$datatrans[1].'.'.$datatrans[2].' '.substr($campo,11,8);
				} else {
					return 'NULL';//null;
				}  
			break;          
		  
		}
	}
	
	private function CampoExiste($tabela, $campo) {		
	
		if($this->isTableExist($tabela)) {
		
			$this->tipocampo=null;
			$this->tamcampo=null;
			
			$sql="select distinct ";
			$sql.="A.RDB\$FIELD_NAME as F_NAME, ";
			$sql.="case ";  	 
			$sql.="when B.RDB\$FIELD_PRECISION > 0 then 'FLOAT' ";
			$sql.="when C.RDB\$TYPE_NAME='LONG' then 'INTEGER' ";
			$sql.="when C.RDB\$TYPE_NAME='SHORT' then 'INTEGER' ";
			$sql.="when C.RDB\$TYPE_NAME='VARYING' then 'STRING' ";
			$sql.="when C.RDB\$TYPE_NAME='TEXT' then 'STRING' ";
			$sql.="when C.RDB\$TYPE_NAME='BLOB' then 'STRING' ";
			$sql.="else ";
			$sql.="C.RDB\$TYPE_NAME ";
			$sql.="end as F_TIPO, ";
			$sql.="case ";
			$sql.="when B.RDB\$FIELD_PRECISION > 0 then ";
			$sql.="''||cast(B.RDB\$FIELD_PRECISION as "; 
			$sql.="varchar(2))||','||cast(B.RDB\$FIELD_SCALE*-1 as varchar(2))||''";  	
			$sql.="when B.RDB\$CHARACTER_LENGTH is null then  '0' ";
			$sql.="else ";
			$sql.="B.RDB\$CHARACTER_LENGTH ";
			$sql.="end as F_TAMANHO ";
			$sql.="from ";
			$sql.="RDB\$RELATION_FIELDS A ";
			$sql.="left join RDB\$FIELDS  B on A.RDB\$FIELD_SOURCE=B.RDB\$FIELD_NAME ";
			$sql.="left join RDB\$TYPES C on C.RDB\$FIELD_NAME='RDB\$FIELD_TYPE' and  ";
			$sql.="B.RDB\$FIELD_TYPE=C.RDB\$TYPE ";
			$sql.="left join RDB\$RELATION_CONSTRAINTS E on A.RDB\$RELATION_NAME=E.RDB\$RELATION_NAME ";
			$sql.="left join RDB\$INDEX_SEGMENTS F on E.RDB\$INDEX_NAME=F.RDB\$INDEX_NAME and ";
			$sql.="A.RDB\$FIELD_NAME=F.RDB\$FIELD_NAME ";
			$sql.="where ";
			$sql.="A.RDB\$RELATION_NAME = '$tabela' and A.RDB\$FIELD_NAME = '$campo'";
			
			$res = $this->Query($sql);
			
			/*$this->fetchObject($this->intQuery);
			//$row=@$this->fetchObject($this->intQuery);
			
			if(!empty($row->F_TIPO)) {
			//if(!empty($this->fetchRow('f_tipo'))) {
				$this->tipocampo = $this->fetchRow('f_tipo');
				$this->tamcampo = $this->fetchRow('f_tamanho');
			   //$this->tipocampo=trim($row->F_TIPO);
			   //$this->tamcampo=$row->F_TAMANHO;
			}
			$this->FreeQueryResult($this->intQuery);
			//ibase_free_result($res);     */
			
			$row=@ibase_fetch_object($res);
			
			if(!empty($row->F_TIPO)) {    	
			   $this->tipocampo=trim($row->F_TIPO);
			   $this->tamcampo=$row->F_TAMANHO;
			}
			
			ibase_free_result($res);			
		} 
	}	
	
	//-------------------------------------------------------------------------------
}

?>