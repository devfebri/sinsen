<?php

class H3_md_pencatatan_poin_sales_campaign_model extends Honda_Model{

    protected $table = 'tr_h3_md_pencatatan_poin_sales_campaign';

    public function insert($data){
        $data['created_by'] = $this->session->userdata('id_user');
        $data['created_at'] = date('Y-m-d H:i:s', time());

        parent::insert($data);
    }
}
