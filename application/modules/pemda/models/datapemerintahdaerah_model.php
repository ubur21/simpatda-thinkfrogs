<?php
class Datapemerintahdaerah_model extends CI_Model {
	
	var $data;
	
	function __construct() 
	{
        // Call the Model constructor
        parent::__construct();
		$this->_table='PEMDA';
		$this->_pk='NAMA_PEMDA';
	}
	
	function fill_data() 
	{
		$NAMA_PEMDA = $this->input->post('NAMA_PEMDA'); $NAMA_PEMDA = $NAMA_PEMDA ? $NAMA_PEMDA : '';
		$LOKASI = $this->input->post('LOKASI'); $LOKASI = $LOKASI ? $LOKASI : '';
		
		return array(
			'NAMA_PEMDA' => $NAMA_PEMDA,
			'LOKASI' => $LOKASI
		);
	}

	function get_all_data()	
	{
		$this->db->trans_start();
		$this->db->select('*');
		$this->db->from('PEMDA');
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
		$this->db->trans_complete();
	}

	function update_data() {	
		$data = $this->fill_data();

		$this->db->trans_start();
		$update = $this->db->update('PEMDA', $data);
		$this->db->trans_complete();

		return $update;
	}
	
	/* LOGO */
	function get_logo()
	{
		$this->db->select('*');
		$rows = $this->db->get();
		return $rows->result_array();
	}
	
	function delete_logo()
	{
		$this->db->trans_start();
		$this->db->where_in($this->_pk, $NAMA_PEMDA);
		$update =$this->db->update('PEMDA', array('LOGO' => NULL) );		
		$this->db->trans_complete();
		return $update;	
	}
	
	function save_logo()
	{
		$dbh = $this->db->conn_id;
		$fileName1 = $_FILES['image']['name'];
		$filename = './uploads/tmp/'.$fileName1;
		
		$fd = fopen($filename, 'r');
		if ($fd) {

			$blob = ibase_blob_import($dbh, $fd);
			fclose($fd);

			if (!is_string($blob)) {
				// import failed
				echo "Gagal Import File";
			} else {
				$query = "UPDATE PEMDA SET LOGO = ?";
				$prepared = ibase_prepare($dbh, $query);
				if (!ibase_execute($prepared, $blob)) {
					// record update failed
					echo "Gagal Simpan Logo";
				}
			}
		} else {
			// unable to open the data file
			echo "Tidak dapat membuka data";
		}
	}
}
?>