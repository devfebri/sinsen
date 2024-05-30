<?php

class kelompok_part_model extends Honda_Model {

    protected $table = 'ms_kelompok_part';

    public function insert($data){
        $data['created_by'] = $this->session->userdata('id_user');
        $data['created_at'] = date('Y-m-d H:i:s', time());

        parent::insert($data);
    }

    public function update($data, $condition){
        $data['updated_by'] = $this->session->userdata('id_user');
        $data['updated_at'] = date('Y-m-d H:i:s', time());

        parent::update($data, $condition);
    }
    
}