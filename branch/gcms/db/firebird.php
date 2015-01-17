<?php

include 'metafire.lib.php';

function gcms_pconnect($server, $user, $password, $database) {
    global $fbdb;
    $fbdb = ibase_pconnect("$server:$database", $user, $password, '', 0, 3);
    return $fbdb;
}

function gcms_trans() {
    global $fbdb, $gcms_trans_id;
    if (!$gcms_trans_id) $gcms_trans_id = ibase_trans(IBASE_COMMITTED, $fbdb);
}

function gcms_commit() {
    global $gcms_trans_id;
    if ($gcms_trans_id) ibase_commit($gcms_trans_id);
    $gcms_trans_id = false;
}

function gcms_blob_echo($nresult) {
  return ibase_blob_echo($nresult);
}

function gcms_rollback() {
    global $gcms_trans_id;
    if ($gcms_trans_id) ibase_rollback($gcms_trans_id);
    $gcms_trans_id = false;
}

function gcms_query($query) {  
    global $fbdb, $gcms_query_result, $gcms_trans_id;
    $xid = $gcms_trans_id;
    if (!$xid) gcms_trans();
    $gcms_query_result = ibase_query ($fbdb, $query);
    if (!$xid) {
        if ($gcms_query_result) gcms_commit();
        else gcms_rollback();
    }
    if (!$gcms_query_result) {
        print "<br>".$query."<br><br>";
    }
    return $gcms_query_result;
}

function gcms_fetch_row($nresult) {
    return ibase_fetch_row($nresult);
}

function gcms_fetch_assoc($nresult) {
    return ibase_fetch_assoc($nresult);
}
function gcms_fetch_object($nresult) {
    $result = ibase_fetch_object($nresult);
    if ($result) {
        $coln = ibase_num_fields($nresult);
        for ($i = 0; $i < $coln; $i ++) {
            $col_info = ibase_field_info($nresult, $i); 
            eval("\$result->".strtolower($col_info['alias'])." = \$result->".$col_info['alias'].";");
        }  
    }
    return $result;
}

function gcms_now() {
    return "'NOW'";
}

function gcms_list_tables($database) {
    return gcms_query("select rdb\$relation_name from rdb\$relations where rdb\$view_blr is null and (rdb\$system_flag is null or rdb\$system_flag = 0)");
    return false;
}

function gcms_insert_id($table) {
    $res = gcms_query("select gen_id(g_" .$table."_nid, 0) as NID from rdb\$database");
    $obj = gcms_fetch_object($res);
    return $obj->NID;
}

function gcms_run_query_block($csql) {
    $term = ";";
    $pos = true;
    while ($pos !== false) {
        $pos = strpos($csql, $term);
        if ($pos !== false) {
            $sql = trim(substr($csql, 0, $pos));
            $csql = substr($csql, $pos + strlen($term));
            if (eregi("(set[ ]*term)[ ]*(.*)", $sql, $t)) $term = trim($t[2]);
            else gcms_query($sql);
        }
    }
}

function gcms_escape_string($str) {
    return str_replace("'", "''", $str);
}

function gcms_is_empty($result) {
    return ($gcms_query_result === true);
}

function gcms_free_result($result) {
    return ibase_free_result($result);
}

function gcms_error() {
    return ibase_errmsg();
}
?>