<?php

class Set_id_booking_po_dealer extends Honda_Controller {

    public function index()
    {
        $this->db->trans_start();
        $this->db
        ->select('po.id')
        ->select('rd.id as id_booking_int')
        ->from('tr_h3_dealer_purchase_order as po')
        ->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = po.id_booking')
        ->where('po.id_booking IS NOT NULL', null, false)
        ->where('po.sinkron', 0);

        foreach ($this->db->get()->result_array() as $row) {
            $this->db
            ->set('id_booking_int', $row['id_booking_int'])
            ->set('sinkron', 1)
            ->where('id', $row['id'])
            ->update('tr_h3_dealer_purchase_order');
        }

        $this->db->trans_complete();

        if($this->db->trans_status()){
            echo 'Berhasil';
        }else{
            echo 'Gagal';
        }
    }
}