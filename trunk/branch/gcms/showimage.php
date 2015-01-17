<?
include('config.php');
$sql = "SELECT * FROM INFO_PEMDA";
$res = gcms_query($sql);
$rs= gcms_fetch_object($res);
$datapicture = gcms_blob_echo($rs->logo);
?>