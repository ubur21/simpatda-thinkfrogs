<?

function gcms_pconnect($server, $user, $password, $database) {
  $result = mysql_pconnect($server, $user, $password);
  if ($result) mysql_select_db($database);
  return $result;
}

function gcms_trans() {
}

function gcms_commit() {
}

function gcms_rollback() {
}

function gcms_query($query) {
  $result = mysql_query ($link_identifier);
  return $result;
}

function gcms_fetch_row($nresult) {
  return mysql_fetch_row($nresult)
}

function gcms_fetch_object($nresult) {
  return mysql_fetch_object($nresult);
}

function gcms_now() {
  return "now()";
}

function gcms_list_tables($database) {
  return mysql_list_tables($database);
}

function gcms_insert_id($table) {
  return mysql_insert_id();
}

function gcms_run_query_block($csql) {
  // split the sql into separate queries 
	$clinessql = explode(';',  $csql); 
	$ncount = count($clinessql); 
	for($ii=0; ($ii<$ncount-1); $ii++  ) { 
	  if(!gcms_query($clinessql[$ii])) { 
      echo "Error on line $ii of Query:<br>"; 
      echo $clinessql[$ii]."<br><Br>"; 
      die; 
    } 
  } 
}

function gcms_escape_string($str) {
  return mysql_real_escape_string($str);
}

function gcms_is_empty($result) {
  return (mysql_num_rows($result) == 0);
}

function gcms_free_result($result) {
  return mysql_free_result($result);
}

function gcms_error() {
  return mysql_error();
}

?>