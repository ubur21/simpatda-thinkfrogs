<?php

require_once "fastreport_config.php";

if ($_REQUEST['action'] == "start") {
  $thisdir = dirname(__FILE__);
  $in = tempnam($thisdir."/".FASTREPORT_INPUT_DIR, "in");
  $handle = fopen($in, "w");
  fwrite($handle, "ReportName=".FASTREPORT_FR3_DIR."/".$_REQUEST['fr3']."\n");
  fwrite($handle, "OutputType=".$_REQUEST['format']."\n");
  fwrite($handle, "DBDriver=".DRIVER."\n");
  fwrite($handle, "DBServer=".SERVER."\n");
  fwrite($handle, "DBUser=".USER."\n");
  fwrite($handle, "DBPassword=".PASSWORD."\n");
  fwrite($handle, "DBName=".DATABASE."\n");
  fwrite($handle, $_REQUEST['param']."\n");
  fclose($handle);
  $log = tempnam($thisdir."/".FASTREPORT_OUTPUT_DIR, "log");
  $out = tempnam($thisdir."/".FASTREPORT_OUTPUT_DIR, "out");
  unlink($out);
  $out = substr($out, 0, strrpos($out, '.'));
  exec($thisdir.'/RepEngine.bat "'.$thisdir.'" "'.$in.'" "'.$out.'" "'.$log.'"');
  $out = basename($out);
  $_SESSION["name_".$out] = $_REQUEST['name'];
  $_SESSION["format_".$out] = $_REQUEST['format'];
  $_SESSION["attachment_".$out] = $_REQUEST['attachment'];
  echo $out;
}

else if ($_REQUEST['action'] == "check") {
  if (file_exists(dirname(__FILE__)."/".FASTREPORT_OUTPUT_DIR."/".$_REQUEST['out'].".ok")) 
	  echo "document.location = 'request.php?mod=fastreport&action=view&out=".$_REQUEST['out']."';";
	else
	  echo "_fastReportCheck('".$_REQUEST['out']."');";
}

else if ($_REQUEST['action'] == "view") {
  $out = $_REQUEST['out'];
  $outfile = dirname(__FILE__)."/".FASTREPORT_OUTPUT_DIR."/".$out.".".$_SESSION["format_".$out];
  header('Content-Disposition: ' . ($_SESSION["attachment_".$out] ? 'attachment; ' : '') . 'filename="'.$_SESSION["name_".$out].".".$_SESSION["format_".$out].'"');
  header('Content-Type: '.mime_content_type($outfile));
  header('Content-Length: '.filesize($outfile));
  unset($_SESSION["name_".$out]);
  unset($_SESSION["format_".$out]);
  unset($_SESSION["attachment_".$out]);
  $fp = fopen($outfile, 'rb');
  fpassthru($fp);
}

?>