<?php

class h3_dealer_lokasi_rak_bin_model extends Honda_Model
{
    protected $table = 'ms_lokasi_rak_bin';

    public function rakWarehousePadaDealer(){
        return $this->db->where("id_gudang =", $this->input->get('id_gudang'))
            ->like('id_rak', $this->input->get('query'))
            ->get($this->table)
            ->result();
    }
}
