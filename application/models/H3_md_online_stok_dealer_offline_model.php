<?php

class H3_md_online_stok_dealer_offline_model extends Honda_Model{

    protected $table = 'tr_h3_md_online_stok_dealer_offline';

    public function insert($data){
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');
        parent::insert($data);
    }

    public function update($data, $condition){
		$data['updated_at'] = date('Y-m-d H:i:s', time());
		$data['updated_by'] = $this->session->userdata('id_user');
        parent::update($data, $condition);
    }

    public function get_qty_onhand($id_part, $id_dealer, $sql = false){
        $data = $this->db
        ->select('s.stok_onhand')
        ->from('tr_h3_md_online_stok_dealer_offline as s')
        ->where('s.id_part', $id_part)
        ->where('s.id_dealer', $id_dealer)
        ->get()->row_array()
        ;

        return $data != null ? $data['stok_onhand'] : 0;
    }
}
