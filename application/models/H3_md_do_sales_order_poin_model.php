<?php

class H3_md_do_sales_order_poin_model extends Honda_Model{

	public function get_poin_do($id_do_sales_order){
		$this->db
		->select('ppsc.id')
		->select('ppsc.id_campaign')
		->select('sc.kode_campaign')
		->select('sc.nama')
		->select('ppsc.poin')
		->select('ppsc.nilai_insentif')
		->from('tr_h3_md_pencatatan_poin_sales_campaign as ppsc')
		->join('ms_h3_md_sales_campaign as sc', 'sc.id = ppsc.id_campaign')
		->where('ppsc.id_transaksi', $id_do_sales_order)
		;

		return $this->db->get()->result_array();
	}
	
}
