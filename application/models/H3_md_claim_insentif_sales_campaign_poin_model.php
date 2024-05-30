<?php

class H3_md_claim_insentif_sales_campaign_poin_model extends Honda_Model{

    protected $table = 'tr_h3_md_claim_insentif_sales_campaign_poin';

    public function insert($data){
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');
		
        parent::insert($data);
	}
}
