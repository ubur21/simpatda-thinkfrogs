<?php
	include "config.php";
/* yadl_spaceid - Skip Stamping */
	header('Content-type: text/plain');
	$search_queries = initArray();
	$query = html_entity_decode(urldecode($_GET['query']));
	$results = search($search_queries, $query);
	sendResults($query,$results);
    
function search($search_queries, $query) {
	if (strlen($query) == 0)
		return;

	$query = strtolower($query);

	$firstChar = $query[0];

	if (!preg_match('/[0-9a-z$]/',$firstChar,$matches))
		return;

	$charQueries = $search_queries[$firstChar];

	$results = array();	
	for($i = 0; $i < count($charQueries); $i++) {
		if (strcasecmp(substr($charQueries[$i],0,strlen($query)),$query) == 0)
			$results[] = $charQueries[$i];
	}

	return $results;
}

function sendResults($query,$results) {
	for ($i = 0; $i < count($results); $i++)
		print "$results[$i]\n";
}

function initArray() {
    $sql = "SELECT * FROM product WHERE spbu_id = 6 ";
    $qry = mysql_query($sql); 
	//$row = list(mysql_fetch_array($qry));
	$numrows = mysql_num_rows($qry);
	while( $row = mysql_fetch_assoc($qry)){
			$arrSelected = $row[0] ;
	}
	for ($i = 0; $i < $numrows ; $i++){
		print "$varValue[0]PO1222\n";			
	}	
	return array(
		'p' => array(
						$varValue,
			),
	);
	
}
?>
