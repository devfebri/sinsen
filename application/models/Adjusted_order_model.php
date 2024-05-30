<?php

class adjusted_order_model extends Honda_Model {

    protected $table = 'ms_h3_adjusted_order';

    public function __construct(){
        $this->load->model('m_admin');    
    }

    public function insert($data){
        $data['id_dealer'] = $this->m_admin->cari_dealer();

        return parent::insert($data);
    }
}

?>