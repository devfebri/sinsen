<?php defined('BASEPATH') OR exit('No direct script access allowed');

class H2_laporan_tarikan_data_nms_model extends CI_Model{
	public function getDataTarikanDealer(){
		$query=$this->db->query("SELECT kode_dealer_md FROM ms_dealer");
		return $query->result();
	}
}
