<?php

class Set_id_booking_so_dealer extends Honda_Controller {

    public function index()
    {
        $this->db->trans_start();
        $this->db
        ->select('so.id')
        ->select('rd.id as booking_id_reference_int')
        ->from('tr_h3_dealer_sales_order as so')
        ->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = so.booking_id_reference')
        ->where('so.booking_id_reference IS NOT NULL', null, false);

        foreach ($this->db->get()->result_array() as $row) {
            $this->db
            ->set('booking_id_reference_int', $row['booking_id_reference_int'])
            ->where('id', $row['id'])
            ->update('tr_h3_dealer_sales_order');
        }

        $this->db->trans_complete();

        if($this->db->trans_status()){
            echo 'Berhasil';
        }else{
            echo 'Gagal';
        }
    }
}