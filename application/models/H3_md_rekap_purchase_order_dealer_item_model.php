<?php

class H3_md_rekap_purchase_order_dealer_item_model extends Honda_Model{

    protected $table = 'tr_h3_md_rekap_purchase_order_dealer_item';

	public function update($data, $condition){
		$data['updated_at'] = date('Y-m-d H:i:s', time());
		$data['updated_by'] = $this->session->userdata('id_user');

        parent::update($data, $condition);
    }
}