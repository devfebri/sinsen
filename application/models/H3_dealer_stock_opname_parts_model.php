<?php

class h3_dealer_stock_opname_parts_model extends Honda_Model
{
    protected $table = 'tr_h3_dealer_stock_opname_parts';

    public function selisih($condition){
        return $this->db->select('*')->where($condition)->where('stock!=stock_aktual')->get($this->table)->result();
    }
}
