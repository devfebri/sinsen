<?php

class h3_dealer_record_reasons_and_parts_demand_model extends Honda_Model
{
    protected $table = 'tr_h3_dealer_record_reasons_and_parts_demand';

    public function __construct(){
        $this->load->model('m_admin');
    }

    public function insert($data){
        $data['id_dealer'] = $this->m_admin->cari_dealer();

        parent::insert($data);
    }
}
