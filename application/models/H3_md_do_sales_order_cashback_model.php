<?php

class H3_md_do_sales_order_cashback_model extends Honda_Model{

	protected $table = 'tr_h3_md_do_sales_order_cashback';

	public function get_cashback_do($id_do_sales_order, $langsung = false){
		$this->db	
		->select('doc.*')
		->select('sc.kode_campaign')
		->select('sc.nama')
		->select('sc.reward_cashback')
		->from("{$this->table} as doc")
		->join('ms_h3_md_sales_campaign as sc', 'sc.id = doc.id_campaign')
		->where('doc.id_do_sales_order', $id_do_sales_order)
		;

		if($langsung){
			$this->db->where('sc.reward_cashback', 'Langsung');
		}else{
			$this->db->where('sc.reward_cashback', 'Tidak Langsung');
		}

		return $this->db->get()->result_array();
	}
	
}
