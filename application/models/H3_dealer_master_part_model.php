<?php

class h3_dealer_master_part_model extends Honda_Model {

    protected $table = 'ms_h3_dealer_master_part';

    public function __construct(){
        $this->load->model('m_admin');    
    }

    public function insert($data){
        $data['id_dealer'] = $this->m_admin->cari_dealer();
        return parent::insert($data);
    }

    public function get_dealer_master_part($id_part, $id_dealer){
        $data = $this->db   
        ->select('dmp.min_stok')
        ->select('dmp.maks_stok')
        ->select('dmp.safety_stock')
        ->select('dmp.min_sales')
        ->from('ms_h3_dealer_master_part as dmp')
        ->where('dmp.id_dealer', $id_dealer)
        ->where('dmp.id_part', $id_part)
        ->limit(1)
        ->get()->row_array();

        return [
            'min_stok' => $data != null ? $data['min_stok'] : null,
            'maks_stok' => $data != null ? $data['maks_stok'] : null,
            'safety_stock' => $data != null ? $data['safety_stock'] : null,
            'min_sales' => $data != null ? $data['min_sales'] : null,
        ];
    }
}