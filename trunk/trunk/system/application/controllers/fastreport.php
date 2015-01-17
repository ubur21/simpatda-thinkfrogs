<?php
class Fastreport extends Controller {
	var $thisdir;
	
	function Fastreport()
	{
		parent::Controller();
		$this->thisdir = set_realpath('./assets/fr');
	}

	function index()
	{

	}
	
	function start()
	{
		$arr = explode('&',$_REQUEST['opt']);
		
		$data = 'ReportName='. $_REQUEST['fr3'].".fr3\n";
		$data .= 'OutputType='. $_REQUEST['format']."\n";			
		$data .= 'DriverName='. $this->db->dbdriver."\n";
		$data .= 'DBServer='.$this->db->hostname."\n";
		$data .= 'DBName='.$this->db->database."\n";
		$data .= 'DBUser='. $this->db->username."\n";
		$data .= 'DBPassword='. $this->db->password."\n";			

		foreach($arr as $value){
			$data.=$value."\n";
		}

		$in = tempnam($this->thisdir."cache", "in");
		write_file($in, $data);
		$out = basename( $in, '.tmp');
		
		exec($this->thisdir.'RepEngine.exe "'.$in.'" "'.$out.'"');

		if (file_exists($this->thisdir."/output/".$out.".ok")) {
/*
			$outfile = $this->thisdir."/output/".$out.".".$_REQUEST["format"];
			header('Content-Disposition: ' . ($_REQUEST["att"] ? 'attachment; ' : '') . 'filename="'.$_REQUEST["name"].".".$_REQUEST["format"].'"');
			header('Content-Type: '.get_mime_by_extension($outfile));
			header('Content-Length: '.filesize($outfile));
			readfile($outfile);
			//$fp = fopen($outfile, 'rb');
			//fpassthru($fp);
*/
			echo $out;
		}
		else {
			echo 'report creation error';
		}
	}
	
	function view($tmp = '', $file = '', $type = 'pdf', $att=0)
	{
		$filename = $tmp. "." .$type;
		if (file_exists($this->thisdir."/output/".$filename)) {
			$outfile = $file. "." .$type;
			header('Content-Disposition: ' . ($att == 1 ? 'attachment; ' : '') .'filename="'.$outfile.'"');
			header('Content-Type: '.get_mime_by_extension($this->thisdir."/output/".$filename));
			header('Content-Length: '.filesize($this->thisdir."/output/".$filename));
			header('Cache-Control: no-store');
			readfile($this->thisdir."/output/".$filename);
		}
	}
}
?>