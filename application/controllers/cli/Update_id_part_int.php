<?php

class Update_id_part_int extends Honda_Controller {

    public function index(){
        $this->update_id_part_int_sales_order();
        $this->update_id_part_int_purchase_order();
    }

    private function update_id_part_int_sales_order(){
        $parts = $this->db
        ->select('sop.nomor_so')
        ->select('sop.id_part')
        ->select('p.id_part_int')
        ->from('tr_h3_dealer_sales_order_parts as sop')
        ->join('ms_part as p', 'p.id_part = sop.id_part')
        ->where('sop.id_part_int', null)
        ->limit(5000)
        ->get()->result_array();

        foreach ($parts as $part) {
            $this->db
            ->set('sop.id_part_int', $part['id_part_int'])
            ->where('sop.id_part', $part['id_part'])
            ->where('sop.nomor_so', $part['nomor_so'])
            ->update('tr_h3_dealer_sales_order_parts as sop');
        }
        echo "Update SO Berhasil <br>";
    }

    public function update_id_part_int_purchase_order(){
        $parts = $this->db
        ->select('pop.po_id')
        ->select('pop.id_part')
        ->select('p.id_part_int')
        ->from('tr_h3_dealer_purchase_order_parts as pop')
        ->join('ms_part as p', 'p.id_part = pop.id_part')
        ->where('pop.id_part_int', null)
        ->get()->result_array();

        foreach ($parts as $part) {
            $this->db
            ->set('pop.id_part_int', $part['id_part_int'])
            ->where('pop.id_part', $part['id_part'])
            ->where('pop.po_id', $part['po_id'])
            ->update('tr_h3_dealer_purchase_order_parts as pop');
        }
        echo "Update PO Berhasil <br>";
    }

    public function query_check_so(){
        $parts = $this->db
        ->select('count(sop.id_part) as count', false)
        ->from('tr_h3_dealer_sales_order_parts as sop')
        ->where('sop.nomor_so = so.nomor_so', null, false)
        ->get_compiled_select();

        echo $this->db
        ->select('so.nomor_so')
        ->select('date_format(so.tanggal_so, "%d-%m-%Y") as tanggal_so')
        ->select('d.kode_dealer_md as kode_dealer')
        ->select('d.nama_dealer')
        ->from('tr_h3_dealer_sales_order as so')
        ->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
        ->where("({$parts}) = 0", null, false)
        ->order_by('so.tanggal_so', 'desc')
        ->get_compiled_select();
        die;
    }
}