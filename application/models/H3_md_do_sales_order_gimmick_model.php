<?php

class H3_md_do_sales_order_gimmick_model extends Honda_Model{

	protected $table = 'tr_h3_md_do_sales_order_gimmick';

	public function get_gimmick_do($id_do_sales_order, $langsung = false){
		$this->db	
		->select('doc.*')
		->select('sc.kode_campaign')
		->select('sc.nama')
		->select('sc.reward_gimmick')
		->select('so.id_sales_order')
		->select('so.status as status_so')
		->from("{$this->table} as doc")
		->join('ms_h3_md_sales_campaign as sc', 'sc.id = doc.id_campaign')
		->join('tr_h3_md_sales_order as so', '(so.gimmick = 1 and so.status != "Canceled" and so.id_campaign = doc.id_campaign and so.id_item = doc.id_item and so.no_do_sumber_gimmick = doc.id_do_sales_order)', 'left')
		->where('doc.id_do_sales_order', $id_do_sales_order)
		;

		if($langsung){
			$this->db->where('sc.reward_gimmick', 'Langsung');
		}

		return $this->db->get()->result_array();
	}
	
}
