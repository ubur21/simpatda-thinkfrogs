<?php
Class Berita {
	public $daftar;
	public $content;
	public $count;
	function __construct() {}
	function set_daftar($daftar) {
		if(is_array($daftar)) {
			$this->daftar=$daftar;
			$this->count=count($this->daftar);
			return true;
		}
		return false;
	}
	function set_content($content) {
		if(is_array($content)) {
			$this->content=$content;
			$this->count=count($this->content);
			return true;
		}
		return false;
	}
	function get_daftar() {
		for($i=0;$i<($this->count);$i++) 
		{
			echo '<li><a class="link" id="'.$this->daftar[$i]['BERITA_ID'].'">'.$this->daftar[$i]['JUDUL'].'</a></li>';
		}
	}
	function get_content() {
		echo '<h1>'.$this->content['JUDUL'].'</h1>';
		echo '<p>'.$this->content['DESKRIPSI'].'</p>';
	}
}
?>