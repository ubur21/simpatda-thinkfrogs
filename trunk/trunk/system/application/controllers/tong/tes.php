<?php
class Tes extends Controller {
	function _index()
	{
		echo "hello world";
		?>
		<a href="<?php echo site_url('tes/kategori'); ?>">kategori</a><?php
	}
	function _kategori()
	{
		echo "kategori";
	}
	function _remap($method) {
		if($method=='kategori') {
			$this->_kategori();
		}
		else {
			$this->_index();
		}
	}
}
?>