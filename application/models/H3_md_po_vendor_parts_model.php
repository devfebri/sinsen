<?php

class h3_md_po_vendor_parts_model extends Honda_Model{

    protected $table = 'tr_h3_md_po_vendor_parts';

    public function get_parts_for_penerimaan_manual($id_po_vendor){
        $parts = $this->db
        ->select('pvp.id_part')
        ->select('p.nama_part')
        ->select('pvp.qty_order as qty_terima')
        ->select('p.harga_md_dealer as harga')
        ->select('0 as tanpa_po_vendor')
        ->from("{$this->table} as pvp")
        ->join('ms_part as p', 'p.id_part = pvp.id_part')
        ->where('pvp.id_po_vendor', $id_po_vendor)
        ->get()->result_array();

        return $parts;
    }

}
