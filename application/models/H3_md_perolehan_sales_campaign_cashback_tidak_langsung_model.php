<?php

class H3_md_perolehan_sales_campaign_cashback_tidak_langsung_model extends Honda_Model{

    protected $table = 'tr_h3_perolehan_sales_campaign_cashback_tidak_langsung';

    public function insert($data){
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $this->session->userdata('id_user');

        parent::insert($data);
    }

}
