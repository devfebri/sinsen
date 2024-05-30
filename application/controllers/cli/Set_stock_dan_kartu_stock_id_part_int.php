<?php


class Set_stock_dan_kartu_stock_id_part_int extends Honda_Controller {


    public function index()
    {
        $this->stock();
        $this->kartu_stock();
    }

    public function stock(){
        $this->db->trans_start();
        $this->db
        ->select('sp.id_stok_part')
        ->select('p.id_part_int')
        ->from('tr_stok_part as sp')
        ->join('ms_part as p', 'p.id_part = sp.id_part')
        ->where('sp.id_part_int IS NULL', null, false);

        foreach ($this->db->get()->result_array() as $row) {
            $this->db
            ->set('id_part_int', $row['id_part_int'])
            ->where('id_stok_part', $row['id_stok_part'])
            ->update('tr_stok_part');
        }
        $this->db->trans_complete();
    }

    public function kartu_stock(){
        $this->db->trans_start();
        $this->db
        ->select('ks.id')
        ->select('p.id_part_int')
        ->from('tr_h3_md_kartu_stock as ks')
        ->join('ms_part as p', 'p.id_part = ks.id_part')
        ->where('ks.id_part_int IS NULL', null, false);

        foreach ($this->db->get()->result_array() as $row) {
            $this->db
            ->set('id_part_int', $row['id_part_int'])
            ->where('id', $row['id'])
            ->update('tr_h3_md_kartu_stock');
        }
        $this->db->trans_complete();
    }
}